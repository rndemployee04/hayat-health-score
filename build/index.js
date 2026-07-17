/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/api/api.js"
/*!************************!*\
  !*** ./src/api/api.js ***!
  \************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   submitAssessment: () => (/* binding */ submitAssessment)
/* harmony export */ });
const submitAssessment = async answers => {
  // hayatHealthData is passed via wp_localize_script in PHP
  const {
    restUrl,
    nonce
  } = window.hayatHealthData;
  const response = await fetch(restUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-WP-Nonce': nonce
    },
    body: JSON.stringify(answers)
  });
  if (!response.ok) {
    throw new Error('Network response was not ok');
  }
  return response.json();
};

/***/ },

/***/ "./src/components/ProgressBar.js"
/*!***************************************!*\
  !*** ./src/components/ProgressBar.js ***!
  \***************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__);

const ProgressBar = ({
  currentStep,
  totalSteps
}) => {
  const percentage = Math.round(currentStep / totalSteps * 100);
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("div", {
    style: {
      marginBottom: '2rem'
    },
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("div", {
      style: {
        display: 'flex',
        justifyContent: 'space-between',
        marginBottom: '0.5rem',
        fontFamily: 'Lexend, sans-serif',
        fontSize: '0.9rem',
        color: '#666'
      },
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("span", {
        children: ["Question ", currentStep, " of ", totalSteps]
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("span", {
        children: [percentage, "% Complete"]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("div", {
      style: {
        width: '100%',
        height: '8px',
        backgroundColor: '#DCD7C9',
        borderRadius: '4px',
        overflow: 'hidden'
      },
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("div", {
        style: {
          height: '100%',
          width: `${percentage}%`,
          backgroundColor: '#2E8B57',
          // Primary Brand Green
          transition: 'width 0.4s ease-in-out'
        }
      })
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressBar);

/***/ },

/***/ "./src/components/Questionnaire.js"
/*!*****************************************!*\
  !*** ./src/components/Questionnaire.js ***!
  \*****************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _api_api__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../api/api */ "./src/api/api.js");
/* harmony import */ var _data_questions__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../data/questions */ "./src/data/questions.js");
/* harmony import */ var _ProgressBar__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ProgressBar */ "./src/components/ProgressBar.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);





