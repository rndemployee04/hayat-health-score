import { useEffect } from '@wordpress/element';

const btnBgTop = window.healthScoreData?.btnBgTop || '#40BAD5';
const btnBgBottom = window.healthScoreData?.btnBgBottom || '#07689F';
const btnHoverTop = window.healthScoreData?.btnHoverTop || '#FCBF1E';
const btnHoverBottom = window.healthScoreData?.btnHoverBottom || '#F59C11';
const primaryColor = btnBgBottom;

// Convert 0° (Left) to 180° (Right) along the top semi-circle arc
function polarToCartesian(centerX, centerY, radius, deg) {
    const rad = (180 - deg) * Math.PI / 180.0;
    return {
        x: centerX + radius * Math.cos(rad),
        y: centerY - radius * Math.sin(rad)
    };
}

function describeArc(x, y, radius, startDeg, endDeg) {
    const start = polarToCartesian(x, y, radius, startDeg);
    const end = polarToCartesian(x, y, radius, endDeg);
    const largeArcFlag = endDeg - startDeg <= 180 ? "0" : "1";
    return [
        "M", start.x, start.y,
        "A", radius, radius, 0, largeArcFlag, 1, end.x, end.y
    ].join(" ");
}

const GliaFitGauge = ({ score, scoreColor, categoryName }) => {
    // 5 Gauge Bands matching GliaFit spec image
    const bands = [
        { label: 'SIGNIFICANT OPPORTUNITY', range: '0–19', color: '#E50914', start: 0, end: 35, textX: 60, textY: 108 },
        { label: 'NEEDS ATTENTION', range: '20–39', color: '#FF5722', start: 36, end: 71, textX: 142, textY: 14 },
        { label: 'FAIR', range: '40–59', color: '#FFB300', start: 72, end: 107, textX: 250, textY: -5 },
        { label: 'GOOD', range: '60–79', color: '#4CAF50', start: 108, end: 143, textX: 358, textY: 14 },
        { label: 'EXCELLENT', range: '80–100', color: '#1B7E39', start: 144, end: 180, textX: 440, textY: 108 }
    ];

    // Center = (250, 175), R = 140
    const scoreAngle = Math.min(180, Math.max(0, (score / 100) * 180));
    const needleTip = polarToCartesian(250, 175, 106, scoreAngle);

    // Ticks run from 0° to 180°
    const ticks = [];
    for (let a = 0; a <= 180; a += 3.6) {
        ticks.push(a);
    }

    return (
        <div style={{ width: '100%', maxWidth: '480px', margin: '0 auto', fontFamily: 'Outfit, sans-serif', boxSizing: 'border-box' }}>
            {/* Header Title */}
            <div style={{ fontSize: 'clamp(0.85rem, 3.5vw, 1.05rem)', fontWeight: '800', color: '#07689F', letterSpacing: '0.6px', textTransform: 'uppercase', marginBottom: '0.6rem', textAlign: 'center' }}>
                YOUR GLIAFIT METABOLIC HEALTH GAUGE™
            </div>

            {/* SVG viewBox tightly cropped around arc (25 -30 450 235) with generous top space for labels */}
            <svg viewBox="25 -30 450 235" style={{ width: '100%', height: 'auto', display: 'block', overflow: 'visible' }}>
                <defs>
                    <filter id="needle-shadow" x="-30%" y="-30%" width="160%" height="160%">
                        <feDropShadow dx="0" dy="3" stdDeviation="3" floodOpacity="0.25" />
                    </filter>
                </defs>

                {/* 5 Color Arc Bands */}
                {bands.map((band, idx) => (
                    <g key={idx}>
                        <path
                            d={describeArc(250, 175, 140, band.start, band.end)}
                            fill="none"
                            stroke={band.color}
                            strokeWidth="26"
                        />
                        {/* Text Label Above/Outside Band */}
                        <g transform={`translate(${band.textX}, ${band.textY})`}>
                            <text
                                textAnchor="middle"
                                fill={band.color}
                                fontFamily="Outfit, sans-serif"
                            >
                                {band.label.includes(' ') ? (
                                    <>
                                        <tspan x="0" dy="-7" fontSize="11" fontWeight="800">{band.label.split(' ')[0]}</tspan>
                                        <tspan x="0" dy="12" fontSize="11" fontWeight="800">{band.label.split(' ').slice(1).join(' ')}</tspan>
                                        <tspan x="0" dy="12" fill="#475569" fontSize="10" fontWeight="700">{band.range}</tspan>
                                    </>
                                ) : (
                                    <>
                                        <tspan x="0" dy="-2" fontSize="12" fontWeight="800">{band.label}</tspan>
                                        <tspan x="0" dy="13" fill="#475569" fontSize="10" fontWeight="700">{band.range}</tspan>
                                    </>
                                )}
                            </text>
                        </g>
                    </g>
                ))}

                {/* Inner Tick Arc */}
                <path
                    d={describeArc(250, 175, 124, 0, 180)}
                    fill="none"
                    stroke="#cbd5e1"
                    strokeWidth="1.5"
                />

                {/* Ticks */}
                {ticks.map((tAngle, i) => {
                    const p1 = polarToCartesian(250, 175, 124, tAngle);
                    const p2 = polarToCartesian(250, 175, (i % 5 === 0 ? 115 : 119), tAngle);
                    return (
                        <line
                            key={i}
                            x1={p1.x}
                            y1={p1.y}
                            x2={p2.x}
                            y2={p2.y}
                            stroke={i % 5 === 0 ? "#475569" : "#94a3b8"}
                            strokeWidth={i % 5 === 0 ? "1.5" : "1"}
                        />
                    );
                })}

                {/* 0 and 100 Scale Markers perfectly aligned with baseline tips */}
                <text x="110" y="194" textAnchor="middle" fill="#1e293b" fontSize="14" fontWeight="800">0</text>
                <text x="390" y="194" textAnchor="middle" fill="#1e293b" fontSize="14" fontWeight="800">100</text>

                {/* Pointer Needle */}
                <g filter="url(#needle-shadow)">
                    <line
                        x1="250"
                        y1="175"
                        x2={needleTip.x}
                        y2={needleTip.y}
                        stroke="#1e293b"
                        strokeWidth="7.5"
                        strokeLinecap="round"
                        style={{ transition: 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)' }}
                    />
                    <circle cx="250" cy="175" r="10" fill="#1e293b" />
                    <circle cx="250" cy="175" r="4" fill="#ffffff" />
                </g>
            </svg>

            {/* Bottom Score & Status Callout Banner */}
            <div style={{ marginTop: '0.2rem', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <div style={{ display: 'flex', alignItems: 'baseline', gap: '4px' }}>
                    <span style={{ fontSize: 'clamp(2rem, 6vw, 2.6rem)', fontWeight: '900', color: scoreColor, lineHeight: '1', fontFamily: 'Outfit, sans-serif', letterSpacing: '-1px' }}>
                        {score}
                    </span>
                    <span style={{ fontSize: 'clamp(1rem, 3.2vw, 1.25rem)', fontWeight: '700', color: '#64748b', fontFamily: 'Outfit, sans-serif' }}>
                        /100
                    </span>
                </div>

                <div style={{
                    backgroundColor: scoreColor,
                    color: '#ffffff',
                    padding: '8px 18px',
                    borderRadius: '50px',
                    display: 'inline-flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '8px',
                    marginTop: '0.6rem',
                    maxWidth: '96%',
                    boxSizing: 'border-box',
                    boxShadow: `0 6px 18px ${scoreColor}40`
                }}>
                    <span style={{
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        width: '20px',
                        height: '20px',
                        borderRadius: '50%',
                        backgroundColor: '#ffffff',
                        color: scoreColor,
                        fontWeight: '900',
                        fontSize: '0.85rem',
                        flexShrink: 0
                    }}>
                        !
                    </span>
                    <span style={{ fontSize: 'clamp(0.72rem, 2.6vw, 0.82rem)', fontWeight: '800', letterSpacing: '0.3px', textTransform: 'uppercase', fontFamily: 'Outfit, sans-serif', textAlign: 'center' }}>
                        YOUR METABOLIC HEALTH: <strong>{categoryName}</strong>
                    </span>
                </div>
            </div>
        </div>
    );
};

const Results = ({ scores, onRetake }) => {
    useEffect(() => {
        const el = document.querySelector('.results-wrapper');
        if (el) {
            const yOffset = -60;
            const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }, []);

    const {
        health_score,
        score_category,
        category_explanation,
        primary_goal,
        main_concerns
    } = scores;

    // Determine 5-color band based on score (matching GliaFit spec)
    let scoreColor = '#E50914'; // Red (0-19)
    let scoreCategoryName = score_category || 'Significant Opportunity';

    if (health_score >= 80) {
        scoreColor = '#1B7E39'; // Excellent (Green)
        scoreCategoryName = 'EXCELLENT';
    } else if (health_score >= 60) {
        scoreColor = '#4CAF50'; // Good (Lime Green)
        scoreCategoryName = 'GOOD';
    } else if (health_score >= 40) {
        scoreColor = '#FFB300'; // Fair (Yellow/Amber)
        scoreCategoryName = 'FAIR';
    } else if (health_score >= 20) {
        scoreColor = '#FF5722'; // Needs Attention (Orange)
        scoreCategoryName = 'NEEDS ATTENTION';
    } else {
        scoreColor = '#E50914'; // Significant Opportunity (Red)
        scoreCategoryName = 'SIGNIFICANT OPPORTUNITY';
    }

    const handleBookingRedirect = () => {
        const bookingUrl = window.healthScoreData?.bookingUrl || '#';
        window.location.href = bookingUrl;
    };

    return (
        <div className="results-wrapper" style={{ padding: 'clamp(0.5rem, 3vw, 1.8rem)', textAlign: 'center', animation: 'fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1)' }}>
            <style>{`
                @media (max-width: 480px) {
                    .results-wrapper {
                        padding: 0.5rem 0.2rem !important;
                    }
                    .cta-button {
                        border-radius: 14px !important;
                        padding: 0.9rem 1.2rem !important;
                        font-size: 1rem !important;
                        line-height: 1.3 !important;
                    }
                }
            `}</style>

            <div style={{ margin: '1rem 0 2rem 0', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <GliaFitGauge score={health_score} scoreColor={scoreColor} categoryName={scoreCategoryName} />
            </div>

            <div style={{
                textAlign: 'left',
                backgroundColor: '#ffffff',
                padding: 'clamp(1.2rem, 4vw, 2rem)',
                borderRadius: '20px',
                border: '1px solid #e2e8f0',
                marginBottom: '1.8rem',
                boxSizing: 'border-box'
            }}>
                <h3 style={{ color: '#0f172a', fontFamily: 'Outfit, sans-serif', margin: '0 0 1.2rem 0', fontSize: 'clamp(1.15rem, 3.5vw, 1.35rem)', fontWeight: '800' }}>
                    Based on What You Shared
                </h3>

                {/* Primary Goal */}
                {primary_goal && (
                    <div style={{
                        marginBottom: '1.2rem',
                        backgroundColor: '#f8fafc',
                        padding: '1rem 1.2rem',
                        borderRadius: '12px',
                        border: '1px solid #e2e8f0'
                    }}>
                        <p style={{ margin: '0 0 4px 0', fontSize: '0.8rem', color: '#64748b', fontFamily: 'Outfit, sans-serif', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                            Your Primary Goal:
                        </p>
                        <p style={{ margin: 0, fontSize: '1.05rem', color: '#0f172a', fontFamily: 'Outfit, sans-serif', fontWeight: '700' }}>
                            {primary_goal}
                        </p>
                    </div>
                )}

                {/* Main Areas of Concern */}
                {main_concerns && main_concerns.length > 0 && (
                    <div style={{ marginBottom: '1.2rem' }}>
                        <p style={{ margin: '0 0 0.8rem 0', fontSize: '0.8rem', color: '#64748b', fontFamily: 'Outfit, sans-serif', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                            Your Main Areas of Concern:
                        </p>
                        <ul style={{ listStyleType: 'none', padding: 0, margin: 0 }}>
                            {main_concerns.map((concern, idx) => (
                                <li key={idx} style={{
                                    marginBottom: '0.6rem',
                                    fontSize: '0.98rem',
                                    color: '#1e293b',
                                    fontFamily: 'Lexend, sans-serif',
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '10px'
                                }}>
                                    <span style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        width: '20px',
                                        height: '20px',
                                        borderRadius: '50%',
                                        backgroundColor: `${primaryColor}15`,
                                        color: primaryColor,
                                        fontWeight: '800',
                                        fontSize: '0.75rem',
                                        flexShrink: 0
                                    }}>
                                        ✓
                                    </span>
                                    {concern}
                                </li>
                            ))}
                        </ul>
                    </div>
                )}

                {/* Category Explanation */}
                {category_explanation && (
                    <div style={{
                        backgroundColor: '#f8fafc',
                        padding: '1rem 1.2rem',
                        borderRadius: '12px',
                        border: '1px solid #e2e8f0',
                        marginTop: '1rem'
                    }}>
                        <p style={{ margin: 0, color: '#334155', fontFamily: 'Lexend, sans-serif', fontSize: '0.92rem', lineHeight: '1.6' }}>
                            {category_explanation}
                        </p>
                    </div>
                )}

                {/* Disclaimer */}
                <div style={{ marginTop: '1.2rem', paddingTop: '1rem', borderTop: '1px solid #f1f5f9' }}>
                    <p style={{ margin: 0, fontSize: '0.8rem', color: '#64748b', fontStyle: 'italic', lineHeight: '1.5', fontFamily: 'Lexend, sans-serif' }}>
                        This Health Score is based on your responses to lifestyle and symptom questions and is intended for educational purposes only. It is not a medical diagnosis. Some health conditions, including insulin resistance and other metabolic disorders, may only be identified through laboratory testing and a comprehensive medical evaluation.
                    </p>
                </div>
            </div>

            <button
                className="cta-button"
                onClick={handleBookingRedirect}
                style={{
                    background: `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`,
                    color: '#FFF',
                    padding: 'clamp(1rem, 4vw, 1.2rem) clamp(1rem, 5vw, 2rem)',
                    border: 'none',
                    borderRadius: '50px',
                    cursor: 'pointer',
                    fontSize: 'clamp(1.05rem, 3.8vw, 1.2rem)',
                    fontFamily: 'Outfit, sans-serif',
                    width: '100%',
                    fontWeight: '700',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    boxShadow: `0 8px 20px rgba(0,0,0,0.15)`,
                    transition: 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                    letterSpacing: '0.5px'
                }}
                onMouseOver={(e) => {
                    e.currentTarget.style.background = `linear-gradient(180deg, ${btnHoverTop} 0%, ${btnHoverBottom} 100%)`;
                    e.currentTarget.style.boxShadow = `0 12px 25px rgba(245, 156, 17, 0.4)`;
                }}
                onMouseOut={(e) => {
                    e.currentTarget.style.background = `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`;
                    e.currentTarget.style.boxShadow = `0 8px 20px rgba(0,0,0,0.15)`;
                }}
            >
                Book My Complimentary Consultation
            </button>

            {onRetake && (
                <div style={{ marginTop: '1.2rem', textAlign: 'center' }}>
                    <button
                        onClick={onRetake}
                        style={{
                            background: 'transparent',
                            border: 'none',
                            color: '#64748b',
                            cursor: 'pointer',
                            fontSize: '0.95rem',
                            fontFamily: 'Lexend, sans-serif',
                            fontWeight: '600',
                            textDecoration: 'underline',
                            padding: '0.4rem 1rem',
                            transition: 'color 0.2s'
                        }}
                        onMouseOver={(e) => { e.currentTarget.style.color = '#1e293b'; }}
                        onMouseOut={(e) => { e.currentTarget.style.color = '#64748b'; }}
                    >
                        Retake Health Assessment
                    </button>
                </div>
            )}
        </div>
    );
};

export default Results;
