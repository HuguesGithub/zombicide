<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SkillsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillsPageBean extends PagePageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    $services = array('Skill', 'SurvivorSkill', 'Survivor');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticGetSkillsSortedAndFiltered($post)
  {
    $Bean = new SkillsPageBean();
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
    $jsonContent = $Bean->getListContentPage($post['colsort'], $post['colorder'], $post['nbperpage'], $post['paged'], $arrFilters);
    return '{"page-competences":'.json_encode($jsonContent).'}';
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new SkillsPageBean($WpPage);
    return $Bean->getContentPage();
  }
  /**
   * {@inheritDoc}
   * @see PagePageBean::getContentPage()
   */
  public function getContentPage()
  {
    $skillId = $this->initVar('skillId', -1);
    if ($skillId==-1) {
      return $this->getListContentPage();
    } else {
      return $this->getSkillContentPage($skillId);
    }
  }
  /**
   * @param int $skillId
   * @return string
   */
  public function getSkillContentPage($skillId)
  {
    $Skill = $this->SkillServices->select(__FILE__, __LINE__, $skillId);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$skillId));
    if (!empty($SurvivorSkills)) {
      foreach ($SurvivorSkills as $SurvivorSkill) {
        $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $SurvivorSkill->getSurvivorId());
        switch ($SurvivorSkill->getTagLevelId()) {
          case 10 :
          case 11 :
            $blueSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          case 20 :
            $yellowSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          case 30 :
          case 31 :
            $orangeSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          case 40 :
          case 41 :
          case 42 :
            $redSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          default :
          break;
        }
      }
    }
    $args = array(
      $Skill->getName(),
      $Skill->getDescription(),
      $this->buildSkillLis($blueSkills, $Survivor, 'blue'),
      $this->buildSkillLis($yellowSkills, $Survivor, 'yellow'),
      $this->buildSkillLis($orangeSkills, $Survivor, 'orange'),
      $this->buildSkillLis($redSkills, $Survivor, 'red'),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-skill.php');
    return vsprintf($str, $args);
  }
  public function buildSkillLis($Skills, $Survivor, $color)
  {
    $strLis = '';
    if (!empty($Skills)) {
      ksort($Skills);
      foreach ($Skills as $key => $Survivor) {
        $strLis .= '<li><a class="badge badge-'.$color.'-skill" href="/page-survivants/?survivorId=';
        $strLis .= $Survivor->getId().'">'.$Survivor->getName().'</a></li>';
      }
    }
    return $strLis;
  }
  public function getListContentPage($sort_col='name', $sort_order='asc', $nbPerPage=10, $curPage=1, $arrFilters=array())
  {
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
    $strPagination = '';
    for ($i=1; $i<=$nbPages; $i++) {
      $strPagination .= '<li class="page-item'.($i==$curPage?' disabled':'').'"><a class="page-link ';
      $strPagination .= 'ajaxAction" href="#" data-paged="'.$i.'" data-ajaxaction="paged">'.$i.'</a></li>';
    }
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
}
