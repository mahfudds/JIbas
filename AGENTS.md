# AGENTS.md — JIBAS SISFO (Sistem Informasi Sekolah)

Sistem informasi sekolah JIBAS. **Ini aplikasi PHP vanilla lawas — TIDAK ada Composer,
framework, npm, test, build, atau linter.** Jangan coba `composer install` / `npm install`
/ `phpunit`. Vertikal "test" tersedia hanya `php -l <file>` untuk cek sintaks.

## Struktur & entrypoint
- Dua webroot terpisah, keduanya di-root repo ini:
  - `jibas/` → aplikasi utama (akademik, keuangan, kepegawaian, perpustakaan, dll).
  - `filesharing/` → modul file-sharing + e-learning (jibasls, media, galeri).
- `jibas_db.sql` di root = dump MariaDB yang membuat SEMUA database. Import ini sekali ke DB kosong.
- Setiap module = app mandiri: punya `index.php` → `login.php` atau frame `main.php`
  (frameset: header/content/footer). Module yang lebih baru (contoh `keuangan/rinjani/`)
  memakai desain plus `login.ajax.php` bukan frameset.
- `jibas/index.php` = portal landing. **Sudah dirombak** menjadi portal responsif gaya
  instansi pemerintah (lihat `style/portal.css` + `script/portal.js`): topbar + brand header,
  hero identitas hijau, kartu statistik, grid layanan ("Layanan &amp; Informasi"), strip kontak,
  footer. Reference layout: `jember.kemenag.go.id` (adaptasi, BUKAN salinan konten).
  Jangan kembali ke layout gambar fixed 1240px lama ataupun frame "phone app" sebelumnya.
  - Tiles berisi `data-action` (mis. `akademik/redirect.php`) membuka **login modal** terpusat
    (`script/portal.js`) yang POST ke `redirect.php` module tsb; decode sukses (`top.location.href`)
    vs gagal (`alert(...)`). Module tanpa `redirect.php` dibuka langsung via link.
  - **Dua protokol login di modal**: mode `redirect` (default; `redirect.php` menghasilan
    `top.location.href`/`alert`) dan mode `json` (keuangan/rinjani; POST `login.ajax.php?op=login`,
    respon `[1,"OK"]`/`[-1,msg]`). `index.php` mendeteksi mode dari URL tile (`keuangan/rinjani`).
  - Login default: `jibas` / `password` (landlord, md5 di `jbsuser.landlord`).
  - **Tiles bisa di-CRUD** lewat Akademik → Referensi → "Pengelolaan Portal Aplikasi"
    (`akademik/referensi/portalapp*.php`). Data disimpan di tabel `jbsakad.jbs_portal_app`
    (nama, deskripsi, ikon, warna, url, action, aktif, urutan). `index.php` membaca tile dari
    DB via `include/portal.tiles.php` (`jbs_portal_tiles()`), dengan fallback bawaan bila tabel kosong.
    **Catatan: tabel + seed 9 tile awalnya dibuat manual via SQL — belum ada di `jibas_db.sql`.**
    Jalankan ulang CREATE TABLE + INSERT bila DB di-restore dari dump.
  - **Teks & tema portal juga bisa diedit** lewat Akademik → Referensi → "Pengaturan Tampilan
    Portal" (`akademik/referensi/portalsetting.php`). Data key-value di `jbsakad.jbs_portal_setting`
    (bar atas, merek, hero, motto, tombol, statistik, layanan, kontak, footer, warna tema).
    `index.php` membacanya via `include/portal.settings.php` (`ps()` / `ps_init()`), fallback ke
    bawaan bila tabel kosong. `hero_motto` pakai `|` (baris) & `**teks**` (tebal). Tema diperluas
    dengan blok `:root` inline yang menimpa CSS. Seperti tabel tile, seed manual — belum di `jibas_db.sql`.
    **Logo portal = upload PNG** (di form settings, field brand_logo): disimpan ke
    `jibas/images/portal/brand_logo.png` (flag `brand_logo_type` = file|text di setting);
    `index.php` render `<img>` bila file ada, else teks `brand_logo`.

