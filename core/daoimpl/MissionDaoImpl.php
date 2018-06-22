<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe MissionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT m.id AS id, title, m.code AS code, levelId, playerId, durationId, origineId, width, height, published ";
	// description, origineId, author, officielle, active, missionliveactive, startingXCoord, startingYCoord
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_mission AS m ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE levelId LIKE '%s' AND durationId LIKE '%s' AND playerId LIKE '%s' AND origineId LIKE '%s' AND published LIKE '%s' ";
	//AND missionliveactive LIKE '%s' AND active LIKE '%s' AND width LIKE '%s' AND height LIKE '%s' AND ( code LIKE '%s' OR name LIKE '%s' OR description LIKE '%s') ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_mission (title, code, levelId, playerId, durationId, origineId, width, height, published) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
	//, description, author, officielle, active, missionliveactive, startingXCoord, startingYCoord) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_mission SET title='%s', code='%s', levelId='%s', playerId='%s', durationId='%s', origineId='%s', width='%s', height='%s', published='%s' ";
	//, description='%s', author='%s', officielle='%s', active='%s', missionliveactive='%s', startingYCoord='%s', startingYCoord='%s' ";
	//protected $distinctDimensions = "SELECT DISTINCT CONCAT(width, 'x', height) AS label ";

	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('Mission', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|Mission
	 */
	public function select($file, $line, $arrParams) {
		$Missions = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Missions) ? new Mission() : array_shift($Missions));
	}
/*
	public function selectDistinctDimensions($file, $line, $arrParams) {
		$request = $this->distinctDimensions.$this->fromRequest.$this->orderBy;
		$Missions = $this->selectEntriesAndLogQuery($file, $line, $request, $arrParams);
		return $Missions;
	}
*/
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @param array $filters
	 * @return array
	 */
	public function selectEntriesWithFiltersIn($file, $line, $arrParams, $filters) {
		$requete  = $this->selectRequest.$this->fromRequest;
    if ( isset($filters['expansionId']) ) { $requete .= 'INNER JOIN wp_11_zombicide_mission_expansion me ON m.id=me.missionId '; }
    $requete .= $this->whereFilters;
		if ( isset($filters['levelId']) ) { $requete .= 'AND levelId IN ('.implode(',', $filters['levelId']).') '; }
		if ( isset($filters['durationId']) ) { $requete .= 'AND durationId IN ('.implode(',', $filters['durationId']).') '; }
		if ( isset($filters['playerId']) ) { $requete .= 'AND playerId IN ('.implode(',', $filters['playerId']).') '; }
		if ( isset($filters['origineId']) ) { $requete .= 'AND origineId IN ('.implode(',', $filters['origineId']).') '; }
		if ( isset($filters['expansionId']) ) { $requete .= 'AND expansionId IN ('.implode(',', $filters['expansionId']).') '; }
		$requete .= $this->orderBy;
		return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, $arrParams));
	}
  
}
?>