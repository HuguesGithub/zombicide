<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe Equipment
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Equipment extends LocalDomain {
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    /**
     * Nom de la carte
     * @var string $name
     */
    protected $name;
    /**
     * Abilité spéciale de l'équipement
     * @var string $textAbility
     */
    protected $textAbility;
    /**
     * @param array $attributes
     */
    public function __construct($attributes=array()) {
    $services = array('EquipmentKeyword', 'EquipmentWeaponProfile');
        parent::__construct($attributes, $services);
    }
    /**
     * @return $id
     */
    public function getId() {return $this->id; }
    /**
     * @return $name
     */
    public function getName() { return $this->name; }
    /**
     * @return $textAbility
     */
    public function getTextAbility() { return $this->textAblity; }
    /**
     * @param int $id
     */
    public function setId($id) { $this->id=$id; }
    /**
     * @param int $name
     */
    public function setName($name) { $this->name=$name; }
    /**
     * @param int $textAbility
     */
    public function setTextAbility($textAbility) { $this->textAbility=$textAbility; }
    /**
     * @return array
     */
    public function getClassVars() { return get_class_vars('Equipment'); }
    /**
     * @param array $row
     * @param string $a
     * @param string $b
     * @return Spawn
     */
    public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Equipment(), self::getClassVars(), $row); }
    /**
     * @return string
     */
    public function getImgUrl($expansionId='00') {
        $urlThumb = '/wp-content/plugins/zombicide/web/rsc/images/equipments/'.(str_pad($this->id, 3, '0', STR_PAD_LEFT)).$expansionId.'-thumb.jpg';
        if ( !is_file($urlThumb) ) {
      // /homepages/42/d239730921/htdocs
            $urlOriginal = '/wp-content/plugins/zombicide/web/rsc/images/equipments/'.(str_pad($this->id, 3, '0', STR_PAD_LEFT)).$expansionId.'.png';
            if ( !is_file('http://zombicide.jhugues.fr'.$urlOriginal) ) {
            	
            	// Fix de sonarCLoud. A développer.
            	return $urlOriginal;
            }
        }
        return $urlThumb;
    }
    /**
     * @return boolean
     */
    public function isRanged() {
        if ( $this->ranged==null ) {
            if ( $this->EquipmentWeaponProfiles == null ) {
                $this->EquipmentWeaponProfiles = $this->getEquipmentWeaponProfiles();
            }
            if ( empty($this->EquipmentWeaponProfiles) ) { 
                $this->ranged = FALSE;
            } else {
                $isRanged = FALSE;
                foreach ( $this->EquipmentWeaponProfiles as $EquipmentWeaponProfile ) {
                    $WeaponProfile = $EquipmentWeaponProfile->getWeaponProfile();
                    if ( $WeaponProfile->getMaxRange()>0 ) { $isRanged = TRUE; }
                    else { $this->melee = TRUE; }
                }
                $this->ranged = $isRanged;
            }
        }
        return $this->ranged;
    }
    /**
     * @return boolean
     */
    public function isMelee() {
        if ( $this->melee==null ) {
            if ( $this->EquipmentWeaponProfiles == null ) {
                $this->EquipmentWeaponProfiles = $this->getEquipmentWeaponProfiles();
            }
            if ( empty($this->EquipmentWeaponProfiles) ) { 
                $this->melee = FALSE;
            } else {
                $isMelee = FALSE;
                foreach ( $this->EquipmentWeaponProfiles as $EquipmentWeaponProfile ) {
                    $WeaponProfile = $EquipmentWeaponProfile->getWeaponProfile();
                    if ( $WeaponProfile->getMaxRange()==0 ) { $isMelee = TRUE; }
                    else { $this->ranged = TRUE; }
                }
                $this->melee = $isMelee;
            }
        }
        return $this->melee;
    }
    /**
     * @return boolean
     */
    public function isPimp() { return $this->hasKeyword('Pimp'); }
    /**
     * @return boolean
     */
    public function isStarter() { return $this->hasKeyword('Starter'); }
    /**
     * @return boolean
     */
    public function isDual() { return $this->hasKeyword('Dual'); }
    /**
     * @param string $keyword
     * @return boolean
     */
    public function hasKeyword($keyword) {
        $hasKeyword = FALSE;
        if ( $this->Keywords == null ) { $this->initKeywords(); }
        if ( !empty($this->Keywords) ) {
            foreach ( $this->Keywords as $Keyword ) {
                if ( $Keyword->getName()==$keyword ) { $hasKeyword = TRUE; }
            }
        }
        return $hasKeyword;
    }
    private function initKeywords() {
        $EquipmentKeywords = $this->getEquipmentKeywords();
        $this->Keywords = array();
        if ( !empty($EquipmentKeywords) ) {
            foreach ( $EquipmentKeywords as $EquipmentKeyword ) {
                array_push($this->Keywords, $EquipmentKeyword->getKeyword());
            }
        }
    }
}
?>