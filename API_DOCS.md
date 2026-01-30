# API Documentation - Monika System

Base URL: `/api`
Format: JSON
Auth: JWT (Not fully documented here, assuming Bearer Token in header)

## 1. User Management (`/api/users`)

### Get List of Users
**GET** `/users`

**Parameters:**
- `search` (optional): Search by name, username, or email.
- `role` (optional): Filter by Role ID (1=Admin, 3=PCL, 5=PML, 4=Processing).

**Response:**
```json
{
    "status": 200,
    "message": "Users retrieved successfully",
    "data": [
        {
            "id_user": "1",
            "fullname": "Admin",
            "username": "admin",
            "email": "admin@example.com",
            "id_role": "1",
            "is_active": "1",
            ...
        }
    ],
    "pager": { ... }
}
```

### Get Single User
**GET** `/users/{id}`

**Response:**
```json
{
    "id_user": "3",
    "fullname": "Budi PCL",
    "wilayah_kerja": "Desa Sukamaju",
    ...
}
```

### Create User
**POST** `/users`

**Body:**
```json
{
    "fullname": "New User",
    "username": "newuser",
    "email": "new@example.com",
    "password": "password123",
    "confpassword": "password123",
    "id_role": 3,
    "nik_ktp": "1234567890123456",
    "phone_number": "08123456789",
    "wilayah_kerja": "Area 1" // Only for PCL
}
```

**Response:**
```json
{
    "status": 201,
    "message": "User created successfully"
}
```

### Update User
**PUT** `/users/{id}`

**Body:** (Send only fields to update)
```json
{
    "fullname": "Updated Name",
    "wilayah_kerja": "Area 2"
}
```

### Delete User
**DELETE** `/users/{id}`

**Response:**
```json
{
    "status": 200,
    "message": "User deleted successfully"
}
```

## 2. Activity Tracking (Planned)
**POST** `/activity`
Used by mobile apps for PCL/PML to report location/activity.

**Body:**
```json
{
    "id_pml": 5,
    "activity_type": "Check-in",
    "location_lat": "-6.200000",
    "location_long": "106.816666",
    "description": "Visiting respondent X"
}
```
