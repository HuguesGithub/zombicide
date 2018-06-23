<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe EquipmentBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentBean extends MainPageBean {

    public function __construct($EquipmentCard) {
        $this->EquipmentCard = $EquipmentCard;
        parent::__construct();
    }
    /**
     * @param int $expansionId
     * @return string
     */
    public function displayCard($expansionId='') {
        $EquipmentCard = $this->EquipmentCard;
        $arrKeyWords = array();
        $strClasse = '';
        if ( $EquipmentCard->isRanged() ) { $strClasse .= ' ranged weapon'; }
        if ( $EquipmentCard->isMelee() ) { $strClasse .= ' melee weapon'; }
        if ( $EquipmentCard->isPimp() ) {
        	$strClasse .= ' pimp';
        	array_push($arrKeyWords, 'Pimp');
        }
        if ( $EquipmentCard->isStarter() ) {
        	$strClasse .= ' starter';
        	array_push($arrKeyWords, 'Starter');
        }
        if ( $EquipmentCard->isDual() ) {
        	$strClasse .= ' dual';
        	array_push($arrKeyWords, 'Dual');
        }
        $arrKeyWordsToCheck = array(
        		'Embuscade', '9mm', 'Pistolet', 'Précision', 'Vivres', '12mm', 'Recharge', 'Effraction', 'Katana',
        		'Munitions', 'Composite'
        );
        foreach ( $arrKeyWordsToCheck as $keyWord ) {
        	if ( $EquipmentCard->hasKeyword($keyWord) ) {
        		array_push($arrKeyWords, $keyWord);
        	}
        }
        $args = array(
        	// Identifiant de l'extension
        	$expansionId,
        	// Classe
        	$strClasse.(self::isAdmin() ? ' hasTooltip' : ''),
        	// Nom de l'équipement
        	$EquipmentCard->getName(),
        	// Liste des mots-clés pour Debug
        	implode(', ', $arrKeyWords),
        	// URL de l'image
        	$EquipmentCard->getImgUrl($expansionId),
        );
        $str = file_get_contents(PLUGIN_PATH.'web/pages/public/fragments/fragment-equipment-card.php');
        return vsprintf($str, $args);
    }
}
?>
