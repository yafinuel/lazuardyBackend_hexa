# Review Sequence Diagrams

Dokumen ini merangkum alur ulasan pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Halaman Daftar Ulasan Milik Siswa

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman daftar ulasan
	Client->>Backend: GET /student/review
	Backend->>DB: ambil daftar review milik siswa aktif
	DB-->>Backend: data review
	Backend-->>Client: data review
	Client-->>User: tampilkan daftar ulasan yang pernah dilakukan
```

## 2. Halaman Ulasan dari Siswa

```mermaid
sequenceDiagram
	autonumber
	actor Tutor as Tutor
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	Tutor->>Client: buka halaman ulasan dari siswa
	Client->>Backend: GET /tutor/review
	Backend->>DB: ambil daftar review untuk tutor aktif
	DB-->>Backend: data review tutor
	Backend-->>Client: data review tutor
	Client-->>Tutor: tampilkan daftar ulasan dari siswa
```

## Catatan

- Halaman daftar ulasan menggunakan endpoint [GET /student/review](../../routes/api.php).
- Halaman ulasan dari siswa menggunakan endpoint [GET /tutor/review](../../routes/api.php).
