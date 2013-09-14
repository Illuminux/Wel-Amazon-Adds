<?php
/*
 * Wel!AmazonAdds v1.3
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
 *
 * waa-webservice.php
 *
 * License:       GNU General Public License, v3
 * License URI:   http://www.gnu.org/licenses/quick-guide-gplv3
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * For details, see htp://www.welzels.de/welzoom2/
 *
 */


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
				$location = $options['location'];
			}
			$locations = WAA_location();
			extract($locations[$location]);
			if(strlen($name)<1)
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
				$src = 'http://' . $affiliate . '?' .
				       'lt1=' . $target . '&' .
				       'bc1=' . substr($borderColor,1) . '&' .
				        $imageSize . '=1&' .
				        'bg1=' . substr($backgroundColor,1) . '&' .
				        'fc1=' . substr($textColor,1) . '&' .
				        'lc1=' . substr($linkColor,1) . '&' .
				        't=' . $partnerID . '&' .
				        'o=' . $code . '&' .
				        'p=8&' .
				        ($priceIndicator==''?'':($priceIndicator . '=1&')) .
				        'l=as1&m=amazon&f=ifr&' .
				        'asins=' . $asin;
	 		
				$output = '<iframe src="'.urldecode($src).'" style="width:120px;height:240px;" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" class="WAA_enhanced"></iframe>';
		}
		else{
			$output = '<p><b>Missing parameter:</p><ul>'.$output.'</ul>';
		}
		
		return $output;
	}
	
	
	function requestIMG($instance){

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
				$location = $options['location'];
			}
			$locations = WAA_location();
			extract($locations[$location]);
			if(strlen($name)<1)
				$output .= '<li>'.__('Amazon Location is not set!','WAA').'</li>';

				
		// Partner ID
			if(is_null($partnerID))
				$partnerID = $options['partnerID'];
			if(strlen($partnerID)<1)
				$output .= '<li>'.__('Amazon Partner ID is not set!','WAA').'</li>';			
			
				
		// imageSize
/*			if(is_null($imageSize))
				$size = $options['size'];	
*/

			
		if(is_null($output)){
						
			$output = "<!-- " . $size . " -->"; //print_r($instance, true);
			
			
			switch($size){
				case('swatch'); //SwatchImage
					$height = "18";
					$format = "_SL110_";
				break;
				case('thumbnail'); // ThumbnailImage
					$height = "32";
					$format = "_SL110_";
				break;
				case('tiny'); // TinyImage
					$height = "75";
					$format = "_SL110_";
				break;
				case('medium'); // MediumImage
					$height = "160";
					$format = "_SL160_";
				break;
				case('large'); // LargeImage
					$height = "500";
					$format = "_SL160_";
				break;
				default;
					$height = "110";
					$format = "_SL110_";
				break;
			}
			
			$src  = "http://ws.assoc-amazon." . $domain . "/widgets/q?".
			        "_encoding=UTF8&".
				    "ASIN=" . $asin . "&".
				    "Format=" . $format . "&".
				    "ID=AsinImage&".
				    "MarketPlace=" . strtoupper($location) . "&".
				    "ServiceVersion=20070822&".
				    "WS=1&".
				    "tag=" . $partnerID;
				 
			$href = "http://www.amazon." . $domain . "/gp/product/" . $asin . "/ref=as_li_tf_il?".
			        "ie=UTF8&".
					"ie=UTF8&".
					"camp=1638&".
					"creative=6742&".
					"creativeASIN=" . $asin . "&".
					"linkCode=as2&".
					"tag=" . $partnerID;

			$output .= '<a href="' . $href . '" target="' . $target . '" class="WAA_image">';
			$output .= '<img src="' . $src . '" border="0" class="WAA" height="' . $height . '" class="WAA_image">';
			
			$output .= "</a>";

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