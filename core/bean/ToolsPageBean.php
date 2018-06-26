<?php
if (!defined('ABSPATH') ) { die('Forbidden' ); }
/**
 * Classe ToolsPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class ToolsPageBean extends PagePageBean {

  public function __construct($WpPage='') {
    $services = array('Equipment', 'EquipmentExpansion', 'Expansion', 'Spawn', 'SpawnLiveDeck', 'Survivor');
    parent::__construct($WpPage, $services);
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPisteContent($WpPage) {
    $Bean = new ToolsPageBean($WpPage);
    return $Bean->getPisteContent();
  }
  /**
   * @return string
   */
  public function getPisteContent() {
  // Nombre de dés à lancer
    $nbDeDes = $this->initVar('nbDeDes');
  // Seuil de réussite
    $seuilReussite = $this->initVar('seuilReussite', 6);
  // Bénéficie de la compétence "Sur un 6 : +1 dé" ?
    $hasSurUn6 = ($this->initVar('surUn6')==1);
  // Bénéficie de la compétence "+1 au résultat du dé" ?
    $hasPlusUnAuDe = ($this->initVar('plusUnAuDe')==1);
  
    if (is_numeric($nbDeDes) ) {
      $str = 'Résultat de ce lancer <strong>'.$nbDeDes.'D</strong> à <strong>'.$seuilReussite.'+</strong> : ';
      $strJets = '';
      for ($i=1; $i<=$nbDeDes; $i++ ) {
        $score = rand(1, 6);
        if ($score == 6 || ($score == 5 && $hasPlusUnAuDe) ) {
          $strClasse = "primary";
          if ($hasSurUn6 ) { $nbDeDes++; }
        } elseif ($score==1 ) {
          $strClasse = "danger";
        } elseif ($score >= ($seuilReussite - ($hasPlusUnAuDe ? 1 : 0)) ) {
          $strClasse = "info";
        } else {
          $strClasse = "warning";
        }
        $strBadge = '<span class="badge badge-'.$strClasse.'">'.$score.'</span>';
        $strJets .= $strBadge.' ';
      }
      $str .= $strJets.'<br><br>';
    }
    $str .= 'Saisir un nombre de dés et les lancer.';
    $args = array(
      $str
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-piste-de-des.php' );
    return vsprintf($str, $args);
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */  
  public function getStaticSurvivorsContent($WpPage) {
    $Bean = new ToolsPageBean($WpPage);
    return $Bean->getSurvivorsContent();
  }
  /**
   * @return string
   */
  public function getSurvivorsContent() {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), array(self::CST_DISPLAYRANK), array('ASC'));
    if (!empty($Expansions) ) {
      $str .= '<div class="btn-group-vertical team-selection" role="group">';
      $str .= '<div class="btn-toolbar" role="toolbar">';
      $str .= '  <div class="btn-group" id="nbSurvSel" role="group">';
      for ($i=1; $i<=6; $i++ ) {
        $str .= '  <button type="button" class="btn btn-dark'.($i==6?' active':'').'" data-nb="'.$i.'">'.$i.'</button>';
      }
      $str .= '  </div>';
      $str .= '</div>';    
      foreach ($Expansions as $Expansion ) {
        $id = $Expansion->getId();
        $Survivors = $this->SurvivorServices->getSurvivorsWithFilters(__FILE__, __LINE__, array(self::CST_EXPANSIONID=>$id), 'name', 'ASC');
        if (empty($Survivors) ) { continue; }
        $str .= '<div type="button" class="btn btn-dark btn-expansion" data-expansion-id="'.$id.'"><span><i class="far fa-square"></i></span> '.$Expansion->getName().'</div>';
        foreach ($Survivors as $Survivor ) {
          $survivorId = $Survivor->getId();
          $str .= '<button type="button" class="btn btn-secondary btn-survivor hidden" data-expansion-id="'.$id.'" data-survivor-id="'.$survivorId.'"><i class="far fa-square"></i> '.$Survivor->getName().'</button>';
        }
      }
      $str .= '<div type="button" class="btn btn-primary btn-expansion" id="proceedBuildTeam"><span><i class="far fa-check-circle"></i></span> Générer</div>';
      $str .= '</div>';
    }
    $args = array(
      $str
    );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-selection-survivors.php' );
    return vsprintf($str, $args);
  }
}
?>
