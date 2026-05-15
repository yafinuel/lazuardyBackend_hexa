# Dokumentasi Alur Kerja Notifikasi

Dokumen ini menjelaskan alur notifikasi yang saat ini diimplementasikan pada domain notifikasi, khususnya untuk kasus `OrderPaidExample`.

## Ringkasan Arsitektur

Implementasi notifikasi menggunakan kombinasi:

- Laravel Notification (`Illuminate\Notifications\Notification`)
- Channel bawaan Laravel: `database`
- Custom channel: `FcmChannel`
- Gateway abstraction: `NotificationGatewayInterface`
- Adapter eksternal: `FcmAdapter` (Firebase Cloud Messaging)

## Komponen Utama

### 1. Notification Class

File: `app/Domains/Notification/Notifications/OrderPaidExample.php`

Tanggung jawab:

- Menentukan channel notifikasi melalui `via()`
- Menentukan payload untuk database melalui `toDatabase()`
- Menentukan payload push FCM melalui `toFcm()`

Channel yang dipakai:

- `database`
- `App\Domains\Notification\Infrastructure\External\Firebase\FcmChannel`

### 2. Custom Channel FCM

File: `app/Domains/Notification/Infrastructure/External/Firebase/FcmChannel.php`

Tanggung jawab:

- Menerima objek notifiable dan notification dari sistem Laravel
- Mengambil payload dari `toFcm()`
- Mengambil token dari relasi `notifiable->fcmTokens`
- Fallback ke `notifiable->fcm_token` jika token per device belum tersedia
- Memanggil gateway untuk tiap token yang tersedia

Catatan:

- Jika tidak ada token yang tersimpan, push tidak dikirim.

### 3. Gateway Interface

File: `app/Domains/Notification/Ports/NotificationGatewayInterface.php`

Tanggung jawab:

- Menjadi kontrak untuk pengiriman push notification
- Method utama: `sendPush(string $token, string $title, string $body, array $data = []): bool`

### 4. Firebase Adapter

File: `app/Domains/Notification/Infrastructure/External/Firebase/FcmAdapter.php`

Tanggung jawab:

- Implementasi nyata dari `NotificationGatewayInterface`
- Mengambil access token Google service account
- Mengirim request HTTP ke endpoint FCM v1
- Logging error saat pengiriman gagal

Dependency konfigurasi:

- `config('services.fcm.project_id')`
- File credential service account pada `storage/app/firebase-auth.json`

### 5. Service Container Binding

File: `app/Providers/AppServiceProvider.php`

Binding saat ini:

- `NotificationGatewayInterface` -> `FcmAdapter`

Dampak:

- Semua channel/action yang bergantung pada interface tersebut akan menggunakan adapter FCM nyata di runtime.

## Alur Eksekusi End-to-End

1. Kode aplikasi memanggil notifikasi pada user, misalnya `user->notify(new OrderPaidExample($details))`.
2. Laravel membaca channel dari method `via()`.
3. Channel `database` menyimpan data dari `toDatabase()` ke tabel `notifications`.
4. Channel custom `FcmChannel` dipanggil.
5. `FcmChannel` memanggil `toFcm()` untuk membentuk message push.
6. `FcmChannel` membaca `fcm_token` dari user.
7. Jika token ada, `FcmChannel` memanggil `NotificationGatewayInterface::sendPush(...)` untuk tiap token.
8. Karena binding interface, panggilan diteruskan ke `FcmAdapter`.
9. `FcmAdapter` mengambil access token, membangun payload FCM, lalu melakukan request ke API FCM.
10. Jika request gagal/exception, error dicatat ke log.

## Struktur Payload

### Payload Database (`toDatabase`)

Default saat detail tidak lengkap:

- `title`: `Order Paid`
- `body`: `Your order has been paid successfully.`
- `data`: `[]`

### Payload FCM (`toFcm`)

Format yang dikirim dari notifikasi:

