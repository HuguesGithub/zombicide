<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe RuleBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class RuleBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Rule $Rule
   */
  public function __construct($Rule='')
  {
    parent::__construct();
    $this->MissionRuleServices = FactoryServices::getMissionRuleServices();
    $this->Rule = ($Rule=='' ? new Rule() : $Rule);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Rule = $this->Rule;
    $Missions = $this->MissionRuleServices->getMissionRulesWithFilters(__FILE__, __LINE__, array('ruleId'=>$Rule->getId()));
    $nb = count($Missions);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'rule',
      'id'=>$Rule->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Mission'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash,
    );
    $tBody  = '<tr><td>'.$Rule->getId().'</td><td>'.$Rule->getSetting().'</td><td>'.$Rule->getCode().'</td>';
	$tBody .= '<td>'.$Rule->getDescription().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
