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
        $utm_source = isset( $params['utm_source'] ) ? sanitize_text_field( $params['utm_source'] ) : '';

        $user_id = null;

        // User Creation / Lookup
        if ( ! empty( $email ) ) {
            $user = get_user_by( 'email', $email );
            if ( $user ) {
                $user_id = $user->ID;
            } else {
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

        // Calculate Scores
        $scores = $this->calculate_scores( $answers );

        // Insert assessment record
        $inserted = $wpdb->insert(
            $table_name,
            [
                'user_id'           => $user_id,
                'first_name'        => $first_name,
                'email'             => $email,
                'risk_score'        => $scores['risk_score'],
                'health_score'      => $scores['health_score'],
                'readiness_score'   => $scores['readiness_score'],
                'top_opportunities' => wp_json_encode( $scores['top_opportunities'] ),
                'raw_answers'       => wp_json_encode( $answers ),
                'utm_source'        => $utm_source,
                'created_at'        => current_time( 'mysql' )
            ],
            [
                '%d', // user_id
                '%s', // first_name
                '%s', // email
                '%d', // risk_score
                '%d', // health_score
                '%d', // readiness_score
                '%s', // top_opportunities
                '%s', // raw_answers
                '%s', // utm_source
                '%s'  // created_at
            ]
        );

        if ( $inserted ) {
            $assessment_id = $wpdb->insert_id;
            
            // Schedule the background event to generate the PDF and send the email
            wp_schedule_single_event( time(), 'hayat_process_assessment', [ $assessment_id ] );

            return rest_ensure_response( [
                'success'  => true,
                'message'  => 'Assessment saved successfully',
                'id'       => $assessment_id,
                'user_id'  => $user_id,
                'scores'   => $scores
            ] );
        }

        return new WP_Error( 'db_error', 'Could not save assessment', [ 'status' => 500 ] );
    }

    private function calculate_scores( $answers ) {
        $risk_score = 0;
        $opportunities = [
            'Weight Management' => 0,
            'Energy & Sleep' => 0,
            'Metabolic Health (Blood Sugar)' => 0,
            'Cardiovascular Health' => 0,
        ];

        // Q1: Symptoms
        if ( isset( $answers['q1'] ) && is_array( $answers['q1'] ) ) {
            foreach ( $answers['q1'] as $symptom ) {
                if ( $symptom !== 'None' && $symptom !== "I don't feel like myself anymore" ) {
                    $risk_score += 4;
                }
                if ( strpos( $symptom, 'Weight' ) !== false ) $opportunities['Weight Management'] += 5;
                if ( strpos( $symptom, 'energy' ) !== false || strpos( $symptom, 'sleep' ) !== false ) $opportunities['Energy & Sleep'] += 5;
                if ( strpos( $symptom, 'Blood sugar' ) !== false ) $opportunities['Metabolic Health (Blood Sugar)'] += 5;
                if ( strpos( $symptom, 'blood pressure' ) !== false || strpos( $symptom, 'cholesterol' ) !== false ) $opportunities['Cardiovascular Health'] += 5;
            }
        }

        // Q3: Duration
        if ( isset( $answers['q3'] ) ) {
            if ( $answers['q3'] === '1–3 years' ) $risk_score += 5;
            if ( $answers['q3'] === 'More than 3 years' ) $risk_score += 10;
        }

        // Q5: Energy Pattern
        if ( isset( $answers['q5'] ) ) {
            if ( $answers['q5'] === 'I often crash in the afternoon.' ) { $risk_score += 3; $opportunities['Energy & Sleep'] += 3; }
            if ( $answers['q5'] === 'I rely on caffeine most days.' ) { $risk_score += 5; $opportunities['Energy & Sleep'] += 4; }
            if ( $answers['q5'] === "I'm tired most of the day." ) { $risk_score += 8; $opportunities['Energy & Sleep'] += 5; }
        }

        // Q6: Cravings
        if ( isset( $answers['q6'] ) ) {
            if ( $answers['q6'] === 'Daily' ) { $risk_score += 5; $opportunities['Metabolic Health (Blood Sugar)'] += 3; }
            if ( $answers['q6'] === 'Multiple times per day' ) { $risk_score += 10; $opportunities['Metabolic Health (Blood Sugar)'] += 5; }
        }

        // Q7: Conditions
        if ( isset( $answers['q7'] ) && is_array( $answers['q7'] ) ) {
            foreach ( $answers['q7'] as $condition ) {
                if ( $condition !== 'None' ) {
                    $risk_score += 8;
                }
                if ( in_array( $condition, ['Prediabetes', 'Type 2 Diabetes', 'Fatty Liver'] ) ) $opportunities['Metabolic Health (Blood Sugar)'] += 8;
                if ( in_array( $condition, ['High Blood Pressure', 'High Cholesterol'] ) ) $opportunities['Cardiovascular Health'] += 8;
                if ( $condition === 'Sleep Apnea' ) $opportunities['Energy & Sleep'] += 5;
            }
        }

        // Cap risk score at 100
        $risk_score = min( 100, $risk_score );
        $health_score = 100 - $risk_score;

        // Readiness Score (Q9)
        $readiness_score = isset( $answers['q9'] ) ? (int) $answers['q9'] : 5;

        // Determine Top 3 Opportunities
        arsort( $opportunities );
        $top_opportunities = array_slice( array_keys( array_filter( $opportunities, function($val) { return $val > 0; } ) ), 0, 3 );
        
        // Fallback if none exist
        if ( empty( $top_opportunities ) ) {
            $top_opportunities = ['General Wellness', 'Preventative Care', 'Nutrition Optimization'];
        }

        return [
            'risk_score'        => $risk_score,
            'health_score'      => $health_score,
            'readiness_score'   => $readiness_score,
            'top_opportunities' => $top_opportunities
        ];
    }
}
