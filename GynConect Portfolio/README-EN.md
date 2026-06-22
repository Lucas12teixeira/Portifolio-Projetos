# GynConect — Commercial Management System

<div align="center">

![GynConect Logo](assets/icons/icon.png)

**Web-Based Commercial Management System for External Sales Teams**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Status](https://img.shields.io/badge/Status-Production-success?style=for-the-badge)](.)

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

**GynConect** is a web-based commercial management system built for external sales teams operating under a **consignment model**. The platform centralizes control over customers, products, inventory, sales, commissions, and finances — all accessible in the field via a PWA with offline support.

### The Problem

Distributors with field teams face critical challenges:
- Scattered inventory control per salesperson
- Difficulty tracking consigned items
- Lack of real-time managerial visibility
- Manual, error-prone commission calculations
- No digital signatures on consignment agreements

### The Solution

GynConect integrates all these controls into a single web platform, combining **field mobility** (PWA, offline) with **managerial visibility** (dashboards, reports, exports).

---

## 📋 Features

### 🔐 Authentication & Access Control

- Email/password login with persistent token-based sessions
- **Four access levels** with distinct permissions:
  - **Salesperson** — restricted to own customers, sales, and commissions
  - **Manager** — full visibility over team and reports
  - **Finance** — limited to financial modules
  - **Dev** — full access including admin panel
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

### 👥 Customers

- Full registration: personal info, contact, address
- Status segmentation: active, inactive, closed
- Inactivity filters: no visit in +30, +60, or +90 days
- Consignment eligibility control
- Last visit date tracking
- Direct WhatsApp links
- Closure with reason and history log
- Customer reactivation
- **End Consumer** — fixed record for direct sales without individual registration

---

### 🛒 Sales

- Multi-item orders with product search
- Two payment methods per sale
- Discount per item or per order
- Automatic commission calculation per salesperson
- Standard sale vs. consignment sale differentiation
- **Digital signature** from the customer
- Auto-draft with 24-hour retention
- Status flow: Pending → Confirmed → Delivered
- Filters: date, status, salesperson, payment method, customer
- PDF and Excel export

---

### 📦 Consignment

- Item tracking per customer and per product
- Quantity tracking: current, sold, returned
- Open balance: unit price × quantity
- Ranking of customers with highest open consignment
- **Digital agreements** with versioning and history
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

---

### 🤝 Introductions

- Track customers introduced by salespersons
- Introduction bonus controlled by manager
- Filter by period and salesperson

---

### 🏦 Finance — Invoices

- Invoice control with status: open, overdue, paid, cancelled
- Automatic overdue marking
- KPIs: total open, overdue, monthly receipts, 7-day due dates
- Payment recording with date and method

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

### 📄 Reports & Exports

- Sales PDF with date, salesperson, and payment filters
- Sales Excel with totals
- Dashboard PDF and delivery sheet
- Order confirmation and signed consignment agreement
- Token-controlled access on all exports

---

### 🛠 Dev Panel

- System-wide KPIs (customers, sales, revenue, inventory, pending items)
- User management: create, edit, activate/deactivate, reset password
- Active session control: view and terminate individually or in bulk
- Recent activity feed
- Database utilities and migrations

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
| **Mobile** | PWA with Service Worker and offline support |
| **Database** | MySQL with parameterized queries |
| **Exports** | PDF and Excel generated natively in PHP |
| **Auth** | Token-based, stateless, long-lived sessions |

---

## 🏗 Architecture

```
GynConect
├── Frontend (SPA)
│   ├── HTML5 + CSS3 (dark theme, responsive design)
│   ├── Vanilla JavaScript — IIFE/Module Revealing pattern
│   ├── Service Worker (offline cache, background sync)
│   └── Chart.js (local, no CDN dependency)
│
├── Backend (REST API)
│   ├── PHP + PDO (parameterized queries)
│   ├── Endpoints organized by module
│   ├── Session token authentication
│   └── Native PDF and Excel export
│
└── Database
    ├── MySQL (normalized schema)
    └── Version control via migrations
```

### Architecture Decisions

**Why Vanilla JavaScript?**
- Zero third-party dependencies in the core
- Faster loading and full control
- Demonstrates language mastery

**Why PWA?**
- Offline use in the field — essential for external salespeople
- App-like experience without going through an app store
- Compatible with any device

**Why PHP + PDO?**
- Universal hosting compatibility
- PDO guarantees parameterized queries throughout the API
- Native exports without external libraries

---

## 📊 Project Summary

| Aspect | Details |
|--------|---------|
| **Type** | Commercial Management System |
| **Stack** | PHP, MySQL, Vanilla JavaScript, PWA |
| **Architecture** | REST API + SPA |
| **Modules** | 15+ integrated modules |
| **Security** | Token auth, parameterized queries, RBAC |
| **Status** | Production |
| **Business Model** | Distributors with external teams + consignment |

---

## 🔒 Security

- **Authentication**: Stateless session tokens with long duration
- **Database**: Parameterized queries (PDO) — no SQL injection
- **Access Control**: RBAC with 4 levels — no cross-permissions
- **Sessions**: Admin control with individual or bulk termination
- **Exports**: Token-protected access
- **Passwords**: Mandatory change on first login + email recovery

> For full details: [SECURITY.md](SECURITY.md)

---

## ✨ Technical Highlights

- **Framework-free SPA** — zero third-party JS dependencies in the core
- **PWA with Service Worker** — offline use in the field
- **Stateless authentication** with long-lived session tokens
- **Parameterized queries** throughout the API (PDO)
- **Native PDF and Excel exports** in PHP, no external libraries
- **IIFE/Module Revealing pattern** for JS organization
- **Application versioning** with automatic cache invalidation
- **Complete audit trail** on consignment movements

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
