<?php

/*
 *	Variablen und Daten
 */

	// Größe der Amaozon Webservice Bilder
	function WAA_pictureSize(){
		return array('Swatch', 'Thumbnail', 'Tiny', 'small', 'Medium', 'large');
	}


// URLs zu allen Verzeichnissen
		
	function WAA_path($name){

		// wel!AmazonAdds Plugin Verzeichnis
		$dir = basename(dirname(__FILE__));
		
		// wel!AmazonAdds Plugin URL
		$url = WP_PLUGIN_URL . "/" . $dir;

		if($name == 'scriptdir'){ return $dir . "/js"; }
		else if($name == 'imagedir') { return $dir . "/images"; }
		else if($name == 'scripturl'){ return $url . "/js"; }
		else if($name == 'imageurl') { return $url . "/images"; }
		else if($name == 'url') { return $url; }
		else if($name == 'flattr') { 
			return array(
				'href' => 'http://flattr.com/thing/58329/WelAmazonAdds-for-Wordpress', 
				'src'  => 'http://api.flattr.com/button/button-static-50x60.png'
			); 
		}
		else { return $dir; }
	}


// Länder spezifische Amazon Daten

	function WAA_location(){

		return array(
			'ca' => array(
				'name'       => 'Canada',
				'code'       => 15,
				'affiliate'  => 'rcm-ca.amazon.ca/e/cm',
				'webservice' => 'ecs.amazonaws.ca'
			),
			'fr' => array(
				'name'       => 'France',
				'code'       => 8,
				'affiliate'  => 'rcm-fr.amazon.fr/e/cm',
				'webservice' => 'ecs.amazonaws.fr'
			),
			'de' => array(
				'name'       => 'Germany',
				'code'       => 3, 
				'affiliate'  => 'rcm-de.amazon.de/e/cm',
				'webservice' => 'ecs.amazonaws.de'
			),
			'jp' => array(
				'name'       => 'Japan',
				'code'       => 9,
				'affiliate'  => 'rcm-jp.amazon.co.jp/e/cm',
				'webservice' => 'ecs.amazonaws.jp'
			),
			'us' => array(
				'name'       => 'United States',
				'code'       => 1,
				'affiliate'  => 'rcm.amazon.com/e/cm',
				'webservice' => 'ecs.amazonaws.com'
			),
			'uk' => array(
				'name'       => 'United Kingdom',
				'code'       => 2,
				'affiliate'  => 'rcm-uk.amazon.co.uk/e/cm',
				'webservice' => 'ecs.amazonaws.co.uk'
			),
			'it' => array(
				'name'       => 'Italy',
				'code'       => 29,
				'affiliate'  => 'rcm-it.amazon.it/e/cm',
				'webservice' => 'ecs.amazonaws.it'
			)
		);
	}
	
	
		
// Userdaten
	function WAA_instance(){
		
		// Ermitteln der Lokatinsdaren aus dem gespeichertem lolations String
		$locationStr = get_option('WAA_location',''); 
		
		list($locationName, $locationCode, $locationAffiliate, $locationWebservice) = explode(';', $locationStr);

		$instance = array(
			'partnerID'       => get_option('WAA_partnerID',false),
			'accesKeyID'      => get_option('WAA_accesKeyID',''),
			'secretAccesKey'  => get_option('WAA_secretAccesKey',''),
			'location'        => array(
				'name'        => $locationName,
				'code'        => $locationCode, 
				'affiliate'   => $locationAffiliate, 
				'webservice'  => $locationWebservice,
				'string'      => $locationStr
			),
			'page'            => array(
				'priceIndicator'  => get_option('WAA_priceIndicatorPage',''),
				'backgroundColor' => get_option('WAA_backgroundColorPage','#FFFFFF'),
				'borderColor'     => get_option('WAA_borderColorPage','#000000'),
				'textColor'       => get_option('WAA_textColorPage','#000000'),
				'linkColor'       => get_option('WAA_linkColorPage','#0000FF'),
				'target'          => get_option('WAA_targetPage','_blank'),
				'imageSize'       => get_option('WAA_imageSizePage','IS2')
			),
			'sidebar'         => array(
				'priceIndicator'  => get_option('WAA_priceIndicatorSidebar',''),
				'backgroundColor' => get_option('WAA_backgroundColorSidebar','#FFFFFF'),
				'borderColor'     => get_option('WAA_borderColorSidebar','#000000'),
				'textColor'       => get_option('WAA_textColorSidebar','#000000'),
				'linkColor'       => get_option('WAA_linkColorSidebar','#0000FF'),
				'target'          => get_option('WAA_targetSidebar','_blank'),
				'imageSize'       => get_option('WAA_imageSizeSidebar','IS2')
			),
			'picture'         => array(
				'target'          => get_option('WAA_pictureTarget','_blank')
			)
		);
		
		if(!$instance['partnerID']){
			$instance['partnerID'] = get_option('WELAmazonAdds_partnerID','');
		}
		
		return $instance;

	}
	
	
// Übergabe der Formularfelder die innerhalb der Optionsseite gespeichert 
// werden sollden

	function WAA_pageOptions(){
		return array(
			'WAA_partnerID',
			'WAA_accesKeyID',
			'WAA_secretAccesKey',
			'WAA_location',
			'WAA_priceIndicatorPage',
			'WAA_backgroundColorPage',
			'WAA_borderColorPage',
			'WAA_textColorPage',
			'WAA_textColorPage',
			'WAA_linkColorPage',
			'WAA_targetPage',
			'WAA_imageSizePage',
			'WAA_pictureTarget'
		);
	}
	
