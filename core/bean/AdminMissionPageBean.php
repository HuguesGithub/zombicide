<?php
declare(strict_types=1);
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * AdminMissionPageBean
 * @version 1.0.00
 * @since 1.0.00
 * @author Hugues
 */
class AdminMissionPageBean extends AdminPageBean {
    public function __construct() {
        $services = array('Mission', 'Duration', 'MissionExpansion', 'Level', 'Origine', 'Player');
        $tag = CST_MISSION;
        parent::__construct($tag, $services);
        $this->title = 'Missions';
    }
    /**
     * @param array $urlParams
     * @return $Bean
     */
    public static function getStaticContentPage($urlParams) {
        $Bean = new AdminMissionPageBean();
        if ( !isset($urlParams[CST_POSTACTION]) ) { return $Bean->getListingPage(); }
        $MissionServices = FactoryServices::getMissionServices();
        $Mission = $MissionServices->select(__FILE__, __LINE__, $urlParams['id']);
        switch ( $urlParams[CST_POSTACTION] ) {
            case 'add'     : $returned = $Bean->getAddPage($Mission); break;
            case 'edit'    : $returned = ($Mission->getId() == '' ? $Bean->getListingPage() : $Bean->getEditPage($Mission)); break;
            case 'trash'   : $returned = 'trash'; break;
            case 'view'    : $returned = 'view'; break;
            case 'clone'   : $returned = 'clone'; break;
            default        : $returned = $Bean->getListingPage(); break;
        }
        return $returned;
    }
    /**
     * @param Mission $Mission
     * @return string
     */
    public function getAddPage($Mission) {
        if ( isset($_POST) && !empty($_POST) ) {
            $doInsert = $Mission->initWithPost($_POST);
            if ( $doInsert ) {
                $this->MissionServices->insert(__FILE__, __LINE__, $Mission);
                $Mission->setId(MySQL::getLastInsertId());
                $this->dealWithExpansions($Mission, $_POST[CST_EXPANSIONID]);
                return $this->getListingPage();
            }
        }
        return $this->getAddEditPage($Mission, 'Ajouter une Mission', 'add');
    }
      /**
       * @param Mission $Mission
       * @return string
       */
    public function getEditPage($Mission) {
        if ( isset($_POST) && !empty($_POST) ) {
            $doUpdate = $Mission->updateWithPost($_POST);
            $this->dealWithExpansions($Mission, $_POST[CST_EXPANSIONID]);
            if ( $doUpdate ) {
                $this->MissionServices->update(__FILE__, __LINE__, $Mission);
                return $this->getListingPage();
            }
        }
        return $this->getAddEditPage($Mission, 'Modifier “'.$Mission->getTitle().'”', 'edit');
    }
    private function dealWithExpansions($Mission, $arrExpansions) {
        $MissionExpansions = $Mission->getMissionExpansions();
        $DelMissionExpansions = array();
        $GetMissionExpansions = array();
        // Si au moins une Extension est déjà accrochée à la Mission et qu'il y en a au moins une de sélectionnée
        if ( !empty($arrExpansions) && !empty($MissionExpansions) ) {
            foreach ( $MissionExpansions as $MissionExpansion ) {
                $expansionId = $MissionExpansion->getExpansionId();
                $isSelected = FALSE;
                foreach ( $arrExpansions as $key=>$value ) {
                    // On ne lui fait rien à cette extension.
                    if ( $expansionId == $value) {
                        $isSelected = TRUE;
                        unset($arrExpansions[$key]);
                        array_push($GetMissionExpansions, $MissionExpansion);
                    }
                }
                if ( !$isSelected ) {
                    array_push($DelMissionExpansions, $MissionExpansion);
                }
            }
        }
        // Si des extensions rattachées n'ont pas été validées, on les supprime
        if ( !empty($DelMissionExpansions) ) {
            foreach ( $DelMissionExpansions as $MissionExpansion ) {
                $this->MissionExpansionServices->delete(__FILE__, __LINE__, $MissionExpansion);
            }
        }
        // Si des extensions cochées n'ont pas été traitées, on les ajoute.        
        $args = array('missionId'=>$Mission->getId());
        foreach ( $arrExpansions as $key=>$value ) {
            $args[CST_EXPANSIONID] = $value;
            $MissionExpansion = new MissionExpansion($args);
            $this->MissionExpansionServices->insert(__FILE__, __LINE__, $MissionExpansion);
        }
        $Mission->setMissionExpansions($GetMissionExpansions);
    }
    
