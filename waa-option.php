<?php
/*
 * Wel!AmazonAdds v1.3
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
 *
 * waa-options.php
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


// load the functions
require_once("waa-functions.php");
	
// Load JavaScript for option page
wp_enqueue_script('welamazonaffiliate-admin');
		
// Load Style Sheet for option page
wp_enqueue_style('welamazonaffiliate-admin');

// Get option parameter
$instance = WAA_instance();
extract($instance);

$pageOptions = array_keys($instance);


?>
<div class=wrap> 
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><? _e("Wel!Amazon Affiliate &rsaquo; Settings", 'WAA'); ?></h2>
		
<!-- WRSCC Sidebar start -->
	<div id="WAA_option_sidebar" style="position: absolute; right: 15px; width: 300px; height: 380px;" class="metabox-holder">
	
		<div id="sidbar-postbox-container-1" class="postbox-container">
			<div id="WAA_wel_plugins" class="meta-box">
				<div class="postbox" id="sidbar-postbox-1" style="width: 296px;">
					<div class="handlediv" title="<? _e("Click to toggle"); ?>"><br></div>
					<h3 class="hndle" style="cursor: pointer !important;"><span><? _e("My other Plugins", 'WAA'); ?></span></h3>
					<div class="inside">
						<p>
						<?
							_e("", 'WAA'); 
						?>
						</p>
					</div>
				</div>
			</div>
		</div>
		
		<div id="sidbar-postbox-container-2" class="postbox-container">
			<div id="WAA_donation" class="meta-box">
				<div class="postbox" id="sidbar-postbox-2" style="width: 296px;">
					<div class="handlediv" title="<? _e("Click to toggle"); ?>"><br></div>
					<h3 class="hndle" style="cursor: pointer !important;"><span><? _e("Do you like this Plugin?", 'WAA');  ?></span></h3>
					<div class="inside">
						<p>
						<?
							_e("plug-in programming and maintenance is very time consuming, so I would be happy for any small donation.", 'WAA'); 
						?>
						</p>
						<p style="color: red">Donate ausgeschaltet!</p>
<!--						
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_donations">
							<input type="hidden" name="business" value="knut.welzel@t-online.de">
							<input type="hidden" name="lc" value="US">
							<input type="hidden" name="item_name" value="Wel!sBlog">
							<input type="hidden" name="no_note" value="0">
							<input type="hidden" name="currency_code" value="EUR">
							<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
						</form>
-->
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- WRSCC Sidebar end -->	

	<div style="margin-right: 320px;">
		<form method="post" action="options.php" id="WAA_form" name="WAA_option">
			<?php wp_nonce_field('update-options'); ?>
				
			<div id="WAA_option" class="metabox-holder" style="width: 100%; min-width: 450px;">
					
				<div id="postbox-container-1" class="postbox-container" style="width: 100%;">
					<div id="WAA_general" class="meta-box">
						<div class="postbox" id="postbox-1">
							<div class="handlediv" title="<? _e("Click to toggle"); ?>"><br></div>
							<h3 class="hndle"><span><? _e('General Options','WAA'); ?></span></h3>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr valign="top">
											<th scope="row"><label for="WAA_location"><? _e('Amazon Location:', 'WAA'); ?></label></th>
											<td>
												<select name="WAA_location" id="WAA_location" class="required">
													<option value=""><? _e('Select your location','WAA'); ?></option>
													<? WAA_locationOptions($location); ?>
												</select>
												<p class="description"><? _e('Amazon does not provide an affiliate program for any country. Go to your local amazon web side and check which country provides an affiliate program for you.','WAA')?></p>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="WAA_partnerID"><? _e('Partner-ID:', 'WAA'); ?></label></th>
											<td>
												<input name="WAA_partnerID" id="WAA_partnerID" type="text" value="<? _e($partnerID) ?>" class="regular-text required" />
												<p class="description"><? _e('You will get your Partner-ID for the affiliate program at the Amazon webpage of your country.','WAA')?></p>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div id="postbox-container-2" class="postbox-container" style="width: 100%;">
					<div id="WAA_textandgraphics" class="meta-box">
						<div class="postbox closed" id="postbox-2">
							<div class="handlediv" title="<? _e("Click to toggle"); ?>"><br></div>
							<h3 class="hndle"><span><? _e('Enhanced Links','WAA') ?></span></h3>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<th scope="row" valign="top" width="240"><label for="WAA_priceIndicatorPage"><? _e('Price Indicator:', 'WAA'); ?></label></th>
											<td valign="top" width="300">
												<select name="WAA_priceIndicatorPage" id="WAA_priceIndicatorPage">
													<option value=""    <? echo $page['priceIndicator']==''    ?'selected':''; ?> ><? _e('Show all prices', 'WAA'); ?></option>
													<option value="nou" <? echo $page['priceIndicator']=='nou' ?'selected':''; ?> ><? _e('Only new prices', 'WAA'); ?></option>
													<option value="npa" <? echo $page['priceIndicator']=='npa' ?'selected':''; ?> ><? _e('Do not show prices', 'WAA'); ?></option>
												</select>
											</td>
											<td rowspan="7">
												<div id="WAA_preView"></div>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="WAA_backgroundColorPage"><? _e('Background Color:', 'WAA'); ?></label></th>
											<td>
												<input name="WAA_backgroundColorPage" id="WAA_backgroundColorPage" value="<? _e($page['backgroundColor']) ?>" type="text" class="WAA_colorpicker" maxlength="7" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="WAA_borderColorPage"><? _e('Border Color:', 'WAA'); ?></label></th>
											<td>
												<input name="WAA_borderColorPage" id="WAA_borderColorPage" value="<? _e($page['borderColor']) ?>" type="text" class="WAA_colorpicker" maxlength="7" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="WAA_textColorPage"><? _e('Text Color:', 'WAA'); ?></label></th>
											<td>
												<input name="WAA_textColorPage" id="WAA_textColorPage" value="<? _e($page['textColor']) ?>" type="text" class="WAA_colorpicker" maxlength="7" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><label for="WAA_linkColorPage"><? _e('Link Color:', 'WAA'); ?></label></th>
											<td>
												<input name="WAA_linkColorPage" id="WAA_linkColorPage" value="<? _e($page['linkColor']) ?>" type="text" class="WAA_colorpicker" maxlength="7" />
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><? _e('Target Window:', 'WAA'); ?></th>
											<td>
												<input type="radio" name="WAA_targetPage" id="WAA_targetBlank" value="_blank" <? _e($page['target']=='_blank'?'checked ':''); ?> /> &nbsp;
												<label for="WAA_targetBlank"><? _e('Open link in a new window','WAA') ?></label><br />
												<input type="radio" name="WAA_targetPage" id="WAA_targetPageTop" value="_top" <? _e($page['target']=='_top'?'checked ':''); ?> /> &nbsp;
												<label for="WAA_targetPageTop"><? _e('Open link in the same window','WAA') ?></label>
											</td>
										</tr>
										<tr valign="top">
											<th scope="row"><? _e('Image Size:', 'WAA'); ?></th>
											<td>
												<input type="radio" name="WAA_imageSizePage" id="WAA_imageLarge" value="IS2" <? _e($page['imageSize']=='IS2'?'checked ':''); ?> /> &nbsp;
												<label for="WAA_imageLarge"><? _e('Use large image','WAA') ?></label><br />
												<input type="radio" name="WAA_imageSizePage" id="WAA_imageSmall" value="IS1" <? _e($page['imageSize']=='IS1'?'checked ':''); ?> /> &nbsp;
												<label for="WAA_imageSmall"><? _e('Use small image','WAA') ?></label>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div id="postbox-container-3" class="postbox-container" style="width: 100%;">
					<div id="WAA_general" class="meta-box">
						<div class="postbox closed" id="postbox-3">
							<div class="handlediv" title="<? _e("Click to toggle"); ?>"><br></div>
							<h3 class="hndle"><span><? _e('Image Links','WAA') ?></span></h3>
							<div class="inside">
								<table class="form-table">
									<tbody>
										<tr>
											<td valign="top" style="width:500px;">
												<table class="form-table" style="width:480px;">
													<tbody>
														<tr valign="top">
															<th scope="row" width="240"><? _e('Target Window:', 'WAA'); ?></th>
															<td width="240">
																<input type="radio" name="WAA_pictureTarget" id="WAA_pictureTargetBlank" value="_blank" <? _e($picture['target']=='_blank'?'checked ':''); ?>/> &nbsp;
																<label for="WAA_pictureTargetBlank"><? _e('Open link in a new window','WAA') ?></label><br />
																<input type="radio" name="WAA_pictureTarget" id="WAA_pictureTargetTop" value="_top" <? _e($picture['target']=='_top'?'checked ':''); ?>/> &nbsp;
																<label for="WAA_pictureTargetTop"><? _e('Open link in the same window','WAA') ?></label>
															</td>
														</tr>
													</tbody>
												</table>
												<h4><? _e('Sandbox:','WAA');?></h4>
												<table class="form-table" style="width:480px;">
													<tbody>
														<tr>
															<th scope="row" width="240"><? _e('Image size:', 'WAA'); ?></th>
															<td width="240">
																<?
																	$WAA_pictureSize = WAA_pictureSize();
																
																	foreach($WAA_pictureSize as $entry){
																		$output = '<input name="WAA_pictureSize" id="WAA_pictureSize' . $entry . '" value="'.strtolower($entry).'" type="radio" /> '
																		        . '<label for="WAA_pictureSize' . $entry . '">' . $entry . ' ' . __('image','WAA') . '</label><br />';
																		echo $output;
																	}
																?>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
											<td>
												<div class="WAA_imagePreview alignleft">
													<img />
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="<?php echo implode(",", WAA_pageOptions()); ?>" />
				<input type="hidden" name="WAA_nonce" id="WAA_nonce" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
				<?php submit_button(); ?>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" language="javascript">
/* <![CDATA[ */		
	jQuery(document).ready(function() {
		jQuery.optionsSetup();
	});
/* ]]> */		
</script>
	
