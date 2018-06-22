<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionTileServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTileServices extends GlobalServices {
	/**
	 * L'objet Dao pour faire les requêtes
	 * @var MissionTileDaoImpl $Dao
	 */
	protected $Dao;
	
	public function __construct() { $this->Dao = new MissionTileDaoImpl(); }

	private function buildFilters($arrFilters) {
		$arrParams = array();
		$arrParams[] = ( isset($arrFilters['missionId']) ? $arrFilters['missionId'] : '%' );
		$arrParams[] = ( isset($arrFilters['coordX']) ? $arrFilters['coordX'] : '%' );
		$arrParams[] = ( isset($arrFilters['coordY']) ? $arrFilters['coordY'] : '%' );
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
		$arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
		return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
	}
	/**
	 * @param array $post
	 */
	public static function staticRotate($post) {
		$MissionTileServices = new MissionTileServices();
		$args = array('missionId'=>$post['missionId'], 'coordX'=>$post['coordX'], 'coordY'=>$post['coordY']);
		$MissionTiles = $MissionTileServices->getMissionTilesWithFilters(__FILE__, __LINE__, $args);
		if ( !empty($MissionTiles) ) {
			$MissionTile = array_shift($MissionTiles);
			while ( !empty($MissionTiles) ) {
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
		$args = array('missionId'=>$post['missionId'], 'coordX'=>$post['coordX'], 'coordY'=>$post['coordY']);
		$MissionTiles = $MissionTileServices->getMissionTilesWithFilters(__FILE__, __LINE__, $args);
		if ( empty($MissionTiles) ) {
			$args['orientation'] = 'N';
			$args['tileId'] = $post['value'];
			$MissionTile = new MissionTile($args);
			$MissionTileServices->insert(__FILE__, __LINE__, $MissionTile);
		} else {
			$MissionTile = array_shift($MissionTiles);
			while ( !empty($MissionTiles) ) {
				$DelMissionTile = array_shift($MissionTiles);
				$MissionTileServices->delete(__FILE__, __LINE__, $DelMissionTile);
			}
			$MissionTile->setTileId($post['value']);
			$MissionTileServices->update(__FILE__, __LINE__, $MissionTile);
		}
	}

}
?>