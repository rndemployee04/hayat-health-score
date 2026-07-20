<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Health_Score_PDF {

    public static function generate_and_save_pdf( $assessment_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';
        
        $assessment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $assessment_id ) );
        if ( ! $assessment ) {
            return false;
        }

        $pdf_filename = $assessment->pdf_sent;
        if ( empty( $pdf_filename ) ) {
            $category = $assessment->score_category;
            $category_map = [
                'Excellent'               => 'Excellent_Health_Snapshot.pdf',
                'Good'                    => 'Good_Health_Snapshot.pdf',
                'Fair'                    => 'Fair_Health_Snapshot.pdf',
                'Needs Attention'         => 'Needs_Attention_Health_Snapshot.pdf',
                'Significant Opportunity' => 'Significant_Opportunity_Health_Snapshot.pdf'
            ];
            $pdf_filename = isset( $category_map[ $category ] ) ? $category_map[ $category ] : 'Fair_Health_Snapshot.pdf';
        }

        $pdf_path = plugin_dir_path( dirname( __FILE__ ) ) . 'assets/pdfs/' . $pdf_filename;

        if ( file_exists( $pdf_path ) ) {
            return $pdf_path;
        }

        return false;
    }
}
