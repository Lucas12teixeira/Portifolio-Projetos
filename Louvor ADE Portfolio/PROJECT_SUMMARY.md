# Louvor ADE — Resumo Executivo

## Visão em Uma Página

**Nome do Projeto**: Louvor ADE — Sistema de Escalas do Ministério de Louvor
**Cliente**: Ministério de Louvor da Assembleia de Deus Expansul
**Tipo**: Progressive Web App (PWA)
**Propósito**: Substituir o controle manual de escalas (planilha/imagem) por um app instalável, offline, com validação automática de regras
**Status**: Produção

---

## 📊 Visão Rápida

| Aspecto | Detalhes |
|---------|---------|
| **Stack** | PHP, MySQL, JavaScript Vanilla, PWA |
| **Arquitetura** | Páginas PHP server-rendered + API JSON + sessão + CSRF |
| **Autenticação** | Sessão PHP + cookie de longa duração + WebAuthn (biometria) |
| **Perfis** | 4 (Convidado, Membro, Admin, Dev) |
| **Funções da escala** | 9, com restrições de acúmulo configuráveis em banco |
| **Base técnica** | Derivado do Gyn Conect, adaptado para server-render |

---

## 🎯 O Desafio

A escala do ministério era montada à mão numa planilha e distribuída como imagem. Consequências:
- Dúvida constante sobre qual era a versão vigente
- Erros de acúmulo de função no mesmo culto
- Sem confirmação de presença nem aviso de indisponibilidade
- Contatos dispersos e nenhum histórico

**Solução**: um PWA com a escala sempre atualizada, montagem assistida com validação automática das restrições, confirmação de presença, avisos, versículo da semana e painel administrativo com permissões finas.

---

## ✨ Funcionalidades Principais

1. **🔐 Acesso por perfil** — Convidado, Membro, Admin, Dev
2. **👆 Login biométrico (WebAuthn)** — Face ID, Touch ID, digital, Windows Hello
3. **📅 Escala** — semana atual e próxima, montagem por culto, template de dias
4. **⚖️ Regras de acúmulo de função** — validadas no servidor, configuráveis em banco
5. **✅ Confirmação de presença / indisponibilidade** pelo membro
6. **🔔 Web Push** — aviso quando o membro é escalado
7. **📢 Avisos e versículo da semana** editáveis pelo admin
8. **📇 Contatos** dos membros
9. **🛠 Painel Dev** — permissões finas, feature flags, logs
10. **🔒 LGPD** — consentimentos e solicitações de privacidade

---

## 🛠 Destaques Técnicos

- **Adaptação de arquitetura**: partiu do Gyn Conect (SPA + token Bearer) e foi reconstruído como **páginas PHP server-rendered com sessão** — o que exigiu adicionar **proteção CSRF** (token sincronizado) em todo o `/api`
- **Regra de negócio fora do código**: a restrição "Ministro × Back Vocal" e quaisquer outras combinações exclusivas vivem na tabela `restricoes_funcao` e são validadas em `includes/escala_regras.php` a cada atribuição — o admin cria e desativa regras pela interface
- **WebAuthn**: cerimônias de registro e login por dispositivo, com `residentKey: discouraged`, portadas do Gyn Conect
- **PWA para iPhone**: `viewport-fit=cover`, `safe-area-inset-*`, splash screens e Service Worker com `no-cache` nos arquivos críticos
- **Web Push** com `minishlink/web-push` e chaves VAPID
- **`/api` mínima**: apenas o necessário para as ceremônias biométricas e ações interativas, num app que no resto é renderizado no servidor

---

## 🧠 O Que Foi Aprendido

- Portar uma base de SPA para páginas server-rendered sem perder os padrões de segurança (e adicionando os que o novo modelo exige — CSRF)
- Modelar regras de negócio como dados configuráveis, não como constantes
- Implementar WebAuthn em um contexto de sessão PHP nativa
- Gerenciar expiração de sessão diferenciada entre mobile e desktop na mesma tabela

---

## 🎬 Pitch em 30 Segundos

> "Desenvolvi o Louvor ADE, um PWA de gestão de escalas para o ministério de louvor de uma igreja. Ele substitui a planilha manual por um app instalável e offline, com login por biometria, montagem de escala assistida e validação automática das regras de acúmulo de função — que ficam configuráveis num banco, não no código. Tem confirmação de presença, notificação push quando você é escalado, avisos, versículo da semana e um painel dev com permissões finas e conformidade com a LGPD. Reaproveitei a base do meu sistema Gyn Conect, mas reconstruí como páginas PHP server-rendered com sessão e proteção CSRF."

---

## 🔗 Navegação

- [📖 README Principal](README.md)
- [🇺🇸 English Version](README-EN.md)

---

<div align="center">

**© 2025-2026 Lucas (Erl Dev)** — *Sistema proprietário — documentação de portfólio apenas*

</div>
