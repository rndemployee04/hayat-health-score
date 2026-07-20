<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Health_Score_Email {

    /**
     * Get tier-specific email content based on health score.
     *
     * @param int    $health_score
     * @param string $score_category
     * @param string $concerns_summary Dynamic summary of top concerns.
     * @return array
     */
    public static function get_email_tier_content( $health_score, $score_category, $concerns_summary ) {
        if ( $health_score >= 80 ) {
            // PDF #1 - Excellent
            return [
                'status_label'    => 'Excellent',
                'status_color'    => '#1B5E20',
                'status_emoji'    => '🟢',
                'intro_copy'      => "Congratulations! Your responses suggest you're consistently practicing many of the habits associated with excellent metabolic health. While this assessment is not a medical diagnosis, you've built a strong lifestyle foundation that supports long-term wellness.",
                'concerns_intro'  => 'Your greatest opportunities for continued optimization appear to be',
                'body_copy'       => "It's important to remember that this assessment is designed to evaluate lifestyle habits and common metabolic risk factors. Some conditions—including insulin resistance or other metabolic abnormalities—may not be detected through a questionnaire alone and sometimes require laboratory testing and a medical evaluation.\n\nThe best thing you can do now is to continue building on the healthy habits you've already established. Consistency is what protects long-term health.",
                'cta_intro'       => "Rather than scheduling a consultation, we encourage you to continue learning and staying engaged with evidence-based health education. We'll be sharing practical tips, nutrition strategies, and wellness resources designed to help you maintain excellent health for years to come.",
                'show_cta_button' => false,
                'cta_button_text' => '',
                'closing'         => "Thank you for allowing us to be a small part of your health journey—and congratulations again on an outstanding result.",
                'section_label'   => 'Your Top Health Priorities',
                'duration_label'  => 'How Long You\'ve Been Working On This',
            ];
        } elseif ( $health_score >= 60 ) {
            // PDF #2 - Good
            return [
                'status_label'    => 'Good',
                'status_color'    => '#43A047',
                'status_emoji'    => '🟢',
                'intro_copy'      => "Your responses suggest you're building many of the habits associated with good metabolic health. While this assessment is not a medical diagnosis, you've established a strong foundation that can continue to support your long-term health.",
                'concerns_intro'  => 'Your greatest opportunities for continued optimization appear to be',
                'body_copy'       => "Keep in mind that this assessment is designed to evaluate lifestyle habits and common metabolic risk factors. Some conditions—including insulin resistance or other metabolic abnormalities—may not be detected through a questionnaire alone and sometimes require laboratory testing and a medical evaluation.\n\nAt this stage, your focus should simply be on maintaining your healthy habits while making small improvements where appropriate.",
                'cta_intro'       => "If you'd like to take a deeper look at your metabolic health or discuss whether additional testing or personalized guidance would be beneficial, we'd be happy to help.",
                'show_cta_button' => true,
                'cta_button_text' => 'Schedule Your Health Optimization Consultation',
                'closing'         => "Thank you for taking the time to invest in your health. We wish you continued success on your wellness journey.",
                'section_label'   => 'Your Top Health Priorities',
                'duration_label'  => 'How Long You\'ve Been Working On This',
            ];
        } elseif ( $health_score >= 40 ) {
            // PDF #3 - Fair
            return [
                'status_label'    => 'Fair',
                'status_color'    => '#FFB300',
                'status_emoji'    => '🟡',
                'intro_copy'      => "Your responses suggest that many aspects of your metabolic health are moving in a positive direction, though there are still opportunities for improvement. You're building a solid foundation, and making a few targeted lifestyle changes now may help improve your energy, support a healthier weight, improve sleep, and reduce your future risk of chronic disease.",
                'concerns_intro'  => 'Your greatest opportunities for improvement appear to be',
                'body_copy'       => "The good news is that you're closer than you think. Small, consistent improvements often produce meaningful long-term results when guided by a clear, personalized plan.",
                'cta_intro'       => "If you'd like help understanding your results and learning what steps may have the biggest impact for your specific situation, we'd love to meet with you.",
                'show_cta_button' => true,
                'cta_button_text' => 'Schedule Your Complimentary Consultation',
                'closing'         => "We look forward to speaking with you.",
                'section_label'   => 'Your Top Areas to Improve',
                'duration_label'  => 'How Long You\'ve Been Dealing With This',
            ];
        } elseif ( $health_score >= 20 ) {
            // PDF #4 - Needs Attention
            return [
                'status_label'    => 'Needs Attention',
                'status_color'    => '#FF6D00',
                'status_emoji'    => '🟠',
                'intro_copy'      => "Although this is not a medical diagnosis, your responses suggest there are several opportunities to improve your metabolic health before these patterns become more significant.",
                'concerns_intro'  => 'Your biggest opportunities for improvement appear to be',
                'body_copy'       => "The encouraging news is that many of these concerns are connected. When the underlying contributors are addressed, it's common to see improvements in several areas of health—not just one.\n\nWe've attached your GliaFit Health Snapshot, which explains what your score means and outlines practical first steps you can begin taking today.",
                'cta_intro'       => "A conversation can often provide the clarity that Google searches and another diet cannot.",
                'show_cta_button' => true,
                'cta_button_text' => 'Schedule Your Complimentary Consultation',
                'closing'         => "We look forward to meeting you.",
                'section_label'   => 'Your Top Health Concerns',
                'duration_label'  => 'How Long You\'ve Been Struggling',
            ];
        } else {
            // PDF #5 - Significant Opportunity
            return [
                'status_label'    => 'Significant Opportunity',
                'status_color'    => '#E50914',
                'status_emoji'    => '🔴',
                'intro_copy'      => "Based on your responses, your body may be showing several signs that its metabolism isn't functioning as efficiently as it could. While this score is not a medical diagnosis, it suggests that now is an excellent time to better understand what's contributing to your symptoms before these patterns become more difficult to reverse.",
                'concerns_intro'  => 'Your biggest opportunities for improvement appear to be',
                'body_copy'       => "The encouraging news? Many people begin their health journey exactly where you are today—and with the right plan, meaningful improvements are possible.\n\nWe've attached your GliaFit Health Snapshot, which explains what your score means, why many health concerns are connected, and three practical steps you can begin today.",
                'cta_intro'       => "A complimentary consultation is simply a conversation.",
                'show_cta_button' => true,
                'cta_button_text' => 'Book Your Complimentary Consultation',
                'closing'         => "We look forward to meeting you.",
                'section_label'   => 'Your Main Health Concerns',
                'duration_label'  => 'How Long You\'ve Been Struggling',
            ];
        }
    }

    /**
     * Process assessment and send email with dynamic PDF attachment.
     *
     * @param int $assessment_id
     * @return void
     */
    public static function process_assessment_and_email( $assessment_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'health_assessments';

        $assessment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $assessment_id ) );
        if ( ! $assessment ) {
            error_log( "Health Score Email: Assessment ID {$assessment_id} not found." );
            return;
        }

        // Generate dynamic PDF snapshot attachment
        $pdf_path = Health_Score_PDF::generate_and_save_pdf( $assessment_id );

        // Extract dynamic properties
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

        // Build dynamic concerns summary
        $concerns_summary = Health_Score_PDF::build_concerns_summary( $main_concerns );

        // Get tier-specific email content
        $tier = self::get_email_tier_content( $health_score, $score_category, $concerns_summary );

        $score_color     = $tier['status_color'];
        $status_label    = $tier['status_label'];
        $status_emoji    = $tier['status_emoji'];
        $intro_copy      = $tier['intro_copy'];
        $concerns_intro  = $tier['concerns_intro'];
        $body_copy       = $tier['body_copy'];
        $cta_intro       = $tier['cta_intro'];
        $show_cta_button = $tier['show_cta_button'];
        $cta_button_text = $tier['cta_button_text'];
        $closing         = $tier['closing'];
        $section_label   = $tier['section_label'];
        $duration_label  = $tier['duration_label'];

        $booking_url = get_option( 'health_score_booking_url', '' );

        // Load Email HTML Template
        $template_path = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/email-snapshot.php';
        if ( file_exists( $template_path ) ) {
            ob_start();
            include $template_path;
            $message = ob_get_clean();
        } else {
            $message = "<p>Hi {$first_name}, your GliaFit Health Score is {$health_score}/100 ({$score_category}).</p>";
        }

        $to      = $assessment->email;
        $subject = "{$first_name}, Your GliaFit Health Snapshot Is Ready";

        $host    = parse_url( site_url(), PHP_URL_HOST ) ?: 'gliafit.com';
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: GliaFit Health Assessment <no-reply@' . $host . '>'
        ];

        $attachments = [];
        if ( $pdf_path && file_exists( $pdf_path ) ) {
            $attachments[] = $pdf_path;
        }

        $sent = wp_mail( $to, $subject, $message, $headers, $attachments );

        if ( ! $sent ) {
            error_log( "Health Score Email: Failed to send email to {$to} for assessment ID {$assessment_id}." );
        }
    }
}