    /**
     * @param Mission $Mission
     * @param string $title
     * @param string $postAction
     * @return string
     */
    public function getAddEditPage($Mission, $title, $postAction) {
        $classe = 'custom-select custom-select-sm filters';
        $Bean = new MissionBean($Mission);
        $args = array(
            // Titre de la Mission - 1
            $title,
            // Url de l'action - 2
            '#',
            // Url pour Annuler - 3
            $this->getQueryArg(array(CST_ONGLET=>CST_MISSION)),
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
            $this->MissionExpansionServices->getMissionExpansionsSelect(__FILE__, __LINE__, $Mission, '', $classe, TRUE),
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
    /**
     * @return string
     */
    public function getListingPage() {
        $arrFilters = array();
        $filter_by_levelId = $this->initVar('filter-by-levelId','');
        $filter_by_playerId = $this->initVar('filter-by-playerId','');
        $filter_by_durationId = $this->initVar('filter-by-durationId','');
        $filter_by_origineId = $this->initVar('filter-by-origineId', '');
        if ( $filter_by_levelId!='' ) { $arrFilters[CST_LEVELID] = $filter_by_levelId; }
        if ( $filter_by_playerId!='' ) { $arrFilters[CST_PLAYERID] = $filter_by_playerId; }
        if ( $filter_by_durationId!='' ) { $arrFilters[CST_DURATIONID] = $filter_by_durationId; }
        if ( $filter_by_origineId!='' ) { $arrFilters[CST_ORIGINEID] = $filter_by_origineId; }
        $orderby = $this->initVar(CST_ORDERBY, CST_TITLE);
        $order = $this->initVar(CST_ORDER, 'ASC');
        $curPage = $this->initVar(CST_CURPAGE, 1);
        $post_status = $this->initVar(CST_POSTSTATUS, 'all');
        if ( $post_status==CST_PENDING ) { $arrFilters['published'] = '0'; }
        $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $NotPublishedMissions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('published'=>0));
        $FilteredMissions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, $arrFilters, $orderby, $order);
        $WpPostsPublished = $this->WpPostServices->getArticles(__FILE__, __LINE__, array(CST_POSTSTATUS=>CST_PUBLISH, CST_ORDERBY=>$orderby, CST_ORDER=>$order));
        $WpPostsFuture = $this->WpPostServices->getArticles(__FILE__, __LINE__, array(CST_POSTSTATUS=>CST_FUTURE, CST_ORDERBY=>$orderby, CST_ORDER=>$order));
        switch ( $post_status ) {
            case CST_PUBLISH    : $ToDisplayMissions = $WpPostsPublished; break;
            case CST_FUTURE    : $ToDisplayMissions = $WpPostsFuture; break;
            case CST_PENDING    :
            case 'all'        :
            default         : $ToDisplayMissions = $FilteredMissions;
                break;
        }
        $nbPerPage = 15;
        if ( $post_status == 'all' || $post_status == CST_PENDING ) {
            $nbElements = count($ToDisplayMissions);
            $nbPages = ceil($nbElements/$nbPerPage);
            $curPage = max(1, min($curPage, $nbPages));
            $DisplayedMissions = array_slice($ToDisplayMissions, ($curPage-1)*$nbPerPage, $nbPerPage); 
            if ( !empty($DisplayedMissions) ) {
                foreach ( $DisplayedMissions as $Mission ) {
                    $MissionBean = new MissionBean($Mission);
                    $strRows .= $MissionBean->getRowForAdminPage();
                }
            }
        } else {
            $ToDisplayFiltered = array();
            if ( !empty($ToDisplayMissions) ) {
                foreach ( $ToDisplayMissions as $WpPost ) {
                    $WpPostMissionBean = $WpPost->getBean();
                    $Mission = $WpPostMissionBean->getMission();
                    if ( !empty($arrFilters[CST_LEVELID]) && $Mission->getLevelId()!=$arrFilters[CST_LEVELID] ) { continue; }
                    if ( !empty($arrFilters[CST_DURATIONID]) && $Mission->getDurationId()!=$arrFilters[CST_DURATIONID] ) { continue; }
                    if ( !empty($arrFilters[CST_PLAYERID]) && $Mission->getPlayerId()!=$arrFilters[CST_PLAYERID] ) { continue; }
                    if ( !empty($arrFilters[CST_ORIGINEID]) && $Mission->getOrigineId()!=$arrFilters[CST_ORIGINEID] ) { continue; }
                    $MissionBean = new MissionBean($Mission);
                    array_push($ToDisplayFiltered, $MissionBean);
                }
            }
            if ( !empty($ToDisplayFiltered) ) {
                foreach ( $ToDisplayFiltered as $MissionBean ) {
                    $strRows .= $MissionBean->getRowForAdminPage();
                }
            }
            $nbElements = count($ToDisplayFiltered);
            $nbPages = ceil($nbElements/$nbPerPage);
            $curPage = min(max(1, $curPage), $nbPages);
            $DisplayedMissions = array_slice($ToDisplayFiltered, ($curPage-1)*$nbPerPage, $nbPerPage); 
        }
        $queryArg = array(CST_ONGLET=>CST_MISSION, CST_ORDERBY=>$orderby, CST_ORDER=>$order);
        // Subs
        $queryArg[CST_POSTSTATUS] = 'all';
        $subs  = '<li class="all"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status=='all'?'current':'').'">Toutes <span class="count">('.count($Missions).')</span></a> |</li>';
        $queryArg[CST_POSTSTATUS] = CST_PENDING;
        $subs .= '<li class="pending"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status==CST_PENDING?'current':'').'">Non publiées <span class="count">('.count($NotPublishedMissions).')</span></a> |</li>';
        $queryArg[CST_POSTSTATUS] = CST_PUBLISH;
        $subs .= '<li class="publish"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status==CST_PUBLISH?'current':'').'">Publiées <span class="count">('.count($WpPostsPublished).')</span></a> |</li>';
        $queryArg[CST_POSTSTATUS] = CST_FUTURE;
        $subs .= '<li class="future"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status==CST_FUTURE?'current':'').'">Planifiées <span class="count">('.count($WpPostsFuture).')</span></a> </li>';
        // Pagination
        if ( $filter_by_levelId!='' ) { $queryArg['filter-by-levelId'] = $filter_by_levelId; }
        if ( $filter_by_playerId!='' ) { $queryArg['filter-by-playerId'] = $filter_by_playerId; }
        if ( $filter_by_durationId!='' ) { $queryArg['filter-by-durationId'] = $filter_by_durationId; }
        if ( $filter_by_origineId!='' ) { $queryArg['filter-by-origineId'] = $filter_by_origineId; }
        $queryArg[CST_POSTSTATUS] = $post_status;
        $strPagination   = '<div class="input-group input-group-sm mb-3">';
        $strPagination  .= '<div class="input-group-prepend"><span class="input-group-text"><span class="displaying-num">'.$nbElements.' éléments</span></span></div>';
        $strPagination  .= '<div class="input-group-prepend">';
        $queryArg[CST_CURPAGE] = 1;
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==1?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Première">&laquo;</a></div>';
        $queryArg[CST_CURPAGE] = max(1, $curPage-1);
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==1?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Précédente">&lsaquo;</a></div>';
        $strPagination  .= '</div>';
        $strPagination  .= '<input class="current-page" name="cur_page" value="'.$curPage.'" size="1" type="text" style="margin: 0;">';
        $strPagination  .= '<div class="input-group-append"><span class="input-group-text"><span class="tablenav-paging-text"> sur <span class="total-pages">'.$nbPages.'</span></span></span></div>';
        $strPagination  .= '<div class="input-group-append">';
        $queryArg[CST_CURPAGE] = min($nbPages, $curPage+1);
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==$nbPage?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Suivante">&rsaquo;</a></div>';
        $queryArg[CST_CURPAGE] = $nbPages;
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==$nbPage?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Dernière">&raquo;</a></div>';
        $strPagination  .= '</div>';
        $strPagination  .= '</div>';
        $strPagination  .= '</span>';
        // Filtre de la Difficulté
        $prefix = 'filter-by-';
        $classe = 'custom-select custom-select-sm filters';
        $filters  = $this->LevelServices->getLevelsSelect(__FILE__, __LINE__, $filter_by_levelId, $prefix, $classe, FALSE, 'Toutes difficultés');
        $filters .= $this->DurationServices->getDurationsSelect(__FILE__, __LINE__, $filter_by_durationId, $prefix, $classe, FALSE, 'Toutes durées');
        $filters .= $this->PlayerServices->getNbPlayersSelect(__FILE__, __LINE__, $filter_by_playerId, $prefix, $classe, FALSE, 'Tous joueurs');
        $filters .= $this->OrigineServices->getOriginesSelect(__FILE__, __LINE__, $filter_by_origineId, $prefix  , $classe, FALSE, 'Toutes origines');
        // Sorts
        $queryArg[CST_POSTSTATUS] = 'all';
        $queryArg[CST_ORDERBY] = 'code';
        $queryArg[CST_ORDER] = 'asc';
            if ( $orderby=='code' ) {
        	$queryArg[CST_ORDER] = ($order=='asc'?'desc':'asc');
        }
        $urlSortCode = $this->getQueryArg($queryArg);
        $queryArg[CST_ORDERBY] = CST_TITLE;
        $queryArg[CST_ORDER] = 'asc';
        if ( $orderby==CST_TITLE ) {
        	$queryArg[CST_ORDER] = ($order=='asc'?'desc':'asc');
        }
        $urlSortTitle = $this->getQueryArg($queryArg);
        $args = array(
            $strRows,
                // Filtres - 2
            $filters,
          // Url pour créer une nouvelle Mission - 3
            $this->getQueryArg(array(CST_ONGLET=>CST_MISSION, CST_POSTACTION=>'add')),
          // subs - 4
            $subs,
          // pagination - 5
            $strPagination,
          // class pour le tri sur code - 6
            ($orderby=='code'?$order:'desc'),
          // url pour le tri sur code - 7
            $urlSortCode,
          // class pour le tri sur title - 8
            ($orderby==CST_TITLE?$order=='asc':'desc'),
          // url pour le tri sur title - 9
            $urlSortTitle,
        );
        $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/missions-admin-board.php');
        return vsprintf($str, $args);
    }

}
?>