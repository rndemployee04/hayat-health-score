<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>GliaFit Health Snapshot</title>
<style>
@page { margin: 16px 20px; size: A4 portrait; }
body { font-family: Helvetica, Arial, sans-serif; color: #1a2744; margin: 0; padding: 0; font-size: 9px; line-height: 1.3; }
table { border-collapse: collapse; width: 100%; }
td { vertical-align: top; }

/* Section titles */
.sec-title { font-size: 10px; font-weight: bold; color: #0a3d6b; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 6px; }
.sec-title-accent { color: <?php echo esc_attr($accent_color); ?>; }

/* Divider */
.hr { border-top: 1px solid #dce3eb; margin: 8px 0; }

/* Status badge */
.badge { color: #fff; font-size: 9px; font-weight: bold; padding: 5px 12px; border-radius: 6px; display: inline-block; }

/* Icon circles for "What Your Score Means" */
.meaning-icon { width: 28px; height: 28px; border-radius: 50%; text-align: center; line-height: 28px; color: #fff; font-weight: bold; font-size: 14px; }

/* Concern icon circles */
.concern-circle { width: 38px; height: 38px; border-radius: 50%; border: 2px solid #0a3d6b; text-align: center; line-height: 34px; color: #0a3d6b; font-size: 16px; margin: 0 auto 3px auto; background: #f0f6fb; }

/* Step number */
.step-circle { width: 22px; height: 22px; border-radius: 50%; background: #00875a; color: #fff; font-weight: bold; font-size: 11px; text-align: center; line-height: 22px; display: inline-block; }

/* Bottom banner */
.banner { background-color: #0a3d6b; color: #ffffff; border-radius: 10px; padding: 10px 12px; }
.banner-check { color: #00cc66; font-weight: bold; }

/* QR card */
.qr-card { background: #ffffff; border-radius: 8px; padding: 8px; text-align: center; color: #0a3d6b; }
.qr-btn { background: #00875a; color: #fff; font-size: 8px; font-weight: bold; padding: 4px 8px; border-radius: 4px; display: inline-block; margin-top: 4px; }
</style>
</head>
<body>

<!-- ========== HEADER ========== -->
<table>
    <tr>
        <td style="width:22%; vertical-align:middle;">
            <div style="font-size:18px; font-weight:bold; color:#00875a; letter-spacing:-0.5px;">GLIA<span style="color:#0a3d6b;">FIT</span></div>
        </td>
        <td style="width:56%; text-align:center; vertical-align:middle;">
            <div style="font-size:10px; font-weight:bold; color:#0a3d6b;">YOUR GLIAFIT</div>
            <div style="font-size:22px; font-weight:bold; color:#0a3d6b; letter-spacing:0.5px;">HEALTH SNAPSHOT</div>
            <div style="font-size:8px; color:#5a6a7a; font-weight:bold; margin-top:3px;"><?php echo esc_html($main_headline); ?></div>
        </td>
        <td style="width:22%; text-align:right; vertical-align:middle;">
            <div style="font-size:9px; font-weight:bold; color:#0a3d6b; line-height:1.3;">FEEL BETTER.<br>LIVE BETTER.<br><span style="color:#00875a;">GET YOUR<br>LIFE BACK.</span></div>
        </td>
    </tr>
</table>

<div class="hr"></div>

<!-- ========== GAUGE + WHAT YOUR SCORE MEANS ========== -->
<table>
    <tr>
        <!-- Left: Gauge -->
        <td style="width:52%; padding-right:12px;">
            <div class="sec-title">YOUR GLIAFIT METABOLIC HEALTH GAUGE&trade;</div>
            <div style="text-align:center;">
                <!-- SVG Gauge -->
                <svg width="260" height="140" viewBox="0 0 500 240" xmlns="http://www.w3.org/2000/svg">
                    <!-- 5 Arc Bands -->
                    <path d="M 60,210 A 190,190 0 0,1 110,72" fill="none" stroke="#E50914" stroke-width="28" stroke-linecap="butt"/>
                    <path d="M 113,68 A 190,190 0 0,1 192,18" fill="none" stroke="#FF6D00" stroke-width="28" stroke-linecap="butt"/>
                    <path d="M 196,16 A 190,190 0 0,1 304,16" fill="none" stroke="#FFB300" stroke-width="28" stroke-linecap="butt"/>
                    <path d="M 308,18 A 190,190 0 0,1 387,68" fill="none" stroke="#43A047" stroke-width="28" stroke-linecap="butt"/>
                    <path d="M 390,72 A 190,190 0 0,1 440,210" fill="none" stroke="#1B5E20" stroke-width="28" stroke-linecap="butt"/>

                    <!-- Band Labels -->
                    <text x="40" y="115" font-size="9" font-weight="bold" fill="#1B5E20" text-anchor="start">EXCELLENT</text>
                    <text x="40" y="125" font-size="8" fill="#5a6a7a" text-anchor="start">80-100</text>
                    <text x="120" y="42" font-size="9" font-weight="bold" fill="#43A047" text-anchor="start">GOOD</text>
                    <text x="120" y="52" font-size="8" fill="#5a6a7a" text-anchor="start">60-79</text>
                    <text x="218" y="14" font-size="9" font-weight="bold" fill="#FFB300" text-anchor="middle">FAIR</text>
                    <text x="218" y="24" font-size="8" fill="#5a6a7a" text-anchor="middle">40-59</text>
                    <text x="340" y="42" font-size="9" font-weight="bold" fill="#FF6D00" text-anchor="middle">NEEDS</text>
                    <text x="340" y="52" font-size="8" font-weight="bold" fill="#FF6D00" text-anchor="middle">ATTENTION</text>
                    <text x="340" y="62" font-size="8" fill="#5a6a7a" text-anchor="middle">20-39</text>
                    <text x="430" y="90" font-size="9" font-weight="bold" fill="#E50914" text-anchor="end">SIGNIFICANT</text>
                    <text x="430" y="100" font-size="9" font-weight="bold" fill="#E50914" text-anchor="end">OPPORTUNITY</text>
                    <text x="430" y="110" font-size="8" fill="#5a6a7a" text-anchor="end">0-19</text>

                    <!-- 0 and 100 labels -->
                    <text x="50" y="228" font-size="13" font-weight="bold" fill="#5a6a7a">0</text>
                    <text x="440" y="228" font-size="13" font-weight="bold" fill="#5a6a7a">100</text>

                    <!-- Needle -->
                    <?php
                        $cx = 250; $cy = 210; $nr = 140;
                        $rad = (180 - $needle_angle) * M_PI / 180.0;
                        $tipX = $cx + $nr * cos($rad);
                        $tipY = $cy - $nr * sin($rad);
                    ?>
                    <line x1="<?php echo $cx; ?>" y1="<?php echo $cy; ?>" x2="<?php echo round($tipX,1); ?>" y2="<?php echo round($tipY,1); ?>" stroke="#1a2744" stroke-width="6" stroke-linecap="round"/>
                    <circle cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="10" fill="#1a2744"/>
                    <circle cx="<?php echo $cx; ?>" cy="<?php echo $cy; ?>" r="4" fill="#ffffff"/>
                </svg>
            </div>

            <!-- Score Number -->
            <div style="text-align:center; margin-top:-8px;">
                <span style="font-size:42px; font-weight:bold; color:<?php echo esc_attr($score_color); ?>;"><?php echo esc_html($health_score); ?></span>
                <span style="font-size:18px; font-weight:bold; color:#5a6a7a;">/100</span>
            </div>

            <!-- Status Badge -->
            <div style="text-align:center; margin-top:4px;">
                <div class="badge" style="background-color:<?php echo esc_attr($score_color); ?>;">
                    YOUR CURRENT METABOLIC HEALTH STATUS: <?php echo esc_html($score_category); ?>
                </div>
            </div>

            <!-- Hope Statement -->
            <div style="text-align:left; margin-top:8px; font-size:9px; color:#00875a; font-weight:bold;">
                <span style="color:#00875a; font-size:12px;">&#9825;</span> <?php echo esc_html($hope_statement); ?>
            </div>
        </td>

        <!-- Right: What Your Score Means -->
        <td style="width:48%; padding-left:12px; border-left:1px solid #dce3eb;">
            <div class="sec-title sec-title-accent">WHAT YOUR SCORE MEANS</div>

            <!-- Item 1: Red warning -->
            <table style="margin-bottom:8px;">
                <tr>
                    <td style="width:36px; vertical-align:top; padding-top:2px;">
                        <div class="meaning-icon" style="background:<?php echo esc_attr($accent_color); ?>;">!</div>
                    </td>
                    <td style="font-size:9px; color:#333; line-height:1.4; padding-left:6px;">
                        <?php echo esc_html($score_means); ?>
                    </td>
                </tr>
            </table>

            <!-- Item 2: Status paragraph -->
            <table style="margin-bottom:8px;">
                <tr>
                    <td style="width:36px; vertical-align:top; padding-top:2px;">
                        <div class="meaning-icon" style="background:<?php echo esc_attr($accent_color); ?>; font-size:11px;">&#9744;</div>
                    </td>
                    <td style="font-size:9px; color:#333; line-height:1.4; padding-left:6px;">
                        <?php echo esc_html($status_paragraph); ?>
                    </td>
                </tr>
            </table>

            <!-- Item 3: Green heart -->
            <table style="margin-bottom:4px;">
                <tr>
                    <td style="width:36px; vertical-align:top; padding-top:2px;">
                        <div class="meaning-icon" style="background:#00875a; font-size:14px;">&#9829;</div>
                    </td>
                    <td style="font-size:9px; color:#00875a; line-height:1.4; padding-left:6px;">
                        <strong>The encouraging news</strong> is that <strong>meaningful improvement is possible</strong> with the right plan and support.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="hr"></div>

<!-- ========== AFFECTING + CONNECTED SYSTEM ========== -->
<table>
    <tr>
        <!-- Left: What Your Score May Be Affecting -->
        <td style="width:48%; padding-right:12px;">
            <div class="sec-title">WHAT YOUR SCORE MAY BE AFFECTING</div>
            <table style="margin-top:4px;">
                <tr>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#9889;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Energy</div>
                    </td>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#9790;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Sleep</div>
                    </td>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#9878;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Weight</div>
                    </td>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#10047;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Blood Sugar</div>
                    </td>
                </tr>
                <tr>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#9829;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Blood Pressure</div>
                    </td>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#10024;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Mental Clarity</div>
                    </td>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#10070;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Cravings</div>
                    </td>
                    <td style="width:25%; text-align:center; padding:4px;">
                        <div class="concern-circle">&#9786;</div>
                        <div style="font-size:8px; font-weight:bold; color:#333;">Mood</div>
                    </td>
                </tr>
            </table>
            <div style="font-size:8px; color:#5a6a7a; text-align:center; margin-top:4px;">
                These concerns often <strong>influence one another</strong> because they can share common underlying contributors.
            </div>
        </td>

        <!-- Right: Connected System -->
        <td style="width:52%; padding-left:12px; border-left:1px solid #dce3eb;">
            <div class="sec-title">YOUR BODY IS ONE CONNECTED SYSTEM</div>
            <div style="background:#f8fafb; border-radius:8px; padding:10px; text-align:center; border:1px solid #e8edf2;">
                <!-- Simple radial layout using a table -->
                <table style="margin:0 auto; width:200px;">
                    <tr>
                        <td style="text-align:center;" colspan="3">
                            <div style="width:32px; height:32px; border-radius:50%; background:#1a3a5c; color:#fff; font-size:10px; line-height:32px; margin:0 auto; font-weight:bold;">&#9790;</div>
                            <div style="font-size:7px; font-weight:bold; color:#333;">Sleep</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; padding:4px 0;">
                            <div style="width:32px; height:32px; border-radius:50%; background:#00875a; color:#fff; font-size:12px; line-height:32px; margin:0 auto;">&#9889;</div>
                            <div style="font-size:7px; font-weight:bold; color:#333;">Energy</div>
                        </td>
                        <td style="text-align:center; padding:4px 8px;">
                            <div style="width:50px; height:50px; border-radius:50%; border:2px solid #0a3d6b; background:#fff; line-height:46px; font-weight:bold; font-size:7px; color:#0a3d6b; margin:0 auto;">METABOLISM</div>
                        </td>
                        <td style="text-align:center; padding:4px 0;">
                            <div style="width:32px; height:32px; border-radius:50%; background:#00875a; color:#fff; font-size:10px; line-height:32px; margin:0 auto;">&#9878;</div>
                            <div style="font-size:7px; font-weight:bold; color:#333;">Weight</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <div style="width:32px; height:32px; border-radius:50%; background:#ff6d00; color:#fff; font-size:12px; line-height:32px; margin:0 auto;">&#10047;</div>
                            <div style="font-size:7px; font-weight:bold; color:#333;">Cravings</div>
                        </td>
                        <td></td>
                        <td style="text-align:center;">
                            <div style="width:32px; height:32px; border-radius:50%; background:#40bad5; color:#fff; font-size:12px; line-height:32px; margin:0 auto;">&#10070;</div>
                            <div style="font-size:7px; font-weight:bold; color:#333;">Blood Sugar</div>
                        </td>
                    </tr>
                </table>
                <div style="font-size:8px; color:#5a6a7a; margin-top:4px;">
                    When <strong>metabolism is out of balance</strong>, it can set off a chain reaction that affects many areas of your health.
                </div>
            </div>
        </td>
    </tr>
</table>

<div class="hr"></div>

<!-- ========== YOUR FIRST THREE ACTION STEPS ========== -->
<div class="sec-title" style="text-align:center;">YOUR FIRST THREE ACTION STEPS</div>
<table style="border:1px solid #e8edf2; border-radius:8px; padding:6px;">
    <tr>
        <td style="width:33%; padding:4px 8px; vertical-align:top;">
            <table><tr>
                <td style="width:28px; vertical-align:top;"><span class="step-circle">1</span></td>
                <td style="padding-left:4px;">
                    <div style="font-size:9px; font-weight:bold; color:#00875a; text-transform:uppercase;">MOVE AFTER MEALS.</div>
                    <div style="font-size:8px; color:#444; margin-top:2px;">Even a 10-minute walk after eating can support healthy blood sugar regulation and boost your energy.</div>
                </td>
            </tr></table>
        </td>
        <td style="width:33%; padding:4px 8px; vertical-align:top; border-left:1px solid #e8edf2; border-right:1px solid #e8edf2;">
            <table><tr>
                <td style="width:28px; vertical-align:top;"><span class="step-circle">2</span></td>
                <td style="padding-left:4px;">
                    <div style="font-size:9px; font-weight:bold; color:#00875a; text-transform:uppercase;">HYDRATE SMARTER.</div>
                    <div style="font-size:8px; color:#444; margin-top:2px;">Choose water instead of sugary drinks whenever possible. Small swaps make a big difference in your metabolism.</div>
                </td>
            </tr></table>
        </td>
        <td style="width:33%; padding:4px 8px; vertical-align:top;">
            <table><tr>
                <td style="width:28px; vertical-align:top;"><span class="step-circle">3</span></td>
                <td style="padding-left:4px;">
                    <div style="font-size:9px; font-weight:bold; color:#00875a; text-transform:uppercase;">PROTECT YOUR SLEEP.</div>
                    <div style="font-size:8px; color:#444; margin-top:2px;">Aim for 7-8 hours of quality sleep. Sleep impacts energy, cravings, metabolism, and recovery.</div>
                </td>
            </tr></table>
        </td>
    </tr>
</table>

<div style="text-align:center; font-size:9px; color:#00875a; font-weight:bold; margin:6px 0;">
    Small, consistent changes are often more powerful than drastic ones.
</div>

<!-- ========== BOTTOM CTA BANNER ========== -->
<div class="banner">
    <table>
        <tr>
            <!-- Left: CTA Content -->
            <td style="width:60%; vertical-align:middle; padding-right:10px;">
                <div style="font-size:14px; font-weight:bold; color:#00cc66;"><?php echo esc_html($cta_headline); ?></div>
                <div style="font-size:8px; color:#b0c4de; margin:4px 0 6px 0;"><?php echo esc_html($consultation_copy); ?></div>
                <div style="font-size:7px; color:#fff; background:rgba(255,255,255,0.15); padding:3px 8px; border-radius:10px; display:inline-block;">
                    &#9829; THERE IS NO OBLIGATION&mdash;JUST CLARITY ABOUT YOUR HEALTH.
                </div>
            </td>

            <!-- Right: QR Code Card -->
            <td style="width:40%; vertical-align:middle;">
                <div class="qr-card">
                    <div style="font-size:9px; font-weight:bold; color:#0a3d6b; text-transform:uppercase;">READY TO LEARN WHAT YOUR SCORE MEANS?</div>
                    <div style="font-size:7px; color:#5a6a7a; margin:3px 0 6px 0;">Scan to schedule your complimentary consultation.</div>
                    <?php if (!empty($qr_code_url)): ?>
                        <img src="<?php echo esc_url($qr_code_url); ?>" width="70" height="70" style="border:1px solid #e8edf2; border-radius:4px; padding:2px;"/>
                    <?php endif; ?>
                    <div class="qr-btn">SCAN TO BOOK YOUR CONSULTATION</div>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- ========== FOOTER ========== -->
<table style="margin-top:6px;">
    <tr>
        <td style="width:82%; font-size:6.5px; color:#7a8a9a; vertical-align:middle; line-height:1.3;">
            This Health Score is based on your responses to lifestyle and symptom questions and is intended for educational purposes only. It is not a medical diagnosis. Some health conditions, including insulin resistance and other metabolic disorders, may only be identified through laboratory testing and a comprehensive medical evaluation.
        </td>
        <td style="width:18%; text-align:right; vertical-align:middle;">
            <span style="font-size:10px; font-weight:bold; color:#00875a;">GLIA</span><span style="font-size:10px; font-weight:bold; color:#0a3d6b;">FIT</span>
        </td>
    </tr>
</table>

</body>
</html>
