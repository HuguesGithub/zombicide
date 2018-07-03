<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SkillBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param Skill $Skill
   */
  public function __construct($Skill='')
  {
    $services = array('Skill', 'SurvivorSkill');
    parent::__construct($services);
    if ($Skill=='') {
      $Skill = new Skill();
    }
    $this->Skill = $Skill;
  }
  
  /**
   * @return string
   */
  public function getRowForAdminPage()
  {
    return '';
  }
  /**
   * @return string
   */
  public function getRowForSkillsPage()
  {
    $Skill = $this->Skill;
    $arrFilters = array('skillId'=>$Skill->getId(), 'tagLevelId'=>10);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbBlues = count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 11;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbBlues += count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 20;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbYellows = count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 30;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbOranges = count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 31;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbOranges += count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 40;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbReds = count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 41;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbReds += count($SurvivorSkills);
    $arrFilters['tagLevelId'] = 42;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
    $nbReds += count($SurvivorSkills);
    $strRow  = '<tr>';
    $strRow .= '<td style="white-space: nowrap;"><a href="/page-competences/?skillId='.$Skill->getId().'">'.$Skill->getName().'</a><br>';
    $strRow .= '<span class="badge badge-blue-skill">'.$nbBlues.'</span> <span class="badge badge-yellow-skill">'.$nbYellows.'</span> ';
    $strRow .= '<span class="badge badge-orange-skill">'.$nbOranges.'</span> <span class="badge badge-red-skill">'.$nbReds.'</span></td>';
    $strRow .= '<td>'.$Skill->getDescription().'</td>';
    return $strRow.'</tr>';
  }
  
}
