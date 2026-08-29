<?php
/**[N]**
 * JIBAS Education Community
 * - Portal tiles loader: reads jbsakad.jbs_portal_app, falls back to defaults.
 * - Requires this file include db_functions.php + OpenDb() to be safe.
 **[N]**/ ?>
<?php
if (!function_exists('jbs_portal_tiles')) {

    // Default tiles when DB table is empty/unavailable (keeps portal usable).
    function jbs_portal_default_tiles() {
        return array(
            array('nama'=>'Akademik','deskripsi'=>'Kesiswaan, akademik & nilai','ikon'=>'🎓','warna'=>'#0a8f61','url'=>'akademik/','action'=>'akademik/redirect.php','aktif'=>1,'urutan'=>1),
            array('nama'=>'Keuangan','deskripsi'=>'SPP, pembayaran & laporan','ikon'=>'💰','warna'=>'#1896a8','url'=>'keuangan/rinjani/','action'=>'','aktif'=>1,'urutan'=>2),
            array('nama'=>'Kepegawaian','deskripsi'=>'Data & presensi pegawai','ikon'=>'🧑‍💼','warna'=>'#2f6bb5','url'=>'kepegawaian/','action'=>'kepegawaian/redirect.php','aktif'=>1,'urutan'=>3),
            array('nama'=>'Perpustakaan','deskripsi'=>'SIMTAKA katalog & peminjaman','ikon'=>'📚','warna'=>'#7a4fb5','url'=>'simtaka/','action'=>'simtaka/redirect.php','aktif'=>1,'urutan'=>4),
            array('nama'=>'Info Guru','deskripsi'=>'Informasi & SLB guru','ikon'=>'👩‍🏫','warna'=>'#c2701f','url'=>'infoguru/','action'=>'infoguru/redirect.php','aktif'=>1,'urutan'=>5),
            array('nama'=>'SMS Gateway','deskripsi'=>'Pesan & notifikasi','ikon'=>'📱','warna'=>'#b53f3f','url'=>'smsgateway/','action'=>'smsgateway/redirect.php','aktif'=>1,'urutan'=>6),
            array('nama'=>'E-Learning','deskripsi'=>'SchoolTube materi & video','ikon'=>'▶️','warna'=>'#c93f66','url'=>'schooltube/','action'=>'','aktif'=>1,'urutan'=>7),
            array('nama'=>'CBE','deskripsi'=>'Ujian berbasis komputer','ikon'=>'🖥️','warna'=>'#3f9db5','url'=>'cbe/','action'=>'','aktif'=>1,'urutan'=>8),
            array('nama'=>'Anjungan','deskripsi'=>'Kiosk informasi mandiri','ikon'=>'🏛️','warna'=>'#5b5f6b','url'=>'anjungan/index.php','action'=>'','aktif'=>1,'urutan'=>9)
        );
    }

    // Load active tiles from DB ordered by urutan.
    function jbs_portal_tiles() {
        $tiles = array();
        $ok = @OpenDb();
        if ($ok) {
            $rs = QueryDb("SELECT nama,deskripsi,ikon,warna,url,action FROM jbs_portal_app WHERE aktif=1 ORDER BY urutan,replid");
            while ($row = @mysqli_fetch_array($rs)) {
                $tiles[] = array(
                    'nama'=>$row['nama'], 'deskripsi'=>$row['deskripsi'], 'ikon'=>$row['ikon'],
                    'warna'=>$row['warna'], 'url'=>$row['url'], 'action'=>$row['action'], 'aktif'=>1
                );
            }
            CloseDb();
        }
        if (count($tiles) === 0) {
            foreach (jbs_portal_default_tiles() as $t) {
                unset($t['aktif'], $t['urutan']);
                $tiles[] = $t;
            }
        }
        return $tiles;
    }
}
