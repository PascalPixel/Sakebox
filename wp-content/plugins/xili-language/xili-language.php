<?php
/*
Plugin Name: xili-language
Plugin URI: http://dev.xiligroup.com/xili-language/
Description: This plugin modify on the fly the translation of the theme depending the language of the post or other blog elements - a way to create a real multilanguage site (cms or blog). Numerous template tags and three widgets are included. It introduce a new taxonomy - here language - to describe posts and pages. To complete with tags, use also xili-tidy-tags plugin. To include and set translation of .mo files use xili-dictionary plugin. Includes add-on for multilingual bbPress forums.
Author: dev.xiligroup.com - MS
Author URI: http://dev.xiligroup.com
Version: 2.11.3
License: GPLv2
Text Domain: xili-language
Domain Path: /languages/
*/
# updated 140421 - 2.11.3 - fixes - style improved in translations metabox
# updated 140411 - 2.11.2 - accurate counter for CPT - more tests with 3.9 - improving nav menu classes assignation with _wp_menu_item_classes_by_context
# updated 140317 - 2.11.1 - add filter to enable Featured_Content class of current theme and disable Featured_Content class of JetPack. Changes ajax/json for WP3.9. Fixes and improves menus links (format, date)
# updated 140307 - 2.11.0 - add new function is_xili_curlang() returning true or false depending passed language slug, the_curlang (xili_curlang) improved, clean wp pointers, [xl-class-admin] improves infos in form for alias refreshing in permalinks, new locales (based on jetpack)
# updated 140302 - 2.10.3 - fixes in add-on
# updated 140226 - 2.10.1 - fixes permalinks class
# updated 140201 - 2.10.0 - new versioning rules - improved GlotPress and Automattic download - screenshots in assets - updated help

# updated 131208 - 2.9.30 - new class for language in permalink options (donators)
# updated 131206 - 2.9.22 - improves widget latest posts (remove xlrp), widget latest comments (new selection),
# updated 131124 - 2.9.21 - fixes curlang in specific condition of page in front-page, fixes matche preferred languages priority with FF if only 2 chars
# updated 131110 - 2.9.20 - Insertion point for menus without location
# updated 131103 - 2.9.11 - fixes theme-multilingual-class when no header images, fixes insertion points issues
# updated 131101 - 2.9.10 - fixes CPT selection ajax find-posts, new theme-multilingual-class 1.3 - tested with 3.7.1 - inline edit and bulk edit improved, incorporate a new feature : List of a sub-selection of pages (according current language of webpage) can be inserted everywhere in nav menu.
# updated 131011 - 2.9.3 - maintenance version as current in repository
# updated 131010 - 2.9.2 - restrict home queries to authorized post_type ( nextgen gallery issue even in admin side )
# updated 131002 - 2.9.1 - improved theme for options classes (multilingual-classes.php) - fixes rare notice - addon bbPress adapted for xtt groups
# updated 130820 - 2.9.0 - Release tested with final WP 3.6, more accurate warning message if load_theme_textdomain is not available
# updated 1306, 130716 - 2.8.10 - test for jetpack, WP3.6-rc1
# updated 130527 - 2.8.9 - fixes, __construct in widget classes.
# updated 130519 - 2.8.8k - temporary fixes for ka_GE (replace ge_GE for Georgian) - changes https to http for glotpress (WP server changed)
# updated 130422 - 130512  - 2.8.8 - fixes notice with bbPress 2.3 - try to find .mo files at automattic svn and GlotPress - best title in href language list - new way to insert languages list in navigation menu - better filter in menu/widget title - clean $wp_roles on deactivating - fixes findposts js - the_other_posts improved to return list.
# updated 130416 - 2.8.7 - fixes lang_perma if search, fixes IE matching(z8po), add option 'Posts selected in' for language_list title link
# updated 130322 - 2.8.6 - verify QUETAG value - improve html attributes - improves searchform js
# updated 130313 - 2.8.5 - add feature to improve pages list insertion
# updated 130303 - 2.8.4.3 - infos in cats removed (added in XD), plugin domain switching improved, clean __construct source, fixes
# updated 130216 - 2.8.4.2 - fixes Notice, media cloning again available in WP 3.5.x
# updated 130203 - 2.8.4.1 - fixes page_for_posts issue when empty and static_front_page, works with permalink - adapt texts in settings
# updated 130127 - 2.8.4 - fixes get_terms cache at init, fixes support settings (s) issue, add nounce in admin UI
# updated 130106 - 2.8.3.1 - Maintenance release, fixes xili-tidy-tags class exists in bbp addon

# updated 121202 - 130103 - 2.8.3 - insert in empty nav menu - improved admin UI - fixes WP 3.5 new process (lang sub-folder)
# updated 120929 - 121118 - 2.8.2 - improvement query var - add date format without locale (no_locale)
# updated 120921 - 2.8.1 - fixes sticky perma - improvements for bbPress - new filters in admin - fixes feed links meta
# updated 120903 - 2.8.0 - fixes, new: admin side language selection - b : langstate
# updated 120819 - 120723 - 2.7.1 - fixes lang_perma detect for permalinks add-ons - new start via action
# updated 120721 - 2.7.0 - multilingual features in media library - centralize alert messages and ready for link to wiki
# updated 120708 - 2.6.3 - fixes notice when page-on-front - new icons - test propagate cats
# updated 120701 - 2.6.2 - add news pointer in class admin, tabs in help
# updated 120615 - 2.6.1 - fixes notice xlrp
# updated 120528 - 2.6.0 - class admin in separate file, able to detect and use local files (local-xx_XX.mo) containing translations
# updated 120420 - 2.5.1 - add style for post edit (flag in imput)
# updated 120415 - 2.5.0 - new dashboard metabox for translations list of linked posts and actions (as in XD2), language postmetas now hidden, hreflang link added in head, WP3.4 pre-tests: fixes metaboxes columns
# updated 120329 - 2.4.4 - fixes posts list language column in 3.2.1
# updated 120225 - 2.4.3 - fixes before releasing
# updated 120212 - 2.4.2 - admin_bar replaced by toolbar and node (>= WP3.3) - cc: + Reply-To: - better merge loadthemedomain in multisite - ready for XD 2.0 - sub-sub-folder for language files
# updated 120128 - 2.4.1 - new readme - new tabs in settings - improved lang slug creation - test menu location get_registered_nav_menus -
# updated 111222 - 2.4.0 - if needed and present add rtl.css for rtl languages - new date based on wp_locale - time options
# less than 2.4….
# see readme text for these intermediate versions for WP 2.x. or visit other previous versions (2.5).
# updated 090228 - Class and OOP - see 0.9.7 in comments of functions below - only for WP 2.7.x

# This plugin is free software; you can redistribute it and/or
# modify it under the terms of the GNU Lesser General Public
# License as published by the Free Software Foundation; either
# version 2.1 of the License, or (at your option) any later version.
#
# This plugin is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
# Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public
# License along with this plugin; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA


define('XILILANGUAGE_VER', '2.11.3'); /* used in admin UI*/
define('XILILANGUAGE_WP_VER', '3.7'); /* used in error - see at end */
define('XILILANGUAGE_PHP_VER', '5.0.0'); /* used in error - see at end */
define('XILILANGUAGE_PREV_VER', '2.9.21');
define('XILILANGUAGE_DEBUG', false ); /* used in dev step UI see #5183 - xili_xl_error_log () */



/********************* the CLASS **********************/

class xili_language {

	var $xili_settings; /* saved in options */

	var $langs_group_id; /* group ID and Term Taxo ID */
	var $langs_group_tt_id;

	var $default_lang; /* language of config.php*/
	var $default_slug; /* slug of language of config.php since 1.5.3 wpmu*/
	var $curlang;

	var $thetextdomain = ""; /* since 1.5.2 - used if multiple */
	var $langstate; /* undefined or not */

	var $default_dir = ''; /* undefined or not in WP config '' or rtl or ltr */
	var $curlang_dir = ''; /* undefined or not according array */
	var $rtllanglist = 'ar-dv-fa-ha-he-ps-ur-uz-yi'; /* default-list - can be set after class instantiation - 2.8.7 improved since JetPack */

	var $get_archives_called = array(); /* if != '' - insert lang in link */
	var $idx = array(); /* used to identify filter or action set from this class - since 0.9.9.6 */
	var $theme_locale = false; /* to control locale hook */
	var $ossep = "/"; /* for recursive file search in xamp */

	var $current_lang_query_tag = ""; /* since 1.3.0 */
	var $temp_lang_query_tag = "";

	var $domaindetectmsg = ""; // msg used if pb with load_theme_textdomain

	var $langs_list_options = array (); // now set in init 2.8.6

	var $comment_form_labels = array ( // since 1.6.0 for comment_form
		'name' => 'Name',
		'email' => 'Email',
		'website' => 'Website',
		'comment' => 'Comment',
		'youmustbe' => 'You must be <a href="%s">logged in</a> to post a comment.',
		'loggedinas' => 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>',
		'emailnotpublished' => 'Your email address will not be published.',
		'requiredmarked' => ' Required fields are marked <span class="required">*</span>',
		'youmayuse' => 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:',
		'leavereply' => 'Leave a Reply',
		'replyto' => 'Leave a Reply to %s',
		'cancelreply' => 'Cancel reply',
		'postcomment' => 'Post Comment'
		);

	var $sticky_keep_original = false ; // since 1.6.1 see translate_sticky_posts_ID function
	var $xl_recent_posts = false ;
	var $ltd = false ; // load_textdomain detected 1.8.5
	var $ltd_parent = false ; // load_textdomain detected in parent if merging 2.9
	var $arraydomains = array(); // for theme domain tests
	var $show = false ;

	var $langs_ids_array ; // array slug => id
	var $langs_slug_name_array ; // array slug => name // 2.4.2
	var $langs_slug_fullname_array; // array slug => full name // 2.6.3

	var $langs_slug_shortqv_array = array() ; // array slug => short query var // 2.8.2
	var $langs_shortqv_slug_array = array() ; // array short query var => slug // 2.8.2
	var $alias_mode = false; // temp

	var $show_page_on_front = false;

	/* for permalink with lang at root */
	var $lang_perma = false; // if true new permalink for root and categories /en_us/category/.... if special action available… 2.1.1
	var $lpr = ''; // 2.3.2
	var $show_page_on_front_array = array(); // array of lang=>page_id if show_on_front == page

	var $undefchar = "."; // 2.2.3 - used to detect undefined
	var $sublang = ""; // 2.2.3 - used to detect - or fr_fr- of like
	var $doing_list_language = false; // 2.11.1 set to loop lang slug when doing list languages

	// 2.5
	// other in class admin
	var $translation_state = '_xl_translation_state' ; // set to initial when post created from dashboard metabox (to update post slug)

	// 2.6 - class admin in separate file
	var $file_file = ''; // see in construct below
	var $file_basename = '';
	var $plugin_basename = '';
	var $plugin_url = '';
	var $plugin_path = ''; // The path to this plugin - see construct

	var $xilidev_folder = '/xilidev-libraries'; //must be in plugins

	var $page_for_posts_array = array();
	var $page_for_posts_name_array = array(); // used if is_permalink
	var $page_for_posts_name_to_id_array = array(); // used if is_permalink
	var $page_for_posts_original = false; // 2.8.4
	var $is_permalink = false;

	// since 2.8.8 - insertion

	var $insertion_point_box_title = 'Languages list Insertion point';
	var $insertion_point_dummy_link = '#insertlist';
	// since 2.9.10 - insertion pages sub-selection
	var $insertion_point_box_title_page = 'Pages list Insertion point';
	var $insertion_point_dummy_link_page = '#insertpagelist';
	// since 2.9.20 - insertion menu selection
	var $insertion_point_box_title_menu = 'Menus list Insertion point';
	var $insertion_point_dummy_link_menu = '#insertmenu';

	/**** Construct is future ****/

	public function __construct( $locale_method = false, $show = false, $class_admin = false ) {

		// 2.6 - class admin in separate file
		$this->file_file = __FILE__ ; // see in construct below
		$this->file_basename = basename(__FILE__) ;
		$this->plugin_basename = plugin_basename(__FILE__) ;
		$this->plugin_url = plugins_url('', __FILE__) ;
		$this->plugin_path = plugin_dir_path(__FILE__) ;
		$this->ossep = strtoupper( substr(PHP_OS ,0 ,3 ) == 'WIN' ) ? '\\' : '/';

		$this->locale_method = $locale_method; /* added for compatibility with cache plugin from johan */
		$this->show = $show;
		$this->class_admin = $class_admin;

		/* activated when first activation of plug */
		register_activation_hook( __FILE__, array(&$this, 'xili_language_activate') );

		register_deactivation_hook( __FILE__, array(&$this, 'remove_capabilities') ); //2.8.8

		if ( !$class_admin ) $this->only_parent_construct(); // 2.8.4.3

	}

	function only_parent_construct () {
		global $wp_version;
		/**
		 * get current settings
		 */
		$this->xili_settings = get_option( 'xili_language_settings', false );
		if( false === $this->xili_settings ) { //1.9.1
			$this->xili_settings = $this->initial_settings ();
			update_option('xili_language_settings', $this->xili_settings );

		} else {
			if ($this->xili_settings['version'] == '1.9') { /* 2.4.0 */
				$this->xili_settings['wp_locale'] = 'wp_locale'; //wp_locale new mode
				$this->xili_settings['version'] = '2.0';
				update_option('xili_language_settings', $this->xili_settings); //
			}
			if ($this->xili_settings['version'] == '2.0') { /* 2.4.0 */
				$this->xili_settings['available_langs'] = array(); // default array
				$this->xili_settings['version'] = '2.1';
				update_option('xili_language_settings', $this->xili_settings); //
			}
			if ($this->xili_settings['version'] == '2.1') { /* 2.6.0 */
				$this->xili_settings['external_xl_style'] = "on";
				$this->xili_settings['creation_redirect'] = 'redirect'; // default - after initial creation
				$this->xili_settings['version'] = '2.2';
				update_option('xili_language_settings', $this->xili_settings);  //
			}
			if ($this->xili_settings['version'] == '2.2') { /* 2.8.3 */
				$this->xili_settings['nav_menu_separator'] = "|";
				$this->xili_settings['version'] = '2.3';
				update_option('xili_language_settings', $this->xili_settings);  //
			}
			if ($this->xili_settings['version'] == '2.3') { /* 2.8.4 */
				$this->xili_settings['pforp_select'] = "select"; // no_select,
				$this->xili_settings['version'] = '2.4';
				update_option('xili_language_settings', $this->xili_settings);  //
			}
			if ($this->xili_settings['version'] == '2.4') { /* 2.8.4.3 */
				if ( isset($this->xili_settings['domains']['all'] ) )  unset ( $this->xili_settings['domains']['all'] ) ; // no_all for plugins
				// move all value to new array
				$this->xili_settings['page_in_nav_menu_array'] = '';
				if ( isset( $this->xili_settings['navmenu_check_optionp'] ) ) {
					$key = $this->xili_settings['navmenu_check_optionp'];
					$enable = ( isset( $this->xili_settings['navmenu_check_optionp']) && $this->xili_settings['page_in_nav_menu'] == 'enable' ) ? 'enable' : '';

					$thenewvalue = array( 'enable'=> $enable, 'args'=> $this->xili_settings['args_page_in_nav_menu'] );
					if ( $key != '' )
						$this->xili_settings['array_navmenu_check_option_page'][$key] = $thenewvalue;

					$this->xili_settings['page_in_nav_menu_array'] = $enable ;
					unset ( $this->xili_settings['navmenu_check_optionp'] );
					unset ( $this->xili_settings['args_page_in_nav_menu'] ) ;
					unset ( $this->xili_settings['page_in_nav_menu'] ) ;
				}

				$this->xili_settings['version'] = '2.5';
				update_option('xili_language_settings', $this->xili_settings);  //
			}
			if ($this->xili_settings['version'] == '2.5') { /* 2.8.4.4 */
				$this->xili_settings['list_pages_check_option'] = 'fixe';
				$this->xili_settings['version'] = '2.6';
				update_option('xili_language_settings', $this->xili_settings);
			}
			// 'Posts selected in %s' 2.8.7
			if ($this->xili_settings['version'] == '2.6') {
				$this->xili_settings['list_link_title'] = array ( 'post_selected' => 'Posts selected in %s',
											 'current_post' => 'Current post in %s',
											 'latest_posts' => 'Latest posts in %s', // used in xili-language list,
											 'view_all_posts' => 'View all posts in %s'); //  the_category (and XD);
				$this->xili_settings['version'] = '2.7';
				update_option('xili_language_settings', $this->xili_settings);
			}
			if ($this->xili_settings['version'] == '2.7') {
				$this->xili_settings['mo_parent_child_merging'] = false;
				$this->xili_settings['parent_langs_folder'] = '';
				$this->xili_settings['version'] = '2.8';
				update_option('xili_language_settings', $this->xili_settings);
			}
			if ($this->xili_settings['version'] == '2.8') {
				$this->xili_settings['version'] = '2.9';
				update_option('xili_language_settings', $this->xili_settings);
			}
			if ($this->xili_settings['version'] == '2.9') {
				$this->xili_settings['version'] = '2.11';
				$this->xili_settings['enable_fc_theme_class'] = 'enable'; //

				$theme_name = get_option("current_theme"); // full name for theme switcher...
				$this->xili_settings['theme_alias_cache'] = array();
				foreach ( $this->xili_settings['lang_features'] as $slug => $values ){
					if (isset ( $this->xili_settings['lang_features'][$slug]['alias'] )) {
						$this->xili_settings['theme_alias_cache'][$theme_name][$slug] = $this->xili_settings['lang_features'][$slug]['alias'];
					}
				}
				update_option('xili_language_settings', $this->xili_settings);
			}
			if ( ! isset ( $this->xili_settings['version'] ) || $this->xili_settings['version'] != '2.11') { // repair or restart from new
				$this->initial_settings ();
				update_option('xili_language_settings', $this->xili_settings);
			}
		}

		if ( ! defined( 'TAXONAME' ) ) define('TAXONAME', $this->xili_settings['taxonomy']);
		if ( ! defined( 'QUETAG' ) ) define('QUETAG', $this->xili_settings['reqtag']); // 'lang'
		if ( ! defined( 'TAXOLANGSGROUP' ) ) define('TAXOLANGSGROUP', $this->xili_settings['taxolangsgroup']);
		if ( ! defined( 'LANG_UNDEF' ) ) define('LANG_UNDEF', $this->xili_settings['lang_undefined']); //2.2.3

		/* detect research about permalinks */

		$this->show_page_on_front = ( 'page' == $this->get_option_wo_xili('show_on_front') ) ;

		$this->is_permalink = ( '' == get_option( 'permalink_structure' ) ) ? false : true; // 2.8.4

		add_action( 'plugins_loaded', array(&$this,'init_plugin_textdomain'), 11 ); // 2.8.10 - class for theme-xili
		add_action( 'init', array(&$this,'init_theme_textdomain'), 9 );
		add_action( 'init', array(&$this,'init_and_register_language_post_taxonomy'), 9 );

		add_action( 'init', array(&$this,'init_vars'), 9 ); //2.8.4 level 9 - these previous lines - to be before XD 130122

		add_action( 'init', array(&$this,'add_link_taxonomy'), 13 ); // 1.8.5
		/* special to detect theme changing since 1.1.9 */
		add_action( 'switch_theme', array(&$this,'theme_switched') );

		/* query filters */
		add_filter( 'posts_join', array(&$this,'posts_join_with_lang'), 10, 2 );
		add_filter( 'posts_where', array(&$this,'posts_where_lang'), 10, 2 );

		add_filter( 'posts_search', array(&$this,'posts_search_filter'), 10, 2 ); //2.2.3
		add_action( 'pre_get_posts', array(&$this,'xiliml_modify_querytag') );

		add_action( 'wp', array(&$this, 'xili_test_lang_perma'), 1 ); // only front-end
		add_action( 'wp', array(&$this, 'xiliml_language_wp') ); /// since 2.2.3 - wp_loaded - wp before
		/* 'wp' = where theme's language is defined just after query */
		if ( $this->xili_settings['wp_locale'] == 'wp_locale' )
			add_action( 'wp', array(&$this,'xili_locale_setup'), 15 ); // since 2.4
		if ( $this->locale_method )
			add_filter('locale', array(&$this,'xiliml_setlocale'), 10);
		/* to be compatible with l10n cache from Johan since 1.1.9 */

 		add_filter( 'widget_title', array(&$this,'one_text'), 9 ); /* added 0.9.8.1 - 9 to avoid quotation filter 2.8.8 */
	 	add_filter( 'widget_text', array(&$this,'one_text'));

		add_filter( 'list_cats', array(&$this,'xiliml_cat_language'), 10, 2 ); /* mode 2 : content = name */
		add_filter( 'link_category', array(&$this,'one_text')); // 1.6.0 for wp_list_bookmarks (forgotten)
		add_filter( 'category_link', array(&$this,'xiliml_link_append_lang'), 10, 2 );

		add_filter( 'category_description',array(&$this,'xiliml_link_translate_desc'));
		add_filter( 'single_cat_title',array(&$this,'xiliml_single_cat_title_translate')); /* 1.4.1 wp_title() */

		add_filter( 'tag_link', array(&$this,'xiliml_taglink_append_lang' ));

		add_filter( 'bloginfo', array(&$this,'xiliml_bloginfo'), 10, 2); /* since 1.6.0 - description - date - time */

		/* filters for archives since 0.9.9.4 */
		add_filter( 'getarchives_join', array(&$this, 'xiliml_getarchives_join'), 10, 2 );
		add_filter( 'getarchives_where', array(&$this, 'xiliml_getarchives_where'), 10, 2 );
		add_filter( 'get_archives_link', array(&$this, 'xiliml_get_archives_link') );

		/* option modified */
		add_filter( 'option_sticky_posts', array(&$this, 'translate_sticky_posts_ID') ); /* 1.6.1 */

		add_filter( 'option_page_on_front', array(&$this, 'translate_page_on_front_ID') ); /* 1.7.0 */

		/* bookmarks and widget_links  1.8.5  #2500 */
		add_filter( 'widget_links_args', array( &$this, 'widget_links_args_and_lang' ), 10, 1 ); // in class WP_Widget_Links (default-widgets.php)
		add_filter( 'get_bookmarks', array( &$this, 'the_get_bookmarks_lang' ), 10, 2); // only active if 'lang' in wp_list_bookmarks()

		add_action( 'wp_head', array(&$this,'head_insert_language_metas'), 11 ); // © and options present in functions.php

		add_filter( 'plugin_locale', array(&$this,'get_plugin_domain_array'), 10, 2 );

		if ( !is_admin() ) {

			add_filter( 'option_page_for_posts', array(&$this, 'translate_page_for_posts_ID') ); /* 2.8.4 */

			add_filter( 'the_category', array(&$this,'xl_get_the_category_list'), 10, 2); /* 1.7.0 */
			add_filter( 'gettext', array(&$this,'change_plugin_domain'), 10, 3); /* 1.8.7 */
			add_filter( 'gettext_with_context', array(&$this,'change_plugin_domain_with_context'), 10, 4); /* 1.8.8 */
			add_filter( 'ngettext', array(&$this,'change_plugin_domain_plural'), 10, 5);
			add_filter( 'ngettext_with_context', array(&$this,'change_plugin_domain_plural_with_context'), 10, 6);

			// for wp nav menu
			add_filter( 'the_title', array(&$this,'wp_nav_title_text'), 9, 2); // 9 to be before RIGHT SINGLE QUOTATION MARK filter 2.8.8 - thanks to PouletFou

			if ('' != $this->xili_settings['in_nav_menu']) {
				add_filter( 'wp_nav_menu_items', 'xili_nav_lang_list', 10, 2 );
			}

			// add_filter( 'wp_nav_menu_items', array(&$this,'insert_language_list_in_nav_menu' ), 10, 2 ); preg method - changed

			add_filter( 'wp_nav_menu_objects', array(&$this,'insert_language_objects_in_nav_menu' ), 10, 2 ); //wp-includes/nav-menu-template.php

			if ('' != $this->xili_settings['page_in_nav_menu_array']) // new 2.8.4.3 to avoid if ''
					add_filter( 'wp_nav_menu_items', 'xili_nav_page_list', 9, 2 ); // before lang's links - 1.7.1

			if ('' != $this->xili_settings['home_item_nav_menu'])
					add_filter( 'walker_nav_menu_start_el', 'xili_nav_page_home_item', 10, 4 ); // add lang if - 1.8.9.2

			if ( version_compare($wp_version, '3.4.9', '>') ) {// new behaviour - add dummy if lang nav or page nav (
				if ( '' != $this->xili_settings['in_nav_menu']  || '' != $this->xili_settings['page_in_nav_menu_array'] ) {
					add_filter( 'wp_nav_menu_args', 'xili_nav_menu_args', 10, 1 ); // filter in nav-menu-template.php 2.8.3
				}
			}

			add_filter( 'language_attributes',  array(&$this,'head_language_attributes'));
			add_action( 'wp_head', array(&$this,'head_insert_hreflang_link' ), 10 ); // since 2.5

			add_filter( 'option_date_format', array(&$this, 'translate_date_format') ); /* 1.7.0 */

			add_filter( 'category_feed_link', array(&$this, 'category_feed_link') ); // 2.8.1
		}

		// since 1.5.5
		add_filter( 'comment_form_default_fields', array(&$this,'xili_comment_form_default_fields'));
		add_filter( 'comment_form_defaults', array(&$this,'xili_comment_form_defaults'));

		// since 2.4.0 for rtl.css
		add_filter( 'locale_stylesheet_uri', array(&$this, 'change_locale_stylesheet_uri' ),10, 2 ) ;

		// since 1.8.8 - activate xl widget series
		if ( $this->xili_settings['widget'] == 'enable' )
			add_action( 'widgets_init', array(&$this,'add_new_widgets') );

		/* new actions for xili-language theme's templates tags */

		add_action( 'xili_language_list', array(&$this, 'xili_language_list' ), 10, 5); /* add third param 0.9.7.4 - 4th 1.6.0*/
		add_action( 'xili_post_language', array(&$this, 'xili_post_language' ), 10, 2);

		add_action( 'xiliml_the_other_posts', array(&$this, 'the_other_posts' ), 10, 4); /* add a param 1.1 */
		add_action( 'xiliml_the_category', array(&$this, 'the_category' ), 10, 3);
		add_filter( 'xiliml_langinsearchform', array(&$this, 'xiliml_langinsearchform' ), 10, 3); // 1.8.2 action to filter

	// verify theme and set ltd for both parent and admin child
		add_filter( 'override_load_textdomain', array(&$this,'xiliml_override_load'), 10, 3); // since 1.5.0
		add_filter( 'theme_locale', array(&$this,'xiliml_theme_locale'), 10, 2);	// two times if is_admin()

		//
		add_action( 'xl_propagate_post_attributes', array(&$this,'propagate_categories'), 10, 2); // 2.8.8

	}

