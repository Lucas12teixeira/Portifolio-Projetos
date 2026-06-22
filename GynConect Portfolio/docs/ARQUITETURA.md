# Arquitetura — GynConect

## Visão Geral

GynConect é construído como uma **SPA (Single-Page Application)** no frontend comunicando com uma **REST API PHP** no backend. A aplicação funciona como **PWA** com suporte offline via Service Worker.

---

## Diagrama de Alto Nível

```
┌─────────────────────────────────────────────────────┐
│                    CLIENTE (Browser)                 │
│                                                      │
│  ┌──────────────┐    ┌───────────────────────────┐  │
│  │ Service      │    │  SPA (HTML5 + JS Vanilla) │  │
│  │ Worker       │◄──►│  - Módulos IIFE           │  │
│  │ (Offline)    │    │  - Chart.js (local)       │  │
│  └──────────────┘    │  - Dark theme CSS         │  │
│                      └───────────┬───────────────┘  │
└──────────────────────────────────┼──────────────────┘
                                   │ HTTPS
                                   │ JSON
                    ┌──────────────▼───────────────┐
                    │        REST API (PHP)         │
                    │                              │
                    │  ┌──────────────────────┐   │
                    │  │  Auth Middleware      │   │
                    │  │  (Token Validation)  │   │
                    │  └──────────┬───────────┘   │
                    │             │               │
                    │  ┌──────────▼───────────┐   │
                    │  │  RBAC Check          │   │
                    │  │  (4 níveis)          │   │
                    │  └──────────┬───────────┘   │
                    │             │               │
                    │  ┌──────────▼───────────┐   │
                    │  │  Business Logic       │   │
                    │  │  (Módulos)           │   │
                    │  └──────────┬───────────┘   │
                    └─────────────┼───────────────┘
                                  │ PDO
                    ┌─────────────▼───────────────┐
                    │         MySQL               │
                    │   (Schema Normalizado)      │
                    └─────────────────────────────┘
```

---

## Frontend

### SPA Architecture

A aplicação é uma Single-Page Application que carrega uma única vez e gerencia toda a navegação via JavaScript.

```
Frontend/
├── index.html          ← Entry point único
├── css/
│   └── style.css       ← Tema dark, variáveis CSS, responsivo
├── js/
│   ├── chart.min.js    ← Chart.js local (sem CDN)
│   ├── auth.js         ← Módulo de autenticação
│   ├── dashboard.js    ← Dashboard e KPIs
│   ├── clientes.js     ← Gestão de clientes
│   ├── vendas.js       ← Módulo de vendas
│   ├── consignacao.js  ← Controle de consignação
│   ├── estoque.js      ← Produtos e estoque
│   ├── comissoes.js    ← Comissões
│   ├── financeiro.js   ← Boletos e financeiro
│   ├── despesas.js     ← Despesas operacionais
│   ├── devolucoes.js   ← Devoluções
│   ├── introducoes.js  ← Introduções
│   ├── relatorios.js   ← Exportações
│   └── dev.js          ← Painel administrativo
├── sw.js               ← Service Worker
└── manifest.json       ← PWA manifest
```

### Padrão de Módulos (IIFE / Module Revealing)

Cada módulo expõe apenas a interface pública necessária:

```javascript
const GCVendas = (() => {
  // Estado privado
  let currentPage = 1;
  let filters = {};

  // Funções privadas
  function _buildRequest(data) { ... }
  function _renderTable(vendas) { ... }

  // Interface pública
  return {
    init,
    listar,
    criar,
    confirmar,
    exportarPDF
  };
})();
```

### Service Worker — Estratégias de Cache

```
Requisições de API    → Network First (dados sempre atualizados)
Assets estáticos      → Cache First (performance)
Imagens               → Stale While Revalidate
Offline fallback      → Cache estático servido quando sem rede
```

---

## Backend

### REST API — Organização de Endpoints

