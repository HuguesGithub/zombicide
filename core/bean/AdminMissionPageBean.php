<?php
declare(strict_types=1);
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminMissionPageBean
 * @version 1.0.00
 * @since 1.0.00
 * @author Hugues
 */
class AdminMissionPageBean extends AdminPageBean
{
  public function __construct()
  {
    $services = array('Mission', 'Duration', 'MissionExpansion', 'Level', 'Origine', 'Player');
    $tag = self::CST_MISSION;
    parent::__construct($tag, $services);
    $this->title = 'Missions';
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminMissionPageBean();
    if (!isset($urlParams[self::CST_POSTACTION])) {
      return $Bean->getListingPage();
    }
    $MissionServices = FactoryServices::getMissionServices();
    $Mission = $MissionServices->select(__FILE__, __LINE__, $urlParams['id']);
    switch ($urlParams[self::CST_POSTACTION]) {
      case 'add'   :
        $returned = $Bean->getAddPage($Mission);
      break;
      case 'edit'  :
        $returned = ($Mission->getId() == '' ? $Bean->getListingPage() : $Bean->getEditPage($Mission));
      break;
      case 'trash' :
        $returned = 'trash';
      break;
      case 'view'  :
        $returned = 'view';
      break;
      case 'clone' :
        $returned = 'clone';
      break;
      default      :
        $returned = $Bean->getListingPage();
      break;
    }
    return $returned;
  }
  /**
   * @param Mission $Mission
   * @return string
   */
  public function getAddPage($Mission)
  {
    if (isset($_POST) && !empty($_POST)) {
      $doInsert = $Mission->initWithPost($_POST);
      if ($doInsert) {
        $this->MissionServices->insert(__FILE__, __LINE__, $Mission);
        $Mission->setId(MySQL::getLastInsertId());
        $this->dealWithExpansions($Mission, $_POST[self::CST_EXPANSIONID]);
        return $this->getListingPage();
      }
    }
    return $this->getAddEditPage($Mission, 'Ajouter une Mission', 'add');
  }
    /**
     * @param Mission $Mission
     * @return string
     */
  public function getEditPage($Mission)
  {
    if (isset($_POST) && !empty($_POST)) {
      $doUpdate = $Mission->updateWithPost($_POST);
      $this->dealWithExpansions($Mission, $_POST[self::CST_EXPANSIONID]);
      if ($doUpdate) {
        $this->MissionServices->update(__FILE__, __LINE__, $Mission);
        return $this->getListingPage();
      }
    }
    return $this->getAddEditPage($Mission, 'Modifier “'.$Mission->getTitle().'”', 'edit');
  }
  private function dealWithExpansions($Mission, $arrExpansions)
  {
    $MissionExpansions = $Mission->getMissionExpansions();
    $DelMissionExpansions = array();
    $GetMissionExpansions = array();
    // Si au moins une Extension est déjà accrochée à la Mission et qu'il y en a au moins une de sélectionnée
    if (!empty($arrExpansions) && !empty($MissionExpansions)) {
      foreach ($MissionExpansions as $MissionExpansion) {
        $expansionId = $MissionExpansion->getExpansionId();
        $isSelected = false;
        foreach ($arrExpansions as $key => $value) {
          // On ne lui fait rien à cette extension.
          if ($expansionId == $value) {
            $isSelected = true;
            unset($arrExpansions[$key]);
            array_push($GetMissionExpansions, $MissionExpansion);
          }
        }
        if (!$isSelected) {
          array_push($DelMissionExpansions, $MissionExpansion);
        }
      }
    }
    // Si des extensions rattachées n'ont pas été validées, on les supprime
    $this->deleteUncheckedMissionExpansions($DelMissionExpansions);
    // Si des extensions cochées n'ont pas été traitées, on les ajoute.
    $args = array(self::CST_MISSIONID=>$Mission->getId());
    foreach ($arrExpansions as $key => $value) {
      $args[self::CST_EXPANSIONID] = $value;
      $MissionExpansion = new MissionExpansion($args);
      $this->MissionExpansionServices->insert(__FILE__, __LINE__, $MissionExpansion);
    }
    $Mission->setMissionExpansions($GetMissionExpansions);
  }
  private function deleteUncheckedMissionExpansions($DelMissionExpansions)
  {
    if (!empty($DelMissionExpansions)) {
      foreach ($DelMissionExpansions as $MissionExpansion) {
        $this->MissionExpansionServices->delete(__FILE__, __LINE__, $MissionExpansion);
      }
    }
  }
  
