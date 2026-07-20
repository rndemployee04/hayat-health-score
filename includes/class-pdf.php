<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Dompdf\Dompdf;
use Dompdf\Options;

class Health_Score_PDF {

    /**
     * Get score-specific content based on the health score tier.
     *
     * @param int    $health_score
     * @param string $score_category
     * @return array Tier-specific content for PDF and email.
     */
    public static function get_tier_content( $health_score, $score_category ) {
        if ( $health_score >= 80 ) {
            // PDF #1 - Excellent
            return [
                'score_color'       => '#1B5E20',
                'accent_color'      => '#1B5E20',
                'main_headline'     => "YOUR RESULTS SHOW YOU'RE THRIVING.",
                'score_means'       => "Your responses suggest that your metabolic health is in an excellent place. While this is not a medical diagnosis, your assessment indicates you're building habits that support long-term health. Continue what you're doing while looking for opportunities to maintain and further optimize your well-being.",
                'status_paragraph'  => "You're building an outstanding foundation for lifelong health. Maintaining these healthy habits and making small refinements over time can help you continue feeling your best and reduce your long-term risk of chronic disease.",
                'hope_statement'    => "Excellent health isn't a destination—it's something you build and protect every day.",
                'cta_headline'      => "LET'S KEEP YOU MOVING FORWARD.",
                'consultation_copy' => "During your complimentary consultation, we'll review your assessment, celebrate what's working well, identify opportunities for continued optimization, answer your questions, and help you build a long-term strategy for maintaining excellent health.",
                'cta_button'        => 'SCHEDULE YOUR COMPLIMENTARY CONSULTATION',
            ];
        } elseif ( $health_score >= 60 ) {
            // PDF #2 - Good
            return [
                'score_color'       => '#43A047',
                'accent_color'      => '#43A047',
                'main_headline'     => "YOUR RESULTS SHOW YOU'RE ON A HEALTHY PATH.",
                'score_means'       => "Your responses suggest that your metabolic health is in a good place. While this is not a medical diagnosis, your assessment indicates you're doing many things well. With a few targeted improvements, you may be able to optimize your energy, resilience, and long-term health even further.",
                'status_paragraph'  => "You're building a strong foundation for long-term health. Continuing healthy habits while making a few strategic adjustments may help you feel even better and lower your future risk of chronic disease.",
                'hope_statement'    => "Great health isn't about perfection—it's about consistently moving in the right direction.",
                'cta_headline'      => "LET'S HELP YOU REACH YOUR FULL POTENTIAL.",
                'consultation_copy' => "During your complimentary consultation, we'll review your assessment, explain your results, identify simple ways to optimize your health, answer your questions, and help you create a personalized plan for long-term success.",
                'cta_button'        => 'SCHEDULE YOUR COMPLIMENTARY CONSULTATION',
            ];
        } elseif ( $health_score >= 40 ) {
            // PDF #3 - Fair
            return [
                'score_color'       => '#FFB300',
                'accent_color'      => '#FFB300',
                'main_headline'     => "YOUR RESULTS SHOW YOU'RE MOVING IN THE RIGHT DIRECTION.",
                'score_means'       => "Your responses suggest that many aspects of your metabolic health are moving in a positive direction, though there are still opportunities for improvement. While this is not a medical diagnosis, making a few targeted lifestyle changes now may help improve your long-term health and reduce future risk.",
                'status_paragraph'  => "Your assessment suggests a solid foundation with room to optimize. Small, consistent improvements may help increase your energy, support a healthier weight, improve sleep, and promote long-term metabolic health.",
                'hope_statement'    => "You're closer than you think—small improvements today can lead to meaningful results tomorrow.",
                'cta_headline'      => "LET'S OPTIMIZE YOUR HEALTH TOGETHER.",
                'consultation_copy' => "During your complimentary consultation, we'll review your assessment, explain your results, identify opportunities to optimize your health, answer your questions, and create a personalized plan to help you continue moving in the right direction.",
                'cta_button'        => 'SCHEDULE YOUR COMPLIMENTARY CONSULTATION',
            ];
        } elseif ( $health_score >= 20 ) {
            // PDF #4 - Needs Attention
            return [
                'score_color'       => '#FF6D00',
                'accent_color'      => '#FF6D00',
                'main_headline'     => "YOUR RESULTS SUGGEST IT'S TIME TO MAKE SOME CHANGES.",
                'score_means'       => "Your responses suggest your metabolism may not be functioning as efficiently as it could. While this is not a medical diagnosis, your score indicates there are areas that deserve attention before they become more significant. The encouraging news is that meaningful improvement is often possible with consistent lifestyle changes and the right plan.",
                'status_paragraph'  => "Your assessment suggests there are meaningful opportunities to improve your metabolic health. Taking action now may help improve your energy, weight, sleep, and overall well-being while reducing the likelihood of future health problems.",
                'hope_statement'    => "Your score doesn't define your future—it simply tells us where to begin.",
                'cta_headline'      => "LET'S TALK ABOUT YOUR HEALTH GOALS.",
                'consultation_copy' => "During your complimentary consultation, we'll review your assessment, explain your score, answer your questions, identify opportunities for improvement, and discuss practical next steps tailored to your goals.",
                'cta_button'        => 'SCHEDULE YOUR COMPLIMENTARY CONSULTATION',
            ];
        } else {
            // PDF #5 - Significant Opportunity
            return [
                'score_color'       => '#E50914',
                'accent_color'      => '#E50914',
                'main_headline'     => "YOUR RESULTS SUGGEST IT'S TIME TO TAKE ACTION.",
                'score_means'       => "Your responses suggest your metabolism may not be functioning as efficiently as it could. While this is not a medical diagnosis, it suggests that now is an excellent time to better understand what's contributing to your symptoms before these patterns become more difficult to reverse. The encouraging news? Many people begin their health journey exactly where you are today—and with the right plan, meaningful improvements are possible.",
                'status_paragraph'  => "Your assessment suggests that several health patterns may be affecting how you feel and function from day to day.",
                'hope_statement'    => "Your score doesn't define your future—it simply tells us where to begin.",
                'cta_headline'      => "LET'S BUILD YOUR PERSONALIZED HEALTH PLAN.",
                'consultation_copy' => "During your complimentary consultation, we'll review your assessment, explain your score and what it means, explore root contributors to your symptoms, answer your questions, and determine if GliaFit is the right fit for you.",
                'cta_button'        => 'SCHEDULE YOUR COMPLIMENTARY CONSULTATION',
            ];
        }
    }

