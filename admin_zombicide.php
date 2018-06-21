<?php define ('ZOMB_SITE_URL', 'http://zombicide.jhugues.fr/'); ?>
<link rel="stylesheet" href="<?php echo ZOMB_SITE_URL; ?>wp-content/plugins/mycommon/web/rsc/css/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo ZOMB_SITE_URL; ?>wp-content/plugins/mycommon/web/rsc/css/bootstrap-4.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo ZOMB_SITE_URL; ?>wp-content/plugins/zombicide/web/rsc/admin_zombicide.css" type="text/css" media="all" />
<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
global $Zombicide;
if ( empty($Zombicide) ) { $Zombicide = new Zombicide(); }
$AdminPageBean = new AdminPageBean();
echo $AdminPageBean->getContentPage();
?>
<script type='text/javascript' src='<?php echo ZOMB_SITE_URL; ?>wp-content/plugins/mycommon/web/rsc/js/jquery-ui-min.js'></script>
<script type='text/javascript' src='<?php echo ZOMB_SITE_URL; ?>wp-content/plugins/zombicide/web/rsc/admin_zombicide.js'></script>