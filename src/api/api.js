export const submitAssessment = async (answers) => {
    // healthScoreData is passed via wp_localize_script in PHP
    const { restUrl, nonce } = window.healthScoreData || {};

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
