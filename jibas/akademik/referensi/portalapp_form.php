<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
 * @notes: Form tambah/ubah Portal Aplikasi
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 ... GPL header ...
**[N]**/ ?>
<?
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../cek.php');

$replid = (int)($_REQUEST['replid'] ?? 0);
$b = array('nama'=>'','deskripsi'=>'','ikon'=>'','warna'=>'#0a8f61','url'=>'','action'=>'');

if ($replid > 0) {
	OpenDb();
	$rs = QueryDb("SELECT * FROM jbs_portal_app WHERE replid='$replid'");
	$row = mysqli_fetch_array($rs);
	CloseDb();
	if ($row) {
		$b['nama'] = $row['nama'];
		$b['deskripsi'] = $row['deskripsi'];
		$b['ikon'] = $row['ikon'];
		$b['warna'] = $row['warna'];
		$b['url'] = $row['url'];
		$b['action'] = $row['action'];
	}
}

$EMOJIS = array('🎓','💰','🧑‍💼','📚','👩‍🏫','📱','▶️','🖥️','🏛️','🏫','📝','📊','📅','🕒','👥','🧪','🎨','📋','🗂️','🖨️','📞','✉️','🔔','🗓️','🏢','📖','💳','📈','⚽','🚌','🍽️','��');
$COLORS = array('#0a8f61','#0bbf7e','#1896a8','#2f6bb5','#7a4fb5','#c2701f','#b53f3f','#c93f66','#3f9db5','#5b5f6b');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS [<?=$replid?'Ubah':'Tambah'?> Portal Aplikasi]</title>
<style type="text/css">
body{font-family:Verdana,Arial,sans-serif;background:#dcdfc4;margin:0}
.frm{margin:14px 16px}
.frm label{display:block;font-size:11px;font-weight:bold;color:#333;margin:10px 0 4px}
.frm input[type=text],.frm input[type=url]{width:100%;padding:7px;border:1px solid #999;border-radius:4px;font-size:12px;box-sizing:border-box}
.preview{display:flex;align-items:center;gap:12px;margin:14px 0}
.prv{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;box-shadow:0 6px 14px rgba(0,0,0,.15)}
.prv-name{font-weight:bold;font-size:13px}
.prv-sub{font-size:11px;color:#777}
.emoji-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:4px;margin-top:6px}
.emoji-grid span{font-size:20px;text-align:center;cursor:pointer;padding:3px;border-radius:6px}
.emoji-grid span:hover{background:#e5e8ee}
.emoji-grid span.sel{background:#d3ede1}
.color-grid{display:flex;gap:6px;margin-top:6px;flex-wrap:wrap}
.color-grid .sw{width:30px;height:30px;border-radius:8px;cursor:pointer;border:2px solid transparent}
.color-grid .sw.sel{border-color:#333}
.color-grid .sw:hover{border-color:#888}
.btns{margin:18px 0 10px;text-align:center}
.btns input{padding:8px 22px;border:none;border-radius:6px;font-weight:bold;font-size:13px;cursor:pointer;margin:0 4px}
.b-save{background:#0a8f61;color:#fff}
.b-close{background:#b0b7c2;color:#fff}
.hint{font-size:11px;color:#777;margin-top:4px}
</style>
<script>
var selEmoji = "<?=$b['ikon']?>";
var selColor = "<?=$b['warna']?>";
function pickEmoji(e,o){ selEmoji=o; document.getElementById('ikon').value=o; document.getElementById('prvIkon').textContent=o;
  var els=document.querySelectorAll('.emoji-grid span'); els.forEach(function(s){s.classList.remove('sel');}); e.classList.add('sel'); }
function pickColor(o){ selColor=o; document.getElementById('warna').value=o; var c=document.getElementById('prvIkon'); c.style.background=o;
  var els=document.querySelectorAll('.color-grid .sw'); els.forEach(function(s){s.classList.remove('sel');});
  event.currentTarget.classList.add('sel'); }
function updPrv(){ var n=document.getElementById('nama').value; document.getElementById('prvName').textContent=n||'Nama Aplikasi';
  var d=document.getElementById('deskripsi').value; document.getElementById('prvSub').textContent=d||'Deskripsi'; }
function validate(){
  if(document.getElementById('nama').value.length==0){ alert('Nama aplikasi wajib diisi.'); document.getElementById('nama').focus(); return false; }
  if(document.getElementById('url').value.length==0){ alert('URL aplikasi wajib diisi.'); document.getElementById('url').focus(); return false; }
  return true;
}
</script>
</head>
<body>
<form name="main" method="post" action="portalapp.php" onsubmit="return validate()">
<input type="hidden" name="replid" value="<?=$replid?>" />
<input type="hidden" name="submit_save" value="1" />
<div class="frm">
  <div class="preview">
    <div class="prv" id="prvIkon" style="background:<?=$b['warna']?>"><?=$b['ikon']?></div>
    <div>
      <div class="prv-name" id="prvName"><?=htmlspecialchars($b['nama'])?></div>
      <div class="prv-sub" id="prvSub"><?=htmlspecialchars($b['deskripsi'])?></div>
    </div>
  </div>

  <label>Nama Aplikasi *</label>
  <input type="text" name="nama" id="nama" value="<?=htmlspecialchars($b['nama'])?>" maxlength="100" onkeyup="updPrv()" />
  <div class="hint">Muncul di tile halaman muka.</div>

  <label>Deskripsi</label>
  <input type="text" name="deskripsi" id="deskripsi" value="<?=htmlspecialchars($b['deskripsi'])?>" maxlength="255" onkeyup="updPrv()" />

  <label>Ikon</label>
  <input type="hidden" name="ikon" id="ikon" value="<?=$b['ikon']?>" />
  <div class="emoji-grid" id="emojiGrid">
    <? foreach ($EMOJIS as $e) { ?>
    <span class="<?=$e==$b['ikon']?'sel':''?>" onclick="pickEmoji(this,'<?=$e?>')"><?=$e?></span>
    <? } ?>
  </div>

  <label>Warna</label>
  <input type="hidden" name="warna" id="warna" value="<?=$b['warna']?>" />
  <div class="color-grid" id="colorGrid">
    <? foreach ($COLORS as $c) { ?>
    <div class="sw <?=$c==$b['warna']?'sel':''?>" style="background:<?=$c?>" onclick="pickColor('<?=$c?>')"></div>
    <? } ?>
  </div>

  <label>URL Aplikasi *</label>
  <input type="text" name="url" id="url" value="<?=htmlspecialchars($b['url'])?>" maxlength="255" placeholder="mis. akademik/ atau keuangan/rinjani/" />

  <label>Login Modal (redirect.php, opsional)</label>
  <input type="text" name="loginurl" id="loginurl" value="<?=htmlspecialchars($b['action'])?>" maxlength="255" placeholder="mis. akademik/redirect.php (kosongkan bila buka langsung)" />
  <div class="hint">Jika diisi, aplikasi dibuka lewat login modal; jika kosong, langsung dibuka.</div>

  <div class="btns">
    <input type="submit" class="b-save" value="Simpan" />
    <input type="button" class="b-close" value="Tutup" onclick="window.close()" />
  </div>
</div>
</form>
</body>
</html>
