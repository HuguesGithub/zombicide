<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SurvivorsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorsPageBean extends PagePageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    $services = array('Survivor');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param array $post
   */
  public static function staticGetRandomTeam($post)
  {
    $Bean = new SurvivorsPageBean();
    return '{"page-selection-result":'.json_encode($Bean->getSelectionTeam($post)).'}';
  }
  /**
   * @param array $post
   */
  public function getSelectionTeam($post)
  {
    $nbMax = $post['nbSurvSel'];
    $arrValues = explode(',', $post['value']);
    shuffle($arrValues);
    $nb = 0;
    $strReturned  = '';
    while (!empty($arrValues) && $nb<$nbMax) {
      $value = array_shift($arrValues);
      $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $value);
      $Bean = new SurvivorBean($Survivor);
      $strReturned .= $Bean->getVisitCard('col-12 col-md-6');
      $nb++;
    }
    return $strReturned;
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticGetSurvivorsSortedAndFiltered($post)
  {
    $Bean = new SurvivorsPageBean();
    $arrFilters = array();
    if ($post['filters']!='') {
      $arrParams = explode('&', $post['filters']);
      if (!empty($arrParams)) {
        foreach ($arrParams as $arrParam) {
          list($key, $value) = explode('=', $arrParam);
          $arrFilters[$key]= $value;
        }
      }
    }
    $strJson = $Bean->getListingContentPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters);
    return '{"page-survivants":'.json_encode($strJson).'}';
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public static function getStaticPageContent($WpPage)
  {
    $Bean = new SurvivorsPageBean($WpPage);
    return $Bean->getListingContentPage();
  }
  /**
   */
  public function getListingContentPage($sort_col='name', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array())
  {
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
    $strPagination = '';
    for ($i=1; $i<=$nbPages; $i++) {
      $strPagination .= '<li class="page-item'.($i==$curPage?' disabled':'').'"><a class="page-link ';
      $strPagination .= 'ajaxAction" href="#" data-paged="'.$i.'" data-ajaxaction="paged">'.$i.'</a></li>';
    }
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
}
