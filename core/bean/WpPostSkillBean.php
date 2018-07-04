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
    parent::__construct();
    $this->SkillServices = FactoryServices::getSkillServices();
    $this->SurvivorServices = FactoryServices::getSurvivorServices();
    $this->SurvivorSkillServices = FactoryServices::getSurvivorSkillServices();
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
        $Survivor = $SurvivorSkill->getSurvivor();
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
      // Nom de la Compétence - 1
      $Skill->getName(),
      // Description de la Compétence - 2
      $Skill->getDescription(),
      // Liste des Survivants ayant la compétence en Bleu (Zombivant et Ultimate compris) - 3
      $this->buildSkillLis($blueSkills, 'blue'),
      // Liste des Survivants ayant la compétence en Jaune (Zombivant et Ultimate compris) - 4
      $this->buildSkillLis($yellowSkills, 'yellow'),
      // Liste des Survivants ayant la compétence en Orange (Zombivant et Ultimate compris) - 5
      $this->buildSkillLis($orangeSkills, 'orange'),
      // Liste des Survivants ayant la compétence en Rouge (Zombivant et Ultimate compris) - 6
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
    $tplLi = '<li><a class="badge badge-%1$s-skill" href="%2$s">%3$s</a></li>';
    $strLis = '';
    if (!empty($Survivors)) {
      ksort($Survivors);
      while (!empty($Survivors)) {
        $Survivor = array_shift($Survivors);
        $strLis .= vsprintf($tplLi, array($color, $Survivor->getWpPostUrl(), $Survivor->getName()));
      }
    }
    return $strLis;
  }
}
