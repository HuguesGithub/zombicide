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

}
