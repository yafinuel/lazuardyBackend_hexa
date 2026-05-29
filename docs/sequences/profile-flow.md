# Profile Sequence Diagrams

Dokumen ini merangkum alur edit profile pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, dan database.

## 1. Edit Profile Student

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman edit profile
	Client->>Backend: GET /student/biodata (Bearer token)
	Backend->>DB: ambil biodata student aktif
	DB-->>Backend: data biodata student
	Backend-->>Client: data biodata student
	Client-->>User: tampilkan biodata untuk diedit

	User->>Client: ubah biodata lalu simpan
	Client->>Backend: PUT /updateStudentBiodata
	Backend->>Backend: validasi data update
	Backend->>DB: simpan perubahan biodata student
	DB-->>Backend: update berhasil
	Backend-->>Client: 200 success
	Client-->>User: biodata student berhasil diperbarui

	alt user ingin update profile photo
		User->>Client: pilih foto profil baru
		Client->>Backend: PATCH /updateProfilePhoto (multipart)
		Backend->>Backend: validasi file foto
		Backend->>DB: simpan perubahan foto profil
		DB-->>Backend: update berhasil
		Backend-->>Client: 200 success
		Client-->>User: foto profil student berhasil diperbarui
	end
```

## 2. Edit Profile Tutor

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: buka halaman edit profile
	Client->>Backend: GET /tutor/biodata (Bearer token)
	Backend->>DB: ambil biodata tutor aktif
	DB-->>Backend: data biodata tutor
	Backend-->>Client: data biodata tutor
	Client-->>User: tampilkan biodata untuk diedit

	User->>Client: ubah biodata lalu simpan
	Client->>Backend: PUT /tutor/profile
	Backend->>Backend: validasi data update
	Backend->>DB: simpan perubahan biodata tutor
	DB-->>Backend: update berhasil
	Backend-->>Client: 200 success
	Client-->>User: biodata tutor berhasil diperbarui

	alt user ingin update profile photo
		User->>Client: pilih foto profil baru
		Client->>Backend: PATCH /updateProfilePhoto (multipart)
		Backend->>Backend: validasi file foto
		Backend->>DB: simpan perubahan foto profil
		DB-->>Backend: update berhasil
		Backend-->>Client: 200 success
		Client-->>User: foto profil tutor berhasil diperbarui
	end
```

## Catatan

- Flow student dan tutor sama pada level tinggi, tetapi dipisahkan karena endpoint update dan pembacaan biodata berbeda.
- Endpoint update profile photo berada di grup `auth:sanctum` dan dipakai bersama oleh student maupun tutor.
- Endpoint profile yang dilindungi Sanctum berada di grup `auth:sanctum` pada [routes/api.php](../../routes/api.php).
