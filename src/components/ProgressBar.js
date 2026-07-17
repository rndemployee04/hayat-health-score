const primaryColor = window.hayatHealthData?.primaryColor || '#2E8B57';
const ProgressBar = ({ currentStep, totalSteps }) => {
    const percentage = Math.round((currentStep / totalSteps) * 100);

    return (
        <div style={{ marginBottom: '2.5rem' }}>
            <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'space-between', gap: '0.5rem', marginBottom: '0.8rem', fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(0.75rem, 3vw, 0.95rem)', color: '#8792a2', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                <span>Question {currentStep} of {totalSteps}</span>
                <span style={{ color: primaryColor }}>{percentage}% Complete</span>
            </div>
            <div style={{ width: '100%', height: '8px', backgroundColor: '#f0f3f6', borderRadius: '10px', overflow: 'hidden', boxShadow: 'inset 0 1px 3px rgba(0,0,0,0.05)' }}>
                <div 
                    style={{
                        height: '100%',
                        width: `${percentage}%`,
                        background: `linear-gradient(90deg, ${primaryColor}80 0%, ${primaryColor} 100%)`,
                        borderRadius: '10px',
                        transition: 'width 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
                        boxShadow: `0 2px 4px ${primaryColor}40`
                    }}
                />
            </div>
        </div>
    );
};

export default ProgressBar;
