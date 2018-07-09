<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ObjectiveBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ObjectiveBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Objective $Objective
   */
  public function __construct($Objective='')
  {
    parent::__construct();
    $this->MissionObjectiveServices = FactoryServices::getMissionObjectiveServices();
    $this->Objective = ($Objective=='' ? new Objective() : $Objective);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Objective = $this->Objective;
    $arrF = array('objectiveId'=>$Objective->getId());
    $Missions = $this->MissionObjectiveServices->getMissionObjectivesWithFilters(__FILE__, __LINE__, $arrF);
    $nb = count($Missions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'objective',
      'id'=>$Objective->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash,
    );
    $tBody = '<tr><td>'.$Objective->getId().'</td><td>'.$Objective->getCode().'</td><td>'.$Objective->getDescription().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
