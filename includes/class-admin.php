<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Assessments_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => 'Assessment Lead',
            'plural'   => 'Assessment Leads',
            'ajax'     => false
        ] );
    }

    public function get_columns() {
        return [
            'cb'                     => '<input type="checkbox" />',
            'id'                     => 'ID',
            'first_name'             => 'First Name',
            'email'                  => 'Email',
            'phone'                  => 'Phone',
            'health_score'           => 'Health Score',
            'readiness_score'        => 'Readiness',
            'score_category'         => 'Category',
            'utm_source'             => 'UTM Source',
            'created_at'             => 'Date',
            'actions'                => 'Actions'
        ];
    }

    public function get_sortable_columns() {
        return [
            'id'              => [ 'id', false ],
            'first_name'      => [ 'first_name', false ],
            'health_score'    => [ 'health_score', false ],
            'readiness_score' => [ 'readiness_score', false ],
            'created_at'      => [ 'created_at', true ]
        ];
    }

    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'first_name':
            case 'email':
            case 'phone':
            case 'health_score':
            case 'score_category':
                return esc_html( $item[ $column_name ] );
            case 'utm_source':
                $src = ! empty( $item[ $column_name ] ) ? $item[ $column_name ] : 'Direct';
                return sprintf(
                    '<span style="background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block;">%s</span>',
                    esc_html( $src )
                );
            case 'readiness_score':
                $val = intval( $item[ $column_name ] );
                $badge_color = '#646970';
                if ( $val >= 8 ) {
                    $badge_color = '#00a32a'; // Green
                } elseif ( $val >= 5 ) {
                    $badge_color = '#dba617'; // Yellow/Orange
                } else {
                    $badge_color = '#d63638'; // Red
                }
                return sprintf(
                    '<span style="background-color:%s; color:#fff; padding:2px 8px; border-radius:10px; font-weight:600; font-size:11px; display:inline-block; text-align:center; min-width:24px;">%d / 10</span>',
                    $badge_color,
                    $val
                );
            case 'actions':
                $lead_data = esc_attr( wp_json_encode( $item ) );
                return '<button type="button" class="button health-view-lead" data-lead="' . $lead_data . '"><span class="dashicons dashicons-visibility"></span></button>';
            case 'created_at':
                return wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $item[ $column_name ] ) );
            case 'id':
                return $item['id'];
            default:
                return print_r( $item, true );
        }
    }

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    public function get_bulk_actions() {
        return [
            'bulk-delete' => 'Delete'
        ];
    }

    public function process_bulk_action() {
        if ( 'bulk-delete' === $this->current_action() && ! empty( $_GET['bulk-delete'] ) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'health_assessments';
            $delete_ids = (array) $_GET['bulk-delete'];
            foreach ( $delete_ids as $id ) {
                $wpdb->delete( $table_name, [ 'id' => intval( $id ) ] );
            }
        }
    }

    protected function extra_tablenav( $which ) {
        if ( 'top' !== $which ) {
            return;
        }

        $current_category = isset( $_REQUEST['filter_category'] ) ? sanitize_text_field( $_REQUEST['filter_category'] ) : '';

        $categories = [
            'Excellent'               => 'Excellent',
            'Good'                    => 'Good',
            'Fair'                    => 'Fair',
            'Needs Attention'         => 'Needs Attention',
            'Significant Opportunity' => 'Significant Opportunity'
        ];

        echo '<div class="alignleft actions">';

        echo '<select name="filter_category">';
        echo '<option value="">All Categories</option>';
        foreach ( $categories as $val => $label ) {
            printf( '<option value="%s" %s>%s</option>', esc_attr( $val ), selected( $current_category, $val, false ), esc_html( $label ) );
        }
        echo '</select>';

        submit_button( 'Filter', '', 'filter_action', false, [ 'id' => 'post-query-submit' ] );
        echo '</div>';
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';

        $per_page = 10;
        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [ $columns, $hidden, $sortable ];
        $this->process_bulk_action();

        $search          = isset( $_REQUEST['s'] ) ? sanitize_text_field( trim( $_REQUEST['s'] ) ) : '';
        $filter_category = isset( $_REQUEST['filter_category'] ) ? sanitize_text_field( trim( $_REQUEST['filter_category'] ) ) : '';

        $where_clauses = [];
        if ( ! empty( $search ) ) {
            $where_clauses[] = $wpdb->prepare(
                "(first_name LIKE %s OR email LIKE %s OR score_category LIKE %s)",
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%',
                '%' . $wpdb->esc_like( $search ) . '%'
            );
        }

        if ( ! empty( $filter_category ) ) {
            $where_clauses[] = $wpdb->prepare( "score_category = %s", $filter_category );
        }

        $where = ! empty( $where_clauses ) ? ' WHERE ' . implode( ' AND ', $where_clauses ) : '';

        $orderby = ! empty( $_GET['orderby'] ) ? sanitize_sql_orderby( $_GET['orderby'] ) : 'id';
        $order   = ! empty( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';

        $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name $where" );

        $paged = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
        $offset = ( $paged - 1 ) * $per_page;

        $this->items = $wpdb->get_results(
            "SELECT * FROM $table_name $where ORDER BY $orderby $order LIMIT $per_page OFFSET $offset",
            ARRAY_A
        );

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page )
        ] );
    }
}