```
api/
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── recuperar-senha.php
├── clientes/
│   ├── listar.php
│   ├── criar.php
│   ├── editar.php
│   └── encerrar.php
├── vendas/
│   ├── listar.php
│   ├── criar.php
│   ├── confirmar.php
│   ├── entregar.php
│   └── exportar.php
├── consignacao/
│   ├── listar.php
│   ├── movimentar.php
│   └── termo.php
├── estoque/
│   ├── listar.php
│   ├── movimentar.php
│   └── alertas.php
├── comissoes/
│   ├── listar.php
│   └── pagar.php
├── financeiro/
│   ├── boletos/
│   └── despesas/
├── devolucoes/
│   ├── registrar.php
│   ├── aprovar.php
│   └── resolver.php
└── dev/
    ├── usuarios.php
    └── sessoes.php
```

### Fluxo de uma Requisição Autenticada

```
1. Cliente envia: POST /api/vendas/criar.php
   Headers: Authorization: Bearer <token>
   Body: { cliente_id, itens[], pagamento[] }

2. auth_middleware.php
   └── Valida token → obtém usuário e nível de acesso

3. rbac_check.php
   └── Verifica se o nível pode criar vendas
   └── Vendedor: apenas para seus clientes
   └── Gerente: qualquer cliente da equipe

4. business logic
   └── Calcula comissão automaticamente
   └── Atualiza estoque
   └── Registra auditoria

5. Resposta JSON
   └── 200 OK + dados da venda criada
   └── 401 Unauthorized / 403 Forbidden / 422 Validation Error
```

---

## Banco de Dados

### Domínios Principais

```
Usuários & Acesso
├── usuarios (id, nome, email, senha_hash, nivel, ativo)
└── sessoes (id, usuario_id, token, expires_at, ip)

Clientes
└── clientes (id, nome, cpf_cnpj, contato, status, vendedor_id)

Catálogo
├── produtos (id, nome, sku, ean, preco, categoria_id)
└── categorias (id, nome)

Operacional
├── vendas (id, cliente_id, vendedor_id, status, total, assinatura)
├── venda_itens (id, venda_id, produto_id, qtd, preco, desconto)
├── consignacao (id, cliente_id, produto_id, qtd_atual, qtd_vendida)
└── movimentacoes_estoque (id, produto_id, tipo, qtd, referencia, usuario_id)

Financeiro
├── comissoes (id, venda_id, vendedor_id, valor, status)
├── boletos (id, cliente_id, valor, vencimento, status)
└── despesas (id, categoria, valor, descricao, data)

Devoluções
└── devolucoes (id, venda_id, produto_id, qtd, motivo, status, resolucao)
```

### Princípios de Design do Schema

- **Normalização**: Sem duplicação de dados — relações por FK
- **Auditoria**: Campos `created_at`, `updated_at` em todas as tabelas principais
- **Soft delete**: Registros desativados (status/ativo) em vez de deletados
- **Integridade**: Foreign keys com ON DELETE RESTRICT onde aplicável

---

## PWA — Progressive Web App

### Manifest

```json
{
  "name": "GYN CONECT",
  "short_name": "GynConect",
  "display": "standalone",
  "orientation": "portrait",
  "theme_color": "#060c14",
  "background_color": "#060c14",
  "start_url": "/",
  "icons": [...]
}
```

### Capacidades Offline

| Funcionalidade | Offline |
|----------------|---------|
| Visualizar vendas em cache | ✅ |
| Ver clientes em cache | ✅ |
| Criar nova venda | ❌ (requer sync) |
| Dashboard com dados cached | ✅ |
| Exportar PDF/Excel | ❌ (server-side) |

---

## Controle de Versão da Aplicação

```javascript
// version.json
{ "version": "2.1.4", "build": "20260101" }

// Service Worker verifica versão no fetch
// Se diferente → invalida cache e recarrega assets
// Garante que todos os usuários usam a versão mais recente
```

---

<div align="center">

[← README](../README.md) • [API →](API.md)

*GynConect — Documentação de portfólio apenas*

</div>
