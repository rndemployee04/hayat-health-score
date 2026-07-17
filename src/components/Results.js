const primaryColor = window.hayatHealthData?.primaryColor || '#2E8B57';

const Results = ({ scores }) => {
    const { health_score, top_opportunities } = scores;

    // Determine color based on score
    let scoreColor = '#d9534f'; // Default Red for < 40
    let scoreMessage = 'Significant Opportunity';
    if (health_score >= 85) {
        scoreColor = primaryColor; // Green
        scoreMessage = 'Excellent';
    } else if (health_score >= 70) {
        scoreColor = '#f0ad4e'; // Orange/Yellow
        scoreMessage = 'Good';
    } else if (health_score >= 55) {
        scoreColor = '#f0ad4e'; // Orange
        scoreMessage = 'Fair';
    } else if (health_score >= 40) {
        scoreColor = '#d9534f'; // Red
        scoreMessage = 'Needs Attention';
    }

    const handleBookingRedirect = () => {
        const bookingUrl = window.hayatHealthData?.bookingUrl || '/book-consultation';
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
            <h2 style={{ color: '#1a1f36', fontFamily: 'Outfit, sans-serif', fontSize: 'clamp(1.8rem, 5vw, 2.4rem)', fontWeight: '700', marginBottom: '0.5rem', letterSpacing: '-0.5px' }}>
                Your Health Snapshot
            </h2>

            <div style={{ margin: '3.5rem 0 2.5rem 0', display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
                <div style={{
                    position: 'relative',
                    display: 'flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    width: '200px',
                    height: '200px',
                    borderRadius: '50%',
                    background: `conic-gradient(${scoreColor} ${health_score}%, #f0f3f6 ${health_score}%)`,
                    boxShadow: `0 20px 40px -10px ${scoreColor}40`,
                    marginBottom: '2rem'
                }}>
                    <div style={{
                        display: 'flex',
                        flexDirection: 'column',
                        justifyContent: 'center',
                        alignItems: 'center',
                        width: '176px',
                        height: '176px',
                        borderRadius: '50%',
                        backgroundColor: '#ffffff',
                        boxShadow: 'inset 0 4px 10px rgba(0,0,0,0.05)'
                    }}>
                        <span style={{ fontSize: '4.5rem', fontWeight: '800', color: '#1a1f36', lineHeight: '1', fontFamily: 'Outfit, sans-serif', letterSpacing: '-1px' }}>
                            {health_score}
                        </span>
                        <span style={{ fontSize: '1rem', color: '#8792a2', fontFamily: 'Lexend, sans-serif', marginTop: '0.2rem', fontWeight: '600' }}>
                            OUT OF 100
                        </span>
                    </div>
                </div>
                
                <span style={{ 
                    display: 'inline-block',
                    padding: '8px 24px', 
                    backgroundColor: `${scoreColor}15`, 
                    color: scoreColor, 
                    borderRadius: '30px',
                    fontFamily: 'Outfit, sans-serif', 
                    fontSize: '1.2rem',
                    fontWeight: '700',
                    textTransform: 'uppercase',
                    letterSpacing: '1px'
                }}>
                    {scoreMessage}
                </span>
            </div>

            <div style={{ 
                textAlign: 'left', 
                backgroundColor: '#ffffff', 
                padding: '2.5rem', 
                borderRadius: '20px', 
                boxShadow: '0 15px 35px rgba(0,0,0,0.03), 0 5px 15px rgba(0,0,0,0.02)',
                border: '1px solid rgba(220, 227, 235, 0.5)', 
                marginBottom: '2.5rem' 
            }}>
                <h4 style={{ color: '#1a1f36', fontFamily: 'Outfit, sans-serif', marginBottom: '1.5rem', fontSize: 'clamp(1.1rem, 4vw, 1.25rem)', fontWeight: '700' }}>
                    Based on what you shared, your biggest opportunities appear to be:
                </h4>
                <ul style={{ listStyleType: 'none', padding: 0, margin: 0 }}>
                    {top_opportunities.map((opp, idx) => (
                        <li key={idx} style={{ 
                            marginBottom: '1rem', 
                            fontSize: 'clamp(1rem, 3.5vw, 1.15rem)', 
                            color: '#4f566b', 
                            fontFamily: 'Lexend, sans-serif', 
                            display: 'flex', 
                            alignItems: 'center',
                            gap: '12px'
                        }}>
                            <span style={{ 
                                display: 'flex', 
                                alignItems: 'center', 
                                justifyContent: 'center', 
                                width: '28px', 
                                height: '28px', 
                                borderRadius: '50%', 
                                backgroundColor: `${primaryColor}15`, 
                                color: primaryColor,
                                flexShrink: 0
                            }}>
                                ✓
                            </span>
                            {opp}
                        </li>
                    ))}
                </ul>
                <div style={{ marginTop: '2rem', paddingTop: '1.5rem', borderTop: '1px solid #f0f3f6' }}>
                    <p style={{ margin: 0, fontSize: '0.9rem', color: '#8792a2', fontStyle: 'italic', lineHeight: '1.6' }}>
                        This score is an educational snapshot based on your responses. It is not a medical diagnosis.<br /><br />
                        *A detailed copy of your results is being generated and will arrive in your email inbox within the next 5 minutes.
                    </p>
                </div>
            </div>

            <button
                onClick={handleBookingRedirect}
                style={{
                    background: `linear-gradient(180deg, #009c46 0%, #004b20 100%)`,
                    color: '#FFF',
                    padding: 'clamp(1rem, 4vw, 1.2rem) clamp(1rem, 5vw, 2.5rem)',
                    border: 'none',
                    borderRadius: '12px',
                    cursor: 'pointer',
                    fontSize: 'clamp(1.1rem, 4vw, 1.25rem)',
                    fontFamily: 'Outfit, sans-serif',
                    width: '100%',
                    fontWeight: '700',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '12px',
                    boxShadow: `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`,
                    transition: 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                    letterSpacing: '0.5px',
                    textShadow: '0 1px 2px rgba(0,0,0,0.2)'
                }}
                onMouseOver={(e) => { 
                    e.currentTarget.style.transform = 'scale(1.03)'; 
                    e.currentTarget.style.boxShadow = `0 15px 35px rgba(0,0,0,0.2), inset 0 2px 4px rgba(255,255,255,0.4)`; 
                }}
                onMouseOut={(e) => { 
                    e.currentTarget.style.transform = 'scale(1)'; 
                    e.currentTarget.style.boxShadow = `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`; 
                }}
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 448 512" fill="currentColor">
                    <path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm64 80v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm128 0v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H208c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H336zM64 400v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H208zm112 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H336c-8.8 0-16 7.2-16 16z"/>
                </svg>
                <span>
                    <span className="hide-on-mobile">Book My Consultation</span>
                    <span className="show-on-mobile">Book Session</span>
                </span>
            </button>
        </div>
    );
};

export default Results;
