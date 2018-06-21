<?php wp_head(); ?>
<link rel="stylesheet" href="http://zombicide.jhugues.fr/wp-content/plugins/mycommon/web/rsc/css/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="http://zombicide.jhugues.fr/wp-content/plugins/mycommon/web/rsc/css/bootstrap-4.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="http://zombicide.jhugues.fr/wp-content/plugins/zombicide/web/rsc/zombicide.css" type="text/css" media="all" />
<?php
	$PageBean = MainPageBean::getPageBean();
?>
<div id="shell" class="shell <?php echo $PageBean->getShellClass(); ?>">
<?php
  echo $PageBean->displayPublicHeader();
  echo $PageBean->getContentPage();
  echo $PageBean->displayPublicFooter();
?>
</div>
<script type='text/javascript' src='http://zombicide.jhugues.fr/wp-content/plugins/mycommon/web/rsc/js/jquery-ui-min.js'></script>
<script type='text/javascript' src='http://zombicide.jhugues.fr/wp-content/plugins/zombicide/web/rsc/zombicide.js'></script>
<!--
<script type='text/javascript' src='http://zombicide.jhugues.fr/wp-content/plugins/zombicide/web/rsc/buttonActions.js'></script>
<script type='text/javascript' src='http://zombicide.jhugues.fr/wp-content/plugins/zombicide/web/rsc/canvas.js'></script>
-->
<?php wp_footer(); ?>