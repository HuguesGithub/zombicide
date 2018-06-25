<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe EquipmentWeaponProfileDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentWeaponProfileDaoImpl extends LocalDaoImpl {
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, equipmentCardId, weaponProfileId, noisy ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_equipment_weaponprofile ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE equipmentCardId LIKE '%s' AND weaponProfileId LIKE '%s' ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_equipment_weaponprofile (equipmentCardId, weaponProfileId, noisy) VALUES ('%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_equipment_weaponprofile SET equipmentCardId='%s', weaponProfileId='%s', noisy='%s' ";
  
  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows) { return $this->globalConvertToArray('EquipmentWeaponProfile', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|EquipmentWeaponProfile
   */
  public function select($file, $line, $arrParams) {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new EquipmentWeaponProfile() : array_shift($Objs));
  }
  
}
?>