## UI design system (wajib konsisten di semua halaman baru)
Tema warna resmi portal & halaman dalam (dipakai `style/portal.css`, `akademik/referensi.php`,
`akademik/referensi/portalsetting.php`, dan semua UI baru — JANGAN bikin palet sendiri):
- `--green:#1D4533` (utama/hijau), `--green-hi:#2A5A45`, `--cream:#F7EAE0` (kartu/latar),
  `--peach:#F9D2BA` & `--peach-deep:#E0AA8C` (aksen), `--brown:#5E3122` (judul/teks aksen),
  `--ink:#2A211B` (teks), `--ink-mut:#6B5748` (teks redup), `--card:#FFFFFF`, `--line:#EADDD2`.
- Referensi UI (halaman dalam): **background putih `#FFF`**, **kartu tile krem `--cream`**,
  **kotak ikon putih dengan teks hitam** & `border:1px solid var(--line)`. Ikon tile = **78px**
  (font 39px), radius ikon 18px; grid `repeat(auto-fill,minmax(170px,1fr))`. Hapus desain gambar
  ImageReady lama (slice `referensi_*.jpg`) — ganti `referensi.php` jadi card grid modern.
- **Menu halaman akademik (PSB, Guru, Jadwal, Kesiswaan, Presensi, Penilaian, Ekspor,
  Kenaikan/Kelulusan, Mutasi, Pelaporan, Pengaturan) juga sudah modern**: pakai sistem menu
  terpusat `include/menuui.php` + `style/menuui.css`. `menu_page_start()` buka halaman,
  `menu_tabs()` buat tab, `menu_panel($id, $items, $info)` render grid kartu. Item berbentuk
  array `href/label/desc/icon/color/alert`; bila ada `alert` kartu jadi info-disabled (klik
  alert), bukan link. Semua halaman ini dulunya slice ImageReady (`pelajaran_*.jpg` dst.).
- **Halaman LIST/DATA modern (contoh: `akademik/referensi/pegawai.php`, `Guru & Pelajaran` /
  `Menu Pelajaran` yang dirombak)**: pola konsisten yang dipakai utk halaman tabel data —
  bg putih, `width:100%` (TANPA max-width center), header `pg-head` (ikon 54px gradien hijau +
  judul 22px + breadcrumb), **toolbar** kartu krem (`--cream`) dgn dropdown filter kiri + tombol
  pill kanan, **tabel** `table.tbl` (header hijau, sortable `onclick=change_urut`, zebra rows,
  kolom NIP monospace), **badge status** `.badge.aktif/.nonaktif`, **aksi** sebagai ikon emoji
  (`#128065` detail, `#128424` cetak, `#128221` ubah, `#128465` hapus), **pager** kanan-kiri
  (halaman + jumlah baris). Tombol pill `.abtn` = bg `#E9F1EC`/teks hijau, `.act` hijau penuh,
  `.warn` peach, `.danger` merah. Semua tombol pakai fungsi JS lama (`tambah()`, `hapus()`,
  `exel()`, `importcsv()`, dst.) — jangan ganti nama fungsi, hanya ubah <em>markup & CSS</em>.
- **Topbar frame atas academia (`akademik/frametop.php`) sudah modern**: nav horizontal satu
  baris (scroll-x), background gradien hijau `--green`, item pill dengan icon emoji, hover peach,
  menu `keluar` merah. Tidak lagi pakai image-swap `MM_nbGroup` + slice `Akademik2_*.png`/`Icon Header/*.png`.
  Frame `frametop` tingginya 87px (di `index2.php`) — cukup untuk nav ini.
- **Layout frame akademik (`index2.php`) sudah disederhanakan**: hanya 3 frame vertikal
  `rows="87,*,41"` (frametop / content / framebottom). Frame border kiri-kanan
  (`frameleft.php`/`frameright.php`) **dihapus** dari frameset (kini hanya redirect ke
  `referensi.php`). `framebottom.php` = status bar modern hijau: user login (dari `SI_USER_NAME()`),
  status Online, badge versi. Jangan kembalikan border slice `Akademik2_16/18.png`.
- Layout responsif: `.wrap { width:100%; margin:0 }` (TANPA max-width), grid `auto-fill` agar
  full width; mobile 3 kolom. Gunakan `lang="id"`, `meta viewport`, dan font sistem saja (tanpa CDN).
