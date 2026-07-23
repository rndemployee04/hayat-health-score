import { useEffect, useState } from '@wordpress/element';

const btnBgTop = window.healthScoreData?.btnBgTop || '#40BAD5';
const btnBgBottom = window.healthScoreData?.btnBgBottom || '#07689F';
const btnHoverTop = window.healthScoreData?.btnHoverTop || '#99ca1d';
const btnHoverBottom = window.healthScoreData?.btnHoverBottom || '#799928';
const primaryColor = btnBgBottom;
const pluginUrl = window.healthScoreData?.pluginUrl || '';

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
    const [displayedScore, setDisplayedScore] = useState(0);
    const [animatedAngle, setAnimatedAngle] = useState(0);

    // 5 Gauge Bands with Red -> Orange -> Yellow -> Green -> Dark Green
    const bands = [
        { label: 'SIGNIFICANT OPPORTUNITY', range: '0-19', color: '#E50914', textColor: '#E50914', start: 0, end: 36, textX: 65, textY: 108 },
        { label: 'NEEDS ATTENTION', range: '20-39', color: '#FF7A00', textColor: '#FF7A00', start: 36, end: 72, textX: 142, textY: 14 },
        { label: 'FAIR', range: '40-59', color: '#FCB017', textColor: '#1E293B', start: 72, end: 108, textX: 250, textY: -18 },
        { label: 'GOOD', range: '60-79', color: '#00C853', textColor: '#00C853', start: 108, end: 144, textX: 358, textY: 14 },
        { label: 'EXCELLENT', range: '80-100', color: '#008A3B', textColor: '#008A3B', start: 144, end: 180, textX: 435, textY: 108 }
    ];

    const gapAngles = [36, 72, 108, 144];

    useEffect(() => {
        const targetScore = Math.min(100, Math.max(0, score));
        const targetAngle = (targetScore / 100) * 180;
        const duration = 1200; // 1.2s smooth needle sweep
        let startTime = null;
        let animationFrameId = null;

        const animate = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);

            // Smooth cubic ease-out curve for needle sweep
            const easeOut = 1 - Math.pow(1 - progress, 3);

            setDisplayedScore(Math.round(easeOut * targetScore));
            setAnimatedAngle(easeOut * targetAngle);

            if (progress < 1) {
                animationFrameId = requestAnimationFrame(animate);
            }
        };

        const timer = setTimeout(() => {
            animationFrameId = requestAnimationFrame(animate);
        }, 150);

        return () => {
            clearTimeout(timer);
            if (animationFrameId) cancelAnimationFrame(animationFrameId);
        };
    }, [score]);

    // Center = (250, 175), R = 140
    const needleTip = polarToCartesian(250, 175, 120, animatedAngle);
    
    // Outer caps center coordinates
    const leftEnd = polarToCartesian(250, 175, 140, 0);
    const rightEnd = polarToCartesian(250, 175, 140, 180);

    return (
        <div style={{ width: '100%', maxWidth: '390px', margin: '0 auto', fontFamily: 'Outfit, sans-serif', boxSizing: 'border-box' }}>

            {/* SVG viewBox cropped around arc */}
            <svg viewBox="25 -20 450 220" style={{ width: '100%', height: 'auto', display: 'block', overflow: 'visible' }}>
                <defs>
                    <filter id="needle-shadow" x="-30%" y="-30%" width="160%" height="160%">
                        <feDropShadow dx="0" dy="3" stdDeviation="3" floodOpacity="0.25" />
                    </filter>
                </defs>

                {/* 5 Flat Color Segments */}
                {bands.map((band, idx) => (
                    <path
                        key={idx}
                        d={describeArc(250, 175, 140, band.start, band.end)}
                        fill="none"
                        stroke={band.color}
                        strokeWidth="24"
                        strokeLinecap="butt"
                    />
                ))}

                {/* Rounded End Caps */}
                <circle cx={leftEnd.x} cy={leftEnd.y} r="12" fill="#E50914" />
                <circle cx={rightEnd.x} cy={rightEnd.y} r="12" fill="#008A3B" />

                {/* White Gap Lines to divide segments cleanly */}
                {gapAngles.map((angle, i) => {
                    const p1 = polarToCartesian(250, 175, 125, angle);
                    const p2 = polarToCartesian(250, 175, 155, angle);
                    return (
                        <line
                            key={i}
                            x1={p1.x}
                            y1={p1.y}
                            x2={p2.x}
                            y2={p2.y}
                            stroke="#ffffff"
                            strokeWidth="3.5"
                        />
                    );
                })}

                {/* Labels and Ranges */}
                {bands.map((band, idx) => {
                    const isTwoLines = band.label.includes(' ');
                    const line1 = isTwoLines ? band.label.split(' ')[0] : band.label;
                    const line2 = isTwoLines ? band.label.split(' ').slice(1).join(' ') : '';

                    return (
                        <g key={idx} transform={`translate(${band.textX}, ${band.textY})`}>
                            <text
                                textAnchor="middle"
                                fill={band.textColor}
                                fontFamily="Outfit, sans-serif"
                                fontSize="12"
                                fontWeight="700"
                            >
                                {isTwoLines ? (
                                    <>
                                        <tspan x="0" dy="-5">{line1}</tspan>
                                        <tspan x="0" dy="15">{line2}</tspan>
                                    </>
                                ) : (
                                    <tspan x="0" dy="2">{line1}</tspan>
                                )}
                            </text>

                            <text x="0" y={isTwoLines ? 30 : 25} textAnchor="middle" fill="#475569" fontFamily="Outfit, sans-serif" fontSize="14" fontWeight="700">
                                {band.range}
                            </text>
                        </g>
                    );
                })}

                {/* 0 and 100 Scale Markers perfectly aligned with baseline tips */}
                <text x="95" y="200" textAnchor="middle" fill="#1e293b" fontSize="14" fontWeight="700">0</text>
                <text x="410" y="200" textAnchor="middle" fill="#1e293b" fontSize="14" fontWeight="700">100</text>

                {/* Pointer Needle */}
                <g filter="url(#needle-shadow)">
                    <line
                        x1="250"
                        y1="175"
                        x2={needleTip.x}
                        y2={needleTip.y}
                        stroke="#1e293b"
                        strokeWidth="5"
                        strokeLinecap="round"
                    />
                    <circle cx="250" cy="175" r="9" fill="#1e293b" />
                </g>
            </svg>

            {/* Bottom Score & Status Callout Banner */}
            <div style={{ marginTop: '0.2rem', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <div style={{ display: 'flex', alignItems: 'baseline', gap: '4px' }}>
                    <span style={{ fontSize: '50px', fontWeight: '700', color: scoreColor, lineHeight: '1', fontFamily: 'Outfit, sans-serif', letterSpacing: '-1px' }}>
                        {displayedScore}
                    </span>
                    <span style={{ fontSize: '22px', fontWeight: '700', color: '#64748b', fontFamily: 'Outfit, sans-serif' }}>
                        /100
                    </span>
                </div>

                <div style={{
                    backgroundColor: scoreColor,
                    color: '#ffffff',
                    padding: '10px 24px',
                    borderRadius: '50px',
                    display: 'inline-flex',
                    alignItems: 'start',
                    justifyContent: 'center',
                    gap: '10px',
                    marginTop: '0.8rem',
                    maxWidth: '100%',
                    boxSizing: 'border-box',
                    boxShadow: `0 6px 18px ${scoreColor}40`
                }}>
                    <span style={{
                        display: 'inline-flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        marginTop: '2px',
                        width: '20px',
                        height: '20px',
                        borderRadius: '50%',
                        backgroundColor: '#ffffff',
                        color: scoreColor,
                        fontWeight: '900',
                        fontSize: '14px',
                        flexShrink: 0
                    }}>
                        !
                    </span>
                    <span style={{ fontSize: '16px', fontWeight: '700', letterSpacing: '1px', fontFamily: 'Outfit, sans-serif', textAlign: 'left' }}>
                        Your Metabolic Health : <strong>{categoryName}</strong>
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

    // Determine 5-color band based on score (matching original colors)
    let scoreColor = '#E50914'; // Red (0-19)
    let scoreCategoryName = score_category || 'Significant Opportunity';

    if (health_score >= 80) {
        scoreColor = '#008A3B'; // Excellent (Dark Green)
        scoreCategoryName = 'EXCELLENT';
    } else if (health_score >= 60) {
        scoreColor = '#00C853'; // Good (Green)
        scoreCategoryName = 'GOOD';
    } else if (health_score >= 40) {
        scoreColor = '#FCB017'; // Fair (Yellow/Orange)
        scoreCategoryName = 'FAIR';
    } else if (health_score >= 20) {
        scoreColor = '#FF7A00'; // Needs Attention (Orange)
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
        <div className="results-wrapper" style={{ padding: '0', textAlign: 'center', animation: 'fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1)' }}>
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

            <div style={{ margin: '1rem 0 1rem 0', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <GliaFitGauge score={health_score} scoreColor={scoreColor} categoryName={scoreCategoryName} />
            </div>

            <div style={{ textAlign: 'center', margin: '1.8rem 0' }}>
                <img src={pluginUrl ? `${pluginUrl}assets/images/lotus-line.png` : ''} style={{ width: '260px', margin: '0 auto' }} alt="Health Score Results" />
                <h3 style={{ color: '#0f172a', fontFamily: 'Outfit, sans-serif', margin: '20px 0 15px', fontSize: '18px', fontWeight: '700', textAlign: 'center' }}>
                    Based on What You Shared
                </h3>

                {/* Primary Goal */}
                {primary_goal && (
                    <div style={{
                        marginBottom: '1.2rem',
                        backgroundColor: '#79992817',
                        padding: '1rem 1.2rem',
                        borderRadius: '12px',
                        border: '1px solid #799928',
                        textAlign: 'center'
                    }}>
                        <p style={{ margin: '0 0 4px 0', fontSize: '16px', color: '#444', fontFamily: 'Lexend, sans-serif', fontWeight: '700', letterSpacing: '0.5px' }}>
                            ----- Your Primary Goal -----
                        </p>
                        <p style={{ margin: 0, fontSize: '20px', color: '#799928', fontFamily: 'Outfit, sans-serif', fontWeight: '700' }}>
                            {primary_goal}
                        </p>
                    </div>
                )}

                {/* Main Areas of Concern */}
                {main_concerns && main_concerns.length > 0 && (
                    <div style={{
                        marginBottom: '12px', padding: '1rem 1.2rem',
                        borderRadius: '12px',
                        border: '1px solid #ddd',
                        backgroundColor: '#fff',
                        borderLeft: `5px solid #ddd`,
                        textAlign: 'left'
                    }}>

                        <p style={{ margin: '0 auto 8px', fontSize: '16px', color: '#444', fontFamily: 'Lexend, sans-serif', fontWeight: '700', letterSpacing: '0.5px' }}>
                             ----- Your Main Areas of Concern  -----                        
                            </p>
                        <ul style={{ listStyleType: 'none', padding: 0, margin: '0 auto', display: 'flex', flexDirection: 'column', gap: '6px', maxWidth: '100%' }}>
                            {main_concerns.map((concern, idx) => (
                                <li key={idx} style={{
                                    marginBottom: '0',
                                    fontSize: '16px',
                                    fontWeight: '700',
                                    color: '#799928',
                                    fontFamily: 'Lexend, sans-serif',
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '10px'
                                }}>
                                    <span style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        width: '18px',
                                        height: '18px',
                                        borderRadius: '50%',
                                        backgroundColor: `#799928`,
                                        color: `#fff`,
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
                        textAlign: 'center'
                    }}>
                        <p style={{ margin: 0, color: '#444', fontWeight: '500', fontFamily: 'Lexend, sans-serif', fontSize: '16px', lineHeight: '1.6' }}>
                            {category_explanation}
                        </p>
                    </div>
                )}

                {/* Disclaimer */}
                <div style={{ margin: '20px 0', paddingTop: '20px', borderTop: '1px solid #8bc34a', textAlign: 'center' }}>
                    <p style={{ margin: 0, fontSize: '11px', color: '#64748b', lineHeight: '12px', fontFamily: 'Lexend, sans-serif' }}>
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
                    padding: '14px 24px',
                    border: '1px solid rgba(220, 227, 235, 0.8)',
                    borderRadius: '50px',
                    cursor: 'pointer',
                    outline: 'none',
                    fontSize: '18px',
                    fontFamily: 'Outfit, sans-serif',
                    fontWeight: '700',
                    margin: '0 auto',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    boxShadow: `0 8px 20px rgba(0,0,0,0.15)`,
                    transition: 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                    letterSpacing: '0.5px'
                }}
                onMouseOver={(e) => {
                    e.currentTarget.style.background = `linear-gradient(180deg, ${btnHoverTop} 0%, ${btnHoverBottom} 100%)`;
                }}
                onMouseOut={(e) => {
                    e.currentTarget.style.background = `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`;
                }}
            >
                Book My Complimentary Consultation
            </button>

            {onRetake && (
                <div style={{ marginTop: '10px', textAlign: 'center' }}>
                    <button
                        onClick={onRetake}
                        style={{
                            backgroundColor: 'transparent',
                            color: '#096ba1', padding: '0',
                            border: 'none',
                            outline: 'none',
                            textDecoration: 'underline',
                            cursor: 'pointer',
                            fontSize: '18px', fontFamily: 'Outfit, sans-serif',
                            fontWeight: '600',
                            transition: 'all 0.2s ease', textAlign: 'center'
                        }}
                        onMouseOver={(e) => {
                            e.currentTarget.style.color = `#000`;
                        }}
                        onMouseOut={(e) => {
                            e.currentTarget.style.color = `#096ba1`;
                        }}
                    >
                        Retake Health Assessment
                    </button>
                </div>
            )}
        </div>
    );
};

export default Results;
