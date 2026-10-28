# Lucas Lima (Erl Dev) — Full-Stack Developer · Applied AI · PWAs

<div align="center">

**I design, build and run production web systems end to end: offline-first PWAs, payment and billing flows, biometric auth and LLM agents with tool use.**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/lucas-lima-10218529a/)
[![Email](https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:lucas12teixeira@gmail.com)
[![Website](https://img.shields.io/badge/erldev.com.br-0A0A0A?style=for-the-badge&logo=google-chrome&logoColor=white)](https://www.erldev.com.br)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/Lucas12teixeira)

🇺🇸 English · [🇧🇷 Português](README-PT.md)

</div>

---

## ⚡ At a Glance

| | |
|---|---|
| 🚀 **7 projects**, 5 live in production | Real users: sales teams, a church ministry, a youth community, my own company |
| 💻 **~165,000 lines** of hand-written code | PHP, JavaScript, CSS and SQL — counted from tracked source, excluding `vendor/` and libraries |
| 🔁 **280+ commits** in 2025–2026 | Shipped, maintained and iterated in production, not just prototyped |
| 🤖 **Applied AI** | Claude agent with 37 tools, human-in-the-loop confirmation, semantic search with cited sources |
| 🔐 **Security by default** | WebAuthn/passkeys, TOTP 2FA, CSRF, RBAC, HMAC-verified webhooks, CSP/HSTS |
| 📴 **Offline-first** | IndexedDB outboxes, idempotent sync with retry, installable PWAs on Android and iOS |

> This repository is **portfolio documentation only** — source code is proprietary. Each folder documents one project in English and Portuguese, with architecture, security and the engineering decisions behind it.

---

## ⭐ Featured Projects

### 🤖 [ERL Cortex](ERL%20Cortex%20Portfolio/README-EN.md) — AI second brain + company management
<img src="ERL%20Cortex%20Portfolio/assets/icons/icon.png" width="64" align="right" alt="ERL Cortex">

Voice and text assistant built on **Claude with tool use** that also runs the operations and billing of a software company.

- **37 tools** exposed to the model; financial and destructive actions require **human confirmation** and can be undone
- **LLM gateway** with a fallback chain (Claude → Ollama / OpenAI-compatible) and a daily token cap
- **Semantic search** (Voyage embeddings) with source-cited answers, graceful FULLTEXT fallback
- Voice pipeline (Web Speech + server-side Whisper for iPhone), Obsidian-style notes, Pix billing, Web Push
- **Zero dependencies**: Web Push (VAPID), AWS SigV4 and WebAuthn verification written in-house · **350+ automated checks**

`PHP` `Claude API` `Embeddings` `PWA` `MySQL` · 🛠 In active development

---

### 🏢 [GynConect](GynConect%20Portfolio/README-EN.md) — Sales & consignment management for field teams
<img src="GynConect%20Portfolio/assets/icons/icon.png" width="64" align="right" alt="GynConect">

Production SPA + REST API + PWA used daily by external sales teams. Current line **v5.7**.

- **Offline sales** with IndexedDB outbox, idempotency keys, automatic retry and a pending-sync screen
- **Recurring billing** with Asaas and Mercado Pago (Pix, boleto), webhooks and a delinquency cron
- **Biometric login** (WebAuthn) and native **Web Push** (VAPID / RFC 8291) without third-party push services
- Server-to-server integration with an internal ERP; RBAC with 4 levels; native PDF/Excel export

`PHP` `REST API` `Vanilla JS SPA` `PWA` `MySQL` · ✅ Production

---

### 🏋️ [FitPersonal AI](FitPersonal%20AI%20Portfolio/README-EN.md) — Personalized strength-training PWA
<img src="FitPersonal%20AI%20Portfolio/assets/icons/icon.png" width="64" align="right" alt="FitPersonal AI">

Generates personalized workout plans with an **in-house deterministic rule engine** — no paid AI APIs — and tracks every session.

- **Safety gate**: health-risk answers stop plan generation and refer the user to a professional
- **Versioned plans** (never overwritten) and compatible exercise swaps
- 101 exercises, 39 tables, **67 test cases** covering IDOR, CSRF, XSS and SQL injection
- Built for shared hosting: in-house rate limiter, SMTP client and WebAuthn (CBOR) — no framework

`PHP` `Rule engine` `PWA` `MySQL` · 🧪 MVP

---

### More projects

| Project | What it is | Highlights | Status |
|---|---|---|---|
| 🌟 [**American Teens**](American%20Teens%20Portifolio/README.md) | Community platform for a youth group | Real-time chat, events, full Bible integration, JWT auth, offline PWA | ✅ [Live](https://americateens.erldev.com.br) |
| 🗂 [**ErlDev Gestão**](ErlDev%20Gestao%20Portfolio/README-EN.md) | Internal ERP for clients, delivered systems and payments | Server-to-server sync with client systems, Pix via Mercado Pago, auto-suspension on non-payment, uptime monitoring | ✅ Production |
| ⛪ [**Louvor ADE**](Louvor%20ADE%20Portfolio/README-EN.md) | Worship-team scheduling PWA | Database-driven role-conflict rules, WebAuthn, CSRF, Web Push, feature flags, LGPD tools | ✅ Production |
| 🌐 [**ErlDev Site**](ErlDev%20Site%20Portfolio/README-EN.md) | My company's website | Zero-dependency PHP 8, hand-written SMTP client, config-driven catalogue, accessible and SEO-ready | ✅ [Live](https://www.erldev.com.br) |

---

## 🛠 Tech Stack

| Area | Technologies |
|---|---|
| **Backend** | PHP 7.4 / 8.x (OOP, MVC), REST APIs, PDO, MySQL / MariaDB, versioned migrations |
| **Frontend** | JavaScript (ES6+, ES modules), SPA routing, HTML5, CSS3, responsive and accessible UI |
| **AI** | Claude Messages API with tool use and streaming (SSE), embeddings and semantic search, Whisper transcription, multi-provider LLM gateway |
| **PWA / Mobile** | Service Workers, IndexedDB, background sync, Web Push (VAPID), Web Share Target, TWA/APK |
| **Security** | WebAuthn / passkeys, TOTP 2FA, CSRF, RBAC, rate limiting, HMAC webhooks, CSP / HSTS, LGPD privacy flows |
| **Payments** | Mercado Pago and Asaas (Pix, boleto), webhooks, recurring billing |
| **DevOps** | Git, GitHub Actions (scheduled jobs), Apache / `.htaccess`, shared hosting, uptime monitoring, S3/R2 backups |

---

## 🧭 How I Work

- **Ship to real users.** Most of these systems run in production and are maintained after launch — offline edge cases, payment webhooks and iOS quirks included.
- **Own the whole stack.** Database design, API, frontend, deployment, monitoring and documentation.
- **Few dependencies, on purpose.** On shared hosting I write what is needed (Web Push, SMTP, WebAuthn, request signing) instead of pulling in heavy libraries.
- **Safety around automation.** AI agents and payment flows get confirmation steps, audit trails and idempotency.

---

## 📬 Let's Talk

Open to **full-time roles**, **freelance projects** and **technical collaborations**.

<div align="center">

[![Email](https://img.shields.io/badge/lucas12teixeira@gmail.com-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:lucas12teixeira@gmail.com)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/lucas-lima-10218529a/)

</div>

---

<div align="center">

© 2024-2026 Lucas Lima (Erl Dev). Documentation in this repository is licensed under CC BY-NC-ND 4.0; source code of the projects is proprietary and not included.

*Last updated: October 2026*

</div>
