<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveSurvivorActionDaoImpl
 * @author Hugues.
 * @version 1.0.01
 * @since 1.0.01
 */
class LiveSurvivorActionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  { parent::__construct('LiveSurvivorAction'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = LiveSurvivorAction::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveSurvivorAction
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new LiveSurvivorAction()); }
}