	/**
	 * first activation or empty settings
	 */
	function initial_settings() {  xili_xl_error_log ( '# 460 -------------------- init' );
		return array(
			    'taxonomy'		=> 'language',
			    'version' 		=> '2.8',
			    'reqtag'		=> 'lang', // query_var
			    'browseroption' => '',
			    'authorbrowseroption' => '',
			    'taxolangsgroup' => 'languages_group',
			    'functions_enable' => '',
			    'langs_folder' => '',
			    'theme_domain' => '',
			    'homelang' => '',
			    'langs_list_status' => '',
			    'in_nav_menu' => '',
			    'page_in_nav_menu' => '',
			    'page_in_nav_menu_array' =>'',
				'args_page_in_nav_menu' => '',
				'multilingual_custom_post' => array(),
				'langs_in_root_theme' => '',
				'domains' => array( 'default' => 'disable', 'bbpress' => 'disable' ),  // no default domain to theme domain 1.8.7 - no all 2.8.4.3
				'widget' => 'enable',
				'delete_settings' => '', //1.8.8 for uninstall
				'allcategories_lang' => 'browser', // 1.8.9.1
				'lang_features' => array() ,
				'home_item_nav_menu' => '', // 1.8.9.2
				'lang_undefined' => $this->undefchar, //2.2.3
				'lang_neither_browser' => '', // 2.3.1
				'wp_locale' => 'wp_locale', // 2.7.1 new mode as default - 2.4.0 = old mode based on db strftime
				'available_langs' => array(),
				'creation_redirect' => 'redirect', // 2.6 to redirect to new post after creation
				'external_xl_style' => "on", // activate external xl-style.css - on by default :2.6.3
				'nav_menu_separator' => "|", // 2.8.3
				'pforp_select' => "select", // 2.8.4
				'shortqv_slug_array' => array(), // special perma
				'list_pages_check_option' => 'fixe', // 2.8.5
				'list_link_title' => array ( 'post_selected' => 'Posts selected in %s',
											 'current_post' => 'Current post in %s',
											 'latest_posts' => 'Latest posts in %s', // used in xili-language list (and XD)
											 'view_all_posts' => 'View all posts in %s' ), // used in the_category
				'mo_parent_child_merging' => false,	// 2.8.8
				'parent_langs_folder'  => '',
				'enable_fc_theme_class' => 'enable', // 2.11.1 - priority to theme Featured Content Class and not jetpack
				'theme_alias_cache' => array()
		    );
	}

	/* first activation of plugin */
	function xili_language_activate() {
		$this->xili_settings = get_option('xili_language_settings', false ); xili_xl_error_log('# 508 -- xl first activate');
		if ( $this->xili_settings === false ) {
			$this->xili_settings = $this->initial_settings();
		    update_option('xili_language_settings', $this->xili_settings );
		}

	}

	// when admin_init (to be refreshed after temporary desactivated)
	function init_roles () {

		global $wp_roles;

		$wp_roles->add_cap ('administrator', 'xili_language_set');
		$wp_roles->add_cap ('administrator', 'xili_language_menu');
		$wp_roles->add_cap ('administrator', 'xili_language_clone_tax');

		$wp_roles->add_cap ('editor', 'xili_language_menu');
		$wp_roles->add_cap ('editor', 'xili_language_clone_tax'); // able to clone taxonomy ('category') during propagate or create post

	}

	// when desactivating - 2.8.8
	function remove_capabilities () {

		global $wp_roles;

		$wp_roles->remove_cap ( 'administrator', 'xili_language_set' );
		$wp_roles->remove_cap ( 'administrator', 'xili_language_menu' );
		$wp_roles->remove_cap ( 'administrator', 'xili_language_clone_tax' );

		$wp_roles->remove_cap ( 'editor', 'xili_language_menu' );
		$wp_roles->remove_cap ( 'editor', 'xili_language_clone_tax' );

		// remove settings if..
		if ( is_multisite() && isset ( $this->xili_settings['delete_settings'] ) &&  $this->xili_settings['delete_settings'] == "delete_this" ) {

			$languages = get_terms($this->xili_settings['taxonomy'], array('hide_empty' => false));
			foreach ($languages as $language ) {
				$postmeta_suffixes[] = $language->slug ;
			}
			foreach ($languages as $language )  {

				$term_id = $language->term_id;

				$post_IDs = get_objects_in_term( array( $term_id ), array( $this->xili_settings['taxonomy'] ) );

				foreach ( $post_IDs as $post_ID ) {
				// delete postmeta lang-xx_xx
					foreach ( $postmeta_suffixes as $postmeta_suffix ) {
						if ( $language->slug != $postmeta_suffix ) delete_post_meta( $post_ID, $this->xili_settings['reqtag'].'-'.$postmeta_suffix ) ;
					}
				// delete relationships posts
	 				wp_delete_object_term_relationships( $post_ID, $this->xili_settings['taxonomy'] );
				}

	 			wp_delete_object_term_relationships( $term_id, $this->xili_settings['taxolangsgroup'] );

				// link_language links
				$links = get_objects_in_term( array( $term_id ), array( 'link_'.$this->xili_settings['taxonomy'] ) );

				foreach ( $links as $link ) {
					wp_delete_object_term_relationships( $link, 'link_'.$this->xili_settings['taxonomy'] );
				}

				// delete terms
				$linklang = term_exists($language->slug,'link_'.$this->xili_settings['taxonomy']);
				if ( $linklang ) wp_delete_term( $term_id, 'link_'.$this->xili_settings['taxonomy'] );
				wp_delete_term( $term_id, $this->xili_settings['taxonomy'] );

			}
			$term_group = term_exists( 'ev_er', 'link_'.$this->xili_settings['taxonomy'] ); /* special ever language for links */
			// link_language links
			$links = get_objects_in_term( array( $term_group['term_id'] ), array( 'link_'.$this->xili_settings['taxonomy'] ) );

			foreach ( $links as $link ) {
				wp_delete_object_term_relationships( $link_id, 'link_'.$this->xili_settings['taxonomy'] );
			}
			wp_delete_term( $term_group['term_id'], 'link_'.$this->xili_settings['taxonomy'] );

			// delete taxonomie groups ['taxolangsgroup'] - when count = 0
			$thegroup = get_terms( TAXOLANGSGROUP, array('hide_empty' => false,'slug' => 'the-langs-group', 'get' => 'all' ) );
			wp_delete_term( $thegroup[0]->term_id, TAXOLANGSGROUP );

			delete_option('xili_language_widgets_options');
			delete_option('xili_widget_recent_comments');
			delete_option('xili_widget_recent_entries');
			delete_option('xili_language_settings');
		}
	}

	// used by customize preview features - 2.8.7
	function get_xili_language_options () {
		return get_option('xili_language_settings', $this->initial_settings() );
	}

	function add_action ( $action, $function = '', $priority = 10, $accepted_args = 1 )
	{
		add_action ($action, array (&$this, $function == '' ? $action : $function), $priority, $accepted_args);
		$this->idx[$action] = _wp_filter_build_unique_id($action, array (&$this, $function == '' ? $action : $function), $priority); /* unique id of this filter from object */
	}

	function add_filter ( $filter, $function = '', $priority = 10, $accepted_args = 1 )
	{
		add_filter ($filter, array (&$this, $function == '' ? $filter : $function), $priority, $accepted_args);
		$this->idx[$filter] = _wp_filter_build_unique_id($filter, array (&$this, $function == '' ? $filter : $function), $priority); /* unique id of this filter from object fixed 1.0.1 */
	}

	/**
	 * More than one filter for the function.
	 *
	 * @since 0.9.7
	 * @update renamed from this_has_filter
	 * @param $the_function (string).
	 * @return true if more than one.
	 */
	function this_has_external_filter( $the_function ) {
		global $wp_filter;
		if ( !isset ( $wp_filter[$the_function]) ) return false; // avoid php warning 2.3.0
		$has = $wp_filter[$the_function];

		if ( ! is_array( $has ) ) return false; // avoid php warning 2.1.0
		$keys = array_keys($has);

		if ( count($has[$keys[0]]) >= 2 ) { /*one from class others from functions.php or elsewhere*/
			return true;
		} else {
			return false;
		}
	}

	/**
	 * for wpmu
	 * register functions must be called by init
	 *
	 * @since 1.5.1
	 *
	 */
	function init_and_register_language_post_taxonomy () {
		/** add new taxonomy in available taxonomies
		 * 1.0.2 - add label false as http://core.trac.wordpress.org/ticket/10437
		 * 			to avoid metabox as tag displayed  , 'query_var' => QUETAG
		 * @updated 1.4.1
		 */

		if ( $this->xili_settings['wp_locale'] == 'wp_locale' )
			xiliml_declare_xl_wp_locale ();

		if ( $this->lang_perma ) {

			register_taxonomy( TAXONAME, 'post', array('hierarchical' => false, 'label' => false, 'rewrite' => false , 'update_count_callback' => array(&$this, '_update_post_lang_count'), 'show_ui' => false, '_builtin' => false, 'query_var' => QUETAG ));
			$this->lpr = "-"; // 2.3.2

		} else {
			add_filter('query_vars', array(&$this,'keywords_addQueryVar')); // now in taxonomy decl. // 2.1.1
			register_taxonomy( TAXONAME, 'post', array('hierarchical' => false, 'label' => false, 'rewrite' => false , 'update_count_callback' => array(&$this, '_update_post_lang_count'), 'show_ui' => false, '_builtin' => false ));

		}

		register_taxonomy( TAXOLANGSGROUP, 'term', array('hierarchical' => false, 'update_count_callback' => '', 'show_ui' => false, 'label'=>false, 'rewrite' => false, '_builtin' => false ));


	}

	/**
	 * since 2.6
	 *
	 */
	function init_vars () {
		// type of languages list see options in xili_language_list or navmenu - third param = title for option

		$this->langs_list_options = array(
			array('', __('default', 'xili-language'),  __('default title', 'xili-language')),
			array('typeone', __('Type #1', 'xili-language'), __('Type #1 title', 'xili-language')),
			array('typeonenew', __('Type for single', 'xili-language'), __('Type for single title', 'xili-language')),
			array('navmenu', __('Nav Menu', 'xili-language'), __('List of all languages are inserted and links redirect to home.', 'xili-language')),
			array('navmenu-a', __('Nav Menu (w/o current lang.)', 'xili-language'), __('Current language is not inserted and links redirect to home.', 'xili-language')) ,
			array('navmenu-1', __('Nav Menu Singular', 'xili-language'), __('List of all languages are inserted and links redirect to post or page if exists in other languages.', 'xili-language')),
			array('navmenu-1a', __('Nav Menu Singular (w/o current lang.)', 'xili-language'), __('Current language is not inserted and links redirect to post or page if exists in other languages.', 'xili-language')),
			array('navmenu-1ao', __('Nav Menu Singular (w/o curr. lang and if exists)', 'xili-language'), __('Current language is not inserted and links appears and redirect to post or page if exists in other languages.', 'xili-language'))
		);

		/* default values */
		if ( ''!= WPLANG && ( strlen( WPLANG )==5 || strlen( WPLANG ) == 2 ) ) : // for japanese
			$this->default_lang = WPLANG;
		else:
			$this->default_lang = 'en_US';
		endif;
		// cache_domain added to avoid annoying caches 2.8.4
		$cache_suffix = ( $this->class_admin ) ? "_ad" : "";
		$thegroup = get_terms(TAXOLANGSGROUP, array('hide_empty' => false,'slug' => 'the-langs-group', 'cache_domain' => 'core1' . $cache_suffix));

		if ( array() == $thegroup ) { /* update langs group 0.9.8 and if from start 2.3.1 */
			$args = array( 'alias_of' => '', 'description' => 'the group of languages', 'parent' => 0, 'slug' =>'the-langs-group');
			wp_insert_term( 'the-langs-group', TAXOLANGSGROUP, $args); /* create and link to existing langs */
			$list_languages = get_terms(TAXONAME, array('hide_empty' => false, 'get' => 'all', 'cache_domain' => 'core1' . $cache_suffix));



			if ( array() == $list_languages ) { /*create two default lines with the default language (as in config)*/
		  		/* language of WP */
		  		if (!class_exists('GP_Locales'))
		  			require_once ( $this->plugin_path .'/xili-includes.locales.php' );

				$term = 'en_US';
				$args = array( 'alias_of' => '', 'description' => 'english', 'parent' => 0, 'slug' =>'en_us');

				$theids = $this->safe_lang_term_creation ( $term, $args );
				if ( ! is_wp_error($theids) ) {
					wp_set_object_terms($theids['term_id'], 'the-langs-group', TAXOLANGSGROUP);
					$this->xili_settings['lang_features']['en_us'] = array('charset'=>"",'hidden'=>"");
				}
				$term = $this->default_lang;

				$locale_2 = GP_Locales::by_field( 'wp_locale', $term );

		 		$desc = ( $locale_2 ) ? $locale_2->english_name : $this->default_lang;
				$desc_array = explode (' (', $desc );
		 		$desc = $desc_array[0];

		 		$slug = strtolower( $this->default_lang ) ; // 2.3.1
		 		if ( !defined('WPLANG') || $this->default_lang == 'en_US' || $this->default_lang == '' ) {
		 			$term = 'fr_FR'; $desc = 'french'; $slug = 'fr_fr' ;
		 		}
		 		$args = array( 'alias_of' => '', 'description' => $desc, 'parent' => 0, 'slug' => $slug);

		 		$theids = $this->safe_lang_term_creation ( $term, $args ) ;
				if ( ! is_wp_error($theids) ) {
					wp_set_object_terms($theids['term_id'], 'the-langs-group', TAXOLANGSGROUP);
					$this->xili_settings['lang_features'][$slug] = array('charset'=>"",'hidden'=>"");

		 		}

				update_option( 'xili_language_settings', $this->xili_settings );

				$list_languages = get_terms(TAXONAME, array('hide_empty' => false, 'get' => 'all', 'cache_domain' => 'core2'.$cache_suffix));

			}
			foreach( $list_languages as $language ) {
				wp_set_object_terms( $language->term_id, 'the-langs-group', TAXOLANGSGROUP );
			}
			$thegroup = get_terms( TAXOLANGSGROUP, array('hide_empty' => false,'slug' => 'the-langs-group', 'get' => 'all', 'cache_domain' => 'core2'.$cache_suffix) );
		} else {
			// if created by XD - update
			$list_languages = get_terms(TAXONAME, array('hide_empty' => false, 'get' => 'all', 'cache_domain' => 'core2'.$cache_suffix));
			$i = 0;
			foreach( $list_languages as $language ) {
				if ( ! isset ( $this->xili_settings['lang_features'][$language->slug] ) ) {
					$this->xili_settings['lang_features'][$language->slug] = array('charset'=>"",'hidden'=>"");
					$i++;
				}
			}
			if ( $i > 0) update_option( 'xili_language_settings', $this->xili_settings );
		}

		$this->langs_group_id = $thegroup[0]->term_id;
		$this->langs_group_tt_id = $thegroup[0]->term_taxonomy_id;

		if ( is_child_theme() ) { // 1.8.1 - 1.8.5
			if ( $this->xili_settings['langs_in_root_theme'] == 'root' ) {
				$this->get_template_directory = get_template_directory();
			} else {
				$this->get_template_directory = get_stylesheet_directory();
			}
			$this->get_parent_theme_directory = get_template_directory();

		} else {
			$this->get_template_directory = get_template_directory();
			$this->get_parent_theme_directory = '';
		}

		$this->get_lang_slug_ids(); // default array of languages slug=>id and slug=>name // 2.4.2

		if ( $this->show_page_on_front ) $this->get_show_page_on_front_array();
		/* here because taxonomy is registered : since 1.5.3 */

		$this->default_slug = $this->get_default_slug(); /*no constant for wpmu */
		if (!is_multisite() && !defined('DEFAULTSLUG')) define('DEFAULTSLUG',$this->default_slug); /* for backward compatibility */

		if ( $dir = get_bloginfo('text_direction') ) /* if present in blog options @since 0.9.9 */
			$this->default_dir = $dir;

		// 1.8.4
		if ( array() == $this->xili_settings['available_langs'] ) {

			$this->xili_settings['available_langs'] = $this->get_lang_ids() ;
			update_option('xili_language_settings', $this->xili_settings);
		}
	}

	/**
	 * Safe language term creation
	 *
	 * @since 2.4.1
	 */
	 function safe_lang_term_creation ( $term, $args ) {
	 	global $wpdb ;
		// test if exists with other slug or name
		if ( $term_id = term_exists( $term ) ) {
			$existing_term = $wpdb->get_row( $wpdb->prepare( "SELECT name, slug FROM $wpdb->terms WHERE term_id = %d", $term_id), ARRAY_A );
			if ( $existing_term['slug'] != $args['slug'] ) {
				$res = wp_insert_term( $term.'xl', TAXONAME, $args); // temp insert with temp other name
				$args['name'] = $term ;
				$res = wp_update_term( $res['term_id'], TAXONAME, $args);
			} else {
				return new WP_Error('term_exists', __('A term with the name provided already exists.'), $term_id );
			}
		} else {
			$res = wp_insert_term( $term, TAXONAME, $args);
		}
		if (is_wp_error($res)) {
			return $res ;
		} else {
			$theids = $res;
		}
		return $theids ;
	 }

	/**
	 * Get list language Objects - designed and used to avoid query by using settings
	 *
	 * @since 1.6.0
	 * @param $force to avoid buffer
	 * @return array of objects
	 */
	function get_listlanguages( $force = false ) {
		if ( $this->xili_settings['langs_list_status'] != "set" || $force === true ) {
	 		$listlanguages = get_terms_of_groups_lite ( $this->langs_group_id, TAXOLANGSGROUP,TAXONAME, 'ASC' );
	 		if ( $listlanguages ) {
	 			$this->xili_settings['languages_list'] = $listlanguages;
	 			$do = ( $this->xili_settings['langs_list_status'] != "set" ) ? true : $this->xili_settings['languages_list'] != $listlanguages ;
	 			$this->xili_settings['langs_list_status'] = "set";
	 			if ( is_admin() && $do && false !== $this->class_admin && $this->xili_settings['languages_list'] != $listlanguages )  {
	 				update_option('xili_language_settings', $this->xili_settings);
	 			}
	 		}
	 		return $listlanguages;
		} else {
			return $this->xili_settings['languages_list'];
		}
	}

	/**
	 * Get list language IDs
	 *
	 * @since 1.8.4
	 */
	function get_lang_ids() {

		$lang_ids = array() ;
		$listlanguages = get_terms_of_groups_lite ($this->langs_group_id,TAXOLANGSGROUP,TAXONAME,'ASC');
		foreach ( $listlanguages as $lang) {
			$lang_ids[] = $lang->term_id;
		}
		return $lang_ids ;
	}

	/**
	 * Get list languages slug_IDs
	 *
	 * @since 2.1.1
	 */
	function get_lang_slug_ids( $flush = 'null') {
		$this->alias_mode = ( has_filter ( 'alias_rule', 'xili_language_trans_slug_qv' ) ) ? true : false  ;
		$lang_slugs = array() ;
		$lang_names = array() ;
		$lang_full_names = array() ;
		$listlanguages = $this->get_listlanguages( true );
		$langs_shortqv_slug = array();
		$langs_slug_shortqv = array();
		$do = false;
		foreach ( $listlanguages as $lang) {
			$key = $lang->slug;
			$lang_slugs[$key] = $lang->term_id;
			$lang_names[$key] = $lang->name;
			$lang_full_names[$key] = $lang->description;


			if ( $this->alias_mode ) {
				$short = ( isset ( $this->xili_settings['lang_features'][$key]['alias'] ) ) ?  $this->xili_settings['lang_features'][$key]['alias'] : $key ;
				$langs_slug_shortqv[$key] = $short;
				if ( '' != $short ) {
					$langs_shortqv_slug[$short] = $key;
				}
			} else {
				if ( isset( $this->xili_settings['lang_features'][$key]['alias'] ) ) {
					unset ( $this->xili_settings['lang_features'][$key]['alias'] ); // need to be rebuilt when theme changed w/o alias mode
					$do = true;
				}
			}
		}

		$this->langs_ids_array = $lang_slugs;
		$this->langs_slug_name_array = $lang_names;
		$this->langs_slug_fullname_array = $lang_full_names;

		$this->xili_settings['langs_ids_array'] = $lang_slugs;

		if ( $this->alias_mode ) {
			// 2.8.2
			$this->langs_slug_shortqv_array = $langs_slug_shortqv;
			$this->langs_shortqv_slug_array = $langs_shortqv_slug;
			$do = ( $this->xili_settings['shortqv_slug_array'] != $langs_shortqv_slug ) ? true : false ;
			$this->xili_settings['shortqv_slug_array'] = $langs_shortqv_slug;  // used by permalinks

		} else {
			$this->langs_slug_shortqv_array = array ();
			$this->langs_shortqv_slug_array = array ();
		}

		if ( is_admin () && $do && !$this->class_admin ) {
			update_option( 'xili_language_settings', $this->xili_settings );
		}
		// 2.9.21 - 22
		if ( $flush == 'edited' &&  $this->is_permalink ) flush_rewrite_rules( false );
	}

	/**
	 * lang query var translator
	 *
	 * 2.8.2
	 *
	 */
	function lang_qv_slug_trans ( $lang_qv ){
		if ( count ( $langs = explode ( ',', $lang_qv ) ) > 1 ) $lang_qv = $langs[0]; // if multilanguage query: keep first 2.8.8

		$lang_qv = wp_kses( $lang_qv, array() ); // fixes security xss  - 2.8.6
		$lang_qv = preg_replace( '/[^a-z0-9_\-]/', '', $lang_qv );
		if ( isset( $this->langs_shortqv_slug_array[$lang_qv]) ) {
			return $this->langs_shortqv_slug_array[$lang_qv];
		} else {
			return $lang_qv;
		}
	}

	/**
	 * lang slug translator
	 *
	 * 2.8.2
	 *
	 */
	function lang_slug_qv_trans ( $lang_slug ){
		return apply_filters ( 'alias_rule', $lang_slug );
	}

	/**
	 * recover alias settings of previous used theme if exists
	 *
	 * 2.11.1
	 *
	 */
	function recover_alias_settings_of_previous_used_theme ( $theme ){
		if ( isset ( $this->xili_settings['theme_alias_cache'][$theme] ) ) {
			foreach ( $this->xili_settings['theme_alias_cache'][$theme] as $slug => $value ){
				$this->xili_settings['lang_features'][$slug]['alias'] = $value;
			}
		}
	}

	/**
	 * get show pages on front
	 *
	 * @since 2.1.1
	 *
	 *
	 * update $this->show_page_on_front_array array
	 */
	function get_show_page_on_front_array() {

		$front_pages_array = array();
		$front_pages_for_posts_array = array();
		$front_pages_for_posts_name_array = array(); // 2.8.4.2

		$languages = $this->get_listlanguages( is_admin () );
		$front_page_id = $this->get_option_wo_xili ('page_on_front');
		$this->page_for_posts_original = true;
		$front_page_for_posts_id = get_option( 'page_for_posts' );
		$this->page_for_posts_original = false;
		foreach ( $languages as $lang) {
			$key = $lang->slug;
			$id = get_post_meta ( $front_page_id, QUETAG.'-'.$key, true );
			$page_id = ( ''!= $id ) ? $id : $front_page_id ;
			$front_pages_array[$key] = $page_id;
			// page_for_posts
			$id = get_post_meta ( $front_page_for_posts_id, QUETAG.'-'.$key, true );
			$page_id = ( ''!= $id ) ? $id : 0 ;
			if ( $page_id > 0 )
				$front_pages_for_posts_array[$key] = $page_id;
			if ( $this->is_permalink && $page_id > 0 ) {

				$pagecontent = get_page ($page_id);
				$front_pages_for_posts_name_array[$key] = $pagecontent->post_name ;
				$this->page_for_posts_name_to_id_array[$pagecontent->post_name] = $page_id;
			}
		}

		$this->show_page_on_front_array = $front_pages_array;
		$this->page_for_posts_array = $front_pages_for_posts_array;
		if ( $this->is_permalink ) {
			$this->page_for_posts_name_array = $front_pages_for_posts_name_array;
		}

		 // debug temp
		$do = ( !isset ( $this->xili_settings['show_page_on_front_array'] ) || $this->xili_settings['show_page_on_front_array'] != $front_pages_array ) ? true : false ;

		$this->xili_settings['show_page_on_front_array'] = $front_pages_array;

		if ( is_admin () && $do ) update_option( 'xili_language_settings', $this->xili_settings );
	}

	/**
	 *
	 *
	 */
	function get_post_language ( $post_ID, $result = 'slug' ) {
		$ress = wp_get_object_terms($post_ID, TAXONAME);
		if ( $ress ) {
			$obj_term = $ress[0]; // today only one language per post

			switch ( $result ) {
				case 'iso':
					$postlang = ('' != $obj_term->name) ? $obj_term->name : "";
					break;
				case 'name':
					$postlang = ('' != $obj_term->description) ? $obj_term->description : "";
					break;
				default: // slug
					$postlang = ('' != $obj_term->slug) ? $obj_term->slug : "";
			}
		} else {
			$postlang = "";
		}
		return $postlang;
	}

	/**
	 * Create a linked copy of current post in target language
	 *
	 * @since 2.5
	 *
	 */
	function create_initial_translation ( $target_lang, $from_post_title = "" , $frompostlang = "", $from_post_ID ) {
		global $user_ID;

		$post_title_prefix = sprintf ( __('Please translate in %s:', 'xili-language' ), $target_lang );
		$target_post_title = ( $from_post_title == '' ) ? $post_title_prefix . ' ' . $from_post_ID : $post_title_prefix . ' ' . $from_post_title ;

		$post_type = get_post_type( $from_post_ID );

		$params = array('post_status' => 'draft', 'post_type' => $post_type, 'post_author' => $user_ID,
			'ping_status' => get_option('default_ping_status'), 'post_parent' => 0,
			'menu_order' => 0, 'to_ping' =>  '', 'pinged' => '', 'post_password' => '',
			'guid' => '', 'post_content_filtered' => '', 'post_excerpt' => '', 'import_id' => 0,
			'post_content' => $target_post_title, 'post_title' => $target_post_title);

		$post_ID = wp_insert_post( $params ) ;

		if ( $post_ID != 0 ) {
			// taxonomy
			wp_set_object_terms( $post_ID, $target_lang, TAXONAME );
			// metas
			// from
			update_post_meta( $from_post_ID, QUETAG.'-'.$target_lang, $post_ID );

			// this with other target of from
			$listlanguages = $this->get_listlanguages () ;
			foreach ( $listlanguages as $language ) {
				if ( $language->slug != $target_lang && $language->slug != $frompostlang ) {
					$id = get_post_meta( $from_post_ID, QUETAG.'-'.$language->slug, true );
					if ( $id != "" ) {
						update_post_meta( $post_ID, QUETAG.'-'.$language->slug, $id );
					}
				}
			}
			// this
			update_post_meta( $post_ID, QUETAG.'-'.$frompostlang, $from_post_ID );
			update_post_meta( $post_ID, $this->translation_state, "initial" ); // to update further slug - post_name

			do_action ( 'xl_propagate_post_attributes', $from_post_ID, $post_ID ); // to personalize features - see class theme-multilingual-classes

			return $post_ID;
		}
	}

