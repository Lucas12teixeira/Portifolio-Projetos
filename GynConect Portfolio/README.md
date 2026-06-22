# GynConect — Sistema de Gestão Comercial

<div align="center">

![GynConect Logo](assets/icons/icon.png)

**Sistema Web de Gestão Comercial para Equipes de Vendas Externas**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Status](https://img.shields.io/badge/Status-Produção-success?style=for-the-badge)](.)

[📋 Funcionalidades](#-funcionalidades) • [🛠 Stack](#-stack-técnico) • [🏗 Arquitetura](#-arquitetura) • [🔒 Segurança](#-segurança) • [📊 Resumo](#-resumo-do-projeto)

---

## 🌍 Idioma / Language

<table>
<tr>
<td align="center">
<b>🇧🇷 Português</b><br>
<sub>Você está aqui</sub>
</td>
<td align="center">
<a href="README-EN.md">
<b>🇺🇸 English</b>
</a><br>
<sub>English version</sub>
</td>
</tr>
</table>

</div>

---

## 🎯 Visão Geral

**GynConect** é um sistema web de gestão comercial desenvolvido sob medida para equipes de vendas externas que operam com modelo de **consignação**. A plataforma centraliza o controle de clientes, produtos, estoque, vendas, comissões e finanças — tudo acessível em campo via PWA com suporte offline.

### O Problema

Distribuidores com equipes de campo enfrentam desafios críticos:
- Controle disperso de estoque por vendedor
- Dificuldade em rastrear itens em consignação
- Falta de visibilidade gerencial em tempo real
- Processo manual e sujeito a erros para cálculo de comissões
- Ausência de assinatura digital nos termos de consignação

### A Solução

GynConect integra todos esses controles em uma única plataforma web, combinando **mobilidade de campo** (PWA, offline) com **visibilidade gerencial** (dashboards, relatórios, exportações).

---

## 📋 Funcionalidades

### 🔐 Autenticação e Controle de Acesso

- Login por e-mail e senha com sessões persistentes via token
- **Quatro níveis de acesso** com permissões distintas:
  - **Vendedor** — acesso restrito aos próprios clientes, vendas e comissões
  - **Gerente** — visibilidade total sobre equipe e relatórios
  - **Financeiro** — acesso limitado aos módulos financeiros
  - **Dev** — acesso completo incluindo painel administrativo
- Primeiro acesso com troca obrigatória de senha
- Recuperação de senha por e-mail

---

### 📊 Dashboard

- KPIs financeiros em tempo real: faturamento bruto, líquido, comissões e repasse
- Filtros de período: hoje, 7 dias, mês atual ou intervalo personalizado
- Ranking de vendedores por desempenho
- Resumo de consignação: valor total em aberto e clientes ativos
- Controle de pagamentos pendentes e despesas do mês
- Exportação em PDF

---

### 👥 Clientes

- Cadastro completo com dados pessoais, contato e endereço
- Segmentação por status: ativo, inativo e encerrado
- Filtros de inatividade: sem visita há +30, +60 ou +90 dias
- Controle de elegibilidade para consignação
- Rastreamento de data da última visita
- Links diretos para WhatsApp
- Encerramento com registro de motivo e histórico
- Reativação de clientes inativos
- **Consumidor Final** — registro fixo para vendas diretas sem cadastro individual

---

### 🛒 Vendas

- Criação de pedidos com múltiplos itens e busca de produtos
- Suporte a duas formas de pagamento por venda
- Desconto por item ou por pedido
- Cálculo automático de comissão por vendedor
- Diferenciação entre venda comum e venda consignada
- **Assinatura digital** do cliente no pedido
- Rascunho automático com retenção de 24 horas
- Fluxo de status: Pendente → Confirmada → Entregue
- Filtros por data, status, vendedor, forma de pagamento e cliente
- Exportação em PDF e Excel

---

### 📦 Consignação

- Controle de itens consignados por cliente e por produto
- Rastreamento de quantidade atual, vendida e devolvida
- Valor em aberto calculado por preço unitário × quantidade
- Ranking dos clientes com maior consignação em aberto
- **Termos com assinatura digital**, versionamento e histórico
- Movimentações com trilha de auditoria completa
- Reconciliação de estoque × consignação

---

### 🗃 Produtos e Estoque

- Catálogo com SKU e EAN (validação 8–14 dígitos)
- Estoque central (depósito) e individual por vendedor
- Estoque em consignação rastreado separadamente
- Alertas visuais de estoque mínimo
- Categorias de produtos
- Histórico de movimentações
- Valor total do estoque calculado automaticamente

---

### 💰 Comissões

- Cálculo automático por venda com percentual configurável por vendedor
- Status: pendente, pago, cancelado
- Pagamento controlado pelo gerente
- Filtro por período com totalizadores

---

### 🤝 Introduções

- Registro de clientes introduzidos por vendedores
- Bônus por introdução controlado pelo gerente
- Filtro por período e por vendedor

---

### 🏦 Financeiro — Boletos

- Controle de boletos com status: aberto, vencido, pago, cancelado
- Marcação automática de vencidos
- KPIs: total em aberto, vencidos, recebimentos do mês, vencimentos em 7 dias
- Registro de pagamento com data e método

---

### 💸 Despesas

- Registro de despesas operacionais com categorias
- Visível apenas para gerentes
- Integrado ao dashboard para cálculo de lucro líquido

---

### 🔄 Devoluções

- Registro com quantidade e motivo
- Tipos: devolução ou troca
- Fluxo de aprovação pelo gerente
- Quarentena de produtos com resolução: devolver ao estoque, descartar ou enviar para conserto
- Impacto automático no estoque ao resolver

---

### 📄 Relatórios e Exportações

- PDF de vendas com filtros de data, vendedor e forma de pagamento
- Excel de vendas com totalizadores
- PDF do dashboard e romaneio de entrega
- Confirmação de pedido e termo de consignação assinado
- Controle de acesso por token nas exportações

---

### 🛠 Painel Dev

- KPIs gerais do sistema (clientes, vendas, faturamento, estoque, pendências)
- Gestão de usuários: criar, editar, ativar/desativar, resetar senha
- Controle de sessões ativas: visualizar e encerrar individualmente ou em massa
- Feed de atividade recente
- Utilitários de banco de dados e migrações

---

## 🛠 Stack Técnico

<div align="center">

### Frontend
![JavaScript](https://img.shields.io/badge/JavaScript_Vanilla-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![PWA](https://img.shields.io/badge/PWA-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)

### Backend
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![REST API](https://img.shields.io/badge/REST_API-009688?style=for-the-badge&logo=fastapi&logoColor=white)

</div>

| Camada | Tecnologia |
|--------|-----------|
| **Frontend** | SPA em HTML5 + JavaScript Vanilla (sem frameworks) |
| **Backend** | PHP com PDO/MySQL, arquitetura REST API |
| **Mobile** | PWA com Service Worker e suporte offline |
| **Banco de Dados** | MySQL com queries parametrizadas |
| **Exportações** | PDF e Excel gerados nativamente em PHP |
| **Autenticação** | Token-based, stateless, longa duração |

---

## 🏗 Arquitetura

```
GynConect
├── Frontend (SPA)
│   ├── HTML5 + CSS3 (dark theme, design responsivo)
│   ├── JavaScript Vanilla — padrão IIFE/Module Revealing
│   ├── Service Worker (cache offline, background sync)
│   └── Chart.js (local, sem CDN)
│
├── Backend (REST API)
│   ├── PHP + PDO (queries parametrizadas)
│   ├── Endpoints organizados por módulo
│   ├── Autenticação por token de sessão
│   └── Exportação nativa de PDF e Excel
│
└── Banco de Dados
    ├── MySQL (schema normalizado)
    └── Controle de versão via migrações
```

### Decisões de Arquitetura

**Por que JavaScript Vanilla?**
- Zero dependências de terceiros no core
- Carregamento mais rápido e controle total
- Demonstra domínio da linguagem

**Por que PWA?**
- Uso offline em campo — essencial para vendedores externos
- Experiência app-like sem passar pela app store
- Compatível com qualquer dispositivo

**Por que PHP + PDO?**
- Compatibilidade universal de hospedagem
- PDO garante queries parametrizadas em toda a API
- Exportações nativas sem biblioteca externa

---

## 📊 Resumo do Projeto

| Aspecto | Detalhes |
|---------|---------|
| **Tipo** | Sistema de Gestão Comercial (SaaS-like) |
| **Stack** | PHP, MySQL, JavaScript Vanilla, PWA |
| **Arquitetura** | REST API + SPA |
| **Módulos** | 15+ módulos integrados |
| **Segurança** | Token-based auth, queries parametrizadas, RBAC |
| **Status** | Produção |
| **Modelo de Negócio** | Distribuidores com equipe externa + consignação |

---

## 🔒 Segurança

- **Autenticação**: Tokens de sessão stateless com longa duração
- **Banco de Dados**: Queries parametrizadas (PDO) — sem SQL injection
- **Controle de Acesso**: RBAC com 4 níveis — sem permissões cruzadas
- **Sessões**: Controle administrativo com encerramento individual ou em massa
- **Exportações**: Protegidas por token de acesso
- **Senhas**: Troca obrigatória no primeiro acesso + recuperação por e-mail

> Para detalhes completos: [SECURITY.md](SECURITY.md)

---

## 🎨 Destaques Técnicos

- **SPA sem framework** — zero dependência de bibliotecas JS de terceiros no core
- **PWA com Service Worker** — uso offline em campo
- **Autenticação stateless** com tokens de sessão de longa duração
- **Queries parametrizadas** em toda a API (PDO)
- **Exportação de PDF e Excel nativos** em PHP, sem bibliotecas externas
- **Padrão IIFE/Module Revealing** para organização do JS
- **Controle de versão** da aplicação com invalidação automática de cache
- **Trilha de auditoria** completa em movimentações de consignação

---

## 📁 Estrutura da Documentação

```
GynConect Portfolio/
├── README.md              ← Você está aqui
├── README-EN.md           ← Versão em inglês
├── PROJECT_SUMMARY.md     ← Resumo executivo (1 página)
├── SECURITY.md            ← Política de segurança
├── CHANGELOG.md           ← Histórico de versões
├── docs/
│   ├── ARQUITETURA.md     ← Detalhes de arquitetura
│   └── API.md             ← Documentação da API
└── assets/
    └── icons/             ← Ícone do sistema
```

---

## 👨‍💻 Sobre o Desenvolvedor

**Lucas (Erl Dev)**
Full-Stack Developer | Especialista em PWA | Soluções Comerciais Web

- 🌐 Portfolio: [erldev.com.br](https://erldev.com.br)
- 💼 LinkedIn: [linkedin.com/in/seuperfil](https://linkedin.com/in/seuperfil)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

## 📜 Licença e Direitos

© 2025-2026 Lucas (Erl Dev). Todos os direitos reservados.

> Este repositório contém apenas **documentação de portfólio**. O código-fonte é proprietário e não está incluído. Sistema desenvolvido sob encomenda — todos os direitos reservados ao desenvolvedor e ao cliente.

---

<div align="center">

[📋 Ver Resumo Executivo](PROJECT_SUMMARY.md) • [🏗 Arquitetura](docs/ARQUITETURA.md) • [🔌 API](docs/API.md) • [🔒 Segurança](SECURITY.md)

**Desenvolvido com ❤️ e ☕ por [Lucas (Erl Dev)](https://erldev.com.br)**

</div>
