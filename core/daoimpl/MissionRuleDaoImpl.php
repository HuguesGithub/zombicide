<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionRuleDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionRuleDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, missionId, ruleId, title ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_mission_rule ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE missionId LIKE '%s' AND ruleId LIKE '%s' AND title LIKE '%s' ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_mission_rule (missionId, ruleId, title) VALUES ('%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_mission_rule SET missionId='%s', ruleId='%s', title='%s' ";
	
	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('MissionRule', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|MissionRule
	 */
	public function select($file, $line, $arrParams) {
		$Objs = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Objs) ? new MissionRule() : array_shift($Objs));
	}
	
}
?>