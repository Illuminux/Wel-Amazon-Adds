<?php
/*
 * Plugin Name: Wel!AmazonAdds
 * Plugin URI: http://wordpress.org/extend/plugins/welamazonadds/
 * Description: The Plugin Wel!Amazon Adds is an easy way to include Amazon links into your Wordpress Blog. It enables the integration of image links and extended links directly from the Amazon database. These links can be placed directly in the text or on the sidebar, so that each page gets a suitable sidebar for the topic of content. 
 * Version: 1.3
 * Author: Knut Welzel
 * Author URI: www.welzels.de
 * 
 * Copyright 2012  Knut Welzel  (email : knut@welzels.de)
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
 * For details, see htp://www.welzels.de/welamazonadds/
 */

	// Klasse mit funktionen laden
	require_once("waa-functions.php");
	
	// Notwendige JavaScripte die WP nicht zur Verfügung stelle registrieren
		
		// Wel!Amazon Affiliate JavaScript für Admin
		wp_register_script(
			'welamazonaffiliate-admin',
			WAA_path('url') . "/js/waa-admin.js",
			array(
				'jquery', 
				'jquery-ui-core',
				'jquery-ui-sortable',
				'jquery-ui-accordion',
				'farbtastic'),
			'1.3'
		);
		
		// Wel!Amazon Affiliate JavaScript für Seiten
		wp_register_script(
			'welamazonaffiliate',
			WAA_path('url') . "/js/welamazonaffiliate.js",
			array('jquery'),
			'1.3'
		);
	
	// Notwendige StyleSheets die WP nicht zur Verfügung stelle registrieren
		
		// Wel!Amazon Affiliate StyleSheet für Admin
		wp_register_style(
			'welamazonaffiliate-admin',
			WAA_path('url') . "/css/waa-admin.css",
			array(
				'farbtastic',
				'jquery-ui-css'),
			'1.3',
			'screen'
		);
		
		// jQuery UI StyleSheet Theme
		wp_register_style(
			'jquery-ui-css',
			WAA_path('url') . "/css/jQuery/jquery.ui.css",
			false,
			'1.7.3',
			'screen'
		);
		
		// Wel!Amazon Affiliate StyleSheet für Seiten
		wp_register_style(
			'welamazonaffiliate',
			WAA_path('url') . "/css/welamazonaffiliate.css",
			array(),
			'1.3' 
		);
	
	
	# Actionen setzen 

		// Aufruf der Aktion zum erstellen der Optionsseite
		add_action('admin_menu',  'WAAoptions');
		
		
		// Aufruf der Aktion zum erstellen des Sidebar Widget
		add_action('widgets_init', 'WAAwidget');
		
		
		// JavaScript und Style laden
			
			// Adminbereich
//			add_action('admin_init', 'WAAenqueue');
			
			// Javascript und Stylesheet für Blogbereich laden
			if(!is_admin()){
				
				wp_enqueue_script('welamazonaffiliate');
				wp_enqueue_style('welamazonaffiliate');
			}
			
		
	// Aufruf der Aktion zum erstellen des Mediabutton in der Toolbar
			
		add_action('media_buttons', 'WAAmediaButtons', 20);
		
		add_action('wp_footer', 'WAAfooter');

	// Filter setzen

		add_filter('tiny_mce_before_init', 'WAAtinymceInit');
		
		add_filter('the_content', 'WAAoldAdds');
		
		load_plugin_textdomain('WAA', WAA_path('url').'/I18n/', 'welamazonadds/I18n/');
?>