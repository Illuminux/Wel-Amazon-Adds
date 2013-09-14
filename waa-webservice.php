<?

/*
 * Load WordPress Administration Bootstrap
 */
	if(file_exists('../../../wp-load.php'))
		require_once("../../../wp-load.php");
	else if(file_exists('../../wp-load.php'))
		require_once("../../wp-load.php");
	else if(file_exists('../wp-load.php'))
		require_once("../wp-load.php");
	else if(file_exists('wp-load.php'))
		require_once("wp-load.php");
	else if(file_exists('../../../../wp-load.php'))
		require_once("../../../../wp-load.php");
	else if(file_exists('../../../../wp-load.php'))
		require_once("../../../../wp-load.php");
	else {
		if(file_exists('../../../wp-config.php'))
			require_once("../../../wp-config.php");
		else if(file_exists('../../wp-config.php'))
			require_once("../../wp-config.php");
		else if(file_exists('../wp-config.php'))
			require_once("../wp-config.php");
		else if(file_exists('wp-config.php'))
			require_once("wp-config.php");
		else if(file_exists('../../../../wp-config.php'))
			require_once("../../../../wp-config.php");
		else if(file_exists('../../../../wp-config.php'))
			require_once("../../../../wp-config.php");
		else {
			echo '<p>Failed to load bootstrap.</p>';
			exit;
		}
	}



/*
 *	Load WAA Functions
 */
	require_once("waa-functions.php");
	
