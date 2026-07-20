<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo esc_html($first_name); ?>, Your GliaFit Health Snapshot Is Ready</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; line-height: 1.6; }
        .email-card { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; padding: 32px; border: 1px solid #e2e8f0; }
        .email-header { text-align: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 24px; }
        .logo-text { font-size: 24px; font-weight: 900; color: #07689F; letter-spacing: -0.5px; }
        .logo-sub { font-size: 10px; font-weight: 700; color: #40BAD5; letter-spacing: 1px; text-transform: uppercase; }
        .score-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; margin: 20px 0; }
        .score-val { font-size: 36px; font-weight: 900; color: <?php echo esc_attr($score_color); ?>; line-height: 1; }
        .category-badge { display: inline-block; background-color: <?php echo esc_attr($score_color); ?>; color: #ffffff; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; margin-top: 8px; }
        .summary-box { background-color: #f1f5f9; border-radius: 10px; padding: 16px 20px; margin: 20px 0; }
        .summary-title { font-size: 12px; font-weight: 800; color: #0b2545; text-transform: uppercase; margin-bottom: 8px; }
        .summary-item { font-size: 14px; color: #334155; margin-bottom: 6px; }
        .cta-btn { display: block; width: 100%; max-width: 360px; margin: 28px auto 16px auto; text-align: center; background: linear-gradient(180deg, #40BAD5 0%, #07689F 100%); color: #ffffff !important; text-decoration: none; padding: 14px 24px; border-radius: 50px; font-weight: 800; font-size: 15px; }
        .footer-note { font-size: 11px; color: #94a3b8; text-align: center; margin-top: 32px; border-top: 1px solid #f1f5f9; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="email-header">
            <div class="logo-text">GLIAFIT</div>
            <div class="logo-sub">METABOLIC HEALTH ASSESSMENT</div>
        </div>

        <p style="font-size: 16px; font-weight: 700; color: #1e293b;">Hi <?php echo esc_html($first_name); ?>,</p>

        <p>Thank you for completing the <strong>GliaFit 60-Second Metabolic Health Assessment</strong>.</p>
        <p>Based on your responses, your GliaFit Metabolic Health Score&trade; is <strong><?php echo esc_html($health_score); ?>/100</strong>.</p>

        <!-- Score Box -->
        <div class="score-box">
            <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Your Health Snapshot</div>
            <div class="score-val"><?php echo esc_html($health_score); ?> <span style="font-size: 16px; color: #64748b;">/ 100</span></div>
            <div class="category-badge"><?php echo esc_html($status_emoji); ?> <?php echo esc_html($status_label); ?></div>
        </div>

        <!-- Intro Copy -->
        <p><?php echo esc_html($intro_copy); ?></p>

        <!-- Summary Box -->
        <div class="summary-box">
            <div class="summary-title">Based on what you shared...</div>

            <div class="summary-item"><strong>Your Primary Goal:</strong> <?php echo esc_html($primary_goal); ?></div>

            <div class="summary-item"><strong><?php echo esc_html($section_label); ?>:</strong></div>
            <ul style="margin: 4px 0 8px 20px; padding: 0; font-size: 13px; color: #475569;">
                <?php foreach ($main_concerns as $concern): ?>
                    <li><?php echo esc_html($concern); ?></li>
                <?php endforeach; ?>
            </ul>

            <?php if (!empty($biggest_challenge)): ?>
                <div class="summary-item"><strong>Your Biggest Challenge:</strong> <?php echo esc_html($biggest_challenge); ?></div>
            <?php endif; ?>

            <?php if (!empty($symptom_duration)): ?>
                <div class="summary-item"><strong><?php echo esc_html($duration_label); ?>:</strong> <?php echo esc_html($symptom_duration); ?></div>
            <?php endif; ?>
        </div>

        <!-- Dynamic Concerns Summary -->
        <p>Based on your assessment, your <?php echo esc_html($concerns_intro); ?> <strong><?php echo esc_html($concerns_summary); ?></strong>.</p>

        <!-- Body Copy -->
        <?php foreach (explode("\n\n", $body_copy) as $paragraph): ?>
            <?php if (trim($paragraph)): ?>
                <p><?php echo esc_html(trim($paragraph)); ?></p>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- CTA Section -->
        <h3 style="color: #0b2545; font-size: 16px; margin-top: 24px;">Your Next Step</h3>
        <p><?php echo esc_html($cta_intro); ?></p>

        <?php if ($show_cta_button): ?>
            <p>During your complimentary consultation, we'll:</p>
            <ul style="color: #475569; font-size: 14px;">
                <li>Review your assessment results together</li>
                <li>Explain what your score may mean</li>
                <li>Discuss your health goals</li>
                <li>Identify possible root contributors to your symptoms</li>
                <li>Answer your questions</li>
                <li>Help you determine the best next steps for your health</li>
            </ul>
            <p>There is no obligation and no pressure—just an opportunity to better understand your health and where to go from here.</p>

            <?php if (!empty($booking_url)): ?>
                <a href="<?php echo esc_url($booking_url); ?>" target="_blank" class="cta-btn"><?php echo esc_html($cta_button_text); ?></a>
            <?php endif; ?>
        <?php endif; ?>

        <p style="margin-top: 24px;"><?php echo esc_html($closing); ?></p>
        <p>Warmly,<br><strong>The GliaFit Team</strong></p>

        <div class="footer-note">
            This Health Score is based on your responses to lifestyle and symptom questions and is intended for educational purposes only. It is not a medical diagnosis. Some health conditions, including insulin resistance and other metabolic disorders, may only be identified through laboratory testing and a comprehensive medical evaluation.
            <br><br>
            <em>Powered by Bashir Neurology &amp; Headache PLLC</em>
        </div>
    </div>
</body>
</html>
