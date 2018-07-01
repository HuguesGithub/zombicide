<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveMissionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveMissionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('LiveDeck');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('LiveMission', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|EquipmentWeaponProfile
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new LiveMission() : array_shift($Objs));
  }
}
