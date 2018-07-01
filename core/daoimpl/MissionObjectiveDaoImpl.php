<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionObjectiveDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionObjectiveDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
  	parent::__construct('MissionObjective');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('MissionObjective', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|MissionRule
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new MissionObjective() : array_shift($Objs));
  }
  
}
