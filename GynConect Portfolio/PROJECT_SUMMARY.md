# GynConect — Resumo Executivo

## Visão em Uma Página

**Nome do Projeto**: GynConect — Sistema de Gestão Comercial
**Tipo**: Sistema Web + Progressive Web App (PWA)
**Propósito**: Gestão comercial para distribuidores com equipe de vendas externa e modelo de consignação
**Status**: Produção — linha de versão 5.x

---

## 📊 Visão Rápida

| Aspecto | Detalhes |
|---------|---------|
| **Stack** | PHP, MySQL, JavaScript Vanilla, CSS3 |
| **Arquitetura** | REST API + SPA + integrações servidor-a-servidor |
| **Segurança** | Token auth, WebAuthn, PDO, RBAC com 4 níveis + permissões finas |
| **Módulos** | 20+ módulos integrados |
| **Pagamentos** | Asaas + Mercado Pago (Pix, boleto, cartão) com webhooks |
| **Offline** | IndexedDB (cache + outbox) com Sync Manager FIFO |
| **Exportações** | PDF e Excel nativos em PHP |
| **Mobile** | PWA com auto-update e suporte offline via Service Worker |

---

## 🎯 O Desafio

**Problema**: Distribuidores com equipes externas enfrentavam:
- Controle manual e descentralizado de estoque por vendedor
- Rastreamento impreciso de itens em consignação
- Ausência de visibilidade gerencial em tempo real
- Cálculo de comissões sujeito a erros
- Sem assinatura digital nos termos de consignação
- Priorização subjetiva de quais clientes visitar
- Cobrança da mensalidade do próprio sistema feita manualmente

**Solução**: Uma plataforma web completa que centraliza toda a operação, acessível em campo via PWA com suporte offline real, login por biometria, cobrança recorrente automatizada e integração com o ERP interno.

---

## ✨ Funcionalidades Principais

1. **🔐 Controle de Acesso** — 4 níveis + permissões finas + login biométrico (WebAuthn)
2. **📊 Dashboard** — KPIs em tempo real com exportação em PDF
3. **📋 Atendimento** — priorização de clientes sem visita, com mapa Leaflet e geolocalização
4. **👥 Gestão de Clientes** — cadastro, histórico de vendas em PDF, inatividade, WhatsApp
5. **🛒 Vendas** — pedidos com assinatura digital, duas formas de pagamento e criação offline
6. **📦 Consignação** — visitas unificadas (acerto + reposição), trilha de auditoria, termos assinados
7. **💰 Comissões e Lucro Padrão** — cálculo automático por venda, margem por produto
8. **🏦 Financeiro** — DRE simplificado, 6 KPIs, boleto parcelado
9. **💳 Cobrança / Assinatura** — faturas recorrentes com Asaas e Mercado Pago, webhooks e cron de inadimplência
10. **🔔 Web Push** — notificações nativas de cobrança (VAPID / RFC 8291)
11. **🔄 Devoluções** — quarentena e resolução com impacto automático no estoque
12. **🔗 Integração ErlDev Gestão** — API servidor-a-servidor para sync e controle remoto da assinatura
13. **📄 Relatórios** — PDF e Excel para vendas, romaneios, termos e histórico por cliente

---

## 🛠 Destaques Técnicos

### Frontend
- **JavaScript Vanilla** — SPA sem frameworks, máxima performance e controle
- **PWA com Service Worker** — auto-update e uso offline em campo
- **Offline real** — IndexedDB com cache de GET e outbox de escrita; Sync Manager FIFO remapeia IDs temporários
- **WebAuthn** — cerimônias de registro e login biométrico por dispositivo
- **Leaflet** — mapa de atendimento com geolocalização, sem CDN
- **Padrão IIFE/Module Revealing** — organização modular sem bundler

### Backend
- **PHP + PDO** — queries parametrizadas em toda a API, sem SQL injection
- **REST API modular** — ~30 domínios de endpoints organizados por negócio
- **Cobrança com gateways** — interface + roteador para Asaas e Mercado Pago; webhooks idempotentes com log e reconciliação
- **Web Push nativo** — VAPID / RFC 8291 sem SDK
- **Integração servidor-a-servidor** — endpoints token Bearer para o ERP interno
- **Exportações nativas** — PDF e Excel gerados em PHP, sem bibliotecas externas
- **Automação** — cron diário de inadimplência em GitHub Actions

### Segurança
- ✅ RBAC com 4 níveis + permissões finas por chave — sem permissões cruzadas
- ✅ WebAuthn com contador anti-clonagem — chave privada nunca sai do dispositivo
- ✅ Queries parametrizadas — PDO em 100% dos endpoints
- ✅ Webhooks com validação, log e idempotência
- ✅ Sessões controladas administrativamente
- ✅ Exportações e integrações protegidas por token

