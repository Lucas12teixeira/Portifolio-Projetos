# ErlDev Gestão — Resumo Executivo

## Visão em Uma Página

**Nome do Projeto**: ErlDev Gestão — ERP Interno
**Cliente**: ErlDev (uso interno)
**Tipo**: Sistema Web + PWA
**Propósito**: Centralizar clientes, sistemas entregues, pagamentos e assinaturas — com cobrança Pix, monitoramento e sincronização automática com os sistemas dos clientes
**Status**: Produção

---

## 📊 Visão Rápida

| Aspecto | Detalhes |
|---------|---------|
| **Stack** | PHP 8 puro (MVC), MySQL/PDO, JavaScript Vanilla, PWA |
| **Hospedagem** | KingHost compartilhada — sem Composer/Node no servidor |
| **Autenticação** | Sessão PHP nativa + WebAuthn (biometria) |
| **Pagamentos** | Pix via Mercado Pago, sem SDK |
| **Notificações** | Web Push nativo (RFC 8291/8292) em PHP puro + OpenSSL |
| **Perfis** | admin, colaborador |

---

## 🎯 O Desafio

Com vários sistemas em produção para clientes diferentes, o controle em planilha não escalava: pagamentos e assinaturas geridos à mão, sem visão de uptime dos sistemas, sem visibilidade da saúde do servidor e com os dados de cobrança do GynConect recadastrados manualmente.

**Solução**: um painel único que cadastra clientes, projetos e pagamentos, gera cobrança Pix, monitora uptime e servidor, suspende sistemas por inadimplência (com push em tempo real) e puxa automaticamente assinatura e faturas dos sistemas que expõem esses dados por API.

---

## ✨ Funcionalidades Principais

1. **👥 Clientes** — cadastro com notas e histórico de atividade
2. **🗂 Projetos** — cada site/sistema entregue, com acesso rápido e status
3. **💰 Pagamentos e Financeiro** — controle por cliente/projeto, despesas, exportação
4. **💳 Cobrança Pix (Mercado Pago)** — QR Code + copia-e-cola, webhook opcional
5. **⏸ Suspensão automática por atraso** — indicador interno + Web Push em tempo real
6. **📡 Monitoramento** — saúde do servidor KingHost + uptime de cada sistema (cURL paralelo)
7. **🔗 Assinaturas** — sincronização servidor-a-servidor com os sistemas dos clientes
8. **🤖 Assistente** — consultas rápidas sobre os dados do sistema
9. **👆 Login biométrico (WebAuthn)** — por dispositivo
10. **📱 PWA** — instalável, offline básico, ícones gerados no navegador

---

## 🛠 Destaques Técnicos

- **MVC em PHP 8 puro** rodando em hospedagem compartilhada, com `vendor/` versionado — zero build no servidor
- **Integração servidor-a-servidor**: `IntegracaoSync` cria/atualiza cliente + projeto + pagamentos a partir de endpoints token-protegidos dos sistemas dos clientes; `AssinaturaRemota` envia ações de controle (suspender/reativar/marcar pago). Deduplicação por `fonte_integracao` + `id_externo`
- **Web Push nativo** (RFC 8291/8292) implementado só com OpenSSL — sem SDK
- **Pseudo-cron oportunista**: inadimplência, monitoramento e sync rodam no `Auth::requireLogin()` com throttle de 5 min por sessão — decisão explícita de não depender de cron da hospedagem
- **Monitoramento** que coleta métricas reais do servidor (PHP, MySQL, memória, disco por plano, tamanho do banco) e checa uptime de N sistemas em paralelo
- **WebAuthn** portado do GynConect e adaptado para sessão PHP nativa
- **Gerador de ícones PWA** que roda 100% no `<canvas>` do navegador, sem GD/Node

---

## 🧠 O Que Foi Aprendido

- Projetar integração entre dois sistemas próprios (este e o GynConect) com contrato claro: endpoint de leitura + endpoint de controle com ações fechadas
- Implementar Web Push criptografado do zero, sem SDK
- Trabalhar dentro das limitações de hospedagem compartilhada (sem cron, sem Composer no servidor) sem abrir mão de segurança
- Coletar e apresentar métricas de saúde de servidor de forma útil para operação

---

## 🎬 Pitch em 30 Segundos

> "Desenvolvi o ErlDev Gestão, o ERP interno da minha operação: ele controla clientes, os sistemas que entreguei, pagamentos e assinaturas. Gera cobrança Pix pelo Mercado Pago, monitora o uptime de cada sistema e a saúde do servidor de hospedagem, e suspende sistemas por inadimplência com notificação push em tempo real. O diferencial é a integração servidor-a-servidor: ele puxa automaticamente a assinatura e as faturas de sistemas como o GynConect e consegue suspender ou reativar remotamente. Tudo em PHP 8 puro (MVC), rodando em hospedagem compartilhada, com Web Push implementado sem SDK."

---

## 🔗 Navegação

- [📖 README Principal](README.md)
- [🇺🇸 English Version](README-EN.md)

---

<div align="center">

**© 2025-2026 Lucas (Erl Dev)** — *Sistema proprietário — documentação de portfólio apenas*

</div>
