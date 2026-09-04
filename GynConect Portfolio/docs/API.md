# Documentação da API — GynConect

> **Nota**: Esta é uma documentação de portfólio. Nenhuma URL de produção está incluída.

---

## Convenções

| Aspecto | Padrão |
|---------|--------|
| **Formato** | JSON |
| **Autenticação** | `Authorization: Bearer <token>` |
| **Erros** | `{ "error": true, "message": "...", "code": 422 }` |
| **Sucesso** | `{ "success": true, "data": {...} }` |
| **Paginação** | `?page=1&per_page=20` |
| **Filtros** | Query string: `?status=ativo&vendedor_id=5` |

---

## Autenticação

### POST `/api/auth/login`

Autentica o usuário e retorna o token de sessão.

**Request**
```json
{
  "email": "usuario@exemplo.com",
  "senha": "••••••••",
  "lembrar": true
}
```

**Response 200**
```json
{
  "success": true,
  "token": "<session_token>",
  "usuario": {
    "id": 1,
    "nome": "Nome do Usuário",
    "nivel": "gerente",
    "primeiro_acesso": false
  }
}
```

**Response 401**
```json
{
  "error": true,
  "message": "Credenciais inválidas"
}
```

---

### POST `/api/auth/logout`

Encerra a sessão atual.

**Headers**: `Authorization: Bearer <token>`

---

## Clientes

### GET `/api/clientes/listar`

Lista clientes com filtros opcionais.

**Query Params**

| Param | Tipo | Descrição |
|-------|------|-----------|
| `status` | string | `ativo`, `inativo`, `encerrado` |
| `inatividade` | int | Dias sem visita: `30`, `60`, `90` |
| `vendedor_id` | int | Filtrar por vendedor (gerente/dev) |
| `busca` | string | Nome, CPF ou telefone |
| `page` | int | Página atual |

**Response 200**
```json
{
  "success": true,
  "data": [
    {
      "id": 42,
      "nome": "Cliente Exemplo",
      "status": "ativo",
      "ultima_visita": "2026-06-15",
      "elegivel_consignacao": true,
      "total_consignacao": 1250.00
    }
  ],
  "pagination": {
    "total": 87,
    "page": 1,
    "per_page": 20,
    "pages": 5
  }
}
```

---

### POST `/api/clientes/criar`

Cadastra novo cliente.

**Request**
```json
{
  "nome": "Nome Completo",
  "cpf_cnpj": "000.000.000-00",
  "telefone": "(00) 00000-0000",
  "email": "cliente@exemplo.com",
  "endereco": {
    "rua": "...",
    "numero": "...",
    "bairro": "...",
    "cidade": "...",
    "uf": "GO"
  }
}
```

---

## Vendas

### GET `/api/vendas/listar`

Lista vendas com filtros.

**Query Params**

| Param | Tipo | Descrição |
|-------|------|-----------|
| `status` | string | `pendente`, `confirmada`, `entregue` |
| `data_inicio` | date | Filtro de período |
| `data_fim` | date | Filtro de período |
| `vendedor_id` | int | Somente para gerente/dev |
| `cliente_id` | int | Filtrar por cliente |
| `pagamento` | string | Forma de pagamento |

---

### POST `/api/vendas/criar`

Cria novo pedido de venda.

**Request**
```json
{
  "cliente_id": 42,
  "itens": [
    {
      "produto_id": 10,
      "quantidade": 3,
      "preco_unitario": 49.90,
      "desconto": 5.00
    }
  ],
  "pagamento": [
    { "forma": "pix", "valor": 139.70 }
  ],
  "tipo": "comum",
  "observacao": "Entrega na sexta"
}
```

**Response 201**
```json
{
  "success": true,
  "data": {
    "id": 157,
    "status": "pendente",
    "total": 139.70,
    "comissao_gerada": 13.97,
    "assinatura_url": "/api/vendas/157/assinatura"
  }
}
```

---

### POST `/api/vendas/confirmar`

Confirma um pedido pendente.

**Request**
```json
{
  "venda_id": 157,
  "assinatura_base64": "data:image/png;base64,..."
}
```

---

### GET `/api/vendas/exportar`

Exporta vendas em PDF ou Excel.

**Query Params**

| Param | Tipo | Descrição |
|-------|------|-----------|
| `formato` | string | `pdf` ou `excel` |
| `data_inicio` | date | Filtro |
| `data_fim` | date | Filtro |
| `vendedor_id` | int | Opcional |

**Response**: arquivo binário (PDF ou XLSX)

---

## Consignação

### GET `/api/consignacao/listar`

Lista itens em consignação.

