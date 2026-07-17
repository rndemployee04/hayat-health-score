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
            case 'utm_source':
                return esc_html( $item[ $column_name ] );
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

        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );
        
        $this->items = $wpdb->get_results( 
            $wpdb->prepare( 
                "SELECT * FROM $table_name ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d", 
                $per_page, 
                $offset 
            ), 
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
    }

    public function add_admin_menu() {
        add_menu_page(
            'Health Assessments',
            'Health Leads',
            'manage_options',
            'hayat-health-assessments',
            [ $this, 'render_admin_page' ],
            'dashicons-heart',
            30
        );
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
                <?php $table->display(); ?>
            </form>
        </div>
        <?php
    }
}
