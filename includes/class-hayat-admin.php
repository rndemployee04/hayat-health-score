<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Hayat_Assessments_List_Table extends WP_List_Table {
    
    public function __construct() {
        parent::__construct( [
            'singular' => 'Assessment',
            'plural'   => 'Assessments',
            'ajax'     => false
        ] );
    }

    public function get_columns() {
        return [
            'id'              => 'ID',
            'first_name'      => 'Name',
            'email'           => 'Email',
            'health_score'    => 'Health Score',
            'readiness_score' => 'Readiness (1-10)',
            'utm_source'      => 'Source',
            'created_at'      => 'Date'
        ];
    }

    public function get_sortable_columns() {
        return [
            'id'              => [ 'id', false ],
            'first_name'      => [ 'first_name', false ],
            'health_score'    => [ 'health_score', false ],
            'readiness_score' => [ 'readiness_score', false ],
            'created_at'      => [ 'created_at', true ] // true means already sorted
        ];
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'first_name':
            case 'email':
            case 'health_score':
            case 'readiness_score':
                return esc_html( $item[ $column_name ] );
            case 'utm_source':
                return ! empty( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '<span style="color:#999;">Direct</span>';
            case 'created_at':
                return wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item[ $column_name ] ) );
            case 'id':
                return absint( $item[ $column_name ] );
            default:
                return print_r( $item, true );
        }
    }

    public function column_readiness_score( $item ) {
        $score = intval( $item['readiness_score'] );
        $color = $score >= 8 ? '#2E8B57' : ( $score >= 5 ? '#f0ad4e' : '#d9534f' );
        return sprintf(
            '<strong style="color:%s">%d</strong>',
            $color,
            $score
        );
    }

    public function extra_tablenav( $which ) {
        if ( $which === 'top' ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'hayat_assessments';
            $sources = $wpdb->get_col( "SELECT DISTINCT utm_source FROM $table_name WHERE utm_source != ''" );
            $current_source = isset( $_GET['filter_source'] ) ? sanitize_text_field( $_GET['filter_source'] ) : '';
            ?>
            <div class="alignleft actions">
                <select name="filter_source">
                    <option value="">All Sources</option>
                    <option value="direct" <?php selected( $current_source, 'direct' ); ?>>Direct</option>
                    <?php foreach ( $sources as $source ) : ?>
                        <option value="<?php echo esc_attr( $source ); ?>" <?php selected( $current_source, $source ); ?>><?php echo esc_html( $source ); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php submit_button( 'Filter', '', 'filter_action', false, [ 'id' => 'post-query-submit' ] ); ?>
            </div>
            <?php
        }
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hayat_assessments';

        $per_page = 20;
        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = [ $columns, $hidden, $sortable ];
        
        $orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'created_at';
        $order   = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'desc';
        
        // Validate orderby against sortable columns
        if ( ! array_key_exists( $orderby, $sortable ) ) {
            $orderby = 'created_at';
        }
        $order = ( $order === 'asc' ) ? 'ASC' : 'DESC';

        // Handle Search and Filter
        $search = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
        $filter_source = isset( $_REQUEST['filter_source'] ) ? sanitize_text_field( $_REQUEST['filter_source'] ) : '';

        $where = "WHERE 1=1";
        if ( ! empty( $search ) ) {
            $where .= $wpdb->prepare( " AND (first_name LIKE %s OR email LIKE %s)", '%' . $wpdb->esc_like( $search ) . '%', '%' . $wpdb->esc_like( $search ) . '%' );
        }
        
        if ( ! empty( $filter_source ) ) {
            if ( $filter_source === 'direct' ) {
                $where .= " AND (utm_source = '' OR utm_source IS NULL)";
            } else {
                $where .= $wpdb->prepare( " AND utm_source = %s", $filter_source );
            }
        }

        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name $where" );
        
        $this->items = $wpdb->get_results( 
            "SELECT * FROM $table_name $where ORDER BY {$orderby} {$order} LIMIT $per_page OFFSET $offset", 
            ARRAY_A 
        );

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ] );
    }
}

class Hayat_Health_Score_Admin {
    
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    public function add_admin_menu() {
        // Main Menu
        add_menu_page(
            'Health Assessments',
            'Health Leads',
            'manage_options',
            'hayat-health-assessments',
            [ $this, 'render_admin_page' ],
            'dashicons-heart',
            30
        );

        // Submenu for Leads
        add_submenu_page(
            'hayat-health-assessments',
            'Health Leads',
            'Leads',
            'manage_options',
            'hayat-health-assessments',
            [ $this, 'render_admin_page' ]
        );

        // Submenu for Settings
        add_submenu_page(
            'hayat-health-assessments',
            'Health Score Settings',
            'Settings',
            'manage_options',
            'hayat-health-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting( 'hayat_health_options_group', 'hayat_booking_url', [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => 'https://cal.com/hayattayyiba/assessment'
        ] );

