# FitPersonal AI — Executive Summary

## One-Page View

**Project**: FitPersonal AI — strength-training PWA with a deterministic rule engine
**Type**: Progressive Web App (UI in Brazilian Portuguese)
**Purpose**: Give gym and home trainees a personalized, versioned workout plan, and safely refuse to generate one when health answers indicate risk
**Status**: MVP

> Health disclaimer: the app does not replace professional guidance.

---

## 🎯 Pitch

FitPersonal AI is a strength-training PWA that generates personalized workout plans with an **in-house, deterministic and tested rule engine** (same input and seed give the same plan), logs every session and shows progress from real data. It includes an opt-in community, RBAC admin tooling and LGPD-oriented privacy features. It uses no paid or third-party APIs and runs on ordinary shared PHP + MySQL hosting.

## Problem → Solution

- **Problem**: trainees without a personal trainer get no structured plan adapted to goal, equipment, time or injuries, and generic plans can be unsafe for people with pain or without clearance.
- **Solution**: a 7-step questionnaire with a safety gate that stops and refers the user to a professional when risk signals appear; otherwise a validated, versioned plan, a guided live session and progress analytics.

---

## 📊 Key Numbers

| Metric | Value |
|--------|-------|
| **Database tables** | 39 |
| **Registered routes** | 122 (10 JSON under `/api/`) |
| **Test suite** | 67 cases (30 unit + 37 integration) |
| **Exercises** | 101 |
| **Licensed illustrations** | 59 (CC BY-SA 3.0, Everkinetic via wger/Wikimedia) |
| **PHP code** | ~17k lines |
| **Third-party APIs / paid services** | None |

---

## 🛠 Stack

PHP 7.4+ (no framework, no Composer) • MySQL 5.7+/8 or MariaDB 10.4+ • Vanilla JavaScript • hand-written CSS • PWA (manifest, service worker, offline page) • WebAuthn passkeys • in-house SMTP client

---

## ⭐ Top Engineering Highlights

1. **Deterministic rule engine** with scoring, split selection, time fitting and independent validation before every save.
2. **Safety gate**: pain, injury or missing clearance stops generation and refers the user out.
3. **Versioned plans**: edits create new versions; swaps only offer compatible alternatives.
4. **Shared-hosting-first design**: in-house SMTP, WebAuthn with a CBOR reader, and a DB-backed rate limiter, with no Composer.
5. **Security details**: epoch-based global session revocation, timing-equalized login, strict CSP, EXIF-stripping image re-encode.
6. **Rule-based Coach** (not an LLM) that refuses diagnosis, drugs and supplements and refers pain reports to a professional.
7. **LGPD tooling**: JSON data export, health-consent revocation and account deletion.
8. **Test suite with 67 cases**, including IDOR, CSRF, XSS and SQL injection scenarios, with a guard that blocks integration runs on non-`_test` databases.

---

## 🔗 Links

- [📖 Main README (Português)](README.md)
- [🇺🇸 English README](README-EN.md)
- 🌐 [Portfolio](https://github.com/Lucas12teixeira/Portifolio-Projetos) • 💼 [LinkedIn](https://www.linkedin.com/in/lucas-lima-10218529a/) • 🐙 [GitHub](https://github.com/Lucas12teixeira) • 🏢 [Erl Dev](https://www.erldev.com.br)

---

<div align="center">

**© 2024-2026 Lucas (Erl Dev)** — *Proprietary system — portfolio documentation only*

</div>
