# Dokumentasi Lengkap Sistem — KulinerReview

> **Platform:** Web-Based Culinary Review  
> **Framework:** CodeIgniter 4  
> **Database:** MySQL  
> **Map:** Leaflet.js + OpenStreetMap  
> **Geocoding:** Nominatim API  
> **Universitas:** Dian Nuswantoro (UDINUS) Semarang

---

## Daftar Isi

1. [Arsitektur Sistem](#1-arsitektur-sistem)
2. [Alur Sistem Frontend → Backend](#2-alur-sistem-frontend--backend)
3. [Entity Relationship Diagram (ERD)](#3-entity-relationship-diagram-erd)
4. [Normalisasi 3NF](#4-normalisasi-3nf)
5. [Relasi & Foreign Key](#5-relasi--foreign-key)
6. [Struktur Database](#6-struktur-database)
7. [Migration & Seeder](#7-migration--seeder)
8. [Autentikasi & Otorisasi](#8-autentikasi--otorisasi)
9. [Route & Filter Mapping](#9-route--filter-mapping)
10. [CRUD Matrix per Role](#10-crud-matrix-per-role)
11. [Fitur yang Telah Diimplementasikan](#11-fitur-yang-telah-diimplementasikan)
12. [API Endpoint](#12-api-endpoint)
13. [Panduan Menjalankan](#13-panduan-menjalankan)

---

## 1. Arsitektur Sistem

### 1.1 Arsitektur Umum

```
                        ┌──────────────────────────────────────────────┐
                        │           WEB BROWSER (User)                 │
                        │  HTML + Tailwind CSS + JavaScript (Vanilla)  │
                        └──────────┬────────────────┬──────────────────┘
                                   │                │
                        ┌──────────▼──┐       ┌────▼──────────┐
                        │  HTTP/GET   │       │  POST/PUT/    │
                        │  (Public)   │       │  DELETE/AJAX  │
                        └──────┬──────┘       └──────┬─────────┘
                               │                     │
                        ┌──────▼─────────────────────▼──────────┐
                        │         CodeIgniter 4 Routing         │
                        │        app/Config/Routes.php          │
                        └──────┬────────────────────────────────┘
                               │
                    ┌──────────┼──────────────┐
                    │          │              │
             ┌──────▼───┐ ┌───▼────┐   ┌─────▼─────┐
             │ Auth     │ │Admin   │   │ API       │
             │ Filter   │ │Filter  │   │ (Public)  │
             │(session) │ │(role)  │   │           │
             └──────┬───┘ └───┬────┘   └─────┬─────┘
                    │         │              │
             ┌──────▼─────────▼──────────────▼──────┐
             │         CONTROLLERS                  │
             │  AuthController, PlaceController,     │
             │  ReviewController, FavoriteController,│
             │  Admin/DashboardController,           │
             │  Admin/CategoryController,            │
             │  Admin/TagController,                 │
             │  Api/KulinerApiController             │
             └──────────────────┬───────────────────┘
                                │
             ┌──────────────────▼───────────────────┐
             │            MODELS                    │
             │  UserModel, PlaceModel, CategoryModel,│
             │  TagModel, ReviewModel, FavoriteModel,│
             │  PlaceTagModel, NotificationModel     │
             └──────────────────┬───────────────────┘
                                │
             ┌──────────────────▼───────────────────┐
             │          MYSQL DATABASE              │
             │         kuliner_review               │
             │  8 tables + 1 pivot table            │
             └──────────────────────────────────────┘
```

### 1.2 Integrasi API Eksternal

```
┌─────────┐    AJAX /geocode?q=alamat     ┌──────────────┐
│ Browser │ ────────────────────────────── ▶  Nominatim  │
│ (User)  │ ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─  │  API (OSM)  │
└─────────┘    JSON {lat, lon, display}    └──────────────┘

┌─────────┐    Leaflet.js CDN              ┌──────────────┐
│ Browser │ ────────────────────────────── ▶  OSM Tiles   │
│ (Map)   │ ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─  │  Server      │
└─────────┘    Map Tiles (PNG)             └──────────────┘
```

---

## 2. Alur Sistem Frontend → Backend

### 2.1 Alur Submit Tempat Baru (Contributor)

```
[FRONTEND]                                [BACKEND]
───────────                               ─────────
User klik "Tambah Tempat"
        │
        ▼
GET /places/create                        PlaceController::create()
        │                                    ├─ Load categories dari DB
        │                                    ├─ Load tags dari DB
        │                                    └─ Return view + data
        ▼
Form tampil dengan Leaflet map
        │
User isi form:
- Nama, kategori, alamat
        │
        ▼
User klik "Cari Lokasi"
        │
        ▼
fetch(/geocode?q=alamat)                PlaceController::geocode()
  ──────────────────────────────▶           ├─ GET ke Nominatim API
        │                                   │  https://nominatim.openstreetmap.org/
        │                                   │  search?q=ALAMAT&format=json
        │                                   ├─ Parse JSON response
        │                                   └─ Return JSON {lat, lon, display_name}
        ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
Isi lat/lng otomatis
Marker peta berpindah
        │
        ▼
User pilih tag, upload foto, isi deskripsi
        │
        ▼
User klik "Simpan"
        │
        ▼
POST /places                              PlaceController::store()
  ──────────────────────────────▶           ├─ Validasi input
                                            │  (name, category_id, address required)
                                            ├─ Upload & resize foto (800px max)
                                            ├─ Insert data ke tabel `places`
                                            │  (status = 'pending')
                                            ├─ Sync tags ke `place_tags`
                                            └─ Redirect + Flash success
        ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
Notifikasi "Menunggu persetujuan admin"
```

### 2.2 Alur Moderasi Admin

```
[FRONTEND]                                [BACKEND]
───────────                               ─────────
Admin login (admin@udinus.ac.id)
        │
        ▼
GET /admin/places/pending                 Admin\PlaceController::pending()
        │                                    ├─ Query places WHERE status = 'pending'
        │                                    └─ Return view + data
        ▼
List tempat pending dengan detail
        │
        ├── Klik "Setujui"
        │   POST /admin/places/X/approve    Admin\PlaceController::approve($id)
        │   ───────────────────────────▶       ├─ Update status = 'approved'
        │                                      ├─ Create notifikasi ke contributor
        │                                      └─ Redirect + Flash success
        │
        └── Klik "Tolak"
            Masukkan alasan penolakan
            POST /admin/places/X/reject     Admin\PlaceController::reject($id)
            ───────────────────────────▶       ├─ Update status = 'rejected'
                                              ├─ Simpan rejection_note
                                              ├─ Create notifikasi ke contributor
                                              └─ Redirect + Flash success
```

### 2.3 Alur Review & Rating

```
[FRONTEND]                                [BACKEND]
───────────                               ─────────
User login → Buka detail tempat
        │
        ▼
GET /places/123                           PlaceController::show($id)
        │                                    ├─ Get detail place + relasi
        │                                    ├─ Get tags, reviews, avg_rating
        │                                    ├─ Check favorite status
        │                                    └─ Return view + data
        ▼
Tampil: detail, peta Leaflet, form review
        │
User pilih rating (1-5 bintang)
User tulis komentar
        │
        ▼
POST /places/123/reviews                  ReviewController::store($placeId)
  ──────────────────────────────▶           ├─ Validasi rating (1-5)
                                            ├─ Cek duplikat review
                                            ├─ Insert ke tabel `reviews`
                                            └─ Redirect back + Flash success
        ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
Review tampil di halaman detail
Rata-rata rating otomatis terupdate
```

### 2.4 Alur Pencarian & Filter

```
[FRONTEND]                                [BACKEND]
───────────                               ─────────
User buka /places
        │
        ▼
Form filter dengan:
- Keyword (q)
- Kategori (category)
- Tag (tag)
- Rating minimal (min_rating)
        │
        ▼
GET /places?q=kata&category=X             PlaceController::index()
  &tag=Y&min_rating=3                      ├─ Query builder dinamis
  ──────────────────────────────▶            ├─ JOIN categories, users, place_tags
                                            ├─ WHERE status = 'approved'
                                            ├─ Filter keyword (LIKE name/address/category)
                                            ├─ Filter kategori (category_id)
                                            ├─ Filter tag (place_tags.tag_id)
                                            ├─ Filter rating (subquery AVG reviews)
                                            ├─ GROUP BY places.id
                                            ├─ ORDER BY created_at DESC
                                            ├─ Paginate(12)
                                            └─ Return view + data + pager
        ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
Tampil hasil filter dengan pagination
```

### 2.5 Alur API Publik

```
[FRONTEND]                                [BACKEND]
───────────                               ─────────
Aplikasi eksternal / poster kampus
        │
        ▼
GET /api/kuliner?lat=-6.9785             KulinerApiController::index()
  &lng=110.4085&radius=5                   ├─ Validasi parameter lat, lng
  ──────────────────────────────▶           ├─ Query semua place approved
                                            ├─ Hitung jarak via Haversine formula
                                            ├─ Filter by radius (<= 5 km)
                                            ├─ Sort by distance ASC
                                            └─ Respond JSON {success, count, data}
        ◀ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
[
  { "id": 1, "name": "...",
    "distance_km": 0.8,
    "latitude": -6.973, "longitude": 110.412, ... },
  ...
]
```

---

## 3. Entity Relationship Diagram (ERD)

### 3.1 ERD Lengkap dengan Notasi Standar

```
 LEGENDA:
 ┌───────────────┐   = ENTITAS (Persegi Panjang)
 │   ENTITAS     │
 └───────────────┘
       ◆           = RELASI (Belah Ketupat)
     1 | N         = KARDINALITAS (One-to-Many / Many-to-One)
     M | N         = KARDINALITAS (Many-to-Many)
     ───           = GARIS HUBUNG RELASI


 ┌─────────────────────────────────────────────────────────────────┐
 │                     ENTITY RELATIONSHIP DIAGRAM                │
 └─────────────────────────────────────────────────────────────────┘

  ┌──────────────┐         ┌──────────────┐         ┌──────────────┐
  │    users     │         │  categories  │         │     tags     │
  ├──────────────┤         ├──────────────┤         ├──────────────┤
  │ id (PK)      │         │ id (PK)      │         │ id (PK)      │
  │ username (UQ)│         │ name (UQ)    │         │ name (UQ)    │
  │ email (UQ)   │         │ slug (UQ)    │         │ slug (UQ)    │
  │ password     │         │ description  │         │ created_at   │
  │ full_name    │         │ created_at   │         │ updated_at   │
  │ role (ENUM)  │         │ updated_at   │         └──────┬───────┘
  │ avatar       │         └──────┬───────┘                │
  │ created_at   │                │                        │
  │ updated_at   │                │                        │
  └──────┬───────┘                │                        │
         │                       ◆                        ◆
         │                    mengklasifikasi          memiliki
         │                  (1:N) ─── (category)    (M:N) ── (tag)
         │                       │                        │
         │  ◆ membuat            │                        │
         │ (1:N) ── (user)       │                        │
         │     │                 │                        │
         │     └─────────────────┼────────────────────────┘
         │                       │
         │              ┌────────▼──────────────────────────────┐
         │              │              places                   │
         │              ├──────────────────────────────────────┤
         │              │ id (PK)       FK: user_id, category_id│
         │              │ name, slug (UQ), description          │
         │              │ address, latitude, longitude          │
         │              │ image, status (ENUM), rejection_note  │
         │              │ is_closed, created_at, updated_at     │
         │              └──────────┬───────────────────────────┘
         │              ┌──────────┼──────────┐
         │              │          │          │
         │              │          │          │
         ◆ menulis      │          │          ◆ difavoritkan
       (1:N) ── (user)  │          │       (1:N) ── (place)
         │              │          │          │
         │         ┌────▼──┐  ┌───▼────┐  ┌──▼──────────┐
         │         │reviews│  │notifica│  │  favorites  │
         │         ├───────┤  │ tions  │  ├─────────────┤
         │         │id(PK) │  ├────────┤  │ id (PK)     │
         │         │user_id│  │id (PK) │  │ user_id (FK)│
         │         │placeid│  │user_id │  │ place_id(FK)│
         │         │rating │  │title   │  │ created_at  │
         │         │comment│  │message │  └─────────────┘
         │         │create │  │is_read │
         │         │update │  │created │
         │         └───────┘  └────────┘
         │
         ◆ menerima notifikasi
       (1:N) ── (user)
```

### 3.2 Ringkasan Kardinalitas

| Diagram                | Tabel A      | Relasi            | Tabel B         | Tipe      | Penjelasan                                 |
| ---------------------- | ------------ | ----------------- | --------------- | --------- | ------------------------------------------ |
| 🟦 Persegi: users      | `users`      | ◆ membuat         | `places`        | **1 : N** | Satu user dapat membuat banyak tempat      |
| 🟦 Persegi: categories | `categories` | ◆ mengklasifikasi | `places`        | **1 : N** | Satu kategori dapat dipakai banyak tempat  |
| 🟦 Persegi: tags       | `tags`       | ◆ dimiliki        | `places`        | **M : N** | Banyak tag ↔ banyak tempat (via pivot)     |
| 🟦 Persegi: users      | `users`      | ◆ menulis         | `reviews`       | **1 : N** | Satu user dapat menulis banyak review      |
| 🟦 Persegi: users      | `users`      | ◆ menerima        | `notifications` | **1 : N** | Satu user dapat menerima banyak notifikasi |
| 🟦 Persegi: users      | `users`      | ◆ menyimpan       | `favorites`     | **1 : N** | Satu user dapat menyimpan banyak favorit   |
| 🟦 Persegi: places     | `places`     | ◆ menerima        | `reviews`       | **1 : N** | Satu tempat dapat menerima banyak review   |
| 🟦 Persegi: places     | `places`     | ◆ difavoritkan    | `favorites`     | **1 : N** | Satu tempat dapat difavoritkan banyak user |
| ◆ Belah Ketupat        | `places`     | ↔ `place_tags`    | `tags`          | **M : N** | Pivot table many-to-many                   |

### 3.3 Penjelasan Notasi

| Simbol         | Nama                                | Makna                                                            |
| -------------- | ----------------------------------- | ---------------------------------------------------------------- |
| `┌──────────┐` | **Persegi Panjang** (Rectangle)     | **Entitas / Tabel** — menyimpan data                             |
| `│  ENTITY  │` |                                     | Contoh: `users`, `places`, `reviews`                             |
| `└──────────┘` |                                     |                                                                  |
| `◆`            | **Belah Ketupat** (Diamond/Rhombus) | **Relasi / Verb** — menghubungkan dua entitas                    |
|                |                                     | Contoh: "membuat", "menulis", "mengklasifikasi"                  |
| `1` — `N`      | **Garis Satu ke Banyak**            | **One-to-Many** — satu record di A terkait banyak record di B    |
| `M` — `N`      | **Garis Banyak ke Banyak**          | **Many-to-Many** — butuh tabel pivot untuk implementasi          |
| `1` — `1`      | **Garis Satu ke Satu**              | **One-to-One** — satu record di A terkait tepat satu record di B |
| `(PK)`         | Primary Key                         | Kunci utama tabel                                                |
| `(FK)`         | Foreign Key                         | Kunci asing ke tabel lain                                        |
| `(UQ)`         | Unique Key                          | Nilai unik (tidak boleh duplikat)                                |

### 3.4 Detail Relasi

#### One-to-Many (1:N)

```
   users                       places
 ┌──────────┐    ◆ membuat    ┌──────────┐
 │ 1 user   │ ──────────────▶ │ N places │
 │          │    (user_id)    │          │
 └──────────┘                 └──────────┘

 categories                   places
 ┌──────────┐    ◆ mengklasi- ┌──────────┐
 │ 1 cat    │ ── fikasi ────▶ │ N places │
 │          │   (category_id) │          │
 └──────────┘                 └──────────┘
```

#### Many-to-Many (M:N)

```
 places           place_tags            tags
 ┌──────────┐   ┌──────────────┐   ┌──────────┐
 │ M places │──▶│ place_id(PK) │◀──│ N tags   │
 │          │   │ tag_id (PK)  │   │          │
 └──────────┘   └──────────────┘   └──────────┘
                ↑ Pivot table ↑
```

#### One-to-One (Kontekstual via UNIQUE Constraint)

```
 users ──→ reviews per place
  1 user hanya boleh memberi 1 review untuk 1 tempat
  → CONSTRAINT UNIQUE(user_id, place_id)

 users ──→ favorites per place
  1 user hanya boleh memiliki 1 favorit untuk 1 tempat
  → CONSTRAINT UNIQUE(user_id, place_id)
```

---

## 4. Normalisasi 3NF

### 4.1 First Normal Form (1NF)

**Kriteria**: Setiap kolom berisi nilai atomik (tidak ada multi-value).

❌ Pelanggaran jika `tags` disimpan sebagai `tag1, tag2, tag3` dalam satu kolom.  
✅ Solusi: Tabel pivot `place_tags` dengan satu baris per kombinasi place-tag.

### 4.2 Second Normal Form (2NF)

**Kriteria**: 1NF + setiap kolom non-key bergantung penuh pada primary key.

❌ Pelanggaran jika `category_name` disimpan di tabel `places`.  
✅ Solusi: Pisahkan `categories` menjadi tabel sendiri, `places` hanya menyimpan `category_id` (FK).

### 4.3 Third Normal Form (3NF)

**Kriteria**: 2NF + tidak ada dependensi transitif.

❌ Pelanggaran jika `contributor_name` disimpan di tabel `places`.  
✅ Solusi: `contributor_name` diambil dari tabel `users` via JOIN.

### 4.4 Ringkasan 3NF per Tabel

| Tabel             | 1NF | 2NF | 3NF | Bukti                                                |
| ----------------- | :-: | :-: | :-: | ---------------------------------------------------- |
| **users**         | ✅  | ✅  | ✅  | Kolom atomik, PK tunggal, tanpa dependensi transitif |
| **categories**    | ✅  | ✅  | ✅  | Deskripsi bergantung pada PK                         |
| **tags**          | ✅  | ✅  | ✅  | Struktur sederhana                                   |
| **places**        | ✅  | ✅  | ✅  | category_name & contributor_name via JOIN            |
| **place_tags**    | ✅  | ✅  | ✅  | Composite PK, tidak ada kolom non-key                |
| **reviews**       | ✅  | ✅  | ✅  | UNIQUE(user_id,place_id), tanpa duplikasi            |
| **favorites**     | ✅  | ✅  | ✅  | UNIQUE(user_id,place_id), tanpa duplikasi            |
| **notifications** | ✅  | ✅  | ✅  | title & message bergantung pada PK                   |

---

## 5. Relasi & Foreign Key

### 5.1 Foreign Key Constraints

| Tabel Asal      | Kolom FK      | Tabel Tujuan | Kolom | ON DELETE | ON UPDATE |
| --------------- | ------------- | ------------ | ----- | :-------: | :-------: |
| `places`        | `user_id`     | `users`      | `id`  |  CASCADE  |  CASCADE  |
| `places`        | `category_id` | `categories` | `id`  |  CASCADE  |  CASCADE  |
| `place_tags`    | `place_id`    | `places`     | `id`  |  CASCADE  |  CASCADE  |
| `place_tags`    | `tag_id`      | `tags`       | `id`  |  CASCADE  |  CASCADE  |
| `reviews`       | `user_id`     | `users`      | `id`  |  CASCADE  |  CASCADE  |
| `reviews`       | `place_id`    | `places`     | `id`  |  CASCADE  |  CASCADE  |
| `favorites`     | `user_id`     | `users`      | `id`  |  CASCADE  |  CASCADE  |
| `favorites`     | `place_id`    | `places`     | `id`  |  CASCADE  |  CASCADE  |
| `notifications` | `user_id`     | `users`      | `id`  |  CASCADE  |  CASCADE  |

### 5.2 Unique Constraints

| Tabel        | Kolom                 | Tujuan                           |
| ------------ | --------------------- | -------------------------------- |
| `users`      | `username`            | Tidak boleh ganda                |
| `users`      | `email`               | Tidak boleh ganda                |
| `categories` | `name`, `slug`        | Unik                             |
| `tags`       | `name`, `slug`        | Unik                             |
| `places`     | `slug`                | URL slug unik                    |
| `reviews`    | `(user_id, place_id)` | 1 user 1 review per tempat       |
| `favorites`  | `(user_id, place_id)` | 1 user 1 favorit per tempat      |
| `place_tags` | `(place_id, tag_id)`  | Tidak boleh tag ganda per tempat |

---

## 6. Struktur Database

### 6.1 Tabel `users`

Menyimpan data pengguna dengan dua peran: **admin** dan **contributor**.

| Kolom        | Tipe                        | Constraint                      | Keterangan                      |
| ------------ | --------------------------- | ------------------------------- | ------------------------------- |
| `id`         | INT UNSIGNED                | PK, AUTO_INCREMENT              | Primary key                     |
| `username`   | VARCHAR(50)                 | UNIQUE, NOT NULL                | Nama pengguna unik              |
| `email`      | VARCHAR(255)                | UNIQUE, NOT NULL                | Email unik                      |
| `password`   | VARCHAR(255)                | NOT NULL                        | Hash bcrypt (auto via callback) |
| `full_name`  | VARCHAR(100)                | NOT NULL                        | Nama lengkap                    |
| `role`       | ENUM('admin','contributor') | NOT NULL, default 'contributor' | Peran pengguna                  |
| `avatar`     | VARCHAR(255)                | NULL                            | Path foto profil                |
| `created_at` | DATETIME                    | AUTO                            | Waktu registrasi                |
| `updated_at` | DATETIME                    | AUTO                            | Waktu update                    |

### 6.2 Tabel `categories`

Kategori jenis tempat kuliner. Slug di-generate otomatis.

| Kolom         | Tipe         | Constraint         | Keterangan         |
| ------------- | ------------ | ------------------ | ------------------ |
| `id`          | INT UNSIGNED | PK, AUTO_INCREMENT | Primary key        |
| `name`        | VARCHAR(50)  | UNIQUE, NOT NULL   | Nama kategori      |
| `slug`        | VARCHAR(50)  | UNIQUE, NOT NULL   | URL-friendly name  |
| `description` | TEXT         | NULL               | Deskripsi opsional |

### 6.3 Tabel `tags`

Label/tag tambahan untuk tempat kuliner.

| Kolom  | Tipe         | Constraint         | Keterangan        |
| ------ | ------------ | ------------------ | ----------------- |
| `id`   | INT UNSIGNED | PK, AUTO_INCREMENT | Primary key       |
| `name` | VARCHAR(50)  | UNIQUE, NOT NULL   | Nama tag          |
| `slug` | VARCHAR(50)  | UNIQUE, NOT NULL   | URL-friendly name |

### 6.4 Tabel `places`

Inti aplikasi. Tempat kuliner dengan sistem moderasi tiga tahap.

| Kolom            | Tipe                                  | Constraint           | Keterangan                  |
| ---------------- | ------------------------------------- | -------------------- | --------------------------- |
| `id`             | INT UNSIGNED                          | PK, AUTO_INCREMENT   | Primary key                 |
| `user_id`        | INT UNSIGNED                          | FK → `users.id`      | Pemilik/contributor         |
| `category_id`    | INT UNSIGNED                          | FK → `categories.id` | Kategori tempat             |
| `name`           | VARCHAR(150)                          | NOT NULL             | Nama tempat kuliner         |
| `slug`           | VARCHAR(150)                          | UNIQUE               | Auto-generated dari name    |
| `description`    | TEXT                                  | NULL                 | Deskripsi opsional          |
| `address`        | VARCHAR(255)                          | NOT NULL             | Alamat lengkap              |
| `latitude`       | DECIMAL(10,7)                         | NULL                 | Koordinat peta              |
| `longitude`      | DECIMAL(10,7)                         | NULL                 | Koordinat peta              |
| `image`          | VARCHAR(255)                          | NULL                 | Path gambar utama           |
| `status`         | ENUM('pending','approved','rejected') | default 'pending'    | Status moderasi             |
| `rejection_note` | TEXT                                  | NULL                 | Alasan penolakan admin      |
| `is_closed`      | TINYINT(1)                            | default 0            | Tanda tempat tutup permanen |
| `created_at`     | DATETIME                              | AUTO                 | Waktu submit                |
| `updated_at`     | DATETIME                              | AUTO                 | Waktu diperbarui            |

### 6.5 Tabel `place_tags` (Pivot)

Tabel junction M:N antara `places` dan `tags`.

| Kolom      | Tipe         | Constraint                      | Keterangan |
| ---------- | ------------ | ------------------------------- | ---------- |
| `place_id` | INT UNSIGNED | Composite PK + FK → `places.id` | ID tempat  |
| `tag_id`   | INT UNSIGNED | Composite PK + FK → `tags.id`   | ID tag     |

### 6.6 Tabel `reviews`

Ulasan dan rating (1–5 bintang) dari user.

| Kolom        | Tipe         | Constraint                | Keterangan                  |
| ------------ | ------------ | ------------------------- | --------------------------- |
| `id`         | INT UNSIGNED | PK, AUTO_INCREMENT        | Primary key                 |
| `user_id`    | INT UNSIGNED | FK → `users.id`           | Reviewer                    |
| `place_id`   | INT UNSIGNED | FK → `places.id`          | Tempat yang diulas          |
| `rating`     | TINYINT(1)   | CHECK 1–5                 | Rating bintang              |
| `comment`    | TEXT         | NULL                      | Komentar opsional           |
| `created_at` | DATETIME     | AUTO                      | Waktu dibuat                |
| `updated_at` | DATETIME     | AUTO                      | Waktu diperbarui            |
|              |              | UNIQUE(user_id, place_id) | 1 review per user per place |

### 6.7 Tabel `favorites`

Bookmark/simpan tempat kuliner favorit.

| Kolom        | Tipe         | Constraint                | Keterangan                   |
| ------------ | ------------ | ------------------------- | ---------------------------- |
| `id`         | INT UNSIGNED | PK, AUTO_INCREMENT        | Primary key                  |
| `user_id`    | INT UNSIGNED | FK → `users.id`           | User yang menyimpan          |
| `place_id`   | INT UNSIGNED | FK → `places.id`          | Tempat yang disimpan         |
| `created_at` | DATETIME     | AUTO                      | Waktu disimpan               |
|              |              | UNIQUE(user_id, place_id) | Tidak bisa duplikasi favorit |

### 6.8 Tabel `notifications`

Notifikasi status moderasi untuk contributor.

| Kolom        | Tipe         | Constraint         | Keterangan                     |
| ------------ | ------------ | ------------------ | ------------------------------ |
| `id`         | INT UNSIGNED | PK, AUTO_INCREMENT | Primary key                    |
| `user_id`    | INT UNSIGNED | FK → `users.id`    | Penerima notifikasi            |
| `title`      | VARCHAR(100) | NOT NULL           | Judul notifikasi               |
| `message`    | TEXT         | NOT NULL           | Isi pesan                      |
| `is_read`    | TINYINT(1)   | default 0          | 0 = belum baca, 1 = sudah baca |
| `created_at` | DATETIME     | AUTO               | Waktu dikirim                  |

---

## 7. Migration & Seeder

### 7.1 Urutan Migration (9 file)

| #   | File                                         | Tabel            | Keterangan                          |
| --- | -------------------------------------------- | ---------------- | ----------------------------------- |
| 1   | `2026-04-29-000001_CreateUsersTable`         | `users`          | Tabel pengguna                      |
| 2   | `2026-04-29-000002_CreateCategoriesTable`    | `categories`     | Tabel kategori                      |
| 3   | `2026-04-29-000003_CreateTagsTable`          | `tags`           | Tabel tag                           |
| 4   | `2026-04-29-000004_CreatePlacesTable`        | `places`         | Tabel tempat (FK users, categories) |
| 5   | `2026-04-29-000005_CreatePlaceTagsTable`     | `place_tags`     | Pivot M:N (FK places, tags)         |
| 6   | `2026-04-29-000006_CreateReviewsTable`       | `reviews`        | Tabel review (FK users, places)     |
| 7   | `2026-04-29-000007_CreateFavoritesTable`     | `favorites`      | Tabel favorit (FK users, places)    |
| 8   | `2026-04-29-000008_CreateNotificationsTable` | `notifications`  | Tabel notifikasi (FK users)         |
| 9   | `2026-04-29-100600_AddIsClosedToPlaces`      | `places` (alter) | Tambah kolom `is_closed`            |

### 7.2 Seeder: `KulinerSeeder`

**Data yang di-seed:**

| Tabel           | Jumlah | Detail                                                                             |
| --------------- | :----: | ---------------------------------------------------------------------------------- |
| `users`         |   5    | 1 admin + 4 contributor                                                            |
| `categories`    |   6    | Angkringan, Warung Makan, Kedai Kopi, Jajanan Kaki Lima, Rumah Makan, Café & Resto |
| `tags`          |   10   | Murah Meriah, Halal, 24 Jam, Wifi Gratis, Parkir Luas, dll.                        |
| `places`        |   27   | 24 approved + 2 pending + 1 rejected (lokasi nyata UDINUS Semarang)                |
| `place_tags`    |   58   | Relasi tempat-tag                                                                  |
| `reviews`       |  ~80   | Rating 1-5, komentar realistis                                                     |
| `favorites`     |  ~25   | Favorit acak tanpa duplikat                                                        |
| `notifications` |   5    | Contoh notifikasi approved & rejected                                              |

**Akun Demo:**

| Role        | Email                        | Password     |
| ----------- | ---------------------------- | ------------ |
| Admin       | `admin@udinus.ac.id`         | `admin123`   |
| Contributor | `budi@student.udinus.ac.id`  | `contrib123` |
| Contributor | `siti@student.udinus.ac.id`  | `contrib123` |
| Contributor | `rendi@student.udinus.ac.id` | `contrib123` |
| Contributor | `dewi@student.udinus.ac.id`  | `contrib123` |

### 7.3 Perintah

```bash
# Jalankan migration
php spark migrate

# Seed data
php spark db:seed KulinerSeeder

# Reset total
php spark migrate:refresh && php spark db:seed KulinerSeeder
```

---

## 8. Autentikasi & Otorisasi

### 8.1 Session Data

Saat login, session menyimpan:

- `isLoggedIn` (boolean) — status login
- `user_id` (int) — ID pengguna
- `username` (string) — username
- `full_name` (string) — nama lengkap
- `role` (string) — "admin" atau "contributor"

### 8.2 Auth Flow

```
POST /login
  ├─ Validasi input (email + password)
  ├─ UserModel::verifyLogin()
  │   ├─ Cari user by email
  │   ├─ password_verify() → bcrypt
  │   ├─ Sukses → Set session → Redirect sesuai role
  │   │   ├─ admin → /admin
  │   │   └─ contributor → /dashboard
  │   └─ Gagal → Flash error → redirect /login
  └─ Validasi gagal → redirect back
```

### 8.3 Filter System

| Filter       | File                         | Fungsi                                                                                                                                    |
| ------------ | ---------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| `AuthFilter` | `app/Filters/AuthFilter.php` | Cek `session('isLoggedIn')`. Jika belum login → redirect ke `/login` dengan flash error. Simpan `intended_url` untuk post-login redirect. |
| `RoleFilter` | `app/Filters/RoleFilter.php` | Cek `session('role')` cocok dengan argumen (misal `role:admin`). Jika tidak cocok → return 403 Forbidden.                                 |

### 8.4 Password Hashing

```php
// UserModel — otomatis hash sebelum insert/update
protected $beforeInsert = ['hashPassword'];
protected $beforeUpdate = ['hashPassword'];
// Menggunakan password_hash() dengan PASSWORD_DEFAULT (bcrypt)
```

---

## 9. Route & Filter Mapping

### 9.1 Public Routes (Tanpa Filter)

| Method   | Route             | Controller                              | Fungsi                 |
| -------- | ----------------- | --------------------------------------- | ---------------------- |
| GET      | `/`               | `Home::index`                           | Beranda                |
| GET      | `/uploads/(:any)` | `Home::serveUpload`                     | Sajikan file upload    |
| GET      | `/places`         | `PlaceController::index`                | Daftar tempat + filter |
| GET      | `/places/(:num)`  | `PlaceController::show`                 | Detail tempat          |
| GET/POST | `/login`          | `AuthController::loginForm/login`       | Login                  |
| GET/POST | `/register`       | `AuthController::registerForm/register` | Registrasi             |
| GET      | `/logout`         | `AuthController::logout`                | Logout                 |
| GET      | `/api/kuliner`    | `Api\KulinerApiController::index`       | API publik             |

### 9.2 Contributor Routes (Filter: `auth`)

| Method | Route                        | Controller                            | Fungsi                   |
| ------ | ---------------------------- | ------------------------------------- | ------------------------ |
| GET    | `/dashboard`                 | `DashboardController::index`          | Dashboard contributor    |
| GET    | `/geocode`                   | `PlaceController::geocode`            | AJAX geocoding Nominatim |
| GET    | `/places/create`             | `PlaceController::create`             | Form tambah tempat       |
| POST   | `/places`                    | `PlaceController::store`              | Simpan tempat baru       |
| GET    | `/places/(:num)/edit`        | `PlaceController::edit`               | Form edit tempat         |
| POST   | `/places/(:num)/update`      | `PlaceController::update`             | Update tempat            |
| POST   | `/places/(:num)/delete`      | `PlaceController::delete`             | Hapus tempat             |
| POST   | `/places/(:num)/mark-closed` | `PlaceController::markClosed`         | Tandai tutup permanen    |
| POST   | `/places/(:num)/reviews`     | `ReviewController::store`             | Tulis review             |
| GET    | `/reviews/(:num)/edit`       | `ReviewController::edit`              | Form edit review         |
| POST   | `/reviews/(:num)/update`     | `ReviewController::update`            | Update review            |
| POST   | `/reviews/(:num)/delete`     | `ReviewController::delete`            | Hapus review             |
| POST   | `/places/(:num)/favorite`    | `FavoriteController::toggle`          | Toggle favorit           |
| GET    | `/favorites`                 | `FavoriteController::index`           | Daftar favorit           |
| GET    | `/notifications`             | `NotificationController::index`       | Daftar notifikasi        |
| POST   | `/notifications/read`        | `NotificationController::markAllRead` | Tandai semua dibaca      |

### 9.3 Admin Routes (Filter: `role:admin`)

| Method | Route                                 | Controller                             | Fungsi                 |
| ------ | ------------------------------------- | -------------------------------------- | ---------------------- |
| GET    | `/admin`                              | `Admin\DashboardController::index`     | Dashboard admin        |
| GET    | `/admin/places`                       | `Admin\PlaceController::index`         | Kelola tempat          |
| GET    | `/admin/places/create`                | `Admin\PlaceController::create`        | Tambah tempat          |
| POST   | `/admin/places`                       | `Admin\PlaceController::store`         | Simpan tempat          |
| GET    | `/admin/places/(:num)/edit`           | `Admin\PlaceController::edit`          | Edit tempat            |
| POST   | `/admin/places/(:num)/update`         | `Admin\PlaceController::update`        | Update tempat          |
| POST   | `/admin/places/(:num)/delete`         | `Admin\PlaceController::delete`        | Hapus tempat           |
| GET    | `/admin/places/pending`               | `Admin\PlaceController::pending`       | Moderasi tempat        |
| POST   | `/admin/places/(:num)/approve`        | `Admin\PlaceController::approve`       | Setujui tempat         |
| POST   | `/admin/places/(:num)/reject`         | `Admin\PlaceController::reject`        | Tolak tempat           |
| POST   | `/admin/places/(:num)/approve-closed` | `Admin\PlaceController::approveClosed` | Setujui tutup permanen |
| POST   | `/admin/places/(:num)/reject-closed`  | `Admin\PlaceController::rejectClosed`  | Tolak tutup permanen   |
| GET    | `/admin/categories`                   | `Admin\CategoryController::index`      | Kelola kategori        |
| GET    | `/admin/categories/create`            | `Admin\CategoryController::create`     | Tambah kategori        |
| POST   | `/admin/categories`                   | `Admin\CategoryController::store`      | Simpan kategori        |
| GET    | `/admin/categories/(:num)/edit`       | `Admin\CategoryController::edit`       | Edit kategori          |
| PUT    | `/admin/categories/(:num)`            | `Admin\CategoryController::update`     | Update kategori        |
| DELETE | `/admin/categories/(:num)`            | `Admin\CategoryController::delete`     | Hapus kategori         |
| GET    | `/admin/tags`                         | `Admin\TagController::index`           | Kelola tag             |
| GET    | `/admin/tags/create`                  | `Admin\TagController::create`          | Tambah tag             |
| POST   | `/admin/tags`                         | `Admin\TagController::store`           | Simpan tag             |
| GET    | `/admin/tags/(:num)/edit`             | `Admin\TagController::edit`            | Edit tag               |
| PUT    | `/admin/tags/(:num)`                  | `Admin\TagController::update`          | Update tag             |
| DELETE | `/admin/tags/(:num)`                  | `Admin\TagController::delete`          | Hapus tag              |
| GET    | `/admin/users`                        | `Admin\UserController::index`          | Kelola user            |
| DELETE | `/admin/users/(:num)`                 | `Admin\UserController::delete`         | Hapus user             |
| GET    | `/admin/reviews`                      | `Admin\ReviewController::index`        | Kelola review          |
| POST   | `/admin/reviews/(:num)/delete`        | `Admin\ReviewController::delete`       | Hapus review           |

---

## 10. CRUD Matrix per Role

| Entitas           | Admin C | Admin R | Admin U | Admin D | Contributor C | Contributor R | Contributor U  | Contributor D |
| ----------------- | :-----: | :-----: | :-----: | :-----: | :-----------: | :-----------: | :------------: | :-----------: |
| **Categories**    |   ✅    |   ✅    |   ✅    |   ✅    |      ❌       |  ✅ (publik)  |       🔒       |      🔒       |
| **Tags**          |   ✅    |   ✅    |   ✅    |   ✅    |      ❌       |  ✅ (publik)  |       🔒       |      🔒       |
| **Places**        |   ✅    |   ✅    |   ✅    |   ✅    | ✅ (pending)  | ✅ (approved) |    ✅ (own)    |   ✅ (own)    |
| **Reviews**       |   ❌    |   ✅    |   ❌    |   ✅    | ✅ (1x/place) |      ✅       |  ✅ (24h,own)  |   ✅ (own)    |
| **Users**         |   ❌    |   ✅    |   ❌    |   ✅    |      🔒       |      🔒       |       🔒       |      🔒       |
| **Favorites**     |   ❌    |   ❌    |   ❌    |   ❌    |  ✅ (toggle)  |   ✅ (own)    |       ❌       |  ✅ (toggle)  |
| **Notifications** |   ❌    |   ❌    |   ❌    |   ❌    |      ❌       |   ✅ (own)    | ✅ (mark read) |      ❌       |

**Keterangan:**

- C = Create, R = Read, U = Update, D = Delete
- ✅ = Available, ❌ = Not available, 🔒 = Not applicable
- `own` = Hanya data milik sendiri
- `pending` = Status default saat contributor submit
- `24h` = Edit hanya dalam 24 jam sejak dibuat

---

## 11. Fitur yang Telah Diimplementasikan

### 11.1 Role: Admin

| Fitur                            | Status | File Utama                  |
| -------------------------------- | :----: | --------------------------- |
| Kelola kategori (CRUD)           |   ✅   | `Admin\CategoryController`  |
| Kelola tag (CRUD)                |   ✅   | `Admin\TagController`       |
| Moderasi tempat (approve/reject) |   ✅   | `Admin\PlaceController`     |
| Moderasi review (hapus)          |   ✅   | `Admin\ReviewController`    |
| Dashboard statistik              |   ✅   | `Admin\DashboardController` |
| CRUD penuh tempat kuliner        |   ✅   | `Admin\PlaceController`     |
| Approve/reject tutup permanen    |   ✅   | `Admin\PlaceController`     |
| Kelola users (read/delete)       |   ✅   | `Admin\UserController`      |
| Pagination listing               |   ✅   | Semua admin listing         |

### 11.2 Role: Contributor (User Login)

| Fitur                           | Status | File Utama                      |
| ------------------------------- | :----: | ------------------------------- |
| Submit tempat baru              |   ✅   | `PlaceController::store`        |
| Geocoding otomatis Nominatim    |   ✅   | `PlaceController::geocode`      |
| Tulis review + rating (1-5)     |   ✅   | `ReviewController::store`       |
| Edit review sendiri (24 jam)    |   ✅   | `ReviewController::edit/update` |
| Upload foto + auto-resize 800px |   ✅   | `PlaceController::uploadImage`  |
| Simpan favorit (toggle)         |   ✅   | `FavoriteController::toggle`    |
| Tandai "tutup permanen"         |   ✅   | `PlaceController::markClosed`   |
| Notifikasi moderasi             |   ✅   | `NotificationModel`             |
| Dashboard contributor           |   ✅   | `DashboardController`           |

### 11.3 Role: Pengunjung (Tanpa Login)

| Fitur                              | Status | File Utama                     |
| ---------------------------------- | :----: | ------------------------------ |
| Browse tempat + filter kategori    |   ✅   | `PlaceController::index`       |
| Filter tag                         |   ✅   | `PlaceController::index` (NEW) |
| Filter rating minimal              |   ✅   | `PlaceController::index` (NEW) |
| Cari berdasarkan nama/alamat       |   ✅   | `PlaceModel::search`           |
| Lihat detail + foto + peta Leaflet |   ✅   | `PlaceController::show`        |
| Baca review contributor            |   ✅   | `PlaceController::show`        |
| Pagination (12 per halaman)        |   ✅   | `PlaceController::index` (NEW) |
| Unread notification badge          |   ✅   | `layouts/app.php` (NEW)        |

### 11.4 Fitur Sistem Lainnya

| Fitur                                          | Status |
| ---------------------------------------------- | :----: |
| Integrasi Nominatim (auto alamat → lat/lng)    |   ✅   |
| Peta interaktif Leaflet.js + OSM tiles         |   ✅   |
| Haversine formula untuk filter jarak API       |   ✅   |
| Perhitungan rata-rata rating otomatis          |   ✅   |
| Rata-rata rating dihitung via AVG() query      |   ✅   |
| Webservice API GET /api/kuliner?lat&lng&radius |   ✅   |
| Filter route dengan CI4 Filter (auth & role)   |   ✅   |
| Upload + resize otomatis (800px max)           |   ✅   |
| Pagination + Tailwind styled                   |   ✅   |
| Seeder 27 tempat kuliner sekitar kampus        |   ✅   |
| Validasi input (controller + model)            |   ✅   |
| Flash messages di semua operasi                |   ✅   |
| CSRF protection                                |   ✅   |

---

## 12. API Endpoint

### GET /api/kuliner

**Deskripsi:** Mengambil daftar tempat kuliner terdekat berdasarkan koordinat.

**Parameters:**

| Parameter | Tipe  | Required | Default | Deskripsi                             |
| --------- | ----- | :------: | :-----: | ------------------------------------- |
| `lat`     | float |    ✅    |    —    | Latitude posisi user                  |
| `lng`     | float |    ✅    |    —    | Longitude posisi user                 |
| `radius`  | float |    ❌    |   10    | Radius pencarian (km), min 1, max 100 |

**Response (200 OK):**

```json
{
  "success": true,
  "count": 5,
  "radius_km": 5,
  "data": [
    {
      "id": 1,
      "user_id": 2,
      "category_id": 3,
      "name": "Angkringan Pendopo",
      "slug": "angkringan-pendopo",
      "description": "...",
      "address": "Jl. Gajah Raya No.1",
      "latitude": "-6.9730000",
      "longitude": "110.4120000",
      "image": "abc123.jpg",
      "status": "approved",
      "is_closed": 0,
      "category_name": "Angkringan",
      "contributor_name": "Budi Santoso",
      "distance_km": 0.85
    }
  ]
}
```

**Algoritma Jarak — Haversine Formula:**

```
a = sin²(Δlat/2) + cos(lat1)·cos(lat2)·sin²(Δlng/2)
c = 2 · atan2(√a, √(1-a))
d = R · c    (R = 6371 km)
```

---

## 13. Panduan Menjalankan

### Prasyarat

- PHP 8.2+ (ekstensi: intl, mysqli, pdo_mysql, gd/imagick)
- MySQL 8.0+
- Composer

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repo-url> kuliner-review
cd kuliner-review

# 2. Install dependencies
composer install

# 3. Copy .env dan konfigurasi
copy .env.example .env
# Edit: app.baseURL, database.default.hostname/username/password/database

# 4. Buat database
mysql -u root -e "CREATE DATABASE kuliner_review DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_general_ci;"

# 5. Jalankan migration
php spark migrate

# 6. Seed data
php spark db:seed KulinerSeeder

# 7. Jalankan server development
php spark serve
```

Akses di `http://localhost:8080`

### Perintah Berguna

```bash
php spark migrate:refresh && php spark db:seed KulinerSeeder  # Reset total
php spark migrate:status                                       # Cek status migration
php spark routes                                               # Lihat semua route
```

### Akun Demo

| Role        | Email                        | Password     |
| ----------- | ---------------------------- | ------------ |
| Admin       | `admin@udinus.ac.id`         | `admin123`   |
| Contributor | `budi@student.udinus.ac.id`  | `contrib123` |
| Contributor | `siti@student.udinus.ac.id`  | `contrib123` |
| Contributor | `rendi@student.udinus.ac.id` | `contrib123` |
| Contributor | `dewi@student.udinus.ac.id`  | `contrib123` |

---

> **Dokumentasi ini diperbarui pada:** 20 Mei 2026  
> **Project:** KulinerReview — Pemrograman Web Lanjut UDINUS
