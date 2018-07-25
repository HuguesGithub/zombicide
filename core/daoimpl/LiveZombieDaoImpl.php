<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveZombieDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveZombieDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('LiveZombie'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = LiveZombie::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveZombie
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new LiveZombie()); }
}
