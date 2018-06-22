<?php
/**
 * WpPostNewsBean
 */
class WpPostNewsBean extends MainPageBean {

	/**
	 * Constructeur
	 */
	public function __construct($WpPost='') {
		parent::__construct();
		$this->WpPost = $WpPost;
	}
	/**
	 * @param string $isHome
	 * @return string
	 */
	public function displayThumbWpPost($isHome=FALSE) {
    return 'News Thumb';

	}
	/**
	 * @param string $isHome
	 * @return string
	 */
	public function displayWpPost($isHome=FALSE) {
		$WpPost = $this->WpPost;
		$args = array(
			$WpPost->getPostContent(),
			$WpPost->getPostTitle(),
      '','','','','','','',
    );
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/fragments/article-news-extract.php' );
		return vsprintf($str, $args);
	}

}
?>