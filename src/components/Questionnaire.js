import { useState, useEffect, useRef } from '@wordpress/element';
import { submitAssessment } from '../api/api';
import { questions } from '../data/questions';
import ProgressBar from './ProgressBar';
import LeadCapture from './LeadCapture';
import Results from './Results';
import AnalysisLoader from './AnalysisLoader';
import confetti from 'canvas-confetti';

const btnBgTop = window.healthScoreData?.btnBgTop || '#40BAD5';
const btnBgBottom = window.healthScoreData?.btnBgBottom || '#07689F';
const btnHoverTop = window.healthScoreData?.btnHoverTop || '#FCBF1E';
const btnHoverBottom = window.healthScoreData?.btnHoverBottom || '#F59C11';
const primaryColor = btnBgBottom;

const STORAGE_KEY = 'health_score_state';
const COMPLETED_KEY = 'health_score_completed_results';

const Questionnaire = () => {
    const getInitialCompleted = () => {
        const saved = localStorage.getItem(COMPLETED_KEY);
        if (saved) {
            try {
                return JSON.parse(saved);
            } catch (e) {
                console.error("Failed to parse completed state");
            }
        }
        return null;
    };

    const getInitialState = () => {
        const urlParams = new URLSearchParams(window.location.search);
        let utm_source = urlParams.get('utm_source') || urlParams.get('source') || urlParams.get('utm_medium') || '';

        if (!utm_source && document.referrer) {
            try {
                const refUrl = new URL(document.referrer);
                if (refUrl.hostname && !refUrl.hostname.includes(window.location.hostname)) {
                    utm_source = refUrl.hostname.replace('www.', '');
                }
            } catch (e) { }
        }

        if (!utm_source) {
            utm_source = 'Direct';
        }

        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            try {
                const parsed = JSON.parse(saved);
                if (utm_source && utm_source !== 'Direct') {
                    parsed.utm_source = utm_source;
                }
                return parsed;
            } catch (e) {
                console.error("Failed to parse saved state");
            }
        }
        return { currentStepIndex: 0, answers: {}, utm_source };
    };

    const initialCompleted = getInitialCompleted();
    const initialState = getInitialState();
    const [currentStepIndex, setCurrentStepIndex] = useState(initialState.currentStepIndex);
    const [answers, setAnswers] = useState(initialState.answers);
    const [utmSource, setUtmSource] = useState(initialState.utm_source);
    const [status, setStatus] = useState(initialCompleted ? 'success' : 'idle');
    const [isAnalyzing, setIsAnalyzing] = useState(false);
    const [showLeadCapture, setShowLeadCapture] = useState(false);
    const [showExitIntent, setShowExitIntent] = useState(false);
    const [finalScores, setFinalScores] = useState(initialCompleted);
    const [isStarted, setIsStarted] = useState(initialState.currentStepIndex > 0 || Object.keys(initialState.answers).length > 0);
    const containerRef = useRef(null);

    useEffect(() => {
        if (isStarted && !finalScores) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({ currentStepIndex, answers, utm_source: utmSource }));
        }
    }, [currentStepIndex, answers, utmSource, isStarted, finalScores]);

    useEffect(() => {
        const handleMouseLeave = (e) => {
            if (e.clientY <= 0 && isStarted && status !== 'success' && !isAnalyzing && !sessionStorage.getItem('health_exit_intent_shown')) {
                setShowExitIntent(true);
                sessionStorage.setItem('health_exit_intent_shown', 'true');
            }
        };
        document.addEventListener('mouseleave', handleMouseLeave);
        return () => document.removeEventListener('mouseleave', handleMouseLeave);
    }, [status, isStarted, isAnalyzing]);

    const scrollToTop = () => {
        if (containerRef.current) {
            const yOffset = -50;
            const element = containerRef.current;
            const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });

            // Additional fail-safe for custom theme wrappers
            try {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (e) {
                // Fallback for legacy browsers
            }
        }
    };

    const handleStart = () => {
        setIsStarted(true);
        setTimeout(scrollToTop, 100);
    };

    const handleNext = () => {
        if (isNextDisabled()) return;
        if (currentStepIndex < questions.length - 1) {
            setCurrentStepIndex(prev => prev + 1);
            scrollToTop();
        } else {
            // Last question completed -> Transition to 2-second Analysis Loader screen
            setIsAnalyzing(true);
            scrollToTop();
        }
    };

    const handleAnalysisComplete = () => {
        setIsAnalyzing(false);
        setShowLeadCapture(true);
        scrollToTop();

        // Fire central celebratory confetti burst when results are ready!
        try {
            confetti({
                particleCount: 120,
                spread: 80,
                origin: { y: 0.6 },
                colors: [btnBgTop, btnBgBottom, btnHoverTop, btnHoverBottom, '#10B981']
            });
        } catch (e) {
            console.error("Confetti error:", e);
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
            localStorage.setItem(COMPLETED_KEY, JSON.stringify(response.scores));
            localStorage.removeItem(STORAGE_KEY);
            setStatus('success');
            setShowLeadCapture(false);
            setTimeout(() => scrollToTop(), 50);
        } catch (error) {
            console.error(error);
            setStatus('error');
        }
    };

    const handleRetake = () => {
        localStorage.removeItem(COMPLETED_KEY);
        localStorage.removeItem(STORAGE_KEY);
        setFinalScores(null);
        setStatus('idle');
        setIsAnalyzing(false);
        setShowLeadCapture(false);
        setCurrentStepIndex(0);
        setAnswers({});
        setIsStarted(false);
        scrollToTop();
    };

    const cardStyle = {
        animation: 'fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
        maxWidth: '650px',
        width: '100%',
        margin: 'clamp(1rem, 4vw, 2rem) auto',
        backgroundColor: '#fff',
        padding: '40px 30px',
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
                <Results scores={finalScores} onRetake={handleRetake} />
            </div>
        );
    }

    if (isAnalyzing) {
        return (
            <div style={cardStyle}>
                <AnalysisLoader onComplete={handleAnalysisComplete} />
            </div>
        );
    }

    if (showLeadCapture) {
        return (
            <div style={cardStyle}>
                <ProgressBar currentStep={questions.length} totalSteps={questions.length} isCompleted={true} />
                <LeadCapture onSubmit={handleFinalSubmit} isSubmitting={status === 'submitting'} onBack={() => setShowLeadCapture(false)} />
            </div>
        );
    }

    if (!isStarted) {
        return (
            <div style={{ ...cardStyle, justifyContent: 'center', textAlign: 'center' }}>
                <style>{`
                    @media (max-width: 480px) {
                        .hide-on-mobile { display: none !important; }
                        .show-on-mobile { display: inline !important; }
                    }
                    @media (min-width: 481px) {
                        .show-on-mobile { display: none !important; }
                    }
                `}</style>
                <h2 style={{ color: '#1a1f36', fontFamily: 'Outfit, sans-serif', fontSize: '40px', fontWeight: '700', margin: '0 0 10px', letterSpacing: '-0.5px', lineHeight: '1.2' }}>
                    GliaFit – 60-Second <br></br>Health Score
                </h2>
                <p style={{ color: '#4f566b', fontFamily: 'Lexend, sans-serif', fontSize: '18px', margin: '0px auto 25px', lineHeight: '1.3' }}>
                    Discover your personalized health score, identify your top opportunities for vitality, and take the first step towards a healthier you. It only takes a minute.
                </p>
                <button
                    onClick={handleStart}
                    style={{
                        background: `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`,
                        color: '#FFF',
                        padding: '14px 24px',
                        border: '1px solid rgba(220, 227, 235, 0.8)',
                        borderRadius: '50px',
                        cursor: 'pointer',
                        fontSize: '18px',
                        fontFamily: 'Outfit, sans-serif',
                        fontWeight: '700',
                        width: '100%',
                        maxWidth: '450px',
                        margin: '0 auto',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        lineHeight: '1.3',
                        boxShadow: `0 8px 20px rgba(0,0,0,0.15)`,
                        transition: 'all 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                        letterSpacing: '0.5px'
                    }}
                    onMouseOver={(e) => {
                        e.currentTarget.style.background = `linear-gradient(180deg, ${btnHoverTop} 0%, ${btnHoverBottom} 100%)`;
                    }}
                    onMouseOut={(e) => {
                        e.currentTarget.style.background = `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`;
                    }}
                >
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
        <div ref={containerRef} style={{
            ...cardStyle,
            padding: 'clamp(1rem, 3.5vw, 1.8rem)',
            minHeight: 'auto',
            margin: '0.8rem auto'
        }}>
            <style>{`
                .option-row {
                    display: flex;
                    align-items: center;
                    gap: 1.2rem;
                    cursor: pointer;
                    padding: 1.1rem 1.4rem;
                    border: 2px solid rgba(220, 227, 235, 0.85);
                    border-radius: 16px;
                    background-color: #ffffff;
                    outline: none;
                    user-select: none;
                    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
                    box-shadow: 0 2px 4px rgba(0,0,0,0.01);
                }
                .option-row:hover {
                    border-color: #94a3b8;
                    background-color: #f8fafc;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.02);
                }
                .option-row.selected {
                    border-color: ${primaryColor} !important;
                    background-color: ${primaryColor}08 !important;
                    box-shadow: 0 4px 12px ${primaryColor}12 !important;
                }
                .primary-button {
                    background: linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%) !important;
                    color: #FFF !important;
                    padding: 1.05rem 1.4rem !important;
                    border: none !important;
                    border-radius: 50px !important;
                    cursor: pointer !important;
                    font-size: clamp(1rem, 3.5vw, 1.1rem) !important;
                    font-family: Outfit, sans-serif !important;
                    font-weight: 700 !important;
                    width: 100% !important;
                    box-shadow: 0 6px 16px rgba(0,0,0,0.12) !important;
                    transition: all 0.3s ease !important;
                    text-align: center !important;
                    display: block !important;
                    opacity: 1 !important;
                }
                .primary-button:disabled {
                    opacity: 0.45 !important;
                    cursor: not-allowed !important;
                    box-shadow: none !important;
                }
                .primary-button:not(:disabled):hover {
                    background: linear-gradient(180deg, ${btnHoverTop} 0%, ${btnHoverBottom} 100%) !important;
                    box-shadow: 0 10px 20px rgba(0,0,0,0.18) !important;
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `}</style>

            {showExitIntent && (
                <div style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(15, 23, 42, 0.75)', backdropFilter: 'blur(8px)', zIndex: 999999, display: 'flex', justifyContent: 'center', alignItems: 'center', padding: '1.5rem' }}>
                    <div style={{ backgroundColor: '#ffffff', borderRadius: '24px', padding: 'clamp(2rem, 5vw, 3rem)', maxWidth: '480px', width: '100%', textAlign: 'center', boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.35)', border: '1px solid rgba(220, 227, 235, 0.8)' }}>
                        <h3 style={{ color: '#1a1f36', marginBottom: '1rem', fontFamily: 'Outfit, sans-serif', fontSize: '20px', fontWeight: '700', letterSpacing: '-0.5px' }}>Wait! Don't leave just yet.</h3>
                        <p style={{ marginBottom: '2rem', fontSize: '12px', color: '#4f566b', fontFamily: 'Lexend, sans-serif', lineHeight: '1.6' }}>You're only a few questions away from seeing your personalized Health Score.</p>
                        <button
                            onClick={() => setShowExitIntent(false)}
                            style={{
                                background: `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`,
                                color: '#FFF',
                                padding: '14px 24px',
                                border: '1px solid rgba(220, 227, 235, 0.8)',
                                borderRadius: '50px',
                                cursor: 'pointer',
                                fontSize: '18px',
                                fontFamily: 'Outfit, sans-serif',
                                fontWeight: '700',
                                width: '100%',
                                boxShadow: `0 8px 20px rgba(0,0,0,0.15)`,
                                transition: 'all 0.3s'
                            }}
                            onMouseOver={(e) => {
                                e.currentTarget.style.background = `linear-gradient(180deg, ${btnHoverTop} 0%, ${btnHoverBottom} 100%)`;
                            }}
                            onMouseOut={(e) => {
                                e.currentTarget.style.background = `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`;
                            }}
                        >
                            Continue Assessment
                        </button>
                    </div>
                </div>
            )}

            <ProgressBar currentStep={currentStepIndex + 1} totalSteps={questions.length} />

            <h3 style={{ color: '#1a1f36', margin: '0 0 0.3rem 0', fontFamily: 'Outfit, sans-serif', fontSize: '20px', fontWeight: '700', letterSpacing: '-0.5px', lineHeight: '1.25' }}>{currentQuestion.title}</h3>
            {currentQuestion.subtitle && <p style={{ margin: '0', fontStyle: 'italic', color: '#64748b', fontSize: '12px', fontFamily: 'Lexend, sans-serif' }}>{currentQuestion.subtitle}</p>}

            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem', marginTop: '1.2rem', marginBottom: '1.4rem' }}>
                {currentQuestion.type === 'checkbox' && currentQuestion.options.map((option) => {
                    const isSelected = currentAnswer.includes(option);
                    return (
                        <label key={option} className={`option-row ${isSelected ? 'selected' : ''}`} onClick={() => handleOptionToggle(option)}>
                            <div style={{
                                width: '24px',
                                height: '24px',
                                borderRadius: '6px',
                                border: `2px solid ${isSelected ? primaryColor : '#94a3b8'}`,
                                backgroundColor: isSelected ? primaryColor : '#ffffff',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                flexShrink: 0,
                                transition: 'all 0.2s'
                            }}>
                                {isSelected && (
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#ffffff" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" style={{ width: '14px', height: '14px' }}>
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                )}
                            </div>
                            <span style={{ fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(1.05rem, 3.8vw, 1.18rem)', color: isSelected ? '#1a1f36' : '#334155', fontWeight: isSelected ? '600' : '400', lineHeight: '1.35' }}>{option}</span>
                        </label>
                    );
                })}

                {currentQuestion.type === 'radio' && currentQuestion.options.map((option) => {
                    const isSelected = currentAnswer === option;
                    return (
                        <label key={option} className={`option-row ${isSelected ? 'selected' : ''}`} onClick={() => handleRadioSelect(option)}>
                            <div style={{
                                width: '24px',
                                height: '24px',
                                borderRadius: '50%',
                                border: `2px solid ${isSelected ? primaryColor : '#94a3b8'}`,
                                backgroundColor: '#ffffff',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                flexShrink: 0,
                                transition: 'all 0.2s'
                            }}>
                                {isSelected && (
                                    <div style={{
                                        width: '12px',
                                        height: '12px',
                                        borderRadius: '50%',
                                        backgroundColor: primaryColor
                                    }} />
                                )}
                            </div>
                            <span style={{ fontFamily: 'Lexend, sans-serif', fontSize: 'clamp(1.05rem, 3.8vw, 1.18rem)', color: isSelected ? '#1a1f36' : '#334155', fontWeight: isSelected ? '600' : '400', lineHeight: '1.35' }}>{option}</span>
                        </label>
                    );
                })}

                {currentQuestion.type === 'slider' && (
                    <div style={{ padding: '1rem', backgroundColor: '#f8fafc', borderRadius: '14px', border: '1px solid rgba(220, 227, 235, 0.5)' }}>
                        <input type="range" min={currentQuestion.min} max={currentQuestion.max} value={currentAnswer} onChange={handleSliderChange} style={{ width: '100%', cursor: 'pointer', outline: 'none', accentColor: '#1a1f36' }} />
                        <div style={{ textAlign: 'center', marginTop: '0.8rem', fontSize: '1.5rem', fontWeight: '800', color: '#1a1f36', fontFamily: 'Outfit, sans-serif' }}>{currentAnswer}</div>
                    </div>
                )}
            </div>

            {currentQuestion.insight && (
                <div style={{
                    marginTop: '0.5rem',
                    marginBottom: '1.6rem',
                    padding: '1rem 1.2rem',
                    backgroundColor: '#eff6ff',
                    borderLeft: `4px solid ${primaryColor}`,
                    borderRadius: '8px',
                    fontSize: 'clamp(0.85rem, 2.8vw, 0.95rem)',
                    color: '#1e3a8a',
                    lineHeight: '1.55',
                    fontFamily: 'Lexend, sans-serif',
                    display: 'flex',
                    alignItems: 'flex-start',
                    gap: '10px'
                }}>
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke={primaryColor}
                        strokeWidth="2.5"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        style={{
                            width: '20px',
                            height: '20px',
                            minWidth: '20px',
                            minHeight: '20px',
                            flexShrink: 0,
                            marginTop: '2px',
                            display: 'block'
                        }}
                    >
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="16" x2="12" y2="12" />
                        <line x1="12" y1="8" x2="12" y2="8" />
                    </svg>
                    <div>
                        {currentQuestion.insight}
                    </div>
                </div>
            )}

            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.6rem', marginTop: 'auto', width: '100%' }}>
                {currentStepIndex > 0 && (
                    <button
                        onClick={handleBack}
                        style={{
                            backgroundColor: '#f7f7f7', color: '#4f566b', padding: '14px 24px',
                            border: '1px solid rgba(220, 227, 235, 0.8)', borderRadius: '50px', cursor: 'pointer',
                            fontSize: '18px', fontFamily: 'Outfit, sans-serif', fontWeight: '600', flex: '1 1 120px',
                            transition: 'all 0.2s ease', textAlign: 'center'
                        }}
                        onMouseOver={(e) => {
                            e.currentTarget.style.background = `#4f566b`;
                            e.currentTarget.style.color = `#fff`;
                        }}
                        onMouseOut={(e) => {
                            e.currentTarget.style.background = `#f7f7f7`;
                            e.currentTarget.style.color = `#4f566b`;
                        }}
                    >
                        Back
                    </button>
                )}
                <button
                    onClick={handleNext}
                    disabled={isNextDisabled()}
                    style={{
                        background: isNextDisabled() ? '#cbd5e1' : `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`,
                        color: isNextDisabled() ? '#64748b' : '#FFF',
                        padding: '14px 24px',
                        border: '1px solid rgba(220, 227, 235, 0.8)',
                        borderRadius: '50px',
                        cursor: isNextDisabled() ? 'not-allowed' : 'pointer',
                        fontSize: '18px',
                        fontFamily: 'Outfit, sans-serif',
                        fontWeight: '700',
                        flex: '2 1 180px',
                        boxShadow: isNextDisabled() ? 'none' : `0 6px 16px rgba(0,0,0,0.12)`,
                        transition: 'all 0.3s',
                        textAlign: 'center'
                    }}
                    onMouseOver={(e) => {
                        if (!isNextDisabled()) {
                            e.currentTarget.style.background = `linear-gradient(180deg, ${btnHoverTop} 0%, ${btnHoverBottom} 100%)`;
                        }
                    }}
                    onMouseOut={(e) => {
                        if (!isNextDisabled()) {
                            e.currentTarget.style.background = `linear-gradient(180deg, ${btnBgTop} 0%, ${btnBgBottom} 100%)`;
                        }
                    }}
                >
                    {currentStepIndex === questions.length - 1 ? 'Get My Health Score' : 'Continue'}
                </button>
            </div>

            {status === 'error' && <p style={{ color: '#d9534f', marginTop: '1.5rem', textAlign: 'center', fontFamily: 'Lexend, sans-serif' }}>An error occurred. Please try again.</p>}
        </div>
    );
};

export default Questionnaire;
