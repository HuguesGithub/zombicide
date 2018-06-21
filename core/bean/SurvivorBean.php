<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe SurvivorBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SurvivorBean extends MainPageBean {

	public function __construct($Survivor='') {
		$services = array('Survivor', 'Expansion');
		parent::__construct($services);
		if ( $Survivor=='' ) { $Survivor = new Survivor(); }
		$this->Survivor = $Survivor;
	}
  
	/**
	 * @return string
	 */	
	public function getRowForAdminPage() {
		/*
		$Mission = $this->Mission;
		$args = array('onglet'=>'mission', 'postAction'=>'edit', 'id'=>$Mission->getId());
		$strRow  = '<tr>';
		$strRow .= '<td><input id="cb-select-'.$Mission->getId().'" name="post[]" value="'.$Mission->getId().'" type="checkbox"></td>';
		$strRow .= '<td>'.$Mission->getCode().'</td>';
		$strRow .= '<td><strong><a class="row-title" href="'.$this->getQueryArg($args).'">'.$Mission->getTitle().'</strong>';
		$strRow .= '<div class="row-actions">';
		$strRow .= '<span class="edit"><a href="'.$this->getQueryArg($args).'">Modifier</a></span>';
		$args['postAction'] = 'trash';
		$strRow .= '<span class="trash"> | <a href="'.$this->getQueryArg($args).'">Corbeille</a></span>';
		$args['postAction'] = 'clone';
		$strRow .= '<span class="clone"> | <a href="'.$this->getQueryArg($args).'">Dupliquer</a></span>';
		$urlWpPost = $Mission->getWpPostUrl();
		if ( $urlWpPost!='#' ) { $strRow .= '<span class="view"> | <a href="'.$urlWpPost.'">Aperçu</a></span>'; }
		$strRow .= '</div>';
		$strRow .= '</td>';
		$strRow .= '<td>'.$Mission->getStrDifficulty().'</td>';
		$strRow .= '<td>'.$Mission->getStrDuree().'</td>';
		$strRow .= '<td>'.$Mission->getStrNbJoueurs().'</td>';
		$strRow .= '<td>'.$Mission->getStrOrigine().'</td>';
		$strRow .= '<td>'.$Mission->getStrTiles().'</td>';
		$strRow .= '<td class="objectivesAndRules">'.$Mission->getStrRules().'</td>';
		$strRow .= '<td class="objectivesAndRules">'.$Mission->getStrObjectives().'</td>';
		$strRow .= '<td>'.$Mission->getStrExpansions().'</td>';
		$strRow .= '</tr>';
		*/
    return $strRow;
  }
	/**
	 * @return string
	 */	
	public function getRowForSurvivorsPage() {
		$Survivor = $this->Survivor;
		$strRow  = '<tr class="survivant">';
		$strRow .= '<td rowspan="3">'.$this->getAllPortraits().'</td>';
		$strRow .= '<td>'.$Survivor->getName().'</td>';
		$strRow .= '<td data-id="'.$Survivor->getId().'" data-type="zombivant" class="'.($Survivor->isZombivor()?'changeProfile':'').'"><i class="far fa-'.($Survivor->isZombivor()?'square pointer':'window-close').'"></td>';
		$strRow .= '<td data-id="'.$Survivor->getId().'" data-type="ultimate" class="'.($Survivor->isUltimate()?'changeProfile':'').'"><i class="far fa-'.($Survivor->isUltimate()?'square pointer':'window-close').'"></td>';
		$strRow .= '<td>'.$Survivor->getExpansionName().'</td>';
		$strRow .= '<td>'.$this->getAllSkills().'</td>';
		$strRow .= '</tr>';
		$strRow .= '<tr><td colspan="5" style="height:0;line-height:0;padding:0;border:0 none;">&nbsp;</td></tr>';
		$strRow .= '<tr><td colspan="5">'.$Survivor->getBackground().'</td></tr>';
		return $strRow;
	}
	private function getAllSkills() {
		$Survivor = $this->Survivor;
		$str  = '<ul>';
		$str .= $this->getSkillsBySurvivorType('skills-survivant', $Survivor->getUlSkills());
		if ( $Survivor->isZombivor() ) {
			$str .= $this->getSkillsBySurvivorType('skills-zombivant', $Survivor->getUlSkills('z'));
		}
		if ( $Survivor->isUltimate() ) {
			$str .= $this->getSkillsBySurvivorType('skills-ultimate skills-survivant', $Survivor->getUlSkills('u'));
			$str .= $this->getSkillsBySurvivorType('skills-ultimate skills-zombivant', $Survivor->getUlSkills('uz'));
		}
		$str .= '</ul>';
		return $str;
	}
	private function getSkillsBySurvivorType($addClass, $content) { return '<li class="'.$addClass.'">'.$content.'</li>'; }
	private function getAllPortraits() {
		$Survivor = $this->Survivor;
		$name = $Survivor->getName();
		$str  = $this->getStrImgPortrait($Survivor->getPortraitUrl(), 'Portrait Survivant - '.$name, 'portrait-survivant');
		if ( $Survivor->isZombivor() ) {
			$str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('z'), 'Portrait Zombivant - '.$name, 'portrait-zombivant');
		}
		if ( $Survivor->isUltimate() ) {
			$str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('u'), 'Portrait Ultimate - '.$name, 'portrait-survivant portrait-ultimate');
			$str .= $this->getStrImgPortrait($Survivor->getPortraitUrl('uz'), 'Portrait ZUltimate - '.$name, 'portrait-zombivant portrait-ultimate');
		}
		return $str;
	}
	private function getStrImgPortrait($src, $alt, $addClass) { return '<img src="'.$src.'" alt="'.$alt.'" class="thumb '.$addClass.'"/>'; }
	/**
	 * @param string $addClass
	 * @return string
	 */
	public function getVisitCard($addClass='') {
		$Survivor = $this->Survivor;
		$name = $Survivor->getName();
		$args = array(
			$this->getStrImgPortrait($Survivor->getPortraitUrl(), 'Portrait Survivant - '.$name, 'portrait-survivant'),
			$name,
			$this->getSkillsBySurvivorType('skills-survivant', $Survivor->getUlSkills()),
			($addClass==''?'':' '.$addClass),
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/fragments/article-survivor-cardvisit.php' );
		return vsprintf($str, $args);
	}
  
}
?>