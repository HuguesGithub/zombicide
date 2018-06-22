<?php
if ( !defined( 'ABSPATH') ) { die( 'Forbidden' ); }
/**
 * Classe Token
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Token extends LocalDomain {
    /**
     * Id technique de la donnée
     * @var int $id
     */
    protected $id;
    /**
     * Code du Token
     * @var string $code
     */
    protected $code;
    /**
     * Largeur du Token
     * @var int $width
     */
    protected $width;
    /**
     * Hauteur du Token
     * @var int $height
     */
    protected $height;
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
     * @return int
     */
    public function getWidth() { return $this->width; }
    /**
     * @return int
     */
    public function getHeight() { return $this->height; }
    /**
     * @param int $id
     */
    public function setId($id) { $this->id=$id; }
    /**
     * @param string $code
     */
    public function setCode($code) { $this->code=$code; }
    /**
     * @param int $width
     */
    public function setWidth($width) { $this->width=$width; }
    /**
     * @param int $height
     */
    public function setHeight($height) { $this->height=$height; }
    /**
     * @return array
     */
    public function getClassVars() { return get_class_vars('Token'); }
    /**
     * @param array $row
     * @param string $a
     * @param string $b
     * @return Token
     */
    public static function convertElement($row, $a='', $b='') { return parent::convertElement(new Token(), self::getClassVars(), $row); }
}
?>