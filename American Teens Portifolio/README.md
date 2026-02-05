# Portifolio-Projetos
**Este Portifolio contem a Descrição dos meus projetos***

# 🌟 American Teens - Digital Community Platform

<div align="center">

![American Teens Logo](assets/icons/iconAmerica.png)

**A Progressive Web Application for young church community management**

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](docs/en/CONTRIBUTING.md)
[![Made with Love](https://img.shields.io/badge/Made%20with-❤️-red.svg)](https://github.com)

[Demo](https://americateens.erldev.com.br) • [📚 Documentation](docs/) • [🇺🇸 English](docs/en/) • [🇧🇷 Português](docs/pt/)

---

## 🌍 Choose Your Language / Escolha seu Idioma

### [🇺🇸 Read in English](docs/en/README.md)
Complete documentation in English for international audience.

### [🇧🇷 Ler em Português](docs/pt/README.md)
Documentação completa em português para o público brasileiro.

---

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Architecture](#-architecture)
- [Getting Started](#-getting-started)
- [Project Structure](#-project-structure)
- [API Documentation](#-api-documentation)
- [Performance](#-performance)
- [Security](#-security)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🎯 Overview

**American Teens** is a comprehensive digital platform designed for youth ministry communities. Built as a Progressive Web App (PWA), it combines social networking features with spiritual growth tools, creating an engaging and secure environment for young members to connect, learn, and grow together.

### 🌍 Purpose

This platform addresses the modern need for digital community engagement in religious youth groups, providing:
- **Secure Communication**: Real-time chat with privacy controls
- **Community Building**: Profile management, event coordination, and group walls
- **Spiritual Growth**: Daily Bible verses, scripture search, and study tools
- **Member Management**: Administrative tools for coordinators and leaders

---

## ✨ Features

### 🔐 Authentication & Authorization
- **Secure JWT-based authentication** with refresh token support
- **Role-based access control** (Admin, Coordinator, Member)
- **Password recovery system** with email verification
- **Session management** with automatic token refresh

### 💬 Real-Time Chat System
- **One-on-one messaging** with typing indicators
- **Online/offline status tracking**
- **Message read receipts**
- **User blocking capabilities**
- **Conversation search and filtering**
- **File sharing support** (images, documents)
- **Real-time polling** for instant message delivery

### 👥 Member Management
- **User profiles** with avatars and personal information
- **Birthday tracking and notifications**
- **Local church association**
- **Member directory** with search functionality

### 📅 Event Management
- **Event creation and scheduling**
- **Event registration system**
- **Featured events showcase**
- **Calendar integration**
- **Event reminders**

### 📖 Bible Integration
- **Daily verse of the day** with automatic scheduling
- **Complete KJV Bible search** (66 books, 1,189 chapters)
- **Verse bookmarking**
- **Share verses** to social media

### 📢 Community Wall
- **Public posts and announcements**
- **Like and comment system**
- **Multimedia support** (images, videos)
- **Moderation tools**

### 🎮 Interactive Quiz System
- **Biblical trivia quizzes**
- **Multiple difficulty levels**
- **Score tracking and leaderboards**
- **Real-time feedback**

### 📱 PWA Features
- **Offline support** with service worker caching
- **Install to home screen**
- **Push notifications** (coming soon)
- **Background sync** for offline actions
- **Responsive design** (mobile-first approach)

---

## 🛠 Tech Stack

### **Frontend**
- **Pure Vanilla JavaScript** - No framework dependencies for maximum performance
- **ES6+ Features** - Modern JavaScript with classes and async/await
- **CSS3 Custom Properties** - Dynamic theming system
- **Progressive Web App** - Service Worker with workbox strategies
- **Responsive Design** - Mobile-first approach with CSS Grid & Flexbox

### **Backend**
- **PHP 7.4+** - Server-side logic
- **MySQL 5.7+** - Relational database
- **RESTful API** - JSON-based communication
- **JWT Authentication** - Secure token-based auth

### **Infrastructure**
- **KingHost Hosting** - Production environment
- **Apache Server** - Web server
- **SSL/HTTPS** - Secure connections
- **Git** - Version control

### **Development Tools**
- **VS Code** - Primary IDE
- **Chrome DevTools** - Debugging and performance analysis
- **Postman** - API testing
- **Git** - Version control

---

## 🏗 Architecture

### **Single Page Application (SPA)**
```
┌─────────────────────────────────────────────────────────┐
│                     Client (Browser)                     │
├─────────────────────────────────────────────────────────┤
│  App.js (Router)                                        │
│    ├─ Auth Module                                        │
│    ├─ Chat Module                                        │
│    ├─ Events Module                                      │
│    ├─ Bible Module                                       │
│    ├─ Quiz Module                                        │
│    ├─ Wall Module                                        │
│    └─ Profile Module                                     │
├─────────────────────────────────────────────────────────┤
│  Service Worker (Offline Caching)                       │
└─────────────────────────────────────────────────────────┘
                          ↕ HTTPS
┌─────────────────────────────────────────────────────────┐
│                    REST API (PHP)                        │
├─────────────────────────────────────────────────────────┤
│  api/index.php (Router)                                 │
│    ├─ auth/*                                            │
│    ├─ chat.php                                          │
│    ├─ events.php                                        │
│    ├─ members.php                                       │
│    ├─ quiz-v2.php                                       │
│    └─ verse-of-day-routes.php                          │
└─────────────────────────────────────────────────────────┘
                          ↕
┌─────────────────────────────────────────────────────────┐
│                    MySQL Database                        │
├─────────────────────────────────────────────────────────┤
│  ├─ members                                             │
│  ├─ chat_conversations                                  │
│  ├─ chat_messages                                       │
│  ├─ events                                              │
│  ├─ quiz_questions                                      │
│  ├─ revelacao (testimony system)                        │
│  └─ verses_of_the_day                                   │
└─────────────────────────────────────────────────────────┘
```

### **Key Design Patterns**
- **Module Pattern** - Encapsulated functionality in separate modules
- **Observer Pattern** - Event-driven communication between components
- **Repository Pattern** - Database abstraction layer
- **Factory Pattern** - Dynamic component creation
- **Singleton Pattern** - Single instance services (Auth, API Client)

---

## 🚀 Getting Started

### Prerequisites
```bash
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional, for dependencies)
- Modern web browser (Chrome, Firefox, Safari, Edge)
```

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/american-teens.git
cd american-teens
```

2. **Configure database**
```bash
# Create MySQL database
mysql -u root -p
CREATE DATABASE americateens;

# Import schema
mysql -u root -p americateens < api/sql/INSTALAR-TUDO.sql
```

3. **Configure environment**
```php
# Edit api/config.php with your credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'americateens');

# Generate secure JWT secret
define('JWT_SECRET', 'your-256-bit-secret-key');
```

4. **Configure web server**
```apache
# Apache .htaccess (included)
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^api/(.*)$ api/index.php?path=$1 [QSA,L]
```

5. **Launch the application**
```bash
# Development server (PHP built-in)
php -S localhost:8000

# Production: Configure virtual host
# Point document root to project folder
# Enable SSL certificate
```

6. **Access the application**
```
http://localhost:8000
```

### First User Setup
```
Navigate to /#auth
Create first account - will be granted admin privileges
```

For detailed installation instructions, see [INSTALLATION.md](INSTALLATION.md)

---

## 📁 Project Structure

```
american-teens/
├── 📱 index.html                    # Main entry point
├── 📋 manifest.json                 # PWA manifest
├── ⚙️ sw.js                         # Service Worker
├── 📝 config.js                     # Client configuration
│
├── 🎨 css/                          # Stylesheets
│   ├── variables.css                # CSS custom properties
│   ├── styles.css                   # Global styles
│   ├── modules.css                  # Module-specific styles
│   ├── responsive.css               # Media queries
│   └── theme-overrides.css          # Theme customization
│
├── 🧩 js/                           # JavaScript modules
│   ├── app.js                       # Main application controller
│   ├── auth.js                      # Authentication service
│   ├── api.js                       # API client
│   ├── ui.js                        # UI utilities
│   ├── session-guard.js             # Session management
│   ├── sync-manager.js              # Offline sync
│   ├── version-manager.js           # Version control
│   └── modules/                     # Feature modules
│       ├── chat.js                  # Chat system
│       ├── events.js                # Event management
│       ├── bible.js                 # Bible features
│       ├── quiz.js                  # Quiz system
│       ├── wall.js                  # Community wall
│       └── profile.js               # User profiles
│
├── 🔌 api/                          # Backend API
│   ├── index.php                    # API router
│   ├── config.php                   # Server configuration
│   ├── db.php                       # Database connection
│   ├── jwt.php                      # JWT utilities
│   ├── helpers.php                  # Helper functions
│   ├── chat.php                     # Chat endpoints
│   ├── events.php                   # Event endpoints
│   ├── members.php                  # Member endpoints
│   ├── quiz-v2.php                  # Quiz endpoints
│   ├── verse-of-day-routes.php      # Bible verse endpoints
│   ├── VerseOfDayManager.php        # Verse scheduling
│   │
│   ├── auth/                        # Authentication endpoints
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── refresh.php
│   │   ├── reset_password.php
│   │   └── change-password.php
│   │
│   └── sql/                         # Database schemas
│       ├── INSTALAR-TUDO.sql        # Complete installation
│       ├── chat-schema.sql          # Chat tables
│       ├── fix-*.sql                # Migration scripts
│       └── add-*.sql                # Feature additions
│
├── 🖼️ assets/                       # Static assets
│   ├── icons/                       # App icons (PWA)
│   └── images/                      # Images and media
│
├── 📊 data/                         # Static data
│   ├── kjv.json                     # Complete KJV Bible
│   └── demo-data.js                 # Demo data for testing
│
└── 📚 docs/                         # Documentation
    ├── ARCHITECTURE.md              # System architecture
    ├── API.md                       # API documentation
    ├── INSTALLATION.md              # Installation guide
    └── CONTRIBUTING.md              # Contribution guidelines
```

---

## 📡 API Documentation

### **Authentication Endpoints**

#### Register New User
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass123!",
  "local": "New York Church",
  "birthday": "2000-01-15"
}

Response: 201 Created
{
  "ok": true,
  "token": "eyJhbGc...",
  "refreshToken": "eyJhbGc...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "SecurePass123!"
}

Response: 200 OK
{
  "ok": true,
  "token": "eyJhbGc...",
  "refreshToken": "eyJhbGc...",
  "user": { ... }
}
```

### **Chat Endpoints**

#### Get Conversations
```http
GET /api/chat/conversations
Authorization: Bearer eyJhbGc...

Response: 200 OK
{
  "ok": true,
  "data": [
    {
      "conversation_id": 1,
      "other_user": {
        "id": 2,
        "name": "Jane Smith",
        "avatar": "/uploads/avatars/2.jpg",
        "is_online": true
      },
      "last_message": {
        "message": "Hello!",
        "created_at": "2026-02-04T10:30:00Z",
        "is_read": true
      },
      "unread_count": 0
    }
  ]
}
```

#### Send Message
```http
POST /api/chat/send
Authorization: Bearer eyJhbGc...
Content-Type: application/json

{
  "other_user_id": 2,
  "message": "Hello, how are you?"
}

Response: 200 OK
{
  "ok": true,
  "message_id": 123,
  "conversation_id": 1,
  "created_at": "2026-02-04T10:30:00Z"
}
```

For complete API documentation, see [API.md](API.md)

---

## ⚡ Performance

### **Optimization Strategies**
- **Code Splitting** - Lazy loading of modules
- **Image Optimization** - WebP format with fallbacks
- **Caching Strategy** - Service Worker with stale-while-revalidate
- **Database Indexing** - Optimized queries with proper indexes
- **Minification** - Production builds with minified assets
- **CDN Integration** - Static asset delivery

### **Performance Metrics**
```
Lighthouse Scores (Mobile):
├─ Performance: 92/100
├─ Accessibility: 95/100
├─ Best Practices: 100/100
└─ SEO: 100/100

Load Times:
├─ First Contentful Paint: < 1.5s
├─ Time to Interactive: < 3.0s
└─ Total Page Size: < 500KB (gzipped)
```

---

## 🔒 Security

### **Implemented Security Measures**

✅ **Authentication**
- JWT-based authentication with secure signing
- Refresh token rotation
- Password hashing with bcrypt (cost factor 12)
- Session timeout and automatic logout

✅ **Input Validation**
- Server-side validation for all inputs
- SQL injection prevention (prepared statements)
- XSS protection (output encoding)
- CSRF token validation

✅ **API Security**
- CORS configuration
- Rate limiting
- Request size limits
- Authorization checks on all endpoints

✅ **Data Protection**
- HTTPS enforcement
- Secure cookie flags (HttpOnly, Secure, SameSite)
- Database credential encryption
- User data anonymization options

✅ **Privacy**
- User blocking functionality
- Content moderation tools
- GDPR compliance ready
- Data export capabilities

---

## 🧪 Testing

### **Testing Strategy**
```bash
# Manual testing tools included
├─ test-chat-send.html          # Chat functionality tests
├─ test-profile-console.html    # Profile system tests
├─ diagnostico-chat-completo.html # Chat diagnostic tool
└─ monitor-performance.html     # Performance monitoring
```

### **Test Coverage Areas**
- ✅ Authentication flows
- ✅ Chat message delivery
- ✅ Event CRUD operations
- ✅ Bible search functionality
- ✅ Quiz question handling
- ✅ Offline mode behavior
- ✅ PWA installation process

---

## 🌐 Browser Support

| Browser | Version | Status |
|---------|---------|--------|
| Chrome  | 90+     | ✅ Full support |
| Firefox | 88+     | ✅ Full support |
| Safari  | 14+     | ✅ Full support |
| Edge    | 90+     | ✅ Full support |
| Opera   | 76+     | ✅ Full support |
| Mobile browsers | Latest | ✅ Full support |

---

## 🤝 Contributing

We welcome contributions from developers of all skill levels! Please read our [Contributing Guidelines](CONTRIBUTING.md) for details on:
- Code of Conduct
- Development process
- How to submit pull requests
- Coding standards
- Testing requirements

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- LinkedIn: [Your LinkedIn](https://linkedin.com/in/yourprofile)
- Email: your.email@example.com
- Portfolio: [yourportfolio.com](https://yourportfolio.com)

---

## 🙏 Acknowledgments

- **Assembleia de Deus** - For the inspiration and community support
- **Open Source Community** - For the amazing tools and libraries
- **Contributors** - Everyone who has contributed to this project

---

## 📊 Project Stats

![GitHub stars](https://img.shields.io/github/stars/yourusername/american-teens?style=social)
![GitHub forks](https://img.shields.io/github/forks/yourusername/american-teens?style=social)
![GitHub issues](https://img.shields.io/github/issues/yourusername/american-teens)
![GitHub pull requests](https://img.shields.io/github/issues-pr/yourusername/american-teens)

---

<div align="center">

**[⬆ Back to Top](#-american-teens---digital-community-platform)**

Made with ❤️ for the youth community

</div>
