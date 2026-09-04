# ErlDev Gestão — Internal ERP for Clients, Systems and Payments

<div align="center">

![ErlDev Gestão Logo](assets/icons/icon.png)

**ErlDev's internal system for managing clients, delivered websites/systems, and payment control**

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![MVC](https://img.shields.io/badge/Architecture-MVC-009688?style=for-the-badge)](.)
[![Status](https://img.shields.io/badge/Status-Production-success?style=for-the-badge)](.)

[📋 Features](#-features) • [🛠 Stack](#-tech-stack) • [🏗 Architecture](#-architecture) • [🔗 Integration](#-automatic-integration-with-client-systems) • [🔒 Security](#-security)

</div>

---

## 🌍 Language / Idioma

<table>
<tr>
<td align="center"><a href="README.md"><b>🇧🇷 Português</b></a><br><sub>Versão em português</sub></td>
<td align="center"><b>🇺🇸 English</b><br><sub>You are here</sub></td>
</tr>
</table>

---

## 🎯 Overview

**ErlDev Gestão** is **ErlDev**'s internal ERP: it centralizes clients, the websites and systems delivered to them, and the control of payments and subscriptions — including Pix billing, uptime monitoring of the delivered systems, and **automatic synchronization with the clients' own systems** (such as GynConect).

Built to run on **shared hosting (KingHost)** with PHP 8.x, **with no Composer or Node dependency on the server**.

### The Problem

As ErlDev started maintaining several production systems for different clients, spreadsheet control didn't scale:
- Each client's payments and subscriptions tracked by hand
- No view of which system was online, slow, or down
- No visibility into the hosting server's health
- GynConect's billing data (it has its own subscription module) re-entered manually

### The Solution

A single panel that registers clients, projects (websites/systems), and payments, generates Pix charges, monitors uptime and server health, **suspends systems for delinquency** (internal indicator + real-time push), and **automatically pulls** the subscription and invoices from systems that expose that data via API.

---

## 📋 Features

### 👥 Clients

- Client registration with notes and activity history
- Linked to projects and payments

### 🗂 Projects (Websites / Systems)

- Registration of each delivered website/system, with public URL and admin/panel URL
- **Quick-access** card with links and individual status check
- State indicator: **active / suspended**
- `fonte_integracao` origin — projects kept automatically by the integration

### 💰 Payments & Finance

- Payment control per client and per project
- Consolidated financial page
- Expense recording
- Data export

### 💳 Pix Billing (Mercado Pago)

- **Generate Pix charge** button on each pending/overdue payment
- Returns QR Code + copy-and-paste code to send to the client
- Optional Mercado Pago **webhook** marks the payment as paid automatically
- SDK-free implementation, straight against the Mercado Pago API

### ⏸ Automatic Suspension for Delinquency + Real-Time Push

- Payment overdue beyond `DIAS_TOLERANCIA_SUSPENSAO` (default 10) → system marked as **suspended** (internal indicator)
- Returns to "active" on its own when the payment is marked as paid (manually or via Pix webhook)
- **Release system** button to revert by hand
- On detecting the suspension, every device with push enabled gets a notice — even with the app closed
- **Opportunistic** check in `App\Core\Cron` (triggered by `Auth::requireLogin()`, 5-min throttle per session) — no hosting cron job needed

### 📡 Monitoring

- **KingHost server health**: PHP, MySQL, memory, disk estimated by plan, database size and largest tables
- **Per-system uptime**: online / slow / offline, checked in parallel via cURL
- "Check everything now" button and per-project individual check

### 🔗 Subscriptions — Client System Integration

Dedicated screen that registers each integrated system as a row in `integracoes_assinatura` (name, slug, client/project, sync URL+token, control URL+token) and automatically syncs subscription, invoices, and status. Details in [Automatic integration](#-automatic-integration-with-client-systems).

### 🤖 Assistant

- Built-in assistant (`AssistenteController` + `App\Core\AssistenteIA`) for quick queries about the system's data
- AI integration status shown in the panel

### 👆 Face ID / Fingerprint Login (WebAuthn)

- Each user enables their device's biometric unlock under **My Profile**
- Per-device: the private key never leaves the device
- Ported from GynConect (`web-auth/webauthn-lib`), adapted to this project's native PHP session

### 📱 PWA

- Installable on Android/Desktop Chrome (`beforeinstallprompt`) and iPhone (manual instruction)
- Basic offline operation
- Icons generated entirely in the browser `<canvas>`, no GD/Node on the server

### 👤 Users

- **admin**: full access, including user management
- **collaborator**: clients, websites/systems and payments, no user management

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Plain PHP 8.x, object-oriented, simple MVC |
| **Database** | MySQL with PDO and prepared statements |
| **Frontend** | HTML5 + CSS3 + Vanilla JavaScript |
| **Mobile** | PWA (installable, basic offline) |
| **Auth** | Native PHP session (`$_SESSION`) + WebAuthn |
| **Payments** | Mercado Pago API (Pix) — no SDK |
| **Notifications** | Native Web Push (RFC 8291/8292) in plain PHP + OpenSSL — no SDK |
| **Monitoring** | Parallel cURL + server metric collection |
| **Hosting** | KingHost (shared) — no Composer/Node on the server |
| **Scheduling** | Opportunistic pseudo-cron (`App\Core\Cron` via `Auth::requireLogin()`) |

---

## 🏗 Architecture

**Flat** structure (same pattern as GYN CONECT and AmericaTeens): everything sits directly in the folder the (sub)domain serves. `app/`, `config/`, `vendor/` and `database/` are protected by an `.htaccess` rule (403 if accessed by URL).

```
ErlDev-Gestao/
├── .htaccess                   # forced HTTPS + block app/config/vendor/database
├── index.php, login.php, dashboard.php, clientes.php, ...   # thin pages (call the Controller only)
├── manifest.json / service-worker.js
├── assets/{css,js,icons}
├── api/                         # JSON endpoints (dashboard_data, webauthn, monitoramento_check,
│                                #                 webhook_mercadopago, push)
├── app/                         # .htaccess-protected — only read via require()
│   ├── bootstrap.php            # autoload + session
│   ├── Core/                    # Database, Auth, Csrf, Helpers, Controller, Model, Cron,
│   │                            # MercadoPagoGateway, ServidorInfo, UptimeChecker,
│   │                            # WebauthnCredentialRepository, WebPushSender, PushNotifier,
│   │                            # IntegracaoSync, AssinaturaRemota, AssistenteIA
│   ├── Models/                  # Usuario, Cliente, Projeto, Pagamento, Nota, LogAtividade, PushSubscription
│   ├── Controllers/             # one per resource (+ Webauthn, Monitoramento, Push, Assistente, IntegracaoAssinatura)
│   └── Views/                   # layout/, auth/, dashboard/, clientes/, projetos/, pagamentos/, ...
├── config/config.php            # credentials and config — .htaccess-protected
├── database/schema.sql          # full DB script — .htaccess-protected
└── vendor/                      # WebAuthn lib, already resolved — .htaccess-protected
```

### Architecture Decisions

**Why simple MVC in plain PHP?**
- Runs on any shared hosting with no build step
- `vendor/` is committed — no `composer install` on the server
- Thin controllers, logic in Models and `Core/`

**Why an opportunistic pseudo-cron?**
- The hosting has no reliable cron; the delinquency check, monitoring, and sync run whenever any logged-in user opens a page (5-min throttle per session)
- Accepted trade-off: with nobody using the system, there's no 24/7 check — a real panel cron would fix it, but it was an explicit decision not to depend on that

**Why Web Push in plain PHP?**
- RFC 8291/8292 implemented with OpenSSL, no SDK or external dependency
- VAPID keys generated by a protected script (CLI/localhost), removed afterwards

---

## 🔗 Automatic Integration with Client Systems

Systems like **GynConect** have their own billing module (subscription + invoices via Pix/bank slip, with delinquency suspension). Instead of re-entering that data by hand, Gestão pulls it all automatically — and supports **as many systems as are registered**, not just GynConect.

```
┌─────────────────────┐        GET  (Bearer token, read-only)            ┌──────────────────┐
│   Client system      │  ◄──────────────────────────────────────────────  │  ErlDev Gestão   │
│   (e.g. GynConect)   │        subscription + invoices (JSON)             │                  │
│                     │                                                   │  IntegracaoSync  │
│  /api/integracoes/  │  ◄──────────────────────────────────────────────  │  AssinaturaRemota│
│    erldev_gestao.php │        POST (actions: suspend / reactivate /      │                  │
│    ..._controle.php  │              mark_paid / config / ...)            └──────────────────┘
└─────────────────────┘
```

- **Registration** (`assinaturas.php`): each system is a row in `integracoes_assinatura` — no credentials hard-coded in `config.php`
- **`App\Core\IntegracaoSync`**: fetches the data and automatically creates/updates the client, the linked project (`fonte_integracao = <slug>`) and the payments (`fonte_integracao` + `id_externo` = invoice id, so nothing ever duplicates)
- **`App\Core\AssinaturaRemota`**: sends control actions (suspend/reactivate/mark paid/etc.) from `assinatura_detalhe.php`
- Suspension there → project here goes "suspended" and fires push, just like manual overdue suspension
- Runs on its own every `sync_intervalo_horas` (per integration), via the same opportunistic pseudo-cron — or manually in **Monitoring** / **Subscriptions**

---

## 🔒 Security

- **Native PHP session** (`$_SESSION`); `password_hash` / `password_verify`
- **PDO prepared statements** on every query (anti-SQL injection)
- **CSRF** (`App\Core\Csrf`) on every write form, validated server-side
- **XSS**: output always escaped with `Helpers::sanitize` (`htmlspecialchars`)
- **`config/config.php` outside the webroot** — not served even if `.htaccess` fails
- **`.htaccess`**: forced HTTPS + block on `app/`, `config/`, `vendor/`, `database/`
- **WebAuthn**: the private key never leaves the device; only the public key is stored (`webauthn_credentials`)
- **Integrations**: client-side endpoints protected by token; remote control with a closed action set
- **VAPID**: keys generated by a protected script and removed from the server after use

---

## 📊 Project Summary

| Aspect | Details |
|--------|---------|
| **Type** | Internal ERP (clients, delivered projects, payments) |
| **Stack** | Plain PHP 8 (MVC), MySQL/PDO, Vanilla JS, PWA |
| **Hosting** | KingHost shared — no Composer/Node on the server |
| **Payments** | Pix via Mercado Pago (no SDK) |
| **Notifications** | Native Web Push (RFC 8291/8292) in PHP + OpenSSL |
| **Differentiator** | Server-to-server sync with the clients' systems |
| **Roles** | admin, collaborator |
| **Status** | Production |

---

## 👨‍💻 About the Developer

**Lucas (Erl Dev)** — Full-Stack Developer | PWA Specialist

- 🌐 Portfolio: [github.com/Lucas12teixeira/Portifolio-Projetos](https://github.com/Lucas12teixeira/Portifolio-Projetos)
- 💼 LinkedIn: [www.linkedin.com/in/lucas-lima-10218529a/](https://www.linkedin.com/in/lucas-lima-10218529a/)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

## 📜 License & Rights

© 2025-2026 Lucas (Erl Dev). All rights reserved.

> This repository contains **portfolio documentation only**. Source code is proprietary and not included.

---

<div align="center">

[📋 Executive Summary](PROJECT_SUMMARY.md) • [🇧🇷 Português](README.md)

**Built with ❤️ and ☕ by [Lucas (Erl Dev)](https://github.com/Lucas12teixeira/Portifolio-Projetos)**

</div>
