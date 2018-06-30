<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe DurationDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class DurationDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Duration');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Duration', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Duration
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Duration() : array_shift($Objs));
  }
  
}
