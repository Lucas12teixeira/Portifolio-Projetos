# Contributing to American Teens

First off, thank you for considering contributing to American Teens! It's people like you that make this project such a great tool for youth communities worldwide.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Commit Guidelines](#commit-guidelines)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Features](#suggesting-features)

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to conduct@americateens.com.

## How Can I Contribute?

### 🐛 Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates. When creating a bug report, include as many details as possible:

**Bug Report Template:**
```markdown
**Description:**
A clear description of the bug

**To Reproduce:**
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

**Expected Behavior:**
What you expected to happen

**Screenshots:**
If applicable, add screenshots

**Environment:**
- OS: [e.g., Windows 11, macOS 13]
- Browser: [e.g., Chrome 120, Firefox 121]
- Version: [e.g., 2.0.0]

**Additional Context:**
Any other relevant information
```

### 💡 Suggesting Features

Feature suggestions are welcome! Please create an issue with:

**Feature Request Template:**
```markdown
**Problem Statement:**
What problem does this feature solve?

**Proposed Solution:**
Describe your proposed solution

**Alternatives Considered:**
What alternatives have you considered?

**Additional Context:**
Mockups, examples, or other context

**Priority:**
Low / Medium / High
```

### 📝 Code Contributions

1. **Bug Fixes** - Always welcome
2. **New Features** - Discuss in an issue first
3. **Documentation** - Improvements always appreciated
4. **Tests** - Help us increase coverage
5. **Translations** - Make the app multilingual

## Getting Started

### Prerequisites

- Git
- PHP 7.4+
- MySQL 5.7+
- Node.js (optional, for development tools)
- A code editor (VS Code recommended)

### Fork & Clone

```bash
# Fork the repository on GitHub
# Then clone your fork
git clone https://github.com/YOUR_USERNAME/american-teens.git
cd american-teens

# Add upstream remote
git remote add upstream https://github.com/ORIGINAL_OWNER/american-teens.git
```

### Setup Development Environment

```bash
# Create database
mysql -u root -p
CREATE DATABASE americateens_dev;
exit;

# Import schema
mysql -u root -p americateens_dev < api/sql/INSTALAR-TUDO.sql

# Configure
cp api/config.php.example api/config.php
# Edit config.php with your credentials

# Start dev server
php -S localhost:8000
```

## Development Workflow

### 1. Create a Branch

```bash
# Update your fork
git checkout main
git pull upstream main

# Create feature branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/bug-description
```

### Branch Naming Convention

- `feature/` - New features
- `fix/` - Bug fixes
- `docs/` - Documentation changes
- `refactor/` - Code refactoring
- `test/` - Adding tests
- `chore/` - Maintenance tasks

### 2. Make Changes

- Write clean, readable code
- Follow our coding standards
- Add comments for complex logic
- Update documentation as needed
- Add tests for new features

### 3. Test Your Changes

```bash
# Test manually
# 1. Test in browser (Chrome, Firefox, Safari)
# 2. Test mobile responsiveness
# 3. Test offline functionality (Service Worker)
# 4. Check browser console for errors

# Test API endpoints
# Use test-*.html files in root directory

# Check for errors
tail -f api/logs/php-errors.log
```

### 4. Commit Changes

```bash
git add .
git commit -m "feat: add user profile editing feature"
```

## Coding Standards

### JavaScript Style Guide

```javascript
// ✅ Good
class ChatModule {
    static async init() {
        try {
            await this.loadConversations();
            this.render();
        } catch (error) {
            console.error('Failed to initialize chat:', error);
            UI.showError('Failed to load chat');
        }
    }
    
    static async loadConversations() {
        const response = await api.get('/chat/conversations');
        this.conversations = response.data;
    }
}

// ❌ Bad
function chatInit() {
    loadConversations()
    render()
}

function loadConversations() {
    var response = api.get('/chat/conversations')
    conversations = response.data
}
```

**Rules:**
- Use ES6+ features (const/let, arrow functions, async/await)
- Use meaningful variable names
- Keep functions small and focused
- Add JSDoc comments for public APIs
- Use template literals for strings
- Handle errors properly

### PHP Style Guide

```php
// ✅ Good
function getConversations(PDO $db, int $userId): array {
    $stmt = $db->prepare("
        SELECT c.*, m.name, m.avatar
        FROM chat_conversations c
        INNER JOIN members m ON c.user2_id = m.id
        WHERE c.user1_id = :user_id
        ORDER BY c.updated_at DESC
    ");
    
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll();
}

// ❌ Bad
function getConvs($db, $uid) {
    $query = "SELECT * FROM chat_conversations WHERE user1_id = $uid";
    return $db->query($query)->fetchAll();
}
```

**Rules:**
- Use type hints
- Use prepared statements (never string interpolation)
- Follow PSR-12 coding standard
- Use meaningful function names
- Add PHPDoc comments
- Handle exceptions properly

### CSS Style Guide

```css
/* ✅ Good */
.chat-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    background-color: var(--bg-primary);
}

.chat-message {
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 0.5rem;
    background-color: var(--bg-secondary);
}

/* ❌ Bad */
.container {
    display: flex;
    height: 100vh;
}

.msg {
    padding: 10px;
}
```

**Rules:**
- Use CSS custom properties (variables)
- Use BEM or descriptive class names
- Mobile-first approach
- Avoid !important
- Group related properties

### SQL Style Guide

