<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe TileDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class TileDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Tile');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Tile', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Tile
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Tile() : array_shift($Objs));
  }
}
