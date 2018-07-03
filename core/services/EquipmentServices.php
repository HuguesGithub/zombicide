<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var EquipmentDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class Constructor
   */
  public function __construct()
  {  $this->Dao = new EquipmentDaoImpl(); }

  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getEquipmentsWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }

  
}
?>