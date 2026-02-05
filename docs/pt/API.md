# 📡 Documentação da API

Referência completa para a API REST do American Teens.

## Índice
- [Visão Geral](#visão-geral)
- [Autenticação](#autenticação)
- [Tratamento de Erros](#tratamento-de-erros)
- [Endpoints de Autenticação](#endpoints-de-autenticação)
- [Endpoints do Chat](#endpoints-do-chat)
- [Endpoints de Membros](#endpoints-de-membros)
- [Endpoints de Eventos](#endpoints-de-eventos)
- [Endpoints de Quiz](#endpoints-de-quiz)
- [Endpoints da Bíblia](#endpoints-da-bíblia)
- [Endpoints do Mural](#endpoints-do-mural)
- [Endpoints Admin](#endpoints-admin)
- [Rate Limiting](#rate-limiting)

---

## Visão Geral

### URL Base

**Produção:**
```
https://americateens.erldev.com.br/api
```

**Desenvolvimento:**
```
http://localhost:8000/api
```

### Formato de Requisição

Todas as requisições devem incluir:

```http
Content-Type: application/json
Authorization: Bearer <seu_token_jwt>
```

### Formato de Resposta

Todas as respostas seguem esta estrutura:

**Resposta de Sucesso:**
```json
{
  "ok": true,
  "data": { /* dados da resposta */ }
}
```

**Resposta de Erro:**
```json
{
  "ok": false,
  "error": "Mensagem de erro aqui",
  "code": 400
}
```

---

## Autenticação

### Token JWT

A maioria dos endpoints requer um token JWT válido no cabeçalho Authorization:

```http
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Payload do Token

```json
{
  "id": 123,
  "email": "usuario@exemplo.com",
  "role": "member",
  "iat": 1738656000,
  "exp": 1738659600
}
```

### Refresh Token

Tokens expiram após 1 hora. Use o refresh token para obter novo access token:

```http
POST /auth/refresh
Content-Type: application/json

{
  "refreshToken": "seu_refresh_token"
}
```

---

## Tratamento de Erros

### Códigos de Status HTTP

| Código | Significado |
|--------|-------------|
| 200    | Sucesso |
| 201    | Criado |
| 400    | Requisição Inválida - Entrada inválida |
| 401    | Não Autorizado - Token inválido ou ausente |
| 403    | Proibido - Permissões insuficientes |
| 404    | Não Encontrado - Recurso não existe |
| 409    | Conflito - Recurso já existe |
| 422    | Entidade Não Processável - Validação falhou |
| 500    | Erro Interno do Servidor |

### Exemplos de Resposta de Erro

**Erro de Validação:**
```json
{
  "ok": false,
  "error": "Validação falhou",
  "errors": {
    "email": "Email é obrigatório",
    "password": "Senha deve ter no mínimo 8 caracteres"
  }
}
```

**Erro de Autenticação:**
```json
{
  "ok": false,
  "error": "Token inválido",
  "code": 401
}
```

---

## Endpoints de Autenticação

### Registrar Novo Usuário

Criar uma nova conta de usuário.

```http
POST /auth/register
```

**Corpo da Requisição:**
```json
{
  "name": "João Silva",
  "email": "joao@exemplo.com",
  "password": "SenhaSegura123!",
  "local": "Igreja São Paulo",
  "birthday": "2000-01-15"
}
```

**Resposta:** `201 Created`
```json
{
  "ok": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR...",
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR...",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "role": "member",
    "local": "Igreja São Paulo",
    "avatar": null,
    "birthday": "2000-01-15"
  }
}
```

**Erros:**
- `409` - Email já registrado
- `422` - Validação falhou

---

### Login

Autenticar usuário existente.

```http
POST /auth/login
```

**Corpo da Requisição:**
```json
{
  "email": "joao@exemplo.com",
  "password": "SenhaSegura123!"
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR...",
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR...",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "role": "member"
  }
}
```

**Erros:**
- `401` - Credenciais inválidas

---

### Atualizar Token

Renovar token de acesso usando refresh token.

```http
POST /auth/refresh
```

**Corpo da Requisição:**
```json
{
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR..."
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "token": "novo_access_token",
  "refreshToken": "novo_refresh_token"
}
```

---

### Solicitar Reset de Senha

Enviar email com link para reset de senha.

```http
POST /auth/reset-request
```

**Corpo da Requisição:**
```json
{
  "email": "joao@exemplo.com"
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Email de reset enviado"
}
```

---

### Resetar Senha

Resetar senha usando token recebido por email.

```http
POST /auth/reset-password
```

**Corpo da Requisição:**
```json
{
  "token": "reset_token_from_email",
  "newPassword": "NovaSenha123!"
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Senha alterada com sucesso"
}
```

---

## Endpoints do Chat

### Listar Conversas

Obter todas as conversas do usuário.

```http
GET /chat/conversations
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "conversation_id": 1,
      "user_id": 2,
      "user_name": "Maria Santos",
      "user_avatar": "/uploads/avatars/maria.jpg",
      "last_message": "Olá, como vai?",
      "last_message_time": "2026-02-04 10:30:00",
      "unread_count": 3,
      "is_online": true
    }
  ]
}
```

---

### Obter Mensagens

Obter mensagens de uma conversa específica.

```http
GET /chat/messages/{conversationId}?limit=50&offset=0
Authorization: Bearer <token>
```

**Parâmetros de Query:**
- `limit` (opcional): Número de mensagens (padrão: 50)
- `offset` (opcional): Deslocamento para paginação (padrão: 0)

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "sender_id": 1,
      "sender_name": "João Silva",
      "message": "Olá!",
      "is_read": true,
      "created_at": "2026-02-04 10:25:00"
    },
    {
      "id": 2,
      "sender_id": 2,
      "sender_name": "Maria Santos",
      "message": "Oi, tudo bem?",
      "is_read": false,
      "created_at": "2026-02-04 10:26:00"
    }
  ]
}
```

---

### Enviar Mensagem

Enviar nova mensagem.

```http
POST /chat/send
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "receiverId": 2,
  "message": "Olá, como vai?"
}
```

**Resposta:** `201 Created`
```json
{
  "ok": true,
  "data": {
    "message_id": 123,
    "conversation_id": 1,
    "created_at": "2026-02-04 10:30:00"
  }
}
```

**Erros:**
- `400` - Mensagem vazia ou receiverId inválido
- `404` - Destinatário não encontrado

---

### Marcar como Lida

Marcar mensagem como lida.

```http
POST /chat/mark-read/{messageId}
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Mensagem marcada como lida"
}
```

---

### Buscar Usuários

Buscar usuários para iniciar conversa.

```http
GET /chat/search-users?q=maria
Authorization: Bearer <token>
```

**Parâmetros de Query:**
- `q`: Termo de busca (nome ou email)

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 2,
      "name": "Maria Santos",
      "avatar": "/uploads/avatars/maria.jpg",
      "local": "Igreja Rio de Janeiro"
    }
  ]
}
```

---

### Bloquear Usuário

Bloquear um usuário.

```http
POST /chat/block
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "userId": 5
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Usuário bloqueado"
}
```

---

### Desbloquear Usuário

Desbloquear um usuário.

```http
POST /chat/unblock
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "userId": 5
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Usuário desbloqueado"
}
```

---

### Status Online

Atualizar status online.

```http
POST /chat/online-status
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "isOnline": true
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Status atualizado"
}
```

---

## Endpoints de Membros

### Obter Perfil

Obter perfil do usuário logado.

```http
GET /members/profile
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "avatar": "/uploads/avatars/joao.jpg",
    "birthday": "2000-01-15",
    "local": "Igreja São Paulo",
    "role": "member",
    "created_at": "2025-01-01 10:00:00"
  }
}
```

---

### Atualizar Perfil

Atualizar informações do perfil.

```http
PUT /members/profile
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "name": "João Pedro Silva",
  "local": "Igreja Nova São Paulo",
  "birthday": "2000-01-15"
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "name": "João Pedro Silva",
    "email": "joao@exemplo.com",
    "local": "Igreja Nova São Paulo"
  }
}
```

---

### Upload de Avatar

Fazer upload de foto de perfil.

```http
POST /upload-avatar
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

