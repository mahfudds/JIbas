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
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

OpenDb();

$bagian = "-1";
if (isset($_REQUEST["bagian"]))
	$bagian=$_REQUEST["bagian"];

$varbaris=20;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];

$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];
	
$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$op = "";
if (isset($_REQUEST['op']))
	$op = $_REQUEST['op'];

if ($op == "dw8dxn8w9ms8zs22")
{
	$sql = "UPDATE jbssdm.pegawai SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
}
else if ($op == "xm8r389xemx23xb2378e23")
{
    // -- v31 -- 2025-05-26
    $sql = "DELETE FROM jbsakad.riwayatfoto WHERE nip = (SELECT nip FROM jbssdm.pegawai WHERE replid = '$_REQUEST[replid]')";
    QueryDb($sql);

    // -- v31 -- 2025-05-26
    $sql = "DELETE FROM jbssdm.tambahandatapegawai WHERE nip = (SELECT nip FROM jbssdm.pegawai WHERE replid = '$_REQUEST[replid]')";
    QueryDb($sql);

	$sql = "DELETE FROM jbssdm.pegawai WHERE replid = '$_REQUEST[replid]'";
	$result = QueryDb($sql);

	$page = 0;
	$hal = 0;
}

if ($op == "fdgfde342ft45tgwer34rfwef") {
	$pin = random(5);
	$sql = "UPDATE jbssdm.pegawai SET `$_REQUEST[field]` = '$pin' WHERE nip = '$_REQUEST[nip]'";
	QueryDb($sql);
}

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	

