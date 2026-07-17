import { useState } from '@wordpress/element';

const LeadCapture = ({ onSubmit, onBack, isSubmitting }) => {
    const [firstName, setFirstName] = useState('');
    const [email, setEmail] = useState('');
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
        
        setError('');
        onSubmit({ first_name: firstName, email: email });
    };

    return (
        <div style={{ padding: '2rem', textAlign: 'left', animation: 'fadeIn 0.4s ease-in-out' }}>
            <h3 style={{ color: '#2E8B57', marginBottom: '1rem', fontFamily: 'Outfit, sans-serif', fontSize: '1.6rem' }}>
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
                            backgroundColor: '#2E8B57', color: '#FFF', padding: '0.75rem 1.5rem',
                            border: 'none', borderRadius: '4px', cursor: isSubmitting ? 'not-allowed' : 'pointer',
                            opacity: isSubmitting ? 0.7 : 1, fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif', flex: 2
                        }}
                    >
                        {isSubmitting ? 'Generating...' : 'See My Score'}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default LeadCapture;