**Response 200**
```json
{
  "success": true,
  "data": [
    {
      "cliente_id": 42,
      "cliente_nome": "Cliente Exemplo",
      "produto_id": 10,
      "produto_nome": "Produto X",
      "quantidade_atual": 5,
      "quantidade_vendida": 3,
      "quantidade_devolvida": 0,
      "valor_em_aberto": 249.50
    }
  ],
  "totais": {
    "valor_total_aberto": 12500.00,
    "clientes_ativos": 34
  }
}
```

---

### POST `/api/consignacao/movimentar`

Registra movimentação de consignação (saída, venda, devolução).

**Request**
```json
{
  "cliente_id": 42,
  "produto_id": 10,
  "tipo": "saida",
  "quantidade": 2,
  "observacao": "..."
}
```

---

## Estoque

### GET `/api/estoque/listar`

Lista estoque com filtros.

**Query Params**: `vendedor_id`, `abaixo_minimo=true`, `categoria_id`

**Response 200**
```json
{
  "success": true,
  "data": [
    {
      "produto_id": 10,
      "nome": "Produto X",
      "sku": "PRD-001",
      "estoque_central": 50,
      "estoque_vendedor": 12,
      "estoque_consignacao": 8,
      "estoque_minimo": 5,
      "alerta": false,
      "valor_total": 3495.00
    }
  ]
}
```

---

## Dashboard

### GET `/api/dashboard/kpis`

Retorna KPIs financeiros do período.

**Query Params**: `periodo` (`hoje`, `7d`, `mes`, `custom`), `data_inicio`, `data_fim`

**Response 200**
```json
{
  "success": true,
  "data": {
    "faturamento_bruto": 45200.00,
    "faturamento_liquido": 38700.00,
    "comissoes_pagas": 4520.00,
    "repasse_empresa": 34180.00,
    "consignacao_aberto": 12500.00,
    "pagamentos_pendentes": 3,
    "despesas_mes": 2100.00,
    "ranking_vendedores": [...]
  }
}
```

---

## Autenticação Biométrica (WebAuthn)

Login por Face ID / Touch ID / digital, seguindo o padrão WebAuthn. As trocas usam
`base64url` para os campos binários.

### POST `/api/auth/webauthn/registro-inicio`

Inicia a cerimônia de registro de uma credencial no dispositivo atual.
Requer sessão autenticada por senha.

**Response 200**
```json
{
  "success": true,
  "publicKey": {
    "challenge": "<base64url>",
    "rp": { "name": "GYN CONECT", "id": "..." },
    "user": { "id": "<base64url>", "name": "...", "displayName": "..." },
    "pubKeyCredParams": [{ "type": "public-key", "alg": -7 }],
    "authenticatorSelection": { "userVerification": "required", "residentKey": "discouraged" }
  }
}
```

### POST `/api/auth/webauthn/registro-fim`

Conclui o registro, enviando o attestation retornado pelo autenticador.

**Request**
```json
{
  "id": "<credential_id base64url>",
  "rawId": "<base64url>",
  "response": { "clientDataJSON": "<base64url>", "attestationObject": "<base64url>" },
  "apelido": "iPhone do Lucas"
}
```

### POST `/api/auth/webauthn/login-inicio`

**Request**
```json
{ "email": "usuario@exemplo.com" }
```

**Response 200**
```json
{
  "success": true,
  "publicKey": {
    "challenge": "<base64url>",
    "allowCredentials": [{ "type": "public-key", "id": "<base64url>" }],
    "userVerification": "required"
  }
}
```

### POST `/api/auth/webauthn/login-fim`

Envia o assertion assinado. Em sucesso retorna o mesmo payload de `/api/auth/login`
(token + usuário). O `sign_counter` é validado contra clonagem.

---

## Atendimento

### GET `/api/atendimento`

Lista clientes sem visita há N dias, para priorização de follow-up.
Vendedor vê apenas os próprios clientes; gerente/dev veem os clientes de gerentes.

**Query Params**

| Param | Tipo | Descrição |
|-------|------|-----------|
| `page` | int | Página (infinite scroll) |
| `per_page` | int | Itens por página (máx. 500 — usado pelo modo mapa) |
| `dias_min` | int | Mínimo de dias sem visita (padrão 30) |
| `search` | string | Nome ou cidade |

**Response 200**
```json
{
  "success": true,
  "data": [
    {
      "id": 42,
      "nome": "Cliente Exemplo",
      "cidade": "Goiânia",
      "ultima_visita": "2026-04-01",
      "dias_sem_visita": 61,
      "bucket": "critico",
      "lat": -16.68, "lng": -49.25
    }
  ],
  "pagination": { "page": 1, "pages": 4, "total": 96 }
}
```

---

## Cobrança (Assinatura do Sistema)

