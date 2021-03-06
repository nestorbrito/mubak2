<?php

/**
 * Advanced Ads.
 *
 * @package   Advanced_Ads
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2013-2015 Thomas Maier, webgilde GmbH
 */

/**
 * This class is used to bundle all ajax callbacks
 *
 * @package Advanced_Ads_Ajax_Callbacks
 * @author  Thomas Maier <thomas.maier@webgilde.com>
 */
class Advanced_Ads_Ad_Ajax_Callbacks {

	public function __construct() {

		// NOTE: admin only!
		//add_action( 'wp_ajax_load_content_editor', array( $this, 'load_content_editor' ) );
		add_action( 'wp_ajax_load_ad_parameters_metabox', array( $this, 'load_ad_parameters_metabox' ) );
		add_action( 'wp_ajax_load_visitor_conditions_metabox', array( $this, 'load_visitor_condition' ) );
		add_action( 'wp_ajax_load_display_conditions_metabox', array( $this, 'load_display_condition' ) );
		add_action( 'wp_ajax_advads-terms-search', array( $this, 'search_terms' ) );
		add_action( 'wp_ajax_advads-close-notice', array( $this, 'close_notice' ) );
		add_action( 'wp_ajax_advads-subscribe-notice', array( $this, 'subscribe' ) );
		add_action( 'wp_ajax_advads-activate-license', array( $this, 'activate_license' ) );
		add_action( 'wp_ajax_advads-deactivate-license', array( $this, 'deactivate_license' ) );
		add_action( 'wp_ajax_advads-adblock-rebuild-assets', array( $this, 'adblock_rebuild_assets' ) );
		add_action( 'wp_ajax_advads-post-search', array( $this, 'post_search' ) );
		add_action( 'wp_ajax_advads-ad-injection-content', array( $this, 'inject_placement' ) );
		add_action( 'wp_ajax_advads-save-hide-wizard-state', array( $this, 'save_wizard_state' ) );

	}

	/**
	 * load content of the ad parameter metabox
	 *
	 * @since 1.0.0
	 */
	public function load_ad_parameters_metabox() {
		if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
			return;
		}

		$types = Advanced_Ads::get_instance()->ad_types;
		$type = $_REQUEST['ad_type'];
		$ad_id = absint( $_REQUEST['ad_id'] );
		if ( empty($ad_id) ) { die(); }

		$ad = new Advanced_Ads_Ad( $ad_id );

		if ( ! empty($types[$type]) && method_exists( $types[$type], 'render_parameters' ) ) {
			$type = $types[ $type ];
			$type->render_parameters( $ad );

			include ADVADS_BASE_PATH . 'admin/views/ad-parameters-size.php';
		}

