<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SpawnLiveDeckDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnLiveDeckDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('SpawnLive');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('SpawnLiveDeck', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|SpawnLiveDeck
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new SpawnLiveDeck() : array_shift($Objs));
  }
  
}
