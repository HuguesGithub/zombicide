<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe RuleDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class RuleDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Rule');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Rule', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Rule
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Rule() : array_shift($Objs));
  }
}
