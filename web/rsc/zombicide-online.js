var $hj = jQuery;
$hj(document).ready(function(){
  // On veut pouvoir afficher et cacher les panneaux de compétences et d'équipement des Survivants
  $hj('article.liveSurvivor .nav-link').unbind().click(function(){
    var tab = $hj(this).data('tab');
	  $hj('article.liveSurvivor .skillsLis').removeClass('active');
	  $hj('article.liveSurvivor .equipList').removeClass('active');
	  $hj(this).parent().parent().siblings('.'+tab).addClass('active');
  });
});
