<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Health_Score_API {
    
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    public function register_routes() {
        register_rest_route( 'health-score/v1', '/submit', [
            'methods'  => 'POST',
            'callback' => [ $this, 'handle_submission' ],
            'permission_callback' => '__return_true', // Public endpoint
        ] );
    }

    public function handle_submission( $request ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';
        
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
                'user_id'                   => $user_id,
                'first_name'                => $first_name,
                'email'                     => $email,
                'phone'                     => isset( $params['phone'] ) ? sanitize_text_field( $params['phone'] ) : '',
                'risk_score'                => isset( $scores['risk_score'] ) ? $scores['risk_score'] : 0,
                'health_score'              => isset( $scores['health_score'] ) ? $scores['health_score'] : 100,
                'score_category'            => isset( $scores['score_category'] ) ? $scores['score_category'] : '',
                'primary_goal'              => isset( $answers['q2'] ) ? sanitize_text_field( $answers['q2'] ) : '',
                'selected_health_concerns'  => isset( $answers['q1'] ) ? wp_json_encode( $answers['q1'] ) : '[]',
                'main_concerns'             => isset( $scores['main_concerns'] ) ? wp_json_encode( $scores['main_concerns'] ) : '[]',
                'duration'                  => isset( $answers['q3'] ) ? sanitize_text_field( $answers['q3'] ) : '',
                'energy_pattern'            => isset( $answers['q4'] ) ? sanitize_text_field( $answers['q4'] ) : '',
                'craving_frequency'         => isset( $answers['q5'] ) ? sanitize_text_field( $answers['q5'] ) : '',
                'diagnosed_conditions'      => isset( $answers['q6'] ) ? wp_json_encode( $answers['q6'] ) : '[]',
                'previous_attempts'         => isset( $answers['q7'] ) ? wp_json_encode( $answers['q7'] ) : '[]',
                'biggest_concern'           => isset( $answers['q8'] ) ? sanitize_text_field( $answers['q8'] ) : '',
                'readiness_score'           => isset( $answers['q9'] ) ? (int) $answers['q9'] : 5,
                'support_preference'        => isset( $answers['q10'] ) ? sanitize_text_field( $answers['q10'] ) : '',
                'current_mindset'           => isset( $answers['q11'] ) ? sanitize_text_field( $answers['q11'] ) : '',
                'pdf_sent'                  => isset( $scores['pdf_file'] ) ? $scores['pdf_file'] : '',
                'top_opportunities'         => isset( $scores['main_concerns'] ) ? wp_json_encode( $scores['main_concerns'] ) : '[]',
                'raw_answers'               => wp_json_encode( $answers ),
                'utm_source'                => $utm_source,
                'created_at'                => current_time( 'mysql' )
            ],
            [
                '%d', // user_id
                '%s', // first_name
                '%s', // email
                '%s', // phone
                '%d', // risk_score
                '%d', // health_score
                '%s', // score_category
                '%s', // primary_goal
                '%s', // selected_health_concerns
                '%s', // main_concerns
                '%s', // duration
                '%s', // energy_pattern
                '%s', // craving_frequency
                '%s', // diagnosed_conditions
                '%s', // previous_attempts
                '%s', // biggest_concern
                '%d', // readiness_score
                '%s', // support_preference
                '%s', // current_mindset
                '%s', // pdf_sent
                '%s', // top_opportunities
                '%s', // raw_answers
                '%s', // utm_source
                '%s'  // created_at
            ]
        );

        if ( $inserted ) {
            $assessment_id = $wpdb->insert_id;
            
            // Schedule the background event to generate the PDF and send the email
            wp_schedule_single_event( time(), 'health_score_process_assessment', [ $assessment_id ] );

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

        // Q1: Selected Health Concerns (Max 25 pts, 5 pts per concern)
        $q1_points = 0;
        $selected_concerns = isset( $answers['q1'] ) && is_array( $answers['q1'] ) ? $answers['q1'] : [];
        if ( ! empty( $selected_concerns ) ) {
            $q1_points = min( count( $selected_concerns ) * 5, 25 );
        }
        $risk_score += $q1_points;

        // Q3: Duration of Concerns (Max 10 pts)
        $q3_points = 0;
        $q3_map = [
            'Less than 6 months' => 0,
            '6–12 months'        => 3,
            '1–3 years'          => 6,
            '3–5 years'          => 8,
            'More than 5 years'  => 10
        ];
        if ( isset( $answers['q3'] ) && isset( $q3_map[ $answers['q3'] ] ) ) {
            $q3_points = $q3_map[ $answers['q3'] ];
        }
        $risk_score += $q3_points;

        // Q4: Energy Pattern (Max 15 pts)
        $q4_points = 0;
        $q4_map = [
            'I feel energetic most days.'         => 0,
            'I usually crash in the afternoon.'   => 4,
            'I rely on caffeine most days.'       => 7,
            'I\'m tired most of the day.'         => 11,
            'I\'m exhausted even after sleeping.' => 15
        ];
        if ( isset( $answers['q4'] ) && isset( $q4_map[ $answers['q4'] ] ) ) {
            $q4_points = $q4_map[ $answers['q4'] ];
        }
        $risk_score += $q4_points;

        // Q5: Cravings (Max 15 pts)
        $q5_points = 0;
        $q5_map = [
            'Rarely'                 => 0,
            'A few times each week'  => 5,
            'Daily'                  => 10,
            'Multiple times per day' => 15
        ];
        if ( isset( $answers['q5'] ) && isset( $q5_map[ $answers['q5'] ] ) ) {
            $q5_points = $q5_map[ $answers['q5'] ];
        }
        $risk_score += $q5_points;

        // Q6: Diagnosed Conditions (Max 35 pts, 5 pts per condition, excluding "None")
        $q6_points = 0;
        $diagnosed = isset( $answers['q6'] ) && is_array( $answers['q6'] ) ? $answers['q6'] : [];
        $valid_conditions = array_filter( $diagnosed, function( $item ) {
            return $item !== 'None';
        } );
        if ( ! empty( $valid_conditions ) ) {
            $q6_points = min( count( $valid_conditions ) * 5, 35 );
        }
        $risk_score += $q6_points;

        // Cap Risk Score between 0 and 100
        $risk_score = min( max( $risk_score, 0 ), 100 );
        $health_score = 100 - $risk_score;

        // Score Category & PDF Selection
        if ( $health_score >= 85 ) {
            $category = 'Excellent';
            $pdf_file = 'Excellent_Health_Snapshot.pdf';
            $category_explanation = 'You appear to be doing many things that support your health. Continue building on these habits while looking for opportunities to optimize your energy, sleep, fitness, and long-term wellness.';
        } elseif ( $health_score >= 70 ) {
            $category = 'Good';
            $pdf_file = 'Good_Health_Snapshot.pdf';
            $category_explanation = 'Your responses suggest that your health is on a generally positive path, although a few areas may benefit from focused improvement.';
        } elseif ( $health_score >= 55 ) {
            $category = 'Fair';
            $pdf_file = 'Fair_Health_Snapshot.pdf';
            $category_explanation = 'Your responses suggest that several health patterns may be affecting how you feel and function from day to day.';
        } elseif ( $health_score >= 40 ) {
            $category = 'Needs Attention';
            $pdf_file = 'Needs_Attention_Health_Snapshot.pdf';
            $category_explanation = 'Your responses indicate multiple health concerns that may be connected and may benefit from a more structured approach.';
        } else {
            $category = 'Significant Opportunity';
            $pdf_file = 'Significant_Opportunity_Health_Snapshot.pdf';
            $category_explanation = 'Your responses suggest several meaningful opportunities to improve your health with appropriate medical guidance, lifestyle support, and consistency.';
        }

        // Top 3 Main Concerns from Q1
        $main_concerns = array_slice( $selected_concerns, 0, 3 );

        // Primary Goal (Q2)
        $primary_goal = isset( $answers['q2'] ) ? $answers['q2'] : '';

        // Readiness Score (Q9)
        $readiness_score = isset( $answers['q9'] ) ? (int) $answers['q9'] : 5;

        return [
            'risk_score'           => $risk_score,
            'health_score'         => $health_score,
            'score_category'       => $category,
            'pdf_file'             => $pdf_file,
            'category_explanation' => $category_explanation,
            'primary_goal'         => $primary_goal,
            'main_concerns'        => $main_concerns,
            'readiness_score'      => $readiness_score
        ];
    }
}
