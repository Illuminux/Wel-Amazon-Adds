<?php
/*
 * Wel!AmazonAdds v1.3
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
 *
 * waa-insert.php
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


// Load WordPress Administration Bootstrap
require_once('../../../wp-admin/admin.php');
require_once('waa-functions.php');


// User Managemant
if (!current_user_can('edit_pages') || !current_user_can('edit_posts'))
	wp_die(__('You do not have permission to edit.', 'WAA'));


// Laden der Benutzerparameter
$instance = WAA_instance();
extract($instance);


// load_plugin_textdomain('welAmazonAdds', WP_PLUGIN_URL.'/welAmazonAdds/I18n/', 'welAmazonAdds/I18n/');
load_plugin_textdomain('WAA', WAA_path('url').'/I18n/', 'welamazonadds/I18n/');


// JavaScript laden

	// Wel!Amazon Affiliate JavaScript für Seiten
	wp_register_script(
		'waa-admin',
		WAA_path('url') . "/js/waa-admin.js",
		array('jquery'),
		'1.3'
	);

	wp_enqueue_script('common');
	wp_enqueue_script('waa-admin');


// Style-Sheet laden
wp_enqueue_style('global');
wp_enqueue_style('wp-admin');
wp_enqueue_style('colors');
wp_enqueue_style('media');
wp_enqueue_style('editimage');


// Header erstellen/senden
@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));


// Testen ob notwendige Benutzerparameter eingestell sind

$requireOut = null;
if(strlen($partnerID)<1){
	$requireOut .= '<li class="toclevel-1">'.__('Amazon Partner ID is not set!','WAA').'</li>';
}
if(strlen($location['string'])<1){
	$requireOut .= '<li>'.__('Amazon Location is not set!','WAA').'</li>';
}
if(!is_null($requireOut))		
	wp_die(__('Missing parameter:', 'WAA') . $requireOut);


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <? do_action('admin_xml_ns'); ?> <? language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<? bloginfo('html_type'); ?>; charset=<? echo get_option('blog_charset'); ?>" />
	<title><? bloginfo('name') ?> &rsaquo; <? _e('Uploads', 'WAA'); ?> &#8212; <? _e('WordPress', 'WAA'); ?></title>

<?php
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');
?>
	
</head>
<body id="media-upload">
	<div id="media-upload-header">
		<ul id="sidemenu">
			<li id="tab-picture-page">
				<a href="waa-insert.php?post_id=<? echo $_GET['post_id']; ?>&tab=image" class="<? echo $_GET['tab']=='image'?'current':''; ?>"><? _e('Image Links', 'WAA'); ?></a>
			</li>
			<li id="tab-link-page">
				<a href="waa-insert.php?post_id=<? echo $_GET['post_id']; ?>&tab=enhanced" class="<? echo $_GET['tab']=='enhanced'?'current':''; ?>"><? _e('Enhanced Links', 'WAA'); ?></a>
			</li>
			<li id="tab-link-sidebar">
				<a href="waa-insert.php?post_id=<? echo $_GET['post_id']; ?>&tab=sidebar" class="<? echo $_GET['tab']=='sidebar'?'current':''; ?>"><? _e('Sidebar Links', 'WAA'); ?></a>
			</li>
		</ul>
	</div>
	<div>
		<form class="media-upload-form type-form validate">

<?php if($_GET['tab']=='image'): ?>
			<h3 class="media-title"><? _e('Image Links', 'WAA'); ?></h3>
			<div id="WAA_pageAdd" class="media-items">
				<div class="media-item media-blank">
					<table class="describe">
						<tbody>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<span class="alignleft"><label for="asin"><? _e('ASIN', 'WAA'); ?></label></span>
									<span class="alignright"><abbr id="status_asin" title="required" class="required">*</abbr></span>
								</th>
								<td class="field">
									<input id="asin" name="asin" value="" type="text" aria-required="true" style="width:410px;" />
									<input type="button" value="<? _e('Load','WAA'); ?>" class="button" /><br >
									<div style="width: 410px;"><span class="description"><? _e('To randomise a single link, type several ASINS seperated by a coma or for toggle by a semicolon.', 'WAA'); ?></span></div>
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="size"><? _e('Image Size', 'WAA'); ?></label><br />
								</th>
								<td class="field">
									<select name="size" id="size">
										<option value="small"><? _e('Small image','WAA'); ?></option>
<!--
										<option value="swatch"><? _e('Swatch image','WAA'); ?></option>
										<option value="thumbnail"><? _e('Thumbnail image','WAA'); ?></option>
										<option value="tiny"><? _e('Tiny image','WAA'); ?></option>
-->
										<option value="medium"><? _e('Medium image','WAA'); ?></option>
<!--
										<option value="large"><? _e('Large image','WAA'); ?></option>
-->
									</select>
									<span id="WAA_dimension">
										<label for="width"><? _e('Width','WAA'); ?></label>
										<input type="text" maxlength="5" id="width" name="width" value="" size="5" style="width:36px;" />
										<label for="height"><? _e('Height','WAA'); ?></label>
										<input type="text" maxlength="5" id="height" name="height" value="" size="5" style="width:36px;" />
										<button id="resize" name="resize" value="" class="button"><? _e('Original Size', 'WAA'); ?></button>
									</span>
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="alt"><? _e('Alternate Text', 'WAA'); ?></label>
								</th>
								<td class="field">
									<input id="alt" name="alt" value="" type="text" />
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="href"><? _e('Link URL', 'WAA'); ?></label>
								</th>
								<td class="field">
									<input id="href" name="href" value="" type="text" readonly />
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="title"><? _e('Link Title', 'WAA'); ?></label>
								</th>
								<td class="field">
									<input id="title" name="title" value="" type="text" />
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="class"><? _e('CSS Class', 'WAA'); ?></label>
								</th>
								<td class="field">
									<input id="class" name="class" value="WAA" type="text" />
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="style"><? _e('Style', 'WAA'); ?></label>
								</th>
								<td class="field">
									<input id="style" name="style" value="" type="text" />
								</td>
							</tr>
							<tr class="align">
								<th valign="top" scope="row" class="label"><p><label for="align"><? _e('Alignment', 'WAA'); ?></label></p></th>
								<td class="field">
									<input name="align" id="align-none" value="none" type="radio" checked="checked" />
									<label for="align-none" class="align image-align-none-label"><? _e('None', 'WAA'); ?></label>
									<input name="align" id="align-left" value="left" type="radio" />
									<label for="align-left" class="align image-align-left-label"><? _e('Left', 'WAA'); ?></label>
									<input name="align" id="align-center" value="center" type="radio" />
									<label for="align-center" class="align image-align-center-label"><? _e('Center', 'WAA'); ?></label>
									<input name="align" id="align-right" value="right" type="radio" />
									<label for="align-right" class="align image-align-right-label"><? _e('Right', 'WAA'); ?></label>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div class="show-align" id="preview" style="height: 12em; overflow: hidden; background-color: #F1F1F1;">
										<span id="img_demo_txt" style="font-family:Monaco, 'Courier New', Courier; font-size: 9px; color: #888;">
											<img class="WAA_image" style="display:none;"/>
											Lorem ipsum dolor sit amet consectetuer velit pretium euismod ipsum enim. Mi cursus at a mollis senectus id arcu gravida quis urna. Sed et felis id tempus Morbi mauris tincidunt enim In mauris. Pede eu risus velit libero natoque enim lorem adipiscing ipsum consequat. In malesuada et sociis tincidunt tempus pellentesque cursus convallis ipsum Suspendisse. Risus In ac quis ut Nunc convallis laoreet ante Suspendisse Nam. Amet amet urna condimentum Vestibulum sem at Curabitur lorem et cursus. Sodales tortor fermentum leo dui habitant Nunc Sed Vestibulum.
											Ut lorem In penatibus libero id ipsum sagittis nec elit Sed. Condimentum eget Vivamus vel consectetuer lorem molestie turpis amet tellus id. Condimentum vel ridiculus Fusce sed pede Nam nunc sodales eros tempor. Sit lacus magna dictumst Curabitur fringilla auctor id vitae wisi facilisi. Fermentum eget turpis felis velit leo Nunc Proin orci molestie Praesent. Curabitur tellus scelerisque suscipit ut sem amet cursus mi Morbi eu. Donec libero Vestibulum augue et mollis accumsan ornare condimentum In enim. Leo eget ac consectetuer quis condimentum malesuada.
											Condimentum commodo et Lorem fringilla malesuada libero volutpat sem tellus enim. Tincidunt sed at Aenean nec nonummy porttitor Nam Sed Nulla ut. Auctor leo In aliquet Curabitur eros et velit Quisque justo morbi. Et vel mauris sit nulla semper vitae et quis at dui. Id at elit laoreet justo eu mauris Quisque et interdum pharetra. Nullam accumsan interdum Maecenas condimentum quis quis Fusce a sollicitudin Sed. Non Quisque Vivamus congue porttitor non semper ipsum porttitor quis vel. Donec eros lacus volutpat et tincidunt sem convallis id venenatis sit. Consectetuer odio.
											Semper faucibus Morbi nulla convallis orci Aliquam Sed porttitor et Pellentesque. Venenatis laoreet lorem id a a Morbi augue turpis id semper. Arcu volutpat ac mauris Vestibulum fringilla Aenean condimentum nibh sed id. Sagittis eu lacus orci urna tellus tellus pretium Curabitur dui nunc. Et nibh eu eu nibh adipiscing at lorem Vestibulum adipiscing augue. Magna convallis Phasellus dolor malesuada Curabitur ornare adipiscing tellus Aliquam tempus. Id Aliquam Integer augue Nulla consectetuer ac Donec Curabitur tincidunt et. Id vel Nunc amet lacus dui magna ridiculus penatibus laoreet Duis. Enim sagittis nibh quis Nulla nec laoreet vel Maecenas mattis vel.
										</span>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="target" id="target" value="<? echo $picture['target']; ?>" />
					<input type="hidden" name="WAA_nonce" id="WAA_nonce" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
				</div>
			</div>

<?php elseif($_GET['tab']=='enhanced'): ?>
			<h3 class="media-title"><? _e('Enhanced Links', 'WAA'); ?></h3>
			<div id="WAA_pageAdd">
				<div class="media-item media-blank">
					<table class="describe">
						<tbody>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<span class="alignleft"><label for="asin"><? _e('ASIN', 'WAA'); ?></label></span>
									<span class="alignright"><abbr id="status_asin" title="required" class="required">*</abbr></span>
								</th>
								<td class="field">
									<input id="asin" name="asin" value="" type="text" aria-required="true" style="width:410px;" />
									<input type="button" value="<? _e('Load','WAA'); ?>" class="button" /><br >
									<div style="width: 410px;"><span class="description"><? _e('To randomise a single link, type several ASINS seperated by a coma or for toggle by a semicolon.', 'WAA'); ?></span></div>
								</td>
							</tr>
					
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="class"><? _e('CSS Class'); ?></label>
								</th>
								<td class="field">
									<input id="class" name="class" value="WAA_enhanced alignnone" type="text" />
								</td>
							</tr>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="style"><? _e('Style'); ?></label>
								</th>
								<td class="field">
									<input id="style" name="style" value="height:240px; width:120px;" type="text" />
								</td>
							</tr>
							<tr class="align">
								<th valign="top" scope="row" class="label"><p><label for="align"><? _e('Alignment'); ?></label></p></th>
								<td class="field">
									<input name="align" id="align-none" value="none" type="radio" checked="checked" />
									<label for="align-none" class="align image-align-none-label"><? _e('None'); ?></label>
									<input name="align" id="align-left" value="left" type="radio" />
									<label for="align-left" class="align image-align-left-label"><? _e('Left'); ?></label>
									<input name="align" id="align-center" value="center" type="radio" />
									<label for="align-center" class="align image-align-center-label"><? _e('Center'); ?></label>
									<input name="align" id="align-right" value="right" type="radio" />
									<label for="align-right" class="align image-align-right-label"><? _e('Right'); ?></label>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<div class="show-align" id="preview" style="height: 22em; overflow: hidden; background-color: #F1F1F1;">
										<span id="img_demo_txt" style="font-family:Monaco, 'Courier New', Courier; font-size: 9px; color: #888;">
											Lorem ipsum dolor sit amet consectetuer velit pretium euismod ipsum enim. Mi cursus at a mollis senectus id arcu gravida quis urna. Sed et felis id tempus Morbi mauris tincidunt enim In mauris. Pede eu risus velit libero natoque enim lorem adipiscing ipsum consequat. In malesuada et sociis tincidunt tempus pellentesque cursus convallis ipsum Suspendisse. Risus In ac quis ut Nunc convallis laoreet ante Suspendisse Nam. Amet amet urna condimentum Vestibulum sem at Curabitur lorem et cursus. Sodales tortor fermentum leo dui habitant Nunc Sed Vestibulum.
											Ut lorem In penatibus libero id ipsum sagittis nec elit Sed. Condimentum eget Vivamus vel consectetuer lorem molestie turpis amet tellus id. Condimentum vel ridiculus Fusce sed pede Nam nunc sodales eros tempor. Sit lacus magna dictumst Curabitur fringilla auctor id vitae wisi facilisi. Fermentum eget turpis felis velit leo Nunc Proin orci molestie Praesent. Curabitur tellus scelerisque suscipit ut sem amet cursus mi Morbi eu. Donec libero Vestibulum augue et mollis accumsan ornare condimentum In enim. Leo eget ac consectetuer quis condimentum malesuada.
											Condimentum commodo et Lorem fringilla malesuada libero volutpat sem tellus enim. Tincidunt sed at Aenean nec nonummy porttitor Nam Sed Nulla ut. Auctor leo In aliquet Curabitur eros et velit Quisque justo morbi. Et vel mauris sit nulla semper vitae et quis at dui. Id at elit laoreet justo eu mauris Quisque et interdum pharetra. Nullam accumsan interdum Maecenas condimentum quis quis Fusce a sollicitudin Sed. Non Quisque Vivamus congue porttitor non semper ipsum porttitor quis vel. Donec eros lacus volutpat et tincidunt sem convallis id venenatis sit. Consectetuer odio.
											Semper faucibus Morbi nulla convallis orci Aliquam Sed porttitor et Pellentesque. Venenatis laoreet lorem id a a Morbi augue turpis id semper. Arcu volutpat ac mauris Vestibulum fringilla Aenean condimentum nibh sed id. Sagittis eu lacus orci urna tellus tellus pretium Curabitur dui nunc. Et nibh eu eu nibh adipiscing at lorem Vestibulum adipiscing augue. Magna convallis Phasellus dolor malesuada Curabitur ornare adipiscing tellus Aliquam tempus. Id Aliquam Integer augue Nulla consectetuer ac Donec Curabitur tincidunt et. Id vel Nunc amet lacus dui magna ridiculus penatibus laoreet Duis. Enim sagittis nibh quis Nulla nec laoreet vel Maecenas mattis vel.
										</span>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="WAA_nonce" id="WAA_nonce" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
				</div>
			</div>
<?php elseif($_GET['tab']=='sidebar'): 
	
	// Optionen der gespeicherten Widgets laden
	
		$widgetOptions = get_option('widget_waa-link');
		$widgetIDs = array_keys($widgetOptions);
		reset($widgetIDs);
		$widgetIDsOPTION = '';
		$widgetIDsJSON = '';
	
	
	// alle verfügbaren Sidebars ermitteln
	
		foreach($widgetOptions as $entry){
			
			if(is_array($entry) && count($entry)!=0){
				
				$widgetID = current($widgetIDs);
				
				$enhancedASINs = get_post_custom_values('_WAA_asin_enhanced_'.$widgetID, $_GET['post_id']);
				$enhancedASINs = is_array($enhancedASINs)?implode("\n", $enhancedASINs):'';
				
				$imageASINs = get_post_custom_values('_WAA_asin_image_'.$widgetID, $_GET['post_id']);
				$imageASINs = is_array($imageASINs)?implode("\n", $imageASINs):'';				
				
				$widgetIDsOPTION .= '<option value="'.$widgetID.'">Widget '.$widgetID.'</option>';
				$widgetIDsJSON   .= '<input type="hidden" name="widgetOptions_'.$widgetID.'" id="widgetOptions_'.$widgetID.'" value=\''.json_encode($entry).'\' />';
				$widgetEnhanced  .= '<input type="hidden" name="WAA_asin_enhanced_'.$widgetID.'" id="WAA_asin_enhanced_'.$widgetID.'" value="'.$enhancedASINs.'" />';
				$widgetImage     .= '<input type="hidden" name="WAA_asin_image_'.$widgetID.'" id="WAA_asin_image_'.$widgetID.'" value="'.$imageASINs.'" />';
			}
			
			next($widgetIDs);
		}

?>
			<h3 class="media-title"><? _e('Sidebar Links', 'WAA'); ?></h3>
			<div id="WAA_pageAdd">
				<div class="media-item media-blank">
					<table class="describe">
						<tbody>
							<tr>
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="widgetID"><? _e('Widget ID', 'WAA'); ?></label>
								</th>
								<td class="field">
									<select name="widgetID" id="widgetID">
										<? echo $widgetIDsOPTION; ?>
									</select>
								</td>
								<td rowspan="5" style="padding-left:50px;">
									<strong><? _e('Preview', 'WAA'); ?></strong>
									<div id="preview" class="WAA_enhancedPreview" style="height:500px; background-color:#F1F1F1; overflow:auto;"></div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" class="label" style="width:130px;"><label><? _e('Type', 'WAA'); ?></label></th>
								<td class="field">
									<input type="radio" name="type" id="type_enhanced" value="enhanced" checked /> <label for="type_enhanced"><? _e('Enhanced Link', 'WAA'); ?></label><br />
<? 
	if(strlen($accesKeyID)>1 && strlen($secretAccesKey)>1){ 
?>
									<input type="radio" name="type" id="type_image" value="image" /> <label for="type_image"><? _e('Image Link', 'WAA'); ?></label>
<? 
	} 
?>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" class="label" style="width:130px;">
									<label for="asin"><? _e('ASIN', 'WAA'); ?></label>
								</th>
								<td class="field" style="vertical-align:top;width:220px;">
									<textarea name="asin" id="asin" cols="24" rows="10" wrap="off" style="width:280px;"></textarea>
									<p><span class="description"><? _e('Use line feed (enter) to separate the ASIN links. To randomise a single link, type several ASINS in one line seperated by a coma or for toggle by a semicolon.', 'WAA'); ?></span></p>
								</td>
							</tr>
							<tr valign="top">
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="style"><? _e('CSS-Style', 'WAA'); ?></label>
								</th>
								<td class="description" style="vertical-align:top;padding-top:0.2em;"><span id="style"></span></td>
							</tr>
							<tr valign="top">
								<th valign="top" scope="row" class="label" style="width:130px;">
									<label for="css"><? _e('CSS-Class', 'WAA'); ?></label>
								</th>
								<td class="description" style="vertical-align:top;padding-top:0.2em;"><span id="css"></span></td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="WAA_nonce" id="WAA_nonce" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
				</div>
			</div>
			<input type="hidden" name="WAA_nonce_post" id="WAA_nonce_post" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
			<input type="hidden" name="post_id" id="post_id" value="<? echo $_GET['post_id']; ?>" />
			<input type="hidden" name="target" id="target" value="<? echo $picture['target']; ?>" />
<?
	echo $widgetEnhanced;
	echo $widgetImage;
	echo $widgetIDsJSON;
?>
<?php endif; ?>

			<div id="saveeditimg" style="margin: 10px;">
				<input type="button" class="button" name="insert" value="<?php esc_attr_e( 'Save all changes' ); ?>" id="insert" />
				<input type="button" class="button" name="cancel" value="<?php esc_attr_e( 'Cancel' ); ?>" id="cancel" />
			</div>
			<input type="hidden" name="webservice" id="webservice" value="<? echo WAA_path('url'); ?>/waa-webservice.php" />
			<input type="hidden" name="plugin" id="plugin" value="<? echo WAA_path('url'); ?>" />
		</form>
	</div>
<script type="text/javascript" language="javascript">
/* <![CDATA[ */		
	jQuery(document).ready(function() {
		jQuery.insertSetup('<? echo $_GET['tab']; ?>');
	});
/* ]]> */		
</script>	
</body>
</html>