<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveTokenDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveTokenDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('LiveToken'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = LiveToken::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveToken
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new LiveToken()); }
}
