import { useState, useEffect } from '@wordpress/element';
import { submitAssessment } from '../api/api';
import { questions } from '../data/questions';
import ProgressBar from './ProgressBar';
import LeadCapture from './LeadCapture';
import Results from './Results';

const STORAGE_KEY = 'hayat_health_score_state';

const Questionnaire = () => {
    const getInitialState = () => {
        // Parse URL params for UTM source
        const urlParams = new URLSearchParams(window.location.search);
        let utm_source = urlParams.get('utm_source') || urlParams.get('source') || '';

        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            try { 
                const parsed = JSON.parse(saved); 
                // Prefer URL param if it exists, otherwise use saved
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
    const [status, setStatus] = useState('idle'); // idle, submitting, success, error
    const [showLeadCapture, setShowLeadCapture] = useState(false);
    const [showExitIntent, setShowExitIntent] = useState(false);
    const [finalScores, setFinalScores] = useState(null);
    const [isStarted, setIsStarted] = useState(initialState.currentStepIndex > 0 || Object.keys(initialState.answers).length > 0);

    useEffect(() => {
        if (isStarted) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({ currentStepIndex, answers, utm_source: utmSource }));
        }
    }, [currentStepIndex, answers, utmSource, isStarted]);

    useEffect(() => {
        const handleMouseLeave = (e) => {
            if (e.clientY <= 0 && isStarted && status !== 'success' && !sessionStorage.getItem('hayat_exit_intent_shown')) {
                setShowExitIntent(true);
                sessionStorage.setItem('hayat_exit_intent_shown', 'true');
            }
        };
        document.addEventListener('mouseleave', handleMouseLeave);
        return () => document.removeEventListener('mouseleave', handleMouseLeave);
    }, [status, isStarted]);

    const handleStart = () => {
        setIsStarted(true);
    };

    const handleNext = () => {
        if (currentStepIndex < questions.length - 1) {
            setCurrentStepIndex(prev => prev + 1);
        } else {
            setShowLeadCapture(true);
        }
    };

    const handleBack = () => {
        if (currentStepIndex > 0) {
            setCurrentStepIndex(prev => prev - 1);
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

    if (status === 'success' && finalScores) {
        return <Results scores={finalScores} />;
    }

    if (showLeadCapture) {
        return (
            <div style={{ animation: 'fadeIn 0.5s' }}>
                <ProgressBar currentStep={questions.length} totalSteps={questions.length} />
                <LeadCapture onSubmit={handleFinalSubmit} isSubmitting={status === 'submitting'} />
            </div>
        );
    }

    if (!isStarted) {
        return (
            <div style={{ textAlign: 'center', padding: '3rem 1rem', animation: 'fadeIn 0.5s' }}>
                <h2 style={{ color: '#2E8B57', fontFamily: 'Outfit, sans-serif', fontSize: '2rem', marginBottom: '1rem' }}>
                    The 60-Second Hayat Tayyiba Health Score
                </h2>
                <p style={{ color: '#666', fontFamily: 'Lexend, sans-serif', fontSize: '1.1rem', marginBottom: '2.5rem', lineHeight: '1.6' }}>
                    Discover your personalized health score, identify your top opportunities for vitality, and take the first step towards a healthier you. It only takes a minute.
                </p>
                <button 
                    onClick={handleStart}
                    style={{
                        backgroundColor: '#2E8B57',
                        color: '#FFF',
                        padding: '1rem 2.5rem',
                        border: 'none',
                        borderRadius: '6px',
                        cursor: 'pointer',
                        fontSize: '1.2rem',
                        fontFamily: 'Outfit, sans-serif',
                        fontWeight: 'bold',
                        boxShadow: '0 4px 6px rgba(46, 139, 87, 0.2)',
                        transition: 'transform 0.2s ease'
                    }}
                    onMouseOver={(e) => { e.currentTarget.style.transform = 'translateY(-2px)'; }}
                    onMouseOut={(e) => { e.currentTarget.style.transform = 'translateY(0)'; }}
                >
                    Start Assessment
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
            if (option === 'None' || option === "I haven't really tried yet") {
                newAnswers = [option];
            } else if (prevAnswers.includes('None') || prevAnswers.includes("I haven't really tried yet")) {
                newAnswers = [option];
            } else {
                newAnswers = prevAnswers.includes(option) ? prevAnswers.filter(item => item !== option) : [...prevAnswers, option];
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
        <div style={{ position: 'relative', padding: '2rem', textAlign: 'left', animation: 'fadeIn 0.4s ease-in-out' }}>
            <style>{`@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }`}</style>
            
            {showExitIntent && (
                <div style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(251, 245, 232, 0.95)', zIndex: 10, display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', padding: '2rem', textAlign: 'center', borderRadius: '12px' }}>
                    <h3 style={{ color: '#2E8B57', marginBottom: '1rem', fontFamily: 'Outfit, sans-serif' }}>Wait! Don't leave just yet.</h3>
                    <p style={{ marginBottom: '2rem', fontSize: '1.1rem', color: '#4A4A4A', fontFamily: 'Lexend, sans-serif' }}>You're only a few questions away from seeing your personalized Hayat Tayyiba Health Score.</p>
                    <button onClick={() => setShowExitIntent(false)} style={{ backgroundColor: '#2E8B57', color: '#FFF', padding: '0.75rem 2rem', border: 'none', borderRadius: '4px', cursor: 'pointer', fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif' }}>Continue Assessment</button>
                </div>
            )}

            <ProgressBar currentStep={currentStepIndex + 1} totalSteps={questions.length} />

            <h3 style={{ color: '#2E8B57', marginBottom: '0.5rem', fontFamily: 'Outfit, sans-serif', fontSize: '1.4rem' }}>{currentQuestion.title}</h3>
            {currentQuestion.subtitle && <p style={{ marginBottom: '1.5rem', fontStyle: 'italic', color: '#666', fontSize: '0.9rem' }}>{currentQuestion.subtitle}</p>}
            
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem', marginBottom: '2rem' }}>
                {currentQuestion.type === 'checkbox' && currentQuestion.options.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', cursor: 'pointer', padding: '0.75rem', border: '1px solid #DCD7C9', borderRadius: '6px', backgroundColor: currentAnswer.includes(option) ? '#f0f9f4' : '#fff' }}>
                        <input type="checkbox" checked={currentAnswer.includes(option)} onChange={() => handleOptionToggle(option)} />
                        <span style={{ fontFamily: 'Lexend, sans-serif' }}>{option}</span>
                    </label>
                ))}

                {currentQuestion.type === 'radio' && currentQuestion.options.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', cursor: 'pointer', padding: '0.75rem', border: '1px solid #DCD7C9', borderRadius: '6px', backgroundColor: currentAnswer === option ? '#f0f9f4' : '#fff' }}>
                        <input type="radio" name={`radio-${currentQuestion.id}`} checked={currentAnswer === option} onChange={() => handleRadioSelect(option)} />
                        <span style={{ fontFamily: 'Lexend, sans-serif' }}>{option}</span>
                    </label>
                ))}

                {currentQuestion.type === 'slider' && (
                    <div style={{ padding: '1rem 0' }}>
                        <input type="range" min={currentQuestion.min} max={currentQuestion.max} value={currentAnswer} onChange={handleSliderChange} style={{ width: '100%', cursor: 'pointer' }} />
                        <div style={{ textAlign: 'center', marginTop: '1rem', fontSize: '1.2rem', fontWeight: 'bold', color: '#2E8B57' }}>{currentAnswer}</div>
                    </div>
                )}
            </div>

            <div style={{ display: 'flex', gap: '1rem' }}>
                {currentStepIndex > 0 && (
                    <button onClick={handleBack} style={{ backgroundColor: '#fff', color: '#4A4A4A', padding: '0.75rem 1.5rem', border: '1px solid #DCD7C9', borderRadius: '4px', cursor: 'pointer', fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif', flex: 1 }}>Back</button>
                )}
                <button onClick={handleNext} disabled={isNextDisabled()} style={{ backgroundColor: '#2E8B57', color: '#FFF', padding: '0.75rem 1.5rem', border: 'none', borderRadius: '4px', cursor: isNextDisabled() ? 'not-allowed' : 'pointer', opacity: isNextDisabled() ? 0.6 : 1, fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif', flex: 2 }}>
                    {currentStepIndex === questions.length - 1 ? 'Continue' : 'Continue'}
                </button>
            </div>
            
            {status === 'error' && <p style={{ color: 'red', marginTop: '1rem', textAlign: 'center' }}>An error occurred. Please try again.</p>}
        </div>
    );
};

export default Questionnaire;