$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="pragma" content="no-cache">
<title>Kepegawaian — JIBAS SIMAKA</title>
<link rel="stylesheet" type="text/css" href="../style/menuui.css" />
<style>
:root{--green:#1D4533;--green-hi:#2A5A45;--cream:#F7EAE0;--peach:#F9D2BA;--peach-deep:#E0AA8C;--brown:#5E3122;--ink:#2A211B;--ink-mut:#6B5748;--line:#EADDD2}
.pg-head{display:flex;align-items:center;gap:14px;margin-bottom:18px}
.pg-head .ico{width:54px;height:54px;border-radius:15px;background:linear-gradient(135deg,var(--green),var(--green-hi));color:var(--cream);display:flex;align-items:center;justify-content:center;font-size:26px;box-shadow:0 8px 18px rgba(29,69,51,.28)}
.pg-head h1{font-size:22px;font-weight:800;color:var(--green);margin:0}
.pg-head .bread{font-size:12px;color:var(--ink-mut);font-weight:700}
.pg-head .bread a{color:var(--green)}
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:var(--cream);border:1px solid var(--line);border-radius:12px;padding:12px 14px;margin-bottom:16px}
.toolbar .sel{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:var(--ink)}
.toolbar select{padding:7px 10px;border:1px solid var(--line);border-radius:8px;background:#fff;font-size:13px}
.toolbar .spacer{flex:1}
.btn-row{display:flex;gap:8px;flex-wrap:wrap}
.abtn{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border:none;border-radius:8px;font-weight:700;font-size:12.5px;cursor:pointer;color:var(--green);background:#E9F1EC;text-decoration:none;transition:background .14s,transform .1s}
.abtn:hover{background:#DCE9E1;transform:translateY(-1px)}
.abtn b{font-size:14px}
.abtn.act{background:var(--green);color:#F7EAE0}
.abtn.warn{background:#FBE7D4;color:var(--brown)}
.abtn.danger{background:#F9E3E0;color:#B53F3F}
table.tbl{width:100%;border-collapse:collapse;background:#fff;border:1px solid var(--line);border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(94,49,34,.06)}
.tbl thead th{background:var(--green);color:#F7EAE0;padding:10px 12px;text-align:left;font-size:12.5px;font-weight:800;cursor:pointer;white-space:nowrap}
.tbl thead th.sort{background:var(--green-hi)}
.tbl thead th:hover{background:var(--green-hi)}
.tbl tbody td{padding:9px 12px;border-top:1px solid var(--line);font-size:13px;color:var(--ink)}
.tbl tbody tr:nth-child(even){background:#FBF6F0}
.tbl tbody tr:hover{background:#F3EAE0}
.tbl .mono{font-family:Consolas,monospace;font-size:12px}
.badge{display:inline-block;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:800}
.badge.aktif{background:#E2F4EA;color:#0a8f61}
.badge.nonaktif{background:#F9E3E0;color:#B53F3F}
.act-icons{display:flex;gap:8px;align-items:center;white-space:nowrap}
.act-icons a{font-size:15px;text-decoration:none;cursor:pointer}
.pager{display:flex;align-items:center;justify-content:space-between;margin-top:14px;flex-wrap:wrap;gap:10px}
.pager .inf{font-size:12.5px;color:var(--ink-mut);font-weight:700}
.empty{background:#fff;border:1px dashed var(--line);border-radius:12px;padding:44px;text-align:center;color:var(--ink-mut);font-size:14px}
.empty a{color:var(--green);font-weight:800}
@media (max-width:640px){.tbl td:nth-child(5),.tbl th:nth-child(5){display:none}.pg-head h1{font-size:18px}}
</style>
<script src="../script/SpryValidationSelect.js" type="text/javascript"></script>
<script language="javascript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh(){
	var bagian=document.getElementById("bagian").value;
	//document.location.href="pegawai.php?bagian="+bagian;
	document.location.href = "pegawai.php?bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>"
}

function change_bagian(){
	var bagian=document.getElementById("bagian").value;
	document.location.href="pegawai.php?bagian="+bagian+"&varbaris=<?=$varbaris?>";
}

function setaktif(replid, aktif) {
	var bagian=document.getElementById("bagian").value;
	var msg;
	var newaktif;
	
	if (aktif == 1) {
		msg = "Apakah anda yakin akan mengubah status pegawai ini menjadi TIDAK AKTIF?";
		newaktif = 0;
	} else	{	
		msg = "Apakah anda yakin akan mengubah status pegawai ini menjadi AKTIF?";
		newaktif = 1;
	}
	
	if (confirm(msg)) 
		document.location.href = "pegawai.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
	
}

function hapus(replid) {
	var bagian=document.getElementById("bagian").value;
	if (confirm("Apakah anda yakin akan menghapus pegawai ini?"))
		document.location.href = "pegawai.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
}

function change_urut(urut,urutan) {	
	var bagian=document.getElementById("bagian").value;
	var varbaris=document.getElementById("varbaris").value;
	
	if (urutan =="ASC"){
		urutan="DESC"
	} else {
		urutan="ASC"
	}
	
	document.location.href="pegawai.php?bagian="+bagian+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
}

function tambah() {
	var bagian=document.getElementById("bagian").value;
	newWindow('pegawai_add.php?bagian='+bagian, 'TambahPegawai','500','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function lihat(replid) {	
	newWindow('pegawai_view.php?replid='+replid, 'LihatPegawai','790','610','resizable=0,scrollbars=1,status=0,toolbar=0')
}

function edit(replid) {
	newWindow('pegawai_edit.php?replid='+replid, 'UbahPegawai','535','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak(urut,urutan) {
	var bagian=document.getElementById("bagian").value;
	var total=document.getElementById("total").value;
	
	newWindow('pegawai_cetak.php?bagian='+bagian+'&urut='+urut+'&urutan='+urutan+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakPegawai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak_detail(replid) {
	newWindow('pegawai_cetak_detail.php?replid='+replid, 'CetakDetailCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function change_page(page) {
	var bagian=document.getElementById("bagian").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="pegawai.php?bagian="+bagian+"&page="+page+"&hal="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_hal() {
	var bagian = document.getElementById("bagian").value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="pegawai.php?bagian="+bagian+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function change_baris() {
	var bagian = document.getElementById("bagian").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="pegawai.php?bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}

function gantipin(field, nip) {
	if (confirm("Apakah anda yakin akan mengganti PIN ini?")) {
		var bagian = document.getElementById("bagian").value;
		var hal = document.getElementById("hal").value;
		var varbaris=document.getElementById("varbaris").value;
		//document.location.href = "pegawai.php?op=fdgfde342ft45tgwer34rfwef&bagian="+bagian+"&urut=<?=$urut?>&urutan=<?=$urutan?>&field="+field+"&nip="+nip+"&hal="+hal+"&varbaris="+varbaris;
		document.location.href = "pegawai.php?op=fdgfde342ft45tgwer34rfwef&bagian="+bagian+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>&field="+field+"&nip="+nip;
	}	
}

function exel()
{
	newWindow('pegawai_excel.php', 'ExcelPegawai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function importcsv()
{
	newWindow('pegawai_import.php', 'ImportPegawai','560','620','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function modaltemplate()
{
	window.open('pegawai_import_template.php', '_blank');
}

</script>

</head>
<body onload="document.getElementById('bagian').focus()">
<div style="width:100%;margin:0;padding:22px 20px 40px">

	<div class="pg-head">
		<span class="ico">&#128104;</span>
		<div>
			<h1>Kepegawaian</h1>
			<div class="bread"><a href="../referensi.php" target="content">Referensi</a> &rsaquo; Kepegawaian</div>
		</div>
	</div>

	<div class="toolbar">
		<div class="sel"><label for="bagian">Bagian</label>
			<select name="bagian" id="bagian" onchange="change_bagian()">
				<option value="-1" <?=StringIsSelected('-1', $bagian)?>>Semua Bagian</option>
				<?
				OpenDb();
				$sql_bag = "SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan";
				$result_bag = QueryDB($sql_bag);
				while ($row_bag = @mysqli_fetch_array($result_bag)){
				?>
				<option value="<?=$row_bag['bagian']?>" <?=StringIsSelected($row_bag['bagian'], $bagian)?>> <?=$row_bag['bagian']?></option>
				<?
				}
				?>
			</select>
		</div>
		<div class="spacer"></div>
		<div class="btn-row">
			<? if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
			<a class="abtn act" href="JavaScript:tambah()"><b>&#10133;</b>Tambah Pegawai</a>
			<? } ?>
			<a class="abtn" href="JavaScript:importcsv()"><b>&#128228;</b>Import CSV</a>
			<a class="abtn warn" href="JavaScript:modaltemplate()"><b>&#128229;</b>Template</a>
			<a class="abtn" href="JavaScript:exel()"><b>&#128196;</b>Excel</a>
			<a class="abtn" href="JavaScript:cetak('<?=$urut?>','<?=$urutan?>')"><b>&#128424;</b>Cetak</a>
			<a class="abtn" href="#" onclick="refresh()"><b>&#10227;</b>Refresh</a>
		</div>
	</div>

	<?
	if ($bagian != "-1"){
		$sql_tot = "SELECT * FROM jbssdm.pegawai WHERE bagian='$bagian' ORDER BY replid";
		$result_tot = QueryDb($sql_tot);
		$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$sql_pegawai="SELECT * FROM jbssdm.pegawai WHERE bagian='$bagian' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	} else {
		$sql_tot = "SELECT * FROM jbssdm.pegawai ORDER BY replid";
		$result_tot = QueryDb($sql_tot);
		$total = ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
		$jumlah = mysqli_num_rows($result_tot);
		$sql_pegawai="SELECT * FROM jbssdm.pegawai ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	}
	$akhir = ceil($jumlah/5)*5;
	$result_pegawai=QueryDb($sql_pegawai);

	if (@mysqli_num_rows($result_pegawai) > 0){ ?>
	<table class="tbl" id="table">
		<thead>
			<tr>
				<th width="46">No</th>
				<th class="sort" onclick="change_urut('nip','<?=$urutan?>')">NIP <?=change_urut('nip',$urut,$urutan)?></th>
				<th onclick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></th>
				<th onclick="change_urut('tmplahir','<?=$urutan?>')">Tempat, Tanggal Lahir <?=change_urut('tmplahir',$urut,$urutan)?></th>
				<th onclick="change_urut('pinpegawai','<?=$urutan?>')">PIN <?=change_urut('pinpegawai',$urut,$urutan)?></th>
				<th onclick="change_urut('aktif','<?=$urutan?>')">Status <?=change_urut('aktif',$urut,$urutan)?></th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
		<?
		if ($page==0) $cnt = 1; else $cnt = (int)$page*(int)$varbaris+1;
		while ($row_pegawai = mysqli_fetch_array($result_pegawai)) { ?>
			<tr>
				<td><?=$cnt ?></td>
				<td class="mono"><?=$row_pegawai['nip'] ?></td>
				<td><strong><?=$row_pegawai['nama'] ?></strong></td>
				<td><?=$row_pegawai['tmplahir'] ?>, <?=format_tgl($row_pegawai['tgllahir']) ?></td>
				<td class="mono"><?=$row_pegawai['pinpegawai'] ?>&nbsp;
				<? if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
					<a href="JavaScript:gantipin('pinpegawai','<?=$row_pegawai['nip']?>')" title="Ganti PIN">&#10227;</a>
				<? } ?></td>
				<td>
				<? if (SI_USER_LEVEL() == $SI_USER_STAFF) { ?>
					<span class="badge <?= $row_pegawai['aktif']==1?'aktif':'nonaktif' ?>"><?= $row_pegawai['aktif']==1?'Aktif':'Nonaktif' ?></span>
				<? } else { ?>
					<a href="JavaScript:setaktif(<?=$row_pegawai['replid'] ?>, <?=$row_pegawai['aktif'] ?>)">
						<span class="badge <?= $row_pegawai['aktif']==1?'aktif':'nonaktif' ?>"><?= $row_pegawai['aktif']==1?'Aktif':'Nonaktif' ?></span>
					</a>
				<? } ?>
				</td>
				<td>
					<div class="act-icons">
						<a href="JavaScript:lihat(<?=$row_pegawai['replid'] ?>)" title="Detail">&#128065;</a>
						<? if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
						<a href="JavaScript:cetak_detail(<?=$row_pegawai['replid'] ?>)" title="Cetak Detail">&#128424;</a>
						<a href="JavaScript:edit(<?=$row_pegawai['replid'] ?>)" title="Ubah">&#128221;</a>
						<a href="JavaScript:hapus(<?=$row_pegawai['replid'] ?>)" title="Hapus">&#128465;</a>
						<? } ?>
					</div>
				</td>
			</tr>
		<? $cnt++; } CloseDb(); ?>
		</tbody>
	</table>
	<script language='JavaScript'>Tables('table', 1, 0);</script>

	<div class="pager">
		<div class="inf">Halaman
			<select name="hal" id="hal" onchange="change_hal()">
			<? for ($m=0; $m<$total; $m++) {?>
				<option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
			<? } ?>
			</select>
			dari <?=$total?> halaman &middot; <?=$jumlah?> pegawai
		</div>
		<div class="inf">Baris per halaman
			<select name="varbaris" id="varbaris" onchange="change_baris()">
			<? for ($m=10; $m<=100; $m=$m+10) {?>
				<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
			<? } ?>
			</select>
		</div>
	</div>

	<? } else { ?>
	<div class="empty">Tidak ditemukan data pegawai.
		<? if (SI_USER_LEVEL() != $SI_USER_STAFF ) { ?><br />Klik <a href="JavaScript:tambah()">di sini</a> untuk mengisi data baru.<? } ?>
	</div>
	<? } ?>

</div>
</body>
</html>
<script language="javascript">
	var spryselect1 = new Spry.Widget.ValidationSelect("bagian");
	var spryselect1 = new Spry.Widget.ValidationSelect("hal");
	var spryselect1 = new Spry.Widget.ValidationSelect("varbaris");
</script>