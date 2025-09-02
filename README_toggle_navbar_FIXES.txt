Perbaikan 2025-09-01
====================
1) Toggle tema tidak berfungsi → diperbaiki di assets/js/palette.js
   - Event binding pakai delegated listener (aman untuk semua halaman).
   - State disimpan di localStorage ("theme" & "palette").
   - Ikon toggle dan swatch langsung aktif di semua page.

2) Navbar di samping toggle
   - CSS di-update supaya urutan: [Brand] | [Menu] | [Toggle + Swatches].
   - Lihat patch di akhir assets/css/style.css (blok "Navbar layout patch").

3) Berlaku di semua halaman HTML
   - Karena semua halaman sudah menyertakan <nav class="navbar"> dan memuat palette.js,
     maka toggle & palet jalan konsisten di index.html, overview.html, simulation.html, contact.html.

Catatan:
- Jika ingin benar-benar *satu sumber* untuk navbar (tanpa copas), buat file assets/js/header.js yang menyuntikkan HTML navbar,
  lalu di setiap page taruh <header id="site-header"></header> dan muat header.js. (Bisa saya siapkan bila diperlukan.)