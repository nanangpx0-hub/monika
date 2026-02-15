# MONIKA - API Documentation

**Last Updated:** 2026-02-16 00:50:52  
**Base URL:** `http://localhost/monika/`

---

## Authentication

All API endpoints require authentication via session. User must login first.

**Login Endpoint:**
- **URL:** `/login`
- **Method:** `POST`
- **Body:**
  ```json
  {
    "username": "string",
    "password": "string"
  }
  ```

---

## Endpoints

### Auth

#### index

- **URL:** `/a-u-t-h`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of a-u-t-h
- **Response:** HTML view or JSON array


#### login

- **URL:** `/a-u-t-h/login`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** login operation for a-u-t-h


#### registerForm

- **URL:** `/a-u-t-h/registerForm`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** registerForm operation for a-u-t-h


#### register

- **URL:** `/a-u-t-h/register`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** register operation for a-u-t-h


#### logout

- **URL:** `/a-u-t-h/logout`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** logout operation for a-u-t-h

---

### Dashboard

#### index

- **URL:** `/d-a-s-h-b-o-a-r-d`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of d-a-s-h-b-o-a-r-d
- **Response:** HTML view or JSON array

---

### Dokumen

#### index

- **URL:** `/d-o-k-u-m-e-n`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of d-o-k-u-m-e-n
- **Response:** HTML view or JSON array


#### create

- **URL:** `/d-o-k-u-m-e-n/create`
- **Method:** `POST`
- **Auth Required:** Yes
- **Description:** create operation for d-o-k-u-m-e-n


#### store

- **URL:** `/d-o-k-u-m-e-n/store`
- **Method:** `POST`
- **Auth Required:** Yes
- **Description:** Store new d-o-k-u-m-e-n data
- **Response:** Redirect or JSON
- **Body:** Form data (application/x-www-form-urlencoded)


#### markEntry

- **URL:** `/d-o-k-u-m-e-n/markEntry/{id}`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** markEntry operation for d-o-k-u-m-e-n


#### reportError

- **URL:** `/d-o-k-u-m-e-n/reportError`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** reportError operation for d-o-k-u-m-e-n

---

### KartuKendali

#### index

- **URL:** `/k-a-r-t-u-k-e-n-d-a-l-i`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of k-a-r-t-u-k-e-n-d-a-l-i
- **Response:** HTML view or JSON array


#### detail

- **URL:** `/k-a-r-t-u-k-e-n-d-a-l-i/detail/{nks}`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get detail of specific k-a-r-t-u-k-e-n-d-a-l-i
- **Response:** HTML view or JSON object


#### store

- **URL:** `/k-a-r-t-u-k-e-n-d-a-l-i/store`
- **Method:** `POST`
- **Auth Required:** Yes
- **Description:** Store new k-a-r-t-u-k-e-n-d-a-l-i data
- **Response:** Redirect or JSON
- **Body:** Form data (application/x-www-form-urlencoded)


#### delete

- **URL:** `/k-a-r-t-u-k-e-n-d-a-l-i/delete`
- **Method:** `DELETE/GET`
- **Auth Required:** Yes
- **Description:** Delete k-a-r-t-u-k-e-n-d-a-l-i data
- **Response:** Redirect or JSON

---

### Kegiatan

#### index

- **URL:** `/k-e-g-i-a-t-a-n`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of k-e-g-i-a-t-a-n
- **Response:** HTML view or JSON array


#### store

- **URL:** `/k-e-g-i-a-t-a-n/store`
- **Method:** `POST`
- **Auth Required:** Yes
- **Description:** Store new k-e-g-i-a-t-a-n data
- **Response:** Redirect or JSON
- **Body:** Form data (application/x-www-form-urlencoded)


#### updateStatus

- **URL:** `/k-e-g-i-a-t-a-n/updateStatus/{id}`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** updateStatus operation for k-e-g-i-a-t-a-n


#### delete

- **URL:** `/k-e-g-i-a-t-a-n/delete/{id}`
- **Method:** `DELETE/GET`
- **Auth Required:** Yes
- **Description:** Delete k-e-g-i-a-t-a-n data
- **Response:** Redirect or JSON

---

### Laporan

#### pcl

- **URL:** `/l-a-p-o-r-a-n/pcl`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** pcl operation for l-a-p-o-r-a-n


#### pengolahan

- **URL:** `/l-a-p-o-r-a-n/pengolahan`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** pengolahan operation for l-a-p-o-r-a-n

---

### Logistik

#### index

- **URL:** `/l-o-g-i-s-t-i-k`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of l-o-g-i-s-t-i-k
- **Response:** HTML view or JSON array

---

### Monitoring

#### index

- **URL:** `/m-o-n-i-t-o-r-i-n-g`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of m-o-n-i-t-o-r-i-n-g
- **Response:** HTML view or JSON array

---

### Presensi

#### index

