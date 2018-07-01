<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionRuleDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionRuleDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('MissionRule');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('MissionRule', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|MissionRule
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new MissionRule() : array_shift($Objs));
  }
}
