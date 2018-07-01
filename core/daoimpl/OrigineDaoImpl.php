<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe OrigineDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class OrigineDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Origine');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Origine', $rows); }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrParams
   * @return Origine
   */
   public function select($file, $line, $arrParams)
   {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Origine() : array_shift($Objs));
  }
}
