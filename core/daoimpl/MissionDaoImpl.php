<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe MissionDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionDaoImpl extends LocalDaoImpl
{
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct('Mission');
  }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('Mission', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Mission
   */
  public function select($file, $line, $arrParams)
  {
    $Missions = $this->selectEntry($file, $line, $arrParams);
    return (empty($Missions) ? new Mission() : array_shift($Missions));
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @param array $filters
   * @return array
   */
  public function selectEntriesWithFiltersIn($file, $line, $arrParams, $filters)
  {
    $requete  = $this->selectRequest.$this->fromRequest;
    if (isset($filters[self::CST_EXPANSIONID])) {
      $requete .= 'INNER JOIN wp_11_zombicide_mission_expansion me ON m.id=me.missionId ';
    }
    $requete .= $this->whereFilters;
    if (isset($filters['levelId'])) {
      $requete .= 'AND levelId IN ('.implode(',', $filters['levelId']).') ';
    }
    if (isset($filters['durationId'])) {
      $requete .= 'AND durationId IN ('.implode(',', $filters['durationId']).') ';
    }
    if (isset($filters['playerId'])) {
      $requete .= 'AND playerId IN ('.implode(',', $filters['playerId']).') ';
    }
    if (isset($filters['origineId'])) {
      $requete .= 'AND origineId IN ('.implode(',', $filters['origineId']).') ';
    }
    if (isset($filters[self::CST_EXPANSIONID])) {
      $requete .= 'AND expansionId IN ('.implode(',', $filters[self::CST_EXPANSIONID]).') ';
    }
    $requete .= $this->orderBy;
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, $arrParams));
  }
}