	/**
	 * propagate categories from a reference post to another (loop is in class admin)
	 * if cat is not in target, cat will be unassigned in target if mode is 'erase'
	 *
	 * @since 2.6
	 *
	 */
	function propagate_categories ( $from_post_ID, $post_ID, $mode = '' ) {
		// categories of from_post
		// get
		$categories = get_the_category( $from_post_ID );
		if ( ! empty( $categories ) ) {
			if ( is_object_in_taxonomy( get_post_type( $from_post_ID ), 'category' ) ) {
				// set
				$the_cats = array();
				foreach ( $categories as $category ) {
					$the_cats[] = $category->slug; // wp_set_object_terms don't like loop
				}
				// prepare target (erase all)
				if ( $mode == 'erase' ) wp_delete_object_term_relationships ( $post_ID, 'category' );
				wp_set_object_terms( $post_ID, $the_cats, 'category' );
			}
		}
	}

	/**
	 * return post object
	 *
	 * @since 2.5
	 *
	 */
	function temp_get_post ( $post_id ) {
		global $wpdb ;
		$res = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d LIMIT 1", $post_id));
		if ( $res && !is_wp_error($res) )
			return $res;
		else
			return false;
	}

	/**
	 * return saved list of post_type and custom_post_type
	 *
	 * @since 2.5
	 *
	 */
	function authorized_custom_post_type () {

		$custompoststype = $this->xili_settings['multilingual_custom_post'] ;
		$custom = get_post_type_object ('post');
		$clabels = $custom->labels;
		$custompoststype['post'] = array( 'name' => $custom->label , 'singular_name' => $clabels->singular_name , 'multilingual' => 'enable');
		$custom = get_post_type_object ('page');
		$clabels = $custom->labels;
		$custompoststype['page'] = array( 'name' => $custom->label, 'singular_name' => $clabels->singular_name , 'multilingual' => 'enable');
		return $custompoststype;
	}

	/**
	 * Will update term count based on posts AND pages.
	 * called from from register taxonomy etc...
	 * @access private
	 * @since 0.9.8.1
	 * @uses $wpdb
	 *
	 * @param array $terms List of Term taxonomy IDs
	 */
	function _update_post_lang_count( $terms ) {
		global $wpdb;
		foreach ( (array) $terms as $term ) {
			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND post_status = 'publish' AND term_taxonomy_id = %d", $term ) );
			$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
		}
	}

	/**
	 * Return language dir
	 *
	 * @since 0.9.9
	 * @param slug of lang
	 */
	function get_dir_of_cur_language( $lang_slug ) {
		$rtlarray = explode ('-',$this->rtllanglist);
		$dir = ( in_array(substr(strtolower($lang_slug), 0, 2 ),$rtlarray) ) ? 'rtl' : 'ltr';
		return $dir;
	}

	/**
	 * Insert rtl.css if exists (default filter of wp_head) - see theme.php (Thanks Sam R.)
	 *
	 * @since 2.4.0
	 */
	function change_locale_stylesheet_uri ( $stylesheet_uri,  $stylesheet_dir_uri ) {
		$rtlarray = explode ('-', $this->rtllanglist);
		$dir = ( in_array( substr( strtolower( $this->curlang ), 0, 2 ), $rtlarray ) ) ? 'rtl' : 'ltr';
		$dircss = get_stylesheet_directory();
		// avoid with locale.css
		if ( $stylesheet_uri == '' || false !== strpos($stylesheet_uri, 'rtl.css'  ) || false !== strpos($stylesheet_uri, 'ltr.css'  ) ) {
			if ( in_array ( substr( $this->curlang, 0, 2 ) , $rtlarray ) ) {

				if ( file_exists("$dircss/{$dir}.css") ) {
					return $stylesheet_dir_uri."/{$dir}.css";
				} else {
					return '';
				}

			}
		}
		return $stylesheet_uri ; // non filtered value
	}

	/**
	 * Return language of post.
	 *
	 * @since 0.9.0
	 * @updated 0.9.7.6, 0.9.9
	 *
	 * @param $post_ID.
	 * @return slug of language of post or false if var langstate is false.
	 */
	function get_cur_language( $post_ID ) {
		$ress = wp_get_object_terms($post_ID, TAXONAME);
		if ($ress) {
			if (is_a($ress, 'WP_Error')){
				echo "Language table not created ! see plug-in admin";
				$this->langstate = false;
			} else {
				$obj_term = $ress[0];
				$this->langstate = true;
				$postlang = $obj_term->slug;
				$postlangdir = $this->get_dir_of_cur_language($postlang);
				return array( QUETAG => $postlang, 'direction' => $postlangdir);
			}
	 	} else {
	 		$this->langstate = false; /* can be used in language attributes for header */
	  		return false;	/* undefined state */
	 	}
	}

	/** enable the new query tag associated with new taxonomy */
	function keywords_addQueryVar( $vars ) {
		$vars[] = QUETAG;
	return $vars ;
	}

	function get_default_slug() {
		$listlanguages = get_terms(TAXONAME, array('hide_empty' => false));
		$default_slug = 'en_us';
		foreach ($listlanguages as $language) {
			if ($language->name == $this->default_lang ) return $language->slug;
		}
		return $default_slug ;
	}

	/**
	 * Query join filter used when querytag is used or home
	 *
	 * @updated 1.7.0 modify page on front and home query
	 * @updated 1.8.4 * to select posts with undefined lang
	 * @updated 2.2.3 LANG_UNDEF = . and no * xili_xl_
	 *
	 */
	function posts_join_with_lang( $join, $query_object = null ) {
		global $wpdb, $wp_query;
		xili_xl_error_log ( '======= 1268 =======' . serialize($query_object->query_vars));
		$insert_join = false ;
		if ( isset ( $query_object->query_vars[QUETAG] ) && '' != $query_object->query_vars[QUETAG] ) {

			if ( ( isset ( $query_object->query_vars['caller_get_posts'] ) && $query_object->query_vars['caller_get_posts']) || ( isset ( $query_object->query_vars['ignore_sticky_posts'] ) && $query_object->query_vars['ignore_sticky_posts']) ) {
				$insert_join = false ;
			} else {

				if ( !($query_object->is_home && $this->show_page_on_front )) { // join if no front-page and other page
					if ( empty( $query_object->query_vars['post_type'] ) )  { // string or array
						$a = $query_object->query_vars['page_id'];
						$b = get_option('page_on_front');
						if ( $a == $b && $a !='' ) {
							$insert_join = false;
						} else {
							$insert_join = true; // as in cat or home+lang
						}
					} else { //  test only post_type
						if ( is_admin () || in_array ( $query_object->query_vars['post_type'], array_keys ($this->authorized_custom_post_type () ) ) )
							$insert_join = true;
					}

				} elseif ( $query_object->is_home && !$this->show_page_on_front ) { // home and lang
					$insert_join = true ;
				} else {
					$insert_join = true ;
				}
				if ( isset( $query_object->query_vars['post_format'] )  ) {
					$insert_join = true ;
					if ( false === strpos( $join, 'wp_term_relationships ON' ))
						$join .= " INNER JOIN wp_term_relationships ON ( wp_posts.ID = wp_term_relationships.object_id ) ";
				}

				xili_xl_error_log ('before lang_perma (insert_join) *** #1301 *** ' . serialize($insert_join));

				$this->xili_test_lang_perma ();
				if ( $this->lang_perma  ){ // basic post
					if ( empty($query_object->query_vars['post_type']) && $query_object->query_vars['category_name'] == ""  && isset( $query_object->query_vars[QUETAG])  && $this->show_page_on_front && !is_admin()) {
						$join = ''; // page linked on front page
						$insert_join = false ;
						unset($wp_query->queried_object); // to avoid notice in get_page_template and force get_queried_object -2.3.2
					}
					xili_xl_error_log ('lang_perma (insert_join) *** #1310 *** ' . serialize($insert_join));

					if ( $query_object->is_tax && $query_object->query_vars['category_name'] != '' ) $insert_join = true ;
					if ( $query_object->is_search &&  $query_object->query_vars['s'] != '' ) $insert_join = true ; // 2.8.7
					if ( $query_object->is_date ) $insert_join = true ;

				}
				xili_xl_error_log ('lang_perma (insert_join) *** #1317 *** ' . serialize($insert_join));
			}

		} else { // join if home and modify home according rule ignore_sticky_posts

			if ( ''== $this->sublang ) {

				if ( ( isset ( $query_object->query_vars['caller_get_posts'] ) && $query_object->query_vars['caller_get_posts']) || ( isset ( $query_object->query_vars['ignore_sticky_posts'] ) && $query_object->query_vars['ignore_sticky_posts']) ) {

				} else {
					if ( ( $query_object->is_home && $query_object->is_posts_page) && $this->xili_settings['pforp_select'] != 'no_select' || ( $query_object->is_home && $this->xili_settings['homelang'] == 'modify') || $query_object->query_vars['ignore_sticky_posts']) {

						if ( '' == $query_object->query_vars['post_type'] || in_array ( $query_object->query_vars['post_type'], array_keys ($this->authorized_custom_post_type () ) ) )
								$insert_join = true ; 											}
				}
			}
		}
xili_xl_error_log ('ici - join (#1326) = ' . serialize($insert_join) );

		if ( $insert_join ) {
			$join .= " LEFT JOIN $wpdb->term_relationships as xtr ON ($wpdb->posts.ID = xtr.object_id) LEFT JOIN $wpdb->term_taxonomy as xtt ON (xtr.term_taxonomy_id = xtt.term_taxonomy_id) ";
		}
		return $join;
	}

	/**
	 * to detect undefined query and unset language tax query
	 * @since 2.2.3 - LANG_UNDEF
	 *
	 */
	function posts_search_filter ( $search, $the_query) {
		$this->sublang = "";

		if ( isset( $the_query->query_vars[QUETAG] ) && false !== strpos( $the_query->query_vars[QUETAG] , LANG_UNDEF ) ) {


			if ( isset ( $the_query->tax_query->queries ) && array() != $the_query->tax_query->queries ) {
				$new_queries = array();
				foreach ( $the_query->tax_query->queries as $query ) {
					if ( $query['taxonomy'] != TAXONAME ) {
						$new_queries[] = $query ;
					}
				}
				$the_query->tax_query->queries = $new_queries;
			}
			$this->sublang = $the_query->query_vars[QUETAG]; // to adapt in where filter below
			unset ( $the_query->query_vars[QUETAG] );
			unset ( $the_query->tax_query->relation );
			if ( isset ( $the_query->tax_query->queries ) && array() == $the_query->tax_query->queries ) {
				$the_query->is_tax = false ;
			}
		}
		return $search;
	}

	/**
	 * Modify the query including lang or home
	 *
	 * @since 0.9.0
	 * @updated 0.9.4 (OR added) lang=xx_xx,yy_yy,..
	 * @updated 1.7.0 modify page on front and home query
	 * @updated 2.2.3 LANG_UNDEF
	 *
	 * @param object $where.
	 * @return $where.
	 */
	function posts_where_lang( $where, $query_object = null ) {
		global $wpdb, $wp_query;
		$reqtags = array();
		$thereqtags = array();

		if ( isset ( $query_object->query_vars[QUETAG] )) xili_xl_error_log( 'where #1387 = ' . $query_object->query_vars[QUETAG] );

		if ( "" != $this->sublang )  { // see above

		    $lang =  str_replace ( LANG_UNDEF, '' , $this->sublang ); //$query_object->query_vars[QUETAG] ) ;
		    if ( "" == $lang ) {
				$lang_string = implode( ", ", $this->xili_settings['available_langs'] );
		    } else {
		    	$id = ( isset ( $this->langs_ids_array[ $lang ] ) ) ? $this->langs_ids_array[ $lang ] : 0 ;
		    	if ( $id > 0 ) {
		    		$remain = array_diff( $this->xili_settings['available_langs'], array($id));
		    		$lang_string = implode( ", ", $remain  );
		    	} else {
		    		$lang_string = implode( ", ", $this->xili_settings['available_langs'] );
		    	}
		    }

			$where .= " AND $wpdb->posts.ID NOT IN ( SELECT xtr.object_id FROM $wpdb->term_relationships AS xtr INNER JOIN $wpdb->term_taxonomy AS xtt ON xtr.term_taxonomy_id = xtt.term_taxonomy_id WHERE xtt.taxonomy = '".TAXONAME."' AND xtt.term_id IN ($lang_string) )";

		} elseif ( isset ($query_object->query_vars[QUETAG]) && '' != $query_object->query_vars[QUETAG] ) {

			$do_it = false;
			if ( (isset ( $query_object->query_vars['caller_get_posts'] ) && $query_object->query_vars['caller_get_posts']) || ( isset ( $query_object->query_vars['ignore_sticky_posts'] ) && $query_object->query_vars['ignore_sticky_posts']) ) {
				$do_it = false;
			} else {

				if ( $this->lang_perma && !is_admin ()) {
					if ( $query_object->is_page && isset( $query_object->query_vars[QUETAG]) ) {

					} elseif (!($query_object->is_home && $this->show_page_on_front )) {

						$do_it = true;
					}
					if ( $query_object->is_tax &&  $query_object->query_vars['taxonomy'] == 'category' ) {
						$do_it = true;
					}
					if ( $query_object->is_tax &&  $query_object->query_vars['taxonomy'] == TAXONAME && "" == $query_object->query_vars['category_name'] ) {
						$do_it = false;
						if ( !$query_object->is_page ) {
							$where .= " AND $wpdb->posts.post_type = 'post'";
						}
					}
				} else {
				    if ( !( $query_object->is_home && $this->show_page_on_front ) ) {
						$do_it = true; // all but not home
					}
				}
			}

xili_xl_error_log ('ici - doit where (#1436) = ' . serialize($do_it) );
			if ($do_it) { // insertion of selection

				if ( strpos($query_object->query_vars[QUETAG], ',') !== false ) {
					$langs = preg_split('/[,\s]+/', $query_object->query_vars[QUETAG]);
					foreach ( (array) $langs as $lang ) {
						$lang = sanitize_term_field('slug', $lang, 0, 'post_tag', 'db');
						$reqtags[]= $lang;
					}
					foreach ($reqtags as $reqtag){
						$thereqtagids[] = $this->langs_ids_array[$reqtag];
					}
					$wherereqtag = implode(", ", $thereqtagids);
					$where .= " AND xtt.taxonomy = '".TAXONAME."' ";
					$where .= " AND xtt.term_id IN ( $wherereqtag )";

				} else {
					$query_object->query_vars[QUETAG] = sanitize_term_field('slug', $query_object->query_vars[QUETAG], 0, 'post_tag', 'db');
					$reqtag = $query_object->query_vars[QUETAG];
					$wherereqtag = $this->langs_ids_array[ $this->lang_qv_slug_trans($reqtag) ];

					if ( ! isset( $query_object->query_vars['post_format'] )  ) {
						/* only one lang */
						xili_xl_error_log ( $query_object->query_vars['page_id'] . ' <-----+++**** 1449 where blog page_for_posts ' .  $query_object->query_vars[QUETAG] . '-' . get_option('page_on_front'));

						$where .= " AND xtt.taxonomy = '".TAXONAME."' ";
						$where .= " AND xtt.term_id = $wherereqtag ";
					} else {
						$where .= " AND xtt.taxonomy = '".TAXONAME."' ";
						$where .= " AND xtt.term_id = $wherereqtag ";
					}
				 }

			} else { // is_home and page

				if ( $query_object->is_home && $this->show_page_on_front ) {
					$query_object->is_home = false ; // renew the values because the query contains lang=
					$query_object->is_page = true ;
					$query_object->is_singular = true ;
					$query_object->query = array();
					$query_object->query_vars['page_id'] = get_option('page_on_front'); // new filtered value

					$where = str_replace ("'post'","'page'",$where); // post_type =
					$where .= " AND 3=3 AND {$wpdb->posts}.ID = " . $query_object->query_vars['page_id'];
					xili_xl_error_log ( $query_object->query_vars['page_id'] . ' <-----+++**** 1471 where home ' .  $query_object->query_vars[QUETAG] . '-' . get_option('page_on_front'));
				}

				if ( $this->lang_perma && $this->show_page_on_front) { // 2.1.1

					if ( $query_object->query_vars[QUETAG] != "" && isset ( $query_object->query_vars['taxonomy'] ) && $query_object->query_vars['taxonomy'] == TAXONAME  ) {

						$query_object->is_page = true ;
						$query_object->is_tax = false ;
						$query_object->is_archive = false ;
						$query_object->is_singular = true ;

						$query_object->query = array();
						$pid = $this->get_option_wo_xili ('page_on_front') ;

						$lang = ( isset ( $query_object->query_vars[QUETAG] ) ) ? $this->lang_qv_slug_trans ( $query_object->query_vars[QUETAG]) : 'en_us' ;

						$id = get_post_meta ( $pid, QUETAG.'-'.$lang, true );
						$pagid = ( ''!= $id ) ? $id : $pid ;
						$query_object->query_vars['page_id'] = $pagid ;

						unset ( $query_object->query_vars['taxonomy'] );

						$where = str_replace ("'post'","'page'",$where); // post_type =
						$where = "AND {$pagid} = {$pagid} "." AND {$wpdb->posts}.ID = " . $query_object->query_vars['page_id'] . " AND {$wpdb->posts}.post_type = 'page'";

						$query_object->query_vars['page_id'] = get_option ('page_on_front') ;


						unset($wp_query->queried_object);
						$wp_query->queried_object_id = $query_object->query_vars['page_id'];
						$wp_query->queried_object->ID = $query_object->query_vars['page_id'];

					}
				}
			}


		} else { // no query tag

			if ( ( isset ( $query_object->query_vars['caller_get_posts'] ) && $query_object->query_vars['caller_get_posts']) || ( isset ( $query_object->query_vars['ignore_sticky_posts'] ) && $query_object->query_vars['ignore_sticky_posts']) ) {
				// nothing
			} else {
				if (  ($query_object->is_home && !$this->show_page_on_front && $this->xili_settings['homelang'] == 'modify') || ($query_object->is_home && $query_object->is_posts_page && $this->xili_settings['pforp_select'] != 'no_select' ) )  {

						// force change if loop - home or page_for_posts
						if ( $query_object->is_posts_page ) { // 2.8.4

							if ( $this->is_permalink ) { // 2.8.4.1
								xili_xl_error_log ( "****** ici 1520 force change ******" . serialize ( $query_object->query_vars['pagename']) );
								$pagenametolang = array_flip ( $this->page_for_posts_name_array ) ;
								if ( isset ( $query_object->query_vars['pagename'] ) && isset ( $pagenametolang[$query_object->query_vars['pagename']] ) ) {
									$curlang = $pagenametolang[$query_object->query_vars['pagename']];
									xili_xl_error_log ( '"****** ici 1565 Name + ' . $curlang );
								} else {

									$curlang = $this->choice_of_browsing_language();

								}

							} else {
								xili_xl_error_log ( '****** ici 1532 force change ****** ' . serialize ( $query_object->query_vars['page_id']) );
								$pageidtolang = array_flip ( $this->page_for_posts_array ) ;
								if ( isset ( $query_object->query_vars['page_id'] ) && isset ( $pageidtolang[$query_object->query_vars['page_id']] ) ) {
									$curlang = $pageidtolang[$query_object->query_vars['page_id']];
									xili_xl_error_log ( 'ID + ' . $curlang );
								} else {

									$curlang = $this->choice_of_browsing_language();

								}
							}
						} else {
							$curlang = $this->choice_of_browsing_language();
						}
						// 2.9.2
						if ( '' == $query_object->query_vars['post_type'] || in_array ( $query_object->query_vars['post_type'], array_keys ($this->authorized_custom_post_type () ) ) ) {
							$wherereqtag = $this->langs_ids_array[$curlang];
							$where .= " AND xtt.taxonomy = '" . TAXONAME . "' "; //"
							$where .= " AND xtt.term_id = $wherereqtag ";
						}
				}
			}
		}
		xili_xl_error_log ( "****** where *** (#1565) ***".$where );
		return $where;
	}

	/******** template theme live modifications ********/

	/**
	 * wp action for theme at end of query
	 *
	 * @since 0.9.0
	 * @updated 1.1.9, 1.4.2a
	 * call by wp hook
	 *
	 */
	function xiliml_language_wp() {
		if ( !is_admin() ) {
			$this->curlang = $this->get_curlang_action_wp(); // see hooks in that function

			$this->curlang_dir = $this->get_dir_of_cur_language( $this->curlang ); /* general dir of the theme */
			if ( $this->locale_method ) {
				$this->xiliml_load_theme_textdomain ( $this->thetextdomain ); /* new method for cache compatibility - tests */
			} else {
				$this->set_mofile( $this->curlang );
			}
		}
	}

	// to fixe event in WP 3.4 - 2.7.1
	function xili_test_lang_perma () {
		global $XL_Permalinks_rules;
		$this->lang_perma = ( class_exists ( 'XL_Permalinks_rules' ) && method_exists ( $XL_Permalinks_rules, 'insert_lang_tag_root') ) ; // 2.9.23 - must have instanciation in theme

	}

	/**
	 * wp action to switch wp_locale only on front-end
	 *
	 * @since 2.4.0
	 *
	 * call by wp hook after theme cur_lang set
	 *
	 */
	function xili_locale_setup ( ) {
		if ( !is_admin() ) {
			unset($GLOBALS['wp_locale']);
			global $wp_locale;
			$wp_locale = new xl_WP_Locale();
		}
	}

	/**
	 * only called in front-end
	 *
	 * @since 2.4.0
	 *
	 */
	function translate_date_format ( $format ) {
		if ( $this->xili_settings['wp_locale'] == 'wp_locale' )
	 	 	return __( $format , $this->thetextdomain );
	 	else
	 		return $format;
	}

	/**
	 * fixes feed link if not permalinks because called after category filter
	 *
	 * @since 2.8.1
	 *
	 */
	function category_feed_link ( $link, $feed = '' ) {
		$permalink_structure = get_option( 'permalink_structure' );

		if ( '' != $permalink_structure ) {
			if ( $feed == get_default_feed() )
				$feed_link = 'feed';
			else
				$feed_link = "feed/$feed";
		// clean current link
			$root_link = str_replace ( user_trailingslashit( $feed_link, 'feed' ), '', $link );
		// get part after ?lang=
			$parts = explode ( '?'.QUETAG.'=', $root_link );
			if ( isset( $parts[1] ) ) {
				// recreate current link
				$link = trailingslashit( $parts[0] ) . user_trailingslashit( $feed_link, 'feed' ) . '?'.QUETAG.'='. str_replace ( '/','',$parts[1] );
			}
		}
		return $link;

	}

	/**
	 * 'theme_locale' filter to detect theme and don't load theme_domain in functions.php
	 *
	 * @since 1.5.0
	 *
	 * call by 'theme_locale' filter
	 */
	function xiliml_theme_locale ( $locale, $domain ) {
		$this->xili_settings['theme_domain'] = $domain;
		$this->thetextdomain = $domain;

		return 'wx_YZ'; // dummy local
	}

	/**
	 * 'override_load_textdomain' filter to avoid dummy load and update langs_folder
	 *
	 * @since 1.5.0
	 * @updated 1.8.1 - 1.8.5
	 * @updated 2.8.3 (WP 3.5) limited to current theme
	 *
	 */
	function xiliml_override_load ( $falseval, $domain, $mofile ) {

		if ( false !== strpos ($mofile , get_stylesheet_directory() ) ) { // limited to current theme - 2.8.3
			if ( $this->show ) {
				if ( !in_array( $domain , $this->arraydomains ) )
					$this->arraydomains[] = $domain;
			}
		   	if (false === strpos ($mofile ,'wx_YZ.mo')) {//
		   		return false;
		   	} else {
		   		if ( str_replace( get_stylesheet_directory(), '', $mofile ) == $mofile ) { // no effect
		   			$this->get_template_directory = get_template_directory();
		   			$this->xili_settings['langs_in_root_theme'] = 'root';
		   		} else {
		   			$this->get_template_directory = get_stylesheet_directory(); // a load is in child
		   			$this->xili_settings['langs_in_root_theme'] = '';

		   		}
		   		$this->ltd = true ;

		   		$langs_folder = str_replace('/wx_YZ.mo','',str_replace( $this->get_template_directory, '', $mofile ));
		   		// in wp3 the detection is only done here (end user side by theme domain) so updated is mandatory for xili-dico

		   		if ( $this->xili_settings['langs_folder'] != $langs_folder ) {
		   			$this->xili_settings['langs_folder'] = $langs_folder ;
			 		update_option( 'xili_language_settings', $this->xili_settings );
			 	}
			 	// to restore theme mo if theme datas and terms in admin ui….
			 	if ( is_admin() ) {
			 		load_textdomain( $domain, str_replace('wx_YZ', get_locale(), $mofile )); // 2.3.1 - 18h18
			 	}

		   		return true;

		   	}
		   	// impossible to use is_child_theme() - test even no $this->xili_settings['mo_parent_child_merging'] 2.9.30
		} else if ( (get_template_directory() != get_stylesheet_directory() ) && false !== strpos ( $mofile , get_template_directory() ) ) {

		   	if (false === strpos ($mofile ,'wx_YZ.mo')) {//
		   		return false;
		   	} else {
		   		$parent_langs_folder = str_replace('/wx_YZ.mo','',str_replace( get_template_directory(), '', $mofile ));
		   		$this->ltd_parent = true ;
		   	}

		   	if ( $this->xili_settings['parent_langs_folder'] != $parent_langs_folder ) {
		   		$this->xili_settings['parent_langs_folder'] = $parent_langs_folder ;
			 	update_option( 'xili_language_settings', $this->xili_settings );
			}
			 // to restore theme mo if theme datas and terms in admin ui….
			if ( is_admin() ) {
			 	load_textdomain( $domain, str_replace('wx_YZ', get_locale(), $mofile )); // 2.3.1 - 18h18
			}

		   	return true;

		} else {
			return false;
		}
	}

	/**
	 * plugin domain catalog ( hook plugin_locale )
	 */
	function get_plugin_domain_array ( $locale, $domain ) {

		if ( !isset ( $this->xili_settings['domains'][$domain] ) && ! in_array ( $domain, array ( 'xili-dictionary', 'xili-language', 'xili-language-widget' ) )  ) {
			$this->xili_settings['domains'][$domain] = 'disable';
			if ( is_admin() )
					update_option( 'xili_language_settings', $this->xili_settings );
		}

		return $locale;
	}

	/**
	 *
	 * Introduced only in visitors side (not in admin) to change domain of plugin or other
	 * gettext filter from function translate() in wp-includes/I10n.php
	 *
	 * @since 1.8.7 - 1.8.8 - 2.8.9 &get
	 */
	function change_plugin_domain ($translation, $text, $domain ) {

		$domain_t = $this->switching_domain ( $domain );

		$translations = get_translations_for_domain( $domain_t );

		return $translations->translate( $text );
	}