		die();

	}

	/**
	 * load interface for single visitor condition
	 *
	 * @since 1.5.4
	 */
	public function load_visitor_condition() {
		if( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
		    return;
		}

		// get visitor condition types
		$visitor_conditions = Advanced_Ads_Visitor_Conditions::get_instance()->conditions;
		$condition = array();
		$condition['type'] = isset( $_POST['type'] ) ? $_POST['type'] : '';
		$index = isset( $_POST['index'] ) ? $_POST['index'] : 0;

		if( isset( $visitor_conditions[$condition['type']] ) ) {
		    $metabox = $visitor_conditions[$condition['type']]['metabox'];
		} else {
		    die();
		}

		if ( method_exists( $metabox[0], $metabox[1] ) ) {
			call_user_func( array($metabox[0], $metabox[1]), $condition, $index );
		}

		die();
	}
	/**
	 * load interface for single display condition
	 *
	 * @since 1.7
	 */
	public function load_display_condition() {
		if( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
		    return;
		}

		// get display condition types
		$conditions = Advanced_Ads_Display_Conditions::get_instance()->conditions;
		$condition = array();

		$condition['type'] = isset( $_POST['type'] ) ? $_POST['type'] : '';

		$index = isset( $_POST['index'] ) ? $_POST['index'] : 0;

		if( isset( $conditions[$condition['type']] ) ) {
		    $metabox = $conditions[$condition['type']]['metabox'];
		} else {
		    die();
		}

		if ( method_exists( $metabox[0], $metabox[1] ) ) {
			call_user_func( array($metabox[0], $metabox[1]), $condition, $index );
		}

		die();
	}

        /**
         * search terms belonging to a specific taxonomy
         *
         * @sinc 1.4.7
         */
        public function search_terms(){
			if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
				return;
			}

            $args = array();
            $taxonomy = $_POST['tax'];
            $args = array('hide_empty' => false, 'number' => 20);

            if ( !isset( $_POST['search'] ) || $_POST['search'] === '' ) { die(); }

            // if search is an id, search for the term id, else do a full text search
            if(0 !== absint($_POST['search'])){
                $args['include'] = array(absint($_POST['search']));
            } else {
                $args['search'] = $_POST['search'];
            }

            $results = get_terms( $taxonomy, $args );
            // $results = _WP_Editors::wp_link_query( $args );
            echo wp_json_encode( $results );
            echo "\n";
            die();
        }

        /**
         * search terms belonging to a specific taxonomy
         *
         * @since 1.5.3
         */
        public function close_notice(){
			if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_manage_options') ) ) {
				return;
			}

            if ( !isset( $_POST['notice'] ) || $_POST['notice'] === '' ) { die(); }

			Advanced_Ads_Admin_Notices::get_instance()->remove_from_queue($_POST['notice']);
            die();
        }

        /**
         * subscribe to newsletter
         *
         * @since 1.5.3
         */
        public function subscribe(){
			if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_see_interface') ) ) {
				return;
			}

            if ( !isset( $_POST['notice'] ) || $_POST['notice'] === '' ) { die(); }

			echo Advanced_Ads_Admin_Notices::get_instance()->subscribe($_POST['notice']);
            die();
        }

	/**
	 * activate license of an add-on
	 *
	 * @since 1.5.7
	 */
	public function activate_license(){
		if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_manage_options') ) ) {
			return;
		}

	    // check nonce
	    check_ajax_referer( 'advads_ajax_license_nonce', 'security' );

	    if ( !isset( $_POST['addon'] ) || $_POST['addon'] === '' ) { die(); }

	    echo Advanced_Ads_Admin_Licenses::get_instance()->activate_license( $_POST['addon'], $_POST['pluginname'], $_POST['optionslug'], $_POST['license'] );

	    die();
	}
	
	/**
	 * deactivate license of an add-on
	 *
	 * @since 1.6.11
	 */
	public function deactivate_license(){
		if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_manage_options') ) ) {
			return;
		}

	    // check nonce
	    check_ajax_referer( 'advads_ajax_license_nonce', 'security' );

	    if ( !isset( $_POST['addon'] ) || $_POST['addon'] === '' ) { die(); }

	    echo Advanced_Ads_Admin_Licenses::get_instance()->deactivate_license( $_POST['addon'], $_POST['pluginname'], $_POST['optionslug'] );

	    die();
	}

	/**
	 * rebuild assets for ad-blocker module
	 *
	 */
	public function adblock_rebuild_assets(){
		if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_manage_options') ) ) {
			return;
		}

		Advanced_Ads_Ad_Blocker_Admin::get_instance()->add_asset_rebuild_form();
		die();
	}

	/**
	 * post search (used in Display conditions)
	 *
	 */
	public function post_search(){
		if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
			return;
		}

		add_filter( 'wp_link_query_args', array( 'Advanced_Ads_Display_Conditions', 'modify_post_search' ) );
		add_filter( 'posts_search', array( 'Advanced_Ads_Display_Conditions', 'modify_post_search_sql' ) );

		wp_ajax_wp_link_ajax();
	}
	
	/**
	 * inject an ad and a placement
	 * 
	 * @since 1.7.3
	 */
	public function inject_placement(){
		if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
			die();
		}

	    $ad_id = absint( $_REQUEST['ad_id'] );
	    if ( empty( $ad_id ) ) { die(); }

		// use existing placement
		if ( isset( $_REQUEST['placement_slug'] ) ) {
			$xml_array[] = '<placements type="array">';
			$xml_array[] = '<item key="0" type="array">';
			$xml_array[] = '<item type="string">ad_' . $ad_id . '</item>';
			$xml_array[] = '<key type="string">' . $_REQUEST['placement_slug'] . '</key>';
			$xml_array[] = '<use_existing type="boolean">1</use_existing>';
			$xml_array[] = '</item>';
			$xml_array[] = '</placements>';

			$xml = '<advads-export>' . implode( '', $xml_array ) . '</advads-export>';

			Advanced_Ads_Import::get_instance()->import( $xml );
			if ( count( Advanced_Ads_Import::get_instance()->imported_data['placements'] ) ) {
				// if the ad was assigned
				echo $_REQUEST['placement_slug'];
			};
			die();
		}

	    // create new placement
	    $placements = Advanced_Ads::get_instance()->get_model()->get_ad_placements_array();

	    $type = esc_attr( $_REQUEST['placement_type'] );

	    $item = 'ad_' . $ad_id;
	    
	    $options = array();
	    
	    // check type
	    $placement_types = Advanced_Ads_Placements::get_placement_types();
	    if( ! isset( $placement_types[ $type ] ) ){
		die();
	    }
	    
	    $title = $placement_types[ $type ]['title'];
	    
	    $new_placement = array( 
		'type' => $type,
		'item' => $item,
		'name' => $title,
	    );
	    
	    // set content specific options
	    if( 'post_content' === $type ){
		$index = isset( $_REQUEST['options']['index'] ) ? absint( $_REQUEST['options']['index'] ) : 1;
		$new_placement['options'] = array(
                    'position' => 'after',
                    'index' => $index,
                    'tag' => 'p'
                );
	    }
	    
	    $i = 1;
	    // try to save placement until we found an empty slug
	    do { 
		$slug = Advanced_Ads_Placements::save_new_placement( $new_placement );
		$new_placement['name'] = $title . ' ' . $i;
		$i ++;
		if( $i === 100 ){ // prevent endless loop, just in case
		    Advanced_Ads::log( 'endless loop when injecting placement' );
		    break;
		}
	    } while ( ! $slug );
			
	    // return potential slug
	    echo $slug;
	    
	    die();
	}
	
	/**
	 * save ad wizard state for each user individually
	 * 
	 * @since 1.7.4
	 */
	public function save_wizard_state(){
	    if ( ! current_user_can( Advanced_Ads_Plugin::user_cap( 'advanced_ads_edit_ads') ) ) {
		    return;
	    }
	    
	    $state = ( isset( $_REQUEST['hide_wizard'] ) && 'true' === $_REQUEST['hide_wizard'] ) ? 'true' : 'false';

	    // get current user
	    $user_id = get_current_user_id();
	    if( ! $user_id ) {
		die();
	    }
	    
	    update_user_meta( $user_id, 'advanced-ads-hide-wizard', $state );
	    
	    die();
	}

}
