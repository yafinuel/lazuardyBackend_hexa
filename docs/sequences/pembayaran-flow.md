# Pembayaran Sequence Diagrams

Dokumen ini merangkum alur riwayat pembayaran pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Halaman Daftar Riwayat Pembayaran

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman riwayat pembayaran
	Client->>Backend: GET /payment/history
	Backend->>DB: ambil daftar riwayat pembayaran milik siswa aktif
	DB-->>Backend: data riwayat pembayaran
	Backend-->>Client: data riwayat pembayaran
	Client-->>User: tampilkan daftar riwayat pembayaran yang telah dilakukan
```

## Catatan

- Halaman riwayat pembayaran dapat diakses oleh role siswa.
- Halaman daftar riwayat pembayaran menggunakan endpoint [GET /payment/history](../../routes/api.php).