	function change_plugin_domain_with_context ($translation, $text, $context, $domain ) {

		$domain = $this->switching_domain ( $domain );

		$translations = get_translations_for_domain( $domain );
		return $translations->translate( $text, $context );
	}
	function change_plugin_domain_plural ($translation, $single, $plural, $number, $domain ) {

		$domain = $this->switching_domain ( $domain );

		$translations = get_translations_for_domain( $domain );
		$translation = $translations->translate_plural( $single, $plural, $number );
		return $translation ;
	}
	function change_plugin_domain_plural_with_context ($translation, $single, $plural, $number, $context, $domain ) {

		$domain = $this->switching_domain ( $domain );

		$translations = get_translations_for_domain( $domain );
		$translation = $translations->translate_plural( $single, $plural, $number, $context );
		return $translation ;
	}

	/**
	 * domain switching
	 */
	function switching_domain ( $domain ) {
		$ok = false ;
		if ( $domain != $this->thetextdomain ) {
			if ( in_array ( $domain, array_keys( $this->xili_settings['domains'] ) ) ) {
				if ( $this->xili_settings['domains'][$domain] == 'enable' ) {
					$ok = true ;

				}
			} //else {
				//if ( $this->xili_settings['domains']['all'] == 'enable' ) $ok = true ;
			//}
			if ( $ok )
				$domain = $this->thetextdomain ;
		}

		return $domain;
	}

	/**
	 * locale hook when load_theme_textdomain is present in functions.php
	 *
	 * @since 1.1.9
	 *
	 * call by locale hook
	 */
	function xiliml_setlocale ( $locale ) {
		if ( $this->theme_locale === true ) {
			$listlanguages = get_terms(TAXONAME, array('hide_empty' => false,'slug' => $this->curlang));
			return $listlanguages[0]->name;
		} else {
			return $locale;
		}
	}

	/**
	 * locale hook when load_theme_textdomain is present in functions.php
	 *
	 * @since 1.1.9
	 *
	 * call by locale hook
	 */
	function xiliml_load_theme_textdomain ( $domain ) {
		$this->theme_locale = true;
		$langfolder = $this->xili_settings['langs_folder'];
		//$langfolder = '/'.str_replace("/","",$langfolder); /* please no lang folder in sub-subfolder */
		$langfolder = ($langfolder == "/") ? "" : $langfolder;
		load_theme_textdomain($domain, $this->get_template_directory . $langfolder);
		$this->theme_locale = false;
	}

