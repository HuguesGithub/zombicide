<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SpawnDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Spawn');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Spawn', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Spawn
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Spawn() : array_shift($Objs));
  }
}