        register_setting( 'hayat_health_options_group', 'hayat_scoring_config', [
            'type' => 'string',
            'sanitize_callback' => function( $val ) {
                $decoded = json_decode( $val, true );
                if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $decoded ) ) {
                    add_settings_error( 'hayat_health_options_group', 'invalid_json', 'Invalid JSON format. Changes were not saved.', 'error' );
                    return get_option( 'hayat_scoring_config' );
                }
                
                // Enforce Keys Match Original File
                $config_path = plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json';
                $default_config = file_exists( $config_path ) ? json_decode( file_get_contents( $config_path ), true ) : [];
                
                if ( ! empty( $default_config ) ) {
                    // Check top-level keys
                    $diff_top = array_diff_key( $default_config, $decoded );
                    if ( ! empty( $diff_top ) ) {
                        add_settings_error( 'hayat_health_options_group', 'invalid_keys', 'Error: You cannot add, remove, or modify the structure (keys) of the JSON. You may only change the number values.', 'error' );
                        return get_option( 'hayat_scoring_config' );
                    }
                    
                    // Check sub-keys for questions
                    foreach ( $default_config as $k => $v ) {
                        if ( is_array( $v ) ) {
                            if ( ! isset( $decoded[$k] ) || ! is_array( $decoded[$k] ) ) {
                                add_settings_error( 'hayat_health_options_group', 'invalid_keys', "Error: Missing configuration for $k.", 'error' );
                                return get_option( 'hayat_scoring_config' );
                            }
                            $diff_sub = array_diff_key( $v, $decoded[$k] );
                            if ( ! empty( $diff_sub ) ) {
                                add_settings_error( 'hayat_health_options_group', 'invalid_keys', "Error: You modified the answers/keys in $k. Please only edit the point values.", 'error' );
                                return get_option( 'hayat_scoring_config' );
                            }
                        }
                    }
                }
                
                return $val;
            },
            'default' => file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) ? file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) : ''
        ] );

        register_setting( 'hayat_health_options_group', 'hayat_pdf_testimonial', [
            'type' => 'string',
            'sanitize_callback' => 'wp_kses_post',
            'default' => '"I finally feel like I have a structured plan. The team helped me focus on the right lifestyle changes, and the improvement in my daily energy has been incredible." <br><br><strong>— A Hayat Tayyiba Client</strong>'
        ] );

        register_setting( 'hayat_health_options_group', 'hayat_primary_color', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#2E8B57'
        ] );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Hayat Health Score Settings</h1>
            <?php settings_errors( 'hayat_health_options_group' ); ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'hayat_health_options_group' ); ?>
                <?php do_settings_sections( 'hayat_health_options_group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Brand Primary Color</th>
                        <td>
                            <input type="color" name="hayat_primary_color" value="<?php echo esc_attr( get_option('hayat_primary_color', '#2E8B57') ); ?>" />
                            <p class="description">Used for buttons, progress bars, and PDF highlights.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Booking Redirect URL</th>
                        <td>
                            <input type="url" name="hayat_booking_url" value="<?php echo esc_attr( get_option('hayat_booking_url', 'https://cal.com/hayattayyiba/assessment') ); ?>" style="width: 100%; max-width: 400px;" />
                            <p class="description">Users will be redirected to this URL after clicking on the "Book Your Complimentary Consultation" button.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">PDF Testimonial</th>
                        <td>
                            <textarea name="hayat_pdf_testimonial" rows="4" style="width: 100%; max-width: 600px;"><?php echo esc_textarea( get_option('hayat_pdf_testimonial', '"I finally feel like I have a structured plan. The team helped me focus on the right lifestyle changes, and the improvement in my daily energy has been incredible." <br><br><strong>— A Hayat Tayyiba Client</strong>') ); ?></textarea>
                            <p class="description">Displayed on the final page of the PDF report.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Scoring Configuration (JSON)</th>
                        <td>
                            <?php $default_json = file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) ? file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) : ''; ?>
                            <textarea name="hayat_scoring_config" rows="15" style="width: 100%; max-width: 800px; font-family: monospace;"><?php echo esc_textarea( get_option('hayat_scoring_config', $default_json) ); ?></textarea>
                            <p class="description">Advanced: Configure point values and max scores using JSON format.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function render_admin_page() {
        $table = new Hayat_Assessments_List_Table();
        $table->prepare_items();
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Hayat Tayyiba Health Leads</h1>
            <p>View all completed health assessments and their calculated scores.</p>
            <form method="get">
                <input type="hidden" name="page" value="hayat-health-assessments" />
                <?php $table->search_box( 'Search Leads', 'search_id' ); ?>
                <?php $table->display(); ?>
            </form>
        </div>
        <?php
    }
}
