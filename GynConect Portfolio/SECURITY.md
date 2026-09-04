# Política de Segurança — GynConect

## Visão Geral

Este documento descreve as práticas e medidas de segurança implementadas no GynConect. Nenhum dado sensível de produção, credencial, chave de gateway ou configuração de servidor está incluído neste portfólio.

---

## 🔐 Autenticação

- **Mecanismo**: token de sessão stateless com longa duração
- **Armazenamento**: token enviado via cabeçalho HTTP (`Authorization: Bearer`) em todas as requisições autenticadas
- **Expiração**: tokens com validade controlada e renovação em sessões ativas
- **Primeiro Acesso**: troca de senha obrigatória no primeiro login
- **Recuperação**: fluxo de recuperação de senha via e-mail com token temporário
- **Senhas**: hash seguro (bcrypt via `password_hash`) antes do armazenamento

---

## 👆 Login Biométrico (WebAuthn)

- Padrão **WebAuthn / FIDO2** — Face ID, Touch ID, digital Android e Windows Hello
- A **chave privada nunca sai do dispositivo**; o servidor guarda apenas a chave pública e os metadados da credencial
- **Contador anti-clonagem** (`sign_counter`) validado a cada autenticação
- Credenciais são **por dispositivo** e podem ser removidas individualmente pelo usuário
- Challenges temporários com validade curta para cada cerimônia de registro ou login

---

## 🛡 Controle de Acesso (RBAC + Permissões Finas)

GynConect implementa **Role-Based Access Control** com quatro níveis:

| Nível | Acesso |
|-------|--------|
| **Vendedor** | Próprios clientes, vendas e comissões apenas |
| **Gerente** | Equipe completa, relatórios, aprovações, despesas |
| **Financeiro** | Módulos financeiros (boletos, comissões) |
| **Dev** | Acesso total incluindo painel administrativo |

- Sobre o nível, há **permissões finas por chave** (ex.: `fin_boletos_ver`) verificadas antes de processar a requisição
- Flag `responsavel_cobranca` habilita o módulo de cobrança para um usuário específico
- Cada endpoint valida o nível e a permissão antes de qualquer processamento
- Sem herança ou permissões cruzadas entre níveis
- Nível Dev não gera comissão nem custo contábil para a empresa

---

## 🗄 Banco de Dados

- **Driver**: PDO (PHP Data Objects)
- **Queries**: 100% parametrizadas — SQL injection eliminado por design
- **Credenciais**: nunca expostas no frontend ou no código versionado; isoladas em arquivo não versionado (`api/config/secrets.php`)
- **Migrações**: versionadas e aplicadas de forma controlada

---

## 💳 Cobrança e Gateways de Pagamento

- Chaves de API dos gateways (Asaas, Mercado Pago) ficam **apenas no servidor**, em arquivo não versionado
- **Webhooks** com validação de origem, log em `webhook_logs` e **idempotência** por gateway + evento
- **Reconciliação ativa** — o sistema consulta o gateway quando o webhook não chega, evitando dependência de um único canal
- IDs externos de cobrança usados como chave de idempotência para nunca duplicar faturas

---

## 🔔 Notificações Web Push

- Web Push nativo (RFC 8291) com par de chaves **VAPID** guardado no servidor
- Assinaturas de push registradas por dispositivo, com possibilidade de revogação
- Payload criptografado ponta a ponta pelo padrão Web Push

---

## 🔗 Integração Servidor-a-Servidor (ErlDev Gestão)

- Endpoints de integração autenticados por **token Bearer dedicado**, comparado com `hash_equals` (resistente a timing attack)
- Não usam sessão nem cookie — chamada servidor-a-servidor pura
- Endpoint de leitura é **somente-leitura**; o endpoint de controle aceita um conjunto fechado de ações
- Cron de inadimplência protegido por token próprio (`CRON_TOKEN`), disparado por GitHub Actions

---

## 🌐 Segurança de Sessão

- **Controle administrativo**: gerente e dev podem visualizar todas as sessões ativas
- **Encerramento**: sessões podem ser terminadas individualmente ou em massa
- **Auditoria**: feed de atividade recente no painel Dev para rastreamento de ações
- **Bloqueio por inadimplência**: com a assinatura vencida, usuários comuns são impedidos de navegar (inclusive com dados de cache offline)

---

## 📄 Exportações

- Todos os endpoints de exportação (PDF, Excel) requerem token de acesso válido
- Geração server-side — nenhum dado é processado no cliente
- Filtros de acesso aplicados antes da geração do documento

---

## 🔒 Práticas Gerais

- **Headers de segurança**: `X-Content-Type-Options: nosniff` e `Referrer-Policy` configurados
- **HTTPS**: conexão criptografada obrigatória em produção (também requisito do WebAuthn e do Service Worker)
- **Dados sensíveis**: CPF/CNPJ e dados de contato protegidos por nível de acesso
- **Bibliotecas locais**: Chart.js e Leaflet servidos localmente, sem CDN de terceiros

---

## 📢 Reporte de Vulnerabilidades

Este é um repositório de portfólio — o código-fonte não está disponível publicamente.

Para contato sobre segurança ou o projeto:
- 🌐 [github.com/Lucas12teixeira/Portifolio-Projetos](https://github.com/Lucas12teixeira/Portifolio-Projetos)
- 🐙 GitHub: [@Lucas12teixeira](https://github.com/Lucas12teixeira)

---

<div align="center">

*GynConect — Sistema proprietário. Documentação de portfólio apenas.*

</div>
