<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe Objective
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Objective extends LocalDomain {
	/**
	 * Id technique de la donnée
	 * @var int $id
	 */
	protected $id;
	/**
	 * Code de l'Objectif
	 * @var string $code
	 */
	protected $code;
	/**
	 * Description de l'Objectif
	 * @var string $description
	 */
	protected $description;
	/**
	 * @param array $attributes
	 */
	public function __construct($attributes=array()) {
		parent::__construct($attributes);
	}
	/**
	 * @return int
	 */
	public function getId() {return $this->id; }
	/**
	 * @return string
	 */
	public function getCode() { return $this->code; }
	/**
	 * @return string
	 */
	public function getDescription() { return $this->description; }
	/**
	 * @param int $id
	 */
	public function setId($id) { $this->id=$id; }
	/**
	 * @param string $code
	 */
	public function setCode($code) { $this->code=$code; }
	/**
	 * @param string $description
	 */
	public function setDescription($description) { $this->description=$description; }
	/**
	 * @return array
	 */
	public function getClassVars() { return get_class_vars('Objective'); }
	/**
	 * @param array $row
	 * @param string $a
	 * @param string $b
	 * @return Objective
	 */
	public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Objective(), self::getClassVars(), $row); }

}
?>