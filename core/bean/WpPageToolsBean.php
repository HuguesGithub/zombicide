<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe WpPageToolsBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class WpPageToolsBean extends WpPageBean
{
  /**
   * Class Constructor
   */
  public function __construct($WpPage='')
  {
    parent::__construct($WpPage);
    $this->EquipmentServices          = new EquipmentServices();
    $this->EquipmentExpansionServices = new EquipmentExpansionServices();
    $this->ExpansionServices          = new ExpansionServices();
    $this->SpawnServices              = new SpawnServices();
    $this->SpawnLiveDeckServices      = new SpawnLiveDeckServices();
    $this->SurvivorServices           = new SurvivorServices();
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticPisteContent($WpPage)
  {
    $Bean = new WpPageToolsBean($WpPage);
    return $Bean->getPisteContent();
  }
  /**
   * @return string
   */
  public function getPisteContent()
  {
  // Nombre de dés à lancer
    $nbDeDes = $this->initVar('nbDeDes');
  // Seuil de réussite
    $seuilReussite = $this->initVar('seuilReussite', 6);
  // Bénéficie de la compétence "Sur un 6 : +1 dé" ?
    $hasSurUn6 = ($this->initVar('surUn6')==1);
  // Bénéficie de la compétence "+1 au résultat du dé" ?
    $hasPlusUnAuDe = ($this->initVar('plusUnAuDe')==1);
  
    if (is_numeric($nbDeDes)) {
      $str = 'Résultat de ce lancer <strong>'.$nbDeDes.'D</strong> à <strong>'.$seuilReussite.'+</strong> : ';
      $strJets = '';
      for ($i=1; $i<=$nbDeDes; $i++) {
        $strJets .= $this->getStrJets($nbDeDes, $hasPlusUnAuDe, $hasSurUn6, $seuilReussite);
      }
      $str .= $strJets.'<br><br>';
    }
    $str .= 'Saisir un nombre de dés et les lancer.';
    $args = array(
      $str
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-piste-de-des.php');
    return vsprintf($str, $args);
  }
  private function getStrJets(&$nbDeDes, $hasPlusUnAuDe, $hasSurUn6, $seuilReussite)
  {
    $score = rand(1, 6);
    if ($score == 6 || ($score == 5 && $hasPlusUnAuDe)) {
      $strClasse = "primary";
      if ($hasSurUn6) {
        $nbDeDes++;
      }
    } elseif ($score==1) {
      $strClasse = "danger";
    } elseif ($score >= ($seuilReussite - ($hasPlusUnAuDe ? 1 : 0))) {
      $strClasse = "info";
    } else {
      $strClasse = "warning";
    }
    return '<span class="badge badge-'.$strClasse.'">'.$score.'</span> ';
  }
  /**
   * @param WpPost $WpPage
   * @return string
   */
  public function getStaticSurvivorsContent($WpPage)
  {
    $Bean = new WpPageToolsBean($WpPage);
    return $Bean->getSurvivorsContent();
  }
  /**
   * @return string
   */
  public function getSurvivorsContent()
  {
    $Expansions = $this->ExpansionServices->getExpansionsWithFilters(__FILE__, __LINE__, array(), self::CST_DISPLAYRANK, 'ASC');
    if (!empty($Expansions)) {
      $str .= '<div class="btn-group-vertical team-selection" role="group"><div class="btn-toolbar" role="toolbar">';
      $str .= '<div class="btn-group" id="nbSurvSel" role="group">';
      for ($i=1; $i<=6; $i++) {
        $str .= '  <button type="button" class="btn btn-dark'.($i==6?' active':'').'" data-nb="'.$i.'">'.$i.'</button>';
      }
      $str .= '</div></div>';
      foreach ($Expansions as $Expansion) {
        $id = $Expansion->getId();
        $Survivors = $this->SurvivorServices->getSurvivorsWithFilters(__FILE__, __LINE__, array(self::CST_EXPANSIONID=>$id));
        if (empty($Survivors)) {
          continue;
        }
        $str .= '<div type="button" class="btn btn-dark btn-expansion" data-expansion-id="'.$id.'"><span>';
        $str .= '<i class="far fa-square"></i></span> '.$Expansion->getName().'</div>';
        while (!empty($Survivors)) {
          $Survivor = array_shift($Survivors);
          $survivorId = $Survivor->getId();
          $str .= '<button type="button" class="btn btn-secondary btn-survivor hidden" data-expansion-id="';
          $str .= $id.'" data-survivor-id="'.$survivorId.'"><i class="far fa-square"></i> '.$Survivor->getName().'</button>';
        }
      }
      $str .= '<div type="button" class="btn btn-primary btn-expansion" id="proceedBuildTeam"><span>';
      $str .= '<i class="far fa-check-circle"></i></span> Générer</div></div>';
    }
    $args = array(
      $str
   );
    $str = file_get_contents(PLUGIN_PATH.'web/pages/public/public-page-selection-survivors.php');
    return vsprintf($str, $args);
  }
}
