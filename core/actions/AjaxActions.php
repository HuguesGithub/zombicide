<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * AjaxActions
 * @since 1.0.00
 * @author Hugues
 */
class AjaxActions {
    /**
     * Constructeur
     */
    public function __construct() {}

    /**
     * Gère les actions Ajax 
     * @since 1.0.00
     */
    public static function dealWithAjax() {
        switch ( $_POST['ajaxAction'] ) {
            case 'addMissionObjRule'            : return self::dealWithAddMissionObjRule($_POST); break;
            case 'addMoreNews'                    : return HomePageBean::staticAddMoreNews($_POST['value']); break;
            case 'addParameter'                    : return self::dealWithAddParameter($_POST); break;
            case 'buildBlockTiles'                : return MissionBean::staticBuildBlockTiles($_POST); break;
            case 'deleteSpawnDeck'            : return SpawnDeckActions::staticDeleteSpawnDeck($_POST); break;
            case 'discardSpawnActive'        : return SpawnDeckActions::staticDiscardSpawnCard($_POST); break;
            case 'drawSpawnCard'                : return SpawnDeckActions::staticDrawSpawnCard($_POST); break;
            case 'getCompetences'                : return SkillsPageBean::staticGetSkillsSortedAndFiltered($_POST); break;
            case 'getMissions'                    : return MissionsPageBean::staticGetMissionsSortedAndFiltered($_POST); break;
            case 'getRandomTeam'                : return SurvivorsPageBean::staticGetRandomTeam($_POST); break;
            case 'getSurvivants'                : return SurvivorsPageBean::staticGetSurvivorsSortedAndFiltered($_POST); break;
            case 'getObjRuleDescription'        : return self::dealWithObjRuleDescription($_POST); break;
            case 'getParameter'                    : return self::dealWithGetParameter($_POST); break;
            case 'joinGame'                        : return self::dealWithJoinLive($_POST); break;
            case 'postChat'                        : return ChatActions::staticPostChat($_POST); break;
            case 'refreshChat'                    : return ChatActions::staticChatContent($_POST); break;
            case 'rmwMissionObjRule'            : return self::dealWithRmvMissionObjRule($_POST); break;
            case 'rotateMissionTile'            : return MissionTileServices::staticRotate($_POST); break;
            case 'showSpawnDiscard'                : return SpawnDeckActions::staticShowSpawnDiscard($_POST); break;
            case 'shuffleSpawnDiscard'        : return SpawnDeckActions::staticShuffleSpawnDiscard($_POST); break;
            case 'updateMissionTile'            : return MissionTileServices::staticUpdate($_POST); break;

            /*
            case 'getSurvivorsByExpansionCode'    : return PageSurvivorBean::getSurvivorsByExpansionCode($_POST['value'], $_POST['type']); break;
            case 'getSurvivorsBySkillId'        : return PageSurvivorBean::getSurvivorsBySkillId($_POST['value']); break;
            case 'getTeam'                        : return PageToolSurvivorBean::getTeam($_POST['filters']); break;
      case 'getSurvivorsForImport'    : return PageToolSurvivorBean::getSurvivorsForImport($_POST['value']); break;
            case 'getPopupSelectSurvivor'        : return MissionLivePageBean::getPopupSelectSurvivor($_POST['value']); break;
      case 'getExportSql'        : return ZombicideTableServices::getExportSql($_POST['content']); break;
      case 'fillTabContent'    : return self::fillTabContent($_POST['tab']); break;
      case 'canvasAction'        : return MissionLiveActions::dealWithCanvasAction($_POST); break;
      case 'drawAction'            : return ToolEquipmentActions::dealWithDrawAction($_POST); break;
      case 'drawEquipment'    : return ToolEquipmentActions::dealWithDrawAction($_POST); break;
      case 'drawInvasion'        : return ToolInvasionActions::dealWithDrawAction($_POST); break;
      case 'checkSkill'            : return SurvivorLiveSkillServices::checkSkill(__FILE__, __LINE__, $_POST['slsid']); break;
      case 'trashEquipment'    : return EquipmentLiveServices::trashEquipment(__FILE__, __LINE__, $_POST['elid']); break;
      case 'trashInvasion'    : return InvasionLiveServices::trashInvasion(__FILE__, __LINE__, $_POST['ilid']); break;
      case 'rollDice'                : return self::rollDice($_POST['diceCode']); break;

      case 'grantLife'            : return SurvivorLiveActions::staticGrantLife($_POST['slid'], $_POST['varpv']); break;
      case 'grantXp'                : return SurvivorLiveActions::staticGrantXp($_POST['slid'], $_POST['varxp']); break;
        
      case 'buildMap'       : return AdminMissionPageActions::staticBuildMap($_POST['missionId']); break;
      case 'addTile'                : return AdminMissionPageActions::staticAddTile($_POST['form']); break;
      case 'rmvTile'        : return AdminMissionPageActions::staticRmvTile($_POST['missionTileId']); break;
      case 'addZone'        : return AdminMissionPageActions::staticAddZone($_POST['form']); break;
      case 'rmvZone'        : return AdminMissionPageActions::staticRmvZone($_POST['missionZoneId']); break;
      case 'addToken'       : return AdminMissionPageActions::staticAddToken($_POST['form'], $_POST['ratio']); break;
      case 'editToken'      : return AdminMissionPageActions::staticEditToken($_POST['missionTokenId'], $_POST['rotate'], $_POST['ratio']); break;
      case 'moveToken'      : return AdminMissionPageActions::staticMoveToken($_POST['missionTokenId'], $_POST['left'], $_POST['top'], $_POST['ratio']); break;
      case 'rmvToken'       : return AdminMissionPageActions::staticRmvToken($_POST['missionTokenId']); break;

    */
            default                                : return 'Erreur dans le POST[action] - '.$_POST['ajaxAction']; break;
        }
    }
    /**
     * @param array $post
     */
    public function dealWithJoinLive($post) {
        $deckKey = $post['keyAccess'];
        $LiveServices = FactoryServices::getLiveServices();
        $arr = array('deckKey'=>$deckKey);
        $Lives = $LiveServices->getLivesWithFilters(__FILE__, __LINE__, $arr);
        if ( empty($Lives) ) {
            $arr['dateUpdate'] = date('Y-m-d H:i:s');
            $Live = new Live($arr);
            $LiveServices->insert(__FILE__, __LINE__, $Live);
            $_SESSION['deckKey'] = $deckKey;
        } else {
            $Live = array_shift($Lives);
            $Live->setDateUpdate(date('Y-m-d H:i:s'));
            $LiveServices->update(__FILE__, __LINE__, $Live);
            $_SESSION['deckKey'] = $deckKey;
        }
    }
    /**
     * @param array $post
     * @return string
     */
    public static function dealWithAddParameter($post) {
    $arrExpected = array('player', 'level');
    $postType = $post['type'];
    if ( in_array($postType, $arrExpected) ) {
      $args = array();
      $inputs = explode('|', $post['inputs']);
      foreach ( $inputs as $key=>$value ) {
        list($field, $value) = explode('=', $value);
        $field = str_replace($postType.'-','',$field);
        if ( $field == 'id' ) { continue; }
        $args[$field] = $value;
      }
      switch ( $postType ) {
        case 'player' :
          $Player = new Player($args);
          $PlayerServices = new PlayerServices();
          $PlayerServices->insert(__FILE__, __LINE__, $Player);
          $Player->setId(MySQL::getLastInsertId());
          return $Player->toJson();
        break;
        case 'level' :
          $Level = new Level($args);
          $LevelServices = new LevelServices();
          $LevelServices->insert(__FILE__, __LINE__, $Level);
          $Level->setId(MySQL::getLastInsertId());
          return $Level->toJson();
        break;
      }
    }
    }
    /**
     * @param array $post
     * @return string
     */
    public static function dealWithGetParameter($post) {
        switch ( $post['type'] ) {
            case 'level' :
                $LevelServices = new LevelServices();
                $Level = $LevelServices->select(__FILE__, __LINE__, $post['id']);
                return $Level->toJson();
            break;
        }
    }
    /**
     * @param array $post
     * @return string
     */
    public static function dealWithObjRuleDescription($post) {
        switch ( $post['type'] ) {
            case 'rule'            :
                $RuleServices = new RuleServices();
                $Rule = $RuleServices->select(__FILE__, __LINE__, $post['id']);
                return $Rule->getDescription();
            break;
            case 'objective'    : 
                $ObjectiveServices = new ObjectiveServices();
                $Objective = $ObjectiveServices->select(__FILE__, __LINE__, $post['id']);
                return $Objective->getDescription();
            break;
        }
        return '';
    }
    /**
     * @param array $post
     * @return string
     */
    public static function dealWithAddMissionObjRule($post) {
        switch ( $post['type'] ) {
            case 'rule' :
            case 'setting' :
                return MissionRuleActions::staticInsert($post);
            break;
            case 'objective' : 
                return MissionObjectiveActions::staticInsert($post);
            break;
        }
    }
    /**
     * @param array $post
     * @return string
     */
    public static function dealWithRmvMissionObjRule($post) {
        switch ( $post['type'] ) {
            case 'rule' :
            case 'setting' :
                return MissionRuleActions::staticDelete($post);
            break;
            case 'objective' : 
                return MissionObjectiveActions::staticDelete($post);
            break;
        }
    }
    /**
     * 
     * @param string $diceCode
     *
    public static function rollDice($diceCode) {
        list($nbDice, $seuil) = explode('D6E', $diceCode);
        $diceList = '';
        for ( $i=0; $i<$nbDice; $i++ ) {
            $rnd = rand(1, 6);
            $diceList .= ($i==0?'':', ').$rnd.($rnd>=$seuil?'*':'');
        }
        $json = '{"showRollResult":'.json_encode(date('H:i:s').' : '.$diceList).'}';
        return $json;
    }

    /**
     * 
     * @param string $tab
     *
    public static function fillTabContent($tab) {
        switch ( $tab ) {
            case '#dashboard' :
                $Bean = new AdminPageBean();
                return $Bean->getDashboardContent($tab);
            break;
            case '#equipment' : return AdminEquipmentPageBean::getAdminContentPage(); break;
            case '#expansion' : return AdminExpansionPageBean::getAdminContentPage(); break;
            case '#mission'     : return AdminMissionPageBean::getAdminContentPage(); break;
            case '#online'         : return AdminOnlinePageBean::getAdminContentPage(); break;
            case '#rule'             : return AdminRulePageBean::getAdminContentPage(); break;
            case '#skill'         : return AdminSkillPageBean::getAdminContentPage(); break;
            case '#spawn'         : return AdminInvasionPageBean::getAdminContentPage(); break;
            case '#survivor'     : return AdminSurvivorPageBean::getAdminContentPage(); break;
            case '#tile'             : return AdminTilePageBean::getAdminContentPage(); break;
            default : return 'Erreur dans le POST[tab] - '.$tab; break;
        }
    }
    */
}
?>