class Health_Score_Admin {
    
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_init', [ $this, 'check_db_schema' ] );
    }

    public function check_db_schema() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';
        $columns_to_check = [
            'score_category'          => "VARCHAR(100) NULL",
            'primary_goal'            => "VARCHAR(255) NULL",
            'selected_health_concerns'=> "TEXT NULL",
            'main_concerns'           => "TEXT NULL",
            'duration'                => "VARCHAR(100) NULL",
            'energy_pattern'          => "VARCHAR(100) NULL",
            'craving_frequency'       => "VARCHAR(100) NULL",
            'diagnosed_conditions'    => "TEXT NULL",
            'previous_attempts'       => "TEXT NULL",
            'biggest_concern'         => "VARCHAR(255) NULL",
            'support_preference'      => "VARCHAR(255) NULL",
            'current_mindset'         => "VARCHAR(255) NULL",
            'pdf_sent'                => "VARCHAR(255) NULL"
        ];

        foreach ( $columns_to_check as $col => $definition ) {
            $exists = $wpdb->get_results( $wpdb->prepare( "SHOW COLUMNS FROM `$table_name` LIKE %s", $col ) );
            if ( empty( $exists ) ) {
                $wpdb->query( "ALTER TABLE `$table_name` ADD `$col` $definition" );
            }
        }
    }

    public function add_admin_menu() {
        // Main Menu
        add_menu_page(
            'Health Assessments',
            'Health Leads',
            'manage_options',
            'health-assessments',
            [ $this, 'render_admin_page' ],
            'dashicons-heart',
            30
        );

        // Submenu for Leads
        add_submenu_page(
            'health-assessments',
            'Health Leads',
            'Leads',
            'manage_options',
            'health-assessments',
            [ $this, 'render_admin_page' ]
        );

        // Submenu for Settings
        add_submenu_page(
            'health-assessments',
            'Health Score Settings',
            'Settings',
            'manage_options',
            'health-settings',
            [ $this, 'render_settings_page' ]
        );
    }

    public function register_settings() {
        register_setting( 'health_score_options_group', 'health_score_booking_url', [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ] );

        register_setting( 'health_score_options_group', 'health_score_ghl_webhook_url', [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ] );

        register_setting( 'health_score_options_group', 'health_score_scoring_config', [
            'type' => 'string',
            'sanitize_callback' => function( $val ) {
                $decoded = json_decode( $val, true );
                if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $decoded ) ) {
                    add_settings_error( 'health_score_options_group', 'invalid_json', 'Invalid JSON format. Changes were not saved.', 'error' );
                    return get_option( 'health_score_scoring_config' );
                }
                
                // Enforce Keys Match Original File
                $config_path = plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json';
                $default_config = file_exists( $config_path ) ? json_decode( file_get_contents( $config_path ), true ) : [];
                
                if ( ! empty( $default_config ) ) {
                    // Check top-level keys
                    $diff_top = array_diff_key( $default_config, $decoded );
                    if ( ! empty( $diff_top ) ) {
                        add_settings_error( 'health_score_options_group', 'invalid_keys', 'Error: You cannot add, remove, or modify the structure (keys) of the JSON. You may only change the number values.', 'error' );
                        return get_option( 'health_score_scoring_config' );
                    }
                    
                    // Check sub-keys for questions
                    foreach ( $default_config as $k => $v ) {
                        if ( is_array( $v ) ) {
                            if ( ! isset( $decoded[$k] ) || ! is_array( $decoded[$k] ) ) {
                                add_settings_error( 'health_score_options_group', 'invalid_keys', "Error: Missing configuration for $k.", 'error' );
                                return get_option( 'health_score_scoring_config' );
                            }
                            $diff_sub = array_diff_key( $v, $decoded[$k] );
                            if ( ! empty( $diff_sub ) ) {
                                add_settings_error( 'health_score_options_group', 'invalid_keys', "Error: You modified the answers/keys in $k. Please only edit the point values.", 'error' );
                                return get_option( 'health_score_scoring_config' );
                            }
                        }
                    }
                }
                
                return $val;
            },
            'default' => file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) ? file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) : ''
        ] );

        register_setting( 'health_score_options_group', 'health_score_btn_bg_top', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#40BAD5'
        ] );

        register_setting( 'health_score_options_group', 'health_score_btn_bg_bottom', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#07689F'
        ] );

        register_setting( 'health_score_options_group', 'health_score_btn_hover_top', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#99ca1d'
        ] );

        register_setting( 'health_score_options_group', 'health_score_btn_hover_bottom', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => '#799928'
        ] );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Health Score Settings</h1>
            <?php settings_errors(); ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'health_score_options_group' ); ?>
                <?php do_settings_sections( 'health_score_options_group' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Primary Brand Color</th>
                        <td>
                            <label style="margin-right: 15px;">
                                Top Shade: <input type="color" name="health_score_btn_bg_top" value="<?php echo esc_attr( get_option('health_score_btn_bg_top', '#40BAD5') ); ?>" />
                            </label>
                            <label>
                                Bottom Shade: <input type="color" name="health_score_btn_bg_bottom" value="<?php echo esc_attr( get_option('health_score_btn_bg_bottom', '#07689F') ); ?>" />
                            </label>
                            <p class="description">Primary brand color gradient applied to primary buttons and UI highlights in normal state.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Button Hover Color</th>
                        <td>
                            <label style="margin-right: 15px;">
                                Top Shade: <input type="color" name="health_score_btn_hover_top" value="<?php echo esc_attr( get_option('health_score_btn_hover_top', '#99ca1d') ); ?>" />
                            </label>
                            <label>
                                Bottom Shade: <input type="color" name="health_score_btn_hover_bottom" value="<?php echo esc_attr( get_option('health_score_btn_hover_bottom', '#799928') ); ?>" />
                            </label>
                            <p class="description">Gradient applied to primary buttons when hovered.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Booking Redirect URL</th>
                        <td>
                            <input type="url" name="health_score_booking_url" value="<?php echo esc_attr( get_option('health_score_booking_url', '') ); ?>" style="width: 100%; max-width: 400px;" />
                            <p class="description">Users will be redirected to this URL after clicking on the "Book Your Complimentary Consultation" button.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">GoHighLevel Webhook URL</th>
                        <td>
                            <input type="url" name="health_score_ghl_webhook_url" value="<?php echo esc_attr( get_option('health_score_ghl_webhook_url', '') ); ?>" style="width: 100%; max-width: 400px;" placeholder="https://services.leadconnectorhq.com/hooks/..." />
                            <p class="description">When a lead submits the quiz, their data will be instantly pushed to this Webhook URL.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Scoring Configuration (JSON)</th>
                        <td>
                            <?php $default_json = file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) ? file_get_contents( plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json' ) : ''; ?>
                            <textarea name="health_score_scoring_config" rows="15" style="width: 100%; max-width: 800px; font-family: monospace;"><?php echo esc_textarea( get_option('health_score_scoring_config', $default_json) ); ?></textarea>
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
        $table = new Assessments_List_Table();
        $table->prepare_items();
        ?>
        <style>
            .wp-list-table th { white-space: nowrap; }
            .wp-list-table th.sortable a, .wp-list-table th.sorted a { display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
            .column-health_score { width: 130px; white-space: nowrap; }
            .column-readiness_score { width: 110px; white-space: nowrap; }
            .column-actions { width: 60px; text-align: center; }
        </style>
        <div class="wrap">
            <h1 class="wp-heading-inline">Health Assessment Leads</h1>
            <p>View all completed health assessments and their calculated scores.</p>
            <form method="get">
                <input type="hidden" name="page" value="health-assessments" />
                <?php $table->search_box( 'Search', 'search_id' ); ?>
                <?php $table->display(); ?>
            </form>
        </div>

        <!-- Patient Profile Modal -->
        <div id="health-profile-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5);">
            <div class="pop-view" style="background-color:#fff;margin:8% auto;padding:20px;border-radius:8px;width:600px;max-width:90%;font-size: 15px;box-shadow:0 5px 15px rgba(0,0,0,0.3);line-height: 22px;">
                <span id="health-modal-close" style="color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
                <h2 id="modal-name" style="margin-top:0;color: #799928;font-size: 20px;">Patient Profile</h2>
                <hr>
                <div style="display:flex; gap:20px; margin-bottom:20px;">
                    <div style="flex:1;">
                        <strong>Health Score:</strong> <span style="color:#096ba1;" id="modal-health-score"></span><br>
                        <strong>Readiness:</strong> <span style="color:#096ba1;" id="modal-readiness"></span>/10<br>
                        <strong>Phone:</strong> <span style="color:#096ba1;" id="modal-phone"></span><br>
                        <strong>Email:</strong> <span style="color:#096ba1;" id="modal-email"></span>
                    </div>
                </div>
                <h3 style="margin-bottom: 0;font-size: 18px;">Patient's Stated Goals</h3>
                <ul id="modal-goals" style="list-style-type:disc;padding-left:20px;margin-top: 5px;"></ul>
                
                <h3 style="margin-bottom: 0;font-size: 18px;">Potential Discussion Topics</h3>
                <ul id="modal-topics" style="list-style-type:disc;padding-left:20px;margin-top: 5px;"></ul>

                <h3 style="margin-bottom: 0;font-size: 18px;">Readiness Context</h3>
                <p id="modal-context" style="margin-top: 5px;"></p>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('health-profile-modal');
            var closeBtn = document.getElementById('health-modal-close');
            
            if (closeBtn) {
                closeBtn.onclick = function() {
                    if (modal) modal.style.display = 'none';
                }
            }
            
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            var viewButtons = document.querySelectorAll('.health-view-lead');
            viewButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var lead = JSON.parse(this.getAttribute('data-lead'));
                    
                    document.getElementById('modal-name').innerText = lead.first_name + "'s Profile";
                    document.getElementById('modal-health-score').innerText = lead.health_score + '/100';
                    document.getElementById('modal-readiness').innerText = lead.readiness_score;
                    document.getElementById('modal-phone').innerText = lead.phone || 'N/A';
                    document.getElementById('modal-email').innerText = lead.email || 'N/A';

                    var goalsList = document.getElementById('modal-goals');
                    goalsList.innerHTML = '';
                    if (lead.primary_goal) {
                        goalsList.innerHTML += '<li>' + lead.primary_goal + '</li>';
                    }
                    var concerns = [];
                    try {
                        concerns = JSON.parse(lead.selected_health_concerns);
                    } catch(e) {}
                    if (concerns.length > 0) {
                        concerns.forEach(function(item) {
                            goalsList.innerHTML += '<li>Address concern: ' + item + '</li>';
                        });
                    }

                    var topicsList = document.getElementById('modal-topics');
                    topicsList.innerHTML = '';
                    var mainConcerns = [];
                    try {
                        mainConcerns = JSON.parse(lead.main_concerns);
                    } catch(e) {}
                    if (mainConcerns.length > 0) {
                        mainConcerns.forEach(function(item) {
                            topicsList.innerHTML += '<li>Discuss opportunity: ' + item + '</li>';
                        });
                    }

                    var readiness = parseInt(lead.readiness_score);
                    var context = '';
                    if (readiness >= 8) {
                        context = 'High readiness (' + readiness + '/10). Extremely motivated to make changes. Focus on clear next steps and quick wins.';
                    } else if (readiness >= 5) {
                        context = 'Moderate readiness (' + readiness + '/10). Open to advice but may need help with sustainable habits and building confidence.';
                    } else {
                        context = 'Low readiness (' + readiness + '/10). Might be hesitant or overwhelmed. Needs a gentle, supportive approach.';
                    }
                    document.getElementById('modal-context').innerText = context;
                    if (modal) modal.style.display = 'block';
                });
            });
        });
        </script>
        <?php
    }
}
