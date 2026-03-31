# API Documentation Guide

Panduan ini menjelaskan cara mengelola dokumentasi OpenAPI yang sudah dimodularisasi.

## Struktur File

- `openapi.yaml`: Entry point utama (source of truth) yang mereferensikan file lain.
- `openapi.bundle.yaml`: File hasil build (flattened) untuk tools/viewer yang tidak mendukung external `$ref`.
- `paths/*.yaml`: Definisi endpoint per domain.
- `components/schemas.yaml`: Schema reusable.
- `components/request-bodies.yaml`: Request body reusable.
- `components/responses.yaml`: Response reusable.

## Workflow Harian

1. Edit dokumentasi di file source modular:
   - `openapi.yaml`
   - `paths/*.yaml`
   - `components/*.yaml`
2. Validasi spesifikasi:

```bash
npm run docs:api:lint
```

3. Generate bundle untuk konsumsi Swagger/UI lain:

```bash
npm run docs:api:bundle
```

4. Gunakan `openapi.bundle.yaml` saat viewer tidak bisa resolve external reference.

## Jangan Edit File Ini Manual

- `openapi.bundle.yaml` **tidak boleh diedit manual**.
- Selalu edit file source, lalu generate ulang bundle.

## Cara Menambah Endpoint Baru

1. Tambahkan route di `paths/<domain>.yaml`.
2. Daftarkan endpoint di `openapi.yaml` pada section `paths`.
3. Tambahkan schema/request/response reusable di `components/*` bila diperlukan.
4. Jalankan lint dan bundle.

Contoh pendaftaran path di `openapi.yaml`:

```yaml
paths:
  /getPackages:
    $ref: './paths/package.yaml#/getPackages'
```

## Troubleshooting

### Error resolver: "Could not resolve pointer"

Penyebab umum:
- Viewer tidak mendukung external file reference.
- Pointer `$ref` tidak cocok dengan key di file target.

Solusi:
1. Pastikan pointer cocok dengan key target.
2. Jalankan lint untuk melihat lokasi error:

```bash
npm run docs:api:lint
```

3. Gunakan file bundle:
   - `docs/api/openapi.bundle.yaml`

## Checklist Sebelum Commit

- [ ] Perubahan dilakukan di file source (bukan bundle).
- [ ] `npm run docs:api:lint` sukses.
- [ ] `npm run docs:api:bundle` sudah dijalankan.
- [ ] Perubahan endpoint dan komponen konsisten.
