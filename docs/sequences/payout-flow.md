# Payout Sequence Diagrams

Dokumen ini merangkum alur payout pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Halaman Tarik Saldo

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman tarik saldo
	Client->>Backend: GET /getTutorById
	Backend->>Backend: validasi token dan ekstrak user_id
	Backend->>DB: ambil saldo tutor + data akun bank
	DB-->>Backend: data saldo dan akun bank
	Backend-->>Client: 200 success + saldo dan akun bank

	Client->>Backend: GET /tutor/payout/history
	Backend->>DB: ambil riwayat penarikan gaji tutor
	DB-->>Backend: data riwayat payout
	Backend-->>Client: 200 success + riwayat payout
	Client-->>Tutor: tampilkan saldo, akun bank, dan riwayat penarikan

	Tutor->>Client: isi jumlah saldo yang ingin ditarik
	Tutor->>Client: klik tombol tarik saldo
	Client->>Backend: POST /tutor/take-money
	Backend->>Backend: validasi nominal penarikan
	Backend->>DB: simpan permintaan penarikan + update saldo sementara
	DB-->>Backend: payout request berhasil dibuat
	Backend-->>Client: 200 success
	Client-->>Tutor: permintaan tarik saldo berhasil dikirim
```

## Catatan

- Endpoint data tutor untuk saldo dan akun bank menggunakan GET /getTutorById pada [routes/api.php](../../routes/api.php).
- Endpoint riwayat penarikan menggunakan GET /tutor/payout/history pada [routes/api.php](../../routes/api.php).
- Endpoint tarik saldo menggunakan POST /tutor/take-money pada [routes/api.php](../../routes/api.php).
