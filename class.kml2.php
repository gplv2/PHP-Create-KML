<?
/**
 * Class kml
 * This class allows generate dynamic kml file to publish in google earth
 * Need google earth installed to view kml file
 * http://code.google.com/apis/kml/schema/kml21.xsd
 * http://googlemapsapi.blogspot.com/2007/06/validate-your-kml-online-or-offline.html
 *
 * @author pablo kogan
 * @modded extensively by Glenn Plas
 *
 */
class kml {
   private $sHeader;
   private $sBody;
   private $sFooter;
   private $sName;

   private $point_counter=0;
   private $line_counter=0;
   private $lookat_counter=0;

   private $multi_open=0;

   private $styles=array();

   private $gx=0;

   private $settings= array(
         'debug' => 0,
         'verbose' => 0,
         'load_default_poly_styles' => 0,
         'load_default_line_styles' => 0,
         'load_default_icon_styles' => 0,
         'output_dir' => 'output'
    );


   /**
    * Constructo
    */
   public function __construct($sName, $properties=array(), $options=array()) {
      if(count($options)) {
         $this->settings = array_merge($this->settings, $options);
      }

      $this->sName = $sName;
      $this->sHeader = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
      // $this->sHeader .= '<kml xmlns="http://earth.google.com/kml/2.0">' . "\n"; -> This makes stuff fail in google earth at the time
      if ($this->gx) {
         $this->sHeader .= '<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2">' . "\n";
      } else {
         $this->sHeader .= '<kml xmlns="http://www.opengis.net/kml/2.2">' . "\n";
      }

      $this->sHeader .= "<Document>" . "\n" ;
      $this->sHeader .= "<name>$sName</name>" . "\n" ;
      $this->sHeader .= "<open>1</open>" . "\n" ;
      $this->sHeader .= "<description><![CDATA[" . $sName . "]]>" . "</description>" . "\n";

      //$this->sHeader .= "<description>Description</description>"  . "\n";
      /** http://code.google.com/p/kml-samples/source/browse/trunk/kml/Style/styles.kml?spec=svn115&r=115 */
      
      if(!empty($this->settings['load_default_line_styles'])) {
         $default_line_styles = array(
            array('id'=> 'LightGreenLine','color'=> 'ff56ff1d','width'=> 3),
            array('id'=> 'GreenLine','color'=> 'ff35d500','width'=> 3),
            array('id'=> 'DarkGreenLine','color'=> 'ff0dc70b','width'=> 3),
            array('id'=> 'LightRedLine','color'=> 'ffa5a5f7','width'=> 3),
            array('id'=> 'RedLine','color'=> 'ff0000ff','width'=> 3),
            array('id'=> 'DarkRedLine','color'=> 'ff2222a5','width'=> 3),
            array('id'=> 'LightBlueLine','color'=> 'fffca17e','width'=> 3),
            array('id'=> 'BlueLine','color'=> 'ffff16a1','width'=> 3),
            array('id'=> 'DarkBlueLine','color'=> 'ffa00b03','width'=> 3)
         );
         $this->add_line_style($default_line_styles);
      }

      if(!empty($this->settings['load_default_icon_styles'])) {
         $default_icon_styles = array(
            array('id'=> 'busIcon','image_url'=> 'http://live.synctrace.com/icons/bus.png','width'=> 2),
            array('id'=> 'busIcon2','image_url'=> 'http://live.synctrace.com/icons/bustour.png','width'=> 3),
            array('id'=> 'StartIcon','image_url'=> 'http://live.synctrace.com/icons/busstopbluesmall.png','width'=> 2),
            array('id'=> 'SleepIcon','image_url'=> 'http://live.synctrace.com/images/kml/2/icon28.png','width'=> 2),
            array('id'=> 'StopIcon','image_url'=> 'http://live.synctrace.com/images/kml/4/icon15.png','width'=> 2)
         );
         $this->add_icon_style($default_icon_styles);
      }


      if(!empty($this->settings['load_default_poly_styles'])) {
         $default_poly_styles = array(
            array('id'=> 'auto_0','color'=> 'ff0000ff','width'=> 4),
            array('id'=> 'auto_1','color'=> 'ff0041ff','width'=> 4),
            array('id'=> 'auto_2','color'=> 'ff00a5ff','width'=> 4),
            array('id'=> 'auto_3','color'=> 'ff00bbff','width'=> 4),
            array('id'=> 'auto_4','color'=> 'ff00ffff','width'=> 4),
            array('id'=> 'auto_5','color'=> 'ff00e4ca','width'=> 4),
            array('id'=> 'auto_6','color'=> 'ff00b265','width'=> 4),
            array('id'=> 'auto_7','color'=> 'ff008000','width'=> 4),
            array('id'=> 'auto_8','color'=> 'ff654d00','width'=> 4),
            array('id'=> 'auto_9','color'=> 'ffff0000','width'=> 4),
            array('id'=> 'auto_A','color'=> 'ffe00012','width'=> 4),
            array('id'=> 'auto_B','color'=> 'ff82004b','width'=> 4),
            array('id'=> 'auto_C','color'=> 'ff981a6c','width'=> 4),
            array('id'=> 'auto_D','color'=> 'ffc34ead','width'=> 4),
            array('id'=> 'auto_E','color'=> 'ffee82ee','width'=> 4)
         );
         $this->add_poly_style($default_poly_styles);
      }
      
      if (!empty($this->settings['icon_styles']) && is_array($this->settings['icon_styles'])) {
         // print_r($this->settings['icon_styles']); exit;
         $this->add_icon_style($this->settings['icon_styles']);
      }
      if (!empty($this->settings['poly_styles']) && is_array($this->settings['poly_styles'])) {
         $this->add_poly_style($this->settings['poly_styles']);
      }
      if (!empty($this->settings['line_styles']) && is_array($this->settings['line_styles'])) {
         $this->add_line_style($this->settings['line_styles']);
      }
         // echo $this->export_all_styles(); exit;

      if (count($this->styles)) {
         $this->sHeader .= $this->export_all_styles();
      }

      $this->sHeader .= '<Folder>' . "\n";

      if (!key_exists('name', $properties)) {
         $this->sHeader .= '<name>Paths</name>' . "\n";
      }

      if (!key_exists('visibility', $properties)) {
         $this->sHeader .= '<visibility>1</visibility>' . "\n";
      }

      if (!key_exists('description', $properties)) {
         //$this->sHeader .= "<description>$sName</description>" . "\n";
         $this->sHeader .= "<description><![CDATA[" . $sName . "]]>" . "</description>" . "\n";
      }

      //print_r($properties); exit;
      foreach ($properties as $key => $property) {
         if (in_array( $key, array('description','name'))) {
            $this->sHeader .= sprintf('<%s><![CDATA[%s]]></%s>%s',$key, $property ,$key,"\n");
         } else {
            $this->sHeader .= sprintf('<%s>%s</%s>%s',$key, $property ,$key, "\n");
         }  
      }

      $this->sFooter .= '</Folder>' . "\n";
      $this->sFooter .= "</Document>" . "\n";
      $this->sFooter .= '</kml>' . "\n";
   }