	/**
	 * select .mo file
	 * @since 0.9.0
	 * @updated 0.9.7.1 - 1.1.9 - 1.5.2 wpmu - 1.8.9.1 (domain select) - 2.8.3 (WP 3.5)
	 * call by function xiliml_language_wp()
	 * @param $curlang .
	 */
	function set_mofile( $curlang ) {
		global $wp_version;

		if ( ""!=$this->thetextdomain ) {
			$themetextdomain = $this->thetextdomain;
		} else {
			$themetextdomain = 'ttd-not-defined';
		}
		$langfolder = $this->xili_settings['langs_folder'];

		$langfolder = ($langfolder == "/") ? "" : $langfolder;

		$filename = '';
		if ( '' != $curlang ) {
			if ( isset ( $this->langs_slug_name_array[$curlang] ) )
				$filename = $this->langs_slug_name_array[$curlang]; // 2.4.2
		}

		if ( '' != $filename ) {
			$filename .= '.mo';

			// if merging method with child theme set - 2.8.8

			if ( $this->xili_settings['mo_parent_child_merging'] ) {

				$mofile = $this->get_parent_theme_directory . $this->xili_settings['parent_langs_folder'] . "/$filename";
				$parent_load = load_textdomain( $themetextdomain, $mofile );

			}

			$mofile = $this->get_template_directory . $langfolder . "/$filename";	 // only child subfolder

			if ( is_multisite() ) { /* completes theme's language with db structure languages (cats, desc,…) */
				if ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) {
					$wpmu_curdir = $uploads['basedir']."/languages";
					load_textdomain( $themetextdomain, $wpmu_curdir."/local-" . $this->langs_slug_name_array[$curlang] . ".mo" ); // here to be the last value
					load_textdomain( $themetextdomain, $wpmu_curdir."/$filename" );
				}
			}
			load_textdomain( $themetextdomain, $this->get_template_directory .$langfolder."/local-" . $this->langs_slug_name_array[$curlang] . ".mo" ); // here to be the last value

			if ( version_compare( $wp_version, '3.5', '<' ) ) {

				load_textdomain( $themetextdomain, $mofile ); // moved here for merge (prior for site and not theme)

			} else {

			// **** new files place since WP 3.5 = wp-content/languages/ and domain-xx_YY.mo **** //
			// I10n.php says : Load the textdomain from the Theme provided location, or theme directory first
			// $mofile = "{$path}/{$locale}.mo";
			// if ( $loaded = load_textdomain($domain, $mofile) )
			// 		return $loaded;

			// Else, load textdomain from the Language directory
			// $mofile = WP_LANG_DIR . "/themes/{$domain}-{$locale}.mo";
			// return load_textdomain($domain, $mofile);

			// XL will follow the same way - if not, will try in WP_LANG_DIR - 2.8.3

				if ( ! ( $loaded = load_textdomain( $themetextdomain, $mofile ) ) ) {
					$mofile = WP_LANG_DIR . "/themes/{$themetextdomain}-{$filename}";
					load_textdomain( $themetextdomain, $mofile );
				}
			}

			// 2.8.1
			do_action ( 'xiliml_add_frontend_mofiles' , $themetextdomain, $this->langs_slug_name_array[$curlang] ); // to add bbpress good mo

		}
	}

	/**
	 * default rules - set curlang for wp action
	 *
	 * @since 1.7.0 - new mechanism for front-page
	 * @updated 1.8.9.1 - better category case
	 * @updated 2.2.3 - fixes rare frontpage infinite loop
	 * replace xiliml_cur_lang_head (0.9.7 -> 1.6.1)
	 * @return $curlang
	 */
	function get_curlang_action_wp () {

		if ( has_filter( 'xiliml_curlang_action_wp' ) ) return apply_filters( 'xiliml_curlang_action_wp', '' ); /* '' warning on some server need one arg by default*/
		/* default */
		global $post, $wp_query ;


		if ( have_posts() ) {
			$showpage = get_option('show_on_front');
			$condition = false;
			if ( 'page' == $showpage ) {
				if (!in_array ( $wp_query->query_vars['page_id'], $this->xili_settings['show_page_on_front_array'] ) ) $condition = true;
			} else {
				if ( !is_home() ) 	$condition = true;
			}

			if ( $condition ) { /* every pages !is_front_page() */
				$curlangdir = $this->get_cur_language($post->ID);
				$curlang = $curlangdir[QUETAG]; /* the first post give the current lang*/
				if ( $curlangdir == false ) $curlang = $this->choice_of_browsing_language(); //$this->default_slug; /* no more constant - wpmu - can be changed if use hook */
				if ( is_page() ) {
					if ( isset($wp_query->query_vars[QUETAG]) ) {
						$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG]) ;
					}
				} elseif ( is_search() ) {
					if ( isset( $wp_query->query_vars[QUETAG] ) ) $curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG]) ; //
				} elseif ( is_category() ) {
					if ( $this->lang_perma ) {
						if ( isset( $wp_query->query_vars[QUETAG] ) ) {
							$curlang = str_replace ( LANG_UNDEF, "", $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG])) ;
						} else {
							$curlang = $this->choice_of_categories_all_languages( $curlang ) ;
						}
					} else {
						if ( isset( $wp_query->query_vars[QUETAG] ) ) {
							$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG]  ) ;
						} else {
							$curlang = $this->choice_of_categories_all_languages( $curlang ) ;  //$this->choice_of_browsing_language(); // again as defined 1.8.9.1
						}
					}
				}
			} else { /* front page - switch between lang (and post/page) is done in query posts_where_lang filter see above */

				if ( isset ( $wp_query->query_vars[QUETAG] ) && '' != $wp_query->query_vars[QUETAG] ) {
					$this->langstate = true; // 2.8.0 b
					$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG] );	// home series type

				} else {

					if ( 'page' == $showpage ) { //$this->show_page_on_front ) {
						$page_front = get_option('page_on_front');  // filtered only if GET known
						// 2.9.21
						if ( in_array ( $wp_query->query_vars['page_id'], $this->xili_settings['show_page_on_front_array'] ) ) {
							$curlang = get_cur_language( $wp_query->query_vars['page_id'] );
						} else {
							$curlang = get_cur_language( $page_front );  // redund !
						}

					} else { // home.php - 1.3.2

						$curlang = $this->choice_of_browsing_language();

					}
				}
			}
		} else { /*no posts for instance in category + lang */
		 	if ( $this->lang_perma ) {
				if ( isset( $wp_query->query_vars[QUETAG] ) ) {
					$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG] ) ;
				} else {
					$curlang = $this->choice_of_browsing_language();
				}
			} else {
				if ( isset( $wp_query->query_vars[QUETAG] ) ) {
					$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG] );
				} else {
					$curlang = $this->choice_of_browsing_language() ;  //$this->choice_of_browsing_language(); // again as defined 1.8.9.1
				}
			}
		}

		xili_xl_error_log ('#2021 - fin wp=' . $curlang );

		return str_replace ( LANG_UNDEF  , '' , $curlang ) ; // 2.3 to return main part
	}

	/**
	 * modify  language_attributes() output
	 *
	 * @since 0.9.7.6
	 *
	 * The - language_attributes() -  template tag is use in header of theme file in html tag
	 *
	 * @param $output
	 */
	function head_language_attributes( $output ) {
		/* hook head_language_attributes */

		if ( has_filter('head_language_attributes') ) return apply_filters('head_language_attributes',$output);
		$attributes = array();
		$output = '';

		if ( $dir = get_bloginfo('text_direction') ) /*use hook for future use */
			$attributes[] = "dir=\"$dir\"";
		if ( $this->langstate = true ) {

			$lang = ( isset( $this->langs_slug_name_array[$this->curlang] ) ) ?  str_replace('_','-',$this->langs_slug_name_array[$this->curlang]) : ""; // 2.8.6

		} else {
			//use hook if you decide to display limited list of languages for use by instance in frontpage
			$listlang = array();

			$listlanguages = $this->get_listlanguages();
			if ( $listlanguages ) {
				foreach ( $listlanguages as $language ) {
					$listlang[] = str_replace( '_', '-', $language->name );
				}
				$lang = $listlang[0]; // implode(', ',$listlang); // not w3c compatible
			}
		}
		if ( get_option('html_type') == 'text/html' )
				$attributes[] = "lang=\"$lang\"";
	// to use both - use the hook - head_language_attributes
		if ( get_option('html_type') != 'text/html' )
			$attributes[] = "xml:lang=\"$lang\"";

		$output = implode(' ', $attributes);

		return $output;
	}

	/**
	 * modify  insert language metas in head (via wp_head)
	 *
	 * @since 0.9.7.6
	 * @updated 1.1.8
	 * @must be defined in functions.php according general theme design (wp_head)
	 *
	 * @param $curlang
	 */
	function head_insert_language_metas( ) {
		$curlang = $this->curlang;
		$undefined = $this->langstate;
		echo "<!-- multilingual website powered with xili-language v. ".XILILANGUAGE_VER." ".$this->lpr." WP plugin of dev.xiligroup.com -->\n";
		if ( has_filter('head_insert_language_metas') ) return apply_filters( 'head_insert_language_metas', $curlang, $undefined );
	}

	/**
	 * insert hreflang link in head (via wp_head)
	 *
	 * @since 2.5
	 * as commented in Google rel="alternate"
	 *
	 * to change rules or be compatible with cpt and taxonomy use head_insert_hreflang_link filter
	 *
	 * @param $curlang
	 */
	function head_insert_hreflang_link ( ) {
		if ( has_filter('head_insert_hreflang_link') ) return apply_filters( 'head_insert_hreflang_link', $this->curlang );
		global $post, $cat;
		if ( is_front_page() || is_category () ) {
			$listlanguages = $this->get_listlanguages();
			$currenturl = $this->current_url ( $this->lang_perma );
			foreach ( $listlanguages as $language ) {
				if ( $language->slug != $this->curlang ) {
					if ( is_category() ) {
						$category = get_category ( $cat ); // test targets count
						if ( 0 < $this->count_posts_in_taxonomy_and_lang ( 'category', $category->slug , $language->slug ) ) {
							$do_it = true; //
						} else {
							$do_it = false;
						}
					} else {
						$do_it = true;
					}
					if ( $do_it ) {
						$lang = str_replace( '_', '-', $language->name );
						$hreflang = ( $this->lang_perma ) ? str_replace ( '%lang%', $language->slug, $currenturl ) : $currenturl.QUETAG."=".$language->slug ;
						printf ( '<link rel="alternate" hreflang="%s" href="%s" />'."\n", $lang, $hreflang ) ;
					}
				}

			}
		} elseif ( is_singular() ) {
			$listlanguages = $this->get_listlanguages();
			foreach ( $listlanguages as $language ) {
				$targetpost = $this->linked_post_in ( $post->ID, $language->slug ) ;
				if ( $language->slug != $this->curlang  && !empty ( $targetpost ) ) {
					$hreflang = $this->link_of_linked_post ( $post->ID, $language->slug ) ;
					$lang = str_replace( '_', '-', $language->name );
					printf ( '<link rel="alternate" hreflang="%s" href="%s" />'."\n", $lang, $hreflang ) ;
				}
			}
		}
	}

	/**
	 * used in head_insert_hreflang_link and in xili_language_list
	 *
	 * @since 2.5
	 *
	 * @updated 2.11.1
	 * @updated 2.11.2
	 */
	function current_url ( $lang_perma ) {
		// to create your own rules to build current url of language switcher in your own context
		if ( has_filter('xiliml_link_current_url') ) return apply_filters( 'xiliml_link_current_url', $lang_perma );

		global $XL_Permalinks_rules, $wp_query;
		$format = get_post_format(); // take first current post format
		if ( $lang_perma ) {
			if ( is_category() ) {
				if ( version_compare( XILILANGUAGE_VER, '2.9.22', '<=') ) {
					remove_filter('term_link', 'insert_lang_4cat') ;
				} else {
					remove_filter('term_link', array ( $XL_Permalinks_rules, 'insert_lang_taxonomy' ), 10 ) ;
				}
				$catcur = xiliml_get_category_link();
				if ( version_compare( XILILANGUAGE_VER, '2.9.22', '<=') ) {
					add_filter( 'term_link', 'insert_lang_4cat', 10, 3 );
				} else {
					add_filter( 'term_link', array ( $XL_Permalinks_rules, 'insert_lang_taxonomy' ), 10, 3 );
				}
				$currenturl = $catcur;
			} else  if ( is_archive() ) {
				$post_type = get_post_type();
				if ( is_post_type_archive ( $post_type ) && isset($wp_query->query_vars['post_format']) ) {
					$currenturl = ( get_post_type_archive_link($post_type)) ? get_post_type_archive_link($post_type) : get_bloginfo('url').'/%lang%/' ;
				} else if ( is_date() ) {
					global $wp_query;
					$date_array = $wp_query->query ;
					if (isset($date_array['day'])){
						$result = get_day_link($date_array['year'], $date_array['monthnum'], $date_array['day']) ;
					} else if (isset($date_array['monthnum'])) {
						$result = get_month_link($date_array['year'], $date_array['monthnum']) ;
					} else if (isset($date_array['year'])) {
						$result = get_year_link($date_array['year'] ) ;
					}
					$currenturl = $result;
				} else  if ( has_post_format($format) && isset($wp_query->query_vars['post_format']) ) {
					$currenturl = get_post_format_link ($format);
				} else {
					$currenturl = get_bloginfo('url').'/%lang%/';
				}
			} else  if ( has_post_format($format) && isset($wp_query->query_vars['post_format']) ) { // in initial query
				$currenturl = get_post_format_link ($format);

			} else {
				$currenturl = get_bloginfo('url').'/%lang%/';
			}
		} else {
			if ( is_category() ) {
				$catcur = xiliml_get_category_link();
				$permalink = get_option('permalink_structure'); /* 1.6.0 */
				$sep = ('' == $permalink) ? "&amp;" : "?" ;
				$currenturl = $catcur.$sep;
			} else  if ( is_archive() ) {
				$permalink = get_option('permalink_structure'); /* 1.6.0 */
				$sep = ('' == $permalink) ? "&amp;" : "?" ;

				$post_type = get_post_type();
				if ( is_post_type_archive ( $post_type ) && isset($wp_query->query_vars['post_format']) ) {
					$currenturl = ( get_post_type_archive_link($post_type)) ? get_post_type_archive_link($post_type).$sep : get_bloginfo('url').'/?' ;
				} else if ( is_date() ) {
					global $wp_query;
					$date_array = $wp_query->query ;
					if (isset($date_array['day'])){
						$result = get_day_link($date_array['year'], $date_array['monthnum'], $date_array['day']) ;
					} else if (isset($date_array['monthnum'])) {
						$result = get_month_link($date_array['year'], $date_array['monthnum']) ;
					} else if (isset($date_array['year'])) {
						$result = get_year_link($date_array['year'] ) ;
					}
					$currenturl = $result.$sep;
				} else  if ( has_post_format($format) && isset($wp_query->query_vars['post_format']) ) {
					$currenturl = get_post_format_link ($format).$sep;
				} else {
					$currenturl = get_bloginfo('url').'/?';
				}
			} else  if ( has_post_format($format) && isset($wp_query->query_vars['post_format']) ) { // in initial query
				$currenturl = get_post_format_link ($format).$sep;

			} else {
			 	$currenturl = get_bloginfo('url').'/?';
			}
		}
		return $currenturl;
	}

	/**
	 * return count of posts in taxonomy and language
	 *
	 * called by head_insert_hreflang_link
	 * available for specific behaviour - why not in custom plugin for xili_language_list or...
	 * @params taxonomy (category), current slug, current lang
	 * @since 2.5
	 */
	 function count_posts_in_taxonomy_and_lang ( $taxonomy, $slug = '' , $language = '', $post_type = 'post' ) {

	 	$args = array(
	 		'post_type' => $post_type,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => array( $slug )
				),
				array( // language
					'taxonomy' => TAXONAME,
					'field' => 'slug',
					'terms' => array( $language )
				)
			)
		);
		$query = new WP_Query( $args );

	 	return $query->found_posts;
	 }

	/**
	 * Translate texts of widgets or other simple text...
	 *
	 * @updated 1.6.0 - Old name widget_texts
	 * @since 0.9.8.1
	 * @ return
	 */
	function one_text ( $value ){
		if ('' != $value)
			return __($value, $this->thetextdomain);
		else
			return $value;
	}

	/**
	 * Translate title of wp nav menu
	 *
	 * @since 1.6.0
	 * @ return
	 */
	function wp_nav_title_text ( $value = "", $itemID = 0 ){
		if ('' != $value) {
			return __( $value, $this->thetextdomain );
		} else {
			return $value;
		}
	}

	/**
	 * Add filters of texts of comment form  - because default text are linked with wp language (and not theme)
	 *
	 * @since 1.5.5
	 * @ return arrays with themetextdomain
	 */
	function xili_comment_form_default_fields ( $fields ) {
		$commenter = wp_get_current_commenter();

		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields =  array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __( $this->comment_form_labels['name'], $this->thetextdomain ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . __( $this->comment_form_labels['email'], $this->thetextdomain ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . __( $this->comment_form_labels['website'], $this->thetextdomain ) . '</label>' .
			            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
		);
		return $fields;
	}
	/** 2.3.2 - noun context */
	function xili_comment_form_defaults ( $defaults ) {
		global $user_identity, $post;
		$req = get_option( 'require_name_email' );
		$xilidefaults = array(

		'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( $this->comment_form_labels['comment'], 'noun', $this->thetextdomain ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( $this->comment_form_labels['youmustbe'], $this->thetextdomain ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( $this->comment_form_labels['loggedinas'], $this->thetextdomain ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ) . '</p>',
		'comment_notes_before' => '<p class="comment-notes">' . __( $this->comment_form_labels['emailnotpublished'], $this->thetextdomain ) . ( $req ? __( $this->comment_form_labels['requiredmarked'], $this->thetextdomain ) : '' ) . '</p>',
		'comment_notes_after'  => '<dl class="form-allowed-tags"><dt>' . __( $this->comment_form_labels['youmayuse'], $this->thetextdomain ) . '</dt> <dd><code>' . allowed_tags() . '</code></dd></dl>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'title_reply'          => __( $this->comment_form_labels['leavereply'], $this->thetextdomain ),
		'title_reply_to'       => __( $this->comment_form_labels['replyto'], $this->thetextdomain ),
		'cancel_reply_link'    => __( $this->comment_form_labels['cancelreply'], $this->thetextdomain ),
		'label_submit'         => __( $this->comment_form_labels['postcomment'], $this->thetextdomain ),
		);
		$args = wp_parse_args( $xilidefaults, $defaults);
		return $args;
	}

	/**
	 * insert other language of wp_list_categories
	 *
	 * @since 0.9.0
	 * @updated 0.9.8.4 - 1.4.1 = no original term in ()
	 * can be hooked by filter add_filter('xiliml_cat_language','yourfunction',2,3) in functions.php
	 * call by do_filter list_cats
	 * @param $content, $category
	 */
	function xiliml_cat_language ( $content, $category = null ) {
		if (has_filter('xiliml_cat_language')) return apply_filters('xiliml_cat_language',$content, $category,$this->curlang);
	    $new_cat_name = (!is_admin()) ? __($category->name,$this->thetextdomain) : $content ;  /*to detect admin UI*/
	    return $new_cat_name;
	 }

	/**
	 * add the language key in category links of current pages
	 *
	 * @since 0.9.0
	 * update 0.9.7 1.5.1
	 * can be hooked by filter add_filter('xiliml_link_append_lang','yourfunction',10,3) in functions.php
	 * call by do_filter
	 * @param $content,
	 */
	function xiliml_link_append_lang( $link, $category_id = 0 ) {
		if ( has_filter( 'xiliml_link_append_lang' ) ) return apply_filters( 'xiliml_link_append_lang', $link, $category_id, $this->curlang );
		/*default*/

	  		if ($this->curlang) :
	  		 	if ( !$this->lang_perma ){ // 2.1.1
	  				$permalink = get_option( 'permalink_structure' );
	  			 	$sep = ('' == $permalink) ? "&amp;".QUETAG."=" : "?".QUETAG."=";
	  			 	$language_qv =  $this->lang_slug_qv_trans ( $this->curlang );
	  				$link .= $sep. $language_qv ;
				}

	  		endif;

	  return $link;
	}

	/**
	 * Setup global post data.
	 *
	 * @since 0.9.4
	 * update 0.9.7
	 * can be hooked by filter add_filter('xiliml_taglink_append_lang','yourfunction',2,3) in functions.php
	 *
	 * @param $taglink, $tag_id.
	 * @return $taglink.
	 */
	function xiliml_taglink_append_lang ( $taglink, $tag_id=null ) {
		if ( has_filter('xiliml_taglink_append_lang') ) return apply_filters('xiliml_taglink_append_lang',$taglink,$tag_id,$this->curlang);
		/* no yet default */
		/* global $curlang;

	  		if ($curlang) :
	  			$taglink .= '&amp;'.QUETAG.'='.$curlang ;
	  		endif;

	  	*/
	 return $taglink;
	}

	/**
	 * Setup global post data.
	 *
	 * @since 1.6.0
	 * can be hooked by filter add_filter('xiliml_bloginfo','yourfunction',10,3) in functions.php
	 *
	 * @param $output, $show.
	 * @return translated $output.
	 */
	 function xiliml_bloginfo ( $output, $show ) {
	 	if (has_filter('xiliml_bloginfo')) return apply_filters('xiliml_bloginfo',$output, $show, $this->curlang);
	 	$info_enabled = array('name', 'blogname', 'description');
	 	if (in_array($show, $info_enabled)) {
	 		return __($output, $this->thetextdomain);
	 	} else {
	 		return $output;
	 	}
	 }

	/**
	 * to cancel sub select by lang in cat 1 by default
	 *
	 * @since 0.9.2
	 * update 0.9.7 - 1.8.4
	 * can be hooked by filter add_filter('xiliml_modify_querytag','yourfunction') in functions.php
	 *
	 *
	 */
	function xiliml_modify_querytag() {
		if ( has_filter('xiliml_modify_querytag') ) {
			apply_filters('xiliml_modify_querytag','');
		} else {
			/*default*/
			global $wp_query;
			if ( defined('XILI_CATS_ALL') && !empty($wp_query->query_vars['cat']) )  { /* change in functions.php or use hook in cat 1 by default*/
				$excludecats = explode(",", XILI_CATS_ALL);
				if 	( $excludecats != array() && in_array($wp_query->query_vars['cat'],$excludecats) ) {
					$wp_query->query_vars[QUETAG] = "";	/* to cancel sub select */
				}
			}
		}
	}

	/**
	 * filters for wp_get_archives
	 *
	 * @since 0.9.2
	 * @params $join or $where and template params
	 *
	 */
	function xiliml_getarchives_join( $join, $r ) {
		global $wpdb;
		if (has_filter('xiliml_getarchives_join')) return apply_filters('xiliml_getarchives_join',$join,$r,$this->curlang);
		extract( $r, EXTR_SKIP );
		$this->get_archives_called = $r;
		if (isset($lang)) {
			if ("" == $lang ) { /* used for link */
				$this->get_archives_called[QUETAG] = $this->curlang;
			} else {
				$this->get_archives_called[QUETAG] = $lang;
			}
			$join = " INNER JOIN $wpdb->term_relationships as tr ON ($wpdb->posts.ID = tr.object_id) INNER JOIN $wpdb->term_taxonomy as tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";

		}
		return $join;

	}

	function xiliml_getarchives_where( $where, $r ) {
		global $wpdb;
		if (has_filter('xiliml_getarchives_where')) return apply_filters('xiliml_getarchives_where',$where,$r,$this->curlang);
		extract( $r, EXTR_SKIP );
		if (isset($lang)) {
			if ("" == $lang ) {
				$curlang = $this->curlang;
			} else {
				$curlang = $lang;
			}
			$reqtag = term_exists( $curlang, TAXONAME );
				if (''!= $reqtag) {
					$wherereqtag = $reqtag['term_id'];
				} else {
					$wherereqtag = 0;
				}
				$where .= " AND tt.taxonomy = '".TAXONAME."' ";
				$where .= " AND tt.term_id = $wherereqtag ";
		}
		return $where;
	}

	/** *here basic translation - to improve depending theme features : use hook 'xiliml_get_archives_link' */
	function xiliml_get_archives_link( $link_html ) {
		if ( has_filter('xiliml_link_translate_desc')) return apply_filters('xiliml_get_archives_link', $link_html,$this->get_archives_called, $this->curlang );
		extract( $this->get_archives_called, EXTR_SKIP );
		if ( isset( $lang ) && '' != $lang ) {
			$permalink = get_option('permalink_structure');
			$sep = ('' == $permalink) ? "&amp;".QUETAG."=" : "?".QUETAG."=";
			if ($format != 'option' && $format != 'link' && $type != 'postbypost' && $type != 'alpha') {
				/* text extract */
				$i = preg_match_all("/'>(.*)<\/a>/Ui", $link_html, $matches,PREG_PATTERN_ORDER);
				$line = $matches[1][0];
				/* link extract */
				$i = preg_match_all("/href='(.*)' title/Ui", $link_html, $matches,PREG_PATTERN_ORDER);
				if ( '' == $type || 'monthly' == $type) {
					if ('' == $permalink) {
						$archivedate = str_replace(get_bloginfo('url').'/?' , "" , $matches[1][0]);
						$r = wp_parse_args( $archivedate, array());
						extract($r, EXTR_SKIP );
						$month = substr($m,-2);
						$year = substr($m,0,4);
					} else {
						/* Due to prevents post ID and date permalinks from overlapping using /date/ v 1.1.9
						 * no / at end for "numeric" permalink giving /archives/date/2009/04
						 */
						$thelink = $matches[1][0];
						$i = preg_match_all("/\/([0-9]{4})\/([0-9]{2})/Ui", $thelink, $results,PREG_PATTERN_ORDER);
						if ($i) {
							$month = $results[2][0];
							$year = $results[1][0];
						}
					}
					$time = strtotime($month.'/1/'.$year);
					$line2print = the_xili_local_time('%B %Y',$time); /* use server local*/
					$link_html = str_replace($line , $line2print , $link_html);
				}
				$link_html = str_replace("' titl" , $sep.$lang."' titl" , $link_html);
			} elseif ($format == 'option') {
				/* need improve with regex */
				$link_html = str_replace("'>" , $sep.$lang."'>" , $link_html);
			}
		}
		return $link_html;
	}

	 /**
	 * translate description of categories
	 *
	 * @since 0.9.0
	 * update 0.9.7 - 0.9.9.4
	 * can be hooked by filter add_filter('xiliml_link_translate_desc','yourfunction',2,4) in functions.php
	 *
	 *
	 */
	function xiliml_link_translate_desc( $description, $category=null, $context='' ) {
		if (has_filter('xiliml_link_translate_desc')) return apply_filters('xiliml_link_translate_desc',$description,$category,$context,$this->curlang);
	  	$translated_desc = ($this->curlang && ''!= $description) ? __($description, $this->thetextdomain) : $description ;
	 	return $translated_desc;
	}

	/**
	 * filters for wp_title() translation - single_cat_title -
	 * since 1.4.1
	 *
	 */
	function xiliml_single_cat_title_translate ( $cat_name ) {
		if (has_filter('xiliml_single_cat_title_translate')) return apply_filters('xiliml_single_cat_title_translate',$cat_name);
		$translated = ($this->curlang && ''!= $cat_name) ? __($cat_name,$this->thetextdomain) : $cat_name;
	 	return $translated;
	}

	/**
	 * Return the list of preferred languages for displaying pages (see in firefox prefs)
	 * thanks to php.net comments HTTP_ACCEPT_LANGUAGE
	 * @since 0.9.7.5
	 * @updated 2.7.1
	 * @return array (non sorted)
	 */
	function the_preferred_languages() {
		$preferred_languages = array();
		if( isset( $_SERVER["HTTP_ACCEPT_LANGUAGE"] ) && preg_match_all("#([^;,]+)(;[^,0-9]*([0-9\.]+)[^,]*)?#i",$_SERVER["HTTP_ACCEPT_LANGUAGE"], $matches, PREG_SET_ORDER)) {
			foreach($matches as $match) {
				if ( isset($match[3]) ) {
					$preferred_languages[$match[1]] = floatval($match[3]);
				} else {
					$preferred_languages[$match[1]] = 1.0;
				}
			}
			return $preferred_languages;
		} else {
			return false;
		}
	}
	/**
	 * Return the lang defined by admin UI if no browser
	 *
	 * @since 1.0
	 *
	 */
	function choice_of_home_selected_lang() {
		if ( $this->xili_settings['browseroption'] == 'browser' ) {
			return choice_of_browsing_language();
		} elseif ($this->xili_settings['browseroption'] != '') { /* slug of the lang*/
			return $this->xili_settings['browseroption'];
		} else {
	 		return strtolower($this->default_lang);
		}
	}

	/**
	 * Return the list of preferred languages for displaying pages (see in firefox prefs)
	 * thanks to php.net comments HTTP_ACCEPT_LANGUAGE
	 * @since 0.9.7.5
	 * @updated 0.9.9.4
	 * @updated 2.3.1 - lang_neither_browser
	 * @updated 2.8.7 - strtolower
	 * @updated 2.11.3 - wp_error
	 * @return default target language
	 */
	function choice_of_browsing_language( $test = '') {
		if ( has_filter('choice_of_browsing_language') ) return apply_filters('choice_of_browsing_language',''); // '' 2.3.1
		if ( $this->xili_settings['browseroption'] != 'browser' ) return $this->choice_of_home_selected_lang(); /* in settings UI - after filter to hook w/o UI */
		$listofprefs = $this->the_preferred_languages();
		$default_lang = ( "" != $this->xili_settings['lang_neither_browser'] ) ? $this->xili_settings['lang_neither_browser'] : $this->default_lang ; //2.3.1
		if ( is_array( $listofprefs ) ) {
			arsort($listofprefs, SORT_NUMERIC);
			$listlanguages = get_terms(TAXONAME, array('hide_empty' => false)); 
			if ( ! is_wp_error( $listlanguages )) { // redundant to call in translate_page_on_front_ID filter 2.11.3
				$sitelanguage = $this->match_languages ( $listofprefs, $listlanguages );
				if ( $sitelanguage ) return $sitelanguage->slug;
			}
			return strtolower( $default_lang );
		} else {
			return strtolower( $default_lang );
		}
	}

	/**
	 * @updated 2.9.21
	 * in priority list in firefox five chars must be before 2 chars - it-ch and it
	 */
	function match_languages ( $listofprefs, $listlanguages ) {

		foreach( $listofprefs as $browserlanguage => $priority ) {
					/* match root languages to give similar in site  - first : five chars langs*/
			foreach( $listlanguages as $sitelanguage ) { // strtolower for IE - thanks to z8po // 2.8.7
						// equal
				if ( $sitelanguage->slug == str_replace('-', '_', strtolower( $browserlanguage ) ) ) return $sitelanguage;
						// only first two chars
				if ( strtolower( $browserlanguage ) == substr( $sitelanguage->slug, 0, 2 ) ) return $sitelanguage;
			}
		}
	}

	/**
	 * Choice of language when is_category and all languages
	 *
	 * @since 1.8.9.1
	 * called by get_curlang_action_wp
	 *
	 */
	function choice_of_categories_all_languages( $curlang ) {
		$choice = $this->xili_settings['allcategories_lang'];
		if ( $choice == "browser" ) {
			return $this->choice_of_browsing_language();
		} elseif ( $choice == "firstpost" ) {
			return $curlang ;
		} elseif ( $choice == "" ) {
			if ( function_exists('xl_choice_of_categories_all_languages') ) {
				return xl_choice_of_categories_all_languages () ;
			} else {
				return ''; // return without mo
			}
		}
		return $choice;
	}

	/**
	 * to encapsulate future method
	 *
	 * @since 1.8.9.1
	 * @param post_ID and lang slug
	 */
	function linked_post_in ( $fromID, $lang_slug ) {
		return get_post_meta( $fromID, QUETAG.'-'.$lang_slug, true ); // will be soon changed
	}

	/**
	 * if possible, translate the array of ID of sticky posts
	 *
	 * @since 1.6.1
	 * called by hook option_sticky_posts
	 * @updated 2.8.1
	 */
	function translate_sticky_posts_ID( $original_array ) {
		global $wp_query ;
		if ( !is_admin() && is_home() ) { // because impossible to register the value in admin UI -
		// and because tracs http://core.trac.wordpress.org/ticket/14115
			if ($original_array != array()) {
				$translated_array = array();
				if ( isset( $wp_query->query_vars[QUETAG] )) { //if (isset($_GET[QUETAG])) {  // $_GET not usable by lang perma mode 2.8.1
					$curlang = $wp_query->query_vars[QUETAG]; // $_GET[QUETAG];
				} else {
					$curlang = $this->choice_of_browsing_language(); // rule defined in admin UI
				}
				foreach ($original_array as $id) {
					$langpost = $this->get_cur_language($id);
					$post_lang = $langpost[QUETAG];
					if ($post_lang != $curlang) { // only if necessary
						$trans_id = $this-> linked_post_in( $id, $curlang ) ; // get_post_meta($id, 'lang-'.$curlang, true);
						if ( '' != $trans_id ) {
							$translated_array[] = $trans_id;
						} else {
							if ( $this->sticky_keep_original === true ) $translated_array[] = $id;
							// set by webmaster  in theme functions

						}
					} else {
						$translated_array[] = $id;
					}
				}
				return $translated_array;
			} else {
				return $original_array;
			}
		} else {
			return $original_array;
		}
	}

	/**
	 * cancel default filtering when page on front
	 *
	 *
	 * @updated 2.6.3
	 */
	function get_option_wo_xili ( $option ) {
		if ( $option == 'page_on_front' && has_filter ( 'option_'.$option,  array(&$this, 'translate_page_on_front_ID' )  ) ) { // 2.6.3
			remove_filter ( 'option_'.$option, array(&$this, 'translate_page_on_front_ID') );
			$value = get_option ( $option ) ;
			add_filter ( 'option_'.$option, array(&$this, 'translate_page_on_front_ID') );
		} else {
			$value = get_option (  $option ) ;
		}
		return $value ;
	}

	/**
	 * if possible, translate the ID of front_page post
	 *
	 * @since 1.7.0
	 * called by hook option_page_on_front
	 *
	 */
	function translate_page_on_front_ID ( $original_id ) {
		if (! taxonomy_exists(TAXONAME) ) return $original_id; // for very initial call
		$this->xili_test_lang_perma (); // not detected in WP hook
		global $wp_query ;
		if ( $this->lang_perma ) {
			if ( !is_admin() && $this->show_page_on_front ) {
			 	if ( isset( $wp_query->query_vars[QUETAG] ) && in_array ( $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG]), array_keys ( $this->show_page_on_front_array ) ) && '' != $wp_query->query_vars['page_id'] ) {
					$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG] );



				} elseif ( isset( $wp_query->query_vars[QUETAG] ) && in_array ( $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG]), array_keys ( $this->show_page_on_front_array ) ) ){

					$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG] ); // to verify


				} else {

						$curlang = $this->choice_of_browsing_language(); // rule defined in admin UI

				}
			 	$trans_id = $this->linked_post_in( $original_id, $curlang ) ;

				if ( '' != $trans_id ) {
					return $trans_id;
				} else {
					return $original_id;
				}
			} else {
				return $original_id;
			}

		} else {

			if ( !is_admin() && $this->show_page_on_front ) {
				if ( isset( $wp_query->query_vars[QUETAG] ) ) {  // $this_curlang is not yet set
					$curlang = $this->lang_qv_slug_trans ( $wp_query->query_vars[QUETAG] );
				} else {
					$curlang = $this->choice_of_browsing_language('2760'); // rule defined in admin UI
				}

				$trans_id = $this-> linked_post_in( $original_id, $curlang ) ;

				if ( '' != $trans_id ) {
					return $trans_id;
				} else {
					return $original_id;
				}
			} else {
				return $original_id;
			}
		}
	}

	/**
	 *
	 * @since 2.8.4
	 *
	 */
	function translate_page_for_posts_ID ( $original_id ) {
		if ( empty ( $original_id ) ) return $original_id; // 2.8.4.1
		global $wp_query ;
		xili_xl_error_log ( ' #2790 ------->>>' .  $original_id . serialize ( $this->page_for_posts_name_array ) );

		if ( $this->is_permalink  ) {
			if ( isset($wp_query->query_vars['pagename']) && in_array ( $wp_query->query_vars['pagename'] , $this->page_for_posts_name_array ) ) {

				xili_xl_error_log ( serialize ($this->page_for_posts_name_array) . ' #2795 ------->>>' . $wp_query->query_vars['pagename']  );
				$wp_query->is_page = false;
				$wp_query->is_home = true;
				$wp_query->is_posts_page = true;

				return $this->page_for_posts_name_to_id_array[$wp_query->query_vars['pagename']];

			} else {

				return $original_id;

			}

		} else { // no permalinks
			if ( isset($wp_query->query_vars['page_id']) && in_array ( $wp_query->query_vars['page_id'] , $this->page_for_posts_array ) ) {

				xili_xl_error_log ( serialize ($this->page_for_posts_array) . ' #2811 ------->>>' . $wp_query->query_vars['page_id']  );
				$wp_query->is_page = false;
				$wp_query->is_home = true;
				$wp_query->is_posts_page = true;

				return $wp_query->query_vars['page_id'];

			} else {

				return $original_id;

			}
		}
	}

	/**
	 * List custom post types $type != 'attachment' &&
	 *
	 * @since 1.8.0
	 *
	 */
	function get_custom_desc() {
		$types = get_post_types(array('show_ui'=>1));
		if ( count($types) > 2 ) {
			$thecheck = array() ;
			$thecustoms = array();

			foreach ( $types as $type) {
				$true = ( defined ('XDMSG') ) ? ( $type != XDMSG ) : true ;
				if ( $type != 'attachment' && $type != 'page' && $type != 'post' && $true == true ) { // temporary WP 3.5 attachement UI
					$custom = get_post_type_object ($type);
					$clabels = $custom->labels;
					$thecustoms[$type] = array ('name' => $custom->label, 'singular_name' => $clabels->singular_name, 'multilingual'=>''  ) ;
				}
			}
			return $thecustoms ;
		}
	}

	/**
	 * unassign a language from a series of objects (post or link)
	 * @since 1.8.8
	 *
	 *
	 */
	function multilingual_links_erase ( $lang_term_id ) {
		$languages = $this -> get_listlanguages();

		foreach ($languages as $language ) {
			if ( $language->term_id == $lang_term_id ) {
				$lang_slug = $language->slug ;
				continue ;
			}
		}
		foreach ($languages as $language ) {
			// for other languages as this - delete postmeta linked to post of erased posts
			if ( $language->term_id != $lang_term_id) {
				$post_IDs = get_objects_in_term( array( $language->term_id ), array( TAXONAME ) );
				foreach ( $post_IDs as $post_ID ) {
						delete_post_meta( $post_ID, QUETAG.'-'.$lang_slug ) ;
				}
			}
		}
		// posts
		$post_IDs = get_objects_in_term( array( $lang_term_id ), array( TAXONAME ) );
		foreach ( $post_IDs as $post_ID ) {
			// delete relationships posts
	 		wp_delete_object_term_relationships( $post_ID, TAXONAME );
		}
		// links of blogroll
		$links = get_objects_in_term( array( $lang_term_id ), array( 'link_'.TAXONAME ) );
		foreach ( $links as $link ) {
			wp_delete_object_term_relationships( $link, 'link_'.TAXONAME );
		}
	}



	/**
	 * Set language plugin
	 *
	 * @updated 2.8.9 (plugins_loaded level 9)
	 */
	function init_plugin_textdomain() {
		load_plugin_textdomain('xili-language', false, 'xili-language/languages' );
	}

	// also include automatic search of domain and lang subfolder in current theme
	function init_theme_textdomain() {
		/* in wp3 multisite - don't use constant - for backward compatibility keep it in mono*/
		if ( '' != $this->xili_settings['theme_domain'] ) {
			if (!is_multisite() && !defined('THEME_TEXTDOMAIN') )
				define('THEME_TEXTDOMAIN',$this->thetextdomain); // for backward compatibility;
			if ( is_admin() ) {
				$do = ( $this->xili_settings['theme_domain'] != $this->thetextdomain ) ? true : false ;
				if ( $do ) {
					$this->xili_settings['theme_domain'] = $this->thetextdomain ;
					update_option('xili_language_settings', $this->xili_settings);
				}
			}
		} else {
			$this->domaindetectmsg = __('no load_theme_textdomain in functions.php','xili-language');
		}

	}
	// deprecated
	function searchpath( $path, $filename ) {
		$this->xili_settings['langs_folder'] = str_replace( $this->get_template_directory, '', $path );
	}

	/**
	 * Reset values when theme was changed... updated by previous function
	 * @since 1.1.9
	 */
	function theme_switched ( $theme ) {
		$this->xili_settings['langs_folder'] = "";
		$this->xili_settings['theme_domain'] = "";   /* to force future search in new theme */

		$this->recover_alias_settings_of_previous_used_theme( $theme ) ; // 2.11.1

		update_option('xili_language_settings', $this->xili_settings);
	}

	/**
	 * to add links in current menu - manual insertion in dashboard (obsolete soon)
	 *
	 *
	 *
	 */
	function add_list_of_language_links_in_wp_menu ( $location ) {
		$defaultarray = array(
			'menu-item-type' => 'custom',
			'menu-item-title' => '',
			'menu-item-url' => '',
			'menu-item-description' => '',
			'menu-item-status' => 'publish');
		$url = get_bloginfo('url') ;
		$listlanguages = get_terms_of_groups_lite ($this->langs_group_id,TAXOLANGSGROUP,TAXONAME,'ASC');
		$langdesc_array = array();
		foreach ($listlanguages as $language){
			$langdesc_array[] = $language->description;
		}
		/* detect menu inside location */
		$menu_locations =  get_nav_menu_locations();
		$menuid = $menu_locations[$location];
		$menuitem = wp_get_nav_menu_object($menuid);
		$items = get_objects_in_term( $menuitem->term_id, 'nav_menu' );
		$nothere = true;
		if ( ! empty( $items ) ) {
			$founditems = wp_get_nav_menu_items($menuid); //try to see if a previous insert was done
			foreach ($founditems as $item) {
		 		if ($item->title =='|' || in_array($item->title, $langdesc_array)) {
		 			$nothere = false;
		 			break;
		 		}
		 	}
		}
		if ( $nothere == true ) {
			/* add separator */
				$defaultarray['menu-item-title'] = '|';
				$defaultarray['menu-item-url'] = $url.'/#';
				wp_update_nav_menu_item($menuid,0,$defaultarray);
			foreach ( $listlanguages as $language ){
				$defaultarray['menu-item-title'] = $language->description ;
				$defaultarray['menu-item-url'] = $url.'/?lang='.$language->slug ;
				wp_update_nav_menu_item( $menuid, 0, $defaultarray );
			}
			return __( "language items added", "xili-language" );
		} else {
			return __("seems to be set","xili-language");
		}
	}

	/**
	 * ***** Functions to improve bookmarks language filtering *****
	 *
	 */

	/**
	 * Filter to widget_links parameter
	 * @ since 1.8.5
	 */
	function widget_links_args_and_lang ($widget_args) {

		$cur_lang = $this->curlang;
		// rules depending category and settings in xili-language
		$cat_settings = $this->xili_settings['link_categories_settings'] ; //array ( 'all'=> true, 'category' => array ( '2' => false , '811' => true ) );
		$sub_select = false;
		if ( $widget_args['category'] ) {
			$sub_select = $cat_settings['category'][$widget_args['category']] ;
		} else {
			$sub_select = $cat_settings['all'];
			// if ( $sub_select ) $widget_args['categorize'] = 0;
		}

		if ( $sub_select ) {
			$linklang = term_exists($cur_lang,'link_'.TAXONAME) ;
			$linklang_ever = term_exists('ev_er','link_'.TAXONAME) ; // the dummy lang - shown ever with selected language
			if ( $cur_lang && $linklang ) {
				if ( $widget_args['category'] ) {
					$cat = get_term( $widget_args['category'], 'link_category' );
					$catname = apply_filters( "link_category", $cat->name );
				}
				$the_link_ids = array ();

				$the_link_ids_cat = get_objects_in_term( array( $widget_args['category'] ), 'link_category' ) ;
				$the_link_ids = get_objects_in_term( array( $linklang['term_id'], $linklang_ever['term_id'] ), 'link_'.TAXONAME ) ; // lang + ever
				$the_link_ids_all = array_intersect ($the_link_ids , $the_link_ids_cat );
				if ( $widget_args['category'] ) $widget_args['categorize'] = 0; // no sub list in one cat asked
				$widget_args['include'] = implode (',' , $the_link_ids_all );
				$widget_args['category'] = ''; // because implode of intersect
				$widget_args['title_li'] = $catname ;

			}
		}
		return $widget_args ;
	}

	/**
	 * only active if 'lang' in template tag wp_list_bookmarks()
	 *
	 * as :  wp_list_bookmarks( array( 'lang'=>the_curlang() ) ) to display only in current language
	 *
	 * don't interfere with widget_links filter
	 *
	 * @ since 1.8.5
	 */
	function the_get_bookmarks_lang ($links_list, $args) {
		if ( isset( $args[QUETAG] ) ) {
			// get links in selected lang
			$linklang = term_exists($args[QUETAG],'link_'.TAXONAME) ;
			$linklang_ever = term_exists('ev_er','link_'.TAXONAME) ; // the dummy lang - shown ever with selected language
			//global $the_link_ids;
			$this->the_link_ids = get_objects_in_term( array( $linklang['term_id'], $linklang_ever['term_id'] ), 'link_'.TAXONAME ) ;

			return array_filter ( $links_list , array(&$this,'_filtering_links') ) ;

		}
		return $links_list;
	}
	function _filtering_links ($link) {
		//global $the_link_ids;
		if ( in_array( $link->link_id , $this->the_link_ids ) ) return true ;
	}

	/**
	 * Register link language taxonomy
	 * @ since 1.8.5
	 */
	function add_link_taxonomy () {
		register_taxonomy( 'link_'.TAXONAME, 'link', array('hierarchical' => false, 'label' => false, 'rewrite' => false, 'update_count_callback' =>  array(&$this,'_update_link_lang_count'), 'show_ui' => false, '_builtin' => false ));

	}
	// count update
	function _update_link_lang_count( $terms ) {
		//
		global $wpdb;
			foreach ( (array) $terms as $term ) {
				$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->links WHERE $wpdb->links.link_id = $wpdb->term_relationships.object_id AND term_taxonomy_id = %d", $term ) );
				$wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
			}
	}


	/** end of language for bookmarks * @ since 1.8.5 **/


	/**
 	* now active in same file as class xili_language
 	* Widgets registration after classes rewritten
 	*
 	* @since 1.8.8
 	*/

	function add_new_widgets() {
		load_plugin_textdomain('xili-language-widget',false, 'xili-language/languages'); // 1.8.8.1 fixes translation and red messages
 		register_widget('xili_Widget_Recent_Posts'); // since 1.3.2
 		register_widget('xili_WP_Widget_Recent_Comments'); // since 1.8.3
 		register_widget('xili_language_Widgets'); // since 1.8.3
	}

	//********************************************//
	// Functions for themes (hookable by add_action() in functions.php - 0.9.7
	//********************************************//

	/**
	 * List of available languages.
	 *
	 * @since 0.9.0
	 * @updated 0.9.7.4 - 0.9.8.3 - 0.9.9.6 - 1.5.5 (add class current-lang in <a>)
	 * @updated 1.6.0 - new option for nav menu hook and echoing 4th param - better permalink
	 * @updated 1.8.1 - delete 'in' prefix in list - class if LI
	 * can be hooked by add_action in functions.php
	 * with : add_action('xili_language_list','my_infunc_language_list',10,4);
	 *
	 * for multiple widgets since 0.9.9.6, 1.6.0 : incorporate options
	 * @updated 2.11.1
	 *
	 * @param $before = '<li>', $after ='</li>'.
	 * @return list of languages of site for sidebar list.
	 */
	function xili_language_list( $before = '<li>', $after ='</li>', $option='', $echo = true, $hidden = false ) {
		global $post, $wp_query;
		$lang_perma = $this->lang_perma; // since 2.1.1

		$before_class = false ;
		if ( substr($before,-2) == '.>' ) { // tips to add dynamic class in before
			$before_class = true ;
			$before = str_replace('.>','>',$before);
		}
		$listlanguages = $this->get_listlanguages();
		$a = ''; // 1.6.1

		if ($option == 'typeone') {
			/* the rules : don't display the current lang if set and add link of category if is_category()*/

			foreach ($listlanguages as $language) {
				$this->doing_list_language = $language->slug;
				$currenturl = $this->current_url ( $lang_perma ); // 2.5
				$language_qv =  $this->lang_slug_qv_trans ( $language->slug );
				$display = ( $hidden && ( $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden' ) ) ? false : true ;
				if ($language->slug != the_curlang()   && $display ) {
					$beforee = ( $before_class && $before == '<li>' ) ? '<li class="lang-'.$language->slug.'" >': $before;
					$class = ' class="lang-'.$language->slug.'"';

					$link = ( $lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;

					$a .= $beforee .'<a '.$class.' href="'.$link.'" title="'. esc_attr( sprintf(__($this->xili_settings['list_link_title']['post_selected'], $this->thetextdomain ), _x( $language->description, 'linktitle', $this->thetextdomain ) ) ) .'" >'. __( $language->description, $this->thetextdomain ) .'</a>'.$after;
				}
			}
			$this->doing_list_language = false;
		} elseif ($option == 'typeonenew') {  // 2.1.0
			/* the rules : don't display the current lang if set and add link of category if is_category() but display linked singular */

			foreach ($listlanguages as $language) {
				$this->doing_list_language = $language->slug;
				$currenturl = $this->current_url ( $lang_perma ); // 2.5
				$language_qv =  $this->lang_slug_qv_trans ( $language->slug );
				$display = ( $hidden && ( $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden' ) ) ? false : true ;
				if ($language->slug != the_curlang()   && $display ) {
					$beforee = ( $before_class && $before == '<li>' ) ? '<li class="lang-'.$language->slug.'" >': $before;
					$class = ' class="lang-'.$language->slug.'"';

					if ( ( is_single() || is_page() ) && !is_front_page() ) {
						$link = $this->link_of_linked_post ( $post->ID, $language->slug ) ;
						$title = sprintf (__($this->xili_settings['list_link_title']['current_post'], $this->thetextdomain ), _x($language->description, 'linktitle', $this->thetextdomain ) );
					} else if (  $wp_query->is_posts_page ) { // 2.8.4
						$link = $this->link_of_linked_post ( get_option( 'page_for_posts' ) , $language->slug ) ;
						$title = sprintf (__($this->xili_settings['list_link_title']['latest_posts'], the_theme_domain()), _x($language->description, 'linktitle', $this->thetextdomain) ) ;
					} else {
						$link = ( $lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;
						$title = sprintf (__($this->xili_settings['list_link_title']['post_selected'], $this->thetextdomain ), _x($language->description, 'linktitle', $this->thetextdomain ) ) ;
					}

					$a .= $beforee .'<a '.$class.' href="'.$link.'" title="'. esc_attr( $title ) .'" >'. __($language->description, $this->thetextdomain ) .'</a>' . $after;
				}
			}
			$this->doing_list_language = false;

		} elseif ( in_array ( $option, array ( 'navmenu', 'navmenu-a' ) ))  {	 /* current list in nav menu 1.6.0 */
			if ( $lang_perma ) {
				$currenturl = get_bloginfo('url').'/%lang%/';
			} else {
	 			$currenturl = get_bloginfo('url').'/?';
			}
				foreach ($listlanguages as $language) {

					if ( ! ( $option == 'navmenu-a' && $language->slug == the_curlang() ) ) { // 2.8.4.3
						$language_qv =  $this->lang_slug_qv_trans ( $language->slug );
						$display = ( $hidden && ( $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden' ) ) ? false : true ;
						if ( $display ) {
							if ($language->slug != the_curlang() ) {
								$class = " class='menu-item menu-item-type-custom lang-".$language->slug."'";
							} else {
								$class = " class='menu-item menu-item-type-custom lang-".$language->slug." current-lang current-menu-item'";
							}
							$beforee = (substr($before,-1) == '>') ? str_replace('>',' '.$class.' >' , $before ) : $before ;


							$link = ( $lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;

							$a .= $beforee .'<a href="'.$link.'" title="'. esc_attr( sprintf(__($this->xili_settings['list_link_title']['post_selected'], $this->thetextdomain ), _x( $language->description, 'linktitle', $this->thetextdomain ) ) ).'" >'. __( $language->description, $this->thetextdomain ) . '</a>' . $after;
						}
					}
				}

		} elseif ( in_array ( $option, array ( 'navmenu-1', 'navmenu-1a', 'navmenu-1ao' ) ) )  {	// 2.1.0  and single

				foreach ($listlanguages as $language) {
					$link = false;
					if ( ! ( ( $option == 'navmenu-1a' || $option == 'navmenu-1ao' ) && $language->slug == the_curlang() ) ) { // 2.8.4.3

						$language_qv =  $this->lang_slug_qv_trans ( $language->slug );
						$display = ( $hidden && ( $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden' ) ) ? false : true ;
						if ( $display ) {

							if ( $language->slug != the_curlang() ) {
								$class = " class='menu-item menu-item-type-custom lang-".$language->slug."'";
							} else {
								$class = " class='menu-item menu-item-type-custom lang-".$language->slug." current-lang current-menu-item'";
							}

							if ( ( is_singular() && !is_front_page()) ) {
								if ($option == 'navmenu-1a') {
									$link = $this->link_of_linked_post ( $post->ID, $language->slug ) ;
								} else {
									$targetpost = $this->linked_post_in ( $post->ID, $language->slug ) ;
	 								if ( $targetpost ) {
	 									$link = get_permalink($targetpost);
	 								}
								}
								$title = sprintf (__($this->xili_settings['list_link_title']['current_post'],the_theme_domain()), __($language->description, $this->thetextdomain) ) ;
							} else if (  $wp_query->is_posts_page ) { // 2.8.4
								$link = $this->link_of_linked_post ( get_option( 'page_for_posts' ) , $language->slug ) ;
								$title = sprintf (__($this->xili_settings['list_link_title']['latest_posts'], the_theme_domain()), _x($language->description, 'linktitle', $this->thetextdomain) ) ;
							} else {
								$this->doing_list_language = $language->slug;
								$currenturl = $this->current_url ( $lang_perma ); // 2.5
								$link = ( $lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;
								$title = sprintf ( __($this->xili_settings['list_link_title']['post_selected'], the_theme_domain()), _x($language->description, 'linktitle', $this->thetextdomain ) ) ;
							}

							$beforee = (substr($before,-1) == '>') ? str_replace('>',' '.$class.' >' , $before ) : $before ;
							if ( $link ) {
								$a .= $beforee .'<a href="'.$link.'" title="'. esc_attr( $title ) .'" >'. __($language->description, $this->thetextdomain ) .'</a>'.$after;
							}
						}
					}
				}
				$this->doing_list_language = false;

		} else {	/* current list only root */
			if ( $lang_perma ) {
				$currenturl = get_bloginfo('url').'/%lang%/';
			} else {
 				$currenturl = get_bloginfo('url').'/?';
			}
			foreach ($listlanguages as $language) {
				$language_qv =  $this->lang_slug_qv_trans ( $language->slug );
				$display = ( $hidden && ( $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden' ) ) ? false : true ;

				if ( $display ) {
					if ( $language->slug != the_curlang() ) {
						$class = " class='lang-".$language->slug."'";
					} else {
						$class = " class='lang-".$language->slug." current-lang'";
					}

					$link = ( $lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;

					$beforee = ( $before_class && $before == '<li>' ) ? '<li class="lang-'.$language->slug.'" >': $before;
					$a .= $beforee .'<a '.$class.' href="'.$link.'" title="'. esc_attr( sprintf(__($this->xili_settings['list_link_title']['post_selected'], $this->thetextdomain ), _x( $language->description, 'linktitle', $this->thetextdomain ) ) ) .'" >'. __( $language->description, $this->thetextdomain ) .'</a>' . $after;
				}
			}
		}
		if ($echo)
			echo $a;
		else
			return $a;
	}

	/**
	 * insert languages list objects in nav menu at insertion point (filter wp_nav_menu_objects)
	 *
	 * @since 2.8.8
	 * @updated 2.9.11 (page) , 2.9.20 (menu)
	 * @updated 2.10.1 - singular if exists
	 * @updated 2.11.2 - better class assignation (ancestor) - thanks to Bastian
	 *
	 */
	function insert_language_objects_in_nav_menu ( $sorted_menu_items, $args ) {
		global $post, $wp_query;
		// detect insertion point menu object and menu type

		$new_sorted_menu_items = array();

		foreach ( $sorted_menu_items as $key => $menu_object ) {

			if ( $menu_object->url == $this->insertion_point_dummy_link_menu ) {

				$queried_object = $wp_query->get_queried_object();
				$queried_object_id = (int) $wp_query->queried_object_id;

				$langkey = explode ( '-', str_replace ( 'menu-wo-', '', $menu_object->attr_title ) ) ;

				foreach ( $menu_object->classes as $one_class ) {
					if ( false !== strpos ( $one_class , 'xlmenulist-' ) ){
						$menu_id_list = str_replace ( 'xlmenulist-', '', $one_class );
						continue;
					}
				}
				$menu_ids = explode ( '-', $menu_id_list );

				$menu_list =  array_combine ( $langkey, $menu_ids );

				$curlang = the_curlang();

				if ( $curlang && isset ( $menu_list [ $curlang ] ) && (int) $menu_list [ $curlang ] > 0 ) {

					$menu_items = wp_get_nav_menu_items( $menu_list [ $curlang ] );
					_wp_menu_item_classes_by_context( $menu_items ); // added 2.11.2
					if ( $menu_items ) {
						foreach (  $menu_items as $new_menu_item ) {
							// not recursive :  impossible to decode insertion point inside menu
							if 	( !in_array ( $new_menu_item->url , array ( $this->insertion_point_dummy_link_menu, $this->insertion_point_dummy_link_page, $this->insertion_point_dummy_link ) )  ) {

								$new_classes = array("insertion-point");
								
								if ( $new_menu_item->menu_item_parent == 0 )
									$new_menu_item->menu_item_parent = $menu_object->menu_item_parent; // heritate from insertion point

								$new_menu_item->classes = array_merge ( $menu_object->classes, $new_classes, $new_menu_item->classes ); // fixed 2.11.2

								$new_sorted_menu_items[] = $new_menu_item;

							}
						}
					}
				}

			} else if ( $menu_object->url == $this->insertion_point_dummy_link_page ) {
				$classes = $menu_object->classes;

				$i = 0;

				$defaults = array(
					'sort_order' => 'ASC',
					'sort_column' => 'menu_order',
					'hierarchical' => 1,
					QUETAG => $this->curlang
				);

				$r = wp_parse_args( $menu_object->attr_title, $defaults );
				extract( $r, EXTR_SKIP );

				$pagelist = get_pages ( $r );

				foreach ( $pagelist as $onepage ) {

					$class = ( is_page ($onepage->ID) ) ? ' current-menu-item' : '';
					$i++;

					$new_lang_menu_item = (object) array();
					$id = $menu_object->ID * 1000 + $i;
					$new_lang_menu_item->ID = $id;
					$new_lang_menu_item->url = $onepage->guid;
					$new_lang_menu_item->title = $onepage->post_title;;
					$new_lang_menu_item->attr_title = '...';

					$new_lang_menu_item->menu_item_parent = $menu_object->menu_item_parent;
					$new_lang_menu_item->db_id = $menu_object->db_id;
					$new_lang_menu_item->target = $menu_object->target;

					$new_lang_menu_item->classes = array_merge ( $menu_object->classes, explode ( ' ', $class ) );

					$new_sorted_menu_items[] = $new_lang_menu_item;

				}

			} else if ( $menu_object->url == $this->insertion_point_dummy_link ) { // language

				$classes = $menu_object->classes;

				$keys = array () ;

				foreach ( $this->langs_list_options as $one ) {
					$keys[] = $one[0];
				}

				$type_array = array_values ( array_intersect ( $keys , $menu_object->classes  ) );

				$type = $type_array[0];

				$hidden = true ; // hidden here as defined in list - only available language are listed

				// create array of language menu objects
				$listlanguages = $this->get_listlanguages();
				$new_menu_objects = array();
				$i = 0;
				foreach ( $listlanguages as $language ) {
					$link = false;
					$display = ( $hidden && ( $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden' ) ) ? false : true ;

					if ( $display && ( ! ( in_array ($type,  array ( 'navmenu-a', 'navmenu-1a', 'navmenu-1ao' ) ) && $language->slug == the_curlang() ) ) ) {
						$i++;
						if ($language->slug != the_curlang() ) {
							$class = 'lang-'.$language->slug;
						} else {
							$class = 'lang-'.$language->slug . ' current-lang current-menu-item';
						}
						$language_qv =  $this->lang_slug_qv_trans ( $language->slug );

						if ( in_array ( $type,  array ( 'navmenu-1', 'navmenu-1a', 'navmenu-1ao' ) ) ) {
							$this->doing_list_language = $language->slug; //  for date filter if lang_perma
							$currenturl = $this->current_url ( $this->lang_perma );

							if (  is_singular() && !is_front_page()  ) {
								if ($type == 'navmenu-1a') {
									$link = $this->link_of_linked_post ( $post->ID, $language->slug ) ;
								} else {
									$targetpost = $this->linked_post_in ( $post->ID, $language->slug ) ;
	 								if ( $targetpost ) {
	 									$link = get_permalink($targetpost);
	 								}
								}
								$title = sprintf (__($this->xili_settings['list_link_title']['current_post'],the_theme_domain()), __($language->description, $this->thetextdomain) ) ;
							} else if (  $wp_query->is_posts_page ) { // 2.8.4
								$link = $this->link_of_linked_post ( get_option( 'page_for_posts' ) , $language->slug ) ;
								$title = sprintf (__($this->xili_settings['list_link_title']['latest_posts'], the_theme_domain()), _x($language->description, 'linktitle', $this->thetextdomain) ) ;

							} else if ( function_exists('xili_tidy_tag_in_other_lang') && ( is_tag () || $this->is_tax_improved() )  ) { // 2.9.1

								$q = '';
								if ( !is_tag () && $this->is_tax_improved() ) {
									$queried_object = $wp_query->get_queried_object();
									$q = '&tidy_post_tag='. $queried_object->taxonomy ;
								}

								if ( $link = xili_tidy_tag_in_other_lang("format=term_link&lang=".$language->name.$q )) {
									$title =  xili_tidy_tag_in_other_lang("format=term_name&lang=".$language->name.$q );
								} else {
									$link = ( $this->lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;
									$title = sprintf ( __($this->xili_settings['list_link_title']['post_selected'], the_theme_domain()), _x($language->description, 'linktitle', $this->thetextdomain ) ) ;
								}

							} else {
								$link = ( $this->lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;

								$title = sprintf ( __($this->xili_settings['list_link_title']['post_selected'], the_theme_domain()), _x($language->description, 'linktitle', $this->thetextdomain ) ) ;
							}
							$this->doing_list_language = false;

						} else { // 'navmenu', 'navmenu-a'
							if ( $this->lang_perma ) {
								$currenturl = get_bloginfo('url').'/%lang%/';
							} else {
	 							$currenturl = get_bloginfo('url').'/?';
							}
							$link = ( $this->lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG."=".$language_qv ;
							$title = esc_attr( sprintf(__($this->xili_settings['list_link_title']['post_selected'], $this->thetextdomain ), _x( $language->description, 'linktitle', $this->thetextdomain ) ) );
						}

						// only required values...
						if ( $link ) {
							$new_lang_menu_item = (object) array();
							$id = $menu_object->ID * 100 + $i;
							$new_lang_menu_item->ID = $id;
							$new_lang_menu_item->url = $link;
							$new_lang_menu_item->title = __( $language->description, $this->thetextdomain );
							$new_lang_menu_item->attr_title = $title;

							$new_lang_menu_item->menu_item_parent = $menu_object->menu_item_parent;
							$new_lang_menu_item->db_id = $menu_object->db_id;
							$new_lang_menu_item->target = $menu_object->target;

							$new_lang_menu_item->classes = array_merge ( $menu_object->classes, explode ( ' ', $class ) );

							$new_sorted_menu_items[] = $new_lang_menu_item;
						}

					} // language
				} // display

			} else { // no dummy insertion

				   $new_sorted_menu_items[] = $menu_object;
			}

		} // foreach menu

		return $new_sorted_menu_items;
	}

	/**
	 * to improve limits of is_tax()
	 *
	 * @since 2.9.1
	 *
	 * tracs #2444 - http://bbpress.trac.wordpress.org/ticket/2444
	 *
	 */

	function is_tax_improved () {

		if ( is_tag () ) return true;

		global $wp_query;
		$queried_object = $wp_query->get_queried_object();

		if ( isset ( $queried_object->taxonomy ) && !in_array( $queried_object->taxonomy, array( '', 'category', 'post_tag' ) ) ) {

			// test taxonomy topic-tag - cannot use is_tax -

			if ( class_exists ( 'bbpress' ) && $queried_object->taxonomy == get_option( '_bbp_topic_tag_slug', 'topic-tag' )  ) return true;

			// test other taxonomy

			if ( is_tax ( $queried_object->taxonomy ) ) return true;
		}
		return false;
	}


	/**
	 * insert language list in nav menu at insertion point
	 *
	 * @since 2.8.8
	 *
	 * @replaced by above function 2.8.8-b3
	 *
	 */
	function insert_language_list_in_nav_menu ( $items, $args ) {

		if ( 0 != strpos( $items, '>' . $this->insertion_point_box_title . '<' )) {

			$res = preg_match('/<li(.*)class="(.*)">(.*)href="'. $this->insertion_point_dummy_link .'(.*)<\/li>/i', $items, $matches );

			if ( $res == 1 ) {

				$keys = array () ;

				foreach ( $this->langs_list_options as $one ) {
					$keys[] = $one[0];
				}

				$type = array_values ( array_intersect ( $keys , explode ( ' ', $matches[2] ) ) );

				$list_to_insert = xili_language_list( '<li>', '</li>', $type[0] , false, true ) ;

				$items = preg_replace ( '/<li(.*)href="'. $this->insertion_point_dummy_link . '(.*)<\/li>/i', $list_to_insert, $items);

			}

		}
		return $items;
	}


	/**
	 * link of linked post
	 *
	 * @since 2.1.0
	 *
	 * @updated 2.1.1
	 */
	function link_of_linked_post ( $fromID, $lang_slug ) {
	 	$targetpost = $this->linked_post_in ( $fromID, $lang_slug ) ;
	 	if ( $targetpost ) {
	 		return get_permalink($targetpost);
	 	} else {
	 		if ( $this->lang_perma ) {
	 			$currenturl = get_bloginfo('url').'/%lang%/';
	 		} else	{
	 			$currenturl = get_bloginfo('url') . '/?';
	 		}
	 		$language_qv =  $this->lang_slug_qv_trans ( $lang_slug );
	 		$link = ( $this->lang_perma ) ? str_replace ( '%lang%', $language_qv, $currenturl ) : $currenturl.QUETAG . '=' . $language_qv ;
	 	    return $link ;
	 	}
	}

	/**
	 * For widget - the list of options above
	 * @since 1.6.0
	 */
	function xili_language_list_options () {
		$this->langs_list_options = array( array('','default'), array('typeone','Type n°1'), array('typeonenew','Type for single') );
	}


	/**
	 * language of current post used in loop
	 * @since 0.9.0
	 *
	 * @updated 2.5.1
	 *
	 * @param $before = '<span class"xili-lang">(', $after =')</span>'.
	 * @return language of post.
	 */
	function xili_post_language( $before = '<span class="xili-lang">(', $after =')</span>', $type = 'iso' ) {
		global $post ;
		$langpost = $this->get_post_language ( $post->ID, $type );

		if ( '' != $langpost ) :
				$curlangname = $langpost;
		else :
				$curlangname = __('undefined', $this->thetextdomain);
		endif;
		$a = $before . $curlangname .$after.'';
		echo $a;
	}

	/**
	 * for one post create a link list of the corresponding posts in other languages
	 *
	 * @since 0.9.0
	 * @updated 0.9.9.2 / 3 $separator replace $after, $before contains pre-text to echo a better list.
	 * @updated 1.1 - see hookable same name function outside class
	 * @updated 2.8.8 - if type == "", return html list
	 * can be hooked by add_action in functions.php
	 *
	 *
	 */
	function the_other_posts ( $post_ID, $before = "This post in", $separator = ", ", $type = "display" ) {
		/* default here*/
			$outputarr = array();
			$output = '';

			$listlanguages = get_terms(TAXONAME, array('hide_empty' => false));
			$langpost = $this->get_cur_language($post_ID); // to be used in multilingual loop since 1.1
			$post_lang = $langpost[QUETAG];
			foreach ($listlanguages as $language) {
				$otherpost = $this->linked_post_in( $post_ID, $language->slug ) ; //get_post_meta($post_ID, 'lang-'.$language->slug, true);

				if ( $type == "display" || $type == "" ) { // 2.8.8
					if ('' != $otherpost && $language->slug != $post_lang ) {
						$outputarr[] = "<a href='".get_permalink($otherpost)."' >"._x($language->description, 'otherposts', $this->thetextdomain) ."</a>";
					}
				} elseif ($type == "array") { // here don't exclude cur lang
					if ('' != $otherpost)
						$outputarr[$language->slug] = $otherpost;
				}
			}
			if ( $type == "display" || $type == "" ) { // 2.8.8
				if ( !empty($outputarr) ) {
					$output =  ( ( $before !="" ) ? __( $before, $this->thetextdomain )." " : "" ) . implode ( $separator, $outputarr );
					if ( $type == "display")
						echo $output;
					else
						return $output;

				} else if ( $type == "") {
					return "" ;
				}

			} else if ( $type == "array") {
				if ( !empty($outputarr) ) {
					$outputarr[$post_ID] = $post_lang;
					// add a key with curid to give his lang (empty if undefined)
					return $outputarr;
				} else {
					return false;
				}
			}
	}

	/**
	 * the_category() rewritten to keep new features of multilingual (and amp & pbs in link)
	 *
	 * @since 0.9.0
	 * @updated 0.9.9.4 - 2.8.9
	 * can be hooked by add_action xiliml_the_category in functions.php
	 *
	 */
	function the_category( $post_ID, $separator = ', ' ,$echo = true ) {
		global $wp_rewrite;
		/* default here*/
		$thelist = '';
		$the_cats_list = wp_get_object_terms($post_ID, 'category');
		$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"'; // 2.8.9
		$i = 0;

		$view_all_posts = __( $this->xili_settings['list_link_title']['view_all_posts'], $this->thetextdomain  ) ;

		foreach ($the_cats_list as $the_cat) {
			if ( 0 < $i )
				$thelist .= $separator . ' ';

			$desc4title = trim( esc_attr( apply_filters( 'category_description', $the_cat->description, $the_cat->term_id ) ) );

			$title = ( '' == $desc4title ) ? esc_attr( sprintf( $view_all_posts, __( $category->name, $this->thetextdomain) ) ) : $desc4title;

			$the_catlink = '<a href="' . get_category_link($the_cat->term_id) . '" title="' . $title . '" ' . $rel . '>';
			//if ($curlang != DEFAULTSLUG) :
	      	$the_catlink .=  __( $the_cat->name, $this->thetextdomain ).'</a>';;
	      	//else :
	      		 //$the_catlink .=  $the_cat->name.'</a>';;
	      	//endif;
			$thelist .= $the_catlink;
			++$i;
		}
		if ( $echo ) :
			echo $thelist;
			return true;
		else :
			return $thelist;
		endif;
	}

	/**
	 * Add list of languages in radio input - for search form.
	 *
	 * @since 0.9.7
	 * can be hooked by add_action in functions.php
	 *
	 * @updated 0.9.9.5, 1.8.2, 2.2.0 , 2.2.2, 2.8.6
	 *
	 * $before, $after each line of radio input
	 *
	 * @param $before, $after.
	 * @return echo the form.
	 */
	function xiliml_langinsearchform ( $before='', $after='', $echo = true ) {
			/* default here*/
		global $wp_query;
		$listlanguages = get_terms(TAXONAME, array('hide_empty' => false));
		$a = '';
		foreach ($listlanguages as $language) {
			if ( is_search() ) {
				if ( isset( $wp_query->query_vars[QUETAG] ) ) { // to rebuilt form after search query
					$selected = ( ( $language->slug == $wp_query->query_vars[QUETAG] ) ) ? 'checked="checked"' : "" ; //2.2.2
				} else {
					$selected = "";
				}
			} else {
				$selected =  ( ( $language->slug == $this->curlang ) ) ? 'checked="checked"' : "" ;
			}
			$a .= $before.'<input onClick="if(this.form.clear.checked) { this.form.clear.checked = false; }" type="radio" name="'.QUETAG.'" value="'.$language->slug.'" id="'.QUETAG.'-'.$language->slug.'" '.$selected.' />&nbsp;'._x( $language->description, 'searchform', $this->thetextdomain ).' '.$after;
		}
		// new javascript to uncheck radio buttons	on form named searchform form.
	    $a .= $before.'<input type="radio" name="clear" onClick="
for (var i=0; i < this.form.' .QUETAG .'.length ; i++) { if(this.form.'.QUETAG.'[i].checked) { this.form.'.QUETAG.'[i].checked = false; }  };" />&nbsp;'. __('All', $this->thetextdomain ) . $after;
 // this to all lang query

	    if ( $echo )
	    	echo $a;
	    else
	    	return $a;

	}

	/**
	 * Language filter for latest comments widget
	 * @since 2.9.22
	 *
	 */
	function xili_language_comments_clauses ( $clauses, $wp_comment_query ) {

		$lang = ( isset( $wp_comment_query->query_vars[QUETAG] ) ) ? ( ( $wp_comment_query->query_vars[QUETAG] == '*' ) ? $this->curlang : $wp_comment_query->query_vars[QUETAG] ) : '' ;

		if ( $lang != '' ) {

			$reqtag = term_exists( $lang, TAXONAME );

			if ( $reqtag ) {

				global $wpdb;

				$wherereqtag = $reqtag['term_id'];
				$join = " LEFT JOIN $wpdb->term_relationships as tr ON ($wpdb->comments.comment_post_ID = tr.object_id) LEFT JOIN $wpdb->term_taxonomy as tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";
				$where = " AND tt.taxonomy = '".TAXONAME."' ";
				$where .= " AND tt.term_id = $wherereqtag ";
				$clauses['where'] = $clauses['where'] . $where;
				$clauses['join'] = $clauses['join'] . $join;
			}
		}

		return $clauses;
	}


	/**
	 * Select latest comments in current lang.
	 *
	 * @since 0.9.9.4
	 * @now 2.9.22 - obsolete
	 * used by widget xili-recent-comments
	 *
	 * @param $number.
	 * @return $comments.
	 */
	function xiliml_recent_comments ( $number = 5 ) {
		global $comments, $wpdb ;
		if ( !$comments = wp_cache_get( 'xili_language_recent_comments', 'widget' ) ) {
				$join = "";
				$where = "" ;// AND 'post_status' = 'publish' ;
				$reqtag = term_exists( $this->curlang, TAXONAME );
					if (''!= $reqtag) {
						$wherereqtag = $reqtag['term_id'];
						$join = " LEFT JOIN $wpdb->term_relationships as tr ON ($wpdb->comments.comment_post_ID = tr.object_id) LEFT JOIN $wpdb->term_taxonomy as tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";
					    $where = " AND tt.taxonomy = '".TAXONAME."' ";
					    $where .= " AND tt.term_id = $wherereqtag ";
					}
				$query = "SELECT * FROM $wpdb->comments".$join." WHERE comment_approved = '1' ".$where." ORDER BY comment_date_gmt DESC LIMIT $number";

				$comments = $wpdb->get_results($query);
				wp_cache_add( 'xili_language_recent_comments', $comments, 'widget' );
		}
		return $comments;
	}

	/**
 	* Enable to add functions and filters that are not in theme's functions.php
 	* These filters are common even if you change default theme...
 	* Place your functions.php in folder plugins/xilidev-libraries/
 	* if you have a filter in this file, avoid to have similar one in functions.php of the theme !!!
	*
 	*/
	function insert_gold_functions () {
		$gold_path = WP_PLUGIN_DIR . $this->xilidev_folder ; /* since 1.0 to add xili-libraries */
		if ( $this->xili_settings['functions_enable'] !='' && file_exists( $gold_path . '/functions.php') )
			include_once ( $gold_path . '/functions.php');
	}

	/**
	 * Retrieve category list in either HTML list or custom format - as in category-template - rewritten for multilingual - filter the_category only frontend
	 *
	 * @since 1.7.0
	 *
	 * @param string $separator Optional, default is empty string. Separator for between the categories.
	 * @param string $parents Optional. How to display the parents.
	 * no third param because call by end filter
	 * @return string
	 */
	function xl_get_the_category_list( $thelist, $separator = '', $parents='' ) {
		global $wp_rewrite, $post;
		$categories = get_the_category( $post->ID );
		//if ( !is_object_in_taxonomy( get_post_type( $post_id ), 'category' ) )
			//return apply_filters( 'the_category', '', $separator, $parents );

		if ( empty( $categories ) ) {
			return  __( 'Uncategorized', $this->thetextdomain ) ; // fixed - avoid a previous recursive filter with custom @since 1.8.0
		}
		$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

		$thelist = '';
		$view_all_posts = __( $this->xili_settings['list_link_title']['view_all_posts'], $this->thetextdomain  ) ;
		if ( '' == $separator ) {
			$thelist .= '<ul class="post-categories">';
			foreach ( $categories as $category ) {
				$thelist .= "\n\t<li>";
				switch ( strtolower( $parents ) ) {
					case 'multiple':
						if ( $category->parent )
							$thelist .= get_category_parents( $category->parent, true, $separator );
						$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( $view_all_posts, __($category->name, $this->thetextdomain) ) ) . '" ' . $rel . '>' . __($category->name, $this->thetextdomain).'</a></li>';
						break;
					case 'single':
						$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( $view_all_posts, __($category->name, $this->thetextdomain) ) ) . '" ' . $rel . '>';
						if ( $category->parent )
							$thelist .= get_category_parents( $category->parent, false, $separator );
						$thelist .= __($category->name, $this->thetextdomain).'</a></li>';
						break;
					case '':
					default:
						$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( $view_all_posts, __($category->name, $this->thetextdomain) ) ) . '" ' . $rel . '>' . __($category->cat_name, $this->thetextdomain).'</a></li>';
				}
			}
			$thelist .= '</ul>';
		} else {
			$i = 0;
			foreach ( $categories as $category ) {
				if ( 0 < $i )
					$thelist .= $separator;
				switch ( strtolower( $parents ) ) {
					case 'multiple':
						if ( $category->parent )
							$thelist .= get_category_parents( $category->parent, true, $separator );
						$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( $view_all_posts, __($category->name, $this->thetextdomain) ) ) . '" ' . $rel . '>' . __($category->name, $this->thetextdomain).'</a>';
						break;
					case 'single':
						$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( $view_all_posts, __($category->name, $this->thetextdomain) ) ) . '" ' . $rel . '>';
						if ( $category->parent )
							$thelist .= get_category_parents( $category->parent, false, $separator );
						$thelist .= __($category->name, $this->thetextdomain)."</a>";
						break;
					case '':
					default:
						$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( $view_all_posts, __($category->name, $this->thetextdomain) ) ) . '" ' . $rel . '>' . __($category->name, $this->thetextdomain).'</a>';
				}
				++$i;
			}
		}
		return $thelist;
	}

	/**
	 * test if insertion point for languages list inside a location of navigation menu
	 * used by admin and customize theme
	 *
	 * return 0 if no insertion
	 *
	 * @since 2.8.8
	 * @updated 2.9.11
	 */
	function has_languages_list_menu ( $menu_location ) { // soon obsolete - conserved for old class
		return $this->has_insertion_point_list_menu ( $menu_location, $this->insertion_point_dummy_link );
	}

	function has_insertion_point_list_menu ( $menu_location, $insertion_point_dummy_link ) {
		$locations_all = get_nav_menu_locations();
		$point = 0;
		 if ( has_nav_menu ( $menu_location ) && isset( $locations_all[ $menu_location ] ) ) {
				$menu = wp_get_nav_menu_object( $locations_all[ $menu_location ] );
				$menu_items = wp_get_nav_menu_items($menu->term_id);

				foreach ( $menu_items as $one_item) {
					if ( $one_item->url == $insertion_point_dummy_link ) $point++ ;
				}
		 }
		 return $point;
	}






} /* **************** end of xili-language class ******************* */

/**
 * called when wp_locale is declared when plugin_loaded
 *
 * @since 2.4
 *
 */

function xiliml_declare_xl_wp_locale () {
	/**
	 * special class extending wp_locale only for theme locale
	 *
	 * to work needs that locale datas and translation (a copy of those in core languages) will be in theme's po,mo files
	 *
	 * @since 2.4.0
	 */
	if ( ! class_exists ( 'xl_WP_Locale'  ) ) {
	class xl_WP_Locale extends WP_locale {

			function __construct() {
				parent::__construct();
			}

			function init() {

			$theme_domain = the_theme_domain();

			// The Weekdays
			$this->weekday[0] = /* translators: weekday */ __('Sunday', $theme_domain);
			$this->weekday[1] = /* translators: weekday */ __('Monday', $theme_domain);
			$this->weekday[2] = /* translators: weekday */ __('Tuesday', $theme_domain);
			$this->weekday[3] = /* translators: weekday */ __('Wednesday', $theme_domain);
			$this->weekday[4] = /* translators: weekday */ __('Thursday', $theme_domain);
			$this->weekday[5] = /* translators: weekday */ __('Friday', $theme_domain);
			$this->weekday[6] = /* translators: weekday */ __('Saturday', $theme_domain);

			// The first letter of each day.  The _%day%_initial suffix is a hack to make
			// sure the day initials are unique.
			$this->weekday_initial[__('Sunday', $theme_domain)]    = /* translators: one-letter abbreviation of the weekday */ __('S_Sunday_initial', $theme_domain);
			$this->weekday_initial[__('Monday', $theme_domain)]    = /* translators: one-letter abbreviation of the weekday */ __('M_Monday_initial', $theme_domain);
			$this->weekday_initial[__('Tuesday', $theme_domain)]   = /* translators: one-letter abbreviation of the weekday */ __('T_Tuesday_initial', $theme_domain);
			$this->weekday_initial[__('Wednesday', $theme_domain)] = /* translators: one-letter abbreviation of the weekday */ __('W_Wednesday_initial', $theme_domain);
			$this->weekday_initial[__('Thursday', $theme_domain)]  = /* translators: one-letter abbreviation of the weekday */ __('T_Thursday_initial', $theme_domain);
			$this->weekday_initial[__('Friday', $theme_domain)]    = /* translators: one-letter abbreviation of the weekday */ __('F_Friday_initial', $theme_domain);
			$this->weekday_initial[__('Saturday', $theme_domain)]  = /* translators: one-letter abbreviation of the weekday */ __('S_Saturday_initial', $theme_domain);

			foreach ($this->weekday_initial as $weekday_ => $weekday_initial_) {
				$this->weekday_initial[$weekday_] = preg_replace('/_.+_initial$/', '', $weekday_initial_);
			}

			// Abbreviations for each day.
			$this->weekday_abbrev[__('Sunday', $theme_domain)]    = /* translators: three-letter abbreviation of the weekday */ __('Sun', $theme_domain);
			$this->weekday_abbrev[__('Monday', $theme_domain)]    = /* translators: three-letter abbreviation of the weekday */ __('Mon', $theme_domain);
			$this->weekday_abbrev[__('Tuesday', $theme_domain)]   = /* translators: three-letter abbreviation of the weekday */ __('Tue', $theme_domain);
			$this->weekday_abbrev[__('Wednesday', $theme_domain)] = /* translators: three-letter abbreviation of the weekday */ __('Wed', $theme_domain);
			$this->weekday_abbrev[__('Thursday', $theme_domain)]  = /* translators: three-letter abbreviation of the weekday */ __('Thu', $theme_domain);
			$this->weekday_abbrev[__('Friday', $theme_domain)]    = /* translators: three-letter abbreviation of the weekday */ __('Fri', $theme_domain);
			$this->weekday_abbrev[__('Saturday', $theme_domain)]  = /* translators: three-letter abbreviation of the weekday */ __('Sat', $theme_domain);

			// The Months
			$this->month['01'] = /* translators: month name */ __('January', $theme_domain);
			$this->month['02'] = /* translators: month name */ __('February', $theme_domain);
			$this->month['03'] = /* translators: month name */ __('March', $theme_domain);
			$this->month['04'] = /* translators: month name */ __('April', $theme_domain);
			$this->month['05'] = /* translators: month name */ __('May', $theme_domain);
			$this->month['06'] = /* translators: month name */ __('June', $theme_domain);
			$this->month['07'] = /* translators: month name */ __('July', $theme_domain);
			$this->month['08'] = /* translators: month name */ __('August', $theme_domain);
			$this->month['09'] = /* translators: month name */ __('September', $theme_domain);
			$this->month['10'] = /* translators: month name */ __('October', $theme_domain);
			$this->month['11'] = /* translators: month name */ __('November', $theme_domain);
			$this->month['12'] = /* translators: month name */ __('December', $theme_domain );

			// Abbreviations for each month. Uses the same hack as above to get around the
			// 'May' duplication.
			$this->month_abbrev[__('January', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Jan_January_abbreviation', $theme_domain);
			$this->month_abbrev[__('February', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Feb_February_abbreviation', $theme_domain);
			$this->month_abbrev[__('March', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Mar_March_abbreviation', $theme_domain);
			$this->month_abbrev[__('April', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Apr_April_abbreviation', $theme_domain);
			$this->month_abbrev[__('May', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('May_May_abbreviation', $theme_domain);
			$this->month_abbrev[__('June', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Jun_June_abbreviation', $theme_domain);
			$this->month_abbrev[__('July', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Jul_July_abbreviation', $theme_domain);
			$this->month_abbrev[__('August', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Aug_August_abbreviation', $theme_domain);
			$this->month_abbrev[__('September', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Sep_September_abbreviation', $theme_domain);
			$this->month_abbrev[__('October', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Oct_October_abbreviation', $theme_domain);
			$this->month_abbrev[__('November', $theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Nov_November_abbreviation', $theme_domain);
			$this->month_abbrev[__('December',$theme_domain)] = /* translators: three-letter abbreviation of the month */ __('Dec_December_abbreviation', $theme_domain);

			foreach ($this->month_abbrev as $month_ => $month_abbrev_) {
				$this->month_abbrev[$month_] = preg_replace('/_.+_abbreviation$/', '', $month_abbrev_);
			}

			// The Meridiems
			$this->meridiem['am'] = __('am', $theme_domain);
			$this->meridiem['pm'] = __('pm', $theme_domain);
			$this->meridiem['AM'] = __('AM', $theme_domain);
			$this->meridiem['PM'] = __('PM', $theme_domain);

			// Numbers formatting
			// See http://php.net/number_format

			/* translators: $thousands_sep argument for http://php.net/number_format, default is , */
			$trans = __('number_format_thousands_sep', $theme_domain);
			$this->number_format['thousands_sep'] = ('number_format_thousands_sep' == $trans) ? ',' : $trans;

			/* translators: $dec_point argument for http://php.net/number_format, default is . */
			$trans = __('number_format_decimal_point', $theme_domain);
			$this->number_format['decimal_point'] = ('number_format_decimal_point' == $trans) ? '.' : $trans;

			// test version // 2.7.1
			global $wp_version;
			if ( version_compare($wp_version, '3.4', '<') ) {
				// Import global locale vars set during inclusion of $locale.php.
				foreach ( (array) $this->locale_vars as $var ) {
					if ( isset($GLOBALS[$var]) )
						$this->$var = $GLOBALS[$var];
				}
			} else {
			// Set text direction.
				if ( isset( $GLOBALS['text_direction'] ) )
					$this->text_direction = $GLOBALS['text_direction'];
				/* translators: 'rtl' or 'ltr'. This sets the text direction for WordPress. */
				elseif ( 'rtl' == _x( 'ltr', 'text direction', $theme_domain ) )
					$this->text_direction = 'rtl';

			}
		}
	} }
}

/**** Functions that improve taxinomy.php ****/

/**
 * get terms and add order in term's series that are in a taxonomy
 * (not in class for general use)
 *
 * @since 0.9.8.2 - full version is in xili-tidy-tags
 * @uses $wpdb
 */
function get_terms_of_groups_lite ($group_ids, $taxonomy, $taxonomy_child, $order = '') {
	global $wpdb;
	if ( !is_array($group_ids) )
		$group_ids = array($group_ids);
	$group_ids = array_map('intval', $group_ids);
	$group_ids = implode(', ', $group_ids);
	$theorderby = '';

	// lite release
	if ($order == 'ASC' || $order == 'DESC') $theorderby = ' ORDER BY tr.term_order '.$order ;

	$query = "SELECT t.*, tt2.term_taxonomy_id, tt2.description,tt2.parent, tt2.count, tt2.taxonomy, tr.term_order FROM $wpdb->term_relationships AS tr INNER JOIN $wpdb->term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->terms AS t ON t.term_id = tr.object_id INNER JOIN $wpdb->term_taxonomy AS tt2 ON tt2.term_id = tr.object_id WHERE tt.taxonomy IN ('".$taxonomy."') AND tt2.taxonomy = '".$taxonomy_child."' AND tt.term_id IN (".$group_ids.") ".$theorderby;

	$listterms = $wpdb->get_results($query);
	if ( ! $listterms )
		return array();

	return $listterms;
}

/**
 * for backward compatibility - soon obsolete - please modify your theme's function.php
 */
function get_terms_with_order ( $group_ids, $taxonomy, $taxonomy_child, $order = 'ASC' ) {
	return get_terms_of_groups_lite ($group_ids, $taxonomy, $taxonomy_child, $order);
}

/**
 * function that improve taxinomy.php
 * @since 0.9.8
 *
 * update term order in relationships (for terms of langs group defined by his taxonomy_id)
 *
 * @param $object_id, $taxonomy_id, $term_order
 *
 */
function update_term_order ( $object_id,$term_taxonomy_id,$term_order ) {
	global $wpdb;
	$wpdb->update( $wpdb->term_relationships, compact( 'term_order' ), array( 'term_taxonomy_id' => $term_taxonomy_id,'object_id' => $object_id ) );
}

/**
 * function that improve taxinomy.php
 * @since 0.9.8
 *
 * get one term and order of it in relationships
 *
 * @param term_id and $group_ttid (taxonomy id of group)
 * @return object with term_order
 */
function get_term_and_order ( $term_id, $group_ttid, $taxonomy ) {
	global $wpdb;
	$term = get_term($term_id, $taxonomy, OBJECT, 'edit');
	$term->term_order = $wpdb->get_var( "SELECT term_order FROM $wpdb->term_relationships WHERE object_id =  $term_id AND term_taxonomy_id = $group_ttid " );
	return $term;
}

/* *** Functions using the xili-language class *** */

/**
 * function to progressively replace the previous constant THEME_TEXTDOMAIN only usable in mono site
 *
 * @since 1.5.2
 */
function the_theme_domain() {
	global $xili_language;
	return $xili_language->thetextdomain;
}


/**
 * Return the current language of theme.
 *
 * @since 0.9.7
 * @updated 2.11.0
 * use by other function elsewhere
 * @param slug, iso, full name, alias, charset, hidden, count
 *
 * @return by default the slug of language (used in query).
 */
function the_curlang( $by = 'slug' ) { // soon obsolete
	return xili_curlang( $by );
}
function xili_curlang( $by = 'slug' ) {
	global $xili_language;
	if ( empty($by) ) $by = 'slug';
	$by = strtolower ($by);
	$slug = $xili_language->curlang;
	if ( $by == 'iso') $by = 'name';
	if ( $by == 'full name') $by = 'description';
	if ( $slug && in_array ( $by, array('name', 'description', 'count' ) ) ){
		$language = get_term_by( 'slug', $slug, TAXONAME, ARRAY_A );
		return $language[$by];
	}
	if ( $slug && in_array ( $by, array( 'alias', 'charset', 'hidden' ) ) ){
		$val = ( isset($xili_language->xili_settings['lang_features'][$slug][$by]) ) ? $xili_language->xili_settings['lang_features'][$slug][$by] : '';
		return $val;
	}
	if ( $slug && $by == 'direction' ) return $xili_language->curlang_dir;

	return $slug ;
}

/**
 * Test the current language of displayed page.
 *
 * @since 2.11
 * use for other function elsewhere
 * @param "" for  undefined, slug of tested language alone or an array list
 * @return true or false
 */
function is_xili_curlang( $lang = false ) {
	if ($lang === false ) return false;
	$lang_array = array();
	if ( !is_array ( $lang ) ) {
		$lang_array[] = $lang; // when not called by xili-postinpost
	} else {
		$lang_array = $lang;
	}
	global $xili_language;
	if ( in_array ( $xili_language->curlang, $lang_array ) )
		return true;
	else
		return false;
}

/**
 * Return the current language and dir of theme.
 *
 * @since 0.9.9
 * use for other function elsewhere
 *
 * @return array with slug of language (used in query) and dir (ltr or rtl).
 */
function the_cur_lang_dir() {
	global $xili_language;
	return array( QUETAG => $xili_language->curlang, 'direction'=>$xili_language->curlang_dir );
}

/**
 * Return the current group of languages
 *
 * @since 0.9.8.3
 */
function the_cur_langs_group_id() {
	global $xili_language;
	return $xili_language->langs_group_id;
}

/**
 * Return the feature of language as array or string if param is specified (language slug)
 * Used by xili_language_list hook to hidden checked language
 *
 * @since 1.8.9.1
 * @updated 2.11.0
 */
function xl_lang_features ( $slug, $param = '' ) {
	global $xili_language;
	if ('' == $param ) {
		$val = ( isset($xili_language->xili_settings['lang_features'][$slug]) ) ? $xili_language->xili_settings['lang_features'][$slug] : array();
	} else {
		$val = ( isset($xili_language->xili_settings['lang_features'][$slug][$param]) ) ? $xili_language->xili_settings['lang_features'][$slug][$param] : '';
	}
	return $val;
}

/**
 * Return the current date or a date formatted with strftime.
 *
 * @since 0.9.7.1
 * @updated 1.6.0 - timezone offset - http://core.trac.wordpress.org/ticket/11672
 * can be used in theme for multilingual date
 * @param format and time (if no time = current date-time)
 * @return the formatted date.
 */
function the_xili_local_time( $format='%B %d, %Y',$time = null ) {
	global $xili_language;
	if ($time == null ) {
		$time = current_time('timestamp'); //to get the Unix timestamp with a timezone offset -
	}
	$curslug = $xili_language->curlang;
	$curlang = ( strlen($curslug) == 5 ) ? substr($curslug,0,3).strtoupper(substr($curslug,-2)) : $curslug ;
	setlocale(LC_TIME, $curlang); /* work if server is ready */
	$charset = ( $xili_language->xili_settings['lang_features'][$curslug]['charset'] != '' ) ? $xili_language->xili_settings['lang_features'][$curslug]['charset'] : "" ; // 1.8.9.1
	if ( "" != $charset  ) {
	  return htmlentities( strftime(__( $format, the_theme_domain() ),$time), ENT_COMPAT, $charset ); /* ,'UTF-8' entities for some server */
	} else {
	 	return htmlentities( strftime(__( $format, the_theme_domain() ),$time), ENT_COMPAT );
	}
}

/**
 * Return the current date or a date formatted with strftime according get_option php date format.
 *
 * @since 1.6.0, 1.8.9.1, 2.2.2
 *
 * can be used in theme for multilingual date
 * @param format and time (if no time = current date-time)
 * @return the formatted date.
 */
function the_xili_wp_local_time( $wp_format='F j, Y', $time = null ) {
	global $xili_language;
	if ($time == null ) {
		$time = current_time('timestamp'); //to get the Unix timestamp with a timezone offset -
	}
	$curslug = $xili_language->curlang;
	if ( $xili_language->xili_settings['lang_features'][$curslug]['charset'] == 'no_locale' ) { // need to be inside charset input
		$date_formatted = date ( __($wp_format, the_theme_domain(), $time ) );
		if ( function_exists ( 'xili_translate_date' ) )
			return xili_translate_date ( $curslug, $date_formatted );
		else
			return $date_formatted ;
	} else {
		$curlang = ( strlen($curslug) == 5 ) ? substr($curslug,0,3).strtoupper(substr($curslug,-2)) : $curslug ;
		setlocale(LC_TIME, $curlang); /* work if server is ready */
		$format = xiliml_php2loc_time_format_translator (__($wp_format, the_theme_domain())); /* translated by theme mo*/

		$charset = ( $xili_language->xili_settings['lang_features'][$curslug]['charset'] != '' ) ? $xili_language->xili_settings['lang_features'][$curslug]['charset'] : "" ; // 1.8.9.1
		if ( "" != $charset  ) {
			return htmlentities(strftime($format, $time),ENT_COMPAT, $charset  ); /* ,'UTF-8' entities for some server - ja char */
		} else {
			return htmlentities(strftime($format, $time),ENT_COMPAT  );
		}
	}
}

/**
 * Return translated format from php time to loc time used in strftime.
 *
 * @since 1.6.0
 * @updated 1.8.1 - add T -> %z, e -> %Z - 1.8.7 T -> %Z (stephen)
 * @ 1.8.9.1 - add n -> %m (japanese)
 * (was formerly in xilidev-libraries)
 * can be used in theme for multilingual date
 * @param phpformat
 * @return locale format.
 */
function xiliml_php2loc_time_format_translator ( $phpformat = 'm/d/Y H:i' ) {
	/* order left to right to avoid over replacing DON'T MODIFY */
	$phpformchar = array('A' ,'a' ,'D' ,'l' ,'g' ,'d' ,'e' ,'j' ,'z' ,'T' ,'N' ,'w ','W' ,'M' ,'F' ,'h' ,'M ','m' ,'y' ,'Y' ,'H' ,'G' ,'i' ,'S' ,'s' ,'O','n');
	/* doc here: http://fr2.php.net/manual/en/function.date.php */
	$locformchar = array('%p','%P','%a','%A','%l','%d','%Z','%e','%j','%Z','%U','%w','%W','%b','%B','%I', '%h', '%m','%y','%Y','%H','%l','%M', '','%S','%z','%m');
	/* doc here: http://fr.php.net/manual/en/function.strftime.php */

   if ('' == $phpformat) $phpformat = 'm/d/Y H:i';
   // use to detect escape char that illustrate date or hour... \h or \m
   	$ars = explode('\\', $phpformat ); $i=0;
	if ($ars[0] == $phpformat) {
		$locform = str_replace($phpformchar, $locformchar,$phpformat);
	} else {
		$locform = "";
		foreach ($ars as $a) {
			if (""!= $a) {
			$locform = $locform.((0 == $i) ? str_replace($phpformchar, $locformchar,$a) : substr($a,0,1).str_replace($phpformchar, $locformchar, substr($a,1)) );
			}
			$i++;
		}

	}
   	return $locform ;
}

/**
 * Return the language of current post in loop.
 *
 * @since 0.9.7.0
 * @updated 0.9.9, 2.6
 * useful for functions in functions.php or other plugins
 *
 * @param ID of the post, $type
 * @return the slug (en_us) or iso (en_US) or name ( english )...
 */
function get_cur_language( $post_ID, $type = 'slug' ) {
	global $xili_language;
	return $xili_language->get_post_language( $post_ID, $type );
}

/**
 * Return the lang and dir of language of current post in loop.
 *
 * @since 0.9.9
 * useful for functions in functions.php or other plugins
 *
 * @param ID of the post
 * @return array two params : lang (as slug) and direction of lang (ltr or rtl).
 */
function get_cur_post_lang_dir( $post_ID ) {
	global $xili_language;
	return $xili_language->get_cur_language($post_ID);
}

/**
 * Return languages objects in taxinomy. Useful for hooks as in functions.php of theme
 *
 * @since 1.6.0
 * @param $force to avoid buffer
 */
 function xili_get_listlanguages( $force = false ) {
 	global $xili_language;
 	return $xili_language->get_listlanguages( $force );
 }

/**
 * Return language object of a post.
 *
 * @since 1.1.8
 * useful for functions in functions.php or other plugins
 *
 * @param ID of the post
 * @return false or object with params as in current term (->description = full name of lang, ->count = number of posts in this language,...
 */
function xiliml_get_lang_object_of_post( $post_ID ) {

	$ress = wp_get_object_terms( $post_ID, TAXONAME ); /* lang of target post */
	if ( $ress == array() ) {
		return false;
	} else {
		return $ress[0];
	}
}

/**
 * Return the language of current browser.
 *
 * @since 0.9.7.6
 * @updated 0.9.9
 * useful for functions in functions.php or other plugins
 *
 * @param no
 * @return the best choice.
 */
function choice_of_browsing_language() {
	global $xili_language;
	return $xili_language->choice_of_browsing_language();
}

/**
 * Return the lang and dir of current browser.
 *
 * @since 0.9.9
 * useful for functions in functions.php or other plugins
 *
 * @param no
 * @return array of the best choice lang and his dir.
 */
function choice_of_browsing_lang_dir() {
	global $xili_language;
	$lang = $xili_language->choice_of_browsing_language();
	$dir = $xili_language->get_dir_of_cur_language($lang);
	return array( QUETAG => $lang,' direction' => $dir );
}

/**
 * Activate hooks of plugin in class.
 *
 * @since 0.9.7.4
 * can be used in functions.php for special action
 *
 * @param filter name and function
 *
 */
function add_again_filter( $filtername, $filterfunction ) {
	global $xili_language;
	add_filter( $filtername, array( $xili_language, $filterfunction ) );
}

/**
 * Replace get_category_link to bypass hook from xili_language
 *
 * @since 0.9.7.4
 * @updated 1.0.1
 * can be used in functions.php for special action needing permalink
 * @param category ID
 * @return the permalink of passed cat_id.
 */
function xiliml_get_category_link( $catid = 0 ) {
			global $xili_language;
			if ($catid == 0) {
				global $wp_query;
				$catid = $wp_query->query_vars['cat'];
			}
			remove_filter('category_link', array ( $xili_language, 'xiliml_link_append_lang') );
				$catcur = get_category_link($catid);
			add_filter('category_link', array ( $xili_language, 'xiliml_link_append_lang' ) );
	return $catcur;
}

/** used by xili widget - usable if you need to create your own template tag
 *
 * @since 0.9.9.4
 * @param quantity of comments
 *
 * @return comments objects...
 */
function xiliml_recent_comments( $number = 5 ) {
	global $xili_language;
	return $xili_language->xiliml_recent_comments( $number );
}

/**
 * Return full object of a language
 * @since 1.1.8
 * @param name (fr_FR) or slug (fr_fr)
 * @return false or full language object (example ->description = full as set in admin UI)
 */
function xiliml_get_language( $lang_nameorslug="" ) {
	$language = term_exists( $lang_nameorslug, TAXONAME );
	if ($language) {
		return get_term( $language['term_id'], TAXONAME, OBJECT, 'edit' );
	} else {
		return false;
	}
}

 	/*
	 **
	 * Template Tags for themes (with current do_action tool some are hookable functions)
	 **
	 */

/**
 * Template Tag insertable in search form for sub-selection of a language
 *
 * @since 0.9.7
 * @updated 1.8.2
 * can be used in theme template
 * example: if(class_exists('xili_language')) xiliml_langinsearchform()
 *
 * hook: add_action('xiliml_langinsearchform','your_xiliml_langinsearchform',10,2) to change its behaviour elsewhere
 * @param html tags
 * @return echo the list as radio-button
 */
function xiliml_langinsearchform ( $before='', $after='', $echo = true ) { /* list of radio buttons for search form*/
	global $xili_language;
	if ( $xili_language->this_has_external_filter('xiliml_langinsearchform') ){
		remove_filter('xiliml_langinsearchform', array( $xili_language, 'xiliml_langinsearchform' ) ); /*no default from class*/
	}
	if ( $echo ) {
		echo apply_filters( 'xiliml_langinsearchform', $before, $after, $echo);
	} else {
		return apply_filters( 'xiliml_langinsearchform', $before, $after, $echo);
	}
}

/**
 * Template Tag - replace the_category() tag of WP Core
 *
 * @since 0.9.0
 * @updated 1.4.2 - default value to post_ID
 * can be used in theme template in each post in loop
 * example: if(class_exists('xili_language')) xiliml_the_category($post->ID)
 *
 * hook: add_action('xiliml_the_category','your_xiliml_the_category',10,3) to change its behaviour elsewhere
 * @param post_id separator echo (true by default)
 * @return echo (by default) the list of cats with comma separated...
 */
function xiliml_the_category( $post_ID = 0, $separator = ', ' , $echo = true ) { /* replace the_category() */
	global $xili_language, $post;
	if ($post_ID == 0) $post_ID = $post->ID;
	if ( $xili_language->this_has_external_filter('xiliml_the_category') ){
		remove_filter('xiliml_the_category', array( $xili_language, 'xiliml_the_category') ); /*no default from class*/
	}
	do_action( 'xiliml_the_category', $post_ID, $separator, $echo );
}

/**
 * Template Tag - in loop display the link of other posts defined as in other languages
 *
 * @since 0.9.0
 * @updated 0.9.9.2, 1.1 (can return an array of lang + id)
 * @updated 1.4.2 - default value to post_ID
 * can be used in theme template in single.php under the title
 * example: if(class_exists('xili_language')) xiliml_the_other_posts($post->ID)
 *
 * hook: add_action('xiliml_the_other_posts','your_xiliml_the_other_posts',10,3) to change its behaviour elsewhere
 * @param post_id, before, separator, type (echo, array).
 * @return echo (by default) the list
 */
function xiliml_the_other_posts ( $post_ID = 0, $before = "This post in", $separator = ", ", $type = "display" ) { /* display the other posts defined as in other lang */
	global $xili_language, $post;
	if ($post_ID == 0) $post_ID = $post->ID;
	if ( $xili_language->this_has_external_filter('xiliml_the_other_posts') ){
		remove_filter('xiliml_the_other_posts', array( $xili_language, 'xiliml_the_other_posts' ) ); /*no default from class*/
	}
	return apply_filters( 'xiliml_the_other_posts', $post_ID, $before, $separator, $type );
}

/**
 * Template Tag - in loop display the language of the post
 *
 * @since 0.9.0
 * can be used in theme template in loop under the title
 * example: if(class_exists('xili_language')) xili_post_language()
 *
 * hook: add_action('xili_post_language','your_xili_post_language',10,2) to change its behaviour elsewhere
 * @param before after
 * @return echo (by default) the language
 */
function xili_post_language( $before = '<span class="xili-lang">(', $after =')</span>' ) { /* post language in loop*/
	do_action( 'xili_post_language',$before, $after );
}

/**
 * Template Tag - outside loop (sidebar) display the languages of the site (used also by widget)
 *
 * @since 0.9.0
 * @updated 0.9.7.4
 * @udpated 1.8.9.1
 * can be used in theme template in sidebar menu or header menu
 * example: if(class_exists('xili_language')) xili_language_list()
 * theoption param is used to define type of display according places (sidebar / header) in theme (see dev.xiligroup.com)
 *
 * hook: add_action('xili_language_list','your_xili_language_list',10,5) to change its behaviour elsewhere
 * @param before after theoption
 * @return echo the list of languages
 * @hidden don't list hidden languages
 */
function xili_language_list( $before = '<li>', $after ='</li>', $theoption='', $echo = true, $hidden = false ) {

	global $xili_language;
	if ( $xili_language->this_has_external_filter('xili_language_list') ){
		remove_filter('xili_language_list', array( $xili_language, 'xili_language_list' ) ); /*no default from class*/
	}
	return apply_filters('xili_language_list', $before, $after, $theoption, $echo, $hidden );
}

/**
 * function to get id, link or permalink of linked post in target lang
 * to replace get_post_meta($post->ID, 'lang-'.$language->slug, true) soon obsolete
 * @since 1.8.9.1
 *
 * @updated 2.1.0 - permalink as
 */
function xl_get_linked_post_in ( $fromID, $lang, $info = 'id' ) {
	global $xili_language;

	$language = xiliml_get_language( $lang ); /* test if lang is available */

	if ( $language !== false ) {
		$otherpost = get_post_meta( $fromID, QUETAG.'-'.$language->slug, true ); // will be soon changed
		if ( $info == 'permalinknav') return  $xili_language ->link_of_linked_post ( $fromID, $language->slug );

		if ( $otherpost ) {
			switch ( $info ) {
				case 'id';
					$output = $otherpost;
					break;
				case 'link';
					$post = get_post($otherpost);
			        if ( isset($post->post_type) ){
			         	if ( 'post' == $post->post_type ) {
							$output = home_url('?p=' . $otherpost);
			         	} elseif ( 'post' == $post->post_type ) {
			         		$output = home_url('?page_id=' . $otherpost);
			         	}
			        }
					break;
				case 'permalink';
					$output = get_permalink( $otherpost );
					break;
			}
		} else {
			switch ( $info ) {
				case 'id';
					$output = 0; // false
					break;
				case 'link';
					$output = '#';
					break;
				case 'permalink';
					$output = '#';
					break;
			}

		}
		return $output;
	}
}

/**
 * insert a dummy items to precede lang list insertion since WP 3.5
 * @since 2.8.3
 */
function xili_nav_menu_args ( $args ) {
	global $xili_language;
	if ( isset ( $xili_language->xili_settings['navmenu_check_options'] ) ) {
		$navmenu_check_options = $xili_language->xili_settings['navmenu_check_options'];

		$args = (object) $args;

		$menu = wp_get_nav_menu_object( $args->menu );

		if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) )
			$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );

			if ( isset ( $navmenu_check_options[$args->theme_location] ) && $navmenu_check_options[$args->theme_location]['navenable'] == 'enable' ) {

				if ( $menu && ! is_wp_error($menu) ) {

					$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

					if ( empty( $menu_items ) ) {

						$menu_id = wp_update_nav_menu_item ( $menu->term_id, 0, array( 'menu-item-title' => 'xili-language un-visible dummy-menu-item', 'menu-item-url' => "#")  );
						$menu_id = wp_update_nav_menu_item ( $menu->term_id, $menu_id, array( 'menu-item-title' => 'xili-language un-visible dummy-menu-item', 'menu-item-url' => "#dummy-link", 'menu-item-attr-title' => __('delete if you add manually another menu item', 'xili-language' ) )  ); // wp-includes/nav-menu.php

					}
				}
			}

			// search another virtual locations created by child themes (2010, 2011, 2012 and others (responsive,…))
			$language_slugs_list = array_keys ( $xili_language->xili_settings['langs_ids_array'] ) ;
			foreach ( $language_slugs_list as $slug ) {
				$default = 'en_us';
				if ( $slug != $default) {

					if ( isset ( $locations[ $args->theme_location.'_'.$slug ] ) && isset ( $navmenu_check_options[ $args->theme_location.'_'.$slug ] ) && $navmenu_check_options[ $args->theme_location.'_'.$slug ]['navenable'] == 'enable' ) {
						$menu = wp_get_nav_menu_object( $locations[ $args->theme_location.'_'.$slug ] );

						if ( $menu && ! is_wp_error($menu) ) {

							$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

							if ( empty( $menu_items ) ) {

								$menu_id = wp_update_nav_menu_item ( $menu->term_id, 0, array( 'menu-item-title' => 'xili-language un-visible dummy-menu-item', 'menu-item-url' => "#")  );
								$menu_id = wp_update_nav_menu_item ( $menu->term_id, $menu_id, array( 'menu-item-title' => 'xili-language un-visible dummy-menu-item', 'menu-item-url' => "#dummy-link", 'menu-item-attr-title' => __('delete if you add manually another menu item', 'xili-language' ) )  );


							}
						}
					}
				}
			}

		$args = (array) $args;
	}
	return $args ;
}


/**
 * Insert automatically some languages items at end in menu
 * @since 1.6.0
 * @updated 1.7.1 - add optionally wp_page_list result
 * @updated 1.8.1 - choose good menu location
 * @updated 1.8.9 - add filter (example: add_filter ('xili_nav_lang_list', 'my_xili_nav_lang_list', 10, 3);)
 *					 and class for separator  ( example: li.menu-separator a {display:none;})
 *
 * @updated 2.1.0 - for multiple navmenu locations - CAUTION new filter: xili_nav_lang_lists (with s at end)
 * @updated 2.8.3 - for empty items (hack args) wp 3.5
 * @updated 2.8.7 - customize preview
 */
function xili_nav_lang_list( $items, $args ) {
	global $xili_language;

	$preview_options =  $xili_language->get_xili_language_options(); // to obtain previewable params

	$li_separator = ( '' != $preview_options['nav_menu_separator'] ) ? '<li class="menu-item menu-separator" ><a>'.$preview_options['nav_menu_separator'].'</a></li>' : '';  // 2.8.3

	if ( 0 != strpos( $items, '>xili-language un-visible dummy-menu-item<' )) {

		$items = preg_replace ( '/<li(.*)href="#dummy-link(.*)<\/li>/i', '',$items);
		$li_separator = '';
	}

	if ( $preview_options['in_nav_menu'] == 'enable' ) {

		if ( isset ( $preview_options['navmenu_check_options'] ) ) {
			$navmenu_check_options = $preview_options['navmenu_check_options'];

			if ( has_filter( 'xili_nav_lang_lists' ) ) return apply_filters ( 'xili_nav_lang_lists',  $items, $args, $navmenu_check_options );

			if ( isset ( $navmenu_check_options[$args->theme_location] ) && $navmenu_check_options[$args->theme_location]['navenable'] == 'enable' ) {

				$navmenu = ( '' != $navmenu_check_options[$args->theme_location]['navtype'] ) ? $navmenu_check_options[$args->theme_location]['navtype'] : "navmenu-1";

				$end = xili_language_list( '<li>', '</li>', $navmenu, false, true ) ; // don't display hidden languages

		   		return $items. $li_separator .$end; // class for display none...

			} else {
				return $items;
			}

		} else { // if settings not updated since updated by admin user
			$navmenu_check_option = $xili_language->xili_settings['navmenu_check_option'];
			if ( has_filter( 'xili_nav_lang_list' ) ) return apply_filters ( 'xili_nav_lang_list',  $items, $args, $navmenu_check_option );
			if ( $args->theme_location  ==  $navmenu_check_option ) {

				$end = xili_language_list( '<li>', '</li>', 'navmenu', false, true ) ; // don't display hidden languages 1.8.9.1

	   			return $items. $li_separator .$end; // class for display none... 1.8.9 no ID for instantiations

			} else {
				return $items;
			}
		}

	}
}

/**
 * Insert automatically some pages items at end in menu
 * @since 1.7.1 - add optionally wp_page_list result
 * @updated 1.8.1 - choose good menu location
 * @updated 1.8.9 - add filter (example: add_filter ('xili_nav_page_list', 'my_xili_nav_page_list', 10, 3);)
 * @updated 2.8.3 - for empty items (hack args) wp 3.5
 * @updated 2.8.4.3 - multi location - new filter - xili_nav_page_list_array - two params
 *
 */
function xili_nav_page_list( $items, $args ) {
	global $xili_language;

	if ( isset ($xili_language->xili_settings['array_navmenu_check_option_page'] ) && $xili_language->xili_settings['array_navmenu_check_option_page'] != array() ) {
		$array_navmenu_check_option_page = $xili_language->xili_settings['array_navmenu_check_option_page'];

		if ( has_filter( 'xili_nav_page_list_array' ) ) return apply_filters ( 'xili_nav_page_list_array',  $items, $args, $array_navmenu_check_option_page );

		$location_keys = array_keys( $array_navmenu_check_option_page  );

		if ( in_array( $args->theme_location, $location_keys ) && $array_navmenu_check_option_page[$args->theme_location]['enable'] == 'enable' ) {
			if ( 0 != strpos( $items, '>xili-language un-visible dummy-menu-item<' )) {
				if ( '' == $xili_language->xili_settings['in_nav_menu'] ) { // no language list in the menus - need to be erased here
					$items = preg_replace ( '/<li(.*)href="#dummy-link(.*)<\/li>/i', '',$items);
				}
			}

			$pagelist = '';
			$pagelist_args = $array_navmenu_check_option_page[$args->theme_location]['args'].'&';
		// sub-selection
			add_filter ( 'page_link', 'xili_nav_page_link_insertion_fixe' ,10, 3  ); // 2.8.5
			$pagelist = wp_list_pages( $pagelist_args . 'title_li=&echo=0&' . QUETAG . '=' . $xili_language->curlang );
			remove_filter ( 'page_link', 'xili_nav_page_link_insertion_fixe');


   			return $items.$pagelist;
		} else {
			return $items;
		}

	} else {

		return $items;
	}
}

// apply_filters( 'page_link', $link, $post->ID, $sample );

/**
 * fixes filter for front-page array
 * @since 2.8.5
 *
 */
function xili_nav_page_link_insertion_fixe ( $link, $post_id, $sample ) {
	global $xili_language;
	$front_page_id = $xili_language->get_option_wo_xili ('page_on_front');
	$list_pages_check_option = $xili_language->xili_settings['list_pages_check_option'];
	if ( $xili_language->show_page_on_front && $list_pages_check_option == 'fixe' && in_array( $post_id , $xili_language->show_page_on_front_array ) ) {
		$post_id = (int) $post_id;  // issue with 3.4.2
		$post = get_post( $post_id );
		$link = _get_page_link( $post, false, $sample );
	}
	return $link;
}

/**
 * modify automatically home page item in nav menu - exemple for twentyten child menu
 * @since 1.8.9.2
 *
 * filter ( example: add_filter ('xili_nav_page_home_item', 'my_xili_nav_page_home_item', 10, 5);)
 *
 */
function xili_nav_page_home_item( $item_output, $item, $depth, $args ) {
	global $xili_language;
	$homemenu_check_option = $xili_language->xili_settings['home_item_nav_menu'];
	if ( has_filter( 'xili_nav_page_home_item' ) ) return apply_filters ( 'xili_nav_page_home_item',  $item_output, $item, $depth, $args, $homemenu_check_option ); // fixed 2.8

	if ( $item->url == get_option('siteurl').'/'  ) { // page or list
		$curlang = $xili_language->curlang ;

		if ( $xili_language->lang_perma ) { // 2.9.22
			$currenturl = get_option('siteurl').'/%lang%/';
		} else {
	 		$currenturl = get_option('siteurl').'/?'.QUETAG."=";
		}
		$link = ( $xili_language->lang_perma ) ? str_replace ( '%lang%', $xili_language->lang_slug_qv_trans ( $curlang ), $currenturl ) : $currenturl.$curlang ;

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . __( esc_attr( $item->attr_title ), $xili_language->thetextdomain ) .'"' : ''; //
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $link ) .'"' : ''; // 2.9.22

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;
		return $item_output;
   	} else {
		return $item_output;
	}
}


/*
 * sub selection of pages for wp_list_pages()
 * @ since 090504 - exemple of new function add here or addable in functions.php
 * © xiligroup.dev
 *
 * only called if xili-language plugin is active and query tag 'lang' is in wp_list_pages template tag
 *
 * example 1 : wp_list_pages('title_li=&lang='.the_curlang() ); will display only pages of current lang
 *
 * example 2 : wp_list_pages('title_li=&setlang=0&lang='.the_curlang() ); will display pages of current lang AND pages with lang undefined (polyglot pages).
 * example 3 : wp_list_pages('title_li=&echo=0&include=2,10&lang='); will display pages of current lang (new since 2.2.2) useful with xili-widget plugin
 *
 */
function ex_pages_by_lang ( $pages, $r ) {
	if (isset($r[QUETAG]) && !empty($pages) && function_exists('get_cur_post_lang_dir')) {
		$keepundefined = null;
		if (isset($r['setlang'])) {
			if ($r['setlang'] == 0 || $r['setlang'] == 'false') $keepundefined = false;
			if ($r['setlang'] == 1 || $r['setlang'] == 'true') $keepundefined = true;
		}
		$resultingpages = array();
		if ( $r[QUETAG] == "" ) $r[QUETAG] = the_curlang(); // when param is here but empty = cur lang of page - 2.2.2
		foreach ($pages as $page) {
			$post_lang_dir = get_cur_post_lang_dir($page->ID);
			if ($post_lang_dir === $keepundefined) {
					$resultingpages[] = $page;
			} elseif ($post_lang_dir[QUETAG] == $r[QUETAG] ) {
					$resultingpages[] = $page;
			}
		}
		return $resultingpages;
	} else {
	 	return $pages;
	}
}
add_filter('get_pages','ex_pages_by_lang',10,2);

/**
 * functions to change and restore loop's query tag
 * (useful for sidebar widget - see functions table)
 * @since 1.3.0
 * @param  lang to modify query_tag -
 *
 */
function xiliml_force_loop_lang ( $lang_query_tag ){
	global $xili_language, $wp_query;
	$xili_language->temp_lang_query_tag = $wp_query->query_vars[QUETAG];
	$wp_query->query_vars[QUETAG] = $lang_query_tag;
	$xili_language->current_lang_query_tag = $wp_query->query_vars[QUETAG];
}

function xiliml_restore_loop_lang (){
	global $xili_language, $wp_query;
	$wp_query->query_vars[QUETAG] = $xili_language->temp_lang_query_tag;
	$xili_language->current_lang_query_tag = $wp_query->query_vars[QUETAG];
}

/**
 * functions to permit lang query tag
 * (useful for WP_Query)
 * @since 1.3.2
 * example: add_action('parse_query','xiliml_add_lang_to_parsed_query');
 *		$r = new WP_Query($thequery);
 *		remove_filter('parse_query','xiliml_add_lang_to_parsed_query');
 * used by class xili_Widget_Recent_Posts
 */
function xiliml_add_lang_to_parsed_query ($theclass = array()) {
		global $wp_query;
		$query = $theclass->query;
		if (is_array($query)) {
			$r = $query;
		} else {
			parse_str($query, $r);
		}
		if (array_key_exists(QUETAG,$r)) $wp_query->query_vars[QUETAG] = $r[QUETAG];
}

/* ****** functions and filter added for new default theme named twentyten and twenty-eleven (since WP 3.0) ******* */

/**
 * in twentyten theme: display the time of current post when mouse is on date
 * - adapted for twentytwelve
 */
function xiliml_get_the_translated_time( $thetime, $format = '' ) {
	global $xili_language;
	if ( $xili_language->xili_settings['wp_locale'] == 'db_locale' ) {
		$theformat = (''== $format) ? get_option('time_format') : $format ;
		return the_xili_wp_local_time( $theformat, strtotime(xiliml_get_the_time('m/d/Y H:i'))); // old method locale
	} else {
		//return $thetime; // new mode wp_locale ;
		$curslug = $xili_language->curlang;
		if ( $xili_language->xili_settings['lang_features'][$curslug]['charset'] == 'no_locale' ) {  // KH or HU
			if ( function_exists ( 'xili_translate_date' ) )
				return xili_translate_date ( $curslug, $thetime );
			else
				return $thetime ;
		} else {
			return $thetime ;
		}
	}
}

/**
 * Clone w/o filter
 */
function xiliml_get_the_time( $d = '', $post = null ) {
	$post = get_post($post);

	if ( '' == $d )
		$the_time = get_post_time(get_option('time_format'), false, $post, true);
	else
		$the_time = get_post_time($d, false, $post, true);
	return $the_time; /* without filter */
}

/**
 * in twentyten theme: display the date of current post - adapted for twentytwelve
 */
function xiliml_get_translated_date( $thedate, $format = '' ) {
	global $xili_language;
	$theformat = (''== $format) ? get_option('date_format') : $format ;
	if ( $xili_language->xili_settings['wp_locale'] == 'db_locale' ) {

		return the_xili_wp_local_time( $theformat, strtotime(xiliml_get_the_date('m/d/Y H:i')));
	} else {
		$curslug = $xili_language->curlang;
		if ( $xili_language->xili_settings['lang_features'][$curslug]['charset'] == 'no_locale' ) {  // KH or HU
			if ( function_exists ( 'xili_translate_date' ) )
				return xili_translate_date ( $curslug, $thedate );
			else
				return $thedate ;
		} else {
			return $thedate ;
		}
	}
}

if ( !is_admin() ) {
	add_filter( 'get_the_time', 'xiliml_get_the_translated_time', 10, 3);
	add_filter( 'get_the_date', 'xiliml_get_translated_date', 10, 2);
}

/**
 * Clone w/o filter
 */
function xiliml_get_the_date( $d = '' ) {
	global $post;
	$the_date = '';

	if ( '' == $d )
		$the_date .= mysql2date(get_option('date_format'), $post->post_date);
	else
		$the_date .= mysql2date($d, $post->post_date);

	return $the_date; /* without filter */
}

/**
 * filter for template tag: get_comment_date()
 */
function xiliml3_comment_date( $comment_time, $format = '' ) {
  $theformat = ( ''== $format ) ? get_option( 'date_format' ) : $format ;
  return the_xili_wp_local_time( $theformat, strtotime(get_comment_time ( 'm/d/Y H:i' ) ) );
  /* impossible to use get_comment_date as it is itself filtered*/
}
if ( !is_admin() ) {
	add_filter( 'get_comment_date', 'xiliml3_comment_date',10 ,2 );
}

/**
 * filter in theme multilingual-permalinks.php file
 *
 * @since 2.10.0
 */
function xili_language_trans_slug_qv ( $lang_slug ) {
	global $xili_language;

	if ( isset ( $_POST['language_alias'] ) )
		$xili_language->xili_settings = get_option('xili_language_settings'); // need update !

	$short = ( isset ( $xili_language->xili_settings['lang_features'][$lang_slug]['alias'] ) ) ?  $xili_language->xili_settings['lang_features'][$lang_slug]['alias'] : $lang_slug ;

	return $short;
}


/****************** instantiation Class *****************/


/**
 * instantiation of xili_language class
 *
 * @since 1.8.8 to verify that WP 3.0 is installed
 * @updated for 1.8.9, 2.3.1, 2.7.1 (function)
 *
 */
function xili_language_start () {
	global $wp_version, $xili_language, $xili_language_admin;
	if ( version_compare(PHP_VERSION, XILILANGUAGE_PHP_VER, '<') ){
		add_action( 'admin_notices', 'xili_language_need_php5' );
		return;
	} elseif ( version_compare($wp_version, XILILANGUAGE_WP_VER, '<') && XILILANGUAGE_VER > XILILANGUAGE_PREV_VER ) {
		add_action( 'admin_notices', 'xili_language_need_31' );
		return;
	} else {

		// new sub-folder since 2.6
	    require_once ( plugin_dir_path( __FILE__ ) . 'xili-includes/xili-language-widgets.php' );

		/**
	 	 * instantiation of xili_language class
	 	 *
	 	 * @since 0.9.7
	 	 * @updated 2.6
	 	 *
	 	 *
	 	 * @param locale_method (true for cache compatibility... in current tests with johan.eenfeldt@kostdoktorn.se)
	 	 * @param future version
	 	 */

		$xili_language = new xili_language( false , false );

		if ( is_admin() ) {
			$plugin_path = dirname(__FILE__) ;
			require( $plugin_path . '/xili-includes/xl-class-admin.php' );
			$xili_language_admin = new xili_language_admin( $xili_language );
		}

		/**
		 * Enable to add functions and filters that are not in theme's functions.php
		 * These filters are common even if you change default theme...
		 * Place your functions.php in folder plugins/xilidev-libraries/
		 * if you have a filter in this file, avoid to have similar one in functions.php of the theme !!!
		 *
		 * (for tests, check / uncheck 'enable gold functions' in settings UI)
		 * @since 1.0
		 * @updated 1.4.0
		 */
		$xili_language->insert_gold_functions ();
	}
}

function permalink_init () {
	if ( get_option('permalink_structure') ) {
		$plugin_path = dirname(__FILE__) ;
		require_once( $plugin_path . '/xili-includes/xili-permalinks-class.php' );

		add_action('init', 'xl_permalinks_theme', 1);
	}
}

function xl_permalinks_theme () {

	if ( function_exists ( 'xl_permalinks_init' ) ) xl_permalinks_init();

}

add_action( 'plugins_loaded', 'permalink_init', 1);
add_action( 'plugins_loaded', 'xili_language_start', 13 ); // before xili-dictionary (20) and xili_tidy_tags (15) - 2.7.1

// special jetpack to live change admin side language - 2.8.9 (was before tested with bbPress addon)
function xili_jetpack_lang_init ( ) {
	if ( is_admin() )
		add_filter( 'plugin_locale', 'xili_jetpack_lang_reload', 10, 2);
}

function xili_jetpack_lang_reload ( $locale = 'en_US', $domain = 'default' ) {

	if ( $domain == 'xili-language' && class_exists ( 'jetpack' ) )  {  // because plugin jetpack domain not filterable
		$locale = get_user_option( 'user_locale' );
		if ( empty( $locale ) ) {
			$locale = ( defined( 'WPLANG' ) ) ? WPLANG : 'en_US';

			if ( is_multisite() ) {
				if ( defined( 'WP_INSTALLING' ) || ( false === $ms_locale = get_option( 'WPLANG' ) ) )
					$ms_locale = get_site_option( 'WPLANG' );

			if ( $ms_locale !== false )
					$locale = $ms_locale;
			}
		}
		unload_textdomain( 'jetpack' ); // by Jetpack_Sync::sync_options( __FILE__, 'widget_twitter' ) during plugin loading 2.3

		load_textdomain( 'jetpack', WP_PLUGIN_DIR.'/jetpack/languages/jetpack-'.$locale.'.mo' );
	}
	return $locale;
}

/**
 * @since 2.10.1 - Needed to recover 2014-xili customized multilingual featured content
 */
function xili_jetpack_disable_featured () {
	global $xili_language;

	if ( $xili_language->xili_settings['enable_fc_theme_class'] == 'enable' && ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
		if ( file_exists( get_stylesheet_directory() . '/inc/featured-content.php' ) ) {
		 	require get_stylesheet_directory() . '/inc/featured-content.php'; // this one will disable others
		}
	}
}

if ( class_exists ( 'jetpack' ) ) { // inited by init filter but without modules (priority 100 in jetpack)
	// 2.11.1
	add_action( 'plugins_loaded', 'xili_jetpack_disable_featured', 17 ); // after XL and XTT
	// must be commented if jetpack changes when load_plugin_textdomain (as # http://plugins.trac.wordpress.org/ticket/1764)
	add_action( 'plugins_loaded', 'xili_jetpack_lang_init', 99 ); // 99 = to be registered before jetpack instantiate - plugins_loaded = 100
}

/**
 * @since 2.8.3 - XILILANGUAGE_DEBUG on top
 */
function xili_xl_error_log ($content = '') {

	if ( defined ('XILILANGUAGE_DEBUG') &&  XILILANGUAGE_DEBUG == true && defined ('WP_DEBUG') && WP_DEBUG == true && $content !='' ) error_log ( $content );

}

/**
 * errors messages
 */
function xili_language_need_php5() {
		global $wp_version;
		load_plugin_textdomain( 'xili_language_errors', false, 'xili-language/languages' );
		echo '<div id="message" class="error fade"><p>';
		echo '<strong>'.__( 'Installation of xili-language is not completed.', 'xili_language_errors' ) . '</strong>';
		echo '<br />';
		printf( __( 'This xili-language version (%s) need PHP Version more than %s; installed release is %s.', 'xili_language_errors' ), XILILANGUAGE_VER , XILILANGUAGE_PHP_VER, PHP_VERSION ) ;
		echo '<br />';
		printf( __( 'Find a server with PHP Version to more %s or use xili-language version less or equal to %s', 'xili_language_errors' ), XILILANGUAGE_PHP_VER, XILILANGUAGE_PREV_VER );
		echo '</p></div>';
}

function xili_language_need_31() {
		global $wp_version;
		load_plugin_textdomain( 'xili_language_errors', false, 'xili-language/languages' );
		echo '<div id="message" class="error fade"><p>';
		echo '<strong>'.__( 'Installation of xili-language is not completed.', 'xili_language_errors' ) . '</strong>';
		echo '<br />';
		printf( __( 'This xili-language version (%s) need WordPress Version more than %s; installed release is %s.', 'xili_language_errors' ), XILILANGUAGE_VER , XILILANGUAGE_WP_VER, $wp_version) ;
		echo '<br />';
		printf( __( 'Upgrade WordPress Version to more %s or use xili-language version less than %s', 'xili_language_errors' ), XILILANGUAGE_WP_VER, XILILANGUAGE_PREV_VER );
		echo '</p></div>';
}

?>