// Support für Versionen kleiner 1.0
	function WAAoldAdds($content){
		
		// Nach Sidbar Adds suchen
		$content = preg_replace_callback( "/(<p>)?\[wABar:([^]]+)](<\/p>)?/i", "WAA_oldAddsSideBar", $content );

		// Nach Intext Adds suchen
		$content = preg_replace_callback( '/<span><img.*?src="(.*?)welAmazonAddsPreview.gif".*?\><\/span>/is', "WAA_oldAddsPage", $content );

		return $content;
	}
	
// Support für Versionen kleiner 1.0 - Seite
	function WAA_oldAddsPage($matches){
		
		$instance = WAA_instance();
		extract($instance);
		
		// Attribute austrennen 
		$attributes = array(); 
		preg_match_all('/([a-z]*?)=(".*?"|\'.*?\')/is', $matches[0], $attributes);
		
		// Attribute abspeichern 
		foreach ($attributes[1] as $key => $value) { 
			$attribute[$value] = substr($attributes[2][$key], 1, -1);
		}
		
		return '<iframe ' . 
		       'src="http://' . $location['affiliate'] . '?' .
		       'lt1=' . $page['target'] . '&' .
		       'bc1=' . substr($page['borderColor'], 1) . '&' .
		       ($page['imageSize']?$page['imageSize']:'IS1')."=1&" .
		       'bg1=' . substr($page['backgroundColor'], 1) . '&' .
		       'fc1=' . substr($page['textColor'], 1) . '&' .
		       'lc1=' . substr($page['linkColor'], 1) . '&' .
		       't=' . $partnerID . '&' .
		       'o=' . $location['code'] . '&' .
		       'p=8&l=as1&m=amazon&f=ifr&md=1M6ABJKN5YT3337HVA02&' .
		       'asins=' . $attribute['alt'] . '" ' .
		       'style="height:240px; width:120px; ' . $attribute['style'] . '" ' .
		       'frameborder="0" marginheight="0" marginwidth="0" scrolling="no" class="WAA_enhanced">' .
		       '</iframe>';
	}

// Support für Versionen kleiner 1.0 - Seiteleiste
	$WAA_oldSideBarAdds = array();
	function WAA_oldAddsSideBar($matches){
		
		global $WAA_oldSideBarAdds;

		// Whitespaces entfernen
		$matchStr = trim($matches[2]);

		// In Globales Array schreiben
		array_push($WAA_oldSideBarAdds, substr($matchStr, 0, 10));

		return '';
	}

/* 
 *	ACTIONS
 */

// Aufruf zum erzeugen der Optionsseite

	function WAAoptions(){
		add_options_page(
			'Wel!Amazon Affiliate',
			'Wel!Amazon Affiliate', 
			8, 
			basename(__FILE__),
			'WAA_options'
		);
	}
	function WAA_options(){
		require("waa-option.php");
	}
	
	function WAAenqueue(){
	
		// JavaScript
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('farbtastic');
		wp_enqueue_script('welamazonaffiliate-admin');
		
		// StyleSheet
		wp_enqueue_style('welamazonaffiliate-admin');
		wp_enqueue_style('farbtastic');
		wp_enqueue_style('jquery-ui-css');
	}

	function WAAmediaButtons(){
			
		global $post_id;
		
		if(!$_GET['action'])
			return;
		
		// HTML-Code des Mediabuttons erzeugen 
		$output  = '<a href="'.WAA_path('url').'/waa-insert.php?post_id='.$post_id.'&tab=image&TB_iframe=true" class="thickbox" title="' . __('Insert Amazon Affiliate','WAA') . '">' . 
	               '<img src="' . WAA_path('imageurl') . '/welAmazonAddsButton.png" alt="' . __('Insert Amazon Add','WAA') . '">' . 
	               '</a>';
		
		echo $output;
	}
	
	function WAAfooter(){
		
		$instance = WAA_instance();
		
		$output = "<script type=\"text/javascript\">\n" .
		          "//<![CDATA[\n" .
		          "\tvar WAA_interim = \"" . wp_create_nonce(get_bloginfo()) . "\";\n" .
		          "\tvar WAA_location = \"" . WAA_path('url') . "\";\n" .
		          "//]]>\n" . 
		          "</script>";
		
		$query = http_build_query(
			array_merge(
				array(
					'n' => wp_create_nonce(get_bloginfo())
				),
				$instance['page']
			)
		);
		
		$output = '<img src="'.WAA_path('url').'/images/empty.png?'.$query.'" src="" width="0" height="0" id="WAA_interim" />';
		
		echo $output;
	}

// Aufruf zum erzeugen des Widgets
	
	function WAAwidget(){
		require_once('waa-widget.php');
		register_widget('WAA_widget');
	}
	
	
	function WAAtinymceInit($init_array){
		
		$init_array["extended_valid_elements"] = "iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]";
		
		return $init_array;
	}
	
	
// Erzeugen der Optionsliste für die Länderauswahl
	
	function WAA_locationOptions($location){

		$output = '';

		foreach(WAA_location() as $entry){
			$value = join(';',$entry);
			$selected = ($location['string']==join(';',$entry)?' selected':'');
			$output .= '<option value="'.$value.'" '.$selected.'>'.$entry['name'].'</option>';
		}
		echo $output;
	}

?>