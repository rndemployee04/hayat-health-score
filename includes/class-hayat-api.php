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
                'phone'             => isset( $params['phone'] ) ? sanitize_text_field( $params['phone'] ) : '',
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
                '%s', // phone
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
            'Energy' => 0,
            'Sleep' => 0,
            'Blood Sugar' => 0,
            'Blood Pressure' => 0,
            'Stress Management' => 0,
        ];

        // Load Scoring Config from Admin Settings
        $config_json = get_option( 'hayat_scoring_config', '' );
        if ( empty( $config_json ) ) {
            $config_path = plugin_dir_path( dirname( __FILE__ ) ) . 'scoring-config.json';
            $config_json = file_exists( $config_path ) ? file_get_contents( $config_path ) : '{}';
        }
        $config = json_decode( $config_json, true );
        if ( ! is_array( $config ) ) {
            $config = [];
        }

        // Q1: Symptoms
        if ( isset( $answers['q1'] ) && is_array( $answers['q1'] ) && isset( $config['q1'] ) ) {
            $q1_score = 0;
            foreach ( $answers['q1'] as $symptom ) {
                if ( isset( $config['q1'][$symptom] ) ) {
                    $q1_score += $config['q1'][$symptom];
                }
                
                // Track Opportunities based on symptoms
                if ( stripos( $symptom, 'Weight' ) !== false ) $opportunities['Weight Management'] += 5;
                if ( stripos( $symptom, 'energy' ) !== false || stripos( $symptom, 'fatigue' ) !== false ) $opportunities['Energy'] += 5;
                if ( stripos( $symptom, 'sleep' ) !== false ) $opportunities['Sleep'] += 5;
                if ( stripos( $symptom, 'Blood sugar' ) !== false ) $opportunities['Blood Sugar'] += 5;
                if ( stripos( $symptom, 'blood pressure' ) !== false || stripos( $symptom, 'cholesterol' ) !== false ) $opportunities['Blood Pressure'] += 5;
                if ( stripos( $symptom, 'stress' ) !== false || stripos( $symptom, 'Brain fog' ) !== false ) $opportunities['Stress Management'] += 5;
            }
            $risk_score += min( $config['q1']['max_points'], $q1_score );
        }

        // Q3: Duration
        if ( isset( $answers['q3'] ) && isset( $config['q3'][$answers['q3']] ) ) {
            $risk_score += $config['q3'][$answers['q3']];
        }

        // Q4: What have you tried
        if ( isset( $answers['q4'] ) && is_array( $answers['q4'] ) && isset( $config['q4'] ) ) {
            $q4_score = 0;
            foreach ( $answers['q4'] as $tried ) {
                if ( isset( $config['q4'][$tried] ) ) {
                    $q4_score += $config['q4'][$tried];
                }
            }
            $risk_score += min( $config['q4']['max_points'], $q4_score );
        }

        // Q5: Energy Pattern
        if ( isset( $answers['q5'] ) && isset( $config['q5'][$answers['q5']] ) ) {
            $val = $config['q5'][$answers['q5']];
            $risk_score += $val;
            if ( $val > 0 ) $opportunities['Energy'] += $val;
        }

        // Q6: Cravings
        if ( isset( $answers['q6'] ) && isset( $config['q6'][$answers['q6']] ) ) {
            $val = $config['q6'][$answers['q6']];
            $risk_score += $val;
            if ( $val > 0 ) $opportunities['Blood Sugar'] += $val;
        }

        // Q7: Conditions
        if ( isset( $answers['q7'] ) && is_array( $answers['q7'] ) && isset( $config['q7'] ) ) {
            $q7_score = 0;
            foreach ( $answers['q7'] as $condition ) {
                if ( isset( $config['q7'][$condition] ) ) {
                    $q7_score += $config['q7'][$condition];
                }
                
                if ( in_array( $condition, ['Prediabetes', 'Type 2 Diabetes', 'Fatty Liver'] ) ) $opportunities['Blood Sugar'] += 8;
                if ( in_array( $condition, ['High Blood Pressure', 'High Cholesterol'] ) ) $opportunities['Blood Pressure'] += 8;
                if ( $condition === 'Sleep Apnea' ) $opportunities['Sleep'] += 5;
            }
            $risk_score += min( $config['q7']['max_points'], $q7_score );
        }

        // Q8: Biggest Concern
        if ( isset( $answers['q8'] ) && isset( $config['q8'][$answers['q8']] ) ) {
            $val = $config['q8'][$answers['q8']];
            $risk_score += $val;
            
            if ( stripos( $answers['q8'], 'weight' ) !== false ) $opportunities['Weight Management'] += 5;
            if ( stripos( $answers['q8'], 'energy' ) !== false ) $opportunities['Energy'] += 5;
        }

        // Cap risk score
        $max_risk = isset( $config['max_risk_score'] ) ? $config['max_risk_score'] : 100;
        $risk_score = min( $max_risk, $risk_score );
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