**Form Data:**
```
avatar: [arquivo de imagem]
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "avatarUrl": "/uploads/avatars/user_1_1738656000.jpg"
}
```

**Restrições:**
- Formatos aceitos: JPG, JPEG, PNG, GIF
- Tamanho máximo: 5MB

---

### Listar Todos os Membros

Obter lista de todos os membros (coordenadores e admin).

```http
GET /members
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com",
      "local": "Igreja São Paulo",
      "role": "member",
      "is_active": true
    }
  ]
}
```

---

## Endpoints de Eventos

### Listar Eventos

Obter lista de eventos futuros.

```http
GET /events?limit=20&offset=0
Authorization: Bearer <token>
```

**Parâmetros de Query:**
- `limit` (opcional): Número de eventos (padrão: 20)
- `offset` (opcional): Deslocamento para paginação
- `featured` (opcional): Filtrar eventos em destaque (true/false)

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "title": "Retiro de Jovens 2026",
      "description": "Retiro anual para jovens",
      "event_date": "2026-03-15 09:00:00",
      "location": "Sítio Boa Vista",
      "organizer_name": "Pastor João",
      "max_participants": 50,
      "registered_count": 32,
      "image_url": "/uploads/events/retiro.jpg",
      "is_featured": true,
      "is_registered": false
    }
  ]
}
```

---

### Obter Detalhes do Evento

Obter detalhes de um evento específico.

```http
GET /events/{eventId}
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "title": "Retiro de Jovens 2026",
    "description": "Retiro anual para jovens com palestras, dinâmicas e momentos de louvor",
    "event_date": "2026-03-15 09:00:00",
    "location": "Sítio Boa Vista",
    "organizer_id": 5,
    "organizer_name": "Pastor João",
    "max_participants": 50,
    "registered_count": 32,
    "is_registered": true,
    "participants": [
      {
        "id": 1,
        "name": "João Silva",
        "avatar": "/uploads/avatars/joao.jpg"
      }
    ]
  }
}
```

---

### Criar Evento

Criar novo evento (coordenadores e admin).

```http
POST /events
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "title": "Culto de Jovens",
  "description": "Culto especial para jovens",
  "event_date": "2026-03-20 19:00:00",
  "location": "Igreja Central",
  "max_participants": 100,
  "image_url": "/uploads/events/culto.jpg"
}
```

**Resposta:** `201 Created`
```json
{
  "ok": true,
  "data": {
    "id": 10,
    "title": "Culto de Jovens",
    "event_date": "2026-03-20 19:00:00"
  }
}
```

**Requer:** role `coordinator` ou `admin`

---

### Atualizar Evento

Atualizar evento existente.

```http
PUT /events/{eventId}
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "title": "Culto de Jovens - Atualizado",
  "max_participants": 150
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Evento atualizado com sucesso"
}
```

---

### Deletar Evento

Deletar um evento.

```http
DELETE /events/{eventId}
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Evento deletado com sucesso"
}
```

---

### Registrar-se no Evento

Registrar participação em um evento.

```http
POST /events/{eventId}/register
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Registrado com sucesso"
}
```

**Erros:**
- `400` - Evento lotado
- `409` - Já registrado neste evento

---

### Cancelar Registro

Cancelar participação em um evento.

```http
DELETE /events/{eventId}/register
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Registro cancelado"
}
```

---

## Endpoints de Quiz

### Obter Perguntas do Quiz

Obter perguntas do quiz bíblico.

```http
GET /quiz/questions?limit=10&category=general
Authorization: Bearer <token>
```

**Parâmetros de Query:**
- `limit` (opcional): Número de perguntas (padrão: 10)
- `category` (opcional): Categoria (general, old_testament, new_testament)

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "question": "Quem foi o primeiro homem criado por Deus?",
      "options": ["Adão", "Noé", "Abraão", "Moisés"],
      "category": "old_testament"
    }
  ]
}
```

