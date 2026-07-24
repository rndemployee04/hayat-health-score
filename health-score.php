<?php
/**
 * Plugin Name: GliaFit Health Score Assessment
 * Description: Lead Generation Form Questionnaire “60-Second Health Assessment”. The goal is to educate visitors, engage them, generate a personalized health score, collect their contact information, and encourage them to book a complimentary consultation.
 * Version: 0.1.0
 * Author: RND Experts
 * Author URI: https://rndexperts.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( file_exists( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' ) ) {
    require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-api.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-email.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin.php';

class Health_Score {
    public function __construct() {
        register_activation_hook( __FILE__, [ $this, 'activate_plugin' ] );
        add_shortcode( 'health_score', [ $this, 'render_shortcode' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        add_action( 'health_score_process_assessment', [ 'Health_Score_Email', 'process_assessment_and_email' ] );
        
        new Health_Score_API();

        if ( is_admin() ) {
            new Health_Score_Admin();
        }
    }

    public function activate_plugin() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NULL,
            first_name varchar(100) NULL,
            email varchar(100) NULL,
            phone varchar(50) NULL,
            risk_score int(11) NULL,
            health_score int(11) NULL,
            score_category varchar(100) NULL,
            primary_goal varchar(255) NULL,
            selected_health_concerns text NULL,
            main_concerns text NULL,
            duration varchar(100) NULL,
            energy_pattern varchar(100) NULL,
            craving_frequency varchar(100) NULL,
            diagnosed_conditions text NULL,
            previous_attempts text NULL,
            biggest_concern varchar(255) NULL,
            readiness_score int(11) NULL,
            support_preference varchar(255) NULL,
            current_mindset varchar(255) NULL,
            pdf_sent varchar(255) NULL,
            top_opportunities text NULL,
            raw_answers longtext NULL,
            utm_source varchar(100) NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        update_option( 'health_score_btn_bg_top', '#40BAD5' );
        update_option( 'health_score_btn_bg_bottom', '#07689F' );
        update_option( 'health_score_btn_hover_top', '#99ca1d' );
        update_option( 'health_score_btn_hover_bottom', '#799928' );
    }

    public function render_shortcode() {
        return '<div id="health-score-root"></div>';
    }

    public function enqueue_scripts() {
        global $post;
        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'health_score' ) ) {
            
            $asset_file_path = plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
            if ( file_exists( $asset_file_path ) ) {
                $asset_file = include( $asset_file_path );
                wp_enqueue_script(
                    'health-score-js',
                    plugin_dir_url( __FILE__ ) . 'build/index.js',
                    $asset_file['dependencies'],
                    $asset_file['version'],
                    true
                );
            }

            // Pass REST API URL, Nonce, and Booking URL to the React app
            $booking_url      = get_option( 'health_score_booking_url', '' );
            $btn_bg_top       = get_option( 'health_score_btn_bg_top', '#40BAD5' );
            $btn_bg_bottom    = get_option( 'health_score_btn_bg_bottom', '#07689F' );
            $btn_hover_top    = get_option( 'health_score_btn_hover_top', '#99ca1d' );
            $btn_hover_bottom = get_option( 'health_score_btn_hover_bottom', '#799928' );

            wp_localize_script( 'health-score-js', 'healthScoreData', [
                'restUrl'        => esc_url_raw( rest_url( 'health-score/v1/submit' ) ),
                'nonce'          => wp_create_nonce( 'wp_rest' ),
                'bookingUrl'     => esc_url_raw( $booking_url ),
                'pluginUrl'      => esc_url_raw( plugin_dir_url( __FILE__ ) ),
                'btnBgTop'       => sanitize_hex_color( $btn_bg_top ) ?: '#40BAD5',
                'btnBgBottom'    => sanitize_hex_color( $btn_bg_bottom ) ?: '#07689F',
                'btnHoverTop'    => sanitize_hex_color( $btn_hover_top ) ?: '#99ca1d',
                'btnHoverBottom' => sanitize_hex_color( $btn_hover_bottom ) ?: '#799928'
            ] );
            
            wp_enqueue_style(
                'health-score-custom-css',
                plugin_dir_url( __FILE__ ) . 'assets/css/custom.css',
                [],
                '1.0.0'
            );
        }
    }
}

// Initialize the plugin
new Health_Score();
