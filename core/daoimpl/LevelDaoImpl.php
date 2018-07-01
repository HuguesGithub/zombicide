<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LevelDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LevelDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Level');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Level', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Level
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Level() : array_shift($Objs));
  }
  
}
