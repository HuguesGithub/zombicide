<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorActionServices
 * @since 1.0.01
 * @version 1.0.01
 * @author Hugues
 */
class LiveSurvivorActionServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var LiveSurvivorActionDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new LiveSurvivorActionDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters[self::CST_LIVESURVIVORID]) ? $arrFilters[self::CST_LIVESURVIVORID] : '%');
    $arrParams[] = (isset($arrFilters[self::CST_ACTIONID]) ? $arrFilters[self::CST_ACTIONID] : '%');
    return $arrParams;
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getLiveSurvivorActionsWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param LiveSurvivor $LiveSurvivor
   * @return array
   */
  public function initLiveSurvivorActions($LiveSurvivor)
  {
    // On va retourner la liste des Actions disponibles.
    $LiveSurvivorActions = array();
    $args = array(self::CST_LIVESURVIVORID=>$LiveSurvivor->getId());
    $LiveSurvivorSkills = $LiveSurvivor->getLiveSurvivorSkills();
    while (!empty($LiveSurvivorSkills)) {
      $LiveSurvivorSkill = array_shift($LiveSurvivorSkills);
      if ($LiveSurvivorSkill->isLocked()) {
        continue;
      }
      $args[self::CST_ACTIONID] = $LiveSurvivorSkill->getSkillId();
      array_push($LiveSurvivorActions, new LiveSurvivorAction($args));
    }
    // Et on rajoute les 3 Actions de base...
    $args[self::CST_ACTIONID] = 1;
    array_push($LiveSurvivorActions, new LiveSurvivorAction($args));
    array_push($LiveSurvivorActions, new LiveSurvivorAction($args));
    array_push($LiveSurvivorActions, new LiveSurvivorAction($args));
    return $LiveSurvivorActions;
  }
}
