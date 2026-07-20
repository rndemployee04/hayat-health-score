import { useState, useEffect } from '@wordpress/element';
import { submitAssessment } from '../api/api';
import { questions } from '../data/questions';
import ProgressBar from './ProgressBar';
import LeadCapture from './LeadCapture';
import Results from './Results';
const primaryColor = window.healthScoreData?.primaryColor || '#2E8B57';

const STORAGE_KEY = 'health_score_state';

const Questionnaire = () => {
    const getInitialState = () => {
        const urlParams = new URLSearchParams(window.location.search);
        let utm_source = urlParams.get('utm_source') || urlParams.get('source') || '';

        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                if (utm_source) {
                    parsed.utm_source = utm_source;
                }
                return parsed;
            } catch (e) {
                console.error("Failed to parse saved state");
            }
        }
        return { currentStepIndex: 0, answers: {}, utm_source };
    };

    const initialState = getInitialState();
    const [currentStepIndex, setCurrentStepIndex] = useState(initialState.currentStepIndex);
    const [answers, setAnswers] = useState(initialState.answers);
    const [utmSource, setUtmSource] = useState(initialState.utm_source);
    const [status, setStatus] = useState('idle'); 
    const [showLeadCapture, setShowLeadCapture] = useState(false);
    const [showExitIntent, setShowExitIntent] = useState(false);
    const [finalScores, setFinalScores] = useState(null);
    const [isStarted, setIsStarted] = useState(initialState.currentStepIndex > 0 || Object.keys(initialState.answers).length > 0);
    const containerRef = wp.element.useRef(null);

    useEffect(() => {
        if (isStarted) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({ currentStepIndex, answers, utm_source: utmSource }));
        }
    }, [currentStepIndex, answers, utmSource, isStarted]);

    useEffect(() => {
        const handleMouseLeave = (e) => {
            if (e.clientY <= 0 && isStarted && status !== 'success' && !sessionStorage.getItem('health_exit_intent_shown')) {
                setShowExitIntent(true);
                sessionStorage.setItem('health_exit_intent_shown', 'true');
            }
        };
        document.addEventListener('mouseleave', handleMouseLeave);
        return () => document.removeEventListener('mouseleave', handleMouseLeave);
    }, [status, isStarted]);

    const scrollToTop = () => {
        if (containerRef.current) {
            const yOffset = -50;
            const element = containerRef.current;
            const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }
    };

    const handleStart = () => {
        setIsStarted(true);
        setTimeout(scrollToTop, 100);
    };

    const handleNext = () => {
        if (currentStepIndex < questions.length - 1) {
            setCurrentStepIndex(prev => prev + 1);
            scrollToTop();
        } else {
            setShowLeadCapture(true);
            scrollToTop();
        }
    };

    const handleBack = () => {
        if (currentStepIndex > 0) {
            setCurrentStepIndex(prev => prev - 1);
            scrollToTop();
        }
    };

    const handleFinalSubmit = async (contactInfo) => {
        setStatus('submitting');
        try {
            const payload = { ...contactInfo, answers, utm_source: utmSource };
            const response = await submitAssessment(payload);
            setFinalScores(response.scores);
            localStorage.removeItem(STORAGE_KEY);
            setStatus('success');
            setShowLeadCapture(false);
        } catch (error) {
            console.error(error);
            setStatus('error');
        }
    };

    const cardStyle = { 
        animation: 'fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1)', 
        maxWidth: '650px', 
        width: '100%',
        margin: 'clamp(1rem, 4vw, 2rem) auto', 
        backgroundColor: '#ffffff', 
        padding: 'clamp(1.5rem, 5vw, 3rem)', 
        borderRadius: '24px', 
        boxShadow: '0 20px 40px rgba(0,0,0,0.04), 0 1px 3px rgba(0,0,0,0.02)', 
        border: '1px solid rgba(220, 227, 235, 0.8)',
        display: 'flex', 
        flexDirection: 'column', 
        minHeight: 'clamp(400px, 70vh, 600px)',
        position: 'relative',
        overflow: 'hidden',
        boxSizing: 'border-box'
    };

    if (status === 'success' && finalScores) {
        return (
            <div style={cardStyle}>
                <Results scores={finalScores} />
            </div>
        );
    }

    if (showLeadCapture) {
        return (
            <div style={cardStyle}>
                <ProgressBar currentStep={questions.length} totalSteps={questions.length} />
                <LeadCapture onSubmit={handleFinalSubmit} isSubmitting={status === 'submitting'} onBack={() => setShowLeadCapture(false)} />
            </div>
        );
    }

    if (!isStarted) {
        return (
            <div style={{...cardStyle, justifyContent: 'center', textAlign: 'center'}}>
                <style>{`
                    @media (max-width: 480px) {
                        .hide-on-mobile { display: none !important; }
                        .show-on-mobile { display: inline !important; }
                    }
                    @media (min-width: 481px) {
                        .show-on-mobile { display: none !important; }
                    }
                `}</style>
                <h2 style={{ color: '#1a1f36', fontFamily: 'Outfit, sans-serif', fontSize: 'clamp(1.8rem, 6vw, 2.5rem)', fontWeight: '800', marginBottom: '1.5rem', letterSpacing: '-0.5px', lineHeight: '1.2' }}>
                    60-Second Metabolic Health Assessment
                </h2>
                <p style={{ color: '#4f566b', fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(1rem, 3.5vw, 1.2rem)', marginBottom: 'clamp(2rem, 5vw, 3rem)', lineHeight: '1.7', maxWidth: '90%', margin: '0 auto clamp(2rem, 5vw, 3rem) auto' }}>
                    Discover your personalized health score, identify your top opportunities for vitality, and take the first step towards a healthier you. It only takes a minute.
                </p>
                <button
                    onClick={handleStart}
                    style={{
                        background: `linear-gradient(180deg, #009c46 0%, #004b20 100%)`,
                        color: '#FFF',
                        padding: '1rem',
                        border: 'none',
                        borderRadius: '12px',
                        cursor: 'pointer',
                        fontSize: 'clamp(0.95rem, 3.5vw, 1.15rem)',
                        fontFamily: 'Outfit, sans-serif',
                        fontWeight: '700',
                        width: '100%',
                        maxWidth: '450px',
                        margin: '0 auto',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: '12px',
                        lineHeight: '1.3',
                        boxShadow: `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`,
                        transition: 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                        letterSpacing: '0.5px',
                        textShadow: '0 1px 2px rgba(0,0,0,0.2)'
                    }}
                    onMouseOver={(e) => { 
                        e.currentTarget.style.transform = 'scale(1.03)'; 
                        e.currentTarget.style.boxShadow = `0 15px 35px rgba(0,0,0,0.2), inset 0 2px 4px rgba(255,255,255,0.4)`; 
                    }}
                    onMouseOut={(e) => { 
                        e.currentTarget.style.transform = 'scale(1)'; 
                        e.currentTarget.style.boxShadow = `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`; 
                    }}
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 448 512" fill="currentColor">
                        <path d="M176 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h16v34.4C92.3 113.8 16 200 16 304c0 114.9 93.1 208 208 208s208-93.1 208-208c0-104-76.3-190.2-176-205.6V64h16c17.7 0 32-14.3 32-32s-14.3-32-32-32H176zm72 192V320c0 13.3-10.7 24-24 24s-24-10.7-24-24V192c0-13.3 10.7-24 24-24s24 10.7 24 24z"/>
                    </svg>
                    <span>
                        <span className="hide-on-mobile">Start My 60-Second Assessment</span>
                        <span className="show-on-mobile">Start Assessment</span>
                    </span>
                </button>
            </div>
        );
    }

    const currentQuestion = questions[currentStepIndex];
    const currentAnswer = answers[currentQuestion.id] || (currentQuestion.type === 'checkbox' ? [] : (currentQuestion.type === 'slider' ? 5 : ''));

    const handleOptionToggle = (option) => {
        setAnswers(prev => {
            const prevAnswers = prev[currentQuestion.id] || [];
            let newAnswers;
            const exclusiveOptions = ['None', "I haven't really tried yet", "I haven't seriously tried yet."];
            
            if (exclusiveOptions.includes(option)) {
                newAnswers = [option];
            } else {
                const filteredPrev = prevAnswers.filter(item => !exclusiveOptions.includes(item));
                newAnswers = filteredPrev.includes(option)
                    ? filteredPrev.filter(item => item !== option)
                    : [...filteredPrev, option];
            }
            return { ...prev, [currentQuestion.id]: newAnswers };
        });
    };

    const handleRadioSelect = (option) => {
        setAnswers(prev => ({ ...prev, [currentQuestion.id]: option }));
    };

    const handleSliderChange = (e) => {
        setAnswers(prev => ({ ...prev, [currentQuestion.id]: parseInt(e.target.value, 10) }));
    };

    const isNextDisabled = () => {
        if (currentQuestion.type === 'checkbox') return currentAnswer.length === 0;
        if (currentQuestion.type === 'radio') return currentAnswer === '';
        return false;
    };

    return (
        <div ref={containerRef} style={cardStyle}>
            <style>{`@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }`}</style>

            {showExitIntent && (
                <div style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(255, 255, 255, 0.95)', backdropFilter: 'blur(10px)', zIndex: 10, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', padding: '3rem', textAlign: 'center' }}>
                    <h3 style={{ color: '#1a1f36', marginBottom: '1rem', fontFamily: 'Outfit, sans-serif', fontSize: '2.5rem', fontWeight: '800', letterSpacing: '-0.5px' }}>Wait! Don't leave just yet.</h3>
                    <p style={{ marginBottom: '2.5rem', fontSize: '1.2rem', color: '#4f566b', fontFamily: 'Lexend, sans-serif', lineHeight: '1.6' }}>You're only a few questions away from seeing your personalized Health Score.</p>
                    <button onClick={() => setShowExitIntent(false)} style={{ background: `linear-gradient(180deg, #009c46 0%, #004b20 100%)`, color: '#FFF', padding: '1.2rem 3rem', border: '3px solid #E8F5E9', borderRadius: '12px', cursor: 'pointer', fontSize: '1.25rem', fontFamily: 'Outfit, sans-serif', fontWeight: '700', boxShadow: `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`, textShadow: '0 1px 2px rgba(0,0,0,0.2)', transition: 'all 0.3s' }} onMouseOver={(e) => { e.currentTarget.style.transform = 'scale(1.03)'; e.currentTarget.style.boxShadow = `0 15px 35px rgba(0,0,0,0.2), inset 0 2px 4px rgba(255,255,255,0.4)`; }} onMouseOut={(e) => { e.currentTarget.style.transform = 'scale(1)'; e.currentTarget.style.boxShadow = `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`; }}>Continue Assessment</button>
                </div>
            )}

            <ProgressBar currentStep={currentStepIndex + 1} totalSteps={questions.length} />

            <h3 style={{ color: '#1a1f36', marginTop: '1rem', marginBottom: '0.5rem', fontFamily: 'Outfit, sans-serif', fontSize: 'clamp(1.4rem, 4.5vw, 1.8rem)', fontWeight: '700', letterSpacing: '-0.5px', lineHeight: '1.3' }}>{currentQuestion.title}</h3>
            {currentQuestion.subtitle && <p style={{ marginBottom: '0', fontStyle: 'italic', color: '#8792a2', fontSize: 'clamp(0.9rem, 3.5vw, 1.05rem)', fontFamily: 'Lexend, sans-serif' }}>{currentQuestion.subtitle}</p>}

            <div style={{ display: 'flex', flexDirection: 'column', gap: '1rem', marginTop: 'clamp(1.5rem, 4vw, 2rem)', marginBottom: '2.5rem', flex: 1 }}>
                {currentQuestion.type === 'checkbox' && currentQuestion.options.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '1rem', cursor: 'pointer', padding: 'clamp(0.8rem, 3vw, 1.2rem) clamp(1rem, 4vw, 1.5rem)', border: '1px solid', borderColor: currentAnswer.includes(option) ? primaryColor : 'rgba(220, 227, 235, 0.8)', borderRadius: '12px', backgroundColor: currentAnswer.includes(option) ? `${primaryColor}08` : '#ffffff', outline: 'none', userSelect: 'none', transition: 'all 0.2s', boxShadow: currentAnswer.includes(option) ? `0 0 0 2px ${primaryColor}30` : 'none' }}>
                        <input type="checkbox" checked={currentAnswer.includes(option)} onChange={() => handleOptionToggle(option)} style={{ width: '24px', height: '24px', accentColor: primaryColor, cursor: 'pointer', border: 'none', boxShadow: 'none', appearance: 'auto', outline: 'none' }} />
                        <span style={{ fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(1rem, 4vw, 1.2rem)', color: currentAnswer.includes(option) ? '#1a1f36' : '#4f566b', fontWeight: currentAnswer.includes(option) ? '600' : '400' }}>{option}</span>
                    </label>
                ))}

                {currentQuestion.type === 'radio' && currentQuestion.options.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '1rem', cursor: 'pointer', padding: 'clamp(0.8rem, 3vw, 1.2rem) clamp(1rem, 4vw, 1.5rem)', border: '1px solid', borderColor: currentAnswer === option ? primaryColor : 'rgba(220, 227, 235, 0.8)', borderRadius: '12px', backgroundColor: currentAnswer === option ? `${primaryColor}08` : '#ffffff', outline: 'none', userSelect: 'none', transition: 'all 0.2s', boxShadow: currentAnswer === option ? `0 0 0 2px ${primaryColor}30` : 'none' }}>
                        <input type="radio" name={`radio-${currentQuestion.id}`} checked={currentAnswer === option} onChange={() => handleRadioSelect(option)} style={{ width: '24px', height: '24px', accentColor: primaryColor, cursor: 'pointer', border: 'none', boxShadow: 'none', appearance: 'auto', outline: 'none' }} />
                        <span style={{ fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(1rem, 4vw, 1.2rem)', color: currentAnswer === option ? '#1a1f36' : '#4f566b', fontWeight: currentAnswer === option ? '600' : '400' }}>{option}</span>
                    </label>
                ))}

                {currentQuestion.type === 'slider' && (
                    <div style={{ padding: 'clamp(1.2rem, 4vw, 2rem) clamp(0.8rem, 3vw, 1rem)', backgroundColor: '#f8fafc', borderRadius: '16px', border: '1px solid rgba(220, 227, 235, 0.5)' }}>
                        <input type="range" min={currentQuestion.min} max={currentQuestion.max} value={currentAnswer} onChange={handleSliderChange} style={{ width: '100%', cursor: 'pointer', outline: 'none', accentColor: '#1a1f36' }} />
                        <div style={{ textAlign: 'center', marginTop: '1.5rem', fontSize: 'clamp(1.4rem, 4.5vw, 1.8rem)', fontWeight: '800', color: '#1a1f36', fontFamily: 'Outfit, sans-serif' }}>{currentAnswer}</div>
                    </div>
                )}
            </div>

            {currentQuestion.insight && (
                <div style={{ backgroundColor: '#fffbe6', padding: 'clamp(1rem, 4vw, 1.5rem)', borderRadius: '12px', borderLeft: `4px solid #f0ad4e`, marginBottom: '2.5rem' }}>
                    <p style={{ margin: 0, color: '#856404', fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(0.9rem, 3vw, 1rem)', lineHeight: '1.6' }}>
                        <strong style={{ fontWeight: '700' }}>Insight:</strong> {currentQuestion.insight}
                    </p>
                </div>
            )}

            <div style={{ display: 'flex', gap: '1rem', marginTop: 'auto', flexWrap: 'wrap' }}>
                {currentStepIndex > 0 && (
                    <button 
                        onClick={handleBack} 
                        style={{ backgroundColor: 'transparent', color: '#4f566b', padding: 'clamp(0.8rem, 3vw, 1.2rem)', border: '1px solid rgba(220, 227, 235, 0.8)', borderRadius: '12px', cursor: 'pointer', fontSize: 'clamp(1rem, 3vw, 1.15rem)', fontFamily: 'Outfit, sans-serif', fontWeight: '600', flex: '1 1 120px', transition: 'all 0.2s', textAlign: 'center' }}
                        onMouseOver={(e) => { e.currentTarget.style.backgroundColor = '#f8fafc'; }}
                        onMouseOut={(e) => { e.currentTarget.style.backgroundColor = 'transparent'; }}
                    >
                        Back
                    </button>
                )}
                <button 
                    onClick={handleNext} 
                    disabled={isNextDisabled()} 
                    style={{ background: `linear-gradient(180deg, #009c46 0%, #004b20 100%)`, color: '#FFF', padding: 'clamp(0.8rem, 3vw, 1.2rem)', border: 'none', borderRadius: '12px', cursor: isNextDisabled() ? 'not-allowed' : 'pointer', opacity: isNextDisabled() ? 0.6 : 1, fontSize: 'clamp(1rem, 3.5vw, 1.15rem)', fontFamily: 'Outfit, sans-serif', fontWeight: '700', flex: '2 1 200px', boxShadow: isNextDisabled() ? 'none' : `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`, textShadow: '0 1px 2px rgba(0,0,0,0.2)', transition: 'all 0.3s', textAlign: 'center' }}
                    onMouseOver={(e) => { if (!isNextDisabled()) { e.currentTarget.style.transform = 'scale(1.03)'; e.currentTarget.style.boxShadow = `0 15px 35px rgba(0,0,0,0.2), inset 0 2px 4px rgba(255,255,255,0.4)`; } }}
                    onMouseOut={(e) => { if (!isNextDisabled()) { e.currentTarget.style.transform = 'scale(1)'; e.currentTarget.style.boxShadow = `0 10px 30px rgba(0,0,0,0.15), inset 0 2px 4px rgba(255,255,255,0.3)`; } }}
                >
                    {currentStepIndex === questions.length - 1 ? 'Get My Health Snapshot' : 'Continue'}
                </button>
            </div>

            {status === 'error' && <p style={{ color: '#d9534f', marginTop: '1.5rem', textAlign: 'center', fontFamily: 'Lexend, sans-serif' }}>An error occurred. Please try again.</p>}
        </div>
    );
};

export default Questionnaire;
