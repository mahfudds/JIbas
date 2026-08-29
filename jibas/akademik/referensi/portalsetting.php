<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Pengaturan Tampilan Portal (halaman muka)
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 * ... GPL header ...
**[N]**/
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

// Field definition: key => [label, kategori]
$FIELDS = array(
	'brand_logo'   => array('Logo (tombol teks cadangan)', 'Merek & Identitas'),
	'brand_eyebrow'=> array('Teks kecil atas nama', 'Merek & Identitas'),
	'brand_judul'  => array('Nama instansi (header & hero)', 'Merek & Identitas'),
	'brand_sub'    => array('Sub judul instansi', 'Merek & Identitas'),
	'topbar_nama'  => array('Nama aplikasi (topbar)', 'Bar Atas'),
	'topbar_law_text'=> array('Teks status layanan (topbar)', 'Bar Atas'),
	'topbar_web'   => array('Link web resmi', 'Bar Atas'),
	'topbar_bantuan'=> array('Label link bantuan', 'Bar Atas'),
	'hero_eyebrow' => array('Teks kecil hero', 'Hero'),
	'hero_judul'   => array('Judul besar hero', 'Hero'),
	'hero_lokasi'  => array('Lokasi / daerah', 'Hero'),
	'hero_motto'   => array('Motto (baris pakai | ; tebal pakai **teks**)', 'Hero'),
	'hero_btn1'    => array('Label tombol 1', 'Hero'),
	'hero_btn2'    => array('Label tombol 2', 'Hero'),
	'hero_btn2_url'=> array('URL tombol 2', 'Hero'),
	'stat1_label'  => array('Kartu statistik 1', 'Statistik'),
	'stat2_label'  => array('Kartu statistik 2', 'Statistik'),
	'stat3_label'  => array('Kartu statistik 3', 'Statistik'),
	'services_kicker'=> array('Teks kecil section layanan', 'Layanan'),
	'services_judul' => array('Judul section layanan', 'Layanan'),
	'services_teks'  => array('Deskripsi section layanan', 'Layanan'),
	'kontak1_label'=> array('Kontak 1 - label', 'Kontak'),
	'kontak1_value'=> array('Kontak 1 - isi', 'Kontak'),
	'kontak2_label'=> array('Kontak 2 - label', 'Kontak'),
	'kontak2_value'=> array('Kontak 2 - isi', 'Kontak'),
	'kontak3_label'=> array('Kontak 3 - label', 'Kontak'),
	'kontak3_value'=> array('Kontak 3 - isi', 'Kontak'),
	'kontak4_label'=> array('Kontak 4 - label', 'Kontak'),
	'kontak4_value'=> array('Kontak 4 - isi', 'Kontak'),
	'footer_teks'  => array('Nama sistem (footer)', 'Kaki Halaman'),
	'warna_green'  => array('Warna utama hijau', 'Tema Warna'),
	'warna_cream'  => array('Warna cream (latar)', 'Tema Warna'),
	'warna_peach'  => array('Warna peach (aksen)', 'Tema Warna'),
	'warna_brown'  => array('Warna coklat (teks)', 'Tema Warna'),
);

// Group order
$GROUPS = array('Merek & Identitas','Bar Atas','Hero','Statistik','Layanan','Kontak','Kaki Halaman','Tema Warna');

// Uploaded brand logo lives at web-accessible jibas/images/portal/brand_logo.png
// (from this file at akademik/referensi: up 2 levels = jibas/)
$LOGO_FILE = realpath(dirname(__FILE__) . '/../../images/portal') ?: (dirname(__FILE__) . '/../../images/portal');
$LOGO_FILE .= '/brand_logo.png';

OpenDb();
$current = array();
$rs = QueryDb("SELECT keyname, valuetext FROM jbs_portal_setting");
while ($r = mysqli_fetch_array($rs)) $current[$r['keyname']] = $r['valuetext'];
CloseDb();