---

## 📈 Impacto e Resultados

- **Operação centralizada**: eliminou controles paralelos em planilhas
- **Visibilidade em tempo real**: gerente acompanha equipe, KPIs e DRE sem deslocamento
- **Redução de erros**: comissões, estoques e faturas calculados automaticamente
- **Campo conectado**: vendedores acessam e registram dados offline, entram por biometria
- **Cobrança autônoma**: mensalidade do sistema gerada, cobrada e conciliada sem intervenção manual
- **ERP integrado**: o ErlDev Gestão reflete a situação financeira do cliente automaticamente

---

## 🧠 O Que Foi Aprendido

### Técnico
- Desenvolvimento full-stack de ponta a ponta
- Design e implementação de API RESTful para contexto comercial
- PWA com estratégia de offline real (cache + outbox + sincronização)
- Integração com gateways de pagamento (Pix/boleto) e tratamento de webhooks idempotentes
- Implementação de WebAuthn (cerimônias de registro/login, contador anti-clonagem)
- Web Push nativo (VAPID, payload criptografado) sem SDK
- Design de banco de dados normalizado para modelo de negócio complexo
- Geração nativa de documentos (PDF/Excel) em PHP

### Arquitetura
- RBAC granular com módulos visíveis por nível de acesso e permissões finas
- Interface + roteador de gateway para trocar provedor de pagamento sem reescrever o módulo
- Trilha de auditoria em operações críticas (consignação, estoque)
- Integração servidor-a-servidor entre dois sistemas próprios
- Automação sem servidor dedicado (GitHub Actions como agendador)

---

## 🎨 Decisões de Design

**Por que SPA sem framework?**
- Zero dependências externas no core
- Carregamento mais rápido — crítico para conexões de campo
- Controle total sobre o comportamento da aplicação

**Por que offline real com outbox?**
- Vendedor no campo não pode perder uma venda por falta de sinal
- A fila FIFO com remapeamento de IDs mantém a integridade referencial na sincronização

**Por que gateways atrás de uma interface comum?**
- Asaas e Mercado Pago são intercambiáveis; adicionar um terceiro não toca o módulo de cobrança
- Webhooks idempotentes + reconciliação garantem consistência mesmo com falha de rede

---

## 🏆 Complexidade do Projeto

| Métrica | Estimativa |
|---------|-----------|
| **Módulos** | 20+ módulos integrados |
| **Domínios de API** | ~30 |
| **Níveis de Acesso** | 4 + flag de cobrança + permissões finas |
| **Migrações de Banco** | v1.x → v5.6 |
| **Gateways de Pagamento** | Asaas, Mercado Pago |
| **Tipos de Estoque** | Central, por Vendedor, Consignação |
| **Status de Venda** | Pendente → Confirmada → Entregue |
| **Status de Devolução** | Registrada → Aprovada → Quarentena → Resolvida |

---

## 🎬 Pitch em 30 Segundos

> "Desenvolvi o GynConect, um sistema web de gestão comercial completo para distribuidores com equipes de vendas externas. A plataforma controla clientes, estoque, vendas, consignação, comissões e financeiro — tudo em uma SPA com PHP e JavaScript Vanilla, funcionando como PWA com offline real (IndexedDB + fila de sincronização) e login por biometria. O sistema cobra a própria mensalidade via Pix e boleto (Asaas e Mercado Pago) com webhooks e cron de inadimplência, envia notificações Web Push, tem um mapa de atendimento com geolocalização e se integra por API ao meu ERP interno. Está em produção, na linha de versão 5.x, atendendo uma operação real."

---

## 📊 Stats Rápidas

```
⚙️  Arquitetura:  REST API + SPA + integrações S2S
📱  Mobile:       PWA + Service Worker + offline real (IndexedDB)
🔐  Segurança:   RBAC 4 níveis + permissões finas + WebAuthn + PDO
💳  Pagamentos:  Asaas + Mercado Pago (Pix, boleto, cartão) + webhooks
🔔  Push:        Web Push nativo (VAPID / RFC 8291)
📦  Módulos:     20+ integrados
📄  Exportações: PDF + Excel (PHP nativo)
🤖  Automação:   Cron diário via GitHub Actions
🚀  Status:      Produção — v5.x
```

---

## 🔗 Navegação

- [📖 README Principal](README.md)
- [🇺🇸 English Version](README-EN.md)
- [🏗 Arquitetura Detalhada](docs/ARQUITETURA.md)
- [🔌 Documentação da API](docs/API.md)
- [🔒 Política de Segurança](SECURITY.md)
- [📋 Changelog](CHANGELOG.md)

---

<div align="center">

**© 2025-2026 Lucas (Erl Dev)**

*Sistema proprietário — documentação de portfólio apenas*

</div>
