<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Mobile-app portal shell
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 * ... GPL license header ...
**[N]**/ ?>
<?php
require_once('include/mainconfig.php');
require_once('include/db_functions.php');
require_once('include/global.patch.manager.php');
ApplyGlobalPatch(".");

session_name("jbsmain");
session_start();

// Portal tiles read from DB (customizable via Akademik -> Referensi),
// fallback built-in if table is empty.
require_once('include/portal.tiles.php');
$jbsTiles = jbs_portal_tiles();

// Portal text/theme settings (customizable via Akademik -> Referensi).
require_once('include/portal.settings.php');
ps_init(); // trigger load once
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta name="theme-color" content="#1D4533" />
<title><?= htmlspecialchars($G_JUDUL_DEPAN_1) ?> — JIBAS <?= $G_VERSION ?></title>
<link rel="shortcut icon" href="images/jibas2015.ico" />
<style>
:root {
  --green: <?= ps('warna_green', '#1D4533') ?>;
  --cream: <?= ps('warna_cream', '#F7EAE0') ?>;
  --peach: <?= ps('warna_peach', '#F9D2BA') ?>;
  --brown: <?= ps('warna_brown', '#5E3122') ?>;
}
</style>
<link rel="stylesheet" href="style/portal.css" />
</head>
<body>

<!-- Top utility bar -->
<div class="topbar">
  <div class="topbar-inner">
    <span><strong><?= htmlspecialchars(ps('topbar_nama')) ?></strong> &mdash; <?= htmlspecialchars(ps('topbar_law_text')) ?></span>
    <span class="tb-links">
      <a href="<?= htmlspecialchars(ps('topbar_web')) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(ps('topbar_nama')) ?></a>
      <a href="readme.txt" target="_blank"><?= htmlspecialchars(ps('topbar_bantuan')) ?></a>
    </span>
  </div>
</div>

<!-- Brand header -->
<header class="site-header">
  <div class="site-header-inner">
    <div class="brand-logo"><?php
      // Uploaded PNG logo takes priority; falls back to the text value.
      $jbsLogoFile = 'images/portal/brand_logo.png';
      if (file_exists($jbsLogoFile)) {
        echo '<img src="' . $jbsLogoFile . '?v=' . filemtime($jbsLogoFile) . '" alt="logo" />';
      } else {
        echo htmlspecialchars(ps('brand_logo'));
      }
    ?></div>
    <div class="brand-text">
      <div class="brand-eyebrow"><?= htmlspecialchars(ps('brand_eyebrow')) ?></div>
      <h1><?= htmlspecialchars(ps('brand_judul')) ?></h1>
      <div class="brand-sub"><?= htmlspecialchars(ps('brand_sub')) ?> &middot; JIBAS v<?= $G_VERSION ?></div>
    </div>
  </div>
</header>

<!-- Hero identity -->
<section class="hero">
  <div class="hero-inner">
    <div class="hero-identity">
      <span class="eyebrow"><?= htmlspecialchars(ps('hero_eyebrow')) ?></span>
      <h2><?= htmlspecialchars(ps('hero_judul')) ?></h2>
      <div class="hero-loc"><?= htmlspecialchars(ps('hero_lokasi')) ?></div>
      <div class="motto"><?= ps_motto_html('hero_motto') ?></div>
      <div class="hero-actions">
        <a class="btn btn-accent" href="#layanan"><?= htmlspecialchars(ps('hero_btn1')) ?></a>
        <a class="btn btn-outline" href="<?= htmlspecialchars(ps('hero_btn2_url')) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(ps('hero_btn2')) ?></a>
      </div>
    </div>
    <div class="stats">
      <div class="stat-card">
        <div class="stat-num"><?= count($jbsTiles) ?><small>+</small></div>
        <div class="stat-tag"><?= htmlspecialchars(ps('stat1_label')) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><?= (int)$G_START_YEAR ?><small>-<?= date('Y') ?></small></div>
        <div class="stat-tag"><?= htmlspecialchars(ps('stat2_label')) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-num"><small>v</small><?= $G_VERSION ?></div>
        <div class="stat-tag"><?= htmlspecialchars(ps('stat3_label')) ?></div>
      </div>
    </div>
  </div>
</section>