const Questionnaire = () => {
  const [currentStepIndex, setCurrentStepIndex] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(0);
  const [answers, setAnswers] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const [status, setStatus] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)('idle'); // idle, submitting, success, error

  const currentQuestion = _data_questions__WEBPACK_IMPORTED_MODULE_2__.questions[currentStepIndex];
  const currentAnswer = answers[currentQuestion.id] || (currentQuestion.type === 'checkbox' ? [] : currentQuestion.type === 'slider' ? 5 : '');
  const handleOptionToggle = option => {
    setAnswers(prev => {
      const prevAnswers = prev[currentQuestion.id] || [];
      let newAnswers;

      // Handle exclusive "None" or "I haven't really tried yet"
      if (option === 'None' || option === "I haven't really tried yet") {
        newAnswers = [option];
      } else if (prevAnswers.includes('None') || prevAnswers.includes("I haven't really tried yet")) {
        newAnswers = [option]; // Remove the exclusive option if another is picked
      } else {
        newAnswers = prevAnswers.includes(option) ? prevAnswers.filter(item => item !== option) : [...prevAnswers, option];
      }
      return {
        ...prev,
        [currentQuestion.id]: newAnswers
      };
    });
  };
  const handleRadioSelect = option => {
    setAnswers(prev => ({
      ...prev,
      [currentQuestion.id]: option
    }));
  };
  const handleSliderChange = e => {
    setAnswers(prev => ({
      ...prev,
      [currentQuestion.id]: parseInt(e.target.value, 10)
    }));
  };
  const handleNext = async () => {
    if (currentStepIndex < _data_questions__WEBPACK_IMPORTED_MODULE_2__.questions.length - 1) {
      setCurrentStepIndex(prev => prev + 1);
    } else {
      // Final submission
      setStatus('submitting');
      try {
        await (0,_api_api__WEBPACK_IMPORTED_MODULE_1__.submitAssessment)(answers);
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
    return false; // slider always has a value
  };
  if (status === 'success') {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
      style: {
        textAlign: 'center',
        padding: '2rem',
        animation: 'fadeIn 0.5s'
      },
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("h3", {
        style: {
          color: '#2E8B57'
        },
        children: "Success!"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("p", {
        children: "All questions answered. State machine complete!"
      })]
    });
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
    style: {
      padding: '2rem',
      textAlign: 'left',
      animation: 'fadeIn 0.4s ease-in-out'
    },
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("style", {
      children: `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                `
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_ProgressBar__WEBPACK_IMPORTED_MODULE_3__["default"], {
      currentStep: currentStepIndex + 1,
      totalSteps: _data_questions__WEBPACK_IMPORTED_MODULE_2__.questions.length
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("h3", {
      style: {
        color: '#2E8B57',
        marginBottom: '0.5rem',
        fontFamily: 'Outfit, sans-serif',
        fontSize: '1.4rem'
      },
      children: currentQuestion.title
    }), currentQuestion.subtitle && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("p", {
      style: {
        marginBottom: '1.5rem',
        fontStyle: 'italic',
        color: '#666',
        fontSize: '0.9rem'
      },
      children: currentQuestion.subtitle
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
      style: {
        display: 'flex',
        flexDirection: 'column',
        gap: '0.75rem',
        marginBottom: '2rem'
      },
      children: [currentQuestion.type === 'checkbox' && currentQuestion.options.map(option => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("label", {
        style: {
          display: 'flex',
          alignItems: 'center',
          gap: '0.75rem',
          cursor: 'pointer',
          padding: '0.75rem',
          border: '1px solid #DCD7C9',
          borderRadius: '6px',
          backgroundColor: currentAnswer.includes(option) ? '#f0f9f4' : '#fff'
        },
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("input", {
          type: "checkbox",
          checked: currentAnswer.includes(option),
          onChange: () => handleOptionToggle(option)
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("span", {
          style: {
            fontFamily: 'Lexend, sans-serif'
          },
          children: option
        })]
      }, option)), currentQuestion.type === 'radio' && currentQuestion.options.map(option => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("label", {
        style: {
          display: 'flex',
          alignItems: 'center',
          gap: '0.75rem',
          cursor: 'pointer',
          padding: '0.75rem',
          border: '1px solid #DCD7C9',
          borderRadius: '6px',
          backgroundColor: currentAnswer === option ? '#f0f9f4' : '#fff'
        },
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("input", {
          type: "radio",
          name: `radio-${currentQuestion.id}`,
          checked: currentAnswer === option,
          onChange: () => handleRadioSelect(option)
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("span", {
          style: {
            fontFamily: 'Lexend, sans-serif'
          },
          children: option
        })]
      }, option)), currentQuestion.type === 'slider' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
        style: {
          padding: '1rem 0'
        },
        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("input", {
          type: "range",
          min: currentQuestion.min,
          max: currentQuestion.max,
          value: currentAnswer,
          onChange: handleSliderChange,
          style: {
            width: '100%',
            cursor: 'pointer'
          }
        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
          style: {
            textAlign: 'center',
            marginTop: '1rem',
            fontSize: '1.2rem',
            fontWeight: 'bold',
            color: '#2E8B57'
          },
          children: currentAnswer
        })]
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
      style: {
        display: 'flex',
        gap: '1rem'
      },
      children: [currentStepIndex > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("button", {
        onClick: handleBack,
        style: {
          backgroundColor: '#fff',
          color: '#4A4A4A',
          padding: '0.75rem 1.5rem',
          border: '1px solid #DCD7C9',
          borderRadius: '4px',
          cursor: 'pointer',
          fontSize: '1.1rem',
          fontFamily: 'Outfit, sans-serif',
          flex: 1
        },
        children: "Back"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("button", {
        onClick: handleNext,
        disabled: isNextDisabled() || status === 'submitting',
        style: {
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
        },
        children: status === 'submitting' ? 'Submitting...' : currentStepIndex === _data_questions__WEBPACK_IMPORTED_MODULE_2__.questions.length - 1 ? 'See My Score' : 'Continue'
      })]
    }), status === 'error' && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("p", {
      style: {
        color: 'red',
        marginTop: '1rem',
        textAlign: 'center'
      },
      children: "An error occurred. Please try again."
    })]
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Questionnaire);

/***/ },

/***/ "./src/data/questions.js"
/*!*******************************!*\
  !*** ./src/data/questions.js ***!
  \*******************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   questions: () => (/* binding */ questions)
/* harmony export */ });
const questions = [{
  id: 'q1',
  title: "Which of these are you currently struggling with?",
  subtitle: "(Check all that apply.)",
  type: 'checkbox',
  options: ["Weight that's difficult to lose", "Low energy or fatigue", "Poor sleep", "Blood sugar concerns", "High blood pressure", "High cholesterol", "Brain fog", "High stress", "I take more medications than I'd like", "I don't feel like myself anymore"]
}, {
  id: 'q2',
  title: "If you could improve ONE thing over the next six months...",
  subtitle: "",
  type: 'radio',
  options: ["Lose weight", "Have more energy", "Sleep better", "Improve blood sugar", "Improve blood pressure", "Reduce medications (with physician guidance)", "Feel healthier overall"]
}, {
  id: 'q3',
  title: "How long have these concerns been affecting you?",
  subtitle: "",
  type: 'radio',
  options: ["Less than 6 months", "6–12 months", "1–3 years", "More than 3 years"]
}, {
  id: 'q4',
  title: "What have you already tried?",
  subtitle: "(Check all that apply.)",
  type: 'checkbox',
  options: ["Diets", "Exercise", "Supplements", "Medications", "Weight-loss programs", "I've tried almost everything", "I haven't really tried yet"]
}, {
  id: 'q5',
  title: "Which statement best describes your energy?",
  subtitle: "",
  type: 'radio',
  options: ["I feel energetic most days.", "I often crash in the afternoon.", "I rely on caffeine most days.", "I'm tired most of the day."]
}, {
  id: 'q6',
  title: "How often do you experience cravings for sugar, bread, snacks, or caffeine?",
  subtitle: "",
  type: 'radio',
  options: ["Rarely", "Occasionally", "Daily", "Multiple times per day"]
}, {
  id: 'q7',
  title: "Do you currently have any of these conditions?",
  subtitle: "(Check all that apply.)",
  type: 'checkbox',
  options: ["Prediabetes", "Type 2 Diabetes", "High Blood Pressure", "High Cholesterol", "Fatty Liver", "Thyroid Concerns", "Sleep Apnea", "None"]
}, {
  id: 'q8',
  title: "If nothing changed over the next year... What concerns you the most?",
  subtitle: "",
  type: 'radio',
  options: ["Taking more medications", "Gaining more weight", "Having less energy", "My health continuing to decline", "Not enjoying life the way I'd like"]
}, {
  id: 'q9',
  title: "On a scale of 1–10... How ready are you to improve your health?",
  subtitle: "1 = Not ready, 10 = Ready right now",
  type: 'slider',
  min: 1,
  max: 10
}];

/***/ },

/***/ "react/jsx-runtime"
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
(module) {

module.exports = window["ReactJSXRuntime"];

/***/ },

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	const __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		const cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		const module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			const e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			const getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter/value functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			if(Array.isArray(definition)) {
/******/ 				var i = 0;
/******/ 				while(i < definition.length) {
/******/ 					var key = definition[i++];
/******/ 					var binding = definition[i++];
/******/ 					if(!__webpack_require__.o(exports, key)) {
/******/ 						if(binding === 0) {
/******/ 							Object.defineProperty(exports, key, { enumerable: true, value: definition[i++] });
/******/ 						} else {
/******/ 							Object.defineProperty(exports, key, { enumerable: true, get: binding });
/******/ 						}
/******/ 					} else if(binding === 0) { i++; }
/******/ 				}
/******/ 			} else {
/******/ 				for(var key in definition) {
/******/ 					if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 						Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 					}
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.hasOwn(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
let __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_Questionnaire__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/Questionnaire */ "./src/components/Questionnaire.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



const HealthScoreApp = () => {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
    style: {
      backgroundColor: '#FBF5E8',
      border: '1px solid #DCD7C9',
      borderRadius: '12px',
      boxShadow: '0 4px 6px rgba(0, 0, 0, 0.05)',
      maxWidth: '600px',
      margin: '0 auto',
      overflow: 'hidden'
    },
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_components_Questionnaire__WEBPACK_IMPORTED_MODULE_1__["default"], {})
  });
};
document.addEventListener('DOMContentLoaded', function () {
  const rootElement = document.getElementById('hayat-health-score-root');
  if (rootElement) {
    const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createRoot)(rootElement);
    root.render(/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(HealthScoreApp, {}));
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map