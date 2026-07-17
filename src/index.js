
import { render } from '@wordpress/element';

const HealthScoreApp = () => {
    return (
        <div style={{
            padding: '3rem 2rem',
            backgroundColor: '#FBF5E8',
            border: '1px solid #DCD7C9',
            borderRadius: '12px',
            textAlign: 'center',
            boxShadow: '0 4px 6px rgba(0, 0, 0, 0.05)',
            maxWidth: '600px',
            margin: '0 auto'
        }}>
            <h2 style={{
                color: '#2E8B57',
                fontFamily: 'Outfit, sans-serif',
                marginBottom: '1rem',
                fontSize: '2rem'
            }}>
                Hayat Tayyiba Health Score
            </h2>
            <p style={{
                color: '#4A4A4A',
                fontFamily: 'Lexend, sans-serif',
                fontSize: '1.1rem',
                lineHeight: '1.5'
            }}>
                The React application has successfully mounted. Now running with modern JSX!
            </p>
        </div>
    );
};

document.addEventListener('DOMContentLoaded', function () {
    const rootElement = document.getElementById('hayat-health-score-root');
    if (rootElement) {
        render(<HealthScoreApp />, rootElement);
    }
});
