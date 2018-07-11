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
class SurvivorBean extends MainPageBean
{

  public function __construct($Survivor='')
  {
    $services = array('Survivor', 'Expansion');
    parent::__construct($services);
    if ($Survivor=='') {
      $Survivor = new Survivor();
    }
    $this->Survivor = $Survivor;
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
      ($Survivor->isZombivor()?'changeProfile':''),
      // Le Survivant a-t-il un profil Zombivant ?
      '<i class="far fa-'.($Survivor->isZombivor()?'square pointer':'window-close').'"></i>',
      //
      ($Survivor->isUltimate()?'changeProfile':''),
      // Le Survivant a-t-il un profil Ultimate ?
      '<i class="far fa-'.($Survivor->isUltimate()?'square pointer':'window-close').'"></i>',
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
    $urlWpPost = $Survivor->getWpPostUrl();
    $strRow  = '<tr class="survivant">';
    $strRow .= '<td rowspan="3">'.$this->getAllPortraits().'</td>';
    $strRow .= '<td><a href="'.$urlWpPost.'">'.$Survivor->getName().'</a></td>';
    $strRow .= '<td data-id="'.$Survivor->getId().'" data-type="zombivant" class="'.($Survivor->isZombivor()?'changeProfile':'');
    $strRow .= '"><i class="far fa-'.($Survivor->isZombivor()?'square pointer':'window-close').'"></i></td>';
    $strRow .= '<td data-id="'.$Survivor->getId().'" data-type="ultimate" class="'.($Survivor->isUltimate()?'changeProfile':'');
    $strRow .= '"><i class="far fa-'.($Survivor->isUltimate()?'square pointer':'window-close').'"></i></td>';
    $strRow .= '<td>'.$Survivor->getExpansionName().'</td>';
    $strRow .= '<td>'.$this->getAllSkills().'</td>';
    $strRow .= '</tr>';
    $strRow .= '<tr><td colspan="5" style="height:0;line-height:0;padding:0;border:0 none;">&nbsp;</td></tr>';
    return $strRow.'<tr><td colspan="5">'.$Survivor->getBackground().'</td></tr>';
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
    $str  = $this->getStrImgPortrait($Survivor->getPortraitUrl(), 'Portrait Survivant - '.$name, 'portrait-survivant');
    if ($Survivor->isZombivor()) {
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('z'), 'Portrait Zombivant - '.$name, 'portrait-zombivant');
    }
    if ($Survivor->isUltimate()) {
      $extraClass = ' portrait-ultimate';
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('u'), 'Portrait Ultimate - '.$name, 'portrait-survivant'.$extraClass);
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('uz'), 'Portrait ZUltimate - '.$name, 'portrait-zombivant'.$extraClass);
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
      $this->getStrImgPortrait($Survivor->getPortraitUrl(), 'Portrait Survivant - '.$name, 'portrait-survivant'),
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
