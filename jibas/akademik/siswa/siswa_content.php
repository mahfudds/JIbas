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
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../include/exceldata.php');
require_once('../cek.php');

if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];
if (isset($_REQUEST['tahunajaran'])) 
	$tahunajaran = $_REQUEST['tahunajaran'];
if (isset($_REQUEST['tingkat']))
	$tingkat = $_REQUEST['tingkat'];
if (isset($_REQUEST['kelas']))
	$kelas = $_REQUEST['kelas'];
//$nis = $_REQUEST['nis'];
$varbaris=20;
if (isset($_REQUEST['varbaris']))
	$varbaris = $_REQUEST['varbaris'];
	
$page=0;
if (isset($_REQUEST['page']))
	$page = $_REQUEST['page'];

$hal=0;
if (isset($_REQUEST['hal']))
	$hal = $_REQUEST['hal'];

$urut = "nama";	
if (isset($_REQUEST['urut']))
	$urut = $_REQUEST['urut'];	
$urutan = "ASC";	
if (isset($_REQUEST['urutan']))
	$urutan = $_REQUEST['urutan'];
	
OpenDb();

// nama kelas + kapasitas utk header (default bila salah filter)
$nama_kelas = ""; $kapasitas = 0; $isi = 0;
if (isset($kelas) && $kelas != "") {
	$rk = QueryDb("SELECT kelas, kapasitas FROM kelas WHERE replid='$kelas'");
	$rowk = @mysqli_fetch_row($rk);
	if ($rowk) {
		$nama_kelas = $rowk[0];
		$kapasitas = (int)$rowk[1];
	}
	$rc2 = QueryDb("SELECT COUNT(*) FROM siswa WHERE idkelas='$kelas' AND aktif=1");
	$rowc = @mysqli_fetch_row($rc2);
	$isi = (int)$rowc[0];
}

