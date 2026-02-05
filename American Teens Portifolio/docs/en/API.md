# 📡 API Documentation

Complete reference for the American Teens REST API.

## Table of Contents
- [Overview](#overview)
- [Authentication](#authentication)
- [Error Handling](#error-handling)
- [Authentication Endpoints](#authentication-endpoints)
- [Chat Endpoints](#chat-endpoints)
- [Member Endpoints](#member-endpoints)
- [Event Endpoints](#event-endpoints)
- [Quiz Endpoints](#quiz-endpoints)
- [Bible Endpoints](#bible-endpoints)
- [Wall Endpoints](#wall-endpoints)
- [Admin Endpoints](#admin-endpoints)
- [Rate Limiting](#rate-limiting)

---

## Overview

### Base URL

**Production:**
```
https://americateens.erldev.com.br/api
```

**Development:**
```
http://localhost:8000/api
```

### Request Format

All requests should include:

```http
Content-Type: application/json
Authorization: Bearer <your_jwt_token>
```

### Response Format

All responses follow this structure:

**Success Response:**
```json
{
  "ok": true,
  "data": { /* response data */ }
}
```

**Error Response:**
```json
{
  "ok": false,
  "error": "Error message here",
  "code": 400
}
```

---

## Authentication

### JWT Token

Most endpoints require a valid JWT token in the Authorization header:

```http
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Token Payload

```json
{
  "id": 123,
  "email": "user@example.com",
  "role": "member",
  "iat": 1738656000,
  "exp": 1738659600
}
```

### Refresh Token

Tokens expire after 1 hour. Use refresh token to get new access token:

```http
POST /auth/refresh
Content-Type: application/json

{
  "refreshToken": "your_refresh_token"
}
```

---

## Error Handling

### HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200  | Success |
| 201  | Created |
| 400  | Bad Request - Invalid input |
| 401  | Unauthorized - Invalid or missing token |
| 403  | Forbidden - Insufficient permissions |
| 404  | Not Found - Resource doesn't exist |
| 409  | Conflict - Resource already exists |
| 422  | Unprocessable Entity - Validation failed |
| 500  | Internal Server Error |

### Error Response Examples

**Validation Error:**
```json
{
  "ok": false,
  "error": "Validation failed",
  "errors": {
    "email": "Email is required",
    "password": "Password must be at least 8 characters"
  }
}
```

**Authentication Error:**
```json
{
  "ok": false,
  "error": "Token inválido",
  "code": 401
}
```

---

## Authentication Endpoints

### Register New User

Create a new user account.

```http
POST /auth/register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePass123!",
  "local": "New York Church",
  "birthday": "2000-01-15"
}
```

**Response:** `201 Created`
```json
{
  "ok": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR...",
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "member",
    "local": "New York Church",
    "avatar": null,
    "birthday": "2000-01-15"
  }
}
```

**Errors:**
- `409` - Email already registered
- `422` - Validation failed

---

### Login

Authenticate existing user.

```http
POST /auth/login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "SecurePass123!"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR...",
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "member",
    "local": "New York Church",
    "avatar": "/uploads/avatars/1.jpg"
  }
}
```

**Errors:**
- `401` - Invalid credentials
- `403` - Account disabled

---

### Refresh Token

Get new access token using refresh token.

```http
POST /auth/refresh
```

**Request Body:**
```json
{
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR..."
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "token": "eyJhbGciOiJIUzI1NiIsInR...",
  "refreshToken": "eyJhbGciOiJIUzI1NiIsInR..."
}
```

---

### Request Password Reset

Send password reset email.

```http
POST /auth/reset_request
```

**Request Body:**
```json
{
  "email": "john@example.com"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Password reset email sent"
}
```

---

### Reset Password

Reset password with token from email.

```http
POST /auth/reset_password
```

**Request Body:**
```json
{
  "token": "reset_token_from_email",
  "newPassword": "NewSecurePass123!"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Password reset successfully"
}
```

---

### Change Password

Change password for authenticated user.

```http
POST /auth/change-password
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "currentPassword": "OldPass123!",
  "newPassword": "NewPass123!"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Password changed successfully"
}
```

---

## Chat Endpoints

### Get Conversations

List all conversations for authenticated user.

```http
GET /chat/conversations
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "conversation_id": 1,
      "other_user": {
        "id": 2,
        "name": "Jane Smith",
        "avatar": "/uploads/avatars/2.jpg",
        "is_online": true,
        "last_seen": "2026-02-04T10:30:00Z"
      },
      "last_message": {
        "id": 123,
        "message": "Hello! How are you?",
        "sender_id": 2,
        "created_at": "2026-02-04T10:30:00Z",
        "is_read": true
      },
      "unread_count": 0
    }
  ]
}
```

---

### Get Messages

Get messages for a specific conversation.

```http
GET /chat/messages?conversation_id=1&limit=50&offset=0
Authorization: Bearer <token>
```

**Query Parameters:**
- `conversation_id` (required) - Conversation ID
- `limit` (optional) - Number of messages (default: 50)
- `offset` (optional) - Pagination offset (default: 0)

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "conversation_id": 1,
    "messages": [
      {
        "id": 123,
        "sender_id": 2,
        "receiver_id": 1,
        "message": "Hello!",
        "is_read": true,
        "read_at": "2026-02-04T10:31:00Z",
        "created_at": "2026-02-04T10:30:00Z"
      },
      {
        "id": 124,
        "sender_id": 1,
        "receiver_id": 2,
        "message": "Hi there!",
        "is_read": false,
        "read_at": null,
        "created_at": "2026-02-04T10:32:00Z"
      }
    ],
    "total": 2,
    "has_more": false
  }
}
```

---

### Send Message

Send a message to another user.

```http
POST /chat/send
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "other_user_id": 2,
  "message": "Hello, how are you?"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message_id": 125,
  "conversation_id": 1,
  "created_at": "2026-02-04T10:35:00Z"
}
```

---

### Mark Messages as Read

Mark messages as read.

```http
POST /chat/mark-read
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "conversation_id": 1
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "marked_count": 5
}
```

---

### Update Typing Status

Indicate user is typing.

```http
POST /chat/typing
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "conversation_id": 1,
  "is_typing": true
}
```

**Response:** `200 OK`
```json
{
  "ok": true
}
```

---

### Update Online Status

Update user's online status.

```http
POST /chat/online
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "is_online": true
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "is_online": true,
  "last_seen": "2026-02-04T10:40:00Z"
}
```

---

### Search Users

Search for users to chat with.

```http
GET /chat/search-users?q=john&limit=10
Authorization: Bearer <token>
```

**Query Parameters:**
- `q` (required) - Search query
- `limit` (optional) - Results limit (default: 10)

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 3,
      "name": "John Smith",
      "avatar": "/uploads/avatars/3.jpg",
      "local": "New York Church",
      "is_online": false
    }
  ]
}
```

---

### Block User

Block a user from sending messages.

```http
POST /chat/block
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "user_id": 2
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "User blocked successfully"
}
```

---

### Unblock User

Unblock a previously blocked user.

```http
POST /chat/unblock
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "user_id": 2
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "User unblocked successfully"
}
```

---

## Member Endpoints

### Get Member Profile

Get profile information for a specific member.

```http
GET /members/{id}
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "avatar": "/uploads/avatars/1.jpg",
    "local": "New York Church",
    "birthday": "2000-01-15",
    "role": "member",
    "joined_at": "2025-01-01T00:00:00Z"
  }
}
```

---

### Update Profile

Update authenticated user's profile.

```http
PUT /members/profile
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "name": "John Doe Jr.",
  "local": "Los Angeles Church",
  "birthday": "2000-01-15"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "name": "John Doe Jr.",
    "local": "Los Angeles Church",
    "birthday": "2000-01-15"
  }
}
```

---

### Upload Avatar

Upload profile picture.

```http
POST /members/upload-avatar
Authorization: Bearer <token>
Content-Type: multipart/form-data
```

**Form Data:**
- `avatar` - Image file (JPG, PNG, max 5MB)

**Response:** `200 OK`
```json
{
  "ok": true,
  "avatar_url": "/uploads/avatars/1_1738656000.jpg"
}
```

---

### Get Birthdays

Get upcoming birthdays.

```http
GET /members/birthdays?month=2&limit=10
Authorization: Bearer <token>
```

**Query Parameters:**
- `month` (optional) - Filter by month (1-12)
- `limit` (optional) - Results limit (default: 10)

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 2,
      "name": "Jane Smith",
      "avatar": "/uploads/avatars/2.jpg",
      "birthday": "2001-02-14",
      "local": "New York Church",
      "days_until": 10
    }
  ]
}
```

