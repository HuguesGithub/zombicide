<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * MissionObjectiveActions
 * @since 1.0.00
 * @author Hugues
 */
class MissionObjectiveActions extends LocalActions
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
        'code'=>'CODE_TODO'
      );
      $Objective = new Objective($args);
      $ObjectiveServices = new ObjectiveServices();
      $ObjectiveServices->insert(__FILE__, __LINE__, $Objective);
      $objectiveId = MySQL::getLastInsertId();
    } else {
      $objectiveId = $post['selId'];
    }
    $args = array(
      self::CST_MISSIONID=>$post[self::CST_MISSIONID],
      'objectiveId'=>$objectiveId,
      self::CST_TITLE=>stripslashes($post[self::CST_TITLE])
    );
    $MissionObjective = new MissionObjective($args);
    $MissionObjectiveServices = new MissionObjectiveServices();
    $MissionObjectiveServices->insert(__FILE__, __LINE__, $MissionObjective);
    $MissionServices = new MissionServices();
    $Mission = $MissionServices->select(__FILE__, __LINE__, $post[self::CST_MISSIONID]);
    $MissionBean = new MissionBean($Mission);
    return $MissionBean->getMissionObjectivesBlock();
  }
  /**
   * @param array $post
   * @return string
   */
  public static function staticDelete($post)
  {
    $MissionObjectiveServices = new MissionObjectiveServices();
    $MissionObjective = $MissionObjectiveServices->select(__FILE__, __LINE__, $post['id']);
    $MissionObjectiveServices->delete(__FILE__, __LINE__, $MissionObjective);
    $MissionServices = new MissionServices();
    $Mission = $MissionServices->select(__FILE__, __LINE__, $MissionObjective->getMissionId());
    $MissionBean = new MissionBean($Mission);
    return $MissionBean->getMissionObjectivesBlock();
  }
  
}
