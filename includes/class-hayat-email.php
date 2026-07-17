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
        $subject = 'Your Personalized Hayat Tayyiba Health Score';
        
        $message = "Dear {$assessment->first_name},\n\n";
        $message .= "Thank you for completing the Hayat Tayyiba Health Assessment.\n\n";
        $message .= "Attached is your personalized Health Score and Top Opportunities report. Review it to see where you can focus over the next six months to improve your overall vitality.\n\n";
        $message .= "We highly recommend booking a complimentary consultation with our clinical team to review your results in depth. You can book here: {$booking_url}\n\n";
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