  /**
   * @param Mission $Mission
   * @param string $title
   * @param string $postAction
   * @return string
   */
  public function getAddEditPage($Mission, $title, $postAction)
  {
    $classe = 'custom-select custom-select-sm filters';
    $Bean = new MissionBean($Mission);
    $args = array(
      // Titre de la Mission - 1
      $title,
      // Url de l'action - 2
      '#',
      // Url pour Annuler - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_MISSION)),
      // Id de la Mission - 4
      $Mission->getId(),
      // Type de l'action - 5
      $postAction,
      // Titre de la Mission - 6
      $Mission->getTitle(),
      // Code de la Mission - 7
      $Mission->getCode(),
      // Select de la Difficulté - 8
      $this->LevelServices->getLevelsSelect(__FILE__, __LINE__, $Mission->getLevelId(), '', $classe),
      // Select de la Durée - 9
      $this->DurationServices->getDurationsSelect(__FILE__, __LINE__, $Mission->getDurationId(), '', $classe),
      // Select du nombre de joueurs - 10
      $this->PlayerServices->getNbPlayersSelect(__FILE__, __LINE__, $Mission->getPlayerId(), '', $classe),
      // Select de l'origine - 11
      $this->OrigineServices->getOriginesSelect(__FILE__, __LINE__, $Mission->getOrigineId(), '', $classe),
      // Select Multiple des extensions - 12
      $this->MissionExpansionServices->getMissionExpansionsSelect(__FILE__, __LINE__, $Mission, '', $classe, true),
      // Construction Map - 13
      $Bean->buildBlockTiles(),
        // 14
      $Bean->getMissionRulesBlock(),
        // 15
      $Bean->getMissionSettingsBlock(),
        // 16
      $Bean->getMissionObjectivesBlock(),
      '','','','','','','','','','','','','','',
  );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/missions-edit-board.php');
    return vsprintf($str, $args);
  }
  private function getSubs($queryArg, $post_status, $numbers)
  {
    $links = array('all'=>'Toutes', self::CST_PENDING=>'Non publiées', self::CST_PUBLISH=>'Publiées', self::CST_FUTURE=>'Planifiées');
    $strSubs = '';
    foreach ($links as $key => $value) {
      $queryArg[self::CST_POSTSTATUS] = $key;
      $strSubs .= '<li class="'.$key.'"><a href="'.$this->getQueryArg($queryArg).'" ';
      $strSubs .= 'class="'.($post_status==$key?self::CST_CURRENT:'').'">'.$value.' ';
      $strSubs .= '<span class="count">('.$numbers[$key].')</span></a>'.($key!='all'?' |':'').'</li>';
    }
    return $strSubs;
  }
  private function getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements)
  {
    $queryArg[self::CST_POSTSTATUS] = $post_status;
    $queryArg[self::CST_CURPAGE] = 1;
    $hrefFirst = $this->getQueryArg($queryArg);
    $queryArg[self::CST_CURPAGE] = max(1, $curPage-1);
    $hrefPrev = $this->getQueryArg($queryArg);
    $queryArg[self::CST_CURPAGE] = min($nbPages, $curPage+1);
    $hrefNext = $this->getQueryArg($queryArg);
    $queryArg[self::CST_CURPAGE] = $nbPages;
    $hrefLast = $this->getQueryArg($queryArg);
    $args = array(
      // Nombre d'éléments
         $nbElements,
      // Disable First/Prev Page
      $curPage==1 ? ' disabled' : '',
      // URL First Page
      $hrefFirst,
      // URL Prev Page
      $hrefPrev,
      // Page courante
      $curPage,
      // Nombre de pages
      $nbPages,
      // Disable Next/Last Page
      $curPage==$nbPages ? ' disabled' : '',
      // URL Next Page
      $hrefNext,
      // URL Last Page
      $hrefLast,
  );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-pagination.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getListingPage()
  {
    $arrFilters = array();
    $fby_levelId = $this->initVar('filter-by-levelId', '');
    $fby_playerId = $this->initVar('filter-by-playerId', '');
    $fby_durationId = $this->initVar('filter-by-durationId', '');
    $fby_origineId = $this->initVar('filter-by-origineId', '');
    if ($fby_levelId!='') {
      $arrFilters[self::CST_LEVELID] = $fby_levelId;
    }
    if ($fby_playerId!='') {
      $arrFilters[self::CST_PLAYERID] = $fby_playerId;
    }
    if ($fby_durationId!='') {
      $arrFilters[self::CST_DURATIONID] = $fby_durationId;
    }
    if ($fby_origineId!='') {
      $arrFilters[self::CST_ORIGINEID] = $fby_origineId;
    }
    $orderby = $this->initVar(self::CST_ORDERBY, self::CST_TITLE);
    $order = $this->initVar(self::CST_ORDER, 'ASC');
    $curPage = $this->initVar(self::CST_CURPAGE, 1);
    $post_status = $this->initVar(self::CST_POSTSTATUS, 'all');
    if ($post_status==self::CST_PENDING) {
      $arrFilters['published'] = '0';
    }
    $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
    $NotPublishedMissions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('published'=>0));
    $FilteredMissions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, $arrFilters, $orderby, $order);
    $argsPublished = array(self::CST_POSTSTATUS=>self::CST_PUBLISH, self::CST_ORDERBY=>$orderby, self::CST_ORDER=>$order);
    $WpPostsPublished = $this->WpPostServices->getArticles(__FILE__, __LINE__, $argsPublished);
    $argsFuture = array(self::CST_POSTSTATUS=>self::CST_FUTURE, self::CST_ORDERBY=>$orderby, self::CST_ORDER=>$order);
    $WpPostsFuture = $this->WpPostServices->getArticles(__FILE__, __LINE__, $argsFuture);
    switch ($post_status) {
      case self::CST_PUBLISH :
        $ToDisplayMissions = $WpPostsPublished;
      break;
      case self::CST_FUTURE  :
        $ToDisplayMissions = $WpPostsFuture;
      break;
      case self::CST_PENDING :
      case 'all'             :
      default                :
        $ToDisplayMissions = $FilteredMissions;
      break;
    }
    $strRows = '';
    $nbPerPage = 15;
    if ($post_status == 'all' || $post_status == self::CST_PENDING) {
      $nbElements = count($ToDisplayMissions);
      $nbPages = ceil($nbElements/$nbPerPage);
      $curPage = max(1, min($curPage, $nbPages));
      $DisplayedMissions = array_slice($ToDisplayMissions, ($curPage-1)*$nbPerPage, $nbPerPage);
      if (!empty($DisplayedMissions)) {
        foreach ($DisplayedMissions as $Mission) {
          $MissionBean = new MissionBean($Mission);
          $strRows .= $MissionBean->getRowForAdminPage();
        }
      }
    } else {
      $ToDisplayFiltered = array();
      if (!empty($ToDisplayMissions)) {
        foreach ($ToDisplayMissions as $WpPost) {
          $WpPostMissionBean = $WpPost->getBean();
          $Mission = $WpPostMissionBean->getMission();
          if (!empty($arrFilters[self::CST_LEVELID]) && $Mission->getLevelId()!=$arrFilters[self::CST_LEVELID]) {
            continue;
          }
          if (!empty($arrFilters[self::CST_DURATIONID]) && $Mission->getDurationId()!=$arrFilters[self::CST_DURATIONID]) {
            continue;
          }
          if (!empty($arrFilters[self::CST_PLAYERID]) && $Mission->getPlayerId()!=$arrFilters[self::CST_PLAYERID]) {
            continue;
          }
          if (!empty($arrFilters[self::CST_ORIGINEID]) && $Mission->getOrigineId()!=$arrFilters[self::CST_ORIGINEID]) {
            continue;
          }
          $MissionBean = new MissionBean($Mission);
          array_push($ToDisplayFiltered, $MissionBean);
        }
      }
      if (!empty($ToDisplayFiltered)) {
        foreach ($ToDisplayFiltered as $MissionBean) {
          $strRows .= $MissionBean->getRowForAdminPage();
        }
      }
      $nbElements = count($ToDisplayFiltered);
      $nbPages = ceil($nbElements/$nbPerPage);
      $curPage = min(max(1, $curPage), $nbPages);
      $DisplayedMissions = array_slice($ToDisplayFiltered, ($curPage-1)*$nbPerPage, $nbPerPage);
    }
    $queryArg = array(self::CST_ONGLET=>self::CST_MISSION,
      self::CST_ORDERBY=>$orderby,
      self::CST_ORDER=>$order
   );
    // Subs
    $numbers = array('all'=>count($Missions),
      self::CST_PENDING=>count($NotPublishedMissions),
      self::CST_PUBLISH=>count($WpPostsPublished),
      self::CST_CURRENT=>count($WpPostsFuture)
   );
    $subs = $this->getSubs($queryArg, $post_status, $numbers);
    // Pagination
    if ($fby_levelId!='') {
      $queryArg['filter-by-levelId'] = $fby_levelId;
    }
    if ($fby_playerId!='') {
      $queryArg['filter-by-playerId'] = $fby_playerId;
    }
    if ($fby_durationId!='') {
      $queryArg['filter-by-durationId'] = $fby_durationId;
    }
    if ($fby_origineId!='') {
      $queryArg['filter-by-origineId'] = $fby_origineId;
    }
    $strPagination = $this->getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements);
    // Filtre de la Difficulté
    $prefix = 'filter-by-';
    $classe = 'custom-select custom-select-sm filters';
    $filters  = $this->LevelServices->getLevelsSelect(__FILE__, __LINE__, $fby_levelId, $prefix, $classe, false, 'Toutes difficultés');
    $filters .= $this->DurationServices->getDurationsSelect(__FILE__, __LINE__, $fby_durationId, $prefix, $classe, false, 'Toutes durées');
    $filters .= $this->PlayerServices->getNbPlayersSelect(__FILE__, __LINE__, $fby_playerId, $prefix, $classe, false, 'Tous joueurs');
    $filters .= $this->OrigineServices->getOriginesSelect(__FILE__, __LINE__, $fby_origineId, $prefix, $classe, false, 'Toutes origines');
    // Sorts
    $queryArg[self::CST_POSTSTATUS] = 'all';
    $queryArg[self::CST_ORDERBY] = 'code';
    $queryArg[self::CST_ORDER] = 'asc';
      if ($orderby=='code') {
      $queryArg[self::CST_ORDER] = ($order=='asc'?'desc':'asc');
    }
    $urlSortCode = $this->getQueryArg($queryArg);
    $queryArg[self::CST_ORDERBY] = self::CST_TITLE;
    $queryArg[self::CST_ORDER] = 'asc';
    if ($orderby==self::CST_TITLE) {
      $queryArg[self::CST_ORDER] = ($order=='asc'?'desc':'asc');
    }
    $urlSortTitle = $this->getQueryArg($queryArg);
    $args = array(
      $strRows,
        // Filtres - 2
      $filters,
      // Url pour créer une nouvelle Mission - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_MISSION, self::CST_POSTACTION=>'add')),
      // subs - 4
      $subs,
      // pagination - 5
      $strPagination,
      // class pour le tri sur code - 6
      ($orderby=='code'?$order:'desc'),
      // url pour le tri sur code - 7
      $urlSortCode,
      // class pour le tri sur title - 8
      ($orderby==self::CST_TITLE?$order=='asc':'desc'),
      // url pour le tri sur title - 9
      $urlSortTitle,
  );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/missions-admin-board.php');
    return vsprintf($str, $args);
  }
}
