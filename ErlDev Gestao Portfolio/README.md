# ErlDev Gestão — ERP Interno de Clientes, Sistemas e Pagamentos

<div align="center">

![ErlDev Gestão Logo](assets/icons/icon.png)

**Sistema interno da ErlDev para gestão de clientes, sites/sistemas entregues e controle de pagamentos**

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![MVC](https://img.shields.io/badge/Arquitetura-MVC-009688?style=for-the-badge)](.)
[![Status](https://img.shields.io/badge/Status-Produção-success?style=for-the-badge)](.)

[📋 Funcionalidades](#-funcionalidades) • [🛠 Stack](#-stack-técnico) • [🏗 Arquitetura](#-arquitetura) • [🔗 Integração](#-integração-automática-com-sistemas-de-clientes) • [🔒 Segurança](#-segurança)

</div>

---

## 🌍 Idioma / Language

<table>
<tr>
<td align="center"><b>🇧🇷 Português</b><br><sub>Você está aqui</sub></td>
<td align="center"><a href="README-EN.md"><b>🇺🇸 English</b></a><br><sub>English version</sub></td>
</tr>
</table>

---

## 🎯 Visão Geral

**ErlDev Gestão** é o ERP interno da **ErlDev**: centraliza os clientes, os sites e sistemas entregues a eles, e o controle de pagamentos e assinaturas — incluindo cobrança Pix, monitoramento de uptime dos sistemas e **sincronização automática com os próprios sistemas dos clientes** (como o GynConect).

Desenvolvido para rodar em **hospedagem compartilhada (KingHost)** com PHP 8.x, **sem depender de Composer ou Node no servidor**.

### O Problema

Conforme a ErlDev passou a manter vários sistemas em produção para clientes diferentes, o controle em planilha não escalava:
- Pagamentos e assinaturas de cada cliente controlados manualmente
- Sem visão de qual sistema estava online, lento ou fora do ar
- Sem visibilidade da saúde do servidor de hospedagem
- Dados de cobrança do GynConect (que tem seu próprio módulo de assinatura) recadastrados à mão

### A Solução

Um painel único que cadastra clientes, projetos (sites/sistemas) e pagamentos, gera cobrança Pix, monitora uptime e saúde do servidor, **suspende sistemas por inadimplência** (indicador interno + push em tempo real) e **puxa automaticamente** a assinatura e as faturas dos sistemas que expõem esses dados por API.

---

## 📋 Funcionalidades

### 👥 Clientes

- Cadastro de clientes com notas e histórico de atividade
- Vínculo com projetos e pagamentos

---

### 🗂 Projetos (Sites / Sistemas)

- Cadastro de cada site/sistema entregue, com URL pública e URL do painel/admin
- Card de **acesso rápido** com links e verificação individual de status
- Indicador de estado: **ativo / suspenso**
- Origem `fonte_integracao` — projetos mantidos automaticamente pela integração

---

### 💰 Pagamentos e Financeiro

- Controle de pagamentos por cliente e por projeto
- Página financeira com visão consolidada
- Registro de despesas
- Exportação de dados

---

### 💳 Cobrança Pix (Mercado Pago)

- Botão **Gerar cobrança Pix** em cada pagamento pendente/atrasado
- Retorna QR Code + código copia-e-cola para enviar ao cliente
- **Webhook** opcional do Mercado Pago marca o pagamento como pago automaticamente
- Implementação sem SDK, direto na API do Mercado Pago

---

### ⏸ Suspensão Automática por Atraso + Push em Tempo Real

- Pagamento atrasado além de `DIAS_TOLERANCIA_SUSPENSAO` (padrão 10) → sistema marcado como **suspenso** (indicador interno)
- Volta a "ativo" sozinho quando o pagamento é marcado como pago (manual ou via webhook Pix)
- Botão **Liberar sistema** para reverter na mão
- Ao detectar a suspensão, todos os dispositivos com push ativado recebem um aviso — mesmo com o app fechado
- Checagem **oportunista** em `App\Core\Cron` (disparada por `Auth::requireLogin()`, throttle de 5 min por sessão) — sem depender de cron job na hospedagem

---

### 📡 Monitoramento

- **Saúde do servidor KingHost**: PHP, MySQL, memória, disco estimado pelo plano, tamanho do banco e maiores tabelas
- **Uptime de cada site/sistema**: online / lento / offline, checados em paralelo via cURL
- Botão "Verificar tudo agora" e verificação individual por projeto

---

### 🔗 Assinaturas — Integração com Sistemas de Clientes

Tela dedicada que cadastra cada sistema integrado como uma linha em `integracoes_assinatura` (nome, slug, cliente/projeto, URL+token de sync, URL+token de controle) e sincroniza automaticamente assinatura, faturas e status. Detalhes na seção [Integração automática](#-integração-automática-com-sistemas-de-clientes).

---

### 🤖 Assistente

- Assistente integrado (`AssistenteController` + `App\Core\AssistenteIA`) para consultas rápidas sobre os dados do sistema
- Status da integração de IA exposto no painel

---

### 👆 Login com Face ID / Digital (WebAuthn)

- Cada usuário ativa o desbloqueio biométrico do próprio aparelho em **Meu Perfil**
- Funciona por dispositivo: a chave privada nunca sai do aparelho
- Portado do GynConect (`web-auth/webauthn-lib`), adaptado para a sessão PHP nativa

---

### 📱 PWA

- Instalável em Android/Desktop Chrome (captura `beforeinstallprompt`) e iPhone (instrução manual)
- Funcionamento básico offline
- Ícones gerados 100% no `<canvas>` do navegador, sem GD/Node no servidor

---

### 👤 Usuários

- **admin**: acesso total, incluindo cadastro/edição de usuários
- **colaborador**: clientes, sites/sistemas e pagamentos, sem gestão de usuários

---

## 🛠 Stack Técnico

| Camada | Tecnologia |
|--------|-----------|
| **Backend** | PHP 8.x puro, orientado a objetos, MVC simples |
| **Banco** | MySQL com PDO e *prepared statements* |
| **Frontend** | HTML5 + CSS3 + JavaScript Vanilla |
| **Mobile** | PWA (instalável, offline básico) |
| **Autenticação** | Sessão nativa PHP (`$_SESSION`) + WebAuthn |
| **Pagamentos** | API do Mercado Pago (Pix) — sem SDK |
| **Notificações** | Web Push nativo (RFC 8291/8292) em PHP puro + OpenSSL — sem SDK |
| **Monitoramento** | cURL paralelo + coleta de métricas do servidor |
| **Hospedagem** | KingHost (compartilhada) — sem Composer/Node no servidor |
| **Agendamento** | Pseudo-cron oportunista (`App\Core\Cron` via `Auth::requireLogin()`) |

---

## 🏗 Arquitetura

Estrutura **achatada** (mesmo padrão do GYN CONECT e do AmericaTeens): tudo fica direto na pasta servida pelo (sub)domínio. `app/`, `config/`, `vendor/` e `database/` ficam protegidos por regra no `.htaccess` (403 se acessados por URL).

```
ErlDev-Gestao/
├── .htaccess                   # HTTPS forçado + bloqueio de app/config/vendor/database
├── index.php, login.php, dashboard.php, clientes.php, ...   # páginas finas (só chamam o Controller)
├── manifest.json / service-worker.js
├── assets/{css,js,icons}
├── api/                         # endpoints JSON (dashboard_data, webauthn, monitoramento_check,
│                                #                 webhook_mercadopago, push)
├── app/                         # protegido via .htaccess — só lido via require()
│   ├── bootstrap.php            # autoload + sessão
│   ├── Core/                    # Database, Auth, Csrf, Helpers, Controller, Model, Cron,
│   │                            # MercadoPagoGateway, ServidorInfo, UptimeChecker,
│   │                            # WebauthnCredentialRepository, WebPushSender, PushNotifier,
│   │                            # IntegracaoSync, AssinaturaRemota, AssistenteIA
│   ├── Models/                  # Usuario, Cliente, Projeto, Pagamento, Nota, LogAtividade, PushSubscription
│   ├── Controllers/             # um por recurso (+ Webauthn, Monitoramento, Push, Assistente, IntegracaoAssinatura)
│   └── Views/                   # layout/, auth/, dashboard/, clientes/, projetos/, pagamentos/, ...
├── config/config.php            # credenciais e configs — protegido via .htaccess
├── database/schema.sql          # script completo do banco — protegido via .htaccess
└── vendor/                      # lib de WebAuthn, já resolvida — protegido via .htaccess
```

### Decisões de Arquitetura

**Por que MVC simples em PHP puro?**
- Roda em qualquer hospedagem compartilhada sem build step
- `vendor/` versionado — nada de `composer install` no servidor
- Controllers finos, lógica nos Models e no `Core/`

**Por que pseudo-cron oportunista?**
- A hospedagem não tem cron confiável; a checagem de inadimplência, monitoramento e sincronização roda quando qualquer usuário logado abre uma página (throttle de 5 min por sessão)
- Trade-off assumido: sem ninguém usando o sistema, não há checagem 24h — um cron real no painel resolveria, mas foi decisão explícita não depender disso

**Por que Web Push em PHP puro?**
- RFC 8291/8292 implementadas com OpenSSL, sem SDK nem dependência externa
- Chaves VAPID geradas por script protegido (CLI/localhost) e removido depois

---

## 🔗 Integração Automática com Sistemas de Clientes

Sistemas como o **GynConect** têm seu próprio módulo de cobrança (assinatura + faturas via Pix/boleto, com suspensão por inadimplência). Em vez de recadastrar esses dados na mão, o Gestão puxa tudo automaticamente — e suporta **quantos sistemas forem cadastrados**, não só o GynConect.

```
┌─────────────────────┐        GET  (Bearer token, somente-leitura)      ┌──────────────────┐
│  Sistema do cliente  │  ◄──────────────────────────────────────────────  │  ErlDev Gestão   │
│  (ex.: GynConect)    │        assinatura + faturas (JSON)                │                  │
│                     │                                                   │  IntegracaoSync  │
│  /api/integracoes/  │  ◄──────────────────────────────────────────────  │  AssinaturaRemota│
│    erldev_gestao.php │        POST (ações: suspender / reativar /        │                  │
│    ..._controle.php  │              marcar_pago / config / ...)          └──────────────────┘
└─────────────────────┘
```

- **Cadastro** (`assinaturas.php`): cada sistema é uma linha em `integracoes_assinatura` — nenhuma credencial fica fixa em `config.php`
- **`App\Core\IntegracaoSync`**: busca os dados e cria/atualiza automaticamente o cliente, o projeto vinculado (`fonte_integracao = <slug>`) e os pagamentos (`fonte_integracao` + `id_externo` = id da fatura, para nunca duplicar)
- **`App\Core\AssinaturaRemota`**: envia as ações de controle (suspender/reativar/marcar pago/etc.) a partir de `assinatura_detalhe.php`
- Suspensão lá → projeto aqui fica "suspenso" e dispara push, igual à suspensão por atraso manual
- Roda sozinho a cada `sync_intervalo_horas` (por integração), pelo mesmo pseudo-cron oportunista — ou manualmente em **Monitoramento** / **Assinaturas**

---

## 🔒 Segurança

- **Sessão** nativa do PHP (`$_SESSION`); `password_hash` / `password_verify`
- **PDO com prepared statements** em todas as queries (anti-SQL injection)
- **CSRF** (`App\Core\Csrf`) em todo formulário de escrita, validado no servidor
- **XSS**: saída sempre escapada com `Helpers::sanitize` (`htmlspecialchars`)
- **`config/config.php` fora do webroot** — mesmo que o `.htaccess` falhe, não é servido
- **`.htaccess`**: HTTPS forçado + bloqueio de `app/`, `config/`, `vendor/`, `database/`
- **WebAuthn**: chave privada nunca sai do aparelho; só a pública é salva (`webauthn_credentials`)
- **Integrações**: endpoints do lado do cliente protegidos por token; controle remoto com ações fechadas
- **VAPID**: chaves geradas por script protegido e removido do servidor após o uso

---

## 📊 Resumo do Projeto

| Aspecto | Detalhes |
|---------|---------|
| **Tipo** | ERP interno (clientes, projetos entregues, pagamentos) |
| **Stack** | PHP 8 puro (MVC), MySQL/PDO, JS Vanilla, PWA |
| **Hospedagem** | KingHost compartilhada — sem Composer/Node no servidor |
| **Pagamentos** | Pix via Mercado Pago (sem SDK) |
| **Notificações** | Web Push nativo (RFC 8291/8292) em PHP + OpenSSL |
| **Diferencial** | Sincronização servidor-a-servidor com os sistemas dos clientes |
| **Perfis** | admin, colaborador |
| **Status** | Produção |

---

## 👨‍💻 Sobre o Desenvolvedor

**Lucas (Erl Dev)** — Full-Stack Developer | Especialista em PWA

- 🌐 Portfolio: [github.com/Lucas12teixeira/Portifolio-Projetos](https://github.com/Lucas12teixeira/Portifolio-Projetos)
- 💼 LinkedIn: [www.linkedin.com/in/lucas-lima-10218529a/](https://www.linkedin.com/in/lucas-lima-10218529a/)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

## 📜 Licença e Direitos

© 2025-2026 Lucas (Erl Dev). Todos os direitos reservados.

> Este repositório contém apenas **documentação de portfólio**. O código-fonte é proprietário e não está incluído.

---

<div align="center">

[📋 Resumo Executivo](PROJECT_SUMMARY.md) • [🇺🇸 English](README-EN.md)

**Desenvolvido com ❤️ e ☕ por [Lucas (Erl Dev)](https://github.com/Lucas12teixeira/Portifolio-Projetos)**

</div>
