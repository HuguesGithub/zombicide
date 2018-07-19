<?php
if (!defined('ABSPATH')) {
  die('Forbidden');
}
/**
 * Classe TokenBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class TokenBean extends LocalBean
{
  /**
   * Class Constructor
   * @param Token $Token
   */
  public function __construct($Token='')
  {
    parent::__construct();
    $this->Token = ($Token=='' ? new Token() : $Token);
  }
  /**
   * @param string $tBodyButtons Template des Boutons de fin de ligne
   * @return string
   */
  public function getRowForAdminPage($tBodyButtons)
  {
    $Token = $this->Token;
    $queryArg = array(
      self::CST_ONGLET=>'parametre',
      self::CST_POSTACTION=>'edit',
      'table'=>'token',
      'id'=>$Token->getId()
    );
    $urlEdit = $this->getQueryArg($queryArg);
    $queryArg[self::CST_POSTACTION] = 'trash';
    $urlTrash = $this->getQueryArg($queryArg);
    $args = array(
      '&nbsp;',
      $urlEdit,
      $urlTrash
    );
    $tBody  = '<tr><td>'.$Token->getId().'</td><td>'.$Token->getCode().'</td>';
    $tBody .= '<td>'.$Token->getWidth().'</td><td>'.$Token->getHeight().'</td>';
    return $tBody.vsprintf($tBodyButtons, $args).'</tr>';
  }
}
