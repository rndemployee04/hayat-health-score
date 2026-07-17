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

    public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }
        $input_id = $input_id . '-search-input';
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['page'] ) ) {
            echo '<input type="hidden" name="page" value="' . esc_attr( $_REQUEST['page'] ) . '" />';
        }
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" placeholder="Search Name or Email" />
            <?php submit_button( $text, '', '', false, [ 'id' => 'search-submit' ] ); ?>
        </p>
        <?php
    }

    public function get_columns() {
        return [
            'id'              => 'ID',
            'first_name'      => 'Name',
            'email'           => 'Email',
            'health_score'    => 'Health Score',
            'readiness_score' => 'Readiness',
            'utm_source'      => 'Source',
            'priority'        => 'Priority',
            'status'          => 'Status',
            'created_at'      => 'Date',
            'actions'         => 'View'
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
            case 'priority':
                $readiness = intval( $item['readiness_score'] );
                $risk_score = 100 - intval( $item['health_score'] );
                if ( $readiness >= 8 && $risk_score >= 40 ) {
                    return '<span style="color: #d63638; font-weight: 500;">High</span>';
                } elseif ( $readiness >= 5 && $risk_score >= 20 ) {
                    return '<span style="color: #dba617; font-weight: 500;">Medium</span>';
                } else {
                    return '<span style="color: #00a32a; font-weight: 500;">Low</span>';
                }
            case 'status':
                $current_status = ! empty( $item['status'] ) ? esc_html( $item['status'] ) : 'Needs Follow-up';
                $colors = [
                    'Needs Follow-up' => '#d63638',
                    'Contacted'       => '#dba617',
                    'Booked'          => '#00a32a',
                    'Not Interested'  => '#646970'
                ];
                $text_color = isset($colors[$current_status]) ? $colors[$current_status] : '#3c434a';
                $html = '<div style="position: relative; display: inline-block;">';
                $html .= '<select class="hayat-status-dropdown" data-id="' . esc_attr($item['id']) . '" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; color: ' . $text_color . '; font-weight: 600; background: transparent; border: 1px solid transparent; padding: 0 24px 0 0; box-shadow: none; cursor: pointer; height: auto; min-height: unset; line-height: 1;">';
                foreach ( $colors as $status_option => $style ) {
                    $selected = selected( $current_status, $status_option, false );
                    $html .= '<option value="' . esc_attr( $status_option ) . '" ' . $selected . ' style="color: #2c3338;">' . esc_html( $status_option ) . '</option>';
                }
                $html .= '</select>';
                $html .= '<span class="dashicons dashicons-edit" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); font-size: 14px; width: 14px; height: 14px; color: #a7aaad; pointer-events: none;"></span>';
                $html .= '</div>';
                return $html;
            case 'actions':
                $lead_data = esc_attr( wp_json_encode( $item ) );
                return '<button type="button" class="button hayat-view-lead" data-lead="' . $lead_data . '"><span class="dashicons dashicons-visibility"></span></button>';
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
            $current_status = isset( $_GET['filter_status'] ) ? sanitize_text_field( $_GET['filter_status'] ) : '';
            $current_priority = isset( $_GET['filter_priority'] ) ? sanitize_text_field( $_GET['filter_priority'] ) : '';
            $current_readiness = isset( $_GET['filter_readiness'] ) ? sanitize_text_field( $_GET['filter_readiness'] ) : '';
            $current_health = isset( $_GET['filter_health'] ) ? sanitize_text_field( $_GET['filter_health'] ) : '';
            ?>
            <div class="alignleft actions">
                <select name="filter_source">
                    <option value="">All Sources</option>
                    <option value="direct" <?php selected( $current_source, 'direct' ); ?>>Direct</option>
                    <?php foreach ( $sources as $source ) : ?>
                        <option value="<?php echo esc_attr( $source ); ?>" <?php selected( $current_source, $source ); ?>><?php echo esc_html( $source ); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="filter_status">
                    <option value="">All Statuses</option>
                    <option value="Needs Follow-up" <?php selected( $current_status, 'Needs Follow-up' ); ?>>Needs Follow-up</option>
                    <option value="Contacted" <?php selected( $current_status, 'Contacted' ); ?>>Contacted</option>
                    <option value="Booked" <?php selected( $current_status, 'Booked' ); ?>>Booked</option>
                    <option value="Not Interested" <?php selected( $current_status, 'Not Interested' ); ?>>Not Interested</option>
                </select>
                <select name="filter_priority">
                    <option value="">All Priorities</option>
                    <option value="High" <?php selected( $current_priority, 'High' ); ?>>High</option>
                    <option value="Medium" <?php selected( $current_priority, 'Medium' ); ?>>Medium</option>
                    <option value="Low" <?php selected( $current_priority, 'Low' ); ?>>Low</option>
                </select>
                <select name="filter_readiness">
                    <option value="">All Readiness</option>
                    <?php for($i=10; $i>=1; $i--): ?>
                        <option value="<?php echo $i; ?>" <?php selected( $current_readiness, (string)$i ); ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <select name="filter_health">
                    <option value="">All Health Scores</option>
                    <option value="85-100" <?php selected( $current_health, '85-100' ); ?>>Excellent (85-100)</option>
                    <option value="70-84" <?php selected( $current_health, '70-84' ); ?>>Good (70-84)</option>
                    <option value="55-69" <?php selected( $current_health, '55-69' ); ?>>Fair (55-69)</option>
                    <option value="40-54" <?php selected( $current_health, '40-54' ); ?>>Needs Attention (40-54)</option>
                    <option value="0-39" <?php selected( $current_health, '0-39' ); ?>>Significant Opportunity (< 40)</option>
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
        $filter_status = isset( $_REQUEST['filter_status'] ) ? sanitize_text_field( $_REQUEST['filter_status'] ) : '';
        $filter_priority = isset( $_REQUEST['filter_priority'] ) ? sanitize_text_field( $_REQUEST['filter_priority'] ) : '';
        $filter_readiness = isset( $_REQUEST['filter_readiness'] ) ? sanitize_text_field( $_REQUEST['filter_readiness'] ) : '';
        $filter_health = isset( $_REQUEST['filter_health'] ) ? sanitize_text_field( $_REQUEST['filter_health'] ) : '';

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
        if ( ! empty( $filter_status ) ) {
            $where .= $wpdb->prepare( " AND status = %s", $filter_status );
        }
        if ( ! empty( $filter_readiness ) ) {
            $where .= $wpdb->prepare( " AND readiness_score = %d", intval($filter_readiness) );
        }
        if ( ! empty( $filter_health ) ) {
            $parts = explode('-', $filter_health);
            if ( count($parts) === 2 ) {
                $where .= $wpdb->prepare( " AND health_score >= %d AND health_score <= %d", intval($parts[0]), intval($parts[1]) );
            }
        }
        if ( ! empty( $filter_priority ) ) {
            // Priority Logic:
            // High = readiness >= 8 AND risk_score >= 40 (health <= 60)
            // Medium = readiness >= 5 AND risk_score >= 20 (health <= 80) [and not high]
            // Low = everything else
            if ( $filter_priority === 'High' ) {
                $where .= " AND readiness_score >= 8 AND health_score <= 60";
            } elseif ( $filter_priority === 'Medium' ) {
                $where .= " AND ( readiness_score >= 5 AND health_score <= 80 ) AND NOT ( readiness_score >= 8 AND health_score <= 60 )";
            } elseif ( $filter_priority === 'Low' ) {
                $where .= " AND NOT ( readiness_score >= 5 AND health_score <= 80 )";
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
        add_action( 'admin_init', [ $this, 'check_db_schema' ] );
        add_action( 'wp_ajax_hayat_update_status', [ $this, 'ajax_update_status' ] );
    }

    public function check_db_schema() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hayat_assessments';
        $column = $wpdb->get_results( "SHOW COLUMNS FROM `$table_name` LIKE 'status'" );
        if ( empty( $column ) ) {
            $wpdb->query( "ALTER TABLE `$table_name` ADD `status` VARCHAR(50) DEFAULT 'Needs Follow-up' NULL" );
            $wpdb->query( "UPDATE `$table_name` SET `status` = 'Needs Follow-up' WHERE `status` IS NULL" );
        }
    }

    public function ajax_update_status() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }
        global $wpdb;
        $id = intval( $_POST['id'] );
        $status = sanitize_text_field( $_POST['status'] );
        $wpdb->update( $wpdb->prefix . 'hayat_assessments', [ 'status' => $status ], [ 'id' => $id ] );
        wp_send_json_success();
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
        <style>
            .column-status { width: 170px; white-space: nowrap; }
            .column-actions { width: 60px; text-align: center; }
            .column-readiness_score { width: 100px; }
        </style>
        <div class="wrap">
            <h1 class="wp-heading-inline">Hayat Tayyiba Health Leads</h1>
            <p>View all completed health assessments and their calculated scores.</p>
            <form method="get">
                <input type="hidden" name="page" value="hayat-health-assessments" />
                <?php $table->search_box( 'Search', 'search_id' ); ?>
                <?php $table->display(); ?>
            </form>
        </div>

        <!-- Patient Profile Modal -->
        <div id="hayat-profile-modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.5);">
            <div style="background-color:#fff; margin:5% auto; padding:20px; border-radius:8px; width:600px; max-width:90%; box-shadow:0 5px 15px rgba(0,0,0,0.3);">
                <span id="hayat-modal-close" style="color:#aaa; float:right; font-size:28px; font-weight:bold; cursor:pointer;">&times;</span>
                <h2 id="modal-name" style="margin-top:0;">Patient Profile</h2>
                <hr>
                <div style="display:flex; gap:20px; margin-bottom:20px;">
                    <div style="flex:1;">
                        <strong>Health Score:</strong> <span id="modal-health-score"></span><br>
                        <strong>Readiness:</strong> <span id="modal-readiness"></span>/10<br>
                        <strong>Phone:</strong> <span id="modal-phone"></span><br>
                        <strong>Email:</strong> <span id="modal-email"></span>
                    </div>
                </div>
                <h3>Patient's Stated Goals</h3>
                <ul id="modal-goals" style="list-style-type:disc; padding-left:20px;"></ul>
                
                <h3>Potential Discussion Topics</h3>
                <ul id="modal-topics" style="list-style-type:disc; padding-left:20px;"></ul>

                <h3>Readiness Context</h3>
                <p id="modal-context"></p>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('hayat-profile-modal');
            var closeBtn = document.getElementById('hayat-modal-close');
            
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            }
            
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            var viewBtns = document.querySelectorAll('.hayat-view-lead');
            viewBtns.forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var lead = JSON.parse(this.getAttribute('data-lead'));
                    
                    document.getElementById('modal-name').innerText = lead.first_name + "'s Profile";
                    document.getElementById('modal-health-score').innerText = lead.health_score;
                    document.getElementById('modal-readiness').innerText = lead.readiness_score;
                    document.getElementById('modal-phone').innerText = lead.phone || 'N/A';
                    document.getElementById('modal-email').innerText = lead.email;
                    
                    var answers = JSON.parse(lead.raw_answers);
                    var opps = JSON.parse(lead.top_opportunities);
                    
                    var goalsHtml = '';
                    if (answers.q2) {
                        goalsHtml += '<li>Wants to: <strong>' + answers.q2 + '</strong></li>';
                    }
                    if (answers.q1 && answers.q1.length > 0) {
                        goalsHtml += '<li>Struggling with: ' + answers.q1.join(', ') + '</li>';
                    }
                    document.getElementById('modal-goals').innerHTML = goalsHtml || '<li>No goals stated</li>';
                    
                    var topicsHtml = '';
                    if (opps && opps.length > 0) {
                        opps.forEach(function(opp) {
                            topicsHtml += '<li>' + opp + '</li>';
                        });
                    }
                    document.getElementById('modal-topics').innerHTML = topicsHtml || '<li>General optimization</li>';
                    
                    var context = '';
                    var readiness = parseInt(lead.readiness_score);
                    if (readiness >= 8) {
                        context = 'High readiness (' + readiness + '/10). Highly motivated to make lifestyle changes. Ready for immediate structured plan.';
                    } else if (readiness >= 5) {
                        context = 'Medium readiness (' + readiness + '/10). Interested but may need some reassurance or clear explanation of the process.';
                    } else {
                        context = 'Low readiness (' + readiness + '/10). Might be hesitant or overwhelmed. Needs a gentle, supportive approach.';
                    }
                    document.getElementById('modal-context').innerText = context;
                    modal.style.display = 'block';
                });
            });
            var statusDropdowns = document.querySelectorAll('.hayat-status-dropdown');
            statusDropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('change', function() {
                    var select = this;
                    var id = select.getAttribute('data-id');
                    var status = select.value;
                    
                    // Update styling instantly
                    var textColors = {
                        'Needs Follow-up': '#d63638',
                        'Contacted': '#dba617',
                        'Booked': '#00a32a',
                        'Not Interested': '#646970'
                    };
                    select.style.color = textColors[status];

                    // AJAX call
                    var data = new FormData();
                    data.append('action', 'hayat_update_status');
                    data.append('id', id);
                    data.append('status', status);

                    fetch(ajaxurl, {
                        method: 'POST',
                        body: data
                    });
                });
            });
        });
        </script>
        <?php
    }
}
