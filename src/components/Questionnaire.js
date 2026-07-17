import { useState } from '@wordpress/element';
import { submitAssessment } from '../api/api';

const Q1_OPTIONS = [
    "Weight that's difficult to lose",
    "Low energy or fatigue",
    "Poor sleep",
    "Blood sugar concerns",
    "High blood pressure",
    "High cholesterol",
    "Brain fog",
    "High stress",
    "I take more medications than I'd like",
    "I don't feel like myself anymore"
];

const Questionnaire = () => {
    const [selectedOptions, setSelectedOptions] = useState([]);
    const [status, setStatus] = useState('idle'); // idle, submitting, success, error

    const toggleOption = (option) => {
        setSelectedOptions((prev) => 
            prev.includes(option)
                ? prev.filter((item) => item !== option)
                : [...prev, option]
        );
    };

    const handleSubmit = async () => {
        if (selectedOptions.length === 0) return;
        
        setStatus('submitting');
        try {
            await submitAssessment({ q1: selectedOptions });
            setStatus('success');
        } catch (error) {
            console.error(error);
            setStatus('error');
        }
    };

    if (status === 'success') {
        return (
            <div style={{ textAlign: 'center', padding: '2rem' }}>
                <h3 style={{ color: '#2E8B57' }}>Success!</h3>
                <p>Tracer bullet complete. Q1 data saved to the database.</p>
            </div>
        );
    }

    return (
        <div style={{ padding: '2rem', textAlign: 'left' }}>
            <h3 style={{ color: '#2E8B57', marginBottom: '1.5rem', fontFamily: 'Outfit, sans-serif' }}>
                Which of these are you currently struggling with?
            </h3>
            <p style={{ marginBottom: '1rem', fontStyle: 'italic', color: '#666' }}>
                (Check all that apply.)
            </p>
            
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.5rem', marginBottom: '2rem' }}>
                {Q1_OPTIONS.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '0.5rem', cursor: 'pointer', padding: '0.5rem', border: '1px solid #DCD7C9', borderRadius: '4px' }}>
                        <input 
                            type="checkbox" 
                            checked={selectedOptions.includes(option)}
                            onChange={() => toggleOption(option)}
                        />
                        <span style={{ fontFamily: 'Lexend, sans-serif' }}>{option}</span>
                    </label>
                ))}
            </div>

            <button 
                onClick={handleSubmit}
                disabled={selectedOptions.length === 0 || status === 'submitting'}
                style={{
                    backgroundColor: '#2E8B57',
                    color: '#FFF',
                    padding: '0.75rem 1.5rem',
                    border: 'none',
                    borderRadius: '4px',
                    cursor: selectedOptions.length === 0 ? 'not-allowed' : 'pointer',
                    opacity: selectedOptions.length === 0 ? 0.6 : 1,
                    fontSize: '1.1rem',
                    fontFamily: 'Outfit, sans-serif',
                    width: '100%'
                }}
            >
                {status === 'submitting' ? 'Submitting...' : 'Continue'}
            </button>
            
            {status === 'error' && (
                <p style={{ color: 'red', marginTop: '1rem' }}>An error occurred while submitting. Please try again.</p>
            )}
        </div>
    );
};

export default Questionnaire;
