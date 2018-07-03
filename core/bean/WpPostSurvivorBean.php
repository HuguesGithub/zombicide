<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * WpPostSurvivorBean
 */
class WpPostSurvivorBean extends MainPageBean
{
  /**
   * Constructeur
   */
  public function __construct($WpPost='')
  {
    $services = array('Survivor');
    parent::__construct($services);
    $this->WpPost = $WpPost;
  }
  public function getSurvivorPageContent($Survivor)
  {
    $WpBean = new SurvivorBean($Survivor);
    $strType  = '';
    if ($Survivor->isZombivor()) {
      $strType .= '<div data-id="'.$Survivor->getId().'" data-type="zombivant" class="changeProfile">';
      $strType .= '<i class="far fa-square pointer"></i> Zombivant</div>';
      if ($Survivor->isUltimate()) {
        $strType .= '&nbsp;<div data-id="'.$Survivor->getId().'" data-type="ultimate" class="changeProfile">';
        $strType .= '<i class="far fa-square pointer"></i> Ultimate</div>';
      }
    }
    $args = array(
      $WpBean->getAllPortraits(),
      $Survivor->getName(),
      $Survivor->getBackground(),
      $strType,
      $WpBean->getAllSkills(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-survivor.php');
    return vsprintf($str, $args);
  }
  /**
   * @param string $isHome
   * @return string
   */
  public function displayWpPost($isHome=false)
  {
    $WpPost = $this->WpPost;
    $Survivor = $this->getSurvivor();
    $args = array(
    // - 1
    '',
      // Le portrait du Survivant - 2
      $Survivor->getPortraitUrl(),
      // La page de recherche des survivants - 3
      'http://zombicide.jhugues.fr/page-survivants/',
      // - 4
      '',
      // Url du post dédié - 5
      $WpPost->getGuid(),
      // Nom du Survivant - 6
      $Survivor->getName(),
      // Les Compétences du Survivant - 7
      $Survivor->getUlSkills(),
      // Difficulté de la Mission - 8
      '',
      // Nombre de joueurs de la Mission - 9
      '',
      // Durée de la Mission - 10
      '',
      // Le Survivant a-t-il une version Zombivant ? - 11
      $Survivor->isZombivor() ? 'Oui' : 'Non',
      // Background du Survivant - 12
      $WpPost->getPostContent(),
      // Classe additionnelle de l'article - 13
      $Survivor->getStrClassFilters($isHome),
      // Le Survivant a-t-il une version Ultimate ?  - 14
      $Survivor->isUltimate() ? 'Oui' : 'Non',
    '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/article-survivor-extract.php');
    return vsprintf($str, $args);
  }
  /**
   * @return Survivor
   */
  public function getSurvivor()
  {
    $WpPost = $this->WpPost;
    $idSurvivor = $WpPost->getPostMeta('survivorId');
    return $this->SurvivorServices->select(__FILE__, __LINE__, $idSurvivor);
  }
}
