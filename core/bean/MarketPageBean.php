<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MarketPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MarketPageBean extends PagePageBean {

	public function __construct($WpPage='') {
		$services = array('Market');
		parent::__construct($WpPage, $services);
	}
	/**
	 * @param WpPost $WpPage
	 * @return string
	 */
	public function getStaticPageContent($WpPage) {
		$Bean = new MarketPageBean($WpPage);
		return $Bean->getContentPage();
	}
	/**
	 * {@inheritDoc}
	 * @see PagePageBean::getContentPage()
	 */
	public function getContentPage() {
		$strBody = '';
		$WpPosts = $this->WpPostServices->getArticles(__FILE__, __LINE__, array('orderby'=> 'rand', 'posts_per_page'=>-1, 'post_status'=>'private'));
		if ( !empty($WpPosts) ) {
			foreach ( $WpPosts as $WpPost ) {
        		$Market = Market::convertWpPost($WpPost);
				$MarketBean = new MarketBean($Market);
				$strBody .= $MarketBean->getVisitCard();
			}
		}
		$args = array(
			$strBody,
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-market.php' );
		return vsprintf($str, $args);
	}
  
}
?>
