<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Penilaian - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
menu_page_start('Penilaian', '&#128202;', 'Pendataan nilai, rapor &amp; penilaian');

menu_panel('all', array(
	array('href'=>'penilaian/formpenilaian.php','label'=>'Cetak Form Nilai','desc'=>'Cetak form-form penilaian','icon'=>'&#128424;','color'=>'#c2701f'),
	array('href'=>'penilaian/ujian_rpp_kelas.php','label'=>'Daftar Nilai RPP / Kelas','desc'=>'Daftar nilai RPP setiap kelas','icon'=>'&#128218;','color'=>'#2f6bb5'),
	array('href'=>'penilaian/ujian_rpp_siswa.php','label'=>'Daftar Nilai RPP / Siswa','desc'=>'Daftar nilai RPP setiap siswa','icon'=>'&#128106;','color'=>'#0a8f61'),
	array('href'=>'penilaian/lap_pelajaran_main.php','label'=>'Laporan Nilai Pelajaran','desc'=>'Laporan nilai pelajaran setiap siswa','icon'=>'&#128200;','color'=>'#7a4fb5'),
	array('href'=>'penilaian/lihat_nilai_pelajaran.php','label'=>'Nilai Pelajaran Siswa','desc'=>'Pendataan nilai pelajaran setiap siswa','icon'=>'&#128221;','color'=>'#1896a8'),
	array('href'=>'penilaian/rataus.main.php','label'=>'Rata-rata Nilai / US','desc'=>'Rata-rata nilai ujian sekolah','icon'=>'&#128202;','color'=>'#b53f3f'),
	array('href'=>'penilaian/lihat_penentuan.php','label'=>'Nilai Rapor Siswa','desc'=>'Pendataan nilai rapor setiap siswa','icon'=>'&#128196;','color'=>'#3f9db5'),
	array('href'=>'penilaian/komentar_main.php','label'=>'Komentar Rapor','desc'=>'Pendataan komentar rapor siswa','icon'=>'&#128172;','color'=>'#e0704f'),
	array('href'=>'penilaian/lap_rapor_main.php','label'=>'Laporan Akhir Hasil Belajar','desc'=>'Laporan akhir hasil belajar setiap siswa','icon'=>'&#127891;','color'=>'#0a8f61'),
	array('href'=>'penilaian/lap_legger.php','label'=>'Laporan Legger Nilai','desc'=>'Laporan legger nilai','icon'=>'&#128203;','color'=>'#7a4fb5'),
	array('href'=>'penilaian/legger.rapor.php','label'=>'Legger Nilai Rapor / Pelajaran','desc'=>'Legger nilai rapor per pelajaran','icon'=>'&#128200;','color'=>'#2f6bb5'),
	array('href'=>'penilaian/legger.kelas.php','label'=>'Legger Nilai Rapor / Kelas','desc'=>'Legger nilai rapor per kelas','icon'=>'&#128218;','color'=>'#c2701f'),
), 'Pendataan &amp; pelaporan nilai siswa.');
menu_page_end();
