<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe PlayerDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class PlayerDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Player');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Player', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Player
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Player() : array_shift($Objs));
  }
  
}