---

## Event Endpoints

### Get Events

List upcoming events.

```http
GET /events?limit=20&offset=0&featured_only=false
Authorization: Bearer <token>
```

**Query Parameters:**
- `limit` (optional) - Results limit (default: 20)
- `offset` (optional) - Pagination offset (default: 0)
- `featured_only` (optional) - Show only featured events (default: false)

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "title": "Youth Conference 2026",
      "description": "Annual youth conference with special guests",
      "event_date": "2026-03-15T14:00:00Z",
      "location": "Main Church",
      "organizer": {
        "id": 5,
        "name": "Pastor John"
      },
      "image_url": "/uploads/events/1.jpg",
      "is_featured": true,
      "max_participants": 100,
      "registered_count": 45,
      "is_registered": false
    }
  ],
  "total": 1,
  "has_more": false
}
```

---

### Get Event Details

Get detailed information about a specific event.

```http
GET /events/{id}
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "title": "Youth Conference 2026",
    "description": "Annual youth conference...",
    "event_date": "2026-03-15T14:00:00Z",
    "location": "Main Church",
    "organizer": {
      "id": 5,
      "name": "Pastor John",
      "avatar": "/uploads/avatars/5.jpg"
    },
    "image_url": "/uploads/events/1.jpg",
    "max_participants": 100,
    "registered_count": 45,
    "is_registered": false,
    "created_at": "2026-01-01T00:00:00Z"
  }
}
```

---

### Create Event

Create a new event (Coordinator/Admin only).

```http
POST /events
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "title": "Youth Conference 2026",
  "description": "Annual youth conference",
  "event_date": "2026-03-15T14:00:00Z",
  "location": "Main Church",
  "max_participants": 100,
  "image_url": "/uploads/events/1.jpg",
  "is_featured": false
}
```

**Response:** `201 Created`
```json
{
  "ok": true,
  "event_id": 1,
  "message": "Event created successfully"
}
```

---

### Register for Event

Register authenticated user for an event.

```http
POST /events/{id}/register
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Registered successfully",
  "registration_id": 123
}
```

**Errors:**
- `409` - Already registered
- `400` - Event is full

---

### Unregister from Event

Cancel event registration.

```http
DELETE /events/{id}/register
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Registration cancelled"
}
```

---

## Quiz Endpoints

### Get Quiz Questions

Get random quiz questions.

```http
GET /quiz/questions?count=10&difficulty=medium
Authorization: Bearer <token>
```

**Query Parameters:**
- `count` (optional) - Number of questions (default: 10)
- `difficulty` (optional) - easy, medium, hard (default: medium)

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "question": "Who was the first man created by God?",
      "options": ["Adam", "Noah", "Abraham", "Moses"],
      "difficulty": "easy",
      "category": "Old Testament"
    }
  ]
}
```

