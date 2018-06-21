<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe MissionTokenServices
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class MissionTokenServices extends LocalServices {
    /**
     * L'objet Dao pour faire les requêtes
     * @var DurationDaoImpl $Dao
     */
    protected $Dao;
    
    public function __construct() { $this->Dao = new MissionTokenDaoImpl(); }

    private function buildFilters($arrFilters) {
        $arrParams = array();
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
    public function getMissionTokensWithFilters($file, $line, $arrFilters=array(), $orderby='id', $order='asc') {
        $arrParams = $this->buildOrderAndLimit($orderby, $order);
        $arrParams[_SQL_PARAMS_WHERE_] = $this->buildFilters($arrFilters);
        return $this->Dao->selectEntriesWithFilters($file, $line, $arrParams);
    }
    
}
?>