const Results = ({ scores }) => {
    const { health_score, top_opportunities } = scores;

    // Determine color based on score
    let scoreColor = '#2E8B57'; // Green
    let scoreMessage = 'Excellent';
    if (health_score < 60) {
        scoreColor = '#d9534f'; // Red
        scoreMessage = 'Action Needed';
    } else if (health_score < 80) {
        scoreColor = '#f0ad4e'; // Orange
        scoreMessage = 'Room for Improvement';
    }

    const handleBookingRedirect = () => {
        const bookingUrl = window.hayatHealthData?.bookingUrl || '/book-consultation';
        window.location.href = bookingUrl;
    };

    return (
        <div style={{ padding: '2rem', textAlign: 'center', animation: 'fadeIn 0.6s ease-in-out' }}>
            <h2 style={{ color: '#333', fontFamily: 'Outfit, sans-serif', fontSize: '2rem', marginBottom: '0.5rem' }}>
                Your Hayat Tayyiba Health Score
            </h2>
            
            <div style={{ margin: '3rem 0' }}>
                <div style={{
                    display: 'inline-flex',
                    flexDirection: 'column',
                    justifyContent: 'center',
                    alignItems: 'center',
                    width: '180px',
                    height: '180px',
                    borderRadius: '50%',
                    border: `8px solid ${scoreColor}`,
                    backgroundColor: '#fff',
                    boxShadow: '0 8px 16px rgba(0,0,0,0.1)'
                }}>
                    <span style={{ fontSize: '4rem', fontWeight: 'bold', color: scoreColor, lineHeight: '1' }}>
                        {health_score}
                    </span>
                    <span style={{ fontSize: '1rem', color: '#666', fontFamily: 'Lexend, sans-serif', marginTop: '0.5rem', fontWeight: 'bold' }}>
                        / 100
                    </span>
                </div>
                <h3 style={{ marginTop: '1.5rem', color: scoreColor, fontFamily: 'Outfit, sans-serif' }}>
                    {scoreMessage}
                </h3>
            </div>

            <div style={{ textAlign: 'left', backgroundColor: '#fff', padding: '1.5rem', borderRadius: '8px', border: '1px solid #DCD7C9', marginBottom: '2rem' }}>
                <h4 style={{ color: '#2E8B57', fontFamily: 'Outfit, sans-serif', marginBottom: '1rem', fontSize: '1.2rem' }}>
                    Top Focus Areas:
                </h4>
                <ul style={{ margin: 0, paddingLeft: '1.5rem', fontFamily: 'Lexend, sans-serif', color: '#4A4A4A', lineHeight: '1.6' }}>
                    {top_opportunities.map((opp, index) => (
                        <li key={index} style={{ marginBottom: '0.5rem' }}>{opp}</li>
                    ))}
                </ul>
                <p style={{ marginTop: '1.5rem', fontSize: '0.95rem', color: '#666', fontStyle: 'italic' }}>
                    *A detailed copy of your results is being generated and will arrive in your email inbox within the next 5 minutes.
                </p>
            </div>

            <button 
                onClick={handleBookingRedirect}
                style={{
                    backgroundColor: '#2E8B57',
                    color: '#FFF',
                    padding: '1rem 2rem',
                    border: 'none',
                    borderRadius: '6px',
                    cursor: 'pointer',
                    fontSize: '1.2rem',
                    fontFamily: 'Outfit, sans-serif',
                    width: '100%',
                    fontWeight: 'bold',
                    boxShadow: '0 4px 6px rgba(46, 139, 87, 0.2)',
                    transition: 'transform 0.2s ease, box-shadow 0.2s ease'
                }}
                onMouseOver={(e) => { e.currentTarget.style.transform = 'translateY(-2px)'; e.currentTarget.style.boxShadow = '0 6px 12px rgba(46, 139, 87, 0.3)'; }}
                onMouseOut={(e) => { e.currentTarget.style.transform = 'translateY(0)'; e.currentTarget.style.boxShadow = '0 4px 6px rgba(46, 139, 87, 0.2)'; }}
            >
                Book Your Complimentary Consultation
            </button>
        </div>
    );
};

export default Results;
