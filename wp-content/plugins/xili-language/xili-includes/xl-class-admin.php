<?php
/**
 * class xili_language_admin - 2.6.3 - 2.7.1 - 2.8.0 - 2.8.3 - 2.8.4 - 2.8.4.1 - 2.8.4.2 - 2.8.4.3 - 2.8.5
 *
 * 2013-03-13 (05)
 * 2013-03-17 (2.8.6)
 * 2013-04-16 (2.8.7)
 * 2013-05-03 (2.8.8)
 * 2013-05-19 (2.8.8k)
 * 2013-05-26 (2.8.9)
 * 2013-08-20 (2.9.0)
 * 2013-10-02 (2.9.1)
 * 2013-11-01 (2.9.10) inline edit and bulk edit improved - Pages insertion point
 * 2013-11-20 (2.9.20) Menu insertion point
 * 2013-11-24 (2.9.21) Optimize flush
 * 2013-12-11 (2.9.30) Option for WP 3.8 style, default menu screen options
 *
 * 2014-02-01 (2.10.0) New versioning - add twentyfourteen - improved downloads from Automattic (branches)
 * 2014-02-27 (2.10.1) Fixes nav menu css, add menu type for singular
 * 2014-03-10 (2.11.0) improves infos in form for alias refreshing in permalinks, new locales (based on jetpack)
 * 2014-03-10 (2.11.1) changes ajax/json for WP3.9
 * 2014-03-30 (2.11.2) accurate counter for CPT - more tests with 3.9
 * 2014-04-21 (2.11.3) fixes keys in title count - style improved in translations metabox - recover admin substitle of tabs
 *
 * @package xili-language
 */

class xili_language_admin extends xili_language {

	// 2.5
	var $authorbrowserlanguage = ''; // author default browser language

	var $exists_style_ext = false; // test if external style exists in theme
	var $style_folder = ''; // where is xl-style.css
	var $style_flag_folder_path = ''; // where are flags
	var $style_message = '';
	var $wikilink = 'http://wiki.xiligroup.org';
	var $parent = null;
	var $news_id = 0; //for multi pointers
	var $news_case = array();
	var $admin_messages = array(); //set in #491
	var $user_locale = 'en_US';
	var $embedded_themes = array('twentyten', 'twentyeleven', 'twentytwelve', 'twentythirteen', 'twentyfourteen');

	var $subpage_titles = array(); // see add_menu_settings_pages
	var $xl_tabs = array();

	var $default_locale = null; // from GP_locale
	/**
	 * PHP 5 Constructor
	 */
	function __construct( $xl_parent ){

		$this->parent = $xl_parent; // to keep values built in parent filters...

		// need parent constructed values (third param - tell coming from admin-class //2.6
		parent::__construct( false, false, true );

		// vars shared between parent and  _admin class - 2.8.4.3
		$this->xili_settings = &$this->parent->xili_settings;

		$this->langs_list_options = &$this->parent->langs_list_options; // 2.8.6
		//$this->examples_list = &$this->parent->examples_list;
		if (!class_exists('GP_Locales'))
			require_once ( $this->plugin_path .'/xili-includes/locales.php' );

		$XL_locales = GP_Locales::instance(); //error_log ('***** ' . serialize($XL_locales->locales));

		$this->examples_list = array();

		foreach ( $XL_locales->locales as $key => $one_locale ) {
			if ( isset($one_locale->wp_locale) && $one_locale->wp_locale != ''){
				$this->examples_list[$one_locale->wp_locale] = sprintf( _x( '%1$s/%2$s', 'locales', 'xili-language' ), $one_locale->english_name, $one_locale->native_name );
			} else {
				$this->examples_list[$key] = sprintf( _x( '%1$s/%2$s *', 'locales', 'xili-language' ), $one_locale->english_name, $one_locale->native_name );
			}
		}

		$this->default_lang = &$this->parent->default_lang;

		$this->default_locale = GP_Locales::by_field( 'wp_locale', $this->default_lang );

		$this->langs_group_id = &$this->parent->langs_group_id;
		$this->langs_group_tt_id = &$this->parent->langs_group_tt_id;

		$this->get_template_directory = &$this->parent->get_template_directory;
		$this->get_parent_theme_directory = &$this->parent->get_parent_theme_directory;

		$this->show_page_on_front = &$this->parent->show_page_on_front;

		$this->arraydomains = &$this->parent->arraydomains;

		$this->lang_perma = &$this->parent->lang_perma;
		$this->alias_mode = &$this->parent->alias_mode;

		$this->langs_ids_array = &$this->parent->langs_ids_array;
		$this->langs_slug_name_array = &$this->parent->langs_slug_name_array;
		$this->langs_slug_fullname_array = &$this->parent->langs_slug_fullname_array;

		$this->langs_slug_shortqv_array = &$this->parent->langs_slug_shortqv_array;
		$this->langs_shortqv_slug_array = &$this->parent->langs_shortqv_slug_array;


		add_action( 'admin_init', array( &$this, 'init_roles') );	// 2.8.8

		add_action( 'admin_head', array(&$this,'xd_flush_permalinks') ); // 2.11

		// since 2.2.0
		add_action( 'admin_bar_init', array( &$this, 'admin_bar_init') ); // add button in toolbar

		// 2.8.0 dashboard language - inspired from Takayuki Miyoshi works
		add_filter( 'locale', array( &$this, 'admin_side_locale') );


		add_action( 'admin_init', array( &$this, 'switch_user_locale') );
		add_action( 'personal_options_update', array( &$this, 'update_user_dashboard_lang_option') );
		add_action( 'personal_options', array( &$this, 'select_user_dashboard_locale') );


		// plugins list infos
		add_filter( 'plugin_row_meta', array( &$this, 'more_infos_in_plugin_list' ), 10, 2);  // class WP_Plugins_List_Table
		add_filter( 'plugin_action_links', array( &$this, 'more_plugin_actions' ), 10, 2); // class WP_Plugins_List_Table
		add_action( 'after_plugin_row', array( &$this, 'more_plugin_row' ), 10, 3); // class WP_Plugins_List_Table

		// Dashboard menu and settings pages

		add_action( 'admin_menu', array( &$this, 'add_menu_settings_pages'), 10 );
		add_action( 'admin_menu', array( &$this, 'admin_sub_menus_hide'), 12 ); //

		add_filter( 'admin_title', array( &$this, 'admin_recover_page_title'), 10, 2 ); // 2.11.3

		add_action( 'admin_print_styles-settings_page_language_page', array(&$this, 'print_styles_options_language_page'), 20 );
		add_action( 'admin_print_styles-settings_page_language_front_set', array(&$this, 'print_styles_options_language_tabs'), 20 );
		add_action( 'admin_print_styles-settings_page_language_expert', array(&$this, 'print_styles_options_language_tabs'), 20 );
		add_action( 'admin_print_styles-settings_page_language_files', array(&$this, 'print_styles_options_language_tabs'), 20 );
		add_action( 'admin_print_styles-settings_page_language_support', array(&$this, 'print_styles_options_language_support'), 20 );

		// Navigation menu builder
		add_action( 'admin_init', array(&$this, 'add_language_nav_menu_meta_boxes') );
		add_action( 'admin_init', array(&$this, 'add_sub_select_page_nav_menu_meta_boxes') );
		add_action( 'admin_init', array(&$this, 'add_sub_select_nav_menu_meta_boxes') );

		// Edit Post Page
		add_action( 'admin_init', array(&$this,'admin_init') ); // styles registering

		add_action( 'admin_menu', array(&$this, 'add_custom_box_in_post_edit') );

		add_action( 'admin_print_scripts-post.php', array(&$this,'find_post_script') ); // 2.2.2
		add_action( 'admin_print_scripts-post-new.php', array(&$this,'find_post_script') );

		// 2.9.10
		add_action( 'wp_ajax_find_post_types', array(&$this,'wp_ajax_find_post_types') );

		add_action( 'admin_print_styles-post.php', array(&$this, 'print_styles_cpt_edit') );
		add_action( 'admin_print_styles-post-new.php', array(&$this, 'print_styles_cpt_edit') );

		//add_filter( 'is_protected_meta', array(&$this,'hide_lang_post_meta'), 10, 3 ); // 2.5
		//add_filter( 'post_meta_key_subselect', array(&$this,'hide_lang_post_meta_popup'), 10, 2); // 2.5

		/* actions for edit post page */
		add_action( 'save_post', array(&$this,'xili_language_add'), 10, 2 );
		add_action( 'save_post', array(&$this, 'fixes_post_slug'), 11, 2 ); // 2.5

		// Edit Attachment Media
		add_filter( 'attachment_fields_to_edit', array(&$this,'add_language_attachment_fields'), 10, 2 ); // 2.6.3
		add_filter( 'attachment_fields_to_save', array(&$this,'set_attachment_fields_to_save'), 10, 2 ); // 2.6.3
		add_action( 'delete_attachment', array(&$this,'if_cloned_attachment') ); // 2.6.3
		add_filter( 'wp_delete_file', array(&$this,'if_file_cloned_attachment') ); // 2.6.3

		// posts edit table
		add_filter( 'manage_edit-post_columns', array(&$this,'xili_manage_column_name')); // 2.9.10 - post quick edit single row
		add_filter( 'manage_post_posts_columns', array(&$this,'xili_manage_column_name')); // 2.8.1
		add_filter( 'manage_page_posts_columns', array(&$this,'xili_manage_column_name'));
		add_filter( 'manage_media_columns', array(&$this,'xili_manage_column_name')); // 2.6.3

		$custompoststype = $this->xili_settings['multilingual_custom_post'] ; // 2.8.1
 		if ( $custompoststype != array()) {
			foreach ( $custompoststype as $key => $customtype ) {
 				if ( ( !class_exists( 'bbPress') && $customtype['multilingual'] == 'enable' ) || ( class_exists( 'bbPress')  && ! in_array( $key, array( bbp_get_forum_post_type(), bbp_get_topic_post_type(), bbp_get_reply_post_type() ) ) && $customtype['multilingual'] == 'enable' ) ) {
 					add_filter( 'manage_'.$key.'_posts_columns', array(&$this,'xili_manage_column_name'));
 				}
			}
 		}

		if ( class_exists( 'bbPress' ) ) {
			add_filter( 'bbp_admin_forums_column_headers', array(&$this,'xili_manage_column_name'));
			add_filter( 'bbp_admin_topics_column_headers', array(&$this,'xili_manage_column_name'));
			add_filter( 'bbp_admin_replies_column_headers', array(&$this,'xili_manage_column_name')); //2.8.1
		}

		add_action( 'manage_posts_custom_column', array(&$this,'xili_manage_column'), 10, 2);
		add_action( 'manage_pages_custom_column', array(&$this,'xili_manage_column'), 10, 2);
		add_action( 'manage_media_custom_column', array(&$this,'xili_manage_column'), 10, 2); // 2.6.3

		add_action( 'admin_print_styles-edit.php', array(&$this, 'print_styles_posts_list'), 20 );
		add_action( 'admin_print_styles-upload.php', array(&$this, 'print_styles_posts_list'), 20 );// 2.6.3

		// quick edit languages in list - 1.8.9
		add_action( 'quick_edit_custom_box', array(&$this,'languages_custom_box'), 10, 2);
		add_action( 'admin_head-edit.php', array(&$this,'quick_edit_add_script') );
		add_action( 'bulk_edit_custom_box', array(&$this,'hidden_languages_custom_box'), 10, 2); // 1.8.9.3

		add_action( 'wp_ajax_save_bulk_edit', array(&$this,'save_bulk_edit_language') ); // 2.9.10

		add_action( 'wp_ajax_get_menu_infos', array(&$this,'ajax_get_menu_infos') ); // 2.9.10


		// sub-select in admin/edit.php 1.8.9
		add_action( 'restrict_manage_posts', array(&$this,'restrict_manage_languages_posts') );

		/* categories edit-tags table */
		add_filter( 'manage_edit-category_columns', array(&$this,'xili_manage_tax_column_name'));
		add_filter( 'manage_category_custom_column', array(&$this,'xili_manage_tax_column'), 10, 3); // 2.6
		add_filter( 'category_row_actions', array(&$this,'xili_manage_tax_action'), 10, 2); // 2.6

		add_action( 'admin_print_styles-edit-tags.php', array(&$this, 'print_styles_posts_list'), 20 );
		add_action( 'category_edit_form_fields', array(&$this, 'show_translation_msgstr'), 10, 2 );

		add_action( 'category_add_form', array(&$this, 'update_xd_msgid_list') ); //do_action($taxonomy . '_add_form', $taxonomy);

		/* actions for edit link page */
		add_action( 'admin_menu', array(&$this, 'add_custom_box_in_link') );

		add_action( 'admin_enqueue_scripts', array(&$this, 'admin_enqueue_menu_script') );

		add_filter( 'manage_link-manager_columns', array(&$this,'xili_manage_link_column_name') ); // 1.8.5
		add_action( 'manage_link_custom_column', array(&$this,'manage_link_lang_column'),10,2);
		add_action( 'admin_print_styles-link.php', array(&$this, 'print_styles_link_edit'), 20 );

		// set or update term for this link taxonomy
		add_action( 'edit_link', array(&$this,'edit_link_set_lang') );
		add_action( 'add_link', array(&$this,'edit_link_set_lang') );

		// default screen options - nav menus

		add_action( 'added_user_meta' , array(&$this,'default_nav_menus_screen_options'), 10, 4 );

		//display contextual help
	    add_action( 'contextual_help', array( &$this,'add_help_text' ), 10, 3 ); /* 1.7.0 */

	}

	function xd_flush_permalinks() {
		$screen = get_current_screen();
		if ( $screen->base == 'settings_page_language_page' ) {
			flush_rewrite_rules();
		}
	}

	/**
 	 * Checks if we should add links to the admin bar.
 	 *
 	 * @since 2.2.0
 	 */
	function admin_bar_init() {
	// Is the user sufficiently leveled, or has the bar been disabled? !is_super_admin() ||
		if ( !is_admin_bar_showing() )
			return;

	 // editor rights
		if ( current_user_can ( 'xili_language_menu' ) )
			add_action( 'admin_bar_menu', array( &$this,'xili_tool_bar_links' ), 500 );

			add_action( 'admin_bar_menu', array( &$this,'lang_admin_bar_menu' ), 500 );

	}

	/**
 	 * Checks if we should add links to the bar.
 	 *
 	 * @since 2.2
 	 * updated and renamed 2.4.2 (node)
 	 */
	function xili_tool_bar_links() {

		$link = plugins_url( 'images/xililang-logo-24.png', $this->file_file ) ;
		$alt = __( 'Languages by ©xiligroup' ,'xili-language');
		$title = __( 'Languages menu by ©xiligroup' ,'xili-language');
		// Add the Parent link.
		$this->add_node_if_version( array(
			'title' => sprintf("<img src=\"%s\" alt=\"%s\" title=\"%s\" />", $link, $alt, $title ),
			'href' => false,
			'id' => 'xili_links',
		));
		if ( current_user_can ( 'xili_language_set' ) )
			$this->add_node_if_version( array(
				'title' => __('Languages settings','xili-language'),
				'href' => admin_url('options-general.php?page=language_page'),
				'id' => 'xl-set',
				'parent' => 'xili_links',
				'meta' => array('title' => __('Languages settings','xili-language') )
			));

		if ( class_exists('xili_tidy_tags' ) && current_user_can ('xili_tidy_editor_set') )
			$this->add_node_if_version( array(
				'title' => sprintf(__("Tidy %s settings","xili_tidy_tags"), __('Tags') ),
				'href' => admin_url( 'admin.php?page=xili_tidy_tags_settings' ),
				'id' => 'xtt-set',
				'parent' => 'xili_links',
				'meta' => array('title' => sprintf(__("Tidy %s settings","xili_tidy_tags"), __('Tags') ) )
			));
		if ( class_exists('xili_tidy_tags' ) && current_user_can ('xili_tidy_editor_group') )
			$this->add_node_if_version( array(
				'title' => sprintf( __('%s groups','xili_tidy_tags'), __('Tags')),
				'href' => admin_url( 'admin.php?page=xili_tidy_tags_assign' ),
				'id' => 'xtt-group',
				'parent' => 'xili_links',
				'meta' => array('title' => sprintf( __('%s groups','xili_tidy_tags'), __('Tags') ) )
			));

		if ( class_exists('xili_dictionary' ) && current_user_can ('xili_dictionary_set')) {
			if ( XILIDICTIONARY_VER > '1.5' ) {
				global $xili_dictionary;
				$link = $xili_dictionary->xd_settings_page ;  // XD 2.0
			} else {
				$link = 'tools.php?page=dictionary_page';
			}

			$this->add_node_if_version( array(
				'title' => 'xili-dictionary',
				'href' => admin_url( $link ),
				'id' => 'xd-set',
				'parent' => 'xili_links',
				'meta' => array('title' => sprintf( __('Translation with %s tools','xili-language'), 'xili-dictionary' ) )
			));
		}
		$this->add_node_if_version( array(
			'title' => __('xili-language : how to','xili-language'),
			'href' => 'http://multilingual.wpmu.xilione.com',
			'id' => 'xilione-multi',
			'parent' => 'xili_links',
			'meta' => array('target' => '_blank')
		));
		$this->add_node_if_version( array(
			'title' => __('About ©xiligroup plugins','xili-language'),
			'href' => 'http://dev.xiligroup.com',
			'id' => 'xili-about',
			'parent' => 'xili_links',
			'meta' => array('target' => '_blank')
		));

	}

	function add_node_if_version ( $args ) {
		global $wp_admin_bar, $wp_version;
		if ( version_compare($wp_version, '3.3', '<') ) {
			$wp_admin_bar->add_menu( $args );
		} else {
			$wp_admin_bar->add_node( $args );
		}
	}

	/**
	 * from after_setup_theme action
	 *
	 * @since 2.8.8
	 *
	 */
	function admin_user_id_locale () {
		$this->user_locale = get_user_option( 'user_locale' );
	}

	/**
	 * Admin side localization - user's dashboard
	 *
	 * @since 2.8.0
	 *
	 */
	function admin_side_locale( $locale = 'en_US' ) {

		// to avoid notice with bbPress 2.3 - brutal approach
		if ( class_exists( 'bbPress') ) remove_action( 'set_current_user', 'bbp_setup_current_user' );
		$locale = get_user_option( 'user_locale' );
		if ( class_exists( 'bbPress') ) add_action( 'set_current_user', 'bbp_setup_current_user', 10 );

		// replace by
		//$locale = $this->user_locale; // see after_setup_theme filter above 2.8.8

		if ( empty( $locale ) )
			$locale = $this->get_default_locale();

		return $locale;
	}


	/**
	 * Admin side localization - available languages inside WP core installation
	 *
	 * @since 2.8.0
	 *
	 */
	function get_default_locale() {

		$locale = ( defined( 'WPLANG' ) ) ? WPLANG : 'en_US';

		if ( is_multisite() ) {
			if ( defined( 'WP_INSTALLING' ) || ( false === $ms_locale = get_option( 'WPLANG' ) ) )
				$ms_locale = get_site_option( 'WPLANG' );

			if ( $ms_locale !== false )
				$locale = $ms_locale;
		}

		return $locale;

	}

	// Admin Bar at top right

	function lang_admin_bar_menu( ) {

		$screen = get_current_screen();  // to limit unwanted side effects (form)
		if ( in_array ( $screen->id , array (
		'dashboard', 'users', 'profile',
		'edit-post', 'edit-page', 'link-manager', 'upload',
		'settings_page_language_page', 'settings_page_language_front_set',
		'settings_page_language_expert', 'settings_page_language_expert', 'settings_page_language_support', 'settings_page_language_files',
		'xdmsg', 'edit-xdmsg', 'xdmsg_page_dictionary_page'
		) )
		|| ( false !== strpos ( $screen->id , '_page_xili_tidy_tags_assign' ) )
		) {

			$current_locale = $this->admin_side_locale();

			//$current_language = (isset ( $this->examples_list[$current_locale]) ) ? $this->examples_list[$current_locale] : '';

			$cur_locale = GP_Locales::by_field( 'wp_locale', $current_locale );
			if ( $cur_locale ) {
				$current_language = $cur_locale->native_name ;
			} else {
				$cur_locale = GP_Locales::by_slug( $current_locale );
				$current_language = ( $cur_locale ) ? $cur_locale->native_name : '' ;
			}

			if ( ! $current_language )
				$current_language = $current_locale;

			$this->add_node_if_version( array(
				'parent' => 'top-secondary',
				'id' => 'xili-user-locale',
				'title' => __('Language','xili-language').': '. $this->lang_to_show( $current_language ) ) ); // '&#10004; '

			$available_languages = $this->available_languages(
				array( 'exclude' => array( $current_locale ) ) );

			foreach ( $available_languages as $locale => $lang ) {
				$url = admin_url( 'profile.php?action=lang-switch-locale&locale=' . $locale );

				$url = add_query_arg(
					array( 'redirect_to' => urlencode( $_SERVER['REQUEST_URI'] ) ),
					$url );

				$url = wp_nonce_url( $url, 'lang-switch-locale' );

				$this->add_node_if_version( array(
					'parent' => 'xili-user-locale',
					'id' => 'xili-user-locale-' . $locale,
					'title' => $this->lang_to_show( $lang ),
					'href' => $url ) );
			}
		}
	}

	function switch_user_locale() {

		if ( empty( $_REQUEST['action'] ) || 'lang-switch-locale' != $_REQUEST['action'] )
			return;

		check_admin_referer( 'lang-switch-locale' );

		$locale = isset( $_REQUEST['locale'] ) ? $_REQUEST['locale'] : '';

		if ( ! $this->is_available_locale( $locale ) || $locale == $this->admin_side_locale() )
			return;

		update_user_option( get_current_user_id(), 'user_locale', $locale, true );

		if ( ! empty( $_REQUEST['redirect_to'] ) ) {
			wp_safe_redirect( $_REQUEST['redirect_to'] );
			exit();
		}
	}

	function  is_available_locale( $locale ) {
		return ! empty( $locale ) && array_key_exists( $locale, (array) $this->available_languages() );
	}

	function available_languages( $args = '' ) {
		$defaults = array(
			'exclude' => array(),
			'orderby' => 'key',
			'order' => 'ASC' );

		$args = wp_parse_args( $args, $defaults );

		$langs = array();

		$installed_locales = get_available_languages();
		$installed_locales[] = $this->get_default_locale();
		$installed_locales[] = 'en_US';
		$installed_locales = array_unique( $installed_locales );
		$installed_locales = array_filter( $installed_locales );

		foreach ( $installed_locales as $locale ) {
			if ( in_array( $locale, (array) $args['exclude'] ) )
				continue;

			// $lang = ( isset ( $this->examples_list[$locale]) ) ? $this->examples_list[$locale] : '';

			$cur_locale = GP_Locales::by_field( 'wp_locale', $locale );
			if ( $cur_locale ) {
				$lang = sprintf( _x( '%1$s/%2$s', 'locales', 'xili-language' ), $cur_locale->english_name, $cur_locale->native_name ) ;
			} else {
				$cur_locale = GP_Locales::by_slug( $locale );
				$lang = ( $cur_locale ) ? sprintf( _x( '%1$s/%2$s', 'locales', 'xili-language' ), $cur_locale->english_name, $cur_locale->native_name ) : '' ;
			}

			if ( empty( $lang ) )
				$lang = "[$locale]";

			$langs[$locale] = $lang;
		}

		if ( 'value' == $args['orderby'] ) {
			natcasesort( $langs );

			if ( 'DESC' == $args['order'] )
				$langs = array_reverse( $langs );
		} else {
			if ( 'DESC' == $args['order'] )
				krsort( $langs );
			else
				ksort( $langs );
		}

		$langs = apply_filters( 'xili_available_languages', $langs, $args );

		return $langs;
	}

	/**
	 * Adds option in user profile to set and update his dashboard language
	 *
	 * 'user_locale' saved as iso (en_US or fr_FR ….)
	 * @since 2.8.0
	 *
	 */
	function update_user_dashboard_lang_option() {
		if ( ! isset( $_POST['user_locale'] ) || empty( $_POST['user_locale'] ) )
			$locale = null;
		else
			$locale = $_POST['user_locale'];

		update_user_option( get_current_user_id(), 'user_locale', $locale, true );
	}

	function select_user_dashboard_locale() {
		$available_languages = $this->available_languages( 'orderby=value' );
		$selected = $this->admin_side_locale();

		?>
		<tr>
			<th scope="row"><?php echo esc_html( __( 'Your dashboard language', 'xili-language' ) ); ?></th>
			<td>
				<select name="user_locale">
				<?php foreach ( $available_languages as $locale => $lang ) : ?>
					<option value="<?php echo esc_attr( $locale ); ?>" <?php selected( $locale, $selected ); ?>><?php echo esc_html( $this->lang_to_show( $lang ) ); ?></option>
				<?php endforeach; ?>
				</select>
				<p><em><?php _e('System’s default language is', 'xili-language'); echo ": " . $this->get_default_locale(); ?></em></p>
			</td>
		</tr>
		<?php
	}

	function lang_to_show ( $lang = 'english' ) {
		return ucwords( $lang ); // uppercase each word
	}

	/** end dashboard user's language functions **/

	/**
	 * Adds links to the plugin row on the plugins page.
	 * Thanks to Zappone et WP engineer.com
	 *
	 * @param mixed $links
	 * @param mixed $file
	 */
	function more_infos_in_plugin_list( $links, $file ) {
		$base = $this->plugin_basename ;
		if ( $file == $base ) {
			$links[] = '<a href="options-general.php?page=language_page">' . __('Settings') . '</a>';
			$links[] = __('Informations and Getting started:', 'xili-language') . ' <a href="'. $this->wikilink . '">' . __('Xili Wiki', 'xili-language') . '</a>';
			$links[] = '<a href="http://forum2.dev.xiligroup.com">' . __('Forum and Support', 'xili-language') . '</a>';
			$links[] = '<a href="http://dev.xiligroup.com/donate/">' . __('Donate', 'xili-language') . '</a>';
		}
		return $links;
	}

	/**
	 * Adds a row to comment situation for multilingual context !
	 *
	 */
	function more_plugin_row ( $plugin_file, $plugin_data, $status ) {
		$base = $this->plugin_basename ;
		if ( $plugin_file == $base ) {
			$statusXili =  array ();

			$statusXili[] = __('Congratulations for choosing xili-language to built a multilingual website. To work optimally, 2 other plugins are recommended', 'xili-language');

			$statusXili[] = $this->plugin_status ( 'xili-dictionary', 'xili-dictionary/xili-dictionary.php', $status ) ;

			$statusXili[] = $this->plugin_status ( 'xili-tidy-tags', 'xili-tidy-tags/xili-tidy-tags.php' , $status) ;

			if ( is_child_theme() ) {
				$theme_name = get_option("stylesheet").' '.__('child of','xili-language').' '.get_option("template");
			} else {
				$theme_name = get_option("template");
			}

			$statusXili[] = sprintf ( __('For Appearance the current active theme is <em>%s</em>', 'xili-language'), $theme_name );

			if ( $this->parent->xili_settings['theme_domain'] == '' ) {
				$statusXili[] = sprintf (__('This theme <em>%s</em> seems to not contain localization function (load_theme_textdomain) to be used for a multilingual website', 'xili-language'), $theme_name );
			} else {
				$statusXili[] = sprintf (__('This theme <em>%s</em> seems to contain localization function to be used for a multilingual website', 'xili-language'), $theme_name );
			}

			$cb_col = '<img src="'.plugins_url( 'images/xililang-logo-24.png', $this->file_file ).'" alt="xili-language trilogy"/>';
			$action_col = __('More infos about', 'xili-language') . '<br />&nbsp;&nbsp;' . $plugin_data['Name'] ;
			$description_col = implode ( '. ', $statusXili ).'.';
			echo "<tr><th>$cb_col</th><td>$action_col</td><td>$description_col</td></tr>";
		}
	}

	function plugin_status ( $plugin_name, $plugin_file, $status ) {

			if ( is_plugin_active( $plugin_file ) ){
				$plug_status = __('active', 'xili-language');
			} else {
				$plugins = get_plugins();
				if ( isset( $plugins[ $plugin_file ] ) ) {
					$plug_status = __('inactive', 'xili-language');
				} else {
					$plug_status = __('not installed', 'xili-language');
				}
			}

		return sprintf ( __('Plugin %s is %s', 'xili-language'), $plugin_name, $plug_status );
	}

	/**
	 * Add action link(s) to plugins page
	 *
	 * @since 0.9.3
	 * @author MS
	 * @copyright Dion Hulse, http://dd32.id.au/wordpress-plugins/?configure-link and scripts@schloebe.de
	 */
	function more_plugin_actions( $links, $file ){
		$this_plugin = $this->plugin_basename ;
		if( $file == $this_plugin ){
			$settings_link = '<a href="options-general.php?page=language_page">' . __('Settings') . '</a>';
			$links = array_merge( array($settings_link), $links); // before other links
		}
		return $links;
	}




	/********************************** SETTINGS ADMIN UI ***********************************/

	/**
	 * add admin menu and associated pages of admin UI
	 *
	 * @since 0.9.0
	 * @updated 0.9.6 - only for WP 2.7.X - do registering of new meta boxes and JS __(' -','xili-language')
	 * @updated 2.4.1 - sub-pages and tab
	 * @updated 2.11.3 - label and title for tabs and pages
	 *
	 */
	function add_menu_settings_pages() {
		/* browser title and menu title - if empty no menu */
		 $this->thehook = add_options_page(__('xili-language plugin','xili-language'). ' - 1', __('Languages ©xili','xili-language'), 'manage_options', 'language_page', array( &$this, 'languages_settings' ) );

		 add_action('load-'.$this->thehook, array(&$this,'on_load_page'));

		 $this->xl_tabs = array(
				'language_page' => array( 'label' => __( 'Languages page', 'xili-language' ), 'url' => 'options-general.php?page=language_page'     ),
				'language_front_set' => array( 'label' => __( 'Languages front-end settings', 'xili-language' ), 'url' => 'options-general.php?page=language_front_set'    ),
				'language_expert' => array( 'label' => __( 'Settings for experts', 'xili-language' ), 'url' => 'options-general.php?page=language_expert'   ),
				'language_files' => array( 'label' => __( 'Managing language files', 'xili-language' ), 'url' => 'options-general.php?page=language_files'   ),
				'language_support' => array( 'label' => __( 'xili-language support', 'xili-language' ), 'url' => 'options-general.php?page=language_support'   ),
			);

		 $this->subpage_titles = array ( 'language_front_set' => __('xili-language plugin','xili-language'). ', 2: ' . $this->xl_tabs['language_front_set']['label'],
		 	'language_expert' => __('xili-language plugin','xili-language'). ', 3: '  . $this->xl_tabs['language_expert']['label'],
		 	'language_files' => __('xili-language plugin','xili-language'). ', 4: '  . $this->xl_tabs['language_files']['label'],
		 	'language_support' => __('xili-language plugin','xili-language'). ', 5: '  . $this->xl_tabs['language_support']['label']
		 	);


		 $hooks = array(); // to prepare highlight those in tabs
		 $this->thehook2 = add_options_page($this->subpage_titles['language_front_set'], 'xl-front-end', 'manage_options', 'language_front_set', array( &$this, 'languages_frontend_settings' ) );
		 add_action('load-'.$this->thehook2, array(&$this,'on_load_page_set'));
		 $hooks[] = $this->thehook2;

		 $this->thehook4 = add_options_page($this->subpage_titles['language_expert'], 'xl-expert', 'manage_options', 'language_expert', array( &$this, 'languages_expert' ) );
		 add_action('load-'.$this->thehook4, array(&$this,'on_load_page_expert'));
		 $hooks[] = $this->thehook4;
		 // since 2.8.8
		 $this->thehook5 = add_options_page($this->subpage_titles['language_files'], __('Languages Files','xili-language'), 'manage_options', 'language_files', array( &$this, 'languages_files' ) );
		 add_action('load-'.$this->thehook5, array(&$this,'on_load_page_files'));
		 $hooks[] = $this->thehook5;

		 $this->thehook3 = add_options_page($this->subpage_titles['language_support'], 'xl-support', 'manage_options', 'language_support', array( &$this, 'languages_support' ) );
		 add_action('load-'.$this->thehook3, array(&$this,'on_load_page_support'));
		 $hooks[] = $this->thehook3;

		 // Fudge the highlighted subnav item when on a XL admin page - 2.8.2
		foreach( $hooks as $hook ) {
			add_action( "admin_head-$hook", array(&$this,'modify_menu_highlight' ));
		}

		 $this->insert_news_pointer ( 'xl_new_version' ); // pointer in menu for updated version

		 add_action( 'admin_print_footer_scripts', array(&$this, 'print_the_pointers_js') );

		 // create library of alert messages

		 $this->create_library_of_alert_messages ();
	}

