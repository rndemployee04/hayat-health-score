const btnBgTop = window.healthScoreData?.btnBgTop || '#40BAD5';
const btnBgBottom = window.healthScoreData?.btnBgBottom || '#07689F';
const primaryColor = btnBgBottom;

const ProgressBar = ({ currentStep, totalSteps, isCompleted = false }) => {
    const completedSteps = isCompleted ? totalSteps : Math.max(0, currentStep - 1);
    const percentage = Math.round((completedSteps / totalSteps) * 100);
    const secondsLeft = isCompleted ? 0 : Math.max(5, (totalSteps - currentStep + 1) * 5);

    return (
        <div style={{ marginBottom: '2.2rem' }}>
            <div style={{
                display: 'flex',
                flexWrap: 'wrap',
                justifyContent: 'space-between',
                gap: '0.5rem',
                marginBottom: '0.8rem',
                fontFamily: 'Lexend, sans-serif',
                fontSize: 'clamp(0.85rem, 3vw, 0.95rem)',
                color: '#475569', // Darker gray for high contrast (> 4.5:1)
                fontWeight: '500',
                letterSpacing: '-0.1px'
            }}>
                <span>{isCompleted ? 'Assessment Completed' : `Question ${currentStep} of ${totalSteps}`}</span>
                {isCompleted ? (
                    <span style={{ fontWeight: '600' }}>
                        100%
                    </span>
                ) : (
                    <span style={{ color: primaryColor, fontWeight: '600' }}>
                        About {secondsLeft} sec left
                    </span>
                )}
            </div>
            <div style={{ width: '100%', height: '8px', backgroundColor: '#e2e8f0', borderRadius: '10px', overflow: 'hidden', boxShadow: 'inset 0 1px 2px rgba(0,0,0,0.06)' }}>
                <div 
                    style={{
                        height: '100%',
                        width: `${percentage}%`,
                        background: `linear-gradient(90deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`,
                        borderRadius: '10px',
                        transition: 'width 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
                        boxShadow: `0 2px 4px ${primaryColor}30`
                    }}
                />
            </div>
        </div>
    );
};

export default ProgressBar;
