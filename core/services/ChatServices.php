<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ChatServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ChatServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var ChatDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct()
  { $this->Dao = new ChatDaoImpl(); }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters['liveId']) ? $arrFilters['liveId'] : '-1');
    $arrParams[] = (isset($arrFilters['sendToId']) ? $arrFilters['sendToId'] : '-1');
    $arrParams[] = (isset($arrFilters['senderId']) ? $arrFilters['senderId'] : '-1');
    $arrParams[] = (isset($arrFilters[self::CST_TIMESTAMP]) ? $arrFilters[self::CST_TIMESTAMP] : '2018-06');
    return $arrParams;
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getChatsWithFilters($file, $line, $arrFilters=array(), $orderby=self::CST_TIMESTAMP, $order='desc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
}
?>