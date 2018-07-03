<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPostSkillBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPostSkillBean extends PostPageBean
{
  /**
   * Class Constructor
   */
  public function __construct($skillId)
  {
    $services = array('Skill', 'SurvivorSkill', 'Survivor');
    parent::__construct('', $services);
  $this->Skill = $this->SkillServices->select(__FILE__, __LINE__, $skillId);
  }
  /**
   * On arrive rarement en mode direct pour afficher la Page. On passe par une méthode static.
   * @return string
   */
  public static function getStaticPageContent($skillId)
  {
    $Bean = new WpPostSkillBean($skillId);
    return $Bean->getContentPage();
  }
  /**
   * On retourne la page dédiée à la compétence.
   * @return string
   */
  public function getContentPage()
  {
    $Skill = $this->Skill;
    $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, array('skillId'=>$Skill->getId()));
    if (!empty($SurvivorSkills)) {
      foreach ($SurvivorSkills as $SurvivorSkill) {
        $Survivor = $this->SurvivorServices->select(__FILE__, __LINE__, $SurvivorSkill->getSurvivorId());
        switch ($SurvivorSkill->getTagLevelId()) {
          case 10 :
          case 11 :
            $blueSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          case 20 :
            $yellowSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          case 30 :
          case 31 :
            $orangeSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          case 40 :
          case 41 :
          case 42 :
            $redSkills[$Survivor->getNiceName()] = $Survivor;
          break;
          default :
          break;
        }
      }
    }
    $args = array(
      $Skill->getName(),
      $Skill->getDescription(),
      $this->buildSkillLis($blueSkills, 'blue'),
      $this->buildSkillLis($yellowSkills, 'yellow'),
      $this->buildSkillLis($orangeSkills, 'orange'),
      $this->buildSkillLis($redSkills, 'red'),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-skill.php');
    return vsprintf($str, $args);
  }
  /**
   * Retourne la liste des Survivants ayant une compétence à ce niveau, dans des cartouches de couleur.
   * @param array $SurvivorSkills Une liste des Survivants ayant cette compétence
   * @param string $color Permet de colorer le cartouche
   * @return string
   */
  public function buildSkillLis($Survivors, $color)
  {
    $strLis = '';
    if (!empty($Survivors)) {
      ksort($Survivors);
    while (!empty($Survivors)) {
      $Survivor = array_shift($Survivors);
        $strLis .= '<li><a class="badge badge-'.$color.'-skill" href="';
    $strLis .= $Survivor->getWpPostUrl().'">'.$Survivor->getName().'</a></li>';
      }
    }
    return $strLis;
  }
}
