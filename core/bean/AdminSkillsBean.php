<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AdminSkillsBean
 * @version 1.0.00
 * @since 1.0.00
 * @author Hugues
 */
class AdminSkillsBean extends AdminPageBean
{
  /**
   * Class Constructor
   **/
  public function __construct()
  {
    $tag = self::CST_SKILL;
    parent::__construct($tag, $services);
    $this->SkillServices = FactoryServices::getSkillServices();
    $this->title = 'Compétences';
  }
  /**
   * @param array $urlParams
   * @return $Bean
   */
  public static function getStaticContentPage($urlParams)
  {
    $Bean = new AdminSkillsBean();
    if (!isset($urlParams[self::CST_POSTACTION])) {
      return $Bean->getListingPage();
    }
    switch ($urlParams[self::CST_POSTACTION]) {
      case 'add'   :
        $returned = $Bean->getAddPage($urlParams['id']);
      break;
      case 'edit'  :
        $returned = $Bean->getEditPage($urlParams['id']);
      break;
      case 'trash' :
        $returned = $Bean->getTrashPage($urlParams['id']);
      break;
      case 'view'  :
        $returned = $Bean->getViewPage($urlParams['id']);
      break;
      case 'clone' :
        $returned = $Bean->getClonePage($urlParams['id']);
      break;
      default      :
        $returned = $Bean->getListingPage();
      break;
    }
    return $returned;
  }
  /**
   * @return string
   */
  public function getListingPage()
  {
    $strRows = '';
    $nbPerPage = 15;
    $curPage = $this->initVar(self::CST_CURPAGE, 1);
    $orderby = $this->initVar(self::CST_ORDERBY, self::CST_NAME);
    $order = $this->initVar(self::CST_ORDER, 'ASC');
    $Skills = $this->SkillServices->getSkillsWithFilters(__FILE__, __LINE__, array(), $orderby, $order);
    $nbElements = count($Skills);
    $nbPages = ceil($nbElements/$nbPerPage);
    $curPage = max(1, min($curPage, $nbPages));
    $DisplayedSkills = array_slice($Skills, ($curPage-1)*$nbPerPage, $nbPerPage);
    if (!empty($DisplayedSkills)) {
      foreach ($DisplayedSkills as $Skill) {
        $SkillBean = new SkillBean($Skill);
        $strRows .= $SkillBean->getRowForAdminPage();
      }
    }
    
    /*
    // Subs
    $numbers = array('all'=>count($Missions),
      self::CST_PENDING=>count($NotPublishedMissions),
      self::CST_PUBLISH=>count($WpPostsPublished),
      self::CST_CURRENT=>count($WpPostsFuture)
   );
    $subs = $this->getSubs($queryArg, $post_status, $numbers);
    // Pagination
    $strPagination = $this->getPagination($queryArg, $post_status, $curPage, $nbPages, $nbElements);
    // Sorts
    $queryArg[self::CST_POSTSTATUS] = 'all';
    $queryArg[self::CST_ORDERBY] = 'name';
    $queryArg[self::CST_ORDER] = 'asc';
      if ($orderby=='code') {
      $queryArg[self::CST_ORDER] = ($order=='asc'?'desc':'asc');
    }
    $urlSortCode = $this->getQueryArg($queryArg);
    $queryArg[self::CST_ORDERBY] = self::CST_TITLE;
    $queryArg[self::CST_ORDER] = 'asc';
    if ($orderby==self::CST_TITLE) {
      $queryArg[self::CST_ORDER] = ($order=='asc'?'desc':'asc');
    }
    $urlSortTitle = $this->getQueryArg($queryArg);
    $args = array(
        // Filtres - 2
      $filters,
      // Url pour créer une nouvelle Mission - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_MISSION, self::CST_POSTACTION=>'add')),
      // subs - 4
      $subs,
      // pagination - 5
      $strPagination,
      // class pour le tri sur code - 6
      ($orderby=='code'?$order:'desc'),
      // url pour le tri sur code - 7
      $urlSortCode,
      // class pour le tri sur title - 8
      ($orderby==self::CST_TITLE?$order=='asc':'desc'),
      // url pour le tri sur title - 9
      $urlSortTitle,
  );
    */
	  $args = array(
      // Liste des compétences affichées - 1
      $strRows,
      // Filtres - 2
      '',
      // Url pour créer une nouvelle Compétence - 3
      $this->getQueryArg(array(self::CST_ONGLET=>self::CST_SKILL, self::CST_POSTACTION=>'add')),
      // Subs - 4
      '',
      // Pagination - 5
      '',
      // class pour le tri sur code - 6
      ($orderby=='code' ? $order : 'desc'),
      // url pour le tri sur code - 7
      $urlSortCode,
      // class pour le tri sur title - 8
      ($orderby==self::CST_NAME ? $order : 'desc'),
      // url pour le tri sur title - 9
      $urlSortTitle,
      '','','','','','','','','','','','','');
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/skill-listing.php');
    return vsprintf($str, $args);
  }























  
  
  
  
  

}
