
# AISight — Rebuild (Comprehensive)

Tanggal build: 2025-09-01

## Cara Setup Cepat
1. Upload seluruh folder ke host (Hostinger / Netlify / Vercel / GitHub Pages).
2. **Konfigurasi** di `assets/js/config.js`:
   - `CONTACT_EMAIL`: ganti dengan email domain Hostinger kamu (mis. info@namadomain.com).
   - `WHATSAPP_NUMBER`: nomor internasional tanpa 0 (mis. 62812XXXXXXX).
   - `FORMSPREE_ENDPOINT`: endpoint dari Formspree (contoh: https://formspree.io/f/abcde123).

3. **Form Contact (Formspree)**
   - Buat form di https://formspree.io/ → ambil form id → isi ke `FORMSPREE_ENDPOINT`.
   - Form akan mengirim via `fetch` dan menampilkan status sukses/gagal, tanpa membuka email client.

4. **Sticky CTA (WA)**
   - Link WA di sticky CTA dibuat dari `WHATSAPP_NUMBER` otomatis saat halaman dimuat.

5. **Ganti Logo Partner**
   - Simpan file di `assets/img/` (PNG/SVG).
   - Edit bagian "Dipercaya oleh" di `index.html` → ganti src `<img>` ke file logo kamu.
   - Contoh: `<img src="assets/img/partner-baru.svg" alt="Nama Partner" width="160" height="48" />`.

6. **SEO**
   - Dasar SEO & Open Graph sudah ditambahkan (title/description).
   - Ubah `robots.txt` dan `sitemap.xml` ke domain kamu (ganti `www.example.com`).

7. **Live Preview Lokal**
   - VS Code → install Live Server → klik kanan `index.html` → Open with Live Server.

## Struktur
- `index.html` — Landing + testimonials, partner logos, highlight "Kurikulum Relevan Industri"
- `overview.html` — Daftar modul (Excel/SQL/Power BI/Python/Portfolio)
- `simulation.html` — Kalkulator paket (aturan perhitungan sesuai versi lama)
- `contact.html` — Email & WhatsApp + form Formspree
- `assets/css/style.css` — Tema & layout
- `assets/js/config.js` — Konfigurasi global
- `assets/js/palette.js` — Toggle light/dark + palet Teal/Violet/Orange
- `assets/js/motion.js` — Reveal animation
- `assets/js/main.js` — Logika kalkulator
- `assets/img/partner-*.svg` — Logo partner placeholder
- `robots.txt`, `sitemap.xml` — SEO opsional

## Catatan
- Kamu bisa mengubah aksen warna lewat palet di navbar (persist di localStorage).
- Untuk domain Hostinger, pastikan A/MX/WWW records sudah benar; upload file ke public_html atau deploy via panel.
