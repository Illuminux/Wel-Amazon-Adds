<?
/*
 *	Variabeln
 */
	$instance = WAA_instance();
	extract($instance);

	// Klasse mit funktionen laden
	require_once("waa-functions.php");

?>
	<div class=wrap> 
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2><? _e('Wel!Amazon Affiliate', 'WAA'); ?></h2>
		<form method="post" action="options.php" id="WAA_form" name="WAA_option">
			<?php wp_nonce_field('update-options'); ?>
			<div id="poststuff" class="metabox-holder">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox" id="WAA_metabox-1">
						<div class="handlediv" title="Click to toggle">
							<br>
						</div>
						<h3 class="hndle">
							<span><? _e('General Options','WAA'); ?></span>
						</h3>
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
										</td>
										<td>
											<span class="description"><? _e('Amazon does not provide an affiliate program for any country. Go to your local amazon web side and check which country provides an affiliate program for you.','WAA')?></span>
										</td>
										<td rowspan="6">
											<div style="margin:5px;"><a href="<? $flattr = WAA_path('flattr'); echo $flattr['href']; ?>"><img src="<? $flattr = WAA_path('flattr'); echo $flattr['src']; ?>" width="50" height="60" alt="Flattr this" /></a></div>
										</td>
									</tr>
									<tr valign="top">
										<th colspan="3"><h4><? _e('Amazon Affiliate Program','WAA'); ?></h4></th>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="WAA_partnerID"><? _e('Partner-ID:', 'WAA'); ?></label></th>
										<td>
											<input name="WAA_partnerID" id="WAA_partnerID" type="text" value="<? _e($partnerID) ?>" class="regular-text required" />
										</td>
										<td>
											<span class="description"><? _e('You will get your Partner-ID for the affiliate program at the Amazon webpage of your country.','WAA')?></span>
										</td>
									</tr>
									<tr valign="top">
										<th colspan="2"><h4><? _e('Amazon Web-Service','WAA'); ?></h4></th>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="WAA_accesKeyID"><? _e('Access Key ID:','WAA'); ?></label></th>
										<td>
											<input name="WAA_accesKeyID" id="WAA_accesKeyID" type="text" value="<? _e($accesKeyID) ?>" class="regular-text required" />
										</td>
										<td rowspan="2">
											<span class="description"><? _e('You will get your Access Key ID at the <a href="http://aws.amazon.com" target="_blank">Amazon Web Service</a> page. After the registration you can generate a Secret Access Key. Copy this key and paste it into the Secret Access Key field.','WAA')?></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="WAA_secretAccesKey"><? _e('Secret Access Key:','WAA'); ?></label></th>
										<td>
											<input name="WAA_secretAccesKey" id="WAA_secretAccesKey" type="text" value="<? _e($secretAccesKey) ?>" class="regular-text required" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					
					
					<div class="postbox closed" id="WAA_metabox-2">
						<div class="handlediv" title="Click to toggle">
							<br>
						</div>
						<h3 class="hndle">
							<span><? _e('Enhanced Links','WAA') ?></span>
						</h3>
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
										
										<td rowspan="7" valign="top" align="right">
											<div style="margin:5px;"><a href="<? $flattr = WAA_path('flattr'); echo $flattr['href']; ?>"><img src="<? $flattr = WAA_path('flattr'); echo $flattr['src']; ?>" width="50" height="60" alt="Flattr this" /></a></div>
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
											<input type="radio" name="WAA_targetPage" id="WAA_targetBlank" value="_blank" <? _e($page['target']=='_blank'?'checked ':''); ?>/> 
											<label for="WAA_targetBlank"><? _e('Open link in a new window','WAA') ?></label><br />
											<input type="radio" name="WAA_targetPage" id="WAA_targetPageTop" value="_top" <? _e($page['target']=='_top'?'checked ':''); ?>/> 
											<label for="WAA_targetPageTop"><? _e('Open link in the same window','WAA') ?></label>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><? _e('Image Size:', 'WAA'); ?></th>
										<td>
											<input type="radio" name="WAA_imageSizePage" id="WAA_imageLarge" value="IS2" <? _e($page['imageSize']=='IS2'?'checked ':''); ?>/> 
											<label for="WAA_imageLarge"><? _e('Use large image','WAA') ?></label><br />
											<input type="radio" name="WAA_imageSizePage" id="WAA_imageSmall" value="IS1" <? _e($page['imageSize']=='IS1'?'checked ':''); ?>/> 
											<label for="WAA_imageSmall"><? _e('Use small image','WAA') ?></label>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					
					
					<div class="postbox closed" id="WAA_metabox-3">
						<div class="handlediv" title="Click to toggle">
							<br>
						</div>
						<h3 class="hndle">
							<span><? _e('Image Links','WAA') ?></span>
						</h3>
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
															<input type="radio" name="WAA_pictureTarget" id="WAA_pictureTargetBlank" value="_blank" <? _e($picture['target']=='_blank'?'checked ':''); ?>/> 
															<label for="WAA_pictureTargetBlank"><? _e('Open link in a new window','WAA') ?></label><br />
															<input type="radio" name="WAA_pictureTarget" id="WAA_pictureTargetTop" value="_top" <? _e($picture['target']=='_top'?'checked ':''); ?>/> 
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
										<td valign="top" align="right">
											<div style="margin:5px;"><a href="<? $flattr = WAA_path('flattr'); echo $flattr['href']; ?>"><img src="<? $flattr = WAA_path('flattr'); echo $flattr['src']; ?>" width="50" height="60" alt="Flattr this" /></a></div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div id="advanced-sortables" class="meta-box-sortables ui-sortable"></div>
			</div>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="<?php echo implode(",", WAA_pageOptions()); ?>" />
			<input type="hidden" name="WAA_nonce" id="WAA_nonce" value="<? echo wp_create_nonce(get_bloginfo()); ?>" />
			<p class="submit"><input type="submit" id="WAA_save" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</form>
	</div>
	<img src="<? echo WAA_path('imageurl') ?>/empty.png" onload="jQuery(document).ready(function(){jQuery.optionsSetup();})" />
