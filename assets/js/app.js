// wp.element is the WordPress abstraction of React and ReactDOM.
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
            'Health Score Assessment'
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
            'The React application has successfully mounted.'
        )
    );
};

// Mount the React app to the DOM once it's fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const rootElement = document.getElementById('health-score-root');
    if (rootElement) {
        render(createElement(HealthScoreApp), rootElement);
    }
});
