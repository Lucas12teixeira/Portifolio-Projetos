# Arquitetura — GynConect

## Visão Geral

GynConect é construído como uma **SPA (Single-Page Application)** no frontend comunicando com uma **REST API PHP** no backend. A aplicação funciona como **PWA** com suporte offline real via Service Worker + IndexedDB. A partir da linha 5.x, o sistema também opera de forma autônoma (cobrança recorrente, webhooks, cron) e se integra por API ao ERP interno (ErlDev Gestão).

---

## Diagrama de Alto Nível

```
┌─────────────────────────────────────────────────────────────┐
│                      CLIENTE (Browser)                       │
│                                                             │
│  ┌──────────────┐   ┌────────────────────────────────────┐  │
│  │ Service      │   │  SPA (HTML5 + JS Vanilla)          │  │
│  │ Worker       │◄─►│  - Módulos IIFE                    │  │
│  │ (offline +   │   │  - Chart.js + Leaflet (locais)     │  │
│  │  auto-update)│   │  - WebAuthn (login biométrico)     │  │
│  └──────┬───────┘   └───────────────┬────────────────────┘  │
│         │                           │                        │
│  ┌──────▼───────────────────────────▼─────────────────────┐ │
│  │ IndexedDB                                              │ │
│  │  - api_cache  (respostas GET com TTL)                  │ │
│  │  - outbox     (POST/PUT/DELETE pendentes)              │ │
│  │  Sync Manager → drena o outbox em FIFO ao reconectar   │ │
│  └───────────────────────────────────────────────────────┘ │
└────────────────────────────────┬────────────────────────────┘
                                 │ HTTPS / JSON / Bearer token
                  ┌──────────────▼───────────────┐
                  │        REST API (PHP)         │
                  │  ┌────────────────────────┐  │
                  │  │ Auth (token) + RBAC    │  │
                  │  │ + permissões finas     │  │
                  │  └───────────┬────────────┘  │
                  │  ┌───────────▼────────────┐  │
                  │  │ Business Logic         │  │
                  │  │ (~30 domínios)         │  │
                  │  └───────────┬────────────┘  │
                  │  ┌───────────▼────────────┐  │
                  │  │ Cobrança: GatewayRouter│  │
                  │  │  → Asaas / Mercado Pago│  │
                  │  └───────────┬────────────┘  │
                  └──────────────┼───────────────┘
                                 │ PDO
                  ┌──────────────▼───────────────┐
                  │            MySQL              │
                  │     (schema normalizado)     │
                  └──────────────────────────────┘

  Entradas externas:
   • Webhooks de pagamento (Asaas, Mercado Pago) → /api/cobranca/webhook_*.php
   • GitHub Actions (cron diário) → /api/cobranca/cron_inadimplencia.php
   • ErlDev Gestão (S2S) → /api/integracoes/erldev_gestao*.php
```

---

## Frontend

### Arquitetura SPA

A aplicação é uma Single-Page Application que carrega uma única vez (`index.html`) e gerencia toda a navegação via um roteador JavaScript (`js/app.js`), que também aplica as regras de RBAC no cliente antes de montar cada página.

```
Frontend/
├── index.html          ← Entry point único
├── css/
│   ├── style.css       ← Tema dark, variáveis CSS, responsivo
│   └── vendor/leaflet/ ← Leaflet local (sem CDN)
├── js/
│   ├── chart.min.js    ← Chart.js local
│   ├── vendor/leaflet.js
│   ├── api.js          ← Wrapper de fetch + cache/outbox offline
│   ├── offline-db.js   ← IndexedDB (api_cache + outbox)
│   ├── sync-manager.js ← Drena o outbox em FIFO, remapeia IDs temporários
│   ├── pwa-installer.js / pwa-update.js
│   ├── auth.js         ← Autenticação + verificação de assinatura
│   ├── webauthn.js     ← Cerimônias WebAuthn (registro / login)
│   ├── dashboard.js · clientes.js · vendas.js · consignacao.js
│   ├── estoque.js · produtos.js · comissoes.js · lucroprodutos.js
│   ├── despesas.js · devolucoes.js · vendedores.js
│   ├── atendimento.js  ← Lista + mapa Leaflet
│   ├── financeiro.js   ← DRE, KPIs, boletos
│   ├── cobranca.js     ← Assinatura + faturas
│   ├── configuracoes.js · admin.js
├── sw.php / sw.js      ← Service Worker (servido com no-cache)
└── manifest.json       ← PWA manifest
```

