/*
 * Wel!AmazonAdds v1.3
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
 *
 * welamazonaffiliate.js
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

	WAA(document).ready(function(){
		
		var get = WAA('#WAA_interim').attr('src').split('?');
		var webSrv = get[0].replace(/images\/empty.png/,'waa-webservice.php');
		var env = WAA.toObject(get[1]);
	
//		WAA.each(WAA('iframe.WAA_enhanced'), function(i,el){
		WAA.each(WAA('iframe[name="WAA_enhanced"]'), function(i, el){
			
			
			
			var elQ = WAA(el);
			var asin = elQ.attr('title');
				
			if(asin){

				elQ.wrap('<div class="WAA_loading" style="overflow: hidden;' + elQ.attr('style') + '" id="WAA_iframe_container_' + i + '">');
				
				var asins = asin.split(';');
				var src = elQ.attr('src').split('?');
				var href = src[0];
				
				var objQ = WAA('#WAA_iframe_container_' + i);
				
				elQ.removeAttr('title');
				elQ.removeAttr('src');
				elQ.removeAttr('style');
				elQhtml = elQ.html();
				
							
				WAA.each(asins, function(j, e){
			
					var data = WAA.map(src[1].split('&'),function(a, i){
				
						var v = a.split('=');
				
						if(v[0] == 'asins')
							a = v[0] + "=" + e;
						
						return a;
						
					}).join('&');
				
					if(j>0){
						elQ.clone().insertAfter(elQ).attr('src', href+'?'+data); //.hide();
					}
					else
						elQ.attr('src', href+'?'+data); //.show();
				});
				
	//			WAA('#WAA_iframe_container_' + i).children().show();
			
				window.setInterval(function(){
					WAA('#WAA_iframe_container_' + i + ' iframe').toggleFadeIFrame(2000, i)
				}, 10000);

			}
		});
		
		var image = WAA('.WAA_image img');
		
		WAA.each(image,function(i, el){
		
			if(this.complete){
				
				window.setTimeout(function(){WAA.setupImage(el, i)}, 50);
				
			}
			else{
				
				WAA(el).load(function(){
					WAA.setupImage(el, i)
				});
			}
		});
	});

	WAA.setupImage = function(el, i){
		
		var elQ = WAA(el);
		var asin = elQ.parent().attr('type');
		var align = WAA.isArray(align = elQ.attr('class').match(/align.*/)) ? align[0] : '';
		var get = WAA('#WAA_interim').attr('src').split('?');
		var webSrv = get[0].replace(/images\/empty.png/, 'waa-webservice.php');
		var env = WAA.toObject(get[1]);
		
		elQ.parent().wrap('<div class="WAA_loading ' + align + '" id="WAA_img_container_' + i + '">');

		if(asin){
			
			if(asin.match(/,/)){
				
				var asins = asin.split(',');
				var rndAsin = asins[Math.round((asins.length-1)*Math.random())];
				var d = {
					
					type:      'image',
					asin:      rndAsin,
					waa_nonce: env.n,
					size:      waaImageSize(el.src)
				};
				
				WAA.ajax({
					
					type:'POST',
					url:webSrv,
					data:WAA.param(d),
					dataType:'html',
					success:function(r){

						var sty = elQ.attr('style');
						var css = elQ.attr('class');
						
						(elQ.parent().parent().html(r).children().children()).addClass(css).attr('style',sty).css({
							'visibility': 'visible',
							'display':'none'
						}).fadeIn('slow');
					}
				});
			}
			else if(asin.match(/;/)){
			
				var asins = asin.split(';');
				var s = waaImageSize(elQ.attr('src'));
				var sty = elQ.attr('style');
				var css = elQ.attr('class');
				var width = WAA('#WAA_img_container_' + i + " img").width();
				var height = WAA('#WAA_img_container_' + i + " img").height();
				
				WAA.each(asins, function(j, e){
					
					if(j>0) {
					
						var d = {
							type:'image',
							waa_nonce:env.n,
							size:s,
							asin:e,
							id:'WAA_img_container_' + i,count:asins.length-1
						};

						WAA.ajax({
							type:'POST',
							url:webSrv,
							data:WAA.param(d),
							dataType:'html',
							success:function(r){

								var elQ = WAA('#' + d.id + ' a img').first();
								var newEl = WAA(r).appendTo(WAA('#' + d.id));
							
								WAA('#' + d.id + ' a img').last().addClass(css).attr({
									'style':  sty, 
									'width':  width,
									'height': height
								}).hide();
							}
						});
					}
					else {
						WAA('#WAA_img_container_' + i + ' a img').attr({
							'width':  width,
							'height': height
						});
					}
				});
				
				window.setInterval(function(){
					WAA('#WAA_img_container_' + i + ' a img').toggleFadeImg(2000, i)
				}, 10000);
			}
		}
		else{
			
			elQ.css({
				'visibility':'visible'
			});
		}
		
		function waaImageSize(src){
			
			if(src.match(/_SL75_/))
				return'small';
			else if(src.match(/_SL30_/))
				return'swatch';
			else if(src.match(/_SL110_/))
				return'tiny';
			else if(src.match(/_SL160_/))
				return'medium';
			else 
				return'large'
		}
	};
	
	WAA.fn.toggleFadeImg = function(options, i){
		
		WAA('#WAA_img_container_' + i + " a:first-child").insertAfter(WAA('#WAA_img_container_' + i + " a:last-child"));
		
		if(typeof options == 'object')
			settings = WAA.extend({ speedIn:'slow', speedOut:'slow' },options);
		else
			settings = WAA.extend({ speedIn:options, speedOut:options });

		WAA('#WAA_img_container_' + i + " img").slideUp(settings.speedIn);
		WAA('#WAA_img_container_' + i + " a:first-child").children().slideDown(settings.speedOut);

	};
	
	WAA.fn.toggleFadeIFrame = function(options, i){		
		
		if(WAA('#WAA_iframe_container_' + i).children().length > 1)
			WAA('#WAA_iframe_container_' + i).children().first().insertAfter(WAA('#WAA_iframe_container_' + i).children().last());
/*	
		if(typeof options == 'object')
			settings = WAA.extend({ speedIn:'slow', speedOut:'slow' },options);
		else
			settings = WAA.extend({ speedIn:options, speedOut:options });

		WAA('#WAA_iframe_container_' + i).children().last().slideUp(settings.speedIn);
		WAA('#WAA_iframe_container_' + i).children().first().slideDown(settings.speedOut);
		*/
	};
	
	
	
	WAA.toObject = function(array){
		
		var obj = {};
		
		WAA.map(array.split('&'),function(a,i){
			
			var e = unescape(a).split('=');
			var k = e[0];
			var v = (e[1]?e[1]:null);
			obj[k] = v
		});
		
		return obj
	}
})(jQuery);