    /**
     * Generate dynamic Health Snapshot PDF using Dompdf and HTML template.
     *
     * @param int $assessment_id
     * @return string|false Path to saved PDF file or false on failure.
     */
    public static function generate_and_save_pdf( $assessment_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';

        $assessment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $assessment_id ) );
        if ( ! $assessment ) {
            error_log( "Health Score PDF: Assessment ID {$assessment_id} not found." );
            return false;
        }

        // Prepare dynamic data
        $first_name      = ! empty( $assessment->first_name ) ? $assessment->first_name : 'Friend';
        $health_score    = intval( $assessment->health_score );
        $score_category  = ! empty( $assessment->score_category ) ? $assessment->score_category : 'Fair';
        $primary_goal    = ! empty( $assessment->primary_goal ) ? $assessment->primary_goal : 'Feel healthy again';
        $readiness_score = intval( $assessment->readiness_score ?: 5 );

        $main_concerns = json_decode( $assessment->main_concerns, true );
        if ( ! is_array( $main_concerns ) || empty( $main_concerns ) ) {
            $main_concerns = json_decode( $assessment->selected_health_concerns, true );
            if ( ! is_array( $main_concerns ) ) {
                $main_concerns = [ 'General Wellness' ];
            }
        }

        $biggest_challenge = ! empty( $assessment->biggest_concern ) ? $assessment->biggest_concern : '';
        $symptom_duration  = ! empty( $assessment->duration ) ? $assessment->duration : '';

        // Get tier-specific content
        $tier = self::get_tier_content( $health_score, $score_category );

        $score_color    = $tier['score_color'];
        $accent_color   = $tier['accent_color'];
        $main_headline  = $tier['main_headline'];
        $score_means    = $tier['score_means'];
        $status_paragraph = $tier['status_paragraph'];
        $hope_statement = $tier['hope_statement'];
        $cta_headline   = $tier['cta_headline'];
        $consultation_copy = $tier['consultation_copy'];
        $cta_button     = $tier['cta_button'];

        // Gauge needle rotation angle (0 to 180 degrees)
        $needle_angle = min( 180, max( 0, ( $health_score / 100 ) * 180 ) );

        $booking_url  = get_option( 'health_score_booking_url', 'https://gliafit.com' );
        $qr_code_url  = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode( $booking_url );

        // Dynamic summary sentence
        $concerns_summary = self::build_concerns_summary( $main_concerns );

        // Render HTML Template
        $template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/pdf-snapshot.php';
        if ( ! file_exists( $template_path ) ) {
            error_log( "Health Score PDF: Template file missing at {$template_path}" );
            return false;
        }

        ob_start();
        include $template_path;
        $html = ob_get_clean();

        // Render PDF via Dompdf
        try {
            $options = new Options();
            $options->set( 'isRemoteEnabled', true );
            $options->set( 'isHtml5ParserEnabled', true );

            $dompdf = new Dompdf( $options );
            $dompdf->loadHtml( $html );
            $dompdf->setPaper( 'A4', 'portrait' );
            $dompdf->render();

            $upload_dir = wp_upload_dir();
            $pdf_dir    = $upload_dir['basedir'] . '/health-snapshots';
            if ( ! file_exists( $pdf_dir ) ) {
                wp_mkdir_p( $pdf_dir );
            }

            $filename = 'GliaFit_Health_Snapshot_' . $assessment_id . '.pdf';
            $pdf_path = $pdf_dir . '/' . $filename;
            file_put_contents( $pdf_path, $dompdf->output() );

            // Update database record
            $wpdb->update(
                $table_name,
                [ 'pdf_sent' => $filename ],
                [ 'id' => $assessment_id ],
                [ '%s' ],
                [ '%d' ]
            );

            return $pdf_path;
        } catch ( Exception $e ) {
            error_log( "Health Score PDF Generation Error: " . $e->getMessage() );
            return false;
        }
    }

    /**
     * Build a dynamic summary sentence from the user's top concerns.
     *
     * @param array $concerns
     * @return string
     */
    public static function build_concerns_summary( $concerns ) {
        if ( empty( $concerns ) ) {
            return 'general wellness and metabolic health';
        }

        $lowered = array_map( 'strtolower', array_slice( $concerns, 0, 3 ) );
        $count   = count( $lowered );

        if ( $count === 1 ) {
            return $lowered[0];
        } elseif ( $count === 2 ) {
            return $lowered[0] . ' and ' . $lowered[1];
        } else {
            return $lowered[0] . ', ' . $lowered[1] . ', and ' . $lowered[2];
        }
    }
}
