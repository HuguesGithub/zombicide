<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe SpawnLiveDeckDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SpawnLiveDeckDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, liveDeckId, spawnCardId, rank, status ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_spawnlivedeck ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE liveDeckId LIKE '%s' AND spawnCardId LIKE '%s' AND status LIKE '%s' ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_spawnlivedeck (liveDeckId, spawnCardId, rank, status) VALUES ('%s', '%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_spawnlivedeck SET liveDeckId='%s', spawnCardId='%s', rank='%s', status='%s' ";

	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('SpawnLiveDeck', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|SpawnLiveDeck
	 */
	public function select($file, $line, $arrParams) {
		$Objs = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Objs) ? new SpawnLiveDeck() : array_shift($Objs));
	}
  
}
?>