---

### Enviar Respostas do Quiz

Enviar respostas e obter resultado.

```http
POST /quiz/submit
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "answers": [
    { "questionId": 1, "answer": "Adão" },
    { "questionId": 2, "answer": "Gênesis" }
  ]
}
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "score": 8,
    "total": 10,
    "percentage": 80,
    "correct_answers": 8,
    "incorrect_answers": 2,
    "ranking_position": 5
  }
}
```

---

### Ranking do Quiz

Obter ranking dos melhores jogadores.

```http
GET /quiz/ranking?limit=10
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "position": 1,
      "user_id": 5,
      "user_name": "Maria Santos",
      "avatar": "/uploads/avatars/maria.jpg",
      "total_score": 950,
      "quizzes_completed": 10
    }
  ]
}
```

---

## Endpoints da Bíblia

### Versículo do Dia

Obter versículo do dia.

```http
GET /verse-of-day
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "verse": "Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito...",
    "reference": "João 3:16",
    "date": "2026-02-04"
  }
}
```

---

### Buscar Versículos

Buscar versículos por texto.

```http
GET /bible/search?q=amor&limit=10
Authorization: Bearer <token>
```

**Parâmetros de Query:**
- `q`: Termo de busca
- `limit` (opcional): Número de resultados (padrão: 10)

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "book": "João",
      "chapter": 3,
      "verse": 16,
      "text": "Porque Deus amou o mundo...",
      "reference": "João 3:16"
    }
  ]
}
```

---

### Obter Capítulo

Obter todos os versículos de um capítulo.

```http
GET /bible/{book}/{chapter}
Authorization: Bearer <token>
```

**Exemplo:**
```http
GET /bible/john/3
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "book": "John",
    "chapter": 3,
    "verses": [
      {
        "verse": 1,
        "text": "There was a man of the Pharisees..."
      },
      {
        "verse": 2,
        "text": "The same came to Jesus by night..."
      }
    ]
  }
}
```

---

## Endpoints do Mural

### Listar Publicações

Obter publicações do mural da comunidade.

```http
GET /wall?limit=20&offset=0
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "user_name": "João Silva",
      "user_avatar": "/uploads/avatars/joao.jpg",
      "content": "Que Deus abençoe nosso dia!",
      "created_at": "2026-02-04 10:00:00",
      "likes_count": 15,
      "is_liked": false
    }
  ]
}
```

---

### Criar Publicação

Criar nova publicação no mural.

```http
POST /wall
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "content": "Deus é fiel! Testemunho de bênção hoje."
}
```

**Resposta:** `201 Created`
```json
{
  "ok": true,
  "data": {
    "id": 10,
    "created_at": "2026-02-04 10:30:00"
  }
}
```

---

### Curtir Publicação

Curtir uma publicação.

```http
POST /wall/{postId}/like
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Publicação curtida",
  "likes_count": 16
}
```

---

### Descurtir Publicação

Remover curtida de uma publicação.

```http
DELETE /wall/{postId}/like
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Curtida removida",
  "likes_count": 15
}
```

---

## Endpoints Admin

### Listar Todos os Membros (Admin)

Obter lista completa de membros com informações detalhadas.

```http
GET /admin/members
Authorization: Bearer <token>
```

**Requer:** role `admin`

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "name": "João Silva",
      "email": "joao@exemplo.com",
      "role": "member",
      "local": "Igreja São Paulo",
      "is_active": true,
      "created_at": "2025-01-01 10:00:00",
      "last_login": "2026-02-04 09:00:00"
    }
  ]
}
```