- **URL:** `/p-r-e-s-e-n-s-i`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of p-r-e-s-e-n-s-i
- **Response:** HTML view or JSON array


#### submit

- **URL:** `/p-r-e-s-e-n-s-i/submit`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** submit operation for p-r-e-s-e-n-s-i

---

### TandaTerima

#### index

- **URL:** `/t-a-n-d-a-t-e-r-i-m-a`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of t-a-n-d-a-t-e-r-i-m-a
- **Response:** HTML view or JSON array


#### new

- **URL:** `/t-a-n-d-a-t-e-r-i-m-a/new`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** Show form to create new t-a-n-d-a-t-e-r-i-m-a
- **Response:** HTML view


#### store

- **URL:** `/t-a-n-d-a-t-e-r-i-m-a/store`
- **Method:** `POST`
- **Auth Required:** Yes
- **Description:** Store new t-a-n-d-a-t-e-r-i-m-a data
- **Response:** Redirect or JSON
- **Body:** Form data (application/x-www-form-urlencoded)


#### edit

- **URL:** `/t-a-n-d-a-t-e-r-i-m-a/edit/{id}`
- **Method:** `PUT/POST`
- **Auth Required:** Yes
- **Description:** edit operation for t-a-n-d-a-t-e-r-i-m-a


#### update

- **URL:** `/t-a-n-d-a-t-e-r-i-m-a/update/{id}`
- **Method:** `PUT/POST`
- **Auth Required:** Yes
- **Description:** update operation for t-a-n-d-a-t-e-r-i-m-a


#### delete

- **URL:** `/t-a-n-d-a-t-e-r-i-m-a/delete/{id}`
- **Method:** `DELETE/GET`
- **Auth Required:** Yes
- **Description:** Delete t-a-n-d-a-t-e-r-i-m-a data
- **Response:** Redirect or JSON

---

### UjiPetik

#### index

- **URL:** `/u-j-i-p-e-t-i-k`
- **Method:** `GET`
- **Auth Required:** Yes
- **Description:** Get list of u-j-i-p-e-t-i-k
- **Response:** HTML view or JSON array


#### new

- **URL:** `/u-j-i-p-e-t-i-k/new`
- **Method:** `GET/POST`
- **Auth Required:** Yes
- **Description:** Show form to create new u-j-i-p-e-t-i-k
- **Response:** HTML view


#### edit

- **URL:** `/u-j-i-p-e-t-i-k/edit/{id}`
- **Method:** `PUT/POST`
- **Auth Required:** Yes
- **Description:** edit operation for u-j-i-p-e-t-i-k


#### store

- **URL:** `/u-j-i-p-e-t-i-k/store`
- **Method:** `POST`
- **Auth Required:** Yes
- **Description:** Store new u-j-i-p-e-t-i-k data
- **Response:** Redirect or JSON
- **Body:** Form data (application/x-www-form-urlencoded)


#### update

- **URL:** `/u-j-i-p-e-t-i-k/update/{id}`
- **Method:** `PUT/POST`
- **Auth Required:** Yes
- **Description:** update operation for u-j-i-p-e-t-i-k


#### delete

- **URL:** `/u-j-i-p-e-t-i-k/delete/{id}`
- **Method:** `DELETE/GET`
- **Auth Required:** Yes
- **Description:** Delete u-j-i-p-e-t-i-k data
- **Response:** Redirect or JSON

---

## Response Format

### Success Response (JSON)
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": {}
}
```

### Error Response (JSON)
```json
{
  "status": "error",
  "message": "Error description",
  "errors": {}
}
```

### HTML Response
Most endpoints return HTML views for browser access.

---

## CSRF Protection

All POST requests require CSRF token:

```html
<input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
```

Or in AJAX:
```javascript
$.ajax({
  headers: {
    'X-CSRF-TOKEN': meta[name="csrf-token"].attr('content')
  }
});
```

---

## Error Codes

- **200** - Success
- **302** - Redirect (after successful operation)
- **400** - Bad Request (validation error)
- **401** - Unauthorized (not logged in)
- **403** - Forbidden (no permission)
- **404** - Not Found
- **500** - Internal Server Error

---

## Rate Limiting

Currently no rate limiting implemented.

---

## Examples

### Example 1: Get Kartu Kendali List

```bash
GET /kartu-kendali
```

**Response:**
```html
<!-- HTML view with table -->
```

### Example 2: Store Entry Data

```bash
POST /kartu-kendali/store
Content-Type: application/x-www-form-urlencoded

nks=1234567890&no_ruta=1&status_entry=Clean&is_patch_issue=0
```

**Response:**
```json
{
  "status": "success",
  "message": "Data berhasil disimpan."
}
```

### Example 3: Get Uji Petik List

```bash
GET /uji-petik
```

**Response:**
```html
<!-- HTML view with table -->
```

---

**Generated by:** MONIKA API Documentation Generator  
**Date:** 2026-02-16 00:50:52
