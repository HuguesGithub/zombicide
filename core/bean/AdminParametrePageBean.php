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
    $tag = 'parametre';
    parent::__construct($tag);
    $this->DurationServices = FactoryServices::getDurationServices();
    $this->ExpansionServices = FactoryServices::getExpansionServices();
    $this->LevelServices = FactoryServices::getLevelServices();
    $this->MissionServices = FactoryServices::getMissionServices();
    $this->MissionExpansionServices = FactoryServices::getMissionExpansionServices();
    $this->MissionObjectiveServices = FactoryServices::getMissionObjectiveServices();
    $this->MissionRuleServices = FactoryServices::getMissionRuleServices();
    $this->ObjectiveServices = FactoryServices::getObjectiveServices();
    $this->PlayerServices = FactoryServices::getPlayerServices();
    $this->RuleServices = FactoryServices::getRuleServices();
    $this->title = 'Paramètres';
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminParametrePageBean();
    $tBodyButtons  = '<td><a class="btn btn-xs btn-success editParam" href="%2$s"><i class="fas fa-pencil-alt"></i></a> ';
    $tBodyButtons .= '<a class="btn btn-xs btn-danger rmvParam" href="%3$s"><i class="fas fa-trash-alt"></i></a></td><td>%1$s</td>';
    switch ($Bean->initVar('table')) {
      case 'duration' :
        $returned = $Bean->getDurationContent($urlParams, $tBodyButtons);
      break;
      case 'expansion' :
        $returned = $Bean->getExpansionContent($urlParams, $tBodyButtons);
      break;
      default :
        $returned = $Bean->buildWithHeaderAndFooter();
      break;
    }
    return $returned;
  }
  /**
   * Prépare les lignes du tableau et retourne le contenu de l'interface Durée
   * @param string $tBodyButtons Template d'affichage des boutons en fin de ligne
   * @return string
   */
  public function getDurationContent($urlParams, $tBodyButtons) {
    $Durations = $this->DurationServices->getDurationsWithFilters(__FILE__, __LINE__);
    $tBody = '';
    if (!empty($Durations)) {
      foreach ($Durations as $Duration) {
        $Bean = new DurationBean($Duration);
        $tBody .= $Bean->getRowForAdminPage($tBodyButtons);
      }
    }
    return $this->buildWithHeaderAndFooter(new Duration(), 'duration', $urlParams, $tBody);
  }
  /**
   * Prépare les lignes du tableau et retourne le contenu de l'interface Extension
   * @param string $tBodyButtons Template d'affichage des boutons en fin de ligne
   * @return string
   */
  public function getExpansionContent($urlParams, $tBodyButtons) {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__);
    $tBody = '';
    if (!empty($Expansions)) {
      foreach ($Expansions as $Expansion) {
        $Bean = new ExpansionBean($Expansion);
        $tBody .= $Bean->getRowForAdminPage($tBodyButtons);
      }
    }
    return $this->buildWithHeaderAndFooter(new Expansion(), 'expansion', $urlParams, $tBody);
  }
  /**
   * Retourne les onglets disponibles pour affichage.
   * @param string $table Onglet actif
   * @return string
   */
  public function buildTabs($table='')
  {
    $arrTabs = array(
      //'level'=>'Difficultés',
      'duration'=>'Durées',
      //'player'=>'Joueurs',
      //'rule'=>'Règles',
      //'objective'=>'Objectifs',
      'expansion'=>'Expansions',
      //'tile'=>'Dalles'
    );
    $strTabs = '';
    foreach ($arrTabs as $key => $value) {
      $strTabs .= '<a href="'.$this->getQueryArg(array('onglet'=>'parametre', 'table'=>$key));
      $strTabs .= '" class="list-group-item list-group-item-action';
      $strTabs .= ($key==$table ? ' active' : '').'">'.$value.'</a>';
    }
    return $strTabs;
  }
  /**
   * Retourne les différents éléments de l'interface Paramètre
   * @param array $classVars Liste des noms de colonnes
   * @param string $table Tag de l'interface
   * @param string $tBody Contenu du body du tableau
   * @return string
   */
  public function buildWithHeaderAndFooter($Obj='', $table='', $urlParams='', $tBody='')
  {
    $tHeader = '';
    if ($table!='') {
      switch ($table) {
        case 'duration' :
          $Obj = $this->DurationServices->select(__FILE__, __LINE__, $urlParams['id']);
        break;
        case 'expansion' :
          $Obj = $this->ExpansionServices->select(__FILE__, __LINE__, $urlParams['id']);
        break;
        default :
      }
      $tHeader .= '<tr>';
      switch ($urlParams[self::CST_POSTACTION]) {
        case 'trash' :
    	 	  $prefixTBody  = '<form method="post" action="#"><tr class="table-danger">';
        break;
        default :
    	 	  $prefixTBody  = '<form method="post" action="#"><tr class="table-success">';
        break;
      }
      $classVars = $Obj->getClassVars();
      foreach ($classVars as $key => $value) {
        $tHeader .= '<td>'.$key.'</td>';
        $prefixTBody .= '<td><input type="text" class="form-control" id="'.$table.'-'.$key.'"'.($key=='id'?' disabled':'').' value="'.$Obj->getField($key).'"/></td>';
      }
      $tHeader .= '<td>&nbsp;</td><td>Utilisations</td></tr>';
      $queryArg = array(
        self::CST_ONGLET=>'parametre',
        'table'=>$table,
      );
      $urlCancel = $this->getQueryArg($queryArg);
      switch ($urlParams[self::CST_POSTACTION]) {
        case 'trash' :
          $prefixTBody .= '<td><input type="hidden" value="'.$table.'" name="table"><input type="hidden" value="edit" name="'.self::CST_POSTACTION.'"><input type="submit" class="btn btn-xs btn-danger" value="Supprimer"/>';
          $prefixTBody .= '<a class="btn btn-xs btn-success" href="'.$urlCancel.'"><i class="fas fa-times-circle"></i></a></td><td>&nbsp;</td></tr></form>';
        break;
        case 'edit' :
          $prefixTBody .= '<td><input type="hidden" value="'.$table.'" name="table"><input type="hidden" value="edit" name="'.self::CST_POSTACTION.'"><input type="submit" class="btn btn-xs btn-success" value="Modifier"/>';
          $prefixTBody .= '<a class="btn btn-xs btn-danger" href="'.$urlCancel.'"><i class="fas fa-times-circle"></i></a></td><td>&nbsp;</td></tr></form>';
        break;
        case 'add' :
        default :
          $prefixTBody .= '<td><input type="hidden" value="'.$table.'" name="table"><input type="hidden" value="add" name="'.self::CST_POSTACTION.'"><input type="submit" class="btn btn-xs btn-success" value="Créer"/>';
          $prefixTBody .= '<a class="btn btn-xs btn-danger" href="'.$urlCancel.'"><i class="fas fa-times-circle"></i></a></td><td>&nbsp;</td></tr></form>';
        break;
      }
    }
    $args = array(
      $this->buildTabs($table),
      $tHeader,
      $prefixTBody.$tBody,
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/parametres-admin-board.php');
    return vsprintf($str, $args);
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
    foreach ($arrTabs as $key => $value) {
      $strTabs .= '<a href="'.$this->getQueryArg(array('onglet'=>'parametre', 'table'=>$key));
      $strTabs .= '" class="list-group-item list-group-item-action';
      $strTabs .= ($key==$table ? ' active' : '').'">'.$value.'</a>';
    }
    $tBody = '';
    $tBodyButtons  = '<td><button class="btn btn-xs btn-success editParam" data-type="%2$s" data-id="%3$s">';
    $tBodyButtons .= '<i class="fas fa-pencil-alt"></i></button>';
    $tBodyButtons .= '<button class="btn btn-xs btn-danger rmvParam" data-type="%2$s" data-id="%3$s">';
    $tBodyButtons .= '<i class="fas fa-trash-alt"></i></button></td><td>%1$s</td>';
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
      case 'rule' :
        $Rule = new Rule();
        $classVars = $Rule->getClassVars();
        $Rules = $this->RuleServices->getRulesWithFilters(__FILE__, __LINE__);
        if (!empty($Rules)) {
          foreach ($Rules as $Rule) {
            $id = $Rule->getId();
            $MissionRules = $this->MissionRuleServices->getMissionRulesWithFilters(__FILE__, __LINE__, array('ruleId'=>$id));
            $nb = count($MissionRules);
            $tBody .= '<tr><td>'.$id.'</td><td>'.$Rule->getSetting().'</td><td>'.$Rule->getCode().'</td><td>';
            $tBody .= $Rule->getDescription().'</td>';
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
            $arrF = array('objectiveId'=>$id);
            $MissionObjectives = $this->MissionObjectiveServices->getMissionObjectivesWithFilters(__FILE__, __LINE__, $arrF);
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
    $tFooter .= '<td><button class="btn btn-xs btn-success addParam" data-type="'.$table;
    $tFooter .= '"><i class="fas fa-plus-circle"></i><i class="fas fa-pencil-alt"></i></button>';
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