   public function add_icon_style($attr) {
/* pseudo:
      array(
      $id='startIcon';
      $url='http://live.synctrace.com/icons/busstopbluesmall.png';
      $width=2;)
*/

   //print_r($attr); exit;
      foreach($attr as $key => $style) {
         if (!empty($style['id']) && !empty($style['image_url']) && !empty($style['width'])){
            $sStyle  = sprintf('<Style id="%s">%s',$style['id'],"\n");
            $sStyle .= '<IconStyle>'. "\n";
            $sStyle .= '<Icon>'. "\n";
            $sStyle .= sprintf('<href>%s</href>%s',$style['image_url'], "\n");
            $sStyle .= '</Icon>'. "\n";
            $sStyle .= '</IconStyle>'. "\n";
            $sStyle .= '<LineStyle>'. "\n";
            $sStyle .= sprintf('<width>%s</width>%s',$style['width'],"\n");
            $sStyle .= '</LineStyle>'. "\n";
            $sStyle .= '</Style>'. "\n";
         }
      }

      $this->styles[]=$sStyle;
   }

   public function add_poly_style($attr) {
/* pseudo:
      array(
      $id='startIcon';
      $url='http://live.synctrace.com/icons/busstopbluesmall.png';
      $width=2;)
*/
      foreach($attr as $key => $style) {
         if (!empty($style['id']) && !empty($style['color']) && !empty($style['width'])){
            $sStyle  = sprintf('<Style id="%s">%s',$style['id'], "\n");
            $sStyle .= '<LineStyle>' ."\n";
            $sStyle .= sprintf('<color>%s</color>%s',$style['color'],"\n");
            $sStyle .= sprintf('<width>%s</width>%s',$style['width'],"\n");
            $sStyle .= '</LineStyle>' ."\n";
            $sStyle .= '<PolyStyle>' ."\n";
            $sStyle .= sprintf('<color>%s</color>%s',(isset($style['polycolor']) ? $style['polycolor'] : $style['color']), "\n" );
            $sStyle .= '</PolyStyle>' ."\n";
            $sStyle .= '</Style>' ."\n";
         }
      }

      $this->styles[]=$sStyle;
   }

