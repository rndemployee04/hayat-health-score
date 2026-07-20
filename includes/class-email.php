<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Health_Score_Email {
    
    public static function process_assessment_and_email( $assessment_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';
        
        $assessment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $assessment_id ) );
        if ( ! $assessment ) {
            error_log( "Health Score: Assessment ID {$assessment_id} not found." );
            return;
        }

        $pdf_path = Health_Score_PDF::generate_and_save_pdf( $assessment_id );

        if ( ! $pdf_path || ! file_exists( $pdf_path ) ) {
            error_log( "Health Score: Failed to locate PDF for assessment ID {$assessment_id}." );
            return;
        }

        $booking_url = get_option( 'health_score_booking_url', '' );

        $first_name   = ! empty( $assessment->first_name ) ? $assessment->first_name : 'Friend';
        $health_score = intval( $assessment->health_score );
        $category     = ! empty( $assessment->score_category ) ? $assessment->score_category : 'Fair';
        $primary_goal = ! empty( $assessment->primary_goal ) ? $assessment->primary_goal : 'Feel healthy again';
        
        $main_concerns = json_decode( $assessment->main_concerns, true );
        if ( ! is_array( $main_concerns ) || empty( $main_concerns ) ) {
            $main_concerns = json_decode( $assessment->selected_health_concerns, true );
            if ( is_array( $main_concerns ) ) {
                $main_concerns = array_slice( $main_concerns, 0, 3 );
            } else {
                $main_concerns = ['General Wellness'];
            }
        }

        $concerns_list = '';
        foreach ( $main_concerns as $concern ) {
            $concerns_list .= "• {$concern}\n";
        }

        $to      = $assessment->email;
        $subject = "{$first_name}, Your Health Snapshot Is Ready";

        $message  = "Hi {$first_name},\n\n";
        $message .= "Thank you for completing the GliaFit – 60-Second Health Score.\n\n";
        $message .= "Your Health Score is:\n\n";
        $message .= "{$health_score} / 100\n\n";
        $message .= "Category:\n";
        $message .= "{$category}\n\n";
        $message .= "You shared that your primary goal is:\n";
        $message .= "{$primary_goal}\n\n";
        $message .= "Based on your answers, your main areas of concern include:\n\n";
        $message .= "{$concerns_list}\n";
        $message .= "We have attached your Health Snapshot, which explains what your score may mean and provides practical next steps.\n\n";
        $message .= "This assessment is educational and is not a medical diagnosis.\n\n";
        $message .= "Book My Complimentary Consultation:\n{$booking_url}\n\n";
        $message .= "Health Assessment Team";

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: Health Assessment <no-reply@domain.com>'
        ];

        $attachments = [ $pdf_path ];

        $sent = wp_mail( $to, $subject, $message, $headers, $attachments );

        if ( ! $sent ) {
            error_log( "Health Score: Failed to send email to {$to} for assessment ID {$assessment_id}." );
        }
    }
}
