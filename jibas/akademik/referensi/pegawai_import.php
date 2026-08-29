<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Import CSV Pegawai
 **/
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

// Fixed column order (must match template). Zero-based indexes.
$COLS = array(
	'nip','nama','gelarawal','gelarakhir','panggilan','tmplahir','tgllahir','kelamin',
	'agama','suku','nikah','noid','alamat','telpon','handphone','email','bagian','keterangan'
);

// Normalize a raw value
function cv($v) {
	$v = trim((string)$v);
	$v = str_replace(array("'", '"'), "`", $v);
	return $v;
}

// Map Kelamin L/P -> l/p
function norm_kelamin($k) {
	$k = strtolower(trim($k));
	if ($k === 'l' || $k === 'laki' || $k === 'laki-laki' || $k === 'm') return 'l';
	if ($k === 'p' || $k === 'perempuan' || $k === 'f') return 'p';
	return 'l';
}

// Map Menikah
function norm_nikah($n) {
	$n = strtolower(trim($n));
	if ($n === 'menikah' || $n === 'sudah' || $n === 'y') return 'menikah';
	if ($n === 'belum' || $n === 'n') return 'belum';
	return 'tak_ada';
}

// Map Bagian to a valid value from jbssdm.bagianpegawai (fallback 'Akademik')
function norm_bagian($b, $validBagian) {
	$b = trim($b);
	if ($b !== '' && in_array($b, $validBagian)) return $b;
	return 'Akademik';
}

// Normalize date to YYYY-MM-DD; accept also DD-MM-YYYY / DD/MM/YYYY
function norm_date($d) {
	$d = trim($d);
	if ($d === '') return null;
	// already YYYY-MM-DD
	if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)) return $d;
	// DD-MM-YYYY or DD/MM/YYYY
	if (preg_match('/^(\d{1,2})[-\\/](\d{1,2})[-\\/](\d{4})$/', $d, $m)) {
		return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
	}
	return null;
}

// Get valid bagian list
OpenDb();
$validBagian = array();
$rb = QueryDb("SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan");
while ($rowb = mysqli_fetch_array($rb)) $validBagian[] = $rowb['bagian'];
$validAgama = array();
$ra = QueryDb("SELECT agama FROM jbsumum.agama ORDER BY urutan");
while ($rowa = mysqli_fetch_array($ra)) $validAgama[] = $rowa['agama'];
$validSuku = array();
$rs = QueryDb("SELECT suku FROM jbsumum.suku ORDER BY urutan");
while ($rows = mysqli_fetch_array($rs)) $validSuku[] = $rows['suku'];
CloseDb();
if (count($validBagian) === 0) $validBagian = array('Akademik','Non Akademik');
if (count($validAgama) === 0) $validAgama = array('Islam','Katolik','Protestan','Hindu','Budha');
if (count($validSuku) === 0) $validSuku = array('Jawa','Sunda','Minang');

function randPin($len = 5) {
	$s = '';
	for ($i = 0; $i < $len; $i++) $s .= random_int(0, 9);
	return $s;
}

$ERROR_MSG = '';
$SUCCESS_MSG = '';
$imported = 0; $skipped = 0; $errors = array();