<!-- Services -->
<section class="section" id="layanan">
  <div class="section-head">
    <div class="kicker"><?= htmlspecialchars(ps('services_kicker')) ?></div>
    <h2><?= htmlspecialchars(ps('services_judul')) ?></h2>
    <p><?= htmlspecialchars(ps('services_teks')) ?></p>
    <div class="search-box">
      <span class="search-ico">&#128269;</span>
      <input type="text" id="jbsSearch" placeholder="Cari aplikasi atau layanan..." autocomplete="off" />
      <button type="button" class="search-clear" id="jbsSearchClear" aria-label="Bersihkan">&times;</button>
    </div>
    <div class="search-empty" id="jbsSearchEmpty" style="display:none">Tidak ada aplikasi yang cocok.</div>
  </div>
  <div class="services-grid" id="jbsServiceGrid"></div>
</section>

<!-- Contact strip -->
<section class="contact-strip">
  <div class="contact-inner">
    <div class="contact-item"><span class="ci-ico">🏛️</span><div><div class="ci-label"><?= htmlspecialchars(ps('kontak1_label')) ?></div><div class="ci-value"><?= htmlspecialchars(ps('kontak1_value')) ?></div></div></div>
    <div class="contact-item"><span class="ci-ico">📍</span><div><div class="ci-label"><?= htmlspecialchars(ps('kontak2_label')) ?></div><div class="ci-value"><?= htmlspecialchars(ps('kontak2_value')) ?></div></div></div>
    <div class="contact-item"><span class="ci-ico">🗓️</span><div><div class="ci-label"><?= htmlspecialchars(ps('kontak3_label')) ?></div><div class="ci-value"><?= htmlspecialchars(ps('kontak3_value')) ?></div></div></div>
    <div class="contact-item"><span class="ci-ico">🛠️</span><div><div class="ci-label"><?= htmlspecialchars(ps('kontak4_label')) ?></div><div class="ci-value"><?= htmlspecialchars(ps('kontak4_value')) ?></div></div></div>
  </div>
</section>

<footer class="site-footer">
  <div class="foot-brand"><?= htmlspecialchars(ps('brand_judul')) ?></div>
  &copy; <?= date('Y') ?> <?= htmlspecialchars(ps('brand_sub')) ?> &middot; <?= htmlspecialchars(ps('footer_teks')) ?> <?= $G_VERSION ?> &middot; <?= $G_COPYRIGHT ?>
  <div class="foot-meta"><?= htmlspecialchars(ps('hero_lokasi')) ?></div>
</footer>

<div class="modal-overlay" id="jbsModalOverlay">
  <div class="modal" role="dialog" aria-modal="true">
    <div class="modal-head">
      <span class="tile-icon" id="jbsModalIcon">🔒</span>
      <div>
        <h3 id="jbsModalName">Masuk</h3>
        <p id="jbsModalSub">Masuk menggunakan akun sistem Anda</p>
      </div>
      <button class="modal-close" id="jbsModalClose" type="button" aria-label="Tutup">&times;</button>
    </div>
    <div class="modal-error" id="jbsModalError"></div>
    <form class="modal-form" id="jbsModalForm" autocomplete="off">
      <div class="field">
        <label for="jbsUser">Username</label>
        <input type="text" id="jbsUser" name="username" placeholder="Username" autocomplete="username" />
      </div>
      <div class="field">
        <label for="jbsPass">Password</label>
        <input type="password" id="jbsPass" name="password" placeholder="Password" autocomplete="current-password" />
      </div>
    </form>
    <div class="modal-actions">
      <button class="btn-primary" id="jbsModalBtn" form="jbsModalForm" type="submit">Masuk</button>
    </div>
    <div class="modal-hint">Ubah username &amp; password di aplikasi masing-masing.</div>
  </div>
</div>

<script>
// Apps data for the portal; rendered by portal.js. Customizable via
// Akademik -> Referensi -> Portal Aplikasi (jbsakad.jbs_portal_app).
window.JBS_APPS = [
<?php
$appJs = array();
foreach ($jbsTiles as $t) {
    $action = $t['action'];
    $mode = 'redirect';
    // keuangan/rinjani memakai protokol JSON (login.ajax.php?op=login).
    if (strpos($t['url'], 'keuangan/rinjani') !== false) {
        $action = 'keuangan/rinjani/login.ajax.php';
        $mode = 'json';
    }
    $appJs[] = sprintf(
        "{ name:%s, desc:%s, icon:%s, color:%s, url:%s, action:%s, mode:%s, lock:%s }",
        json_encode($t['nama']), json_encode($t['deskripsi']), json_encode($t['ikon']),
        json_encode($t['warna']), json_encode($t['url']),
        json_encode($action), json_encode($mode), ($action ? 'true' : 'false')
    );
}
echo implode(",\n  ", $appJs);
?>
];
</script>
<script src="script/portal.js"></script>
</body>
</html>
