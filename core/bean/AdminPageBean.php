<?php
if ( !defined( 'ABSPATH') ) die( 'Forbidden' );
/**
 * Classe AdminPageBean
 * @author Hugues.
 * @version 1.0.00
 * @since 1.0.00
 */
class AdminPageBean extends MainPageBean {
    
    /**
     * 
     * @param string $tag
     * @param array $services
     */
    public function __construct($tag='', $services=array()) {
        parent::__construct($services);
        $this->analyzeUri();
        $this->tableName = 'wp_11_zombicide_'.$tag;
        $this->tplAdminerUrl = 'http://zombicide.jhugues.fr/wp-content/plugins/adminer/inc/adminer/loader.php?username=dbo507551204&db=db507551204&table='.$this->tableName;
    /*
        $this->tplHeader = 'web/pages/admin/'.$tag.'/header.php';
        $this->tplRow = 'web/pages/admin/'.$tag.'/row.php';
        $this->tplEdit = 'web/pages/admin/'.$tag.'/edit.php';
    */
    }

    /**
     * @return string
     */
    public function analyzeUri() {
        $uri = $_SERVER['REQUEST_URI'];
        $pos = strpos($uri, '?');
        if ( $pos!==FALSE ) {
            $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
            if ( !empty($arrParams) ) {
                foreach ( $arrParams as $param ) {
                    list($key, $value) = explode('=', $param);
                    $this->urlParams[$key] = $value;
                }
            }
            $uri = substr($uri, 0, $pos-1);
        }
        $pos = strpos($uri, '#');
        if ( $pos!=FALSE ) { $this->anchor = substr($uri, $pos+1, strlen($uri)); }
        if ( isset($_POST) ) { foreach ( $_POST as $key=>$value ) { $this->urlParams[$key] = $value; } }
        return $uri;
    }
    /**
     * @return string
     */
    public function getContentPage() {
        if ( self::isAdmin() ) {
            switch ( $this->urlParams['onglet'] ) {
                case 'mission'        : return AdminMissionPageBean::getStaticContentPage($this->urlParams); break;
                case 'parametre'    : return AdminParametrePageBean::getStaticContentPage($this->urlParams); break;
                case '' : return $this->getHomeContentPage(); break;
                default : echo "Need to add <b>".$this->urlParams['onglet']."</b> to AdminPageBean > getContentPage()."; break;
            }
        }
    }
    /**
     * @return string
     */
    public function getHomeContentPage() {
        $reset = $this->initVar('reset', '');
        $doReset = !empty($reset);
        if ( $doReset ) {
            $ts = time();
            list($N, $d, $m, $y) = explode(' ', date('N d m y', $ts));
            $nd = $d + ( $N==1 ? 1 : 9-$N );
            $resetTs = mktime(1, 0, 0, $m, $nd, $y);
        }
        $request = "SELECT option_value FROM wp_11_options WHERE option_name='cron';";
        $row = MySQL::wpdbSelect($request);
        $Obj = array_shift($row);
    //echo "[[".$Obj->option_value."]]<br>";
        $arrOptions = unserialize($Obj->option_value);
    //print_r($arrOptions);
    //echo "<br><br><br>";
        foreach ( $arrOptions as $key=>$value ) {
            if ( isset($value['wp_db_backup_cron']) ) {
                $nextTs = $key;
                $arrOptions[$resetTs]['wp_db_backup_cron'] = $value['wp_db_backup_cron'];
                unset($arrOptions[$key]);
            }
        }
        if ( $doReset ) {
        //print_r($arrOptions);
            $serialized = serialize($arrOptions);
            $request = "UPDATE wp_11_options SET option_value='$serialized' WHERE option_name='cron';";
      //echo "<br><br>[[$request]]<br>";
        }
        $args = array(
      // Date de la prochaine sauvegarde - 1
            date('d/m/Y h:i:00', $nextTs),
        );
        $str = file_get_contents(PLUGIN_PATH.'web/pages/admin/home-admin-board.php');
        return vsprintf($str, $args);
    }
    
}
?>