<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe ToolsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ToolsPageBean extends PagePageBean {

	public function __construct($WpPage='') {
		$services = array('Equipment', 'EquipmentExpansion', 'Expansion', 'Spawn', 'SpawnLiveDeck', 'Survivor');
		parent::__construct($WpPage, $services);
	}
  /**
	 * @param WpPost $WpPage
	 * @return string
	 */
	public function getStaticPisteContent($WpPage) {
		$Bean = new ToolsPageBean($WpPage);
		return $Bean->getPisteContent();
	}
	/**
	 * @return string
	 */
	public function getPisteContent() {
    // Nombre de dés à lancer
		$nbDeDes = $this->initVar('nbDeDes');
    // Seuil de réussite
		$seuilReussite = $this->initVar('seuilReussite', 6);
    // Bénéficie de la compétence "Sur un 6 : +1 dé" ?
		$hasSurUn6 = ($this->initVar('surUn6')==1);
    // Bénéficie de la compétence "+1 au résultat du dé" ?
		$hasPlusUnAuDe = ($this->initVar('plusUnAuDe')==1);
    
		if ( is_numeric($nbDeDes) ) {
			$str = 'Résultat de ce lancer <strong>'.$nbDeDes.'D</strong> à <strong>'.$seuilReussite.'+</strong> : ';
			$strJets = '';
			for ( $i=1; $i<=$nbDeDes; $i++ ) {
				$score = rand(1, 6);
				if ( $score == 6 || ($score == 5 && $hasPlusUnAuDe) ) {
					$strClasse = "primary";
					if ( $hasSurUn6 ) { $nbDeDes++; }
				} elseif ( $score==1 ) {
					$strClasse = "danger";
				} elseif ( $score >= ($seuilReussite - ($hasPlusUnAuDe ? 1 : 0)) ) {
					$strClasse = "info";
				} else {
					$strClasse = "warning";
				}
//        $strDice = '<i class="fas fa-dice-'.$this->getNumber($score).'"></i>';
				$strBadge = '<span class="badge badge-'.$strClasse.'">'.$score.'</span>';
				$strJets .= $strBadge.' ';
			}
			$str .= $strJets.'<br><br>';
		}
		$str .= 'Saisir un nombre de dés et les lancer.';
		$args = array(
			$str
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-piste-de-des.php' );
		return vsprintf($str, $args);
	}
  private function getNumber($score) {
    switch ( $score ) {
      case 1 : return 'one'; break;
      case 2 : return 'two'; break;
      case 3 : return 'three'; break;
      case 4 : return 'four'; break;
      case 5 : return 'five'; break;
      case 6 : return 'six'; break;
      default : break;
    }
  }
	/**
	 * @param WpPost $WpPage
	 * @return string
	 */  
	public function getStaticSurvivorsContent($WpPage) {
		$Bean = new ToolsPageBean($WpPage);
		return $Bean->getSurvivorsContent();
	}
	/**
	 * @return string
	 */
	public function getSurvivorsContent() {
		$Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array('displayRank'), array('ASC'));
		if ( !empty($Expansions) ) {
			$str .= '<div class="btn-group-vertical team-selection" role="group">';
			$str .= '<div class="btn-toolbar" role="toolbar">';
			$str .= '  <div class="btn-group" id="nbSurvSel" role="group">';
			for ( $i=1; $i<=6; $i++ ) {
				$str .= '    <button type="button" class="btn btn-dark'.($i==6?' active':'').'" data-nb="'.$i.'">'.$i.'</button>';
			}
			$str .= '  </div>';
			$str .= '</div>';      
			foreach ( $Expansions as $Expansion ) {
				$id = $Expansion->getId();
				$Survivors = $this->SurvivorServices->getSurvivorsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id), 'name', 'ASC');
				if ( empty($Survivors) ) { continue; }
				$str .= '<div type="button" class="btn btn-dark btn-expansion" data-expansion-id="'.$id.'"><span><i class="far fa-square"></i></span> '.$Expansion->getName().'</div>';
				foreach ( $Survivors as $Survivor ) {
					$survivorId = $Survivor->getId();
					$str .= '<button type="button" class="btn btn-secondary btn-survivor hidden" data-expansion-id="'.$id.'" data-survivor-id="'.$survivorId.'"><i class="far fa-square"></i> '.$Survivor->getName().'</button>';
				}
			}
			$str .= '<div type="button" class="btn btn-primary btn-expansion" id="proceedBuildTeam"><span><i class="far fa-check-circle"></i></span> Générer</div>';
			$str .= '</div>';
		}
		$args = array(
			$str
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-selection-survivors.php' );
		return vsprintf($str, $args);
	}
	/**
	 * @param WpPage $WpPage
	 * @return string
	 */
	public function getStaticInvasionsContent($WpPage) {
		$Bean = new ToolsPageBean($WpPage);
		return $Bean->getInvasionsContent();
	}
	/**
	 * @return string
	 */
	public function getInvasionsContent() {
		$Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array('displayRank'), array('ASC'));
		$strFilters = '';
		$strSpawns = '';
		if ( !empty($Expansions) ) {
			foreach ( $Expansions as $Expansion ) {
				$id = $Expansion->getId();
				$SpawnCards = $this->SpawnServices->getSpawnsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id), 'spawnNumber', 'ASC');
				if ( empty($SpawnCards) ) { continue; }
				$strFilters .= '<option value="set-'.$id.'">'.$Expansion->getName().'</option>';
				foreach ( $SpawnCards as $SpawnCard ) {
					$strSpawns .= '<div class="card spawn set-'.$id.'"><img width="320" height="440" src="'.$SpawnCard->getImgUrl().'" alt="#'.$SpawnCard->getSpawnNumber().'"></div>';
				}
			}
		}
		$args = array(
			$strFilters,
			$strSpawns
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-spawncards.php' );
		return vsprintf($str, $args);
	}
	/**
	 * @param WpPage $WpPage
	 * @return string
	 */
	public function getStaticEquipmentsContent($WpPage) {
		$Bean = new ToolsPageBean($WpPage);
		return $Bean->getEquipmentsContent();
	}
	/**
	 * @return string
	 */
	public function getEquipmentsContent() {
    if ( MainPageBean::isAdmin() ) {
		$Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array('displayRank'), array('ASC'));
    $strFilters = '';
    $strEquipments = '';
		if ( !empty($Expansions) ) {
			foreach ( $Expansions as $Expansion ) {
        $id = $Expansion->getId();
				$EquipmentExpansions = $this->EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, array('expansionId'=>$id));
				if ( empty($EquipmentExpansions) ) { continue; }
				$strFilters .= '<option value="set-'.$id.'">'.$Expansion->getName().'</option>';
				foreach ( $EquipmentExpansions as $EquipmentExpansion ) {
          $EquipmentCard = $this->EquipmentServices->select(__FILE__, __LINE__, $EquipmentExpansion->getEquipmentCardId());
          $EquipmentBean = new EquipmentBean($EquipmentCard);
          $strEquipments .= $EquipmentBean->displayCard($id);
				}
			}
		}
    $strCategories = '';
		$strCategories .= '<option value="weapon">Armes</option>';
		$strCategories .= '<option value="melee">Armes de Mêlée</option>';
		$strCategories .= '<option value="ranged">Armes A distance</option>';
		$strCategories .= '<option value="pimp">Armes Pimp</option>';
		$strCategories .= '<option value="dual">Armes Dual</option>';
		$strCategories .= '<option value="starter">Armes de départ</option>';
		$args = array(
			$strFilters,
			$strEquipments,
      $strCategories,
      '','','','',
		);
		$str = file_get_contents( PLUGIN_PATH.'web/pages/public/public-page-equipmentcards.php' );
		return vsprintf($str, $args);
    }
  }
}
?>
