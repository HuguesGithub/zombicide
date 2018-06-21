<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe LiveTokenDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class LiveTokenDaoImpl extends LocalDaoImpl {
    /**
     * Corps de la requête de sélection
     * @var string $selectRequest
     */
    protected $selectRequest = "SELECT id, liveId, tokenId, coordX, coordY, color, status, orientation ";
    /**
     * Table concernée
     * @var string $fromRequest
     */
    protected $fromRequest = "FROM wp_11_zombicide_live_token ";
    /**
     * Recherche avec filtres
     * @var string $whereFilters
     */
    protected $whereFilters = "WHERE liveId LIKE '%s' AND tokenId LIKE '%s' ";
    /**
     * Requête d'insertion en base
     * @var string $insert
     */
    protected $insert = "INSERT INTO wp_11_zombicide_live_token (liveId, tokenId, coordX, coordY, color, status, orientation) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s');";
    /**
     * Requête de mise à jour en base
     * @var string $update
     */
    protected $update = "UPDATE wp_11_zombicide_live_token SET liveId='%s', tokenId='%s', coordX='%s', coordY='%s', color='%s', status='%s', orientation='%s' ";

    public function __construct() {}
    /**
     * @param array $rows
     * @return array
     */
    protected function convertToArray($rows) { return $this->globalConvertToArray('LiveToken', $rows); }
    /**
     * @param string $file
     * @param int $line
     * @param array $arrParams
     * @return array|LiveToken
     */
    public function select($file, $line, $arrParams) {
        $Objs = $this->selectEntry($file, $line, $arrParams);
        return ( empty($Objs) ? new LiveToken() : array_shift($Objs));
    }
  
}
?>