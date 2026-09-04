# GynConect — Commercial Management System

<div align="center">

![GynConect Logo](assets/icons/icon.png)

**Web-Based Commercial Management System for External Sales Teams**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Status](https://img.shields.io/badge/Status-Production_v5.x-success?style=for-the-badge)](.)

[📋 Features](#-features) • [🛠 Stack](#-tech-stack) • [🏗 Architecture](#-architecture) • [🔒 Security](#-security) • [📊 Summary](#-project-summary)

---

## 🌍 Language / Idioma

<table>
<tr>
<td align="center">
<a href="README.md">
<b>🇧🇷 Português</b>
</a><br>
<sub>Versão em português</sub>
</td>
<td align="center">
<b>🇺🇸 English</b><br>
<sub>You are here</sub>
</td>
</tr>
</table>

</div>

---

## 🎯 Overview

**GynConect** is a web-based commercial management system built for external sales teams operating under a **consignment model**. The platform centralizes control over customers, products, inventory, sales, commissions, and finances — all accessible in the field via a PWA with real offline support (IndexedDB + write-sync queue).

The system is in **active production** and has evolved continuously through the **5.x** version line: it now includes biometric login, recurring billing with payment gateways (Pix/bank slip), Web Push notifications, a field-service map with geolocation, and server-to-server integration with the internal ERP (ErlDev Gestão).

### The Problem

Distributors with field teams face critical challenges:
- Scattered inventory control per salesperson
- Difficulty tracking consigned items
- Lack of real-time managerial visibility
- Manual, error-prone commission calculations
- No digital signatures on consignment agreements
- Subjective prioritization of which customers to visit

### The Solution

GynConect integrates all these controls into a single web platform, combining **field mobility** (PWA, offline, biometrics) with **managerial visibility** (dashboards, P&L, reports, exports) and **autonomous operation** (automated billing, webhooks, scheduled cron).

---

## 📋 Features

### 🔐 Authentication & Access Control

- Email/password login with persistent token-based sessions
- **Biometric login (WebAuthn)** — Face ID, Touch ID, Android fingerprint and Windows Hello, with a per-device credential and anti-clone counter
- **Four access levels** with distinct permissions:
  - **Salesperson** — restricted to own customers, sales, and commissions
  - **Manager** — full visibility over team and reports
  - **Finance** — limited to financial modules
  - **Dev** — full access including admin panel
- **Fine-grained permissions** by key (e.g. `fin_boletos_ver`) on top of the level
- **Billing officer** flag — enables the billing module for a specific user
- Mandatory password change on first login
- Password recovery via email

---

### 📊 Dashboard

- Real-time financial KPIs: gross revenue, net revenue, commissions, and company remittance
- Period filters: today, 7 days, current month, or custom range
- Salesperson performance ranking
- Consignment summary: total open value and active customers
- Pending payments and monthly expenses
- PDF export

---

### 📋 Field Service (Atendimento)

- Infinite-scroll list of customers **not visited in 30+ days**, to prioritize follow-up
- Quick filters by inactivity band (30 / 60 / 90 days)
- **Map view (Leaflet)** with salesperson geolocation and customer markers
- Color-coded criticality buckets (ok, warning, critical, never visited)
- Salespeople see only their own customers; managers and dev see managers' customers

---

### 👥 Customers

- Full registration: personal info, contact, address
- Status segmentation: active, inactive, closed, finalized
- Inactivity filters: no visit in +30, +60, or +90 days
- Consignment eligibility control
- Last visit date tracking
- Direct WhatsApp links
- Closure with reason and history log
- Customer reactivation
- **Per-customer sales history** with PDF generation and sharing
- **End Consumer** — fixed system record for direct sales without individual registration

---

### 🛒 Sales

- Multi-item orders with product search
- Two payment methods per sale
- Discount per item or per order
- Automatic commission calculation per salesperson
- Standard sale vs. consignment sale differentiation
- **Digital signature** from the customer (waived for End Consumer)
- Auto-draft with 24-hour retention
- Status flow: Pending → Confirmed → Delivered
- Filters: date, status, salesperson, payment method, customer
- PDF and Excel export with all filters applied
- Create and edit work **offline** — the sale enters the queue and syncs when the connection returns

---

### 📦 Consignment

- Item tracking per customer and per product
- Quantity tracking: current, sold, returned
- Open balance: unit price × quantity
- Ranking of customers with highest open consignment
- **Digital agreements** with versioning and history
- **Unified visits** — settlement (sale) + restock in a single visit, with previous and new balance, numbered `VIS-YYYY-NNNN`
- Settlement that invoices only the units sold and returns the rest to the salesperson's stock
- Movements with complete audit trail
- Inventory × consignment reconciliation

---

### 🗃 Products & Inventory

- Product catalog with SKU and EAN (8–14 digit validation)
- Central warehouse stock and individual per salesperson
- Consignment inventory tracked separately
- Visual low-stock alerts
- Product categories
- Movement history
- Total inventory value calculated automatically

---

### 💰 Commissions

- Automatic calculation per sale with configurable percentage per salesperson
- Status: pending, paid, cancelled
- Payment controlled by manager
- Period filter with totals
- `dev`-level users generate no commission or cost for the company

---

### 📈 Standard Profit (per Product)

- Configures the standard profit (currency per unit) of each product — applies to all customers
- At settlement the value is locked as default; the salesperson can unlock and type a manual value for that sale only
- Visible to manager and dev only

---

### 🤝 Introductions

- Track customers introduced by salespersons
- Introduction bonus controlled by manager
- Status: pending, paid, cancelled
- Filter by period and salesperson

---

### 🏦 Finance

- Panel redesigned as a **simplified P&L (DRE)**, with 6 KPIs and quick filters
- Invoice control with status: open, overdue, paid, cancelled
- Automatic overdue marking
- **Installment bank slips** with multiple installments
- KPIs: total open, overdue, monthly receipts, 7-day due dates
- Payment recording with date and method
- Access gated by the `fin_boletos_ver` permission

---

### 💳 Billing & Subscription (SaaS model)

GynConect itself manages the recurring billing of the client that uses it:

- Single **subscription** record with monthly fee, due day, and tolerance days
- **Monthly invoices** via Pix, bank slip, or card
- **Integrated payment gateways** — Asaas and Mercado Pago, behind a common interface with a gateway router
- **Webhooks** for payment confirmation (Asaas and Mercado Pago) with logging and per-event idempotency
- **Automatic reconciliation** — queries the gateway when the webhook doesn't arrive
- **Daily delinquency cron** (GitHub Actions) that checks overdue invoices
- **Delinquency lockout** — when the subscription goes overdue, regular users land on a notice screen and only the billing officer can navigate, restricted to the billing module
- Module visible only to the billing officer and dev

---

### 🔔 Web Push Notifications

- Per-device/browser push subscriptions (PushManager + VAPID)
- Native Web Push (RFC 8291) for billing alerts
- The same event is delivered to **both the dev and the billing officer**, each with their own delivery record

---

### 💸 Expenses

- Operational expense recording with categories
- Visible to managers only
- Integrated with dashboard for net profit calculation

---

### 🔄 Returns

- Registration with quantity and reason
- Types: return or exchange
- Manager approval flow
- Product quarantine with resolution: return to stock, discard, or send for repair
- Automatic inventory impact on resolution

---

### 🔗 ErlDev Gestão Integration

- **Read-only** token-protected endpoint that exposes the current state of `subscription` and `invoices` for the internal ERP to sync the client automatically
- **Remote control** endpoint that accepts actions (`suspend`, `reactivate`, `mark_paid`, `config`, `cancel_invoice`, `delete_invoice`)
- Server-to-server communication with `Authorization: Bearer`, no session/cookie

---

### 📄 Reports & Exports

- Sales PDF with date, salesperson, and payment filters
- Sales Excel with totals
- Dashboard PDF and delivery sheet
- Order confirmation and signed consignment agreement
- Per-customer sales history PDF
- Token-controlled access on all exports

---

### 🛠 Dev Panel

- System-wide KPIs (customers, sales, revenue, inventory, pending items)
- User management: create, edit, activate/deactivate, reset password, fine-grained permissions
- Active session control: view and terminate individually or in bulk
- Recent activity feed with search
- Force version update across all clients and maintenance mode
- Database utilities, migrations, and subscription migration

---

## 🛠 Tech Stack

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

| Layer | Technology |
|-------|-----------|
| **Frontend** | SPA in HTML5 + Vanilla JavaScript (no frameworks) |
| **Backend** | PHP with PDO/MySQL, REST API architecture |
| **Mobile** | PWA with Service Worker, auto-update and offline support |
| **Offline** | IndexedDB (GET cache + write outbox) with a FIFO Sync Manager |
| **Database** | MySQL with parameterized queries and versioned migrations |
| **Biometrics** | WebAuthn (`web-auth/webauthn-lib`) — Face ID / Touch ID / fingerprint |
| **Payments** | Asaas and Mercado Pago gateways (Pix, bank slip, card) with webhooks |
| **Notifications** | Native Web Push (VAPID / RFC 8291) |
| **Maps** | Leaflet (local, no CDN) |
| **Exports** | PDF and Excel generated natively in PHP |
| **Auth** | Token-based, stateless, long-lived sessions |
| **Automation** | Daily cron via GitHub Actions (delinquency check) |

---

## 🏗 Architecture

```
GynConect
├── Frontend (SPA)
│   ├── HTML5 + CSS3 (dark theme, responsive design)
│   ├── Vanilla JavaScript — IIFE/Module Revealing pattern
│   ├── Service Worker (offline cache, auto-update)
│   ├── IndexedDB — api_cache (GET with TTL) + outbox (pending writes)
│   ├── Sync Manager — processes the outbox in FIFO, remaps temporary IDs
│   ├── WebAuthn — per-device biometric login
│   ├── Chart.js + Leaflet (local, no CDN)
│
├── Backend (REST API)
│   ├── PHP + PDO (parameterized queries)
│   ├── Endpoints organized by module (~30 domains)
│   ├── Session-token authentication + RBAC + fine-grained permissions
│   ├── Billing — gateway interface + router (Asaas / Mercado Pago)
│   ├── Webhooks with logging and idempotency
│   ├── Server-to-server integration with ErlDev Gestão
│   └── Native PDF and Excel export
│
├── Database
│   ├── MySQL (normalized schema)
│   └── Version control via migrations (v1.x → v5.6)
│
└── Automation
    └── GitHub Actions — daily delinquency cron
```

### Architecture Decisions

**Why Vanilla JavaScript?**
- Zero third-party dependencies in the core
- Faster loading and full control
- Demonstrates language mastery

**Why a PWA with real offline?**
- Field use with unstable connectivity — the salesperson records the sale and syncs later
- A write queue (outbox) in IndexedDB with temporary-ID remapping prevents data loss
- App-like experience with automatic updates, no app store

**Why payment gateways behind a common interface?**
- Swap or add a provider (Asaas, Mercado Pago) without rewriting the billing module
- Idempotent webhooks + reconciliation ensure the payment is recorded even on network failure

**Why PHP + PDO?**
- Universal shared-hosting compatibility
- PDO guarantees parameterized queries throughout the API
- Native exports and Web Push, no heavy external library

---

## 📊 Project Summary

| Aspect | Details |
|--------|---------|
| **Type** | Commercial Management System (SaaS-like) |
| **Stack** | PHP, MySQL, Vanilla JavaScript, PWA |
| **Architecture** | REST API + SPA + server-to-server integrations |
| **Modules** | 20+ integrated modules |
| **Security** | Token auth, WebAuthn, parameterized queries, RBAC + fine-grained permissions |
| **Payments** | Asaas + Mercado Pago (Pix, bank slip, card) with webhooks |
| **Offline** | IndexedDB + outbox + Sync Manager |
| **Status** | Production — 5.x version line |
| **Business Model** | Distributors with external teams + consignment |

---

## 🔒 Security

- **Authentication**: stateless session tokens with long duration
- **Biometrics**: WebAuthn with the private key never leaving the device and an anti-clone counter
- **Database**: parameterized queries (PDO) — no SQL injection
- **Access Control**: RBAC with 4 levels + fine-grained permissions by key — no cross-permissions
- **Billing**: validated webhooks with logging and idempotency; dedicated tokens per gateway
- **Integration**: server-to-server endpoints with a Bearer token compared via `hash_equals`
- **Sessions**: admin control with individual or bulk termination
- **Exports**: token-protected access
- **Passwords**: secure hash, mandatory change on first login, email recovery

> For full details: [SECURITY.md](SECURITY.md)

---

## ✨ Technical Highlights

- **Framework-free SPA** — zero third-party JS dependencies in the core
- **Real offline** — IndexedDB with GET cache and write outbox; FIFO Sync Manager with negative temporary-ID remapping
- **Biometric login** — WebAuthn registration and login ceremonies, per-device credentials
- **Recurring billing with gateways** — interface + router for Asaas and Mercado Pago, idempotent webhooks and reconciliation
- **Native Web Push** — VAPID / RFC 8291 with no SDK
- **Field-service map** — Leaflet with geolocation and criticality prioritization
- **Server-to-server integration** — the internal ERP (ErlDev Gestão) pulls and controls the subscription via API
- **Serverless automation** — daily delinquency cron on GitHub Actions
- **Native PDF and Excel exports** in PHP, no external libraries
- **Application versioning** with automatic cache invalidation
- **Complete audit trail** on consignment and inventory movements

---

## 👨‍💻 About the Developer

**Lucas (Erl Dev)**
Full-Stack Developer | PWA Specialist | Commercial Web Solutions

- 🌐 Portfolio: [github.com/Lucas12teixeira/Portifolio-Projetos](https://github.com/Lucas12teixeira/Portifolio-Projetos)
- 💼 LinkedIn: [www.linkedin.com/in/lucas-lima-10218529a/](https://www.linkedin.com/in/lucas-lima-10218529a/)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

## 📜 License & Rights

© 2025-2026 Lucas (Erl Dev). All rights reserved.

> This repository contains **portfolio documentation only**. Source code is proprietary and not included. System developed on commission — all rights reserved to the developer and client.

---

<div align="center">

[📋 Executive Summary](PROJECT_SUMMARY.md) • [🏗 Architecture](docs/ARQUITETURA.md) • [🔌 API](docs/API.md) • [🔒 Security](SECURITY.md)

**Built with ❤️ and ☕ by [Lucas (Erl Dev)](https://github.com/Lucas12teixeira/Portifolio-Projetos)**

</div>
