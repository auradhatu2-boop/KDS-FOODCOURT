# Migration - Sistem KDS Food Court Multi-Tenant

Asumsi: stack Laravel (mengikuti gaya penamaan & konvensi yang sama dengan
referensi SISANTRI). Kalau kelompok kalian pakai framework lain, struktur
tabel dan urutannya tetap bisa dipakai sebagai acuan, tinggal disesuaikan
sintaksnya.

## Cara pakai
1. Salin seluruh file `2026_09_09_xxxxxx_*.php` ke folder `database/migrations/`
   pada project Laravel kalian.
2. Jalankan:
   ```
   php artisan migrate
   ```
3. Urutan file (angka di depan nama file) sudah disusun sesuai dependensi
   foreign key, jadi jangan diacak urutannya:
   1. tenants
   2. tenant_komisi_riwayat (riwayat komisi, append-only - NFR-DAT-03)
   3. meja
   4. menu_items
   5. pesanan_induk
   6. pembayaran (relasi 1:1 ke pesanan_induk)
   7. sub_pesanan (pecahan pesanan_induk per tenant - FR-PAY-01)
   8. item_pesanan
   9. pencairan_dana
   10. ledger_recall (jejak audit recall - FR-KDS-02)

## Catatan desain
- Semua tabel yang datanya harus terpisah antar tenant (menu_items,
  sub_pesanan, pencairan_dana) punya kolom `tenant_id` dan sudah diberi index
  sesuai NFR-DAT-01.
- `tenant_komisi_riwayat` dan `ledger_recall` sengaja dibuat append-only
  (tanpa `updated_at` di kolom utamanya) supaya koreksi selalu berupa entri
  baru, bukan overwrite - sesuai NFR-DAT-03.
- `sub_pesanan` menyimpan empat timestamp tahap (`waktu_diterima`,
  `waktu_dimasak`, `waktu_siap`, `waktu_diambil`) supaya FR-KDS-03
  (kalibrasi estimasi waktu masak) tinggal menghitung selisihnya, tanpa
  perlu tabel log terpisah.
- `item_pesanan.harga_satuan` adalah snapshot harga saat pesan, bukan
  referensi live ke `menu_items.harga`, supaya perubahan harga menu di
  kemudian hari tidak mengubah nilai transaksi lama.
- Proses split payment (insert ke `sub_pesanan`) dan perubahan
  `status_dapur` wajib dibungkus `DB::transaction()` + `lockForUpdate()`
  di level kode aplikasi sesuai NFR-DAT-02 - migration ini hanya
  menyiapkan skemanya, bukan logikanya.