// Save
if (isset($_REQUEST['submit_setting'])) {
	OpenDb();
	foreach ($FIELDS as $k => $info) {
		if ($k == 'brand_logo') continue; // logo handled via upload/text flag below
		$val = isset($_REQUEST[$k]) ? CQ($_REQUEST[$k]) : '';
		QueryDb("INSERT INTO jbs_portal_setting (keyname, valuetext) VALUES ('$k','$val') ON DUPLICATE KEY UPDATE valuetext='$val'");
	}

	// Logo mode: 'file' (uploaded PNG) or 'text' (fallback text).
	$logoMode = 'text';
	if (isset($_REQUEST['brand_logo_hapus'])) {
		@unlink($LOGO_FILE);
		$logoMode = 'text';
	} elseif (isset($_FILES['brand_logo_file']) && $_FILES['brand_logo_file']['error'] == UPLOAD_ERR_OK) {
		$mime = @finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['brand_logo_file']['tmp_name']);
		if ($mime == 'image/png') {
			@move_uploaded_file($_FILES['brand_logo_file']['tmp_name'], $LOGO_FILE);
			$logoMode = 'file';
		} else {
			$logoMode = isset($_REQUEST['brand_logo_mode']) ? $_REQUEST['brand_logo_mode'] : 'text';
		}
	} else {
		$logoMode = isset($_REQUEST['brand_logo_mode']) ? $_REQUEST['brand_logo_mode'] : 'text';
	}
	$brandLogoText = isset($_REQUEST['brand_logo']) ? CQ($_REQUEST['brand_logo']) : 'J';
	QueryDb("INSERT INTO jbs_portal_setting (keyname, valuetext) VALUES ('brand_logo','$brandLogoText') ON DUPLICATE KEY UPDATE valuetext='$brandLogoText'");
	QueryDb("INSERT INTO jbs_portal_setting (keyname, valuetext) VALUES ('brand_logo_type','$logoMode') ON DUPLICATE KEY UPDATE valuetext='$logoMode'");

	CloseDb();
	// Reload same frame (works inside frameset and as popup) and show result,
	// avoiding a blank page from relying on window.close()/opener.
	header("Location: portalsetting.php?msg=saved");
	exit;
}
$SAVED = isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'saved';

$SWATCHES = array('#1D4533','#F7EAE0','#F9D2BA','#5E3122');
$portalUrl = "http://$G_SERVER_ADDR/";

