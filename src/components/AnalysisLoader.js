import { useEffect, useState } from '@wordpress/element';

const btnBgBottom = window.healthScoreData?.btnBgBottom || '#07689F';
const primaryColor = btnBgBottom;

const AnalysisLoader = ({ onComplete }) => {
    const [progress, setProgress] = useState(0);
    const [statusMessage, setStatusMessage] = useState('Evaluating your responses...');

    useEffect(() => {
        const startTime = Date.now();
        const duration = 2000; // 2 seconds

        const interval = setInterval(() => {
            const elapsed = Date.now() - startTime;
            const currentProgress = Math.min(100, Math.floor((elapsed / duration) * 100));
            setProgress(currentProgress);

            if (currentProgress < 35) {
                setStatusMessage('Evaluating your responses...');
            } else if (currentProgress < 75) {
                setStatusMessage('Calculating metabolic health score...');
            } else {
                setStatusMessage('Your results are ready!');
            }

            if (elapsed >= duration) {
                clearInterval(interval);
                setTimeout(() => {
                    onComplete();
                }, 300);
            }
        }, 50);

        return () => clearInterval(interval);
    }, [onComplete]);

    return (
        <div style={{
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '2.5rem 1rem',
            textAlign: 'center',
            animation: 'fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
            minHeight: '380px'
        }}>
            <style>{`
                @keyframes pulseGlow {
                    0% { transform: scale(0.95); opacity: 0.8; box-shadow: 0 0 0 0 rgba(11, 95, 132, 0.4); }
                    50% { transform: scale(1.05); opacity: 1; box-shadow: 0 0 0 20px rgba(11, 95, 132, 0); }
                    100% { transform: scale(0.95); opacity: 0.8; box-shadow: 0 0 0 0 rgba(11, 95, 132, 0); }
                }
                @keyframes spinRing {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `}</style>

            {/* Outer Pulsing Icon Ring */}
            <div style={{
                position: 'relative',
                width: '100px',
                height: '100px',
                marginBottom: '2rem',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center'
            }}>
                <div style={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    width: '100%',
                    height: '100%',
                    borderRadius: '50%',
                    border: '3px solid #e2e8f0',
                    borderTopColor: primaryColor,
                    animation: 'spinRing 1.2s linear infinite'
                }} />

                <div style={{
                    width: '74px',
                    height: '74px',
                    borderRadius: '50%',
                    backgroundColor: `${primaryColor}12`,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    animation: 'pulseGlow 2s ease-in-out infinite'
                }}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke={primaryColor} strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                </div>
            </div>

            <h3 style={{
                color: '#1a1f36',
                fontFamily: 'Outfit, sans-serif',
                fontSize: '35px',
                fontWeight: '700',
                marginBottom: '0',
                letterSpacing: '-0.5px'
            }}>
                Analyzing Your Results...
            </h3>

            <p style={{
                color: '#64748b',
                fontFamily: 'Lexend, sans-serif',
                fontSize: 'clamp(0.95rem, 3.2vw, 1.1rem)',
                marginBottom: '2rem',
                minHeight: '1.5em'
            }}>
                {statusMessage}
            </p>

            {/* Progress Bar Container */}
            <div style={{
                width: '100%',
                maxWidth: '380px',
                backgroundColor: '#f1f5f9',
                borderRadius: '50px',
                height: '10px',
                overflow: 'hidden',
                position: 'relative',
                boxShadow: 'inset 0 1px 3px rgba(0,0,0,0.06)'
            }}>
                <div style={{
                    height: '100%',
                    width: `${progress}%`,
                    background: `linear-gradient(90deg, ${primaryColor} 0%, #6cb33f 100%)`,
                    borderRadius: '50px',
                    transition: 'width 0.1s ease-out'
                }} />
            </div>

            <div style={{
                marginTop: '0.8rem',
                fontFamily: 'Outfit, sans-serif',
                fontWeight: '700',
                color: primaryColor,
                fontSize: '0.95rem'
            }}>
                {progress}%
            </div>
        </div>
    );
};

export default AnalysisLoader;
