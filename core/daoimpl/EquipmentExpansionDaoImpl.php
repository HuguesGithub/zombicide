<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentExpansionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentExpansionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('EquipmentExpansion');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = EquipmentExpansion::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|EquipmentExpansion
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new EquipmentExpansion() : array_shift($Objs));
  }
}
