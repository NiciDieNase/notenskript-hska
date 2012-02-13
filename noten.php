<?php
error_reporting(E_ALL);
require('config.php');



function istZeileNeu($row) {
  global $__letzterNotenspiegel;
  if(file_exists(FILE_LETZTER_NOTENSPIEGEL)) {
    if(sizeof($__letzterNotenspiegel) <= 0) {
      $__letzterNotenspiegel = file(FILE_LETZTER_NOTENSPIEGEL);
    }
    if(sizeof($__letzterNotenspiegel) > 0) {
      $prepare_row = '"' . join('";"', $row) . '"';
      foreach($__letzterNotenspiegel as $line) {
        if(trim($prepare_row) == trim($line)) {
          return(false);
        } 
      }
      return(true);
    }
  }
  return(false);
}

function getNoteninfo($link){
            $curl = new mycurl(str_replace('amp;', '', $link));
            $curl->createCurl();
            $uebersicht_seite = str_replace("\n","",$curl->__tostring());
          if(preg_match('/'.
 '<tr>\s*<td class="tabelle1"[^>]*align="right">(sehr gut)\s*([^<]*)<\/td>\s*<td class="tabelle1".*align="right">(\S*)[^<]*<\/td>[^<]*<\/tr>\s*'.
      '<tr>\s*<td class="tabelle1"[^>]*align="right">(gut)\s*([^<]*)<\/td>\s*<td class="tabelle1".*align="right">(\S*)[^<]*<\/td>[^<]*<\/tr>\s*'.
'<tr>\s*<td class="tabelle1"[^>]*align="right">(befriedigend)\s*([^<]*)<\/td>\s*<td class="tabelle1".*align="right">(\S*)[^<]*<\/td>[^<]*<\/tr>\s*'.
'<tr>\s*<td class="tabelle1"[^>]*align="right">(ausreichend)\s*([^<]*)<\/td>\s*<td class="tabelle1".*align="right">(\S*)[^<]*<\/td>[^<]*<\/tr>\s*'.
'<tr>\s*<td class="tabelle1".*align="right">(nicht ausreichend)\s*([^<]*)<\/td>\s*<td class="tabelle1".*align="right">(\S*)[^<]*<\/td>[^<]*<\/tr>\s*'.
'/', $uebersicht_seite, $link)){
         preg_match('/(In .* Leistungen wurde eine Durchschnittsnote von  .* erzielt.)/', $uebersicht_seite, $schnitt); 
          
          
           $tab1 = 20;
          $tab2 = 15;
          return ("\n".
           str_pad("Notenbereich", $tab1, ' ').str_pad("", $tab2, ' ')."Anzahl"."\n".
           str_pad($link[1], $tab1, ' ').str_pad($link[2], $tab2, ' ').$link[3]."\n".
           str_pad($link[4], $tab1, ' ').str_pad($link[5], $tab2, ' ').$link[6]."\n".
           str_pad($link[7], $tab1, ' ').str_pad($link[8], $tab2, ' ').$link[9]."\n".
           str_pad($link[10], $tab1, ' ').str_pad($link[11], $tab2, ' ').$link[12]."\n".
           str_pad($link[13], $tab1, ' ').str_pad($link[14], $tab2, ' ').$link[15]."\n".
           $schnitt[1]."\n\n");
          

      }
}





 class mycurl {
     protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
     protected $_url;
     protected $_followlocation;
     protected $_timeout;
     protected $_maxRedirects;
     protected $_cookieFileLocation = './cookie.txt';
     protected $_post;
     protected $_postFields;
     protected $_referer ="http://www.google.com";

     protected $_session;
     protected $_webpage;
     protected $_includeHeader;
     protected $_noBody;
     protected $_status;
     protected $_binaryTransfer;
     public    $authentication = 0;
     public    $auth_name      = '';
     public    $auth_pass      = '';

     public function useAuth($use){
       $this->authentication = 0;
       if($use == true) $this->authentication = 1;
     }

     public function setName($name){
       $this->auth_name = $name;
     }
     public function setPass($pass){
       $this->auth_pass = $pass;
     }

     public function __construct($url,$followlocation = true,$timeOut = 30,$maxRedirecs = 4,$binaryTransfer = false,$includeHeader = false,$noBody = false)
     {
         $this->_url = $url;
         $this->_followlocation = $followlocation;
         $this->_timeout = $timeOut;
         $this->_maxRedirects = $maxRedirecs;
         $this->_noBody = $noBody;
         $this->_includeHeader = $includeHeader;
         $this->_binaryTransfer = $binaryTransfer;

         $this->_cookieFileLocation = dirname(__FILE__).'/cookie.txt';

     }

     public function setReferer($referer){
       $this->_referer = $referer;
     }

     public function setCookiFileLocation($path)
     {
         $this->_cookieFileLocation = $path;
     }

     public function setPost ($postFields)
     {
        $this->_post = true;
        $this->_postFields = $postFields;
     }

     public function setUserAgent($userAgent)
     {
         $this->_useragent = $userAgent;
     }

     public function createCurl($url = 'nul')
     {
        if($url != 'nul'){
          $this->_url = $url;
        }

         $s = curl_init();

         curl_setopt($s,CURLOPT_URL,$this->_url);
         curl_setopt($s,CURLOPT_HTTPHEADER,array('Except:'));
         curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
         curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
         curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
         curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation);
         curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
         curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation);

         if($this->authentication == 1){
           curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
         }
         if($this->_post)
         {
             curl_setopt($s,CURLOPT_POST,true);
             curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields);

         }

         if($this->_includeHeader)
         {
               curl_setopt($s,CURLOPT_HEADER,true);
         }

         if($this->_noBody)
         {
             curl_setopt($s,CURLOPT_NOBODY,true);
         }
         /*
         if($this->_binary)
         {
             curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
         }
         */
         curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent);
         curl_setopt($s,CURLOPT_REFERER,$this->_referer);

         $this->_webpage = curl_exec($s);
                   $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);
         curl_close($s);

     }

   public function getHttpStatus()
   {
       return $this->_status;
   }

   public function __tostring(){
      return $this->_webpage;
   }
}


