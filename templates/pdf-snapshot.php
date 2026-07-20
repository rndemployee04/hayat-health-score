<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>GliaFit Health Snapshot</title>
<style>
@page {
    size: A4 portrait;
    margin: 8mm 12mm 8mm 12mm;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    color: #333333;
    background: #ffffff;
    font-size: 8.5pt;
    line-height: 1.25;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td {
    vertical-align: top;
}

.dejavu {
    font-family: "DejaVu Sans", sans-serif;
}

/* Header Styles */
.header-table {
    width: 100%;
    margin-bottom: 4px;
}

.title-main {
    color: #0b5f84;
    font-size: 20pt;
    font-weight: bold;
    letter-spacing: 0.5px;
    line-height: 1;
}

.title-sub {
    color: #0b5f84;
    font-size: 9.5pt;
    font-weight: bold;
    letter-spacing: 0.5px;
}

.title-headline {
    color: #222222;
    font-size: 8.5pt;
    font-weight: bold;
    margin-top: 2px;
}

.right-tag {
    color: #0b5f84;
    font-weight: bold;
    line-height: 1.2;
    font-size: 8.5pt;
    letter-spacing: 0.02em;
    text-align: right;
}

.right-tag span {
    color: #6cb33f;
}

.hr-green {
    border: none;
    border-top: 1.5px solid #6cb33f;
    margin: 4px 0 6px 0;
}

/* Section Title */
.section-title {
    color: #0b5f84;
    font-weight: bold;
    font-size: 9.5pt;
    text-transform: uppercase;
    text-align: center;
    margin-bottom: 6px;
}

/* Meter / Gauge */
.score-number {
    font-size: 36pt;
    font-weight: bold;
    line-height: 1;
}

.score-small {
    font-size: 18pt;
    color: #666666;
    font-weight: normal;
}

.status-caption {
    font-size: 6.5pt;
    text-transform: uppercase;
    display: block;
    letter-spacing: 0.5px;
    color: #ffffff;
    opacity: 0.9;
}

.status-message {
    font-size: 10.5pt;
    font-weight: bold;
    text-transform: uppercase;
    display: block;
    margin-top: 1px;
    color: #ffffff;
}

.hope-note {
    font-size: 8pt;
    color: #2e8d3d;
    font-weight: bold;
    margin-top: 4px;
    text-align: center;
}

/* Bullet Items */
.meaning-item-table {
    margin-bottom: 8px;
    width: 100%;
}

.icon-circle-badge {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1.5px solid #d62b2b;
    color: #d62b2b;
    text-align: center;
    line-height: 29px;
    font-weight: bold;
    font-size: 12pt;
    background: #ffffff;
}

.icon-circle-badge.green {
    border-color: #6cb33f;
    color: #6cb33f;
}

/* Grid Circle Icons */
.circle-box {
    text-align: center;
    padding: 2px;
}

.icon-circle-bg {
    width: 36px;
    height: 36px;
    background: #ffffff;
    border: 1px solid #c2d4de;
    border-radius: 50%;
    margin: 0 auto 3px auto;
    text-align: center;
    padding-top: 6px;
}

.circle-label {
    font-size: 7.5pt;
    color: #333333;
    font-weight: bold;
}

.middle-note {
    text-align: center;
    font-size: 7.5pt;
    color: #555555;
    margin-top: 4px;
    line-height: 1.25;
}

/* Steps */
.step-num {
    background: #6cb33f;
    color: #ffffff;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    text-align: center;
    line-height: 18px;
    font-weight: bold;
    font-size: 8.5pt;
}

.step-title {
    color: #6cb33f;
    font-weight: bold;
    font-size: 8.5pt;
    margin-bottom: 2px;
}

.step-desc {
    font-size: 7.5pt;
    color: #222222;
    line-height: 1.2;
}

/* Consultation Banner */
.consult-banner {
    background: #0b4d67;
    border-radius: 14px;
    padding: 8px 10px;
    color: #ffffff;
    margin-top: 6px;
}

.consult-title {
    font-size: 10.5pt;
    font-weight: bold;
    color: #ffffff;
    line-height: 1.2;
}

.consult-title .accent {
    color: #6cb33f;
}

.consult-sub {
    font-size: 7.5pt;
    color: #d0e6f0;
    margin-top: 2px;
    margin-bottom: 4px;
}

.consult-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.consult-list li {
    font-size: 7.5pt;
    color: #ffffff;
    margin-bottom: 2px;
}

.consult-list .check {
    color: #6cb33f;
    font-weight: bold;
    margin-right: 4px;
    font-family: "DejaVu Sans", sans-serif;
}

.obligation-badge {
    background: #6cb33f;
    color: #ffffff;
    padding: 3px 6px;
    border-radius: 6px;
    font-size: 6.5pt;
    font-weight: bold;
    display: inline-block;
    margin-top: 4px;
}

.qr-card {
    background: #ffffff;
    border-radius: 8px;
    padding: 6px;
    text-align: center;
    color: #0b4d67;
}

.qr-card h4 {
    font-size: 7.5pt;
    font-weight: 800;
    color: #0f3c58;
    text-transform: uppercase;
    line-height: 1.15;
}

.qr-card p {
    font-size: 6.5pt;
    color: #3b5471;
    margin: 2px 0 3px 0;
}

.qr-btn {
    background: #6cb33f;
    color: #ffffff;
    font-size: 6.5pt;
    font-weight: bold;
    padding: 3px 5px;
    border-radius: 6px;
    margin-top: 3px;
    text-align: center;
}

/* Footer */
.footer-text {
    font-size: 6.5pt;
    color: #666666;
    line-height: 1.3;
}
</style>
</head>
<body>

<?php
$plugin_dir = plugin_dir_path( dirname( __FILE__ ) );
$img_dir    = $plugin_dir . 'assets/images';

if ( ! function_exists( 'gliafit_get_img_base64' ) ) {
    function gliafit_get_img_base64( $file_path ) {
        if ( file_exists( $file_path ) ) {
            $type = pathinfo( $file_path, PATHINFO_EXTENSION );
            $data = file_get_contents( $file_path );
            $mime = ( strtolower( $type ) === 'jpg' || strtolower( $type ) === 'jpeg' ) ? 'jpeg' : 'png';
            return 'data:image/' . $mime . ';base64,' . base64_encode( $data );
        }
        return '';
    }
}

// Function to composite gauge needle directly on Meter.png using PHP GD
if ( ! function_exists( 'gliafit_get_meter_with_needle_base64' ) ) {
    function gliafit_get_meter_with_needle_base64( $file_path, $health_score ) {
        if ( ! file_exists( $file_path ) ) {
            return '';
        }
        $im = @imagecreatefrompng( $file_path );
        if ( ! $im ) {
            return gliafit_get_img_base64( $file_path );
        }

        imagealphablending( $im, true );
        imagesavealpha( $im, true );

        $w  = imagesx( $im );
        $h  = imagesy( $im );
        $cx = $w / 2.0;       // Center X
        $cy = $h * 0.90;      // Center Y near bottom arc

        $score_clamped = min( 100, max( 0, intval( $health_score ) ) );
        $angle_deg     = 180.0 - ( $score_clamped / 100.0 ) * 180.0;
        $rad           = deg2rad( $angle_deg );

        $needle_len = $h * 0.68;

        $x2 = $cx + $needle_len * cos( $rad );
        $y2 = $cy - $needle_len * sin( $rad );

        $black = imagecolorallocate( $im, 34, 34, 34 );
        imagesetthickness( $im, 14 );
        imageline( $im, (int)$cx, (int)$cy, (int)$x2, (int)$y2, $black );

        imagefilledellipse( $im, (int)$cx, (int)$cy, 44, 44, $black );
        $white = imagecolorallocate( $im, 255, 255, 255 );
        imagefilledellipse( $im, (int)$cx, (int)$cy, 20, 20, $white );

        ob_start();
        imagepng( $im );
        $data = ob_get_clean();
        imagedestroy( $im );

        return 'data:image/png;base64,' . base64_encode( $data );
    }
}

$logo_img        = gliafit_get_img_base64( $img_dir . '/Gloafit-logo.png' );
$lotus_line_img  = gliafit_get_img_base64( $img_dir . '/lotus-line.png' );
$meter_img       = gliafit_get_meter_with_needle_base64( $img_dir . '/Meter.png', $health_score );
$process_img     = gliafit_get_img_base64( $img_dir . '/Process.png' );
$lotus_green_img = gliafit_get_img_base64( $img_dir . '/lotus-green.png' );
$scan_arrow_img  = gliafit_get_img_base64( $img_dir . '/Scan-arrow.png' );
$doctor_img      = gliafit_get_img_base64( $img_dir . '/bottom-img.png' );
$qr_fallback_img = gliafit_get_img_base64( $img_dir . '/QR.jpg' );

// QR Code handling
$final_qr_img = $qr_fallback_img;
if ( ! empty( $qr_code_url ) ) {
    $qr_remote_data = @file_get_contents( $qr_code_url );
    if ( $qr_remote_data ) {
        $final_qr_img = 'data:image/png;base64,' . base64_encode( $qr_remote_data );
    }
}
?>

<!-- ==================== HEADER ==================== -->
<table class="header-table">
    <tr>
        <td style="width: 20%; vertical-align: middle;">
            <?php if ( $logo_img ): ?>
                <img src="<?php echo $logo_img; ?>" height="44" alt="GliaFit Logo" />
            <?php else: ?>
                <div style="font-size: 16pt; font-weight: bold; color: #0b5f84;">GLIAFIT</div>
            <?php endif; ?>
        </td>
        <td style="width: 60%; text-align: center; vertical-align: middle;">
            <div class="title-sub">YOUR GLIAFIT</div>
            <div class="title-main">HEALTH SNAPSHOT</div>
            <div style="margin: 2px 0;">
                <?php if ( $lotus_line_img ): ?>
                    <img src="<?php echo $lotus_line_img; ?>" height="12" alt="lotus line" />
                <?php endif; ?>
            </div>
            <div class="title-headline"><?php echo esc_html( $main_headline ); ?></div>
        </td>
        <td style="width: 20%; text-align: right; vertical-align: middle;">
            <div class="right-tag">
                FEEL BETTER.<br>
                LIVE BETTER.<br>
                <span>GET YOUR <br>LIFE BACK.</span>
            </div>
        </td>
    </tr>
</table>

<div class="hr-green"></div>

<!-- ==================== TOP SECTION (GAUGE + WHAT YOUR SCORE MEANS) ==================== -->
<table style="margin-top: 4px;">
    <tr>
        <!-- Left Column: Gauge & Status -->
        <td style="width: 49%; padding-right: 8px;">
            <div class="section-title">YOUR GLIAFIT METABOLIC HEALTH GAUGE&trade;</div>

            <div style="text-align: center;">
                <?php if ( $meter_img ): ?>
                    <img src="<?php echo $meter_img; ?>" style="width: 100%; max-width: 280px; height: auto;" alt="Metabolic Gauge" />
                <?php endif; ?>
            </div>

            <!-- Score Display -->
            <div style="text-align: center; margin-top: -8px;">
                <span class="score-number" style="color: <?php echo esc_attr( $score_color ); ?>;"><?php echo esc_html( $health_score ); ?></span>
                <span class="score-small">/100</span>
            </div>

            <!-- Status Badge -->
            <div style="text-align: center; margin-top: 4px;">
                <table style="width: 220px; margin: 0 auto; background-color: <?php echo esc_attr( $score_color ); ?>; border-radius: 6px; padding: 4px 8px;">
                    <tr>
                        <td style="width: 24px; vertical-align: middle; text-align: center; padding-right: 6px;">
                            <div style="width: 18px; height: 18px; border: 1.5px solid #ffffff; border-radius: 50%; color: #ffffff; font-size: 10pt; font-weight: bold; line-height: 16px; text-align: center; margin: 0 auto;">!</div>
                        </td>
                        <td style="vertical-align: middle; text-align: left;">
                            <span class="status-caption">YOUR CURRENT METABOLIC HEALTH STATUS</span>
                            <span class="status-message"><?php echo esc_html( $score_category ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Hope Note -->
            <div class="hope-note">
                <table style="width: 100%;">
                    <tr>
                        <td style="text-align: center; vertical-align: middle; font-size: 8pt; color: #2e8d3d; font-weight: bold;">
                            <span class="dejavu" style="color: #6cb33f; font-size: 10pt; vertical-align: middle;">&#9829;</span>
                            <?php echo esc_html( $hope_statement ); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </td>

        <!-- Right Column: What Your Score Means -->
        <td style="width: 51%; padding-left: 10px; border-left: 1px solid #ddd;">
            <div class="section-title" style="color: <?php echo esc_attr( $accent_color ); ?>; text-align: left; padding-left: 4px;">
                WHAT YOUR SCORE MEANS
            </div>

            <table class="meaning-item-table">
                <tr>
                    <td style="width: 36px; vertical-align: top; padding-top: 2px;">
                        <div class="icon-circle-badge" style="border-color: <?php echo esc_attr( $accent_color ); ?>; color: <?php echo esc_attr( $accent_color ); ?>;">!</div>
                    </td>
                    <td style="font-size: 8.5pt; color: #333333; line-height: 1.35; padding-left: 6px;">
                        <?php echo esc_html( $score_means ); ?>
                    </td>
                </tr>
            </table>

            <table class="meaning-item-table" style="margin-top: 6px;">
                <tr>
                    <td style="width: 36px; vertical-align: top; padding-top: 2px;">
                        <div class="icon-circle-badge" style="border-color: <?php echo esc_attr( $accent_color ); ?>; color: <?php echo esc_attr( $accent_color ); ?>;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="<?php echo esc_attr( $accent_color ); ?>" style="margin-top: 6px;"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                        </div>
                    </td>
                    <td style="font-size: 8.5pt; color: #333333; line-height: 1.35; padding-left: 6px;">
                        <?php echo esc_html( $status_paragraph ); ?>
                    </td>
                </tr>
            </table>

            <table class="meaning-item-table" style="margin-top: 6px;">
                <tr>
                    <td style="width: 36px; vertical-align: top; padding-top: 2px;">
                        <div class="icon-circle-badge green">
                            <span class="dejavu" style="color: #6cb33f; font-size: 11pt; line-height: 29px;">&#9829;</span>
                        </div>
                    </td>
                    <td style="font-size: 8.5pt; color: #6cb33f; font-weight: bold; line-height: 1.35; padding-left: 6px;">
                        The encouraging news is that meaningful improvement is possible with the right plan and support.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="border-top: 1px solid #eee; margin: 8px 0;"></div>

<!-- ==================== MIDDLE SECTION (AFFECTING + CONNECTED SYSTEM) ==================== -->
<table>
    <tr>
        <!-- Left Box: What Your Score May Be Affecting -->
        <td style="width: 50%; padding-right: 8px;">
            <div class="section-title">WHAT YOUR SCORE MAY BE AFFECTING</div>

            <table style="margin-top: 4px;">
                <tr>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M7 2v11h3v9l7-12h-4l4-8z"/></svg>
                        </div>
                        <div class="circle-label">Energy</div>
                    </td>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M12.3 2a10 10 0 1 0 9.7 12.8A8 8 0 0 1 12.3 2z"/></svg>
                        </div>
                        <div class="circle-label">Sleep</div>
                    </td>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H6v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1z"/></svg>
                        </div>
                        <div class="circle-label">Weight</div>
                    </td>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>
                        </div>
                        <div class="circle-label">Blood Sugar</div>
                    </td>
                </tr>
                <tr>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </div>
                        <div class="circle-label">Blood Pressure</div>
                    </td>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7z"/></svg>
                        </div>
                        <div class="circle-label">Mental Clarity</div>
                    </td>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.55 3.89 3.57 4.23V22h2.86v-8.77C11.45 12.89 13 11.12 13 9V2h-2v7zm8-7h-2v20h2V2z"/></svg>
                        </div>
                        <div class="circle-label">Cravings</div>
                    </td>
                    <td class="circle-box" style="width: 25%;">
                        <div class="icon-circle-bg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#0b5f84"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                        </div>
                        <div class="circle-label">Mood</div>
                    </td>
                </tr>
            </table>

            <div class="middle-note">
                These concerns often <strong style="color: #0b5f84;">influence one another</strong><br>because they can share common underlying contributors.
            </div>
        </td>

        <!-- Right Box: Your Body is One Connected System -->
        <td style="width: 50%; padding-left: 8px; border-left: 1px solid #eee;">
            <div class="section-title">YOUR BODY IS ONE CONNECTED SYSTEM</div>

            <div style="text-align: center; margin-top: 4px;">
                <?php if ( $process_img ): ?>
                    <img src="<?php echo $process_img; ?>" style="width: 100%; max-width: 230px; height: auto;" alt="Connected System" />
                <?php endif; ?>
            </div>

            <div class="middle-note">
                When <strong style="color: #0b5f84;">metabolism is out of balance</strong>, it can set off a<br>chain reaction that affects many areas of your health.
            </div>
        </td>
    </tr>
</table>

<div style="border-top: 1px solid #eee; margin: 8px 0;"></div>

<!-- ==================== ACTION STEPS ==================== -->
<div class="section-title" style="text-align: center;">YOUR FIRST THREE ACTION STEPS</div>

<table style="background: #f8f6f6; border-radius: 8px; padding: 6px; width: 100%;">
    <tr>
        <!-- Step 1 -->
        <td style="width: 33%; padding: 4px; vertical-align: top;">
            <table>
                <tr>
                    <td style="width: 22px; vertical-align: top;">
                        <div class="step-num">1</div>
                    </td>
                    <td style="width: 32px; vertical-align: top; text-align: center; padding-top: 2px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#0d5875"><path d="M13.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3C14.8 12 16.8 13 19 13v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L6 8.3V13h2V9.6l1.8-.7z"/></svg>
                    </td>
                    <td style="vertical-align: top;">
                        <div class="step-title">MOVE AFTER MEALS.</div>
                        <div class="step-desc">Even a 10-minute walk after eating can support healthy blood sugar regulation and boost your energy.</div>
                    </td>
                </tr>
            </table>
        </td>

        <!-- Step 2 -->
        <td style="width: 34%; padding: 4px; vertical-align: top; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
            <table>
                <tr>
                    <td style="width: 22px; vertical-align: top;">
                        <div class="step-num">2</div>
                    </td>
                    <td style="width: 32px; vertical-align: top; text-align: center; padding-top: 2px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#0d5875"><path d="M3 2l2.21 20H18.8L21 2H3zm9 17c-2.76 0-5-2.24-5-5 0-3.5 5-8.5 5-8.5s5 5 5 8.5c0 2.76-2.24 5-5 5z"/></svg>
                    </td>
                    <td style="vertical-align: top;">
                        <div class="step-title">HYDRATE SMARTER.</div>
                        <div class="step-desc">Choose water instead of sugary drinks whenever possible. Small swaps make a big difference in your metabolism.</div>
                    </td>
                </tr>
            </table>
        </td>

        <!-- Step 3 -->
        <td style="width: 33%; padding: 4px; vertical-align: top;">
            <table>
                <tr>
                    <td style="width: 22px; vertical-align: top;">
                        <div class="step-num">3</div>
                    </td>
                    <td style="width: 32px; vertical-align: top; text-align: center; padding-top: 2px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#0d5875"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>
                    </td>
                    <td style="vertical-align: top;">
                        <div class="step-title">PROTECT YOUR SLEEP.</div>
                        <div class="step-desc">Aim for 7–8 hours of quality sleep. Sleep impacts energy, cravings, metabolism, and recovery.</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="text-align: center; font-size: 8.5pt; color: #6cb33f; font-weight: bold; margin-top: 4px;">
    <?php if ( $lotus_green_img ): ?>
        <img src="<?php echo $lotus_green_img; ?>" height="14" style="vertical-align: middle; margin-right: 4px;" />
    <?php endif; ?>
    Small, consistent changes are often more powerful than drastic ones.
</div>

<!-- ==================== CONSULTATION CTA BANNER ==================== -->
<div class="consult-banner">
    <table>
        <tr>
            <!-- Left: Doctor Consultation Photo -->
            <td style="width: 32%; vertical-align: middle; padding-right: 8px;">
                <?php if ( $doctor_img ): ?>
                    <img src="<?php echo $doctor_img; ?>" style="width: 100%; border-radius: 8px; display: block;" alt="Doctor Consultation" />
                <?php endif; ?>
            </td>

            <!-- Middle: Consultation Copy -->
            <td style="width: 42%; vertical-align: middle; padding-right: 8px;">
                <div class="consult-title">
                    <?php echo esc_html( $cta_headline ); ?>
                </div>
                <div class="consult-sub">
                    <?php echo esc_html( $consultation_copy ); ?>
                </div>
                <ul class="consult-list">
                    <li><span class="check">&#10004;</span> Review your assessment</li>
                    <li><span class="check">&#10004;</span> Explain your score and what it means</li>
                    <li><span class="check">&#10004;</span> Explore root contributors to your symptoms</li>
                    <li><span class="check">&#10004;</span> Answer your questions</li>
                    <li><span class="check">&#10004;</span> Determine if GliaFit is right for you</li>
                </ul>
                <div class="obligation-badge">
                    <span class="dejavu" style="font-size: 7pt; vertical-align: middle;">&#9829;</span> THERE IS NO OBLIGATION&mdash;JUST CLARITY ABOUT YOUR HEALTH.
                </div>
            </td>

            <!-- Right: QR Card -->
            <td style="width: 26%; vertical-align: middle;">
                <div class="qr-card">
                    <div style="text-align: left; margin-bottom: 2px;">
                        <?php if ( $scan_arrow_img ): ?>
                            <img src="<?php echo $scan_arrow_img; ?>" style="width: 28px; height: auto;" />
                        <?php endif; ?>
                    </div>

                    <h4>READY TO LEARN WHAT<br>YOUR SCORE MEANS?</h4>
                    <p>Scan to schedule your<br>complimentary consultation.</p>

                    <div style="margin: 3px 0;">
                        <?php if ( $final_qr_img ): ?>
                            <img src="<?php echo $final_qr_img; ?>" width="58" height="58" style="border: 2px solid #6cb33f; border-radius: 6px; padding: 2px;" />
                        <?php endif; ?>
                    </div>

                    <div class="qr-btn">
                        SCAN TO BOOK YOUR CONSULTATION
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ==================== FOOTER ==================== -->
<table style="margin-top: 6px; width: 100%;">
    <tr>
        <td style="width: 82%; vertical-align: middle;" class="footer-text">
            This Health Score is based on your responses to lifestyle and symptom questions and is intended for educational purposes only. It is not a medical diagnosis. Some health conditions, including insulin resistance and other metabolic disorders, may only be identified through laboratory testing and a comprehensive medical evaluation.
        </td>
        <td style="width: 18%; text-align: right; vertical-align: middle;">
            <?php if ( $logo_img ): ?>
                <img src="<?php echo $logo_img; ?>" height="24" alt="GliaFit Logo" />
            <?php endif; ?>
        </td>
    </tr>
</table>

</body>
</html>