	// to remove those visible in tabs - 2.8.2
	function admin_sub_menus_hide() { 
		remove_submenu_page( 'options-general.php', 'language_front_set' ); 
		remove_submenu_page( 'options-general.php', 'language_expert' );
		remove_submenu_page( 'options-general.php', 'language_files' );
		remove_submenu_page( 'options-general.php', 'language_support' ); 
	}

	// to recover title saved in $submenu and removed when hidding above ! 2.11.3
	function admin_recover_page_title ( $admin_title, $title ) {
		global $current_screen;
		$keys = array_keys ( $this->subpage_titles );
		if ( false !== strpos( $current_screen->base, 'settings_page_' )) {
			$indice = str_replace( 'settings_page_', '', $current_screen->base );
			if (in_array( $indice, $keys )) {
				$admin_title = $this->subpage_titles[$indice] . " " . $admin_title;
			}
		}
		return $admin_title;
	}

	// 2.8.2
	function modify_menu_highlight() {
		global $plugin_page, $submenu_file;

		// This tweaks the Tools subnav menu to only show one XD menu item
		if ( in_array( $plugin_page, array( 'language_expert', 'language_support', 'language_front_set', 'language_files' ) ) )
			$submenu_file = 'language_page';
	}

	// called by each pointer
	function insert_news_pointer ( $case_news ) {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer', false, array('jquery') );
			++$this->news_id;
			$this->news_case[$this->news_id] = $case_news;
	}
	// insert the pointers registered before
	function print_the_pointers_js (  ) {
		if ( $this->news_id != 0 ) {
			for ($i = 1; $i <= $this->news_id; $i++) {
				$this->print_pointer_js ( $i );
			}
		}


	}

