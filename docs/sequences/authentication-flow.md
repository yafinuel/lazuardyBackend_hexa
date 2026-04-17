# Authentication Sequence Diagrams

Dokumen ini merangkum alur autentikasi pada level tinggi agar mudah dipahami. Diagram disederhanakan menjadi interaksi utama antara client, backend, database, dan API eksternal.

## 1. Manual Login

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database

	User->>Client: isi email + kata sandi
	Client->>Backend: POST /login
	Backend->>DB: cari user by email
	DB-->>Backend: data user / tidak ada

	alt user tidak ditemukan
		Backend-->>Client: 422 user tidak ditemukan
	else user ditemukan
		Backend->>Backend: verifikasi password
		alt password salah
			Backend-->>Client: 422 password salah
		else password benar
			Backend->>DB: buat access token
			DB-->>Backend: token
			Backend-->>Client: 200 success + Bearer token
			Client-->>User: login sukses
		end
	end
```

## 2. Register Siswa

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant EmailApi as Email API
	participant DB as Database

	User->>Client: isi email + password + confirm password
	Client->>Backend: POST /registerOtpEmail
	Backend->>DB: simpan OTP register
	Backend->>EmailApi: kirim OTP ke email
	EmailApi-->>Backend: success
	Backend-->>Client: 201 OTP terkirim

	User->>Client: isi kode OTP
	Client->>Backend: POST /verifyOtpRegisterEmail
	Backend->>DB: verifikasi OTP register
	Backend-->>Client: 200 OTP valid

	User->>Client: lengkapi biodata siswa
	Client->>Backend: POST /studentRegister
	Backend->>DB: simpan user + biodata siswa
	DB-->>Backend: user berhasil dibuat
	Backend->>DB: buat access token
	DB-->>Backend: token
	Backend-->>Client: 200 success + Bearer token
	Client-->>User: registrasi siswa berhasil
```

## 3. Register Tutor

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant StorageApi as File Storage API
	participant EmailApi as Email API
	participant DB as Database

	User->>Client: isi email + password + confirm password
	Client->>Backend: POST /registerOtpEmail
	Backend->>DB: simpan OTP register
	Backend->>EmailApi: kirim OTP ke email
	EmailApi-->>Backend: success
	Backend-->>Client: 201 OTP terkirim

	User->>Client: isi kode OTP
	Client->>Backend: POST /verifyOtpRegisterEmail
	Backend->>DB: verifikasi OTP register
	Backend-->>Client: 200 OTP valid

	User->>Client: lengkapi biodata tutor + unggah berkas
	Client->>Backend: POST /tutorRegister (multipart)
	Backend->>StorageApi: upload file pendukung
	StorageApi-->>Backend: file path
	Backend->>DB: simpan user + biodata tutor + file refs
	DB-->>Backend: user berhasil dibuat
	Backend->>DB: buat access token
	DB-->>Backend: token
	Backend-->>Client: 200 success + Bearer token
	Client-->>User: registrasi tutor berhasil
```

## 4. Forgot Password

```mermaid
sequenceDiagram
	autonumber
	actor User as Pengguna
	participant Client as Frontend / Mobile App
	participant Backend as Backend API
	participant DB as Database
	participant Cache as Cache
	participant EmailApi as Email API

	User->>Client: input email
	Client->>Backend: POST /forgotPasswordOtpEmail
	Backend->>Backend: generate OTP
	Backend->>DB: simpan OTP forgot password
	Backend->>EmailApi: kirim OTP
	EmailApi-->>Backend: success
	Backend-->>Client: 201 success

	User->>Client: input kode OTP
	Client->>Backend: POST /forgotPasswordVerifyOtpEmail
	Backend->>DB: verifikasi OTP
	alt OTP salah / tidak ditemukan
		Backend-->>Client: 403 / 404 error
	else OTP benar
		Backend->>DB: tandai OTP sudah dipakai
		Backend->>Cache: simpan reset token sementara
		Backend-->>Client: 200 success + reset_token
	end

	User->>Client: input password baru + confirm password
	Client->>Backend: POST /forgotPasswordResetPassword
	Backend->>Backend: validasi password dan confirm password
	Backend->>Cache: cek reset_token
	alt token tidak valid
		Backend-->>Client: 403 error
	else token valid
		Backend->>DB: update password user
		Backend->>Cache: hapus reset token
		Backend-->>Client: 200 success
		Client-->>User: kata sandi berhasil diperbarui
	end
```

## 5. OAuth Social Login

```mermaid
sequenceDiagram
	autonumber
	actor Client as Browser / Mobile App
	participant Backend as Backend API
	participant OAuthApi as OAuth Provider API
	participant DB as Database

	Client->>Backend: GET /auth/{provider}/redirect
	Backend->>OAuthApi: minta authorization URL
	OAuthApi-->>Backend: redirect URL
	Backend-->>Client: JSON url

	Client->>Backend: GET /auth/{provider}/callback
	Backend->>OAuthApi: tukar callback ke profil user
	OAuthApi-->>Backend: social profile
	Backend->>DB: cari user by email

	alt user sudah ada
		Backend->>DB: update social id jika perlu
		Backend->>DB: buat access token
		Backend-->>Client: 200 success + Bearer token
	else user belum ada
		Backend-->>Client: 200 data profil awal untuk register lanjutan
	end
```

## 6. Ambil User Aktif dan Logout

```mermaid
sequenceDiagram
	autonumber
	actor Client as Mobile App / Client
	participant Backend as Backend API
	participant DB as Database

	Client->>Backend: GET /user (Bearer token)
	Backend->>DB: validasi token + ambil user aktif
	DB-->>Backend: data user
	Backend-->>Client: data user aktif

	Client->>Backend: POST /logout (Bearer token)
	Backend->>DB: hapus/revoke current token
	DB-->>Backend: token deleted
	Backend-->>Client: 200 success
```

## Catatan

- Endpoint yang dilindungi Sanctum berada di grup `auth:sanctum` pada [routes/api.php](../../routes/api.php).
- Flow OTP di level tinggi ditampilkan sebagai interaksi Backend, Database, Cache, dan Email API.
- Flow OAuth di level tinggi ditampilkan sebagai interaksi Backend dengan OAuth Provider API dan Database.
