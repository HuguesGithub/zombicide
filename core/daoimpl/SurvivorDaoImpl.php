<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SurvivorDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Survivor');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Survivor', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Skill
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Survivor() : array_shift($Objs));
  }
  
}
