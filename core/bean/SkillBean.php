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
    $strSkillsCartouches = '';
    $strTpl = '<span class="badge badge-%1$s-skill">%2$s : %3$s</span> ';
    $arrTags = array(
      'blue' => array(10, 11),
      'yellow' => array(20),
      'orange' => array(30, 31),
      'red' => array(40, 41, 42),
    );
    $arrLvls = array(1=>'S', 2=>'Z', 3=>'U', 4=>'UZ');
    foreach ($arrTags as $key => $value) {
      foreach ($arrLvls as $k => $v) {
        $nb = $this->getNbSkillsByTag($value, $k);
        if ($nb!=0) {
          $strSkillsCartouches .= vsprintf($strTpl, array($key, $v, $nb));
        }
      }
    }
    $args = array(
      // Id de la Compétence - 1
      $Skill->getId(),
      // Nom de la Compétence - 2
      $Skill->getName(),
      // Nombre de Compétences possédées par un Survivant en Bleu Survivant - 3
      $strSkillsCartouches,
      // Description de la Compétence - 4
      $Skill->getDescription(),
      /*
      // Nombre de Compétences possédées par un Survivant en Bleu Ultimate - 4
      $this->getNbSkillsByTag(array(11), 3),
      // Nombre de Compétences possédées par un Survivant en Jaune Survivant - 5
      $this->getNbSkillsByTag(array(20)),
      // Nombre de Compétences possédées par un Survivant en Jaune Zombivant - 6
      $this->getNbSkillsByTag(array(20), 2),
      // Nombre de Compétences possédées par un Survivant en Orange (y compris Ultimate) - 7
      $this->getNbSkillsByTag(array(30, 31)),
      // Nombre de Compétences possédées par un Survivant en Orange (y compris Ultimate) - 8
      $this->getNbSkillsByTag(array(30, 31), 2),
      // Nombre de Compétences possédées par un Survivant en Rouge (y compris Ultimate) - 9
      $this->getNbSkillsByTag(array(40, 41, 42)),
      // Nombre de Compétences possédées par un Survivant en Rouge (y compris Ultimate) - 10
      $this->getNbSkillsByTag(array(40, 41, 42), 2),
      // Description de la Compétence - 11
      $Skill->getDescription(),
      */
    );
    /*    <ul>
      <li>
    <span class="badge badge-blue-skill">S : %3$s</span>
    <span class="badge badge-blue-skill">U : %4$s</span>
      </li>
      <li>
    <span class="badge badge-yellow-skill">S : %5$s</span>
    <span class="badge badge-yellow-skill">Z : %6$s</span>
      </li>
      <li>
    <span class="badge badge-orange-skill">S : %7$s</span>
    <span class="badge badge-orange-skill">Z : %8$s</span>
      </li>
      <li>
    <span class="badge badge-red-skill">S : %9$s</span>
    <span class="badge badge-red-skill">Z : %10$s</span>
      </li>
    </ul>
*/
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/skill-row-public.php');
    return vsprintf($str, $args);
  }
  /**
   * @param array $arrTags Liste des tags dont on veut le nombre de couples SurvivorSkill
   * @return int
   */
  private function getNbSkillsByTag($arrTags, $type=1)
  {
    $arrFilters = array(
      'skillId'=>$this->Skill->getId(),
      'survivorTypeId'=>$type,
    );
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
