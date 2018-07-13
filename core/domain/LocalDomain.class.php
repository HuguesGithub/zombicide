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
  public function __construct($attributes=array())
  { parent::__construct($attributes); }

  /**
   * @param int $expansionId
   * @return Expansion
   */
  public function getExpansionFromGlobal($expansionId)
  {
    global $globalExpansions;
    if (!empty($globalExpansions)) {
      foreach ($globalExpansions as $Expansion) {
        if ($Expansion->getId()==$expansionId) {
          return $Expansion;
        }
      }
    }
    $GlobalExpansion = $this->ExpansionServices->select(__FILE__, __LINE__, $expansionId);
    if ($GlobalExpansion != null) {
      $globalExpansions[] = $GlobalExpansion;
    }
    return $GlobalExpansion;
  }
  /**
   * @param int $origineId
   * @return Origine
   */
  protected function getOrigineFromGlobal($origineId)
  {
    global $globalOrigines;
    if (!empty($globalOrigines)) {
      foreach ($globalOrigines as $Origine) {
        if ($Origine->getId() == $origineId) {
         return $Origine;
        }
      }
    }
    $GlobalOrigine = $this->OrigineServices->select(__FILE__, __LINE__, $origineId);
    if ($GlobalOrigine != null) {
      $globalOrigines[] = $GlobalOrigine;
    }
    return $GlobalOrigine;
  }
  
  
  

  /**
   * @param int $objectiveId
   * @return Objective
   *
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
   * @param int $ruleId
   * @return Rule
   *
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
   *
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
