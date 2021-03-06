<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * MissionRuleActions
 * @since 1.0.00
 * @author Hugues
 */
class MissionRuleActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct()
  {}
  /**
   * @param array $post
   * @return string
   */
  public static function staticInsert($post)
  {
    if ($post['selId']=='') {
      $args = array(
        self::CST_DESCRIPTION=>stripslashes($post[self::CST_DESCRIPTION]),
        self::CST_MISSIONID=>($post['type']==self::CST_MISSIONID),
        'code'=>'CODE_TODO'
      );
      $Rule = new Rule($args);
      $RuleServices = new RuleServices();
      $RuleServices->insert(__FILE__, __LINE__, $Rule);
      $ruleId = MySQL::getLastInsertId();
    } else {
      $ruleId = $post['selId'];
    }
    $args = array(
      self::CST_MISSIONID=>$post[self::CST_MISSIONID],
      'ruleId'=>$ruleId,
      self::CST_TITLE=>stripslashes($post[self::CST_TITLE])
    );
    $MissionRule = new MissionRule($args);
    $MissionRuleServices = new MissionRuleServices();
    $MissionRuleServices->insert(__FILE__, __LINE__, $MissionRule);
    $MissionServices = new MissionServices();
    $Mission = $MissionServices->select(__FILE__, __LINE__, $post[self::CST_MISSIONID]);
    $MissionBean = new MissionBean($Mission);
    if ($post['type']==self::CST_MISSIONID) {
      return $MissionBean->getMissionSettingsBlock();
    } else {
      return $MissionBean->getMissionRulesBlock();
    }
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticDelete($post)
  {
    $MissionRuleServices = new MissionRuleServices();
    $MissionRule = $MissionRuleServices->select(__FILE__, __LINE__, $post['id']);
    $MissionRuleServices->delete(__FILE__, __LINE__, $MissionRule);
    $MissionServices = new MissionServices();
    $Mission = $MissionServices->select(__FILE__, __LINE__, $MissionRule->getMissionId());
    $MissionBean = new MissionBean($Mission);
    if ($post['type']==self::CST_MISSIONID) {
      return $MissionBean->getMissionSettingsBlock();
    } else {
      return $MissionBean->getMissionRulesBlock();
    }
  }
}
