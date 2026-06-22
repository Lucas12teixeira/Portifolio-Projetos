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
