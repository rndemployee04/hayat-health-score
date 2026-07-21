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
.grid .circle {
    text-align: center;
    vertical-align: top;
    padding: 3px;
}

.icon-circle {
    width: 36px;
    height: 36px;
    background: #ffffff;
    border: 1px solid #c2d4de;
    border-radius: 50%;
    margin: 0 auto 3px auto;
    text-align: center;
    padding-top: 8px;
}

.grid .circle p {
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

            <table class="grid" cellpadding="0" cellspacing="0" border="0" style="margin-top: 4px;">
                <tbody>
                    <tr>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-energy">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 384 512" fill="#0b5f84"><path d="M0 256L224 0x176 192H384L160 512 208 320H0z"/></svg>
                            </div>
                            <p>Energy</p>
                        </td>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-sleep">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 640 512" fill="#0b5f84"><path d="M32 32c17.7 0 32 14.3 32 32V288H576V64c0-17.7 14.3-32 32-32s32 14.3 32 32V448c0 17.7-14.3 32-32 32s-32-14.3-32-32V352H64v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V64C0 46.3 14.3 32 32 32zM160 224c35.3 0 64-28.7 64-64s-28.7-64-64-64s-64 28.7-64 64s28.7 64 64 64zm112-48c-17.7 0-32 14.3-32 32v80H576V208c0-17.7-14.3-32-32-32H272z"/></svg>
                            </div>
                            <p>Sleep</p>
                        </td>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-weight">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512" fill="#0b5f84"><path d="M128 32C92.7 0 32 92.7 32 128V384c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H128zm128 64a96 96 0 1 1 0 192 96 96 0 1 1 0-192zM232 160c0-13.3 10.7-24 24-24s24 10.7 24 24v40h-48v-40z"/></svg>
                            </div>
                            <p>Weight</p>
                        </td>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-bloodsugar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 384 512" fill="#0b5f84"><path d="M192 512C86 512 0 426 0 320C0 228.8 130.2 57.7 166.6 11.7C173.2 3.4 182.4 0 192 0C201.6 0 210.8 3.4 217.4 11.7C253.8 57.7 384 228.8 384 320C384 426 298 512 192 512Z"/></svg>
                            </div>
                            <p>Blood Sugar</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-bp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512" fill="#0b5f84"><path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141c-45.6-7.6-92 7.3-124.6 39.9l-12 12-12-12c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5zM160 192h48l24-48 32 96 24-64 16 16h48"/></svg>
                            </div>
                            <p>Blood Pressure</p>
                        </td>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-mental">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512" fill="#0b5f84"><path d="M184 0c30.9 0 56 25.1 56 56V240H128c-35.3 0-64-28.7-64-64c0-23.6 12.8-44.3 32-55.4C92.2 108.6 88 92.8 88 76c0-41.9 34.1-76 76-76c7 0 13.7 1 20 2.8V0zM272 240V56c0-30.9 25.1-56 56-56c6.3-1.8 13-2.8 20-2.8c41.9 0 76 34.1 76 76c0 16.8-4.2 32.6-12 44.6c19.2 11.1 32 31.8 32 55.4c0 35.3-28.7 64-64 64H272zM0 328c0-30.9 25.1-56 56-56h184v184c0 30.9-25.1 56-56 56c-6.3 1.8-13 2.8-20 2.8c-41.9 0-76-34.1-76-76c0-16.8 4.2-32.6 12-44.6C80.8 481.1 68 460.4 68 436.8c0-35.3 28.7-64 64-64H0v-44.8z"/></svg>
                            </div>
                            <p>Mental Clarity</p>
                        </td>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-cravings">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 448 512" fill="#0b5f84"><path d="M416 0C400 0 384 16 384 32V224H352V32c0-16-16-32-32-32s-32 16-32 32V224H256V32c0-16-16-32-32-32s-32 16-32 32V256c0 35.3 28.7 64 64 64h32v160c0 17.7 14.3 32 32 32s32-14.3 32-32V320h32c35.3 0 64-28.7 64-64V32c0-16-16-32-32-32zM64 32C46.3 32 32 46.3 32 64v192c0 53 43 96 96 96v128c0 17.7 14.3 32 32 32s32-14.3 32-32V352c53 0 96-43 96-96V64c0-17.7-14.3-32-32-32S224 46.3 224 64v128H160V64c0-17.7-14.3-32-32-32S96 46.3 96 64v128H64V64c0-17.7-14.3-32-32-32z"/></svg>
                            </div>
                            <p>Cravings</p>
                        </td>
                        <td class="circle" style="width: 25%;">
                            <div class="icon-circle icon-mood">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 512 512" fill="#0b5f84"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM176 208a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm192-32a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM160 336c12-20 40-48 96-48s84 28 96 48c7.7 12.8 3.8 29.4-8.9 37.1s-29.4 3.8-37.1-8.9c-2.4-4-16.1-23.2-50-23.2s-47.6 19.2-50 23.2c-7.7 12.8-24.4 16.6-37.1 8.9s-16.6-24.4-8.9-37.1z"/></svg>
                            </div>
                            <p>Mood</p>
                        </td>
                    </tr>
                </tbody>
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