if (isset($_REQUEST['proses_import'])) {
	if (empty($_FILES['csvfile']['tmp_name']) || $_FILES['csvfile']['error'] != UPLOAD_ERR_OK) {
		$ERROR_MSG = "Tidak ada file CSV yang dipilih / gagal upload.";
	} else {
		$tmp = $_FILES['csvfile']['tmp_name'];
		$fh = fopen($tmp, 'r');
		// Skip UTF-8 BOM if present
		$bom = fread($fh, 3);
		if ($bom !== "\xEF\xBB\xBF") fseek($fh, 0);

		$firstRow = fgetcsv($fh);
		$lineNum = 1; // point to header consumed
		// Treat first row as header always (template has header). Skip.
		while (($data = fgetcsv($fh)) !== false) {
			$lineNum++;
			if (count($data) < 2) continue;
			// Skip fully blank rows
			$nonEmpty = false;
			foreach ($data as $cell) if (trim((string)$cell) !== '') { $nonEmpty = true; break; }
			if (!$nonEmpty) continue;

			$rec = array();
			foreach ($COLS as $i => $col) $rec[$col] = isset($data[$i]) ? cv($data[$i]) : '';

			$nip = $rec['nip'];
			$nama = $rec['nama'];

			if ($nip === '' || $nama === '') {
				$skipped++;
				$errors[] = "Baris $lineNum: NIP/Nama kosong, dilewati.";
				continue;
			}

			// duplicate check
			OpenDb();
			$rc = QueryDb("SELECT nip FROM jbssdm.pegawai WHERE nip = '$nip'");
			$dup = @mysqli_num_rows($rc) > 0;
			CloseDb();
			if ($dup) {
				$skipped++;
				$errors[] = "Baris $lineNum: NIP '$nip' sudah ada, dilewati.";
				continue;
			}

			$tgllahir = norm_date($rec['tgllahir']);
			if ($tgllahir === null) { $tgllahir = '0000-00-00'; }

			$pin = randPin();
			$kelamin = norm_kelamin($rec['kelamin']);
			$nikah = norm_nikah($rec['nikah']);
			$bagian = norm_bagian($rec['bagian'], $validBagian);

			// agama & suku are FK-linked; empty/invalid would violate constraint.
			$agama = trim($rec['agama']);
			if ($agama === '' || !in_array($agama, $validAgama)) $agama = 'Islam';
			$suku = trim($rec['suku']);
			if ($suku === '' || !in_array($suku, $validSuku)) $suku = 'Jawa';

			OpenDb();
			$sql = "INSERT INTO jbssdm.pegawai SET
					nip='$nip', nama='{$rec['nama']}', gelarawal='{$rec['gelarawal']}', gelarakhir='{$rec['gelarakhir']}',
					panggilan='{$rec['panggilan']}', tmplahir='{$rec['tmplahir']}', tgllahir='$tgllahir',
					agama='$agama', suku='$suku', nikah='$nikah', noid='{$rec['noid']}',
					alamat='{$rec['alamat']}', telpon='{$rec['telpon']}', handphone='{$rec['handphone']}',
					email='{$rec['email']}', bagian='$bagian', keterangan='{$rec['keterangan']}',
					kelamin='$kelamin', aktif='1', pinpegawai='$pin'";
			$res = QueryDb($sql);
			CloseDb();
			if ($res) $imported++; else { $skipped++; $errors[] = "Baris $lineNum: gagal insert NIP '$nip'."; }
		}
		fclose($fh);
		$SUCCESS_MSG = "Import selesai: $imported berhasil, $skipped dilewati.";
	}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title>Import CSV Pegawai</title>
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
input[type=file],input[type=text]{width:100%;padding:9px;border:1px solid #CBB9A9;border-radius:8px;font-size:13px}
.btn{display:inline-block;padding:10px 20px;border:none;border-radius:9px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;margin-top:6px}
.b-green{background:var(--green);color:#F7EAE0}
.b-peach{background:var(--peach);color:var(--brown)}
.b-close{background:#ccc;color:#333}
.note{font-size:11.5px;color:var(--ink-mut);margin-top:8px;line-height:1.5}
.ok{background:#E8F5EE;border:1px solid #BFE3D0;color:#1D4533;padding:10px 12px;border-radius:9px;font-weight:700;margin-bottom:14px}
.err{background:#FDE7E0;border:1px solid #F0B7A8;color:#A3351F;padding:10px 12px;border-radius:9px;font-weight:700;margin-bottom:14px}
.list{margin:6px 0 0;padding-left:18px;font-size:12px;color:var(--brown);max-height:200px;overflow:auto}
.list li{margin:2px 0}
.cols{font-size:12px;color:var(--ink-mut);line-height:1.6;background:#FFF;border:1px solid var(--line);border-radius:8px;padding:10px 12px}
</style>
</head>
<body>
<div class="hdr">Import CSV Pegawai <a href="pegawai.php" target="_parent">&#8592; Kembali</a></div>
<div class="wrap">
	<?php if ($SUCCESS_MSG): ?><div class="ok">&#9989; <?= htmlspecialchars($SUCCESS_MSG) ?></div><?php endif; ?>
	<?php if ($ERROR_MSG): ?><div class="err">&#9888; <?= htmlspecialchars($ERROR_MSG) ?></div><?php endif; ?>
	<?php if (count($errors)): ?>
	<div class="err">Baris yang dilewati:</div>
	<div class="card"><ul class="list"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
	<?php endif; ?>

	<div class="card">
		<h3>1. Unduh Template</h3>
		<p style="font-size:12.5px;color:var(--ink-mut);margin:0 0 8px">Gunakan template CSV berikut sebagai acuan format kolom (header wajib dipertahankan, baris contoh dihapus).</p>
		<a class="btn b-peach" href="pegawai_import_template.php">&#128229; Download Template CSV</a>
	</div>

	<div class="card">
		<h3>2. Upload File CSV</h3>
		<form method="post" enctype="multipart/form-data" action="pegawai_import.php">
			<input type="hidden" name="proses_import" value="1" />
			<label>File CSV (max 2MB, delimiter koma)</label>
			<input type="file" name="csvfile" id="csvfile" accept=".csv,text/csv" />
			<button type="submit" class="btn b-green">&#128228; Import Sekarang</button>
			<button type="button" class="btn b-close" onclick="window.close()">Tutup</button>
		</form>
		<div class="note">
			<strong>Urutan kolom:</strong> NIP, Nama, Gelar Awal, Gelar Akhir, Panggilan, Tempat Lahir,
			Tanggal Lahir, Kelamin, Agama, Suku, Menikah, No Identitas, Alamat, Telepon, Handphone, Email, Bagian, Keterangan.<br />
			<strong>Aturan:</strong> Tanggal = <em>YYYY-MM-DD</em> (atau DD-MM-YYYY); Kelamin = <em>L/P</em>; Menikah = <em>menikah/belum/tak_ada</em>; Bagian harus sesuai daftar bagian. Baris dengan NIP sudah ada akan dilewati.
		</div>
	</div>
</div>
</body>
</html>