```sql
-- ✅ Good
SELECT 
    m.id,
    m.name,
    m.email,
    COUNT(cm.id) AS message_count
FROM members m
LEFT JOIN chat_messages cm ON m.id = cm.sender_id
WHERE m.is_active = 1
GROUP BY m.id
ORDER BY message_count DESC
LIMIT 10;

-- ❌ Bad
select * from members where is_active=1 order by id desc limit 10
```

**Rules:**
- Use uppercase for SQL keywords
- Indent for readability
- Use table aliases
- Add indexes for frequently queried columns
- Use EXPLAIN to optimize queries

## Commit Guidelines

We follow [Conventional Commits](https://www.conventionalcommits.org/) specification:

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation changes
- `style:` - Code style changes (formatting, no logic change)
- `refactor:` - Code refactoring
- `perf:` - Performance improvements
- `test:` - Adding/updating tests
- `chore:` - Maintenance tasks
- `revert:` - Revert previous commit

### Examples

```bash
feat(chat): add typing indicators

Implement real-time typing status indicators in chat conversations.
Shows "User is typing..." message when other user is actively typing.

Closes #123

fix(auth): prevent token refresh loop

Fixed infinite loop in token refresh logic that occurred when
refresh token was expired.

Fixes #456

docs(api): update chat endpoints documentation

Added missing parameters and response examples for chat API endpoints.
```

### Rules

- Use present tense ("add feature" not "added feature")
- Use imperative mood ("move cursor to" not "moves cursor to")
- First line max 72 characters
- Reference issues when applicable

## Pull Request Process

### Before Submitting

- [ ] Code follows our coding standards
- [ ] Changes tested locally
- [ ] Documentation updated (if needed)
- [ ] No console errors
- [ ] Commits follow commit guidelines
- [ ] Branch is up to date with main

### Submit Pull Request

1. **Push to Your Fork**
```bash
git push origin feature/your-feature-name
```

2. **Create Pull Request on GitHub**
   - Go to your fork on GitHub
   - Click "New Pull Request"
   - Select your branch
   - Fill out the PR template

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## How Has This Been Tested?
Describe testing process

## Screenshots (if applicable)
Add screenshots

## Checklist
- [ ] My code follows the style guidelines
- [ ] I have performed a self-review
- [ ] I have commented my code
- [ ] I have updated the documentation
- [ ] My changes generate no new warnings
- [ ] I have tested offline functionality
- [ ] I have tested on mobile devices

## Related Issues
Closes #123
```

### Review Process

1. **Automated Checks**
   - Code will be automatically reviewed
   - Must pass all checks

2. **Maintainer Review**
   - A maintainer will review your code
   - May request changes
   - Discussion may occur

3. **Changes Requested**
   - Make requested changes
   - Push to same branch
   - PR updates automatically

4. **Approval & Merge**
   - Once approved, maintainer will merge
   - Your contribution is now part of the project!

## Project Structure

Understanding the project structure helps you contribute effectively:

```
american-teens/
├── index.html              # Entry point
├── manifest.json           # PWA manifest
├── sw.js                   # Service Worker
│
├── css/                    # Stylesheets
│   ├── variables.css       # CSS variables
│   ├── styles.css          # Global styles
│   └── modules.css         # Module styles
│
├── js/                     # JavaScript
│   ├── app.js              # Main app controller
│   ├── auth.js             # Authentication
│   ├── api.js              # API client
│   └── modules/            # Feature modules
│       ├── chat.js
│       ├── events.js
│       └── ...
│
├── api/                    # Backend
│   ├── index.php           # API router
│   ├── config.php          # Configuration
│   ├── *.php               # Endpoints
│   └── sql/                # Database schemas
│
└── docs/                   # Documentation
    ├── README.md
    ├── ARCHITECTURE.md
    └── API.md
```

## Testing Guidelines

### Manual Testing Checklist

**Authentication:**
- [ ] Register new user
- [ ] Login with valid credentials
- [ ] Login with invalid credentials
- [ ] Logout
- [ ] Password reset flow
- [ ] Token refresh

**Chat:**
- [ ] Send message
- [ ] Receive message
- [ ] Mark as read
- [ ] Search users
- [ ] Block/unblock user
- [ ] Typing indicator

**Events:**
- [ ] View events list
- [ ] View event details
- [ ] Register for event
- [ ] Cancel registration
- [ ] Create event (coordinator)

**Cross-Browser:**
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile browsers

**PWA:**
- [ ] Install app
- [ ] Offline mode
- [ ] Cache updates
- [ ] Service Worker updates

## Documentation

### Code Comments

```javascript
/**
 * Sends a chat message to another user
 * @param {number} userId - Recipient user ID
 * @param {string} message - Message content
 * @returns {Promise<Object>} Response with message ID and timestamp
 * @throws {Error} If message send fails
 */
static async sendMessage(userId, message) {
    // Implementation
}
```

### README Updates

When adding features, update relevant documentation:

- Main README.md - Feature description
- ARCHITECTURE.md - Technical details
- API.md - API endpoints
- INSTALLATION.md - Setup requirements

## Community

### Getting Help

- **Discord:** [Join our server](https://discord.gg/americateens)
- **GitHub Discussions:** Ask questions
- **Email:** dev@americateens.com

### Recognition

Contributors are recognized in:
- GitHub Contributors page
- Release notes
- Project credits

## License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

## Questions?

Don't hesitate to ask! We're here to help:
- Open an issue with your question
- Join our Discord
- Email dev@americateens.com

Thank you for contributing to American Teens! 🎉

---

<div align="center">

**[⬆ Back to Top](#contributing-to-american-teens)**

Made with ❤️ by the American Teens community

</div>
