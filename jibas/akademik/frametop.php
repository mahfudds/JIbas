<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Frame atas - topbar navigasi modern
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 ... GPL header ...
**[N]**/ ?>
<?php
require_once("include/theme.php");
require_once("include/errorhandler.php");
require_once("include/sessioninfo.php");
require_once("include/common.php");
require_once("include/config.php");
require_once("include/db_functions.php");

function show_info(){ }
function hide_info(){ }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Navigasi — JIBAS SIMAKA</title>
<script type="text/javascript" language="JavaScript1.2" src="script/ajax.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="script/tools.js"></script>
<script type="text/javascript" language="JavaScript1.2">
function get_fresh(){ document.location.reload(); }
function home(){ document.location.reload(); parent.framecenter.location.href="home.php"; }
function logout() {
	if (confirm("Anda yakin akan menutup Aplikasi Manajemen Akademik ini?"))
		document.location.href="logout.php";
}
</script>
<style>
:root{
	--green:#1D4533; --green-hi:#2A5A45; --cream:#F7EAE0; --peach:#F9D2BA;
	--peach-deep:#E0AA8C; --brown:#5E3122; --ink:#2A211B; --ink-mut:#6B5748;
	--line:#EADDD2;
}
*{box-sizing:border-box}
html,body{margin:0;padding:0;height:100%}
body{
	font-family:"Segoe UI",system-ui,-apple-system,Roboto,"Helvetica Neue",Arial,sans-serif;
	background:linear-gradient(90deg,#16352a 0%, var(--green) 55%, var(--green-hi) 100%);
	overflow:hidden;
}
a{text-decoration:none;color:inherit}
.nav{
	display:flex;align-items:center;height:100%;padding:0 10px;gap:6px;
	overflow-x:auto;white-space:nowrap;scrollbar-width:thin;
}
.brand{display:flex;align-items:center;gap:8px;flex:none;padding-right:12px}
.brand .bk{width:30px;height:30px;border-radius:8px;background:var(--peach);
	display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--brown)}
.brand .bt{color:#F7EAE0;font-size:13px;font-weight:800;letter-spacing:.5px}
.item{
	display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;flex:none;
	color:#EAD6C4;font-size:9px;font-weight:700;line-height:1.1;
	padding:5px 11px;border-radius:10px;border:1px solid transparent;
	transition:background .14s,color .14s,border-color .14s;text-align:center;
}
.item:hover{background:rgba(255,255,255,.10);color:#F9D2BA;border-color:rgba(249,210,186,.35)}
.item .em{font-size:33px;line-height:1}
.item .lb{font-size:9px;line-height:1.1;max-width:80px;overflow:hidden;text-overflow:ellipsis}
.item.active{background:rgba(249,210,186,.20);color:#F9D2BA;border-color:rgba(249,210,186,.4)}
.item.logout{color:#F1C7B6}
.item.logout:hover{background:rgba(212,84,84,.25);color:#fff;border-color:rgba(212,84,84,.5)}
</style>
</head>
<body>
<nav class="nav">
	<div class="brand">
		<span class="bk">&#127963;</span>
		<span class="bt">SIMAKA</span>
	</div>
	<a class="item<?= $menu=='referensi'?' active':'' ?>" href="referensi.php" target="content"><span class="em">&#128209;</span><span class="lb">Referensi</span></a>
	<a class="item<?= $menu=='psb'?' active':'' ?>" href="siswa_baru.php" target="content"><span class="em">&#128101;</span><span class="lb">PSB</span></a>
	<a class="item<?= $menu=='guru'?' active':'' ?>" href="guru.php" target="content"><span class="em">&#128104;</span><span class="lb">Guru &amp; Pelajaran</span></a>
	<a class="item<?= $menu=='jadwal'?' active':'' ?>" href="jadwal.php" target="content"><span class="em">&#128197;</span><span class="lb">Jadwal &amp; Kalender</span></a>
	<a class="item<?= $menu=='siswa'?' active':'' ?>" href="siswa.php" target="content"><span class="em">&#128106;</span><span class="lb">Kesiswaan</span></a>
	<a class="item<?= $menu=='presensi'?' active':'' ?>" href="presensi.php" target="content"><span class="em">&#128203;</span><span class="lb">Presensi</span></a>
	<a class="item<?= $menu=='penilaian'?' active':'' ?>" href="penilaian.php" target="content"><span class="em">&#128202;</span><span class="lb">Penilaian</span></a>
	<a class="item<?= $menu=='exim'?' active':'' ?>" href="exim.php" target="content"><span class="em">&#128229;</span><span class="lb">Ekspor Impor</span></a>
	<a class="item<?= $menu=='kelulusan'?' active':'' ?>" href="kelulusan.php" target="content"><span class="em">&#127891;</span><span class="lb">Kenaikan &amp; Kelulusan</span></a>
	<a class="item<?= $menu=='mutasi'?' active':'' ?>" href="mutasi.php" target="content"><span class="em">&#128260;</span><span class="lb">Mutasi</span></a>
	<a class="item<?= $menu=='pelaporan'?' active':'' ?>" href="pelaporanmenu.php" target="content"><span class="em">&#128196;</span><span class="lb">Pelaporan</span></a>
	<a class="item<?= $menu=='pengaturan'?' active':'' ?>" href="usermenu.php" target="content"><span class="em">&#9881;</span><span class="lb">Pengaturan</span></a>
	<a class="item logout" href="javascript:logout()"><span class="em">&#9203;</span><span class="lb">Keluar</span></a>
</nav>
</body>
</html>