---

### Submit Quiz Answer

Submit answer for a question.

```http
POST /quiz/answer
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "question_id": 1,
  "answer": "Adam"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "correct": true,
  "correct_answer": "Adam",
  "explanation": "Adam was the first human created by God in the Garden of Eden."
}
```

---

### Get Quiz Results

Get quiz history and statistics.

```http
GET /quiz/results
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "total_quizzes": 15,
    "total_questions": 150,
    "correct_answers": 120,
    "accuracy": 80.0,
    "recent_results": [
      {
        "id": 1,
        "date": "2026-02-04T10:00:00Z",
        "questions_count": 10,
        "correct_count": 8,
        "score": 80
      }
    ]
  }
}
```

---

## Bible Endpoints

### Get Verse of the Day

Get the daily Bible verse.

```http
GET /verse-of-day
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": {
    "id": 1,
    "verse": "For God so loved the world...",
    "reference": "John 3:16",
    "book": "John",
    "chapter": 3,
    "verse_number": 16,
    "date": "2026-02-04"
  }
}
```

---

### Search Bible

Search for Bible verses.

```http
GET /bible/search?q=love&book=John&limit=10
Authorization: Bearer <token>
```

**Query Parameters:**
- `q` (required) - Search term
- `book` (optional) - Filter by book name
- `limit` (optional) - Results limit (default: 10)

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "book": "John",
      "chapter": 3,
      "verse": 16,
      "text": "For God so loved the world...",
      "reference": "John 3:16"
    }
  ],
  "total": 1
}
```

---

### Get Bible Book

Get all verses from a specific book and chapter.

```http
GET /bible/{book}/{chapter}
Authorization: Bearer <token>
```

**Example:**
```http
GET /bible/John/3
```

**Response:** `200 OK`
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
        "verse": 16,
        "text": "For God so loved the world..."
      }
    ]
  }
}
```

