<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WeaponProfileDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WeaponProfileDaoImpl extends LocalDaoImpl
{
  /**
   * Corps de la requête de sélection
   * @var string $selectRequest
   */
  protected $selectRequest = "SELECT id, minRange, maxRange, nbDice, successRate, damageLevel ";
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest = "FROM wp_11_zombicide_weaponprofile ";
  /**
   * Recherche avec filtres
   * @var string $whereFilters
   */
  protected $whereFilters = "WHERE 1=1 ";
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert = "INSERT INTO wp_11_zombicide_weaponprofile (minRange, maxRange, nbDice, successRate, damageLevel) VALUES ('%s', '%s', '%s', '%s', '%s');";
  /**
   * Requête de mise à jour en base
   * @var string $update
   */
  protected $update = "UPDATE wp_11_zombicide_weaponprofile SET minRange='%s', maxRange='%s', nbDice='%s', successRate='%s', damageLevel='%s' ";

  public function __construct() {}
  /**
   * @param array $rows
   * @return array
   */
  protected function convertToArray($rows)
  { return $this->globalConvertToArray('WeaponProfile', $rows); }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @return array|WeaponProfile
   */
  public function select($file, $line, $arrParams)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? new WeaponProfile() : array_shift($Objs));
  }
  
}
