<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * OnlineActions
 * @since 1.0.00
 * @author Hugues
 */
class OnlineActions extends LocalActions
{
  /**
   * Constructeur
   */
  public function __construct($post)
  {
    $this->LiveMissionServices  = new LiveMissionServices();
    $this->LiveSurvivorServices = new LiveSurvivorServices();
  }
  /**
   * Point d'entrée des méthodes statiques.
   * @param array $post
   * @return string
   **/
  public static function dealWithStatic($post)
  {
    $Act = new OnlineActions($post);
    switch ($post['ajaxChildAction']) {
      case 'startSurvivorTurn' :
        $returned = $Act->startSurvivorTurn($post);
      break;
      default :
      break;
    }
    return $returned;
  }
  
  public function startSurvivorTurn($post)
  {
    // Pour qu'un Survivant puisse démarrer son tour, il faut :
    // Vérifier que ce LiveSurvivor peut être actif.
    // Finir le tour du LiveSurvivor actif, s'il y en a un. Et marqué que celui-ci a déjé joué ce tour
    // Purger la table des Actions du précédent LiveSurvivor actif
    // Créer les Actions du nouveau LiveSurvivor actif et les représentées autant que possible.
    // Retourner une toolbar agrémentée des boutons qui vont bien selon le LiveSurvivor actif.
    // Et sans doute d'autres choses...
    
    // Le LiveSurvivor qu'on veut voir devenir actif
    $LiveSurvivor = $this->LiveSurvivorServices->select(__FILE__, __LINE__, $post['liveSurvivorId']);
    if ($LiveSurvivor->hasPlayedThisTurn()) {
      return '{"error-panel":'.json_encode('Ce Survivant a déjà agit ce tour. Il ne peut pas le faire de nouveau.').'}';
    }
    // La LiveMission concernée
    $LiveMission = $LiveSurvivor->getLiveMission();
    // Le LiveSurvivor actif, qui si il existe doit être passé en ayant agit ce tour.
    $ActiveLiveSurvivor = $LiveMission->getActiveLiveSurvivor();
    if ($ActiveLiveSurvivor->getId()!='') {
      $ActiveLiveSurvivor->setPlayedThisTurn(1);
      $this->LiveSurvivorServices->update(__FILE__, __LINE__, $ActiveLiveSurvivor);
      // TODO : Faudrait supprimer les Actions restantes en base.
    }
    $LiveMission->setActiveLiveSurvivorId($LiveSurvivor->getId());
    $this->LiveMissionServices->update(__FILE__, __LINE__, $LiveMission);
    // TODO : Faudrait créer les Actions disponibles pour ce tour.
    $WpPageOnlineBean = new WpPageOnlineBean();
    $Live = $LiveSurvivor->getLive();
    return '{"online-btn-actions": '.json_encode($WpPageOnlineBean->getActionButtons($Live)).'}';
  }
}
