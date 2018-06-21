<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe SkillBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillBean extends MainPageBean {

	public function __construct($Skill='') {
		$services = array('Skill');
		parent::__construct($services);
		if ( $Skill=='' ) { $Skill = new Skill(); }
		$this->Skill = $Skill;
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
	public function getRowForSkillsPage() {
		$Skill = $this->Skill;
		$strRow  = '<tr>';
		$strRow .= '<td style="white-space: nowrap;">'.$Skill->getName().'</td>';
		$strRow .= '<td>'.$Skill->getDescription().'</td>';
		$strRow .= '</tr>';
		return $strRow;
	}
  
}
?>