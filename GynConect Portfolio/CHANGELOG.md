# Changelog — GynConect

Todas as mudanças notáveis do projeto são documentadas aqui.
Formato baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/).

> Documentação de portfólio. Datas e números de versão referem-se às migrações
> de banco e aos marcos de release do sistema em produção.

---

## [Produção] — Linha 5.x (atual)

### Destaques da linha 5.x

- ✅ Login biométrico (WebAuthn) — Face ID, Touch ID, digital Android, Windows Hello
- ✅ Offline real — IndexedDB (cache de GET + outbox de escrita) e Sync Manager FIFO
- ✅ Módulo de Cobrança / Assinatura recorrente com gateways de pagamento
- ✅ Notificações Web Push nativas (VAPID / RFC 8291)
- ✅ Módulo Atendimento com mapa Leaflet e geolocalização
- ✅ Integração servidor-a-servidor com o ErlDev Gestão
- ✅ Financeiro redesenhado (DRE simplificado, 6 KPIs)
- ✅ Cron diário de inadimplência via GitHub Actions

---

### v5.6 — Notificações de cobrança multi-destinatário

- Notificação de cobrança passa a ser entregue ao **dev e ao responsável pela cobrança**, cada um com registro de envio próprio (idempotência por fatura + tipo + canal + destinatário)

### v5.5 — Web Push + reconciliação automática

- Tabela `push_subscriptions` (uma assinatura por dispositivo/navegador)
- Canal `push` nas notificações de cobrança, além de e-mail
- Reconciliação automática com o gateway quando o webhook não chega

### v5.4 — Cobrança recorrente + bloqueio por inadimplência

- Registro único de `assinatura` (valor, dia de vencimento, tolerância)
- Tabela `faturas` — uma por ciclo, com Pix, boleto e cartão
- Gateways **Asaas** e **Mercado Pago** atrás de interface + roteador comum
- `webhook_logs` com idempotência por gateway + evento
- Flag `responsavel_cobranca` no usuário
- Bloqueio da navegação para usuários comuns quando a assinatura fica vencida

### v5.3 — Consumidor Final

- Cadastro genérico "Consumidor Final" para vendas rápidas sem cadastrar cada comprador
- Venda a Consumidor Final dispensa assinatura digital, com campo de identificação do comprador

### v5.2 — Histórico de vendas e melhorias do Painel Dev

- Histórico de vendas por cliente com PDF e compartilhamento
- Painel Dev: permissões finas, controle de sessões, busca no feed de atividade
- Boleto parcelado com múltiplas parcelas
- Correção de comissão duplicada no acerto de consignação
- Status "Finalizado" para clientes encerrados

### v5.1 — Painel Financeiro redesenhado

- Formato de DRE simplificado com 6 KPIs e filtros rápidos

---

## Módulos e recursos anteriores (linhas 1.x – 4.x)

### Integração e biometria
- Login com Face ID / digital (WebAuthn) — credencial por dispositivo, contador anti-clonagem
- Endpoints de integração servidor-a-servidor com o ErlDev Gestão (somente-leitura + controle remoto)

### Atendimento e visitas
- Módulo Atendimento — lista de clientes sem visita há 30+ dias
- Visão em mapa (Leaflet) com geolocalização e buckets de criticidade
- Visitas de consignação unificadas (acerto + reposição), numeração `VIS-AAAA-NNNN`

### Consignação e estoque
- Controle completo de consignação com trilha de auditoria
- Estoque central, por vendedor e em consignação
- Termos de consignação com assinatura digital e versionamento
- Lucro Padrão por produto

### Financeiro e relatórios
- Dashboard com KPIs financeiros e exportação PDF
- Módulo de boletos e controle de despesas
- Exportações PDF e Excel nativos

### Campo e operação
- PWA com Service Worker, auto-update e suporte offline
- Módulo de devoluções com quarentena
- Módulo de introduções com bônus
- Painel Dev com controle de sessões
- Controle de versão com cache busting automático

---

## Histórico de Desenvolvimento

### Fase 1 — Fundação
- Estrutura SPA com autenticação token-based
- RBAC com 4 níveis de acesso
- Cadastro de clientes e produtos
- Módulo de vendas básico

### Fase 2 — Consignação e Estoque
- Controle completo de consignação
- Estoque por vendedor e central
- Termos de consignação com assinatura digital
- Trilha de auditoria em movimentações

### Fase 3 — Financeiro e Relatórios
- Dashboard com KPIs financeiros
- Módulo de boletos e despesas
- Exportações PDF e Excel nativos

### Fase 4 — Campo e Operação
- PWA com Service Worker e suporte offline
- Módulo de devoluções com quarentena
- Módulo de introduções
- Painel Dev com controle de sessões

### Fase 5 — Autonomia e Integração (linha 5.x)
- Offline real com IndexedDB e fila de sincronização
- Login biométrico (WebAuthn)
- Cobrança recorrente com gateways de pagamento e webhooks
- Notificações Web Push
- Atendimento com mapa e geolocalização
- Integração servidor-a-servidor com o ERP interno
- Automação de inadimplência via GitHub Actions

---

<div align="center">

*GynConect — Sistema proprietário. Documentação de portfólio apenas.*

</div>
