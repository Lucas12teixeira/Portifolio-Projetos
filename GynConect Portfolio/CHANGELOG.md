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
- ✅ Offline resiliente — chaves de idempotência, retry automático e tela de pendências de sincronização

---

### v5.7 — Offline resiliente, idempotência e vendas sem trava de estoque (versão atual: 5.7.12)

> Mudanças desde 03/09/2026.

**Adicionado**
- **Idempotência em requisições de escrita** — o frontend envia `Idempotency-Key` em toda requisição não-GET (a chave é criada uma vez por ação e reaproveitada nos retries); o backend grava a resposta na mesma transação da escrita e devolve a resposta salva em vez de duplicar. Aplicado a vendas, finalização de visitas e cadastro de clientes. Nova tabela `idempotencia_requisicoes` (migração v5.7)
- **Tela de pendências de sincronização** — botão de sincronização na barra superior com badge e modal listando os itens pendentes, com ações "Tentar novamente" e "Descartar"
- **Retry automático a cada 2 minutos** e toast-resumo após cada sincronização, com avisos nas tentativas 5, 10 e 15
- **Movimentação de estoque com vários itens** em uma única transação (carrinho "Adicionar"); o formato de item único continua suportado
- **"Marcar visita"** no Atendimento — registra visita sem venda e atualiza a data da última visita do cliente, validando o vínculo com o vendedor
- **Visualizador de pedido em PDF** com botões Fechar e Compartilhar; o PDF ganha coluna de **lucro do cliente** por unidade e inclui itens de consignação
- Novas regras de cache de API no Service Worker: `configuracoes` (1 h), `atendimento` (30 min) e `termos` (30 min)

**Alterado**
- **Vendas não são mais bloqueadas por estoque insuficiente** — o saldo pode ficar negativo (visível nos relatórios) até um ajuste de estoque
- Itens offline com erro 5xx são reenviados sem limite de tentativas; erros 4xx ficam como "falhou" aguardando ação do usuário (antes, itens eram descartados após 5 tentativas)
- Geração do PDF de pedido refatorada: construtor de PDF nativo e autenticação/consulta compartilhados entre o PDF binário e o visualizador
- Venda com pagamento a prazo (boleto, pendente, prazo) exige assinatura do cliente, inclusive para Consumidor Final

**Corrigido**
- Navegação offline não cai mais na tela de assinatura suspensa: GET sem cópia em cache retorna "Sem conexão", e o 503 só conta como suspensão com o dispositivo online e em duas ocorrências seguidas
- Envio duplicado (duplo clique) no salvamento de cliente, venda e visita, com travas de envio

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
