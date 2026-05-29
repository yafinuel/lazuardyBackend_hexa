# Pembelian Paket Sequence Diagrams

Dokumen ini merangkum alur halaman paket pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Halaman List Paket

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman list paket
	Client->>Backend: GET /getPackages
	Backend->>DB: ambil daftar paket
	DB-->>Backend: data list paket
	Backend-->>Client: data list paket
	Client-->>User: tampilkan list paket
```

## 2. Halaman Detail Paket

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: pilih salah satu paket
	Client->>Backend: GET /package/{id}
	Backend->>DB: ambil detail paket berdasarkan id
	DB-->>Backend: data detail paket
	Backend-->>Client: data detail paket
	Client-->>User: tampilkan halaman detail paket

	User->>Client: klik beli paket
	Client->>Backend: POST /package/order
	Backend->>Backend: validasi data pesanan
	Backend->>DB: simpan pesanan paket
	DB-->>Backend: pesanan berhasil dibuat
	Backend-->>Client: 200 success
	Client-->>User: pesanan paket berhasil dibuat
```

## Catatan

- Halaman list paket menggunakan endpoint [GET /getPackages](../../routes/api.php) yang ada di grup `auth:sanctum`.
- Halaman detail paket menggunakan endpoint [GET /package/{id}](../../routes/api.php) yang ada di grup `auth:sanctum`.
- Pembuatan pesanan paket menggunakan endpoint [POST /package/order](../../routes/api.php) yang ada di grup `auth:sanctum`.
