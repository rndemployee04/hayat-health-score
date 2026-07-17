import { createRoot } from '@wordpress/element';
import Questionnaire from './components/Questionnaire';

const HealthScoreApp = () => {
    return (
        <div style={{
            backgroundColor: '#FBF5E8', 
            border: '1px solid #DCD7C9', 
            borderRadius: '12px', 
            boxShadow: '0 4px 6px rgba(0, 0, 0, 0.05)',
            maxWidth: '600px',
            margin: '0 auto',
            overflow: 'hidden'
        }}>
            <Questionnaire />
        </div>
    );
};

document.addEventListener('DOMContentLoaded', function() {
    const rootElement = document.getElementById('hayat-health-score-root');
    if (rootElement) {
        const root = createRoot(rootElement);
        root.render(<HealthScoreApp />);
    }
});
