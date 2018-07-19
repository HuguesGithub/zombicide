<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe SurvivorBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorBean extends LocalBean
{
  private $strPortraitSurvivant = 'portrait-survivant';
  private $strPortraitZombivant = 'portrait-zombivant';
	
  public function __construct($Survivor='')
  {
    parent::__construct();
    $this->Survivor = ($Survivor=='' ? new Survivor() : $Survivor);
    $this->ExpansionServices = new ExpansionServices();
    $this->SurvivorServices  = new SurvivorServices();
  }
  
  /**
   * @return string
   */
  public function getRowForAdminPage()
  {
    $Survivor = $this->Survivor;
    $queryArgs = array('onglet'=>self::CST_SURVIVOR, self::CST_POSTACTION=>'edit', 'id'=>$Survivor->getId());
    $hrefEdit = $this->getQueryArg($queryArgs);
    $queryArgs[self::CST_POSTACTION] = 'trash';
    $hrefTrash = $this->getQueryArg($queryArgs);
    $queryArgs[self::CST_POSTACTION] = 'clone';
    $hrefClone = $this->getQueryArg($queryArgs);
    $urlWpPost = $Survivor->getWpPostUrl();
    $args = array(
      // Identifiant du Survivant
      $Survivor->getId(),
      // Url d'édition
      $hrefEdit,
      // Nom du Survivant
      $Survivor->getName(),
      // Url de suppression
      $hrefTrash,
      // Url de Duplication
      $hrefClone,
      // Article publié ?
      $urlWpPost!='#' ? '' : ' hidden',
      // Url Article
      $urlWpPost,
      //
      ($Survivor->isZombivor()?self::CST_CHANGEPROFILE:''),
      // Le Survivant a-t-il un profil Zombivant ?
      '<i class="far fa-'.($Survivor->isZombivor()?self::CST_SQUAREPOINTER:self::CST_WINDOWCLOSE).'"></i>',
      //
      ($Survivor->isUltimate()?self::CST_CHANGEPROFILE:''),
      // Le Survivant a-t-il un profil Ultimate ?
      '<i class="far fa-'.($Survivor->isUltimate()?self::CST_SQUAREPOINTER:self::CST_WINDOWCLOSE).'"></i>',
      // Extension de provenance
      $Survivor->getExpansionName(),
      // Background du Survivant
      $Survivor->getBackground(),
      // Nom de l'image alternative, si défini.
      $Survivor->getAltImgName(),
      // Portraits
      $this->getAllPortraits(),
      '', '', '', '', '', '', '',
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/fragments/survivor-row.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getRowForSurvivorsPage()
  {
    $Survivor = $this->Survivor;
  	$args = array(
  	  // Les portraits du Survivants - 1
      $this->getAllPortraits(),
  	  // Url du WpPost associé, s'il existe - 2
      $Survivor->getWpPostUrl(),
  	  // Nom du Survivant - 3
  	  $Survivor->getName(),
  	  // Id du Survivant - 4
  	  $Survivor->getId(),
  	  // Si on a un profil de Zombivant, on donne la possibilité de l'afficher - 5
      ($Survivor->isZombivor()?self::CST_CHANGEPROFILE:''),
  	  // Si on a un profil de Zombivant, on veut une case à cocher - 6
      ($Survivor->isZombivor()?self::CST_SQUAREPOINTER:self::CST_WINDOWCLOSE),
  	  // Si on a un profil d'Ultimate, on donne la possibilité de l'afficher - 7
      ($Survivor->isUltimate()?self::CST_CHANGEPROFILE:''),
  	  // Si on a un profil d'Ultimate, on veut une case à cocher - 8
      ($Survivor->isUltimate()?self::CST_SQUAREPOINTER:self::CST_WINDOWCLOSE),
  	  // Extension à laquelle est rattaché le Survivant - 9
  	  $Survivor->getExpansionName(),
  	  // Liste des Compétences du Survivant - 10
  	  $this->getAllSkills(),
  	  // Background du Survivant - 11
  	  $Survivor->getBackground(),
  	);
  	$str = file_get_contents(PLUGIN_PATH.'web/pages/admin/fragments/survivor-row-public.php');
  	return vsprintf($str, $args);
  }
  public function getAllSkills()
  {
    $Survivor = $this->Survivor;
    $str  = '<ul>';
    $str .= $this->getSkillsBySurvivorType('skills-survivant', $Survivor->getUlSkills('', true));
    if ($Survivor->isZombivor()) {
      $str .= $this->getSkillsBySurvivorType('skills-zombivant', $Survivor->getUlSkills('z', true));
    }
    if ($Survivor->isUltimate()) {
      $str .= $this->getSkillsBySurvivorType('skills-ultimate skills-survivant', $Survivor->getUlSkills('u', true));
      $str .= $this->getSkillsBySurvivorType('skills-ultimate skills-zombivant', $Survivor->getUlSkills('uz', true));
    }
    return $str.'</ul>';
  }
  public function getSkillsBySurvivorType($addClass, $content)
  { return '<li class="'.$addClass.'">'.$content.'</li>'; }
  public function getAllPortraits()
  {
    $Survivor = $this->Survivor;
    $name = $Survivor->getName();
    $str  = $this->getStrImgPortrait($Survivor->getPortraitUrl(), 'Portrait Survivant - '.$name, $this->strPortraitSurvivant);
    if ($Survivor->isZombivor()) {
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('z'), 'Portrait Zombivant - '.$name, $this->strPortraitZombivant);
    }
    if ($Survivor->isUltimate()) {
      $extraClass = ' portrait-ultimate';
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('u'), 'Portrait Ultimate - '.$name, $this->strPortraitSurvivant.$extraClass);
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('uz'), 'Portrait ZUltimate - '.$name, $this->strPortraitZombivant.$extraClass);
    }
    return $str;
  }
  public function getStrImgPortrait($src, $alt, $addClass)
  { return '<img src="'.$src.'" alt="'.$alt.'" class="thumb '.$addClass.'"/>'; }
  /**
   * @param string $addClass
   * @return string
   */
  public function getVisitCard($addClass='')
  {
    $Survivor = $this->Survivor;
    $name = $Survivor->getName();
    $args = array(
      $this->getStrImgPortrait($Survivor->getPortraitUrl(), 'Portrait Survivant - '.$name, $this->strPortraitSurvivant),
      $name,
      $this->getSkillsBySurvivorType('skills-survivant', $Survivor->getUlSkills()),
      ($addClass==''?'':' '.$addClass),
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/article-survivor-cardvisit.php');
    return vsprintf($str, $args);
  }
  /**
   * @return string
   */
  public function getSurvivorPage()
  {
    $Survivor = $this->Survivor;
    $strType  = '';
    if ($Survivor->isZombivor()) {
      $strType .= '<div data-id="'.$Survivor->getId().'" data-type="zombivant" class="changeProfile">';
      $strType .= '<i class="far fa-square pointer"></i> Zombivant</div>';
      if ($Survivor->isUltimate()) {
        $strType .= '&nbsp;<div data-id="'.$Survivor->getId().'" data-type="ultimate" class="changeProfile">';
        $strType .= '<i class="far fa-square pointer"></i> Ultimate</div>';
      }
    }
    $args = array(
      $this->getAllPortraits(),
      $Survivor->getName(),
      $Survivor->getBackground(),
      $strType,
      $this->getAllSkills(),
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-survivor.php');
    return vsprintf($str, $args);
  }
  
}
