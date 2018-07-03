<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * PagePageBean
 * @author Hugues
 */
class PagePageBean extends MainPageBean
{
  /**
   * WpPost affiché
   * @var WpPost $WpPage
   */
  protected $WpPage;
  /**
   * @param string $post
   * @param array $services
   */
  public function __construct($post='', $services=array())
  {
    if ($post=='') {
      $post = get_post();
    }
    if (get_class($post) == 'WpPost') {
      $this->WpPage = $post;
    } else {
      $this->WpPage = WpPost::convertElement($post);
    }
    parent::__construct($services);
  }
  /**
   * @return string|Error404PageBean
   */
  public function getContentPage()
  {
    switch ($this->WpPage->getPostName()) {
      case 'page-competences'          :
        $strReturned = WpPageSkillsBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-live-pioche-invasion' :
        $strReturned = SpawnDeckPageBean::getStaticSpawnDeckContent($this->WpPage);
      break;
      case 'page-market'               :
        $strReturned = MarketPageBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-missions'             :
        $strReturned = MissionsPageBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-partie-online'        :
        $strReturned = OnlinePageBean::getStaticPageContent($this->WpPage);
      break;
      case 'page-piste-de-des'         :
        $strReturned = ToolsPageBean::getStaticPisteContent($this->WpPage);
      break;
      case 'page-selection-survivants' :
        $strReturned = ToolsPageBean::getStaticSurvivorsContent($this->WpPage);
      break;
      case 'page-equipmentcards'       :
        $strReturned = EquipmentsPageBean::getStaticEquipmentsContent($this->WpPage);
      break;
      case 'page-spawncards'           :
        $strReturned = SpawnsPageBean::getStaticInvasionsContent($this->WpPage);
      break;
      case 'page-survivants'           :
        $strReturned = SurvivorsPageBean::getStaticPageContent($this->WpPage);
      break;
      default                          :
        $strReturned = new Error404PageBean();
      break;
    }
    return $strReturned;
  }
  /**
   * {@inheritDoc}
   * @see MainPageBean::getShellClass()
   */
  public function getShellClass()
  { return ''; }
}
