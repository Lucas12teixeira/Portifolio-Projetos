# 🏗️ System Architecture

## Table of Contents
- [Overview](#overview)
- [System Design](#system-design)
- [Frontend Architecture](#frontend-architecture)
- [Backend Architecture](#backend-architecture)
- [Database Schema](#database-schema)
- [Security Architecture](#security-architecture)
- [Performance Optimization](#performance-optimization)
- [Scalability Considerations](#scalability-considerations)

---

## Overview

American Teens is built as a **Progressive Web Application (PWA)** using a **Single Page Application (SPA)** architecture on the frontend and a **RESTful API** backend powered by PHP and MySQL.

### Key Architectural Principles

1. **Separation of Concerns** - Clear boundaries between presentation, business logic, and data layers
2. **Modularity** - Loosely coupled, independently testable components
3. **Progressive Enhancement** - Core functionality works everywhere, enhanced features where supported
4. **Mobile-First** - Optimized for mobile devices with responsive design
5. **Offline-First** - Works offline using service workers and local storage
6. **Security by Design** - Multiple layers of security controls

---

## System Design

### High-Level Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                         Client Layer                              │
├──────────────────────────────────────────────────────────────────┤
│  Browser (Chrome, Firefox, Safari, Edge)                         │
│                                                                   │
│  ┌──────────────────┐  ┌──────────────────┐  ┌──────────────┐  │
│  │   UI Components  │  │  Service Worker  │  │ Local Storage│  │
│  │   (Modules)      │  │  (Caching)       │  │ (IndexedDB)  │  │
│  └──────────────────┘  └──────────────────┘  └──────────────┘  │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │              App.js (SPA Router & Controller)            │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ HTTPS (REST API)
                              │ JSON Payloads
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                      Application Layer                            │
├──────────────────────────────────────────────────────────────────┤
│  Apache/Nginx Web Server                                         │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │           PHP Backend (REST API)                         │   │
│  │  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐     │   │
│  │  │   Router    │  │ Controllers │  │   Services  │     │   │
│  │  │ (index.php) │  │  (Modules)  │  │  (JWT, DB)  │     │   │
│  │  └─────────────┘  └─────────────┘  └─────────────┘     │   │
│  └──────────────────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ PDO (MySQL)
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                        Data Layer                                 │
├──────────────────────────────────────────────────────────────────┤
│  MySQL 5.7+ Database                                             │
│                                                                   │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐  │
│  │ members │ │  chat   │ │ events  │ │  quiz   │ │  verses │  │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘  │
└──────────────────────────────────────────────────────────────────┘
```

---

## Frontend Architecture

### Component Structure

The frontend follows a **modular architecture** where each feature is encapsulated in its own module.

```
App.js (Main Controller)
│
├─ Core Services
│  ├─ auth.js           - Authentication service
│  ├─ api.js            - HTTP client
│  ├─ ui.js             - UI utilities
│  ├─ session-guard.js  - Session management
│  └─ sync-manager.js   - Offline sync
│
├─ Feature Modules
│  ├─ chat.js           - Real-time messaging
│  ├─ events.js         - Event management
│  ├─ bible.js          - Scripture features
│  ├─ quiz.js           - Interactive quizzes
│  ├─ wall.js           - Community posts
│  ├─ profile.js        - User profiles
│  └─ admin.js          - Admin panel
│
└─ PWA Features
   ├─ sw.js             - Service Worker
   ├─ pwa-installer.js  - Installation prompt
   └─ version-manager.js - Update notifications
```

### Module Pattern

Each module follows a consistent pattern:

```javascript
const ModuleName = {
    // Private state
    state: {
        data: null,
        loading: false,
        error: null
    },

    // Initialization
    async init() {
        this.setupEventListeners();
        await this.loadData();
        this.render();
    },

    // Render UI
    render() {
        const container = document.getElementById('app-container');
        container.innerHTML = this.getHTML();
        this.bindEvents();
    },

    // Get HTML template
    getHTML() {
        return `<div>...</div>`;
    },

    // Event handling
    setupEventListeners() {
        // Global listeners
    },

    bindEvents() {
        // Local listeners
    },

    // Data management
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

    // Public API
    async doSomething() {
        // Implementation
    }
};
```

### State Management

**Local State**: Each module manages its own state
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

**Global State**: Shared across modules
```javascript
// auth.js
const auth = {
    currentUser: null,
    token: null,
    refreshToken: null
};
```

**Persistent State**: localStorage and IndexedDB
```javascript
// Cached data
localStorage.setItem('user', JSON.stringify(user));
localStorage.setItem('token', token);

// Offline queue
await db.offlineQueue.add({ method: 'POST', url: '/chat/send', data });
```

### Routing System

Client-side routing using hash-based navigation:

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
        
        // Check authentication
        if (page !== 'auth' && !auth.isAuthenticated()) {
            window.location.hash = '#auth';
            return;
        }
        
        // Load page module
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
            // ... other pages
        }
    }
}
```

### Service Worker Strategy

```javascript
// sw.js
const CACHE_NAME = 'americateens-v1.0.0';

// Installation
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

// Fetch strategies
self.addEventListener('fetch', event => {
    const { request } = event;
    
    // API calls - Network first, cache fallback
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
    // Static assets - Cache first, network fallback
    else {
        event.respondWith(
            caches.match(request)
                .then(response => response || fetch(request))
        );
    }
});
```

---

## Backend Architecture

### API Structure

```
api/
├── index.php                 # Main router
├── config.php               # Configuration
├── db.php                   # Database connection
├── jwt.php                  # JWT utilities
├── helpers.php              # Shared functions
│
├── auth/                    # Authentication endpoints
│   ├── login.php
│   ├── register.php
│   ├── refresh.php
│   └── reset_password.php
│
├── Module Endpoints
│   ├── chat.php
│   ├── events.php
│   ├── members.php
│   ├── quiz-v2.php
│   ├── revelacao.php
│   └── verse-of-day-routes.php
│
└── sql/                     # Database migrations
    ├── INSTALAR-TUDO.sql
    └── add-*.sql
```

### Request Flow

```
1. Client Request
   │
   ▼
2. Apache mod_rewrite
   │  - Routes /api/* to /api/index.php
   │
   ▼
3. API Router (index.php)
   │  - Parses ?path parameter
   │  - Routes to appropriate module
   │
   ▼
4. Module Controller (e.g., chat.php)
   │  - Validates JWT token
   │  - Extracts user from token
   │  - Routes to function based on path
   │
   ▼
5. Business Logic
   │  - Input validation
   │  - Database operations
   │  - Business rules
   │
   ▼
6. Database Layer
   │  - Prepared statements (PDO)
   │  - Query execution
   │  - Result mapping
   │
   ▼
7. Response
   │  - JSON encoding
   │  - HTTP status codes
   │  - Error handling
   │
   ▼
8. Client receives response
```

### Authentication Flow

```
┌─────────────┐
│   Client    │
└──────┬──────┘
       │
       │ 1. POST /api/auth/login
       │    { email, password }
       ▼
┌─────────────┐
│  login.php  │
└──────┬──────┘
       │
       │ 2. Verify credentials
       │    SELECT * FROM members WHERE email = ?
       │
       │ 3. Hash comparison
       │    password_verify($password, $hash)
       │
       │ 4. Generate tokens
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
│  Response   │
│ {           │
│   token,    │
│   refreshToken,
│   user      │
│ }           │
└──────┬──────┘
       │
       │ 5. Store tokens
       │    localStorage.setItem('token', token)
       │    localStorage.setItem('refreshToken', refreshToken)
       │
       ▼
┌─────────────┐
│ Subsequent  │
│  Requests   │
│ Headers:    │
│ Authorization: Bearer <token>
└─────────────┘
```

### Database Access Pattern

```php
// db.php - Connection
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

// Usage in controllers
function getConversations($db, $userId) {
    // Prepared statement (prevents SQL injection)
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

## Database Schema

### Core Tables

#### Members Table
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

#### Chat System
```sql
-- Conversations
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

-- Messages
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

-- Online Status
CREATE TABLE chat_online_status (
    user_id INT PRIMARY KEY,
    is_online BOOLEAN DEFAULT 0,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Blocked Users
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

#### Events System
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

### Database Optimization

**Indexes Strategy:**
- Primary keys on all tables
- Foreign key indexes for JOIN operations
- Composite indexes for common query patterns
- Covering indexes for frequent SELECT columns

**Query Optimization:**
- Use EXPLAIN to analyze queries
- Avoid SELECT * (specify columns)
- Use JOINs instead of subqueries
- Implement pagination for large datasets
- Cache frequent queries

---

## Security Architecture

### Authentication & Authorization

**JWT Token Structure:**
```json
{
  "header": {
    "alg": "HS256",
    "typ": "JWT"
  },
  "payload": {
    "id": 123,
    "email": "user@example.com",
    "role": "member",
    "iat": 1738656000,
    "exp": 1738659600
  },
  "signature": "..."
}
```

**Token Validation Flow:**
```php
function jwt_verify($token) {
    try {
        // Split token
        list($header, $payload, $signature) = explode('.', $token);
        
        // Verify signature
        $valid_signature = base64_encode(
            hash_hmac('sha256', "$header.$payload", JWT_SECRET, true)
        );
        
        if ($signature !== $valid_signature) {
            return false;
        }
        
        // Decode payload
        $data = json_decode(base64_decode($payload), true);
        
        // Check expiration
        if ($data['exp'] < time()) {
            return false;
        }
        
        return $data;
    } catch (Exception $e) {
        return false;
    }
}
```

### Input Validation

**Server-Side Validation:**
```php
function validateInput($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? null;
        
        // Required check
        if ($rule['required'] && empty($value)) {
            $errors[$field] = "$field is required";
            continue;
        }
        
        // Type check
        if (isset($rule['type'])) {
            switch ($rule['type']) {
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field] = "Invalid email format";
                    }
                    break;
                case 'int':
                    if (!is_numeric($value)) {
                        $errors[$field] = "$field must be a number";
                    }
                    break;
            }
        }
        
        // Length check
        if (isset($rule['max']) && strlen($value) > $rule['max']) {
            $errors[$field] = "$field is too long";
        }
    }
    
    return empty($errors) ? true : $errors;
}
```

### SQL Injection Prevention

**Always use prepared statements:**
```php
// ❌ DANGEROUS
$query = "SELECT * FROM members WHERE email = '{$_POST['email']}'";
$result = $db->query($query);

// ✅ SAFE
$stmt = $db->prepare("SELECT * FROM members WHERE email = :email");
$stmt->execute(['email' => $_POST['email']]);
$result = $stmt->fetch();
```

### XSS Prevention

**Output encoding:**
```php
// Encode HTML
function escapeHtml($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Usage
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

### CORS Configuration

```php
// Restrict allowed origins in production
$allowed_origins = ['https://americateens.erldev.com.br'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: *"); // Development only
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400'); // Cache preflight for 24 hours
```

---

## Performance Optimization

### Frontend Optimization

**1. Lazy Loading**
```javascript
// Load modules on demand
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
// Search input
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
// Cache API responses
const cache = {
    data: {},
    timestamp: {},
    
    set(key, value, ttl = 300000) { // 5 minutes
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

### Backend Optimization

**1. Query Optimization**
```php
// Use indexes
CREATE INDEX idx_search ON members(name, local);

// Limit results
SELECT * FROM events WHERE event_date > NOW() 
ORDER BY event_date ASC LIMIT 20;

// Avoid N+1 queries
// Bad: Loop and query for each
foreach ($conversations as $conv) {
    $user = $db->query("SELECT * FROM members WHERE id = {$conv['user_id']}");
}

// Good: Single query with JOIN
$conversations = $db->query("
    SELECT c.*, m.name, m.avatar 
    FROM chat_conversations c
    LEFT JOIN members m ON c.user2_id = m.id
    WHERE c.user1_id = $userId
");
```

**2. Response Compression**
```php
// Enable gzip compression
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

## Scalability Considerations

### Horizontal Scaling

**Load Balancing:**
```
        ┌─────────────┐
        │Load Balancer│
        └──────┬──────┘
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
        │  Database   │
        │   (Master)  │
        └──────┬──────┘
               │
       ┌───────┼───────┐
   ┌───▼──┐ ┌──▼──┐
   │Slave1│ │Slave2│
   └──────┘ └──────┘
```

### Caching Strategy

**Multi-Level Caching:**
```
Client (Browser Cache)
    ↓
Service Worker Cache
    ↓
CDN Cache (Static Assets)
    ↓
Application Cache (Redis/Memcached)
    ↓
Database Query Cache
    ↓
Database
```

### Future Enhancements

1. **WebSocket Integration** - Real-time bidirectional communication
2. **Redis/Memcached** - Distributed caching layer
3. **CDN Integration** - Global asset delivery
4. **Microservices** - Split features into independent services
5. **Message Queue** - Async task processing (RabbitMQ, Redis Queue)
6. **ElasticSearch** - Full-text search capabilities
7. **Docker** - Containerization for easy deployment
8. **CI/CD Pipeline** - Automated testing and deployment

---

## Monitoring & Logging

### Error Logging

```php
// PHP error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');

// Custom logging
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

### Performance Monitoring

```javascript
// Client-side performance
const perfData = performance.getEntriesByType('navigation')[0];
console.log('Page Load Time:', perfData.loadEventEnd - perfData.fetchStart);
console.log('DOM Ready:', perfData.domContentLoadedEventEnd - perfData.fetchStart);

// API call timing
const startTime = performance.now();
await api.get('/data');
const endTime = performance.now();
console.log(`API call took ${endTime - startTime}ms`);
```

---

<div align="center">

**[⬆ Back to Top](#-system-architecture)**

</div>