---

### Atualizar Role do Usuário

Alterar role de um usuário.

```http
PUT /admin/members/{userId}/role
Authorization: Bearer <token>
Content-Type: application/json
```

**Corpo da Requisição:**
```json
{
  "role": "coordinator"
}
```

**Valores válidos:** `member`, `coordinator`, `admin`

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Role atualizada com sucesso"
}
```

---

### Desativar Usuário

Desativar conta de usuário.

```http
POST /admin/members/{userId}/deactivate
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Usuário desativado"
}
```

---

### Ativar Usuário

Reativar conta de usuário.

```http
POST /admin/members/{userId}/activate
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "message": "Usuário ativado"
}
```

---

### Estatísticas do Sistema

Obter estatísticas gerais do sistema.

```http
GET /admin/stats
Authorization: Bearer <token>
```

**Resposta:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "total_members": 150,
    "active_members": 145,
    "total_events": 25,
    "upcoming_events": 8,
    "total_messages": 5420,
    "total_quiz_plays": 350
  }
}
```

---

## Rate Limiting

Para prevenir abuso, a API implementa rate limiting:

### Limites

- **Requisições gerais:** 100 requisições / 15 minutos por IP
- **Autenticação:** 5 tentativas / 15 minutos por IP
- **Envio de mensagens:** 30 mensagens / minuto por usuário
- **Upload de arquivos:** 10 uploads / hora por usuário

