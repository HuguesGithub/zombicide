<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe OrigineBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class OrigineBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Origine $Origine
   */
  public function __construct($Origine='')
  {
    parent::__construct();
    $this->MissionServices = FactoryServices::getMissionServices();
    $this->Origine = ($Origine=='' ? new Origine() : $Origine);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Origine = $this->Origine;
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('origineId'=>$Origine->getId()));
    $nb = count($Missions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'origine',
      'id'=>$Origine->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$Origine->getId().'</td><td>'.$Origine->getName().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
