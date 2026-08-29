<?php
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Template CSV Import Siswa
 **/
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../cek.php');

$cols = array('NIS','Nama','NISN','NIK','Tempat Lahir','Tanggal Lahir','Kelamin','Alamat','No HP','Nama Ayah','Nama Ibu','Nama Wali','Tahun Masuk','ID Angkatan','ID Kelas','Agama','Suku','Status');
$example = array('202600001','Contoh Nama Siswa','0112345678','3521010101010001','NGAWI','2010-01-01','L','Jl. Contoh No. 1','62812345678','Nama Ayah','Nama Ibu','Nama Wali','2026','22','47','Islam','Jawa','Reguler');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=template_import_siswa.csv');
header('Expires: 0');
header('Cache-Control: no-cache');

$out=fopen('php://output','w');
fwrite($out,"\xEF\xBB\xBF");
fputcsv($out,$cols);
fputcsv($out,$example);
fclose($out);
exit;
