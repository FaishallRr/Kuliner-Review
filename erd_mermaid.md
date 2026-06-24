# Mermaid ERD — KulinerReview

Copy dan paste kode di bawah ke tool/editor yang mendukung Mermaid diagram (GitHub Markdown, Mermaid Live Editor, Notion, dsb.).

---

## 3.1 ERD Lengkap

```mermaid
erDiagram
    users {
        int id PK
        varchar username UK
        varchar email UK
        varchar password
        varchar full_name
        enum role
        varchar avatar
        datetime created_at
        datetime updated_at
    }

    categories {
        int id PK
        varchar name UK
        varchar slug UK
        text description
        datetime created_at
        datetime updated_at
    }

    tags {
        int id PK
        varchar name UK
        varchar slug UK
        datetime created_at
        datetime updated_at
    }

    places {
        int id PK
        int user_id FK
        int category_id FK
        varchar name
        varchar slug UK
        text description
        varchar address
        decimal latitude
        decimal longitude
        varchar image
        enum status
        text rejection_note
        tinyint is_closed
        datetime created_at
        datetime updated_at
    }

    place_tags {
        int place_id PK
        int tag_id PK
    }

    reviews {
        int id PK
        int user_id FK
        int place_id FK
        int rating
        text comment
        datetime created_at
        datetime updated_at
    }

    favorites {
        int id PK
        int user_id FK
        int place_id FK
        datetime created_at
    }

    notifications {
        int id PK
        int user_id FK
        varchar title
        text message
        tinyint is_read
        datetime created_at
    }

    users ||--o{ places : "membuat"
    categories ||--o{ places : "mengklasifikasi"
    places }o--o{ tags : "memiliki (via place_tags)"
    users ||--o{ reviews : "menulis"
    places ||--o{ reviews : "menerima"
    users ||--o{ favorites : "menyimpan"
    places ||--o{ favorites : "difavoritkan"
    users ||--o{ notifications : "menerima"
```

---

## 3.2 Ringkasan Kardinalitas

```mermaid
erDiagram
    users ||--o{ places : "1 : N — Satu user membuat banyak tempat"
    categories ||--o{ places : "1 : N — Satu kategori mengklasifikasi banyak tempat"
    places }o--o{ tags : "M : N — Banyak tempat memiliki banyak tag (via pivot)"
    users ||--o{ reviews : "1 : N — Satu user menulis banyak review"
    places ||--o{ reviews : "1 : N — Satu tempat menerima banyak review"
    users ||--o{ favorites : "1 : N — Satu user menyimpan banyak favorit"
    places ||--o{ favorites : "1 : N — Satu tempat difavoritkan banyak user"
    users ||--o{ notifications : "1 : N — Satu user menerima banyak notifikasi"
```

---

## 3.3 Detail Relasi

### One-to-Many (1:N)

```mermaid
erDiagram
    users ||--o{ places : "membuat"
    categories ||--o{ places : "mengklasifikasi"
    users ||--o{ reviews : "menulis"
    places ||--o{ reviews : "menerima"
    users ||--o{ favorites : "menyimpan"
    places ||--o{ favorites : "difavoritkan"
    users ||--o{ notifications : "menerima"
```

### Many-to-Many (M:N) — dengan Pivot Table

```mermaid
erDiagram
    places ||--o{ place_tags : "memiliki"
    tags ||--o{ place_tags : "dimiliki"
    place_tags {
        int place_id PK
        int tag_id PK
    }
```

### One-to-One — Kontekstual (via UNIQUE Constraint)

```mermaid
erDiagram
    users ||--o| reviews : "1 review per place (UNIQUE)"
    places ||--o| reviews : "1 review per user (UNIQUE)"
    users ||--o| favorites : "1 favorit per place (UNIQUE)"
    places ||--o| favorites : "1 favorit per user (UNIQUE)"
```

---

## 3.4 Penjelasan Notasi Mermaid

| Simbol Mermaid | Makna | Contoh |
|:---:|---|:---:|
| `||` | **Satu** (One) — tepat satu record | Satu user |
| `o{` atau `}|` | **Banyak** (Many) — nol atau lebih | Banyak tempat |
| `||--o{` | **One-to-Many (1:N)** | `users ||--o{ places` |
| `}o--o{` | **Many-to-Many (M:N)** | `places }o--o{ tags` |
| `||--o|` | **One-to-One (1:1)** | `users ||--o| reviews` (per place) |
| `PK` | Primary Key | `int id PK` |
| `FK` | Foreign Key | `int user_id FK` |
| `UK` | Unique Key | `varchar email UK` |

---

> **Catatan:** Relasi Many-to-Many di Mermaid tidak bisa langsung ditulis `places }o--o{ tags` tanpa tabel pivot. Implementasi fisiknya menggunakan tabel `place_tags` sebagai junction table.
