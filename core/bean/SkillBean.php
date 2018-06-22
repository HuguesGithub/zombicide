<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
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