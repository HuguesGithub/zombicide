<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WeaponProfileBean
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.00
 */
class WeaponProfileBean extends LocalBean
{
  /**
   * Class Constructor
   * @param WeaponProfile $WeaponProfile
   */
  public function __construct($WeaponProfile='')
  {
    parent::__construct();
    $this->WeaponProfile = ($WeaponProfile=='' ? new WeaponProfile() : $WeaponProfile);
    $this->EquipmentWeaponProfileServices = new EquipmentWeaponProfileServices();
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $WeaponProfile = $this->WeaponProfile;
    $arrF = array('weaponProfileId'=>$WeaponProfile->getId());
    $EquipmentWeaponProfiles = $this->EquipmentWeaponProfileServices->getEquipmentWeaponProfilesWithFilters(__FILE__, __LINE__, $arrF);
    $nb = count($EquipmentWeaponProfiles);
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'weaponprofile',
      'id'=>$WeaponProfile->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = self::CST_TRASH;
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      $nb.' Equipement'.($nb>1?'s':''),
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$WeaponProfile->getId().self::CST_TD_SEP.$WeaponProfile->getMinRange();
    $tBody .= self::CST_TD_SEP.$WeaponProfile->getMaxRange().self::CST_TD_SEP.$WeaponProfile->getNbDice();
    $tBody .= self::CST_TD_SEP.$WeaponProfile->getSuccessRate().self::CST_TD_SEP.$WeaponProfile->getDamageLevel().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
