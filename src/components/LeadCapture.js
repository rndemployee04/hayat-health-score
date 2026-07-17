import { useState } from '@wordpress/element';
const primaryColor = window.hayatHealthData?.primaryColor || '#2E8B57';

const LeadCapture = ({ onSubmit, onBack, isSubmitting }) => {
    const [firstName, setFirstName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [consent, setConsent] = useState(false);
    const [error, setError] = useState('');

    const handleSubmit = (e) => {
        e.preventDefault();
        
        if (!firstName.trim() || !email.trim()) {
            setError('Please provide your name and email.');
            return;
        }

        // Basic email validation
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setError('Please provide a valid email address.');
            return;
        }
        if (!consent) {
            setError('Please agree to receive emails to continue.');
            return;
        }
        
        setError('');
        onSubmit({ first_name: firstName, email: email, phone: phone });
    };

    return (
        <div style={{ padding: '1rem 0 0 0', textAlign: 'left', animation: 'fadeIn 0.4s ease-in-out', display: 'flex', flexDirection: 'column', flex: 1 }}>
            <h3 style={{ color: primaryColor, marginBottom: '1rem', fontFamily: 'Outfit, sans-serif', fontSize: '1.6rem' }}>
                Receive Your Personalized Hayat Tayyiba Health Snapshot
            </h3>
            <p style={{ marginBottom: '2rem', color: '#4A4A4A', fontFamily: 'Lexend, sans-serif', fontSize: '1rem', lineHeight: '1.5' }}>
                You're almost there! Enter your details below to see your Health Score and receive your customized action plan.
            </p>

            <form onSubmit={handleSubmit} style={{ display: 'flex', flexDirection: 'column', gap: '1.25rem' }}>
                
                <div>
                    <label style={{ display: 'block', marginBottom: '0.5rem', fontFamily: 'Lexend, sans-serif', color: '#333', fontWeight: 'bold' }}>
                        First Name <span style={{ color: 'red' }}>*</span>
                    </label>
                    <input 
                        type="text" 
                        value={firstName} 
                        onChange={(e) => setFirstName(e.target.value)}
                        placeholder="e.g., John"
                        required
                        style={{ width: '100%', padding: '0.75rem', borderRadius: '4px', border: '1px solid #DCD7C9', fontSize: '1rem', fontFamily: 'Outfit, sans-serif' }}
                    />
                </div>

                <div>
                    <label style={{ display: 'block', marginBottom: '0.5rem', fontFamily: 'Lexend, sans-serif', color: '#333', fontWeight: 'bold' }}>
                        Email Address <span style={{ color: 'red' }}>*</span>
                    </label>
                    <input 
                        type="email" 
                        value={email} 
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="e.g., john@example.com"
                        required
                        style={{ width: '100%', padding: '0.75rem', borderRadius: '4px', border: '1px solid #DCD7C9', fontSize: '1rem', fontFamily: 'Outfit, sans-serif' }}
                    />
                </div>

                <div>
                    <label style={{ display: 'block', marginBottom: '0.5rem', fontFamily: 'Lexend, sans-serif', color: '#333', fontWeight: 'bold' }}>
                        Mobile Phone (Optional)
                    </label>
                    <input 
                        type="tel" 
                        value={phone} 
                        onChange={(e) => setPhone(e.target.value)}
                        placeholder="e.g., 555-0123"
                        style={{ width: '100%', padding: '0.75rem', borderRadius: '4px', border: '1px solid #DCD7C9', fontSize: '1rem', fontFamily: 'Outfit, sans-serif' }}
                    />
                </div>

                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '0.5rem', marginTop: '0.5rem' }}>
                    <input 
                        type="checkbox" 
                        id="consent" 
                        checked={consent} 
                        onChange={(e) => setConsent(e.target.checked)}
                        style={{ marginTop: '0.25rem' }}
                    />
                    <label htmlFor="consent" style={{ fontSize: '0.9rem', color: '#666', fontFamily: 'Lexend, sans-serif', lineHeight: '1.4' }}>
                        I agree to receive emails from Hayat Tayyiba. I understand I can unsubscribe at any time.
                    </label>
                </div>
                
                {error && <p style={{ color: 'red', margin: 0, fontSize: '0.9rem' }}>{error}</p>}

                <div style={{ marginTop: '1rem', display: 'flex', gap: '1rem' }}>
                    <button 
                        type="button"
                        onClick={onBack}
                        style={{
                            backgroundColor: '#fff', color: '#4A4A4A', padding: '0.75rem 1.5rem',
                            border: '1px solid #DCD7C9', borderRadius: '4px', cursor: 'pointer',
                            fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif', flex: 1
                        }}
                    >
                        Back
                    </button>

                    <button 
                        type="submit"
                        disabled={isSubmitting}
                        style={{
                            backgroundColor: primaryColor, color: '#FFF', padding: '0.75rem 1.5rem',
                            border: 'none', borderRadius: '4px', cursor: isSubmitting ? 'not-allowed' : 'pointer',
                            opacity: isSubmitting ? 0.7 : 1, fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif', flex: 2
                        }}
                    >
                        {isSubmitting ? 'Generating...' : 'Get My Health Score'}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default LeadCapture;
