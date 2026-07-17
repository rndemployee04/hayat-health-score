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
        
        $opportunities = json_decode( $assessment->top_opportunities, true );
        if ( ! is_array( $opportunities ) ) {
            $opportunities = [];
        }

        $score_color = '#2E8B57'; // Green
        $score_message = 'Excellent';
        if ( $score < 60 ) {
            $score_color = '#d9534f'; // Red
            $score_message = 'Action Needed';
        } elseif ( $score < 80 ) {
            $score_color = '#f0ad4e'; // Orange
            $score_message = 'Room for Improvement';
        }

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
                }
                .container {
                    background-color: #fff;
                    padding: 30px;
                    border-radius: 8px;
                    border: 1px solid #DCD7C9;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #2E8B57;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .header h1 {
                    color: #2E8B57;
                    margin: 0;
                }
                .score-section {
                    text-align: center;
                    margin-bottom: 40px;
                }
                .score-circle {
                    display: inline-block;
                    width: 150px;
                    height: 150px;
                    border-radius: 75px;
                    border: 6px solid <?php echo $score_color; ?>;
                    text-align: center;
                    line-height: 150px;
                    font-size: 48px;
                    font-weight: bold;
                    color: <?php echo $score_color; ?>;
                }
                .opportunities {
                    margin-top: 30px;
                }
                .opportunities h3 {
                    color: #2E8B57;
                }
                .opportunities ul {
                    line-height: 1.8;
                    font-size: 16px;
                }
                .footer {
                    margin-top: 50px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Hayat Tayyiba Health Score</h1>
                    <p>Personalized Report for <?php echo $first_name; ?></p>
                </div>

                <div class="score-section">
                    <h2>Your Health Score</h2>
                    <div class="score-circle">
                        <?php echo $score; ?>
                    </div>
                    <h3 style="color: <?php echo $score_color; ?>;"><?php echo $score_message; ?></h3>
                </div>

                <div class="opportunities">
                    <h3>Your Top Focus Areas</h3>
                    <p>Based on your answers, we recommend focusing on the following areas over the next 6 months to improve your overall health and vitality:</p>
                    <ul>
                        <?php foreach ( $opportunities as $opp ) : ?>
                            <li><strong><?php echo esc_html( $opp ); ?></strong></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="footer">
                    <p>Book your complimentary consultation today at cal.com/hayattayyiba/assessment</p>
                    <p>This report is for informational purposes only and does not constitute medical advice.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}
