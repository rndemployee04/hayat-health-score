<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Hayat_Health_Score_API {
    
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes() {
        register_rest_route( 'hayat/v1', '/submit', [
            'methods'  => 'POST',
            'callback' => [ $this, 'handle_submission' ],
            'permission_callback' => '__return_true', // Public endpoint
        ] );
    }

    public function handle_submission( $request ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hayat_assessments';
        
        $params = $request->get_json_params();
        
        $first_name = isset( $params['first_name'] ) ? sanitize_text_field( $params['first_name'] ) : '';
        $email      = isset( $params['email'] ) ? sanitize_email( $params['email'] ) : '';
        $answers    = isset( $params['answers'] ) ? $params['answers'] : [];

        $user_id = null;

        // User Creation / Lookup
        if ( ! empty( $email ) ) {
            $user = get_user_by( 'email', $email );
            if ( $user ) {
                $user_id = $user->ID;
            } else {
                // Create new user
                $password = wp_generate_password();
                $new_user_id = wp_insert_user([
                    'user_login' => $email,
                    'user_email' => $email,
                    'user_pass'  => $password,
                    'first_name' => $first_name,
                    'role'       => 'subscriber'
                ]);

                if ( ! is_wp_error( $new_user_id ) ) {
                    $user_id = $new_user_id;
                }
            }
        }

        // Insert assessment record
        $inserted = $wpdb->insert(
            $table_name,
            [
                'user_id'     => $user_id,
                'first_name'  => $first_name,
                'email'       => $email,
                'raw_answers' => wp_json_encode( $answers ),
                'created_at'  => current_time( 'mysql' )
            ],
            [
                '%d', // user_id
                '%s', // first_name
                '%s', // email
                '%s', // raw_answers
                '%s'  // created_at
            ]
        );

        if ( $inserted ) {
            return rest_ensure_response( [
                'success' => true,
                'message' => 'Assessment saved successfully',
                'id'      => $wpdb->insert_id,
                'user_id' => $user_id
            ] );
        }

        return new WP_Error( 'db_error', 'Could not save assessment', [ 'status' => 500 ] );
    }
}
