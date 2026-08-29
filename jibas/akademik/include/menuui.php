<?php
/**[N]**
 * JIBAS Education Community
 * @version: 35.5 (August 10, 2026)
 * @notes: Modern menu UI helper.
 *
 * Usage:
 *   require_once('include/menuui.php');
 *   menu_page_start('TITLE', 'ICON', 'SUB');
 *   menu_tabs(array(
 *     array('id'=>'p','label'=>'Pelajari','icon'=>'X'),
 *     array('id'=>'g','label'=>'Guru','icon'=>'Y'),
 *   ));
 *   menu_panel('p', array( items... ));
 *   menu_panel('g', array( items... ));
 *   menu_page_end();
 *
 * Item shape: array('href'=>..., 'label'=>..., 'desc'=>..., 'icon'=>..., 'color'=>..., 'alert'=>...)
 * If 'alert' is set, the card is a disabled "info" tile that alerts on click.
 * If 'info' string is set on parent, renders an info note.
 **/
if (!function_exists('menu_page_start')) {

    function menu_page_start($title, $icon, $sub = '') {
        $crumb = '<a href="referensi.php">Akademik</a>';
        ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= htmlspecialchars($title) ?> — JIBAS SIMAKA</title>
<link rel="stylesheet" type="text/css" href="style/menuui.css" />
</head>
<body>
<div class="menu-head">
    <span class="mh-icon"><?= $icon ?></span>
    <div>
        <div class="mh-title"><?= htmlspecialchars($title) ?></div>
        <?php if ($sub): ?><div class="mh-sub"><?= htmlspecialchars($sub) ?></div><?php endif; ?>
    </div>
    <div class="mh-spacer"></div>
    <span class="crumb"><?= $crumb ?> &rsaquo; <?= htmlspecialchars($title) ?></span>
</div>
<?php
    }

    function menu_tabs($tabs, $active = null) {
        if ($active === null && count($tabs)) $active = $tabs[0]['id'];
        $GLOBALS['__menu_active'] = $active;
        echo '<div class="tabs">';
        foreach ($tabs as $t) {
            $act = $t['id'] === $active ? ' active' : '';
            echo '<div class="tab'.$act.'" data-panel="'.htmlspecialchars($t['id']).'" onclick="menuShow(this)"><span class="em">'.($t['icon'] ?? '').'</span>'.htmlspecialchars($t['label']).'</div>';
        }
        echo '</div>';
        echo '<script>window.__menuActive="'.htmlspecialchars($active).'";</script>';
    }

    function menu_panel($id, $items, $info = '') {
        $act = (isset($GLOBALS['__menu_active']) && $GLOBALS['__menu_active'] === $id) ? ' active' : '';
        if (!isset($GLOBALS['__firstPanel'])) { $GLOBALS['__firstPanel'] = $id; $act = ' active'; }
        echo '<div class="panel'.$act.'" id="panel-'.$id.'">';
        if ($info) echo '<div class="info">'.htmlspecialchars($info).'</div>';
        echo '<div class="grid">';
        foreach ($items as $it) {
            $label = htmlspecialchars($it['label']);
            $desc = htmlspecialchars($it['desc'] ?? '');
            $ico = $it['icon'] ?? '🧩';
            $color = htmlspecialchars($it['color'] ?? '#1D4533');
            if (!empty($it['alert'])) {
                echo '<div class="card disabled" style="--tc:'.$color.'" onclick="menuInfo(\''.addslashes($it['alert']).'\')">';
                echo '<span class="c-ico">'.$ico.'</span><span class="c-name">'.$label.'</span>';
                if ($desc) echo '<span class="c-desc">'.$desc.'</span>';
                echo '<span class="c-foot"><span class="c-star">&#9432; Hub. Referensi</span></span></div>';
            } else {
                $target = !empty($it['target']) ? ' target="_blank" rel="noopener"' : '';
                echo '<a class="card" style="--tc:'.$color.'" href="'.htmlspecialchars($it['href']).'"'.$target.'>';
                echo '<span class="c-ico">'.$ico.'</span><span class="c-name">'.$label.'</span>';
                if ($desc) echo '<span class="c-desc">'.$desc.'</span>';
                echo '<span class="c-foot"><span class="c-chev">&rsaquo;</span></span></a>';
            }
        }
        echo '</div></div>';
    }

    function menu_tabs_script() {
        ?>
<script>
function menuShow(el){
    document.querySelectorAll('.tab').forEach(function(t){t.classList.remove('active');});
    el.classList.add('active');
    var id = el.getAttribute('data-panel');
    document.querySelectorAll('.panel').forEach(function(p){p.classList.remove('active');});
    var target = document.getElementById('panel-'+id);
    if(target) target.classList.add('active');
}
function menuInfo(msg){
    if(msg) alert(msg);
}
document.addEventListener('DOMContentLoaded', function(){
    var w = window.__menuActive;
    if(w){
        var tab = document.querySelector('.tab[data-panel="'+w+'"]');
        if(tab) menuShow(tab);
    }
});
</script>
<?php
    }

    function menu_page_end() {
        menu_tabs_script();
        echo '</body></html>';
    }
}
