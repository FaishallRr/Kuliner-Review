# KulinerReview - Platform Kuliner Sekitar Kampus UDINUS

> **Pemrograman Web Lanjut — UTS Komponen 1-4**  
> Universitas Dian Nuswantoro (UDINUS) Semarang

Platform berbasis web untuk menemukan, menambahkan, dan mengulas tempat makan/jajanan di sekitar kampus. Dilengkapi dengan geocoding otomatis (OpenStreetMap Nominatim), filter kategori & tag, sistem rating, dan peta interaktif (Leaflet.js).

---

## Daftar Isi

1. [Fitur Utama](#fitur-utama)
2. [Tech Stack](#tech-stack)
3. [Cara Instalasi](#cara-instalasi)
4. [Akun Demo](#akun-demo)
5. [Screenshot Fitur Utama](#screenshot-fitur-utama)
6. [Struktur Database](#struktur-database)
7. [API Endpoint](#api-endpoint)
8. [Lisensi](#lisensi)

---

## Fitur Utama

### Peran Admin
- ✅ Kelola kategori (angkringan, kafe, street food, dll)
- ✅ Kelola tag (halal, murah, AC, WiFi,parkir, dll)
- ✅ Moderasi tempat kuliner (approve/reject submission)
- ✅ Moderasi review (hapus review tidak pantas)
- ✅ Dashboard dengan statistik
- ✅ CRUD penuh data kuliner

### Peran Contributor (User Login)
- ✅ Submit tempat makan baru
- ✅ Geocoding otomatis via Nominatim API
- ✅ Tulis review dan rating (1-5 bintang)
- ✅ Edit review sendiri (dalam 24 jam)
- ✅ Upload foto (max 3 per tempat, auto-resize 800px)
- ✅ Simpan favorit
- ✅ Tandai tempat sebagai "tutup permanen"

### Pengunjung (Tanpa Login)
- ✅ Browse daftar kuliner dengan filter
- ✅ Lihat detail, foto, peta lokasi (Leaflet)
- ✅ Baca review
- ✅ Cari berdasarkan nama/alamat

### Fitur Sistem
- ✅ Integrasi Nominatim untuk konversi alamat → lat/lng
- ✅ Peta interaktif Leaflet.js per tempat
- ✅ Perhitungan rating otomatis
- ✅ API: `GET /api/kuliner?lat=x&lng=y&radius=km`
- ✅ Pagination dan pencarian

---

## Tech Stack

- **Framework**: CodeIgniter 4 (PHP 8.2+)
- **Database**: MySQL
- **Geocoding API**: OpenStreetMap Nominatim (free, unlimited)
- **Map Display**: Leaflet.js + OpenStreetMap tiles
- **Image Processing**: CodeIgniter 4 Image Library
- **Authentication**: Session-based dengan Role Filter

---

## Cara Instalasi

### Prerequisites
- PHP 8.2+ dengan ekstensi: `intl`, `mysqli`, `pdo_mysql`, `gd`
- MySQL Server
- Composer

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd kuliner-review

# 2. Install dependencies
composer install

# 3. Buat database MySQL
mysql -u root -e "CREATE DATABASE kuliner_review DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_general_ci;"

# 4. Copy .env
cp .env.example .env

# 5. Edit konfigurasi database di .env
# Sesuaikan database.default.username dan password

# 6. Jalankan migration
php spark migrate

# 7. Jalankan seeder (data awal)
php spark db:seed KulinerSeeder

# 8. Jalankan server
php spark serve
```

Aplikasi dapat diakses di: **http://localhost:8080**

---

## Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@udinus.ac.id` | `admin123` |
| Contributor | `budi@student.udinus.ac.id` | `contrib123` |
| Contributor | `siti@student.udinus.ac.id` | `contrib123` |
| Contributor | `rendi@student.udinus.ac.id` | `contrib123` |
| Contributor | `dewi@student.udinus.ac.id` | `contrib123` |

---

## Screenshot Fitur Utama

### 1. Halaman Utama (Beranda)
Menampilkan daftar tempat kuliner dengan pencarian dan filter kategori.

### 2. Detail Tempat
Menampilkan informasi lengkap, foto, peta Leaflet, rating rata-rata, dan review.

### 3. Form Submit Tempat
Fitur geocoding otomatis via Nominatim - user input alamat → klik "Cari Koordinat" → dapat lat/lng otomatis.

### 4. Dashboard Admin
Statistik: total tempat, pending, approved, rejected, user, review, kategori, tag.

### 5. Moderasi Tempat
Admin dapat approve/reject submission dari contributor dengan notifikasi.

---

## Struktur Database

### Relasi Tabel

```
users (id, username, email, password, full_name, role)
    │
    ├── places (user_id FK)
    ├── reviews (user_id FK)
    ├── favorites (user_id FK)
    └── notifications (user_id FK)

categories (id, name, slug, description)
    │
    └── places (category_id FK)

tags (id, name, slug)
    │
    └── place_tags (tag_id FK) ←─── places (place_id FK)

places (id, user_id FK, category_id FK, name, slug, description, 
        address, latitude, longitude, image, status, is_closed)
    │
    ├── reviews (place_id FK)
    ├── favorites (place_id FK)
    └── place_tags (place_id FK)
```

### Migration List
1. `001_CreateUsersTable`
2. `002_CreateCategoriesTable`
3. `003_CreateTagsTable`
4. `004_CreatePlacesTable`
5. `005_CreatePlaceTagsTable`
6. `006_CreateReviewsTable`
7. `007_CreateFavoritesTable`
8. `008_CreateNotificationsTable`
9. `009_AddIsClosedToPlaces`

---

## API Endpoint

### GET /api/kuliner
Mengambil daftar tempat kuliner terdekat berdasarkan koordinat.

**Parameter:**
- `lat` (required): Latitude
- `lng` (required): Longitude
- `radius` (optional): Radius dalam km (default: 10)

**Response:**
```json
{
  "success": true,
  "count": 5,
  "radius_km": 5,
  "data": [
    {
      "id": 1,
      "name": "Angkringan Pendopo",
      "latitude": -6.9840,
      "longitude": 110.4075,
      "distance_km": 0.5,
      ...
    }
  ]
}
```

**Contoh Penggunaan:**
```
GET http://localhost:8080/api/kuliner?lat=-6.9840&lng=110.4075&radius=5
```

---

##Lisensi

MIT License - Universitas Dian Nuswantoro (UDINUS) Semarang