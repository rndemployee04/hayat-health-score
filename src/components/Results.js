const btnBgTop = window.healthScoreData?.btnBgTop || '#40BAD5';
const btnBgBottom = window.healthScoreData?.btnBgBottom || '#07689F';
const btnHoverTop = window.healthScoreData?.btnHoverTop || '#FCBF1E';
const btnHoverBottom = window.healthScoreData?.btnHoverBottom || '#F59C11';
const primaryColor = btnBgBottom;

const Results = ({ scores }) => {
    const {
        health_score,
        score_category,
        category_explanation,
        primary_goal,
        main_concerns
    } = scores;

    // Determine color based on score
    let scoreColor = '#d9534f'; // Default Red for < 40
    let scoreCategoryName = score_category || 'Significant Opportunity';

    if (health_score >= 85) {
        scoreColor = primaryColor; // Green
    } else if (health_score >= 70) {
        scoreColor = '#f0ad4e'; // Yellow/Orange
    } else if (health_score >= 55) {
        scoreColor = '#f0ad4e'; // Orange
    } else if (health_score >= 40) {
        scoreColor = '#d9534f'; // Red
    }

    const handleBookingRedirect = () => {
        const bookingUrl = window.healthScoreData?.bookingUrl || '#';
        window.location.href = bookingUrl;
    };

    return (
        <div style={{ padding: 'clamp(1rem, 4vw, 2rem)', textAlign: 'center', animation: 'fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1)' }}>
            <style>{`
                @media (max-width: 480px) {
                    .hide-on-mobile { display: none !important; }
                    .show-on-mobile { display: inline !important; }
                }
                @media (min-width: 481px) {
                    .show-on-mobile { display: none !important; }
                }
            `}</style>

            <h2 style={{ color: '#1a1f36', fontFamily: 'Outfit, sans-serif', fontSize: 'clamp(1.6rem, 5vw, 2.2rem)', fontWeight: '800', marginBottom: '0.5rem', letterSpacing: '-0.5px' }}>
                Your Health Score Is:
            </h2>

            <div style={{ margin: '2.5rem 0 2rem 0', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <div style={{
                    position: 'relative',
                    display: 'flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    width: '190px',
                    height: '190px',
                    borderRadius: '50%',
                    background: `conic-gradient(${scoreColor} ${health_score}%, #f0f3f6 ${health_score}%)`,
                    boxShadow: `0 20px 40px -10px ${scoreColor}40`,
                    marginBottom: '1.5rem'
                }}>
                    <div style={{
                        display: 'flex',
                        flexDirection: 'column',
                        justifyContent: 'center',
                        alignItems: 'center',
                        width: '166px',
                        height: '166px',
                        borderRadius: '50%',
                        backgroundColor: '#ffffff',
                        boxShadow: 'inset 0 4px 10px rgba(0,0,0,0.05)'
                    }}>
                        <span style={{ fontSize: '4rem', fontWeight: '800', color: '#1a1f36', lineHeight: '1', fontFamily: 'Outfit, sans-serif', letterSpacing: '-1px' }}>
                            {health_score}
                        </span>
                        <span style={{ fontSize: '0.9rem', color: '#8792a2', fontFamily: 'Lexend, sans-serif', marginTop: '0.2rem', fontWeight: '600' }}>
                            OUT OF 100
                        </span>
                    </div>
                </div>

                <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <span style={{ fontFamily: 'Lexend, sans-serif', fontSize: '1.1rem', color: '#4f566b', fontWeight: '600' }}>
                        Category:
                    </span>
                    <span style={{
                        display: 'inline-block',
                        padding: '6px 18px',
                        backgroundColor: `${scoreColor}15`,
                        color: scoreColor,
                        borderRadius: '30px',
                        fontFamily: 'Outfit, sans-serif',
                        fontSize: '1.1rem',
                        fontWeight: '700',
                        letterSpacing: '0.5px'
                    }}>
                        {scoreCategoryName}
                    </span>
                </div>
            </div>

            <div style={{
                textAlign: 'left',
                backgroundColor: '#ffffff',
                padding: 'clamp(1.5rem, 4vw, 2.5rem)',
                borderRadius: '20px',
                boxShadow: '0 15px 35px rgba(0,0,0,0.03), 0 5px 15px rgba(0,0,0,0.02)',
                border: '1px solid rgba(220, 227, 235, 0.6)',
                marginBottom: '2rem'
            }}>
                <h3 style={{ color: '#1a1f36', fontFamily: 'Outfit, sans-serif', marginBottom: '1.2rem', fontSize: 'clamp(1.2rem, 4vw, 1.4rem)', fontWeight: '700' }}>
                    Based on What You Shared
                </h3>

                {primary_goal && (
                    <div style={{ marginBottom: '1.5rem', backgroundColor: '#f8fafc', padding: '1rem 1.2rem', borderRadius: '12px' }}>
                        <p style={{ margin: 0, fontSize: '0.95rem', color: '#8792a2', fontFamily: 'Lexend, sans-serif', fontWeight: '600' }}>
                            Your primary goal:
                        </p>
                        <p style={{ margin: '0.3rem 0 0 0', fontSize: '1.1rem', color: '#1a1f36', fontFamily: 'Outfit, sans-serif', fontWeight: '700' }}>
                            {primary_goal}
                        </p>
                    </div>
                )}

                {main_concerns && main_concerns.length > 0 && (
                    <div style={{ marginBottom: '1.5rem' }}>
                        <p style={{ margin: '0 0 0.8rem 0', fontSize: '1rem', color: '#4f566b', fontFamily: 'Lexend, sans-serif', fontWeight: '600' }}>
                            Your main areas of concern:
                        </p>
                        <ul style={{ listStyleType: 'none', padding: 0, margin: 0 }}>
                            {main_concerns.map((concern, idx) => (
                                <li key={idx} style={{
                                    marginBottom: '0.6rem',
                                    fontSize: '1.05rem',
                                    color: '#1a1f36',
                                    fontFamily: 'Lexend, sans-serif',
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '10px'
                                }}>
                                    <span style={{
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'center',
                                        width: '24px',
                                        height: '24px',
                                        borderRadius: '50%',
                                        backgroundColor: `${primaryColor}15`,
                                        color: primaryColor,
                                        fontWeight: '700',
                                        fontSize: '0.9rem',
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

                {category_explanation && (
                    <div style={{ backgroundColor: '#f0f4f8', padding: '1.2rem', borderRadius: '12px', borderLeft: `4px solid ${primaryColor}` }}>
                        <p style={{ margin: 0, color: '#334155', fontFamily: 'Lexend, sans-serif', fontSize: '0.95rem', lineHeight: '1.6' }}>
                            {category_explanation}
                        </p>
                    </div>
                )}

                <div style={{ marginTop: '1.5rem', paddingTop: '1.2rem', borderTop: '1px solid #f0f3f6' }}>
                    <p style={{ margin: 0, fontSize: '0.85rem', color: '#8792a2', fontStyle: 'italic', lineHeight: '1.6', fontFamily: 'Lexend, sans-serif' }}>
                        This score is an educational snapshot based only on the answers you provided. It is not a medical diagnosis and does not replace evaluation by a qualified healthcare professional.
                    </p>
                </div>
            </div>

            <button
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
        </div>
    );
};

export default Results;
