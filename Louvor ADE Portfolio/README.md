# Louvor ADE — Sistema de Escalas do Ministério de Louvor

<div align="center">

![Louvor ADE Logo](assets/icons/icon.png)

**PWA de Gestão de Escalas para o Ministério de Louvor da AD Expansul**

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![WebAuthn](https://img.shields.io/badge/WebAuthn-Biometria-2ECC71?style=for-the-badge&logo=fidoalliance&logoColor=white)](https://webauthn.io)
[![Status](https://img.shields.io/badge/Status-Produção-success?style=for-the-badge)](.)

[📋 Funcionalidades](#-funcionalidades) • [🛠 Stack](#-stack-técnico) • [🏗 Arquitetura](#-arquitetura) • [🔒 Segurança](#-segurança)

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

**Louvor ADE** é um Progressive Web App de gestão de escalas desenvolvido para o Ministério de Louvor da **Assembleia de Deus Expansul**. Substitui o controle manual em planilha/imagem por um aplicativo instalável no celular, que funciona offline, com login por biometria e validação automática das regras de acúmulo de função na escala.

### O Problema

A escala do ministério era montada à mão numa planilha e distribuída como imagem no grupo. Isso trazia problemas recorrentes:
- Ninguém sabia com certeza qual era a versão mais recente
- Erros de acúmulo de função (ex.: a mesma pessoa como Ministro e Back Vocal no mesmo culto)
- Sem forma prática de confirmar presença ou avisar indisponibilidade
- Contatos dos membros espalhados
- Nenhum registro histórico das escalas passadas

### A Solução

Um app instalável, com a escala sempre atualizada, montagem assistida com **validação automática das restrições de função**, confirmação de presença pelos membros, avisos e versículo da semana, e um painel administrativo com controle fino de permissões.

---

## 📋 Funcionalidades

### 🔐 Acesso e Perfis

Login tradicional (e-mail/senha) **ou por biometria** (Face ID, Touch ID, digital Android, Windows Hello), com opção "lembrar neste dispositivo". RBAC com quatro perfis:

| Perfil | Acesso |
|--------|--------|
| **Convidado** | Leitura da escala atual e da próxima, sem dados de contato |
| **Membro** | Escala completa, contatos, confirmar presença/indisponibilidade, biometria |
| **Admin** | + Montar escala, cadastrar membros, editar avisos, versículo e restrições |
| **Dev** | + Promover/rebaixar usuários, permissões finas, logs, ativar/desativar funcionalidades |

- Primeiro acesso com troca obrigatória de senha
- Recuperação de senha por e-mail
- Gerenciamento de dispositivos de biometria por usuário

---

### 📅 Escala

- Visualização da **semana atual e da próxima**
- Montagem da escala pelo admin, com atribuição de funções por culto
- **Template de dias de culto** e disponibilidade de dias por membro
- **Validação automática das regras de acúmulo de função** na hora de escalar
- Confirmação de presença / registro de indisponibilidade pelo membro
- Notificação **Web Push** quando o membro é escalado

---

### ⚖️ Regras de Acúmulo de Função

O ministério trabalha com 9 funções (Ministro, Back Vocal, Violão, Guitarra, Contrabaixo, Bateria, Teclado, Datashow, Técnico de Som).

- A restrição **Ministro × Back Vocal mutuamente exclusivos** **não está fixa no código** — vive em uma tabela (`restricoes_funcao`) e é validada no servidor a cada atribuição
- O admin pode **desativar essa regra** ou **criar novas combinações exclusivas** em *Admin → Avisos → Restrições de acúmulo de função*

---

### 📢 Avisos, Versículo e Contatos

- Mural de avisos do ministério
- Versículo da semana editável pelo admin
- Lista de contatos dos membros (visível a partir do perfil Membro)

---

### 🛠 Painel Dev

- Painel com visão geral
- Promoção/rebaixamento de usuários e **permissões finas**
- **Feature flags** — ativar/desativar funcionalidades sem deploy
- Logs de atividade
- Ferramentas de privacidade (LGPD)

---

### 🔒 Privacidade (LGPD)

- Registro de consentimentos
- Fluxo de solicitações de privacidade (acesso / exclusão de dados)
- Páginas de Termos de Uso e Política de Privacidade

---

## 🛠 Stack Técnico

| Camada | Tecnologia |
|--------|-----------|
| **Frontend** | Páginas server-renderizadas (PHP) + JavaScript Vanilla + CSS3 |
| **Backend** | PHP 7.4+ com PDO/MySQL |
| **Autenticação** | Sessão PHP (`$_SESSION`) + cookie de longa duração + WebAuthn |
| **Mobile** | PWA com Service Worker, manifest e splash screens iOS |
| **API** | Endpoints JSON (`/api`) para cerimônias WebAuthn e ações interativas |
| **Bibliotecas** | `web-auth/webauthn-lib`, `nyholm/psr7`, `phpmailer/phpmailer`, `minishlink/web-push` |
| **Notificações** | Web Push (VAPID) |
| **Segurança** | CSRF token sincronizado, PDO com prepared statements, `password_hash` |

---

## 🏗 Arquitetura

Baseado no projeto **Gyn Conect** (PHP + PDO + JS vanilla + PWA), **adaptado** para páginas server-renderizadas com sessão PHP em vez do SPA + token Bearer do Gyn Conect.

```
/louvor-ade
  /assets        css, js, ícones, manifest
  /config        conexão com banco, constantes (SMTP, VAPID)
  /includes      bootstrap, auth, RBAC, CSRF, helpers, regras de negócio da escala
  /api           endpoints JSON (auth, escala, admin, dev) — chamados via fetch()
  /pages
    /login       login, cadastro, convidado, recuperar/redefinir senha, termos, conta
    /escala      visualização da escala (semana atual + próxima)
    /admin       montar escala, membros, contatos, dias de culto, avisos, restrições, versículo
    /dev         painel, permissões, logs, funcionalidades, privacidade
  /sql           schema.sql
  manifest.json
  service-worker.js
  index.php
```

### Diferenças em relação ao Gyn Conect

| Aspecto | Gyn Conect | Louvor ADE |
|---------|-----------|------------|
| Frontend | SPA (`index.html` + roteador JS) | Páginas PHP renderizadas no servidor |
| Autenticação | Token Bearer em `localStorage` | Sessão PHP (`$_SESSION`) + cookie longo |
| CSRF | Não usa (header `Authorization` protege) | **Token CSRF sincronizado** em todo `/api` |
| Dispositivos | Tabela `sessoes` | Mesma tabela `sessoes` (expiração diferenciada mobile/desktop) |
| WebAuthn | `residentKey: discouraged` | Idêntico |

A pasta `/api` foi necessária mesmo num app de páginas PHP: o WebAuthn e as ações interativas (confirmar presença, montar escala) precisam de endpoints JSON chamados via `fetch()`.

### Regras de negócio da escala

A validação da restrição de função vive em `includes/escala_regras.php`, chamada por `api/admin/escala_atribuir.php` sempre que alguém é escalado — lendo as combinações exclusivas da tabela `restricoes_funcao`, não de constantes no código.

---

## 🔒 Segurança

- **Autenticação**: sessão PHP + cookie de longa duração ("lembrar neste dispositivo") apoiado na tabela `sessoes`
- **Biometria**: WebAuthn — chave privada nunca sai do aparelho; só a chave pública é salva
- **CSRF**: token sincronizado validado em todo endpoint de escrita
- **Banco**: PDO com prepared statements em 100% das queries
- **Senhas**: `password_hash` / `password_verify`
- **HTTPS obrigatório** em produção (requisito do WebAuthn e do Service Worker no iPhone)
- **`.htaccess`**: força HTTPS e bloqueia acesso direto a `config/`, `includes/`, `sql/`, `vendor/`
- **LGPD**: consentimentos registrados e fluxo de solicitações de privacidade

---

## 📊 Resumo do Projeto

| Aspecto | Detalhes |
|---------|---------|
| **Tipo** | PWA de gestão de escalas (ministério de louvor) |
| **Stack** | PHP, MySQL, JavaScript Vanilla, PWA |
| **Arquitetura** | Páginas PHP server-rendered + API JSON + sessão + CSRF |
| **Perfis** | 4 (Convidado, Membro, Admin, Dev) |
| **Funções da escala** | 9, com restrições de acúmulo configuráveis |
| **Base** | Derivado do Gyn Conect, adaptado para server-render |
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
