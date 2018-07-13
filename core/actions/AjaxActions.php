<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * AjaxActions
 * @since 1.0.00
 * @author Hugues
 */
class AjaxActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct()
  {}

  /**
   * Gère les actions Ajax
   * @since 1.0.00
   */
  public static function dealWithAjax()
  {
    switch ($_POST['ajaxAction']) {
      case 'addMissionObjRule'     :
        $returned = self::dealWithAddMissionObjRule($_POST);
      break;
      case 'addMoreNews'         :
        $returned = HomePageBean::staticAddMoreNews($_POST['value']);
      break;
      case 'addParameter'        :
        $returned = self::dealWithAddParameter($_POST);
      break;
      case 'buildBlockTiles'       :
        $returned = MissionBean::staticBuildBlockTiles($_POST);
      break;
      case 'deleteEquipmentDeck'     :
      case 'discardEquipmentActive'  :
      case 'discardEquippedCard'     :
      case 'drawEquipmentCard'       :
      case 'equipEquipmentActive'    :
      case 'leaveEquipmentDeck'      :
      case 'pregenEquipmentCard'     :
      case 'showEquipmentDiscard'    :
      case 'showEquipmentEquip'      :
      case 'shuffleEquipmentDiscard' :
        $returned = EquipmentDeckActions::dealWithStatic($_POST);
      break;
      case 'deleteSpawnDeck'         :
      case 'discardSpawnActive'      :
      case 'drawSpawnCard'           :
      case 'leaveSpawnDeck'          :
      case 'showSpawnDiscard'        :
      case 'shuffleSpawnDiscard'     :
        $returned = SpawnDeckActions::dealWithStatic($_POST);
      break;
      case 'getCompetences'      :
        $returned = WpPageSkillsBean::staticGetSkillsSortedAndFiltered($_POST);
      break;
      case 'getMissions'         :
        $returned = WpPageMissionsBean::staticGetMissionsSortedAndFiltered($_POST);
      break;
      case 'getRandomTeam'       :
        $returned = SurvivorsPageBean::staticGetRandomTeam($_POST);
      break;
      case 'getSurvivants'       :
        $returned = SurvivorsPageBean::staticGetSurvivorsSortedAndFiltered($_POST);
      break;
      case 'getObjRuleDescription'   :
        $returned = self::dealWithObjRuleDescription($_POST);
      break;
      case 'getParameter'        :
        $returned = self::dealWithGetParameter($_POST);
      break;
      case 'joinGame'          :
        $returned = self::dealWithJoinLive($_POST);
      break;
      case 'postChat'          :
        $returned = ChatActions::staticPostChat($_POST);
      break;
      case 'refreshChat'         :
        $returned = ChatActions::staticChatContent($_POST);
      break;
      case 'rmwMissionObjRule'     :
        $returned = self::dealWithRmvMissionObjRule($_POST);
      break;
      case 'rotateMissionTile'     :
        $returned = MissionTileServices::staticRotate($_POST);
      break;
      case 'updateMissionTile'     :
        $returned = MissionTileServices::staticUpdate($_POST);
      break;
      default              :
        $returned = 'Erreur dans le POST[action] - '.$_POST['ajaxAction'];
      break;
    }
    return $returned;
  }
  /**
   * @param array $post
   */
  public function dealWithJoinLive($post)
  {
    $deckKey = $post['keyAccess'];
    $LiveServices = FactoryServices::getLiveServices();
    $arr = array(self::CST_DECKKEY=>$deckKey);
    $Lives = $LiveServices->getLivesWithFilters(__FILE__, __LINE__, $arr);
    if (empty($Lives)) {
      $arr['dateUpdate'] = date('Y-m-d H:i:s');
      $Live = new Live($arr);
      $LiveServices->insert(__FILE__, __LINE__, $Live);
      $_SESSION[self::CST_DECKKEY] = $deckKey;
    } else {
      $Live = array_shift($Lives);
      $Live->setDateUpdate(date('Y-m-d H:i:s'));
      $LiveServices->update(__FILE__, __LINE__, $Live);
      $_SESSION[self::CST_DECKKEY] = $deckKey;
    }
  }
  /**
   * @param array $post
   * @return string
   */
  public static function dealWithAddParameter($post)
  {
    $arrExpected = array('player', self::CST_LEVEL);
    $postType = $post['type'];
    if (in_array($postType, $arrExpected)) {
      $args = array();
      $inputs = explode('|', $post['inputs']);
      while (!empty($inputs)) {
        $value = array_pop($inputs);
        list($field, $value) = explode('=', $value);
        $field = str_replace($postType.'-', '', $field);
        if ($field == 'id') {
          continue;
        }
        $args[$field] = $value;
      }
      if ($postType == 'player') {
        $Player = new Player($args);
        $PlayerServices = new PlayerServices();
        $PlayerServices->insert(__FILE__, __LINE__, $Player);
        $Player->setId(MySQL::getLastInsertId());
        return $Player->toJson();
      } elseif ($postType == self::CST_LEVEL) {
        $Level = new Level($args);
        $LevelServices = new LevelServices();
        $LevelServices->insert(__FILE__, __LINE__, $Level);
        $Level->setId(MySQL::getLastInsertId());
        return $Level->toJson();
      }
    }
  }
  /**
   * @param array $post
   * @return string
   */
  public static function dealWithGetParameter($post)
  {
    switch ($post['type']) {
      case 'duration' :
        $DurationServices = FactoryServices::getDurationServices();
        $Duration = $DurationServices->select(__FILE__, __LINE__, $post['id']);
        $returned = $Duration->toJson();
      break;
      case self::CST_LEVEL :
        $LevelServices = FactoryServices::getLevelServices();
        $Level = $LevelServices->select(__FILE__, __LINE__, $post['id']);
        $returned = $Level->toJson();
      break;
      default :
        $returned = '{"msg-error": '.json_encode($post['type'].' non défini dans dealWithGetParameter de AjaxActions.').'}';
      break;
    }
    return $returned;
  }
  /**
   * @param array $post
   * @return string
   */
  public static function dealWithObjRuleDescription($post)
  {
    $description = '';
    if ($post['type'] == 'rule') {
      $RuleServices = new RuleServices();
      $Rule = $RuleServices->select(__FILE__, __LINE__, $post['id']);
      $description = $Rule->getDescription();
    } elseif ($post['type'] == self::CST_OBJECTIVE) {
      $ObjectiveServices = new ObjectiveServices();
      $Objective = $ObjectiveServices->select(__FILE__, __LINE__, $post['id']);
      $description = $Objective->getDescription();
    }
    return $description;
  }
  /**
   * @param array $post
   * @return string
   */
  public static function dealWithAddMissionObjRule($post)
  {
    switch ($post['type']) {
      case 'rule' :
      case 'setting' :
        $insert = MissionRuleActions::staticInsert($post);
      break;
      case self::CST_OBJECTIVE :
        $insert = MissionObjectiveActions::staticInsert($post);
      break;
      default :
        $insert = '';
      break;
    }
    return $insert;
  }
  /**
   * @param array $post
   * @return string
   */
  public static function dealWithRmvMissionObjRule($post)
  {
    switch ($post['type']) {
      case 'rule' :
      case 'setting' :
        $delete = MissionRuleActions::staticDelete($post);
      break;
      case self::CST_OBJECTIVE :
        $delete = MissionObjectiveActions::staticDelete($post);
      break;
      default :
        $delete = '';
      break;
    }
    return $delete;
  }

}
