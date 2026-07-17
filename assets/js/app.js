// wp.element is the WordPress abstraction of React and ReactDOM.
// This allows us to use React without needing a complex build step (Webpack/Babel) immediately.
const { createElement } = wp.element;
const { render } = wp.element;

const HealthScoreApp = () => {
    return createElement(
        'div',
        { 
            style: { 
                padding: '3rem 2rem', 
                backgroundColor: '#FBF5E8', // Brand Warm Neutral
                border: '1px solid #DCD7C9', // Brand Border
                borderRadius: '12px', 
                textAlign: 'center',
                boxShadow: '0 4px 6px rgba(0, 0, 0, 0.05)',
                maxWidth: '600px',
                margin: '0 auto'
            } 
        },
        createElement(
            'h2',
            { 
                style: { 
                    color: '#2E8B57', // Primary Brand Green
                    fontFamily: 'Outfit, sans-serif',
                    marginBottom: '1rem',
                    fontSize: '2rem'
                } 
            },
            'Hayat Tayyiba Health Score'
        ),
        createElement(
            'p',
            {
                style: {
                    color: '#4A4A4A',
                    fontFamily: 'Lexend, sans-serif',
                    fontSize: '1.1rem',
                    lineHeight: '1.5'
                }
            },
            'The React application has successfully mounted. The design tokens have been verified and applied. Ready for Issue 2!'
        )
    );
};

// Mount the React app to the DOM once it's fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const rootElement = document.getElementById('hayat-health-score-root');
    if (rootElement) {
        render(createElement(HealthScoreApp), rootElement);
    }
});
