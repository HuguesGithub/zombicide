
<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe FactoryServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class FactoryServices
{
  /**
   * Retourne les Services relatifs au Chat
   */
  public static function getChatServices()
  { return new ChatServices(); }
  /**
   * Retourne les Services relatifs à Equipment
   */
  public static function getEquipmentServices()
  { return new EquipmentServices(); }
  /**
   * Retourne les Services relatifs à EquipmentExpansion
   */
  public static function getEquipmentExpansionServices()
  { return new EquipmentExpansionServices(); }
  /**
   * Retourne les Services relatifs à EquipmentKeyword
   */
  public static function getEquipmentKeywordServices()
  { return new EquipmentKeywordServices(); }
  /**
   * Retourne les Services relatifs à EquipmentLive
   */
  public static function getEquipmentLiveServices()
  { return new EquipmentLiveServices(); }
  /**
   * Retourne les Services relatifs à EquipmentLiveDeck
   */
  public static function getEquipmentLiveDeckServices()
  { return new EquipmentLiveDeckServices(); }
  /**
   * Retourne les Services relatifs à EquipmentWeaponProfile
   */
  public static function getEquipmentWeaponProfileServices()
  { return new EquipmentWeaponProfileServices(); }
  /**
   * Retourne les Services relatifs à Expansion
   */
  public static function getExpansionServices()
  { return new ExpansionServices(); }
  /**
   * Retourne les Services relatifs à GenKey
   */
  public static function getGenKeyServices()
  { return new GenKeyServices(); }
  /**
   * Retourne les Services relatifs à Invasion
   */
  public static function getInvasionServices()
  { return new InvasionServices(); }
  /**
   * Retourne les Services relatifs à InvasionLive
   */
  public static function getInvasionLiveServices()
  { return new InvasionLiveServices(); }
  /**
   * Retourne les Services relatifs à Keyword
   */
  public static function getKeywordServices()
  { return new KeywordServices(); }
  /**
   * Retourne les Services relatifs à Mission
   */
  public static function getMissionServices()
  { return new MissionServices(); }
  /**
   * Retourne les Services relatifs à Duration
   */
  public static function getDurationServices()
  { return new DurationServices(); }
  /**
   * Retourne les Services relatifs à MissionExpansion
   */
  public static function getMissionExpansionServices()
  { return new MissionExpansionServices(); }
  /**
   * Retourne les Services relatifs à Level
   */
  public static function getLevelServices()
  { return new LevelServices(); }
  /**
   * Retourne les Services relatifs à Live
   */
  public static function getLiveServices()
  { return new LiveServices(); }
  /**
   * Retourne les Services relatifs à LiveMission
   */
  public static function getLiveMissionServices()
  { return new LiveMissionServices(); }
  /**
   * Retourne les Services relatifs à LiveToken
   */
  public static function getLiveTokenServices()
  { return new LiveTokenServices(); }
  /**
   * Retourne les Services relatifs à Market
   */
  public static function getMarketServices()
  { return new MarketServices(); }
  /**
   * Retourne les Services relatifs à MissionLive
   */
  public static function getMissionLiveServices()
  { return new MissionLiveServices(); }
  /**
   * Retourne les Services relatifs à MissionLiveToken
   */
  public static function getMissionLiveTokenServices()
  { return new MissionLiveTokenServices(); }
  /**
   * Retourne les Services relatifs à MissionObjective
   */
  public static function getMissionObjectiveServices()
  { return new MissionObjectiveServices(); }
  /**
   * Retourne les Services relatifs à MissionRule
   */
  public static function getMissionRuleServices()
  { return new MissionRuleServices(); }
  /**
   * Retourne les Services relatifs à MissionTile
   */
  public static function getMissionTileServices()
  { return new MissionTileServices(); }
  /**
   * Retourne les Services relatifs à MissionToken
   */
  public static function getMissionTokenServices()
  { return new MissionTokenServices(); }
  /**
   * Retourne les Services relatifs à MissionZone
   */
  public static function getMissionZoneServices()
  { return new MissionZoneServices(); }
  /**
   * Retourne les Services relatifs à Objective
   */
  public static function getObjectiveServices()
  { return new ObjectiveServices(); }
  /**
   * Retourne les Services relatifs à Origine
   */
  public static function getOrigineServices()
  { return new OrigineServices(); }
  /**
   * Retourne les Services relatifs à Player
   */
  public static function getPlayerServices()
  { return new PlayerServices(); }
  /**
   * Retourne les Services relatifs à Rule
   */
  public static function getRuleServices()
  { return new RuleServices(); }
  /**
   * Retourne les Services relatifs à Skill
   */
  public static function getSkillServices()
  { return new SkillServices(); }
  /**
   * Retourne les Services relatifs à Spawn
   */
  public static function getSpawnServices()
  { return new SpawnServices(); }
  /**
   * Retourne les Services relatifs à SpawnLiveDeck
   */
  public static function getSpawnLiveDeckServices()
  { return new SpawnLiveDeckServices(); }
  /**
   * Retourne les Services relatifs à SpawnType
   */
  public static function getSpawnTypeServices()
  { return new SpawnTypeServices(); }
  /**
   * Retourne les Services relatifs à Survivor
   */
  public static function getSurvivorServices()
  { return new SurvivorServices(); }
  /**
   * Retourne les Services relatifs à SurvivorLive
   */
  public static function getSurvivorLiveServices()
  { return new SurvivorLiveServices(); }
  /**
   * Retourne les Services relatifs à SurvivorLiveSkill
   */
  public static function getSurvivorLiveSkillServices()
  { return new SurvivorLiveSkillServices(); }
  /**
   * Retourne les Services relatifs à SurvivorSkill
   */
  public static function getSurvivorSkillServices()
  { return new SurvivorSkillServices(); }
  /**
   * Retourne les Services relatifs à Tile
   */
  public static function getTileServices()
  { return new TileServices(); }
  /**
   * Retourne les Services relatifs à Token
   */
  public static function getTokenServices()
  { return new TokenServices(); }
  /**
   * Retourne les Services relatifs à WeaponProfile
   */
  public static function getWeaponProfileServices()
  { return new WeaponProfileServices(); }
  /**
   * Retourne les Services relatifs à WpPost
   */
  public static function getWpPostServices()
  { return new WpPostServices(); }
  /**
   * Retourne les Services relatifs à Zone
   */
  public static function getZoneServices()
  { return new ZoneServices(); }
  /**
   * Retourne les Services relatifs à ZombicideTable
   */
  public static function getZombicideTableServices()
  { return new ZombicideTableServices(); }
}
