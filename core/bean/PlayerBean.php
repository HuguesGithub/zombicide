<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe PlayerBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class PlayerBean extends LocalBean
{
  /**
   * Class Constructor
   * @param Player $Player
   */
  public function __construct($Player='')
  {
    parent::__construct();
    $this->Player = ($Player=='' ? new Player() : $Player);
    $this->MissionServices = new MissionServices();
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Player = $this->Player;
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('playerId'=>$Player->getId()));
    $nb = count($Missions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'player',
      'id'=>$Player->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$Player->getId().'</td><td>'.$Player->getName().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