function pv($current, $k) {
	return htmlspecialchars($current[$k] ?? '');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<title>Pengaturan Portal Aplikasi</title>
<style type="text/css">
body{background:#fff;margin:0;padding:0;font-family:Verdana,Arial,sans-serif;color:#2a211b}
.hdr{background:#1D4533;color:#F7EAE0;padding:14px 18px;font-size:16px;font-weight:bold}
.hdr a{color:#F9D2BA;font-size:12px;font-weight:normal;float:right;text-decoration:none}
.wrap{padding:16px 18px}
.group{background:#FBF3EC;border:1px solid #EADDD2;border-radius:10px;margin-bottom:16px;padding:14px 16px}
.group h3{margin:0 0 12px;font-size:13px;color:#5E3122;border-bottom:1px dashed #D8C2B0;padding-bottom:8px;letter-spacing:.5px}
.row{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:4px}
.fld{flex:1;min-width:230px;margin-bottom:8px}
.fld label{display:block;font-size:11px;font-weight:bold;color:#6B5748;margin-bottom:4px}
.fld input{width:100%;padding:8px 10px;border:1px solid #CBB9A9;border-radius:7px;font-size:13px;box-sizing:border-box;background:#fff}
.fld input:focus{outline:none;border-color:#1D4533}
.swatches{display:flex;gap:6px;flex-wrap:wrap;margin-top:5px}
.swatches .sw{width:26px;height:26px;border-radius:6px;cursor:pointer;border:2px solid transparent}
.swatches .sw.sel{border-color:#5E3122}
.btns{margin-top:10px}
.btns input{padding:9px 24px;border:none;border-radius:7px;font-weight:bold;font-size:13px;cursor:pointer;margin-right:6px}
.b-save{background:#1D4533;color:#F7EAE0}
.b-view{background:#F9D2BA;color:#5E3122}
.b-close{background:#ccc;color:#333}
.preview{display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #EADDD2;border-radius:10px;padding:12px;margin-bottom:16px}
.okmsg{background:#E8F5EE;border:1px solid #BFE3D0;color:#1D4533;padding:10px 14px;border-radius:9px;font-weight:bold;font-size:13px;margin-bottom:14px}
.preview .lg{width:52px;height:52px;border-radius:12px;background:#1D4533;color:#F7EAE0;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800}
.preview .tx b{font-size:14px;color:#1D4533;display:block}
.preview .tx span{font-size:11.5px;color:#6B5748}
</style>
<script>
function updPreview(){
	var l=document.getElementById('brand_logo').value||'J';
	document.getElementById('pvLogo').textContent=l;
	document.getElementById('pvJudul').textContent=document.getElementById('brand_judul').value||'';
	document.getElementById('pvSub').textContent=document.getElementById('brand_sub').value||'';
}
function updPreviewLogo(id){
	var t=document.getElementById(id).value||'J';
	var el=document.getElementById('pvLogoText');
	if(el){el.textContent=t;}
	var el2=document.getElementById('pvLogo');
	if(el2){el2.textContent=t;}
}
function pickColor(inputId,el,color,sw){
	document.getElementById(inputId).value=color;
	document.getElementById('sw_'+inputId).style.background=color;
	var box=document.getElementById('box_'+inputId);
	if(box){box.style.background=color;}
	var sibs=el.parentNode.children;
	for(var i=0;i<sibs.length;i++){sibs[i].classList.remove('sel');}
	el.classList.add('sel');
}
</script>
</head>
<body>
<div class="hdr">Pengaturan Portal Aplikasi <a href="<?= htmlspecialchars($portalUrl) ?>" target="_blank">&#128279; Lihat Halaman Muka</a></div>
<div class="wrap">
<?php if ($SAVED): ?>
<div class="okmsg">&#9989; Pengaturan berhasil disimpan.</div>
<?php endif; ?>
<form method="post" action="portalsetting.php" enctype="multipart/form-data">
<input type="hidden" name="submit_setting" value="1" />

<div class="preview">
	<div class="lg" id="pvLogo"><?= pv($current,'brand_logo') ?: 'J' ?></div>
	<div class="tx"><b id="pvJudul"><?= pv($current,'brand_judul') ?></b><span id="pvSub"><?= pv($current,'brand_sub') ?></span></div>
</div>

<?php foreach ($GROUPS as $g): ?>
	<div class="group">
		<h3><?= htmlspecialchars($g) ?></h3>
		<div class="row">
			<?php foreach ($FIELDS as $k => $info): ?>
				<?php if ($info[1] != $g) continue; ?>
				<div class="fld">
					<label><?= htmlspecialchars($info[0]) ?></label>
						<?php if ($k == 'brand_logo'): ?>
						<?php $hasLogo = file_exists($LOGO_FILE); ?>
						<div class="logobox" style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
							<div class="lg" id="pvLogoThumb" style="width:48px;height:48px;background:<?= pv($current,'warna_green') ?: '#1D4533' ?>">
								<?php if ($hasLogo): ?><img src="../../../images/portal/brand_logo.png?<?= filemtime($LOGO_FILE) ?>" alt="logo" style="width:100%;height:100%;object-fit:contain;padding:6px;border-radius:12px" /><?php else: ?><span id="pvLogoText"><?= pv($current,'brand_logo') ?: 'J' ?></span><?php endif; ?>
							</div>
							<div style="font-size:11px;color:#6B5748"><b>Logo instansi (PNG)</b><br /><?= $hasLogo ? '&#10003; File terpasang' : 'Belum ada file' ?></div>
						</div>
						<input type="file" name="brand_logo_file" id="brand_logo_file" accept="image/png" style="width:100%" />
						<div class="hint" style="font-size:10.5px;color:#A08B7A;margin-top:4px">Khusus format PNG. Jika tidak diisi, memakai teks di bawah.</div>
						<input type="text" name="brand_logo" id="brand_logo" value="<?= pv($current,'brand_logo') ?>" placeholder="Teks cadangan / alt logo" onkeyup="updPreviewLogo('brand_logo')" oninput="updPreviewLogo('brand_logo')" style="margin-top:6px" />
						<?php if ($hasLogo): ?>
							<label style="display:block;font-size:11px;font-weight:bold;color:#A3351F;margin-top:6px"><input type="checkbox" name="brand_logo_hapus" value="1" /> Hapus logo yang terpasang</label>
						<?php endif; ?>
					<?php else: ?>
						<input type="text" name="<?= $k ?>" id="<?= $k ?>" value="<?= pv($current,$k) ?>" onkeyup="updPreview()" oninput="updPreview()" />
					<?php endif; ?>
					<?php if ($g == 'Tema Warna'): ?>
						<div class="swatches" id="box_<?= $k ?>">
							<?php foreach ($SWATCHES as $sw): ?>
								<span class="sw <?php if(($current[$k]??'')==$sw) echo 'sel'; ?>" style="background:<?= $sw ?>" onmouseover="pickColor('<?= $k ?>',this,'<?= $sw ?>','<?= $k ?>')" onclick="pickColor('<?= $k ?>',this,'<?= $sw ?>','<?= $k ?>')"></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endforeach; ?>

<div class="btns">
	<input type="submit" class="b-save" value="Simpan Pengaturan" />
	<input type="button" class="b-view" value="Lihat Halaman Muka" onclick="window.open('<?= htmlspecialchars($portalUrl) ?>','_blank')" />
	<input type="button" class="b-close" value="Tutup" onclick="window.close()" />
</div>
</form>
</div>
</body>
</html>
