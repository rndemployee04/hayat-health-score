import { createRoot } from '@wordpress/element';
import Questionnaire from './components/Questionnaire';

const HealthScoreApp = () => {
    return (
        <div style={{ padding: '2rem 1rem' }}>
            <Questionnaire />
        </div>
    );
};

document.addEventListener('DOMContentLoaded', function() {
    const rootElement = document.getElementById('health-score-root');
    if (rootElement) {
        const root = createRoot(rootElement);
        root.render(<HealthScoreApp />);
    }
});
