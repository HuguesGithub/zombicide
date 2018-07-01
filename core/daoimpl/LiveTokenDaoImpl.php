<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveTokenDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveTokenDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('LiveToken');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('LiveToken', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveToken
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new LiveToken() : array_shift($Objs));
  }  
}
