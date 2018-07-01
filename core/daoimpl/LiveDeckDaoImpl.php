<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LiveDeckDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveDeckDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('LiveDeck');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('LiveDeck', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|LiveDeck
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new LiveDeck() : array_shift($Objs));
  }
}