/*
 *	Functions
 */
	function requestAWS($instance){

		// ermitteln der Benutzerdaten und speichern in die entsprechenden 
		// Variablen
			extract($instance);
			
			list($location_name,$location_code,$location_affiliate,$location_aws) = explode(';',$location);
			
			// erzeugen der Anfrage
				$queryString = 'AWSAccessKeyId=' . $accesKeyID . '&' .
							   'AssociateTag=' . $partnerID . '&' .
							   'ItemId=' . trim($asin) . '&' .
							   'Operation=ItemLookup&' .
							   'ResponseGroup=Small,Images&' .
							   'Service=AWSECommerceService&' .
							   'Timestamp=' . gmdate("Y-m-d\TH:i:s\Z") . '&' .
							   'Version=2009-11-01';

			// in GET string wandeln
				$queryString = str_replace(':', '%3A', str_replace(',', '%2C', $queryString));


			// Amazon XML Verzeichnis
				$uri = '/onca/xml';


			// Signatur erzeugen
				$signatureStr = "GET\n" . 
							  	$location_aws . "\n" . 
							  	$uri . "\n" . 
							  	$queryString;


			// Signatur codieren
				$signature = base64_encode(hash_hmac("sha256", $signatureStr, $secretAccesKey, true));


			// Anfrage string erzeugen
				$request = 'http://' . $location_aws . $uri . '?' . $queryString . '&Signature=' . urlencode($signature);
				
			$xml = simplexml_load_file($request);

			return $xml;
	}
	
	
	function requestAdd($instance){

		// die Ausgabe
			$output = null;
		
		// ermitteln der Benutzerdaten und speichern in die entsprechenden 
		// Variablen
			extract($instance);
			$options = WAA_instance();
			
		// Berechtigung prüfen
			if(!wp_verify_nonce($waa_nonce, get_bloginfo())){
				die('Busted!');
			}

		// Amazon Server Namen
			if(is_null($location)){
				$location = $options['location']['string'];
			}
			list($location_name,$location_code,$location_affiliate) = explode(';',$location);
			if(strlen($location_name)<1)
				$output .= '<li>'.__('Amazon Location is not set!','WAA').'</li>';
				
		// Partner ID
			if(is_null($partnerID))
				$partnerID = $options['partnerID'];
			if(strlen($partnerID)<1)
				$output .= '<li>'.__('Amazon Partner ID is not set!','WAA').'</li>';
				
		// backgroundColor
		 	if(is_null($backgroundColor))
				$backgroundColor = $options[$position]['backgroundColor'];
				
		// linkColor
			if(is_null($linkColor))
				$linkColor = $options[$position]['linkColor'];
				
		// textColor
			if(is_null($textColor))
				$textColor = $options[$position]['textColor'];
				
		// borderColor
			if(is_null($borderColor))
				$borderColor = $options[$position]['borderColor'];
				
		// target
			if(is_null($target))
				$target = $options[$position]['target'];
				
		// imageSize
			if(is_null($imageSize))
				$imageSize = $options[$position]['imageSize'];
				
		// priceIndicator
			if(is_null($priceIndicator))
				$priceIndicator = $options[$position]['priceIndicator'];
		
		if(is_null($output)){
							
			// Amazon Grafik & Text src
				$src = 'http://' . $location_affiliate . '?' .
				       'lt1=' . $target . '&' .
				       'bc1=' . substr($borderColor,1) . '&' .
				        $imageSize . '=1&' .
				        'bg1=' . substr($backgroundColor,1) . '&' .
				        'fc1=' . substr($textColor,1) . '&' .
				        'lc1=' . substr($linkColor,1) . '&' .
				        't=' . $partnerID . '&' .
				        'o=' . $location_code . '&' .
				        'p=8&' .
				        ($priceIndicator==''?'':($priceIndicator . '=1&')) .
				        'l=as1&m=amazon&f=ifr&' .
				        'asins=' . $asin;
	 		
				$output = '<iframe src="'.urldecode($src).'" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0"></iframe>';
		}
		else{
			$output = '<p><b>Missing parameter:</p><ul>'.$output.'</ul>';
		}
		
		return $output;
	}
	
	
	function requestIMG($instance){
		
		
		if(!function_exists('simplexml_load_file')) {
			
			$errorStr = "<p><b>The PHP-Function simplexml_load_file is not available.</b></p>\n"
			          . "<p>This Function is include since PHP 5.1.0 and Libxml 2.6.0.</p>\n"
			          . "<p>Your curren PHP version is ".phpversion()."!</p>";
			
			return $errorStr;
		}
		
		// Die Ausgabe
			$output = null;

		// ermitteln der Benutzerdaten und speichern in die entsprechenden 
		// Variablen
			extract($instance);
			$options = WAA_instance();
//		return 'clt_nonce: ' . $waa_nonce . ' srv_nonce: ' . wp_create_nonce(get_bloginfo());
		// Berechtigung prüfen
			if(!wp_verify_nonce($waa_nonce, get_bloginfo())){
				die('Busted!');
			}
			
			
		// Amazon Server Namen
			if(is_null($location)){
				$location = $instance['location'] = $options['location']['string'];
			}
			list($location_name,$location_code,$location_affiliate,$location_aws) = explode(';',$location);
			if(strlen($location_name)<1)
				$output .= '<li>'.__('Amazon Location is not set!','WAA').'</li>';
				
		// Partner ID
			if(is_null($partnerID))
				$partnerID = $instance['partnerID'] = $options['partnerID'];
			if(strlen($partnerID)<1)
				$output .= '<li>'.__('Amazon Partner ID is not set!','WAA').'</li>';
				
		// Access Key 
			if(is_null($accesKeyID))
				$accesKeyID = $instance['accesKeyID'] = $options['accesKeyID'];
			if(strlen($accesKeyID)<1)
				$output .= '<li>'.__('Amazon Acces Key is not set!','WAA').'</li>';
		
		// Secret Acces Key
			if(is_null($secretAccesKey))
				$secretAccesKey = $instance['secretAccesKey'] = $options['secretAccesKey'];
			if(strlen($secretAccesKey)<1)
				$output .= '<li>'.__('Amazon Secret Acces Key is not set!','WAA').'</li>';
			
			
		if(is_null($output)){
			
			$xml = requestAWS($instance);
			
			$Item = $xml->Items->Item;

			$DetailPageURL = $Item->DetailPageURL;

			$option = explode(',',$option);

			$output = '<a href="'.urldecode($DetailPageURL).'" title="'.$Item->ItemAttributes->Title.' - '.$Item->ItemAttributes->Author.'" target="'.$target.'" class="WAA_image">';

			switch($size){
				case('swatch'); //SwatchImage
					$image = $Item->ImageSets->ImageSet->SwatchImage;
				break;
				case('thumbnail'); // ThumbnailImage
					$image = $Item->ImageSets->ImageSet->ThumbnailImage;
				break;
				case('tiny'); // TinyImage
					$image = $Item->ImageSets->ImageSet->TinyImage;
				break;
				case('medium'); // MediumImage
					$image = $Item->ImageSets->ImageSet->MediumImage;
				break;
				case('large'); // LargeImage
					$image = $Item->ImageSets->ImageSet->LargeImage;
				break;
				default;
					$image = $Item->ImageSets->ImageSet->SmallImage;
				break;
			}

			$output .= '<img src="'.$image->URL.'" width="'.$image->Width.'" height="'.$image->Height.'" class="WAA" />';
			$output .= '</a>';
			
			if(count($image)==0){
				
				$output = '<div class="ui-widget" style="margin:2px 0">' .
				          '<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">' .
				          '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' .
				          '<strong>Wrong ASIN:</strong> ' . $asin .
				          '</div>' .
				          '</div>';
			}
		}
		else{
			$output = '<p><b>'.__('Missing parameter','WAA').':</p><ul>'.$output.'</ul>';
		}

		return $output;

	}
	
	// ermitteln der POST parameter
	extract($_POST);
	
	if($asin){

		$instance = WAA_instance();

		if($type == 'add'){
			header("Content-Type: text/html; charset=UTF-8");
			$htm = requestAdd($_POST);
			
			print $htm;
		}
		// Anforderung Amazon Grafik und Text als HTML
		else if($type == 'enhanced'){
			header("Content-Type: text/html; charset=UTF-8");
			$htm = requestEnhanced($_POST);
			
			print $htm;
		}
		// Anforderung Bild
		else if($type == 'image'){
			header("Content-Type: text/html; charset=UTF-8");
			$htm = requestIMG($_POST);
			
			print $htm;
		}
		else {
			echo __('Type is not set!','WAA');
		}
	}
	else if($type == 'sidebar') {
		
		if(!wp_verify_nonce($WAA_nonce, get_bloginfo())){
			die('Busted!');
		}
		
		$output = 'error';
		
		header("Content-Type: text/html; charset=UTF-8");
		
		$meta_keys = array_keys($enhanced);
		
		foreach($enhanced as $entry){
			
			$meta_key = "_" . current($meta_keys);
			
			$old_meta = get_post_meta($post_id, $meta_key, true);
			
			if(strlen($entry)>=10){
								
				if($old_meta){
					update_post_meta($post_id, $meta_key, $entry);
				}
				else{
					add_post_meta($post_id, $meta_key, $entry);
				}
				
				$output = 'success';
			}
			else {
				if($old_meta){
					delete_post_meta($post_id, $meta_key);
				}
			}
			
			next($meta_keys);
		}
		
		$meta_keys = array_keys($images);
		
		foreach($images as $entry){
			
			$meta_key = "_" . current($meta_keys);
			
			$old_meta = get_post_meta($post_id, $meta_key, true);
			
			if(strlen($entry)>=10){
				
				if($old_meta){
					update_post_meta($post_id, $meta_key, $entry);
				}
				else{
					add_post_meta($post_id, $meta_key, $entry);
				}
				
				$output = 'success';
			}
			else {
				if($old_meta){
					delete_post_meta($post_id, $meta_key);
				}
			}
			
			next($meta_keys);
		}
		
		print $output;
	}
	else if(!isset($WAA_nonce)) {
		echo __('Missing parameter','WAA');
	}

?>