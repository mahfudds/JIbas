<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Guru & Pelajaran - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
$page = preg_replace('/[^a-z0-9_]/','', $_REQUEST['page'] ?? 'p');
$tabs = array(
	array('id'=>'p','label'=>'Pelajari','icon'=>'&#128214;'),
	array('id'=>'g','label'=>'Guru','icon'=>'&#128104;'),
);
$active = in_array($page, array('p','g')) ? $page : 'p';

menu_page_start('Guru &amp; Pelajaran', '&#128104;', 'Pendataan pelajaran, guru &amp; penilaian');

menu_tabs($tabs, $active);

menu_panel('p', array(
	array('href'=>'guru/pelajaran.php','label'=>'Pendataan Pelajaran Wajib','desc'=>'Buat &amp; kelola daftar pelajaran wajib','icon'=>'&#128214;','color'=>'#c2701f'),
	array('href'=>'guru/rpp_main.php','label'=>'Rencana Program Pembelajaran','desc'=>'Pendataan RPP setiap pelajaran','icon'=>'&#128196;','color'=>'#2f6bb5'),
	array('href'=>'guru/jenis_pengujian.php','label'=>'Jenis Pengujian','desc'=>'Pendataan jenis pengujian','icon'=>'&#128203;','color'=>'#0a8f61'),
	array('href'=>'guru/perhitungan_rapor.php','label'=>'Aturan Perhitungan Nilai Rapor','desc'=>'Pendataan aturan perhitungan nilai rapor','icon'=>'&#129518;','color'=>'#7a4fb5'),
	array('href'=>'guru/aturannilai_main.php','label'=>'Aturan Grading Nilai','desc'=>'Pendataan aturan grading nilai','icon'=>'&#128202;','color'=>'#b53f3f'),
	array('href'=>'guru/aspeknilai.php','label'=>'Aspek Penilaian','desc'=>'Pendataan aspek penilaian','icon'=>'&#128200;','color'=>'#1896a8'),
	array('href'=>'guru/kelompokpelajaran.php','label'=>'Kelompok Pelajaran','desc'=>'Pendataan kelompok pelajaran','icon'=>'&#128203;','color'=>'#3f9db5'),
));

menu_panel('g', array(
	array('href'=>'guru/statusguru.php','label'=>'Status Guru','desc'=>'Pendataan status guru','icon'=>'&#9881;','color'=>'#2f6bb5'),
	array('href'=>'guru/guru_main.php','label'=>'Pendataan Guru','desc'=>'Pendataan data guru','icon'=>'&#128104;','color'=>'#0a8f61'),
	array('href'=>'','label'=>'Pendataan Pelajaran','desc'=>'Gunakan menu Pelajaran untuk mendata','icon'=>'&#128214;','color'=>'#8a6a12','alert'=>'Gunakan Pendataan Pelajaran di menu Pelajaran untuk mendata pelajaran'),
	array('href'=>'','label'=>'Pegawai','desc'=>'Gunakan menu Pegawai di Referensi','icon'=>'&#128188;','color'=>'#8a6a12','alert'=>'Gunakan menu Pegawai di bagian referensi untuk mendata pegawai'),
));

menu_page_end();
