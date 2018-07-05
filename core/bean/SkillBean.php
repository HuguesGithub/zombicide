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
    parent::__construct();
    $this->SkillServices = FactoryServices::getSkillServices();
    $this->SurvivorSkillServices = FactoryServices::getSurvivorSkillServices();
    $this->Skill = ($Skill=='' ? new Skill() : $Skill);
  }
  /**
   * @return string
   */
  public function getRowForAdminPage()
  {
    $Skill = $this->Skill;
    $queryArgs = array('onglet'=>'skill', self::CST_POSTACTION=>'edit', 'id'=>$Skill->getId());
    $hrefEdit = $this->getQueryArg($queryArgs);
    $queryArgs[self::CST_POSTACTION] = 'trash';
    $hrefTrash = $this->getQueryArg($queryArgs);
    $queryArgs[self::CST_POSTACTION] = 'clone';
    $hrefClone = $this->getQueryArg($queryArgs);
    $urlWpPost = $Skill->getWpPostUrl();
    $args = array(
      // Identifiant de la Competence
      $Skill->getId(),
      // Code de la Compétence
      $Skill->getCode(),
      // Url d'édition
      $hrefEdit,
      // Nom de la Compétence
      $Skill->getName(),
      // Url de suppression
      $hrefTrash,
      // Url de Duplication
      $hrefClone,
      // Article publié ?
      '#',
      // Url Article
      $urlWpPost,
      $Skill->getDescription(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/fragments/skill-row.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getRowForSkillsPage()
  {
    $Skill = $this->Skill;
    $args = array(
      // Id de la Compétence - 1
      $Skill->getId(),
      // Nom de la Compétence - 2
      $Skill->getName(),
      // Nombre de Compétences possédées par un Survivant en Bleu (y compris Zombivant et Ultimate) - 3
      $this->getNbSkillsByTag(array(10, 11)),
      // Nombre de Compétences possédées par un Survivant en Jaune (y compris Zombivant et Ultimate) - 4
      $this->getNbSkillsByTag(array(20)),
      // Nombre de Compétences possédées par un Survivant en Orange (y compris Zombivant et Ultimate) - 5
      $this->getNbSkillsByTag(array(30, 31)),
      // Nombre de Compétences possédées par un Survivant en Rouge (y compris Zombivant et Ultimate) - 6
      $this->getNbSkillsByTag(array(40, 41, 42)),
      // Description de la Compétence - 7
      $Skill->getDescription(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/skill-row-public.php');
    return vsprintf($str, $args);
  }
  /**
   * @param array $arrTags Liste des tags dont on veut le nombre de couples SurvivorSkill
   * @return int
   */
  private function getNbSkillsByTag($arrTags)
  {
    $arrFilters = array('skillId'=>$this->Skill->getId());
    $nb = 0;
    // Pour chaque tag, on fait une recherche en base et on cumule le nombre que l'on renvoie.
    while (!empty($arrTags)) {
      $arrFilters['tagLevelId'] = array_shift($arrTags);
      $SurvivorSkills = $this->SurvivorSkillServices->getSurvivorSkillsWithFilters(__FILE__, __LINE__, $arrFilters);
      $nb += count($SurvivorSkills);
    }
    return $nb;
  }
}
