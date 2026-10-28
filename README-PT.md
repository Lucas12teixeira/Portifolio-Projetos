# Lucas Lima (Erl Dev) — Desenvolvedor Full-Stack · IA Aplicada · PWAs

<div align="center">

**Projeto, construo e opero sistemas web em produção de ponta a ponta: PWAs offline-first, cobrança e pagamentos, autenticação biométrica e agentes de IA com tool use.**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/lucas-lima-10218529a/)
[![Email](https://img.shields.io/badge/Email-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:lucas12teixeira@gmail.com)
[![Site](https://img.shields.io/badge/erldev.com.br-0A0A0A?style=for-the-badge&logo=google-chrome&logoColor=white)](https://www.erldev.com.br)
[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/Lucas12teixeira)

[🇺🇸 English](README.md) · 🇧🇷 Português

</div>

---

## ⚡ Em Resumo

| | |
|---|---|
| 🚀 **7 projetos**, 5 em produção | Usuários reais: equipes de vendas, um ministério de louvor, uma comunidade jovem, minha própria empresa |
| 💻 **~165.000 linhas** de código próprio | PHP, JavaScript, CSS e SQL — contadas no código versionado, sem `vendor/` e bibliotecas |
| 🔁 **280+ commits** em 2025–2026 | Entregues, mantidos e evoluídos em produção, não só protótipos |
| 🤖 **IA aplicada** | Agente Claude com 37 ferramentas, confirmação humana, busca semântica com fontes citadas |
| 🔐 **Segurança por padrão** | WebAuthn/passkeys, 2FA TOTP, CSRF, RBAC, webhooks com HMAC, CSP/HSTS |
| 📴 **Offline-first** | Filas no IndexedDB, sincronização idempotente com retry, PWAs instaláveis no Android e iOS |

> Este repositório contém **apenas documentação de portfólio** — o código-fonte é proprietário. Cada pasta documenta um projeto em português e inglês, com arquitetura, segurança e as decisões de engenharia por trás dele.

---

## ⭐ Projetos em Destaque

### 🤖 [ERL Cortex](ERL%20Cortex%20Portfolio/README.md) — Segundo cérebro com IA + gestão da empresa
<img src="ERL%20Cortex%20Portfolio/assets/icons/icon.png" width="64" align="right" alt="ERL Cortex">

Assistente por voz e texto construído sobre o **Claude com tool use**, que também é o sistema de operação e cobrança de uma empresa de software.

- **37 ferramentas** expostas ao modelo; ações financeiras e destrutivas exigem **confirmação humana** e podem ser desfeitas
- **Gateway de LLM** com cadeia de fallback (Claude → Ollama / compatível com OpenAI) e teto diário de tokens
- **Busca semântica** (embeddings Voyage) com respostas citando as fontes e fallback para FULLTEXT
- Voz (Web Speech + Whisper no servidor para iPhone), notas estilo Obsidian, cobrança Pix, Web Push
- **Zero dependências**: Web Push (VAPID), assinatura AWS SigV4 e verificação WebAuthn feitas à mão · **350+ verificações automatizadas**

`PHP` `Claude API` `Embeddings` `PWA` `MySQL` · 🛠 Em desenvolvimento ativo

---

### 🏢 [GynConect](GynConect%20Portfolio/README.md) — Gestão comercial e consignação para equipes externas
<img src="GynConect%20Portfolio/assets/icons/icon.png" width="64" align="right" alt="GynConect">

SPA + API REST + PWA em produção, usado diariamente por equipes de vendas externas. Linha atual **v5.7**.

- **Vendas offline** com fila no IndexedDB, chaves de idempotência, retry automático e tela de pendências
- **Cobrança recorrente** com Asaas e Mercado Pago (Pix, boleto), webhooks e cron de inadimplência
- **Login biométrico** (WebAuthn) e **Web Push** nativo (VAPID / RFC 8291) sem serviços de terceiros
- Integração servidor-a-servidor com o ERP interno; RBAC com 4 níveis; exportação nativa de PDF e Excel

`PHP` `API REST` `SPA em JS puro` `PWA` `MySQL` · ✅ Produção

---

### 🏋️ [FitPersonal AI](FitPersonal%20AI%20Portfolio/README.md) — PWA de treino de musculação personalizado
<img src="FitPersonal%20AI%20Portfolio/assets/icons/icon.png" width="64" align="right" alt="FitPersonal AI">

Gera planos de treino personalizados com um **motor de regras próprio e determinístico** — sem APIs de IA pagas — e registra cada sessão.

- **Barreira de segurança**: respostas de risco à saúde interrompem a geração e orientam a buscar um profissional
- **Planos versionados** (nunca sobrescritos) e troca por exercícios compatíveis
- 101 exercícios, 39 tabelas, **67 casos de teste** cobrindo IDOR, CSRF, XSS e SQL injection
- Feito para hospedagem compartilhada: rate limiter, cliente SMTP e WebAuthn (CBOR) próprios — sem framework

`PHP` `Motor de regras` `PWA` `MySQL` · 🧪 MVP

---

### Outros projetos

| Projeto | O que é | Destaques | Status |
|---|---|---|---|
| 🌟 [**American Teens**](American%20Teens%20Portifolio/README-PT.md) | Plataforma de comunidade para um grupo jovem | Chat em tempo real, eventos, Bíblia completa, autenticação JWT, PWA offline | ✅ [No ar](https://americateens.erldev.com.br) |
| 🗂 [**ErlDev Gestão**](ErlDev%20Gestao%20Portfolio/README.md) | ERP interno de clientes, sistemas entregues e pagamentos | Sincronização servidor-a-servidor com os sistemas dos clientes, Pix via Mercado Pago, suspensão automática por inadimplência, monitoramento de uptime | ✅ Produção |
| ⛪ [**Louvor ADE**](Louvor%20ADE%20Portfolio/README.md) | PWA de escalas do ministério de louvor | Regras de acúmulo de função no banco, WebAuthn, CSRF, Web Push, feature flags, ferramentas LGPD | ✅ Produção |
| 🌐 [**Site ErlDev**](ErlDev%20Site%20Portfolio/README.md) | Site da minha empresa | PHP 8 sem dependências, cliente SMTP próprio, catálogo dirigido por configuração, acessível e otimizado para SEO | ✅ [No ar](https://www.erldev.com.br) |

---

## 🛠 Tecnologias

| Área | Tecnologias |
|---|---|
| **Backend** | PHP 7.4 / 8.x (OOP, MVC), APIs REST, PDO, MySQL / MariaDB, migrações versionadas |
| **Frontend** | JavaScript (ES6+, ES modules), SPA, HTML5, CSS3, interface responsiva e acessível |
| **IA** | API Messages do Claude com tool use e streaming (SSE), embeddings e busca semântica, transcrição Whisper, gateway multi-provedor |
| **PWA / Mobile** | Service Workers, IndexedDB, sincronização em segundo plano, Web Push (VAPID), Web Share Target, TWA/APK |
| **Segurança** | WebAuthn / passkeys, 2FA TOTP, CSRF, RBAC, rate limiting, webhooks com HMAC, CSP / HSTS, fluxos de privacidade LGPD |
| **Pagamentos** | Mercado Pago e Asaas (Pix, boleto), webhooks, cobrança recorrente |
| **DevOps** | Git, GitHub Actions (tarefas agendadas), Apache / `.htaccess`, hospedagem compartilhada, monitoramento de uptime, backups S3/R2 |

---

## 🧭 Como Eu Trabalho

- **Entrego para usuários reais.** A maior parte destes sistemas roda em produção e é mantida depois do lançamento — incluindo casos offline, webhooks de pagamento e particularidades do iOS.
- **Cuido da stack inteira.** Modelagem de banco, API, frontend, implantação, monitoramento e documentação.
- **Poucas dependências, de propósito.** Em hospedagem compartilhada, escrevo o necessário (Web Push, SMTP, WebAuthn, assinatura de requisições) em vez de puxar bibliotecas pesadas.
- **Segurança em volta da automação.** Agentes de IA e fluxos de pagamento têm etapas de confirmação, trilha de auditoria e idempotência.

---

## 📬 Vamos Conversar

Aberto a **vagas full-time**, **projetos freelance** e **colaborações técnicas**.

<div align="center">

[![Email](https://img.shields.io/badge/lucas12teixeira@gmail.com-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:lucas12teixeira@gmail.com)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/lucas-lima-10218529a/)

</div>

---

<div align="center">

© 2024-2026 Lucas Lima (Erl Dev). A documentação deste repositório está sob licença CC BY-NC-ND 4.0; o código-fonte dos projetos é proprietário e não está incluído.

*Última atualização: outubro de 2026*

</div>
