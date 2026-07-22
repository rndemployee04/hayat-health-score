<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>GliaFit Health Snapshot</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
.health-pdf {
    margin: 20px;
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
    font-size: 22pt;
    font-weight: bold;
    letter-spacing: 0.5px;
    line-height: 1;
}

.title-sub {
    color: #0b5f84;
    font-size: 9pt;
    font-weight: bold;
    letter-spacing: 0.5px;
}

.title-headline {
    color: #222222;
    font-size: 8pt;
    font-weight: bold;
    margin-top: 2px;
}

.right-tag {
    color: #0b5f84;
    font-weight: bold;
    line-height: 1.2;
    font-size: 9pt;
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
    font-size: 9pt;
    text-transform: uppercase;
    text-align: center;
    margin-bottom: 10px;
}

/* Meter / Gauge */
.score-number {
    font-size: 32pt;
    font-weight: bold;
    line-height: 1;
}

.score-small {
    font-size: 18pt;
    color: #666666;
    font-weight: normal;
}

.status-caption {
    font-size: 7pt;
    text-transform: uppercase;
    display: block;
    letter-spacing: 0.5px;
    color: #ffffff;
}

.status-message {
    font-size: 10pt;
    font-weight: bold;
    text-transform: uppercase;
    display: block;
    margin-top: 1px;
    color: #ffffff;
}

.hope-note {
    font-size: 9pt;
    color: #2e8d3d;
    font-weight: bold;
    margin-top: 4px;
}

/* Bullet Items */
.meaning-item-table {
    margin-bottom: 10px;
    width: 100%;
}

.icon-circle-badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #d62b2b;
    text-align: center;
    background: #ffffff;
}

.icon-circle-badge i {
    line-height: 32px;
}

/* Grid Circle Icons */
.circle-score {
    text-align: center;
}

.icon-circle {
    width: 40px;
    height: 40px;
    background: #ffffff;
    border: 1px solid #eee;
    border-radius: 50%;
    margin: 0 auto 3px auto;
    text-align: center;
    padding: 6px;
}
.icon-circle i {
    line-height: 30px;
}

.circle-score p {
    font-size: 7.5pt;
    color: #222;
    font-weight: bold;
}

.middle-note {
    text-align: center;
    font-size: 9pt;
    color: ##666;
    margin-top: 18px;
    line-height: 1.5;
}

/* Steps */
.step-num {
    background: #6cb33f;
    color: #ffffff;
    border-radius: 50%;
    text-align: center;
    line-height: 14px;
    font-weight: bold;
    font-size: 8pt;
    width: 18px;
    height: 18px;
}

.step-title {
    color: #6cb33f;
    font-weight: bold;
    font-size: 9pt;
    margin-bottom: 4px;
}

.step-desc {
    font-size: 8pt;
    color: #222222;
    line-height: 1.2;
}

/* Consultation Banner */
.consult-banner {
    background: #0b4d67;
    border-radius: 14px;
    padding: 12px;
    color: #ffffff;
    margin-top: 10px;
}

.consult-title {
    font-size: 9pt;
    font-weight: bold;
    color: #ffffff;
    line-height: 1.2;
}
hr.green {
    border: none;
    border-top: 1px solid #6cb33f;
    margin: 8px 0;
    width: 50px;
}
.consult-title .accent {
    color: #6cb33f;
}

.consult-sub {
    font-size: 8pt;
    color: #fff;
    margin-top: 2px;
    margin-bottom: 4px;
}

.consult-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.consult-list li {
    font-size: 8pt;
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
    border-radius: 10px;
    padding: 10px;
    text-align: center;
}

.qr-card h4 {
    font-size: 9pt;
    font-weight: 800;
    color: #0f3c58;
    text-transform: uppercase;
    line-height: 1.15;
}

.qr-card p {
    font-size: 8.5pt;
    color: #3b5471;
    margin: 3px 0 5px 0;
}

.qr-btn {
    background: #6cb33f;
    color: #ffffff;
    font-size: 7pt;
    font-weight: bold;
    padding: 3px;
    border-radius: 6px;
    margin-top: 3px;
}

