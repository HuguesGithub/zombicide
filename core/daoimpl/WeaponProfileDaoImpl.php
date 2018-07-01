<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WeaponProfileDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WeaponProfileDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('WeaponProfile');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('WeaponProfile', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|WeaponProfile
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new WeaponProfile() : array_shift($Objs));
  }  
}