### Padrão de Módulos (IIFE / Module Revealing)

Cada módulo expõe apenas a interface pública necessária:

```javascript
const GCVendas = (() => {
  let currentPage = 1;
  let filters = {};

  function _buildRequest(data) { /* ... */ }
  function _renderTable(vendas) { /* ... */ }

  return { render, renderNova, listar, criar, confirmar, exportarPDF };
})();
```

### Camada offline (IndexedDB + Sync Manager)

```
GET  → tenta rede; em falha, serve de api_cache (com TTL). Sucesso atualiza o cache.
POST/PUT/DELETE offline → gravado no outbox com um ID temporário negativo (-1, -2, ...)
                          e a UI é atualizada de forma otimista.

Ao reconectar, o Sync Manager:
  1. Lê o outbox em ordem (FIFO)
  2. Reenvia cada requisição; até 5 tentativas por item
  3. Mapeia o ID temporário → ID real retornado pelo servidor
  4. Substitui referências pendentes (ex.: itens de uma venda ainda não sincronizada)

Prefixos nunca enfileirados: /api/auth/, /api/admin/, /api/exportar/, /api/utils/
```

### Service Worker — estratégias

```
Requisições de API    → Network First (dados sempre atualizados; cache é fallback)
Assets estáticos      → Cache First (performance)
Imagens               → Stale While Revalidate
Atualização           → novo SW recebe SKIP_WAITING e assume na próxima navegação
```

---

## Backend

### REST API — organização de endpoints

```
api/
├── auth/            login, logout, recuperar-senha, webauthn (registro/login)
├── clientes/        listar, criar, editar, encerrar, histórico
├── vendas/          listar, criar, confirmar, entregar, exportar
├── consignacao/     listar, movimentar, termo
├── fechamentos/     acerto de consignação (fatura só o que foi vendido)
├── visitas/         visita unificada (acerto + reposição)
├── estoque/         listar, movimentar, alertas
├── produtos/        catálogo, categorias
├── comissoes/       listar, pagar
├── lucro/           lucro padrão por produto
├── introducoes/     registrar, pagar
├── despesas/        registrar, listar
├── devolucoes/      registrar, aprovar, resolver
├── financeiro/      boletos + KPIs (DRE)
├── atendimento/     clientes sem visita há 30+ dias (lista + mapa)
├── dashboard/       KPIs do período
├── cobranca/        status, faturas, gerar cobrança, webhooks, cron
│   ├── GatewayInterface.php · GatewayRouter.php
│   ├── AsaasGateway.php · MercadoPagoGateway.php
│   ├── CobrancaService.php · NotificacaoService.php
│   ├── webhook_asaas.php · webhook_mercadopago.php
│   └── cron_inadimplencia.php
├── integracoes/     erldev_gestao.php (leitura) · erldev_gestao_controle.php (ações)
├── configuracoes/   feature flags (ex.: vendas_bloqueadas, texto do termo)
├── exportar/        PDF / Excel / romaneio / termo assinado
└── admin/           usuários, sessões, forçar atualização, manutenção, migrações
```

### Fluxo de uma requisição autenticada

```
1. Cliente envia: POST /api/vendas/criar
   Headers: Authorization: Bearer <token>
   Body: { cliente_id, itens[], pagamento[] }

2. Auth middleware  → valida token → obtém usuário, nível e permissões
3. RBAC + permissão → vendedor: só para seus clientes / gerente: qualquer cliente da equipe
4. Assinatura       → se vencida e usuário comum → 403 (modo cobrança)
5. Business logic   → calcula comissão, atualiza estoque, registra auditoria
6. Resposta JSON    → 201 + dados da venda | 401 | 403 | 422
```

### Cobrança — interface + roteador de gateway

```
GatewayInterface   → criarCobranca(), consultarStatus(), parsearWebhook()
      ▲
      ├── AsaasGateway
      └── MercadoPagoGateway

GatewayRouter      → escolhe a implementação conforme a configuração da assinatura
CobrancaService    → orquestra: gera fatura, chama o gateway, concilia pendentes
webhook_*.php      → registra em webhook_logs (idempotente) e marca a fatura como paga
cron_inadimplencia → varre faturas vencidas, aplica tolerância, notifica e suspende
```

