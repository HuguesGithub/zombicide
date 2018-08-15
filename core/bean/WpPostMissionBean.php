<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPostMissionBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPostMissionBean extends WpPostBean
{
  private $h5Ul = '<h5>%1$s</h5><ul>%2$s</ul>';
  /**
   * Constructeur
   */
  public function __construct($WpPost='')
  {
    parent::__construct();
    $this->MissionServices = new MissionServices();
    $this->WpPost = $WpPost;
  }
  private function getMissionContentObjectives($Mission, $strModel)
  {
    $contentObjs = '';
    $MissionObjectives = $Mission->getMissionObjectives();
    if (!empty($MissionObjectives)) {
      $strObj = '';
      foreach ($MissionObjectives as $MissionObjective) {
        $strObj .= vsprintf($strModel, array($MissionObjective->getTitle(), $MissionObjective->getObjectiveDescription()));
      }
      if ($strObj!='') {
        $contentObjs .= vsprintf($this->h5Ul, array('Objectifs', $strObj));
      }
    }
    return $contentObjs;
  }
  private function getMissionContentRules($Mission, $strModel)
  {
    $contentRules = '';
    $MissionRules = $Mission->getMissionRules();
    if (!empty($MissionRules)) {
      $strMep = '';
      $strRs = '';
      foreach ($MissionRules as $MissionRule) {
        if ($MissionRule->getRuleSetting()==1) {
          $strMep .= vsprintf($strModel, array($MissionRule->getTitle(), $MissionRule->getRuleDescription()));
        } else {
          $strRs .= vsprintf($strModel, array($MissionRule->getTitle(), $MissionRule->getRuleDescription()));
        }
      }
      if ($strMep!='') {
        $contentRules .= vsprintf($this->h5Ul, array('Mise en place', $strMep));
      }
      if ($strRs!='') {
        $contentRules .= vsprintf($this->h5Ul, array('Regles speciales', $strRs));
      }
    }
    return $contentRules;
  }
  /**
   * @param Mission $Mission
   * @return string
   */
  public function getMissionPageContent($Mission)
  {
    $arrF = array('orderby'=> 'rand', 'posts_per_page'=>6, 'post_status'=>'publish');
    $WpPosts = $this->WpPostServices->getArticles(__FILE__, __LINE__, $arrF, 'WpPostMission');
    $strContent = '';
    if (!empty($WpPosts)) {
      foreach ($WpPosts as $WpPost) {
        $WpBean = new WpPostMissionBean($WpPost);
        $strContent .= $WpBean->displayThumbWpPost(true);
      }
    }
    $strModel = '<li class="objRule">%1$s <span class="tooltip"><header>%1$s</header><content>%2$s</content></span></li>';
    $contentRules  = $this->getMissionContentObjectives($Mission, $strModel);
    $contentRules .= $this->getMissionContentRules($Mission, $strModel);
    $media = get_attached_media('image');
    if (!empty($media)) {
      $WpPostMedia = WpPost::convertElement(array_shift($media));
    } else {
      $WpPostMedia = new WpPost();
    }
    $navigationMissions = '';
    $prevPost = get_previous_post();
    if (!empty($prevPost)) {
      $navigationMissions .= '<a href="'.$prevPost->guid.'" class="mission-adjacent-link float-left">'.$prevPost->post_title.'</a>';
    }
    $nextPost = get_next_post();
    if (!empty($nextPost)) {
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
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/article-mission-page.php');
    return vsprintf($str, $args);
  }
  /**
   * @param string $isHome
   * @return string
   */
  public function displayThumbWpPost($isHome=false)
  {
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
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/article-mission-thumb.php');
    return vsprintf($str, $args);
  }
  /**
   * @param string $isHome
   * @return string
   */
  public function displayWpPost($isHome=false)
  {
    $WpPost = $this->WpPost;
    $Mission = $this->getMission();
    $missionImg = 'http://zombicide.jhugues.fr/wp-content/uploads/sites/11'.$WpPost->getPostMeta('missionImg');
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
      $WpPost->getGuid(),
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
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/article-mission-extract.php');
    return vsprintf($str, $args);
  }
  /**
   * @return Mission
   */
  public function getMission()
  {
    $WpPost = $this->WpPost;
    $idMission = $WpPost->getPostMeta('missionId');
    return $this->MissionServices->select(__FILE__, __LINE__, $idMission);
  }
}
