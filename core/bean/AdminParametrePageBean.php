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
    $this->KeywordServices = FactoryServices::getKeywordServices();
    $this->LevelServices = FactoryServices::getLevelServices();
    $this->MissionServices = FactoryServices::getMissionServices();
    $this->MissionExpansionServices = FactoryServices::getMissionExpansionServices();
    $this->MissionObjectiveServices = FactoryServices::getMissionObjectiveServices();
    $this->MissionRuleServices = FactoryServices::getMissionRuleServices();
    $this->ObjectiveServices = FactoryServices::getObjectiveServices();
    $this->OrigineServices = FactoryServices::getOrigineServices();
    $this->PlayerServices = FactoryServices::getPlayerServices();
    $this->RuleServices = FactoryServices::getRuleServices();
    $this->SpawnTypeServices = FactoryServices::getSpawnTypeServices();
    $this->TokenServices = FactoryServices::getTokenServices();
    $this->WeaponProfileServices = FactoryServices::getWeaponProfileServices();
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
    $orderby = $Bean->initVar('orderby', 'id');
    $order = $Bean->initVar('order', 'asc');
    /**
     * On initialise le Service qui va être utilisé pour les traitements de la page.
     * On traite éventuellement une action formulaure
     * On affiche la page dédiée à l'onglet
     */
    switch ($urlParams['table']) {
      case 'duration' :
        $Services = $Bean->DurationServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Durations = $Services->getDurationsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Durations, $urlParams, $tBodyButtons);
      break;
      case 'expansion' :
        $Services = $Bean->ExpansionServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Expansions = $Services->getExpansionsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Expansions, $urlParams, $tBodyButtons);
      break;
      case 'keyword' :
        $Services = $Bean->KeywordServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Keywords = $Services->getKeywordsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Keywords, $urlParams, $tBodyButtons);
      break;
      case 'level' :
        $Services = $Bean->LevelServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Levels = $Services->getLevelsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Levels, $urlParams, $tBodyButtons);
      break;
      case 'objective' :
        $Services = $Bean->ObjectiveServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Objectives = $Services->getObjectivesWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Objectives, $urlParams, $tBodyButtons);
      break;
      case 'origine' :
        $Services = $Bean->OrigineServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Origines = $Services->getOriginesWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Origines, $urlParams, $tBodyButtons);
      break;
      case 'player' :
        $Services = $Bean->PlayerServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Players = $Services->getPlayersWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Players, $urlParams, $tBodyButtons);
      break;
      case 'rule' :
        $Services = $Bean->RuleServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Rules = $Services->getRulesWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Rules, $urlParams, $tBodyButtons);
      break;
      case 'spawntype' :
        $Services = $Bean->SpawnTypeServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $SpawnTypes = $Services->getSpawnTypesWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $SpawnTypes, $urlParams, $tBodyButtons);
      break;
      case 'token' :
        $Services = $Bean->TokenServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $Tokens = $Services->getTokensWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $Tokens, $urlParams, $tBodyButtons);
      break;
      case 'weaponprofile' :
        $Services = $Bean->WeaponProfileServices;
        $Bean->dealWithPostAction($Services, $urlParams);
        $WeaponProfiles = $Services->getWeaponProfilesWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $returned = $Bean->getMutualizedContent($Services, $WeaponProfiles, $urlParams, $tBodyButtons);
      break;
      default :
        $returned = $Bean->buildWithHeaderAndFooter();
      break;
    }
    return $returned;
  }
  /**
   * Retourne les onglets disponibles pour affichage.
   * @param string $table Onglet actif
   * @return string
   */
  public function buildTabs($table='')
  {
    $arrTabs = array(
      'level'=>'Difficultés',
      'duration'=>'Durées',
      'expansion'=>'Expansions',
      'player'=>'Joueurs',
      'keyword'=>'Mots-clés',
      'objective'=>'Objectifs',
      'origine'=>'Origines',
      'weaponprofile'=>'Profils d\'armes',
      'rule'=>'Règles',
      'token'=>'Tokens',
      'spawntype'=>'Types de Spawns',
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
  public function getMutualizedContent($Services, $Objs, $urlParams, $tBodyButtons)
  {
    // Initialisation des variables
    $curPage = $urlParams['cur_page'];
    $orderby = $urlParams['orderby'];
    $order = $urlParams['order'];
    $table = $urlParams['table'];
    $nbPerPage = 15;
    $tHeader = '';
    $prefixTBody = '';
    $tBody = '';
    $tFooter = '';
    // Si on a cliqué sur un onglet
    if ($table!='') {
      $queryArg = array(
        self::CST_ONGLET=>'parametre',
        'table'=>$table,
        self::CST_ORDERBY=>$orderby,
        self::CST_ORDER=>$order,
      );
      // Gestion de la Pagination
      // Et construction des lignes du tableau paginé
      $nbElements = count($Objs);
      $nbPages = ceil($nbElements/$nbPerPage);
      $curPage = max(1, min($curPage, $nbPages));
      $Objs = array_slice($Objs, ($curPage-1)*$nbPerPage, $nbPerPage);
      $strPagination = $this->getPagination($queryArg, 'all', $curPage, $nbPages, $nbElements);    
      if (!empty($Objs)) {
        foreach ($Objs as $Obj) {
          $Bean = $Obj->getBean();
          $tBody .= $Bean->getRowForAdminPage($tBodyButtons);
        }
      }
      // Construction du Header
      $Obj = $Services->select(__FILE__, __LINE__, $urlParams['id']);
      $classVars = $Obj->getClassVars();
      $tHeader .= '<tr>';
      $tFooter .= '<tr>';
      $prefixTBody  = '<form method="post" action="#"><tr class="table-';
      $prefixTBody .= ($urlParams[self::CST_POSTACTION]=='trash' ? 'danger' : 'success').'">';
      foreach ($classVars as $key => $value) {
        $queryArg[self::CST_ORDERBY] = $key;
        if ($orderby==$key) {
          $queryArg[self::CST_ORDER] = ($order=='asc'?'desc':'asc');
        }
        $urlSort = $this->getQueryArg($queryArg);
        $tHeader .= '<th scope="col" id="'.$key.'" class="manage-column column-primary sortable "><a href="'.$urlSort.'"><span>'.$key;
        $tHeader .= '</span><span class="sorting-indicator"></span></a></th>';
        $prefixTBody .= '<td><input type="text" class="form-control" name="'.$key.'" ';
        $prefixTBody .= ($key=='id' || $urlParams[self::CST_POSTACTION]=='trash'?' disabled':'').' value="'.$Obj->getField($key).'"/></td>';
      }
      $tHeader .= '<td>&nbsp;</td><td>Utilisations</td></tr>';
      $tFooter .= '<tr><th colspan="'.(2+count($classVars)).'"><div class="tablenav-pages float-right">'.$strPagination.'</div></th></tr>';
      unset($queryArg[self::CST_ORDERBY]);
      unset($queryArg[self::CST_ORDER]);
      $urlCancel = $this->getQueryArg($queryArg);
      // Gestion des boutons en fin de ligne, pour la création, l'édition et la suppression ainsi que pour l'annulation.
      switch ($urlParams[self::CST_POSTACTION]) {
        case 'trash' :
          $inpValue = 'trashConfirm';
          $mainClass = 'danger';
          $label = 'Supprimer';
          $secClass = 'success';
        break;
        case 'edit' :
          $inpValue = 'editConfirm';
          $mainClass = 'success';
          $label = 'Modifier';
          $secClass = 'danger';
        break;
        case 'add' :
        default :
          $inpValue = 'add';
          $mainClass = 'success';
          $label = 'Créer';
          $secClass = 'danger';
        break;
      }
      $prefixTBody .= '<td><input type="hidden" value="'.$table.'" name="table">';
      $prefixTBody .= '<input type="hidden" value="'.$inpValue.'" name="'.self::CST_POSTACTION.'">';
      $prefixTBody .= '<input type="submit" class="btn btn-xs btn-'.$mainClass.'" value="'.$label.'"/>&nbsp;';
      $prefixTBody .= '<a class="btn btn-xs btn-'.$secClass.'" href="'.$urlCancel.'"><i class="fas fa-times-circle"></i></a>';
      $prefixTBody .= '</td><td>&nbsp;</td></tr></form>';
    }
    $args = array(
      $this->buildTabs($table),
      $tHeader,
      $prefixTBody.$tBody,
      $tFooter,
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/parametres-admin-board.php');
    return vsprintf($str, $args);
  }
  /**
   * Vérifie si on a une action provenant d'un formulaire. Réoriente le cas échéant.
   * @param $Services Services à utiliser pour l'opération
   * @param array $urlParams Contient les différents éléments de l'objet
   */
  private function dealWithPostAction($Services, &$urlParams)
  {
    /**
     * Si on a une action par formulaire, elle doit être traitée en priorité.
     */
    switch ($urlParams[self::CST_POSTACTION]) {
      case 'add' :
        $this->addElement($Services, $urlParams);
      break;
      case 'editConfirm' :
        $this->editElement($Services, $urlParams);
      break;
      case 'trashConfirm' :
        $this->trashElement($Services, $urlParams);
        $urlParams[self::CST_POSTACTION] = 'add';
      break;
      default :
      break;
    }
  }
  /**
   * Supprime un élément
   * @param $Services Services à utiliser pour la suppression
   * @param array $urlParams Contient les différents éléments de l'objet dont l'identifiant
   */
  private function trashElement($Services, $urlParams)
  {
    if ($Services!=null) {
      $Obj = $Services->select(__FILE__, __LINE__, $urlParams['id']);
      $Services->delete(__FILE__, __LINE__, $Obj);
    }
  }
  /**
   * Modifie un élément
   * @param $Services Services à utiliser pour la modification
   * @param array $urlParams Contient les différents éléments de l'objet
   */
  private function editElement($Services, $urlParams)
  {
    if ($Services!=null) {
      $Obj = $Services->select(__FILE__, __LINE__, $urlParams['id']);
      $doUpdate = $Obj->updateWithPost($urlParams);
      if ($doUpdate) {
        $Services->update(__FILE__, __LINE__, $Obj);
      }
    }
  }
  /**
   * Créé un élément
   * @param $Services Services à utiliser pour la création
   * @param array $urlParams Contient les différents éléments de l'objet
   */
  private function addElement($Services, $urlParams)
  {
    if ($Services!=null) {
      $Obj = $Services->select(__FILE__, __LINE__, -1);
      $Obj->updateWithPost($urlParams);
      $Services->insert(__FILE__, __LINE__, $Obj);
    }
  }
}
