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
class WpPostSkillBean extends WpPostBean
{
  /**
   * Class Constructor
   */
  public function __construct($skillId)
  {
    parent::__construct();
    $this->SkillServices         = new SkillServices();
    $this->SurvivorServices      = new SurvivorServices();
    $this->SurvivorSkillServices = new SurvivorSkillServices();
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
    $arrF = array('skillId'=>$Skill->getId());

    $arrTags = array(
      'blue' => array(10, 11),
      self::CST_YELLOW => array(20),
      self::CST_ORANGE => array(30, 31),
      'red' => array(40, 41, 42),
    );
    $arrLvls = array(1=>'S', 2=>'Z', 3=>'U', 4=>'UZ');
    foreach ($arrTags as $key => $value) {
      while (!empty($value)) {
        $val = array_shift($value);
        $arrF['tagLevelId'] = $val;
        foreach ($arrLvls as $k => $v) {
          $arrF['survivorTypeId'] = $k;
          $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrF);
          foreach ($SurvivorSkills as $SurvivorSkill) {
            $Survivor = $SurvivorSkill->getSurvivor();
            $skills[$key][$k][$Survivor->getNiceName()] = $Survivor;
          }
          if (!empty($skills[$key][$k])) {
            ksort($skills[$key][$k]);
          }
        }
      }
    }
    $strTpl = '<ul class="col-3"><li>%1$s :</li>%2$s</ul>';
    $strBlue  = vsprintf($strTpl, array('S', $this->buildSkillLis($skills['blue'][1], 'blue')));
    $strBlue .= vsprintf($strTpl, array('U', $this->buildSkillLis($skills['blue'][3], 'blue')));
    $strYellow  = vsprintf($strTpl, array('S', $this->buildSkillLis($skills[self::CST_YELLOW][1], self::CST_YELLOW)));
    $strYellow .= vsprintf($strTpl, array('Z', $this->buildSkillLis($skills[self::CST_YELLOW][2], self::CST_YELLOW)));
    $strOrange  = vsprintf($strTpl, array('S', $this->buildSkillLis($skills[self::CST_ORANGE][1], self::CST_ORANGE)));
    $strOrange .= vsprintf($strTpl, array('Z', $this->buildSkillLis($skills[self::CST_ORANGE][2], self::CST_ORANGE)));
    $strOrange .= vsprintf($strTpl, array('U', $this->buildSkillLis($skills[self::CST_ORANGE][3], self::CST_ORANGE)));
    $strOrange .= vsprintf($strTpl, array('UZ', $this->buildSkillLis($skills[self::CST_ORANGE][4], self::CST_ORANGE)));
    $strRed  = vsprintf($strTpl, array('S', $this->buildSkillLis($skills['red'][1], 'red')));
    $strRed .= vsprintf($strTpl, array('Z', $this->buildSkillLis($skills['red'][2], 'red')));
    $strRed .= vsprintf($strTpl, array('U', $this->buildSkillLis($skills['red'][3], 'red')));
    $strRed .= vsprintf($strTpl, array('UZ', $this->buildSkillLis($skills['red'][4], 'red')));
    $args = array(
      // Nom de la Compétence - 1
      $Skill->getName(),
      // Description de la Compétence - 2
      $Skill->getDescription(),
      // Liste des Survivants ayant la compétence en Bleu (Zombivant et Ultimate compris) - 3
      $strBlue,
      // Liste des Survivants ayant la compétence en Jaune (Zombivant et Ultimate compris) - 4
      $strYellow,
      // Liste des Survivants ayant la compétence en Orange (Zombivant et Ultimate compris) - 5
      $strOrange,
      // Liste des Survivants ayant la compétence en Rouge (Zombivant et Ultimate compris) - 6
      $strRed,
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
