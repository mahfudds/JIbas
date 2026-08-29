<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Pengelolaan Portal Aplikasi (tiles halaman muka)
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 ... GPL header ...
**[N]**/ ?>
<?
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

OpenDb();

// set aktif (toggle tampil)
$op = $_REQUEST['op'] ?? '';
if ($op == "dw8dxn8w9ms8zs22") {
	QueryDb("UPDATE jbs_portal_app SET aktif='".$_REQUEST['newaktif']."' WHERE replid='".$_REQUEST['replid']."'");
}

// urutan naik/turun
if ($op == "naik" || $op == "turun") {
	$id = (int)$_REQUEST['replid'];
	$rs = QueryDb("SELECT replid FROM jbs_portal_app ORDER BY urutan, replid");
	$order = array();
	while ($r = mysqli_fetch_row($rs)) $order[] = $r[0];
	$pos = array_search($id, $order);
	$swap = $op == "naik" ? $pos - 1 : $pos + 1;
	if ($pos !== false && $swap >= 0 && $swap < count($order)) {
		$a = $order[$pos]; $b = $order[$swap];
		$order[$pos] = $b; $order[$swap] = $a;
	}
	// reindex urutan sequentially (1..n) to avoid gaps
	foreach ($order as $i => $rid) {
		QueryDb("UPDATE jbs_portal_app SET urutan=".($i+1)." WHERE replid='".$rid."'");
	}
}

// hapus
if ($op == "hapus") {
	QueryDb("DELETE FROM jbs_portal_app WHERE replid='".$_REQUEST['replid']."'");
}

// add / update (umbal dari popup)
if (isset($_REQUEST['submit_save'])) {
	$nama = CQ($_REQUEST['nama']);
	$deskripsi = CQ($_REQUEST['deskripsi']);
	$ikon = CQ($_REQUEST['ikon']);
	$warna = CQ($_REQUEST['warna']);
	$url = CQ($_REQUEST['url']);
	$loginurl = CQ($_REQUEST['loginurl']);
	$replid = (int)($_REQUEST['replid'] ?? 0);

	if (strlen($nama) == 0) {
		echo "<script>alert('Nama aplikasi wajib diisi.');</script>";
	} else {
		if ($replid > 0) {
			QueryDb("UPDATE jbs_portal_app SET nama='$nama', deskripsi='$deskripsi', ikon='$ikon', warna='$warna', url='$url', action='$loginurl' WHERE replid='$replid'");
		} else {
			$max = QueryDb("SELECT COALESCE(MAX(urutan),0)+1 FROM jbs_portal_app");
			$next = mysqli_fetch_row($max);
			QueryDb("INSERT INTO jbs_portal_app SET nama='$nama', deskripsi='$deskripsi', ikon='$ikon', warna='$warna', url='$url', action='$loginurl', aktif=1, urutan='".$next[0]."'");
		}
		echo "<script>if(opener)opener.location.href='portalapp.php'; window.close();</script>";
		exit;
	}
}

$result = QueryDb("SELECT * FROM jbs_portal_app ORDER BY urutan, replid");
CloseDb();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Portal Aplikasi</title>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="JavaScript">
function tambah() {
	newWindow('portalapp_add.php', 'TambahPortalApp','540','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function edit(replid) {
	newWindow('portalapp_edit.php?replid='+replid, 'UbahPortalApp','540','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}
function hapus(replid) {
	if (confirm("Apakah anda yakin akan menghapus aplikasi portal ini?"))
		document.location.href = "portalapp.php?op=hapus&replid="+replid;
}
function setaktif(replid, aktif) {
	var newaktif = (aktif == 1) ? 0 : 1;
	var msg = (aktif == 1) ? "Sembunyikan aplikasi ini dari halaman muka?" : "Tampilkan aplikasi ini di halaman muka?";
	if (confirm(msg))
		document.location.href = "portalapp.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif;
}
function naik(replid) { document.location.href = "portalapp.php?op=naik&replid="+replid; }
function turun(replid) { document.location.href = "portalapp.php?op=turun&replid="+replid; }
</script>
</head>
<body>
<table border="0" width="100%" height="100%">
<tr><td align="center" valign="top">

<table border="0" width="95%" align="center">
<tr>
	<td width="92%" align="right"><font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Portal Aplikasi</font></td>
</tr>
<tr>
	<td align="right"><a href="../referensi.php" target="content"><font size="1" color="#000000"><b>Referensi</b></font></a>&nbsp;&gt;&nbsp;<font size="1" color="#000000"><b>Portal Aplikasi</b></font></td>
</tr>
<tr><td align="left">&nbsp;</td></tr>
</table><br /><br />

<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
<tr>
	<td align="right"><a href="JavaScript:tambah()"><img src="../images/ico/tambah.png" border="0" onmouseover="showhint('Tambah!', this, event, '50px')"/>&nbsp;Tambah Aplikasi</a></td>
</tr>
</table><br />

<table class="tab" id="table" border="1" style="border-collapse:collapse" width="95%" align="center" bordercolor="#000000">
<tr height="30" align="center" class="header">
	<td width="4%">Urut</td>
	<td width="6%">Ikon</td>
	<td width="16%">Nama</td>
	<td width="20%">Deskripsi</td>
	<td width="18%">URL</td>
	<td width="12%">Login Modal</td>
	<td width="8%">Status</td>
	<td width="10%">Aksi</td>
</tr>
<?
while ($row = mysqli_fetch_array($result)) { ?>
<tr height="25">
	<td align="center">
		<a href="JavaScript:naik(<?=$row['replid']?>)" onMouseOver="showhint('Naik', this, event, '50px')"><b>&#9650;</b></a>
		<a href="JavaScript:turun(<?=$row['replid']?>)" onMouseOver="showhint('Turun', this, event, '50px')"><b>&#9660;</b></a>
	</td>
	<td align="center" style="font-size:20px"><?=$row['ikon']?></td>
	<td><?=$row['nama']?></td>
	<td><?=$row['deskripsi']?></td>
	<td><?=$row['url']?></td>
	<td align="center"><?=$row['action'] ? "Ya" : "Tidak"?></td>
	<td align="center">
		<? if ($row['aktif'] == 1) { ?>
			<a href="JavaScript:setaktif(<?=$row['replid']?>, <?=$row['aktif']?>)"><img src="../images/ico/aktif.png" border="0" onMouseOver="showhint('Aktif', this, event, '80px')"/></a>
		<? } else { ?>
			<a href="JavaScript:setaktif(<?=$row['replid']?>, <?=$row['aktif']?>)"><img src="../images/ico/nonaktif.png" border="0" onMouseOver="showhint('Tidak Aktif', this, event, '80px')"/></a>
		<? } ?>
	</td>
	<td align="center">
		<a href="JavaScript:edit(<?=$row['replid']?>)"><img src="../images/ico/ubah.png" border="0" onMouseOver="showhint('Ubah', this, event, '80px')"/></a>&nbsp;
		<a href="JavaScript:hapus(<?=$row['replid']?>)"><img src="../images/ico/hapus.png" border="0" onMouseOver="showhint('Hapus', this, event, '80px')"/></a>
	</td>
</tr>
<? } ?>
</table>

<script language='JavaScript'>Tables('table', 1, 0);</script>

</td></tr>
</table>
</body>
</html>
