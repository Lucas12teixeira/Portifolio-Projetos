# Louvor ADE — Worship Team Scheduling System

<div align="center">

![Louvor ADE Logo](assets/icons/icon.png)

**Scheduling PWA for the AD Expansul Worship Ministry**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![WebAuthn](https://img.shields.io/badge/WebAuthn-Biometrics-2ECC71?style=for-the-badge&logo=fidoalliance&logoColor=white)](https://webauthn.io)
[![Status](https://img.shields.io/badge/Status-Production-success?style=for-the-badge)](.)

[📋 Features](#-features) • [🛠 Stack](#-tech-stack) • [🏗 Architecture](#-architecture) • [🔒 Security](#-security)

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

**Louvor ADE** is a scheduling Progressive Web App built for the worship ministry of **Assembleia de Deus Expansul**. It replaces a manually maintained spreadsheet/image with an installable mobile app that works offline, offers biometric login, and automatically enforces the ministry's role-stacking rules when building the schedule.

### The Problem

The schedule used to be built by hand in a spreadsheet and shared as an image in the group chat, which caused recurring issues:
- No one was sure which version was the latest
- Role-stacking mistakes (e.g. the same person as Worship Leader and Backing Vocal in the same service)
- No practical way to confirm attendance or flag unavailability
- Members' contacts scattered around
- No historical record of past schedules

### The Solution

An installable app with an always-current schedule, assisted schedule building with **automatic role-restriction validation**, attendance confirmation by members, notices and a verse of the week, and an admin panel with fine-grained permission control.

---

## 📋 Features

### 🔐 Access & Roles

Traditional login (email/password) **or biometrics** (Face ID, Touch ID, Android fingerprint, Windows Hello), with a "remember this device" option. RBAC with four roles:

| Role | Access |
|------|--------|
| **Guest** | Read the current and next schedule, no contact data |
| **Member** | Full schedule, contacts, confirm attendance/unavailability, biometrics |
| **Admin** | + Build schedule, register members, edit notices, verse and restrictions |
| **Dev** | + Promote/demote users, fine-grained permissions, logs, feature flags |

- Mandatory password change on first login
- Password recovery via email
- Per-user biometric device management

---

### 📅 Schedule

- View of the **current and next week**
- Schedule building by the admin, assigning roles per service
- **Service-day template** and per-member day availability
- **Automatic role-stacking validation** at assignment time
- Attendance confirmation / unavailability by the member
- **Web Push** notification when a member is scheduled

---

### ⚖️ Role-Stacking Rules

The ministry works with 9 roles (Worship Leader, Backing Vocal, Acoustic Guitar, Electric Guitar, Bass, Drums, Keys, Projection, Sound Tech).

- The **Worship Leader × Backing Vocal mutually exclusive** restriction is **not hard-coded** — it lives in a table (`restricoes_funcao`) and is validated server-side on every assignment
- The admin can **disable that rule** or **create new exclusive combinations** in *Admin → Notices → Role-stacking restrictions*

---

### 📢 Notices, Verse & Contacts

- Ministry notice board
- Editable verse of the week
- Member contact list (from the Member role up)

---

### 🛠 Dev Panel

- Overview panel
- User promotion/demotion and **fine-grained permissions**
- **Feature flags** — toggle functionality without a deploy
- Activity logs
- Privacy (LGPD) tools

---

### 🔒 Privacy (LGPD)

- Consent tracking
- Privacy request flow (data access / deletion)
- Terms of Use and Privacy Policy pages

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Frontend** | Server-rendered PHP pages + Vanilla JavaScript + CSS3 |
| **Backend** | PHP 7.4+ with PDO/MySQL |
| **Auth** | PHP session (`$_SESSION`) + long-lived cookie + WebAuthn |
| **Mobile** | PWA with Service Worker, manifest and iOS splash screens |
| **API** | JSON endpoints (`/api`) for WebAuthn ceremonies and interactive actions |
| **Libraries** | `web-auth/webauthn-lib`, `nyholm/psr7`, `phpmailer/phpmailer`, `minishlink/web-push` |
| **Notifications** | Web Push (VAPID) |
| **Security** | Synchronized CSRF token, PDO prepared statements, `password_hash` |

---

## 🏗 Architecture

Based on the **Gyn Conect** project (PHP + PDO + vanilla JS + PWA), **adapted** to server-rendered pages with a PHP session instead of Gyn Conect's SPA + Bearer token.

```
/louvor-ade
  /assets        css, js, icons, manifest
  /config        database connection, app constants (SMTP, VAPID)
  /includes      bootstrap, auth, RBAC, CSRF, helpers, schedule business rules
  /api           JSON endpoints (auth, schedule, admin, dev) — called via fetch()
  /pages
    /login       login, signup, guest, recover/reset password, terms, account
    /escala      schedule view (current + next week)
    /admin       build schedule, members, contacts, service days, notices, restrictions, verse
    /dev         panel, permissions, logs, features, privacy
  /sql           schema.sql
  manifest.json
  service-worker.js
  index.php
```

### Differences from Gyn Conect

| Aspect | Gyn Conect | Louvor ADE |
|--------|-----------|------------|
| Frontend | SPA (`index.html` + JS router) | Server-rendered PHP pages |
| Auth | Bearer token in `localStorage` | PHP session (`$_SESSION`) + long cookie |
| CSRF | Not used (`Authorization` header protects) | **Synchronized CSRF token** on every `/api` |
| Devices | `sessoes` table | Same `sessoes` table (mobile/desktop expiry split) |
| WebAuthn | `residentKey: discouraged` | Identical |

The `/api` folder was still needed in a PHP-pages app: WebAuthn and interactive actions (confirm attendance, build schedule) require JSON endpoints called via `fetch()`.

### Schedule business rules

The role-restriction check lives in `includes/escala_regras.php`, called by `api/admin/escala_atribuir.php` whenever someone is scheduled — reading the exclusive combinations from the `restricoes_funcao` table, not from code constants.

---

## 🔒 Security

- **Authentication**: PHP session + long-lived "remember this device" cookie backed by the `sessoes` table
- **Biometrics**: WebAuthn — the private key never leaves the device; only the public key is stored
- **CSRF**: synchronized token validated on every write endpoint
- **Database**: PDO prepared statements in 100% of queries
- **Passwords**: `password_hash` / `password_verify`
- **HTTPS required** in production (WebAuthn and iPhone Service Worker requirement)
- **`.htaccess`**: forces HTTPS and blocks direct access to `config/`, `includes/`, `sql/`, `vendor/`
- **LGPD**: recorded consents and a privacy request flow

---

## 📊 Project Summary

| Aspect | Details |
|--------|---------|
| **Type** | Scheduling PWA (worship ministry) |
| **Stack** | PHP, MySQL, Vanilla JavaScript, PWA |
| **Architecture** | Server-rendered PHP pages + JSON API + session + CSRF |
| **Roles** | 4 (Guest, Member, Admin, Dev) |
| **Schedule roles** | 9, with configurable role-stacking restrictions |
| **Base** | Derived from Gyn Conect, adapted to server-render |
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
