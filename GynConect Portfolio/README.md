# GynConect — Sistema de Gestão Comercial

<div align="center">

![GynConect Logo](assets/icons/icon.png)

**Sistema Web de Gestão Comercial para Equipes de Vendas Externas**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Status](https://img.shields.io/badge/Status-Produção_v5.x-success?style=for-the-badge)](.)

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

**GynConect** é um sistema web de gestão comercial desenvolvido sob medida para equipes de vendas externas que operam com modelo de **consignação**. A plataforma centraliza o controle de clientes, produtos, estoque, vendas, comissões e finanças — tudo acessível em campo via PWA com suporte offline real (IndexedDB + fila de sincronização).

O sistema está em **produção ativa** e evoluiu de forma contínua ao longo da linha de versão **5.x**: hoje inclui login por biometria, cobrança recorrente com gateways de pagamento (Pix/boleto), notificações Web Push, mapa de atendimento com geolocalização e integração servidor-a-servidor com o ERP interno (ErlDev Gestão).

### O Problema

Distribuidores com equipes de campo enfrentam desafios críticos:
- Controle disperso de estoque por vendedor
- Dificuldade em rastrear itens em consignação
- Falta de visibilidade gerencial em tempo real
- Processo manual e sujeito a erros para cálculo de comissões
- Ausência de assinatura digital nos termos de consignação
- Priorização subjetiva de quais clientes visitar

### A Solução

GynConect integra todos esses controles em uma única plataforma web, combinando **mobilidade de campo** (PWA, offline, biometria) com **visibilidade gerencial** (dashboards, DRE, relatórios, exportações) e **operação autônoma** (cobrança automática, webhooks, cron agendado).

---

## 📋 Funcionalidades

### 🔐 Autenticação e Controle de Acesso

- Login por e-mail e senha com sessões persistentes via token
- **Login biométrico (WebAuthn)** — Face ID, Touch ID, digital Android e Windows Hello, com credencial por dispositivo e contador anti-clonagem
- **Quatro níveis de acesso** com permissões distintas:
  - **Vendedor** — acesso restrito aos próprios clientes, vendas e comissões
  - **Gerente** — visibilidade total sobre equipe e relatórios
  - **Financeiro** — acesso limitado aos módulos financeiros
  - **Dev** — acesso completo incluindo painel administrativo
- **Permissões finas** por chave (ex.: `fin_boletos_ver`) além do nível
- Flag de **responsável pela cobrança** — habilita o módulo de cobrança para um usuário específico
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

### 📋 Atendimento

- Lista contínua (infinite scroll) de clientes **sem visita há 30+ dias**, para priorizar o acompanhamento
- Filtros rápidos por faixa de inatividade (30 / 60 / 90 dias)
- **Visão em mapa (Leaflet)** com geolocalização do vendedor e marcadores dos clientes
- Buckets de criticidade por cor (ok, alerta, crítico, nunca visitado)
- Vendedor vê apenas os próprios clientes; gerente e dev veem os clientes de gerentes

---

### 👥 Clientes

- Cadastro completo com dados pessoais, contato e endereço
- Segmentação por status: ativo, inativo, encerrado e finalizado
- Filtros de inatividade: sem visita há +30, +60 ou +90 dias
- Controle de elegibilidade para consignação
- Rastreamento de data da última visita
- Links diretos para WhatsApp
- Encerramento com registro de motivo e histórico
- Reativação de clientes inativos
- **Histórico de vendas por cliente** com geração de PDF e compartilhamento
- **Consumidor Final** — registro fixo do sistema para vendas diretas sem cadastro individual

---

### 🛒 Vendas

- Criação de pedidos com múltiplos itens e busca de produtos
- Suporte a duas formas de pagamento por venda
- Desconto por item ou por pedido
- Cálculo automático de comissão por vendedor
- Diferenciação entre venda comum e venda consignada
- **Assinatura digital** do cliente no pedido (dispensada para Consumidor Final)
- Rascunho automático com retenção de 24 horas
- Fluxo de status: Pendente → Confirmada → Entregue
- Filtros por data, status, vendedor, forma de pagamento e cliente
- Exportação em PDF e Excel com todos os filtros aplicados
- Criação e edição funcionam **offline** — a venda entra na fila e sincroniza ao voltar a conexão

---

### 📦 Consignação

- Controle de itens consignados por cliente e por produto
- Rastreamento de quantidade atual, vendida e devolvida
- Valor em aberto calculado por preço unitário × quantidade
- Ranking dos clientes com maior consignação em aberto
- **Termos com assinatura digital**, versionamento e histórico
- **Visitas unificadas** — acerto (venda) + reposição numa mesma visita, com saldo anterior e novo saldo, numeração `VIS-AAAA-NNNN`
- Acerto/fechamento que fatura apenas as unidades vendidas e devolve o restante ao estoque do vendedor
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
- Usuários com nível `dev` não geram comissão nem custo para a empresa

---

### 📈 Lucro Padrão (por Produto)

- Configuração do lucro padrão (R$ por peça) de cada produto — vale para todos os clientes
- No acerto/romaneio o valor vem travado como padrão; o vendedor pode destravar e digitar um valor manual só para aquela venda
- Visível apenas a gerente e dev

---

### 🤝 Introduções

- Registro de clientes introduzidos por vendedores
- Bônus por introdução controlado pelo gerente
- Status: pendente, pago, cancelado
- Filtro por período e por vendedor

---

### 🏦 Financeiro

- Painel redesenhado no formato de **DRE simplificado**, com 6 KPIs e filtros rápidos
- Controle de boletos/cobranças com status: aberto, vencido, pago, cancelado
- Marcação automática de vencidos
- **Boleto parcelado** com múltiplas parcelas
- KPIs: total em aberto, vencidos, recebimentos do mês, vencimentos em 7 dias
- Registro de pagamento com data e método
- Acesso condicionado à permissão `fin_boletos_ver`

---

### 💳 Cobrança e Assinatura (modelo SaaS)

O próprio GynConect gerencia a cobrança recorrente do cliente que o utiliza:

- Registro único de **assinatura** com valor de mensalidade, dia de vencimento e dias de tolerância
- **Faturas mensais** com Pix, boleto ou cartão
- **Gateways de pagamento integrados** — Asaas e Mercado Pago, atrás de uma interface comum com roteador de gateway
- **Webhooks** de confirmação de pagamento (Asaas e Mercado Pago) com log e idempotência por evento
- **Reconciliação automática** — consulta o gateway quando o webhook não chega
- **Cron de inadimplência** diário (GitHub Actions) que verifica faturas vencidas
- **Bloqueio por inadimplência** — quando a assinatura fica vencida, usuários comuns caem numa tela de aviso e só o responsável pela cobrança navega, restrito ao módulo de cobrança
- Módulo visível apenas ao responsável pela cobrança e ao dev

---

### 🔔 Notificações Web Push

- Assinaturas de push por dispositivo/navegador (PushManager + VAPID)
- Web Push nativo (RFC 8291) para avisos de cobrança
- Um mesmo evento é entregue ao **dev e ao responsável pela cobrança**, cada um com seu registro de envio

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

### 🔗 Integração com o ErlDev Gestão

- Endpoint **somente-leitura** protegido por token que expõe o estado atual de `assinatura` e `faturas` para o ERP interno sincronizar o cliente automaticamente
- Endpoint de **controle remoto** que aceita ações (`suspender`, `reativar`, `marcar_pago`, `config`, `cancelar_fatura`, `deletar_fatura`)
- Comunicação servidor-a-servidor com `Authorization: Bearer`, sem sessão/cookie

---

### 📄 Relatórios e Exportações

- PDF de vendas com filtros de data, vendedor e forma de pagamento
- Excel de vendas com totalizadores
- PDF do dashboard e romaneio de entrega
- Confirmação de pedido e termo de consignação assinado
- Histórico de vendas por cliente em PDF
- Controle de acesso por token nas exportações

---

### 🛠 Painel Dev

- KPIs gerais do sistema (clientes, vendas, faturamento, estoque, pendências)
- Gestão de usuários: criar, editar, ativar/desativar, resetar senha, permissões finas
- Controle de sessões ativas: visualizar e encerrar individualmente ou em massa
- Feed de atividade recente com busca
- Forçar atualização de versão em todos os clientes e modo manutenção
- Utilitários de banco de dados, migrações e migração de assinaturas

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
| **Mobile** | PWA com Service Worker, auto-update e suporte offline |
| **Offline** | IndexedDB (cache de GET + outbox de escrita) com Sync Manager FIFO |
| **Banco de Dados** | MySQL com queries parametrizadas e migrações versionadas |
| **Biometria** | WebAuthn (`web-auth/webauthn-lib`) — Face ID / Touch ID / digital |
| **Pagamentos** | Gateways Asaas e Mercado Pago (Pix, boleto, cartão) com webhooks |
| **Notificações** | Web Push nativo (VAPID / RFC 8291) |
| **Mapas** | Leaflet (local, sem CDN) |
| **Exportações** | PDF e Excel gerados nativamente em PHP |
| **Autenticação** | Token-based, stateless, longa duração |
| **Automação** | Cron diário via GitHub Actions (verificação de inadimplência) |

---

## 🏗 Arquitetura

```
GynConect
├── Frontend (SPA)
│   ├── HTML5 + CSS3 (dark theme, design responsivo)
│   ├── JavaScript Vanilla — padrão IIFE/Module Revealing
│   ├── Service Worker (cache offline, auto-update)
│   ├── IndexedDB — api_cache (GET com TTL) + outbox (escritas pendentes)
│   ├── Sync Manager — processa o outbox em FIFO, remapeia IDs temporários
│   ├── WebAuthn — login biométrico por dispositivo
│   ├── Chart.js + Leaflet (locais, sem CDN)
│
├── Backend (REST API)
│   ├── PHP + PDO (queries parametrizadas)
│   ├── Endpoints organizados por módulo (~30 domínios)
│   ├── Autenticação por token de sessão + RBAC + permissões finas
│   ├── Cobrança — interface de gateway + roteador (Asaas / Mercado Pago)
│   ├── Webhooks com log e idempotência
│   ├── Integração servidor-a-servidor com o ErlDev Gestão
│   └── Exportação nativa de PDF e Excel
│
├── Banco de Dados
│   ├── MySQL (schema normalizado)
│   └── Controle de versão via migrações (v1.x → v5.6)
│
└── Automação
    └── GitHub Actions — cron diário de inadimplência
```

### Decisões de Arquitetura

**Por que JavaScript Vanilla?**
- Zero dependências de terceiros no core
- Carregamento mais rápido e controle total
- Demonstra domínio da linguagem

**Por que PWA com offline real?**
- Uso em campo com conexão instável — vendedor registra a venda e sincroniza depois
- Fila de escrita (outbox) no IndexedDB com remapeamento de IDs temporários evita perda de dados
- Experiência app-like com atualização automática, sem app store

**Por que gateways de pagamento com interface comum?**
- Trocar ou adicionar provedor (Asaas, Mercado Pago) sem reescrever o módulo de cobrança
- Webhooks idempotentes + reconciliação garantem que o pagamento seja registrado mesmo com falha de rede

**Por que PHP + PDO?**
- Compatibilidade universal de hospedagem compartilhada
- PDO garante queries parametrizadas em toda a API
- Exportações e Web Push nativos, sem biblioteca externa pesada

---

## 📊 Resumo do Projeto

| Aspecto | Detalhes |
|---------|---------|
| **Tipo** | Sistema de Gestão Comercial (SaaS-like) |
| **Stack** | PHP, MySQL, JavaScript Vanilla, PWA |
| **Arquitetura** | REST API + SPA + integrações servidor-a-servidor |
| **Módulos** | 20+ módulos integrados |
| **Segurança** | Token auth, WebAuthn, queries parametrizadas, RBAC + permissões finas |
| **Pagamentos** | Asaas + Mercado Pago (Pix, boleto, cartão) com webhooks |
| **Offline** | IndexedDB + outbox + Sync Manager |
| **Status** | Produção — linha de versão 5.x |
| **Modelo de Negócio** | Distribuidores com equipe externa + consignação |

---

## 🔒 Segurança

- **Autenticação**: tokens de sessão stateless com longa duração
- **Biometria**: WebAuthn com chave privada nunca saindo do dispositivo e contador anti-clonagem
- **Banco de Dados**: queries parametrizadas (PDO) — sem SQL injection
- **Controle de Acesso**: RBAC com 4 níveis + permissões finas por chave — sem permissões cruzadas
- **Cobrança**: webhooks com validação, log e idempotência; tokens dedicados por gateway
- **Integração**: endpoints servidor-a-servidor com token Bearer comparado via `hash_equals`
- **Sessões**: controle administrativo com encerramento individual ou em massa
- **Exportações**: protegidas por token de acesso
- **Senhas**: hash seguro, troca obrigatória no primeiro acesso e recuperação por e-mail

> Para detalhes completos: [SECURITY.md](SECURITY.md)

---

## 🎨 Destaques Técnicos

- **SPA sem framework** — zero dependência de bibliotecas JS de terceiros no core
- **Offline real** — IndexedDB com cache de GET e outbox de escrita; Sync Manager em FIFO com remapeamento de IDs temporários negativos
- **Login biométrico** — cerimônias WebAuthn de registro e login, credenciais por dispositivo
- **Cobrança recorrente com gateways** — interface + roteador para Asaas e Mercado Pago, webhooks idempotentes e reconciliação
- **Web Push nativo** — VAPID / RFC 8291 sem SDK
- **Mapa de atendimento** — Leaflet com geolocalização e priorização por criticidade
- **Integração servidor-a-servidor** — o ERP interno (ErlDev Gestão) puxa e controla a assinatura via API
- **Automação sem servidor** — cron diário de inadimplência em GitHub Actions
- **Exportação de PDF e Excel nativos** em PHP, sem bibliotecas externas
- **Controle de versão** da aplicação com invalidação automática de cache
- **Trilha de auditoria** completa em movimentações de consignação e estoque

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

- 🌐 Portfolio: [github.com/Lucas12teixeira/Portifolio-Projetos](https://github.com/Lucas12teixeira/Portifolio-Projetos)
- 💼 LinkedIn: [www.linkedin.com/in/lucas-lima-10218529a/](https://www.linkedin.com/in/lucas-lima-10218529a/)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

## 📜 Licença e Direitos

© 2025-2026 Lucas (Erl Dev). Todos os direitos reservados.

> Este repositório contém apenas **documentação de portfólio**. O código-fonte é proprietário e não está incluído. Sistema desenvolvido sob encomenda — todos os direitos reservados ao desenvolvedor e ao cliente.

---

<div align="center">

[📋 Ver Resumo Executivo](PROJECT_SUMMARY.md) • [🏗 Arquitetura](docs/ARQUITETURA.md) • [🔌 API](docs/API.md) • [🔒 Segurança](SECURITY.md)

**Desenvolvido com ❤️ e ☕ por [Lucas (Erl Dev)](https://github.com/Lucas12teixeira/Portifolio-Projetos)**

</div>
