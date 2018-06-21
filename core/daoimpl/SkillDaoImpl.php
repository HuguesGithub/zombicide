<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe SkillDaoImpl
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class SkillDaoImpl extends LocalDaoImpl {
	/**
	 * Corps de la requête de sélection
	 * @var string $selectRequest
	 */
	protected $selectRequest = "SELECT id, code, name, description ";
	/**
	 * Table concernée
	 * @var string $fromRequest
	 */
	protected $fromRequest = "FROM wp_11_zombicide_skill ";
	/**
	 * Recherche avec filtres
	 * @var string $whereFilters
	 */
	protected $whereFilters = "WHERE code LIKE '%s' AND name LIKE '%s' AND description LIKE '%s' ";
	/**
	 * Requête d'insertion en base
	 * @var string $insert
	 */
	protected $insert = "INSERT INTO wp_11_zombicide_skill (code, name, description) VALUES ('%s', '%s', '%s');";
	/**
	 * Requête de mise à jour en base
	 * @var string $update
	 */
	protected $update = "UPDATE wp_11_zombicide_skill SET code='%s', name='%s', description='%s' ";

	public function __construct() {}
	/**
	 * @param array $rows
	 * @return array
	 */
	protected function convertToArray($rows) { return $this->globalConvertToArray('Skill', $rows); }
	/**
	 * @param string $file
	 * @param int $line
	 * @param array $arrParams
	 * @return array|Skill
	 */
	public function select($file, $line, $arrParams) {
		$Skills = $this->selectEntry($file, $line, $arrParams);
		return ( empty($Skills) ? new Skill() : array_shift($Skills));
	}
  
}
?>