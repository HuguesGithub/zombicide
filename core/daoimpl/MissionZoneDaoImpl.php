<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionZoneDaoImpl
 * @author Hugues.
 * @version 1.0.01
 * @since 1.0.01
 */
class MissionZoneDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('MissionZone'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = MissionZone::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|MissionZone
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new MissionZone()); }
}
