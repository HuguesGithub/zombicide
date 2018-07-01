<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MarketDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MarketDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Market');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Market', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Market
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Market() : array_shift($Objs));
  }
}
