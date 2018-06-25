<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * PagePageBean
 * @author Hugues
 */
class PagePageBean extends MainPageBean {
  /**
   * WpPost affiché
   * @var WpPost $WpPage
   */
  protected $WpPage;
  /**
   * @param string $post
   * @param array $services
   */
  public function __construct($post='', $services=array()) {
    if ( $post=='' ) { $post = get_post(); }
    if ( get_class($post) == 'WpPost' ) { $this->WpPage = $post; }
    else { $this->WpPage = WpPost::convertElement($post); }
    parent::__construct($services);
  }
  /**
   * @return string|Error404PageBean
   */
  public function getContentPage() {
    switch ( $this->WpPage->getPostName() ) {
      case 'page-competences'      : $returned = SkillsPageBean::getStaticPageContent($this->WpPage); break;
      case 'page-live-pioche-invasion' : $returned = SpawnDeckPageBean::getStaticSpawnDeckContent($this->WpPage); break;
      case 'page-market'         : $returned = MarketPageBean::getStaticPageContent($this->WpPage); break;
      case 'page-missions'       : $returned = MissionsPageBean::getStaticPageContent($this->WpPage); break;
      case 'page-partie-online'     : $returned = OnlinePageBean::getStaticPageContent($this->WpPage); break;
      case 'page-piste-de-des'     : $returned = ToolsPageBean::getStaticPisteContent($this->WpPage); break;
      case 'page-selection-survivants' : $returned = ToolsPageBean::getStaticSurvivorsContent($this->WpPage); break;
      case 'page-equipmentcards'     : $returned = ToolsPageBean::getStaticEquipmentsContent($this->WpPage); break;
      case 'page-spawncards'       : $returned = ToolsPageBean::getStaticInvasionsContent($this->WpPage); break;
      case 'page-survivants'       : $returned = SurvivorsPageBean::getStaticPageContent($this->WpPage); break;
      default              : $returned = new Error404PageBean(); break;
    }
    return $returned;
  }
  
  public function getShellClass() { return ''; }
}
?>