//Login
$curl = new mycurl('https://qis2.hs-karlsruhe.de/qisserver/rds?state=user&type=1&category=auth.login&startpage=portal.vm&breadCrumbSource=portal');
$curl->setPost('asdf=' . IZ_BENUTZER . '&fdsa=' . IZ_PASSWORT . '&submit=Anmelden');

$curl->createCurl();

$login_seite = $curl->__tostring();
#echo $login_seite."\n\n";
$asi = false;
if(preg_match('<a href=\"https://qis2\.hs-karlsruhe\.de/qisserver/rds.+asi=(.*)\">', $login_seite, $found)) {
  $asi = substr($found[1], 0, strpos($found[1], '"'));
}

if($asi) {
  //Seite Prüfungsverwaltung..
  $curl = new mycurl('https://qis2.hs-karlsruhe.de/qisserver/rds?state=change&type=1&moduleParameter=studyPOSMenu&nextdir=change&next=menu.vm&subdir=applications&xml=menu&purge=y&menuid=studyPOSMenu&breadcrumb=studyPOSMenu&breadCrumbSource=loggedin&asi=' . $asi);

  $curl->createCurl();
  $p_seite = $curl->__tostring();
  //Link zum Notespiegel finden..
  if(preg_match('/a href="(https.*qis2\.hs-karlsruhe\.de.*notenspiegelStudent.*)".*title/', $p_seite, $link)) {
   # echo $link[0]."\n";
   # echo "link: " . $link[1];

    $curl = new mycurl(str_replace('amp;', '', $link[1]));

    $curl->createCurl();

    $n_seite = $curl->__tostring();
    
    //Link zu den Leistungen
    if(preg_match('/a href="(https.*qis2\.hs-karlsruhe\.de.*notenspiegelStudent.*)".*title="Leistungen für Abschluss Bachelor anzeigen"><img src/', $n_seite, $link)) {
    #echo $link[1];  
      $curl = new mycurl(str_replace('amp;', '', $link[1]));

      $curl->createCurl();

      $notenhtml = $curl->__tostring();
      #echo $notenhtml;
       $zeilen = explode("\n", $notenhtml);
       $csv_rows = array();
       $linkInfoseite = "";
       $linkInfoseiten = array();
       $row = array();
       $neue_noten = array();
       for($i = 0; $i < sizeof($zeilen); $i++) {
         //Kurs Nummer
         if(preg_match('<td class="tabelle1_alignleft" valign="top" align="left" width="8%">', $zeilen[$i])) {
          $i++;
          if(sizeof($row) > 0) {
            if(istZeileNeu($row)) {
              $neue_noten[] = $row;
              $linkInfoseiten[] = $linkInfoseite;
                  
            }
            $csv_rows[] = $row;
          }
          $row = array(trim($zeilen[$i]));
          $linkInfoseite = "";

          $i++;
         }
         //Kurs Name
         if(preg_match('<td class="tabelle1_alignleft" valign="top" align="left" width="34%">', $zeilen[$i])) {
           $i++;
           $row[] = trim($zeilen[$i]);
           $i++;
         }
         //Semester
         if(preg_match('<td class="tabelle1_alignleft" valign="top" align="left" width="15%">', $zeilen[$i])) {
            $i++;
            $row[] = trim($zeilen[$i]);
            $i++;
          }
         //Datum
          if(preg_match('<td class="tabelle1_alignright" valign="top" align="right" width="10%">', $zeilen[$i])) {
            $i++;
            $row[] = trim($zeilen[$i]);
            $i++;
          }
          //Note
          if(preg_match('<td class="tabelle1_alignright" valign="top" align="right" width="8%">', $zeilen[$i])) {
            $i++;
            $row[] = trim($zeilen[$i]);
            $i++;
          }
          //Status
          if(preg_match('<td class="tabelle1_aligncenter" valign="top" align="center" width="13%">', $zeilen[$i])) {
            $i++;
            $row[] = trim($zeilen[$i]);
            $i++;
          }
          //Versuche
          if(preg_match('<td class="tabelle1_aligncenter" valign="top" align="center" width="6%">', $zeilen[$i])) {
            $i++;
            $row[] = trim($zeilen[$i]);
            $i++;
          }

          if(preg_match('/<a href="(.*qis2\.hs-karlsruhe\.de.qisserver.rds.state=notenspiegelStudent&amp;next=list\.vm&amp;nextdir=qispos.notenspiegel.student&amp;createInfos=Y&amp.*)"><img src=/',$zeilen[$i] , $link)){
           $linkInfoseite = trim($link[1]);

          }
            #echo $uebersicht_seite;
          
            
       }

       if(sizeof($row) > 0) {
         if(istZeileNeu($row)) {
           $neue_noten[] = $row;
           $linkInfoseiten[] = $linkInfoseite;
         }
         $csv_rows[] = $row;
       }
       $linkInfoseite = "";
       
       $f = fopen(FILE_LETZTER_NOTENSPIEGEL, 'w+');
       if(!$f) {
         die('Kann Datei ' . FILE_LETZTER_NOTENSPIEGEL . ' nicht zum schreiben öffnen');
       }
       foreach($csv_rows as $row) {
         fwrite($f, '"' . join('";"', $row) . '"' . "\n");
       }
       fclose($f);
      
       if(sizeof($neue_noten) > 0) {
         $strNoten = '';
         array_unshift($neue_noten, array('Kurs Nr.', 'Kurs Bezeichnung', 'Semester', 'Datum', 'Note', 'Status', 'Versuch'));
         array_unshift($linkInfoseiten, "");
#         foreach($neue_noten as $note) {
         for ($j = 0; $j < sizeof($neue_noten); $j++){
           $note = $neue_noten[$j];
           for($i = 0; $i < sizeof($note); $i++) {
             $note[$i] = str_replace(array('ä', 'ö', 'ü'), array('ae', 'oe', 'ue'), $note[$i]);
             switch($i) {
               case 0:
                 $pad_length = 8;
                 break;
               case 1:
                 $pad_length = 30;
                 if(strlen($note[$i]) > ($pad_length -1)) {
                   $note[$i] = substr($note[$i], 0, $pad_length - 3) . "..";
                 }
                 break;
               case 5:
                 $pad_length = 20;
                 break;
               default:
                 $pad_length = 13;
                 break;
             }
             $strNoten .= str_pad($note[$i], $pad_length, ' ');
           }
           
           $strNoten .= "\n";
           $strNoten .= getNoteninfo($linkInfoseiten[$j]);
         }
         mail(BENACHRICHTUNGS_EMAILADRESSE, 'Neue Noten sind Online!', "Hallo,\nfolgende Noten wurden soeben eingetragen:\n\n" . $strNoten . "\nDies ist eine Automatisch erstellte E-Mail.", 'From: notenscript@localhost' . "\nContent-Type: text/plain;charset=utf-8\n");
        # echo "E-Mail Benachrichtigung wurde versendet.\n";
       }
       
    }
  }
} else {
 mail(ERROR_EMAILADRESSE, 'Noten-TimeOut!', "Hallo,\nirgendwas ist schief gelaufen, ich konnte mich nicht anmelden. \n\nDies ist eine Automatisch erstellte E-Mail.", 'From: notenscript@localhost' . "\nContent-Type: text/plain;charset=utf-8\n");
}


//Abmelden nicht vergessen!!
$curl = new mycurl('https://qis2.hs-karlsruhe.de/qisserver/rds?state=user&type=4&re=last&category=auth.logout&breadCrumbSource=');

$curl->createCurl();


?>