   public function add_line_style($attr) {
/* pseudo:
      array(
      $id='startIcon';
      $url='http://live.synctrace.com/icons/busstopbluesmall.png';
      $width=2;)
*/
      foreach($attr as $key => $style) {
         if (!empty($style['id']) && !empty($style['color']) && !empty($style['width'])){
            $sStyle  = sprintf('<Style id="%s">%s',$style['id'],"\n");
            $sStyle .= '<LineStyle>'."\n";
            $sStyle .= sprintf('<color>%s</color>%s',$style['color'], "\n");
            $sStyle .= sprintf('<width>%s</width>%s',$style['width'], "\n");
            $sStyle .= '</LineStyle>'."\n";
            $sStyle .= '</Style>' . "\n";
         }
      }

      $this->styles[]=$sStyle;
   }

   /* Creates the styles text */
   private function export_all_styles() {
      $my_style="";
      // print_r($this->styles); exit;
      foreach ( $this->styles as $key => $style ){
         // print_r($style); 
         $my_style .= $style;
      }
   //echo $style; exit;
      return $my_style;
   }

   /**
   /**
    * Add element to kml file
    */
   private function add_element($sElement) {
      $this->sBody .= $sElement;
   }
   /**
    * Print kml, change the header to open Google earth
    */
   public function export($filename) {
      if($filename) {
         $this->sName=$filename;
      }
      header('Content-type: text/xml');
      header('Content-type: application/vnd.google-earth.kml+xml');
      //header('Content-Disposition: attachement; filename="' . $this->sName . '.kml"');
      //header('Content-Length: ' . strlen($sKml));
      header('Expires: 0');
      header('Pragma: cache');
      header('Cache-Control: private');

      //header('Content-type: application/keyhole');
      header('Content-Disposition:atachment; filename="' . $filename. '.kml"');
      $sKml = $this->sHeader . $this->sBody . $this->sFooter;
      // header('Content-Length: ' . strlen($sKml));
      echo $sKml;
   }
   /**
    * Save the file locally to postprocess it
    */
   function exportlocal($filename) {
      if(strlen($filename)>0) {
         $this->sName=$filename;
      }
      // trigger_error($filename);
      //debug($this->getCurrentDirectory());

      if (!empty($this->settings['output_dir'])) {
         $myfile=$this->getCurrentDirectory() . '/' . $this->settings['output_dir'] . '/'.$filename.".kml";
      } else {
         $myfile=$this->getCurrentDirectory() . '/'.$filename.".kml";
      }

      $tempf = @fopen($myfile, 'w');
      if(!$tempf) {
         return;
      } else {
         $sKml = $this->sHeader . $this->sBody . $this->sFooter;
         fputs($tempf, $sKml);
      }
      if(!fclose($tempf)) {
         //echo "Error! Couldn't close the file.";
      }
      return($myfile);
   }

   function getCurrentDirectory() {
      if(defined('STDIN')) {
         return getcwd();
      } else {
         return $_SERVER['DOCUMENT_ROOT'] ;
      }
   } 

