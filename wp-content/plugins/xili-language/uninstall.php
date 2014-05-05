<?php

/**
 * During uninstall: delete all taxonomies elements and relationships
 *
 * @since 1.8.8
 *
 * ONLY WORKS IF WEBMASTER has checked "delete DB datas" in xili-language settings BEFORE deactivate and fires delete in plugins list.
 *
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || is_multisite() ) {// must done site by site 
	echo 'Impossible to erase plugin settings in multisite mode - need to done site by site before erasing folder !';
	exit();
}
// check if delete_settings is set 

$xl_settings = get_option('xili_language_settings');

if ( $xl_settings['delete_settings'] == 'delete'  ) {
	
	delete_taxonomies ($xl_settings);
	
	// Options
	delete_option('xili_language_widgets_options');
	delete_option('xili_widget_recent_comments');
	delete_option('xili_widget_recent_entries');
	delete_option('xili_language_settings');
}

function delete_taxonomies ($xl_settings) {
	
	// temporary register taxonomies (plugin is deactivated)
	
	register_taxonomy( $xl_settings['taxonomy'], 'post', array('hierarchical' => false, 'label' => false, 'rewrite' => false,   'show_ui' => false, '_builtin' => false ));
	register_taxonomy( $xl_settings['taxolangsgroup'], 'term', array('hierarchical' => false, 'update_count_callback' => '', 'show_ui' => false, 'label'=>false, 'rewrite' => false, '_builtin' => false ));
	register_taxonomy( 'link_'.$xl_settings['taxonomy'], 'link', array('hierarchical' => false, 'label' => false, 'rewrite' => false, 'show_ui' => false, '_builtin' => false ));
	
	
	// list of languages
	
	$languages = get_terms($xl_settings['taxonomy'], array('hide_empty' => false));
	//update_option ( 'xili_language_settings_bk', $xl_settings );
	
	// array postmeta lang-xx_xx
	foreach ($languages as $language ) {
		$postmeta_suffixes[] = $language->slug ;
	}
	foreach ($languages as $language )  {
		
		$term_id = $language->term_id;
		
		$post_IDs = get_objects_in_term( array( $term_id ), array( $xl_settings['taxonomy'] ) );
		
		foreach ( $post_IDs as $post_ID ) {
			// delete postmeta lang-xx_xx
			foreach ( $postmeta_suffixes as $postmeta_suffix ) {
				if ( $language->slug != $postmeta_suffix ) delete_post_meta( $post_ID, $xl_settings['reqtag'].'-'.$postmeta_suffix ) ;
			}
			// delete relationships posts
	 		wp_delete_object_term_relationships( $post_ID, $xl_settings['taxonomy'] );
		}
		
	 	wp_delete_object_term_relationships( $term_id, $xl_settings['taxolangsgroup'] );
		
		// link_language links
		$links = get_objects_in_term( array( $term_id ), array( 'link_'.$xl_settings['taxonomy'] ) );
		
		foreach ( $links as $link ) {	   
			wp_delete_object_term_relationships( $link, 'link_'.$xl_settings['taxonomy'] );
		}
		
		// delete terms
		$linklang = term_exists($language->slug,'link_'.$xl_settings['taxonomy']);
			if ( $linklang ) wp_delete_term( $term_id, 'link_'.$xl_settings['taxonomy'] );
		wp_delete_term( $term_id, $xl_settings['taxonomy'] );
	 
	} 
	$term_group = term_exists( 'ev_er', 'link_'.$xl_settings['taxonomy'] ); /* special ever language for links */
	// link_language links
	$links = get_objects_in_term( array( $term_group['term_id'] ), array( 'link_'.$xl_settings['taxonomy'] ) );
		
	foreach ( $links as $link ) {	   
		wp_delete_object_term_relationships( $link_id, 'link_'.$xl_settings['taxonomy'] );
	}
	wp_delete_term( $term_group['term_id'], 'link_'.$xl_settings['taxonomy'] );
	
	// delete taxonomie groups ['taxolangsgroup'] - when count = 0
	$term_group = term_exists( 'the-langs-group', $xl_settings['taxolangsgroup'] );
	wp_delete_term( $term_group['term_id'], $xl_settings['taxolangsgroup'] );
	
}

?>