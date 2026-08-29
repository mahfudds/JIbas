<?php
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Import CSV Siswa (per jenjang/rombel)
 **/
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

// CSV column order (must match template):
// nis,nama,nisn,nik,tmplahir,tgllahir,kelamin,alamatsiswa,hpsiswa,namaayah,namaibu,namawn,tahunmasuk,idangkatan,idkelas,agama,suku,status
$COLS = array('nis','nama','nisn','nik','tmplahir','tgllahir','kelamin','alamatsiswa','hpsiswa','namaayah','namaibu','namawn','tahunmasuk','idangkatan','idkelas','agama','suku','status');

function cv($v){ $v=trim((string)$v); $v=str_replace(array("'",'"'),'`',$v); return $v; }
function esc($v){ return addslashes($v); }
function norm_date($d){ $d=trim($d); if($d==='') return null; if(preg_match('/^\d{4}-\d{2}-\d{2}$/',$d)) return $d; if(preg_match('/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})$/',$d,$m)) return sprintf('%04d-%02d-%02d',$m[3],$m[2],$m[1]); return null; }
function randPin($len=5){ $s=''; for($i=0;$i<$len;$i++) $s.=random_int(0,9); return $s; }

// valid FK lookups
OpenDb();
$validAgama=array(); $ra=QueryDb("SELECT agama FROM jbsumum.agama"); while($r=mysqli_fetch_array($ra))$validAgama[]=$r['agama'];
$validSuku=array(); $rs=QueryDb("SELECT suku FROM jbsumum.suku"); while($r=mysqli_fetch_array($rs))$validSuku[]=$r['suku'];
$validStatus=array(); $rst=QueryDb("SELECT status FROM jbsakad.statussiswa"); while($r=mysqli_fetch_array($rst))$validStatus[]=$r['status'];
$validKelas=array(); $rk=QueryDb("SELECT replid FROM jbsakad.kelas"); while($r=mysqli_fetch_array($rk))$validKelas[]=$r['replid'];
$validAngkt=array(); $ra2=QueryDb("SELECT replid FROM jbsakad.angkatan"); while($r=mysqli_fetch_array($ra2))$validAngkt[]=$r['replid'];
CloseDb();
if(count($validAgama)===0)$validAgama=array('Islam','Katolik','Protestan','Hindu','Budha');
if(count($validSuku)===0)$validSuku=array('Jawa','Sunda','Minang');
if(count($validStatus)===0)$validStatus=array('Reguler','Eksklusif');

$ERROR_MSG=''; $SUCCESS_MSG=''; $imported=0; $skipped=0; $errors=array();

