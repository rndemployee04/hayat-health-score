<?php

use Dompdf\Dompdf;
use Dompdf\Options;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Hayat_Health_Score_PDF {

    public static function generate_and_save_pdf( $assessment_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hayat_assessments';
        
        $assessment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $assessment_id ) );
        if ( ! $assessment ) {
            return false;
        }

        $html = self::get_pdf_html( $assessment );

        $options = new Options();
        $options->set( 'isHtml5ParserEnabled', true );
        $options->set( 'isRemoteEnabled', true ); // Allow external CSS/images

        $dompdf = new Dompdf( $options );
        $dompdf->loadHtml( $html );
        $dompdf->setPaper( 'A4', 'portrait' );
        $dompdf->render();

        $output = $dompdf->output();

        // Save to uploads directory
        $upload_dir = wp_upload_dir();
        $reports_dir = $upload_dir['basedir'] . '/hayat-reports';
        
        if ( ! file_exists( $reports_dir ) ) {
            wp_mkdir_p( $reports_dir );
        }

        // Generate secure filename
        $hash = md5( $assessment_id . $assessment->email . wp_salt() );
        $filename = "health-report-{$assessment_id}-{$hash}.pdf";
        $filepath = $reports_dir . '/' . $filename;

        file_put_contents( $filepath, $output );

        return $filepath;
    }

    private static function get_pdf_html( $assessment ) {
        $first_name = esc_html( $assessment->first_name );
        $score = intval( $assessment->health_score );
        $booking_url = esc_url( get_option( 'hayat_booking_url', 'https://cal.com/hayattayyiba/assessment' ) );
        $primary_color = esc_attr( get_option( 'hayat_primary_color', '#2E8B57' ) );
        
        $opportunities = json_decode( $assessment->top_opportunities, true );
        if ( ! is_array( $opportunities ) || empty( $opportunities ) ) {
            $opportunities = ['General Health Optimization'];
        }

        // Determine Template Based on Score
        $score_color = '<?php echo $primary_color; ?>'; // Green
        $score_bg_color = '#EAF3ED';
        $title = '';
        $subtitle = '';
        $body_html = '';
        $category = '';
        
        $date_completed = wp_date( get_option( 'date_format' ), strtotime( $assessment->created_at ) );

        if ( $score >= 85 ) {
            $category = 'Excellent';
            $title = "You're Off to a Great Start.";
            $subtitle = "Congratulations!";
            $body_html = "
                <p>Based on your responses, you're already doing many things that support your health. That's something to be proud of.</p>
                <p><strong>However...</strong></p>
                <p>Even people with excellent health scores often tell us they want more:</p>
                <ul class='check-list'>
                    <li>More energy</li>
                    <li>Better sleep</li>
                    <li>Better body composition</li>
                    <li>Better fitness</li>
                    <li>Better long-term health</li>
                </ul>
                <p>Health isn't simply avoiding disease. It's feeling your best.</p>
                <h3>Three Things To Keep Doing</h3>
                <ul>
                    <li>Prioritize quality sleep.</li>
                    <li>Stay physically active.</li>
                    <li>Continue eating whole, minimally processed foods.</li>
                </ul>
                <h3>One Question...</h3>
                <p>Imagine how you'd feel six months from now if you improved just 10%. Would it be worth it?</p>
                <h3>Next Step</h3>
                <p>If you'd like to optimize your health even further, we'd love to meet with you. Book a complimentary Hayat Tayyiba consultation.</p>
            ";
        } elseif ( $score >= 70 ) {
            $category = 'Good';
            $score_color = '#f0ad4e'; // Orange
            $score_bg_color = '#FDF6EB';
            $title = "You're On The Right Track.";
            $subtitle = "Your responses suggest you're doing well in many areas.";
            $body_html = "
                <p><strong>However...</strong></p>
                <p>There are a few patterns that may be preventing you from feeling your absolute best. Small issues often become bigger over time.</p>
                <p><strong>The encouraging news?</strong></p>
                <p>Small improvements made consistently often create meaningful results.</p>
                <h3>Your Biggest Opportunities</h3>
                <ul class='check-list'>
                    <li>Energy</li>
                    <li>Sleep</li>
                    <li>Weight</li>
                </ul>
                <h3>Did You Know?</h3>
                <p>Weight, fatigue, poor sleep, blood sugar, and blood pressure often influence one another. Improving one area can positively affect several others.</p>
                <h3>You Don't Need To Be Perfect.</h3>
                <p>You simply need a plan that's realistic and sustainable.</p>
                <h3>Next Step</h3>
                <p>A complimentary Hayat Tayyiba consultation can help identify practical steps based on your goals.</p>
            ";
        } elseif ( $score >= 55 ) {
            $category = 'Fair';
            $score_color = '#f0ad4e'; // Orange
            $score_bg_color = '#FDF6EB';
            $title = "Your Body May Be Asking For Attention.";
            $subtitle = "Several health concerns appear to be affecting your day-to-day life.";
            $body_html = "
                <p>Many people assume these issues are simply part of getting older. Often, they aren't.</p>
                <p>Weight.<br>Energy.<br>Sleep.<br>Blood sugar.<br>Blood pressure.</p>
                <p>These concerns frequently occur together.</p>
                <h3>The Good News</h3>
                <p>Your body is remarkably adaptable. Many people experience meaningful improvements when they focus on the underlying contributors rather than chasing individual symptoms.</p>
                <h3>Imagine Six Months From Now...</h3>
                <p>More energy.<br>Better sleep.<br>A healthier weight.<br>More confidence.<br>More life.</p>
                <h3>Next Step</h3>
                <p>This is exactly what your complimentary Hayat Tayyiba consultation is designed for.</p>
            ";
        } elseif ( $score >= 40 ) {
            $category = 'Needs Attention';
            $score_color = '#d9534f'; // Red
            $score_bg_color = '#F9ECEC';
            $title = "There Is Hope.";
            $subtitle = "Your responses suggest multiple areas that deserve attention.";
            $body_html = "
                <p>If you've tried different diets...<br>Different supplements...<br>Different programs...<br>You're not alone.</p>
                <p>Many people tell us they've spent years trying to figure things out on their own.</p>
                <h3>Here's Something Important</h3>
                <p>Trying harder isn't always the answer. Sometimes the problem isn't effort. Sometimes it's having the right plan.</p>
                <h3>Our Goal</h3>
                <p>Not another fad diet.<br>Not another quick fix.<br>Not adding more medications.</p>
                <p>Our goal is helping people build healthier habits that support lasting improvements.</p>
                <h3>Your Next Step</h3>
                <p>Schedule your complimentary Hayat Tayyiba consultation. Together we'll discuss your goals and determine whether Hayat Tayyiba is right for you.</p>
            ";
        } else {
            $category = 'Significant Opportunity';
            $score_color = '#d9534f'; // Red
            $score_bg_color = '#F9ECEC';
            $title = "Your Results Suggest There May Be Significant Opportunities To Improve Your Health.";
            $subtitle = "The encouraging news? Today's score does not determine tomorrow's health.";
            $body_html = "
                <p>Many people begin their journey exactly where you are now. What matters most isn't where you start. It's where you're headed.</p>
                <h3>You're Not Alone.</h3>
                <p>Many people who struggle with weight, low energy, poor sleep, blood sugar, or blood pressure believe these are separate problems. In reality, they often influence one another.</p>
                <h3>Imagine Looking Back One Year From Today...</h3>
                <p>Imagine having more energy.<br>Sleeping better.<br>Feeling lighter.<br>Needing fewer limitations.<br>Feeling like yourself again.</p>
                <h3>Your Next Step</h3>
                <p>A Hayat Tayyiba consultation isn't a commitment. It's simply a conversation. We'll review your assessment, discuss your goals, answer your questions, and determine whether Hayat Tayyiba is the right fit for you.</p>
            ";
        }

        // Generate QR Code URL
        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($booking_url);

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body {
                    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                    color: #333;
                    background-color: #FBF5E8;
                    padding: 40px;
                    line-height: 1.6;
                }
                .container {
                    background-color: #fff;
                    padding: 40px;
                    border-radius: 8px;
                    border: 1px solid #DCD7C9;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid <?php echo $primary_color; ?>;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .header h1 {
                    color: <?php echo $primary_color; ?>;
                    margin: 0 0 10px 0;
                    font-size: 28px;
                }
                .header h2 {
                    color: #666;
                    margin: 0;
                    font-size: 18px;
                    font-weight: normal;
                }
                .score-section {
                    text-align: center;
                    margin-bottom: 40px;
                }
                .score-circle {
                    display: block;
                    width: 140px;
                    height: 140px;
                    border-radius: 70px;
                    background-color: <?php echo $score_bg_color; ?>;
                    border: 6px solid <?php echo $score_color; ?>;
                    text-align: center;
                    color: <?php echo $score_color; ?>;
                    margin: 0 auto 20px auto;
                }
                .score-value {
                    font-size: 56px;
                    font-weight: bold;
                    margin-top: 36px;
                    display: block;
                }
                .content-section h2 {
                    color: <?php echo $score_color; ?>;
                    margin-bottom: 5px;
                }
                .content-section h3 {
                    color: <?php echo $primary_color; ?>;
                    margin-top: 25px;
                    margin-bottom: 10px;
                    page-break-after: avoid;
                }
                .content-section .subtitle {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }
                .check-list {
                    list-style: none;
                    padding-left: 0;
                }
                .check-list li::before {
                    content: "✓ ";
                    color: <?php echo $primary_color; ?>;
                    font-weight: bold;
                }
                .btn {
                    display: inline-block;
                    background-color: <?php echo $primary_color; ?>;
                    color: #fff;
                    padding: 15px 30px;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    margin-top: 20px;
                }
                /* Page Break for Universal Last Page */
                .page-break {
                    page-break-before: always;
                }
                .final-page {
                    text-align: center;
                }
                .final-page h1 {
                    color: <?php echo $primary_color; ?>;
                }
                .qr-box {
                    margin: 30px 0;
                }
                .testimonial {
                    background-color: #FBF5E8;
                    padding: 20px;
                    border-left: 4px solid <?php echo $primary_color; ?>;
                    margin-top: 40px;
                    font-style: italic;
                    text-align: left;
                }
            </style>
        </head>
        <body>
            <!-- PAGE 1: Personalized Score & Copy -->
            <div class="container">
                <div class="header">
                    <h1><?php echo $first_name; ?>'s Hayat Tayyiba Health Snapshot</h1>
                    <h2>Health Score: <?php echo $score; ?></h2>
                </div>

                <div class="score-section">
                    <div class="score-circle">
                        <span class="score-value"><?php echo $score; ?></span>
                    </div>
                    <p style="font-family: 'Outfit', sans-serif; font-size: 1.2rem; color: #4A4A4A; margin-top: 15px;">
                        <strong>Category:</strong> <?php echo esc_html( $category ); ?>
                    </p>
                    <p style="font-family: 'Lexend', sans-serif; font-size: 0.9rem; color: #888;">
                        Completed on: <?php echo esc_html( $date_completed ); ?>
                    </p>
                </div>

                <div class="content-section">
                    <h2><?php echo $title; ?></h2>
                    <p class="subtitle"><?php echo $subtitle; ?></p>
                    
                    <?php echo $body_html; ?>
                    
                    <div style="text-align: center; margin-top: 40px;">
                        <a href="<?php echo $booking_url; ?>" class="btn">Book Consultation</a>
                    </div>
                </div>
            </div>

            <!-- PAGE 2: Universal Last Page -->
            <div class="page-break"></div>
            <div class="container final-page">
                <h1><?php echo $first_name; ?>'s Personalized Next Steps</h1>
                
                <div style="text-align: left; margin-bottom: 30px;">
                    <h3 style="color: <?php echo $primary_color; ?>;">Your Top Opportunities</h3>
                    <p>Based on your unique assessment, focusing on these areas will yield the highest return for your health:</p>
                    <ul>
                        <?php foreach ( $opportunities as $opp ) : ?>
                            <li><strong><?php echo esc_html( $opp ); ?></strong></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div style="text-align: left; margin-bottom: 30px;">
                    <h3 style="color: <?php echo $primary_color; ?>;">What to Expect During Your Consultation:</h3>
                    <ul>
                        <li>Review of your Health Score</li>
                        <li>Discussion of what's been keeping you stuck</li>
                        <li>Personalized recommendations</li>
                        <li>Opportunity to ask questions</li>
                    </ul>
                </div>

                <div class="qr-box">
                    <p><strong>Scan this code to book immediately:</strong></p>
                    <img src="<?php echo $qr_url; ?>" alt="Booking QR Code" width="150" height="150" />
                    <br><br>
                    <a href="<?php echo $booking_url; ?>" class="btn">Or Click Here to Schedule</a>
                </div>

                <div class="testimonial">
                    <?php echo get_option('hayat_pdf_testimonial', '"I finally feel like I have a structured plan. The team helped me focus on the right lifestyle changes, and the improvement in my daily energy has been incredible." <br><br><strong>— A Hayat Tayyiba Client</strong>'); ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
