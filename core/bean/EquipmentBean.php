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
        $strDiv  = '<div class="card equipment set-'.$expansionId;
        $strToolTip = '<div class="tooltip"><div>'.$EquipmentCard->getName().'</div>';
        $arrKeyWords = array();
        if ( $EquipmentCard->isRanged() ) { $strDiv .= ' ranged weapon'; }
        if ( $EquipmentCard->isMelee() ) { $strDiv .= ' melee weapon'; }
        if ( $EquipmentCard->isPimp() ) { 
            $strDiv .= ' pimp';
            array_push($arrKeyWords, 'Pimp');
        }
        if ( $EquipmentCard->isStarter() ) {
            $strDiv .= ' starter';
            array_push($arrKeyWords, 'Starter');
        }
        if ( $EquipmentCard->isDual() ) {
            $strDiv .= ' dual';
            array_push($arrKeyWords, 'Dual');
        }
        $arrKeyWordsToCheck = array('Embuscade', '9mm', 'Pistolet', 'Précision', 'Vivres', '12mm', 'Recharge', 'Effraction', 'Katana', 'Munitions', 'Composite');
        foreach ( $arrKeyWordsToCheck as $keyWord ) {
            if ( $EquipmentCard->hasKeyword($keyWord) ) { 
                array_push($arrKeyWords, $keyWord);
            }
        }
        if ( !empty($arrKeyWords) ) {
            $strToolTip .= '<div>'.implode(', ', $arrKeyWords).'</div>';
        }
        $strToolTip .= '</div>';
        $strDiv .= ' hasTooltip">';
        if ( $this->isAdmin() ) { $strDiv .= $strToolTip; }
        $strDiv .= '<img src="'.$EquipmentCard->getImgUrl($expansionId).'" alt="'.$EquipmentCard->getName().'"></div>';
        return $strDiv;
    }
}
?>
