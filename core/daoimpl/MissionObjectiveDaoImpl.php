<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionObjectiveDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionObjectiveDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, missionId, objectiveId, title ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_mission_objective ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE missionId LIKE '%s' AND objectiveId LIKE '%s' AND title LIKE '%s' ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_mission_objective (missionId, objectiveId, title) VALUES ('%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_mission_objective SET missionId='%s', objectiveId='%s', title='%s' ";
	
	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('MissionObjective', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|MissionRule
	 */
	public function select($file, $line, $arrParams) {
		$Objs = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Objs) ? new MissionObjective() : array_shift($Objs));
	}
	
}
?>