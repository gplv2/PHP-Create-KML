<?php

$json= <<<EOD
{"0":{"lijn":"1","type":"0","halte_nr":"3620","gemeente":"Steendorp","halte_label":"Kapelstraat 'Hemelrijkstraat'","uur":"06:40","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.134124","lon":"4.277015"},"2":{"lijn":"1","type":"0","halte_nr":"3630","gemeente":"Bazel","halte_label":"Kruibekestraat 'Dorp'","uur":"06:43","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.147748","lon":"4.298472"},"3":{"lijn":"1","type":"0","halte_nr":"3640","gemeente":"Kruibeke","halte_label":"Bazelstraat 'Mercatorstraat'","uur":"06:49","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.167343","lon":"4.310575"},"4":{"lijn":"1","type":"0","halte_nr":"3650","gemeente":"Kruibeke","halte_label":"O.L.Vrouwplein 'Dorp'","uur":"06:50","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.170895","lon":"4.312806"},"5":{"lijn":"1","type":"0","halte_nr":"3660","gemeente":"Kruibeke","halte_label":"Burchtstraat 'Scheldelei'","uur":"06:52","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.176384","lon":"4.315896"},"6":{"lijn":"1","type":"0","halte_nr":"3670","gemeente":"Burcht","halte_label":"Kruibeeksesteenweg 'Veldstraat'","uur":"06:56","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.197741","lon":"4.328599"},"7":{"lijn":"1","type":"0","halte_nr":"3680","gemeente":"Burcht","halte_label":"Heirbaan 'Kampstraat'","uur":"06:57","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.200645","lon":"4.335637"},"8":{"lijn":"1","type":"0","halte_nr":"3690","gemeente":"Burcht","halte_label":"Dorpstraat 'Kerkplein'","uur":"06:59","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.201775","lon":"4.345937"},"9":{"lijn":"1","type":"0","halte_nr":"3700","gemeente":"Antwerpen","halte_label":"Frederik Van Eedenplein 'Van Eedenplein - Perron 1a'","uur":"07:03","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.220594","lon":"4.386535"},"10":{"lijn":"1","type":"0","halte_nr":"3710","gemeente":"Antwerpen","halte_label":"Blancefloerlaan 'Halewijn'","uur":"07:05","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.220594","lon":"4.379153"},"11":{"lijn":"1","type":"0","halte_nr":"3720","gemeente":"Zwijndrecht","halte_label":"Verbrandendijk \"Van Goey\"","uur":"07:09","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.219572","lon":"4.334607"},"12":{"lijn":"1","type":"0","halte_nr":"3730","gemeente":"Zwijndrecht","halte_label":"Dorp West 'Dorp'","uur":"07:11","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.219216","lon":"4.325683"},"13":{"lijn":"1","type":"0","halte_nr":"3740","gemeente":"Zwijndrecht","halte_label":"Dorp West 'Hof Ter Rijen'","uur":"07:12","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.219035","lon":"4.318385"},"14":{"lijn":"1","type":"0","halte_nr":"3750","gemeente":"Melsele","halte_label":"Grote Baan 'Burggravenlaan'","uur":"07:15","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.220862","lon":"4.293151"},"15":{"lijn":"1","type":"0","halte_nr":"3760","gemeente":"Melsele","halte_label":"Grote Baan 'Spoorweglaan'","uur":"07:17","uur_v":null,"uur_l":null,"uur_n":null,"lat":"51.219216","lon":"4.325683"}}
EOD;

$array=json_decode($json,true);

$custom_icon_styles = array(
   array('id'=> 'HalteIcon','image_url'=> 'http://live.synctrace.com/icons/busstopblue.png','width'=> 2)
);

require_once('class.kml2.php');
$kml = new kml('Haltes', array('name'=> 'Lijn 1','glenn'=>'is cool'), array('icon_styles' => $custom_icon_styles));

foreach ($array as $key=>$val) {
   $pointStyle="HalteIcon";
   $kml->addPoint($val['lon'], $val['lat'], 0 , $options = array('title' => $val['halte_label'] , 'description' => $val['halte_label']), $pointStyle );
}

$parsedfile=$kml->export('testfile.kml');

// print_r($array);
?>
