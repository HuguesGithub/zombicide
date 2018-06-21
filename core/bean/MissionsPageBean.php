<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe MissionsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionsPageBean extends PagePageBean {

	public function __construct($WpPage='') {
		$services = array('Duration', 'Expansion', 'Level', 'Mission', 'Origine', 'Player');
		parent::__construct($WpPage, $services);
	}
	/**
	 * @param array $post
	 * @return string
	 */
	public static function staticGetMissionsSortedAndFiltered($post) {
		$Bean = new MissionsPageBean();
		$arrFilters = array();
		if ( $post['filters']!='' ) {
			$arrParams = explode('&', $post['filters']);
			if ( !empty($arrParams) ) {
				foreach ( $arrParams as $arrParam ) {
					list($key, $value) = explode('=', $arrParam);
					$arrFilters[$key][]= $value;
				}
			}
		}
		return '{"page-missions":'.json_encode($Bean->getContentPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters)).'}';
	}
	/**
	 * @param WpPost $WpPage
	 * @return string
	 */
	public function getStaticPageContent($WpPage) {
		$Bean = new MissionsPageBean($WpPage);
		return $Bean->getContentPage();
	}
	/**
	 * {@inheritDoc}
	 * @see PagePageBean::getContentPage()
	 */
	public function getContentPage($sort_col='title', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array()) {
		$Missions = $this->MissionServices->getMissionsWithFiltersIn(__FILE__, __LINE__, $arrFilters, $sort_col, $sort_order);
		$nbElements = count($Missions);
		$nbPages = ceil($nbElements/$nbPerPage);
		$displayedMissions = array_slice($Missions, $nbPerPage*($curPage-1), $nbPerPage);
		if ( !empty($displayedMissions) ) {
			foreach ( $displayedMissions as $Mission ) {
        $MissionBean = new MissionBean($Mission);
				$strBody .= $MissionBean->getRowForMissionsPage();
			}
		}
		for ( $i=1; $i<=$nbPages; $i++ ) {
			$strPagination .= '<li class="page-item'.($i==$curPage?' disabled':'').'"><a class="page-link ';
			$strPagination .= 'ajaxAction" href="#" data-paged="'.$i.'" data-ajaxaction="paged">'.$i.'</a></li>';
		}
    $hasFilters = FALSE;
    $arrLevelIds = '';
    $Levels = $this->LevelServices->getLevelsWithFilters(__FILE__, __LINE__);
    if ( isset($arrFilters['levelId']) && !empty($arrFilters['levelId']) ) {
      $arrLevelIds = $arrFilters['levelId'];
      foreach ( $arrFilters['levelId'] as $id ) {
        $name = $this->getNameById($Levels, $id);
    		$hasFilters = TRUE;
      }
    }
    $arrPlayerIds = '';
    $Players = $this->PlayerServices->getPlayersWithFilters(__FILE__, __LINE__);
    if ( isset($arrFilters['playerId']) && !empty($arrFilters['playerId']) ) {
      $arrPlayerIds = $arrFilters['playerId'];
      foreach ( $arrFilters['playerId'] as $id ) {
        $name = $this->getNameById($Players, $id);
    		$hasFilters = TRUE;
      }
    }
    $arrDurationIds = '';
    $Durations = $this->DurationServices->getDurationsWithFilters(__FILE__, __LINE__);
    if ( isset($arrFilters['durationId']) && !empty($arrFilters['durationId']) ) {
      $arrDurationIds = $arrFilters['durationId'];
      foreach ( $arrFilters['durationId'] as $id ) {
        $name = $this->getNameById($Durations, $id);
    		$hasFilters = TRUE;
      }
    }
    $arrOrigineIds = '';
    $Origines = $this->OrigineServices->getOriginesWithFilters(__FILE__, __LINE__);
    if ( isset($arrFilters['origineId']) && !empty($arrFilters['origineId']) ) {
      $arrOrigineIds = $arrFilters['origineId'];
      foreach ( $arrFilters['origineId'] as $id ) {
        $name = $this->getNameById($Origines, $id);
    		$hasFilters = TRUE;
      }
    }
    $arrExpansionIds = '';
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array('nbMissions'=>1));
    if ( isset($arrFilters['expansionId']) && !empty($arrFilters['expansionId']) ) {
      $arrExpansionIds = $arrFilters['expansionId'];
      foreach ( $arrFilters['expansionId'] as $id ) {
        $name = $this->getNameById($Expansions, $id);
    		$hasFilters = TRUE;
      }
    }
    $selectClasses = 'custom-select custom-select-sm filters';
		$args = array(
			($nbPerPage==10 ? 'selected':''),
			($nbPerPage==25 ? 'selected':''),
			($nbPerPage==50 ? 'selected':''),
			// Tri sur le Code - 4
			($sort_col=='code' ? '_'.$sort_order:''),
			// Tri sue le Titre - 5
			($sort_col=='title' ? '_'.$sort_order:''),
			// Filtre sur Difficulté
			$this->LevelServices->getLevelsSelectAlreadyRequested(__FILE__, __LINE__, $Levels, $arrLevelIds, '', $selectClasses, TRUE),
			// Filtre sur Joueurs
			$this->PlayerServices->getNbPlayersSelect(__FILE__, __LINE__, $arrPlayerIds, '', $selectClasses, TRUE),
			// Les lignes du tableau - 8
			$strBody,
			// N° du premier élément - 9
			$nbPerPage*($curPage-1)+1,
			// Nb par page - 10
			min($nbPerPage*$curPage,$nbElements),
			// Nb Total - 11
			$nbElements,
			// Liste des éléments de la Pagination - 12
			$strPagination,
			// Si page 1, on peut pas revenir à la première
			($curPage==1?' disabled':''),
			// Si page $nbPages, on peut pas aller à la dernière
			($curPage==$nbPages?' disabled':''),
			// Nombre de pages - 15
			$nbPages,
			// Filtre sur Durée - 16
			$this->DurationServices->getDurationsSelect(__FILE__, __LINE__, $arrDurationIds, '', $selectClasses, TRUE),
			// Filtre sur Origine - 17
			$this->OrigineServices->getOriginesSelect(__FILE__, __LINE__, $arrOrigineIds, '', $selectClasses, TRUE),
      // Doit-on afficher les filtres ? - 18
      (!$hasFilters ? 'hidden' : ''),
			// Filtre sur Extension - 19
			($this->isAdmin() ? $this->ExpansionServices->getExpansionsSelectAlreadyRequested(__FILE__, __LINE__, $Expansions, $arrExpansionIds, '', $selectClasses, TRUE) : ''),
      
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-missions.php' );
		return vsprintf($str, $args);
	}
  private function getNameById($Objs, $id) {
    if ( !empty($Objs) ) {
      foreach ( $Objs as $Obj ) {
        if ( $Obj->getId() == $id ) {
          if ( $Obj instanceof Level || $Obj instanceof Origine ) {
              return $Obj->getName();
          } elseif ( $Obj instanceof Player ) {
              return $Obj->getNbJoueurs();
          } elseif ( $Obj instanceof Duration ) {
              return $Obj->getStrDuree();
          }
        }
      }
    }
    return '';
  }
  
}
?>
