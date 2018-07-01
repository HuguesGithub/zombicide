<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe EquipmentsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class EquipmentsPageBean extends PagePageBean
{
  /**
   * Class Constructor
   * @param WpPage $WpPage
   */
  public function __construct($WpPage='')
  {
    $services = array('Equipment', 'EquipmentExpansion', 'Expansion');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param WpPage $WpPage
   * @return string
   */
  public function getStaticEquipmentsContent($WpPage)
  {
    $Bean = new EquipmentsPageBean($WpPage);
    return $Bean->getEquipmentsContent();
  }
  /**
   * Retourne la page des cartes Equipement
   * @return string
   */
  public function getEquipmentsContent()
  {
    // On récupère toutes les extensions
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), self::CST_DISPLAYRANK);
    $strFilters = '';
    $strEquipments = '';
    $EquipmentCardsToDisplay = array();
    if (!empty($Expansions)) {
    // Pour chaque extension, on vérifie qu'il y a au moins une carte Equipement rattachée.
      foreach ($Expansions as $Expansion) {
        $id = $Expansion->getId();
        $arrFilters = array(self::CST_EXPANSIONID=>$id);
        $EquipmentExpansions = $this->EquipmentExpansionServices->getEquipmentExpansionsWithFilters(__FILE__, __LINE__, $arrFilters);
    // Si on n'a pas de carte Equipement rattachée, on n'a pas besoin d'afficher cette extension.
        if (empty($EquipmentExpansions)) {
          continue;
        }
    // On peut ajouter l'Extension au menu pour filtrer.
        $strFilters .= '<option value="set-'.$id.'">'.$Expansion->getName().'</option>';
    // On récupère l'ensemble des cartes de l'extension.
        foreach ($EquipmentExpansions as $EquipmentExpansion) {
          $EquipmentCard = $this->EquipmentServices->select(__FILE__, __LINE__, $EquipmentExpansion->getEquipmentCardId());
          $niceName = $EquipmentCard->getNiceName().'-'.$EquipmentCard->getId().'-'.$id;
          $EquipmentCard->setExpansionId($id);
      // Par contre, vu qu'on s'appuye sur la tablede jointure, on ne peut pas directement trier les cartes.
      // On les stocke donc temporairement.
          $EquipmentCardsToDisplay[$niceName] = $EquipmentCard;
        }
      }
    }
  // On peut les afficher une à une, juste après les avoir triées.
    if (!empty($EquipmentCardsToDisplay)) {
      // On trie les cartes selon leur nom.
      ksort($EquipmentCardsToDisplay);
      foreach ($EquipmentCardsToDisplay as $name => $EquipmentCard) {
        list(, , $id) = explode('-', $name);
        $EquipmentBean = new EquipmentBean($EquipmentCard);
        $strEquipments .= $EquipmentBean->displayCard();
      }
    }
    // On construit la liste déroulante pour les filtres "mots-clés" (mais pas que).
    $arr = array('weapon'=>'Armes', 'melee'=>'Armes de Mêlée', 'ranged'=>'Armes A distance', 'pimp'=>'Armes Pimp',
                  'dual'=>'Armes Dual', 'starter'=>'Armes de départ');
    $strCategories  = '';
    foreach ($arr as $key => $value) {
      $strCategories .= '<option value="'.$key.'">'.$value.'</option>';
    }
    $args = array(
      $strFilters,
      $strEquipments,
      $strCategories,
      '','','','',
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-equipmentcards.php');
    return vsprintf($str, $args);
  }
}
