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
        $tag = 'mission';
        parent::__construct($tag, $services);
        $this->title = 'Missions';
    }
    /**
     * @param array $urlParams
     * @return $Bean
     */
    public static function getStaticContentPage($urlParams) {
        $Bean = new AdminMissionPageBean();
        if ( !isset($urlParams['postAction']) ) { return $Bean->getListingPage(); }
        $MissionServices = FactoryServices::getMissionServices();
        $Mission = $MissionServices->select(__FILE__, __LINE__, $urlParams['id']);
        switch ( $urlParams['postAction'] ) {
            case 'add'        :
                return $Bean->getAddPage($Mission);
            break;
            case 'edit'        :
                if ( $Mission->getId() == '' ) { return $Bean->getListingPage(); }
                return $Bean->getEditPage($Mission);
            break;
            case 'trash'    : echo 'trash'; break;
            case 'view'        : echo 'view'; break;
            case 'clone'    : echo 'clone'; break;
            default            : return $Bean->getListingPage(); break;
        }
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
                $this->dealWithExpansions($Mission, $_POST['expansionId']);
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
            $this->dealWithExpansions($Mission, $_POST['expansionId']);
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
            $args['expansionId'] = $value;
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
            $this->getQueryArg(array('onglet'=>'mission')),
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
        if ( $filter_by_levelId!='' ) { $arrFilters['levelId'] = $filter_by_levelId; }
        if ( $filter_by_playerId!='' ) { $arrFilters['playerId'] = $filter_by_playerId; }
        if ( $filter_by_durationId!='' ) { $arrFilters['durationId'] = $filter_by_durationId; }
        if ( $filter_by_origineId!='' ) { $arrFilters['origineId'] = $filter_by_origineId; }
        $orderby = $this->initVar('orderby', 'title');
        $order = $this->initVar('order', 'ASC');
        $curPage = $this->initVar('cur_page', 1);
        $post_status = $this->initVar('post_status', 'all');
        if ( $post_status=='pending' ) { $arrFilters['published'] = '0'; }
        $Missions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
        $NotPublishedMissions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, array('published'=>0));
        $FilteredMissions = $this->MissionServices->getMissionsWithFilters(__FILE__, __LINE__, $arrFilters, $orderby, $order);
        $WpPostsPublished = $this->WpPostServices->getArticles(__FILE__, __LINE__, array('post_status'=>'publish', 'orderby'=>$orderby, 'order'=>$order));
        $WpPostsFuture = $this->WpPostServices->getArticles(__FILE__, __LINE__, array('post_status'=>'future', 'orderby'=>$orderby, 'order'=>$order));
        switch ( $post_status ) {
            case 'publish'    : $ToDisplayMissions = $WpPostsPublished; break;
            case 'future'    : $ToDisplayMissions = $WpPostsFuture; break;
            case 'pending'    :
            case 'all'        :
            default         : $ToDisplayMissions = $FilteredMissions;
                break;
        }
        $nbPerPage = 15;
        if ( $post_status == 'all' || $post_status == 'pending' ) {
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
                    if ( !empty($arrFilters['levelId']) && $Mission->getLevelId()!=$arrFilters['levelId'] ) { continue; }
                    if ( !empty($arrFilters['durationId']) && $Mission->getDurationId()!=$arrFilters['durationId'] ) { continue; }
                    if ( !empty($arrFilters['playerId']) && $Mission->getPlayerId()!=$arrFilters['playerId'] ) { continue; }
                    if ( !empty($arrFilters['origineId']) && $Mission->getOrigineId()!=$arrFilters['origineId'] ) { continue; }
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
        $queryArg = array('onglet'=>'mission', 'orderby'=>$orderby, 'order'=>$order);
        // Subs
        $queryArg['post_status'] = 'all';
        $subs  = '<li class="all"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status=='all'?'current':'').'">Toutes <span class="count">('.count($Missions).')</span></a> |</li>';
        $queryArg['post_status'] = 'pending';
        $subs .= '<li class="pending"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status=='pending'?'current':'').'">Non publiées <span class="count">('.count($NotPublishedMissions).')</span></a> |</li>';
        $queryArg['post_status'] = 'publish';
        $subs .= '<li class="publish"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status=='publish'?'current':'').'">Publiées <span class="count">('.count($WpPostsPublished).')</span></a> |</li>';
        $queryArg['post_status'] = 'future';
        $subs .= '<li class="future"><a href="'.$this->getQueryArg($queryArg).'" class="'.($post_status=='future'?'current':'').'">Planifiées <span class="count">('.count($WpPostsFuture).')</span></a> </li>';
        // Pagination
        if ( $filter_by_levelId!='' ) { $queryArg['filter-by-levelId'] = $filter_by_levelId; }
        if ( $filter_by_playerId!='' ) { $queryArg['filter-by-playerId'] = $filter_by_playerId; }
        if ( $filter_by_durationId!='' ) { $queryArg['filter-by-durationId'] = $filter_by_durationId; }
        if ( $filter_by_origineId!='' ) { $queryArg['filter-by-origineId'] = $filter_by_origineId; }
        $queryArg['post_status'] = $post_status;
        $strPagination   = '<div class="input-group input-group-sm mb-3">';
        $strPagination  .= '<div class="input-group-prepend"><span class="input-group-text"><span class="displaying-num">'.$nbElements.' éléments</span></span></div>';
        $strPagination  .= '<div class="input-group-prepend">';
        $queryArg['cur_page'] = 1;
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==1?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Première">&laquo;</a></div>';
        $queryArg['cur_page'] = max(1, $curPage-1);
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==1?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Précédente">&lsaquo;</a></div>';
        $strPagination  .= '</div>';
        $strPagination  .= '<input class="current-page" name="cur_page" value="'.$curPage.'" size="1" type="text" style="margin: 0;">';
        $strPagination  .= '<div class="input-group-append"><span class="input-group-text"><span class="tablenav-paging-text"> sur <span class="total-pages">'.$nbPages.'</span></span></span></div>';
        $strPagination  .= '<div class="input-group-append">';
        $queryArg['cur_page'] = min($nbPages, $curPage+1);
        $strPagination  .= '<div class="btn btn-outline-secondary'.($curPage==$nbPage?' disabled':'').'" type="button"><a class="page-link" href="'.$this->getQueryArg($queryArg).'" aria-label="Suivante">&rsaquo;</a></div>';
        $queryArg['cur_page'] = $nbPages;
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
        //$filters .= $this->MissionExpansionServices->getExpansionsSelect(__FILE__, __LINE__, '', 'filter-by-', 'custom-select custom-select-sm filters', FALSE, 'Toutes extensions');
        // Sorts
        $queryArg['post_status'] = 'all';
        $queryArg['orderby'] = 'code';
        $queryArg['order'] = ($orderby=='code'?($order=='asc'?'desc':'asc'):'asc');
        $urlSortCode = $this->getQueryArg($queryArg);
        $queryArg['orderby'] = 'title';
        $queryArg['order'] = ($orderby=='title'?($order=='asc'?'desc':'asc'):'asc');
        $urlSortTitle = $this->getQueryArg($queryArg);
        $args = array(
            $strRows,
                // Filtres - 2
            $filters,
          // Url pour créer une nouvelle Mission - 3
            $this->getQueryArg(array('onglet'=>'mission', 'postAction'=>'add')),
          // subs - 4
            $subs,
          // pagination - 5
            $strPagination,
          // class pour le tri sur code - 6
            ($orderby=='code'?$order:'desc'),
          // url pour le tri sur code - 7
            $urlSortCode,
          // class pour le tri sur title - 8
            ($orderby=='title'?$order=='asc':'desc'),
          // url pour le tri sur title - 9
            $urlSortTitle,
        );
        $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/missions-admin-board.php');
        return vsprintf($str, $args);
    }

}
?>