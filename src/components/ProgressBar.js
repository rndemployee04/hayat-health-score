const ProgressBar = ({ currentStep, totalSteps }) => {
    const percentage = Math.round((currentStep / totalSteps) * 100);

    return (
        <div style={{ marginBottom: '2rem' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.5rem', fontFamily: 'Lexend, sans-serif', fontSize: '0.9rem', color: '#666' }}>
                <span>Question {currentStep} of {totalSteps}</span>
                <span>{percentage}% Complete</span>
            </div>
            <div style={{ width: '100%', height: '8px', backgroundColor: '#DCD7C9', borderRadius: '4px', overflow: 'hidden' }}>
                <div 
                    style={{
                        height: '100%',
                        width: `${percentage}%`,
                        backgroundColor: '#2E8B57', // Primary Brand Green
                        transition: 'width 0.4s ease-in-out'
                    }}
                />
            </div>
        </div>
    );
};

export default ProgressBar;
