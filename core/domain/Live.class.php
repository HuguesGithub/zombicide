<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe Live
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class Live extends LocalDomain
{
  /**
   * Id technique de la donnée
   * @var int $id
   */
  protected $id;
  /**
   * Code alphanumérique
   * @var string $deckKey
   */
  protected $deckKey;
  /**
   * Date de dernière mise à jour
   * @var datetime $dateUpdate
   */
  protected $dateUpdate;
  /**
   * @return int
   */
  public function getId()
  { return $this->id; }
  /**
   * @return string
   */
  public function getDeckKey()
  { return $this->deckKey; }
  /**
   * @return string
   */
  public function getDateUpdate()
  { return $this->dateUpdate; }
  /**
   * @param int $id
   */
  public function setId($id)
  { $this->id = $id; }
  /**
   * @param string $deckKey
   */
  public function setDeckKey($deckKey)
  { $this->deckKey = $deckKey; }
  /**
   * @param string $dateUpdate
   */
  public function setDateUpdate($dateUpdate)
  { $this->dateUpdate = $dateUpdate; }
  /**
   * @return array
   */
  public function getClassVars()
  { return get_class_vars('Live'); }
  /**
   * @param array $row
   * @param string $a
   * @param string $b
   * @return LiveDeck
   */
  public static function convertElement($row, $a='', $b='')
  { return parent::convertElement(new Live(), self::getClassVars(), $row); }
}
