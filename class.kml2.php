<?
/**
 * Class kml
 * This class allows generate dynamic kml file to publish in google earth
 * Need google earth installed to view kml file
 * http://code.google.com/apis/kml/schema/kml21.xsd
 * http://googlemapsapi.blogspot.com/2007/06/validate-your-kml-online-or-offline.html
 *
 * @author pablo kogan
 * @ Modded by Glenn
 *
 */
class kml {
	private $sBody;
	private $sHeader;
	private $sFooter;
   private $sName;

   private $point_counter=0;
   private $gx=0;
   private $line_counter=0;
   private $lookat_counter=0;

   private $multi_open=0;

   private $styles=array();

   private $settings= array(
         'debug' => 0,
         'verbose' => 0
    );


	/**
	 * Constructor
	 */
	function __construct($sName, $tags) {
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
		//$this->sHeader .= "<description>Description</description>"  . "\n";
		$this->sHeader .= "<description><![CDATA[" . $sName . "]]>" . "</description>" . "\n";

		/** This is in styles.kml now: see: -> doesn't work
      http://code.google.com/p/kml-samples/source/browse/trunk/kml/Style/styles.kml?spec=svn115&r=115

		 * To change the style generate kml in google eath y paste in the next
		 * line the header style.
		$this->sHeader .= '<Style id="LightGreenLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ff56ff1d</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="GreenLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ff35d500</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="DarkGreenLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ff0dc70b</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="LightRedLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ffa5a5f7</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="RedLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ff0000ff</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="DarkRedLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ff2222a5</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="LightBlueLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>fffca17e</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="BlueLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ffff16a1</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";

		$this->sHeader .= '<Style id="DarkBlueLine">' ."\n";
      $this->sHeader .= '<LineStyle>'."\n";
      $this->sHeader .= '<color>ffa00b03</color>'."\n";
      $this->sHeader .= '<width>3</width>'."\n";
      $this->sHeader .= '</LineStyle>'."\n";
      $this->sHeader .= '</Style>' . "\n";
      */
      $this->sHeader .= '<Style id="busIcon">'. "\n";
      $this->sHeader .= '<IconStyle>'. "\n";
      $this->sHeader .= '<Icon>'. "\n";
      $this->sHeader .= '<href>http://live.synctrace.com/icons/bus.png</href>'. "\n";
      $this->sHeader .= '</Icon>'. "\n";
      $this->sHeader .= '</IconStyle>'. "\n";
      $this->sHeader .= '<LineStyle>'. "\n";
      $this->sHeader .= '<width>2</width>'. "\n";
      $this->sHeader .= '</LineStyle>'. "\n";
      $this->sHeader .= '</Style>'. "\n";

      $this->sHeader .= '<Style id="busIcon2">'. "\n";
      $this->sHeader .= '<IconStyle>'. "\n";
      $this->sHeader .= '<Icon>'. "\n";
      $this->sHeader .= '<href>http://live.synctrace.com/icons/bustour.png</href>'. "\n";
      $this->sHeader .= '</Icon>'. "\n";
      $this->sHeader .= '</IconStyle>'. "\n";
      $this->sHeader .= '<LineStyle>'. "\n";
      $this->sHeader .= '<width>2</width>'. "\n";
      $this->sHeader .= '</LineStyle>'. "\n";
      $this->sHeader .= '</Style>'. "\n";

      $this->sHeader .= '<Style id="startIcon">'. "\n";
      $this->sHeader .= '<IconStyle>'. "\n";
      $this->sHeader .= '<Icon>'. "\n";
      $this->sHeader .= '<href>http://live.synctrace.com/icons/busstopblue.png</href>'. "\n";
      $this->sHeader .= '</Icon>'. "\n";
      $this->sHeader .= '</IconStyle>'. "\n";
      $this->sHeader .= '<LineStyle>'. "\n";
      $this->sHeader .= '<width>2</width>'. "\n";
      $this->sHeader .= '</LineStyle>'. "\n";
      $this->sHeader .= '</Style>'. "\n";

      $this->sHeader .= '<Style id="sleepIcon">'. "\n";
      $this->sHeader .= '<IconStyle>'. "\n";
      $this->sHeader .= '<Icon>'. "\n";
      $this->sHeader .= '<href>http://live.synctrace.com/images/kml/2/icon28.png</href>'. "\n";
      $this->sHeader .= '</Icon>'. "\n";
      $this->sHeader .= '</IconStyle>'. "\n";
      $this->sHeader .= '<LineStyle>'. "\n";
      $this->sHeader .= '<width>2</width>'. "\n";
      $this->sHeader .= '</LineStyle>'. "\n";
      $this->sHeader .= '</Style>'. "\n";

      $this->sHeader .= '<Style id="i4Icon">'. "\n";
      $this->sHeader .= '<IconStyle>'. "\n";
      $this->sHeader .= '<Icon>'. "\n";
      $this->sHeader .= '<href>http://live.synctrace.com/images/kml/3/icon3.png</href>'. "\n";
      $this->sHeader .= '</Icon>'. "\n";
      $this->sHeader .= '</IconStyle>'. "\n";
      $this->sHeader .= '<LineStyle>'. "\n";
      $this->sHeader .= '<width>2</width>'. "\n";
      $this->sHeader .= '</LineStyle>'. "\n";
      $this->sHeader .= '</Style>'. "\n";

      $this->sHeader .= '<Style id="stopIcon">'. "\n";
      $this->sHeader .= '<IconStyle>'. "\n";
      $this->sHeader .= '<Icon>'. "\n";
      $this->sHeader .= '<href>http://live.synctrace.com/images/kml/4/icon15.png</href>'. "\n";
      $this->sHeader .= '</Icon>'. "\n";
      $this->sHeader .= '</IconStyle>'. "\n";
      $this->sHeader .= '<LineStyle>'. "\n";
      $this->sHeader .= '<width>2</width>'. "\n";
      $this->sHeader .= '</LineStyle>'. "\n";
      $this->sHeader .= '</Style>'. "\n";

      /*
      $this->sHeader .= '<Style id="transPurpleLineGreenPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>7fff00ff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7f00ff00</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="yellowLineGreenPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>7f00ffff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7f00ff00</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="thickBlackLine">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>87000000</color>' ."\n";
      $this->sHeader .= '<width>10</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="redLineBluePoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff0000ff</color>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ffff0000</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
/*
      $this->sHeader .= '<Style id="transPurpleLineGreenPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>7fff00ff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7f00ff00</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="yellowLineGreenPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>7f00ffff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7f00ff00</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="thickBlackLine">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>87000000</color>' ."\n";
      $this->sHeader .= '<width>10</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="redLineBluePoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff0000ff</color>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ffff0000</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="blueLineRedPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ffff0000</color>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff0000ff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="transRedPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<width>1.5</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7d0000ff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="transBluePoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<width>1.5</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7dff0000</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="transGreenPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<width>1.5</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7d00ff00</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

      $this->sHeader .= '<Style id="transYellowPoly">' ."\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<width>1.5</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>7d00ffff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
*/

      $this->sHeader .= '<Style id="auto_0">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff0000ff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff0000ff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_1">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff0041ff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff0041ff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_2">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff00a5ff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff00a5ff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_3">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff00bbff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff00bbff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_4">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff00ffff</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff00ffff</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_5">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff00e4ca</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff00e4ca</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_6">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff00b265</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff00b265</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_7">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff008000</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff008000</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_8">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff654d00</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff654d00</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_9">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ffff0000</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ffff0000</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_A">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ffe00012</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ffe00012</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_B">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff82004b</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff82004b</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_C">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ff981a6c</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ff981a6c</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_D">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ffc34ead</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ffc34ead</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";
      
      $this->sHeader .= '<Style id="auto_E">' . "\n";
      $this->sHeader .= '<LineStyle>' ."\n";
      $this->sHeader .= '<color>ffee82ee</color>' ."\n";
      $this->sHeader .= '<width>4</width>' ."\n";
      $this->sHeader .= '</LineStyle>' ."\n";
      $this->sHeader .= '<PolyStyle>' ."\n";
      $this->sHeader .= '<color>ffee82ee</color>' ."\n";
      $this->sHeader .= '</PolyStyle>' ."\n";
      $this->sHeader .= '</Style>' ."\n";

		$this->sHeader .= '<Folder>' . "\n";
      if (!empty($tags['name'])) {
         $this->sHeader .= sprintf('<name>%s</name>',$tags['name']) . "\n";
      } else {
         $this->sHeader .= '<name>Paths</name>' . "\n";
      }
      $this->sHeader .= '<visibility>1</visibility>' . "\n";
      $this->sHeader .= "<description>$sName</description>" . "\n";

		$this->sFooter .= '</Folder>' . "\n";
		$this->sFooter .= "</Document>" . "\n";
		$this->sFooter .= '</kml>' . "\n";
	}

