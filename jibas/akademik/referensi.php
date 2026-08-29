<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Referensi - modern menu shell
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 ... GPL header ...
**[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/config.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Referensi — JIBAS SIMAKA</title>
<style>
:root{
  --green:#1D4533; --green-hi:#2A5A45; --cream:#F7EAE0; --peach:#F9D2BA;
  --peach-deep:#E0AA8C; --brown:#5E3122; --ink:#2A211B; --ink-mut:#6B5748;
  --card:#FFFFFF; --line:#EADDD2;
  --shadow:0 1px 3px rgba(94,49,34,.06), 0 12px 34px rgba(94,49,34,.10);
}
*{box-sizing:border-box}
html,body{margin:0;padding:0;min-height:100%}
body{
  font-family:"Segoe UI",system-ui,-apple-system,Roboto,"Helvetica Neue",Arial,sans-serif;
  color:var(--ink);
  background:#FFFFFF;
  padding:18px 18px 32px;
  -webkit-font-smoothing:antialiased;
}
a{text-decoration:none;color:inherit}
.topbar{display:flex;align-items:center;gap:12px;margin-bottom:22px}
.topbar .mark{
  width:30px;height:30px;border-radius:9px;flex:none;
  background:linear-gradient(135deg,var(--green),var(--green-hi));
  color:var(--cream);display:flex;align-items:center;justify-content:center;
  font-size:15px;box-shadow:0 5px 11px rgba(29,69,51,.28);
}
.topbar .tt b{font-size:16px;color:var(--green);display:block}
.topbar .tt span{font-size:11px;color:var(--ink-mut)}
.wrap{width:100%;margin:0}
.sec-head{display:flex;align-items:center;justify-content:space-between;margin:18px 0 10px}
.sec-head h2{margin:0;font-size:13px;font-weight:800;color:var(--brown);letter-spacing:.4px}
.sec-head .count{font-size:11px;font-weight:700;color:var(--ink-mut)}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:10px}
.tile{
  background:var(--cream);border:1px solid #EDE0D5;border-radius:11px;padding:11px 10px;
  box-shadow:0 1px 3px rgba(94,49,34,.05);display:flex;flex-direction:column;gap:5px;
  transition:transform .15s ease,box-shadow .15s ease;position:relative;overflow:hidden;
}
.tile::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:var(--tc,var(--green))}
.tile:hover{transform:translateY(-2px);box-shadow:0 10px 26px rgba(94,49,34,.12)}
.tile .ico{width:78px;height:78px;border-radius:18px;background:#FFFFFF;
  color:#000;display:flex;align-items:center;justify-content:center;font-size:39px;
  border:1px solid var(--line)}
.tile .nm{font-size:12px;font-weight:800;color:var(--green)}
.tile .desc{font-size:10.5px;color:var(--ink-mut);line-height:1.35}
.tile a.chev{color:var(--peach-deep);font-weight:700;font-size:14px;align-self:flex-end;margin-top:auto}
.hi{display:flex;align-items:center;gap:8px;color:var(--green);font-size:11px;font-weight:700;margin-top:6px}
@media (max-width:560px){
  body{padding:14px 12px 26px}
  .grid{grid-template-columns:repeat(3,1fr);gap:8px}
}
</style>
</head>
<body>
<div class="wrap">
  <div class="topbar">
    <div class="mark">&#128209;</div>
    <div class="tt"><b>REFERENSI</b><span>JIBAS SIMAKA &middot; Pendataan &amp; Pengaturan</span></div>
  </div>

  <div class="sec-head"><h2>&#127991; Data Referensi</h2><span class="count">8 menu</span></div>
  <div class="grid">
    <a class="tile" style="--tc:#2f6bb5" href="referensi/pegawai.php" target="content">
      <span class="ico">&#128104;</span><span class="nm">Pegawai</span><span class="desc">Pendataan data pegawai.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#0a8f61" href="referensi/identitas.php" target="content">
      <span class="ico">&#127963;</span><span class="nm">Identitas Sekolah</span><span class="desc">Data identitas &amp; profil sekolah.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#7a4fb5" href="referensi/departemen.php" target="content">
      <span class="ico">&#127970;</span><span class="nm">Departemen</span><span class="desc">Pendataan departemen.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#c2701f" href="referensi/angkatan.php" target="content">
      <span class="ico">&#128101;</span><span class="nm">Angkatan</span><span class="desc">Pendataan angkatan siswa.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#b53f3f" href="referensi/tingkat.php" target="content">
      <span class="ico">&#128218;</span><span class="nm">Tingkat</span><span class="desc">Pendataan tingkat/kelas tingkatan.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#1896a8" href="referensi/tahunajaran.php" target="content">
      <span class="ico">&#128197;</span><span class="nm">Tahun Ajaran</span><span class="desc">Pendataan tahun ajaran.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#3f9db5" href="referensi/semester.php" target="content">
      <span class="ico">&#128337;</span><span class="nm">Semester</span><span class="desc">Pendataan semester.</span><span class="chev">&rsaquo;</span></a>
    <a class="tile" style="--tc:#7a6a2f" href="referensi/kelas.php" target="content">
      <span class="ico">&#128218;</span><span class="nm">Kelas</span><span class="desc">Pendataan kelas &amp; rombel.</span><span class="chev">&rsaquo;</span></a>
  </div>

  <div class="hi">&#128100; Login: <?= htmlspecialchars(SI_USER_NAME()) ?> &middot; v JIBAS <?= $G_VERSION ?></div>
</div>
</body>
</html>
