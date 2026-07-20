export const questions = [
    {
        id: 'q1',
        title: "What health concerns are bothering you most right now?",
        subtitle: "(Select all that apply.)",
        insight: "Many of these symptoms often occur together because they can share the same underlying metabolic contributors.",
        type: 'checkbox',
        options: [
            "Weight that's difficult to lose",
            "Low energy or fatigue",
            "Poor sleep",
            "Brain fog",
            "High blood sugar / Prediabetes / Diabetes",
            "High blood pressure",
            "High cholesterol",
            "Digestive issues",
            "Joint pain",
            "I don't feel like myself anymore"
        ]
    },
    {
        id: 'q2',
        title: "Which ONE improvement would make the biggest difference in your life over the next six months?",
        subtitle: "",
        insight: "Improving one area of your health often creates positive improvements in several others.",
        type: 'radio',
        options: [
            "Lose weight",
            "Have more energy",
            "Sleep better",
            "Improve my blood sugar",
            "Lower my blood pressure",
            "Reduce medications (with physician guidance)",
            "Feel healthy again"
        ]
    },
    {
        id: 'q3',
        title: "How long have these concerns been affecting you?",
        subtitle: "",
        insight: "Many people live with metabolic dysfunction for years before discovering what's actually driving it.",
        type: 'radio',
        options: [
            "Less than 6 months",
            "6–12 months",
            "1–3 years",
            "3–5 years",
            "More than 5 years"
        ]
    },
    {
        id: 'q4',
        title: "Which statement best describes your energy throughout the day?",
        subtitle: "",
        insight: "Energy isn't determined by sleep alone. It can also reflect how efficiently your body produces and uses energy.",
        type: 'radio',
        options: [
            "I feel energetic most days.",
            "I usually crash in the afternoon.",
            "I rely on caffeine most days.",
            "I'm tired most of the day.",
            "I'm exhausted even after sleeping."
        ]
    },
    {
        id: 'q5',
        title: "How often do you crave sugar, bread, snacks, or caffeine?",
        subtitle: "",
        insight: "Frequent cravings may be a sign that your body isn't regulating blood sugar and energy as efficiently as it could.",
        type: 'radio',
        options: [
            "Rarely",
            "A few times each week",
            "Daily",
            "Multiple times per day"
        ]
    },
    {
        id: 'q6',
        title: "Have you ever been diagnosed with any of the following?",
        subtitle: "(Select all that apply.)",
        insight: "These conditions often occur together because they share many of the same underlying contributors.",
        type: 'checkbox',
        options: [
            "Prediabetes",
            "Type 2 Diabetes",
            "High Blood Pressure",
            "High Cholesterol",
            "Fatty Liver",
            "Sleep Apnea",
            "PCOS",
            "Thyroid Problems",
            "None"
        ]
    },
    {
        id: 'q7',
        title: "What have you already tried?",
        subtitle: "(Select all that apply.)",
        insight: "Most people don't fail because they lack motivation—they simply haven't found an approach that addresses the underlying causes of their health challenges.",
        type: 'checkbox',
        options: [
            "Diets",
            "Exercise",
            "Weight-loss medications",
            "Supplements",
            "Nutrition coaching",
            "Personal trainer",
            "I've tried almost everything.",
            "I haven't seriously tried yet."
        ]
    },
    {
        id: 'q8',
        title: "If nothing changed over the next 12 months, what concerns you the most?",
        subtitle: "",
        insight: "Small changes made today can often prevent much bigger health problems tomorrow.",
        type: 'radio',
        options: [
            "Gaining more weight",
            "Taking more medications",
            "Developing diabetes",
            "My health continuing to decline",
            "Having less energy",
            "Missing out on life"
        ]
    },
    {
        id: 'q9',
        title: "On a scale of 1–10, how ready are you to improve your health?",
        subtitle: "Slider: 1 = Not Ready • 10 = I'm Ready",
        insight: "Your readiness helps us personalize recommendations that match where you are today.",
        type: 'slider',
        min: 1,
        max: 10
    },
    {
        id: 'q10',
        title: "If your results suggested that your health could improve with the right guidance, which best describes you?",
        subtitle: "",
        insight: "Everyone's journey is different. Our goal is simply to recommend the next step that's most appropriate for you.",
        type: 'radio',
        options: [
            "I'd like to improve my health on my own.",
            "I'd be open to learning more if it's physician-led.",
            "I'd be interested in a structured step-by-step program.",
            "I'd want someone to coach and keep me accountable.",
            "I'm just curious about my results."
        ]
    },
    {
        id: 'q11',
        title: "Which statement best describes where you are today?",
        subtitle: "",
        insight: "Lasting health improvements usually require more than information—they require the right plan, support, and consistency.",
        type: 'radio',
        options: [
            "I want to understand what's causing my health problems.",
            "I want a clear plan that actually works.",
            "I know what to do, but I struggle to stay consistent.",
            "I think I need accountability to stay on track.",
            "I'm ready to make a serious change."
        ]
    }
];
