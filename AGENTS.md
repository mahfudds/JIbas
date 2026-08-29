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
- `jibas/index.php` / `jibas/index.html` hanya redirect ke `jibas` (module portal).

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
