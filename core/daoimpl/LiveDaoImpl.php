<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Live');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Live', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveDeck
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Live() : array_shift($Objs));
  }
}
