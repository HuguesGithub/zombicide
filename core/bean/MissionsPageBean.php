<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionsPageBean extends PagePageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    $services = array('Duration', 'Expansion', 'Level', 'Mission', 'Origine', 'Player');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticGetMissionsSortedAndFiltered($post)
  {
    $Bean = new MissionsPageBean();
    $arrFilters = array();
    if ($post['filters']!='') {
      $arrParams = explode('&', $post['filters']);
      if (!empty($arrParams)) {
        foreach ($arrParams as $arrParam) {
          list($key, $value) = explode('=', $arrParam);
          $arrFilters[$key][]= $value;
        }
      }
    }
    $jsonStr = $Bean->getContentPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters);
    return '{"page-missions":'.json_encode($jsonStr).'}';
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new MissionsPageBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage($sort_col='title', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array())
  {
    $strBody = '';
    $Missions = $this->MissionServices->getMissionsWithFiltersIn(__FILE__, __LINE__, $arrFilters, $sort_col, $sort_order);
    $nbElements = count($Missions);
    $nbPages = ceil($nbElements/$nbPerPage);
    $displayedMissions = array_slice($Missions, $nbPerPage*($curPage-1), $nbPerPage);
    if (!empty($displayedMissions)) {
      foreach ($displayedMissions as $Mission) {
        $MissionBean = new MissionBean($Mission);
        $strBody .= $MissionBean->getRowForMissionsPage();
      }
    }
    $strPagination = '';
    for ($i=1; $i<=$nbPages; $i++) {
      $strPagination .= '<li class="page-item'.($i==$curPage?' disabled':'').'"><a class="page-link ';
      $strPagination .= 'ajaxAction" href="#" data-paged="'.$i.'" data-ajaxaction="paged">'.$i.'</a></li>';
    }
    $hasFilters = false;
    $arrLevelIds = '';
    $Levels = $this->LevelServices->getLevelsWithFilters(__FILE__, __LINE__);
    if (isset($arrFilters[self::CST_LEVELID]) && !empty($arrFilters[self::CST_LEVELID])) {
      $arrLevelIds = $arrFilters[self::CST_LEVELID];
      foreach ($arrFilters[self::CST_LEVELID] as $id) {
        $name = $this->getNameById($Levels, $id);
        $hasFilters = true;
      }
    }
    $arrPlayerIds = '';
    $Players = $this->PlayerServices->getPlayersWithFilters(__FILE__, __LINE__);
    if (isset($arrFilters[self::CST_PLAYERID]) && !empty($arrFilters[self::CST_PLAYERID])) {
      $arrPlayerIds = $arrFilters[self::CST_PLAYERID];
      foreach ($arrFilters[self::CST_PLAYERID] as $id) {
        $name = $this->getNameById($Players, $id);
        $hasFilters = true;
      }
    }
    $arrDurationIds = '';
    $Durations = $this->DurationServices->getDurationsWithFilters(__FILE__, __LINE__);
    if (isset($arrFilters[self::CST_DURATIONID]) && !empty($arrFilters[self::CST_DURATIONID])) {
      $arrDurationIds = $arrFilters[self::CST_DURATIONID];
      foreach ($arrFilters[self::CST_DURATIONID] as $id) {
        $name = $this->getNameById($Durations, $id);
        $hasFilters = true;
      }
    }
    $arrOrigineIds = '';
    $Origines = $this->OrigineServices->getOriginesWithFilters(__FILE__, __LINE__);
    if (isset($arrFilters[self::CST_ORIGINEID]) && !empty($arrFilters[self::CST_ORIGINEID])) {
      $arrOrigineIds = $arrFilters[self::CST_ORIGINEID];
      foreach ($arrFilters[self::CST_ORIGINEID] as $id) {
        $name = $this->getNameById($Origines, $id);
        $hasFilters = true;
      }
    }
    $arrExpansionIds = '';
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array('nbMissions'=>1));
    if (isset($arrFilters[self::CST_EXPANSIONID]) && !empty($arrFilters[self::CST_EXPANSIONID])) {
      $arrExpansionIds = $arrFilters[self::CST_EXPANSIONID];
      foreach ($arrFilters[self::CST_EXPANSIONID] as $id) {
        $name = $this->getNameById($Expansions, $id);
        $hasFilters = true;
      }
    }
    $selectClasses = 'custom-select custom-select-sm filters';
    $args = array(
      ($nbPerPage==10 ? self::CST_SELECTED:''),
      ($nbPerPage==25 ? self::CST_SELECTED:''),
      ($nbPerPage==50 ? self::CST_SELECTED:''),
      // Tri sur le Code - 4
      ($sort_col=='code' ? '_'.$sort_order:''),
      // Tri sue le Titre - 5
      ($sort_col=='title' ? '_'.$sort_order:''),
      // Filtre sur Difficulté
      $this->LevelServices->getLevelsSelectAlreadyRequested(__FILE__, __LINE__, $Levels, $arrLevelIds, '', $selectClasses, true),
      // Filtre sur Joueurs
      $this->PlayerServices->getNbPlayersSelect(__FILE__, __LINE__, $arrPlayerIds, '', $selectClasses, true),
      // Les lignes du tableau - 8
      $strBody,
      // N° du premier élément - 9
      $nbPerPage*($curPage-1)+1,
      // Nb par page - 10
      min($nbPerPage*$curPage, $nbElements),
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
      $this->DurationServices->getDurationsSelect(__FILE__, __LINE__, $arrDurationIds, '', $selectClasses, true),
      // Filtre sur Origine - 17
      $this->OrigineServices->getOriginesSelect(__FILE__, __LINE__, $arrOrigineIds, '', $selectClasses, true),
        // Doit-on afficher les filtres ? - 18
        (!$hasFilters ? 'hidden' : ''),
      // Filtre sur Extension - 19
      ($this->isAdmin() ? $this->ExpansionServices->getExpansionsSelectAlreadyRequested(__FILE__, __LINE__, $Expansions, $arrExpansionIds, '', $selectClasses, true) : ''),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-missions.php');
    return vsprintf($str, $args);
  }
  private function getNameById($Objs, $id)
  {
    $returned = '';
    if (!empty($Objs)) {
      foreach ($Objs as $Obj) {
        if ($Obj->getId() == $id) {
          if ($Obj instanceof Level || $Obj instanceof Origine) {
            $returned = $Obj->getName();
          } elseif ($Obj instanceof Player) {
            $returned = $Obj->getNbJoueurs();
          } elseif ($Obj instanceof Duration) {
            $returned = $Obj->getStrDuree();
          }
        }
      }
    }
    return $returned;
  }
  
}
