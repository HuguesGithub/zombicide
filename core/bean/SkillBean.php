<?php
if (!defined('ABSPATH')) { die('Forbidden'); }
/**
 * Classe SkillBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillBean extends MainPageBean {

  public function __construct($Skill='') {
    $services = array('Skill', 'SurvivorSkill');
    parent::__construct($services);
    if ($Skill=='') { $Skill = new Skill(); }
    $this->Skill = $Skill;
  }
  
  /**
   * @return string
   */  
  public function getRowForAdminPage() {
    return '';
  }
  /**
   * @return string
   */  
  public function getRowForSkillsPage() {
    $Skill = $this->Skill;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>10));
    $nbBlues = count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>11));
    $nbBlues += count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>20));
    $nbYellows = count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>30));
    $nbOranges = count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>31));
    $nbOranges += count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>40));
    $nbReds = count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>41));
    $nbReds += count($SurvivorSkills);
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId(), 'tagLevelId'=>42));
    $nbReds += count($SurvivorSkills);
    $strRow  = '<tr>';
    $strRow .= '<td style="white-space: nowrap;"><a href="/page-competences/?skillId='.$Skill->getId().'">'.$Skill->getName().'</a><br>';
    $strRow .= '<span class="badge badge-blue-skill">'.$nbBlues.'</span> <span class="badge badge-yellow-skill">'.$nbYellows.'</span> ';
    $strRow .= '<span class="badge badge-orange-skill">'.$nbOranges.'</span> <span class="badge badge-red-skill">'.$nbReds.'</span></td>';
    $strRow .= '<td>'.$Skill->getDescription().'</td>';
    return $strRow.'</tr>';
  }
  
}
?>