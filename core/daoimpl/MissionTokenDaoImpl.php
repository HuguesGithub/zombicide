<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionTokenDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTokenDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('MissionToken'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = MissionToken::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|MissionToken
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new MissionToken()); }
}
