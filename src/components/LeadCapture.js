import { useState } from '@wordpress/element';
const primaryColor = window.healthScoreData?.primaryColor || '#2E8B57';

const LeadCapture = ({ onSubmit, onBack, isSubmitting }) => {
    const [firstName, setFirstName] = useState('');
    const [email, setEmail] = useState('');
    const [consent, setConsent] = useState(false);
    const [error, setError] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        
        if (!firstName.trim() || !email.trim()) {
            setError('Please provide your first name and email address.');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('Please provide a valid email address.');
            return;
        }

        if (!consent) {
            setError('Please agree to receive emails to view your results.');
            return;
        }
        
        setError('');
        onSubmit({ first_name: firstName, email: email });
    };

    const inputStyle = {
        width: '100%', 
        padding: '1rem 1.25rem', 
        borderRadius: '12px', 
        border: '1px solid rgba(220, 227, 235, 0.8)', 
        fontSize: '1.05rem', 
        fontFamily: 'Outfit, sans-serif',
        backgroundColor: '#ffffff',
        color: '#1a1f36',
        boxShadow: 'inset 0 2px 4px rgba(0,0,0,0.02)',
        transition: 'all 0.2s ease',
        outline: 'none',
        boxSizing: 'border-box'
    };

    const labelStyle = {
        display: 'block', 
        marginBottom: '0.6rem', 
        fontFamily: 'Outfit, sans-serif', 
        color: '#4f566b', 
        fontWeight: '600',
        fontSize: '0.95rem'
    };

    return (
        <div style={{ padding: '1rem 0 0 0', textAlign: 'left', animation: 'fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1)', display: 'flex', flexDirection: 'column', flex: 1 }}>
            <div style={{ textAlign: 'center', marginBottom: '2rem' }}>
                <h3 style={{ color: '#1a1f36', marginBottom: '1rem', fontFamily: 'Outfit, sans-serif', fontSize: 'clamp(1.4rem, 5vw, 2rem)', fontWeight: '800', letterSpacing: '-0.5px' }}>
                    Your Personalized Health Snapshot Is Ready
                </h3>
                <p style={{ color: '#8792a2', fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(0.95rem, 3vw, 1.1rem)', lineHeight: '1.6', maxWidth: '90%', margin: '0 auto' }}>
                    Enter your first name and email to view your results and receive your Health Snapshot.
                </p>
            </div>

            <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.5rem', backgroundColor: '#ffffff', padding: 'clamp(1.5rem, 5vw, 2.5rem)', borderRadius: '20px', boxShadow: '0 15px 35px rgba(0,0,0,0.03), 0 5px 15px rgba(0,0,0,0.02)', border: '1px solid rgba(220, 227, 235, 0.5)' }}>
                
                <div>
                    <label style={labelStyle}>
                        First Name <span style={{ color: '#d9534f' }}>*</span>
                    </label>
                    <input 
                        type="text" 
                        value={firstName} 
                        onChange={(e) => setFirstName(e.target.value)}
                        placeholder="e.g., Sarah"
                        required
                        style={inputStyle}
                        onFocus={(e) => { e.target.style.borderColor = primaryColor; e.target.style.boxShadow = `0 0 0 3px ${primaryColor}20`; }}
                        onBlur={(e) => { e.target.style.borderColor = 'rgba(220, 227, 235, 0.8)'; e.target.style.boxShadow = 'inset 0 2px 4px rgba(0,0,0,0.02)'; }}
                    />
                </div>

                <div>
                    <label style={labelStyle}>
                        Email Address <span style={{ color: '#d9534f' }}>*</span>
                    </label>
                    <input 
                        type="email" 
                        value={email} 
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="e.g., sarah@example.com"
                        required
                        style={inputStyle}
                        onFocus={(e) => { e.target.style.borderColor = primaryColor; e.target.style.boxShadow = `0 0 0 3px ${primaryColor}20`; }}
                        onBlur={(e) => { e.target.style.borderColor = 'rgba(220, 227, 235, 0.8)'; e.target.style.boxShadow = 'inset 0 2px 4px rgba(0,0,0,0.02)'; }}
                    />
                </div>

                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '12px', marginTop: '0.5rem', padding: '1rem', backgroundColor: '#f8fafc', borderRadius: '12px' }}>
                    <input 
                        type="checkbox" 
                        id="consent" 
                        checked={consent} 
                        onChange={(e) => setConsent(e.target.checked)}
                        style={{ marginTop: '0.25rem', width: '22px', height: '22px', cursor: 'pointer', accentColor: primaryColor, border: 'none', boxShadow: 'none', appearance: 'auto', outline: 'none' }}
                    />
                    <label htmlFor="consent" style={{ fontSize: '0.95rem', color: '#4f566b', fontFamily: 'Lexend, sans-serif', lineHeight: '1.5', cursor: 'pointer' }}>
                        I agree to receive my assessment report and updates via email. I understand that I may unsubscribe at any time.
                    </label>
                </div>
                
                {error && (
                    <div style={{ padding: '0.75rem 1rem', backgroundColor: '#fef2f2', borderLeft: '4px solid #ef4444', borderRadius: '4px' }}>
                        <p style={{ color: '#b91c1c', margin: 0, fontSize: '0.95rem', fontWeight: '500' }}>{error}</p>
                    </div>
                )}

                <div style={{ marginTop: 'clamp(1rem, 4vw, 1.5rem)', display: 'flex', gap: '1rem', flexWrap: 'wrap' }}>
                    <button 
                        type="button"
                        onClick={onBack}
                        style={{
                            backgroundColor: 'transparent', color: '#4f566b', padding: 'clamp(0.8rem, 3vw, 1.2rem)',
                            border: '1px solid rgba(220, 227, 235, 0.8)', borderRadius: '12px', cursor: 'pointer',
                            fontSize: 'clamp(1rem, 3vw, 1.15rem)', fontFamily: 'Outfit, sans-serif', fontWeight: '600', flex: '1 1 120px',
                            transition: 'all 0.2s ease', textAlign: 'center'
                        }}
                        onMouseOver={(e) => { e.currentTarget.style.backgroundColor = '#f8fafc'; }}
                        onMouseOut={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                    >
                        Back
                    </button>

                    <button 
                        type="submit"
                        disabled={isSubmitting}
                        style={{
                            background: `linear-gradient(180deg, #009c46 0%, #004b20 100%)`, 
                            color: '#FFF', padding: 'clamp(0.8rem, 3vw, 1.2rem)',
                            border: 'none', borderRadius: '12px', cursor: isSubmitting ? 'not-allowed' : 'pointer',
                            opacity: isSubmitting ? 0.7 : 1, fontSize: 'clamp(1rem, 3.5vw, 1.15rem)', fontFamily: 'Outfit, sans-serif', 
                            fontWeight: '700', flex: '2 1 200px',
                            boxShadow: `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`,
                            transition: 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                            letterSpacing: '0.5px',
                            textShadow: '0 1px 2px rgba(0,0,0,0.2)',
                            textAlign: 'center'
                        }}
                        onMouseOver={(e) => { 
                            if (!isSubmitting) {
                                e.currentTarget.style.transform = 'scale(1.03)'; 
                                e.currentTarget.style.boxShadow = `0 15px 35px rgba(0,0,0,0.2), inset 0 2px 4px rgba(255,255,255,0.4)`; 
                            }
                        }}
                        onMouseOut={(e) => { 
                            if (!isSubmitting) {
                                e.currentTarget.style.transform = 'scale(1)'; 
                                e.currentTarget.style.boxShadow = `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`; 
                            }
                        }}
                    >
                        {isSubmitting ? 'Analyzing Results...' : 'View My Results'}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default LeadCapture;
