<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 35.5 (August 10, 2026)
 * @notes: 
 * 
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

OpenDb();
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

if (isset($_REQUEST['tahunajaran'])) 
	$tahunajaran = $_REQUEST['tahunajaran'];
	
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
	
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
	
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Pendataan Siswa — JIBAS SIMAKA</title>
<link rel="stylesheet" type="text/css" href="../style/menuui.css" />
<style>
:root{--green:#1D4533;--green-hi:#2A5A45;--cream:#F7EAE0;--peach:#F9D2BA;--peach-deep:#E0AA8C;--brown:#5E3122;--ink:#2A211B;--ink-mut:#6B5748;--line:#EADDD2}
body{margin:0;padding:14px 18px;background:#FFF;font-family:"Segoe UI",system-ui,sans-serif;color:var(--ink)}
.filter{display:flex;align-items:flex-end;gap:18px;flex-wrap:wrap;background:var(--cream);border:1px solid var(--line);border-radius:12px;padding:16px 18px}
.field{display:flex;flex-direction:column;gap:6px}
.field label{font-size:12px;font-weight:800;color:var(--brown)}
.field select,.field input[type=text]{padding:8px 10px;border:1px solid var(--line);border-radius:8px;font-size:13px;background:#fff}
.field select:focus,.field input:focus{outline:none;border-color:var(--green)}
.view-btn{width:52px;height:52px;border-radius:12px;border:none;cursor:pointer;background:linear-gradient(135deg,var(--green),var(--green-hi));color:var(--cream);font-size:26px;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 18px rgba(29,69,51,.28);transition:transform .14s,filter .14s}
.view-btn:hover{transform:translateY(-2px);filter:brightness(1.08)}
.head-title{margin-left:auto;text-align:right}
.head-title h1{font-size:20px;font-weight:800;color:var(--green);margin:0}
.head-title .bread{font-size:12px;color:var(--ink-mut);font-weight:700}
.head-title .bread a{color:var(--green)}
@media (max-width:640px){.head-title{margin-left:0;text-align:left}.filter{gap:12px}}
</style>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript">
function change_dep() {
	var departemen = document.getElementById("departemen").value;
	parent.header.location.href = "siswa_header.php?departemen="+departemen;
	parent.footer.location.href = "blank_siswa.php";
}
function change_tingkat() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;
	parent.header.location.href = "siswa_header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen;
	parent.footer.location.href = "blank_siswa.php";
}
function change_kelas() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;
	var kelas = document.getElementById("kelas").value;
	parent.header.location.href = "siswa_header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen+"&kelas="+kelas;
	parent.footer.location.href = "blank_siswa.php";
}
function show_siswa() {
	var departemen = document.getElementById("departemen").value;
	var tahunajaran = document.getElementById("tahunajaran").value;
	var tingkat = document.getElementById("tingkat").value;
	var kelas = document.getElementById("kelas").value;
	if (kelas==""){
		alert ('Kelas tidak boleh kosong');
		return false;
	}
	parent.footer.location.href = "siswa_content.php?departemen="+departemen+"&tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&kelas="+kelas;
}
function focusNext(elemName, evt) {
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
	if (charCode == 13) {
		document.getElementById(elemName).focus();
		if (elemName == 'tabel') { show_siswa(); }
		return false;
	}
	return true;
}
</script>
</head>
<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">

<div class="filter">
	<div class="field">
		<label>Departemen</label>
		<select name="departemen" id="departemen" onchange="change_dep()" onKeyPress="return focusNext('tingkat', event)">
		<?	$dep = getDepartemen(SI_USER_ACCESS());
			foreach($dep as $value) {
			if ($departemen == "")
				$departemen = $value; ?>
			<option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?> ><?=$value ?></option>
		<?	} ?>
		</select>
	</div>

	<div class="field">
		<label>Tahun Ajaran</label>
		<?  $sql = "SELECT replid,tahunajaran FROM tahunajaran WHERE departemen = '$departemen' AND aktif=1 ORDER BY replid DESC";
			$result = QueryDb($sql);
			$row = @mysqli_fetch_array($result);
			$tahunajaran = $row['replid']; ?>
		<input type="text" name="tahun" id="tahun" readonly class="disabled" value="<?=$row['tahunajaran']?>" />
		<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$row['replid']?>">
	</div>

	<div class="field">
		<label>Kelas</label>
		<div style="display:flex;gap:8px;flex-wrap:wrap">
			<select name="tingkat" id="tingkat" onchange="change_tingkat()" onKeyPress="return focusNext('kelas', event)">
			<? $sql = "SELECT replid,tingkat FROM tingkat where departemen='$departemen' AND aktif = 1 ORDER BY urutan";
				$result = QueryDb($sql);
				while ($row = @mysqli_fetch_array($result)) {
				if ($tingkat == "")
					$tingkat = $row['replid'];	?>
				<option value="<?=urlencode($row['replid'])?>" <?=IntIsSelected($row['replid'], $tingkat)?> ><?=$row['tingkat']?></option>
			<?	}	?>
			</select>
			<select name="kelas" id="kelas" onchange="change_kelas()" style="min-width:220px" onKeyPress="return focusNext('tabel', event)">
			<?	$sql = "SELECT replid, kelas, kapasitas FROM kelas where idtingkat='$tingkat' AND idtahunajaran='$tahunajaran' AND aktif = 1 ORDER BY kelas";
				$result = QueryDb($sql);
				while ($row = @mysqli_fetch_array($result)) {
					if ($kelas == "")
						$kelas = $row['replid'];
					$sql1 = "SELECT COUNT(*) FROM siswa WHERE idkelas = '$row[0]' AND aktif = 1";
					$result1 = QueryDb($sql1);
					$row1 = @mysqli_fetch_row($result1);
			?>
				<option value="<?=urlencode($row['replid'])?>" <?=IntIsSelected($row['replid'], $kelas)?> >
				<?=$row['kelas'].', kapasitas: '.$row['kapasitas'].', terisi: '.$row1[0]?>
				</option>
			<?	} ?>
			</select>
		</div>
	</div>

	<button class="view-btn" onclick="show_siswa()" title="Tampilkan daftar siswa">&#128065;</button>

	<div class="head-title">
		<h1>Pendataan Siswa</h1>
		<div class="bread"><a href="../siswa.php" target="content">Kesiswaan</a> &rsaquo; Pendataan Siswa</div>
	</div>
</div>

</body>
</html>
<script language="javascript">
	var spryselect = new Spry.Widget.ValidationSelect("departemen");
	var spryselect1 = new Spry.Widget.ValidationSelect("tingkat");
	var spryselect2 = new Spry.Widget.ValidationSelect("kelas");
</script>
<?
CloseDb();
?>