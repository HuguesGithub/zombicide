<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Equipment
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Equipment extends LocalDomain
{
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
  public function __construct($attributes=array())
  {
    $services = array('EquipmentKeyword', 'EquipmentWeaponProfile');
      parent::__construct($attributes, $services);
  }
  /**
   * @return $id
   */
  public function getId()
  {return $this->id; }
  /**
   * @return $name
   */
  public function getName()
  { return $this->name; }
  /**
   * @return $textAbility
   */
  public function getTextAbility()
  { return $this->textAblity; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param int $name
   */
  public function setName($name)
  { $this->name=$name; }
  /**
   * @param int $textAbility
   */
  public function setTextAbility($textAbility)
  { $this->textAbility=$textAbility; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('Equipment'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return Spawn
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Equipment(), self::getClassVars(), $row); }
  public function getExpansionId()
  { return $this->expansionId; }
  /**
   * @param $expansionId
   * @return string
   */
  public function getImgUrl($expansionId='00')
  {
    $uniqueId = (str_pad($this->id, 3, '0', STR_PAD_LEFT)).(str_pad($expansionId, 2, '0', STR_PAD_LEFT));
    $urlThumb = '/wp-content/plugins/zombicide/web/rsc/images/equipments/'.$uniqueId.'-thumb.jpg';
    // Si l'image Thumb n'existe pas, on va la créer à partir de l'original. Puis on supprime l'original.
    if (!is_file(getcwd().$urlThumb)) {
      $urlOriginal = '/wp-content/plugins/zombicide/web/rsc/images/equipments/'.$uniqueId.'.png';
      $adminUrl = getcwd().$urlOriginal;
      $src = imagecreatefrompng($adminUrl);
      $dst = imagecreatetruecolor(320, 440);
      imagecopyresized($dst, $src, 0, 0, 0, 0, 320, 440, 597, 822);
      imagejpeg($dst, getcwd().$urlThumb);
      unlink($adminUrl);
    }
    return $urlThumb;
  }
  public function getNiceName()
  {
    $cleanDigits = array(' ', '#', '-', '!', 'à', 'é', "'", '(', ')', 'ê', 'ç', '&', '.', 'è');
    return str_replace($cleanDigits, '', strtolower($this->getName()));
  }
  /**
   * @return boolean
   */
  public function isRanged()
  {
    if ($this->ranged == null) {
      if ($this->EquipmentWeaponProfiles == null) {
        $this->EquipmentWeaponProfiles = $this->getEquipmentWeaponProfiles(__FILE__, __LINE__);
      }
      if (empty($this->EquipmentWeaponProfiles)) {
        $this->ranged = false;
      } else {
        $isRanged = false;
        foreach ($this->EquipmentWeaponProfiles as $EquipmentWeaponProfile) {
          $WeaponProfile = $EquipmentWeaponProfile->getWeaponProfile(__FILE__, __LINE__);
          if ($WeaponProfile->getMaxRange()>0) {
            $isRanged = true;
          } else {
            $this->melee = true;
          }
        }
        $this->ranged = $isRanged;
      }
    }
    return $this->ranged;
  }
  /**
   * @return boolean
   */
  public function isMelee()
  {
    if ($this->melee==null) {
      if ($this->EquipmentWeaponProfiles == null) {
        $this->EquipmentWeaponProfiles = $this->getEquipmentWeaponProfiles(__FILE__, __LINE__);
      }
      if (empty($this->EquipmentWeaponProfiles)) {
        $this->melee = false;
      } else {
        $isMelee = false;
        foreach ($this->EquipmentWeaponProfiles as $EquipmentWeaponProfile) {
          $WeaponProfile = $EquipmentWeaponProfile->getWeaponProfile(__FILE__, __LINE__);
          if ($WeaponProfile->getMaxRange()==0) {
            $isMelee = true;
          } else {
            $this->ranged = true;
          }
        }
        $this->melee = $isMelee;
      }
    }
    return $this->melee;
  }
  /**
   * @return boolean
   */
  public function isPimp()
  { return $this->hasKeyword('Pimp'); }
  /**
   * @return boolean
   */
  public function isStarter()
  { return $this->hasKeyword('Starter'); }
  /**
   * @return boolean
   */
  public function isDual()
  { return $this->hasKeyword('Dual'); }
  /**
   * @param string $keyword
   * @return boolean
   */
  public function hasKeyword($keyword)
  {
    $hasKeyword = false;
    if ($this->Keywords == null) {
      $this->initKeywords();
    }
    if (!empty($this->Keywords)) {
      foreach ($this->Keywords as $Keyword) {
        if ($Keyword->getName()==$keyword) {
          $hasKeyword = true;
        }
      }
    }
    return $hasKeyword;
  }
  private function initKeywords()
  {
    if ($this->Keywords==null) {
      $EquipmentKeywords = $this->getEquipmentKeywords(__FILE__, __LINE__);
      $ownKeyWords = array();
      if (!empty($EquipmentKeywords)) {
        foreach ($EquipmentKeywords as $EquipmentKeyword) {
          array_push($ownKeyWords, $EquipmentKeyword->getKeyword(__FILE__, __LINE__));
        }
      }
      $this->Keywords = empty($ownKeyWords) ? array(new Equipment()) : $ownKeyWords;
    }
  }
  public function setExpansionId($expansionId)
  { $this->expansionId = $expansionId; }
}
