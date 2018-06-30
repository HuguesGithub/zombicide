<?php
declare(strict_types=1);
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminParametrePageBean
 * @version 1.0.00
 * @since 1.0.00
 * @author Hugues
 */
class AdminParametrePageBean extends AdminPageBean
{

  public function __construct()
  {
    $services = array('Expansion', 'Mission', 'Duration', 'MissionExpansion', 'Level', 'MissionObjective',
      'Player', 'MissionRule', 'Objective', 'Rule');
    $tag = 'parametre';
    parent::__construct($tag, $services);
    $this->title = 'Paramètres';
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminParametrePageBean();
    return $Bean->getContentPage();
  }
  /**
   * @return string
   */
  public function getContentPage()
  {
    $table = $this->initVar('table');
    $arrTabs = array('level'=>'Difficultés', 'duration'=>'Durées', 'player'=>'Joueurs', 'rule'=>'Règles',
      'objective'=>'Objectifs', 'expansion'=>'Expansions', 'tile'=>'Dalles');
    $strTabs = '';
    foreach ($arrTabs as $key=>$value) {
      $strTabs .= '<a href="'.$this->getQueryArg(array('onglet'=>'parametre', 'table'=>$key)).'" class="list-group-item list-group-item-action';
      $strTabs .= ($key==$table ? ' active' : '').'">'.$value.'</a>';
    }
    $tBody = '';
    $tBodyButtons  = '<td><button class="btn btn-xs btn-success editParam" data-type="%2$s" data-id="%3$s"><i class="fas fa-pencil-alt"></i></button>';
    $tBodyButtons .= '<button class="btn btn-xs btn-danger rmvParam" data-type="%2$s" data-id="%3$s"><i class="fas fa-trash-alt"></i></button></td><td>%1$s</td>';
    switch ($table) {
      case 'level' :
        $Level = new Level();
        $classVars = $Level->getClassVars();
        $Levels = $this->LevelServices->getLevelsWithFilters(__FILE__, __LINE__);
        if (!empty($Levels)) {
          foreach ($Levels as $Level) {
            $id = $Level->getId();
            $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('levelId'=>$id));
            $nb = count($Missions);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Level->getName().'</td>';
            $args = array($nb.' Mission'.($nb>1?'s':''), $table, $id);
            $tBody .= vsprintf($tBodyButtons, $args).'</tr>';
          }
        }
      break;
      case 'duration' :
        $Duration = new Duration();
        $classVars = $Duration->getClassVars();
        $Durations = $this->DurationServices->getDurationsWithFilters(__FILE__, __LINE__);
        if (!empty($Durations)) {
          foreach ($Durations as $Duration) {
            $id = $Duration->getId();
            $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('durationId'=>$id));
            $nb = count($Missions);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Duration->getMinDuration().'</td><td>'.$Duration->getMaxDuration().'</td>';
            $args = array($nb.' Mission'.($nb>1?'s':''), $table, $id);
            $tBody .= vsprintf($tBodyButtons, $args).'</tr>';
          }
        }
      break;
      case 'player' :
        $Player = new Player();
        $classVars = $Player->getClassVars();
        $Players = $this->PlayerServices->getPlayersWithFilters(__FILE__, __LINE__);
        if (!empty($Players)) {
          foreach ($Players as $Player) {
            $id = $Player->getId();
            $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('playerId'=>$id));
            $nb = count($Missions);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Player->getName().'</td>';
            $args = array($nb.' Mission'.($nb>1?'s':''), $table, $id);
            $tBody .= vsprintf($tBodyButtons, $args).'</tr>';
          }
        }
      break;
      case 'expansion' :
        $Expansion = new Expansion();
        $classVars = $Expansion->getClassVars();
        $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__);
        if (!empty($Expansions)) {
          foreach ($Expansions as $Expansion) {
            $id = $Expansion->getId();
            $MissionExpansions = $this->MissionExpansionServices->getMissionExpansionsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id));
            $nb = count($MissionExpansions);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Expansion->getCode().'</td><td>'.$Expansion->getName().'</td><td>'.$Expansion->getDisplayRank().'</td>';
            $args = array($nb.' Mission'.($nb>1?'s':''), $table, $id);
            $tBody .= vsprintf($tBodyButtons, $args).'</tr>';
          }
        }
      break;
      case 'rule' :
        $Rule = new Rule();
        $classVars = $Rule->getClassVars();
        $Rules = $this->RuleServices->getRulesWithFilters(__FILE__, __LINE__);
        if (!empty($Rules)) {
          foreach ($Rules as $Rule) {
            $id = $Rule->getId();
            $MissionRules = $this->MissionRuleServices->getMissionRulesWithFilters(__FILE__, __LINE__, array('ruleId'=>$id));
            $nb = count($MissionRules);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Rule->getSetting().'</td><td>'.$Rule->getCode().'</td><td>'.$Rule->getDescription().'</td>';
            $args = array($nb.' Mission'.($nb>1?'s':''), $table, $id);
            $tBody .= vsprintf($tBodyButtons, $args).'</tr>';
          }
        }
      break;
      case 'objective' :
        $Objective = new Objective();
        $classVars = $Objective->getClassVars();
        $Objectives = $this->ObjectiveServices->getObjectivesWithFilters(__FILE__, __LINE__);
        if (!empty($Objectives)) {
          foreach ($Objectives as $Objective) {
            $id = $Objective->getId();
            $MissionObjectives = $this->MissionObjectiveServices->getMissionObjectivesWithFilters(__FILE__, __LINE__, array('objectiveId'=>$id));
            $nb = count($MissionObjectives);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Objective->getCode().'</td><td>'.$Objective->getDescription().'</td>';
            $args = array($nb.' Mission'.($nb>1?'s':''), $table, $id);
            $tBody .= vsprintf($tBodyButtons, $args).'</tr>';
          }
        }
      break;
      default :
      break;
    }
    $tHeader  = '<tr>';
    $tFooter  = '<tr>';
    foreach ($classVars as $key => $value) {
      $tHeader .= '<td>'.$key.'</td>';
      $tFooter .= '<td><input type="text" class="form-control" id="'.$table.'-'.$key.'"'.($key=='id'?' disabled':'').'/></td>';
    }
    $tHeader .= '<td>&nbsp;</td><td>Utilisations</td></tr>';
    $tFooter .= '<td><button class="btn btn-xs btn-success addParam" data-type="'.$table.'"><i class="fas fa-plus-circle"></i><i class="fas fa-pencil-alt"></i></button>';
    $tFooter .= '<button class="btn btn-xs btn-danger cleanParam"><i class="fas fa-times-circle"></i></button></td><td>&nbsp;</td></tr>';
    $args = array(
      $strTabs,
      $tHeader,
      $tBody,
      $tFooter,
      '',
      '',
      '',
      '',
      '',
      '',
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/parametres-admin-board.php');
    return vsprintf($str, $args);
  }

}
