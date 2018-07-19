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
  { parent::__construct('Mission'); }
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        $Items[] = Mission::convertElement($row);
      }
    }
    return $Items;
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|Mission
   */
  public function select($file, $line, $arrParams)
  { return parent::localSelect($file, $line, $arrParams, new Mission()); }
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
    if (isset($filters[self::CST_LEVELID])) {
      $requete .= 'AND levelId IN ('.implode(',', $filters[self::CST_LEVELID]).') ';
    }
    if (isset($filters[self::CST_DURATIONID])) {
      $requete .= 'AND durationId IN ('.implode(',', $filters[self::CST_DURATIONID]).') ';
    }
    if (isset($filters[self::CST_PLAYERID])) {
      $requete .= 'AND playerId IN ('.implode(',', $filters[self::CST_PLAYERID]).') ';
    }
    if (isset($filters[self::CST_ORIGINEID])) {
      $requete .= 'AND origineId IN ('.implode(',', $filters[self::CST_ORIGINEID]).') ';
    }
    if (isset($filters[self::CST_EXPANSIONID])) {
      $requete .= 'AND expansionId IN ('.implode(',', $filters[self::CST_EXPANSIONID]).') ';
    }
    $requete .= $this->orderBy;
    return $this->convertToArray($this->selectEntriesAndLogQuery($file, $line, $requete, $arrParams));
  }
}
