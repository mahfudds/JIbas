<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Jadwal & Kalender - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
$page = preg_replace('/[^a-z0-9_]/','', $_REQUEST['page'] ?? 'j');
$tabs = array(
	array('id'=>'j','label'=>'Jadwal','icon'=>'&#128197;'),
	array('id'=>'k','label'=>'Kalender Akademik','icon'=>'&#128337;'),
);
$active = in_array($page, array('j','k')) ? $page : 'j';

menu_page_start('Jadwal &amp; Kalender', '&#128197;', 'Penyusunan jadwal &amp; kalender akademik');

menu_tabs($tabs, $active);

menu_panel('j', array(
	array('href'=>'jadwal/jadwal_guru_main.php','label'=>'Jadwal Setiap Guru','desc'=>'Penyusunan jadwal mengajar guru','icon'=>'&#128197;','color'=>'#2f6bb5'),
	array('href'=>'jadwal/definisi_jam.php','label'=>'Definisi Jam Belajar','desc'=>'Pendefinisian jam belajar','icon'=>'&#128337;','color'=>'#c2701f'),
	array('href'=>'jadwal/rekap_jadwal_main.php','label'=>'Rekapitulasi Jadwal','desc'=>'Rekap jadwal pelajaran','icon'=>'&#128202;','color'=>'#0a8f61'),
	array('href'=>'jadwal/jadwal_kelas_main.php','label'=>'Jadwal Setiap Kelas','desc'=>'Penyusunan jadwal per kelas','icon'=>'&#128218;','color'=>'#7a4fb5'),
));

menu_panel('k', array(
	array('href'=>'jadwal/kalender_main.php','label'=>'Kalender Akademik','desc'=>'Pendataan kalender akademik','icon'=>'&#128197;','color'=>'#0a8f61'),
	array('href'=>'','label'=>'Tahun Ajaran','desc'=>'Kelola tahun ajaran via menu Referensi','icon'=>'&#128198;','color'=>'#8a6a12','alert'=>'Untuk mendata Tahun Ajaran, silakan masuk ke menu Tahun Ajaran di bagian Referensi'),
));

menu_page_end();