   /**
    * Save localally, parse with command line tool and hand back as gml
    */
   function exportgml($filename) {
      $parsedfile=$this->exportlocal($filename);
      if (strlen($parsedfile)<=0) {
         trigger_error("File name empty!");
         return;
      }
      // Call command line tools
/* pre 5.3 this worked
      $output=array();
      $retval=null;
      exec("/bin/sed 's/ xmlns=\"http\:\/\/earth.google.com\/kml\/2.0\"//' " . $parsedfile . " > " . $parsedfile ."_post",&$output,&$retval);
      exec("/usr/bin/xsltproc -o " . $parsedfile . ".gml ". $this->getCurrentDirectory() ."kml2gml.xsl " . $parsedfile . "_post",&$output,&$retval);
      //debug($this->getCurrentDirectory());
*/

/* post 5.3 this does it */
      $cmd="/bin/sed 's/ xmlns=\"http\:\/\/earth.google.com\/kml\/2.2\"//' " . $parsedfile . " > " . $parsedfile ."_post";
      // echo $cmd . "\n";
      exec($cmd);
      $xslt="/usr/bin/xsltproc -o " . $parsedfile . ".gml ". $this->getCurrentDirectory() ."/kml2gml.xsl " . $parsedfile . "_post";
      // echo $xslt . "\n";
      exec($xslt);
      //debug($this->getCurrentDirectory());

      // Clean up
      unlink($parsedfile ."_post");
      unlink($parsedfile);

      $content=null;
      $tempf = @fopen($parsedfile.".gml", 'r');
      if ($tempf) {
         while (!feof($tempf)) {
            $buffer = fgets($tempf, 4096);
            $content.=$buffer;
         }
         fclose($tempf);
      }

      header('Content-type: application/keyhole');
      header('Content-Disposition:atachment; filename="' . $filename. '.gml"');
      header('Content-Length: ' . strlen($content));
      header('Expires: 0');
      header('Pragma: cache');
      header('Cache-Control: private');
      echo $content;
      //return($filename);
   }
   /**
   /**
    * Add point to kml file
    * @param int $lat latitude
    * @param int $lon longitude
    * @param int $alt altitude
    * @param string $tit title of point
    * @param string $des description of point
    * @param string $sLayer style of point default ''
    */

   function addPoint($lon, $lat, $alt=0, $user_options , $sLayer = '') {
      //print_r($user_options); 
      //print_r($user_options); exit;
      if(!isset($lon) or !isset($lat)) { return null; }

      $this->point_counter++;

      $defaults =  array('title' => 'PointTitle', 'description' => 'PointDescription' , 'heading' => 0, 'altitude' =>0, 'visibility' => 1, 'altitude' =>0, 'range' => 150, 'tilt' => 60, 'altitudemode' =>'clampToGround', 'tessellate' => 1, 'extrude' => 1);
      $options = array_merge($defaults, $user_options);

      $sResponse = '<Placemark id="po_'. $this->point_counter . '">' . "\n";
      if(isset($options['timestamp'])){
         $sResponse .= "<TimeStamp><when>" .$options['timestamp'] . "</when></TimeStamp>" . "\n";
         if (!empty($options['begin'])) {
            $sResponse .= "<TimeSpan><begin>" .$options['begin'] . "</begin>" . "\n";
            if (!empty($options['end'])) {
               $sResponse .= "<end>" .$options['end'] . "</end></TimeSpan>" . "\n";
            } else {
               $sResponse .= "</TimeSpan>" . "\n";
            }
         }
      }

      //$sResponse .= "<description><![CDATA[" . $options['description'] . "]]>" . "</description>" . "\n";
      if(isset($options['title'])){
         $mkey='name';
         $sResponse .= sprintf('<%s><![CDATA[%s]]></%s>%s',$mkey, $options['title'],$mkey,"\n");
         //$sResponse .= "<name>". $options['title'] ."</name>" . "\n";
      }
      if(isset($options['description'])){
         $sResponse .= "<description><![CDATA[" . $options['description'] . "]]>" . "</description>" . "\n";
      }

      if(isset($options['image_url'])){
         $sResponse .= "<image_url>" . $options['image_url'] . "</image_url>" . "\n";
      }

      if(isset($options['visibility'])){
         $sResponse .= '<visibility>1</visibility>' . "\n";
      }
      $sResponse .= "<styleUrl>#$sLayer</styleUrl>" . "\n";
      $sResponse .= '<Point>' . "\n";
      if(isset($options['visibility'])){
         $sResponse .= "<tessellate>". $options['tessellate']  ."</tessellate>" . "\n";
      }
      //$sResponse .= "<extrude>". $options['extrude'] ."</extrude>" . "\n";
      $sResponse .= "<coordinates>$lon,$lat,$alt</coordinates>" . "\n";
      $sResponse .= '</Point>' . "\n";
      $sResponse .= '</Placemark>' . "\n";
      $this->add_element($sResponse);
   }
   /**
    * Add line to kml file
    * @param array $coordinates poits of line array of array('lat'=>num,'lon'=>num,'alt'=num)
    * @param string $tit title of line
    * @param string $des description of line
    * @param string $sLayer style of line default ''
    */