---

## Wall Endpoints

### Get Posts

Get community wall posts.

```http
GET /wall?limit=20&offset=0
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "user": {
        "id": 2,
        "name": "Jane Smith",
        "avatar": "/uploads/avatars/2.jpg"
      },
      "content": "Excited for the upcoming conference!",
      "image_url": null,
      "likes_count": 15,
      "comments_count": 3,
      "is_liked": false,
      "created_at": "2026-02-04T09:00:00Z"
    }
  ]
}
```

---

### Create Post

Create a new wall post.

```http
POST /wall
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "content": "Excited for the conference!",
  "image_url": "/uploads/wall/123.jpg"
}
```

**Response:** `201 Created`
```json
{
  "ok": true,
  "post_id": 1,
  "message": "Post created successfully"
}
```

---

### Like Post

Like a wall post.

```http
POST /wall/{id}/like
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "likes_count": 16
}
```

---

### Unlike Post

Remove like from post.

```http
DELETE /wall/{id}/like
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "likes_count": 15
}
```

---

## Admin Endpoints

### Get All Members

List all members (Admin only).

```http
GET /admin/members?limit=50&offset=0&role=all
Authorization: Bearer <token>
```

**Query Parameters:**
- `limit` (optional) - Results limit (default: 50)
- `offset` (optional) - Pagination offset
- `role` (optional) - Filter by role: all, member, coordinator, admin

**Response:** `200 OK`
```json
{
  "ok": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "member",
      "local": "New York Church",
      "is_active": true,
      "joined_at": "2025-01-01T00:00:00Z"
    }
  ],
  "total": 1
}
```

---

### Update Member Role

Change member's role (Admin only).

```http
PUT /admin/members/{id}/role
Authorization: Bearer <token>
```

**Request Body:**
```json
{
  "role": "coordinator"
}
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Role updated successfully"
}
```

---

### Deactivate Member

Deactivate a member account (Admin only).

```http
POST /admin/members/{id}/deactivate
Authorization: Bearer <token>
```

**Response:** `200 OK`
```json
{
  "ok": true,
  "message": "Member deactivated"
}
```

---

## Rate Limiting

To prevent abuse, the API implements rate limiting:

**Limits:**
- Authentication endpoints: 5 requests per minute
- Regular endpoints: 60 requests per minute
- Search endpoints: 30 requests per minute

**Headers:**
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1738656000
```

**Rate Limit Exceeded:**
```json
{
  "ok": false,
  "error": "Rate limit exceeded",
  "retry_after": 30
}
```

---

## Webhooks (Coming Soon)

Subscribe to real-time events:

- `message.received` - New chat message
- `event.created` - New event created
- `member.joined` - New member registered

---

## Changelog

### Version 2.0.0 (2026-02-04)
- ✅ Complete chat system overhaul
- ✅ Improved authentication flow
- ✅ Added rate limiting
- ✅ Enhanced error responses

### Version 1.0.0 (2025-01-01)
- Initial API release

---

## Support

For API support:
- **Documentation**: [README.md](README.md)
- **Issues**: [GitHub Issues](https://github.com/yourusername/american-teens/issues)
- **Email**: api@americateens.com

---

<div align="center">

**[⬆ Back to Top](#-api-documentation)**

Built with ❤️ by American Teens Team

</div>
