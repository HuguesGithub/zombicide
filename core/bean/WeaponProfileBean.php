<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WeaponProfileBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WeaponProfileBean extends MainPageBean
{
  /**
   * Class Constructor
   * @param WeaponProfile $WeaponProfile
   */
  public function __construct($WeaponProfile='')
  {
    parent::__construct();
    $this->EquipmentWeaponProfileServices = FactoryServices::getEquipmentWeaponProfileServices();
    $this->WeaponProfile = ($WeaponProfile=='' ? new WeaponProfile() : $WeaponProfile);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $WeaponProfile = $this->WeaponProfile;
    $EquipmentWeaponProfiles = $this->EquipmentWeaponProfileServices->getEquipmentWeaponProfilesWithFilters(__FILE__, __LINE__, array('weaponProfileId'=>$WeaponProfile->getId()));
    $nb = count($EquipmentWeaponProfiles);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'weaponprofile',
      'id'=>$WeaponProfile->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Equipement'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$WeaponProfile->getId().'</td><td>'.$WeaponProfile->getMinRange().'</td>';
    $tBody .= '<td>'.$WeaponProfile->getMaxRange().'</td><td>'.$WeaponProfile->getNbDice().'</td>';
    $tBody .= '<td>'.$WeaponProfile->getSuccessRate().'</td><td>'.$WeaponProfile->getDamageLevel().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
