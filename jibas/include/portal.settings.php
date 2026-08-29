<?php
/**[N]**
 * JIBAS Education Community
 * - Portal text/theme settings loader (reads jbsakad.jbs_portal_setting).
 * - ps('<key>') returns value; falls back to defaults if missing/empty.
 **/
if (!function_exists('jbs_portal_settings')) {

    // Built-in defaults (used when the DB table is empty or key missing).
    function jbs_portal_default_settings() {
        return array(
            'topbar_nama'=>'JIBAS','topbar_bantuan'=>'Bantuan','topbar_law_text'=>'Portal Layanan Resmi','topbar_web'=>'www.jibas.net',
            'brand_logo'=>'J','brand_eyebrow'=>'Portal Layanan Resmi','brand_judul'=>'SEKOLAH PENDIDIKAN INDONESIA','brand_sub'=>'YAYASAN PENDIDIKAN INDONESIA',
            'hero_eyebrow'=>'Sistem Informasi Sekolah — SISFO','hero_judul'=>'SEKOLAH PENDIDIKAN INDONESIA','hero_lokasi'=>'Bandung',
            'hero_motto'=>'Integrasi, Transparan, dan **Amanah**.|Satu aplikasi untuk seluruh unit kerja sekolah.',
            'hero_btn1'=>'Mulai Layanan','hero_btn2'=>'Web JIBAS','hero_btn2_url'=>'https://www.jibas.net',
            'stat1_label'=>'Aplikasi & Layanan','stat2_label'=>'Tahun Pendataan','stat3_label'=>'Versi Sistem',
            'services_kicker'=>'Layanan & Informasi','services_judul'=>'Pilih aplikasi yang Anda butuhkan','services_teks'=>'Akses seluruh modul sistem informasi sekolah dalam satu portal.',
            'kontak1_label'=>'Instansi','kontak1_value'=>'YAYASAN PENDIDIKAN INDONESIA',
            'kontak2_label'=>'Lokasi','kontak2_value'=>'Bandung',
            'kontak3_label'=>'Mulai Pendataan','kontak3_value'=>'2011',
            'kontak4_label'=>'Dukungan','kontak4_value'=>'www.jibas.net',
            'footer_teks'=>'JIBAS',
            'warna_green'=>'#1D4533','warna_cream'=>'#F7EAE0','warna_peach'=>'#F9D2BA','warna_brown'=>'#5E3122'
        );
    }

    $GLOBALS['JBS_PS'] = null;

    // Load all settings from DB into $GLOBALS['JBS_PS'] merged over defaults.
    function jbs_portal_settings_load() {
        if ($GLOBALS['JBS_PS'] !== null) return;
        $GLOBALS['JBS_PS'] = jbs_portal_default_settings();
        if (@OpenDb()) {
            $rs = QueryDb("SELECT keyname, valuetext FROM jbs_portal_setting");
            while ($row = @mysqli_fetch_array($rs)) {
                $GLOBALS['JBS_PS'][$row['keyname']] = $row['valuetext'];
            }
            CloseDb();
        }
    }

    // Get a portal setting value.
    function ps($key, $default = null) {
        jbs_portal_settings_load();
        $v = $GLOBALS['JBS_PS'][$key] ?? ($default ?? '');
        return ($v === '' && $default !== null) ? $default : $v;
    }

    // Explicitly force settings to be loaded (call once at boot if needed).
    function ps_init() {
        jbs_portal_settings_load();
    }

    // Split hero motto on '|'; each line gets <strong>**...**</strong> rendered.
    // Returns e.g. "line1<br />line2" with markup.
    function ps_motto_html($key) {
        $raw = ps($key, '');
        $lines = explode('|', $raw);
        $out = array();
        foreach ($lines as $line) {
            $line = htmlspecialchars($line);
            // convert **bold** to <strong>
            $line = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $line);
            $out[] = $line;
        }
        return implode('<br />', $out);
    }
}
