<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * SpawnDeckActions
 * @author Hugues
 * @since 1.0.00
 * @version 1.0.02
 */
class SpawnDeckActions extends DeckActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->SpawnLiveDeckServices = new SpawnLiveDeckServices();
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $Act = new SpawnDeckActions($post);
    $returned = '{}';
    if ($_SESSION[self::CST_DECKKEY]==$post[self::CST_KEYACCESS]) {
      switch ($post['ajaxChildAction']) {
        case 'deleteSpawnDeck'    :
          $returned = $Act->deleteSpawnCard();
        break;
        case 'discardSpawnActive' :
          $returned = $Act->discardSpawnCard();
        break;
        case 'drawSpawnCard' :
          $returned = $Act->drawSpawnCard();
        break;
        case 'leaveSpawnDeck' :
          $returned = $Act->leaveSpawnCard();
        break;
        case 'showSpawnDiscard' :
          $returned = $Act->showDiscardSpawnCards();
        break;
        case 'shuffleSpawnDiscard' :
          $returned = $Act->shuffleSpawnCards();
        break;
        default :
          $returned = 'Valeur de ajaxChildAction non prévue : ['.$post['ajaxChildAction'].']';
        break;
      }
    } else {
      $returned = 'Session non valide.';
    }
    return $returned;
  }
  /**
   * Retourne la chaîne json mutualisée pour l'ensemble des méthodes.
   * @param int $nbCardInDeck Nombre de cartes dans la pioche
   * @param int $nbCardInDiscard Nombre de cartes dans la défausse
   * @param string $pageSelectionResult Le contenu à afficher
   * @return string
   */
  private function jsonBuild($nbCardInDeck, $nbCardInDiscard, $pageSelectionResult)
  {
    $json  = '{';
    if ($nbCardInDeck!==-1) {
      $json .= '"nbCardInDeck":'.json_encode($nbCardInDeck);
    }
    if ($nbCardInDiscard!==-1) {
      $json .= ($json!='{'?',':'').'"nbCardInDiscard":'.json_encode($nbCardInDiscard);
    }
    return $json.($json!='{'?',':'').'"page-selection-result":'.json_encode($pageSelectionResult).'}';
  }
  /**
   * @return string
   */
  public function deleteSpawnCard()
  { return parent::deleteDeckCard(); }
  /**
   * @return string
   */
  public function discardSpawnCard()
  {
    $SpawnLiveDecks = $this->getLiveDecksByStatus('A');
    // Si au moins une carte est actuellement révélée, on doit la défausser.
    $this->updateToStatus($SpawnLiveDecks, $this->SpawnLiveDeckServices, 'D');
    // Le nombre de cartes défaussées augmente, il faut le mettre à jour.
    $SpawnLiveDecks = $this->getLiveDecksByStatus('D');
    // On retourne les infos à modifier sur l'interface en mode Json.
    return $this->jsonBuild(-1, count($SpawnLiveDecks), '');
  }
  /**
   * @return string
   */
  public function drawSpawnCard()
  {
    $nbInDeck = $this->drawCardDeck($this->SpawnLiveDeckServices);
    // On récupère toutes les cartes Actives, au cas où plusieurs seraient affichées en même temps.
    $SpawnLiveDecks = $this->getLiveDecksByStatus('A');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $Bean = new WpPageLiveSpawnBean();
    $pageSelectionResult = $Bean::getStaticSpawnCardActives($SpawnLiveDecks);
    return $this->jsonBuild($nbInDeck, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function leaveSpawnCard()
  { return parent::leaveDeckCard(); }
  /**
   * @return string
   */
  public function showDiscardSpawnCards()
  {
    $SpawnLiveDecks = $this->getLiveDecksByStatus('D');
    // On retourne les infos à modifier sur l'interface en mode Json.
    $pageSelectionResult = WpPageLiveSpawnBean::getStaticSpawnCardActives($SpawnLiveDecks);
    return $this->jsonBuild(-1, -1, $pageSelectionResult);
  }
  /**
   * @return string
   */
  public function shuffleSpawnCards()
  {
    $SpawnLiveDecks = $this->getLiveDecksByStatus();
    // S'il y a au moins une carte de Spawn (si ce n'est pas le cas, on se demande ce qu'on fait ici...), on remélange ensemble
    $cpt = 0;
    if (!empty($SpawnLiveDecks)) {
      $cpt = $this->shuffleDeckCards($SpawnLiveDecks, $this->SpawnLiveDeckServices);
    }
    return $this->jsonBuild($cpt, 0, '');
  }
}
