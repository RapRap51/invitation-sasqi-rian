# Catatan Redesign Undangan Rafi & Ina

## Yang diubah
- `resources/views/invitation.blade.php`: halaman undangan publik disusun ulang menjadi pengalaman single-page dengan opening envelope, hero, kutipan, profil mempelai, countdown, acara, galeri, RSVP, QR check-in, ucapan, dan penutup.
- `public/css/wedding-invitation.css`: stylesheet khusus halaman undangan dengan konsep Romantic Fairytale Garden, responsif desktop/mobile.
- `public/js/wedding-invitation.js`: interaksi opening, musik, countdown, scroll reveal, toggle jumlah RSVP, lightbox galeri, dan load-more ucapan.

## Yang sengaja tidak diubah
- Routes Laravel.
- Controller dan model.
- Migrasi dan database.
- Halaman admin, autentikasi, manajemen tamu, QR, import/export, dan check-in.
- `public/css/wedding.css` yang masih dipakai layout admin.
- `public/js/wedding.js` (tidak lagi dipakai halaman undangan baru).

## Data yang tetap mengikuti project
- Nama: Rafi Andi Hidayah & Aprinita Dwisna Hapsari.
- Tanggal: 4 April 2027.
- Waktu: 09.00 WIB.
- Lokasi: Masjid Al-Akbar Surabaya.
- Foto: empat foto existing di `public/img/`.
- Musik: `public/music.mp3`.

## Catatan pengujian
- Semua file PHP yang ada lulus `php -l`.
- JavaScript baru lulus `node --check`.
- Kurung CSS seimbang.
- Blade belum dapat diuji melalui Laravel runtime karena folder `vendor/` tidak disertakan dalam ZIP sumber. Jalankan `composer install` di lingkungan project sebelum pengujian browser.

## Menjalankan
1. Extract ZIP.
2. Siapkan `.env` dan konfigurasi database sesuai environment.
3. Jalankan `composer install` jika dependency belum terpasang.
4. Jalankan migrasi hanya jika database target memang belum berisi skema/data yang dibutuhkan.
5. Jalankan `php artisan serve`.
6. Buka URL undangan `/undangan/{kode-tamu}`.