	function print_pointer_js ( $indice  ) {  ;

		$args = $this->localize_admin_js( $this->news_case[$indice], $indice );
		if ( $args['pointerText'] != '' ) { // only if user don't read it before
		?>
		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function() {

 	var strings<?php echo $indice; ?> = <?php echo json_encode( $args ); ?>;

	<?php /** Check that pointer support exists AND that text is not empty - inspired www.generalthreat.com */ ?>

	if(typeof(jQuery().pointer) != 'undefined' && strings<?php echo $indice; ?>.pointerText != '') {
		jQuery( strings<?php echo $indice; ?>.pointerDiv ).pointer({
			content    : strings<?php echo $indice; ?>.pointerText,
			position: { edge: strings<?php echo $indice; ?>.pointerEdge,
				at: strings<?php echo $indice; ?>.pointerAt,
				my: strings<?php echo $indice; ?>.pointerMy,
				offset: strings<?php echo $indice; ?>.pointerOffset
			},
			close  : function() {
				jQuery.post( ajaxurl, {
					pointer: strings<?php echo $indice; ?>.pointerDismiss,
					action: 'dismiss-wp-pointer'
				});
			}
		}).pointer('open');
	}
});
		//]]>
		</script>
		<?php
		}
	}

	/**
	 * News pointer for tabs
	 *
	 * @since 2.6.2
	 *
	 */
	function localize_admin_js( $case_news, $news_id ) {
 			$about = __('Docs about xili-language', 'xili-language');
 			$changelog = __('Changelog tab of xili-language', 'xili-language');
 			$pointer_Offset = '';
 			$pointer_edge = '';
 			$pointer_at = '';
 			$pointer_my = '';
 		switch ( $case_news ) {

 			case 'xl_new_version' :
 				$pointer_text = '<h3>' . esc_js( __( 'xili-language updated', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( sprintf( __( 'xili-language was updated to version %s', 'xili-language' ) , XILILANGUAGE_VER) ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf( __('This version %s starts to use final WP 3.9. See also %s.','xili-language') , XILILANGUAGE_VER, '<a href="http://wordpress.org/plugins/xili-language/changelog/" title="'.$changelog.'" >'.$changelog.'</a>') ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf( __('This version %s improves style size in Translation metabox.','xili-language') , XILILANGUAGE_VER, '<a href="http://wordpress.org/plugins/xili-language/changelog/" title="'.$changelog.'" >'.$changelog.'</a>') ). '</p>';

				$pointer_text .= '<p>' . esc_js( sprintf( __('Previous version before v. %s introduces new functions like is_xili_curlang() to test current language in theme.','xili-language') , XILILANGUAGE_VER) ). '</p>';

				$pointer_text .= '<p>' . esc_js( __( 'See settings submenu', 'xili-language' ).' “<a href="options-general.php?page=language_page">'. __('Languages ©xili','xili-language')."</a>”" ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-new-version-'.str_replace('.', '-', XILILANGUAGE_VER);
 				$pointer_div = '#menu-settings';

 				$pointer_edge = 'left';
 				$pointer_my = 'left';
 				$pointer_at = 'right';
				break;


			case 'languages_settings':
				$pointer_text = '<h3>' . esc_js( __( 'To define languages', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( __( 'This screen is designed to define the list of languages assigned to this website. Use the form below to add a new language with the help of preset list (popup) or by input your own ISO code.', 'xili-language' ) ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-settings-news';
 				$pointer_div = '#xili-language-lang-list';

				break;

			case 'languages_frontend_settings':
				$pointer_text = '<h3>' . esc_js( __( 'To define front-page', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( __( 'This screen contains selectors to define the behaviour of frontpage according languages and visitors browser.', 'xili-language' ) ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-frontend-news';
 				$pointer_div = '#post-body-content';

				break;
			case 'languages_theme_infos':
				$pointer_text = '<h3>' . esc_js( __( 'Infos about current theme', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( __( 'This metabox contains infos about the theme and the joined available language files (.mo).', 'xili-language' ) ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-frontend-theme-news';
 				$pointer_div = '#xili-language-sidebox-theme';

 				$pointer_edge = 'right';
 				$pointer_my = 'left';
 				$pointer_at = 'left';
				break;

			case 'languages_expert':
				$pointer_text = '<h3>' . esc_js( __( 'For documented webmaster', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( __( 'This screen contains nice selectors and features to customize menus and other objects for your CMS multilingual website.', 'xili-language' ) ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-expert-news';
 				$pointer_div = '#post-body-content';

				break;
			case 'languages_expert_special':
				$pointer_text = '<h3>' . esc_js( __( 'For documented webmaster', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( __( 'This metabox contains advanced selectors and features to customize behaviours, style and other objects like widgets for your CMS multilingual website.', 'xili-language' ) ). '</p>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-expert-special-news';
 				$pointer_div = '#xili-language-sidebox-special';

 				$pointer_edge = 'right';
 				$pointer_my = 'right top';
 				$pointer_at = 'left top';
				break;

			case 'languages_support':
				$pointer_text = '<h3>' . esc_js( __( 'In direct with support', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Before to question dev.xiligroup support, do not forget to check needed website infos and to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-support-news';
 				$pointer_div = '#post-body-content';

				break;

			case 'media_language':
				$pointer_text = '<h3>' . esc_js( __( 'Language of media', 'xili-language') ) . '</h3>';
				$pointer_text .= '<p>' . esc_js( sprintf(__( 'Language concern title, caption and description of media. With clonage approach, the file is shared between version for each language. When modifying a media, new fields are available at end of form. Before to assign language to media, do not forget to visit %s documentation', 'xili-language' ), '<a href="http://wiki.xiligroup.org" title="'.$about.'" >wiki</a>' ) ). '</p>';
 				$pointer_dismiss = 'xl-media-upload';
 				$pointer_div = '#language';
 				$pointer_edge = 'right';
 				$pointer_my = 'right top';
 				$pointer_at = 'left top';

				break;

			default: // nothing
				$pointer_text = '';
			}

 			// inspired from www.generalthreat.com
		// Get the list of dismissed pointers for the user
		$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		if ( in_array( $pointer_dismiss, $dismissed ) && $pointer_dismiss == 'xl-new-version-'.str_replace('.', '-', XILILANGUAGE_VER) ) {
			$pointer_text = '';

		} elseif ( in_array( $pointer_dismiss, $dismissed )  ) {
			$pointer_text = '';
		}

		return array(
			'pointerText' => html_entity_decode( (string) $pointer_text, ENT_QUOTES, 'UTF-8'),
			'pointerDismiss' => $pointer_dismiss,
			'pointerDiv' => $pointer_div,
			'pointerEdge' => ( '' == $pointer_edge ) ? 'top' : $pointer_edge ,
			'pointerAt' => ( '' == $pointer_at ) ? 'left top' : $pointer_at ,
			'pointerMy' => ( '' == $pointer_my ) ? 'left top' : $pointer_my ,
			'pointerOffset' => $pointer_Offset,
			'newsID' => $news_id
		);
    }

	/**
	 * Create list of messages
	 * @since 2.6.3
	 *
	 */
	function create_library_of_alert_messages() {

		$this->admin_messages['alert']['default'] = sprintf(__('See %sWiki%s for more details','xili-language'),'<a href="'.$this->wikilink.'">' ,'</a>');
		$this->admin_messages['alert']['no_load_function'] = sprintf(__('CAUTION: no load_theme_textdomain() in functions.php - review the content of file in the current theme or choose another canonical theme. %s','xili-language'), $this->admin_messages['alert']['default'] ) ;
		$this->admin_messages['alert']['no_load_function_child'] = sprintf(__('CAUTION: no load_theme_textdomain() in functions.php of child theme - review the content of file in the current child theme or leave as is to use only parent theme translation file. %s','xili-language'), $this->admin_messages['alert']['default'] ) ;
		$this->admin_messages['alert']['no_domain_defined'] = __('Theme domain NOT defined','xili-language');

		$this->admin_messages['alert']['menu_auto_inserted'] = sprintf(__('Be aware that language list is already automatically inserted (see above) and %s','xili-language'), $this->admin_messages['alert']['default'] ) ;

		if ( is_multisite() ) {
			$this->admin_messages['alert']['plugin_deinstalling'] = sprintf(__('CAUTION: If checked below, during deactivating xili-language plugin, ALL the xili-language datas in database will be definitively ERASED !!! (only multilingual features on <strong>this</strong> website of the WP network (multisite) install). %s', 'xili-language'), $this->admin_messages['alert']['default'] ) ;
		} else {
			$this->admin_messages['alert']['plugin_deinstalling'] = sprintf(__('CAUTION: When checking below, after deactivating xili-language plugin, if delete it through plugins list, ALL the xili-language datas in database will be definitively ERASED !!! (only multilingual features). %s', 'xili-language'), $this->admin_messages['alert']['default'] ) ;
		}

		$this->admin_messages['alert']['erasing_language'] = __('Erase (only) multilingual features of concerned posts when this language will be erased !','xili-language');

	}

	/**
	 * Manage list of languages
	 * @since 0.9.0
	 */
	function on_load_page() {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');
			add_meta_box('xili-language-sidebox-theme', __('Current theme infos','xili-language'), array(&$this,'on_sidebox_4_theme_info'), $this->thehook , 'side', 'high');
			add_meta_box('xili-language-sidebox-msg', __('Message','xili-language'), array(&$this,'on_sidebox_msg_content'), $this->thehook , 'side', 'core');
			add_meta_box('xili-language-sidebox-info', __('Info','xili-language'), array(&$this,'on_sidebox_info_content'), $this->thehook , 'side', 'core');

			// if ( !is_multisite() ) 2.8.8
			  add_meta_box('xili-language-sidebox-uninstall', __('Uninstall Options','xili-language'), array(&$this,'on_sidebox_uninstall_content'), $this->thehook , 'side', 'low');

			$this->insert_news_pointer ( 'languages_settings' ); // news pointer 2.6.2

	}

	/**
	 * Manage settings of languages behaviour in front-end (theme)
	 * @since 2.4.1
	 */
	function on_load_page_set() {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');

			add_meta_box('xili-language-sidebox-theme', __('Current theme infos','xili-language'), array(&$this,'on_sidebox_4_theme_info'), $this->thehook2 , 'side', 'high');
			add_meta_box('xili-language-sidebox-info', __('Info','xili-language'), array(&$this,'on_sidebox_info_content'), $this->thehook2 , 'side', 'core');

			$this->insert_news_pointer ( 'languages_frontend_settings' ); // news pointer 2.6.2
			$this->insert_news_pointer ( 'languages_theme_infos' );
	}

	/**
	 * Settings by experts and info
	 * @since 2.4.1
	 */
	function on_load_page_expert() {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');

			add_meta_box('xili-language-sidebox-theme', __('Current theme infos','xili-language'), array(&$this,'on_sidebox_4_theme_info'), $this->thehook4 , 'side', 'high');

			add_meta_box('xili-language-sidebox-special', __('Special','xili-language'), array(&$this,'on_sidebox_for_specials'), $this->thehook4 , 'side', 'core');
			add_meta_box('xili-language-sidebox-info', __('Info','xili-language'), array(&$this,'on_sidebox_info_content'), $this->thehook4 , 'side', 'core');

			$this->insert_news_pointer ( 'languages_expert' ); // news pointer 2.6.2
			$this->insert_news_pointer ( 'languages_expert_special' );
	}

	/**
	 * Settings by experts and info
	 * @since 2.4.1
	 */
	function on_load_page_files() {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');

			add_meta_box('xili-language-sidebox-theme', __('Current theme infos','xili-language'), array(&$this,'on_sidebox_4_theme_info'), $this->thehook5 , 'side', 'high');

			add_meta_box('xili-language-sidebox-info', __('Info','xili-language'), array(&$this,'on_sidebox_info_content'), $this->thehook5 , 'side', 'core');

			//$this->insert_news_pointer ( 'languages_expert' ); // news pointer 2.6.2
			//$this->insert_news_pointer ( 'languages_expert_special' );
	}


	/**
	 * Support and info
	 * @since 2.4.1
	 */
	function on_load_page_support() {
			wp_enqueue_script('common');
			wp_enqueue_script('wp-lists');
			wp_enqueue_script('postbox');

			add_meta_box('xili-language-sidebox-info', __('Info','xili-language'), array(&$this,'on_sidebox_info_content'), $this->thehook3 , 'side', 'core');

			$this->insert_news_pointer ( 'languages_support' ); // news pointer 2.6.2
	}


	/******************************** Main Settings screens *************************/

	/**
	 * to display the languages settings admin UI
	 *
	 * @since 0.9.0
	 * @updated 0.9.6 - only for WP 2.7.X - do new meta boxes and JS
	 *
	 */
	function languages_settings() {

		$formtitle = __('Add a language', 'xili-language'); /* translated in form */
		$submit_text = __('Add &raquo;','xili-language');
		$cancel_text = __('Cancel');
		$action = '';
		$actiontype = '';
		$language = (object) array ('name' => '', 'slug' => '', 'description' => '', 'term_order' => '' ); //2.2.3


		$msg = 0 ; /* 1.7.1 */
		if (isset($_POST['reset'])) {
			$action =$_POST['reset'];
		} elseif ( isset($_POST['updateoptions']) ) {
			$action ='updateoptions';
		} elseif ( isset($_POST['updateundefined'])) {
			$action ='updateundefined';
		} elseif ( isset($_POST['menuadditems'])) {
			$action ='menuadditems';
		} elseif ( isset($_POST['sendmail']) ) { //1.8.5
			$action = 'sendmail' ;
		} elseif ( isset($_POST['uninstalloption']) ) { //1.8.8
			$action = 'uninstalloption' ;
		} elseif ( isset($_POST['action'])) {
			$action=$_POST['action'];
		}

		if (isset($_GET['action'])) :
			$action=$_GET['action'];
			$term_id = $_GET['term_id'];
		endif;
		$message = $action ;

		$theme_name = get_option("current_theme"); // full name

		switch( $action ) {

			case 'uninstalloption' ; // 1.8.8 see Uninstall Options metabox in sidebar
				$this->xili_settings['delete_settings'] = $_POST['delete_settings'];
				update_option('xili_language_settings', $this->xili_settings);
				break;

			case 'add';
				check_admin_referer( 'xili-language-settings' );
				$term = $_POST['language_name'];
				if ("" != $term ) {
					$slug = $_POST['language_nicename'];
					$args = array( 'alias_of' => '', 'description' => $_POST['language_description'], 'parent' => 0, 'slug' =>$slug );

				    $theids = $this->safe_lang_term_creation ( $term, $args );

					if ( ! is_wp_error($theids) ) {

						wp_set_object_terms($theids['term_id'], 'the-langs-group', TAXOLANGSGROUP);
				    	update_term_order ($theids['term_id'],$this->langs_group_tt_id,$_POST['language_order']);

				    	$this->xili_settings['langs_list_status'] = "added"; // 1.6.0
				    	$lang_ids = $this->get_lang_ids();
				    	//$this->available_langs = $lang_ids ;
				    	$this->xili_settings['available_langs'] = $lang_ids;
				    	$this->xili_settings['lang_features'][$slug]['hidden'] = ( isset($_POST['language_hidden']) ) ? $_POST['language_hidden'] : "" ;
				    	$this->xili_settings['lang_features'][$slug]['charset'] = ( isset($_POST['language_charset'])) ? $_POST['language_charset'] : "";
				    	$this->xili_settings['lang_features'][$slug]['alias'] = ( isset($_POST['language_alias'])) ? $_POST['language_alias'] : ""; // 2.8.2
				    	$this->xili_settings['theme_alias_cache'][$theme_name][$slug] = ( isset($_POST['language_alias'])) ? $_POST['language_alias'] : "";
						update_option('xili_language_settings', $this->xili_settings);

						$this->get_lang_slug_ids('edited'); // flush - 2.9.21
				    	$actiontype = "add";
				    	$message .= " - ".__('A new language was added.','xili-language');
				    	$msg = 5;
					} else {
						$message .= " error type = " . $theids->get_error_message() . " with slug (". $slug .")"; //2.4
						$msg = 10;
					}
				} else {
						$message .= " error type = empty name"; //2.4
						$msg = 10;
				}
			    break;

			case 'edit';
				// check id
				if ( isset ($_GET['term_id']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'edit-' . $_GET['term_id'] ) ) {
				    $actiontype = "edited";
				    $language = get_term_and_order ($term_id,$this->langs_group_tt_id,TAXONAME);
				    $submit_text = __('Update &raquo;', 'xili-language');
				    $formtitle = __('Edit language', 'xili-language');
				    $message .= " - ".__('Language to update.','xili-language');
				    $msg = 3;

				} else {
					wp_die( __( 'Security check', 'xili-language' ) );
				}
			    break;

			case 'edited';
				check_admin_referer( 'xili-language-settings' );
			    $actiontype = "add";
			    $term_id = $_POST['language_term_id'];
			    $term = $_POST['language_name']; // 2.4
			    $slug = $_POST['language_nicename'];
				$args = array( 'name' => $term, 'alias_of' => '', 'description' => $_POST['language_description'], 'parent' => 0, 'slug' => $slug);
				$theids = wp_update_term( $term_id, TAXONAME, $args);
				if ( !is_wp_error($theids) ) {
					wp_set_object_terms($theids['term_id'], 'the-langs-group', TAXOLANGSGROUP);
					update_term_order ($theids['term_id'],$this->langs_group_tt_id,$_POST['language_order']);
					$this->xili_settings['langs_list_status'] = "edited"; // 1.6.0
					$this->xili_settings['lang_features'][$slug]['hidden'] = ( isset ( $_POST['language_hidden'] ) ) ? $_POST['language_hidden'] : "";
				    $this->xili_settings['lang_features'][$slug]['charset'] = $_POST['language_charset'];

				    $this->xili_settings['lang_features'][$slug]['alias'] = ( isset($_POST['language_alias'])) ? $_POST['language_alias'] : ""; // 2.8.2
				    $this->xili_settings['theme_alias_cache'][$theme_name][$slug] = ( isset($_POST['language_alias'])) ? $_POST['language_alias'] : "";

					update_option('xili_language_settings', $this->xili_settings);

					$this->get_lang_slug_ids('edited');
					$message .= " - ".__('A language was updated.','xili-language');
					$msg = 4 ;
				} else {
					$msg = 8 ;
					$message .= " error type = ".$theids->get_error_code(); //2.4
				}
			    break;

			case 'delete';
				// check id
				if ( isset ($_GET['term_id']) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete-' . $_GET['term_id'] ) ) {

				    $actiontype = "deleting";
				    $submit_text = __('Delete &raquo;','xili-language');
				    $formtitle = __('Delete language ?', 'xili-language');
				    $language = get_term_and_order ($term_id,$this->langs_group_tt_id,TAXONAME);
				    $message .= " - ".__('A language to delete.','xili-language');
				    $msg = 1;

				} else {
					wp_die( __( 'Security check', 'xili-language' ) );
				}
				break;
			case 'deleting';
				check_admin_referer( 'xili-language-settings' );
			    $actiontype = "add";
			    $term_id = $_POST['language_term_id'];
			    $slug = $_POST['language_nicename'];
			    if ( isset ( $_POST['multilingual_links_erase'] ) && $_POST['multilingual_links_erase'] == 'erase' ) {
			    	$this->multilingual_links_erase ( $term_id ); // as in uninstall.php - 1.8.8
			    }

			    wp_delete_object_term_relationships( $term_id, TAXOLANGSGROUP ); // degrouping
			    wp_delete_term( $term_id, TAXONAME );

			    $this->xili_settings['langs_list_status'] = "deleted"; // 1.6.0
			    $lang_ids = $this->get_lang_ids();
				//$this->available_langs = $lang_ids ;
				$this->xili_settings['available_langs'] = $lang_ids;
				unset ( $this->xili_settings['lang_features'][$slug] );
				update_option('xili_language_settings', $this->xili_settings);
			    $message .= " - ".__('A language was deleted.','xili-language');
			    $msg = 2;
			    break;

			case 'reset';
			    $actiontype = "add";
			    break;

			default :
			    $actiontype = "add";
			    $message .= ' '.__('Find above the list of languages.','xili-language');


		}
		/* register the main boxes always available */
		add_meta_box('xili-language-lang-list', __('List of languages','xili-language'), array(&$this,'on_box_lang_list_content'), $this->thehook , 'normal', 'high');
		add_meta_box('xili-language-lang-form', __('Language','xili-language'), array(&$this,'on_box_lang_form_content'), $this->thehook , 'normal', 'high');

		$themessages[1] = __('A language to delete.','xili-language');
		$themessages[2] = __('A language was deleted.','xili-language');
		$themessages[3] = __('Language to update.','xili-language');
		$themessages[4] = __('A language was updated.','xili-language');
		$themessages[5] = __('A new language was added.','xili-language');
		$themessages[8] = __('Error when updating.','xili-language');
		$themessages[10] = __('Error when adding.','xili-language');

		/* form datas in array for do_meta_boxes() */
		$language_features = ( isset( $this->xili_settings['lang_features'][$language->slug] ) && '' != $language->slug ) ? $this->xili_settings['lang_features'][$language->slug] : array('charset'=>"",'hidden'=>"");

		$data = array(
			'message'=>$message, 'action'=>$action, 'formtitle'=>$formtitle, 'language'=>$language,'submit_text'=>$submit_text,'cancel_text'=>$cancel_text,
			'language_features' => $language_features
		);
		?>

		<div id="xili-language-settings" class="wrap columns-2 minwidth" >
			<?php screen_icon('options-general'); ?>
			<h2><?php _e('Languages','xili-language') ?></h2>
			<h3 class="nav-tab-wrapper">
			<?php $this->set_tabs_line() ?>
			</h3>

			<?php if (0!= $msg ) { ?>
			<div id="message" class="updated fade"><p><?php echo $themessages[$msg]; ?></p></div>
			<?php } ?>
			<form name="add" id="add" method="post" action="options-general.php?page=language_page">
				<input type="hidden" name="action" value="<?php echo $actiontype ?>" />
				<?php wp_nonce_field('xili-language-settings'); ?>
				<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
				$this->setting_form_content( $this->thehook, $data );
				?>
		</form>
		</div>
		<?php $this->setting_form_js( $this->thehook );
	}

	/**
	 * Settings page for front-end features
	 *
	 * @since 2.4.1
	 */
	function languages_frontend_settings() {

		$msg = 0;
		$themessages = array('ok');
		$action = '';
		$optionmessage = '';

		if (isset($_POST['reset'])) {
			$action =$_POST['reset'];
		} elseif ( isset($_POST['updateoptions']) ) {
			$action ='updateoptions';
		} elseif ( isset($_POST['updateundefined'])) {
			$action ='updateundefined';

		} elseif ( isset($_POST['action'])) {
			$action=$_POST['action'];
		}

		if (isset($_GET['action'])) :
			$action=$_GET['action'];
			$term_id = $_GET['term_id'];
		endif;
		$message = $action ;


		switch( $action ) {

			case 'updateundefined'; // gold options
				check_admin_referer( 'xili-language-frontsettings' );
				if ( function_exists('xiliml_setlang_of_undefined_posts') ) {
					$targetlang = $_POST['xili_language_toset'];
					$fromcats = $_POST['from_categories'];
					if (""!= $targetlang) {
						$q = xiliml_setlang_of_undefined_posts ($targetlang, $fromcats, 50);
						$message .= " _ $q ".__('posts are set in:','xili-language')." ".$targetlang." ".__("category")." =[$fromcats]";
					} else {
						$q = xiliml_setlang_of_undefined_posts ($targetlang, $fromcats, 50);
						$message .= " _ around $q ".__('posts are undefined in','xili-language')." ".__("category")."  = [$fromcats]";
					}
				}

				break;

			case 'updateoptions'; // sidebox 3 - below in source
				check_admin_referer( 'xili-language-frontsettings' );
				$this->xili_settings['browseroption'] = ( isset($_POST['xili_language_check_option'] ) ) ? $_POST['xili_language_check_option'] : "";
				$this->xili_settings['authorbrowseroption'] = ( isset($_POST['xili_language_check_option_author'] ) ) ? $_POST['xili_language_check_option_author'] : "";
				$this->xili_settings['functions_enable'] = ( isset($_POST['xili_language_check_functions_enable'] ) ) ?$_POST['xili_language_check_functions_enable'] : "";
				//$this->xili_settings['browseroption'] = $this->browseroption;
				$this->xili_settings['allcategories_lang'] = ( isset($_POST['allcategories_lang'] ) ) ?$_POST['allcategories_lang'] : ""; // 1.8.9.1
				$this->xili_settings['lang_neither_browser'] = ( isset($_POST['xili_lang_neither_browser'] ) ) ? $_POST['xili_lang_neither_browser'] : ""; // 2.3.1
				//$this->xili_settings['lang_neither_browser'] = $this->lang_neither_browser ;
				//$this->xili_settings['authorbrowseroption'] = $this->authorbrowseroption;

				$this->xili_settings['widget'] = ( isset($_POST['xili_language_widgetenable'] ) ) ? $_POST['xili_language_widgetenable'] : ""; //1.8.8
				$this->xili_settings['homelang'] = ( isset($_POST['xili_language_home_lang'] ) ) ? $_POST['xili_language_home_lang'] : ""; // 1.3.2
				$this->xili_settings['pforp_select'] = ( isset($_POST['xili_language_pforp_select'] ) ) ?$_POST['xili_language_pforp_select'] : ""; // 2.8.4 - page_for_posts sub-selection - no currently visible
				/* since 1.8.0 */
				$types = get_post_types(array('show_ui'=>1));
				if ( count($types) > 2 ) {
					$desc_customs = $this->get_custom_desc() ;
					if ( count($desc_customs) > 0 ) {
						foreach ( $desc_customs as $type => $desc_custom) {
							if ( isset($_POST['xili_language_multilingual_custom_'.$type]) ) $desc_customs[$type]['multilingual'] = $_POST['xili_language_multilingual_custom_'.$type];
						}
						$this->xili_settings['multilingual_custom_post'] = $desc_customs ;
					} else {
						$this->xili_settings['multilingual_custom_post'] = array() ;
					}
				} else {
					$this->xili_settings['multilingual_custom_post'] = array() ;
				}
				/* widget settings */
				if ( current_theme_supports( 'widgets' ) ) {
					$link_cats = get_terms( 'link_category');
					$this->xili_settings['link_categories_settings']['all'] = ( isset($_POST['xili_language_link_cat_all'] ) && $_POST['xili_language_link_cat_all'] == 'enable'  ) ? true : false ;
					foreach ( $link_cats as $link_cat ) {
						$this->xili_settings['link_categories_settings']['category'][$link_cat->term_id] = (( isset($_POST['xili_language_link_cat_'.$link_cat->term_id] ) && $_POST['xili_language_link_cat_'.$link_cat->term_id] == 'enable'  ) ? true : false );
					}
				}

				/* UPDATE OPTIONS */
				update_option('xili_language_settings', $this->xili_settings);

				/* messages */
				$optionmessage .= " - ".sprintf(__("Options are updated: home language = %s, For Author language of a new post = %s, xilidev functions = %s ",'xili-language'), $this->xili_settings['browseroption'], $this->xili_settings['authorbrowseroption'], $this->xili_settings['functions_enable'] );
				$message .= $optionmessage ;
				$msg = 1;
				$this->insert_gold_functions ();

				break;

		}
		add_meta_box('xili-language-box-2', __('Settings','xili-language'), array(&$this,'on_box_frontend'), $this->thehook2 , 'normal', 'high');

		$themessages[1] = $optionmessage ;

		$data = array(
			'message'=>$message, 'action'=>$action,
			'browseroption'=>$this->xili_settings['browseroption'],
			'authorbrowseroption'=>$this->xili_settings['authorbrowseroption'] ,
			'functions_enable'=>$this->xili_settings['functions_enable'],
		);

		?>
		<div id="xili-language-frontsettings" class="wrap columns-2 minwidth">
			<?php screen_icon('options-general'); ?>
			<h2><?php _e('Languages','xili-language') ?></h2>
			<h3 class="nav-tab-wrapper">
			<?php $this->set_tabs_line() ?>
			</h3>

			<?php if (0!= $msg ) { ?>
			<div id="message" class="updated fade"><p><?php echo $themessages[$msg]; ?></p></div>
			<?php } ?>
			<form name="add" id="add" method="post" action="options-general.php?page=language_front_set">
				<?php wp_nonce_field('xili-language-frontsettings');
				wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
				wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
				$this->setting_form_content( $this->thehook2, $data );
			?>
			</form>
		</div>
		<?php $this->setting_form_js( $this->thehook2 );
	}

	/**
	 * Support page
	 *
	 * @since 2.4.1
	 */
	function languages_expert() {

		$msg = 0;
		$themessages = array('ok');
		$action = '';
		$message = '';
		$optionmessage = '';

		if (isset($_POST['reset'])) {
			$action =$_POST['reset'];
		} elseif ( isset($_POST['menuadditems'])) {
			$action ='menuadditems';
		} elseif ( isset($_POST['updatespecials'])) {
			$action ='updatespecials';
		} elseif ( isset($_POST['innavenable']) || isset($_POST['pagnavenable']) ) {
			$action ='menunavoptions';
		} elseif ( isset($_POST['jetpack_fc_enable'])) {
			$action ='jetpack_fc_enable';
		}

		switch( $action ) {
			case 'menuadditems';
				check_admin_referer( 'xili-language-expert' );
				$this->xili_settings['navmenu_check_option2'] = $_POST['xili_navmenu_check_option2']; // 1.8.1

				$result = $this->add_list_of_language_links_in_wp_menu($this->xili_settings['navmenu_check_option2']);
				$optionmessage .= ' - '. __('Go to Nav-menus in Themes to validate changes','xili-language').' ('.$result.')';
				$message .= $optionmessage ;
				$msg = 1;

				break;

			case 'menunavoptions';
				check_admin_referer( 'xili-language-expert' );
				if ( current_theme_supports( 'menus' ) ) {
					$menu_locations = get_nav_menu_locations();
					$selected_menu_locations = array();
					if ( $menu_locations ) {
						$pagenablelist = '';
						foreach ($menu_locations as $menu_location => $location_id) {
							if ( isset ( $_POST['xili_navmenu_check_option_'.$menu_location] ) && $_POST['xili_navmenu_check_option_'.$menu_location] == 'enable' ) {
								$selected_menu_locations[$menu_location]['navenable'] = 'enable';
								$selected_menu_locations[$menu_location]['navtype'] = $_POST['xili_navmenu_check_optiontype_'.$menu_location]; //0.9.1
							}
							// page list in array 2.8.4.3
							$enable = ( isset ( $_POST['xili_navmenu_check_option_page_'.$menu_location] ) && $_POST['xili_navmenu_check_option_page_'.$menu_location] == 'enable' ) ? 'enable' : '' ;
							$pagenablelist .= $enable;
							$args = $_POST['xili_navmenu_page_args_'.$menu_location];
							$thenewvalue = array( 'enable'=> $enable, 'args'=> $args );
							$this->xili_settings['array_navmenu_check_option_page'][$menu_location] = $thenewvalue;
						}

						$this->xili_settings['page_in_nav_menu_array'] = $pagenablelist ;

					} else {
						$optionmessage = '<strong>'.__('Locations menu not set: go to menus settings','xili-language').'</strong> ';
					}
					$this->xili_settings['navmenu_check_options'] = $selected_menu_locations; // 2.1.0

					$this->xili_settings['in_nav_menu'] = ( isset($_POST['list_in_nav_enable'] ) ) ? $_POST['list_in_nav_enable'] : ""; // 1.6.0
					//$this->xili_settings['page_in_nav_menu'] = ( isset($_POST['page_in_nav_enable'] ) ) ? $_POST['page_in_nav_enable'] : ""; // 1.7.1
					//$this->xili_settings['args_page_in_nav_menu'] = ( isset($_POST['args_page_in_nav'] ) ) ? $_POST['args_page_in_nav'] : ""; // 1.7.1

					$this->xili_settings['nav_menu_separator'] = stripslashes($_POST['nav_menu_separator']) ;

					$this->xili_settings['navmenu_check_option'] = ( isset($_POST['xili_navmenu_check_option'] ) ) ? $_POST['xili_navmenu_check_option'] : "";
					$this->xili_settings['list_pages_check_option'] = ( isset($_POST['xili_list_pages_check_option'] ) ) ? $_POST['xili_list_pages_check_option'] : ""; // 2.8.4.4

					// new method if more than one nav-menu 2.8.4.3

					$this->xili_settings['home_item_nav_menu'] = ( isset($_POST['xili_home_item_nav_menu'] ) ) ?$_POST['xili_home_item_nav_menu'] : ""; // 1.8.9.2
				// 1.8.1
				}
				/* UPDATE OPTIONS */
				update_option('xili_language_settings', $this->xili_settings);
				/* messages */
				$optionmessage .= " - ".sprintf(__("Options are updated: Automatic Nav Menu = %s, Selection of pages in Nav Menu = %s",'xili-language'), $this->xili_settings['in_nav_menu'], $this->xili_settings['page_in_nav_menu']);
				$message .= $optionmessage ;
				$msg = 1;


				break;

			case 'updatespecials':

				/* force rules flush - 2.1.1 */
				if ( isset($_POST['force_permalinks_flush'] ) && $_POST['force_permalinks_flush'] == 'enable' ) {
					$this->get_lang_slug_ids('edited'); // if list need refresh - 2013-11-24

				}
				/* domains switching settings 1.8.7 */
				foreach ( $this->xili_settings['domains'] as $domain => $state ) {
					if ( isset($_POST['xili_language_domains_'.$domain] ) && $_POST['xili_language_domains_'.$domain] == 'enable' ) {
						$this->xili_settings['domains'][$domain] = 'enable';
					} else {
						$this->xili_settings['domains'][$domain] = '';
					}
				}
				// 2.4.0
				$this->xili_settings['wp_locale'] = ( isset($_POST['xili_language_wp_locale'] ) ) ? $_POST['xili_language_wp_locale'] : "db_locale";
				$this->xili_settings['creation_redirect'] = ( isset($_POST['xili_language_creation_redirect'] ) ) ? $_POST['xili_language_creation_redirect'] : "";
				// xili_language_exists_style_ext on off
				$this->xili_settings['external_xl_style'] = ( isset($_POST['xili_language_external_xl_style'] ) ) ? $_POST['xili_language_external_xl_style'] : "off";
				/* UPDATE OPTIONS */
				update_option('xili_language_settings', $this->xili_settings);
				/* messages */
				$optionmessage .= " - ".sprintf(__("Options are updated %s ",'xili-language'), $this->xili_settings['wp_locale']);
				$message .= $optionmessage ;
				$msg = 1;

				break;

			case 'jetpack_fc_enable':
				check_admin_referer( 'xili-language-expert' );
				if ( isset($_POST['enable_fc_theme_class'] ) && $_POST['enable_fc_theme_class'] == 'enable' ) {
					$this->xili_settings['enable_fc_theme_class'] = 'enable';
				} else {
					$this->xili_settings['enable_fc_theme_class'] = 'disable';
				}
				update_option('xili_language_settings', $this->xili_settings);
				$optionmessage = sprintf(__("Settings for JetPack are updated to ‘%s’.",'xili-language'), $this->xili_settings['enable_fc_theme_class']);
				$msg = 1; // green
			break;
		}

		add_meta_box('xili-language-box-3', __('Navigation menus','xili-language'), array(&$this,'on_box_expert'), $this->thehook4 , 'normal', 'high');
		add_meta_box('xili-language-box-3-2', __('Special settings (JetPack,...)','xili-language'), array(&$this,'on_box_plugins_expert'), $this->thehook4 , 'normal', 'high');
		$themessages[1] = $optionmessage ;

		$data = array(
			'message'=>$message, 'action'=>$action, 'list_in_nav_enable' => $this->xili_settings['in_nav_menu']
			);
		?>
		<div id="xili-language-support" class="wrap columns-2 minwidth">
			<?php screen_icon('options-general'); ?>
			<h2><?php _e('Languages','xili-language') ?></h2>
			<h3 class="nav-tab-wrapper">
			<?php $this->set_tabs_line() ?>
			</h3>

			<?php if (0!= $msg ) { ?>
			<div id="message" class="updated fade"><p><?php echo $themessages[$msg]; ?></p></div>
			<?php } ?>
			<form name="add" id="add" method="post" action="options-general.php?page=language_expert">
				<?php wp_nonce_field('xili-language-expert'); ?>
				<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );
				$this->setting_form_content( $this->thehook4, $data );
			?>
			</form>
		</div>
		<?php $this->setting_form_js( $this->thehook4 );

	}


	/**
	 * Navigation menu: new ways to insert language list via menu builder screen
	 *
	 * @since 2.8.8
	 *
	 */
	function add_language_nav_menu_meta_boxes() {
     	add_meta_box(
     		'insert-xl-list',
     		sprintf ( __('%s Languages list', 'xili-language'), '[©xili]'),
     		array( $this, 'language_nav_menu_link'),
     		'nav-menus',
     		'side',
     		'high'
     	);
     }

	// called by above filter
	function language_nav_menu_link() {
			// div id in submit below
		global $_nav_menu_placeholder, $nav_menu_selected_id;
		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
			?>
        	<div id="posttype-xllist" class="posttypediv">
        		<div id="tabs-panel-xllist" class="tabs-panel tabs-panel-active">
        			<ul id ="xllist-checklist" class="categorychecklist form-no-clear">

        				<?php
        				foreach ($this->langs_list_options as $typeoption) {
							if ( false !== strpos( $typeoption[0], 'navmenu' ) ) {
        				// list according available types of menu
        				?>
        				<li>
        					<label  title="<?php echo $typeoption[2]; ?>" class="menu-item-title">
        						<input type="radio" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="-1">&nbsp;<?php _e( $typeoption[1], 'xili-language' ); ?>
        					</label>
        					<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
        					<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo $this->insertion_point_box_title; ?>">
        					<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo $this->insertion_point_dummy_link; ?>">
        					<input type="hidden" class="menu-item-attr-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-attr-title]" value="<?php echo $typeoption[2]; ?>">
        					<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="xl-list <?php echo $typeoption[0]; ?>">
        				</li>
        				<?php } } ?>

        			</ul>
        		</div>
        		<p class='description'><?php _e( 'Check to decide what type of languages menu. Only an insertion point will be placed inside the menu. The content of the language list will be automatically made according navigation rules and contexts.', 'xili-language' ); ?></p>
        		<p class="button-controls">
        			<span class="list-controls">

        			</span>
        			<span class="add-to-menu">
        				<input type="submit" <?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-post-type-menu-item" id="submit-posttype-xllist">
        				<span class="spinner"></span>
        			</span>
        		</p>
        	</div>
        <?php
    }

    // prepares javascript to modify the languages list menu item
	function admin_enqueue_menu_script() {
		$screen = get_current_screen();
		if ('nav-menus' != $screen->base)
			return;

		$suffix = defined( 'WP_DEBUG') && WP_DEBUG ? '.dev' : '.min';
		wp_enqueue_script( 'xllist_nav_menu', plugin_dir_url ( $this->file_file ) .'js/nav-menu'.$suffix.'.js', array('jquery'), XILILANGUAGE_VER );

		$data = array ();
		$data['strings'][0] = $this->insertion_point_box_title;
		$data['strings'][1] = esc_js( __('The languages list will be inserted here and :','xili-language') );
		$data['strings'][2] = esc_js( __(' (Hidden input items below will be used for live menu generating.)','xili-language') );

		$data['strings'][3] = $this->insertion_point_box_title_page;
		$data['strings'][4] = esc_js( __('The list of a sub-selection of pages will be inserted here according current language of webpage.','xili-language') );
		$data['strings'][5] = esc_js( __('This is an experimental feature.','xili-language') );

		$data['strings'][6] = $this->insertion_point_box_title_menu;
		$data['strings'][7] = esc_js( __('One menu from this list of menus will be inserted here according current language of displayed webpage.','xili-language') );
		$data['strings'][8] = esc_js( __('This is an experimental poweful feature for dynamic menus.','xili-language') );
		// send all these data to javascript
		wp_localize_script( 'xllist_nav_menu', 'xili_data', $data );
	}



    /**
	 * Navigation menu: new ways to insert pages sub-selection via menu builder screen
	 *
	 * @since 2.9.10
	 *
	 */
	function add_sub_select_page_nav_menu_meta_boxes() {
     	add_meta_box(
     		'insert-xlspage-list',
     		sprintf ( __('%s Pages selection', 'xili-language'), '[©xili]'),
     		array( $this, 'sub_select_page_nav_menu_link'),
     		'nav-menus',
     		'side',
     		'high'
     	);
     }
	// called by above filter
	function sub_select_page_nav_menu_link() {
			// div id in submit below
		global $_nav_menu_placeholder, $nav_menu_selected_id;
		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
			?>
        <div id="posttype-xlsplist" class="posttypediv">
        		<div id="tabs-panel-xlsplist" class="tabs-panel tabs-panel-active">
        			<ul id ="xllist-checklist" class="categorychecklist form-no-clear">

        				<li>
        					<label  title="" class="menu-item-title">
        						<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="-1">&nbsp;
        					</label>
        					<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
        					<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo $this->insertion_point_box_title_page; ?>">
        					<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo $this->insertion_point_dummy_link_page; ?>" >
        					<input type="text" class="menu-item-attr-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-attr-title]" value="include=">
        					<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="xlsplist">
        				</li>


        			</ul>
        		</div>
        		<p class='description'><?php _e( 'Check and add to menu an insertion point of sub-selection of pages (during displaying menu, a sub-selection will be done according current language. Args is like in function wp_list_pages. Example: <code>include=11,15</code>', 'xili-language' ); ?></p>
        		<p class="button-controls">
        			<span class="list-controls">

        			</span>
        			<span class="add-to-menu">
        				<input type="submit" <?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-post-type-menu-item" id="submit-posttype-xlsplist">
        				<span class="spinner"></span>
        			</span>
        		</p>
        	</div>
        <?php
	}

	/**
	 * Navigation menu: new ways to insert another menu from menu builder and set language selection
	 *
	 * @since 2.9.20
	 *
	 */
	function add_sub_select_nav_menu_meta_boxes() {
     	add_meta_box(
     		'insert-xlmenus-list',
     		sprintf ( __('%s Menus selection', 'xili-language'), '[©xili]'),
     		array( $this, 'sub_select_nav_menus'),
     		'nav-menus',
     		'side',
     		'high'
     	);
     }
	// called by above filter
	function sub_select_nav_menus() {
		global $_nav_menu_placeholder, $nav_menu_selected_id;
		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
		?>
        	<div id="posttype-xlmenulist" class="posttypediv">
        		<div id="tabs-panel-xlmenulist" class="tabs-panel tabs-panel-active tabs-panel-menu">
        			<ul id ="xlmenulist-checklist" class="categorychecklist form-no-clear">

        				<?php

        				// load menus without location
        				$locations = get_registered_nav_menus();
						$menu_locations = get_nav_menu_locations();
        				$nav_menus = wp_get_nav_menus( array('orderby' => 'name') );
        				// reduce to those without location
        				$nav_menus_wo = array();
        				$already_located = array(); // to avoid multiple items 2.9.22
        				foreach ( $locations as $_location => $_name ) {
        					if ( isset( $menu_locations[$_location] ) && $menu_locations[$_location] > 0 )
        						$already_located[] = $menu_locations[$_location];
        				}
        				foreach ( $nav_menus as $menu ) {
        					if ( $already_located != array() && !in_array ( $menu->term_id, $already_located ) ) {
								$nav_menus_wo[] = $menu;
							}
						}

        				if ( $nav_menus_wo ) {
        					echo '<li>';
	        				$listlanguages = $this->get_listlanguages();
							foreach ( $listlanguages as $language )  {
								echo '<span class="lang-menu-name" > '.  __( $language->description, 'xili-language' ) . '</span>'; ?>&nbsp;
	        					<select name="menu" id="menu-wo-<?php echo $language->slug ?>" class="menu-wo">
	        						<option value="0" ><?php echo esc_attr( __( 'Select a menu...', 'xili-language' ) ); ?></option>
	        						<?php foreach( $nav_menus_wo as $_nav_menu ) { ?>
											<option value="<?php echo esc_attr( $_nav_menu->term_id ); ?>" >
												<?php echo wp_html_excerpt( $_nav_menu->name, 40, '&hellip;' ); ?>
											</option>
									<?php } ?>
	        					</select><br />
	        				<?php } ?>

	        				</li><li>
	        					<label  title="<?php echo $language->name; ?>" class="menu-item-title menu-item-title-ok">
	        						<input type="checkbox" class="menu-item-checkbox menu-check-ok" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-object-id]" value="-1">&nbsp;&nbsp;<?php _e('Check before adding to menu'); ?>
	        					</label>
	        					<input type="hidden" class="menu-item-type" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom">
	        					<input type="hidden" class="menu-item-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" value="<?php echo $this->insertion_point_box_title_menu; ?>">
	        					<input type="hidden" class="menu-item-url" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]" value="<?php echo $this->insertion_point_dummy_link_menu; ?>">
	        					<input type="hidden" class="menu-item-attr-title menu-list-index" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-attr-title]" value="">
	        					<input type="hidden" class="menu-item-classes menu-list-menu" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-classes]" value="">
	        				</li>
        				<?php } else { ?>
        					<li>
	        					<label  class="menu-item-title">
	        						<?php _e('No menu is available. We must create them without assigning a theme location', 'xili-language'); ?>
	        					</label>
        					</li>
        				<?php } ?>

        			</ul>
        		</div>
        		<p class='description'><?php _e( 'Select to assign a language to a menu container without location. Only an insertion point will be placed inside the Menu Structure. The content of the insertion will be automatically made according navigation language rules and contexts. After selection, check and click “Add to Menu” - button below.', 'xili-language' ); ?></p>
        		<p class="button-controls">
        			<span class="list-controls">

        			</span>
        			<?php if ( $nav_menus_wo ) { ?>
        			<span class="add-to-menu">
        				<input type="submit" <?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-post-type-menu-item" id="submit-posttype-xlmenulist">
        				<span class="spinner"></span>
        			</span>
        			<?php } ?>
        		</p>
        	</div>
        	<script type="text/javascript">
	//<![CDATA[
			jQuery(document).ready( function($) {
				$(".lang-menu-name").css ({display:'inline-block', width:'55px' });
				$(".lang-menu-name").css ( "font-size", "12px");
				$(".tabs-panel-menu").css ({background:'#f5f5f5'});
				$(".menu-item-title-ok").css ({ display:'inline-block', margin:'7px 0 0'});
				$(".menu-check-ok").change(function() {
					if ( $(this).attr('checked') ) {
						var langindex = new Array();
						var menuvalue = new Array();
						var message = '<?php echo esc_js ( __( "Assign at least one menu to a language !!!", "xili-language") ) ?>';
						var total = 0;
						$('.menu-wo').each(function() {

							langindex.push( $(this).attr('id') );
							menuvalue.push( $(this).find('option:selected').val() );
							total = total + $(this).find('option:selected').val();
						});
						$('.menu-list-index').val( langindex.join('-') ) ;
						$('.menu-list-menu').val( 'xlmenulist-' + menuvalue.join('-') ) ;
						//alert ( '-----' +  langindex );
						if ( total == 0 ) {
							$(this).attr('checked', false );
							alert ( message ) ;
						}
					}
				});
			});
			//]]>
		</script>
        <?php

	}

	/**
	 * Files page - to download automattic mo files
	 *
	 * @since 2.8.8
	 */
	function languages_files() {
		global $wp_version;
		$msg = 0;
		$themessages = array('ok');
		$action = '';
		$emessage = '';
		$message = '';
		$upgrade = false ;
		if ( isset( $_POST['downloadmo'] ) ) {
			$action = 'downloadmo' ;
		} else if ( isset( $_POST['mo_merging'] ) ) {
			$action = 'mo_merging' ;
		} else if ( isset( $_POST['checkdownloadmo'] ) ) {
			$action = 'checkdownloadmo' ;
		}


		$message = $action ;



		$themessages = array ( "",
		__('mo files updated','xili-language'),
		__('mo files unreachable','xili-language') ) ;

		add_meta_box('xili-language-files', __('Languages System Files','xili-language'), array(&$this,'on_box_files_content'), $this->thehook5 , 'normal', 'low');


		?>
		<div id="xili-language-files" class="wrap columns-2 minwidth">
			<?php screen_icon('options-general'); ?>
			<h2><?php _e('Languages','xili-language') ?></h2>
			<h3 class="nav-tab-wrapper">
				<?php $this->set_tabs_line() ?>
			</h3>

			<?php if ( $action == 'downloadmo' ) {
				check_admin_referer( 'xili-language-files' );
				$listlanguages = $this->get_listlanguages();
				$a = 0;

				foreach ( $listlanguages as $language )  {

					if ( $language->name != 'en_US' ) {

						if ( isset( $_POST['downloadtheme_'.$language->name] ) ) {

							if ( 'Choose' != $_POST['downloadtheme_'.$language->name] ) {

								$s = explode ( '_', $_POST['downloadtheme_'.$language->name] );

								$theme = $s[1];

							} else {
								$theme = "";
							}

						} else {
							$theme = "";
						}

						if ( isset( $_POST['download_'.$language->name] ) && false !== ( strpos ( $_POST['download_'.$language->name], "Auto"  ) ) ) {

							$version = str_replace ( 'Auto_', '' , $_POST['download_'.$language->name] ) ;

				    	// download_mo_from_automattic( $locale = 'en_US', $upgrade = false, $theme_name = "" )
				    		$a = $this->download_mo_from_automattic( $language->name, $version, $theme, 1 ) ;

						} else if ( isset( $_POST['download_'.$language->name] ) &&  false !== ( strpos ( $_POST['download_'.$language->name], "GlotPress"  ) ) ) {

							$a = $this->download_mo_from_translate_wordpress( $language->name, $wp_version, $theme , 1 ) ;

						} else if ( isset( $_POST['downloadtheme_'.$language->name] ) && 'Choose' !== $_POST['downloadtheme_'.$language->name] ) {

							if ( $s[0] == 'Auto' ) {

								$automattic_root = 'http://svn.automattic.com/wordpress-i18n/';
								$url_base = $automattic_root."{$language->name}/branches/"; // replace /tags/ 2014-02-01
								$versions_to_check = $this->versions_to_check ( $url_base ); // to recover version

								$version = $this->find_if_version_exists( $language->name, $versions_to_check, $url_base ) ;

								$a = $this->download_mo_from_automattic( $language->name, $version, $theme, 2 ) ;

							} else {

								$a = $this->download_mo_from_translate_wordpress( $language->name, $wp_version, $theme, 2 ) ;
							}
						}
					}
				}
			    $msg = ( $a ) ? 1 : 2;
			}

		if ( $action == 'checkdownloadmo' ) {
			check_admin_referer( 'xili-language-files' );
			$upgrade = true ;
		}

		if ( $action == 'mo_merging' ) {
			check_admin_referer( 'xili-language-files' );
			if ( isset( $_POST['mo_parent_child_merging'] ) && $_POST['mo_parent_child_merging'] == 'merging' ) {
				$this->xili_settings['mo_parent_child_merging'] = true ;
			} else {
				$this->xili_settings['mo_parent_child_merging'] = false ;
			}
			update_option('xili_language_settings', $this->xili_settings);
		}

			?>
			<?php if (0!= $msg ) { ?>
			<div id="message" class="updated fade"><p><?php echo $themessages[$msg]; ?></p></div>
			<?php } ?>
			<form name="files" id="files" method="post" action="options-general.php?page=language_files">
				<?php wp_nonce_field('xili-language-files'); ?>
				<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
				<p class="width23 boldtext">
				<?php printf(__('This tab is added to help aware webmaster to find WP core MO files from %1$s Automattic SVN server %2$s or %3$s GlotPress server%2$s. <br />Be aware that files are not always available for the current WP version (%4$s).<br /> So, the rules for Automattic SVN are set to find the most recent version equal or below the current (exp. 3.5.x or 3.4.x for 3.6). Only check the wished files if language is used in dashboard or default theme (2011, 2012,… ).','xili-language'),'<a href="http://svn.automattic.com/wordpress-i18n/" target="_blank">','</a>', '<a href="http://translate.wordpress.org/projects/wp" target="_blank">', $wp_version );

				echo '<br /><strong>'.__('Be aware that choosen files will be downloaded in Core or Theme languages sub-folder. Verify folder rights !','xili-language').'</strong>';

				?></p>
				<?php

				$data = array(
			'message'=>$message, 'action'=>$action, 'emessage'=>$emessage, 'upgrade' => $upgrade
		);

				$this->setting_form_content( $this->thehook5, $data );
				?>
			</form>
		</div>
		<?php $this->setting_form_js( $this->thehook5 );

	}

	function on_box_files_content ( $data ) {
		extract ( $data );
		$how = $this->states_of_mofiles( $upgrade );
		 ?>
		 <div class="submit"><input id='downloadmo' name='downloadmo' type='submit' value="<?php _e('Download mo','xili-language') ?>" /></div>
		<div class="submit"><input id='checkdownloadmo' name='checkdownloadmo' type='submit' value="<?php _e('Check again before downloading mo','xili-language') ?>" /></div>

				<?php if ( $how == 0  )
					 echo '<p><strong>'.__('All seems to be in WP languages folder','xili-language').'</strong></p>';
				?>
				<div class="clearb1">&nbsp;</div><br/>
		<?php
	}

	// display states of files locally and at automattic svn server

	function states_of_mofiles ( $show_upgrade = false ) {
		global $wp_version;
		$wp_version_details = explode ('.', $wp_version );

		$wp_version_root = $wp_version_details[0] ;

		if ( count ( $wp_version_details ) > 2 )
			$wp_version_root_2 = $wp_version_details[0] . '.' . $wp_version_details[1] ;

		$available_languages_installed = get_available_languages();
		//$available_languages_installed[] = $this->get_default_locale();
		$available_languages_installed = array_unique( $available_languages_installed );
		$available_languages_installed = array_filter( $available_languages_installed );

		$listlanguages = $this->get_listlanguages();

		$automattic_root = 'http://svn.automattic.com/wordpress-i18n/';

		$i = 0 ;
		if ( is_child_theme() ) {
			$theme_name = get_option("stylesheet");
			$parent_theme_name = get_option("template");

		} else {
			$theme_name = get_option("template");
			$parent_theme_name = get_option("template");

		}
		foreach ( $listlanguages as $language )  {

			echo '<div class="langbox" style="overflow:hidden;">';
			echo '<h4>';
			echo $language->description . ' ('.$language->name.') ';
			echo '</h4>';

			$installed = in_array( $language->name, $available_languages_installed );

			if ( $installed ) {
				echo '<p>'.__('Installed in WP languages folder','xili-language').'</p>';
			}

			$show = ( ( $installed && $show_upgrade ) || ( !$installed ) ) ? true : false ;

			if ( $show )  {

				// GlotPress
				$glot = false;
				if ( $language->name != 'en_US' ) {

					if ( $ver = $this->check_versions_in_glotpress ( $language->name, $wp_version ) ) {
						if ( $ver == 'dev' ) { //2.8.8k
							echo '<p><em>'.__('Development Version available on GlotPress WordPress.org server','xili-language').'</em></p>';
						} else {
							echo '<p><em>'. sprintf( __('Version %s ready to be downloaded on GlotPress WordPress.org server','xili-language'), $wp_version ).'</em></p>';
						}
						$glot = true;
					} else {
						$glot = false;
						echo '<p>'.__('Not available from GlotPress WordPress.org server','xili-language').'</p>';
					}
				}
				// Automattic
				$url_base = $automattic_root."{$language->name}/branches/"; // replaces /tags/ 2014-02-01
				$versions_to_check = $this->versions_to_check ( $url_base );
				//$version/messages/{$language->name}.mo

				if ( $language->name == 'en_US' ) {
					echo '<p>'.__('Root language of WordPress','xili-language').'</p>';
					$auto = false;
				} else if ( $version = $this->find_if_version_exists( $language->name, $versions_to_check, $url_base ) ) {
					echo '<p><em>'. sprintf( __('Version %s ready to be downloaded from Automattic SVN server','xili-language'), $version ).'</em></p>';

					$auto = true;
					$i++;
				} else {
					$auto = false;
					echo '<p>'.__('Not available from Automattic SVN server','xili-language').'</p>';
				}

				if ( $glot || $auto ) {

					echo __('Server to download','xili-language');
					echo ' : <select id="download_'.$language->name.'" name="download_'.$language->name.'" >';
					echo '<option value="Choose" >' . __('Choose server...','xili-language') . '</option>';
					if ( $auto )
						echo '<option value="Auto_'.$version.'">' . __( 'Try from Automattic', 'xili-language' ) . '</option>' ;
					if ( $glot )
					   echo '<option value="GlotPress_'.$version.'">' . __( 'Try from GlotPress', 'xili-language' ) . '</option>' ;

					echo '</select>';
				}

			}
				if ( $language->name != 'en_US' && in_array ( $parent_theme_name, $this->embedded_themes ) ) {
					echo '<fieldset class="themebox"><legend>';
					echo sprintf( __('Theme\'s files %s','xili-language'), ( ( is_child_theme() ) ? $theme_name .' ('.$parent_theme_name.') ':  $theme_name ) );
					echo '</legend>';

					$mofile = get_template_directory(). '/languages/' . $language->name.'.mo';
					if ( file_exists ( $mofile )  ) {
						if ( is_child_theme() ) {

							echo '<p>'. sprintf( __('Installed in parent theme\'s (%s) languages folder','xili-language'), $parent_theme_name ).'</p>';
						} else {
							echo '<p>'.__('Installed in theme\'s languages folder','xili-language').'</p>';
						}
					} else {
						if ( is_child_theme() ) {
							echo '<p>'. sprintf( __('Not installed in parent theme\'s (%s) languages folder','xili-language'), $parent_theme_name ).'</p>';
						} else {
							echo '<p>'.__('Not installed in theme\'s languages folder','xili-language').'</p>';
						}

					echo __('Server to download theme file','xili-language');
					echo ' : <select id="downloadtheme_'.$language->name.'" name="downloadtheme_'.$language->name.'" >';
					echo '<option value="Choose" >' . __('Choose server...','xili-language') . '</option>';
					echo '<option value="Auto_'.$parent_theme_name.'" >' . __( 'Try from Automattic', 'xili-language' ) . '</option>' ;
					echo '<option value="GlotPress_'.$parent_theme_name.'" >' . __( 'Try from GlotPress', 'xili-language' ) . '</option>' ;
					echo '</select>';

						$i++;
					}
				echo '</fieldset>';

				}
			echo '</div>';




		}
		return $i;
	}

	function find_if_version_exists ( $language, $versions_to_check, $url_base ) {
		if ( $versions_to_check != array () ) {
			foreach ( $versions_to_check as $version ) {
				$url = $url_base . "$version/messages/$language.mo";

				if ( $this->url_exists( $url ) ) return $version;
			}
		} else {
			return false;
		}
	}

	function versions_to_check ( $url_base, $upgrade = false ) {
		// define versions to check
		global $wp_version;

		$wp_version_details = explode ('.', $wp_version );

		if ( $this->url_exists( $url_base ) ) {
			// get all the versions available in the subdirectory
			$resp = wp_remote_get($url_base);
			if (is_wp_error($resp) || 200 != $resp['response']['code'])
			  return false;

			preg_match_all('#>([0-9\.]+)\/#', $resp['body'], $matches);
			if (empty($matches[1]))
				return false;

			rsort($matches[1]); // sort from newest to oldest

			$versions = $matches[1];

			foreach ($versions as $key => $version) {

				$version_details = explode ('.', $version );

				if ( $version_details[0] != $wp_version_details[0] ) {
					unset($versions[$key]);
				// will not try to download a too recent mofile
				} else if ( version_compare( $version, $wp_version, '>') ) {
					unset($versions[$key]);
				// no big diff
				} else if ( abs ((int) $version_details[1] - (int)( $wp_version_details[1])) > 2 ) { // 3.6 and 3.5.x
					unset($versions[$key]);
				// will not download an older version if we are upgrading
				} else if ($upgrade && version_compare($version, $wp_version, '<=')) {
					unset($versions[$key]);
				}
			}
			return $versions;
		} else {
			return false;
		}
	}

	function check_versions_in_glotpress ( $locale, $version = 'dev' ) {

		$version_folder = $this->glotPress_version_folder ( $version ) ;

		// Get the list of available translation from Translate WordPress. This is expected to be JSON.
		$translations = wp_remote_get( sprintf( 'http://translate.wordpress.org/api/projects/wp/%1$s', $version_folder ) ); // 2.8.8k

		if ( is_wp_error( $translations ) || wp_remote_retrieve_response_code( $translations ) !== 200 ) {
			// again with forcing 'dev'
			$translations = wp_remote_get( sprintf( 'http://translate.wordpress.org/api/projects/wp/%1$s', 'dev' ) ); // rules changed in glot
			if ( is_wp_error( $translations ) || wp_remote_retrieve_response_code( $translations ) !== 200 ) {
				return false ;
			}
		}

		$translations = json_decode( wp_remote_retrieve_body( $translations ) );
		if ( is_null( $translations ) )
			return false ;

		$filtered = wp_list_filter( $translations->translation_sets, array( 'locale' => substr( $locale, 0, 2 ) )) ;  // 2.9.10  (no more wp_locale)
		// See if the requested $locale has an available translation
		$translations = array_shift( $filtered ); // param variable

		if ( empty( $translations ) )
			return false ;

		return $translations->locale ;
	}

	/**
	 * Support page
	 *
	 * @since 2.4.1
	 */
	function languages_support() {
		global $wp_version ;
		$msg = 0;
		$themessages = array('ok');
		$emessage = "";
		$action = '';
		if ( isset( $_POST['sendmail'] ) ) {
			$action = 'sendmail' ;
		}
		$message = $action ;


		switch( $action ) {

			case 'sendmail'; // 1.8.5
					check_admin_referer( 'xili-plugin-sendmail' );

					$this->xili_settings['url'] = ( isset( $_POST['urlenable'] ) ) ? $_POST['urlenable'] : '' ;
					$this->xili_settings['theme'] = ( isset( $_POST['themeenable'] ) ) ? $_POST['themeenable'] : '' ;
					$this->xili_settings['wplang'] = ( isset( $_POST['wplangenable'] ) ) ? $_POST['wplangenable'] : '' ;
					$this->xili_settings['version-wp'] = ( isset( $_POST['versionenable'] ) ) ? $_POST['versionenable'] : '' ;
					$this->xili_settings['permalink_structure'] = ( isset( $_POST['permalink_structure'] ) ) ? $_POST['permalink_structure'] : '' ;
					$this->xili_settings['xiliplug'] = ( isset( $_POST['xiliplugenable'] ) ) ? $_POST['xiliplugenable'] : '' ;
					$this->xili_settings['webmestre-level'] = $_POST['webmestre']; // 2.8.4
					update_option('xili_language_settings', $this->xili_settings);
					$contextual_arr = array();
					if ( $this->xili_settings['url'] == 'enable' ) $contextual_arr[] = "url=[ ".get_bloginfo ('url')." ]" ;
					if ( isset($_POST['onlocalhost']) ) $contextual_arr[] = "url=local" ;
					if ( $this->xili_settings['theme'] == 'enable' ) $contextual_arr[] = "theme=[ ".get_option ('stylesheet')." ]" ;
					if ( $this->xili_settings['wplang'] == 'enable' ) $contextual_arr[] = "WPLANG=[ ".WPLANG." ]" ;
					if ( $this->xili_settings['version-wp'] == 'enable' ) $contextual_arr[] = "WP version=[ ".$wp_version." ]" ;
					if ( $this->xili_settings['permalink_structure'] == 'enable' ) {
						$contextual_arr[] = "Permalinks=[ ".get_option('permalink_structure')." ]" ;
						if ( isset ( $XL_Permalinks_rules ) ) $contextual_arr[] = "XL lang perma" ;
					}
					if ( $this->xili_settings['xiliplug'] == 'enable' ) $contextual_arr[] = "xiliplugins=[ ". $this->check_other_xili_plugins() ." ]" ;

					$contextual_arr[] = $this->xili_settings['webmestre-level'];  // 1.9.1

					$headers = 'From: xili-language plugin page <' . get_bloginfo ('admin_email').'>' . "\r\n" ;
		   			if ( '' != $_POST['ccmail'] ) {
		   				$headers .= 'Cc: <'.$_POST['ccmail'].'>' . "\r\n";
		   				$headers .= 'Reply-To: <'.$_POST['ccmail'].'>' . "\r\n";
		   			}
		   			$headers .= "\\";
		   			$message = "Message sent by: ".get_bloginfo ('admin_email')."\n\n" ;
		   			$message .= "Subject: ".$_POST['subject']."\n\n" ;
		   			$message .= "Topic: ".$_POST['thema']."\n\n" ;
		   			$message .= "Content: ".$_POST['mailcontent']."\n\n" ;
		   			$message .= "Checked contextual infos: ". implode ( ', ', $contextual_arr ) ."\n\n" ;
		   			$message .= "This message was sent by webmaster in xili-language plugin settings page.\n\n";
		   			$message .= "\n\n";
		   			if ( preg_match ( '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i', $_POST['ccmail'] ) && preg_match ( '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i', get_bloginfo ('admin_email') ) ) {
			   			$result = wp_mail('contact@xiligroup.com', $_POST['thema'].' from xili-language v.'.XILILANGUAGE_VER.' plugin settings page.' , $message, $headers );
			   			$message = __('Email sent.','xili-language');
						$msg = 1;
						$emessage = sprintf( __( 'Thanks for your email. A copy was sent to %s (%s)','xili-language' ), $_POST['ccmail'],  $result ) ;
		   			} else {
		   				$msg = 2;
		   				$emessage = sprintf( __( 'Issue in your email. NOT sent to Cc: %s or the return address %s is not good !','xili-language' ), $_POST['ccmail'], get_bloginfo ('admin_email') ) ;
		   			}
			break;
		}
		$themessages[1] = __('Email sent.','xili-language');
		$themessages[2] = __('Email not sent. Please verify email field','xili-language');

		add_meta_box('xili-language-box-mail', __('Mail & Support','xili-language'), array(&$this,'on_box_mail_content'), $this->thehook3 , 'normal', 'low');



		$data = array(
			'message'=>$message, 'action'=>$action, 'emessage'=>$emessage
		);

		?>
		<div id="xili-language-support" class="wrap columns-2 minwidth">
			<?php screen_icon('options-general'); ?>
			<h2><?php _e('Languages','xili-language') ?></h2>
			<h3 class="nav-tab-wrapper">
			<?php $this->set_tabs_line() ?>
			</h3>
			<?php //echo '---'.$id  ?>
			<?php if (0!= $msg ) { ?>
			<div id="message" class="updated fade"><p><?php echo $themessages[$msg]; ?></p></div>
			<?php } ?>
			<form name="support" id="support" method="post" action="options-general.php?page=language_support">
				<?php wp_nonce_field('xili-language-support'); ?>
				<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
				<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
				<p class="width23 boldtext">
				<?php printf(__("For support, before sending an email with the form below, don't forget to visit the readme as %shere%s and the links listed in contextual help tab (on top left).",'xili-language'),'<a href="http://wordpress.org/extend/plugins/xili-language/" target="_blank">','</a>' ); ?>
				</p>
				<?php $this->setting_form_content( $this->thehook3, $data );
			?>
			</form>
		</div>
		<?php $this->setting_form_js( $this->thehook3 );
	}


	function check_other_xili_plugins () {
		$list = array();
		//if ( class_exists( 'xili_language' ) ) $list[] = 'xili-language' ;
		if ( class_exists( 'xili_tidy_tags' ) ) $list[] = 'xili-tidy-tags' ;
		if ( class_exists( 'xili_dictionary' ) ) $list[] = 'xili-dictionary' ;
		if ( class_exists( 'xilithemeselector' ) ) $list[] = 'xilitheme-select' ;
		if ( function_exists( 'insert_a_floom' ) ) $list[] = 'xili-floom-slideshow' ;
		if ( class_exists( 'xili_postinpost' ) ) $list[] = 'xili-postinpost' ;
		return implode (', ',$list) ;
	}


	/**
	 * for each page : tabs line
	 * @since 2.4.1
	 */
	function set_tabs_line() {
		global $pagenow;
		$id = isset( $_REQUEST['page'] ) ? $_REQUEST['page']  : 'language_page';
		
		foreach ( $this->xl_tabs as $tab_id => $tab ) {
				$class = ( $tab['url'] == $pagenow.'?page='.$id ) ? ' nav-tab-active' : '';
				echo '<a href="' . $tab['url'] .'" class="nav-tab' . $class . '">' .  esc_html( $tab['label'] ) . '</a>';
		}
	}

	/**
	 * for each three forms of settings side-info-column
	 * @since 2.4.1
	 * @updated 2.5
	 */
	function setting_form_content( $the_hook, $data ) {
		global $wp_version;
		if ( version_compare($wp_version, '3.3.9', '<') ) {
			$poststuff_class = 'class="metabox-holder has-right-sidebar"';
			$postbody_class = "";
			$postleft_id = "";
			$postright_id = "side-info-column";
			$postleft_class = "";
			$postright_class = "inner-sidebar";
		} else { // 3.4
			$poststuff_class = "";
			$postbody_class = 'class="metabox-holder columns-2"';
			$postleft_id = 'id="postbox-container-2"';
			$postright_id = "postbox-container-1";
			$postleft_class = 'class="postbox-container"';
			$postright_class = "postbox-container";
		}

		?>
		<div id="poststuff" <?php echo $poststuff_class; ?>>
			<div id="post-body" <?php echo $postbody_class; ?> >

				<div id="<?php echo $postright_id; ?>" class="<?php echo $postright_class; ?>">
					<?php do_meta_boxes($the_hook, 'side', $data); ?>
				</div>

				<div id="post-body-content">

					<div <?php echo $postleft_id; ?> <?php echo $postleft_class; ?> style="min-width:360px">
						<?php do_meta_boxes($the_hook, 'normal', $data); ?>
					</div>

					<h4><a href="http://dev.xiligroup.com/xili-language" title="xili-language page and docs" target="_blank" style="text-decoration:none" ><img style="vertical-align:bottom; margin-right:10px" src="<?php echo plugins_url( 'images/xililang-logo-32.png', $this->file_file ) ;  ?>" alt="xili-language logo"/></a>&nbsp;&nbsp;&nbsp;©&nbsp;<a href="http://dev.xiligroup.com" target="_blank" title="<?php _e('Author'); ?>" >xiligroup.com</a>™ - msc 2007-2014 - v. <?php echo XILILANGUAGE_VER; ?></h4>

				</div>
			</div>
			<br class="clear" />
		</div>
	<?php
	}

	/**
	 * add js at end of each three forms of settings
	 * @since 2.4.1
	 */
	function setting_form_js( $the_hook ) { ?>
	<script type="text/javascript">
	//<![CDATA[
			jQuery(document).ready( function($) {
				// close postboxes that should be closed
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				// postboxes setup
				postboxes.add_postbox_toggles('<?php echo $the_hook; ?>');

			<?php if ( $the_hook == $this->thehook4 ) {	/* expert */ ?>
				$('#show-manual-box').change(function() {

						$('#manual-menu-box').toggle();

				});
			<?php } ?>
			<?php if ( $the_hook == $this->thehook ) {	?>
				// for examples list
				$('#language_name_list').change(function() {
                 	var x = $(this).val();
      				$('#language_name').val(x);
      				var x = $(this).val();
      				x = x.toLowerCase();
      				$('#language_nicename').val(x);
      				var v = $('#language_name_list option:selected').text();
      				v1 = v.substring(0,v.indexOf("/",0));
      				v2 = v1.substring(0,v1.indexOf(" (",0));
      				if  ('' != v2 ) {
      					v = v2;
      				} else {
      					v = v1;
      				}
      				$('#language_description').val(v);
    			});
			<?php } ?>
			});
			//]]>
		</script>
	<?php
	}



	/***************** Side settings metaboxes *************/

	/**
	 * private functions for languages_settings
	 * @since 0.9.6
	 *
	 * fill the content of the boxes (right side and normal)
	 *
	 */
	function  on_sidebox_msg_content( $data ) {
		extract($data);
		?>
	 	<h4><?php _e('Note:','xili-language') ?></h4>
		<p><?php echo $message;?></p>
		<?php
	}

	/**
	 * info box
	 */
	function  on_sidebox_info_content() { ?>

		<p><?php _e("This plugin was developed with the taxonomies, terms tables and WP specifications. <br /> xili-language create a new taxonomy used for language of posts and pages and custom post types. For settings (basic or expert), 5 tabs were available.<br /><br /> To attach a language to a post, a box gathering infos in available in new and edit post admin side pages. Also, selectors are in Quick Edit bulk mode of Posts list.",'xili-language') ?></p>
		<?php
	}

	/**
	 *where to choose if browser language preferences is tested or not
	 */
	function on_box_frontend ( $data ) {
		extract( $data );
		/* 1.0 browser - default - languages */


		?>
		<div style="overflow:hidden">
		<fieldset class="box leftbox">
			<p><?php _e('Here select language of the home webpage', 'xili-language'); ?></p>
			<p><em><?php printf(__('As set in <a href="%1$s">%2$s</a>, the home webpage is', 'xili-language'), 'options-reading.php',  __('Reading')); ?>:&nbsp;
			<?php if ( $this->show_page_on_front ) {
					printf(__('a static <a href="%1$s">page</a>.', 'xili-language'), "edit.php?post_type=page") ;
					$page_for_posts = get_option('page_for_posts');
					if ( !empty ( $page_for_posts ) ) {
						echo '<br /><br /><br />';
						_e('Another page is set to display the latest posts (in default theme).', 'xili-language');
					}
				} else {
					echo '<br />';
					_e('set as to display the latest posts (in default theme).', 'xili-language');
				}
			?>
			</em></p>
		</fieldset>
		<fieldset class="box rightbox" ><legend><?php _e('Select language of the front page displays', 'xili-language'); ?></legend>
			<select name="xili_language_check_option" id="xili_language_check_option" class="fullwidth">
				<?php  if ( $browseroption == 'browser' )
						$checked = 'selected = "selected"';
						else
						$checked = '';
				?>
				<option value="" ><?php _e('Software defined','xili-language'); ?></option>
				<option value="browser" <?php echo $checked; ?> ><?php _e("Language of visitor's browser",'xili-language'); ?></option>
				<?php $listlanguages = get_terms_of_groups_lite ($this->langs_group_id,TAXOLANGSGROUP,TAXONAME,'ASC');
			foreach ($listlanguages as $language) {
				if ($browseroption == $language->slug)
						$checked = 'selected = "selected"';
					else
						$checked = '';
				echo '<option value="'.$language->slug.'" '.$checked.' >'.__($language->description,'xili-language').'</option>';
			}
				?>
			</select>

			<?php  if ( $browseroption == 'browser' ) {  // 2.3.1 ?>
				<br /><label for="xili_lang_neither_browser" ><?php _e("if not found",'xili-language'); ?>:&nbsp;<select name="xili_lang_neither_browser" id="xili_lang_neither_browser" class="width23">
				<?php  if ( $this->xili_settings['lang_neither_browser'] == '' )
						$checked = 'selected = "selected"';
						else
						$checked = '';
				?>
				<option value="" <?php echo $checked; ?> ><?php _e("Language of dashboard",'xili-language'); ?></option>
				<?php
				foreach ($listlanguages as $language) {
					if ( $this->xili_settings['lang_neither_browser'] == $language->slug )
							$checked = 'selected = "selected"';
						else
							$checked = '';
					echo '<option value="'.$language->slug.'" '.$checked.' >'.__($language->description,'xili-language').'</option>';
				}
				?>
				</select></label>
			<?php }  ?>

			<?php  if ( !$this->show_page_on_front ) { ?>
				<br /> &nbsp;&nbsp;<label for="xili_language_home_lang"><?php _e('Modify home query','xili-language') ?> <input id="xili_language_home_lang" name="xili_language_home_lang" type="checkbox" value="modify" <?php if($this->xili_settings['homelang'] == 'modify') echo 'checked="checked"' ?> /></label>
			<?php }

				$page_for_posts = get_option('page_for_posts'); // 2.8.4.1
				if ( !empty ( $page_for_posts ) ) { ?>
					<br /><br /><label for="xili_lang_neither_browser" ><?php _e("In list inside page",'xili-language'); ?>:&nbsp;
					<select name="xili_language_pforp_select" id="xili_language_pforp_select" >
						<option value="no_select" <?php selected( $this->xili_settings['pforp_select'] , 'no_select'); ?> ><?php _e("No selection of latest posts",'xili-language'); ?></option>
						<option value="select" <?php selected( $this->xili_settings['pforp_select'] , 'select'); ?> ><?php _e("Selection of latest posts",'xili-language'); ?></option>
					</select></label><?php
				}
				?></fieldset>
		<br />
		</div>
		<div style="overflow:hidden">
		<fieldset class="box leftbox">
			<p><?php _e('Here select language of the theme items when a category is displayed without language sub-selection', 'xili-language'); ?></p>
		</fieldset>
		<fieldset class="box rightbox"><legend><?php _e("Theme's language when categories in 'all'", 'xili-language'); ?></legend>
			<label for="allcategories_lang" >
			<select name="allcategories_lang" id="allcategories_lang" class="fullwidth">
				<?php $allcategories_lang = $this->xili_settings['allcategories_lang']; ?>
				<option value="" ><?php _e('Software defined','xili-language'); ?></option>
				<option value="browser" <?php echo  ( ($allcategories_lang == 'browser') ? 'selected = "selected"' : '' ) ; ?> ><?php _e("Language of visitor's browser",'xili-language'); ?></option>
				<option value="firstpost" <?php echo  ( ($allcategories_lang == 'firstpost') ? 'selected = "selected"' : '' ) ; ?> ><?php _e("Language of first post in loop",'xili-language'); ?></option>
				<?php
				foreach ($listlanguages as $language) {
					if ($allcategories_lang == $language->slug)
						$checked = 'selected = "selected"';
					else
						$checked = '';
				echo '<option value="'.$language->slug.'" '.$checked.' >'.__($language->description,'xili-language').'</option>';
			}
				?>
			</select></label>

		</fieldset>
		<br />
		</div>
		<div style="overflow:hidden">
		<fieldset class="box leftbox">
			<p><?php _e('For new post, in post edit author side:', 'xili-language'); ?></p>
		</fieldset>
		<fieldset class="box rightbox">
		<label for="xili_language_check_option_author" class="selectit">

		<select name="xili_language_check_option_author" id="xili_language_check_option_author" class="fullwidth" >
			<option value="" <?php selected( '', $authorbrowseroption ); ?>><?php _e('No default language','xili-language'); ?></option>
			<option value="authorbrowser" <?php selected( 'authorbrowser', $authorbrowseroption ); ?>><?php _e('Browser language','xili-language'); ?></option>
			<option value="authordashboard" <?php selected( 'authordashboard', $authorbrowseroption ); ?>><?php _e('Dashboard language','xili-language'); ?></option>
		</select><br /><em>
		<?php _e('For new post, pre-select by default the browser or dashboard language of current author', 'xili-language'); /* 2.8.0*/ ?></em></label>

		</fieldset>
		<br /><br /></div>
		<?php if ( file_exists( WP_PLUGIN_DIR . $this->xilidev_folder ) ) { /* test if folder exists - ready to add functions.php inside - since 1.0 */?>
		<label for="xili_language_check_functions_enable" class="selectit"><input id="xili_language_check_functions_enable" name="xili_language_check_functions_enable" type="checkbox" value="enable"  <?php if($functions_enable =='enable') echo 'checked="checked"' ?> /> <?php _e('Enable gold functions', 'xili-language'); ?></label>&nbsp;&nbsp;
		<?php } else {
		echo '<input type="hidden" name="xili_language_check_functions_enable" value="'.$functions_enable.'" />';
		}
		?>
		<br /><br />
		<?php
		$types = get_post_types(array('show_ui'=>1));

		if ( count($types) > 2 ) {
			$thecheck = array() ;
			$thecustoms = $this->get_custom_desc() ;
			if ( count($thecustoms) > 0 ) {
				foreach ( $thecustoms as $type => $thecustom) {
					$thecheck[] = $type ;
				}
				$clabel = implode(', ', $thecheck);


		?>
		<fieldset class="box fullwidth"><legend><?php _e('Multilingual custom posts', 'xili-language'); ?></legend>
		<?php ( count($thecheck) == 1 ) ? printf(__('One custom post (%s) is available.','xili-language'), $clabel ) : printf(__('More than one custom post (%s) are available.','xili-language'), $clabel );
		?>
		<br /><?php _e('Check the custom to enable multilanguage features.', 'xili-language'); ?><br /><br />
		<?php
		$customs_options = $this->xili_settings['multilingual_custom_post'];
		foreach ( $thecustoms as $type => $thecustom) {
			$customs_enable = ( isset($customs_options[$type]) ) ? $customs_options[$type]['multilingual'] : '';
		?>
		<label for="xili_language_multilingual_custom_<?php echo $type; ?>" class="selectit"><input id="xili_language_multilingual_custom_<?php echo $type; ?>" name="xili_language_multilingual_custom_<?php echo $type; ?>" type="checkbox" value="enable"  <?php if($customs_enable =='enable') echo 'checked="checked"' ?> /> <?php echo $thecustom['singular_name']; ?></label><br />
		<?php } ?>

		</fieldset>
		<?php } }
		if ( current_theme_supports( 'widgets' ) ) {  // 1.8.8 ?>
			<br /><label for="xili_language_widgetenable" class="selectit"><input id="xili_language_widgetenable" name="xili_language_widgetenable" type="checkbox" value="enable"  <?php if($this->xili_settings['widget'] =='enable') echo 'checked="checked"' ?> /> <?php _e('Enable widgets', 'xili-language'); ?></label><br /><br />
		<?php } else {
			echo '<br /><small>'.__('Current theme has no widgets support.','xili-language').'</small>';
			echo '<input type="hidden" name="xili_language_widgetenable" value="'.$this->xili_settings['widget'].'" />';
		}
		if ( current_theme_supports( 'widgets' ) ) {  // theme widget enable
			$link_cats = get_terms( 'link_category');
			$cat_settings = ( isset($this->xili_settings['link_categories_settings'] ) ) ? $this->xili_settings['link_categories_settings']  : array ( 'all' => '', 'category' => array() ) ; // 2.3.1
			?>
			<fieldset class="box fullwidth"><legend><?php _e('Bookmarks widget settings', 'xili-language'); ?></legend>
			<?php _e("Check the bookmark's categories where to enable multilanguage features.", "xili-language"); ?><br /><br />

			<label for="xili_language_link_cat_all" class="selectit"><input id="xili_language_link_cat_all" name="xili_language_link_cat_all" type="checkbox" value="enable"  <?php if( $cat_settings['all'] == 'enable') echo 'checked="checked"' ?> /> <?php _e('All Links'); ?></label><br />
			<?php
			foreach ( $link_cats as $link_cat ) {

			?>
			<label for="xili_language_link_cat_<?php echo intval($link_cat->term_id) ?>" class="selectit"><input id="xili_language_link_cat_<?php echo intval($link_cat->term_id) ?>" name="xili_language_link_cat_<?php echo intval($link_cat->term_id) ?>" type="checkbox" value="enable"  <?php echo ( isset( $cat_settings['category'][$link_cat->term_id] ) && $cat_settings['category'][$link_cat->term_id]  == 'enable' ) ? ' checked="checked"' : ''  ?> /> <?php echo $link_cat->name ; ?></label><br />

			<?php
			}
			?>


			</fieldset><br />
		<?php } ?>

	 	<div class='submit'>
		<input id='updateoptions' name='updateoptions' type='submit' tabindex='6' value="<?php _e('Update','xili-language') ?>" /></div>

		<div class="clearb1">&nbsp;</div><?php
	}

	/**
	 * Special box
	 *
	 * @since 2.4.1
	 *
	 */
	function on_sidebox_for_specials ( $data ) {

	 	$xili_language_lang_perma = ( has_filter ( 'term_link', 'insert_lang_4cat' ) ) ? true : false ;
		if ( $xili_language_lang_perma ) { // to force permalinks flush ?>

			<fieldset class="box"><legend><?php _e('Permalinks rules', 'xili-language'); ?></legend>
			<label for="force_permalinks_flush" class="selectit"><input id="force_permalinks_flush" name="force_permalinks_flush" type="checkbox" value="enable"  /> <?php _e('force permalinks flush', 'xili-language'); ?></label>
			</fieldset>
		<?php } ?>

		<fieldset class="box"><legend><?php _e('Translation domains settings', 'xili-language'); ?></legend><p>
			<?php _e("For experts in multilingual CMS: Check to modify domains switching.", "xili-language"); ?><br />
			<em><?php printf(__("Some plugins are well built to be translation ready. On front-end side, xili-language is able to switch the text_domain of the plugin to the theme_domain. So, if terms (and translations) are available in theme or local .mo files, these terms are displayed in the right language. Plugins without front-end text don’t need to be checked. Others need modification of php source.<br />More infos in <a href=\"%s\">wiki</a>.", "xili-language"), $this->wikilink ) ; ?></em><br /><br />
			<?php
			foreach ( $this->xili_settings['domains'] as $domain => $state ) {
				$domaininlist = ( $domain == 'default' ) ? __( 'Switch default domain of WP','xili-language' ) : $domain ;
			?>
			<label for="xili_language_domains_<?php echo $domain ; ?>" class="selectit"><input id="xili_language_domains_<?php echo $domain ; ?>" name="xili_language_domains_<?php echo $domain ; ?>" type="checkbox" value="enable"  <?php echo ( $state  == 'enable' ? ' checked="checked"' : '' ) ?> /> <?php echo $domaininlist ; ?></label><br />

			<?php
			}
			if ( $this->show ) print_r( $this->arraydomains ) ;
			 ?>
		</p></fieldset>
		<fieldset class="box" ><legend><?php _e('Dashboard style: External xl-style.css', 'xili-language'); ?></legend>
		<?php
		if ( ! $this->exists_style_ext ) {

			echo '<p>'. __( 'There is no style for dashboard','xili-language' ) .' ('.$this->style_message . ' )</p>';

		} else {

			echo '<p>'. $this->style_message . '</p>';
		}
		?>
		<p><label for="xili_language_external_xl_style"><?php _e('Activate xl-style.css','xili-language') ?> <input id="xili_language_external_xl_style" name="xili_language_external_xl_style" type="checkbox" value="on" <?php if( $this->xili_settings['external_xl_style'] == 'on') echo 'checked="checked"' ?> /></label></p>
		</fieldset>
		<fieldset class="box" ><legend><?php _e('Locale (date) translation', 'xili-language'); ?></legend><p>
			<?php _e("Since v2.4, new way for locale (wp_locale) translation.", "xili-language"); ?><br /><br />
			<label for="xili_language_wp_locale"><?php _e('Mode wp_locale','xili-language') ?> <input id="xili_language_wp_locale" name="xili_language_wp_locale" type="checkbox" value="wp_locale" <?php if( $this->xili_settings['wp_locale'] == 'wp_locale') echo 'checked="checked"' ?> /></label></p>
		</fieldset>
	 	<fieldset class="box"><legend><?php _e('Redirect to created post', 'xili-language'); ?></legend><p>
			<?php _e("After creating a linked post in other language, the Edit post is automatically displayed.", "xili-language"); ?><br /><br />
			<label for="xili_language_creation_redirect"><?php _e('Redirection','xili-language') ?> <input id="xili_language_creation_redirect" name="xili_language_creation_redirect" type="checkbox" value="redirect" <?php if( $this->xili_settings['creation_redirect'] == 'redirect') echo 'checked="checked"' ?> /></label></p>
		</fieldset>


	 	<div class='submit'>
		<input id='updatespecials' name='updatespecials' type='submit' tabindex='6' value="<?php _e('Update','xili-language') ?>" /></div>

		<div class="clearb1">&nbsp;</div><?php

	}

	/**
	 * Theme's information box
	 *
	 * @since 2.4.1
	 *
	 */
	function on_sidebox_4_theme_info( $data ) {
		$template_directory = $this->get_template_directory;
		if ( function_exists('is_child_theme') && is_child_theme() ) { // 1.8.1 and WP 3.0
			$theme_name = get_option("current_theme").' </strong>'.__('child of','xili-language').' <strong>'.get_option("template");
		} else {
			$theme_name = get_option("current_theme");
		}
	 	?>
	 	<fieldset class="themeinfo"><legend><?php echo __("Theme type and domain:",'xili-language'); ?></legend>
	 		<strong><?php echo ' - '.$theme_name.' -'; ?></strong>
	 		<?php
	 		if ("" != $this->parent->thetextdomain) {
	 			echo '<br />' . __('theme_domain:','xili-language').' <em>'.$this->parent->thetextdomain.'</em><br />'.__('as function like:','xili-language').'<em> _e(\'-->\',\''.$this->parent->thetextdomain.'\');</em>';
	 		} else {
	 			echo '<span class="red-alert">'.$this->admin_messages['alert']['no_domain_defined'].'</span>';
	 			if (''!=$this->domaindetectmsg) {
	 				echo '<br /><span class="red-alert">'. $this->domaindetectmsg.' '.$this->admin_messages['alert']['default'].'</span>';
	 			}
	 		} ?><br />

	 	</fieldset>
	 	<fieldset class="box"><legend><?php echo __("Language files:",'xili-language'); ?></legend>
	 	<p>
	 	<?php echo __("Languages sub-folder:",'xili-language').' '.$this->xili_settings['langs_folder']; ?><br />
	 	<?php _e('Available MO files:','xili-language'); echo '<br />';
	 	if ( file_exists( $template_directory ) ) // when theme was unavailable
	 		$this->find_files($template_directory, "/.mo$/", array(&$this,"available_mo_files")) ;

	 	if ( $this->parent->ltd === false )	{
	 		if ( is_child_theme() ) {
	 			echo '<br /><span class="red-alert">'.$this->admin_messages['alert']['no_load_function_child'].'</span>';
	 		} else {
	 			if ( $this->parent->ltd_parent === false )
	 				echo '<br /><span class="red-alert">'.$this->admin_messages['alert']['no_load_function'].'</span>';
	 		}

	 	}
	 		?>
	 	</p><br />
	 	</fieldset>


	<?php
		$screen = get_current_screen();  // to limit unwanted side effects (form)

		if ( $screen->id == 'settings_page_language_files' && is_child_theme() ) {

			if ( $this->xili_settings['mo_parent_child_merging']  ) {
			?>
			<fieldset class="box"><legend><?php echo __("Language files in parent theme:",'xili-language'); ?></legend>
		 	<p>
		 	<?php echo __("Languages sub-folder:",'xili-language').' '.$this->xili_settings['parent_langs_folder']; ?><br />
		 	<?php _e('Available MO files:','xili-language'); echo '<br />';
		 	$template_directory = $this->get_parent_theme_directory;
		 	if ( file_exists( $template_directory ) ) {// when theme was unavailable
		 		$this->find_files( $template_directory, "/.mo$/", array(&$this,"available_mo_files") ) ;
		 	}

		 	if ( $this->parent->ltd_parent === false   )	{

	 			echo '<br /><span class="red-alert">'.$this->admin_messages['alert']['no_load_function'].'</span>';

	 		}
	 		?>
		 	</p><br />
		 	</fieldset>

			<?php
			}
			echo '<br />' . __( 'MO merging between parent and child','xili-language'). ':&nbsp;<input type="checkbox" id="mo_parent_child_merging" name="mo_parent_child_merging" value="merging" '. checked ( $this->xili_settings['mo_parent_child_merging'], true ,false ) .' />';

			?>
			<div class='submit'>
			<input id='mo_merging' name='mo_merging' type='submit' value="<?php _e('Update','xili-language') ?>" />
			</div>
			<?php


		}

	}



	/**
	 * Actions box
	 * menu
	 * gold options
	 */
	function on_box_expert( $data ) {
		extract($data);
		$template_directory = $this->get_template_directory;
		if ( function_exists('is_child_theme') && is_child_theme() ) { // 1.8.1 and WP 3.0
			$theme_name = get_option("stylesheet").' '.__('child of','xili-language').' '.get_option("template");
		} else {
			$theme_name = get_option("template");
		}

		if ( current_theme_supports( 'menus' ) ) { ?>

		<p><em><?php _e('These options are still present for compatibility reasons with previous versions < 2.9.22. <strong>For new installation, it is preferable to use the insertion points and menu options.</strong>', 'xili-language'); ?></em></p>

	 	<fieldset class="box"><legend><?php echo __("Nav menu: Home links in each language",'xili-language'); ?></legend>
	 		<?php
	 			$menu_locations =  get_registered_nav_menus(); // get_nav_menu_locations() keeps data in cache; // only if linked to a content - get_registered_nav_menus() ; // 2.8.8 with has_nav_menu()

		 		$selected_menu_locations = ( isset($this->xili_settings['navmenu_check_options'] ) ) ? $this->xili_settings['navmenu_check_options'] : array();
		 	if ( is_array( $menu_locations ) &&  $menu_locations != array() ) { // 2.8.6 - wp 3.6
		 	?>
		 	<fieldset class="box leftbox">
				<?php _e('Choose location(s) of nav menu(s) where languages list will be automatically inserted. For each location, choose the type of list. Experts can create their own list by using api (hook) available in plugin.','xili-language'); ?>
				<br><strong><?php printf( __("Since version 2.8.8, it is possible to insert the languages list anywhere in the navigation menu via the <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus Builder</a> (drag and drop method).",'xili-language'), "nav-menus.php") ; ?></strong>
				<br><em><?php printf( __("To avoid unwanted double items in navigation menu, choose one of the 2 methods but not both ! The future version will use only menus set in <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus Builder</a>.",'xili-language'), "nav-menus.php") ; ?></em>

			</fieldset>
		 	<fieldset class="box rightbox">
			<?php
			if ( $this->this_has_external_filter('xl_language_list') ) {	// is list of options described
				$this->langs_list_options = array();
				do_action( 'xili_language_list_options', $theoption); // update the list of external action
			}
			echo '<table style="width:98%;"><tbody>';
			foreach ( $menu_locations as $menu_location => $location_id ) {

				$locations_enable = ( isset($selected_menu_locations[$menu_location]) ) ? $selected_menu_locations[$menu_location]['navenable'] : '';

				if ( $locations_enable == 'enable' || ( !isset($this->xili_settings['navmenu_check_options'] ) && isset($this->xili_settings['navmenu_check_option']) && $this->xili_settings['navmenu_check_option'] ==  $menu_location ) )
						$checked = 'checked="checked"'; // ascendant compatibility ( !isset($this->xili_settings['navmenu_check_options']) &&
					else
						$checked = '';

				?>
				<tr><th style="text-align:left;"><label for="xili_navmenu_check_option_<?php echo $menu_location; ?>" class="selectit"><input id="xili_navmenu_check_option_<?php echo $menu_location; ?>" name="xili_navmenu_check_option_<?php echo $menu_location; ?>" type="checkbox" value="enable"  <?php echo $checked; ?> /> <?php echo $menu_location; ?></label>&nbsp;<?php echo ( has_nav_menu ( $menu_location ) ) ? '' : '<abbr title="menu location without content" class="red-alert"> (?) </abbr>' ; ?>
				</th><td><label for="xili_navmenu_check_optiontype_<?php echo $menu_location; ?>"><?php _e('Type','xili-language' ) ?>:
				<select style="width:80%;" name="xili_navmenu_check_optiontype_<?php echo $menu_location; ?>" id="xili_navmenu_check_optiontype_<?php echo $menu_location; ?>">
				<?php
				if ( $this->langs_list_options == array() ) {
						echo '<option value="" >default</option>';
				} else {
					$subtitle = '';
					foreach ($this->langs_list_options as $typeoption) {
						if ( false !== strpos( $typeoption[0], 'navmenu' ) ) {
							$seltypeoption = ( isset( $this->xili_settings['navmenu_check_options'][$menu_location]['navtype']) ) ? $this->xili_settings['navmenu_check_options'][$menu_location]['navtype'] : "";
							if ( $seltypeoption == $typeoption[0] ) $subtitle = $typeoption[2] ; // 2.8.6
							echo '<option title="'. $typeoption[2] .'" value="'.$typeoption[0].'" '. selected($seltypeoption, $typeoption[0], false ).' >'. $typeoption[1] .'</option>';
						}
					}
				}

				?>

				</select></label>
				<?php
				$point = $this->has_insertion_point_list_menu ( $menu_location, $this->insertion_point_dummy_link );
				if ( $point != 0 ) echo '<br />&nbsp; <span class="red-alert">' . __('This menu location contains a language list insertion point !','xili-language') . '</span>'; //  && $checked != ''
				if ( $subtitle != '' ) echo '<br /><span id="title_xili_navmenu_check_optiontype_'.$menu_location.'" ><em>' . $subtitle . '</em></span>';
				?>
				</td></tr>
				<?php

				// focus error
			}
			echo '</tbody></table>';
				?>


			<hr />	<br />
			<label for="nav_menu_separator" class="selectit"><?php _e('Separator before language list (<em>Character or Entity Number or Entity Name</em>)', 'xili-language'); ?> : <input id="nav_menu_separator" name="nav_menu_separator" type="text" value="<?php echo htmlentities(stripslashes($this->xili_settings['nav_menu_separator'])) ?>"  /> </label><br /><br />
	 		<label for="list_in_nav_enable" class="selectit"><input id="list_in_nav_enable" name="list_in_nav_enable" type="checkbox" value="enable"  <?php if($list_in_nav_enable =='enable') echo 'checked="checked"' ?> /> <?php _e('Add language list at end of nav menus checked above', 'xili-language'); ?></label><br />


	 		</fieldset>
	 		<br />
	 		<fieldset class="box leftbox">
	 			<?php echo __("Home menu item will be translated when changing language:",'xili-language'); ?>
	 		</fieldset>
	 		<fieldset class="box rightbox">
	 			<label for="xili_home_item_nav_menu" class="selectit"><input id="xili_home_item_nav_menu" name="xili_home_item_nav_menu" type="checkbox" value="modify"  <?php if($this->xili_settings['home_item_nav_menu'] =='modify') echo 'checked="checked"' ?> /> <?php _e('Menu Home item with language.', 'xili-language'); ?></label>
	 		</fieldset>
	 		<?php if ( $this->show_page_on_front ) { ?>
		 		<br />
		 		<fieldset class="box leftbox">
		 			<?php echo __("Keep original link of frontpage array in menu pages list:",'xili-language'); ?>
		 		</fieldset>
		 		<fieldset class="box rightbox">
		 			<label for="xili_list_pages_check_option" class="selectit"><input id="xili_list_pages_check_option" name="xili_list_pages_check_option" type="checkbox" value="fixe"  <?php if($this->xili_settings['list_pages_check_option'] =='fixe') echo 'checked="checked"' ?> /> <?php _e('One home per language.', 'xili-language'); ?></label>
		 		</fieldset>
	 		<?php } ?>
	 		<br />
	 		<div class="submit"><input  id='innavenable' name='innavenable' type='submit' value="<?php _e('Update','xili-language') ?>" /></div>
	 		<br />

	 		</fieldset>
	 		<br />

	 		<fieldset class="box"><legend><?php echo __("Nav menu: Automatic sub-selection of pages according current language",'xili-language'); ?></legend>
	 			<fieldset class="box leftbox">
					<?php _e('Choose location of nav menu where sub-selection of pages list will be automatically inserted according current displayed language:','xili-language'); ?><br /><?php _e('Args is like in function wp_list_pages, example: <em>include=11,15</em><br />Note: If args kept empty, the selection will done on all pages (avoid it).','xili-language'); ?>
				<br><strong><?php printf( __("Since version 2.9.10, it is possible to insert a list of pages sub-selected according current language anywhere in the navigation menu via the <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus Builder</a> (drag and drop method).",'xili-language'), "nav-menus.php") ; ?></strong>
				<br><em><?php printf( __("To avoid unwanted double items in navigation menu, choose one of the 2 methods but not both ! The future version will use only menus set in <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus Builder</a>.",'xili-language'), "nav-menus.php") ; ?></em>
				</fieldset>
		 		<fieldset class="box rightbox">
	 				<?php

	 				$selected_page_menu_locations = ( isset($this->xili_settings['array_navmenu_check_option_page'] ) ) ? $this->xili_settings['array_navmenu_check_option_page'] : array();
		 			if ( is_array( $menu_locations ) ) {
	 					echo '<table><tbody>';
	 					foreach ( $menu_locations as $menu_location => $location_id ) {
	 						$args= ( isset ( $selected_page_menu_locations[$menu_location]['args'] ) ) ? $selected_page_menu_locations[$menu_location]['args'] : "";
	 						?>
	 						<tr><th style="text-align:left;"><label for="xili_navmenu_check_option_page_<?php echo $menu_location; ?>" class="selectit"><input id="xili_navmenu_check_option_page_<?php echo $menu_location; ?>" name="xili_navmenu_check_option_page_<?php echo $menu_location; ?>" type="checkbox" value="enable"  <?php echo checked ( ( isset ( $selected_page_menu_locations[$menu_location]['enable'] )  ) ? $selected_page_menu_locations[$menu_location]['enable'] : '' , 'enable' ) ; ?> /> <?php echo $menu_location; ?></label>&nbsp;&nbsp;<?php echo ( has_nav_menu ($menu_location) ) ? '' : '<abbr title="menu location without content" class="red-alert"> (?) </abbr>' ; ?>
				</th><td><label for="xili_navmenu_page_args_<?php echo $menu_location; ?>"><?php _e('Args','xili-language' ) ?>:
					<input id="xili_navmenu_page_args_<?php echo $menu_location; ?>" name="xili_navmenu_page_args_<?php echo $menu_location; ?>" type="text" value="<?php echo $args ?>"  />
	 			</label>
	 			<?php
	 			$point = $this->has_insertion_point_list_menu ( $menu_location, $this->insertion_point_dummy_link_page );
				if ( $point != 0 ) echo '<br />&nbsp; <span class="red-alert">' . __('This menu location contains a page sub-list insertion point !','xili-language') . '</span>';
	 			?>
	 			</td></tr>
	 			<?php
	 					}
	 					echo '</tbody></table>';
		 			}
	 				?>

	 		</fieldset>
	 		<br />
		 	<div class="submit"><input  id='pagnavenable' name='pagnavenable' type='submit' value="<?php _e('Update','xili-language') ?>" /></div>
		 	<?php } else {
		 		printf (__("This theme doesn't contain active Nav Menu. List of languages cannot be automatically added.","xili-language"));
		 		echo '<br />';printf (__("See <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus activation</a> settings.","xili-language"), "nav-menus.php");
		 	} ?>

		</fieldset>
	 	<br />
	 	<label for="show-manual-box" class="selectit"><input name="show-manual-box" id="show-manual-box" type="checkbox" value="show">&nbsp;<?php _e('Show toolbox for manual insertion (reserved purposes)','xili-language'); ?></label>
	 	<fieldset id="manual-menu-box" class="box hiddenbox"><legend><?php echo __("Theme's nav menu items settings",'xili-language'); ?></legend>
		 	<p><?php
		 	if ( $menu_locations ) {
		 		$loc_count = count( $menu_locations ); ?>
		 		<fieldset class="box leftbox">
		 			<?php printf (__("This theme (%s) contains %d Nav Menu(s).",'xili-language'), $theme_name, $loc_count); ?>
		 			<p><?php _e('Choose nav menu where languages list will be manually inserted:','xili-language'); ?></p>
		 		</fieldset>
		 		<fieldset class="box rightbox">
		 		<select name="xili_navmenu_check_option2" id="xili_navmenu_check_option2" class="fullwidth">
				<?php
					foreach ($menu_locations as $menu_location => $location_id) {
				if ( isset( $this->xili_settings['navmenu_check_option2'] ) && $this->xili_settings['navmenu_check_option2'] == $menu_location )
						$checked = 'selected = "selected"';
					else
						$checked = '';

				echo '<option value="'.$menu_location.'" '.$checked.' >'.$menu_location.'</option>';
			}
				?>
			</select>
			<br />	<br />
		 	<?php
		 	echo '<br />';printf (__("See <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus</a> settings.","xili-language"), "nav-menus.php");
		 	if($list_in_nav_enable =='enable') {
		 			echo '<br /><span class="red-alert">'.$this->admin_messages['alert']['menu_auto_inserted'].'</span>'; }

		 	?>
		 	</p>
		 	</fieldset>
		 	<br /><?php _e('Do you want to add list of language links at the end ?','xili-language'); ?><br />
			<div class="submit"><input  id='menuadditems' name='menuadditems' type='submit' value="<?php _e('Add menu items','xili-language') ?>" /></div>

		 	<?php } else {
		 		printf (__("This theme doesn't contain active Nav Menu.","xili-language"));
		 		echo '<br />';printf (__("See <a href=\"%s\" title=\"Menu Items definition\">Appearance Menus</a> settings.","xili-language"), "nav-menus.php");
		 	} ?>
	 	</fieldset>
	 	<?php }


		if ( $this->xili_settings['functions_enable'] !='' && function_exists('xiliml_setlang_of_undefined_posts')) {
			?><p><?php _e("Special Gold Actions",'xili-language') ?></p><?php
			xiliml_special_UI_undefined_posts ($this->langs_group_id);
		}
	}
	/**
	 *
	 * @since 2.11.1
	 *
	 */
	function on_box_plugins_expert( $data ) {
		//extract( $data );
		$checked = checked ($this->xili_settings['enable_fc_theme_class'], 'enable', false ) ;
		?>
		<p><?php _e('Define some behaviours with plugins like JetPack.','xili-language'); ?></p>
	 	<fieldset id="jetpack-box" class="box"><legend><?php echo __("JetPack settings",'xili-language'); ?></legend>
	 		<?php if ( class_exists ('jetpack')) { ?>
	 		<label for="enable_fc_theme_class" class="selectit">
	 			<input <?php echo $checked; ?> name="enable_fc_theme_class" id="enable_fc_theme_class" type="checkbox" value="enable">
	 			&nbsp;<?php _e('Give priority to class - Featured_Content - of current theme.','xili-language'); ?>
	 		</label>
			<div class="submit"><input  id='jetpack_fc_enable' name='jetpack_fc_enable' type='submit' value="<?php _e('Update','xili-language') ?>" /></div>
			<?php } else {
				echo '<p>' . __('JetPack plugin is not active.','xili-language') . '</p>';
			} ?>
		</fieldset>
	 	<?php
	}

	function on_box_mail_content ( $data ) {
		extract( $data );
		global $wp_version ;
		$theme = ( isset ($this->xili_settings['theme']) ) ? $this->xili_settings['theme'] : "";
		$wplang = ( isset ($this->xili_settings['wplang']) ) ? $this->xili_settings['wplang'] : "";
		$xiliplug = ( isset ($this->xili_settings['xiliplug']) ) ? $this->xili_settings['xiliplug'] : "";
		if ( '' != $emessage ) { ?>
	 		<h4><?php _e('Note:','xili-language') ?></h4>
			<p><strong><?php echo $emessage;?></strong></p>
		<?php } ?>
		<fieldset class="mailto"><legend><?php _e('Mail to dev.xiligroup', 'xili-language'); ?></legend><p class="textright">
		<label for="ccmail"><?php _e('Cc: (Reply to:)','xili-language'); ?>
		<input class="widefat width23" id="ccmail" name="ccmail" type="text" value="<?php bloginfo ('admin_email') ; ?>" /></label><br /><br /></p><p class="textleft">
		<?php if ( false === strpos( get_bloginfo ('url'), 'local' ) ){ ?>
			<label for="urlenable">
				<input type="checkbox" id="urlenable" name="urlenable" value="enable" <?php if( isset ($this->xili_settings['url']) && $this->xili_settings['url']=='enable') echo 'checked="checked"' ?> />&nbsp;<?php bloginfo ('url') ; ?>
			</label><br />
		<?php } else { ?>
			<input type="hidden" name="onlocalhost" id="onlocalhost" value="localhost" />
		<?php } ?>
		<br /><em><?php _e('When checking and giving detailled infos, support will be better !', 'xili-language'); ?></em><br />
		<label for="themeenable">
			<input type="checkbox" id="themeenable" name="themeenable" value="enable" <?php if( $theme == 'enable' ) echo 'checked="checked"' ?> />&nbsp;<?php echo "Theme name= ".get_option ('stylesheet') ; ?>
		</label><br />
		<?php if (''!= WPLANG ) {?>
		<label for="wplangenable">
			<input type="checkbox" id="wplangenable" name="wplangenable" value="enable" <?php if( $wplang == 'enable' ) echo 'checked="checked"' ?> />&nbsp;<?php echo "WPLANG= ".WPLANG ; ?>
		</label><br />
		<?php } ?>
		<label for="versionenable">
			<input type="checkbox" id="versionenable" name="versionenable" value="enable" <?php if( isset ($this->xili_settings['version-wp']) && $this->xili_settings['version-wp']=='enable') echo 'checked="checked"' ?> />&nbsp;<?php echo "WP version: ".$wp_version ; ?>
		</label><br />
		<?php if ( get_option('permalink_structure') ) { // 2.10.0 - ?>
		<label for="versionenable">
			<input type="checkbox" id="permalink_structure" name="permalink_structure" value="enable" <?php if( isset ($this->xili_settings['permalink_structure']) && $this->xili_settings['permalink_structure']=='enable') echo 'checked="checked"' ?> />&nbsp;<?php echo "Permalink structure: <small>".get_option('permalink_structure') ."</small>"; ?>
		</label><br />
		<?php } ?>
		<br />
		<?php $list = $this->check_other_xili_plugins();
		if (''!= $list ) {?>
		<label for="xiliplugenable">
			<input type="checkbox" id="xiliplugenable" name="xiliplugenable" value="enable" <?php if( $xiliplug == 'enable' ) echo 'checked="checked"' ?> />&nbsp;<?php echo "Other xili plugins = ".$list ; ?>
		</label><br /><br />
		<?php } ?>
		</p><p class="textright">
		<label for="webmestre"><?php _e('Type of webmaster:','xili-language'); ?>
		<select name="webmestre" id="webmestre" class="width23">
			<?php if ( !isset ( $this->xili_settings['webmestre-level'] ) ) $this->xili_settings['webmestre-level'] = '?' ; ?>
			<option value="?" <?php selected( $this->xili_settings['webmestre-level'], '?' ); ?>><?php _e('Define your experience as webmaster…','xili-language'); ?></option>
			<option value="newbie" <?php selected( $this->xili_settings['webmestre-level'], "newbie" ); ?>><?php _e('Newbie in WP','xili-language'); ?></option>
			<option value="wp-php" <?php selected( $this->xili_settings['webmestre-level'], "wp-php" ); ?>><?php _e('Good knowledge in WP and few in php','xili-language'); ?></option>
			<option value="wp-php-dev" <?php selected( $this->xili_settings['webmestre-level'], "wp-php-dev" ); ?>><?php _e('Good knowledge in WP, CMS and good in php','xili-language'); ?></option>
			<option value="wp-plugin-theme" <?php selected( $this->xili_settings['webmestre-level'], "wp-plugin-theme" ); ?>><?php _e('WP theme and /or plugin developper','xili-language'); ?></option>
		</select></label><br /><br />
		<label for="subject"><?php _e('Subject:','xili-language'); ?>
		<input class="widefat width23" id="subject" name="subject" type="text" value="" /></label>
		<select name="thema" id="thema" class="width23">
			<option value="" ><?php _e('Choose topic...','xili-language'); ?></option>
			<option value="Message" ><?php _e('Message','xili-language'); ?></option>
			<option value="Question" ><?php _e('Question','xili-language'); ?></option>
			<option value="Encouragement" ><?php _e('Encouragement','xili-language'); ?></option>
			<option value="Support need" ><?php _e('Support need','xili-language'); ?></option>
		</select>
		<textarea class="widefat width45" rows="5" cols="20" id="mailcontent" name="mailcontent"><?php _e('Your message here…','xili-language'); ?></textarea>
		</p></fieldset>
		<p>
		<?php _e('Before send the mail, be accurate, check the infos to inform support and complete textarea. A copy (Cc:) is sent to webmaster email (modify it if needed).','xili-language'); ?>
		</p>
		<?php wp_nonce_field('xili-plugin-sendmail'); ?>
		<div class='submit'>
		<input id='sendmail' name='sendmail' type='submit' tabindex='6' value="<?php _e('Send email','xili-language') ?>" /></div>

		<div class="clearb1">&nbsp;</div><br/>
		<?php
	}

	/**
	 * If checked, functions in uninstall.php will be fired when deleting the plugin via plugins list.
	 *
	 * @since 1.8.8
	 */
	function on_sidebox_uninstall_content ( $data ) {
		extract( $data );
		$delete = ( is_multisite() ) ? 'delete_this' : 'delete';
	?>
	<p class="red-alert"><?php echo $this->admin_messages['alert']['plugin_deinstalling']; ?></p>
	<label for="delete_settings">
			<input type="checkbox" id="delete_settings" name="delete_settings" value="<?php echo $delete ?>" <?php if( in_array ( $this->xili_settings['delete_settings'] , array ( 'delete_this', 'delete' ) ) ) echo 'checked="checked"' ?> />&nbsp;<?php _e("Delete DB plugin's datas",'xili-language') ; ?>
	</label>
	<div class='submit'>
		<input id='uninstalloption' name='uninstalloption' type='submit' tabindex='6' value="<?php _e('Update','xili-language') ?>" /></div>
	<?php
	}

	/**
	 * main setting window
	 * the list
	 * clear:none - compat 3.3 - 3.4
	 */
	function on_box_lang_list_content( $data ) {
		extract($data); ?>
					<table class="widefat" style="clear:none;">
						<thead>
						<tr>
						<th scope="col" class="head-id" ><?php _e('ID') ?></th>
	        			<th scope="col"><?php _e('ISO Name','xili-language') ?></th>
	        			<?php if ( $this->alias_mode ) {
	        				echo '<th scope="col">'.__('Alias','xili-language') . '</th>';
	        			} ?>
	        			<th scope="col"><?php _e('Full name','xili-language') ?></th>
	        			<th scope="col"><?php _e('Native','xili-language') ?></th>
	        			<th scope="col"><?php _e('Slug','xili-language') ?></th>
	        			<th scope="col"><?php _e('Order','xili-language') ?></th>
	        			<th scope="col"><?php _e('Vis.','xili-language') ?></th>
	        			<th scope="col"><?php _e('Dashb.','xili-language') ?></th>
	        			<th scope="col" class="head-count" ><?php _e('Posts') ?></th>
	        			<th scope="col" class="head-action" ><?php _e('Action') ?></th>
						</tr>
						</thead>
						<tbody id="the-list">
							<?php $this->available_languages_row(); /* the lines #2260 */ ?>
						</tbody>
					</table>
	<?php
	}

	/**
	 * form to create or edit one language
	 */
	function on_box_lang_form_content( $data ) {
		extract($data);
		if ( $this->alias_mode  ) { // used for flush alias refreshing in permalink-class - 2.11  ?>
			<input type="hidden" id="language_settings_action" name="language_settings_action" value="<?php echo $action ?>" />
		<?php } ?>
		<h2 id="addlang" <?php if ($action=='delete') echo 'class="red-alert"'; ?>><?php echo $formtitle ;  ?></h2>
		<?php if ($action=='edit' || $action=='delete') :?>
			<input type="hidden" name="language_term_id" value="<?php echo $language->term_id ?>" />

		<?php endif; ?>
		<?php if ( $action=='delete') :?>

			<input type="hidden" name="language_nicename" value="<?php echo $language->slug ?>" />
		<?php endif; ?>
		<table class="editform" width="100%" cellspacing="2" cellpadding="5">
			<tr>
				<th width="33%" scope="row" valign="middle" align="right"><label for="language_name_list"><?php _e('Examples', 'xili-language') ?></label>:&nbsp;</th>
				<td width="67%"><select name="language_name_list" id="language_name_list">
					<?php $this->example_langs_list($language->name, $action);  ?>
				</select>&nbsp;<small> <a href="http://www.gnu.org/software/hello/manual/gettext/Usual-Language-Codes.html#Usual-Language-Codes" target="_blank"><?php _e('ISO Language-Codes','xili-language'); ?></a></small>&nbsp;_&nbsp;<small><a href="http://www.gnu.org/software/hello/manual/gettext/Country-Codes.html#Country-Codes" target="_blank"><?php _e('ISO Country-Codes','xili-language'); ?></a></small><br />&nbsp;</td>
			</tr>
			<tr>
				<th scope="row" valign="middle" align="right"><label for="language_name"><?php _e('ISO Name', 'xili-language') ?></label>:&nbsp;</th>
				<td ><input name="language_name" id="language_name" type="text" value="<?php echo esc_attr($language->name); ?>" size="10" <?php if($action=='delete') echo 'disabled="disabled"' ?> />  <small>(<?php printf( __("two or five chars like 'ja' or 'zh_TW', see %s docs", 'xili-language'), '<a href="'.$this->wikilink.'" target="_blank" >wiki</a>'); ?>)</small></td>
			</tr>

			<?php if ( $this->alias_mode  ) {  // 2.8.2
				if (  $language->slug != '' ) {
					$alias_val = ( $this->lang_slug_qv_trans ( $language->slug ) == $language->slug ) ? '' : $this->lang_slug_qv_trans ( $language->slug );
					if ( '' == $alias_val )
						$alias_val = substr( $language->slug, 0, 2 );
				} else {
					$alias_val = "";
				}
			?>

			<tr>
				<th scope="row" valign="middle" align="right"><label for="language_alias"><?php _e('Alias','xili-language') ?></label>:&nbsp;</th>
				<td><input name="language_alias" id="language_alias" size="20" type="text" value="<?php echo esc_attr($alias_val) ?>" <?php if($action=='delete') echo 'disabled="disabled"' ?> />  <small>(<?php _e('as visible in query or permalink on front-end.', 'xili-language'); ?>,…)</small>
					<?php
					// used for flush alias refreshing in permalink-class - 2.11
					$listlanguages = get_terms_of_groups_lite ( $this->langs_group_id, TAXOLANGSGROUP, TAXONAME, 'ASC' );
					foreach ( $listlanguages as $one_language ) {
						$one_alias_val = $this->lang_slug_qv_trans ( $one_language->slug );
						if ( $one_language->slug ==  $language->slug ) {
							echo  '<input name="prev_language_alias" id="prev_language_alias" type="hidden" value="'.$one_alias_val.'" />';
						} else {
							echo  '<input name="list_language_alias['.$one_language->slug.']" id="list_language_alias['.$one_language->slug.']" type="hidden" value="'.$one_alias_val.'" />';
						}
					}
					?>
				</td>

			</tr>

			<?php } ?>

			<tr>
				<th scope="row" valign="middle" align="right"><label for="language_description"><?php _e('Full name','xili-language') ?></label>:&nbsp;</th>
				<td><input name="language_description" id="language_description" size="20" type="text" value="<?php echo esc_attr($language->description); ?>" <?php if($action=='delete') echo 'disabled="disabled"' ?> />  <small>(<?php _e('as visible in list or menu: english, chinese', 'xili-language'); ?>,…)</small></td>

			</tr>

			<tr>
				<th scope="row" valign="middle" align="right"><label for="language_nicename"><?php _e('Language slug','xili-language') ?></label>:&nbsp;</th>
				<td><input name="language_nicename" id="language_nicename" type="text" value="<?php echo esc_attr($language->slug); ?>" size="10" <?php if( $action=='delete' ) echo 'disabled="disabled"' ?> />
				<?php if ('' != $language->slug ) {
					$cur_locale = GP_Locales::by_field( 'wp_locale', $language->name );
					if ( $cur_locale ) {
						$native = $cur_locale->native_name ;
					} else {
						$cur_locale = GP_Locales::by_slug( $language->slug );
						$native = ( $cur_locale ) ? $cur_locale->native_name . ' *' : '' ;
					}
					if ($native )
						printf ( '&nbsp;&nbsp;&nbsp;&nbsp;<strong>' . __('Native Name: %s','xili-language') . '</strong>' , $native );
					}
					?>
				</td>
			</tr>

			<tr>
				<th scope="row" valign="middle" align="right"><label for="language_order"><?php _e('Order','xili-language') ?></label>:&nbsp;</th>
				<td><input name="language_order" id="language_order" size="3" type="text" value="<?php echo esc_attr($language->term_order); ?>" <?php if( $action=='delete' ) echo 'disabled="disabled"' ?> />&nbsp;&nbsp;&nbsp;<small>
					<label for="language_hidden"><?php _e('hidden','xili-language') ?>&nbsp;<input name="language_hidden" id="language_hidden" type="checkbox" value="hidden" <?php if($action=='delete') echo 'disabled="disabled"' ?> <?php if($language_features['hidden']=='hidden') echo 'checked="checked"' ?> /></label>&nbsp;&nbsp;
					<label for="language_charset"><?php _e('Server Entities Charset:','xili-language') ?>&nbsp;<input name="language_charset" id="language_charset" type="text" value="<?php echo $language_features['charset'] ?>" size="25" <?php if($action=='delete') echo 'disabled="disabled"' ?> /></label></small>

				</td>
			</tr>
			<?php if ( $action=='delete' ) :?>
			<tr>
				<th scope="row" valign="top" align="right"><label for="multilingual_links_erase"><span class="red-alert" ><?php echo $this->admin_messages['alert']['erasing_language']; ?></span></label>&nbsp;:&nbsp;</th>
				<td><input name="multilingual_links_erase" id="multilingual_links_erase" type="checkbox" value="erase" /></td>

			</tr>
			<?php endif; ?>
			<tr>
			<th><p class="submit"><input type="submit" name="reset" value="<?php echo $cancel_text ?>" /></p></th>
			<td>
			<p class="submit"><input type="submit" name="submit" value="<?php echo $submit_text ?>" /></p>
			</td>
			</tr>
		</table>
	<?php
	}

	/**
	 * private functions for admin page : the language example list
	 * @since 1.6.0
	 */
	function example_langs_list($language_name, $state) {

		/* reduce list according present languages in today list */
		if ($state != 'delete' && $state != 'edit') {
			$listlanguages = get_terms_of_groups_lite ($this->langs_group_id,TAXOLANGSGROUP,TAXONAME,'ASC');
			foreach ($listlanguages as $language) {
			 	if ( array_key_exists($language->name, $this->examples_list))  unset ($this->examples_list[$language->name]);
			}
		}
		//
		echo '<option value="">'.__('Choose…','xili-language').'</option>';
		foreach($this->examples_list AS $key=>$value) {
			$selected = (''!=$language_name && $language_name == $key) ? 'selected=selected' : '';
			echo '<option value="'.$key.'" '.$selected.'>'.$value.' ('.$key.')</option>';
		}
	}

	/**
	 * add styles in options
	 *
	 * @since 2.6
	 *
	 */
	 function print_styles_options_language_page ( ) { // first tab

	 	echo "<!---- xl options css 1  ----->\n";
		echo '<style type="text/css" media="screen">'."\n";
			echo ".red-alert {color:red;}\n";
			echo ".minwidth {min-width:840px !important ;}\n";
			echo "th.head-id { color:red ; width:60px; }\n";
			echo "th.head-count { text-align: center !important; width: 60px; }\n";
			echo "th.head-action { text-align: center !important; width: 140px; }\n";
			echo ".col-center { text-align: center; }\n";
		 	echo "th.lang-id { font-size:70% !important; }\n";
		 	echo "span.lang-flag { display:inline-block; height: 18px; }\n";
		 	echo ".box { margin:2px; padding:12px 6px; border:1px solid #ccc; } \n";
		 	echo ".themeinfo {margin:2px 2px 5px; padding:12px 6px; border:1px solid #ccc;} \n";
	 	echo "</style>\n";

	 	if ( $this->exists_style_ext && $this->xili_settings['external_xl_style'] == "on" ) wp_enqueue_style( 'xili_language_stylesheet' );
	 }

	 function print_styles_options_language_tabs ( ) {  // the 2 others tabs

	 	echo "<!---- xl options css 2 to 3  ----->\n";
		echo '<style type="text/css" media="screen">'."\n";
			echo ".red-alert {color:red;}\n";
			echo ".minwidth {min-width:840px !important ;}\n";
			echo ".fullwidth { width:97%; }\n";
			echo ".width23 { width:70% ; }\n";
			echo ".box { margin:2px; padding:6px 6px; border:1px solid #ccc; } \n";
			echo ".langbox { margin:2px 2px 6px; padding:0px 6px 6px; border-bottom:1px solid #ccc; } \n";
			echo ".langbox h4 { color:#333; } \n";
			echo ".themebox { margin:6px 2px 6px 40px; padding:2px 6px 6px; border:1px solid #ccc; } \n";
			echo ".themebox legend { font-style:italic; } \n";
			echo ".hiddenbox {display:none}\n";
			echo ".rightbox { margin:2px 5px 2px 49%; width:47%;} \n";
			echo ".leftbox {border:0px; width:44%; float:left;} \n";
			echo ".clearb1 {clear:both; height:1px;} \n";
			echo ".themeinfo {margin:2px 2px 5px; padding:12px 6px; border:1px solid #ccc;} \n";
	 	echo "</style>\n";

	 	if ( $this->exists_style_ext && $this->xili_settings['external_xl_style'] == "on" ) wp_enqueue_style( 'xili_language_stylesheet' );
	 }

	 function print_styles_options_language_support ( ) {

	 	echo "<!---- xl options css 4  ----->\n";
	 	echo '<style type="text/css" media="screen">'."\n";
	 		echo ".red-alert {color:red;}\n";
	 		echo ".minwidth {min-width:840px !important;}\n";
	 		echo ".textleft {text-align:left;}\n";
	 		echo ".textright {text-align:right;}\n";
	 		echo ".fullwidth { width:97%; }\n";
	 		echo ".width23 { width:70% !important; }\n";
	 		echo ".width45 { width:80% !important; }\n";
	 		echo ".boldtext {font-size:1.15em;}\n";
	 		echo ".mailto {margin:2px; padding:12px 100px 12px 30px; border:1px solid #ccc; }\n";
	 	echo "</style>\n";

	 	if ( $this->exists_style_ext && $this->xili_settings['external_xl_style'] == "on" ) wp_enqueue_style( 'xili_language_stylesheet' );
	 }

	/**
	 * private functions for admin page : the language list
	 * @since 0.9.0
	 *
	 * @update 0.9.5 : two default languages if taxonomy languages is empty
	 * @update 1.8.8 : fixes slug of defaults
	 * @update 1.8.9.1 : visible = *
	 * @updated 2.6 : style
	 * @updated 2.7.1 : default full name
	 * @updated 2.11.2 : detailled counter in title
	 */
	function available_languages_row() {
		global $wpdb;
		/*list of languages*/
		$listlanguages = get_terms_of_groups_lite ( $this->langs_group_id, TAXOLANGSGROUP, TAXONAME, 'ASC' );
		if ( empty($listlanguages) ) { /*create two default lines with the default language (as in config)*/
		  	/* language of WP */
			$term = 'en_US';
			$args = array( 'alias_of' => '', 'description' => 'english', 'parent' => 0, 'slug' =>'en_us');
			$theids = $this->safe_lang_term_creation ( $term, $args );
			if ( ! is_wp_error($theids) ) {
				wp_set_object_terms($theids['term_id'], 'the-langs-group', TAXOLANGSGROUP);
			}
		 	$term = $this->default_lang;
		 	$desc = ( isset($this->examples_list[$term]) ) ? $this->examples_list[$term] : $this->default_lang;
		 	$desc_array = explode (' (', $desc );
		 	$desc = $desc_array[0];
		 	$slug = strtolower( $this->default_lang ) ; // 2.3.1
		 	if ( !defined('WPLANG') || $this->default_lang == 'en_US' || $this->default_lang == '' ) {
		 		$term = 'fr_FR'; $desc = 'French'; $slug = 'fr_fr' ;
		 	}
		 	$args = array( 'alias_of' => '', 'description' => $desc, 'parent' => 0, 'slug' => $slug);

		 	$theids = $this->safe_lang_term_creation ( $term, $args );
		 	if ( ! is_wp_error($theids) ) {
		 		wp_set_object_terms($theids['term_id'], 'the-langs-group', TAXOLANGSGROUP);
		 	}
			$listlanguages = get_terms_of_groups_lite ($this->langs_group_id,TAXOLANGSGROUP,TAXONAME,'ASC');
		}
		$trclass = '';



		foreach ($listlanguages as $language) {

			$trclass = ((defined('DOING_AJAX') && DOING_AJAX) || ' alternate' == $trclass ) ? '' : ' alternate';
			$language->count = number_format_i18n( $language->count );
			// count for each CPT
			$counts = array();
			$title = array();
			$custompoststype = $this->authorized_custom_post_type();

			foreach ( $custompoststype as $key => $customtype ) {
				if ( $customtype['multilingual'] == 'enable' ) {
					$counts[$key] = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND post_status = 'publish' AND term_taxonomy_id = %d AND post_type = %s", $language->term_id, $key ) );
					$title[] = $customtype['name'] .' = ' . $counts[$key];
				}
			}
			$title = implode (' | ', $title );
			$posts_count = ( $language->count > 0 ) ? '<a title= "'.$title.'" href="edit.php?lang='.$language->slug.'">'.$language->count . '</a>' : $language->count;

			/* edit link*/
			// nounce added
			$link = wp_nonce_url( "?action=edit&amp;page=language_page&amp;term_id=".$language->term_id, "edit-".$language->term_id );

			$edit = "<a href='".$link."' >".__( 'Edit' )."</a>&nbsp;|";
			/* delete link*/
			// nounce added
			$link = wp_nonce_url( "?action=delete&amp;page=language_page&amp;term_id=".$language->term_id, "delete-".$language->term_id );

			$edit .= "&nbsp;<a href='".$link."' class='delete'>".__( 'Delete' )."</a>";

			$h = ( isset ( $this->xili_settings['lang_features'][$language->slug]['hidden'] ) && $this->xili_settings['lang_features'][$language->slug]['hidden'] == 'hidden') ? "&nbsp;" : "&#10004;";
			$h .= ( isset ( $this->xili_settings['lang_features'][$language->slug]['charset'] ) && $this->xili_settings['lang_features'][$language->slug]['charset'] != '') ? "&nbsp;+" : "";

			$is_mo = ! empty( $language->name ) && array_key_exists( $language->name, (array) $this->available_languages() );

			$mo_available_for_dashboard = ( $is_mo ) ? "&#10004;" : "";

			$line = '<tr id="lang-'.$language->term_id.'" class="lang-'. $language->slug . $trclass . '" >'
			.'<th scope="row" class="lang-id" ><span class="lang-flag">'.$language->term_id.'<span></th>'
			.'<td>' . $language->name . '</td>';

			$cur_locale = GP_Locales::by_field( 'wp_locale', $language->name );
			if ( $cur_locale ) {
				$native = $cur_locale->native_name ;
			} else {
				$cur_locale = GP_Locales::by_slug( $language->slug );
				$native = ( $cur_locale ) ? $cur_locale->native_name . ' *' : '' ;
			}

			if ( $this->alias_mode ) {
				$alias_val = ( $this->lang_slug_qv_trans ( $language->slug ) == $language->slug && 2 != strlen($language->slug)) ? ' ? ' : $this->lang_slug_qv_trans ( $language->slug );

				$key_slug = array_keys ( $this->langs_slug_shortqv_array, $alias_val ) ;

				if ( count ( $key_slug ) == 1 ) {
					$line .= '<td>' . $alias_val . '</td>';
				} else {
					$line .= '<td><span class="red-alert">' . $alias_val . '</span></td>';
				}
			}

			$line .= '<td>' . $language->description . '</td>'
			.'<td>' . $native . '</td>'
			.'<td>' . $language->slug . '</td>'
			.'<td>' . $language->term_order . '</td>'
			.'<td class="col-center" >'. $h . '</td>'
			.'<td class="col-center" >'. $mo_available_for_dashboard . '</td>'
			.'<td class="col-center" >' . $posts_count . '</td>'
			.'<td class="col-center" >'. $edit . "</td>\n\t</tr>\n";

			echo $line;

		}

	}

	/**
	 * Recursive search of files in a path
	 * @since 1.1.9
	 * @update 1.2.1 - 1.8.5
	 *
	 */
	 function find_files( $path, $pattern, $callback ) {
 		//$path = rtrim(str_replace("\\", "/", $path), '/') . '/';

		  $matches = Array();
		  $entries = Array();
		  $dir = dir($path);

		  while (false !== ($entry = $dir->read())) {
		    $entries[] = $entry;
		  }
		  $dir->close();
		  foreach ($entries as $entry) {
		    $fullname = $path .$this->ossep. $entry;
		    if ($entry != '.' && $entry != '..' && is_dir($fullname)) {
		      $this->find_files($fullname, $pattern, $callback);
		    } else if (is_file($fullname) && preg_match($pattern, $entry)) {
		      call_user_func( $callback, $path , $entry );
		    }
		  }

	}

	/**
	 * display lines of files in special sidebox
	 * @since 1.1.9
	 *
	 * @updated 1.8.8
	 */
	function available_mo_files( $path , $filename ) {
  		$shortfilename = str_replace(".mo","",$filename );
  		$alert = '<span class="red-alert">'.__('Uncommon filename','xili-language').'</span>' ;
  		if ( strlen($shortfilename)!=5 && strlen($shortfilename) != 2  ) {
  		  if ( false === strpos( $shortfilename, 'local-' ) ) {
  		  	$message = $alert;
  		  } else {
  		  	$message = '<em>'.__("Site's values",'xili-language').'</em>';
  		  }

  		} else if (  false === strpos( $shortfilename, '_' ) && strlen($shortfilename) == 5 )  {
  			$message = $alert;
  		} else {
  			$message = '';
  		}
  		// $this->get_template_directory

  		if ( !is_child_theme() ) {
  			$theme_directory = $this->get_template_directory;
  		} elseif ( is_child_theme() ) {
  			if ( false === strpos ( $path, $this->get_parent_theme_directory . '/' )  )  {  // / to avoid -xili
  				$theme_directory = $this->get_template_directory; // show child
  			} else {
  				$theme_directory = $this->get_parent_theme_directory; // show parent
  			}
  		}

  		echo $shortfilename. " (".$this->ossep.str_replace($this->ossep,"",str_replace( $theme_directory, '', $path )).") ".$message."<br />";
	}


	/********************************** Edit Post UI ***********************************/

	/**
	 * style for new dashboard
	 * @since 2.5
	 * @updated 2.6
	 */
	function admin_init () {
				// test successively style file in theme, plugins, current plugin subfolder
		if ( file_exists ( get_stylesheet_directory().'/xili-css/xl-style.css' ) ) { // in child theme
				$this->exists_style_ext = true;
				$this->style_folder = get_stylesheet_directory_uri();
				$this->style_flag_folder_path = get_stylesheet_directory () . '/images/flags/';
				$this->style_message = __( 'xl-style.css is in sub-folder <em>xili-css</em> of current theme folder', 'xili-language' );
		} elseif ( file_exists( WP_PLUGIN_DIR . $this->xilidev_folder . '/xili-css/xl-style.css' ) ) { // in plugin xilidev-libraries
				$this->exists_style_ext = true;
				$this->style_folder = plugins_url() . $this->xilidev_folder;
				$this->style_flag_folder_path = WP_PLUGIN_DIR . $this->xilidev_folder . '/xili-css/flags/' ;
				$this->style_message = sprintf( __( 'xl-style.css is in sub-folder <em>xili-css</em> of %s folder', 'xili-language' ), $this->style_folder );
		} elseif ( file_exists ( $this->plugin_path.'/xili-css/xl-style.css' ) ) { // in current plugin
				$this->exists_style_ext = true;
				$this->style_folder = $this->plugin_url ;
				$this->style_flag_folder_path = $this->plugin_path . '/xili-css/flags/' ;
				$this->style_message = __( 'xl-style.css is in sub-folder <em>xili-css</em> of xili-language plugin folder (example)', 'xili-language' );
		} else {
				$this->style_message = __( 'no xl-style.css', 'xili-language' );
		}
		if ( $this->exists_style_ext ) wp_register_style( 'xili_language_stylesheet', $this->style_folder . '/xili-css/xl-style.css' );
	}



	/**
	 * Add Translations Dashboard in post edit screen
	 *
	 * @since 2.5
	 *
	 */
	function add_custom_box_in_post_edit () {

		$custompoststype = $this->authorized_custom_post_type();

		foreach ( $custompoststype as $key => $customtype ) {
			if ( $customtype['multilingual'] == 'enable' ) {
				$plural_name = $customtype['name'] ;
	 			$singular_name = $customtype['singular_name'] ;
				add_meta_box( 'post_state', sprintf(__("%s of this %s",'xili-language'), __('Translations', 'xili-language'), $singular_name ), array(&$this,'post_state_box'), $key, 'normal', 'high' );
			}
		}
	}

	/**
	 * Display content and parts of translations dashboard metabox
	 *
	 * @since 2.5
	 *
	 */
	function post_state_box () {
	  global $post_ID ;
	  ?>
<div id="msg-states">
	  <?php

	    $curlang = $this->post_translation_display ( $post_ID );

	  ?>
</div>
<div id="msg-states-comments">
	  <?php
	  $this->post_status_addons ( $post_ID, $curlang );

	  ?>
	<p class="docinfos" ><?php printf(__( 'This list gathers together the titles and infos about (now and future) linked posts by language. For more info, visit the <a href="%s">wiki</a> website.', 'xili-language' ), $this->wikilink) ; ?></p>
	<p class="xlversion">©xili-language v. <?php echo XILILANGUAGE_VER; ?></p>
</div>
<div class="clearb1">&nbsp;</div>
	  <?php
	}

	/**
	 * Display main part and list of translation dashboard metabox
	 *
	 * @since 2.5
	 *
	 */
	function post_translation_display ( $post_ID ) {
		global $post ;
		$postlang = '';
		$test = ($post->post_status == 'auto-draft') ? false : true ;
		if ($test === true){
			$postlang = $this->get_post_language( $post_ID );
		} else {
			$postlang = ""; /* new post */
		}

		$listlanguages = get_terms_of_groups_lite ( $this->langs_group_id, TAXOLANGSGROUP, TAXONAME, 'ASC');

		if ( $this->xili_settings['authorbrowseroption'] == 'authorbrowser' ) { // setting = select language of author's browser
			$listofprefs = $this->the_preferred_languages();
			if ( is_array( $listofprefs ) ) {
				arsort($listofprefs, SORT_NUMERIC);
				$sitelanguage = $this->match_languages ( $listofprefs, $listlanguages );
				if ( $sitelanguage ) {
					$defaultlanguage = $sitelanguage->slug;
				} else {
					$defaultlanguage = "";
				}
				$mention = __('Your browser language preset by default for this new post...', 'xili-language') ;
			} else {
				$defaultlanguage = ""; /* undefined */
			}
		} elseif ( $this->xili_settings['authorbrowseroption'] == 'authordashboard' ) {
			$current_dash_lang = strtolower( $this->admin_side_locale() );
			if ( isset( $this->langs_slug_name_array[$current_dash_lang]) ) {
				$defaultlanguage = $current_dash_lang;
				$mention = __('Your dashboard language preset by default for this new post...', 'xili-language') ;
			} else {
				$defaultlanguage = ""; /* undefined */
			}
		} else {
			$defaultlanguage = ""; /* undefined */
			$mention = "";
		}
		$this->authorbrowserlanguage = $defaultlanguage; // for right box

		if ( isset ($_GET['xlaction'] ) && isset ($_GET['xllang']) ) {
			// create new translation
			$targetlang = $_GET['xllang'];
			if ( $_GET['xlaction'] == 'transcreate' )
				$translated_post_ID = $this->create_initial_translation ( $targetlang, $post->post_title , $postlang, $post_ID );

				if ( $translated_post_ID > 0 && $this->xili_settings['creation_redirect'] == 'redirect') {
					$url_redir = admin_url().'post.php?post='.$translated_post_ID.'&action=edit';

				?>
   <script type="text/javascript">
   <!--
      window.location= <?php echo "'" . $url_redir . "'"; ?>;
   //-->
   </script>
<?php
				}
		} //elseif ( isset ($_GET['xlaction'] ) && $_GET['xlaction'] == 'refresh' ) {
		if ( $postlang != ""  ) {	// refresh only if defined
			foreach ( $listlanguages as $language ) {
				if ( $language->slug != $postlang ) {
					$otherpost = $this->linked_post_in( $post_ID, $language->slug ) ;
					if ( $otherpost ) {
						$linepost = $this->temp_get_post ( $otherpost );
						if ( $linepost && $otherpost != $post_ID) {
							// search metas of target
							$metacurlang = $this->get_cur_language( $linepost->ID ) ; // array
							foreach ( $listlanguages as $metalanguage ) {
								if ( $metalanguage->slug != $postlang && $metalanguage->slug != $metacurlang[QUETAG] ) {
									$id = get_post_meta( $linepost->ID, QUETAG.'-'.$metalanguage->slug, true );
									$locid = get_post_meta( $post_ID, QUETAG.'-'.$metalanguage->slug, true ); // do not erase
									if ( $id != "" && $locid =='' && $id != $post_ID ) {
										update_post_meta( $post_ID, QUETAG.'-'.$metalanguage->slug, $id );
									}
								}
								if ( $metalanguage->slug == $postlang ) {
									update_post_meta( $linepost->ID, QUETAG.'-'.$metalanguage->slug, $post_ID );
								}
							}

						} else {
							delete_post_meta ( $post_ID,  QUETAG.'-'.$language->slug );
						}
					}
				}
			} // for
		}
		if ( isset ($_GET['xlaction'] ) && $_GET['xlaction'] == 'propataxo' ) {
			$this->propagate_categories_to_linked ( $post_ID, $postlang );
		}


		$post_type = $post->post_type ;

		$post_type_object = get_post_type_object( $post_type );

		$i = 0;
		// table of languages - asc sorted
		?>
		<table id="postslist" class="widefat">
		<thead>
		<tr><th class="language" ><?php _e('Language','xili-language'); ?></th><th class="postid"><?php _e('ID', 'xili-language'); ?></th><th class="title"><?php _e('Title','xili-language'); ?></th><th class="status" ><?php _e('Status'); ?></th><th class="action" ><?php _e('Edit'); ?></th></tr>
		</thead>
		<tbody id='the-linked' class='postsbody'>
		<?php
		foreach ( $listlanguages as $language ) {
			$otherpost = $this->linked_post_in( $post_ID, $language->slug ) ;

			$checkpostlang = ( ''!= $postlang ) ? $postlang : $defaultlanguage ; // according author language
			$checked = ( $checkpostlang == $language->slug ) ? 'checked="checked"' : '';

			$creation_edit = ( $this->xili_settings['creation_redirect'] == 'redirect' ) ? __('Create and edit', 'xili-language') : __('Create', 'xili-language');

			$tr_class = ' class="lang-'.$language->slug.'" ';

			$language_name = '<span class="lang-iso"><abbr class="abbr_name" title="'.$language->description.'">'.$language->name.'</abbr></span>';

			$checkline = '<label  title="'.$language->description.'" class="checklang" for="xili_language_check_'.$language->slug.'" class="selectit"></label><input id="xili_language_check_'.$language->slug.'" title="'.$language->description.'" name="xili_language_set" type="radio" value="'.$language->slug .'"  '. $checked.' />&nbsp;&nbsp;'.$language_name ;

			$hiddeninput = '<input class="inputid" id="xili_language_'.QUETAG.'-'.$language->slug .'" name="xili_language_'.QUETAG.'-'.$language->slug.'"  value="" /><input type="hidden" name="xili_language_rec_'.QUETAG.'-'.$language->slug.'" value=""/>';

			if ( $otherpost && $language->slug !=  $postlang ) {
				$linepost = $this->temp_get_post ( $otherpost );

				if ( $linepost ) {

					if ( $linepost->post_status == 'trash' ) {

						$edit = __( 'uneditable', 'xili-language' );
					} else {
						$edit = sprintf( ' <a href="%s" title="link to:%d">%s</a> ', 'post.php?post='.$otherpost.'&action=edit', $otherpost, __('Edit') );
					}


					echo '<tr'.$tr_class.'><th  title="'.$language->description.'" >&nbsp;'.$language_name .'</th><td>'.$otherpost.'</td><td>'.$linepost->post_title

					.'</td><td>';

					switch ( $linepost->post_status ) {
						case 'private':
							_e('Privately Published');
							break;
							case 'publish':
								_e('Published');
								break;
							case 'future':
								_e('Scheduled');
								break;
							case 'pending':
								_e('Pending Review');
								break;
							case 'trash':
								_ex('Trash' ,'post');
								break;
							case 'draft':
							case 'auto-draft':
								_e('Draft');
								break;
					}

					echo '</td><td>'
					.$edit
					.'</td></tr>';

				} else {
					// delete post_meta - not target post
					delete_post_meta ( $post_ID,  QUETAG.'-'.$language->slug );
					$search = '<a class="hide-if-no-js" onclick="findPosts.open( \'lang[]\',\''.$language->slug.'\' );return false;" href="#the-list" title="'.__( 'Search linked post', 'xili-language' ).'"> '.__( 'Search', 'xili-language' ).'</a>';

					echo '<tr'.$tr_class.'><th>'.$checkline.'</th><td>'. $hiddeninput.' </td><td>'.__('not yet translated', 'xili-language')
						.'&nbsp;&nbsp;'.sprintf( '<a href="%s" title="%s">'.$creation_edit.'</a>', 'post.php?post='.$post_ID.'&action=edit&xlaction=transcreate&xllang='.$language->slug, sprintf(__('For create a linked draft translation in %s', 'xili-language'), $language->name )  ). '&nbsp;|&nbsp;'.  $search
			 			.'</td><td>&nbsp;</td><td>'. $search
			 			. '&nbsp;'
			 			. '</td></tr>';

				}

			} elseif ( $language->slug ==  $postlang) {

				echo '<tr class="editing lang-'.$language->slug.'" ><th>'.$checkline.'</th><td>'.$post_ID.'</td><td>'
				.$post->post_title
			 	.'</td><td>';
			 	switch ( $post->post_status ) {
						case 'private':
							_e('Privately Published');
							break;
							case 'publish':
								_e('Published');
								break;
							case 'future':
								_e('Scheduled');
								break;
							case 'pending':
								_e('Pending Review');
								break;
							case 'trash':
								_e('Trash');
								break;
							case 'draft':
							case 'auto-draft':
								_e('Draft');
								break;
					}

			 	echo '</td><td>&nbsp;</td></tr>';

			} else { // no linked post

				if ( in_array( $post->post_status, array ( 'draft', 'pending', 'future', 'publish', 'private' ) ) && $postlang != '' ) {

				$search = '<a class="hide-if-no-js" onclick="findPosts.open( \'lang[]\',\''.$language->slug.'\' );return false;" href="#the-list" title="'.__( 'Search linked post', 'xili-language' ).'"> '.__( 'Search' ).'</a>';

				echo '<tr'.$tr_class.'><th>'.$checkline.'</th><td>' . $hiddeninput .'</td><td>'
					. sprintf(__('not yet translated in %s', 'xili-language'), $language->description )
					.'&nbsp;&nbsp;'.sprintf( '<a href="%s" title="%s">'. $creation_edit .'</a>', 'post.php?post='.$post_ID.'&action=edit&xlaction=transcreate&xllang='.$language->slug, sprintf(__('For create a linked draft translation in %s', 'xili-language'), $language->name )  ).'&nbsp;|&nbsp;'.  $search
			 		.'</td><td>&nbsp;</td><td>'
			 		. '&nbsp;'
			 		. '</td></tr>';

				} else {

					if ( $defaultlanguage != '' &&  $defaultlanguage == $language->slug ) {
						// if post-new.php and pre-checked for author's brother
						$the_message = $mention;
						$the_class = ' class="editing lang-'.$defaultlanguage.'"';

					} else {
							$the_message = sprintf(__('select language %s !', 'xili-language'), $language->description );
							$the_class = $tr_class;
					}

					echo '<tr'.$the_class.'><th>'.$checkline.'</th><td>&nbsp;</td><td>'
						. '<p class="message" ><––––– '.$the_message.'</p>'
						.'</td><td>&nbsp;</td><td>'
						.'&nbsp'
						. '</td></tr>';
				}
			}
		}
		?>
		</tbody>
		</table>
		<div id="ajax-response"></div>
				<?php
				// ajax form
					$this->xili_find_posts_div('', $post_type, $post_type_object->label);
				?>
		<?php
		return $postlang ;
	}

	/**
	 * Display right part of translations dashboard
	 *
	 * @since 2.5
	 *
	 */
	function post_status_addons ( $post_ID, $curlang ) {
		$notundefinedlang = ( $curlang != "" ) ? $curlang : $this->authorbrowserlanguage; // set in left box
		$un_id = ( $curlang == "" ) ? '&nbsp;('. $post_ID .')' : '';
		$refresh = sprintf( '<a href="%s" title="%s">%s</a> ', 'post.php?post='.$post_ID.'&action=edit&xlaction=refresh', __('Refresh links series', 'xili-language'), __('Refresh links', 'xili-language') );
		?>
		<p><?php echo $refresh; ?>
		<?php if ( '' != $curlang && current_user_can ('xili_language_clone_tax') && is_object_in_taxonomy( get_post_type($post_ID), 'category') ) { //2.6.3
			printf( '&nbsp|&nbsp;<a href="%s" title="%s">%s</a> ', 'post.php?post='.$post_ID.'&action=edit&xlaction=propataxo', __('Propagate categories', 'xili-language'), __('Propagate categories', 'xili-language') );
		} ?></p>
		<label for="xili_language_check" class="selectit"><?php _e( 'set post to:', 'xili-language') ?>&nbsp;<input id="xili_language_check" name="xili_language_set" type="radio" value="undefined" <?php if($notundefinedlang=="") echo 'checked="checked"' ?> />&nbsp;<?php _e('undefined','xili-language'); echo $un_id; ?></label>
		<?php
	}

	function propagate_categories_to_linked ( $post_ID, $curlang ) {

		$listlanguages = $this->get_listlanguages();
		foreach ( $listlanguages as $language ) {
			if ( $language->slug != $curlang ) {
				// get to post
				$otherpost = $this->linked_post_in( $post_ID, $language->slug ) ;
				if ( $otherpost ) {
					$this->propagate_categories ( $post_ID, $otherpost, 'erase' );
				}
			}
		}
	}

	/**
	 * scripts for findposts only in post-new and post
	 * @since 2.2.2
	 */
	function  find_post_script () {
		global $wp_version ;
		wp_enqueue_script( 'wp-ajax-response' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		$suffix = defined( 'WP_DEBUG') && WP_DEBUG ? '.dev' : '.min'; // 2.8.8
		if ( version_compare($wp_version, '3.8.9', '>') ) {
			wp_enqueue_script( 'xili-find-post', plugin_dir_url ( $this->file_file ) . 'js/xili-findposts'.$suffix.'.js','' , XILILANGUAGE_VER );
		} else {
			wp_enqueue_script( 'xili-find-post', plugin_dir_url ( $this->file_file ) . 'js/xili-findposts8'.$suffix.'.js','' , XILILANGUAGE_VER );
		}
	}


	function wp_ajax_find_post_types() {
			global $wp_version ;

			check_ajax_referer( 'find-post-types' );

			$post_types = get_post_types( array( 'public' => true ), 'objects' );
			unset( $post_types['attachment'] );

			$s = wp_unslash( $_POST['ps'] );
			$searchand = $search = '';
			$args = array(
				'post_type' => array($_POST['post_type']), //array_keys( $post_types ),
				'post_status' => 'any',
				'posts_per_page' => 50,
			);
			if ( '' !== $s )
				$args['s'] = $s;

			$posts = get_posts( $args );

			if ( ! $posts )
				wp_die( __('No items found.') );


			$html = '<table class="widefat" cellspacing="0"><thead><tr><th class="found-radio"><br /></th><th>'.__('Title').'</th><th class="no-break">'.__('Type').'</th><th class="no-break">'.__('Date').'</th><th class="no-break">'.__('Status').'</th></tr></thead><tbody>';
			foreach ( $posts as $post ) {
				$title = trim( $post->post_title ) ? $post->post_title : __( '(no title)' );

				switch ( $post->post_status ) {
					case 'publish' :
					case 'private' :
						$stat = __('Published');
						break;
					case 'future' :
						$stat = __('Scheduled');
						break;
					case 'pending' :
						$stat = __('Pending Review');
						break;
					case 'draft' :
						$stat = __('Draft');
						break;
				}

				if ( '0000-00-00 00:00:00' == $post->post_date ) {
					$time = '';
				} else {
					/* translators: date format in table columns, see http://php.net/date */
					$time = mysql2date(__('Y/m/d'), $post->post_date);
				}

				$html .= '<tr class="found-posts"><td class="found-radio"><input type="radio" id="found-'.$post->ID.'" name="found_post_id" value="' . esc_attr($post->ID) . '"></td>';
				$html .= '<td><label for="found-'.$post->ID.'">' . esc_html( $title ) . '</label></td><td class="no-break">' . esc_html( $post_types[$post->post_type]->labels->singular_name ) . '</td><td class="no-break">'.esc_html( $time ) . '</td><td class="no-break">' . esc_html( $stat ). ' </td></tr>' . "\n\n";
			}

			$html .= '</tbody></table>';
			if  ( version_compare( $wp_version, '3.8.9', '>' ) ) {
				wp_send_json_success( $html );
			} else {
				$x = new WP_Ajax_Response();
				$x->add( array(
					'data' => $html
				));
				$x->send();
			}
	}




	/**
	 * add styles in edit msg screen
	 *
	 * @since 2.5
	 *
	 */
	 function print_styles_cpt_edit ( ) {
	 	global $post;

	 	$custompoststype = $this->authorized_custom_post_type();
	 	$custompoststype_keys = array_keys ( $custompoststype );
	 	$type = get_post_type( $post->ID );
	 	if ( in_array ( $type , $custompoststype_keys ) && $custompoststype[$type]['multilingual'] == 'enable'  ){
	  		$insert_flags = ( $this->xili_settings['external_xl_style'] == "on" );
		 	echo '<!---- xl css ----->'."\n";
			echo '<style type="text/css" media="screen">'."\n";
		 	echo '#msg-states { width:79%;  float:left; overflow:hidden; }'."\n";
		 	echo '#msg-states-comments { width:18.5%; margin-left: 80%; border-left:0px #666 solid;  padding:10px 10px 0;  }'."\n";
		 	echo ".clearb1 {clear:both; height:1px;} \n"; // 2.8.8

		 	echo '.xlversion {font-size:80%; margin-top:20px; text-align:right;}';

		 	echo '.alert { color:red;}'."\n";
		 	echo '.message { font-size:80%; color:#bbb !important; font-style:italic; }'."\n";
		 	echo '.editing { color:#333; background:#fffbcc;}'."\n";
		 	echo '.abbr_name:hover {border-bottom:1px dotted grey;}'."\n";
		 	echo '#postslist {width: 100%; border:1px solid grey ;}'."\n";

		 	echo '.language {width: 80px;}'."\n";
		 	echo '.postid {width: 35px;}'."\n";

		 	echo '.status {width: 60px;}'."\n";
		 	echo '.action {width: 120px;}'."\n";

		 	echo '.inputid {width: 55px; font-size:90%}'."\n";
		 	$lang = $this->get_post_language( $post->ID ) ; //slug
		 	if (  $this->style_folder == get_stylesheet_directory_uri() ) {
		 		$folder = $this->style_folder . '/images/flags/' ;
		 	} else {
		 		$folder = $this->style_folder . '/xili-css/flags/' ;
		 	}
		 	if ( $insert_flags && $lang != ''  && file_exists( $this->style_flag_folder_path . $lang .'.png' ) )
		 		echo '#titlewrap input {background : url('. $folder . $lang.'.png' . ') 98.5% center no-repeat !important; }'."\n";

		 	echo '.postsbody tr > th span { display:inline-block; height: 20px; }'."\n";
		 	$listlanguages = $this->get_listlanguages();
		 	foreach ($listlanguages as $language)  {
	 			if ( $insert_flags && file_exists( $this->style_flag_folder_path . $language->slug .'.png' ) && $this->xili_settings['external_xl_style'] == "on" ) {
			 		echo '.postsbody tr.lang-'. $language->slug .' > th span { display:inline-block; text-indent:-9999px ; height: 20px; }'."\n";
	 			}
		 	}

		 	echo '</style>'."\n";

		 	if ( $this->exists_style_ext && $insert_flags ) wp_enqueue_style( 'xili_language_stylesheet' );

	 	}
	 }

	 /**
	 * Hide language post_meta link
	 * from apply_filters( 'is_protected_meta', $protected, $meta_key, $meta_type );
	 *
	 * @since 2.5
	 */
	function hide_lang_post_meta ( $protected, $meta_key, $meta_type ) {
		if ( $meta_type == 'post' && QUETAG.'-' == substr( $meta_key, 0, strlen(QUETAG) + 1 ) ) {
			$protected = true;
		}
		return $protected;
	}

	/**
	 * test of tracs http://core.trac.wordpress.org/ticket/18979#comment:2
	 */

	function hide_lang_post_meta_popup ( $keys, $limit = 10 ) {
		global $wpdb, $post;
		$q = "SELECT meta_key FROM $wpdb->postmeta";
 		$post_type = get_post_type ( $post->ID  );
 		if ( ! empty( $post_type ) )
 			$q .= $wpdb->prepare( " INNER JOIN $wpdb->posts ON post_id = ID WHERE post_type LIKE %s", $post_type );

 	 	$q .= " GROUP BY meta_key HAVING ( meta_key NOT LIKE '\_%' AND meta_key NOT LIKE '" . QUETAG . "-%' )  ORDER BY meta_key LIMIT $limit";
 	  	$keys = $wpdb->get_col( $q );
	 	//$keys = apply_filters( 'postmeta_form_keys', $keys, $post_type );
	 	if ( $keys )
			natcasesort($keys);
		return $keys;
	}

	/**
	 * set language when post or page is saved or changed
	 *
	 * @since 0.9.0
	 * @completed 0.9.7.1 to record postmeta of linked posts in other languages
	 * @updated 0.9.7.5 to delete relationship when undefined
	 * @updated 0.9.9 to avoid delete relationship when in quick_edit
	 * @updated 1.3.0 to avoid delete relationship when trashing - 1.4.1 - create post-meta xl-search-linked
	 * @updated 1.8.9.3 for bulk edit...
	 * @updated 2.5, 2.6
	 * @updated 2.9.10 bulk edit via ajax
	 *
	 * @param $post_ID
	 */
	function xili_language_add( $post_ID, $post ) {

		$posttypes = array_keys( $this->xili_settings['multilingual_custom_post'] );
		$posttypes[] = 'post';
		$posttypes[] = 'page';
		$thetype = $post->post_type;
		if ( in_array ( $thetype, $posttypes ) ) {

			$listlanguages = $this->get_listlanguages () ;
			$previous_lang = $this->get_post_language ( $post_ID ) ;

			if ( isset($_POST['_inline_edit']) ) { /* when in quick_edit (edit.php) */
				$sellang = $_POST['xlpop'];
				if ( "" != $sellang ) {

					if ( $sellang != $previous_lang ) {
						// move a language
						// clean linked targets
						foreach ($listlanguages as $language) {

							$target_id = get_post_meta( $post_ID, QUETAG.'-'.$language->slug, true );
							if ( $target_id != "" ) {
								if ( $previous_lang != '' ) delete_post_meta( $target_id, QUETAG.'-'.$previous_lang );
								update_post_meta( $target_id, QUETAG.'-'.$sellang, $post_ID  );
							}
						}
						wp_delete_object_term_relationships( $post_ID, TAXONAME );
					}

					wp_set_object_terms( $post_ID, $sellang, TAXONAME );

				} else { // undefined
					if ( !isset ( $_GET['action'] ) ) { // trash - edit

						// clean linked targets
						foreach ($listlanguages as $language) {
							//delete_post_meta( $post_ID, QUETAG.'-'.$language->slug ); // erase translated because undefined
							$target_id = get_post_meta( $post_ID, QUETAG.'-'.$language->slug, true );
							if ( $target_id != "" ) {
								delete_post_meta( $target_id, QUETAG.'-'.$previous_lang );
							}
						}

						wp_delete_object_term_relationships( $post_ID, TAXONAME );

					}
				}

			// bulk-edit via ajax 2.9.10
			} else if ( isset($_GET['bulk_edit']) ) {
			 	return;

			} else {
				// post-edit single
				$sellang = ( isset ( $_POST['xili_language_set'] )) ? $_POST['xili_language_set'] : "" ;
				if ( "" != $sellang && "undefined" != $sellang ) {
					if ( $sellang != $previous_lang && $previous_lang != '' ) {
						// move a language
						// clean linked targets
						foreach ($listlanguages as $language) {

							$target_id = get_post_meta( $post_ID, QUETAG.'-'.$language->slug, true );
							if ( $target_id != "" ) {
								delete_post_meta( $target_id, QUETAG.'-'.$previous_lang );
								update_post_meta( $target_id, QUETAG.'-'.$sellang, $post_ID  );
							}
						}
						wp_delete_object_term_relationships( $post_ID, TAXONAME );
					}
					wp_set_object_terms($post_ID, $sellang, TAXONAME);
				} elseif (  "undefined" == $sellang ) {

					// clean linked targets
					foreach ($listlanguages as $language) {

						$target_id = get_post_meta( $post_ID, QUETAG.'-'.$language->slug, true );
						if ( $target_id != "" ) {
							delete_post_meta( $target_id, QUETAG.'-'.$previous_lang );
						}
					}
					// now undefined
					wp_delete_object_term_relationships( $post_ID, TAXONAME );
				}

				$curlang = $this->get_cur_language( $post_ID ) ; // array


				/* the linked posts set by author in postmeta */

				foreach ($listlanguages as $language) {
					$inputid = 'xili_language_'.QUETAG.'-'.$language->slug ;
					$recinputid = 'xili_language_rec_'.QUETAG.'-'.$language->slug ;
					$linkid = ( isset ( $_POST[$inputid] ) ) ? $_POST[$inputid] : 0 ;
					$reclinkid = ( isset ( $_POST[$recinputid] ) ) ? $_POST[$recinputid] : 0 ; /* hidden previous value */
					$langslug = QUETAG.'-'.$language->slug ;

					if ( $reclinkid != $linkid ) { /* only if changed value or created since 1.3.0 */
						if ((is_numeric($linkid) && $linkid == 0) || '' == $linkid ) {
							delete_post_meta($post_ID, $langslug);
						} elseif ( is_numeric( $linkid ) && $linkid > 0 ) {
							// test if possible 2.5.1
							if ( $this->is_post_free_for_link ( $post_ID, $curlang[QUETAG], $language->slug, $linkid ) ) {
								update_post_meta( $post_ID, $langslug, $linkid);

								if ($reclinkid == "-1")	update_post_meta( $linkid, QUETAG.'-'.$sellang, $post_ID);

								// update target 2.5
								foreach ($listlanguages as $metalanguage) {
									if ( $metalanguage->slug != $language->slug && $metalanguage->slug != $curlang[QUETAG] ) {
										$id = get_post_meta( $post_ID, QUETAG.'-'.$metalanguage->slug, true );
										if ( $id != "" ) {
											update_post_meta( $linkid, QUETAG.'-'.$metalanguage->slug, $id );
										}
									}
								}
								update_post_meta( $linkid, QUETAG.'-'.$curlang[QUETAG], $post_ID ); // cur post
								wp_set_object_terms( $linkid, $language->slug, TAXONAME );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * add to secure manual input of linked post
	 *
	 * @since 2.5.1
	 *
	 */

	function is_post_free_for_link ( $from_post_ID, $from_lang, $target_lang, $target_ID ) {

		if ( $from_post_ID == $target_ID ) return false ; // obvious

		if ( $this->temp_get_post ( $target_ID ) ) {
			// check if target ID is not yet in another lang
			$target_slug = $this->get_post_language ( $target_ID ) ;
			if ( $target_slug == '' ) {
				return true; // undefined
			} elseif (  $target_slug == $target_lang ) {
				// check target is not yet link to other
				$id = get_post_meta( $target_ID, QUETAG.'-'.$from_lang, true );
				if ( $id != "" ) {
					return false; // yet linked
				} else {
					return true;
				}

			} else {
				return false; // yet another language
			}

		} else {
			return false; // no target
		}

	}

	/**
	 * if post created by dashboard, when first saved by author, fixes post_name for permalinks use
	 *
	 * @since 2.5
	 *
	 */
	function fixes_post_slug ( $post_ID, $post ) {
		$state = get_post_meta( $post_ID, $this->translation_state, true );
		if (  $state == "initial" ) {
			global $wpdb;
    		if ( defined ( 'XDMSG' ) && get_post_type( $post_ID ) == XDMSG ) return;

    		$where = array( 'ID' => $post_ID );
        	$what = array ();

        	$what['post_name'] = sanitize_title($post->post_title);

        	if ( $what != array() )
        			$wpdb->update( $wpdb->posts, $what, $where );

			delete_post_meta( $post_ID, $this->translation_state );
		}
	}

	/**
	 * inspired by find_posts_div from wp-admin/includes/template.php
	 *
	 * @since 2.3.1 to restrict to type of post
	 *
	 * @param unknown_type $found_action -
	 */
	function xili_find_posts_div($found_action = '', $post_type, $post_label ) {
		global $wp_version;
	?>
		<div id="find-posts" class="find-box" style="display:none;">
			<div id="find-posts-head" class="find-box-head"><?php printf( __( 'Find %s','xili-language' ), $post_label ) ; ?>
				<?php if ( version_compare($wp_version, '3.8.9', '>') ) { ?>
					<div id="find-posts-close"></div>
				<?php } ?>
			</div>
			<div class="find-box-inside">
				<div class="find-box-search">
					<?php if ( $found_action ) { ?>
						<input type="hidden" name="found_action" value="<?php echo esc_attr($found_action); ?>" />
					<?php } ?>

					<input type="hidden" name="affected" id="affected" value="" />
					<?php wp_nonce_field( 'find-post-types', '_ajax_nonce', false ); ?>
					<label class="screen-reader-text" for="find-posts-input"><?php _e( 'Search' ); ?></label>
					<input type="text" id="find-posts-input" name="ps" value="" />
					<input type="button" id="find-posts-search" value="<?php esc_attr_e( 'Search' ); ?>" class="button" />
					<div class="clear"></div>
					<?php /* checks replaced by hidden - see js findposts*/ ?>
					<input type="hidden" name="find-posts-what" id="find-posts-what" value="<?php echo esc_attr($post_type); ?>" />

				</div>
				<div id="find-posts-response"></div>
			</div>
			<div class="find-box-buttons">
				<?php if ( version_compare($wp_version, '3.8.9', '<') ) { ?>
					<input id="find-posts-close" type="button" class="button alignleft" value="<?php esc_attr_e('Close'); ?>" />
				<?php } ?>
				<?php submit_button( __( 'Select' ), 'button-primary alignright', 'find-posts-submit', false ); ?>
			</div>
		</div>
	<?php
	}

	/**************** Attachment post language  *******************/

	function add_language_attachment_fields ($form_fields, $post) {
		global $wp_version;

		// 2.9.0
		if ( current_theme_supports( 'custom-header') ) {
			$meta_header = get_post_meta($post->ID, '_wp_attachment_is_custom_header', true );
			if ( !empty( $meta_header ) && $meta_header == get_option('stylesheet') ) {
				$form_fields['_final'] = '<strong>'.__('This media is inside Header Image list.', 'xili-language') .'</strong><br /><br /><small>© xili-language v.'.XILILANGUAGE_VER .'</small>';
				return $form_fields ;
			}
		}

		$attachment_id = $post->ID;

		// get list of languages for popup
		$attachment_post_language = get_cur_language( $attachment_id, 'slug' );

		$listlanguages = $this->get_listlanguages () ;
		// get_language
		if ( '' != $attachment_post_language ) { // impossible to change if assigned
			$name = $this->langs_slug_name_array[$attachment_post_language];
			$fullname = $this->langs_slug_fullname_array[$attachment_post_language];
			$form_fields['attachment_post_language'] = array(
				'label'      => __('Language', 'xili-language'),
				'input'      => 'html',
				'html'       =>  "<strong>$fullname</strong> ($name)<input type='hidden' name='attachments[$attachment_id][attachment_post_language]' value='" . $attachment_post_language . "' /><br />",
				'helps'      => __('Language of the file caption and description.', 'xili-language')
			);

		} else { // selector

			$html_input = '<select name="attachments['.$attachment_id.'][attachment_post_language]" ><option value="undefined">'.__('Choose…','xili-language').'</option>';
			foreach ($listlanguages as $language) {
				$selected = (''!=$attachment_post_language && $language->slug == $attachment_post_language) ? 'selected=selected' : '';
				$html_input .= '<option value="'.$language->slug.'" '.$selected.'>'.$language->description.' ('.$language->name.')</option>';
			}
			$html_input .= '</select>';

			$form_fields['attachment_post_language'] = array(
				'label'      => __('Language', 'xili-language'),
				'input'      => 'html',
				'html'       =>  $html_input,
				'helps'      => __('Language of the file caption and description.', 'xili-language')
			);
		}

		if ( version_compare( $wp_version, '3.5', '<') ) { // 2.8.4.2
		  	$clone = ( get_current_screen()->base == "media" ) ? true : false ;
		} else {
			if ( isset ( $post->ID ) &&  get_current_screen() ) { // test ajax WP 3.5
				$clone = ( get_current_screen()->base == "post" && get_post_type( $post->ID ) == 'attachment' ) ? true : false ;

			} else {
				$clone = false; // not visible if called by ajax
			}

		}

		if ( '' != $attachment_post_language && $clone ) { // only in media edit not in media-upload

			if ( $post->post_parent > 0 ) {
				$html_input = '<strong>'.sprintf( '%s:&nbsp;',__('attached to','xili-language')).get_the_title ( $post->post_parent ).'</strong>';
				$html_input .='&nbsp;&nbsp;<a href="post.php?post='.$post->post_parent.'&action=edit" title="'.__('Edit').'" >'.__('Edit').'</a>';
				$helps = __('This titled post above has this media as attachment.', 'xili-language');
			} else {
				$html_input = '<strong>'.__( 'not attached to a post.' , 'xili-language').'</strong>';
				$helps = __('In the Media Library table, it is possible to attach a media to a post.', 'xili-language');
			}

			$form_fields['attachment-linked-post'] = array(
				'label'      => __('<small>Info: </small>This media is', 'xili-language').'&nbsp;&nbsp',
				'input'      => 'html',
				'html'       =>  $html_input,
				'helps'      => $helps
			);


			$type_post = ( version_compare( $wp_version, '3.5', '<') ) ? 'attachment' : 'post' ;
			$result = $this->translated_in ( $attachment_id, 'link', $type_post );

			$trans = $this->translated_in ( $attachment_id, 'array');
			$html_input = '<br />'; //'<div class="updated" style="background: #f5f5f5; border:#dfdfdf 1px solid;">';
			if ( $result == '' ) {
				$html_input .= __('not yet translated', 'xili-language') ;
			} else {
				$html_input .= __('Title, caption and description are already translated in', 'xili-language');
				$html_input .= '&nbsp;:&nbsp;<span class="translated-in">' . $result .'</span><br />';
			}

			if ( version_compare( $wp_version, '3.5', '>=') ) { // 2.8.4.2 - post_parent not present
				$html_input .= '<input type="hidden" id="xl_post_parent" name="xl_post_parent" value="'. $post->post_parent . '" />';
			}

			$html_input .= '<br /><select name="attachments['.$attachment_id.'][create_clone_attachment_with_language]" ><option value="undefined">'.__('Select…','xili-language').'</option>';
			foreach ($listlanguages as $language) {
				if ( $language->slug != $attachment_post_language && !isset ($trans[$language->slug] )) {
					$selected = '' ; //(''!=$attachment_post_language && $language->slug == $attachment_post_language) ? 'selected=selected' : '';
					$html_input .= '<option value="'.$language->slug.'" '.$selected.'>'.$language->description.' ('.$language->name.')</option>';
				}
			}
			$html_input .= '</select>';
			$form_fields['create_clone_attachment_with_language'] = array(
				'label'      => __('Create clone in language', 'xili-language'),
				'input'      => 'html',
				'html'       =>  $html_input,
				'helps'      => __('Selection of the language of a linked cloned attachment (with same file).', 'xili-language')

			);

			$form_fields['_final'] = '<small>© xili-language v.'.XILILANGUAGE_VER .'</small>';
		}

		return $form_fields ;
	}

	// attachment_fields_to_save apply_filters('attachment_fields_to_save', $post, $attachment);

	function set_attachment_fields_to_save ( $post, $attachment ) {
		global $wpdb, $wp_version;

		if ( isset($attachment['attachment_post_language']) ){
			if ( $attachment['attachment_post_language'] != '' && $attachment['attachment_post_language'] != 'undefined' ) {
				wp_set_object_terms($post['ID'], $attachment['attachment_post_language'], TAXONAME);
			} else {
				wp_delete_object_term_relationships( $post['ID'], TAXONAME );
			}
		}
		$clone = $post;
		unset ($clone['ID']);
		if ( isset($attachment['create_clone_attachment_with_language']) && $attachment['create_clone_attachment_with_language'] != 'undefined' ){

			$clone['post_title'] = sprintf(__('Translate in %2$s: %1$s', 'xili-language'),$clone['post_title'], $attachment['create_clone_attachment_with_language'] );

			if ( version_compare( $wp_version, '3.5', '>=') ) {
				$parent_id = $post['xl_post_parent']; // 2.8.4.2
			} else {
				$parent_id = $post['post_parent'];
			}


			$linked_parent_id = xl_get_linked_post_in ( $parent_id, $attachment['create_clone_attachment_with_language'] );
			$clone['post_parent'] = $linked_parent_id; // 0 if unknown linked id of parent in assigned language

			$cloned_attachment_id = wp_insert_post( $clone );
			// clone post_meta
			$data = get_post_meta( $post['ID'], '_wp_attachment_metadata', true );
			$data_file = get_post_meta( $post['ID'], '_wp_attached_file', true );
			$data_alt = get_post_meta( $post['ID'], '_wp_attachment_image_alt', true );
			update_post_meta( $cloned_attachment_id, '_wp_attachment_metadata', $data);
			update_post_meta( $cloned_attachment_id, '_wp_attached_file', $data_file);
			if ( '' != $data_alt ) update_post_meta( $cloned_attachment_id, '_wp_attachment_image_alt', $data_alt);
			// set language and links of cloned of current
			update_post_meta( $cloned_attachment_id, QUETAG.'-'.$attachment['attachment_post_language'], $post['ID'] );
			wp_set_object_terms( $cloned_attachment_id, $attachment['create_clone_attachment_with_language'], TAXONAME );

			// get already linked of cloned
			$already_linked = array();
			if ( $meta_values = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, meta_key FROM $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", QUETAG .'-' . '%', $post['ID']) ) ) {

				foreach ( $meta_values as $key_val ) {
					update_post_meta( $key_val->meta_value, QUETAG.'-'.$attachment['create_clone_attachment_with_language'], $cloned_attachment_id );
					$slug = str_replace( QUETAG.'-', '', $key_val->meta_key );
					$already_linked[$slug] = $key_val->meta_value;
				}
			}
			// set links of current to cloned
			update_post_meta( $post['ID'], QUETAG.'-'.$attachment['create_clone_attachment_with_language'], $cloned_attachment_id );
			if ( $already_linked != array() ) {
				foreach ( $already_linked as $key => $id ) {
					update_post_meta( $post['ID'], QUETAG.'-'.$key, $id );
					if ( $key != $attachment['create_clone_attachment_with_language'] ) update_post_meta( $cloned_attachment_id, QUETAG.'-'.$key, $id );
				}
			}
		}

		return $post;
	}
	// called before deleting attachment by do_action( 'delete_attachment'
	function if_cloned_attachment ( $post_ID ) {
		global $wpdb;
		if ( $post = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", $post_ID) ) ) {

			if ( 'attachment' == $post->post_type ) {
				$attachment_post_language = get_cur_language( $post_ID, 'slug' );
				// test meta lang
				$linked_list = $this->translated_in ( $post_ID, 'array');
				if ( array()  != $linked_list ) {
					$this->dont_delete_file = true;
					// update meta in linked attachments
					foreach ( $linked_list as $lang_slug => $linked_id ) {
						delete_post_meta ( $linked_id,  QUETAG.'-'.$attachment_post_language );
					}
				} else {
					$this->dont_delete_file = false;
				}
			}
		}
	}

	// called before deleting file by apply_filters( 'wp_delete_file'
	function if_file_cloned_attachment ( $file ) {
		if ( $this->dont_delete_file == true ) $file = '';
		return $file;
	}


	/**************** List of Posts (edit.php)  *******************/

	/**
	 * display languages column name in Posts/Pages list
	 *
	 * @updated 1.8.9
	 *
	 * complete get_column_headers (screen.php) filter
	 *
	 * @since 2.9.10
	 */
	function xili_manage_column_name( $cols ) {

		if ( defined ('XDMSG') ) $CPTs = array( XDMSG );
		$CPTs[] = 'page';
		$CPTs[] = 'post';
		$CPTs[] = 'attachment'; // 2.8.4.1

		$custompoststype = $this->xili_settings['multilingual_custom_post'] ;
 		if ( $custompoststype != array()) {
			foreach ( $custompoststype as $key => $customtype ) {
 				if ( $customtype['multilingual'] == 'enable' ) {
 					$CPTs[] = $key;
 				}
			}
 		}

 		$post_type = get_post_type();

		if ( in_array ( $post_type, $CPTs)  )   {

			$ends = apply_filters ( 'xiliml_manage_column_name', array( 'comments', 'date', 'rel', 'visible'), $cols, $post_type ); // 2.8.1
			$end = array();
			foreach( $cols AS $k=>$v ) {
				if ( in_array($k, $ends) ) {
					$end[$k] = $v;
					unset($cols[$k]);
				}
			}
			$cols[TAXONAME] = __('Language','xili-language');
			$cols = array_merge($cols, $end);
		}
		return $cols;
	}

	/**
	 * display languages column in Posts/Pages list
	 *
	 * @updated 1.8.9
	 */
	function xili_manage_column( $name, $id ) {
		global $wp_query; // 2.8.1
		if( $name != TAXONAME )
			return;
		$output = '';
		$terms = wp_get_object_terms( $id, TAXONAME );
		$first = true;
		foreach( $terms AS $term ) {
			if ( $first )
				$first = false;
			else
				$output .= ', ';

			if ( current_user_can ('activate_plugins') ) {
				$output .= '<span class="curlang lang-'. $term->slug .'"><a href="' . 'options-general.php?page=language_page'.'" title="'.sprintf(__('Post in %s. Link to see list of languages…','xili-language'), $term->description ) .'" >'; /* see more precise link ?*/
				$output .= $term->name .'</a></span>';
			} else {
				$output .= '<span title="'. sprintf(__('Post in %s.','xili-language'), $term->description ) .'" class="curlang lang-'. $term->slug .'">' . $term->name . '</span>' ;
			}

			$output .= '<input type="hidden" id="'.QUETAG.'-'.$id.'" value="'.$term->slug.'" >'; // for Quick-Edit - 1.8.9
		}
		$xdmsg = ( defined ('XDMSG') ) ? XDMSG : '' ;


		$post_type = ( isset ( $wp_query->query_vars['post_type' ] ) ) ?  $wp_query->query_vars['post_type' ] : '' ;

		if (  $post_type != $xdmsg  )  {  // no for XDMSG
			$output .= '<br />';

			$result = $this->translated_in ( $id );

			$output .= apply_filters ( 'xiliml_language_translated_in_column', $this->display_translated_in_result ( $result ), $result, $post_type );
		}
		echo '<div id="'.TAXONAME.'-' . $id . '">' .$output . '</div>'; // called by do_action() class wp_posts_list_table 2.9.10
	}

	/**
	*
	*/

	function display_translated_in_result ( $result ) {
		$output = "";
		if ( $result == '' ) {
			$output .= __('not yet translated', 'xili-language') ;
		} else {
			$output .= __('translated in:', 'xili-language') ;
			$output .= '&nbsp;<span class="translated-in">' . $result .'</span>';
		}
		return $output;
	}


	/**
	 * Return list of linked posts
	 * used in edit list
	 *
	 * @param: mode = array to customize list
	 * @since 2.5
	 *
	 */
	function translated_in ( $post_ID, $mode = 'link', $type = 'post' ) {

		$curlang = $this->get_cur_language( $post_ID ) ; // array
		$listlanguages = $this->get_listlanguages () ;
		$trans = array();
			foreach ( $listlanguages as $language ) {
				if ( $language->slug != $curlang[QUETAG] ) {
					$otherpost = $this->linked_post_in( $post_ID, $language->slug ) ;
					if ( $otherpost ) {
						$linepost = $this->temp_get_post ( $otherpost );
						if ( $linepost ) {
							switch ( $mode ) {
								case 'link' :
									if ( $type == 'post' ) {
										$link = 'post.php?post='.$linepost->ID.'&action=edit';
									} elseif ( $type == 'attachment') {
										$link = 'media.php?attachment_id='.$linepost->ID.'&action=edit';
									}

									$title = sprintf ( __( 'link to edit %s %d in %s', 'xili-language' ), $type, $linepost->ID, $language->description );
									$trans[] = sprintf( __('<a href="%1$s" title="%2$s" class="lang-%4$s" >%3$s</a>','xili-language'), $link, $title, $language->name, $language->slug );
								break;
								case 'array' :
									$trans[$language->slug] = array ( 'post_ID'=>$linepost->ID, 'name'=>$language->name, 'description'=>$language->description );
								break;

							}
						}
					}
				}
			}

		if ( $mode == 'array' ) return $trans;

		$list = implode (' ', $trans ) ;
		return $list;
	}

	/**
	 * style for posts (and categories) list
	 *
	 *
	 */
	function print_styles_posts_list () {

	 	if ( get_current_screen()->base == "upload" )
		 		$this->insert_news_pointer ( 'media_language' ); // 2.6.3

	   $insert_flags = ( $this->xili_settings['external_xl_style'] == "on" );
	   echo "<!---- xl css --->\n";
	   echo '<style type="text/css" media="screen">'."\n";
	   		echo ".langquickedit { background: #E4EAF8; padding:0 5px 4px !important; border:1px solid #ccc; width:140px !important; float:right !important;}\n";
	   		echo ".toppost { margin: 0 20px 2px 7px; }  \n";
	   		echo ".toppage { margin: -40px 20px 2px 7px; } \n";
	   		echo "span.curlang a { display:inline-block; font-size:80%; height:18px; width:60px; } \n";
	   		$listlanguages = $this->get_listlanguages();

	   		if (  $this->style_folder == get_stylesheet_directory_uri() ) {
	 			$folder_url = $this->style_folder . '/images/flags/' ;
	 		} else {
	 			$folder_url = $this->style_folder . '/xili-css/flags/' ;
	 		}

	   		foreach ($listlanguages as $language)  {

	 			if ( $insert_flags && file_exists( $this->style_flag_folder_path . $language->slug .'.png' ) ) {
	 				echo "span.lang-". $language->slug ." { background: url(". $folder_url . $language->slug .'.png' .") no-repeat 0% center } \n";
	 				echo "span.curlang.lang-" . $language->slug ." a { color:#f5f5f5; text-indent:-9999px ;}\n";

	 				if ( class_exists( 'xili_tidy_tags' ) ) {
	 					echo "div#xtt-edit-tag span.curlang.lang-" . $language->slug ." { margin-left:5px; color:#f5f5f5; display:inline-block; height:18px; width:25px; text-indent:-9999px ; }\n";

	 				}

	 			} else {
	 				echo "span.curlang.lang-" . $language->slug ." a { font-size:100%; text-align: left; }\n";
	 			}
	   		}
	   echo "</style>\n";

	   if ( $this->exists_style_ext && $this->xili_settings['external_xl_style'] == "on" ) wp_enqueue_style( 'xili_language_stylesheet' );
	}

	/**
	 * Insert popup in quickedit at end (filter quick_edit_custom_box - template.php)
	 *
	 * @since 1.8.9
	 *
	 * hook with only two params - no $post_ID - populated by.. || $type != 'post' || $type != 'page'
	 *
	 */
	function languages_custom_box ( $col, $type ) {
		if ( 'edit-tags' == $type )
			return;
		if( $col != TAXONAME )
          return;

    	$listlanguages = $this->get_listlanguages();
    	$margintop = ($type == 'page' ) ? 'toppage' : 'toppost';
		?>

		<fieldset class="inline-edit-language langquickedit <?php echo $margintop; ?>" ><legend><em><?php _e('Language','xili-language') ?></em></legend>

			<select name="xlpop" id="xlpop">
			<option value=""> <?php _e('undefined','xili-language') ?> </option>
			<?php foreach ($listlanguages as $language)  {
				echo '<option value="'.$language->slug.'">'.__($language->description, 'xili-language').'</option>';
			// no preset values now (see below)
			}
			?>
			</select>
		</fieldset>


	<?php
	}

	/**
	 * script used in quick and bulk edit
	 *
	 * @since 2.9.10
	 *
	 */
	function quick_edit_add_script () {
		$suffix = defined( 'WP_DEBUG') && WP_DEBUG ? '.dev' : '.min';
		wp_enqueue_script( 'xl-admin-edit', plugin_dir_url ( $this->file_file ) . 'js/xl_quick_edit'.$suffix.'.js', array( 'jquery', 'inline-edit-post' ), '', true );
	}

	/**
	 * Insert popup in BULK quickedit at end (filter bulk_edit_custom_box - template.php)
	 *
	 * @since 1.8.9.3
	 *
	 * hook with only two params - no $post_ID - populated by.. || $type != 'post' || $type != 'page'
	 *
	 */
	 function hidden_languages_custom_box ( $col, $type ) {
		if( $col != TAXONAME ) {
         return;
    	}
    	$listlanguages = $this->get_listlanguages();
    	$margintop = ($type == 'page' ) ? '-40px' : '0';
		?>

		<label class="alignright">
			<span class="title"><?php _e( 'Language','xili-language' ); ?></span>
			<select name="xlpop" id="xlpop">
			<option value="-1"> <?php _e('&mdash; No Change &mdash;') ?> </option>
			<option value="*"> <?php _e('undefined','xili-language') ?> </option>
			<?php foreach ($listlanguages as $language)  {
				echo '<option value="'.$language->slug.'">'.__($language->description, 'xili-language').'</option>';
			// no preset values now (see below)
			}
			?>
			</select>
		</label>
	<?php
	}

	/**
	*
	* @since 2.9.20
	*/
	function ajax_get_menu_infos () {

		$menu_id = ( isset( $_POST[ 'menu_id' ] ) && !empty( $_POST[ 'menu_id' ] ) ) ? $_POST[ 'menu_id' ] : 0 ;

		$menu_obj = wp_get_nav_menu_object( (int) $menu_id );
		if ( $menu_obj )
			echo '<strong>' . wp_html_excerpt( $menu_obj->name, 40, '&hellip;' ) . '</strong>';
		else
			echo '<strong>' . sprintf(__('Warning: Unavailable menu id %s', 'xili-language'), $menu_id) . '</strong>';

		die();
	}


	/**
	 * language saved via ajax for bulk_edit
	 *
	 * @since 2.9.10
	 *
	 */

	function save_bulk_edit_language() {
	   	// get our variables
	   	$post_IDs = ( isset( $_POST[ 'post_ids' ] ) && !empty( $_POST[ 'post_ids' ] ) ) ? $_POST[ 'post_ids' ] : array();
	   	$assigned_lang = ( isset( $_POST[ 'assigned_lang' ] ) && !empty( $_POST[ 'assigned_lang' ] ) ) ? $_POST[ 'assigned_lang' ] : NULL;
	   	// if everything is in order
	   	if ( !empty( $post_IDs ) && is_array( $post_IDs ) && !empty( $assigned_lang ) ) {

	   		$listlanguages = $this->get_listlanguages () ;


	      	foreach( $post_IDs as $post_ID ) {

	      		$previous_lang = $this->get_post_language ( $post_ID ) ;

				if ( "-1" != $assigned_lang && "*" != $assigned_lang) {

					if ( $assigned_lang != $previous_lang ) {

						foreach ($listlanguages as $language) {

							$target_id = get_post_meta( $post_ID, QUETAG.'-'.$language->slug, true );
							if ( $target_id != "" ) {
								if ( $previous_lang != '' )  delete_post_meta( $target_id, QUETAG.'-'.$previous_lang );
								update_post_meta( $target_id, QUETAG.'-'.$assigned_lang, $post_ID  );
							}
						}
					}


					wp_set_object_terms( $post_ID, $assigned_lang, TAXONAME );
				} else if ( "*" == $assigned_lang )  { // undefined

					foreach ($listlanguages as $language) {
							//delete_post_meta( $post_ID, QUETAG.'-'.$language->slug ); // erase translated because undefined - not yet
						$target_id = get_post_meta( $post_ID, QUETAG.'-'.$language->slug, true );
						if ( $target_id != "" ) {
							delete_post_meta( $target_id, QUETAG.'-'.$previous_lang );
						}
					}

					wp_delete_object_term_relationships( $post_ID, TAXONAME );
				}


	     	}
	   	}
	   	die();
	}



	/**
	 * Add Languages selector in edit.php edit after Category Selector (hook: restrict_manage_posts)
	 *
	 * @since 1.8.9
	 *
	 */
	function restrict_manage_languages_posts () {
		$listlanguages = $this->get_listlanguages();
		?>
		<select name="<?php echo QUETAG ?>" id="<?php echo QUETAG ?>" class='postform'>
			<option value=""> <?php _e('View all languages','xili-language') ?> </option>

			<option value="<?php echo LANG_UNDEF ?>" <?php echo ( isset ( $_GET[QUETAG] ) && $_GET[QUETAG] == LANG_UNDEF ) ? "selected=selected" : "" ; ?> > <?php _e('Without language','xili-language') ?> </option>

			<?php foreach ($listlanguages as $language)  {
				$selected = ( isset ( $_GET[QUETAG] ) && $language->slug == $_GET[QUETAG] ) ? "selected=selected" : "" ;
				echo '<option value="'.$language->slug.'" '.$selected.' >'.__($language->description, 'xili-language').'</option>';
			}
			?>
			</select>
		<?php
	}

/******************************* TAXONOMIES ****************************/

	function xili_manage_tax_column_name ( $cols ) {

		$ends = array('posts');
		$end = array();
		foreach( $cols AS $k=>$v ) {
			if(in_array($k, $ends)) {
				$end[$k] = $v;
				unset($cols[$k]);
			}
		}
		$cols[TAXONAME] = __('Language','xili-language');
		$cols = array_merge($cols, $end);

		//if ( class_exists ('xili_dictionary') ) $this->taxlist = array();

		$this->local_theme_mos = $this->get_localmos_from_theme() ;

		return $cols;
	}

	function xili_manage_tax_column ( $content, $name, $id ) {
		if( $name != TAXONAME )
			return $content; // to have more than one added column 2.8.1

		global $taxonomy ;
		$tax = get_term((int)$id , $taxonomy ) ;
		$a = '<div class="taxinmoslist" >';

		$res = $this->is_msg_saved_in_localmos ( $tax->name, 'msgid' );

		if ( false === strpos ( $res[0] , '**' ) ) {

			$a .= __( 'ready in mo file:', 'xili-language' )." ";

			$a .= $res[0];
		} else {
			$a .= __( 'need mo file', 'xili-language' )." ";
		}

		$a .= '</div>';
	  	return $content.$a; // 2.8.1 - to have more than one filter for this column ! #21222 comments...
	}

	function xili_manage_tax_action ( $actions, $tag ) {
		return $actions;
	}

	function show_translation_msgstr ( $tag, $taxonomy ) {
		if ( !class_exists('xili_dictionary' ) ) {
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="description"><?php _e('Translated in', 'xili-language'); ?></label></th>
			<td>
			<?php
			echo '<fieldset class="taxinmos" ><legend>'.__('Name').'</legend>';
			$a = $this->is_msg_saved_in_localmos ( $tag->name, 'msgid', '', 'single' ); echo $a[0];

			echo '</fieldset><br /><fieldset class="taxinmos" ><legend>'.__('Description').'</legend>';
			$a = $this->is_msg_saved_in_localmos ( $tag->description, 'msgid', '', 'single' ); echo $a[0];

			echo '</fieldset>';

			?>
			<p><em><?php _e( 'This list above gathers the translations of name and description saved in current local-xx_XX.mo files of the current theme.', 'xili-language'); ?></em></p>
			</td>
		</tr>

		<?php
		}
	}

	/**
	 * Update msgid list when a term is created
	 *
	 * @updated 2.8.4.2
	 *
	 */
	function update_xd_msgid_list ( $taxonomy ) {
		if ( class_exists ('xili_dictionary') ) {
			global $xili_dictionary;

			if ( isset ( $_POST['tag-name'] ) && $_POST['tag-name'] != '' ) {
				$nbterms = $xili_dictionary->xili_read_catsterms_cpt( $taxonomy, $xili_dictionary->local_tag );

				if ( $nbterms[0] +  $nbterms[1] > 0 )
					echo '<p>' . sprintf( __( 'xili-dictionary: msgid list updated (n=%1s, d=%2s', 'xili-dictionary' ), $nbterms[0], $nbterms[1] ) . ')</p>';	}

		} else {
			echo '<p><strong>' . __( 'xili-dictionary plugin is not active to prepare language local .po files.', 'xili-dictionary' ). '</strong></p>';
		}
	}


/******************************* MO TOOLS FOR TAXONOMIES AND LOCAL VALUES ****************************/

	/**
	 * test if line is in entries
	 * @since 2.6.0
	 */
	function is_msg_in_entries ( $msg, $type, $entries, $context ) {
		foreach ($entries as $entry) {
			$diff = 1;
			switch ( $type ) {
		 		case 'msgid' :
		 			$diff = strcmp( $msg , $entry->singular );
		 			if ( $context != "" ) {
		 				if ( $entry->context != null ) {
		 					$diff += strcmp( $context , $entry->context );
		 				}
		 			}
					break;
				case 'msgid_plural' :
					$diff = strcmp( $msg , $entry->plural );
					break;
				case 'msgstr' :
				 if ( isset ( $entry->translations[0] ) )
					$diff = strcmp( $msg , $entry->translations[0] );
					break;
				default:
					if ( false !== strpos ( $type, 'msgstr_'  ) ) {
						$indice = (int) substr ( $type, -1) ;
						if ( isset ( $entry->translations[$indice] ) )
							$diff = strcmp( $msg , $entry->translations[$indice] );
					}
			}

			//if ( $diff != 0) { echo $msg.' i= '.strlen($msg); echo $entry->singular.') e= '.strlen($entry->singular); }
			if ( $diff == 0) return true;
		}
	return false;
	}

	function get_msg_in_entries ( $msg, $type, $entries, $context ) {
		foreach ($entries as $entry) {
			$diff = 1;
			switch ( $type ) {
		 		case 'msgid' :
		 			$diff = strcmp( $msg , $entry->singular );
		 			if ( $context != "" ) {
		 				if ( $entry->context != null ) {
		 					$diff += strcmp( $context , $entry->context );
		 				}
		 			}
					break;
				case 'msgid_plural' :
					$diff = strcmp( $msg , $entry->plural );
					break;
				case 'msgstr' :
				 if ( isset ( $entry->translations[0] ) )
					$diff = strcmp( $msg , $entry->translations[0] );
					break;
				default:
					if ( false !== strpos ( $type, 'msgstr_'  ) ) {
						$indice = (int) substr ( $type, -1) ;
						if ( isset ( $entry->translations[$indice] ) )
							$diff = strcmp( $msg , $entry->translations[$indice] );
					}
			}

			//if ( $diff != 0) { echo $msg.' i= '.strlen($msg); echo $entry->singular.') e= '.strlen($entry->singular); }
			if ( $diff == 0) {
				if ( isset ( $entry->translations[0] ) ) {
					return array( 'msgid' => $entry->singular , 'msgstr' => $entry->translations[0] );
				} else {
					return array() ;
				}
			}
		}
	return array() ;
	}


	/**
	 * Detect if cpt are saved in theme's languages folder
	 * @since 2.0
	 *
	 */
	function is_msg_saved_in_localmos ( $msg, $type, $context = "", $mode = "list" ) {
		$thelist = array();
		$thelistsite = array();
		$outputsite = "";
		$output = "";

			$listlanguages = $this->get_listlanguages();

		 	foreach ($listlanguages as $reflanguage) {
		 		if ( isset($this->local_theme_mos[$reflanguage->slug]) ) {
		 			if ( $mode == "list"  && $this->is_msg_in_entries ( $msg, $type, $this->local_theme_mos[$reflanguage->slug], $context ) ) {
		 				$thelist[] = '<span class="lang-'. $reflanguage->slug .'" >'. $reflanguage->name .'</span>';
		 			} else if ( $mode == "single" ) {
		 				$res = $this->get_msg_in_entries ( $msg, $type, $this->local_theme_mos[$reflanguage->slug], $context ) ;
		 				if ( $res != array () )
		 					$thelist[$reflanguage->name] = $res ;
		 			}
		 		}

		 		if ( is_multisite() ) {
		 			if ( isset($this->local_site_mos[$reflanguage->slug]) ) {
		 				if ( $this->is_msg_in_entries ( $msg, $type, $this->local_site_mos[$reflanguage->slug], $context ) )
		 					$thelistsite[] = '<span class="lang-'. $reflanguage->slug .'" >'. $reflanguage->name .'</span>';
		 			}
		 		}

		 	}

		 	if ( $mode == "list" ) {

			$output = ($thelist == array()) ? '<br /><small><span style="color:black" title="'.__("No translations saved in theme's .mo files","xili-dictionary").'">**</span></small>' : '<br /><small><span style="color:green" title="'.__("Original with translations saved in theme's files: ","xili-dictionary").'" >'. implode(' ',$thelist).'</small></small>';

			if ( is_multisite() ) {

				$outputsite = ($thelistsite == array()) ? '<br /><small><span style="color:black" title="'.__("No translations saved in site's .mo files","xili-dictionary").'">**</span></small>' : '<br /><small><span style="color:green" title="'.__("Original with translations saved in site's files: ","xili-dictionary").'" >'. implode(', ',$thelistsite).'</small></small>';

			}

		 	} else if ( $mode == "single" ) {

		 		if  ($thelist == array()) {

		 			$output = __('Not yet translated in any language','xili-language') .'<br />';
		 		} else {
		 			$output = '';
		 			foreach ( $thelist as $key => $msg ) {

		 				$output .=  '<span class="lang-'. strtolower ( $key ) .'" >' . $key . '</span> : ' . $msg['msgstr'] . '<br />';
		 			}
		 		}
		 	}

			return array ( $output, $outputsite ) ;

	}

	/**
	 * create an array of local mos content of theme
	 *
	 * @since 2.6.0
	 */
	 function get_localmos_from_theme() {
	 	$local_theme_mos = array();

	 		$listlanguages = $this->get_listlanguages();

	 		if ( is_multisite() ) {
	 			if ( ( $uploads = wp_upload_dir() ) && false === $uploads['error'] ) {
					$folder = $uploads['basedir']."/languages";
	 			}
	 		}

	 		foreach ( $listlanguages as $reflanguage ) {
	 			if ( is_multisite() ) {
	 				$folder_file = $folder . '/local-' . $reflanguage->name . '.mo';
	 			} else {
	 				$folder_file = '';
	 			}

	     		$res = $this->pomo_import_MO ( $reflanguage->name, $folder_file, true ); // local only
	     		if ( false !== $res ) $local_theme_mos[$reflanguage->slug] = $res->entries;
	 		}

	 	return $local_theme_mos;
	 }

	/**
	 * Import MO file in class PO
	 *
	 *
	 * @since 1.0.2 - only WP >= 2.8.4
	 * @updated 1.0.5 - for wpmu
	 * @param lang
	 * @param $mofile since 1.0.5
	 */
	function pomo_import_MO ( $lang = "", $mofile = "", $local = false ) {
		$mo = new MO();

		if ( $mofile == "" &&  $local == true ) {
			$mofile = $this->get_template_directory.$this->xili_settings['langs_folder'] .'/'.'local-'.$lang.'.mo';
		} else if ( '' == $mofile ) {
			$mofile = $this->get_template_directory.$this->xili_settings['langs_folder'] .'/'.$lang.'.mo';
		}

		if ( file_exists($mofile) ) {
			if ( !$mo->import_from_file( $mofile ) ) {
				return false;
			} else {

				return $mo;
			}
		} else {
			return false;
		}
	}

/******************************* LINKS ****************************/

	/**
	 * @updated 1.8.0
	 */
	function add_custom_box_in_link() {

 		add_action( 'add_meta_boxes_link', array( &$this,'new_box' ) );
	}



	/**
	 * Box, action and function to set language in edit-link-form
	 * @ since 1.8.5
	 */
	function new_box () {
		add_meta_box('linklanguagediv', __("Link's language","xili-language"), array(&$this,'link_language_meta_box'), 'link', 'side', 'core');
	}

	function link_language_meta_box( $link) {

		$theid = '['.$link->link_id.'] ';
		$ress = wp_get_object_terms($link->link_id, 'link_'.TAXONAME);
		$curlangname = "";
		if ( $ress ) {
			$obj_term = $ress[0];
			if ( '' != $obj_term->name ) :
				$curlangname = $obj_term->name;
			endif;
		}

		echo '<h4>'.__('Check the language for this link','xili-language').'</h4><div style="line-height:1.7em;">';
		// built the check series with saved check if edit
		$listlanguages = get_terms_of_groups_lite ( $this->langs_group_id, TAXOLANGSGROUP, TAXONAME, 'ASC' );
		$l = 2;
		foreach ( $listlanguages as $language ) {
			if ( $l % 3 == 0 && $l != 3) { echo '<br />'; }
			?>

				<label class="check-lang selectit" for="xili_language_check_<?php echo $language->slug ; ?>"><input id="xili_language_check_<?php echo $language->slug ; ?>" name="xili_language_set" type="radio" value="<?php echo $language->slug ; ?>"  <?php if( $curlangname==$language->name ) echo 'checked="checked"' ?> /> <?php _e($language->description, 'xili-language'); ?></label>

				<?php } /*link to top of sidebar*/?>
				<br /><label class="check-lang selectit" for="xili_language_check" ><input id="xili_language_check_ever" name="xili_language_set" type="radio" value="ev_er" <?php if($curlangname=="ev_er") echo 'checked="checked"' ?> /> <?php _e('Ever','xili-language') ?></label>
				<label class="check-lang selectit" for="xili_language_check" ><input id="xili_language_check" name="xili_language_set" type="radio" value="" <?php if($curlangname=="") echo 'checked="checked"' ?> /> <?php _e('undefined','xili-language') ?></label><br /></div>
			  	<br /><small>© xili-language <?php echo XILILANGUAGE_VER; ?></small>
		<?php

	}

	function print_styles_link_edit () {
		echo "<!---- xl options css links  ----->\n";
	 	echo '<style type="text/css" media="screen">'."\n";
	 		echo ".check-lang { border:solid 1px grey; margin:1px 0px; padding:3px 4px; width:45%; display:inline-block; }\n";
	 	echo "</style>\n";

	 	if ( $this->exists_style_ext && $this->xili_settings['external_xl_style'] == "on" ) wp_enqueue_style( 'xili_language_stylesheet' );
	}

	/**
	 * Action and filter to add language column in link-manager page
	 * @ since 1.8.5
	 */


	function xili_manage_link_column_name ( $cols ) {
				$ends = array('rel', 'visible', 'rating'); // insert language before rel
				$end = array();
				foreach($cols AS $k=>$v) {
					if(in_array($k, $ends)) {
						$end[$k] = $v;
						unset($cols[$k]);
					}
				}
				$cols[TAXONAME] = __('Language','xili-language');
				$cols = array_merge($cols, $end);
				return $cols;
	}

	function manage_link_lang_column ( $column_name, $link_id ) {

		if ( $column_name != TAXONAME )
					return;
		$ress = wp_get_object_terms($link_id, 'link_'.TAXONAME);
		if ( $ress ) {
			$obj_term = $ress[0];
			echo $obj_term->name ;
		}
	}

	/**
	 * To edit language when submit in edit-link-form
	 * @ since 1.8.5
	 */
	function edit_link_set_lang ( $link_id ) {
		// create relationships with link_language taxonomy
				$sellang = $_POST['xili_language_set'];
				// test if exist in link taxinomy or create it
				$linklang = term_exists($sellang,'link_'.TAXONAME);
				if ( !$linklang ) {
					$lang = term_exists($sellang,TAXONAME);
					$lang_term = get_term($lang[ 'term_id' ], TAXONAME );
					wp_insert_term( $lang_term -> name, 'link_'.TAXONAME , array ( 'alias_of' => '', 'description' => $lang_term -> description, 'parent' => 0, 'slug' => $lang_term->slug )  );
				}

				if ("" != $sellang) {
					wp_set_object_terms($link_id, $sellang, 'link_'.TAXONAME);
				} else {
					wp_delete_object_term_relationships( $link_id, 'link_'.TAXONAME );
				}
	}

	/**
	 * Function to manage mo files downloaded from automattic
	 *
	 *
	 */
	function download_mo_from_automattic( $locale = 'en_US', $version , $theme_name = "", $upgrade = 0  ) {

		$mofile = WP_LANG_DIR."/$locale.mo";

		// does core file exists in current installation ?
		if ((file_exists($mofile) && $upgrade == 0 ) || $locale == 'en_US')
			return true;

		// does language directory exists in current installation ?
		if (!is_dir(WP_LANG_DIR)) {
			if (!@mkdir(WP_LANG_DIR))
				return false;
		}

		// will first look in tags/ (most languages) then in branches/ (only Greek ?)
		$automattic_locale_root = 'http://svn.automattic.com/wordpress-i18n/'.$locale;
		$automattic_locale_root = $automattic_locale_root.'/branches/' ;  // replaces /tags/ 2014-02-01


		$args = array('timeout' => 30, 'stream' => true);

		if ( $upgrade != 2 ) { // only core files
		// try to download the file

			$resp = wp_remote_get($automattic_locale_root."$version/messages/$locale.mo", $args + array('filename' => $mofile));
			if (is_wp_error($resp) || 200 != $resp['response']['code'])
				continue;

			// try to download ms and continents-cities files if exist (will not return false if failed)
			// with new files introduced in WP 3.4
			foreach (array('ms', 'continents-cities', 'admin', 'admin-network') as $file) {
				$url = $automattic_locale_root."$version/messages/$file-$locale.mo";
				if ( $this->url_exists( $url ) )
					wp_remote_get( $url, $args + array('filename' => WP_LANG_DIR."/$file-$locale.mo") );
			}
		}

				// try to download theme files if exist (will not return false if failed)
				// FIXME not updated when the theme is updated outside a core update
		if ( in_array ($theme_name, $this->embedded_themes ) ) {
			 $url = $automattic_locale_root."$version/messages/$theme_name/$locale.mo";
			 if ( $this->url_exists( $url ) ) {
				wp_remote_get($url, $args + array('filename' => get_theme_root()."/$theme_name/languages/$locale.mo"));
			}
		}
		return true;

	}

	// GlotPress version sub-folder

	function glotPress_version_folder ( $version ) {
		$version_parts = explode ( '.', $version );
		$version_to_search = $version_parts[0] . '.' . $version_parts[1] . '.x' ;
		$list = wp_remote_get( 'http://translate.wordpress.org/api/projects/wp/' );
		if ( is_wp_error( $list ) || wp_remote_retrieve_response_code( $list ) !== 200 ) {
			return '' ;
		}
		$list = json_decode( wp_remote_retrieve_body( $list ) );
		if ( is_null( $list ) )
			return '' ;

		$filtered = wp_list_filter( $list->sub_projects, array( 'name' => $version_to_search )) ; // search in list  of sub_projects 2014-02-01
		//error_log ( $version_to_search . '**********' . serialize( $filtered  ));
		$filtereds = array_shift( $filtered );
		if ( empty( $filtereds ) )
			return '' ;
		//error_log ( '**** path = '. $filtereds->slug );
		return $filtereds->slug ;
	}

	/**
	 * Download from translation.wordpress.org
	 *
	 */
	function download_mo_from_translate_wordpress( $locale = 'en_US', $version , $theme_name = "", $upgrade = 0  ) {

		$locale_subfolder = $this->check_versions_in_glotpress ( $locale, $version ) ;
		// return subfolder at WP (en, en-ca, fr, zn-tw…)
		if ( $locale_subfolder === false ) return false;

		$version_folder = $this->glotPress_version_folder ( $version ) ;

		$mofile = WP_LANG_DIR."/$locale.mo";

		// does file exists in current installation ?
		if ((file_exists($mofile) && $upgrade == 0 ) || $locale == 'en_US')
			return true;

		// does language directory exists in current installation ?
		if (!is_dir(WP_LANG_DIR)) {
			if (!@mkdir(WP_LANG_DIR))
				return false;
		}

		// will first look in tags/ (most languages) then in branches/ (only Greek ?)
		$translate_wordpress_root = 'http://translate.wordpress.org/projects/wp/'.$version_folder .'/';

		$suffix = 'mo'; // tested with po

		// 'http://translate.wordpress.org/projects/wp/3.5.x/admin/fr/default/export-translations'
		// GET ( sent by verified jquery in above url)

		$sub_folder_array = array (
			'default' => '%lang%/default',
			'admin' => 'admin/%lang%/default',
			'admin-network' => 'admin/network/%lang%/default',
			'continents-cities' => 'cc/%lang%/default',
		);
		if ( $upgrade != 2 ) { // only theme files
			foreach ( $sub_folder_array as $prename => $one_subfolder ) {

				$url = $translate_wordpress_root . str_replace ( '%lang%', $locale_subfolder, $one_subfolder ) . '/export-translations?format='.$suffix ;

				$fileprename = ( $prename != 'default' ) ? $prename . '-' : '' ;
				$request = wp_remote_get( $url , array( 'filename' => WP_LANG_DIR."/".$fileprename.$locale.".".$suffix , 'timeout' => 15, 'stream' => true, 'body' => array() ) );

				if ( 200 != wp_remote_retrieve_response_code( $request ) ){
					unlink( WP_LANG_DIR."/".$fileprename.$locale.".".$suffix );
					// see /wp-includes/file.php
				}
			}
		}

		if ( in_array ($theme_name, $this->embedded_themes ) ) {

		// thanks for format  - markoheijnen - http://buddypress.trac.wordpress.org/raw-attachment/ticket/4857/translatecode-003.php, http://buddypress.trac.wordpress.org/attachment/ticket/4857/translatecode-003.php

		// temp patch for twentythirteen 20130503 - fixed 20130504 via polyglots blog
			//$theme_subfolder = ( $theme_name == 'twentythirteen' ) ? 'twenty-thirteen' : $theme_name ;

			$url = $translate_wordpress_root . $theme_name . '/' . $locale_subfolder . '/default/export-translations?format='.$suffix ;
			$request = wp_remote_get( $url , array( 'filename' => get_theme_root()."/$theme_name/languages/$locale.".$suffix , 'timeout' => 15, 'stream' => true, 'body' => array() ) );


			if ( 200 != wp_remote_retrieve_response_code( $request ) ){
				@unlink( get_theme_root()."/$theme_name/languages/$locale.".$suffix );
				// see /wp-includes/file.php
			}

		}
		return true;
	}

	function url_exists($url) {
        //if (!$fp = curl_init($url)) return false;
        //return true;
        $file = $url;
		$file_headers = @get_headers($file);
		if( $file_headers[0] == 'HTTP/1.1 404 Not Found' ) {
    		return false;
		} else {
		    return true;
		}
    }

    /**
     * set new default value screen options for new admin user
     *
     * @since 2.9.30
     */
	function default_nav_menus_screen_options ( $meta_id, $object_id, $meta_key, $_meta_value ) {

		if ( $meta_key == 'metaboxhidden_nav-menus' ) {

			 if ( user_can ( $object_id , 'edit_theme_options' ) ) {
			 	$intersect = array_intersect ( $_meta_value  , array( "insert-xl-list", "insert-xlspage-list", "insert-xlmenus-list" ) );
			 	if ( $intersect != array() )
			 		update_user_option( $object_id, 'metaboxhidden_nav-menus', array_diff ( $_meta_value  , array( "insert-xl-list", "insert-xlspage-list", "insert-xlmenus-list" ) ), true );
			 }
		}
	}

	/**
	 * Contextual help
	 *
	 * @since 1.7.0
	 * @updated 2.4.1, 2.6.2, 2.8.8
	 */
	 function add_help_text( $contextual_help, $screen_id, $screen ) {

	 	if ( $screen->id == 'nav-menus' ) { // 2.8.8
	 		$wikilink = $this->wikilink . '/index.php/Xili-language:_languages_list_insertion_in_nav_menu';
	 		$to_remember =
	 	  '<p><em>' . __('To show insertion metabox, remember to check them inside Screen Options.','xili-language') . '</em></p>' .
	      '<p><strong>' . __('Things to remember to insert Languages list:','xili-language') . '</strong></p>' .
	      '<ul>' .
		      '<li>' . __('Checking radio button, choose type of languages list to insert.','xili-language') . '</li>' .
		      '<li>' . __('Click the button - Add to Menu -.','xili-language') . '</li>' .
		      '<li>' . __('Drag and drop to the desired place.','xili-language') . '</li>' .
		      '<li>' . __('Do not modify content of url and label. These infos will be used to generate the final languages list according position in website during navigation.','xili-language') . '</li>' .
	      '</ul>'.
	      '<p><strong>' . __('Things to remember to insert Pages Selection:','xili-language') . '</strong></p>' .
	      '<ul>' .
		      '<li>' . __('With prefix - include= - fill the list of page IDs where sub-selection will be done according current language','xili-language') . '</li>' .
		      '<li><em>' . __('Args is like in function wp_list_pages, example: <em>include=11,15</em><br />Note: If args kept empty, the selection will done on all pages (avoid it).','xili-language') . '</em></li>' .
		      '<li>' . __('Check the input field line,','xili-language') . '</li>' .
		      '<li>' . __('Click the button - Add to Menu -.','xili-language') . '</li>' .
		      '<li>' . __('Drag and drop to the desired place.','xili-language') . '</li>' .

	      '</ul>'.
	      '<p><strong>' . __('Things to remember to insert Menus Selection:','xili-language') . '</strong></p>' .
	      '<ul>' .
		      '<li>' . __('After creating menu structures containing items linked to a language (but not assigned to a loacation), select a menu structure for each language in Menus selection box.','xili-language') . '</li>' .

		      '<li>' . __('Check after selecting,','xili-language') . '</li>' .
		      '<li>' . __('Click the button - Add to Menu -.','xili-language') . '</li>' .
		      '<li>' . __('Drag and drop to the desired place.','xili-language') . '</li>' .
		      '<li><em>' . __('If after changing or removing menu, you see - unavailable menu - in Menu list insertion point box, you must remove this insertion point and create a new one with new menus.','xili-language') . '</em></li>' .

	      '</ul>'.
	      '<p>' . sprintf(__('<a href="%s" target="_blank">Xili Wiki Documentation</a>','xili-language'), $wikilink ) . '</p>'  ;

	 		$screen->add_help_tab( array(
 				'id'      => 'xili-language-list',
				'title'   => sprintf( __('About %s insertion points', 'xili-language'), '[©xili]'),
				'content' => $to_remember,
		  ));

	 	}


	  	if ( in_array ( $screen->id , array ('settings_page_language_page', 'settings_page_language_front_set',  'settings_page_language_expert', 'settings_page_language_files', 'settings_page_language_support') ) ) {

	  		$page_title[ 'settings_page_language_page' ] = __( 'Languages page', 'xili-language' ) ;
	  		$page_title[ 'settings_page_language_front_set' ] = __( 'Languages front-end settings', 'xili-language' ) ;
	  		$page_title[ 'settings_page_language_expert' ] = __( 'Settings for experts', 'xili-language' ) ;
	  		$page_title[ 'settings_page_language_files' ] = __( 'Managing MO files', 'xili-language' ) ;
	  		$page_title[ 'settings_page_language_support' ] = __( 'xili-language support', 'xili-language' ) ;

	  		$line[ 'settings_page_language_page' ] = __('In this page, the list of languages used by the multilingual website is set.','xili-language');
	  		$line[ 'settings_page_language_front_set' ] = __('Here, you decide what happens when a visitor arrives on the website homepage with his browser commonly set according to his mother language. Xili-language offers multiple ways according your content strategy.','xili-language');
	  		$line[ 'settings_page_language_expert' ] = __('This sub-page will present how to set navigation menu in multilingual context with xili-language.','xili-language');

	  		// list
	  		$line[ 'settings_page_language_files' ] =  __( 'This sub-page will help to import MO files from WordPress SVN.', 'xili-language' ) . '</li>' .
	  		'<li>' . __('Be aware that, before to be displayed, this page scans datas from servers, be patient, it takes time.','xili-language') . '</li>' .
	  		'<li>' . __('If the theme is a child theme, in the box containing infos about theme, a list of languages from the parent theme is shown.','xili-language') . '</li>' .
	  		'<li>' . sprintf ( __('If the option %s is checked, available translations from child are merged with those from parent.','xili-language'), "<em>" . __( 'MO merging between parent and child','xili-language') . "</em>" ) . '</li>';
	  		;

	  		$line[ 'settings_page_language_support' ] = __('This form to email to dev.xiligroup.com team your observations.','xili-language');

	  		$wiki_page[ 'settings_page_language_page' ] = '/index.php/Xili-language_settings:_the_list_of_languages,_line_by_line';
	  		$wiki_page[ 'settings_page_language_front_set' ] = '/index.php/Xili-language_settings:_Home_page_and_more...';
	  		$wiki_page[ 'settings_page_language_expert' ] = '/index.php/Xili-language:_navigation_menu';
	  		$wiki_page[ 'settings_page_language_files' ] = '/index.php/Xili-language:_managing_mo_files';
	  		$wiki_page[ 'settings_page_language_support' ] = '/index.php/Xili-language_settings:_Assistance,_support_form';

	   	  $this_tab =
	   	  '<p><strong>' . sprintf( __('About this tab %s:','xili-language'), $page_title[$screen->id] ) . '</strong></p>' .
	   	  '<ul>' .
	      '<li>' . $line[$screen->id] .'</li>' .
	      '<li>' . sprintf(__('<a href="%s" target="_blank">Xili Wiki Post</a>','xili-language'), $this->wikilink . $wiki_page[$screen->id] ) . '</li>' .
	   	  '</ul>' ;

	   	  $to_remember =
	      '<p><strong>' . __('Things to remember to set xili-language:','xili-language') . '</strong></p>' .
	      '<ul>' .
	      '<li>' . __('Verify that the theme is localizable (like kubrick, fusion or twentyten or others...).','xili-language') . '</li>' .
	      '<li>' . __('Define the list of targeted languages.','xili-language') . '</li>' .
	      '<li>' . __('Prepare .po and .mo files for each language with poEdit or xili-dictionary plugin.','xili-language') . '</li>' .
	      '<li>' . __('If your website contains custom post type: check those which need to be multilingual. xili-language will add automatically edit meta boxes.','xili-language') . '</li>' .
	      '</ul>' ;

	      $more_infos =
	      '<p><strong>' . __('For more information:') . '</strong></p>' .
	      '<p>' . __('<a href="http://dev.xiligroup.com/xili-language" target="_blank">Xili-language Plugin Documentation</a>','xili-language') . '</p>' .
	      '<p>' . sprintf(__('<a href="%s" target="_blank">Xili Wiki Documentation</a>','xili-language'), $this->wikilink ) . '</p>' .
	      '<p>' . __('<a href="http://dev.xiligroup.com/?post_type=forum" target="_blank">Support Forums</a>','xili-language') . '</p>' .
	      '<p>' . __('<a href="http://codex.wordpress.org/" target="_blank">WordPress Documentation</a>','xili-language') . '</p>' ;

	      $screen->add_help_tab( array(
 				'id'      => 'this-tab',
				'title'   => __('About this tab','xili-language'),
				'content' => $this_tab,
		  ));

	      $screen->add_help_tab( array(
 				'id'      => 'to-remember',
				'title'   => __('Things to remember','xili-language'),
				'content' => $to_remember,
		  ));

	      $screen->add_help_tab( array(
 				'id'      => 'more-infos',
				'title'   => __('For more information', 'xili-language'),
				'content' => $more_infos,
		  ));

	  }
	  return $contextual_help;
	}

}


?>