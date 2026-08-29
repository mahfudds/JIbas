<?php
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Template CSV Import Pegawai (fixed columns + example row)
 **/
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../cek.php');

$cols = array('NIP','Nama','Gelar Awal','Gelar Akhir','Panggilan','Tempat Lahir','Tanggal Lahir','Kelamin','Agama','Suku','Menikah','No Identitas','Alamat','Telepon','Handphone','Email','Bagian','Keterangan');
// Example data row (Tanggal Lahir format: YYYY-MM-DD; Kelamin: L/P; Menikah: menikah/belum/tak_ada)
$example = array('198512102012121001','Budi Santoso','Dr.','M.Pd.','Budi','Jakarta','1985-12-10','L','Islam','Jawa','menikah','3174508512100001','Jl. Merdeka No. 1','021123456','08123456789','budi@email.com','Akademik','Contoh baris, hapus sebelum import');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=template_import_pegawai.csv');
header('Expires: 0');
header('Cache-Control: no-cache');

$out = fopen('php://output', 'w');
// BOM for Excel
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out, $cols);
fputcsv($out, $example);
fclose($out);
exit;
