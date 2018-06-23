<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * PostPageBean
 * @author Hugues
 */
class PostPageBean extends MainPageBean {
	/**
	 * WpPost affiché
	 * @var WpPost $WpPost
	 */
	protected $WpPost;
	/**
	 * @param string $post
	 * @param array $services
	 */
	public function __construct($post='', $services=array()) {
		if ( $post=='' ) { $post = get_post(); }
		if ( get_class($post) == 'WpPost' ) { $this->WpPost = $post; }
		else { $this->WpPost = WpPost::convertElement($post); }
		parent::__construct($services);
	}
	/**
	 * @return string|Error404PageBean
	 */
	public function getContentPage() {
		$WpBean = new WpPostMissionBean($this->WpPost);
		$Mission = $WpBean->getMission();
		if ( $Mission->getId()!='' ) {
			return $this->getPostPageContent($Mission);
		} else {
			return new Error404PageBean();
		}
	}
	/**
	 * @param unknown $Mission
	 * @return string
	 */
	public function getPostPageContent($Mission) {
		$WpPosts = $this->WpPostServices->getArticles(__FILE__, __LINE__, array('orderby'=> 'rand', 'posts_per_page'=>6, 'post_status'=>'publish'), 'WpPostMission');
		$strContent = '';
		if ( !empty($WpPosts) ) {
			foreach ( $WpPosts as $WpPost ) {
				$WpBean = new WpPostMissionBean($WpPost);
				$strContent .= $WpBean->displayThumbWpPost(TRUE);
			}
		}
		$strModel = '<li class="objRule">%1$s <span class="tooltip"><header>%1$s</header><content>%2$s</content></span></li>';
		$contentRules = '';
		$MissionObjectives = $Mission->getMissionObjectives();
		if ( !empty($MissionObjectives) ) {
			$strObj = '';
			foreach ( $MissionObjectives as $MissionObjective ) {
				$strObj .= vsprintf($strModel, array($MissionObjective->getTitle(), $MissionObjective->getObjectiveDescription()));
			}
			if ( $strObj!='' ) {
				$contentRules .= '<h5>Objectifs</h5>';
				$contentRules .= '<ul>'.$strObj.'</ul>';
			}
		}
		$MissionRules = $Mission->getMissionRules();
		if ( !empty($MissionRules) ) {
			$strMep = '';
			$strRs = '';
			foreach ( $MissionRules as $MissionRule ) {
				if ( $MissionRule->getRuleSetting()==1 ) {
					$strMep .= vsprintf($strModel, array($MissionRule->getTitle(), $MissionRule->getRuleDescription()));
				} else {
					$strRs .= vsprintf($strModel, array($MissionRule->getTitle(), $MissionRule->getRuleDescription()));
				}
			}
			if ( $strMep!='' ) {
				$contentRules .= '<h5>Mise en place</h5>';
				$contentRules .= '<ul>'.$strMep.'</ul>';
			}
			if ( $strRs!='' ) {
				$contentRules .= '<h5>Regles speciales</h5>';
				$contentRules .= '<ul>'.$strRs.'</ul>';
			}
		}
		$media = get_attached_media( 'image' );
		if ( !empty($media) ) {
			$WpPostMedia = WpPost::convertElement(array_shift($media));
		} else {
			$WpPostMedia = new WpPost();
		}
    $navigationMissions = '';
    $prevPost = get_previous_post();
    if ( !empty($prevPost) ) {
	    $navigationMissions .= '<a href="'.$prevPost->guid.'" class="mission-adjacent-link float-left">'.$prevPost->post_title.'</a>';
    }
    $nextPost = get_next_post();
    if ( !empty($nextPost) ) {
	    $navigationMissions .= '<a href="'.$nextPost->guid.'" class="mission-adjacent-link float-right">'.$nextPost->post_title.'</a>';
    }
		$args = array(
			$Mission->getCode(),
			$Mission->getTitle(),
			$Mission->getStrDifPlaDur(),
			$this->WpPost->getPostContent(),
			$Mission->getStrExpansions(),
			$Mission->getStrTiles(),
			$contentRules,
			$strContent,
			'<img src="'.$WpPostMedia->getGuid().'" alt="'.$Mission->getTitle().'">',
      $navigationMissions,
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/fragments/article-mission-page.php' );
		return vsprintf($str, $args);
	}
	
	public function getShellClass() { return ''; }
}
?>
