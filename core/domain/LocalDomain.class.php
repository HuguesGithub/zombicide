<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe LocalDomain
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LocalDomain extends GlobalDomain implements iConstants
{
  /**
   * @param array $attributes
   * @param array $services
   */
  public function __construct($attributes=array(), $services=array())
  {
    parent::__construct($attributes);
    if (!empty($services)) {
      foreach ($services as $service) {
        switch ($service) {
          case 'Duration'      :
            $this->DurationServices = FactoryServices::getDurationServices();
          break;
          case 'Equipment'        :
            $this->EquipmentServices = FactoryServices::getEquipmentServices();
          break;
          case 'EquipmentExpansion'     :
            $this->EquipmentExpansionServices = FactoryServices::getEquipmentExpansionServices();
          break;
          case 'EquipmentKeyword'     :
            $this->EquipmentKeywordServices = FactoryServices::getEquipmentKeywordServices();
          break;
          case 'EquipmentWeaponProfile'  :
            $this->EquipmentWeaponProfileServices = FactoryServices::getEquipmentWeaponProfileServices();
          break;
          case 'Expansion'         :
            $this->ExpansionServices = FactoryServices::getExpansionServices();
          break;
          case 'Keyword'     :
            $this->KeywordServices = FactoryServices::getKeywordServices();
          break;
          case 'Level'        :
            $this->LevelServices = FactoryServices::getLevelServices();
          break;
          case 'Live'        :
            $this->LiveServices = FactoryServices::getLiveServices();
          break;
          case 'LiveDeck'        :
            $this->LiveDeckServices = FactoryServices::getLiveDeckServices();
          break;
          case 'LiveMission'          :
            $this->LiveMissionServices = FactoryServices::getLiveMissionServices();
          break;
          case 'LiveToken'        :
            $this->LiveTokenServices = FactoryServices::getLiveTokenServices();
          break;
          case 'Market'        :
            $this->MarketServices = FactoryServices::getMarketServices();
          break;
          case 'Mission'          :
            $this->MissionServices = FactoryServices::getMissionServices();
          break;
          case 'MissionExpansion'      :
            $this->MissionExpansionServices = FactoryServices::getMissionExpansionServices();
          break;
          case 'MissionObjective'      :
            $this->MissionObjectiveServices = FactoryServices::getMissionObjectiveServices();
          break;
          case 'MissionRule'        :
            $this->MissionRuleServices = FactoryServices::getMissionRuleServices();
          break;
          case 'MissionTile'        :
            $this->MissionTileServices = FactoryServices::getMissionTileServices();
          break;
          case 'MissionToken'        :
            $this->MissionTokenServices = FactoryServices::getMissionTokenServices();
          break;
          case 'Objective'        :
            $this->ObjectiveServices = FactoryServices::getObjectiveServices();
          break;
          case 'Origine'      :
            $this->OrigineServices = FactoryServices::getOrigineServices();
          break;
          case 'Player'      :
            $this->PlayerServices = FactoryServices::getPlayerServices();
          break;
          case 'Rule'            :
            $this->RuleServices = FactoryServices::getRuleServices();
          break;
          case 'Skill'          :
            $this->SkillServices = FactoryServices::getSkillServices();
          break;
          case 'Spawn'        :
            $this->SpawnServices = FactoryServices::getSpawnServices();
          break;
          case 'SpawnLiveDeck'        :
            $this->SpawnLiveDeckServices = FactoryServices::getSpawnLiveDeckServices();
          break;
          case 'Survivor'          :
            $this->SurvivorServices = FactoryServices::getSurvivorServices();
          break;
          case 'SurvivorSkill'      :
            $this->SurvivorSkillServices = FactoryServices::getSurvivorSkillServices();
          break;
          case 'Tile'            :
            $this->TileServices = FactoryServices::getTileServices();
          break;
          case 'Token'          :
            $this->TokenServices = FactoryServices::getTokenServices();
          break;
          case 'WeaponProfile'      :
            $this->WeaponProfileServices = FactoryServices::getWeaponProfileServices();
          break;
          case 'WpPost'          :
            $this->WpPostServices = FactoryServices::getWpPostServices();
          break;
          default              :
            echo 'Must add ['.$service.'] in Zomb::LocalDomain.<br>';
          break;
        }
      }
    }
  }

  /**
   * @return Equipment
   */
  public function getEquipment()
  {
    if ($this->Equipment == null ) {
      if ($this->equipmentId != null) {
        $this->Equipment = $this->EquipmentServices->select(__FILE__, __LINE__, $this->equipmentId);
      } elseif ($this->equipmentCardId != null) {
        $this->Equipment = $this->EquipmentServices->select(__FILE__, __LINE__, $this->equipmentCardId);
      }
    }
    return $this->Equipment;
  }
  /**
   * @return EquipmentExpansion
   */
  public function getEquipmentExpansion()
  {
    if ($this->EquipmentExpansion == null) {
      $this->EquipmentExpansion = $this->EquipmentExpansionServices->select(__FILE__, __LINE__, $this->equipmentExpansionId);
    }
    return $this->EquipmentExpansion;
  }
  /**
   * @return Expansion
   */
  public function getExpansion()
  {
    if ($this->Expansion == null) {
      $this->Expansion = $this->getExpansionFromGlobal($this->expansionId);
    }
    return $this->Expansion;
  }
  /**
   * @return Invasion
   */
  public function getInvasion()
  {
    if ($this->Invasion == null) {
      $this->Invasion = $this->InvasionServices->select(__FILE__, __LINE__, $this->invasionId);
    }
    return $this->Invasion;
  }
  /**
   * @return Mission
   */
  public function getMission()
  {
    if ($this->Mission == null) {
      $this->Mission = $this->MissionServices->select(__FILE__, __LINE__, $this->missionId);
    }
    return $this->Mission;
  }
  /**
   * @return Duration
   */
  public function getDuration()
  {
    if ($this->Duration == null) {
      $this->Duration = $this->DurationServices->select(__FILE__, __LINE__, $this->durationId);
    }
    return $this->Duration;
  }
  /**
   * @return Keyword
   */
  public function getKeyword()
  {
    if ($this->Keyword == null) {
      $this->Keyword = $this->KeywordServices->select(__FILE__, __LINE__, $this->keywordId);
    }
    return $this->Keyword;
  }
  /**
   * @return Level
   */
  public function getLevel()
  {
    if ($this->Level == null) {
      $this->Level = $this->LevelServices->select(__FILE__, __LINE__, $this->levelId);
    }
    return $this->Level;
  }
  /**
   * @return Player
   */
  public function getPlayer()
  {
    if ($this->Player == null) {
      $this->Player = $this->PlayerServices->select(__FILE__, __LINE__, $this->playerId);
    }
    return $this->Player;
  }
  /**
   * @return Origine
   */
  public function getOrigine()
  {
    if ($this->Origine==null) {
      $this->Origine = $this->getOrigineFromGlobal($this->origineId);
    }
    return $this->Origine;
  }
  /**
   * @return Objective
   */
  public function getObjective()
  {
    if ($this->Objective==null) {
      $this->Objective = $this->getObjectiveFromGlobal($this->objectiveId);
    }
    return $this->Objective;
  }
  /**
   * @return Rule
   */
  public function getRule()
  {
    if ($this->Rule==null) {
     $this->Rule = $this->getRuleFromGlobal($this->ruleId);
    }
    return $this->Rule;
  }
  /**
   * @return Skill
   */
  public function getSkill()
  {
    if ($this->Skill == null) {
      $this->Skill = $this->SkillServices->select(__FILE__, __LINE__, $this->skillId);
    }
    return $this->Skill;
  }
  /**
   * @return SpawnCard
   */
  public function getSpawnCard()
  {
    if ($this->SpawnCard == null) {
      $this->SpawnCard = $this->SpawnServices->select(__FILE__, __LINE__, $this->spawnCardId);
    }
    return $this->SpawnCard;
  }
  /**
   * @return Survivor
   */
  public function getSurvivor()
  {
    if ($this->Survivor == null) {
      $this->Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $this->survivorId);
    }
    return $this->Survivor;
  }
  /**
   * @return Tile
   */
  public function getTile()
  {
    if ($this->Tile==null) {
      $this->Tile = $this->TileServices->select(__FILE__, __LINE__, $this->tileId);
    }
    return $this->Tile;
  }
  /**
   * @return Token
   */
  public function getToken()
  {
    if ($this->Token==null) {
      $this->Token = $this->TokenServices->select(__FILE__, __LINE__, $this->tokenId);
    }
    return $this->Token;
  }
  /**
   * @return WeaponProfile
   */
  public function getWeaponProfile()
  {
    if ($this->WeaponProfile==null) {
      $this->WeaponProfile = $this->WeaponProfileServices->select(__FILE__, __LINE__, $this->weaponProfileId);
    }
    return $this->WeaponProfile;
  }
  /**
   * @return Zone
   */
  public function getZone()
  {
    if ($this->Zone==null) {
      $this->Zone = $this->ZoneServices->select(__FILE__, __LINE__, $this->zoneId);
    }
    return $this->Zone;
  }
  /**
   * @param string $type
   * @return array EquipmentExpansion
   */
  public function getEquipmentExpansions($type=self::CST_EQUIPMENTCARDID)
  {
    if ($this->EquipmentExpansions == null &&  $this->id!='') {
      $arrFilters = array($type=>$this->id);
      $this->EquipmentExpansions = $this->EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->EquipmentExpansions;
  }
  /**
   * @return array EquipmenKeyword
   */
  public function getEquipmentKeywords()
  {
    if ($this->EquipmentKeywords == null &&  $this->id!='') {
      $arrFilters = array(self::CST_EQUIPMENTCARDID=>$this->id);
      $this->EquipmentKeywords = $this->EquipmentKeywordServices->getEquipmentKeywordsWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->EquipmentKeywords;
  }
  /**
   * @return array EquipmentWeaponProfile
   */
  public function getEquipmentWeaponProfiles()
  {
    if ($this->EquipmentWeaponProfiles == null &&  $this->id!='') {
      $arrFilters = array(self::CST_EQUIPMENTCARDID=>$this->id);
      $this->EquipmentWeaponProfiles = $this->EquipmentWeaponProfileServices->getEquipmentWeaponProfilesWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->EquipmentWeaponProfiles;
  }
  /**
   * @return array MissionExpansion
   */
  public function getMissionExpansions()
  {
    if ($this->MissionExpansions == null &&  $this->id!='') {
      $arrFilters = array(self::CST_MISSIONID=>$this->id);
      $this->MissionExpansions = $this->MissionExpansionServices->getMissionExpansionsWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->MissionExpansions;
  }
  /**
   * @return array MissionObjective
   */
  public function getMissionObjectives($orderBy='id')
  {
    if ($this->MissionObjectives == null &&  $this->id!='') {
      $arrFilters = array(self::CST_MISSIONID=>$this->id);
      $this->MissionObjectives = $this->MissionObjectiveServices->getMissionObjectivesWithFilters(__FILE__, __LINE__, $arrFilters, $aF);
    }
    return $this->MissionObjectives;
  }
  /**
   * @return array MissionRule
   */
  public function getMissionRules($aF='id')
  {
    if ($this->MissionRules == null &&  $this->id!='') {
      $arrFilters = array(self::CST_MISSIONID=>$this->id);
      $this->MissionRules = $this->MissionRuleServices->getMissionRulesWithFilters(__FILE__, __LINE__, $arrFilters, $aF);
    }
    return $this->MissionRules;
  }
  /**
   * @param string $aF
   * @param string $aO
   * @return array MissionTile
   */
  public function getMissionTiles($aF='id', $aO='asc')
  {
    if ($this->MissionTiles == null &&  $this->id!='') {
      $arrFilters = array(self::CST_MISSIONID=>$this->id);
      $this->MissionTiles = $this->MissionTileServices->getMissionTilesWithFilters(__FILE__, __LINE__, $arrFilters, $aF, $aO);
    }
    return $this->MissionTiles;
  }
  /**
   * @param string $aF
   * @param string $aO
   * @return array MissionToken
   */
  public function getMissionTokens($aF='id', $aO='asc')
  {
    if ($this->MissionTokens == null &&  $this->id!='') {
      $arrFilters = array(self::CST_MISSIONID=>$this->id);
      $this->MissionTokens = $this->MissionTokenServices->getMissionTokensWithFilters(__FILE__, __LINE__, $arrFilters, $aF, $aO);
    }
    return $this->MissionTokens;
  }
  /**
   * @return array MissionZone
   */
  public function getMissionZones()
  {
    if ($this->MissionZones == null &&  $this->id!='') {
      $arrFilters = array(self::CST_MISSIONID=>$this->id);
      $this->MissionZones = $this->MissionZoneServices->getMissionZonesWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->MissionZones;
  }
  /**
   * @return array SpawnLiveDeck
   */
  public function getSpawnLiveDecks()
  {
    if ($this->SpawnLiveDecks == null && $this->id!='') {
      $arrFilters = array('liveId'=>$this->id);
      $this->SpawnLiveDecks = $this->SpawnLiveDeckServices->getSpawnLiveDecksWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->SpawnLiveDecks;
  }
  /**
   * @return array SurvivorLive
   */
  public function getSurvivorLives()
  {
    if ($this->SurvivorLives == null &&  $this->id!='') {
      $arrFilters = array('missionLiveId'=>$this->id);
      $this->SurvivorLives = $this->SurvivorLiveServices->getSurvivorsLiveWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->SurvivorLives;
  }
  /**
   * @return array SurvivorSkill
   */
  public function getSurvivorSkills()
  {
    if ($this->SurvivorSkills == null &&  $this->id!='') {
      $arrFilters = array('survivorId'=>$this->id);
      $this->SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    }
    return $this->SurvivorSkills;
  }
  /**
   * @param string $metaKey
   * @return WpPost
   */
  public function getWpPost($metaKey)
  {
    if ($this->WpPost == null) {
      $args = array('post_status' => 'publish,future', 'meta_key' => $metaKey, 'meta_value' => $this->id);
      $Articles = $this->WpPostServices->getArticles(__FILE__, __LINE__, $args, true);
      if (!empty($Articles)) {
        $this->WpPost = array_shift($Articles);
      }
    }
    return $this->WpPost;
  }
  /**
   * Retourne l'url du PDF associé à la Mission, s'il existe.
   * @return string
   */
  public function getPdfUrl()
  {
    $WpPost = $this->getWpPost();
    if ($WpPost!=null) {
      $medias = get_attached_media('application/pdf', $WpPost->getID());
      if (!empty($medias)) {
        $media = array_shift($medias);
        if ($media->guid!='') {
          return $media->guid;
        }
      }
    }
    return '#';
  }
  /**
   * @param int $expansionId
   * @return Expansion
   */
  public function getExpansionFromGlobal($expansionId)
  {
    global $globalExpansions;
    $GlobalExpansion = null;
    if (!empty($globalExpansions)) {
      foreach ($globalExpansions as $Expansion) {
        if ($Expansion->getId()==$expansionId) {
          $GlobalExpansion = $Expansion;
        }
      }
    }
    if ($GlobalExpansion==null) {
      $GlobalExpansion = $this->ExpansionServices->select(__FILE__, __LINE__, $expansionId);
      if ($GlobalExpansion != null) {
        $globalExpansions[] = $GlobalExpansion;
      }
    }
    return $GlobalExpansion;
  }
  /**
   * @param int $objectiveId
   * @return Objective
   */
  protected function getObjectiveFromGlobal($objectiveId)
  {
    global $globalObjectives;
    $GlobalObjective = null;
    if (!empty($globalObjectives)) {
      foreach ($globalObjectives as $Objective) {
        if ($Objective->getId()==$objectiveId) {
          $GlobalObjective = $Objective;
        }
      }
    }
    if ($GlobalObjective == null) {
      $GlobalObjective = $this->ObjectiveServices->select(__FILE__, __LINE__, $objectiveId);
      if ($GlobalObjective != null) {
        $globalObjectives[] = $GlobalObjective;
      }
    }
    return $GlobalObjective;
  }
  /**
   * @param int $origineId
   * @return Origine
   */
  protected function getOrigineFromGlobal($origineId)
  {
    global $globalOrigines;
    $GlobalOrigine = null;
    if (!empty($globalOrigines)) {
      foreach ($globalOrigines as $Origine) {
        if ($Origine->getId() == $origineId) {
         $GlobalOrigine = $Origine;
        }
      }
    }
    if ($GlobalOrigine==null) {
      $GlobalOrigine = $this->OrigineServices->select(__FILE__, __LINE__, $origineId);
      if ($GlobalOrigine != null) {
        $globalOrigines[] = $GlobalOrigine;
      }
    }
    return $GlobalOrigine;
  }
  /**
   * @param int $ruleId
   * @return Rule
   */
  protected function getRuleFromGlobal($ruleId)
  {
    global $globalRules;
    $GlobalRule = null;
    if (!empty($globalRules)) {
      foreach ($globalRules as $Rule) {
        if ($Rule->getId()==$ruleId) {
          $GlobalRule = $Rule;
        }
      }
    }
    if ($GlobalRule == null) {
      $GlobalRule = $this->RuleServices->select(__FILE__, __LINE__, $ruleId);
      if ($GlobalRule != null) {
        $globalRules[] = $GlobalRule;
      }
    }
    return $GlobalRule;
  }
  /**
   * @param int $weaponProfileId
   * @return WeaponProfile
   */
  protected function getWeaponProfileFromGlobal($weaponProfileId)
  {
    global $globalWeaponProfiles;
    $GlobalWeaponProfile = null;
    if (!empty($globalWeaponProfiles)) {
      foreach ($globalWeaponProfiles as $WeaponProfile) {
        if ($WeaponProfile->getId()==$weaponProfileId) {
          $GlobalWeaponProfile = $WeaponProfile;
        }
      }
    }
    if ($GlobalWeaponProfile == null) {
      $GlobalWeaponProfile = $this->WeaponProfileServices->select(__FILE__, __LINE__, $weaponProfileId);
      if ($GlobalWeaponProfile != null) {
        $globalWeaponProfiles[] = $GlobalWeaponProfile;
      }
    }
    return $GlobalWeaponProfile;
  }
  /**
   * @return string
   */
  public function toJson()
  {
    $classVars = $this->getClassVars();
    $str = '';
    foreach ($classVars as $key => $value) {
      if ($str!='') {
        $str .= ', ';
      }
      $str .= '"'.$key.'":'.json_encode($this->getField($key));
    }
    return '{'.$str.'}';
  }
  /**
   * @param array $post
   * @return bool
   */
  public function updateWithPost($post)
  {
    $classVars = $this->getClassVars();
    unset($classVars['id']);
    $doUpdate = false;
    foreach ($classVars as $key => $value) {
      $value = stripslashes($post[$key]);
      if ($this->{$key} != $value) {
        $doUpdate = true;
        $this->{$key} = $value;
      }
    }
    return $doUpdate;
  }
}
