export const questions = [
    {
        id: 'q1',
        title: "Which of these are you currently struggling with?",
        subtitle: "(Check all that apply.)",
        insight: "Many people are surprised to discover these concerns often occur together and may share common underlying contributors.",
        type: 'checkbox',
        options: [
            "Weight that's difficult to lose",
            "Low energy or fatigue",
            "Poor sleep",
            "Blood sugar concerns",
            "High blood pressure",
            "High cholesterol",
            "Brain fog",
            "High stress",
            "I take more medications than I'd like",
            "I don't feel like myself anymore"
        ]
    },
    {
        id: 'q2',
        title: "If you could improve ONE thing over the next six months...",
        subtitle: "",
        insight: "Improving one area often creates positive improvements in others.",
        type: 'radio',
        options: [
            "Lose weight",
            "Have more energy",
            "Sleep better",
            "Improve blood sugar",
            "Improve blood pressure",
            "Reduce medications (with physician guidance)",
            "Feel healthier overall"
        ]
    },
    {
        id: 'q3',
        title: "How long have these concerns been affecting you?",
        subtitle: "",
        insight: "You're not alone. Many people live with these concerns for years before discovering a more structured approach.",
        type: 'radio',
        options: [
            "Less than 6 months",
            "6–12 months",
            "1–3 years",
            "More than 3 years"
        ]
    },
    {
        id: 'q4',
        title: "What have you already tried?",
        subtitle: "(Check all that apply.)",
        insight: "Many people aren't lacking motivation—they simply haven't found an approach that addresses the underlying contributors to their health.",
        type: 'checkbox',
        options: [
            "Diets",
            "Exercise",
            "Supplements",
            "Medications",
            "Weight-loss programs",
            "I've tried almost everything",
            "I haven't really tried yet"
        ]
    },
    {
        id: 'q5',
        title: "Which statement best describes your energy?",
        subtitle: "",
        insight: "Energy is often influenced by sleep, nutrition, stress, and metabolic health—not just how much you sleep.",
        type: 'radio',
        options: [
            "I feel energetic most days.",
            "I often crash in the afternoon.",
            "I rely on caffeine most days.",
            "I'm tired most of the day."
        ]
    },
    {
        id: 'q6',
        title: "How often do you experience cravings for sugar, bread, snacks, or caffeine?",
        subtitle: "",
        insight: "Cravings can sometimes be a sign that your body isn't regulating energy as efficiently as it could.",
        type: 'radio',
        options: [
            "Rarely",
            "Occasionally",
            "Daily",
            "Multiple times per day"
        ]
    },
    {
        id: 'q7',
        title: "Do you currently have any of these conditions?",
        subtitle: "(Check all that apply.)",
        insight: "Many chronic health conditions share common lifestyle and metabolic contributors.",
        type: 'checkbox',
        options: [
            "Prediabetes",
            "Type 2 Diabetes",
            "High Blood Pressure",
            "High Cholesterol",
            "Fatty Liver",
            "Thyroid Concerns",
            "Sleep Apnea",
            "None"
        ]
    },
    {
        id: 'q8',
        title: "If nothing changed over the next year... What concerns you the most?",
        subtitle: "",
        insight: "Small changes today can often make a meaningful difference over time.",
        type: 'radio',
        options: [
            "Taking more medications",
            "Gaining more weight",
            "Having less energy",
            "My health continuing to decline",
            "Not enjoying life the way I'd like"
        ]
    },
    {
        id: 'q9',
        title: "On a scale of 1–10... How ready are you to improve your health?",
        subtitle: "1 = Not ready, 10 = Ready right now",
        insight: "(This question is NOT part of the Health Score. It is used internally to measure readiness.)",
        type: 'slider',
        min: 1,
        max: 10
    }
];
