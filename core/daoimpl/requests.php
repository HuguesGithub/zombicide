[Chat]
select="SELECT id, liveId, sendToId, senderId, timestamp, texte "
from="FROM wp_11_zombicide_chat "
where="WHERE (liveId LIKE '%s' OR sendToId LIKE '%s' OR senderId LIKE '%s') AND timestamp > '%s' "
insert="INSERT INTO wp_11_zombicide_chat (liveId, sendToId, senderId, timestamp, texte) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_chat SET liveId='%s', sendToId='%s', senderId='%s', timestamp='%s', texte='%s' "
[Duration]
select="SELECT id, minDuration, maxDuration "
from="FROM wp_11_zombicide_duration "
where="WHERE minDuration LIKE '%s' AND maxDuration LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_duration (minDuration, maxDuration) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_duration SET minDuration='%s', maxDuration='%s' "
[Equipment]
select="SELECT id, name, textAbility "
from="FROM wp_11_zombicide_equipmentcards "
insert="INSERT INTO wp_11_zombicide_equipmentcards (name, textAbility) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_equipmentcards SET name='%s', textAbility='%s' "
[EquipmentExpansion]
select="SELECT id, equipmentCardId, expansionId, quantity "
from="FROM wp_11_zombicide_equipment_expansion "
where="WHERE equipmentCardId LIKE '%s' AND expansionId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_equipment_expansion (equipmentCardId, expansionId, quantity) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_equipment_expansion SET equipmentCardId='%s', expansionId='%s', quantity='%s' "
[EquipmentKeyword]
select="SELECT id, equipmentCardId, keywordId "
from="FROM wp_11_zombicide_equipment_keyword "
where="WHERE equipmentCardId LIKE '%s' AND keywordId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_equipment_keyword (equipmentCardId, keywordId) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_equipment_keyword SET equipmentCardId='%s', keywordId='%s' "
[EquipmentLiveDeck]
select="SELECT id, liveId, equipmentCardId, rank, status, liveSurvivorId "
from="FROM wp_11_zombicide_equipmentlivedeck "
where="WHERE liveId LIKE '%s' AND equipmentCardId LIKE '%s' AND status LIKE '%s' AND liveSurvivorId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_equipmentlivedeck (liveId, equipmentCardId, rank, status, liveSurvivorId) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_equipmentlivedeck SET liveId='%s', equipmentCardId='%s', rank='%s', status='%s', liveSurvivorId='%s' "
[EquipementWeaponProfile]
select="SELECT id, equipmentCardId, weaponProfileId, noisy "
from="FROM wp_11_zombicide_equipment_weaponprofile "
where="WHERE equipmentCardId LIKE '%s' AND weaponProfileId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_equipment_weaponprofile (equipmentCardId, weaponProfileId, noisy) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_equipment_weaponprofile SET equipmentCardId='%s', weaponProfileId='%s', noisy='%s' "
[Expansion]
select="SELECT id, code, name, displayRank "
from="FROM wp_11_zombicide_expansion "
where="WHERE code LIKE '%s' AND nbMissions >= '%s' "
insert="INSERT INTO wp_11_zombicide_expansion (code, name, displayRank) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_expansion SET code='%s', name='%s', displayRank='%s' "
[Keyword]
select="SELECT id, name, description "
from="FROM wp_11_zombicide_keyword "
where="WHERE name LIKE '%s' AND description LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_keyword (name, description) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_keyword SET name='%s', description='%s' "
[Level]
select="SELECT id, name "
from="FROM wp_11_zombicide_level "
insert="INSERT INTO wp_11_zombicide_level (name) VALUES ('%s');"
update="UPDATE wp_11_zombicide_level SET name='%s' "
[Live]
select="SELECT id, deckKey, dateUpdate "
from="FROM wp_11_zombicide_live "
where="WHERE deckKey LIKE '%s' AND dateUpdate <= '%s' "
insert="INSERT INTO wp_11_zombicide_live (deckKey, dateUpdate) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_live SET deckKey='%s', dateUpdate='%s' "
[LiveDeck]
select="SELECT id, deckKey, dateUpdate "
from="FROM wp_11_zombicide_livedeck "
where="WHERE deckKey LIKE '%s' AND dateUpdate <= '%s' "
insert="INSERT INTO wp_11_zombicide_livedeck (deckKey, dateUpdate) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_livedeck SET deckKey='%s', dateUpdate='%s' "
[LiveMission]
select="SELECT id, liveId, missionId "
from="FROM wp_11_zombicide_live_mission "
where="WHERE liveId LIKE '%s' AND missionId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_live_mission (liveId, missionId) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_live_mission SET liveId='%s', missionId='%s' "
[LiveMissionToken]
select="SELECT id, liveId, missionTokenId, status "
from="FROM wp_11_zombicide_live_missiontoken "
where="WHERE liveId LIKE '%s' AND tokenId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_live_missiontoken (liveId, missionTokenId, status) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_live_missiontoken SET liveId='%s', missionTokenId='%s', status='%s' "
[LiveSurvivor]
select="SELECT id, liveId, survivorId, missionZoneId, survivorTypeId, experiencePoints, hitPoints "
from="FROM wp_11_zombicide_live_survivor "
where="WHERE liveId LIKE '%s' AND survivorId LIKE '%s' AND missionZoneId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_live_survivor (liveId, survivorId, missionZoneId, survivorTypeId, experiencePoints, hitPoints) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_live_survivor SET liveId='%s', survivorId='%s', missionZoneId='%s', survivorTypeId='%s', experiencePoints='%s', hitPoints='%s' "
[LiveSurvivorSkill]
select="SELECT id, liveSurvivorId, skillId, tagLevelId, locked "
from="FROM wp_11_zombicide_live_survivorskill "
where="WHERE survivorId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_live_survivorskill (liveSurvivorId, skillId, tagLevelId, locked) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_live_survivorskill SET liveSurvivorId='%s', skillId='%s', tagLevelId='%s', locked='%s' "
[LiveZombie]
select="SELECT id, liveId, missionZoneId, zombieTypeId, zombieCategoryId, quantity "
from="FROM wp_11_zombicide_live_zombie "
where="WHERE liveId LIKE '%s' AND tokenId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_live_zombie (liveId, missionZoneId, zombieTypeId, zombieCategoryId, quantity) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_live_zombie SET liveId='%s', missionZoneId='%s', zombieTypeId='%s', zombieCategoryId='%s', quantity='%s' "
[Market]
select="SELECT id, name, description, quantity, price, imgProduct, universId, lang "
from="FROM wp_11_zombicide_market "
insert="INSERT INTO wp_11_zombicide_market (name, description, quantity, price, imgProduct, universId, lang) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_market SET name='%s', description='%s', quantity='%s', price='%s', imgProduct='%s', universId='%s', lang='%s' "
[Mission]
select="SELECT m.id AS id, title, m.code AS code, levelId, playerId, durationId, origineId, width, height, published, liveAble "
from="FROM wp_11_zombicide_mission AS m "
where="WHERE levelId LIKE '%s' AND durationId LIKE '%s' AND playerId LIKE '%s' AND origineId LIKE '%s' AND published LIKE '%s' AND liveAble LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_mission (title, code, levelId, playerId, durationId, origineId, width, height, published, liveAble) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_mission SET title='%s', code='%s', levelId='%s', playerId='%s', durationId='%s', origineId='%s', width='%s', height='%s', published='%s', liveAble='%s' "
[MissionExpansion]
select="SELECT id, missionId, expansionId "
from="FROM wp_11_zombicide_mission_expansion "
where="WHERE missionId LIKE '%s' AND expansionId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_mission_expansion (missionId, expansionId) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_mission_expansion SET missionId='%s', expansionId='%s' "
[MissionObjective]
select="SELECT id, missionId, objectiveId, title "
from="FROM wp_11_zombicide_mission_objective "
where="WHERE missionId LIKE '%s' AND objectiveId LIKE '%s' AND title LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_mission_objective (missionId, objectiveId, title) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_mission_objective SET missionId='%s', objectiveId='%s', title='%s' "
[MissionRule]
select="SELECT id, missionId, ruleId, title "
from="FROM wp_11_zombicide_mission_rule "
where="WHERE missionId LIKE '%s' AND ruleId LIKE '%s' AND title LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_mission_rule (missionId, ruleId, title) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_mission_rule SET missionId='%s', ruleId='%s', title='%s' "
[MissionTile]
select="SELECT id, missionId, tileId, orientation, coordX, coordY "
from="FROM wp_11_zombicide_mission_tile "
where="WHERE missionId LIKE '%s' AND coordX LIKE '%s' AND coordY LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_mission_tile (missionId, tileId, orientation, coordX, coordY) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_mission_tile SET missionId='%s', tileId='%s', orientation='%s', coordX='%s', coordY='%s' "
[MissionToken]
select="SELECT id, missionId, tokenId, coordX, coordY, color, status, orientation "
from="FROM wp_11_zombicide_mission_token "
where="WHERE missionId LIKE '%s' AND tokenId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_mission_token (missionId, tokenId, coordX, coordY, color, status, orientation) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_mission_token SET missionId='%s', tokenId='%s', coordX='%s', coordY='%s', color='%s', status='%s', orientation='%s' "
[Objective]
select="SELECT id, code, description "
from="FROM wp_11_zombicide_objective "
where="WHERE code LIKE '%s' AND description LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_objective (code, description) VALUES ('%s', '%s');"
update="UPDATE wp_11_zombicide_objective SET code='%s', description='%s' "
[Origine]
select="SELECT id, name "
from="FROM wp_11_zombicide_origine "
where="WHERE name LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_origine (name) VALUES ('%s');"
update="UPDATE wp_11_zombicide_origine SET name='%s' "
[Player]
select="SELECT id, name "
from="FROM wp_11_zombicide_player "
insert="INSERT INTO wp_11_zombicide_player (name) VALUES ('%s');"
update="UPDATE wp_11_zombicide_player SET name='%s' "
[Rule]
select="SELECT id, setting, code, description "
from="FROM wp_11_zombicide_rule "
where="WHERE setting LIKE '%s' AND code LIKE '%s' AND description LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_rule (setting, code, description) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_rule SET setting='%s', code='%s', description='%s' "
[Skill]
select="SELECT id, code, name, description "
from="FROM wp_11_zombicide_skill "
where="WHERE code LIKE '%s' AND name LIKE '%s' AND description LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_skill (code, name, description) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_skill SET code='%s', name='%s', description='%s' "
[Spawn]
select="SELECT id, expansionId, spawnNumber, spawnTypeId, zombieCategoryId, blueZombieTypeId, blueQuantity, yellowZombieTypeId, yellowQuantity, orangeZombieTypeId, orangeQuantity, redZombieTypeId, redQuantity "
from="FROM wp_11_zombicide_spawncards "
where="WHERE expansionId LIKE '%s' AND spawnNumber LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_spawncards (expansionId, spawnNumber, spawnTypeId, zombieCategoryId, blueZombieTypeId, blueQuantity, yellowZombieTypeId, yellowQuantity, orangeZombieTypeId, orangeQuantity, redZombieTypeId, redQuantity) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_spawncards SET expansionId='%s', spawnNumber='%s', spawnTypeId='%s', zombieCategoryId='%s', blueZombieTypeId='%s', blueQuantity='%s', yellowZombieTypeId='%s', yellowQuantity='%s', orangeZombieTypeId='%s', orangeQuantity='%s', redZombieTypeId='%s', redQuantity='%s' "
[SpawnLive]
select="SELECT id, liveId, spawnCardId, rank, status "
from="FROM wp_11_zombicide_spawnlivedeck "
where="WHERE liveId LIKE '%s' AND spawnCardId LIKE '%s' AND status LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_spawnlivedeck (liveId, spawnCardId, rank, status) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_spawnlivedeck SET liveId='%s', spawnCardId='%s', rank='%s', status='%s' "
[Survivor]
select="SELECT id, name, zombivor, ultimate, expansionId, background, altImgName, liveAble "
from="FROM wp_11_zombicide_survivor "
where="WHERE name LIKE '%s' AND zombivor LIKE '%s' AND ultimate LIKE '%s' AND expansionId LIKE '%s' and background LIKE '%s' and liveAble LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_survivor (name, zombivor, ultimate, expansionId, background, altImgName, liveAble) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_survivor SET name='%s', zombivor='%s', ultimate='%s', expansionId='%s', background='%s', altImgName='%s', liveAble='%s' "
[SurvivorSkill]
select="SELECT id, survivorId, skillId, survivorTypeId, tagLevelId "
from="FROM wp_11_zombicide_survivor_skill "
where="WHERE survivorId LIKE '%s' AND skillId LIKE '%s' AND survivorTypeId LIKE '%s' AND tagLevelId LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_survivor_skill (survivorId, skillId, survivorTypeId, tagLevelId) VALUES ('%s', '%s', '%s', '%s');"
update="UPDATE wp_11_z_survivor_skill SET survivorId='%s', skillId='%s', survivorTypeId='%s', tagLevelId='%s' "
[Tile]
select="SELECT id, expansionId, code, coordPoly, zoneType, zoneAcces, activeTile "
from="FROM wp_11_zombicide_tile "
where="WHERE code LIKE '%s' AND expansionId LIKE '%s' AND activeTile LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_tile (expansionId, code, coordPoly, zoneType, zoneAcces, activeTile) VALUES ('%s', '%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_tile SET expansionId='%s', code='%s', coordPoly='%s', zoneType='%s', zoneAcces='%s', activeTile='%s' "
[Token]
select="SELECT id, code, width, height "
from="FROM wp_11_zombicide_token "
where="WHERE code LIKE '%s' "
insert="INSERT INTO wp_11_zombicide_token (code, width, height) VALUES ('%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_token SET code='%s', width='%s', height='%s' "
[WeaponProfile]
select="SELECT id, minRange, maxRange, nbDice, successRate, damageLevel "
from="FROM wp_11_zombicide_weaponprofile "
insert="INSERT INTO wp_11_zombicide_weaponprofile (minRange, maxRange, nbDice, successRate, damageLevel) VALUES ('%s', '%s', '%s', '%s', '%s');"
update="UPDATE wp_11_zombicide_weaponprofile SET minRange='%s', maxRange='%s', nbDice='%s', successRate='%s', damageLevel='%s' "