Acesso restrito ao `dev` e ao usuário com `responsavel_cobranca = 1`.

### GET `/api/cobranca?acao=status`

Retorna a assinatura e as últimas faturas. Antes de responder, reconcilia
faturas pendentes com o gateway (cobre o caso do webhook não ter chegado).

**Response 200**
```json
{
  "success": true,
  "data": {
    "assinatura": {
      "status": "ativo",
      "valor_mensalidade": 149.90,
      "dia_vencimento": 10,
      "data_proximo_vencimento": "2026-08-10",
      "tolerancia_dias": 5
    },
    "faturas": [
      {
        "id": 31, "valor": 149.90, "data_vencimento": "2026-07-10",
        "status": "pago", "gateway": "mercadopago",
        "pago_em": "2026-07-09T14:22:00", "pago_valor": 149.90
      }
    ]
  }
}
```

### GET `/api/cobranca?acao=faturas&status=pendente`

Lista as faturas da assinatura, com filtro opcional por status.

### POST `/api/cobranca?acao=gerar_cobranca`

Gera a cobrança da próxima fatura no gateway configurado e retorna Pix + boleto.

**Response 200**
```json
{
  "success": true,
  "data": {
    "fatura_id": 32,
    "gateway": "asaas",
    "pix_copia_cola": "00020126...",
    "qr_code_base64": "data:image/png;base64,...",
    "url_boleto": "https://..."
  }
}
```

### POST `/api/cobranca/webhook_asaas.php` · `/api/cobranca/webhook_mercadopago.php`

Recebem os eventos de pagamento do gateway. Registram em `webhook_logs`
(idempotente por `gateway + id_externo + evento`) e marcam a fatura como paga.

### GET `/api/cobranca/cron_inadimplencia.php?token=<CRON_TOKEN>`

Disparado diariamente pelo GitHub Actions. Varre faturas vencidas, aplica a
tolerância, dispara notificações (e-mail + Web Push) e suspende a assinatura
quando estoura o prazo.

---

## Notificações Web Push

### POST `/api/cobranca?acao=push_subscribe`

Registra a assinatura de push do dispositivo (saída do `PushManager.subscribe`).

**Request**
```json
{
  "endpoint": "https://fcm.googleapis.com/fcm/send/...",
  "keys": { "p256dh": "<base64url>", "auth": "<base64url>" }
}
```

---

## Integração — ErlDev Gestão (Servidor-a-Servidor)

Autenticação por token dedicado: `Authorization: Bearer <ERLDEV_GESTAO_SYNC_TOKEN>`
ou `?token=`. Não usa sessão/cookie.

### GET `/api/integracoes/erldev_gestao.php`

Somente-leitura. Expõe o estado atual da assinatura e das faturas para o ERP
interno sincronizar o cliente automaticamente.

**Response 200**
```json
{
  "success": true,
  "assinatura": { "status": "ativo", "valor_mensalidade": 149.90, "data_proximo_vencimento": "2026-08-10" },
  "faturas": [
    { "id": 31, "valor": 149.90, "data_vencimento": "2026-07-10", "status": "pago", "gateway": "mercadopago" }
  ]
}
```

### POST `/api/integracoes/erldev_gestao_controle.php`

Aceita um conjunto fechado de ações de controle remoto da assinatura.

**Request**
```json
{ "acao": "suspender" }
```

| Ação | Efeito |
|------|--------|
| `suspender` | Marca a assinatura como suspensa (bloqueia usuários comuns) |
| `reativar` | Volta a assinatura para ativa |
| `marcar_pago` | Marca uma fatura como paga manualmente |
| `config` | Atualiza valor / dia de vencimento / tolerância |
| `cancelar_fatura` | Cancela uma fatura em aberto |
| `deletar_fatura` | Remove uma fatura |

---

## Códigos de Resposta

| Código | Significado |
|--------|------------|
| `200` | Sucesso |
| `201` | Criado com sucesso |
| `400` | Requisição inválida |
| `401` | Token ausente ou inválido |
| `403` | Sem permissão para este recurso |
| `404` | Recurso não encontrado |
| `409` | Conflito (ex.: e-mail já cadastrado) |
| `422` | Erro de validação nos dados enviados |
| `500` | Erro interno do servidor |

---

## Padrão de Erro

```json
{
  "error": true,
  "message": "Descrição legível do erro",
  "code": 422,
  "fields": {
    "email": "E-mail já cadastrado",
    "quantidade": "Quantidade não pode ser negativa"
  }
}
```

---

<div align="center">

[← Arquitetura](ARQUITETURA.md) • [← README](../README.md)

*GynConect — Documentação de portfólio apenas*

</div>