$op = $_REQUEST['op'];
if ($op == "dw8dxn8w9ms8zs22") 
{
	$sql = "UPDATE siswa SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
} 
else if ($op == "xm8r389xemx23xb2378e23") 
{
    $success = true;
    BeginTrans();

    $sql = "SELECT nis FROM siswa WHERE replid = '$_REQUEST[replid]'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $nis = $row[0];

    $sql = "DELETE FROM tambahandatasiswa WHERE nis = '$nis'";
    QueryDbTrans($sql, $success);

    if ($success)
    {
        $sql = "DELETE FROM riwayatfoto WHERE nis = '$nis'";
        QueryDbTrans($sql, $success);
    }

    if ($success)
    {
        $sql = "DELETE FROM siswa WHERE replid = '$_REQUEST[replid]'";
        QueryDbTrans($sql, $success);
    }

	if ($success) 
	{
		$sql = "SELECT * FROM calonsiswa WHERE replidsiswa = '$_REQUEST[replid]'";
		$result = QueryDb($sql);
		if (mysqli_num_rows($result) > 0) 
		{
			$sql = "UPDATE calonsiswa SET replidsiswa = NULL WHERE replidsiswa = '$_REQUEST[replid]'";
            QueryDbTrans($sql, $success);
		}
	}

    if ($success)
	    CommitTrans();
    else
        RollbackTrans();
	
	if ($success) 
	{	?>
		<script>refresh();</script> 
<?	} 
}	
//OpenDb();

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pendataan Siswa — JIBAS SIMAKA</title>
<link rel="stylesheet" type="text/css" href="../style/menuui.css" />
<style>
:root{--green:#1D4533;--green-hi:#2A5A45;--cream:#F7EAE0;--peach:#F9D2BA;--peach-deep:#E0AA8C;--brown:#5E3122;--ink:#2A211B;--ink-mut:#6B5748;--line:#EADDD2}
.pg-head{display:flex;align-items:center;gap:14px;margin-bottom:16px;flex-wrap:wrap}
.pg-head .ico{width:54px;height:54px;border-radius:15px;background:linear-gradient(135deg,var(--green),var(--green-hi));color:var(--cream);display:flex;align-items:center;justify-content:center;font-size:26px;box-shadow:0 8px 18px rgba(29,69,51,.28)}
.pg-head h1{font-size:22px;font-weight:800;color:var(--green);margin:0}
.pg-head .bread{font-size:12px;color:var(--ink-mut);font-weight:700}
.pg-head .bread a{color:var(--green)}
.toolbar{display:flex;align-items:center;gap:10px;flex-wrap:wrap;background:var(--cream);border:1px solid var(--line);border-radius:12px;padding:12px 14px;margin-bottom:14px}
.toolbar .cap{font-size:13px;font-weight:800;color:var(--brown)}
.toolbar .cap small{color:var(--ink-mut);font-weight:700}
.toolbar .spacer{flex:1}
.btn-row{display:flex;gap:8px;flex-wrap:wrap}
.abtn{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;border:none;border-radius:8px;font-weight:700;font-size:12.5px;cursor:pointer;color:var(--green);background:#E9F1EC;text-decoration:none;transition:background .14s,transform .1s}
.abtn:hover{background:#DCE9E1;transform:translateY(-1px)}
.abtn b{font-size:14px}
.abtn.act{background:var(--green);color:#F7EAE0}
.abtn.warn{background:#FBE7D4;color:var(--brown)}
table.tbl{width:100%;border-collapse:collapse;background:#fff;border:1px solid var(--line);border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(94,49,34,.06)}
.tbl thead th{background:var(--green);color:#F7EAE0;padding:10px 12px;text-align:left;font-size:12.5px;font-weight:800;cursor:pointer;white-space:nowrap}
.tbl thead th:hover{background:var(--green-hi)}
.tbl tbody td{padding:9px 12px;border-top:1px solid var(--line);font-size:13px;color:var(--ink)}
.tbl tbody tr:nth-child(even){background:#FBF6F0}
.tbl tbody tr:hover{background:#F3EAE0}
.tbl .mono{font-family:Consolas,monospace;font-size:12px}
.badge{display:inline-block;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:800}
.badge.aktif{background:#E2F4EA;color:#0a8f61}
.badge.nonaktif{background:#F9E3E0;color:#B53F3F}
.badge.mutasi{background:#E8EFF7;color:#2f6bb5}
.act-icons{display:flex;gap:8px;align-items:center;white-space:nowrap}
.act-icons a{font-size:15px;text-decoration:none;cursor:pointer}
.pager{display:flex;align-items:center;justify-content:space-between;margin-top:14px;flex-wrap:wrap;gap:10px}
.pager .inf{font-size:12.5px;color:var(--ink-mut);font-weight:700}
.empty{background:#fff;border:1px dashed var(--line);border-radius:12px;padding:44px;text-align:center;color:var(--ink-mut);font-size:14px}
.empty a{color:var(--green);font-weight:800}
@media (max-width:640px){.tbl td:nth-child(4),.tbl th:nth-child(4){display:none}.pg-head h1{font-size:18px}}
</style>
<script language="JavaScript" src="../script/tooltips.js"></script>
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language="javascript">
function refresh() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen;
}
function tambah() {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	newWindow('siswa_add.php?departemen='+departemen+'&kelas='+kelas+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat, 'TambahSiswa','905','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function edit(replid, nis) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	newWindow('siswa_edit.php?replid='+replid+'&departemen='+departemen+'&tahunajaran='+tahunajaran+'&kelas='+kelas+'&tingkat='+tingkat, 'UbahSiswa','905','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function hapus(replid, nis) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var urut = document.getElementById('urut').value;
	var urutan = document.getElementById('urutan').value;
	if (confirm('Apakah anda yakin akan menghapus siswa ini?'))
		document.location.href = "siswa_content.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&nis="+nis+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}
function change_urut(urut,urutan) {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	if (urutan =="ASC"){ urutan="DESC" } else { urutan="ASC" }
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&urut="+urut+"&urutan="+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}
function cetak() {
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	var total=document.getElementById("total").value;
	newWindow('siswa_cetak.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat+'&kelas='+kelas+'&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function exel(){
	var departemen = document.getElementById('departemen').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var kelas = document.getElementById('kelas').value;
	var tingkat = document.getElementById('tingkat').value;
	newWindow('siswa_cetak_excel.php?departemen='+departemen+'&tahunajaran='+tahunajaran+'&tingkat='+tingkat+'&kelas='+kelas, 'CetakSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function tampil(replid) {
	newWindow('../library/detail_siswa.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}
function refresh_after_add(){
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
}
function setaktif(replid, aktif) {
	var msg; var newaktif;
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	if (aktif == 1) { msg = "Apakah anda yakin akan mengubah siswa ini menjadi TIDAK AKTIF?"; newaktif = 0; }
	else { msg = "Apakah anda yakin akan mengubah siswa ini menjadi AKTIF?"; newaktif = 1; }
	if (confirm(msg)) {
		document.location.href = "siswa_content.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
		parent.header.location.href = "siswa_header.php?tahunajaran="+tahunajaran+"&tingkat="+tingkat+"&departemen="+departemen+"&kelas="+kelas;
	}
}
function change_page(page) {
	var departemen = document.getElementById('departemen').value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href = "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&page="+page+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris+"&hal="+page;
}
function change_hal() {
	var departemen = document.getElementById("departemen").value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var hal = document.getElementById("hal").value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href="siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
function change_baris() {
	var departemen = document.getElementById("departemen").value;
	var kelas = document.getElementById('kelas').value;
	var tahunajaran = document.getElementById('tahunajaran').value;
	var tingkat = document.getElementById('tingkat').value;
	var varbaris=document.getElementById("varbaris").value;
	document.location.href= "siswa_content.php?tingkat="+tingkat+"&kelas="+kelas+"&tahunajaran="+tahunajaran+"&departemen="+departemen+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
}
</script>
</head>
<body topmargin="0" leftmargin="0">
<input type="hidden" name="departemen" id="departemen" value="<?=$departemen ?>" />
<input type="hidden" name="tahunajaran" id="tahunajaran" value="<?=$tahunajaran ?>" />
<input type="hidden" name="kelas" id="kelas" value="<?=$kelas ?>" />
<input type="hidden" name="tingkat" id="tingkat" value="<?=$tingkat ?>" />
<input type="hidden" name="urut" id="urut" value="<?=$urut ?>" />
<input type="hidden" name="urutan" id="urutan" value="<?=$urutan ?>" />

<div style="width:100%;margin:0;padding:22px 20px 40px">
	<div class="pg-head">
		<span class="ico">&#128106;</span>
		<div>
			<h1>Pendataan Siswa</h1>
			<div class="bread"><a href="siswa.php" target="content">Kesiswaan</a> &rsaquo; Pendataan Siswa</div>
		</div>
	</div>

	<div class="toolbar">
		<span class="cap"><?=$nama_kelas ?: 'Pilih kelas' ?> <small>(isi <?=$isi ?> / <?=$kapasitas ?> , kapasitas)</small></span>
		<div class="spacer"></div>
		<div class="btn-row">
			<a class="abtn act" href="JavaScript:tambah()"><b>&#10133;</b>Tambah Siswa</a>
			<a class="abtn" href="#" onclick="refresh()"><b>&#10227;</b>Refresh</a>
			<a class="abtn" href="JavaScript:exel()"><b>&#128196;</b>Excel</a>
			<a class="abtn" href="JavaScript:cetak()"><b>&#128424;</b>Cetak</a>
		</div>
	</div>

<?
	$sql_tot = "SELECT nis,nama,asalsekolah,tmplahir,tgllahir,s.aktif,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),s.replid,s.nisn FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.alumni=0 ORDER BY replid ";
	$result_tot = QueryDb($sql_tot);
	$total=ceil(mysqli_num_rows($result_tot)/(int)$varbaris);
	$jumlah = mysqli_num_rows($result_tot);
	$akhir = ceil($jumlah/5)*5;

	$sql = "SELECT nis,nama,asalsekolah,tmplahir,tgllahir,s.aktif,DAY(tgllahir),MONTH(tgllahir),YEAR(tgllahir),s.replid,s.statusmutasi,s.alumni,s.nisn FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tahunajaran t WHERE s.idkelas = '$kelas' AND k.idtahunajaran = '$tahunajaran' AND k.idtingkat = '$tingkat' AND s.idkelas = k.replid AND t.replid = k.idtahunajaran AND s.alumni=0 ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
	$result = QueryDb($sql);

	if (@mysqli_num_rows($result)>0){
		$sql_kapasitas = "SELECT kapasitas FROM kelas WHERE replid = '$kelas'";
		$result_kapasitas = QueryDb($sql_kapasitas);
		$row_kapasitas = mysqli_fetch_row($result_kapasitas);
		$kapasitas = $row_kapasitas[0];
		$sql_siswa = "SELECT COUNT(*) FROM siswa WHERE idkelas = '$kelas' AND aktif = 1";
		$result_siswa = QueryDb($sql_siswa);
		$row_siswa = mysqli_fetch_row($result_siswa);
		$isi = $row_siswa[0];
?>
	<input type="hidden" name="total" id="total" value="<?=$total?>"/>
	<input type="hidden" name="kapasitas" id="kapasitas" value="<?=$kapasitas?>"/>
	<input type="hidden" name="isi" id="isi" value="<?=$isi?>"/>

	<table class="tbl" id="table">
		<thead>
			<tr>
				<th width="46">No</th>
				<th class="sort" onclick="change_urut('nis','<?=$urutan?>')">NIS <?=change_urut('nis',$urut,$urutan)?></th>
				<th onclick="change_urut('nisn','<?=$urutan?>')">NISN <?=change_urut('nisn',$urut,$urutan)?></th>
				<th onclick="change_urut('nama','<?=$urutan?>')">Nama <?=change_urut('nama',$urut,$urutan)?></th>
				<th onclick="change_urut('asalsekolah','<?=$urutan?>')">Asal Sekolah <?=change_urut('asalsekolah',$urut,$urutan)?></th>
				<th onclick="change_urut('tgllahir','<?=$urutan?>')">Tempat, Tanggal Lahir <?=change_urut('tgllahir',$urut,$urutan)?></th>
				<th onclick="change_urut('aktif','<?=$urutan?>')">Status <?=change_urut('aktif',$urut,$urutan)?></th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody>
		<?
		CloseDb();
		if ($page==0){ $cnt = 1; } else { $cnt = (int)$page*(int)$varbaris+1; }
		while ($row = @mysqli_fetch_row($result)) {
		?>
		<tr>
			<td><?=$cnt?></td>
			<td class="mono"><?=$row[0]?></td>
			<td class="mono"><?=$row[12]?></td>
			<td><strong><?=$row[1]?></strong></td>
			<td><?=$row[2]?></td>
			<td><?=$row[3]?>, <?=$row[6]?>&nbsp;<?=NamaBulan($row[7])?>&nbsp;<?=$row[8]?></td>
			<td>
			<? if ($row[10] == 0) { ?>
				<? if ($row[5] == 1) { ?>
					<? if (SI_USER_LEVEL() == $SI_USER_STAFF) { ?>
						<span class="badge aktif">Aktif</span>
					<? } else { ?>
						<a href="JavaScript:setaktif(<?=$row[9]?>, <?=$row[5]?>)"><span class="badge aktif">Aktif</span></a>
					<? } ?>
				<? } else { ?>
					<? if (SI_USER_LEVEL() == $SI_USER_STAFF || $kapasitas <= $isi) { ?>
						<span class="badge nonaktif">Nonaktif</span>
					<? } else { ?>
						<a href="JavaScript:setaktif(<?=$row[9]?>, <?=$row[5]?>)"><span class="badge nonaktif">Nonaktif</span></a>
					<? } ?>
				<? } ?>
			<? } else { ?>
				<span class="badge <?= $row[5]==1 ? 'mutasi' : 'nonaktif' ?>"><?= $row[5]==1 ? 'Dimutasi' : 'Nonaktif' ?></span>
			<? } ?>
			</td>
			<td>
				<div class="act-icons">
					<a href="JavaScript:tampil(<?=$row[9]?>)" title="Detail">&#128065;</a>
					<a href="#" onClick="newWindow('siswa_cetak_detail.php?replid=<?=$row[9]?>', 'DetailSiswa','800','650','resizable=1,scrollbars=1,status=0,toolbar=0')" title="Cetak Detail">&#128424;</a>
					<a href="JavaScript:edit(<?=$row[9]?>)" title="Ubah">&#128221;</a>
					<? if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
					<a href="JavaScript:hapus(<?=$row[9]?>,'<?=$row[0]?>')" title="Hapus">&#128465;</a>
					<? } ?>
				</div>
			</td>
		</tr>
		<? $cnt++; } ?>
		</tbody>
	</table>
	<script language='JavaScript'>Tables('table', 1, 0);</script>

	<div class="pager">
		<div class="inf">Halaman
			<select name="hal" id="hal" onChange="change_hal()">
			<? for ($m=0; $m<$total; $m++) {?>
				<option value="<?=$m ?>" <?=IntIsSelected($hal,$m) ?>><?=$m+1 ?></option>
			<? } ?>
			</select>
			dari <?=$total?> halaman &middot; <?=$jumlah ?> siswa
		</div>
		<div class="inf">Baris per halaman
			<select name="varbaris" id="varbaris" onChange="change_baris()">
			<? for ($m=10; $m<=100; $m=$m+10) {?>
				<option value="<?=$m ?>" <?=IntIsSelected($varbaris,$m) ?>><?=$m ?></option>
			<? } ?>
			</select>
		</div>
	</div>

<? } else { ?>
	<div class="empty">Tidak ditemukan data siswa.
		<br />Klik <a href="JavaScript:tambah()">di sini</a> untuk mengisi data baru.
	</div>
<? } ?>
</div>
</body>
</html>
