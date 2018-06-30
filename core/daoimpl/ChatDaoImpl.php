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
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, liveId, sendToId, senderId, timestamp, texte ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_chat ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE (liveId LIKE '%s' OR sendToId LIKE '%s' OR senderId LIKE '%s') AND timestamp > '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_chat (liveId, sendToId, senderId, timestamp, texte) VALUES ('%s', '%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_chat SET liveId='%s', sendToId='%s', senderId='%s', timestamp='%s', texte='%s' ";
  
  public function __construct() {}
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
