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
			case 'page-competences' : return SkillsPageBean::getStaticPageContent($this->WpPage); break;
      case 'page-live-pioche-invasion' : return SpawnDeckPageBean::getStaticSpawnDeckContent($this->WpPage); break;
			case 'page-market' : return MarketPageBean::getStaticPageContent($this->WpPage); break;
			case 'page-missions'	: return MissionsPageBean::getStaticPageContent($this->WpPage); break;
			case 'page-partie-online'	: return OnlinePageBean::getStaticPageContent($this->WpPage); break;
			case 'page-piste-de-des'	: return ToolsPageBean::getStaticPisteContent($this->WpPage); break;
			case 'page-selection-survivants'	: return ToolsPageBean::getStaticSurvivorsContent($this->WpPage); break;
      case 'page-equipmentcards'	: return ToolsPageBean::getStaticEquipmentsContent($this->WpPage); break;
      case 'page-spawncards'	: return ToolsPageBean::getStaticInvasionsContent($this->WpPage); break;
			case 'page-survivants'	: return SurvivorsPageBean::getStaticPageContent($this->WpPage); break;
			default : return new Error404PageBean(); break;
		}
	}
	
	public function getShellClass() { return ''; }
}
?>