- `title`: `Pembayaran Berhasil`
- `body`: `Klik untuk lihat detail paket kamu.`
- `data.type`: `payment_success`
- `data.id`: string dari `order_id`

## Tabel yang Terkait

### notifications

Migration: `database/migrations/2026_04_04_192125_create_notifications_table.php`

Kolom penting:

- `id` (uuid)
- `type` (class notifikasi)
- `notifiable_type`
- `notifiable_id`
- `data` (payload notifikasi)
- `read_at`

## Testing Saat Ini

File test: `tests/Feature/Notifications/OrderPaidExampleTest.php`

Skenario yang sudah diuji:

- Notifikasi tersimpan ke database
- Default payload database ketika detail kosong
- Format payload FCM
- Channel yang dipakai (`database` dan `FcmChannel`)

Catatan testing:

- Untuk mencegah dependency Firebase saat test, binding `NotificationGatewayInterface` dioverride dengan fake implementation.

## Catatan Operasional

- Pastikan nilai `services.firebase.project_id` tersedia pada environment.
- Pastikan file `storage/app/firebase-auth.json` tersedia dan valid.
- User harus memiliki token di tabel `user_fcm_tokens` agar push benar-benar terkirim.
- Kegagalan kirim push tidak mencegah penyimpanan notifikasi ke database karena berjalan pada channel terpisah.

## Contoh Flutter (Registrasi Token)

Frontend perlu mengirim token FCM ke backend setelah login, saat app dibuka ulang, dan saat token berubah.

Contoh sederhana (gunakan `firebase_messaging` dan `http`):

```dart
import 'dart:convert';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:http/http.dart' as http;

Future<void> syncFcmToken({
	required String apiBaseUrl,
	required String authToken,
	required String deviceId,
	required String platform,
}) async {
	final token = await FirebaseMessaging.instance.getToken();
	if (token == null) return;

	await http.patch(
		Uri.parse('$apiBaseUrl/user/fcm-token'),
		headers: {
			'Authorization': 'Bearer $authToken',
			'Content-Type': 'application/json',
		},
		body: jsonEncode({
			'fcm_token': token,
			'device_id': deviceId,
			'platform': platform,
		}),
	);
}

void registerFcmTokenRefresh({
	required String apiBaseUrl,
	required String authToken,
	required String deviceId,
	required String platform,
}) {
	FirebaseMessaging.instance.onTokenRefresh.listen((newToken) async {
		await http.patch(
			Uri.parse('$apiBaseUrl/user/fcm-token'),
			headers: {
				'Authorization': 'Bearer $authToken',
				'Content-Type': 'application/json',
			},
			body: jsonEncode({
				'fcm_token': newToken,
				'device_id': deviceId,
				'platform': platform,
			}),
		);
	});
}

Future<void> clearFcmToken({
	required String apiBaseUrl,
	required String authToken,
	required String deviceId,
}) async {
	await http.delete(
		Uri.parse('$apiBaseUrl/user/fcm-token'),
		headers: {
			'Authorization': 'Bearer $authToken',
			'Content-Type': 'application/json',
		},
		body: jsonEncode({
			'device_id': deviceId,
		}),
	);
}
```

Catatan:

- `deviceId` sebaiknya stabil per perangkat (contoh: hasil dari `device_info_plus` + penyimpanan lokal).
- Panggil `clearFcmToken` saat logout untuk device tersebut.

## Referensi File

- `app/Domains/Notification/Notifications/OrderPaidExample.php`
- `app/Domains/Notification/Infrastructure/External/Firebase/FcmChannel.php`
- `app/Domains/Notification/Infrastructure/External/Firebase/FcmAdapter.php`
- `app/Domains/Notification/Ports/NotificationGatewayInterface.php`
- `app/Providers/AppServiceProvider.php`
- `database/migrations/2026_04_04_192125_create_notifications_table.php`
- `tests/Feature/Notifications/OrderPaidExampleTest.php`
