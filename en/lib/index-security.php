﻿<?php
if(php_sapi_name() == "cli"){
while($i < 60) {
sleep(1);
require_once __DIR__ . '/../wcp_config.php';
$guvenlikcek = $dbbaglan->query("SELECT * FROM guvenlik", PDO::FETCH_ASSOC);
$ayarcek = $dbbaglan->query("SELECT * FROM ayar", PDO::FETCH_ASSOC);

if ( $ayarcek->rowCount() ){
foreach( $ayarcek as $row5 ){
$entdizin = htmlspecialchars($row5['dizinent']);
$wcpdizin = htmlspecialchars($row5['dizinwcp']);
$emailadm = htmlspecialchars($row5['adminemail']);
$site = htmlspecialchars($row5['siteurl']);
}
}

if ( $guvenlikcek->rowCount() ){
foreach( $guvenlikcek as $row4 ){
if($row4['anasayfaguvenligi'] == 1){
    // islemler
$dosyalar = array("index.php", "index.html");
$filtre = array("owned", "own3d", "hacked", "h4cked", "0wned", "0wn3d", "hack3d", "h4ck3d", "pwned", "pwn3d", "rooted", "r00ted", "hackle", "hack'le", 'hack"le');
foreach($dosyalar as $x) {
	
	$dosyaadr = $x;
 
	$x = $entdizin."/".$x;

    if (file_exists($x)) {
        $ac = fopen($x, "r");
        $icerik = @fread($ac, filesize($x));
        $icerik .= @file_get_contents("http://".$site."/".$dosyaadr);
		$icerik .= @file_get_contents("https://".$site."/".$dosyaadr);
        fclose($ac);
     foreach($filtre as $y) {
            if (stristr($icerik, $y)) {	
             $hacklenmis = 1;
            }
        }
		if(isset($hacklenmis) && $hacklenmis == 1){		
		if (@copy($x, $wcpdizin."/backups/{$dosyaadr}-".date('d-m-Y')."-".md5(rand()))) {
                    $ac = fopen($x, "w+");
                    // if backup is successfull:
                    fwrite($ac, "<meta charset='utf8'>Our Website is Under Maintenance");
                    fclose($ac);
                    @mail($emailadm, "Your Website is Under Attack", "$site is under attack, take your website offline and take precaution immediately. Defaced file is: $x");
                } else {
                    // if backup is not successfull:
                    $ac = fopen($x, "w");
                    $yorum = " Original Codes of Source: (Base64 Encoded)\n ------------------------------------------------------ \n ";
                    $yorum .= base64_encode($icerik);
                    $yorum .= "\n \n ------------------------------------------------------ \n End of Original Codes";
                    // basilacak olan mesaj:
                    fwrite($ac, "<meta charset='utf8'>Our Website is Under Maintenance");
                    fclose($ac);
					@mail($emailadm, "Your Website is Under Attack", "$site is under attack, take your website offline and take precaution immediately. Defaced file is: $x. Also source codes are: \n \n $yorum");
                }
	unset($hacklenmis);
		}
		unset($ac);
		unset($icerik);
    }

}
}
}
}

	// islemler son
    $i++;
}	
}
	
?>