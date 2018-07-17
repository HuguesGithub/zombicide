<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageSurvivorsBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageSurvivorsBean extends PagePageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->SurvivorServices = FactoryServices::getSurvivorServices();
  }
  /**
   * On arrive rarement en mode direct pour afficher la Page. On passe par une méthode static.
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageSurvivorsBean($WpPage);
    return $Bean->getListingPage();
  }
  /**
   * Retourne une liste partielle des survivants
   * @param string $sort_col Selon quelle colonne on trie ? Par défaut : 'name'.
   * @param string $sort_order Dans quel sens on trie ? Par défaut : 'asc'.
   * @param int $nbPerPage Combien de survivants affichés par page ? Par défaut : 10.
   * @param int $curPage Quelle page est affichée ? Par défaut : 1.
   * @param array $arrFilters
   * @return string
   */
  public function getListingPage($sort_col='name', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array())
  {
    /**
    * On récupère tous les survivants répondant aux différents critères.
    * On ne prend que la page recherchée pour l'affichage. La totalité de la requête permet la pagination.
    * On construit chaque ligne du tableau
    */
    $Survivors = $this->SurvivorServices->getSurvivorsWithFilters(__FILE__, __LINE__, $arrFilters, $sort_col, $sort_order);
    $nbElements = count($Survivors);
    $nbPages = ceil($nbElements/$nbPerPage);
    $displayedSurvivors = array_slice($Survivors, $nbPerPage*($curPage-1), $nbPerPage);
    $strBody = '';
    if (!empty($displayedSurvivors)) {
      foreach ($displayedSurvivors as $Survivor) {
        $SurvivorBean = new SurvivorBean($Survivor);
        $strBody .= $SurvivorBean->getRowForSurvivorsPage();
      }
    }
    /**
     * Construction de la liste des liens vers les différentes pages de la recherche.
     * On les met tous. Réfléchir à faire des intervalles si beaucoup trop de pages.
     */
    $strPagination = $this->getPaginateLis($curPage, $nbPages);
    /**
     * Gestion des différents filtres
     *
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
    $args = array(
      ($nbPerPage==10 ? self::CST_SELECTED:''),
      ($nbPerPage==25 ? self::CST_SELECTED:''),
      ($nbPerPage==50 ? self::CST_SELECTED:''),
      // Tri sur le Nom - 4
      ($sort_col=='name' ? '_'.$sort_order:''),
      // Les lignes du tableau - 5
      $strBody,
      // N° du premier élément - 6
      $nbPerPage*($curPage-1)+1,
      // Nb par page - 7
      min($nbPerPage*$curPage, $nbElements),
      // Nb Total - 8
      $nbElements,
      // Liste des éléments de la Pagination - 9
      $strPagination,
      // Si page 1, on peut pas revenir à la première
      ($curPage==1?' disabled':''),
      // Si page $nbPages, on peut pas aller à la dernière
      ($curPage==$nbPages?' disabled':''),
      // Nombre de pages - 12
      $nbPages,
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-survivors.php');
    return vsprintf($str, $args);
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticGetSurvivorsSortedAndFiltered($post)
  {
    $Bean = new WpPageSurvivorsBean();
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
    $jsonStr = $Bean->getListingPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters);
    return '{"page-survivants":'.json_encode($jsonStr).'}';
  }
}
