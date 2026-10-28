# ErlDev Website — Executive Summary

## One-Page Overview

**Project**: ErlDev company website and product showcase
**Owner**: ErlDev, the developer's own studio
**Type**: Lightweight marketing site (PHP 8, vanilla JS/CSS)
**Purpose**: Present the studio's services, act as the hub linking to its products, and capture inbound contacts
**Status**: Live at [www.erldev.com.br](https://www.erldev.com.br)

---

## Pitch

A dependency-free PHP 8 website with no build step that shows craft where it counts: a hand-written SMTP client with STARTTLS, a config-driven product catalogue rendered by one shared template, flash-free theme switching, an animated canvas hero, accessibility basics done properly, full SEO metadata and aggressive caching with `filemtime` cache-busting.

---

## Problem & Solution

**Problem**: a studio selling custom software needs a fast, credible website that is cheap to host and easy to extend, without the maintenance burden of frameworks and build pipelines.

**Solution**: a small PHP 8 site on shared Apache hosting. Products are entries in a config array rendered by a single template; the contact form posts to one endpoint that validates input and sends mail over a custom SMTP client.

---

## Key Numbers

| Aspect | Details |
|--------|---------|
| **Code** | ~4.1k lines: PHP 1,741, JS 323, CSS 2,052 |
| **Code files** | 20 (16 PHP, 1 JS, 3 CSS) |
| **Public pages** | 6 (home, 4 product pages, 404) |
| **API endpoints** | 1 (contact form) |
| **Database tables** | 0 |
| **Dependencies** | 0, no build step |

---

## Stack

PHP 8 · HTML5 · CSS3 custom properties · vanilla JavaScript · Apache (`.htaccess`: rewrite, expires, deflate, headers) · Manrope + JetBrains Mono · inline SVG icons

---

## Engineering Highlights

- **Custom SMTP client** over sockets with multi-line reply parsing and STARTTLS, switchable with `mail()` by config
- **One template, four product pages**: adding a product is one array entry plus a 19-line wrapper
- **Flash-free theme switching**: inline pre-paint script plus `MutationObserver` for canvas colours
- **Canvas hero** with compass arcs and a sweep line, disabled under `prefers-reduced-motion`
- **Accessibility**: skip link, ARIA labels and states, `:focus-visible`, Escape-to-close menu, `role="status"` feedback
- **Caching**: one-year `immutable` assets versioned by `filemtime`
- **SEO**: Open Graph, Twitter Cards, canonical URL, sitemap, robots, manifest
- **Contact endpoint**: honeypot, length limits, email validation and per-session throttling

---

## Links

- 🌐 Live site: [www.erldev.com.br](https://www.erldev.com.br)
- 📖 [Main README (PT-BR)](README.md) • [English README](README-EN.md)
- 🚀 Products: [American Teens](../American%20Teens%20Portifolio/) • [GynConect](../GynConect%20Portfolio/) • [Louvor ADE](../Louvor%20ADE%20Portfolio/) • [ErlDev Gestão](../ErlDev%20Gestao%20Portfolio/)
- 💼 [LinkedIn](https://www.linkedin.com/in/lucas-lima-10218529a/) • 🐙 [GitHub](https://github.com/Lucas12teixeira) • 🌐 [Portfolio](https://github.com/Lucas12teixeira/Portifolio-Projetos)

---

<div align="center">

**© 2024-2026 Lucas (Erl Dev)** — *Proprietary system — portfolio documentation only*

</div>