if (isset($_REQUEST['proses_import'])) {
	if (empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['error']!=UPLOAD_ERR_OK) {
		$ERROR_MSG="Tidak ada file CSV yang dipilih / gagal upload.";
	} else {
		$fh=fopen($_FILES['csvfile']['tmp_name'],'r');
		$bom=fread($fh,3); if($bom!==chr(0xEF).chr(0xBB).chr(0xBF)) fseek($fh,0);
		fgetcsv($fh); // header
		$lineNum=1; $kumplit=date('Y-m-d');
		while(($data=fgetcsv($fh))!==false){
			$lineNum++;
			if(count($data)<3) continue;
			$nonEmpty=false; foreach($data as $c) if(trim((string)$c)!==''){$nonEmpty=true;break;}
			if(!$nonEmpty) continue;
			$rec=array(); foreach($COLS as $i=>$col) $rec[$col]=isset($data[$i])?cv($data[$i]):'';

			$nis=$rec['nis']; $nama=$rec['nama'];
			if($nis===''||$nama===''){ $skipped++; $errors[]="Baris $lineNum: NIS/Nama kosong."; continue; }
			$idkelas=(int)$rec['idkelas'];
			$idangkatan=(int)$rec['idangkatan'];
			if(!in_array($idkelas,$validKelas)){ $skipped++; $errors[]="Baris $lineNum: idkelas $idkelas tidak valid."; continue; }
			if(!in_array($idangkatan,$validAngkt)){ $skipped++; $errors[]="Baris $lineNum: idangkatan $idangkatan tidak valid."; continue; }

			OpenDb();
			$rc=@mysqli_query($GLOBALS['mysqlconnection'],"SELECT nis FROM jbsakad.siswa WHERE nis='".esc($nis)."'");
			$dup=@mysqli_num_rows($rc)>0 && mysqli_errno($GLOBALS['mysqlconnection'])==0;
			CloseDb();
			if($dup){ $skipped++; $errors[]="Baris $lineNum: NIS '$nis' sudah ada."; continue; }

			$tgll=norm_date($rec['tgllahir']); if($tgll===null)$tgll='0000-00-00';
			$kel= strtolower($rec['kelamin'])==='p' ? 'p':'l';
			$agama=in_array($rec['agama'],$validAgama)?$rec['agama']:'Islam';
			$suku=in_array($rec['suku'],$validSuku)?$rec['suku']:'Jawa';
			$status=in_array($rec['status'],$validStatus)?$rec['status']:'Reguler';
			$tahunmasuk=(int)$rec['tahunmasuk']; if($tahunmasuk===0)$tahunmasuk=2026;
			$pin=randPin(); $pinO=randPin(); $pinOB=randPin();
			// alamatortu varchar(100) — truncate to fit (alamatsiswa keeps full 255)
			$alamatSiswa=esc($rec['alamatsiswa']);
			$alamatOrtu=mb_substr($rec['alamatsiswa'],0,90);

			OpenDb();
			@mysqli_query($GLOBALS['mysqlconnection'],"INSERT INTO jbsakad.siswa SET
			  nis='".esc($nis)."', nisn='".esc($rec['nisn'])."', nik='".esc($rec['nik'])."', nama='".esc($nama)."',
			  tahunmasuk=$tahunmasuk, idangkatan=$idangkatan, idkelas=$idkelas, agama='".esc($agama)."', suku='".esc($suku)."',
			  status='".esc($status)."', kondisi=NULL, kelamin='$kel', tmplahir='".esc($rec['tmplahir'])."', tgllahir='$tgll',
			  warga='WNI', alamatsiswa='$alamatSiswa', hpsiswa='".esc($rec['hpsiswa'])."',
			  namaayah='".esc($rec['namaayah'])."', namaibu='".esc($rec['namaibu'])."', wali='".esc($rec['namawn'])."',
			  alamatortu='$alamatOrtu', aktif=1, pinsiswa='$pin', pinortu='$pinO', pinortuibu='$pinOB'");
			$er=mysqli_errno($GLOBALS['mysqlconnection']);
			if($er==0){
				@mysqli_query($GLOBALS['mysqlconnection'],"INSERT INTO jbsakad.riwayatdeptsiswa SET nis='".esc($nis)."',departemen='MAN 4 NGAWI',mulai='$kumplit'");
				@mysqli_query($GLOBALS['mysqlconnection'],"INSERT INTO jbsakad.riwayatkelassiswa SET nis='".esc($nis)."',idkelas=$idkelas,mulai='$kumplit'");
				$imported++;
			} else {
				$skipped++;
				$errors[]="Baris $lineNum: NIS '$nis' gagal: ".mysqli_error($GLOBALS['mysqlconnection']);
			}
			CloseDb();
		}
		fclose($fh);
		$SUCCESS_MSG="Import selesai: $imported berhasil, $skipped dilewati.";
	}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title>Import CSV Siswa</title>
<style>
:root{--green:#1D4533;--green-hi:#2A5A45;--cream:#F7EAE0;--peach:#F9D2BA;--brown:#5E3122;--ink:#2A211B;--ink-mut:#6B5748;--line:#EADDD2}
*{box-sizing:border-box}
body{margin:0;padding:0;font-family:"Segoe UI",system-ui,sans-serif;background:#FFF;color:var(--ink)}
.hdr{background:var(--green);color:#F7EAE0;padding:14px 18px;font-weight:800}
.hdr a{color:#F9D2BA;font-size:12px;font-weight:600;float:right;text-decoration:none}
.wrap{padding:18px}
.card{background:var(--cream);border:1px solid var(--line);border-radius:12px;padding:16px;margin-bottom:16px}
.card h3{margin:0 0 10px;font-size:14px;color:var(--green)}
label{display:block;font-size:12px;font-weight:700;color:var(--ink-mut);margin:10px 0 5px}
input[type=file]{width:100%;padding:9px;border:1px solid #CBB9A9;border-radius:8px;font-size:13px}
.btn{display:inline-block;padding:10px 20px;border:none;border-radius:9px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;margin-top:6px}
.b-green{background:var(--green);color:#F7EAE0}
.b-peach{background:var(--peach);color:var(--brown)}
.b-close{background:#ccc;color:#333}
.note{font-size:11.5px;color:var(--ink-mut);margin-top:8px;line-height:1.5}
.ok{background:#E8F5EE;border:1px solid #BFE3D0;color:#1D4533;padding:10px 12px;border-radius:9px;font-weight:700;margin-bottom:14px}
.err{background:#FDE7E0;border:1px solid #F0B7A8;color:#A3351F;padding:10px 12px;border-radius:9px;font-weight:700;margin-bottom:14px}
.list{margin:6px 0 0;padding-left:18px;font-size:12px;color:var(--brown);max-height:200px;overflow:auto}
.list li{margin:2px 0}
</style>
</head>
<body>
<div class="hdr">Import CSV Siswa <a href="siswa.php" target="_parent">&#8592; Kembali</a></div>
<div class="wrap">
	<?php if($SUCCESS_MSG): ?><div class="ok">&#9989; <?=htmlspecialchars($SUCCESS_MSG)?></div><?php endif; ?>
	<?php if($ERROR_MSG): ?><div class="err">&#9888; <?=htmlspecialchars($ERROR_MSG)?></div><?php endif; ?>
	<?php if(count($errors)): ?>
	<div class="err">Baris yang dilewati:</div>
	<div class="card"><ul class="list"><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul></div>
	<?php endif; ?>

	<div class="card">
		<h3>1. Unduh Template</h3>
		<a class="btn b-peach" href="siswa_import_template.php">&#128229; Download Template CSV</a>
	</div>

	<div class="card">
		<h3>2. Upload File CSV</h3>
		<form method="post" enctype="multipart/form-data" action="siswa_import.php">
			<input type="hidden" name="proses_import" value="1" />
			<label>File CSV (delimiter koma)</label>
			<input type="file" name="csvfile" accept=".csv,text/csv" />
			<button type="submit" class="btn b-green">&#128228; Import Sekarang</button>
			<button type="button" class="btn b-close" onclick="window.close()">Tutup</button>
		</form>
		<div class="note">
			<strong>Urutan kolom:</strong> NIS, Nama, NISN, NIK, Tempat Lahir, Tanggal Lahir, Kelamin, Alamat, No HP, Nama Ayah, Nama Ibu, Nama Wali, Tahun Masuk, ID Angkatan, ID Kelas, Agama, Suku, Status.<br />
			ID Kelas/ID Angkatan diisi dari sistem; kolom lain boleh kosong. Baris dengan NIS sudah ada akan dilewati.
		</div>
	</div>
</div>
</body>
</html>
