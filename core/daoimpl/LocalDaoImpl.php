<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
define('SQL_PARAMS_WHERE', 'where');
define('SQL_PARAMS_ORDERBY', '__orderby__');
/**
 * Classe LocalDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LocalDaoImpl extends GlobalDaoImpl implements iConstants
{
  /**
   * Recherche unitaire
   * @var string $whereId
   */
  protected $whereId = "WHERE id='%s' ";
  /**
   * Règle de tri
   * @var string $orderBy
   */
  protected $orderBy = SQL_PARAMS_ORDERBY;
  /**
   * Requête de suppression en base
   * @var string $delete
   */
  protected $delete = "DELETE ";
  /**
   * Requête de sélection en base
   * @var string $selectRequest
   */
  protected $selectRequest;
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest;
  /**
   * Requête de recherche en base avec Filtres
   * @var string $whereFilters
   */
  protected $whereFilters;
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert;
  /**
   * Requête d'update en base
   * @var string $update
   */
  protected $update;
  /**
   * Class Constructor
   * @param string $strDao
   */
  public function __construct($strDao='')
  {
    $urlIni = '/wp-content/plugins/zombicide/core/daoimpl/requests.ini';
    $adminUrl = getcwd().$urlIni;
    $arrConfigs = parse_ini_file($adminUrl, true);
    
    $this->selectRequest = $arrConfigs[$strDao]['select'];
    $this->fromRequest = $arrConfigs[$strDao]['from'];
    $this->whereFilters = isset($arrConfigs[$strDao][SQL_PARAMS_WHERE]) ? $arrConfigs[$strDao][SQL_PARAMS_WHERE] : "WHERE 1=1 ";
    $this->insert = $arrConfigs[$strDao]['insert'];
    $this->update = $arrConfigs[$strDao]['update'];
  }
  /**
   * @param string $type
   * @param array $rows
   */
  protected function globalConvertToArray($type, $rows)
  {
    $Items = array();
    if (!empty($rows)) {
      foreach ($rows as $row) {
        switch ($type) {
          case 'Chat'           :
            $Items[] = Chat::convertElement($row);
          break;
          case 'Duration'         :
            $Items[] = Duration::convertElement($row);
          break;
          case 'Equipment'        :
            $Items[] = Equipment::convertElement($row);
          break;
          case 'EquipmentExpansion'   :
            $Items[] = EquipmentExpansion::convertElement($row);
          break;
          case 'EquipmentKeyword'     :
            $Items[] = EquipmentKeyword::convertElement($row);
          break;
          case 'EquipmentWeaponProfile' :
            $Items[] = EquipmentWeaponProfile::convertElement($row);
          break;
          case 'Expansion'        :
            $Items[] = Expansion::convertElement($row);
          break;
          case 'Keyword'        :
            $Items[] = Keyword::convertElement($row);
          break;
          case 'Level'          :
            $Items[] = Level::convertElement($row);
          break;
          case 'Live'           :
            $Items[] = Live::convertElement($row);
          break;
          case 'LiveDeck'         :
            $Items[] = LiveDeck::convertElement($row);
          break;
          case 'Market'         :
            $Items[] = Market::convertElement($row);
          break;
          case 'Mission'        :
            $Items[] = Mission::convertElement($row);
          break;
          case 'MissionExpansion'     :
            $Items[] = MissionExpansion::convertElement($row);
          break;
          case 'MissionObjective'     :
            $Items[] = MissionObjective::convertElement($row);
          break;
          case 'MissionRule'      :
            $Items[] = MissionRule::convertElement($row);
          break;
          case 'MissionTile'      :
            $Items[] = MissionTile::convertElement($row);
          break;
          case 'Objective'        :
            $Items[] = Objective::convertElement($row);
          break;
          case 'Origine'        :
            $Items[] = Origine::convertElement($row);
          break;
          case 'Player'         :
            $Items[] = Player::convertElement($row);
          break;
          case 'Rule'           :
            $Items[] = Rule::convertElement($row);
          break;
          case 'Skill'          :
            $Items[] = Skill::convertElement($row);
          break;
          case 'Spawn'          :
            $Items[] = Spawn::convertElement($row);
          break;
          case 'SpawnLiveDeck'      :
            $Items[] = SpawnLiveDeck::convertElement($row);
          break;
          case 'Survivor'         :
            $Items[] = Survivor::convertElement($row);
          break;
          case 'SurvivorSkill'      :
            $Items[] = SurvivorSkill::convertElement($row);
          break;
          case 'Tile'           :
            $Items[] = Tile::convertElement($row);
          break;
          case 'Token'          :
            $Items[] = Token::convertElement($row);
          break;
          case 'WeaponProfile'      :
            $Items[] = WeaponProfile::convertElement($row);
          break;
          default                    :
            echo 'Must add ['.$type.'] in Zomb::LocalDaoImpl.<br>';
          break;
        }
      }
    }
    return $Items;
  }
  
}
