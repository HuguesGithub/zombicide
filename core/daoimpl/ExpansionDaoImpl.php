<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ExpansionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ExpansionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Expansion');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Expansion', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Expansion
   */
  public function select($file, $line, $arrParams)
  {
    $Expansions = $this->selectEntry($file, $line, $arrParams);
    return (empty($Expansions) ? new Expansion() : array_shift($Expansions));
  }
  protected function updateNbMissions($file, $line)
  {
    $subRequest = 'SELECT COUNT(*) FROM wp_11_zombicide_mission_expansion me WHERE me.expansionId=e.id';
    $requete = 'UPDATE wp_11_zombicide_expansion e SET nbMissions = ('.$subRequest.');';
    $this->createEditDeleteEntry($file, $line, $requete);
  }
}