### Cabeçalhos de Resposta

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1738657800
```

### Resposta ao Exceder Limite

```http
HTTP/1.1 429 Too Many Requests
Content-Type: application/json

{
  "ok": false,
  "error": "Limite de requisições excedido",
  "retry_after": 900
}
```

---

## Exemplos de Uso

### JavaScript (Fetch API)

```javascript
// Login
async function login(email, password) {
  const response = await fetch('https://americateens.erldev.com.br/api/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  
  if (data.ok) {
    localStorage.setItem('token', data.token);
    localStorage.setItem('refreshToken', data.refreshToken);
    return data.user;
  } else {
    throw new Error(data.error);
  }
}

// Buscar conversas
async function getConversations() {
  const token = localStorage.getItem('token');
  
  const response = await fetch('https://americateens.erldev.com.br/api/chat/conversations', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  
  const data = await response.json();
  return data.ok ? data.data : [];
}

// Enviar mensagem
async function sendMessage(receiverId, message) {
  const token = localStorage.getItem('token');
  
  const response = await fetch('https://americateens.erldev.com.br/api/chat/send', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    },
    body: JSON.stringify({ receiverId, message })
  });
  
  return await response.json();
}
```

### PHP (cURL)

```php
<?php
// Login
function login($email, $password) {
    $ch = curl_init('https://americateens.erldev.com.br/api/auth/login');
    
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            'email' => $email,
            'password' => $password
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Buscar eventos com token
function getEvents($token) {
    $ch = curl_init('https://americateens.erldev.com.br/api/events');
    
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    return $data['ok'] ? $data['data'] : [];
}
?>
```

---

## Webhooks (Futuro)

Planejado para futuras versões:

- **Novos eventos** - Notificação quando novo evento é criado
- **Nova mensagem** - Notificação de mensagem em tempo real
- **Novo membro** - Notificação quando novo membro se registra

---

## Versionamento

Atualmente: **v1.0**

Mudanças futuras serão versionadas na URL:
```
/api/v2/endpoint
```

---

<div align="center">

**[⬆ Voltar ao Topo](#-documentação-da-api)**

**[📚 Documentação Principal](README.md)** | **[🇺🇸 English Version](../en/API.md)**

Para mais detalhes, consulte o [código fonte da API](../../api/)

</div>
