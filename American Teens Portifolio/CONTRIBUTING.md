# 🤝 Contributing to American Teens

First off, **thank you** for considering contributing to American Teens! 🎉 It's people like you that make this project a great tool for youth communities worldwide.

Following these guidelines helps to communicate that you respect the time of the developers managing and developing this open source project. In return, they should reciprocate that respect in addressing your issue, assessing changes, and helping you finalize your pull requests.

## 📋 Table of Contents

- [Code of Conduct](#-code-of-conduct)
- [Getting Started](#-getting-started)
- [How Can I Contribute?](#-how-can-i-contribute)
- [Development Setup](#-development-setup)
- [Coding Standards](#-coding-standards)
- [Commit Guidelines](#-commit-guidelines)
- [Pull Request Process](#-pull-request-process)
- [Testing Guidelines](#-testing-guidelines)
- [Documentation](#-documentation)
- [Community](#-community)

---

## 📜 Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to your.email@example.com.

---

## 🚀 Getting Started

### Prerequisites

Before you begin, ensure you have:

- ✅ Git installed
- ✅ PHP 7.4 or higher
- ✅ MySQL 5.7 or higher
- ✅ A GitHub account
- ✅ Basic knowledge of JavaScript, PHP, and MySQL
- ✅ Familiarity with REST APIs

### First Steps

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/american-teens.git
   cd american-teens
   ```
3. **Add upstream remote**:
   ```bash
   git remote add upstream https://github.com/original-owner/american-teens.git
   ```
4. **Set up your development environment** (see [Development Setup](#-development-setup))

---

## 💡 How Can I Contribute?

### 🐛 Reporting Bugs

Before creating bug reports, please check the [existing issues](https://github.com/yourusername/american-teens/issues) to avoid duplicates.

**When submitting a bug report, include:**

- Clear, descriptive title
- Steps to reproduce the issue
- Expected behavior vs actual behavior
- Screenshots (if applicable)
- Your environment (OS, browser version, PHP version)
- Error messages or console logs

**Use our bug report template:**
[Create Bug Report](https://github.com/yourusername/american-teens/issues/new?template=bug_report.md)

### ✨ Suggesting Features

Feature suggestions are welcome! Before creating a feature request:

- Check if the feature has already been suggested
- Ensure it aligns with the project's goals
- Be clear about the problem it solves

**When suggesting a feature, include:**

- Clear description of the feature
- Why this feature would be useful
- Possible implementation approaches
- Mockups or examples (if applicable)

**Use our feature request template:**
[Request Feature](https://github.com/yourusername/american-teens/issues/new?template=feature_request.md)

### 📝 Improving Documentation

Documentation improvements are always appreciated! This includes:

- Fixing typos or clarifying existing docs
- Adding examples or tutorials
- Translating documentation
- Creating video tutorials or guides

### 🔨 Contributing Code

**Good First Issues:**
Look for issues labeled [`good first issue`](https://github.com/yourusername/american-teens/labels/good%20first%20issue) - these are great for newcomers!

**Areas that need help:**
- Frontend components
- API endpoints
- Database optimization
- Security enhancements
- Testing
- Accessibility improvements

---

## 💻 Development Setup

### 1. Install Dependencies

```bash
# No npm dependencies - pure vanilla JS!
# Just ensure PHP and MySQL are installed
php --version
mysql --version
```

### 2. Configure Database

```bash
# Create database
mysql -u root -p
CREATE DATABASE americateens_dev;

# Import schema
mysql -u root -p americateens_dev < api/sql/INSTALAR-TUDO.sql
```

### 3. Configure Environment

```php
# Copy and edit api/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'americateens_dev');
define('JWT_SECRET', 'your-development-secret-key');
```

### 4. Start Development Server

```bash
# Using PHP built-in server
php -S localhost:8000

# Or configure Apache/Nginx virtual host
```

### 5. Access Application

Open `http://localhost:8000` in your browser.

---

## 📐 Coding Standards

### JavaScript Style Guide

**We follow modern ES6+ practices:**

```javascript
// ✅ Good: Use const/let, not var
const API_URL = '/api';
let userCount = 0;

// ✅ Good: Arrow functions for callbacks
items.map(item => item.name);

// ✅ Good: Template literals
const message = `Hello, ${userName}!`;

// ✅ Good: Destructuring
const { name, email } = user;

// ✅ Good: Async/await for promises
async function loadData() {
  try {
    const data = await fetch('/api/data');
    return data.json();
  } catch (error) {
    console.error('Error:', error);
  }
}

// ❌ Bad: var, string concatenation
var msg = 'Hello, ' + userName + '!';
```

**Naming Conventions:**

```javascript
// Classes: PascalCase
class UserProfile { }

// Functions/Variables: camelCase
function getUserData() { }
const userEmail = 'user@example.com';

// Constants: UPPER_SNAKE_CASE
const MAX_LOGIN_ATTEMPTS = 3;

// Private methods: _prefixWithUnderscore
_validateInput(data) { }
```

**Comments:**

```javascript
/**
 * Sends a chat message to another user
 * @param {number} userId - The recipient's user ID
 * @param {string} message - The message content
 * @returns {Promise<Object>} The created message object
 */
async function sendMessage(userId, message) {
  // Implementation
}
```

### PHP Style Guide

**Follow PSR-12 standards:**

```php
<?php
// ✅ Good: Type hints and return types
function getUser(int $id): ?array {
    // Implementation
}

// ✅ Good: Prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);

// ✅ Good: Try-catch blocks
try {
    $result = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    return null;
}

// ❌ Bad: SQL injection vulnerable
$query = "SELECT * FROM users WHERE id = $id";
```

### CSS Style Guide

```css
/* ✅ Good: Use CSS custom properties */
:root {
  --primary-color: #007bff;
  --spacing-unit: 8px;
}

/* ✅ Good: BEM naming convention */
.chat-message { }
.chat-message__text { }
.chat-message--unread { }

/* ✅ Good: Mobile-first media queries */
.container {
  width: 100%;
}

@media (min-width: 768px) {
  .container {
    width: 750px;
  }
}
```

### File Organization

```
- Keep files focused and single-purpose
- Maximum 300-500 lines per file
- Extract reusable functions to utilities
- Group related functionality in modules
```

---

## 📝 Commit Guidelines

### Commit Message Format

We follow the [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `perf`: Performance improvements
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**

```bash
# Feature
git commit -m "feat(chat): add typing indicator to conversations"

# Bug fix
git commit -m "fix(auth): resolve token expiration issue"

# Documentation
git commit -m "docs(readme): update installation instructions"

# Multiple changes
git commit -m "feat(events): add event categories

- Add category field to events table
- Update event creation form
- Add category filter to event list"
```

### Commit Best Practices

- ✅ Write clear, concise commit messages
- ✅ Use present tense ("add feature" not "added feature")
- ✅ Keep commits atomic (one logical change per commit)
- ✅ Reference issue numbers when applicable
- ❌ Don't commit commented-out code
- ❌ Don't commit sensitive data (passwords, API keys)

---

## 🔀 Pull Request Process

### Before Submitting

1. ✅ **Sync with upstream:**
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

2. ✅ **Test your changes thoroughly**

3. ✅ **Update documentation** if needed

4. ✅ **Follow coding standards**

5. ✅ **Write descriptive commit messages**

### Submitting Your PR

1. **Push to your fork:**
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create Pull Request** on GitHub

3. **Fill out the PR template** with:
   - Description of changes
   - Related issue number(s)
   - Type of change (bug fix, feature, etc.)
   - Testing performed
   - Screenshots (if UI changes)

4. **Wait for review** - maintainers will review your code

### During Review

- Be responsive to feedback
- Make requested changes promptly
- Keep discussions respectful and constructive
- Ask questions if feedback is unclear

### After Approval

- A maintainer will merge your PR
- Your contribution will be credited
- Celebrate! 🎉 You've made the project better!

---

## 🧪 Testing Guidelines

### Manual Testing

Before submitting, test:

1. **Core Functionality:**
   - Login/logout
   - Registration
   - Password reset

2. **Feature-Specific:**
   - Test the feature you modified
   - Test related features that might be affected

3. **Cross-Browser:**
   - Chrome, Firefox, Safari, Edge
   - Mobile browsers

4. **Responsive Design:**
   - Mobile (320px+)
   - Tablet (768px+)
   - Desktop (1024px+)

### Test Files

Use the provided test files:

```bash
# Test chat functionality
open test-chat-send.html

# Test profile system
open test-profile-console.html

# Run diagnostics
open diagnostico-chat-completo.html
```

### Writing Tests

When adding new features, consider adding:

```html
<!-- Create test-your-feature.html -->
<!DOCTYPE html>
<html>
<head>
    <title>Test: Your Feature</title>
</head>
<body>
    <h1>Your Feature Tests</h1>
    <div id="test-results"></div>
    <script>
        // Your test code
    </script>
</body>
</html>
```

---

## 📚 Documentation

### Code Documentation

**Document all public functions:**

```javascript
/**
 * Fetches user profile data
 * @param {number} userId - The user's ID
 * @returns {Promise<Object>} User profile object
 * @throws {Error} If user not found
 */
async function getUserProfile(userId) {
  // Implementation
}
```

### README Updates

Update relevant documentation when:

- Adding new features
- Changing APIs
- Modifying configuration
- Updating dependencies

### Creating New Documentation

Place documentation in the `docs/` folder:

```
docs/
├── en/           # English documentation
│   ├── API.md
│   ├── ARCHITECTURE.md
│   └── CONTRIBUTING.md
└── pt/           # Portuguese documentation
    ├── API.md
    └── ARQUITETURA.md
```

---

## 👥 Community

### Getting Help

- 💬 **GitHub Discussions**: Ask questions, share ideas
- 🐛 **Issues**: Report bugs, request features
- 📧 **Email**: your.email@example.com
- 💼 **LinkedIn**: Connect with maintainers

### Recognition

Contributors are recognized in:

- README.md contributors section
- Release notes
- Project documentation

### Communication Channels

- **GitHub Issues**: Bug reports, feature requests
- **GitHub Discussions**: General questions, ideas
- **Pull Requests**: Code reviews, discussions
- **Email**: Private matters, security issues

---

## 🎯 Development Workflow

### Feature Development

```bash
# 1. Create feature branch
git checkout -b feature/my-new-feature

# 2. Make changes
# ... code, code, code ...

# 3. Commit changes
git add .
git commit -m "feat: add my new feature"

# 4. Keep up to date
git fetch upstream
git rebase upstream/main

# 5. Push and create PR
git push origin feature/my-new-feature
```

### Bug Fix Workflow

```bash
# 1. Create fix branch
git checkout -b fix/bug-description

# 2. Fix the bug
# ... fix code ...

# 3. Test thoroughly
# ... test, test, test ...

# 4. Commit and push
git commit -m "fix: resolve bug description"
git push origin fix/bug-description
```

---

## 📊 Project Structure

Understanding the project structure helps you contribute:

```
american-teens/
├── index.html              # Main entry point
├── css/                    # Stylesheets
├── js/                     # JavaScript modules
│   ├── app.js             # Main app controller
│   ├── auth.js            # Authentication
│   └── modules/           # Feature modules
├── api/                    # Backend API
│   ├── index.php          # API router
│   ├── auth/              # Auth endpoints
│   └── sql/               # Database schemas
└── docs/                   # Documentation
```

---

## 🏆 Recognition

### Hall of Fame

Top contributors will be featured in:

- Project README
- Release notes
- Social media shoutouts
- Annual contributor recognition

### Contribution Levels

- 🥉 **Bronze**: 1-5 merged PRs
- 🥈 **Silver**: 6-15 merged PRs
- 🥇 **Gold**: 16+ merged PRs
- 💎 **Diamond**: Significant architectural contributions

---

## ❓ FAQ

### Q: I'm new to open source. Where do I start?

**A:** Start with issues labeled `good first issue`. These are designed for newcomers!

### Q: How long does PR review take?

**A:** Usually 3-7 days. Larger PRs may take longer.

### Q: Can I work on multiple issues at once?

**A:** Yes, but please create separate branches and PRs for each issue.

### Q: What if my PR is rejected?

**A:** Don't worry! Use the feedback to improve and try again.

### Q: Can I add my employer's logo?

**A:** If your company significantly contributes, contact maintainers to discuss.

---

## 📞 Need Help?

Don't hesitate to ask for help:

- 💬 [GitHub Discussions](https://github.com/yourusername/american-teens/discussions)
- 📧 Email: your.email@example.com
- 📖 Check existing [documentation](docs/)

---

## 🙏 Thank You!

Your contributions make American Teens better for youth communities worldwide. Whether you're fixing a typo or implementing a major feature, **every contribution matters**!

**Happy coding! 🚀**

---

<div align="center">

**[⬆ Back to Top](#-contributing-to-american-teens)**

Made with ❤️ by the American Teens Community

</div>
