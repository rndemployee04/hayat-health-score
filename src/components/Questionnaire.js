import { useState, useEffect } from '@wordpress/element';
import { submitAssessment } from '../api/api';
import { questions } from '../data/questions';
import ProgressBar from './ProgressBar';

const STORAGE_KEY = 'hayat_health_score_state';

const Questionnaire = () => {
    // Initialize state from localStorage if it exists
    const getInitialState = () => {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            try {
                return JSON.parse(saved);
            } catch (e) {
                console.error("Failed to parse saved state");
            }
        }
        return { currentStepIndex: 0, answers: {} };
    };

    const initialState = getInitialState();
    const [currentStepIndex, setCurrentStepIndex] = useState(initialState.currentStepIndex);
    const [answers, setAnswers] = useState(initialState.answers);
    const [status, setStatus] = useState('idle'); // idle, submitting, success, error
    const [showExitIntent, setShowExitIntent] = useState(false);

    // Autosave to localStorage whenever answers or step changes
    useEffect(() => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({ currentStepIndex, answers }));
    }, [currentStepIndex, answers]);

    // Exit Intent Logic
    useEffect(() => {
        const handleMouseLeave = (e) => {
            // Trigger if cursor leaves the top of the window and we haven't finished yet
            if (e.clientY <= 0 && status !== 'success' && !sessionStorage.getItem('hayat_exit_intent_shown')) {
                setShowExitIntent(true);
                sessionStorage.setItem('hayat_exit_intent_shown', 'true');
            }
        };

        document.addEventListener('mouseleave', handleMouseLeave);
        return () => document.removeEventListener('mouseleave', handleMouseLeave);
    }, [status]);

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
                newAnswers = prevAnswers.includes(option)
                    ? prevAnswers.filter(item => item !== option)
                    : [...prevAnswers, option];
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

    const handleNext = async () => {
        if (currentStepIndex < questions.length - 1) {
            setCurrentStepIndex(prev => prev + 1);
        } else {
            setStatus('submitting');
            try {
                await submitAssessment(answers);
                // Clear localStorage on successful final submission
                localStorage.removeItem(STORAGE_KEY);
                setStatus('success');
            } catch (error) {
                console.error(error);
                setStatus('error');
            }
        }
    };

    const handleBack = () => {
        if (currentStepIndex > 0) {
            setCurrentStepIndex(prev => prev - 1);
        }
    };

    const isNextDisabled = () => {
        if (currentQuestion.type === 'checkbox') return currentAnswer.length === 0;
        if (currentQuestion.type === 'radio') return currentAnswer === '';
        return false;
    };

    if (status === 'success') {
        return (
            <div style={{ textAlign: 'center', padding: '2rem', animation: 'fadeIn 0.5s' }}>
                <h3 style={{ color: '#2E8B57' }}>Success!</h3>
                <p>All questions answered. State machine complete!</p>
            </div>
        );
    }

    return (
        <div style={{ position: 'relative', padding: '2rem', textAlign: 'left', animation: 'fadeIn 0.4s ease-in-out' }}>
            <style>
                {`
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                `}
            </style>
            
            {showExitIntent && (
                <div style={{
                    position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, 
                    backgroundColor: 'rgba(251, 245, 232, 0.95)', zIndex: 10, 
                    display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center',
                    padding: '2rem', textAlign: 'center', borderRadius: '12px'
                }}>
                    <h3 style={{ color: '#2E8B57', marginBottom: '1rem', fontFamily: 'Outfit, sans-serif' }}>Wait! Don't leave just yet.</h3>
                    <p style={{ marginBottom: '2rem', fontSize: '1.1rem', color: '#4A4A4A', fontFamily: 'Lexend, sans-serif' }}>
                        You're only a few questions away from seeing your personalized Hayat Tayyiba Health Score.
                    </p>
                    <button 
                        onClick={() => setShowExitIntent(false)}
                        style={{
                            backgroundColor: '#2E8B57', color: '#FFF', padding: '0.75rem 2rem', 
                            border: 'none', borderRadius: '4px', cursor: 'pointer',
                            fontSize: '1.1rem', fontFamily: 'Outfit, sans-serif'
                        }}
                    >
                        Continue Assessment
                    </button>
                </div>
            )}

            <ProgressBar currentStep={currentStepIndex + 1} totalSteps={questions.length} />

            <h3 style={{ color: '#2E8B57', marginBottom: '0.5rem', fontFamily: 'Outfit, sans-serif', fontSize: '1.4rem' }}>
                {currentQuestion.title}
            </h3>
            {currentQuestion.subtitle && (
                <p style={{ marginBottom: '1.5rem', fontStyle: 'italic', color: '#666', fontSize: '0.9rem' }}>
                    {currentQuestion.subtitle}
                </p>
            )}
            
            <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem', marginBottom: '2rem' }}>
                {currentQuestion.type === 'checkbox' && currentQuestion.options.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', cursor: 'pointer', padding: '0.75rem', border: '1px solid #DCD7C9', borderRadius: '6px', backgroundColor: currentAnswer.includes(option) ? '#f0f9f4' : '#fff' }}>
                        <input 
                            type="checkbox" 
                            checked={currentAnswer.includes(option)}
                            onChange={() => handleOptionToggle(option)}
                        />
                        <span style={{ fontFamily: 'Lexend, sans-serif' }}>{option}</span>
                    </label>
                ))}

                {currentQuestion.type === 'radio' && currentQuestion.options.map((option) => (
                    <label key={option} style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', cursor: 'pointer', padding: '0.75rem', border: '1px solid #DCD7C9', borderRadius: '6px', backgroundColor: currentAnswer === option ? '#f0f9f4' : '#fff' }}>
                        <input 
                            type="radio" 
                            name={`radio-${currentQuestion.id}`}
                            checked={currentAnswer === option}
                            onChange={() => handleRadioSelect(option)}
                        />
                        <span style={{ fontFamily: 'Lexend, sans-serif' }}>{option}</span>
                    </label>
                ))}

                {currentQuestion.type === 'slider' && (
                    <div style={{ padding: '1rem 0' }}>
                        <input 
                            type="range" 
                            min={currentQuestion.min} 
                            max={currentQuestion.max} 
                            value={currentAnswer} 
                            onChange={handleSliderChange}
                            style={{ width: '100%', cursor: 'pointer' }}
                        />
                        <div style={{ textAlign: 'center', marginTop: '1rem', fontSize: '1.2rem', fontWeight: 'bold', color: '#2E8B57' }}>
                            {currentAnswer}
                        </div>
                    </div>
                )}
            </div>

            <div style={{ display: 'flex', gap: '1rem' }}>
                {currentStepIndex > 0 && (
                    <button 
                        onClick={handleBack}
                        style={{
                            backgroundColor: '#fff',
                            color: '#4A4A4A',
                            padding: '0.75rem 1.5rem',
                            border: '1px solid #DCD7C9',
                            borderRadius: '4px',
                            cursor: 'pointer',
                            fontSize: '1.1rem',
                            fontFamily: 'Outfit, sans-serif',
                            flex: 1
                        }}
                    >
                        Back
                    </button>
                )}

                <button 
                    onClick={handleNext}
                    disabled={isNextDisabled() || status === 'submitting'}
                    style={{
                        backgroundColor: '#2E8B57',
                        color: '#FFF',
                        padding: '0.75rem 1.5rem',
                        border: 'none',
                        borderRadius: '4px',
                        cursor: isNextDisabled() ? 'not-allowed' : 'pointer',
                        opacity: isNextDisabled() ? 0.6 : 1,
                        fontSize: '1.1rem',
                        fontFamily: 'Outfit, sans-serif',
                        flex: 2
                    }}
                >
                    {status === 'submitting' ? 'Submitting...' : (currentStepIndex === questions.length - 1 ? 'See My Score' : 'Continue')}
                </button>
            </div>
            
            {status === 'error' && (
                <p style={{ color: 'red', marginTop: '1rem', textAlign: 'center' }}>An error occurred. Please try again.</p>
            )}
        </div>
    );
};

export default Questionnaire;
