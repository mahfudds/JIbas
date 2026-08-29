<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Ekspor Impor - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Ekspor &amp; Impor', '&#128229;', 'Ekspor &amp; impor data nilai');

menu_panel('all', array(
	array('href'=>'penilaian/expnilai.php','label'=>'Ekspor Form Nilai','desc'=>'Dokumen Excel berisi form pengisian data nilai per kelas','icon'=>'&#128228;','color'=>'#0a8f61'),
	array('href'=>'penilaian/impnilai.php','label'=>'Impor Nilai','desc'=>'Impor data nilai dari form Excel','icon'=>'&#128229;','color'=>'#2f6bb5'),
), 'Ekspor &amp; impor data nilai via file Excel.');
menu_page_end();
