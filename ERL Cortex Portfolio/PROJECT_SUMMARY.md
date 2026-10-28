# ERL Cortex — Executive Summary

## One-Page Overview

**Project**: ERL Cortex — AI second brain and company-management PWA
**Type**: Progressive Web App powered by Claude with tool use (voice + text)
**Users**: Single owner/operator account (not multi-tenant)
**Status**: In active development

**Pitch**: ERL Cortex is a voice and text AI assistant that captures ideas, notes, tasks and reminders, answers questions from the user's own notes with semantic search and cited sources, and also runs the operations and billing of a small software company — clients, recurring Pix billing, servers, domains and expenses. Claude acts through 37 tools, and every financial or destructive action requires explicit human confirmation.

---

## 🎯 Problem & Solution

**Problem**: Personal knowledge, tasks and reminders live in one set of apps while company operations and billing live in another, with no source-backed way to query either — and handing an AI the power to move money without a safety gate is not acceptable.

**Solution**: One installable PWA where an LLM assistant retrieves knowledge (hybrid semantic search), executes actions through tool use with a human confirmation gate and an undo ledger, and is backed by a full management module, cron automations, offline capture and strong authentication.

---

## 📊 Key Numbers

| Aspect | Details |
|--------|---------|
| **Assistant tools** | 37 (with confirmation gate for financial/destructive actions) |
| **Code (tracked files)** | ~19.3k lines PHP, ~8.4k JavaScript, 850 SQL |
| **Database** | 48 tables, 14 migrations |
| **API endpoints** | 46 |
| **Tests** | 209 unit checks + 82 and 61 integration checks + 3 Node test modules |
| **Dependencies** | None (no Composer, no npm) |
| **Documentation** | 14 docs (deployment, security, migration) |

---

## 🛠 Stack

PHP 7.4 • MySQL/MariaDB • Vanilla JavaScript (ES modules) • Claude Messages API (tool use, SSE streaming) • Ollama / OpenAI-compatible fallback • Voyage embeddings • Whisper (Groq/OpenAI) • Web Speech • Service Worker + IndexedDB • Web Push (VAPID) • Mercado Pago (Pix) • GitHub Actions (scheduler) • WebAuthn / TOTP

---

## ⭐ Top Engineering Highlights

1. **Money-safe tool use** — financial and destructive tools become pending actions that run only after user confirmation; undo ledger, 30-day trash and audit records on writes
2. **Single LLM gateway with fallback chain** — one payload format, translated to Ollama/OpenAI-compatible servers; fails over only on transient errors and caps paid Claude fallback by a daily token limit
3. **Semantic search with graceful degradation** — JSON embeddings in MySQL, PHP cosine similarity, FULLTEXT/LIKE fallback, private notes never sent to the external provider; answers cite sources
4. **Voice pipeline** — Web Speech on Chrome/Android, server-side Whisper transcription for iPhone, neural TTS, voice commands
5. **Obsidian-style notes** — wikilinks, backlinks, tags, relation graph, vault export, link updates on rename
6. **Zero dependencies by choice** — Web Push VAPID, AWS SigV4 signing and WebAuthn verification implemented in-house
7. **Security depth** — TOTP 2FA, passkeys, CSRF, strict CSP, HMAC-verified webhooks, login rate limiting
8. **Operational pragmatism** — GitHub Actions as a scheduler for shared hosting; idempotent migrator from the predecessor system with dry-run mode; offline capture queue; usage/cost panel

---

## 🔗 Links

- [📖 Main README (Português)](README.md)
- [🇺🇸 English README](README-EN.md)
- 🌐 Portfolio: [github.com/Lucas12teixeira/Portifolio-Projetos](https://github.com/Lucas12teixeira/Portifolio-Projetos)
- 💼 LinkedIn: [www.linkedin.com/in/lucas-lima-10218529a/](https://www.linkedin.com/in/lucas-lima-10218529a/)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)
- 🏢 Company site: [www.erldev.com.br](https://www.erldev.com.br)

---

<div align="center">

**© 2024-2026 Lucas (Erl Dev)** — *Proprietary system — portfolio documentation only*

</div>
