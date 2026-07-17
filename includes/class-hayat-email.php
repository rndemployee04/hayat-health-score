<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Hayat_Health_Score_Email {
    
    public static function process_assessment_and_email( $assessment_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hayat_assessments';
        
        $assessment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $assessment_id ) );
        if ( ! $assessment ) {
            error_log( "Hayat Health Score: Assessment ID {$assessment_id} not found." );
            return;
        }

        // Generate the PDF
        $pdf_path = Hayat_Health_Score_PDF::generate_and_save_pdf( $assessment_id );

        if ( ! $pdf_path || ! file_exists( $pdf_path ) ) {
            error_log( "Hayat Health Score: Failed to generate PDF for assessment ID {$assessment_id}." );
            return;
        }

        // Get dynamic booking URL
        $booking_url = get_option( 'hayat_booking_url', 'https://cal.com/hayattayyiba/assessment' );

        // Prepare email
        $to = $assessment->email;
        $subject = 'Your Hayat Tayyiba Health Snapshot';
        
        $score = intval( $assessment->health_score );
        $score_message = 'Significant Opportunity';
        if ( $score >= 85 ) {
            $score_message = 'Excellent - You\'re doing many things well. Keep building on those healthy habits.';
        } elseif ( $score >= 70 ) {
            $score_message = 'Good - Your health appears to be on a good path, with a few areas that could benefit from improvement.';
        } elseif ( $score >= 55 ) {
            $score_message = 'Fair - Your responses suggest there may be several areas affecting your overall health.';
        } elseif ( $score >= 40 ) {
            $score_message = 'Needs Attention - Your responses indicate several health concerns that may be connected.';
        } else {
            $score_message = 'Significant Opportunity - Your responses suggest multiple areas that may benefit from a personalized health improvement plan.';
        }

        $message = "Dear {$assessment->first_name},\n\n";
        $message .= "Thank you for completing the Hayat Tayyiba Health Assessment.\n\n";
        $message .= "Your Hayat Tayyiba Health Score is: {$score}/100\n";
        $message .= "{$score_message}\n\n";
        $message .= "Attached is your personalized Health Snapshot PDF. Review it to see your top opportunities for improvement and what you can focus on over the next six months.\n\n";
        $message .= "If you'd like to optimize your health even further, we'd love to meet with you. Schedule a complimentary Hayat Tayyiba consultation here: {$booking_url}\n\n";
        $message .= "Best regards,\nThe Hayat Tayyiba Team";

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: Hayat Tayyiba <no-reply@hayattayyiba.com>'
        ];

        $attachments = [ $pdf_path ];

        // Send email
        $sent = wp_mail( $to, $subject, $message, $headers, $attachments );

        if ( ! $sent ) {
            error_log( "Hayat Health Score: Failed to send email to {$to} for assessment ID {$assessment_id}." );
        }
        
        // Optionally, delete the PDF after sending to save space, or keep it for the admin dashboard.
        // We'll keep it for the admin dashboard.
    }
}
