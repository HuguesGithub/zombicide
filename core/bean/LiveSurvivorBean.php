<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorBean
 * @author Hugues.
 * @version 1.0.01
 * @since 1.0.00
 */
class LiveSurvivorBean extends LocalBean
{
  private $btnAction;
  /**
   * Class Constructor
   * @param LiveSurvivor $LiveSurvivor
   */
  public function __construct($LiveSurvivor='')
  {
    parent::__construct();
    $this->LiveSurvivor = ($LiveSurvivor=='' ? new LiveSurvivor() : $LiveSurvivor);
    $this->btnAction  = '<div class="btn%1$s" data-ajaxaction="toolbarAction" data-ajaxchildaction="%2$s"';
    $this->btnAction .= ' data-livesurvivor="%3$s"><i class="%4$s"></i></div>';
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
    for ($j=$i; $j<2; $j++) {
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
  
  public function getPortraitButton()
  {
    $LiveSurvivor = $this->LiveSurvivor;
    $Survivor = $LiveSurvivor->getSurvivor();
    $returned  = '';
    $returned .= '<div class="btn'.($LiveSurvivor->hasPlayedThisTurn()?' disabled':'').'" data-ajaxaction="toolbarAction"';
    $returned .= ' data-ajaxchildaction="startSurvivorTurn" data-livesurvivor="'.$LiveSurvivor->getId().'">';
    return $returned.'<img src="'.$Survivor->getPortraitUrl().'"/></div>';
  }
  
  public function getActionsButton()
  {
    $LiveSurvivor = $this->LiveSurvivor;
    $id = $LiveSurvivor->getId();
    // On récupère les livesurvivor_actions
    $LiveSurvivorActions = $LiveSurvivor->getLiveSurvivorActions();
    $cpt = 0;
    $specActions = '';
    // On compte le nombre de livesurvivor_action où actionId == 1
    while (!empty($LiveSurvivorActions)) {
      $LiveSurvivorAction = array_shift($LiveSurvivorActions);
      if ($LiveSurvivorAction->getActionId()==1) {
        $cpt++;
      } else {
        // Et le cas échéant, on créé un bouton spécial.
        $specActions .= $LiveSurvivorAction->getBean()->getToolbarButton($id);
      }
    }
    // On affiche en conséquence le nombre d'actions polyvalentes
    $returned = $this->getBatteryFromCpt($cpt);
    // Si on n'a plus d'actions polyvalentes, on disable celles-ci, mais on les affiche quand même.
    $returned .= '<div class="btn-group" role="group">';
    $returned .= vsprintf($this->btnAction, array('', 'endTurn', $id, 'fas fa-times'));
    $extraClass = ($cpt==0?' disabled':'');
    $returned .= vsprintf($this->btnAction, array($extraClass, 'makeNoise', $id, 'fas fa-bullhorn'));
    $returned .= vsprintf($this->btnAction, array($extraClass, 'move', $id, 'fas fa-shoe-prints'));
    $returned .= vsprintf($this->btnAction, array($extraClass, 'search', $id, 'fas fa-search'));
    $returned .= vsprintf($this->btnAction, array($extraClass, 'openDoor', $id, 'fas fa-door-closed'));
    $returned .= vsprintf($this->btnAction, array($extraClass, 'trade', $id, 'fas fa-gift'));
    $returned .= '</div>';
    // Si on a au moins une Action spéciale, il faut les afficher
    if ($specActions!='') {
      $returned .= '&nbsp;<div class="btn-group" role="group">'.$specActions.'</div>';
    }
    return $returned;
  }
  private function getBatteryFromCpt($cpt)
  {
    $returned  = '<div class="btn-group" role="group">';
    switch ($cpt) {
      case 0 :
        $returned .= vsprintf($this->btnAction, array('', 'none', '', 'fas fa-battery-empty'));
      break;
      case 1 :
        $returned .= vsprintf($this->btnAction, array('', 'none', '', 'fas fa-battery-quarter'));
      break;
      case 2 :
        $returned .= vsprintf($this->btnAction, array('', 'none', '', 'fas fa-battery-half'));
      break;
      case 3 :
        $returned .= vsprintf($this->btnAction, array('', 'none', '', 'fas fa-battery-three-quarters'));
      break;
      case 4 :
      default :
        $returned .= vsprintf($this->btnAction, array('', 'none', '', 'fas fa-battery-full'));
      break;
    }
    // On affiche en conséquence le nombre d'actions polyvalentes
    return $returned.'</div>&nbsp;';
  }
}