   private function add_style() {
      
      $style_icon
      $style_id
      $width=2;

      $sStyle = '<Style id="startIcon">'. "\n";
      $sStyle .= '<IconStyle>'. "\n";
      $sStyle .= '<Icon>'. "\n";
      $sStyle .= '<href>http://live.synctrace.com/icons/busstopblue.png</href>'. "\n";
      $sStyle .= '</Icon>'. "\n";
      $sStyle .= '</IconStyle>'. "\n";
      $sStyle .= '<LineStyle>'. "\n";
      $sStyle .= '<width>2</width>'. "\n";
      $sStyle .= '</LineStyle>'. "\n";
      $sStyle .= '</Style>'. "\n";

      $this->styles[]=$sStyle;
   }
	/**
	 * Add element to kml file
	 */
	function addElement($sElement) {
		$this->sBody .= $sElement;
	}
	/**
	 * Print kml, change the header to open Google earth
	 */
	function export($filename) {
		if($filename) {
			$this->sName=$filename;
		}
		header('Content-type: text/xml');
		header('Content-type: application/vnd.google-earth.kml+xml');
		//header('Content-Disposition: attachement; filename="' . $this->sName . '.kml"');
		$sKml = $this->sHeader . $this->sBody . $this->sFooter;
		//header('Content-Length: ' . strlen($sKml));
		header('Expires: 0');
		header('Pragma: cache');
		header('Cache-Control: private');

		//header('Content-type: application/keyhole');
		header('Content-Disposition:atachment; filename="' . $filename. '.kml"');
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
		$myfile=$this->getCurrentDirectory() . '/output/'.$filename.".kml";

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

	function addPoint($lon, $lat, $alt, $user_options , $sLayer = '') {
//print_r($user_options); 
 //print_r($user_options); exit;
      // echo " GLENN \n";
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
		   $sResponse .= "<name>". $options['title'] ."</name>" . "\n";
      }
      if(isset($options['description'])){
		   $sResponse .= "<description><![CDATA[" . $options['description'] . "]]>" . "</description>" . "\n";
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
		$this->addElement($sResponse);
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
		$this->addElement($sResponse);
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
		$this->addElement($sResponse);
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
		$this->addElement($sResponse);
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
		$this->addElement($sResponse);
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
		$this->addElement($sResponse);
	}
}
?>
