<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LevelBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LevelBean extends LocalBean
{
  /**
   * Class Constructor
   * @param Level $Level
   */
  public function __construct($Level='')
  {
    parent::__construct();
    $this->Level = ($Level=='' ? new Level() : $Level);
    $this->MissionServices = new MissionServices();
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Level = $this->Level;
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('levelId'=>$Level->getId()));
    $nb = count($Missions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'level',
      'id'=>$Level->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$Level->getId().'</td><td>'.$Level->getName().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
