/*
 * Wel!AmazonAdds v1.3
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
 *
 * waa-admin.js
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

(function(WAA){
	
	WAA.fn.pad=function(options){
		
		settings=WAA.extend({
			char:'0',align:'left',size:0,first:null
		},options);
		
		WAA.each(this,function(i,e){
			
			var d=undefined;
			
			if(e.nodeName=='INPUT')WAA(e).val(_pad(WAA(e).val()));
			
			function _pad(d){
				
				if(!settings.first) settings.first = settings.char;
				
				if(d.length<=0) d=settings.first;
					
				while(d.length<settings.size) d+=settings.char;
				return d;
			}
		});
	};
	
	WAA.fn.viewAdd=function(options){
		
		WAA(this).addClass('WAA_loading');
		
		settings = WAA.extend({
			asin:'0470592745,1847193595,1847195326,1847198449,1847198821,1847196543,1849510083,1236214105,1435454359,1847196543',
			position:'page',
			parameter:false,
			insertion:'html',
			url:'../wp-content/plugins/welamazonadds/waa-webservice.php'
		},options);
		
		var query=WAA.param(settings);
		var response=WAA.ajax({
			type:'POST',
			url:settings.url,
			data:query,
			dataType:'html',
			async:false
		}).responseText;
		
		if(settings.insertion=='html'){
			
			WAA(this).html(response);
			WAA(this.selector+" iframe").bind('load',function(){
				
				WAA(this).show();
				WAA(this.parentNode).removeClass('WAA_loading');
			});
		}
		else if(settings.insertion=='append'){
			
			WAA(this).append(response);
			WAA(this.selector+" iframe").bind('load',function(){
				
				WAA(this.parentNode).removeClass('WAA_loading');
			});
		}
		else if(settings.insertion=='prepend'){
			
			WAA(this).prepend(response);
			WAA(this.selector+" iframe").bind('load',function(){
				
				WAA(this.parentNode).removeClass('WAA_loading');
			});
		}
		
		function setData(o,n,v){
			
			if(settings[n]){
				o[n]=settings[n]; 
				return;
			}
			
			for(var i in v){
				
				if(v[i].toLowerCase().indexOf(n.toLowerCase())>-1){
					
					o[n]=v[i].split('=')[1];
					
					return;
				}
			}
		}
	};
	
	WAA.fn.viewPicture=function(options){
		
		var asin=('0470592745,1847193595,1847195326,1847198449,1847198821,1847196543,1849510083,1435454359,1847196543').split(',')[Math.round(8*Math.random())];
		
		settings=WAA.extend({
			asin:asin,
			position:'page',
			size:'small',
			type:'image',
			bordure:0,
			url:'../wp-content/plugins/welamazonadds/waa-webservice.php',
			parameter:false,
			insertion:'html'
		},options);
		
		var parameter=decodeURIComponent(settings.parameter).split('&');
		var data={
			asin:settings.asin,
			size:settings.size,
			bordure:settings.bordure,
			type:settings.type,
			position:'page'
		};
		
		WAA.each(['location','partnerID','waa_nonce','target'],function(i,e){
			setData(data,e,parameter);
		});
		
		var query=WAA.param(data);
		var response=WAA.ajax({
			type:'POST',
			url:settings.url,
			data:query,
			dataType:'html',
			async:false
		}).responseText;
				
		if(settings.insertion=='html') WAA(this).html(response);
		else if(settings.insertion=='append') WAA(this).append(response);
		else if(settings.insertion=='prepend') WAA(this).prepend(response);
		
		function setData(o,n,v){
			
			if(settings[n]){
				o[n]=settings[n];
				return;
			}
			for(var i in v){
				
				if(v[i].toLowerCase().indexOf(n.toLowerCase())>-1){
					
					o[n]=v[i].split('=')[1];
					return;
				}
			}
		}
	};
	
	WAA.fn.postbox_toggles=function(){
		
		WAA(".postbox h3, .postbox .handlediv").bind('click',function(){
			WAA(this.parentNode).toggleClass('closed');
		});
	};
	
	WAA.fn.widgetSetup=function(opt){
		
		if(opt.indexOf('__i__')>-1) return false;
		
		var color;
		var colorPicker={};
		
		WAA('#'+opt+'options').accordion({
			heightStyle: "content"
		});
		
		WAA.each(WAA('#'+opt+'backgroundColor,#'+opt+'borderColor,#'+opt+'textColor,#'+opt+'linkColor'),function(i,e){
			
			var id=this.id;
			var cPid=id+'ColorPicker';
			var cPcnl=id+'ColorPickerCancel';
			var cPok=id+'ColorPickerOK';
			
			WAA('#'+cPid).remove();
			WAA('body').append('<div id="'+cPid+'" class="WAA_colorpicker"></div>');
			
			colorPicker[id]=WAA.farbtastic('#'+cPid).linkTo('#'+id);
			WAA("#"+cPid).append('<button id="'+cPcnl+'" class="button alignright">Cancel</button>');
			WAA("#"+cPcnl).bind('click',function(){
				colorPicker[id].setColor(color);
				WAA('div[id$="ColorPicker"]').fadeOut('fast');
				return false;
			});
			WAA("#"+cPid).append('<button id="'+cPok+'" class="button alignright">OK</button>');
			WAA("#"+cPok).bind('click',function(){
				
				if(color==this.value) return false;
				
				WAA('div[id$="ColorPicker"]').fadeOut('fast');
				
				widgetViewAdd('#'+opt+'preview');
				
				if(WAA('#'+opt+'enhancedDefaults').val().length>0){
					dispDefaultEnhanced(WAA('#'+opt+'enhancedDefaults').val());
				}
				
				return false;
			});
			
			WAA(e).bind('change',function(event){
				
				WAA(this).pad({align:'right',first:'#',size:7});

				colorPicker[e.id].setColor(this.value);
				
				return false;
			});
			
			WAA(e).bind('focus',function(){
				
				WAA('div[id$="ColorPicker"]').hide();
				
				color=this.value;
				
				WAA('#'+cPid).css({'left':WAA(this).offset().left+'px','top':(WAA(this).offset().top+WAA(this).outerHeight(true))+'px'});
				WAA('#'+cPid).fadeIn('fast');
				
				return false;
			});
		});
		
		WAA('#'+opt+'imageSize,#'+opt+'target,#'+opt+'priceIndicator').bind('change',function(){
			
			widgetViewAdd('#'+opt+'preview');
			
			if(WAA('#'+opt+'enhancedDefaults').val().length>0){
				
				dispDefaultEnhanced(WAA('#'+opt+'enhancedDefaults').val());
			}
			
			return false;
		});
		
		WAA('#'+opt+'enhancedStyle').bind('change',function(){
			
			var sty=this.value;
			
			WAA.each(WAA('#'+opt+'enhancedPreview iframe'),function(i,e){
				WAA(e).attr('style',sty);
			});
			
			return false;
		});
		
		WAA('#'+opt+'enhancedCSS').bind('change',function(){
			
			var css=this.value;
			
			WAA.each(WAA('#'+opt+'enhancedPreview iframe'),function(i,e){
				
				WAA(e).attr('css',css);
			});
			
			return false;
		});
		
		widgetViewAdd('#'+opt+'preview');
		
		WAA('#'+opt+'enhancedDefaults').bind('change',function(){
			
			var asinStr=WAA(this).val();
			
			if(asinStr.length > 0) dispDefaultEnhanced(asinStr);
			else WAA('#'+opt+'enhancedPreview').html('');
			return false;
		});
		
		if(WAA('#'+opt+'enhancedDefaults').val().length>0){
			dispDefaultEnhanced(WAA('#'+opt+'enhancedDefaults').val());
		}
		
		function widgetViewAdd(target,asin,insertion){
			WAA(target).viewAdd({
				type:'add',
				position:'sidebar',
				asin:asin,
				insertion:insertion,
				backgroundColor:WAA('#'+opt+'backgroundColor').val(),
				linkColor:WAA('#'+opt+'linkColor').val(),
				textColor:WAA('#'+opt+'textColor').val(),
				borderColor:WAA('#'+opt+'borderColor').val(),
				imageSize:WAA('#'+opt+'imageSize').val(),
				priceIndicator:WAA('#'+opt+'priceIndicator').val(),
				target:WAA('#'+opt+'target').val(),
				waa_nonce:WAA('#'+opt+'nonce').val()
			});
		}
		
		function dispDefaultEnhanced(asinStr){
			
			WAA('#'+opt+'enhancedPreview').html('');
			
			var asins=asinStr.split('\n');
			
			WAA.each(asins,function(i,e){
				
				if(e.length<10){
					WAA('#'+opt+'enhancedPreview').append('<div class="ui-widget" style="margin:2px 0"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Wrong ASIN:</strong> '+e+'</div></div>');
				}
				else{
					if(e.indexOf(';')>0) e=e.split(';')[0];
					
					widgetViewAdd('#'+opt+'enhancedPreview',e,(i==0?'html':'append'));
				}
			});
			
			WAA('#'+opt+'enhancedPreview iframe').addClass(WAA('#'+opt+'enhancedCSS').val()).attr('style',WAA('#'+opt+'enhancedStyle').val());
		}
		
		WAA('#'+opt+'ImageDefaults,#'+opt+'imageDefaultsSize').bind('change',function(){
			
			var asinStr=WAA('#'+opt+'ImageDefaults').val();
		
			dispDefaultImages(asinStr);
		
			return false;
		});
		
		WAA('#'+opt+'ImageFloat').bind('change',function(){
			
			var cek=this.checked;
			var css=WAA('#'+opt+'imageCSS').val();
			
			WAA.each(['aligncenter','alignleft','alignright','alignnone'],function(i,e){
				
				var n=new RegExp(e);css=css.replace(n,'');
			});
			
			WAA('#'+opt+'imageCSS').val(WAA.trim(css)+(cek?' aligncenter':' alignleft'));
			WAA('#'+opt+'imagePreview').children().children().removeClass('aligncenter alignleft alignright alignnone').addClass(WAA('#'+opt+'imageCSS').val());
			
			return false;
		});
		
		WAA('#'+opt+'imageStyle').bind('change',function(){
			
			var sty=this.value;
			
			WAA.each(WAA('#'+opt+'imagePreview').children(),function(i,e){
				
				WAA(e).children().first().attr('style',sty);
			});
			
			return false;
		});
		
		WAA('#'+opt+'imageCSS').bind('change',function(){
			
			var css=this.value;
			
			if(this.value.indexOf('aligncenter')<0) WAA('#'+opt+'ImageFloat').attr('checked',false);
			
			WAA.each(WAA('#'+opt+'imagePreview').children(),function(i,e){
				
				WAA(e).children().first().attr('class',css);
			});
			return false;
		});
		
		if(WAA('#'+opt+'ImageDefaults').val().length>0){
			
			dispDefaultImages(WAA('#'+opt+'ImageDefaults').val());
		}
		
		function dispDefaultImages(asinStr){
			
			if(asinStr.length<1){
				
				WAA('#'+opt+'imagePreview').html('');
				
				return false;
			}
			
			var asins=asinStr.split('\n');
			
			WAA('#'+opt+'imagePreview').css({'minHeight':'64px','minWidth':'64px'}).addClass('WAA_loading');
			WAA.each(asins,function(i,e){
				
				if(e.length<10){
					WAA('#'+opt+'imagePreview').append('<div class="ui-widget" style="margin:2px 0"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Wrong ASIN:</strong> '+e+'</div></div>');
				}
				else{
					
					if(e.indexOf(',')>0) e=e.split(',')[0];
					else if(e.indexOf(';')>0) e=e.split(';')[0];
					
					WAA('#'+opt+'imagePreview').viewPicture({
						asin:e,position:'sidebar',
						insertion:i==0?'html':'append',
						size:WAA('#'+opt+'imageDefaultsSize').val(),
						waa_nonce:WAA('#'+opt+'nonce').val()
					});
				}
			});
			
			WAA('#'+opt+'imagePreview img').load(function(){
				
				WAA('#'+opt+'imagePreview').removeClass('WAA_loading');
				WAA('#'+opt+'imagePreview').hide();
				WAA(this).addClass(WAA('#'+opt+'imageCSS').val());
				WAA(this).attr('style', WAA('#'+opt+'imageStyle').val());
				WAA('#'+opt+'imagePreview').fadeIn('slow');
			});
		}
	};
	
	WAA.optionsSetup=function(){
		
		var color;
		var colorPicker={};
		
		WAA('.postbox').postbox_toggles();
//		WAA("#normal-sortables").sortable().disableSelection();
		WAA.each(WAA('.required'),function(i,e){
			if(WAA(e).val().length==0) WAA('label[for="'+e.id+'"]').css('color','red');
		});
		WAA('.required').bind('change',function(){
			WAA('label[for="'+this.id+'"]').css('color',WAA(this).val()==''?'red':'');
		});
		WAA.each(WAA('input[name$="ColorPage"]'),function(index,entry){
			
			var id=this.id;
			var cPid=id+'ColorPicker';
			var cPcancel=cPid+'Cancel';
			var cPok=cPid+'OK';
			
			WAA('body').append('<div id="'+cPid+'" class="WAA_colorpicker"></div>');
			
			colorPicker[id]=WAA.farbtastic('#'+cPid).linkTo('#'+id);WAA("#"+cPid).append('<button id="'+cPcancel+'" class="button alignright">Cancel</button>');
			
			WAA("#"+cPcancel).bind('click',function(){
				
				colorPicker[id].setColor(color);
				WAA('div[id$="ColorPicker"]').fadeOut('fast');
				return false;
			});
			WAA("#"+cPid).append('<button id="'+cPok+'" class="button alignright">OK</button>');
			WAA("#"+cPok).bind('click',function(){
				
				if(color==this.value) return false;
				
				optionViewADD();
				
				WAA('div[id$="ColorPicker"]').hide();
				
				return false;
			});
			WAA(entry).bind('focus',function(){
				
				WAA('div[id$="ColorPicker"]').hide();
				
				color = this.value;
				
				WAA('#'+cPid).css({'left':WAA(this).offset().left+'px','top':(WAA(this).offset().top+WAA(this).outerHeight(true))+'px'});
				WAA('#'+cPid).fadeIn('fast');
			});
			WAA(entry).bind('change',function(event){
				
				WAA(this).pad({align:'right',first:'#',size:7});
				
				colorPicker[id].setColor(this.value);
			});
		});
		
		WAA('[name="WAA_location"],[name="WAA_partnerID"],[name="WAA_priceIndicatorPage"],[name="WAA_targetPage"],[name="WAA_imageSizePage"]').bind('change',function(){
			optionViewADD();
		});
		
		function optionViewADD(){
			
			WAA('#WAA_preView').viewAdd({
				type:'add',
				position:'page',
				partnerID:WAA('#WAA_partnerID').val(),
				backgroundColor:WAA('#WAA_backgroundColorPage').val(),
				linkColor:WAA('#WAA_linkColorPage').val(),
				textColor:WAA('#WAA_textColorPage').val(),
				borderColor:WAA('#WAA_borderColorPage').val(),
				imageSize:WAA('[name="WAA_imageSizePage"]:checked').val(),
				priceIndicator:WAA('[name="WAA_priceIndicatorPage"]').val(),
				target:WAA('[name="WAA_targetPage"]:checked').val(),
				location:WAA('#WAA_location').val(),
				waa_nonce:WAA('#WAA_nonce').val()
			});
		}
		optionViewADD();
		
		WAA('[name="WAA_location"],[name="WAA_pictureSize"],[name="WAA_pictureTarget"]').bind('change',function(){
			optionViewPicture();
		});
		
		function optionViewPicture(){
			
			WAA('.WAA_imagePreview img').fadeOut('slow',function(){
				WAA('.WAA_imagePreview').css({'minWidth':'64px','minHeight':'64px'})
					.addClass('WAA_loading')
					.viewPicture({
						position:'page',
						size:WAA('[name="WAA_pictureSize"]:checked').val(),
						target:WAA('[name="WAA_pictureTarget"]:checked').val(),
						location:WAA('[name="WAA_location"]:checked').val(),
						partnerID:WAA('[name="WAA_partnerID"]').val(),
						waa_nonce:WAA('[name="WAA_nonce"]').val(),
						secretAccesKey:WAA('[name="WAA_secretAccesKey"]').val(),
						accesKeyID:WAA('[name="WAA_accesKeyID"]').val()
					});
				
				WAA('.WAA_imagePreview img').load(function(){
					WAA(this).fadeIn('slow');WAA('.WAA_imagePreview').removeClass('WAA_loading');
				});
			});
		}
		optionViewPicture();
		
		if(WAA('[name="WAA_pictureSize"]:checked').length==0) WAA('#WAA_pictureSizesmall').attr('checked',true);
	};
		
	//
	// Methoden zum einfügen von Amazon Adds auf Seiten und Artikeln
	//
	WAA.insertSetup=function(tab){
	
		// Alle Inputs disablen
		WAA('input').attr('disabled', true);
		// Input zur Eingabe der ASIN enablen
		WAA('#asin, .button').attr('disabled',false);
						
		// Wenn Tab auf Image
		if(tab=='image'){
			
			WAA('#size').bind('change', function(){
				this.blur();
			});
			
			// Wenn Feld ASIN oder Size verlassen wurden
			WAA('#asin ,#size').bind('blur', function(){
				
				// Alle Lerzeichen aus ASIN-String entfernen
				WAA('#asin').val(WAA('#asin').val().replace(/\s/g, ''));
				
				// ASIN Wert in Variable speichern / Einzellink
				var asin = WAA('#asin').val();
				
				// ASIN Werte in Array Speichern für Random oder Toggle
				var asins = asin.split(asin.match(/;/)?';':',');
				
				// Wenn ASIN String 10 Zeichen enthält
				if(asin.length >= 10){
					
					// Alle Eingabefelder einschalten
					WAA('input').attr('disabled', false);
					
					// Benötigthinweiß (*) ausblenden
					WAA('#status_asin').hide();
					
					// Vorhandenen Adds entfernen
					WAA('.WAA_image').remove();
					
					// Neues Add laden und in der Vorschau anzeigen
					WAA('#preview').viewPicture({
						asin:       asins[0],
						position:   'page',
						size:       WAA('#size').val(),
						url:        WAA('#webservice').val(),
						waa_nonce:  WAA('#WAA_nonce').val(),
						target:     WAA('#target').val(),
						insertion: 'prepend'
					});
					
					// Bild in Variable speichern
					var image = WAA('#preview img:first');
					
					WAA.each(['alt','width','height','class','style'],function(i,e){
						
						if(WAA('#'+e).val().length>0 && e!='width' && e!='height') image.attr(e,WAA('#'+e).val());
						else WAA('#'+e).val(image.attr(e));
					});
					
					if(asins.length>1){
						WAA('#preview a').attr('type',WAA('#asin').val());
					}
					
					WAA('#resize').val(WAA('#width').val()+','+WAA('#height').val());
					WAA('#WAA_dimension').show();
					WAA.each(['href','title'],function(i,e){
						WAA('#'+e).val(WAA('#preview a:first').attr(e));
					});
					WAA('#preview img:first').bind('click',function(){return false;});
				}
				// Wenn ASIN String kleiner 10 oder leer vorschau löschen und Werte zurücksetzen
				else if(this.id=='asin'){
					WAA('input').attr('disabled',true);
					WAA('#asin, .button').attr('disabled',false);
					WAA('#status_asin').show();
					WAA('#WAA_dimension').hide();
					
					this.form.reset();
					
					WAA('#preview img, #preview div').remove();
				}
			});
			
			// Event Keys einbinden
			WAA('#asin').bind('keydown',function(e){
				switch(e.keyCode){
					case 13:this.blur(); break;
					case 27:this.value='';this.blur(); break;
					case 32: return false; break;
					default: break;
				}
			});
			
			// Feld für Höhe und Breide mit Event versehen
			WAA('#width, #height').bind('change',function(){
				WAA('#preview img:first').attr(this.id,this.value);
			});
			// Feld für Alt und CSS-Klasse mit Event versehen
			WAA('#alt, #class').bind('change',function(){
				WAA('#preview img:first').attr(this.id,this.value);
			});
			// Feld für Alt und CSS-Klasse mit Event versehen
			WAA('#href, #title').bind('change',function(){
				WAA('#preview img:first').attr(this.id,this.value);
			});
			// Feld für Style mit Event versehen
			WAA('#style').bind('keypress',function(){
				if(event.keyCode==59) WAA('#preview img:first').attr('style',this.value);
			});
			WAA('#style').bind('change',function(){
				WAA('#preview img:first').attr('style',this.value);
			});
			WAA('#resize').bind('click',function(){
				
				var dimension=this.value.split(',');
				
				WAA('#width').val(dimension[0]);
				WAA('#height').val(dimension[1]);
				WAA('#preview img:first').attr({width:dimension[0],height:dimension[1]});
				
				return false;
			});
			WAA('input[name="align"]').bind('change',function(){
				
				WAA('#preview img:first').removeClass('alignleft alignright aligncenter alignnone');
				
				switch(this.value){
					case 'left':WAA('#preview img:first').addClass('alignleft');break;
					case 'right':WAA('#preview img:first').addClass('alignright');break;
					case 'center':WAA('#preview img:first').addClass('aligncenter');break;
					default:WAA('#preview img:first').addClass('alignnone');break;
				}
				
				WAA('#class').val(WAA('#preview img:first').attr('class'));
			});
			WAA('#insert').bind('click',function(){
				
				var win=window.dialogArguments||opener||parent||top;
				WAA('#preview span').remove();
				win.send_to_editor(WAA('#preview').html());
			});
		}
		else if(tab=='enhanced'){
			
			WAA('#asin').bind('blur',function(){
				
				WAA('#asin').val(WAA('#asin').val().replace(/\s/g,''));
				
				var asins=WAA('#asin').val().split(';');
				
				if(asins[0].length>=10){
					
					WAA('input').attr('disabled',false);
					WAA('#status_asin').hide();
					WAA('#preview iframe').remove();
					WAA('#preview').viewAdd({
						asin:asins[0],
						type:'add',
						position:'page',
						url:WAA('#webservice').val(),
						insertion:'prepend',
						waa_nonce:WAA('#WAA_nonce').val(),
						style:WAA('#style').val()
					});
					WAA('#preview iframe').addClass(WAA('#class').val());
					WAA('#preview iframe').attr('style', WAA('#style').val());
					WAA('#preview iframe').attr('name', 'WAA_enhanced');
					
					if(asins.length>1){
						WAA('#preview iframe').attr('title',asins);
					}
				}
				else{
					WAA('input').attr('disabled',true);
					WAA('#asin, .button').attr('disabled',false);
					WAA('#preview iframe').remove();
					WAA('#status_asin').show();
				
					this.form.reset();
				}
			});
			
			WAA('input[name="align"]').bind('change',function(){
				
				var cssAlign;
		
				WAA('.WAA_enhanced').removeClass('alignleft alignright aligncenter alignnone');
				
				switch(this.value){
					case 'left':
						cssAlign = 'alignleft';
						break;
					case 'right':
						cssAlign = 'alignright';
						break;
					case 'center':
						cssAlign='aligncenter';
						break;
					default:
						cssAlign='alignnone';
						break;
				}
			
				WAA('.WAA_enhanced').addClass(cssAlign);
				WAA('#class').val(WAA('.WAA_enhanced').attr('class'));
			});
			
			WAA('#asin').bind('keydown',function(e){
				
				switch(e.keyCode){
					case 13:
						this.blur();
						break;
					case 27:
						this.value='';
						this.blur();
						break;
					case 32:
						return false;
						break;
					default:
						break;
				}
			});
			
			WAA('#style').bind('keypress',function(){
				
				if(event.keyCode==59){
					WAA('.WAA_enhanced').attr('style',this.value);
				}
			});
			
			WAA('#style').bind('change',function(){
				WAA('.WAA_enhanced').attr('style', this.value);
			});
			
			WAA('#class').bind('change',function(){
				
				if(this.value.indexOf('WAA_enhanced')<1) this.value='WAA_enhanced '+this.value;
				
				WAA('.WAA_enhanced').attr('class',this.value);
			});
			
			WAA('#insert').bind('click',function(){
				
				WAA('#preview span').remove();
				var enhancedStr = WAA('#preview').html();
				var win = window.dialogArguments||opener||parent||top;
				
				win.send_to_editor(enhancedStr);
			});
		}
		else if(tab=='sidebar'){

			WAA('input').attr('disabled',false);
			WAA('input[name="type"], #widgetID').bind('change',function(){
				WAA('#asin').val('');update();
			});
			WAA('#insert').bind('click',function(){

				var data={WAA_nonce:WAA('#WAA_nonce_post').val(),type:'sidebar',post_id:WAA('#post_id').val(),images:{},enhanced:{}};
				var enhanced=WAA('input[name^="WAA_asin_enhanced"]');
				var images=WAA('input[name^="WAA_asin_image"]');

				WAA.each(enhanced,function(i,entry){
					data.enhanced[entry.name]=WAA(entry).val();
				});
				WAA.each(images,function(i,entry){
					data.images[entry.name]=WAA(entry).val();
				});

				var response=WAA.ajax({
					type:'POST',
					url:settings.url,
					data:WAA.param(data),
					dataType:'html',
					async:false
				}).responseText;

				if(response=="error") alert("An error occurred, while updating the ASINs!");

				var win=window.dialogArguments||opener||parent||top;win.tb_remove();

				tinymce=tinyMCE=null;
			});
		
			function update(){
				var asins;
				var widgetOptions=WAA.parseJSON(WAA('#widgetOptions_'+WAA('#widgetID').val()).val());
				var enhanced=WAA('input[name="type"]:checked').val()=='enhanced';

				WAA('#preview').html('');
				WAA('#style').html(enhanced?widgetOptions.enhancedStyle:widgetOptions.imageStyle);
				WAA('#css').html(enhanced?widgetOptions.enhancedCSS:widgetOptions.imageCSS);
			
				if(WAA('#asin').val().length==0){

					asins=WAA('#WAA_asin_'+(enhanced?'enhanced_':'image_')+WAA('#widgetID').val()).val().split('\n');
					WAA('#asin').val(asins.join("\n"));
				}
				else asins=WAA('#asin').val().split('\n');
			
				WAA('#preview').removeClass('WAA_imagePreview WAA_enhancedPreview');
				WAA('#preview').addClass(enhanced?'WAA_enhancedPreview':'WAA_imagePreview');
			
				if(asins[0].length<1) return;
			
				WAA.each(asins, function(i, entrys){
				
					if(WAA('input[name="type"]:checked').val()=='enhanced'){

						var entry=entrys.split(';');
						WAA('#preview').viewAdd({
							asin:entry[0],
							type:'add',
							position:'sidebar',
							url:WAA('#webservice').val(),
							insertion:'append',
							backgroundColor:widgetOptions.backgroundColor,
							borderColor:widgetOptions.borderColor,
							textColor:widgetOptions.textColor,
							linkColor:widgetOptions.linkColor,
							priceIndicator:widgetOptions.priceIndicator,
							target:widgetOptions.target,
							imageSize:widgetOptions.imageSize,
							waa_nonce:WAA('#WAA_nonce').val()
						});
						WAA('#preview').children().last().addClass(widgetOptions.enhancedCSS);
						WAA('#preview').children().last().attr('style',widgetOptions.enhancedStyle);

						if(entry.length>1) WAA('#preview').children().last().attr('title', entry);
					}
					else{

						var entry=entrys.split(';');

						if(entry.length==1) entry=entrys.split(',');

						WAA('#preview').viewPicture({
							asin:entry[0],
							position:'sidebar',
							size:widgetOptions.imageDefaultsSize,
							url:WAA('#webservice').val(),
							insertion:'append',
							waa_nonce:WAA('#WAA_nonce').val(),
							target:WAA('#target').val()
						});
						WAA('#preview a img').last().addClass(widgetOptions.imageCSS);
						WAA('#preview a img').last().attr('style',widgetOptions.imageStyle);
					}
				});
			}
			
			WAA('#asin').bind('change',function(){

				WAA('#WAA_asin_'+WAA('input[name="type"]:checked').val()+'_'+WAA('#widgetID').val()).val(this.value);
				update();
			});
		
			update();
		}
	
		
		WAA('#cancel').bind('click',function(){
		
			var win=window.dialogArguments||opener||parent||top;
		
			win.tb_remove();
		
			tinymce=tinyMCE=null;
		});
	};
})(jQuery);
