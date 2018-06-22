<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionTileDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTileDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, missionId, tileId, orientation, coordX, coordY ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_mission_tile ";
	/**
	 * Recherche unitaire
	 * @var string $whereId
	 */
	protected $whereId = "WHERE id='%s' ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE missionId LIKE '%s' AND coordX LIKE '%s' AND coordY LIKE '%s' ";
	/**
	 * Règle de tri
	 * @var string $orderBy
	 */
	protected $orderBy = _SQL_PARAMS_ORDERBY_;
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_mission_tile (missionId, tileId, orientation, coordX, coordY) VALUES ('%s', '%s', '%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_mission_tile SET missionId='%s', tileId='%s', orientation='%s', coordX='%s', coordY='%s' ";
	/**
	 * Requête de suppression en base
	 * @var string $delete
	 */
	protected $delete = "DELETE ";
	
	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('MissionTile', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|MissionTile
	 */
	public function select($file, $line, $arrParams) {
		$MissionTiles = $this->selectEntry($file, $line, $arrParams);
		return ( empty($MissionTiles) ? new MissionTile() : array_shift($MissionTiles));
	}
	
}
?>