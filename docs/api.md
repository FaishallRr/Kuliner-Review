# API Documentation — KulinerReview (singkat)

Ringkasan endpoint publik dan usulan tambahan API untuk project Lokasi Kuliner & Review Jajanan.

---

## Endpoint yang sudah ada (diimplementasikan)

1. GET /api/kuliner
   - Deskripsi: Mengembalikan daftar tempat kuliner terdekat berdasarkan koordinat.
   - Query params: `lat` (required), `lng` (required), `radius` (km, optional, default 10)
   - Respon (200 JSON): `{ success: true, count: N, radius_km: R, data: [ ...place objects...] }`
   - Contoh:
     ```bash
     curl "http://localhost:8080/api/kuliner?lat=-6.98&lng=110.41&radius=3"
     ```

---

## API tambahan yang direkomendasikan (prioritas)

A. Public / Read
- GET /api/places
  - Daftar tempat (filterable): `q`, `category`, `tag`, `min_rating`, `page`.
- GET /api/places/{id}
  - Detail tempat + tags + reviews + avgRating.
- GET /api/categories
- GET /api/tags

B. Auth & Write (harus dilindungi — rekomendasi: token-based auth/JWT atau api_token)
- POST /api/auth/login
  - Body: `{ email, password }` -> respon: `{ token }`
  - Notes: token is short-lived (7 days by default). Use header `Authorization: Bearer <token>` for protected endpoints.
- POST /api/places
  - Buat tempat baru (auth). Body: `name,address,category_id,description,tags[],latitude?,longitude?,images[]`
- POST /api/places/{id}/reviews
  - Kirim review (auth). Body: `{ rating, comment }`
- POST /api/places/{id}/favorite
  - Toggle favorite (auth)

Auth usage example
------------------

1. Login to obtain token:

```bash
curl -X POST "http://localhost:8080/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"secret"}'
```

Response contains `token` which you send in `Authorization` header:

```bash
curl "http://localhost:8080/api/places" -H "Authorization: Bearer <token>"
```

C. Admin (auth: admin)
- GET /api/admin/places?status=pending
- POST /api/admin/places/{id}/approve
- POST /api/admin/places/{id}/reject

D. Opsional
- GET /api/search?q=... (unified search: places + categories + tags)
- POST /api/uploads (multipart) untuk upload gambar via API (auth)

---

## Skema singkat request/response (contoh)

- POST /api/auth/login
  - Request: `application/json`
    ```json
    { "email": "admin@udinus.ac.id", "password": "admin123" }
    ```
  - Response (200):
    ```json
    { "success": true, "token": "<bearer-token>" }
    ```

- POST /api/places (auth)
  - Request (multipart/form-data atau application/json):
    ```json
    {
      "name": "Warung Baru",
      "address": "Jl. Contoh No.1",
      "category_id": 2,
      "description": "Deskripsi",
      "tags": [1,5],
      "latitude": -6.979,
      "longitude": 110.407
    }
    ```
  - Response (201): `{ "success": true, "id": 123 }

---

## Rekomendasi implementasi cepat
- Buat `App\Controllers\Api\PlacesController` (ResourceController) untuk GET list/detail.
- Buat `App\Controllers\Api\AuthController` ringan yang mengembalikan `api_token` (simpan di `users.api_token` atau gunakan JWT jika ingin lebih aman).
- Reuse `PlaceModel`, `ReviewModel`, `TagModel` untuk logic; minimal perubahan diperlukan.
- Tambahkan filter middleware untuk memeriksa header `Authorization: Bearer <token>`.

---

Jika Anda setuju, sebutkan API mana yang mau saya implementasikan dulu (rekomendasi: `GET /api/places` + `GET /api/places/{id}` lalu `POST /api/auth/login` untuk token). Saya akan mulai mengimplementasikan pilihan Anda.
