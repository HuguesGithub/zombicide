<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorActionBean
 * @since 1.0.01
 * @version 1.0.01
 * @author Hugues
 */
class LiveSurvivorActionBean extends LocalBean
{
  private $btnAction;
  /**
   * Class Constructor
   * @param LiveSurvivorAction $LiveSurvivorAction
   */
  public function __construct($LiveSurvivorAction='')
  {
    parent::__construct();
    $this->LiveSurvivorAction = ($LiveSurvivorAction=='' ? new LiveSurvivorAction() : $LiveSurvivorAction);
    $this->btnAction  = '<div class="btn%1$s" data-ajaxaction="toolbarAction" data-ajaxchildaction="%2$s"';
    $this->btnAction .= ' data-livesurvivor="%3$s"><i class="%4$s"></i></div>';
  }
  
  public function getToolbarButton($id)
  {
    $toolbarButton = '';
    switch ($this->LiveSurvivorAction->getActionId()) {
      case 11 : // +1 Action [Combat]
        $toolbarButton  = vsprintf($this->btnAction, array('', 'melee', $id, 'fas fa-question-circle'));
        $toolbarButton .= vsprintf($this->btnAction, array('', 'ranged', $id, 'fas fa-question-circle'));
      break;
      case 12 : // +1 Action [Déplacement]
        $toolbarButton  = vsprintf($this->btnAction, array('', 'move', $id, 'fas fa-shoe-prints'));
      break;
      case 13 : // +1 Action [Fouille]
        $toolbarButton  = vsprintf($this->btnAction, array('', 'search', $id, 'fas fa-search'));
      break;
    // 16 : 1 Relance par tour
    // 20 : Poussée
    // 22 : Bruyant
    // 25 : Charognard
    // 26 : Chef-né
    // 28 : Destinée
      case 33 : // Médic
        $toolbarButton  = vsprintf($this->btnAction, array('', 'medic', $id, 'fas fa-medkit'));
      break;
    // 38 : +1 Action [Mêlée]
    // 39 : +1 Action [A distance]
    // 41 : Effraction
    // 55 : Ange gardien
    // 59 : Provocation
    // 91 : +1 Action [Equipe]
    // 93 : +1 Action [Chien]
    // 97 : Sprint
    // 98 : Saut
    // 109 : +1 Action [Recharge]
      default :
      break;
    }
    return $toolbarButton;
  }
}
