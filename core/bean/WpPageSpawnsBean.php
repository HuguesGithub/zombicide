<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageSpawnsBean
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.00
 */
class WpPageSpawnsBean extends WpPageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->ExpansionServices = new ExpansionServices();
    $this->SpawnServices     = new SpawnServices();
  }
  /**
   * On arrive rarement en mode direct pour afficher la Page. On passe par une méthode static.
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPageContent($WpPage)
  {
    $Bean = new WpPageSpawnsBean($WpPage);
    return $Bean->getListingPage();
  }
  /**
   * Retourne une liste partielle des cartes Invasions
   * @return string
   */
  public function getListingPage()
  {
    /**
    * On récupère toutes les cartes Invasion.
    * On construit chaque ligne du tableau
    */
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), self::CST_DISPLAYRANK);
    $strFilters = '';
    $strSpawns = '';
      if (!empty($Expansions)) {
      foreach ($Expansions as $Expansion) {
        $id = $Expansion->getId();
        $SpawnCards = $this->SpawnServices->getSpawnsWithFilters(__FILE__, __LINE__, array(self::CST_EXPANSIONID=>$id), 'spawnNumber');
        if (empty($SpawnCards)) {
          continue;
        }
        $strFilters .= '<option value="set-'.$id.'">'.$Expansion->getName().'</option>';
        foreach ($SpawnCards as $SpawnCard) {
          $strSpawns .= '<div class="card spawn set-'.$id.'"><img width="320" height="440" src="';
          $strSpawns .= $SpawnCard->getImgUrl().'" alt="#'.$SpawnCard->getSpawnNumber().'"></div>';
        }
      }
    }
    /**
     * Tableau de données pour l'affichage de la page.
     */
    $args = array(
      $strFilters,
      $strSpawns
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-spawncards.php');
    return vsprintf($str, $args);
  }
}
