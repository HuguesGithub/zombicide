<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveSurvivorBean extends LocalBean
{
  /**
   * Class Constructor
   * @param LiveSurvivor $LiveSurvivor
   */
  public function __construct($LiveSurvivor='')
  {
    parent::__construct();
    $this->LiveSurvivor = ($LiveSurvivor=='' ? new LiveSurvivor() : $LiveSurvivor);
  }
  
  public function getSideBarContent()
  {
    $LiveSurvivor = $this->LiveSurvivor;
    $Survivor = $LiveSurvivor->getSurvivor();
    // On construit la chaîne qui représente les points de vie.
    $hps = '';
    for ($i=0; $i<$LiveSurvivor->getHitPoints(); $i++) {
      $hps .= '<i class="fa fa-heart"></i>';
    }
    // Une fois qu'on a compté les points de vie restants, on affiche ceux manquants.
    for ( $j=$i; $j<2; $j++) {
      $hps .= '<i class="far fa-heart"></i>';
    }
    $xps = $LiveSurvivor->getExperiencePoints();
    if ($xps<=6) {
      $color = 'blue';
      $maxXp = 6;
    } else {
      $maxXp = 1;
    }
    $args = array (
      // Url du portrait - 1
      // TODO : De manière dynamique, passer en paramètre le type du Survivant.
      $Survivor->getPortraitUrl(),
      // Nom du Survivant - 2
      $Survivor->getName(),
      // Points de vie - 3
      $hps,
      // Couleur de la barre d'xp, selon le niveau - 4
      $color,
      // Pourcentage d'expérience avant de changer de niveau - 5
      round($xps*100/$maxXp, 2).'%',
      // Nombre d'xps actuels et prochain seuil - 6
      $xps.'/'.$maxXp,
      // Liste des compétences - 7
      // TODO : Afficher de façon dynamique selon le type de survivant ET griser celles qui n'ont pas encore été débloquées.
      $Survivor->getUlSkills(),
      // Liste de l'équipement - 8
      '', // <div class="hand">Eq1 En Main</div><div class="inventory">Eq2 Inventaire</div>
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/online-survivor-cardvisit.php');
    return vsprintf($str, $args);
  }
}
