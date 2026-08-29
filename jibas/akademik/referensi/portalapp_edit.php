<?
/**[N]** Portal App - Edit wrapper **[N]**/ ?>
<?php
$_REQUEST['replid'] = (int)($_GET['replid'] ?? $_REQUEST['replid'] ?? 0);
require_once('portalapp_form.php');
