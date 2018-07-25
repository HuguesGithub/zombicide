<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentExpansion
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentExpansion extends LocalDomain
{
  /**
   * Id technique de la jointure
   * @var int $id
   */
  protected $id;
  /**
   * Id technique de la carte Equipement
   * @var int $equipmentCardId
   */
  protected $equipmentCardId;
  /**
   * Id technique de l'Expansion
   * @var int $expansionId
   */
  protected $expansionId;
  /**
   * Nombre de cartes dans l'extension
   * @var int $quantity
   */
  protected $quantity;
  /**
   * @param array $attributes
   */
  public function __construct($attributes=array())
  {
    parent::__construct($attributes);
    $this->EquipmentServices          = new EquipmentServices();
  }
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @ return int
   */
  public function getEquipmentCardId()
  { return $this->equipmentCardId; }
  /**
   * @ return int
   */
  public function getExpansionId()
  { return $this->expansionId; }
  /**
   * @ return int
   */
  public function getQuantity()
  { return $this->quantity; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id=$id; }
  /**
   * @param int $equipmentCardId
   */
  public function setEquipmentCardId($equipmentCardId)
  { $this->equipmentCardId = $equipmentCardId; }
  /**
   * @param int $expansionId
   */
  public function setExpansionId($expansionId)
  { $this->expansionId = $expansionId; }
  /**
   * @param int $quantity
   */
  public function setQuantity($quantity)
  { $this->quantity = $quantity; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('EquipmentExpansion'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return EquipmentExpansion
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new EquipmentExpansion(), self::getClassVars(), $row); }
  public function setEquipment($Equipment)
  { $this->Equipment = $Equipment; }
  
  /**
   * @return Equipment
   */
  public function getEquipment()
  {
    if ($this->Equipment == null) {
      $this->Equipment = $this->EquipmentServices->select(__FILE__, __LINE__, $this->equipmentCardId);
    }
    return $this->Equipment;
  }
  
  public static function getFromStartingSkill($Skill)
  {
    $EquipmentExpansions = array();
    $EquipmentExpansionServices = new EquipmentExpansionServices();
    switch ($Skill->getCode()) {
      case 'STARTS_WITH_PISTOL' :
        $args = array('equipmentCardId'=>24, 'expansionId'=>23);
        $EEs = $EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, $args);
        $EquipmentExpansion = array_shift($EEs);
        $EquipmentExpansion->setQuantity(1);
        array_push($EquipmentExpansions, $EquipmentExpansion);
      break;
        /*
      case 'STARTS_WITH_2_KUKRIS' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>2);
      break;
      case 'STARTS_WITH_CHAINSAW' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_MAGNUM' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_2_MACHETES' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_BASEBALL_BAT' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_FLASHLIGHT' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_FLAMETHROWER' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_SAWED_OFF' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_KATANA' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_SUBMG' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_SHOTGUN' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_2_MOLOTOV' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>2);
      break;
      case 'STARTS_WITH_RIFLE' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_ARBALETE' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>1);
      break;
      case 'STARTS_WITH_2_KATANAS' :
        $args = array('equipmentCardId'=>, 'expansionId'=>, 'quantity'=>2);
      break;
      */
    }
    return $EquipmentExpansions;
  }
}
