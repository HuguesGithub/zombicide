<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ExpansionBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ExpansionBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Expansion $Expansion
   */
  public function __construct($Expansion='')
  {
    parent::__construct();
    $this->MissionExpansionServices = FactoryServices::getMissionExpansionServices();
    $this->Expansion = ($Expansion=='' ? new Expansion() : $Expansion);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Expansion = $this->Expansion;
    $arrF = array('expansionId'=>$Expansion->getId());
    $MissionExpansions = $this->MissionExpansionServices->getMissionExpansionsWithFilters(__FILE__, __LINE__, $arrF);
    $nb = count($MissionExpansions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'expansion',
      'id'=>$Expansion->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''), 
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$Expansion->getId().'</td><td>'.$Expansion->getCode().'</td><td>'.$Expansion->getName();
    $tBody .= '</td><td>'.$Expansion->getDisplayRank().'</td>';
	return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
