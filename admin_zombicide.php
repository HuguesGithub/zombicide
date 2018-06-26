<?php
  define('ZOMB_SITE_URL', 'http://zombicide.jhugues.fr/');
  define('PLUGINS_MYCOMMON', ZOMB_SITE_URL.'wp-content/plugins/mycommon');
  define('PLUGINS_ZOMBICIDE', ZOMB_SITE_URL.'wp-content/plugins/zombicide');
?>
<link rel="stylesheet" href="<?php echo PLUGINS_MYCOMMON; ?>/web/rsc/css/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo PLUGINS_MYCOMMON; ?>/web/rsc/css/bootstrap-4.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo PLUGINS_ZOMBICIDE; ?>/web/rsc/admin_zombicide.css" type="text/css" media="all" />
<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
global $Zombicide;
if (empty($Zombicide)) { $Zombicide = new Zombicide(); }
$AdminPageBean = new AdminPageBean();
echo $AdminPageBean->getContentPage();
?>
<script type='text/javascript' src='<?php echo PLUGINS_MYCOMMON; ?>/web/rsc/js/jquery-ui-min.js'></script>
<script type='text/javascript' src='<?php echo PLUGINS_ZOMBICIDE; ?>/web/rsc/admin_zombicide.js'></script>
