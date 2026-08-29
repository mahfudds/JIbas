<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Pelaporan - menu modern
 **[N]**/ ?>
<?
require_once('include/sessioninfo.php');
require_once('cek.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Pelaporan', '&#128196;', 'Penyusunan surat &amp; pelaporan');

menu_panel('all', array(
	array('href'=>'pelaporan/pengantar.php','label'=>'Kata Pengantar','desc'=>'Membuat kata pengantar surat','icon'=>'&#128196;','color'=>'#2f6bb5'),
	array('href'=>'pelaporan/lampiran.php','label'=>'Halaman Lampiran','desc'=>'Membuat halaman lampiran surat','icon'=>'&#128229;','color'=>'#0a8f61'),
	array('href'=>'pelaporan/penyusunan.php','label'=>'Menyusun Surat','desc'=>'Menyusun surat resmi','icon'=>'&#128221;','color'=>'#7a4fb5'),
), 'Pelaporan &amp; penyusunan surat.');
menu_page_end();
