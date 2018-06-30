<?php
if (!defined('ABSPATH')) { die('Forbidden'); }
/**
 * Classe MissionTileServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTileServices extends LocalServices {
  /**
   * L'objet Dao pour faire les requêtes
   * @var MissionTileDaoImpl $Dao
   */
  protected $Dao;
  
  public function __construct() { $this->Dao = new MissionTileDaoImpl(); }

  private function buildFilters($arrFilters) {
    $arrParams = array();
    $arrParams[] = (isset($arrFilters[self::CST_MISSIONID]) ? $arrFilters[self::CST_MISSIONID] : '%');
    $arrParams[] = (isset($arrFilters[self::CST_COORDX]) ? $arrFilters[self::CST_COORDX] : '%');
    $arrParams[] = (isset($arrFilters['coordY']) ? $arrFilters['coordY'] : '%');
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
  public function getMissionTilesWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param array $post
   */
  public static function staticRotate($post) {
    $MissionTileServices = new MissionTileServices();
    $args = array(self::CST_MISSIONID=>$post[self::CST_MISSIONID], self::CST_COORDX=>$post[self::CST_COORDX], self::CST_COORDY=>$post[self::CST_COORDY]);
    $MissionTiles = $MissionTileServices->getMissionTilesWithFilters(__FILE__, __LINE__, $args);
    if (!empty($MissionTiles)) {
      $MissionTile = array_shift($MissionTiles);
      while (!empty($MissionTiles)) {
        $DelMissionTile = array_shift($MissionTiles);
        $MissionTileServices->delete(__FILE__, __LINE__, $DelMissionTile);
      }
      $MissionTile->setOrientation($post['orientation']);
      $MissionTileServices->update(__FILE__, __LINE__, $MissionTile);
    }
  }
  /**
   * @param array $post
   */
  public static function staticUpdate($post) {
    $MissionTileServices = new MissionTileServices();
    $args = array(self::CST_MISSIONID=>$post[self::CST_MISSIONID], self::CST_COORDX=>$post[self::CST_COORDX], self::CST_COORDY=>$post[self::CST_COORDY]);
    $MissionTiles = $MissionTileServices->getMissionTilesWithFilters(__FILE__, __LINE__, $args);
    if (empty($MissionTiles)) {
      $args['orientation'] = 'N';
      $args['tileId'] = $post['value'];
      $MissionTile = new MissionTile($args);
      $MissionTileServices->insert(__FILE__, __LINE__, $MissionTile);
    } else {
      $MissionTile = array_shift($MissionTiles);
      while (!empty($MissionTiles)) {
        $DelMissionTile = array_shift($MissionTiles);
        $MissionTileServices->delete(__FILE__, __LINE__, $DelMissionTile);
      }
      $MissionTile->setTileId($post['value']);
      $MissionTileServices->update(__FILE__, __LINE__, $MissionTile);
    }
  }

}
?>