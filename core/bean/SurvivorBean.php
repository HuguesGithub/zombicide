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
    return '';
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
    $strRow .= '<td data-id="'.$Survivor->getId().'" data-type="zombivant" class="'.($Survivor->isZombivor()?'changeProfile':'').'"><i class="far fa-'.($Survivor->isZombivor()?'square pointer':'window-close').'"></i></td>';
    $strRow .= '<td data-id="'.$Survivor->getId().'" data-type="ultimate" class="'.($Survivor->isUltimate()?'changeProfile':'').'"><i class="far fa-'.($Survivor->isUltimate()?'square pointer':'window-close').'"></i></td>';
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
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('u'), 'Portrait Ultimate - '.$name, 'portrait-survivant portrait-ultimate');
      $str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('uz'), 'Portrait ZUltimate - '.$name, 'portrait-zombivant portrait-ultimate');
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
  public function getSurvivorPage() {
    $Survivor = $this->Survivor;
    $strSType  = '';
    if ($Survivor->isZombivor()) {
      $strType .= '<div data-id="'.$Survivor->getId().'" data-type="zombivant" class="changeProfile"><i class="far fa-square pointer"></i> Zombivant</div>';
      if ($Survivor->isUltimate()) {
        $strType .= '&nbsp;<div data-id="'.$Survivor->getId().'" data-type="ultimate" class="changeProfile"><i class="far fa-square pointer"></i> Ultimate</div>';
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
