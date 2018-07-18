<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageMissionsBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageMissionsBean extends PagePageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->DurationServices  = FactoryServices::getDurationServices();
    $this->ExpansionServices = FactoryServices::getExpansionServices();
    $this->LevelServices     = FactoryServices::getLevelServices();
    $this->MissionServices   = FactoryServices::getMissionServices();
    $this->OrigineServices   = FactoryServices::getOrigineServices();
    $this->PlayerServices    = FactoryServices::getPlayerServices();
  }
  /**
   * On arrive rarement en mode direct pour afficher la Page. On passe par une méthode static.
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageMissionsBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * Retourne une liste partielle des missions
   * @param string $sort_col Selon quelle colonne on trie ? Par défaut : 'name'.
   * @param string $sort_order Dans quel sens on trie ? Par défaut : 'asc'.
   * @param int $nbPerPage Combien de missions affichées par page ? Par défaut : 10.
   * @param int $curPage Quelle page est affichée ? Par défaut : 1.
   * @param array $arrFilters
   * @return string
   */
  public function getContentPage($sort_col='title', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array())
  {
    /**
    * On récupère toutes les missions répondant aux différents critères.
    * On ne prend que la page recherchée pour l'affichage. La totalité de la requête permet la pagination.
    * On construit chaque ligne du tableau
    */
    $Missions = $this->MissionServices->getMissionsWithFiltersIn(__FILE__, __LINE__, $arrFilters, $sort_col, $sort_order);
    $nbElements = count($Missions);
    $nbPages = ceil($nbElements/$nbPerPage);
    $displayedMissions = array_slice($Missions, $nbPerPage*($curPage-1), $nbPerPage);
    $strBody = '';
    if (!empty($displayedMissions)) {
      foreach ($displayedMissions as $Mission) {
        $MissionBean = new MissionBean($Mission);
        $strBody .= $MissionBean->getRowForMissionsPage();
      }
    }
    /**
     * Construction de la liste des liens vers les différentes pages de la recherche.
     * On les met tous. Réfléchir à faire des intervalles si beaucoup trop de pages.
     */
    $strPagination = $this->getPaginateLis($curPage, $nbPages);
  /**
   * Gestion des différents filtres
   */
    $hasFilters = false;
    $Levels = $this->LevelServices->getLevelsWithFilters(__FILE__, __LINE__);
    $arrLevelIds = $this->dealWithFilters($hasFilters, $Levels, $arrFilters, self::CST_LEVELID);
    $Players = $this->PlayerServices->getPlayersWithFilters(__FILE__, __LINE__);
    $arrPlayerIds = $this->dealWithFilters($hasFilters, $Players, $arrFilters, self::CST_PLAYERID);
    $Durations = $this->DurationServices->getDurationsWithFilters(__FILE__, __LINE__);
    $arrDurationIds = $this->dealWithFilters($hasFilters, $Durations, $arrFilters, self::CST_DURATIONID);
    $Origines = $this->OrigineServices->getOriginesWithFilters(__FILE__, __LINE__);
    $arrOrigineIds = $this->dealWithFilters($hasFilters, $Origines, $arrFilters, self::CST_ORIGINEID);
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array('nbMissions'=>1));
    $arrExpansionIds = $this->dealWithFilters($hasFilters, $Expansions, $arrFilters, self::CST_EXPANSIONID);
    /**
     * Tableau de données pour l'affichage de la page.
     */
    $selClass = 'custom-select custom-select-sm filters';
    $args = array(
      ($nbPerPage==10 ? self::CST_SELECTED:''),
      ($nbPerPage==25 ? self::CST_SELECTED:''),
      ($nbPerPage==50 ? self::CST_SELECTED:''),
      // Tri sur le Code - 4
      ($sort_col=='code' ? '_'.$sort_order:''),
      // Tri sue le Titre - 5
      ($sort_col=='title' ? '_'.$sort_order:''),
      // Filtre sur Difficulté
      $this->LevelServices->getLevelsSelectAlreadyRequested(__FILE__, __LINE__, $Levels, $arrLevelIds, '', $selClass, true),
      // Filtre sur Joueurs
      $this->PlayerServices->getNbPlayersSelect(__FILE__, __LINE__, $arrPlayerIds, '', $selClass, true),
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
      $this->ExpansionServices->getExpansionsSelectAlreadyRequested(__FILE__, __LINE__, $Expansions, $arrExpansionIds, '', $selClass, true),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-missions.php');
    return vsprintf($str, $args);
  }
  /**
   * @param boolean $hasFilters
   * @param array $Objs
   * @param array $arrFilters
   * @param string $tag
   * @return array
   */
  private function dealWithFilters(&$hasFilters, $Objs, $arrFilters, $tag)
  {
    $arrIds = '';
    if (isset($arrFilters[$tag]) && !empty($arrFilters[$tag])) {
      $arrIds = $arrFilters[$tag];
      foreach ($arrFilters[$tag] as $id) {
    if ($this->getNameById($Objs, $id)!='') {
          $hasFilters = true;
    }
      }
    }
  return $arrIds;
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticGetMissionsSortedAndFiltered($post)
  {
    $Bean = new WpPageMissionsBean();
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
   * Retourne le Nom d'un objet parmi une liste identifié par son id.
   * @param array $Objs La liste d'objets
   * @param int $id Identifiant de l'objet recherché
   * @return string
   */
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
