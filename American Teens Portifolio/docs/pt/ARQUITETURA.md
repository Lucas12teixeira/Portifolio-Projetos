# 🏗️ Arquitetura do Sistema

## Índice
- [Visão Geral](#visão-geral)
- [Design do Sistema](#design-do-sistema)
- [Arquitetura Frontend](#arquitetura-frontend)
- [Arquitetura Backend](#arquitetura-backend)
- [Esquema do Banco de Dados](#esquema-do-banco-de-dados)
- [Arquitetura de Segurança](#arquitetura-de-segurança)
- [Otimização de Performance](#otimização-de-performance)
- [Considerações de Escalabilidade](#considerações-de-escalabilidade)

---

## Visão Geral

American Teens é construído como uma **Progressive Web Application (PWA)** usando arquitetura **Single Page Application (SPA)** no frontend e uma **API RESTful** no backend alimentada por PHP e MySQL.

### Princípios Arquiteturais Chave

1. **Separação de Responsabilidades** - Limites claros entre camadas de apresentação, lógica de negócios e dados
2. **Modularidade** - Componentes fracamente acoplados e testáveis independentemente
3. **Progressive Enhancement** - Funcionalidade core funciona em todos os lugares, recursos avançados onde suportado
4. **Mobile-First** - Otimizado para dispositivos móveis com design responsivo
5. **Offline-First** - Funciona offline usando service workers e armazenamento local
6. **Segurança por Design** - Múltiplas camadas de controles de segurança

---

## Design do Sistema

### Arquitetura de Alto Nível

```
┌──────────────────────────────────────────────────────────────────┐
│                      Camada do Cliente                            │
├──────────────────────────────────────────────────────────────────┤
│  Navegador (Chrome, Firefox, Safari, Edge)                       │
│                                                                   │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────┐  │
│  │  Componentes UI  │  │  Service Worker  │  │Local Storage │  │
│  │    (Módulos)     │  │    (Caching)     │  │  (IndexedDB) │  │
│  └──────────────────┘  └──────────────────┘  └──────────────┘  │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │         App.js (Roteador SPA & Controlador)              │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ HTTPS (API REST)
                              │ Payloads JSON
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                     Camada de Aplicação                           │
├──────────────────────────────────────────────────────────────────┤
│  Servidor Web Apache/Nginx                                       │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           Backend PHP (API REST)                         │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │   │
│  │  │   Roteador  │  │Controladores│  │  Serviços   │     │   │
│  │  │ (index.php) │  │  (Módulos)  │  │  (JWT, DB)  │     │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘     │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ PDO (MySQL)
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                       Camada de Dados                             │
├──────────────────────────────────────────────────────────────────┤
│  Banco de Dados MySQL 5.7+                                       │
│                                                                   │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐  │
│  │ members │ │  chat   │ │ events  │ │  quiz   │ │  verses │  │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘  │
└──────────────────────────────────────────────────────────────────┘
```

---

## Arquitetura Frontend

### Estrutura de Componentes

O frontend segue uma **arquitetura modular** onde cada funcionalidade é encapsulada em seu próprio módulo.

```
App.js (Controlador Principal)
│
├─ Serviços Core
│  ├─ auth.js           - Serviço de autenticação
│  ├─ api.js            - Cliente HTTP
│  ├─ ui.js             - Utilitários de UI
│  ├─ session-guard.js  - Gerenciamento de sessão
│  └─ sync-manager.js   - Sincronização offline
│
├─ Módulos de Funcionalidades
│  ├─ chat.js           - Mensagens em tempo real
│  ├─ events.js         - Gerenciamento de eventos
│  ├─ bible.js          - Recursos bíblicos
│  ├─ quiz.js           - Quizzes interativos
│  ├─ wall.js           - Publicações da comunidade
│  ├─ profile.js        - Perfis de usuário
│  └─ admin.js          - Painel administrativo
│
└─ Recursos PWA
   ├─ sw.js             - Service Worker
   ├─ pwa-installer.js  - Prompt de instalação
   └─ version-manager.js - Notificações de atualização
```

### Padrão de Módulo

Cada módulo segue um padrão consistente:

```javascript
const NomeDoModulo = {
    // Estado privado
    state: {
        data: null,
        loading: false,
        error: null
    },

    // Inicialização
    async init() {
        this.setupEventListeners();
        await this.loadData();
        this.render();
    },

    // Renderizar UI
    render() {
        const container = document.getElementById('app-container');
        container.innerHTML = this.getHTML();
        this.bindEvents();
    },

    // Obter template HTML
    getHTML() {
        return `<div>...</div>`;
    },

    // Manipulação de eventos
    setupEventListeners() {
        // Listeners globais
    },

    bindEvents() {
        // Listeners locais
    },

    // Gerenciamento de dados
    async loadData() {
        try {
            this.state.loading = true;
            const response = await api.get('/endpoint');
            this.state.data = response.data;
        } catch (error) {
            this.state.error = error;
        } finally {
            this.state.loading = false;
        }
    },

    // API pública
    async fazerAlgo() {
        // Implementação
    }
};
```

### Gerenciamento de Estado

**Estado Local**: Cada módulo gerencia seu próprio estado
```javascript
const ChatModule = {
    state: {
        conversations: [],
        activeConversation: null,
        messages: [],
        pollingInterval: null
    }
};
```

**Estado Global**: Compartilhado entre módulos
```javascript
// auth.js
const auth = {
    currentUser: null,
    token: null,
    refreshToken: null
};
```

**Estado Persistente**: localStorage e IndexedDB
```javascript
// Dados em cache
localStorage.setItem('user', JSON.stringify(user));
localStorage.setItem('token', token);

// Fila offline
await db.offlineQueue.add({ method: 'POST', url: '/chat/send', data });
```

### Sistema de Roteamento

Roteamento client-side usando navegação baseada em hash:

```javascript
class App {
    static pages = {
        'auth': 'auth',
        'home': 'home',
        'chat': 'chat',
        'events': 'agenda',
        'bible': 'bible',
        'profile': 'profile',
        'admin': 'admin'
    };

    static handleNavigation() {
        const hash = window.location.hash.replace('#', '') || 'home';
        const page = this.pages[hash] || 'home';
        
        // Verificar autenticação
        if (page !== 'auth' && !auth.isAuthenticated()) {
            window.location.hash = '#auth';
            return;
        }
        
        // Carregar módulo da página
        this.loadPage(page);
    }

    static async loadPage(page) {
        this.currentPage = page;
        
        switch(page) {
            case 'chat':
                await ChatModule.init();
                break;
            case 'events':
                await AgendaModule.init();
                break;
            // ... outras páginas
        }
    }
}
```

### Estratégia do Service Worker

```javascript
// sw.js
const CACHE_NAME = 'americateens-v1.0.0';

// Instalação
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                '/',
                '/index.html',
                '/css/styles.css',
                '/js/app.js',
                '/manifest.json'
            ]);
        })
    );
});

// Estratégias de Fetch
self.addEventListener('fetch', event => {
    const { request } = event;
    
    // Chamadas API - Network first, cache fallback
    if (request.url.includes('/api/')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(request, clone);
                    });
                    return response;
                })
                .catch(() => caches.match(request))
        );
    }
    // Assets estáticos - Cache first, network fallback
    else {
        event.respondWith(
            caches.match(request)
                .then(response => response || fetch(request))
        );
    }
});
```

---

## Arquitetura Backend

### Estrutura da API

```
api/
├── index.php                 # Roteador principal
├── config.php               # Configuração
├── db.php                   # Conexão com banco de dados
├── jwt.php                  # Utilitários JWT
├── helpers.php              # Funções compartilhadas
│
├── auth/                    # Endpoints de autenticação
│   ├── login.php
│   ├── register.php
│   ├── refresh.php
│   └── reset_password.php
│
├── Endpoints de Módulos
│   ├── chat.php
│   ├── events.php
│   ├── members.php
│   ├── quiz-v2.php
│   ├── revelacao.php
│   └── verse-of-day-routes.php
│
└── sql/                     # Migrações do banco de dados
    ├── INSTALAR-TUDO.sql
    └── add-*.sql
```

### Fluxo de Requisição

```
1. Requisição do Cliente
   │
   ▼
2. Apache mod_rewrite
   │  - Roteia /api/* para /api/index.php
   │
   ▼
3. Roteador da API (index.php)
   │  - Analisa parâmetro ?path
   │  - Roteia para o módulo apropriado
   │
   ▼
4. Controlador do Módulo (ex: chat.php)
   │  - Valida token JWT
   │  - Extrai usuário do token
   │  - Roteia para função baseada no path
   │
   ▼
5. Lógica de Negócios
   │  - Validação de entrada
   │  - Operações no banco de dados
   │  - Regras de negócio
   │
   ▼
6. Camada de Banco de Dados
   │  - Prepared statements (PDO)
   │  - Execução de queries
   │  - Mapeamento de resultados
   │
   ▼
7. Resposta
   │  - Codificação JSON
   │  - Códigos de status HTTP
   │  - Tratamento de erros
   │
   ▼
8. Cliente recebe resposta
```

### Fluxo de Autenticação

```
┌─────────────┐
│   Cliente   │
└──────┬──────┘
       │
       │ 1. POST /api/auth/login
       │    { email, password }
       ▼
┌─────────────┐
│  login.php  │
└──────┬──────┘
       │
       │ 2. Verificar credenciais
       │    SELECT * FROM members WHERE email = ?
       │
       │ 3. Comparação de hash
       │    password_verify($password, $hash)
       │
       │ 4. Gerar tokens
       │    $token = jwt_encode([
       │        'id' => $user['id'],
       │        'email' => $user['email'],
       │        'exp' => time() + 3600
       │    ]);
       │
       │    $refreshToken = jwt_encode([
       │        'id' => $user['id'],
       │        'type' => 'refresh',
       │        'exp' => time() + 604800
       │    ]);
       │
       ▼
┌─────────────┐
│  Resposta   │
│ {           │
│   token,    │
│   refreshToken,
│   user      │
│ }           │
└──────┬──────┘
       │
       │ 5. Armazenar tokens
       │    localStorage.setItem('token', token)
       │    localStorage.setItem('refreshToken', refreshToken)
       │
       ▼
┌─────────────┐
│ Requisições │
│ Subsequentes│
│ Headers:    │
│ Authorization: Bearer <token>
└─────────────┘
```

### Padrão de Acesso ao Banco de Dados

```php
// db.php - Conexão
function getDB() {
    static $db = null;
    if ($db === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    }
    return $db;
}

// Uso em controladores
function getConversations($db, $userId) {
    // Prepared statement (previne SQL injection)
    $stmt = $db->prepare("
        SELECT 
            c.id as conversation_id,
            c.user1_id,
            c.user2_id,
            m1.name as user1_name,
            m2.name as user2_name,
            last_msg.message as last_message,
            last_msg.created_at as last_message_time,
            COUNT(unread.id) as unread_count
        FROM chat_conversations c
        INNER JOIN members m1 ON c.user1_id = m1.id
        INNER JOIN members m2 ON c.user2_id = m2.id
        LEFT JOIN chat_messages last_msg ON c.last_message_id = last_msg.id
        LEFT JOIN chat_messages unread 
            ON c.id = unread.conversation_id 
            AND unread.receiver_id = :user_id 
            AND unread.is_read = 0
        WHERE c.user1_id = :user_id OR c.user2_id = :user_id
        GROUP BY c.id
        ORDER BY last_msg.created_at DESC
    ");
    
    $stmt->execute(['user_id' => $userId]);
    $conversations = $stmt->fetchAll();
    
    return sendSuccess($conversations);
}
```

---

## Esquema do Banco de Dados

### Tabelas Core

#### Tabela Members
```sql
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    birthday DATE,
    local VARCHAR(255),
    role ENUM('member', 'coordinator', 'admin') DEFAULT 'member',
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_local (local)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Sistema de Chat
```sql
-- Conversas
CREATE TABLE chat_conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    last_message_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user1_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (user2_id) REFERENCES members(id) ON DELETE CASCADE,
    UNIQUE KEY unique_conversation (user1_id, user2_id),
    INDEX idx_users (user1_id, user2_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mensagens
CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (conversation_id) REFERENCES chat_conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_conversation (conversation_id, created_at),
    INDEX idx_unread (receiver_id, is_read),
    INDEX idx_sender (sender_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Status Online
CREATE TABLE chat_online_status (
    user_id INT PRIMARY KEY,
    is_online BOOLEAN DEFAULT 0,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuários Bloqueados
CREATE TABLE chat_blocked_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    blocker_id INT NOT NULL,
    blocked_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (blocker_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (blocked_id) REFERENCES members(id) ON DELETE CASCADE,
    UNIQUE KEY unique_block (blocker_id, blocked_id),
    INDEX idx_blocker (blocker_id),
    INDEX idx_blocked (blocked_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Sistema de Eventos
```sql
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    location VARCHAR(255),
    organizer_id INT NOT NULL,
    max_participants INT,
    image_url VARCHAR(255),
    is_featured BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (organizer_id) REFERENCES members(id),
    INDEX idx_date (event_date),
    INDEX idx_featured (is_featured, event_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE event_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, user_id),
    INDEX idx_event (event_id),
    INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Otimização do Banco de Dados

**Estratégia de Índices:**
- Primary keys em todas as tabelas
- Índices de foreign key para operações JOIN
- Índices compostos para padrões comuns de consulta
- Índices de cobertura para colunas de SELECT frequentes

**Otimização de Consultas:**
- Use EXPLAIN para analisar queries
- Evite SELECT * (especifique colunas)
- Use JOINs em vez de subqueries
- Implemente paginação para grandes conjuntos de dados
- Faça cache de consultas frequentes

---

## Arquitetura de Segurança

### Autenticação & Autorização

**Estrutura de Token JWT:**
```json
{
  "header": {
    "alg": "HS256",
    "typ": "JWT"
  },
  "payload": {
    "id": 123,
    "email": "usuario@exemplo.com",
    "role": "member",
    "iat": 1738656000,
    "exp": 1738659600
  },
  "signature": "..."
}
```

**Fluxo de Validação de Token:**
```php
function jwt_verify($token) {
    try {
        // Dividir token
        list($header, $payload, $signature) = explode('.', $token);
        
        // Verificar assinatura
        $valid_signature = base64_encode(
            hash_hmac('sha256', "$header.$payload", JWT_SECRET, true)
        );
        
        if ($signature !== $valid_signature) {
            return false;
        }
        
        // Decodificar payload
        $data = json_decode(base64_decode($payload), true);
        
        // Verificar expiração
        if ($data['exp'] < time()) {
            return false;
        }
        
        return $data;
    } catch (Exception $e) {
        return false;
    }
}
```

### Validação de Entrada

**Validação Server-Side:**
```php
function validateInput($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? null;
        
        // Verificação de obrigatório
        if ($rule['required'] && empty($value)) {
            $errors[$field] = "$field é obrigatório";
            continue;
        }
        
        // Verificação de tipo
        if (isset($rule['type'])) {
            switch ($rule['type']) {
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = "Formato de email inválido";
                    }
                    break;
                case 'int':
                    if (!is_numeric($value)) {
                        $errors[$field] = "$field deve ser um número";
                    }
                    break;
            }
        }
        
        // Verificação de comprimento
        if (isset($rule['max']) && strlen($value) > $rule['max']) {
            $errors[$field] = "$field é muito longo";
        }
    }
    
    return empty($errors) ? true : $errors;
}
```

### Prevenção de SQL Injection

**Sempre use prepared statements:**
```php
// ❌ PERIGOSO
$query = "SELECT * FROM members WHERE email = '{$_POST['email']}'";
$result = $db->query($query);

// ✅ SEGURO
$stmt = $db->prepare("SELECT * FROM members WHERE email = :email");
$stmt->execute(['email' => $_POST['email']]);
$result = $stmt->fetch();
```

### Prevenção de XSS

**Codificação de saída:**
```php
// Codificar HTML
function escapeHtml($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Uso
echo escapeHtml($user['name']);
```

```javascript
// Client-side
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
```

### Configuração CORS

```php
// Restringir origens permitidas em produção
$allowed_origins = ['https://americateens.erldev.com.br'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: *"); // Apenas desenvolvimento
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400'); // Cache preflight por 24 horas
```

---

## Otimização de Performance

### Otimização Frontend

**1. Lazy Loading**
```javascript
// Carregar módulos sob demanda
static async loadPage(page) {
    if (!this.loadedModules[page]) {
        const module = await import(`./modules/${page}.js`);
        this.loadedModules[page] = module;
    }
    await this.loadedModules[page].init();
}
```

**2. Debouncing**
```javascript
// Input de pesquisa
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

const searchUsers = debounce(async (query) => {
    const results = await api.get(`/chat/search-users?q=${query}`);
    displayResults(results);
}, 300);
```

**3. Caching**
```javascript
// Cache de respostas da API
const cache = {
    data: {},
    timestamp: {},
    
    set(key, value, ttl = 300000) { // 5 minutos
        this.data[key] = value;
        this.timestamp[key] = Date.now() + ttl;
    },
    
    get(key) {
        if (this.timestamp[key] && Date.now() < this.timestamp[key]) {
            return this.data[key];
        }
        return null;
    }
};
```

### Otimização Backend

**1. Otimização de Consultas**
```php
// Usar índices
CREATE INDEX idx_search ON members(name, local);

// Limitar resultados
SELECT * FROM events WHERE event_date > NOW() 
ORDER BY event_date ASC LIMIT 20;

// Evitar consultas N+1
// Ruim: Loop e consulta para cada
foreach ($conversations as $conv) {
    $user = $db->query("SELECT * FROM members WHERE id = {$conv['user_id']}");
}

// Bom: Consulta única com JOIN
$conversations = $db->query("
    SELECT c.*, m.name, m.avatar 
    FROM chat_conversations c
    LEFT JOIN members m ON c.user2_id = m.id
    WHERE c.user1_id = $userId
");
```

**2. Compressão de Resposta**
```php
// Habilitar compressão gzip
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
    ob_start('ob_gzhandler');
}
```

**3. Opcache**
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

---

## Considerações de Escalabilidade

### Escalabilidade Horizontal

**Load Balancing:**
```
        ┌─────────────────┐
        │ Load Balancer   │
        └────────┬────────┘
                 │
         ┌───────┼───────┐
         │       │       │
     ┌───▼──┐ ┌──▼──┐ ┌─▼───┐
     │Web 1 │ │Web 2│ │Web 3│
     └───┬──┘ └──┬──┘ └─┬───┘
         │       │      │
         └───────┼──────┘
                 │
          ┌──────▼──────┐
          │Banco de Dados│
          │   (Master)  │
          └──────┬──────┘
                 │
         ┌───────┼───────┐
     ┌───▼──┐ ┌──▼──┐
     │Slave1│ │Slave2│
     └──────┘ └──────┘
```

### Estratégia de Caching

**Caching Multi-Nível:**
```
Cliente (Cache do Navegador)
    ↓
Cache do Service Worker
    ↓
Cache CDN (Assets Estáticos)
    ↓
Cache de Aplicação (Redis/Memcached)
    ↓
Cache de Consultas do Banco
    ↓
Banco de Dados
```

### Melhorias Futuras

1. **Integração WebSocket** - Comunicação bidirecional em tempo real
2. **Redis/Memcached** - Camada de cache distribuído
3. **Integração CDN** - Entrega global de assets
4. **Microserviços** - Dividir funcionalidades em serviços independentes
5. **Fila de Mensagens** - Processamento assíncrono de tarefas (RabbitMQ, Redis Queue)
6. **ElasticSearch** - Capacidades de busca full-text
7. **Docker** - Containerização para deploy facilitado
8. **Pipeline CI/CD** - Testes e deploy automatizados

---

## Monitoramento & Logging

### Logging de Erros

```php
// Logging de erros PHP
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');

// Logging customizado
function logError($message, $context = []) {
    $log = [
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => $message,
        'context' => $context,
        'trace' => debug_backtrace()
    ];
    file_put_contents(
        __DIR__ . '/logs/app-errors.log',
        json_encode($log) . PHP_EOL,
        FILE_APPEND
    );
}
```

### Monitoramento de Performance

```javascript
// Performance client-side
const perfData = performance.getEntriesByType('navigation')[0];
console.log('Tempo de Carregamento:', perfData.loadEventEnd - perfData.fetchStart);
console.log('DOM Pronto:', perfData.domContentLoadedEventEnd - perfData.fetchStart);

// Timing de chamadas API
const startTime = performance.now();
await api.get('/data');
const endTime = performance.now();
console.log(`Chamada API levou ${endTime - startTime}ms`);
```

---

<div align="center">

**[⬆ Voltar ao Topo](#-arquitetura-do-sistema)**

**[📚 Documentação Principal](README.md)** | **[🇺🇸 English Version](../en/ARCHITECTURE.md)**

</div>
