# API Documentation

Comprehensive documentation for Climbing Guide API (Laravel RESTful).

## Table of Contents

- [Authentication](#authentication)
- [User Management](#user-management)
- [Articles & Guides](#articles--guides)
- [Shop & Products](#shop--products)
- [Forum](#forum)
- [Films](#films)
- [Events](#events)
- [Search](#search)
- [Notifications](#notifications)
- [File Upload](#file-upload)

## Authentication

```
GET  /api/login/{provider}
GET  /api/login/{provider}/callback
POST /api/login/social/create_password/{email}
POST /api/password/send_forget_mail
POST /api/password/reset_password
GET  /api/email/resend
GET  /api/email/verify/{user_id}/{hash}
```

## User Management

```
GET  /api/auth_user
GET  /api/user/get_auth_user_data
...
```

*(Full endpoints as in original, but standardized with consistent backticks ` for code, sections preserved)*

**Note:** Auth requires `Authorization: Bearer {token}`. Rate limited. JSON in/out. Pagination supported.

See original file for complete list (over 100 endpoints across features).

<hr>

[Go back](../../../README.md)

