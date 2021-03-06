<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe ExpansionServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ExpansionServices extends LocalServices
{
  /**
   * L'objet Dao pour faire les requêtes
   * @var ExpansionDaoImpl $Dao
   */
  protected $Dao;
  /**
   * Class constructor
   */
  public function __construct()
  {
    parent::__construct();
    $this->Dao = new ExpansionDaoImpl();
  }

  private function buildFilters($arrFilters)
  {
    $arrParams = array();
    array_push($arrParams, (isset($arrFilters['code']) && !is_array($arrFilters['code'])) ? $arrFilters['code'] : '%');
    $bTest = isset($arrFilters[self::CST_NBMISSIONS]) && !is_array($arrFilters[self::CST_NBMISSIONS]);
    array_push($arrParams, $bTest? $arrFilters[self::CST_NBMISSIONS] : '0');
    return $arrParams;
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $arrFilters
   * @param string $orderby
   * @param string $order
   * @return array
   */
  public function getExpansionsWithFilters($file, $line, $arrFilters=array(), $orderby='name', $order='asc')
  {
    $arrParams = $this->buildOrderAndLimit($orderby, $order);
    $arrParams[SQL_PARAMS_WHERE] = $this->buildFilters($arrFilters);
    return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
  }
  /**
   * @param string $file
   * @param string $line
   * @param string $value
   * @param string $prefix
   * @param string $classe
   * @param string $multiple
   * @param string $defaultLabel
   * @return string
   */
  public function getExpansionsSelect($file, $line, $value, $prefix, $classe, $multiple, $defaultLabel)
  {
    $Expansions = $this->getExpansionsWithFilters($file, $line);
    $this->labelDefault = $defaultLabel;
    $this->classe = $classe;
    $this->multiple = $multiple;
    return $this->getExpansionsSelectAlreadyRequested($file, $line, $Expansions, $value, $prefix);
  }
  /**
   * @param string $file
   * @param string $line
   * @param array $Expansions
   * @param string $value
   * @param string $prefix
   * @return string
   */
  public function getExpansionsSelectAlreadyRequested($file, $line, $Expansions, $value, $prefix)
  {
    $arrSetLabels = array();
    foreach ($Expansions as $Expansion) {
      $arrSetLabels[$Expansion->getId()] = $Expansion->getName();
    }
    return $this->getSetSelect($file, $line, $arrSetLabels, $prefix, $value);
  }
  
  
  
  
  /**
   * Met à jour les donnée de la table Expansion... Obsolète pour l'heure
   *
  public function cleanAndUpdateExpansionData() {
    $requete  = 'UPDATE wp_3_z_expansion SET nbSurvivants = 0, nbDalles = 0, nbEquipmentCards=0, nbInvasionCards=0;';
    $requete .= 'UPDATE wp_3_z_expansion AS e1 ';
    $requete .= 'INNER JOIN (SELECT COUNT(s.id) AS nbSurvivants, e2.id AS e2Id FROM wp_3_z_survivor AS s ';
    $requete .= 'INNER JOIN wp_3_z_expansion AS e2 ON e2.id=s.expansionId GROUP BY s.expansionId) AS t2 ';
    $requete .= 'SET e1.nbSurvivants = t2.nbSurvivants ';
    $requete .= 'WHERE e1.id=t2.e2Id;';
    $requete .= 'UPDATE wp_3_z_expansion AS e1 ';
    $requete .= 'INNER JOIN (SELECT COUNT(t.id) AS nbDalles, e2.id AS e2Id FROM wp_3_z_tile AS t ';
    $requete .= 'INNER JOIN wp_3_z_expansion AS e2 ON e2.id=t.expansionId GROUP BY t.expansionId) AS t2 ';
    $requete .= 'SET e1.nbDalles = t2.nbDalles ';
    $requete .= 'WHERE e1.id=t2.e2Id;';
    $requete .= 'UPDATE wp_3_z_expansion AS e1 ';
    $requete .= 'INNER JOIN (SELECT COUNT(ee.id) AS nbEquipmentCards, e2.id AS e2Id FROM wp_3_z_equipment_expansion AS ee ';
    $requete .= 'INNER JOIN wp_3_z_expansion AS e2 ON e2.id=ee.expansionId GROUP BY ee.expansionId) AS t2 ';
    $requete .= 'SET e1.nbEquipmentCards = t2.nbEquipmentCards ';
    $requete .= 'WHERE e1.id=t2.e2Id;';
    $requete .= 'UPDATE wp_3_z_expansion AS e1 ';
    $requete .= 'INNER JOIN (SELECT COUNT(i.id) AS nbInvasionCards, e2.id AS e2Id FROM wp_3_z_invasion AS i ';
    $requete .= 'INNER JOIN wp_3_z_expansion AS e2 ON e2.id=i.expansionId GROUP BY i.expansionId) AS t2 ';
    $requete .= 'SET e1.nbInvasionCards = t2.nbInvasionCards ';
    $requete .= 'WHERE e1.id=t2.e2Id;';
  }
  */
}
