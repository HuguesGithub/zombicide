<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe SkillsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillsPageBean extends PagePageBean {

  public function __construct($WpPage='') {
    $services = array('Skill');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticGetSkillsSortedAndFiltered($post) {
    $Bean = new SkillsPageBean();
    $arrFilters = array();
    if ($post['filters']!='' ) {
      $arrParams = explode('&', $post['filters']);
      if (!empty($arrParams) ) {
        foreach ($arrParams as $arrParam ) {
          list($key, $value) = explode('=', $arrParam);
          $arrFilters[$key]= $value;
        }
      }
    }
    return '{"page-competences":'.json_encode($Bean->getContentPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters)).'}';
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage) {
    $Bean = new SkillsPageBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage($sort_col='name', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array()) {
    $Skills = $this->SkillServices->getSkillsWithFilters(__FILE__, __LINE__, $arrFilters, $sort_col, $sort_order);
    $nbElements = count($Skills);
    $nbPages = ceil($nbElements/$nbPerPage);
    $displayedSkills = array_slice($Skills, $nbPerPage*($curPage-1), $nbPerPage);
    $strBody = '';
    if (!empty($displayedSkills) ) {
      foreach ($displayedSkills as $Skill ) {
        $SkillBean = new SkillBean($Skill);
        $strBody .= $SkillBean->getRowForSkillsPage();
      }
    }
    $strPagination = '';
    for ($i=1; $i<=$nbPages; $i++ ) {
      $strPagination .= '<li class="page-item'.($i==$curPage?' disabled':'').'"><a class="page-link ';
      $strPagination .= 'ajaxAction" href="#" data-paged="'.$i.'" data-ajaxaction="paged">'.$i.'</a></li>';
    }
    $args = array(
      ($nbPerPage==10 ? CST_SELECTED:''),
      ($nbPerPage==25 ? CST_SELECTED:''),
      ($nbPerPage==50 ? CST_SELECTED:''),
      // Tri sur le Code - 4
      ($sort_col=='code' ? '_'.$sort_order:''),
      // Tri sur le Nom - 5
      ($sort_col=='name' ? '_'.$sort_order:''),
      // Les lignes du tableau - 6
      $strBody,
      // N° du premier élément - 7
      $nbPerPage*($curPage-1)+1,
      // Nb par page - 8
      min($nbPerPage*$curPage,$nbElements),
      // Nb Total - 9
      $nbElements,
      // Liste des éléments de la Pagination - 10
      $strPagination,
      // Si page 1, on peut pas revenir à la première
      ($curPage==1?' disabled':''),
      // Si page $nbPages, on peut pas aller à la dernière
      ($curPage==$nbPages?' disabled':''),
      // Nombre de pages - 13
      $nbPages,
    // Filtre sur la Description - 14
    $arrFilters['description'],
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-skills.php' );
    return vsprintf($str, $args);
  }
  
}
?>
