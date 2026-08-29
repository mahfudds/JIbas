<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Frame bawah - status bar modern
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 ... GPL header ...
**[N]**/ ?>
<?php
require_once("include/sessioninfo.php");
require_once("include/config.php");

$user = SI_USER_NAME();
if ($user == "landlord") $user = "Administrator JIBAS [Akademik]";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Status</title>
<style>
:root{--green:#1D4533;--green-hi:#2A5A45;--cream:#F7EAE0;--peach:#F9D2BA;--brown:#5E3122;--ink-mut:#CDB9A6}
*{box-sizing:border-box}
html,body{margin:0;padding:0;height:100%;overflow:hidden}
body{
	font-family:"Segoe UI",system-ui,-apple-system,Roboto,sans-serif;
	background:linear-gradient(90deg,#16352a 0%, var(--green) 55%, var(--green-hi) 100%);
	color:var(--cream);
}
.bar{display:flex;align-items:center;justify-content:space-between;height:100%;padding:0 14px;gap:12px}
.side{display:flex;align-items:center;gap:10px;min-width:0}
.side.right{justify-content:flex-end}
.dot{width:8px;height:8px;border-radius:50%;background:#6fd598;box-shadow:0 0 8px rgba(111,213,152,.8);flex:none}
.lbl{font-size:12px;font-weight:700}
.muted{font-size:11px;color:var(--ink-mut)}
.ver{font-size:11px;font-weight:700;color:var(--peach);border:1px solid rgba(249,210,186,.4);padding:3px 9px;border-radius:999px}
</style>
</head>
<body>
<div class="bar">
	<div class="side left">
		<span class="dot"></span>
		<span class="lbl"><?= htmlspecialchars($user) ?></span>
		<span class="muted">Online</span>
	</div>
	<div class="side right">
		<span class="muted">JIBAS SIMAKA</span>
		<span class="ver">v<?= $G_VERSION ?></span>
	</div>
</div>
</body>
</html>
