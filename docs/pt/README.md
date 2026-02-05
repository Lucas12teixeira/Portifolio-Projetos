# 🌟 American Teens - Plataforma Digital Comunitária

<div align="center">

![Logo American Teens](../../assets/icons/iconAmerica.png)

**Uma Aplicação Web Progressiva para gestão de comunidades jovens cristãs**

[![Licença](https://img.shields.io/badge/licença-MIT-blue.svg)](../../LICENSE)
[![PRs Bem-vindos](https://img.shields.io/badge/PRs-bem--vindos-brightgreen.svg)](CONTRIBUINDO.md)
[![Feito com Amor](https://img.shields.io/badge/Feito%20com-❤️-red.svg)](https://github.com)

[Demo](https://americateens.erldev.com.br) • [Documentação](../) • [Referência da API](API.md) • [Contribuir](CONTRIBUINDO.md)

</div>

---

## 📋 Índice

- [Visão Geral](#-visão-geral)
- [Funcionalidades](#-funcionalidades)
- [Stack Tecnológica](#-stack-tecnológica)
- [Arquitetura](#-arquitetura)
- [Começando](#-começando)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Documentação da API](#-documentação-da-api)
- [Performance](#-performance)
- [Segurança](#-segurança)
- [Contribuindo](#-contribuindo)
- [Licença](#-licença)

---

## 🎯 Visão Geral

**American Teens** é uma plataforma digital abrangente projetada para comunidades jovens de ministérios religiosos. Construída como uma Progressive Web App (PWA), combina recursos de redes sociais com ferramentas de crescimento espiritual, criando um ambiente engajador e seguro para jovens membros se conectarem, aprenderem e crescerem juntos.

### 🌍 Propósito

Esta plataforma atende à necessidade moderna de engajamento digital em grupos jovens religiosos, fornecendo:
- **Comunicação Segura**: Chat em tempo real com controles de privacidade
- **Construção de Comunidade**: Gestão de perfis, coordenação de eventos e mural de grupo
- **Crescimento Espiritual**: Versículos bíblicos diários, busca nas escrituras e ferramentas de estudo
- **Gestão de Membros**: Ferramentas administrativas para coordenadores e líderes

---

## ✨ Funcionalidades

### 🔐 Autenticação & Autorização
- **Autenticação segura baseada em JWT** com suporte a refresh token
- **Controle de acesso baseado em funções** (Admin, Coordenador, Membro)
- **Sistema de recuperação de senha** com verificação por e-mail
- **Gestão de sessão** com atualização automática de token

### 💬 Sistema de Chat em Tempo Real
- **Mensagens individuais** com indicadores de digitação
- **Rastreamento de status online/offline**
- **Confirmação de leitura de mensagens**
- **Capacidade de bloquear usuários**
- **Busca e filtro de conversas**
- **Suporte para compartilhamento de arquivos** (imagens, documentos)
- **Polling em tempo real** para entrega instantânea de mensagens

### 👥 Gestão de Membros
- **Perfis de usuário** com avatares e informações pessoais
- **Rastreamento de aniversários e notificações**
- **Associação com igreja local**
- **Diretório de membros** com funcionalidade de busca

### 📅 Gestão de Eventos
- **Criação e agendamento de eventos**
- **Sistema de inscrição em eventos**
- **Destaque de eventos em evidência**
- **Integração com calendário**
- **Lembretes de eventos**

### 📖 Integração com a Bíblia
- **Versículo do dia** com agendamento automático
- **Busca completa na Bíblia KJV** (66 livros, 1.189 capítulos)
- **Marcação de versículos**
- **Compartilhar versículos** nas redes sociais

### 📢 Mural da Comunidade
- **Posts e anúncios públicos**
- **Sistema de curtidas e comentários**
- **Suporte multimídia** (imagens, vídeos)
- **Ferramentas de moderação**

### 🎮 Sistema de Quiz Interativo
- **Quiz bíblicos de trivia**
- **Múltiplos níveis de dificuldade**
- **Rastreamento de pontuação e placar de líderes**
- **Feedback em tempo real**

### 📱 Recursos PWA
- **Suporte offline** com cache de service worker
- **Instalável na tela inicial**
- **Notificações push** (em breve)
- **Sincronização em background** para ações offline
- **Design responsivo** (abordagem mobile-first)

---

## 🛠 Stack Tecnológica

### **Frontend**
- **JavaScript Vanilla Puro** - Sem dependências de frameworks para máxima performance
- **Recursos ES6+** - JavaScript moderno com classes e async/await
- **Propriedades Personalizadas CSS3** - Sistema de temas dinâmicos
- **Progressive Web App** - Service Worker com estratégias workbox
- **Design Responsivo** - Abordagem mobile-first com CSS Grid & Flexbox

### **Backend**
- **PHP 7.4+** - Lógica do lado do servidor
- **MySQL 5.7+** - Banco de dados relacional
- **API RESTful** - Comunicação baseada em JSON
- **Autenticação JWT** - Autenticação segura baseada em token

### **Infraestrutura**
- **Hospedagem KingHost** - Ambiente de produção
- **Servidor Apache** - Servidor web
- **SSL/HTTPS** - Conexões seguras
- **Git** - Controle de versão

### **Ferramentas de Desenvolvimento**
- **VS Code** - IDE principal
- **Chrome DevTools** - Depuração e análise de performance
- **Postman** - Testes de API
- **Git** - Controle de versão

---

## 🏗 Arquitetura

### **Single Page Application (SPA)**
```
┌─────────────────────────────────────────────────────────┐
│                     Cliente (Navegador)                  │
├─────────────────────────────────────────────────────────┤
│  App.js (Roteador)                                      │
│    ├─ Módulo de Autenticação                            │
│    ├─ Módulo de Chat                                    │
│    ├─ Módulo de Eventos                                 │
│    ├─ Módulo da Bíblia                                  │
│    ├─ Módulo de Quiz                                    │
│    ├─ Módulo do Mural                                   │
│    └─ Módulo de Perfil                                  │
├─────────────────────────────────────────────────────────┤
│  Service Worker (Cache Offline)                         │
└─────────────────────────────────────────────────────────┘
                          ↕ HTTPS
┌─────────────────────────────────────────────────────────┐
│                    API REST (PHP)                        │
├─────────────────────────────────────────────────────────┤
│  api/index.php (Roteador)                               │
│    ├─ auth/*                                            │
│    ├─ chat.php                                          │
│    ├─ events.php                                        │
│    ├─ members.php                                       │
│    ├─ quiz-v2.php                                       │
│    └─ verse-of-day-routes.php                          │
└─────────────────────────────────────────────────────────┘
                          ↕
┌─────────────────────────────────────────────────────────┐
│                    Banco de Dados MySQL                  │
└─────────────────────────────────────────────────────────┘
```

Para detalhes completos da arquitetura, veja [ARQUITETURA.md](ARQUITETURA.md)

---

## 🚀 Começando

### Pré-requisitos
```bash
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web Apache/Nginx
- Navegador web moderno (Chrome, Firefox, Safari, Edge)
```

---

## 📁 Estrutura do Projeto

```
american-teens/
├── 📱 index.html                    # Ponto de entrada principal
├── 📋 manifest.json                 # Manifesto PWA
├── ⚙️ sw.js                         # Service Worker
│
├── 🎨 css/                          # Folhas de estilo
├── 🧩 js/                           # Módulos JavaScript
│   ├── app.js                       # Controlador principal da aplicação
│   ├── auth.js                      # Serviço de autenticação
│   └── modules/                     # Módulos de recursos
│
├── 🔌 api/                          # Backend API
│   ├── index.php                    # Roteador da API
│   ├── config.php                   # Configuração do servidor
│   ├── *.php                        # Endpoints
│   └── sql/                         # Schemas do banco de dados
│
├── 🖼️ assets/                       # Assets estáticos
└── 📚 docs/                         # Documentação
    ├── en/                          # Documentação em inglês
    └── pt/                          # Documentação em português
```

---

## 📡 Documentação da API

### Autenticação

#### Registrar Novo Usuário
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "SenhaSegura123!",
  "local": "Igreja de São Paulo",
  "birthday": "2000-01-15"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "joao@example.com",
  "password": "SenhaSegura123!"
}
```

### Chat

#### Enviar Mensagem
```http
POST /api/chat/send
Authorization: Bearer seu_token_jwt
Content-Type: application/json

{
  "other_user_id": 2,
  "message": "Olá, tudo bem?"
}
```

Para documentação completa da API, veja [API.md](API.md)

---

## ⚡ Performance

### Métricas Lighthouse (Mobile)
```
├─ Performance: 92/100
├─ Acessibilidade: 95/100
├─ Melhores Práticas: 100/100
└─ SEO: 100/100
```

### Tempos de Carregamento
```
├─ First Contentful Paint: < 1.5s
├─ Time to Interactive: < 3.0s
└─ Tamanho Total da Página: < 500KB (gzipped)
```

---

## 🔒 Segurança

### Medidas de Segurança Implementadas

✅ **Autenticação**
- Autenticação baseada em JWT com assinatura segura
- Rotação de refresh token
- Hash de senha com bcrypt (fator de custo 12)

✅ **Validação de Entrada**
- Validação do lado do servidor para todas as entradas
- Prevenção de injeção SQL (prepared statements)
- Proteção XSS (codificação de saída)

✅ **Segurança da API**
- Configuração CORS
- Limitação de taxa
- Verificações de autorização em todos os endpoints

✅ **Proteção de Dados**
- Aplicação de HTTPS
- Flags de cookie seguro (HttpOnly, Secure, SameSite)
- Conformidade com GDPR

---

## 🧪 Testes

Ferramentas de teste manuais incluídas:
```
├─ test-chat-send.html              # Testes de funcionalidade do chat
├─ test-profile-console.html        # Testes do sistema de perfil
├─ diagnostico-chat-completo.html   # Ferramenta de diagnóstico do chat
└─ monitor-performance.html         # Monitoramento de performance
```

---

## 🌐 Suporte de Navegadores

| Navegador | Versão | Status |
|-----------|--------|--------|
| Chrome    | 90+    | ✅ Suporte completo |
| Firefox   | 88+    | ✅ Suporte completo |
| Safari    | 14+    | ✅ Suporte completo |
| Edge      | 90+    | ✅ Suporte completo |
| Navegadores móveis | Mais recentes | ✅ Suporte completo |

---

## 🤝 Contribuindo

Contribuições são bem-vindas de desenvolvedores de todos os níveis de habilidade! Por favor, leia nossas [Diretrizes de Contribuição](CONTRIBUINDO.md) para detalhes sobre:
- Código de Conduta
- Processo de desenvolvimento
- Como enviar pull requests
- Padrões de codificação
- Requisitos de testes

---

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](../../LICENSE) para detalhes.

---

## 👨‍💻 Autor

**Seu Nome**
- GitHub: https://github.com/Lucas12teixeira
- LinkedIn: https://www.linkedin.com/in/lucas-lima-10218529a/
- Email: lucas12teixeira@gmail.com
- Portfólio: https://github.com/Lucas12teixeira/AmericaTeensOficial/blob/main/docs/pt/GUIA_PORTFOLIO.md

---

## 🙏 Agradecimentos

- **Assembleia de Deus** - Pela inspiração e apoio da comunidade
- **Comunidade Open Source** - Pelas ferramentas e bibliotecas incríveis
- **Contribuidores** - Todos que contribuíram para este projeto

---

## 📊 Estatísticas do Projeto

![GitHub stars](https://img.shields.io/github/stars/seuusuario/american-teens?style=social)
![GitHub forks](https://img.shields.io/github/forks/seuusuario/american-teens?style=social)
![GitHub issues](https://img.shields.io/github/issues/seuusuario/american-teens)

---

<div align="center">

**[⬆ Voltar ao Topo](#-american-teens---plataforma-digital-comunitária)**

Feito com ❤️ para a comunidade jovem

[🇺🇸 English](../en/README.md) | 🇧🇷 **Português**

</div>