/* Footer */
.footer-text {
    font-size: 7pt;
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
        imagesetthickness( $im, 20 );
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

$logo_img        = gliafit_get_img_base64( $img_dir . '/GliaFit-logo.png' );
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


<div class="health-pdf">
<!-- ==================== HEADER ==================== -->
<table class="header-table" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 20%;">
            <?php if ( $logo_img ): ?>
                <img src="<?php echo $logo_img; ?>" height="70" alt="GliaFit Logo" />
            <?php else: ?>
                <div style="font-size: 16pt; font-weight: bold; color: #0b5f84;">GLIAFIT</div>
            <?php endif; ?>
        </td>
        <td style="width: 60%; text-align: center;">
            <div class="title-sub">YOUR GLIAFIT</div>
            <div class="title-main">HEALTH SNAPSHOT</div>
            <div style="margin: 2px 0;">
                <?php if ( $lotus_line_img ): ?>
                    <img src="<?php echo $lotus_line_img; ?>" height="12" alt="lotus line" />
                <?php endif; ?>
            </div>
            <div class="title-headline"><?php echo esc_html( $main_headline ); ?></div>
        </td>
        <td style="width: 20%; text-align: right;">
            <div class="right-tag">
                FEEL BETTER.<br>
                LIVE BETTER.<br>
                <span>GET YOUR <br>LIFE BACK.</span>
            </div>
        </td>
    </tr>
</table>

<!-- ==================== TOP SECTION (GAUGE + WHAT YOUR SCORE MEANS) ==================== -->
<table style="margin-top: 4px;">
    <tr>
        <!-- Left Column: Gauge & Status -->
        <td style="width: 50%; padding-right: 8px;">
            <div class="section-title">YOUR GLIAFIT METABOLIC HEALTH GAUGE</div>

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
                <table style="width:90%; margin: 0 auto; background-color: <?php echo esc_attr( $score_color ); ?>; border-radius: 6px; padding: 4px 8px;">
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
                        <td>
                        <i class="fa-regular fa-heart" style="font-size:18px; padding-right: 5px;"></i>
                </td>
                <td><?php echo esc_html( $hope_statement ); ?>
                        </td>
                    </tr>
                </table>
            </div>
        </td>

        <!-- Right Column: What Your Score Means -->
        <td style="width: 50%; padding-left: 20px; border-left: 1px solid #ddd;">
            <div class="section-title" style="color: <?php echo esc_attr( $accent_color ); ?>; text-align: left; padding-left: 4px;">
                WHAT YOUR SCORE MEANS
            </div>

            <table class="meaning-item-table">
                <tr>
                    <td style="vertical-align: top;">
                        <div class="icon-circle-badge" style="border-color: <?php echo esc_attr( $accent_color ); ?>; color: <?php echo esc_attr( $accent_color ); ?>;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size:22px;"></i>
                        </div>
                    </td>
                    <td style="font-size: 8pt; color: #333333; line-height: 1.35; padding-left: 10px;">
                        <?php echo esc_html( $score_means ); ?>
                    </td>
                </tr>
            </table>

            <div style="border-top: 1px solid #eee; margin: 10px 0;"></div>

            <table class="meaning-item-table">
                <tr>
                    <td style="vertical-align: top;">
                        <div class="icon-circle-badge" style="border-color: <?php echo esc_attr( $accent_color ); ?>; color: <?php echo esc_attr( $accent_color ); ?>;">
                         <i class="fa-solid fa-calendar-days" style="font-size:22px;"></i>
                        </div>
                    </td>
                    <td style="font-size: 8pt; color: #333333; line-height: 1.35; padding-left: 10px;">
                        <?php echo esc_html( $status_paragraph ); ?>
                    </td>
                </tr>
            </table>

            <div style="border-top: 1px solid #eee; margin: 10px 0;"></div>

            <table class="meaning-item-table">
                <tr>
                    <td style="vertical-align: top;">
                        <div class="icon-circle-badge" style="border-color: <?php echo esc_attr( $accent_color ); ?>; color: <?php echo esc_attr( $accent_color ); ?>;">
                         <i class="fa-solid fa-heart" style="font-size:22px;"></i>
                        </div>
                    </td>
                    <td style="font-size: 8pt; color: #333333; line-height: 1.35; padding-left: 10px;">
                        The encouraging news is that meaningful improvement is possible with the right plan and support.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="border-top: 1px solid #eee; margin: 10px 0;"></div>

<!-- ==================== MIDDLE SECTION (AFFECTING + CONNECTED SYSTEM) ==================== -->
<table>
    <tr>
        <!-- Left Box: What Your Score May Be Affecting -->
        <td style="width: 50%; padding:0 20px;">
            <div class="section-title">WHAT YOUR SCORE MAY BE AFFECTING</div>

            <table class="grid" cellpadding="0" cellspacing="0" border="0" style="margin-top: 10px;">
                <tbody>
                    <tr>
                        <td class="circle-score" style="width: 25%; padding-bottom: 6px; vertical-align: middle;">
                            <div class="icon-circle icon-energy">
                            <i class="fa-solid fa-bolt" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Energy</p>
                        </td>
                        <td class="circle-score" style="width: 25%; padding-bottom: 6px; vertical-align: middle;">
                            <div class="icon-circle icon-sleep">
                               <i class="fa-solid fa-bed" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Sleep</p>
                        </td>
                        <td class="circle-score" style="width: 25%; padding-bottom: 6px; vertical-align: middle;">
                            <div class="icon-circle icon-weight">
                                <i class="fa-solid fa-weight" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Weight</p>
                        </td>
                        <td class="circle-score" style="width: 25%; padding-bottom: 6px; vertical-align: middle;">
                            <div class="icon-circle icon-bloodsugar">
                                <i class="fa-solid fa-tint" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Blood Sugar</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="circle-score" style="width: 25%; vertical-align: middle;">
                            <div class="icon-circle icon-bp">
                                <i class="fa-solid fa-heartbeat" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Blood Pressure</p>
                        </td>
                        <td class="circle-score" style="width: 25%; vertical-align: middle;">
                            <div class="icon-circle icon-mental">
                                <i class="fa-solid fa-brain" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Mental Clarity</p>
                        </td>
                        <td class="circle-score" style="width: 25%; vertical-align: middle;">
                            <div class="icon-circle icon-cravings">
                                <i class="fa-solid fa-apple-alt" style="font-size:22px;color:#0b5f84;"></i>
                            </div>
                            <p>Cravings</p>
                        </td>
                        <td class="circle-score" style="width: 25%; vertical-align: middle;">
                            <div class="icon-circle icon-mood">
                                <i class="fa-solid fa-face-smile" style="font-size:22px;color:#0b5f84;"></i>
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
        <td style="width: 50%; padding:0 20px; border-left: 1px solid #eee;">
            <div class="section-title">YOUR BODY IS ONE CONNECTED SYSTEM</div>

            <div style="text-align: center;">
                <?php if ( $process_img ): ?>
                    <img src="<?php echo $process_img; ?>" style="width: 100%; max-width: 208px; height: auto;" alt="Connected System" />
                <?php endif; ?>
            </div>

            <div class="middle-note">
                When <strong style="color: #0b5f84;">metabolism is out of balance</strong>, it can set off a<br>chain reaction that affects many areas of your health.
            </div>
        </td>
    </tr>
</table>

<div style="border-top: 1px solid #eee; margin: 15px 0;"></div>

<!-- ==================== ACTION STEPS ==================== -->
<div class="section-title" style="text-align: center;">YOUR FIRST THREE ACTION STEPS</div>

<table style="background: #f8f6f6; border-radius: 8px; padding: 8px; width: 100%;">
    <tr>
        <!-- Step 1 -->
        <td style="width: 33%; padding: 4px; vertical-align: top;">
            <table>
                <tr>
                    <td>
                        <div class="step-num">1</div>
                        <div style="padding-left:14px;"><i class="fa-solid fa-walking" style="font-size:42px;color:#0d5875;"></i></div>
                    </td>
                    <td style="vertical-align: top; padding-left: 10px;">
                        <div class="step-title">MOVE AFTER MEALS.</div>
                        <div class="step-desc">Even a 10-minute walk after eating can support healthy blood sugar regulation and boost your energy.</div>
                    </td>
                </tr>
            </table>
        </td>

        <!-- Step 2 -->
        <td style="width: 34%; padding: 4px; vertical-align: top;">
            <table>
                <tr>
                    <td>
                    <div class="step-num">2</div>
                    <div style="padding-left:14px;"><i class="fa-solid fa-glass-water" style="font-size:42px;color:#0d5875;"></i></div>
                    </td>

                    <td style="vertical-align: top; padding-left: 10px;">
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
                    <td>
                        <div class="step-num">3</div>
                        <div style="padding-left:14px;"><i class="fa-solid fa-bed" style="font-size:42px;color:#0d5875;"></i></div>
                    </td>
                    <td style="vertical-align: top; padding-left: 10px;">
                        <div class="step-title">PROTECT YOUR SLEEP.</div>
                        <div class="step-desc">Aim for 7–8 hours of quality sleep. Sleep impacts energy, cravings, metabolism, and recovery.</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="text-align: center; font-size: 9pt; color: #6cb33f; font-weight: bold; margin-top:10px;">
    <?php if ( $lotus_green_img ): ?>
        <img src="<?php echo $lotus_green_img; ?>" height="16" style="vertical-align: middle; margin-right: 6px;" />
    <?php endif; ?>
    Small, consistent changes are often more powerful than drastic ones.
</div>

<!-- ==================== CONSULTATION CTA BANNER ==================== -->
<div class="consult-banner">
    <table>
        <tr>
            <!-- Left: Doctor Consultation Photo -->
            <td style="width: 30%; vertical-align: middle; padding-right: 8px;">
                <?php if ( $doctor_img ): ?>
                    <img src="<?php echo $doctor_img; ?>" style="width: 200px; border-radius: 20px;" alt="Doctor Consultation" />
                <?php endif; ?>
            </td>

            <!-- Middle: Consultation Copy -->
            <td style="width: 44%; vertical-align: middle; padding-right: 8px;">
                <div class="consult-title">
                    <?php echo esc_html( $cta_headline ); ?>
                </div>

                <hr class="green">

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
            <td style="width: 26%; position: relative;">
                <div class="qr-card">
                    <h4>READY TO LEARN WHAT<br>YOUR SCORE MEANS?</h4>
                    <p>Scan to schedule your<br>complimentary consultation.</p>

                    <div style="margin: 3px 0;">
                        <?php if ( $final_qr_img ): ?>
                            <img src="<?php echo $final_qr_img; ?>" width="70" height="70" style="padding: 6px;border-radius: 18px;border: 2px solid #6cb33f;
                            box-shadow: 0 14px 30px rgba(16, 72, 108, 0.08); margin-bottom: 8px;" />
                        <?php endif; ?>
                    </div>

                    <div style="text-align: left; margin-bottom: 2px;">
                        <?php if ( $scan_arrow_img ): ?>
                            <img src="<?php echo $scan_arrow_img; ?>" style="position: absolute;left: 3px;bottom: 90px;width: 45px;height: 50px;"/>
                        <?php endif; ?>
                    </div>

                    <div class="qr-btn">SCAN TO BOOK YOUR CONSULTATION</div>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ==================== FOOTER ==================== -->
<table style="margin-top:6px; width:100%;" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:94%;" class="footer-text" valign="top">

            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="20" valign="top">
                        <i class="fa-solid fa-user-shield"
                           style="color:#0b4d67; font-size:14px;"></i>
                    </td>

                    <td valign="top" style="padding-left:4px;">
                        This Health Score is based on your responses to lifestyle and symptom questions and is intended for educational purposes only. It is not a medical diagnosis. Some health conditions, including insulin resistance and other metabolic disorders, may only be identified through laboratory testing and a comprehensive medical evaluation.
                    </td>
                </tr>
            </table>

        </td>

        <td style="width:6%; text-align:right;" valign="middle">
            <?php if ($logo_img): ?>
                <img src="<?php echo $logo_img; ?>" height="28" alt="GliaFit Logo" />
            <?php endif; ?>
        </td>
    </tr>
</table>
</div>
</body>
</html>
