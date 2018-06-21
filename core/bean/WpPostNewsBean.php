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
    /*
		$WpPost = $this->WpPost;
		$Mission = $this->getMission();
		$args = array(
			// Url de la Mission - 1
			$WpPost->getGuid(),
			// Code et Nom de la Mission - 2
			$Mission->getCode().' - '.$Mission->getTitle(),
			// Difficulté de la Mission - 3
			$Mission->getStrDifficulty(),
			// Nombre de joueurs de la Mission - 4
			$Mission->getStrNbJoueurs(),
			// Durée de la Mission - 5
			$Mission->getStrDuree(),
			// Extensions de la Mission - 6
			$Mission->getStrExpansions(),
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/fragments/article-mission-thumb.php' );
		return vsprintf($str, $args);
    */
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
    /*
		$args = array(
			// Href du PDF de la Mission - 1
			$WpPost->getPdfUrl(),
			// L'image associée à la Mission - 2
			$missionImg,
			// La page de recherche des missions - 3
			'http://zombicide.jhugues.fr/page-missions/',
			// - 4
			'', // $Mission->getOrigineName()
			// - 5
			$WpPost->getGuid(), // '#', // TODO à modifier à terme
			// Code et Nom de la Mission - 6
			$Mission->getCode().' - '.$Mission->getTitle(),
			// - 7
			'',
			// Difficulté de la Mission - 8
			$Mission->getStrDifficulty(),
			// Nombre de joueurs de la Mission - 9
			$Mission->getStrNbJoueurs(),
			// Durée de la Mission - 10
			$Mission->getStrDuree(),
			// - 11
			$Mission->getStrExpansions(),
			// Synopsis de la Mission - 12
			$WpPost->getPostContent(),
			// Classe additionnelle de l'article - 13
			$Mission->getStrClassFilters($isHome),
			// Dalles requises - 14
			$Mission->getStrTiles(),
		);
    */
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/fragments/article-news-extract.php' );
		return vsprintf($str, $args);
	}

}
?>