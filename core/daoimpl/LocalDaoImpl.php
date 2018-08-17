<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
define('SQL_PARAMS_WHERE', 'where');
define('SQL_PARAMS_ORDERBY', '__orderby__');
/**
 * Classe LocalDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LocalDaoImpl extends GlobalDaoImpl implements ConstantsInterface
{
  /**
   * Recherche unitaire
   * @var string $whereId
   */
  protected $whereId = "WHERE id='%s' ";
  /**
   * Règle de tri
   * @var string $orderBy
   */
  protected $orderBy = SQL_PARAMS_ORDERBY;
  /**
   * Requête de suppression en base
   * @var string $delete
   */
  protected $delete = "DELETE ";
  /**
   * Requête de sélection en base
   * @var string $selectRequest
   */
  protected $selectRequest;
  /**
   * Table concernée
   * @var string $fromRequest
   */
  protected $fromRequest;
  /**
   * Requête de recherche en base avec Filtres
   * @var string $whereFilters
   */
  protected $whereFilters;
  /**
   * Requête d'insertion en base
   * @var string $insert
   */
  protected $insert;
  /**
   * Requête d'update en base
   * @var string $update
   */
  protected $update;
  /**
   * Class Constructor
   * @param string $strDao
   */
  public function __construct($strDao='')
  {
    $urlIni = '/wp-content/plugins/zombicide/core/config/requests.php';
    $strGetCwd = getcwd();
    if (strpos($strGetCwd, 'wp-admin')!==false) {
      $strGetCwd = substr($strGetCwd, 0, -9);
    }
    $adminUrl = $strGetCwd.$urlIni;
    $arrConfigs = parse_ini_file($adminUrl, true);
    $this->selectRequest = $arrConfigs[$strDao]['select'];
    $this->fromRequest = $arrConfigs[$strDao]['from'];
    $this->whereFilters = isset($arrConfigs[$strDao][SQL_PARAMS_WHERE]) ? $arrConfigs[$strDao][SQL_PARAMS_WHERE] : "WHERE 1=1 ";
    $this->insert = $arrConfigs[$strDao]['insert'];
    $this->update = $arrConfigs[$strDao]['update'];
  }
  /**
   * @param string $file
   * @param int $line
   * @param array $arrParams
   * @param Obj $Obj
   * @return array|Obj
   */
  public function localSelect($file, $line, $arrParams, $Obj)
  {
    $Objs = $this->selectEntry($file, $line, $arrParams);
    return (empty($Objs) ? $Obj : array_shift($Objs));
  }
}
