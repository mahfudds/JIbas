<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Pengaturan - menu modern
 **[N]**/ ?>
<?
require_once('include/sessioninfo.php');
require_once('cek.php');
require_once('include/menuui.php');
?>
<?php
$restricted = (SI_USER_LEVEL() == 2);
$userItems = array();
if ($restricted) {
	$userItems[] = array('href'=>'','label'=>'Daftar Pengguna','desc'=>'Anda tidak berhak mengakses halaman ini','icon'=>'&#128101;','color'=>'#8a6a12','alert'=>'Maaf, Anda tidak berhak mengakses halaman ini !');
} else {
	$userItems[] = array('href'=>'user/user.php','label'=>'Daftar Pengguna','desc'=>'Kelola daftar pengguna sistem','icon'=>'&#128101;','color'=>'#2f6bb5');
}
?>

<?php
menu_page_start('Pengaturan', '&#9881;', 'Pengaturan sistem, portal halaman muka &amp; pengguna');

menu_panel('all', array_merge($userItems, array(
	array('href'=>"javascript:menuGanti()",'label'=>'Ganti Password','desc'=>'Ubah password pengguna','icon'=>'&#128273;','color'=>'#c2701f'),
	array('href'=>'referensi/auditnilai.php','label'=>'Audit Perubahan Nilai','desc'=>'Melihat log perubahan nilai','icon'=>'&#128196;','color'=>'#0a8f61'),
	($restricted
		? array('href'=>'','label'=>'Query Error Log','desc'=>'Anda tidak berhak mengakses halaman ini','icon'=>'&#128683;','color'=>'#8a6a12','alert'=>'Maaf, Anda tidak berhak mengakses halaman ini !')
		: array('href'=>'referensi/queryerror.php','label'=>'Query Error Log','desc'=>'Melihat log error query','icon'=>'&#128683;','color'=>'#b53f3f')),
	array('href'=>'referensi/portalapp.php','label'=>'Portal Aplikasi','desc'=>'Kelola tile aplikasi di halaman muka (tambah, ubah, hapus, urutkan, icon)','icon'=>'&#9881;','color'=>'#1D4533'),
	array('href'=>'referensi/portalsetting.php','label'=>'Tampilan Portal','desc'=>'Ubah teks, logo, motto &amp; warna tema halaman muka','icon'=>'&#128295;','color'=>'#5E3122'),
)), 'Pengaturan sistem, portal halaman muka &amp; pengguna.');

echo '<script>function menuGanti(){var w=window.open("user/user_ganti.php","GantiPasswordUser","width=500,height=280,resizable=1,scrollbars=1,status=0,toolbar=0");if(w)w.focus();}</script>';
menu_page_end();
