# Panduan Instalasi

1. Extract zip kemudian masukkan local server anda.

2. Buka terminal pada folder root local server dan masuk ke direktori proyek:

    ```bash
    cd aproval_sistem
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Copy file `.env` dan sesuaikan konfigurasi database:

    ```bash
    cp .env.example .env
    ```

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Sesuaikan DB_Connection dengan milik local server anda.
7. Jalankan server lokal:
    ```bash
    php artisan serve
    ```

## Penggunaan API

### Tambah Pengeluaran

-   Endpoint: `POST /api/expenses`
-   Deskripsi: Menambahkan pengeluaran baru
-   Body Request:
    ```json
    {
        "amount": 1000,
        "status_id": 1
    }
    ```
-   Respons Sukses: 201 Created
    ```json
    {
        "id": 1,
        "amount": 1000,
        "status": {
            "id": 1,
            "name": "menunggu persetujuan"
        }
    }
    ```

### Setujui Pengeluaran

-   Endpoint: `PATCH /api/expenses/{id}/approve`
-   Deskripsi: Menyetujui pengeluaran berdasarkan ID
-   Body Request:
    ```json
    {
        "approver_id": 2,
        "status_id": 2
    }
    ```
-   Respons Sukses: 200 OK
    ```json
    {
        "id": 1,
        "amount": 1000,
        "status": {
            "id": 2,
            "name": "disetujui"
        },
        "approvals": [
            {
                "approver": {
                    "id": 2,
                    "name": "Ani"
                },
                "status": {
                    "id": 2,
                    "name": "disetujui"
                }
            }
        ]
    }
    ```

### Lihat Pengeluaran

-   Endpoint: `GET /api/expenses/{id}`
-   Deskripsi: Mengambil detail pengeluaran berdasarkan ID
-   Respons Sukses: 200 OK
    ```json
    {
        "id": 1,
        "amount": 1000,
        "status": {
            "id": 2,
            "name": "disetujui"
        },
        "approvals": [
            {
                "approver": {
                    "id": 1,
                    "name": "Ana"
                },
                "status": {
                    "id": 2,
                    "name": "disetujui"
                }
            }
        ]
    }
    ```

## Testing

1. Jalankan perintah berikut untuk menjalankan test menggunakan PHPUnit:

    ```bash
    php artisan test
    ```

2. Uji respons pengeluaran yang disetujui oleh beberapa approver:
    ```php
    $this->patchJson('/api/expenses/1/approve', [
        'approver_id' => 1,
        'status_id' => 2
    ])->assertStatus(200);
    ```

## Dokumentasi API (Swagger UI)

Swagger UI telah diintegrasikan untuk mendokumentasikan dan menguji API. Untuk mengakses Swagger UI, jalankan server lokal dan buka [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).

## Struktur Database

### Tabel Expense

-   `id`: Primary key
-   `amount`: Jumlah pengeluaran
-   `status_id`: Foreign key untuk status

### Tabel Approvals

-   `id`: Primary key
-   `expense_id`: Foreign key ke tabel expenses
-   `approver_id`: Foreign key ke tabel approvers
-   `status_id`: Foreign key ke tabel status

### Tabel Approvers

-   `id`: Primary key
-   `name`: Nama approver

### Tabel Status

-   `id`: Primary key
-   `name`: Nama status (e.g., `menunggu persetujuan`, `disetujui`)

## Terimakasih
