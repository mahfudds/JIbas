<?
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Presensi - menu modern
 **[N]**/ ?>
<?
include('cek.php');
require_once('include/sessioninfo.php');
require_once('include/menuui.php');
?>
<?php
$page = preg_replace('/[^a-z0-9_]/','', $_REQUEST['page'] ?? 'pp');
$tabs = array(
	array('id'=>'pp','label'=>'Presensi Pelajaran','icon'=>'&#128275;'),
	array('id'=>'ph','label'=>'Presensi Harian','icon'=>'&#128197;'),
	array('id'=>'pk','label'=>'Presensi Kegiatan','icon'=>'&#128203;'),
);
$active = in_array($page, array('pp','ph','pk')) ? $page : 'pp';

menu_page_start('Presensi', '&#128203;', 'Presensi pelajaran, harian &amp; kegiatan');

menu_tabs($tabs, $active);

menu_panel('pp', array(
	array('href'=>'presensi/formpresensi_pelajaran.php','label'=>'Cetak Form Presensi Pelajaran','desc'=>'Cetak form presensi pelajaran','icon'=>'&#128424;','color'=>'#c2701f'),
	array('href'=>'presensi/presensi_main.php','label'=>'Pengisian Presensi Pelajaran','desc'=>'Isi data presensi setiap pelajaran','icon'=>'&#128203;','color'=>'#0a8f61'),
	array('href'=>'presensi/lap_siswa_main.php','label'=>'Laporan Presensi Siswa','desc'=>'Laporan presensi setiap siswa','icon'=>'&#128200;','color'=>'#2f6bb5'),
	array('href'=>'presensi/lap_kelas_main.php','label'=>'Laporan Presensi per Kelas','desc'=>'Laporan presensi siswa setiap kelas','icon'=>'&#128218;','color'=>'#7a4fb5'),
	array('href'=>'presensi/lap_pengajar_main.php','label'=>'Laporan Presensi Pengajar','desc'=>'Laporan presensi pengajar','icon'=>'&#128104;','color'=>'#b53f3f'),
	array('href'=>'presensi/lap_absen_main.php','label'=>'Laporan Siswa Tidak Hadir','desc'=>'Laporan data siswa yang tidak hadir','icon'=>'&#128683;','color'=>'#e0704f'),
	array('href'=>'presensi/lap_refleksi_main.php','label'=>'Laporan Refleksi Mengajar','desc'=>'Laporan refleksi mengajar','icon'=>'&#128172;','color'=>'#3f9db5'),
	array('href'=>'presensi/statistik_siswa_main.php','label'=>'Statistik Kehadiran Siswa','desc'=>'Statistik kehadiran setiap siswa','icon'=>'&#128202;','color'=>'#0a8f61'),
	array('href'=>'presensi/statistik_kelas_main.php','label'=>'Statistik Kehadiran per Kelas','desc'=>'Statistik kehadiran setiap kelas','icon'=>'&#128200;','color'=>'#7a4fb5'),
));

menu_panel('ph', array(
	array('href'=>'presensi/formpresensi_harian.php','label'=>'Cetak Form Presensi Harian','desc'=>'Cetak form presensi harian','icon'=>'&#128424;','color'=>'#c2701f'),
	array('href'=>'presensi/input_presensi_main.php','label'=>'Pengisian Presensi Harian','desc'=>'Isi presensi harian setiap pelajaran','icon'=>'&#128221;','color'=>'#0a8f61'),
	array('href'=>'presensi/lap_hariansiswa_main.php','label'=>'Laporan Presensi Harian Siswa','desc'=>'Laporan presensi harian setiap siswa','icon'=>'&#128200;','color'=>'#2f6bb5'),
	array('href'=>'presensi/lap_hariankelas_main.php','label'=>'Laporan Presensi Harian / Kelas','desc'=>'Laporan presensi harian setiap kelas','icon'=>'&#128218;','color'=>'#7a4fb5'),
	array('href'=>'presensi/lap_harianabsen_main.php','label'=>'Laporan Siswa Tidak Hadir Harian','desc'=>'Laporan harian data siswa yang tidak hadir','icon'=>'&#128683;','color'=>'#e0704f'),
	array('href'=>'presensi/statistik_hariansiswa_main.php','label'=>'Statistik Kehadiran Siswa','desc'=>'Statistik kehadiran siswa','icon'=>'&#128202;','color'=>'#0a8f61'),
	array('href'=>'presensi/statistik_hariankelas_main.php','label'=>'Statistik Kehadiran per Kelas','desc'=>'Statistik kehadiran siswa setiap kelas','icon'=>'&#128200;','color'=>'#7a4fb5'),
));

menu_panel('pk', array(
	array('href'=>'http://www.jibas.net/content/sptfgr/sptfgr.php','label'=>'Aplikasi SPT / Form Kegiatan','desc'=>'Buka aplikasi pendataan presensi sidik jari','icon'=>'&#128274;','color'=>'#b53f3f','target'=>'_blank'),
	array('href'=>'presensi/presensikeg.siswa2.php','label'=>'Presensi Kegiatan Siswa','desc'=>'Isi presensi kegiatan siswa','icon'=>'&#128101;','color'=>'#0a8f61'),
	array('href'=>'presensi/presensikeg.rekapsiswa.php','label'=>'Rekap Presensi Kegiatan Siswa','desc'=>'Rekapitulasi presensi kegiatan siswa','icon'=>'&#128200;','color'=>'#2f6bb5'),
	array('href'=>'presensi/presensikeg.guru.php','label'=>'Presensi Kegiatan Guru','desc'=>'Isi presensi kegiatan guru','icon'=>'&#128104;','color'=>'#7a4fb5'),
	array('href'=>'presensi/presensikeg.rekapguru.php','label'=>'Rekap Presensi Kegiatan Guru','desc'=>'Rekapitulasi presensi kegiatan guru','icon'=>'&#128200;','color'=>'#3f9db5'),
));

menu_page_end();
