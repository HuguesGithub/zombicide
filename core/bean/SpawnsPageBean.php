<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SpawnsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnsPageBean extends PagePageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    $services = array('Expansion', 'Spawn');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param WpPage $WpPage
   * @return string
   */
  public function getStaticInvasionsContent($WpPage)
  {
    $Bean = new SpawnsPageBean($WpPage);
    return $Bean->getInvasionsContent();
  }
  /**
   * @return string
   */
  public function getInvasionsContent()
  {
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
    $args = array(
      $strFilters,
      $strSpawns
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-spawncards.php');
    return vsprintf($str, $args);
  }
}
