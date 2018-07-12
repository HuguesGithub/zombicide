<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentLiveDeckDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentLiveDeckDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('EquipmentLive');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('EquipmentLiveDeck', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|EquipmentLiveDeck
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new EquipmentLiveDeck() : array_shift($Objs));
  }
  
}
