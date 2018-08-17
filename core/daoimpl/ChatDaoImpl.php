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
  public function __construct()
  { parent::__construct('Chat'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Chat::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Chat
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new Chat()); }
  /**
   * @param string $file
   * @param string $line
   * @return array
   */
  public function selectPurgeableChats($file, $line)
  {
    $requete  = $this->selectRequest.$this->fromRequest;
    $requete .= 'WHERE liveId NOT IN (SELECT liveId FROM wp_11_zombicide_live_mission) OR senderId=0;';
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, array()));
  }
  /**
   * @param string $file
   * @param string $line
   * @return array
   */
  public function selectDistinctUsersOnline($file, $line)
  {
    $requete  = 'SELECT DISTINCT(senderId) AS senderId '.$this->fromRequest;
    $requete .= "WHERE senderId<>0 AND timestamp>'".date(self::CST_FORMATDATE, time()-300)."'";
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, array()));
  }

}