---

## Banco de Dados

### Domínios principais

```
Usuários & Acesso
├── vendedores (id, nome, email, senha_hash, nivel, ativo, responsavel_cobranca)
├── sessoes (id, vendedor_id, token, expires_at, ip)
├── webauthn_credentials (id, vendedor_id, credential_id, public_key, sign_counter)
└── webauthn_challenges (challenge temporário por cerimônia)

Clientes & Catálogo
├── clientes (id, nome, cpf_cnpj, contato, status, finalizado, consumidor_final, vendedor_id, ultima_visita)
├── produtos (id, nome, sku, ean, preco, lucro_padrao, categoria_id)
└── categorias (id, nome)

Operacional
├── vendas / venda_itens (assinatura, tipo comum|consignado, status)
├── consignacao (cliente_id, produto_id, qtd_atual, qtd_vendida, qtd_devolvida)
├── visitas_consignacao (numero VIS-AAAA-NNNN, acerto + reposição, saldos)
└── movimentacoes_estoque (produto_id, tipo, qtd, referencia, usuario_id)

Financeiro
├── comissoes (venda_id, vendedor_id, valor, status)
├── financeiro_boletos (cliente_id, valor, data_vencimento, status, parcelas)
└── despesas (categoria, valor, descricao, data)

Cobrança (SaaS)
├── assinatura (registro único: valor_mensalidade, dia_vencimento, tolerancia_dias, status)
├── faturas (assinatura_id, valor, vencimento, status, gateway, id_externo, pix/boleto)
├── webhook_logs (gateway, id_externo, evento, payload, processado)  — idempotência
├── push_subscriptions (vendedor_id, endpoint, p256dh, auth_key)
└── notificacoes_cobranca (fatura_id, tipo, canal email|push, destinatario_id)

Devoluções
└── devolucoes (venda_id, produto_id, qtd, motivo, status, resolucao)

Config
└── configuracoes (chave, valor, descricao)  — feature flags
```

### Princípios de design do schema

- **Normalização**: sem duplicação de dados — relações por FK
- **Auditoria**: campos `criado_em` / `atualizado_em` nas tabelas principais e trilha em movimentações
- **Soft delete**: registros desativados (status/ativo/finalizado) em vez de deletados
- **Idempotência**: chaves únicas em `faturas (gateway, id_externo)`, `webhook_logs` e `notificacoes_cobranca`
- **Migrações versionadas**: `database/migracao_v*.sql` de v1.x a v5.6

---

## PWA — Progressive Web App

### Capacidades offline

| Funcionalidade | Offline |
|----------------|---------|
| Visualizar vendas / clientes / dashboard em cache | ✅ |
| Criar e editar venda | ✅ (entra no outbox e sincroniza depois) |
| Cadastrar cliente | ✅ (fila `pending_clientes` + remapeamento de ID) |
| Login por senha ou biometria | ❌ (requer rede) |
| Exportar PDF/Excel | ❌ (server-side) |
| Cobrança / webhooks | ❌ (server-side) |

### Controle de versão da aplicação

```
version.json → { "version": "5.x", "buildDate": "...", "changelog": [...] }

O Service Worker e o app checam a versão periodicamente (a cada ~10 min).
Versão diferente → invalida o cache, aplica o novo SW e recarrega os assets.
O Painel Dev pode forçar a atualização em todos os clientes.
```

---

## Automação

```
GitHub Actions (.github/workflows/cobranca-cron.yml)
  schedule: '0 11 * * *'  (08:00 America/Sao_Paulo)
  step: curl https://<dominio>/api/cobranca/cron_inadimplencia.php?token=$CRON_TOKEN
```

Sem servidor de cron dedicado — o agendador do GitHub dispara o endpoint protegido por token uma vez por dia. A verificação também roda de forma oportunista quando o responsável pela cobrança consulta o status (a cada ~10 min), funcionando como um segundo gatilho.

---

<div align="center">

[← README](../README.md) • [API →](API.md)

*GynConect — Documentação de portfólio apenas*

</div>
