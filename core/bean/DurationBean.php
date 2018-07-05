<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DurationBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class DurationBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Duration $Duration
   */
  public function __construct($Duration='')
  {
    parent::__construct();
    $this->MissionServices = FactoryServices::getMissionServices();
    $this->Duration = ($Duration=='' ? new Duration() : $Duration);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Duration = $this->Duration;
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('durationId'=>$Duration->getId()));
    $nb = count($Missions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'duration',
      'id'=>$Duration->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash,
    );
    $tBody = '<tr><td>'.$Duration->getId().'</td><td>'.$Duration->getMinDuration().'</td><td>'.$Duration->getMaxDuration().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