- **Gotcha CSS dalam repo:** file `.css` harus MURNI CSS (mulai dengan `/* */`), JANGAN dibungkus
  `<style>...</style>` saat disimpan sebagai file eksternal — itu merusak render. Form pakai
  `enctype="multipart/form-data"` bila ada `<input type=file>`.

## Konfigurasi (edit di `jibas/include/`)
- `mainconfig.php` = aggregator yang `require` semua konfig lain (jangan diubah, cukup edit file yang di-include).
- `database.config.php` → kredensial DB. Saat ini `host=localhost:3434`, `user=root`,
  `pass=kebersamaan`, `db_name=jbsakad`. **PENTING: port MySQL kustom 3434, bukan 3306.**
- `application.config.php` → `$G_SERVER_ADDR` (dipakai di header laporan) & `$G_OS` (`win`/`lin`).
- `filesharing.config.php` → `$FILESHARE_UPLOAD_DIR` & `$FILESHARE_ADDR`. `UPLOAD_DIR` masih
  hardcoded path Windows `C:\YIM\JIBAS\xampp\htdocs\filesharing\` — **harus diubah saat jalan di Linux.**
- `school.config.php` → logo & judul halaman muka. `version.config.php` → `$G_VERSION` badge.
- `system.config.php` → timezone (`JIBAS_TIMEZONE`).
- **Gotcha multiuser LAN:** `$G_SERVER_ADDR` dan `$FILESHARE_ADDR` JANGAN diisi `localhost`
  bila dipakai lebih dari satu komputer; gunakan IP/hostname (tertulis di komentar config).

## Database & pemetaan module→DB
10 database, semuanya dibuat oleh `jibas_db.sql`: `jbsakad`, `jbscbe`, `jbsclient`,
`jbsfina`, `jbsletter`, `jbsperpus`, `jbssat`, `jbssdm`, `jbssms`, `jbsumum`.

Setiap module menimpa `$db_name` di config lokalnya (bukan lewat `database.config.php`):
- `akademik/`, `anjungan/`, `cbe/`, `infoguru/` → `jbsakad`
- `kepegawaian/` → `jbssdm`
- `keuangan/` & `keuangan/rinjani/` → `jbsfina`
- `simtaka/` → `jbsperpus` (pakai `inc/`, bukan `include/`)
- `smsgateway/` → `jbssms`

`cbe/` memakai tambahan `$CBE_SERVER` dari `include/cbe.config.php`.

## Pola & quirk PHP yang mudah salah
- **Short tag `<?`** dipakai di banyak file (config, index module). Wajib `short_open_tag=On`,
  atau sebagian besar module error. Setiap module menyetel `session_name()` sendiri
  (bervariasi: `jbsakad`, `jbsmain`, `jbsfina`, `_JIBAS_ANJUNGAN__`, dll) **sebelum** `session_start()`.
- Setiap module punya `include/` (atau `inc/`) sendiri berisi `config.php` + `db_functions.php`
  + `sessionchecker.php`. `config.php` module sendiri yang me-load `mainconfig.php` lewat
  path relatif bertingkat (`../`, `../../`, `../../../`) — jangan memindah file tanpa periksa ini.
- Koneksi DB selalu via `OpenDb()` / `OpenDbi()` dari `db_functions.php` module tersebut
  (pakai `mysqli` global `$mysqlconnection`). Query via `QueryDb()` / `QueryDbEx()`.
- Tidak ada perpustakaan ORM; seluruh query SQL ditulis manual sebagai string.
- `include/config.php` module juga menjalankan filter anti-injection pada `$_REQUEST`
  (`FmtReq_FormatValue`).

## Patch management (otomatis saat request)
`ApplyGlobalPatch()` (global.patch.manager.php) dan `ApplyModulePatch()` (module.patch.manager.php)
dijalankan di awal setiap `config.php`. File patch berakhiran `.install.php` **dieksekusi lalu
dihapus otomatis** (`unlink`). Mark `global.patch-*.done` di `jibas/include/` menandai patch sudah dipasang.
Jangan membuat/meng-edit file `.install.php` secara manual tanpa memahami alurnya.

## Direktori runtime
`log/` (error log query, via `LogError()` bila `$G_ENABLE_QUERY_ERROR_LOG`) dan `temp/`
harus bisa ditulis oleh user web server. Tiap module juga punya `log/` & `temp/` masing-masing.

## Git
Repo ini BUKAN git repository; semua file langsung di tree.
