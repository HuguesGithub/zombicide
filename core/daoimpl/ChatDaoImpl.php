<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ChatDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ChatDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct() {
  	parent::__construct('Chat');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Chat', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Chat
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new Chat() : array_shift($Objs));
  }
}
