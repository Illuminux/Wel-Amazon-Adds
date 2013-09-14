<?php
/*
 * Wel!AmazonAdds v1.3
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
 *
 * waa-widget.php
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


class WAA_widget extends WP_Widget {
	
	// Setup Widget
	function WAA_widget(){
		
		// Load JavaScript for option page
		
			wp_register_script(
				'waa-admin',
				WAA_path('url') . "/js/waa-admin.js",
				array(
					'jquery', 
					'jquery-ui-core',
					'jquery-ui-sortable',
					'jquery-ui-accordion',
					'farbtastic'),
				'1.3'
			);
			
			
		// Wel!Amazon Affiliate StyleSheet für Admin
			
			wp_register_style(
				'waa-admin',
				WAA_path('url') . "/css/waa-admin.css",
				array(
					'farbtastic',
					'jquery-ui-css'),
				'1.3',
				'screen'
			);

			
			if(is_admin()){
				wp_enqueue_script('waa-admin');			
				wp_enqueue_style('waa-admin');
			}
			
		
		// Widget Einstellungen 
			$widget_ops = array(
				'classname'   => __('wwa_widget', 'WAA'),
				'description' => __('Embeds Amazon links to Sidebar', 'WAA')
			);
				
		// Widget kontroll Einstellungen
			$control_ops = array( 
				'width' => 400,
				'id_base' => 'waa-link' 
			);
			
		// Widget erzeugen
			$this->WP_Widget(
				'waa-link',
				'Wel!Amazon Affiliate', 
				$widget_ops, 
				$control_ops
			);
	}
	
	// Anzeigen des Widgets
	function widget( $args, $instance ) {
		
		extract( $args );
		
		global $WAA_oldSideBarAdds;
		
		$requestUrl = WAA_path('url') . '/waa-webservice.php';
		
		$WAA_nonce = wp_create_nonce(get_bloginfo());
		
		require_once('waa-webservice.php');
		
			$output ="";
		
		// Widget content
			
			// Display Amazon Enhanced Links

				// Filter für Enhanced Links im Text anwenden
				$postID = get_the_id();
				$widgetID = str_replace('waa-link-', '', $widget_id);
				
				$asins = array();
				$subasins = array();
				
				if($_SERVER['REQUEST_URI']!=COOKIEPATH){
				
					if($postMeta = get_post_meta($postID, '_WAA_asin_enhanced_'.$widgetID))
						$asins = explode("\n", is_array($postMeta)?implode('\n', $postMeta):'');
						
					if(count($WAA_oldSideBarAdds)>0){
						$asins = array_merge($WAA_oldSideBarAdds, $asins);
						$WAA_oldSideBarAdds = array();
					}
				}
				
				// Wenn Enhanced Links definiert sind
				if(strlen($instance['enhancedDefaults'])>0){
					
					// Anzeige auf allen Seiten
					if($instance['enhancedDisplay']=='all'){
						$subasins = explode("\n", $instance['enhancedDefaults']);
					}
					// Anzeige auf der Start Seite
					else if($instance['enhancedDisplay']=='start' && $_SERVER['REQUEST_URI']==COOKIEPATH){
						$subasins = explode("\n", $instance['enhancedDefaults']);
					}
					// Anzeige auf Seiten ohne eigene Adds
					else if($instance['enhancedDisplay']=='blank' && count($asins)==0){
						$subasins = explode("\n", $instance['enhancedDefaults']);
					}
					
					$asins = array_merge($asins, $subasins);
				}
				
				// Alle Enhanced Links vom Webservice anfordern
				foreach($asins as $entry){
					
					$asin = explode(';', $entry);
					
					$request = array_merge(
						$instance, 
						array(
							'asin' => $asin[0],
							'waa_nonce' => wp_create_nonce(get_bloginfo())
						)
					);
					
					$enhancedHTML = str_replace('style="width:120px;height:240px;"', 'class="'.$instance['enhancedCSS'].' aligncenter" style="'.$instance['enhancedStyle'].'"', requestAdd($request));
					
					if(count($asin)>1){
						$enhancedHTML = str_replace('></', ' title="' . implode(',', $asin) . '"></', $enhancedHTML);
					}
					
					$output .= '<div class="WAA_enhancedViewSideBar">';
					$output .= $enhancedHTML;
					$output .= '</div>';
				}
				
				
			// Display Amazon Image Links
				
				// Filter für Image Links im Text anwenden
				$asins = array();
				$subasins = array();
				
				if($_SERVER['REQUEST_URI']!=COOKIEPATH){
				
					if($postMeta = get_post_meta($postID, '_WAA_asin_image_'.$widgetID))
						$asins = explode("\n", is_array($postMeta)?implode('\n', $postMeta):'');
				}
			
				// Wenn Enhanced Links definiert sind
				if(strlen($instance['ImageDefaults'])>0){
					
					if($instance['ImageDisplay']=='all'){
						$subasins = explode("\n", $instance['ImageDefaults']);
					}
					else if($instance['ImageDisplay']=='start' && $_SERVER['REQUEST_URI']==COOKIEPATH){
						$subasins = explode("\n", $instance['ImageDefaults']);
					}
					else if($instance['ImageDisplay']=='blank' && count($asins)==0){
						$subasins = explode("\n", $instance['ImageDefaults']);
					}
						
					$asins = array_merge($asins, $subasins);
				}
				
				$output .= count($asins)>0?'<div class="WAA_imageViewSideBar">':'';
				
				// Alle Enhanced Links vom Webservice anfordern
				foreach($asins as $entry){
					
					$asin = explode(';', $entry);
					if(count($asin)==1){
						$asin = explode(',', $entry);
					}
					
					$request = array_merge(
						$instance, 
						array(
							'asin' => $asin[0],
							'waa_nonce' => wp_create_nonce(get_bloginfo()),
							'size' => $instance['imageDefaultsSize']
						)
					);
					
					$imageHTML = str_replace('class="WAA"', 'class="'.$instance['imageCSS'].'" style="'.$instance['imageStyle'].'"', requestIMG($request));
					
					if(count($asin)>1){
						$imageHTML = str_replace('><img', ' type="' . $entry . '"><img', $imageHTML);
					}
					
					$output .= $imageHTML;
				}
				
				$output .= count($asins)>0?'</div>':'';
		
		
			if( strlen($output) > 0 ) {
				
				// Before widget (defined by themes). 
					echo $before_widget;
					
				// Widget Title
					if(strlen($instance['title']) > 0)
						echo $before_title . $instance['title'] . $after_title;
						
				// output sidebar widget
					echo $output;
					
				// After widget (defined by themes).
					echo $after_widget;
			}
	}
	
	
	# Widget Optionen erzeugen 
	function form($instance) {
	
		// Laden der Benutzerangaben

			$plugin_option = WAA_instance();
			extract($plugin_option);


		// Testen ob notwendige Benutzerparameter eingestell sind

			$output = null;

			if(strlen($partnerID)<1)
				$output .= '<li>'.__('Amazon Partner ID is not set!','WAA').'</li>';

			if(strlen($location['string'])<1)
				$output .= '<li>'.__('Amazon Location is not set!','WAA').'</li>';


		// Ausgabe
		
			if(is_null($output)){

				if(is_null($instance['title']))
					$defaults['title'] = __('Amazon Affiliate','WAA');
				if(is_null($instance['backgroundColor']))
					$defaults['backgroundColor'] = $sidebar['backgroundColor'];
				if(is_null($instance['borderColor']))
					$defaults['borderColor'] = $sidebar['borderColor'];
				if(is_null($instance['textColor']))
					$defaults['textColor'] = $sidebar['textColor'];
				if(is_null($instance['linkColor']))
					$defaults['linkColor'] = $sidebar['linkColor'];
				if(is_null($instance['priceIndicator']))
					$defaults['priceIndicator'] = $sidebar['priceIndicator'];
				if(is_null($instance['target']))
					$defaults['target'] = $sidebar['target'];
				if(is_null($instance['imageSize']))
					$defaults['imageSize'] = $sidebar['imageSize'];
				if(is_null($instance['enhancedCSS']))
					$defaults['enhancedCSS'] = 'WAA_enhanced';
				if(is_null($instance['enhancedStyle']))
					$instance['enhancedStyle'] = 'width:120px; height:240px;';
				if(is_null($instance['imageCSS']))
					$defaults['imageCSS'] = 'WAA';

				$instance = wp_parse_args((array)$instance, $defaults);

?>

<p><strong>Widget WAA <? echo $this->number ?></strong></p>
<div id="<? _e($this->get_field_id('options'));?>">
	<h3><a href="#"><? _e('Enhanced Links Layout','WAA'); ?></a></h3>
	<div>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
				<tr>
					<td width="50%">
						<p>
							<label for="<? _e($this->get_field_id('title'));?>"><? _e('Title:','WAA'); ?></label><br />
							<input name="<? _e($this->get_field_name('title'));?>" id="<? _e($this->get_field_id('title'));?>" value="<? echo $instance['title']; ?>" type="text" class="widefat" />
						</p>
						<table width="100%" cellspacing="0" cellpadding="0">
							<tbody>
								<tr>
									<td>
										<p><label for="<? _e($this->get_field_id('backgroundColor'));?>"><? _e('Background Color:', 'WAA'); ?></label></p>
									</td>
									<td class="alignright">
										<p><input name="<? _e($this->get_field_name('backgroundColor'));?>" id="<? _e($this->get_field_id('backgroundColor'));?>" type="text" value="<? echo $instance['backgroundColor']; ?>" class="WAA_colorpicker" /></p>
									</td>
								</tr>
								<tr>
									<td>
										<p><label for="<? _e($this->get_field_id('borderColor'));?>"><? _e('Border Color:', 'WAA'); ?></label></p>
									</td>
									<td class="alignright">
										<p><input name="<? _e($this->get_field_name('borderColor'));?>" id="<? _e($this->get_field_id('borderColor'));?>" type="text" value="<? echo $instance['borderColor']; ?>" class="WAA_colorpicker" /></p>
									</td>
								</tr>
								<tr>
									<td>
										<p><label for="<? _e($this->get_field_id('textColor'));?>"><? _e('Text Color:', 'WAA'); ?></label></p>
									</td>
									<td class="alignright">
										<p><input name="<? _e($this->get_field_name('textColor'));?>" id="<? _e($this->get_field_id('textColor'));?>" type="text" value="<? echo $instance['textColor']; ?>" class="WAA_colorpicker" /></p>
									</td>
								</tr>
								<tr>
									<td>
										<p><label for="<? _e($this->get_field_id('linkColor'));?>"><? _e('Link Color:', 'WAA'); ?></label></p>
									</td>
									<td class="alignright">
										<p><input name="<? _e($this->get_field_name('linkColor'));?>" id="<? _e($this->get_field_id('linkColor'));?>" type="text" value="<? echo $instance['linkColor']; ?>" class="WAA_colorpicker" /></p>
									</td>
								</tr>
							</tbody>
						</table>
						<p>
							<label for="<? _e($this->get_field_id('priceIndicator'));?>"><? _e('Price Indicator:', 'WAA'); ?></label>
							<select name="<? _e($this->get_field_name('priceIndicator'));?>" id="<? _e($this->get_field_id('priceIndicator'));?>" class="widefat">
								<option value=""    <? echo ($instance['priceIndicator']==''    ?'selected':''); ?> ><? _e('Show all prices', 'WAA'); ?></option>
								<option value="nou" <? echo ($instance['priceIndicator']=='nou' ?'selected':''); ?> ><? _e('Only new prices', 'WAA'); ?></option>
								<option value="npa" <? echo ($instance['priceIndicator']=='npa' ?'selected':''); ?> ><? _e('Do not show prices', 'WAA'); ?></option>
							</select>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('target'));?>"><? _e('Target Window:', 'WAA'); ?></label><br />
							<select name="<? _e($this->get_field_name('target'));?>" id="<? _e($this->get_field_id('target'));?>" class="widefat">
								<option <? echo ($instance['target']=='_blank'?'selected ':''); ?> value="_blank"><? _e('Open link in a new window','WAA') ?></option>
								<option <? echo ($instance['target']=='_top'?'selected ':''); ?> value="_top"><? _e('Open link in the same window','WAA') ?></option>
							</select>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('imageSize'));?>"><? _e('Image Size:', 'WAA'); ?></label><br />
							<select name="<? _e($this->get_field_name('imageSize'));?>" id="<? _e($this->get_field_id('imageSize'));?>" class="widefat">
								<option <? echo ($instance['imageSize']=='IS2'?'selected ':''); ?> value="IS2"><? _e('Use large image','WAA') ?></option>
								<option <? echo ($instance['imageSize']=='IS1'?'selected ':''); ?> value="IS1"><? _e('Use small image','WAA') ?></option>
							</select>
						</p>
					</td>
					<td width="50%">
						<div id="<? echo $this->get_field_id('preview');?>" class="WAA_preView alignright"></div>
<!--						
						<div style="clear:both;"><a href="<? $flattr = WAA_path('flattr'); echo $flattr['href']; ?>"><img src="<? $flattr = WAA_path('flattr'); echo $flattr['src']; ?>" width="50" height="60" alt="Flattr this" class="alignright" style="margin-top:30px;" /></a></div>
-->
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<h3><a href="#"><? _e('Default Enhanced Links','WAA'); ?></a></h3>
	<div>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
				<tr valign="top">
					<td width="50%">
						<p>
							<label for="<? _e($this->get_field_id('enhancedDisplay'));?>"><? _e('Display Links:', 'WAA'); ?></label><br />
							<select name="<? _e($this->get_field_name('enhancedDisplay'));?>" id="<? _e($this->get_field_id('enhancedDisplay'));?>" class="widefat">
								<option value="start" <? echo ($instance['enhancedDisplay']=='start'?'selected ':''); ?>><? _e('On the start page','WAA');?></option>
								<option value="blank" <? echo ($instance['enhancedDisplay']=='blank'?'selected ':''); ?> ><? _e('On the side without a link','WAA');?></option>
								<option value="all"   <? echo ($instance['enhancedDisplay']=='all'?'selected ':''); ?> ><? _e('On all sides','WAA');?></option>
							</select>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('enhancedDefaults'));?>"><? _e('Defaults (ASIN):', 'WAA'); ?></label><br />
							<textarea name="<? _e($this->get_field_name('enhancedDefaults'));?>" id="<? _e($this->get_field_id('enhancedDefaults'));?>" wrap="off" class="widefat" rows="4"><? echo $instance['enhancedDefaults'] ?></textarea>
							<span class="description"><? _e('Use line feed (enter) to separate the ASIN links.','WAA')?></span>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('enhancedCSS'));?>"><? _e('CSS-Class:', 'WAA'); ?></label><br />
							<input type="text" name="<? _e($this->get_field_name('enhancedCSS')); ?>" id="<? _e($this->get_field_id('enhancedCSS')); ?>" value="<? echo $instance['enhancedCSS'] ?>"  class="widefat" />
						</p>
						<p>
							<label for="<? _e($this->get_field_id('enhancedStyle'));?>"><? _e('CSS-Style:', 'WAA'); ?></label><br />
							<textarea name="<? _e($this->get_field_name('enhancedStyle'));?>" id="<? _e($this->get_field_id('enhancedStyle'));?>" class="widefat" rows="4"><? echo $instance['enhancedStyle'] ?></textarea>
						</p>
					</td>
					<td width="50%">
						<div id="<? _e($this->get_field_id('enhancedPreview'));?>" class="WAA_enhancedPreview"></div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<h3><a href="#"><? _e('Default Image Links','WAA'); ?></a></h3>
	<div>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
				<tr valign="top">
					<td width="50%">
						<p>
							<label for="<? _e($this->get_field_id('ImageDisplay'));?>"><? _e('Display Images:', 'WAA'); ?></label><br />
							<select name="<? _e($this->get_field_name('ImageDisplay'));?>" id="<? _e($this->get_field_id('ImageDisplay'));?>" class="widefat">
								<option value="start" <? echo ($instance['ImageDisplay']=='start'?'selected ':''); ?>><? _e('On the start page','WAA');?></option>
								<option value="blank" <? echo ($instance['ImageDisplay']=='blank'?'selected ':''); ?> ><? _e('On the side without a link','WAA');?></option>
								<option value="all"   <? echo ($instance['ImageDisplay']=='all'?'selected ':''); ?> ><? _e('On all sides','WAA');?></option>
							</select>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('ImageDefaults'));?>"><? _e('Defaults (ASIN):', 'WAA'); ?></label><br />
							<textarea name="<? _e($this->get_field_name('ImageDefaults'));?>" id="<? _e($this->get_field_id('ImageDefaults'));?>" wrap="off" class="widefat" rows="4"><? echo $instance['ImageDefaults'] ?></textarea>
							<span class="description"><? _e('Use line feed (enter) to separate the ASIN links.','WAA')?></span>
						</p>
						<p>
							<input type="checkbox" name="<? _e($this->get_field_name('ImageFloat'));?>" id="<? _e($this->get_field_id('ImageFloat'));?>" <? echo isset($instance['ImageFloat'])?'checked':''; ?> /><label for="<? _e($this->get_field_id('ImageFloat'));?>"> <? _e('Images in vertical order','WAA'); ?></label>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('imageDefaultsSize'));?>"><? _e('Image Size:', 'WAA'); ?></label><br />
							<select name="<? _e($this->get_field_name('imageDefaultsSize'));?>" id="<? _e($this->get_field_id('imageDefaultsSize'));?>" class="widefat">
								<option  <? echo ($instance['imageDefaultsSize']=='small'?'selected ':''); ?> value="small"><? _e('Small image','WAA'); ?></option>
								<option  <? echo ($instance['imageDefaultsSize']=='swatch'?'selected ':''); ?> value="swatch"><? _e('Swatch image','WAA'); ?></option>
								<option  <? echo ($instance['imageDefaultsSize']=='thumbnail'?'selected ':''); ?> value="thumbnail"><? _e('Thumbnail image','WAA'); ?></option>
								<option  <? echo ($instance['imageDefaultsSize']=='tiny'?'selected ':''); ?> value="tiny"><? _e('Tiny image','WAA'); ?></option>
								<option  <? echo ($instance['imageDefaultsSize']=='medium'?'selected ':''); ?> value="medium"><? _e('Medium image','WAA'); ?></option>
							</select>
						</p>
						<p>
							<label for="<? _e($this->get_field_id('imageCSS'));?>"><? _e('CSS-Class:', 'WAA'); ?></label><br />
							<input type="text" name="<? _e($this->get_field_name('imageCSS')); ?>" id="<? _e($this->get_field_id('imageCSS')); ?>" value="<? echo $instance['imageCSS'] ?>"  class="widefat" />
						</p>
						<p>
							<label for="<? _e($this->get_field_id('imageStyle'));?>"><? _e('CSS-Style:', 'WAA'); ?></label><br />
							<textarea name="<? _e($this->get_field_name('imageStyle'));?>" id="<? _e($this->get_field_id('imageStyle'));?>" class="widefat" rows="4"><? echo $instance['imageStyle'] ?></textarea>
						</p>
					</td>
					<td width="50%">
						<div id="<? _e($this->get_field_id('imagePreview'));?>" class="WAA_imagePreview"></div>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="<? _e($this->get_field_name('nonce'));?>" id="<? _e($this->get_field_id('nonce'));?>" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
		<img src="<? echo WAA_path('imageurl') ?>/empty.png" onload="jQuery(this).widgetSetup('<? echo $this->get_field_id(''); ?>');" />
	</div>
</div>
<?
		}
		else{
			$output = '<h3 class="ul-disc">'.__('Missing parameter','WAA').'</h3>' .
			          '<ul>' . $output . '</ul>';
			echo $output;
		}
	}
}
?>