   public function setMultiGeometry($state) {
      // return "";
      $sResponse = '';
      if ($state==1) {
         $sResponse .= '<MultiGeometry>' . "\n";
         $this->multi_open=1;
      } else {
         $sResponse .= '</MultiGeometry>' . "\n";
         $this->multi_open=0;
      }
      $this->add_element($sResponse);
   }

   function addLine($coordinates, $user_options , $sLayer = '') {
// print_r($user_options); exit;
      if(!isset($coordinates)) { return null; }

      $this->line_counter++;
      $defaults =  array('title' => 'LineTitle', 'description' => 'LineDescription' , 'heading' => 0, 'altitude' =>0, 'visibility' => 1, 'altitude' =>0, 'range' => 150, 'tilt' => 60, 'altitudemode' =>'clampToGround', 'tessellate' => 1, 'extrude' => 1);
      $options = array_merge($defaults, $user_options);

      //print_r($defaults);
      // print_r($options);
      // print_r($user_options); 

      if ($options['speed_level'] <3 ) {
         $options['range']= $options['range']/3;
         $options['tilt']= 30;
      } elseif ($options['speed_level'] <6 ) {
         $options['range']= $options['range']/2;
         $options['tilt']= 40;
      } elseif ($options['speed_level'] <9 ) {
         $options['range']= $options['range'] - ( $options['range'] * 0.25);
         $options['tilt']= 50;
      }

      // Take the middle position
      $middle = round(count($coordinates)/2,0);

      $sResponse = '<Placemark id="pm_'. $this->line_counter . '">' . "\n";

      if(isset($options['timestamp'])){
         $sResponse .= "<TimeStamp><when>" .$options['timestamp'] . "</when></TimeStamp>" . "\n";
      }
      if(isset($options['title'])){
         $sResponse .= "<name>". $options['title'] ."</name>" . "\n";
      }
      if(isset($options['description'])){
         $sResponse .= "<description><![CDATA[" . $options['description'] . "]]>" . "</description>" . "\n";
      }
      if(isset($options['visibility'])){
         $sResponse .= '<visibility>'. $options['visibility'] .'</visibility>' . "\n";
      }
      $sResponse .= "<LookAt id=\"la_". $this->line_counter . "\">" ."\n";

      $sResponse .= "<longitude>" . $coordinates[$middle]['lon'] . "</longitude>" . "\n";
      $sResponse .= "<latitude>" . $coordinates[$middle]['lat'] . "</latitude>" . "\n";

      $sResponse .= "<altitude>" . $options['altitude'] . "</altitude>" . "\n";
      $sResponse .= "<range>" . $options['range'] . "</range>" . "\n";
      $sResponse .= "<tilt>" .$options['tilt'] . "</tilt>" . "\n";
      $sResponse .= "<heading>" . $options['heading'] . "</heading>" . "\n";
      $sResponse .= "<altitudeMode>" . $options['altitudemode'] . "</altitudeMode>" . "\n";
      $sResponse .= "</LookAt>" . "\n";
      $sResponse .= "<styleUrl>#$sLayer</styleUrl>" . "\n";
      $sResponse .= "<LineString>" . "\n";

      $sResponse .= "<tessellate>". $options['tessellate']  ."</tessellate>" . "\n";
      $sResponse .= "<extrude>". $options['extrude'] ."</extrude>" . "\n";

      $sResponse .= "<coordinates> ". "\n";
      $first = true;
      foreach ($coordinates as $key => $point) {
         if ($first) {
            $sResponse .= $point['lon'] . "," . $point['lat'] . "," . $point['alt'];
            $first = false;
         } else
            $sResponse .= " " . $point['lon'] . "," . $point['lat'] . "," . $point['alt'];
      }
      $sResponse .= " </coordinates>". "\n";
      $sResponse .= "</LineString>". "\n";
      $sResponse .= "</Placemark>". "\n";
      $this->add_element($sResponse);
   }
   /**
    * Add Polygon
    * @param array $coordinates poits of polygon array of array('lat'=>num,'lon'=>num,'alt'=num)
    * @param string $tit title of polygon
    * @param string $des description of polygon
    * @param string $sLayer style of polygon default ''
   */
   function addPolygon($coordinates, $tit, $des, $sLayer = '') {

      $sResponse = "<Placemark>";
      $sResponse .= "<name>$tit</name>";
      $sResponse .= "<styleUrl>#$sLayer</styleUrl>";
      $sResponse .= "<Polygon>";
      $sResponse .= "<tessellate>1</tessellate>";
      $sResponse .= "<outerBoundaryIs>
                           <LinearRing>
                              <coordinates>
               ";
      $first = true;
      foreach ($coordinates as $key => $point) {
         if ($first) {
            $sResponse .= $point['lon'] . "," . $point['lat'] . "," . $point['alt'];
            $first = false;
         } else
            $sResponse .= " " . $point['lon'] . "," . $point['lat'] . "," . $point['alt'];
      }
      $sResponse .= "</coordinates>
                          </LinearRing>
                        </outerBoundaryIs>
                     </Polygon>
                  </Placemark>
               ". "\n" ;
      $this->add_element($sResponse);
   }
   /**
    * Add Link
    * @param string $link link to file
    * @param string $tit title of link
    * @param string $sLayer style of link default ''
   */
   function addLink($link, $tit) {
      $aScript = explode('/', $_SERVER[SCRIPT_NAME]);
      array_pop($aScript);
      $sScript = implode('/', $aScript);
      $sLink = "http://" . $_SERVER[SERVER_NAME] . "/" . $sScript . "/$link";
      $sResponse = "<NetworkLink>";
      $sResponse .= "<name>$tit</name>";
      $sResponse .= "<Url>
               <href>$sLink</href>
               <refreshMode>onInterval</refreshMode>
               <viewRefreshMode>onRequest</viewRefreshMode>
            </Url>
            </NetworkLink>";
      //echo $sResponse;
      $this->add_element($sResponse);
   }
   /**
    * Add SreenOverlay
    * @param string $link link to logo file
    * @param string $tit title of logo
   */
   function addScreenOverlay($link, $tit) {
      $aScript = explode('/', $_SERVER[SCRIPT_NAME]);
      array_pop($aScript);
      $sScript = implode('/', $aScript);
      $sLink = "http://" . $_SERVER[SERVER_NAME] . "/" . $sScript . "/$link";
      $sResponse = "<ScreenOverlay>";
      $sResponse .= "<name>$tit</name>";
      $sResponse .= "<Icon>
               <href>$sLink</href>
            </Icon>
   <overlayXY x=\"1\" y=\"1\" xunits=\"fraction\" yunits=\"fraction\"/>
   <screenXY x=\"1\" y=\"1\" xunits=\"fraction\" yunits=\"fraction\"/>
   <rotationXY x=\"0\" y=\"0\" xunits=\"fraction\" yunits=\"fraction\"/>
   <size x=\"0.1\" y=\"0.1\" xunits=\"fraction\" yunits=\"fraction\"/>
   </ScreenOverlay>";
      //echo $sResponse;
      $this->add_element($sResponse);
   }
}
?>
