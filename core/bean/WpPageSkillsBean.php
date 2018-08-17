<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageSkillsBean
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.00
 */
class WpPageSkillsBean extends WpPageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->SkillServices = new SkillServices();
  }
  /**
   * On arrive rarement en mode direct pour afficher la Page. On passe par une méthode static.
   * @param WpPost $WpPage
   * @return string
   */
  public static function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageSkillsBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * On vérifie si on est ici pour traiter la page des compétences, ou une compétence en particulier.
   * Pour le cas d'une compétence, on retourne une WpPageSkillBean.
   * @return string
   */
  public function getContentPage()
  {
    $skillId = $this->initVar('skillId', -1);
    return ($skillId==-1 ? $this->getListContentPage() : WpPostSkillBean::getStaticPageContent($skillId));
  }
  /**
   * Retourne une liste partielle des compétences
   * @param string $sort_col Selon quelle colonne on trie ? Par défaut : 'name'.
   * @param string $sort_order Dans quel sens on trie ? Par défaut : 'asc'.
   * @param int $nbPerPage Combien de compétences affichées par page ? Par défaut : 10.
   * @param int $curPage Quelle page est affichée ? Par défaut : 1.
   * @param array $arrFilters Quelle chaîne de caractères cherche-t-on dans le champ Description ?
   * @return string
   */
  public function getListContentPage($sort_col='name', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array())
  {
    /**
    * On récupère toutes les compétences répondant aux différents critères.
    * On ne prend que la page recherchée pour l'affichage. La totalité de la requête permet la pagination.
    * On construit chaque ligne du tableau
    */
    $Skills = $this->SkillServices->getSkillsWithFilters(__FILE__, __LINE__, $arrFilters, $sort_col, $sort_order);
    $nbElements = count($Skills);
    $nbPages = ceil($nbElements/$nbPerPage);
    $displayedSkills = array_slice($Skills, $nbPerPage*($curPage-1), $nbPerPage);
    $strBody = '';
    if (!empty($displayedSkills)) {
      foreach ($displayedSkills as $Skill) {
        $SkillBean = new SkillBean($Skill);
        $strBody .= $SkillBean->getRowForSkillsPage();
      }
    }
    /**
     * Construction de la liste des liens vers les différentes pages de la recherche.
     * On les met tous. Réfléchir à faire des intervalles si beaucoup trop de pages.
     */
    $strPagination = $this->getPaginateLis($curPage, $nbPages);
    /**
     * Tableau de données pour l'affichage de la page.
     */
    $args = array(
      ($nbPerPage==10 ? self::CST_SELECTED:''),
      ($nbPerPage==25 ? self::CST_SELECTED:''),
      ($nbPerPage==50 ? self::CST_SELECTED:''),
      // Tri sur le Code - 4
      ($sort_col=='code' ? '_'.$sort_order:''),
      // Tri sur le Nom - 5
      ($sort_col=='name' ? '_'.$sort_order:''),
      // Les lignes du tableau - 6
      $strBody,
      // N° du premier élément - 7
      $nbPerPage*($curPage-1)+1,
      // Nb par page - 8
      min($nbPerPage*$curPage, $nbElements),
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
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-skills.php');
    return vsprintf($str, $args);
  }
  /**
   * Récupération du contenu de la page via une requête Ajax.
   * @param array $post
   * @return string
   */
  public static function staticGetSkillsSortedAndFiltered($post)
  {
    $arrFilters = array();
    if ($post['filters']!='') {
      $arrParams = explode('&', $post['filters']);
      if (!empty($arrParams)) {
        foreach ($arrParams as $arrParam) {
          list($key, $value) = explode('=', $arrParam);
          if ($value!='') {
            $arrFilters[$key]= $value;
          }
        }
      }
    }
  /**
   * On appelle la méthode principale de la classe pour récupérer le contenu.
   * Puis on l'habille en mode json avant d'être retournée.
   */
    $Bean = new WpPageSkillsBean();
    $jsonContent = $Bean->getListContentPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters);
    return '{"page-competences":'.json_encode($jsonContent).'}';
  }
}
