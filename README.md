# Health Score Assessment Plugin

A custom WordPress plugin that provides an interactive "60-Second Metabolic Health Assessment" tool. This plugin acts as a lead generation engine by calculating a personalized health score, delivering a category snapshot PDF report, and encouraging users to book a consultation.

## Features
- **Interactive React UI:** Smooth, multi-step assessment flow with no page reloads.
- **State Persistence:** Autosaves progress in the browser (`localStorage`) so users never lose their answers.
- **Exit-Intent Capture:** Prompts users if they attempt to abandon the form.
- **Lead Capture:** Collects first name and email.
- **Dynamic Scoring Logic:** Calculates a Risk Score, Health Score (0-100), Category, and identifies the Top 3 Main Concerns.
- **Fixed PDF & Async Email Delivery:** Delivers category-specific PDF reports via WP-Cron background task.
- **UTM Source Tracking:** Captures URL parameters (e.g., `?utm_source=facebook`) to track lead generation efforts.
- **Staff Admin Dashboard:** Provides a secure WP Admin interface for staff to view, filter, and manage incoming leads.

## Installation & Setup

1. **Upload/Clone the Plugin:** Place the plugin folder inside your `/wp-content/plugins/` directory.
2. **Install Dependencies:**
   Install Node dependencies and build the React assets:
   ```bash
   npm install
   npm run build
   ```
3. **Activate Plugin:** Go to your WordPress Admin panel -> Plugins and activate "Health Score Assessment".
4. **Configure SMTP:** For the PDF emails to actually reach user inboxes, ensure you have an SMTP plugin (like WP Mail SMTP) configured and active on your WordPress site.

## Usage

To display the assessment on any page or post, use the following shortcode:

```text
[health_score]
```

## Admin Dashboard

Clinical staff can view leads by navigating to **Health Leads** (the heart icon) in the WordPress Admin sidebar. 
- You can sort by **Readiness (1-10)** to prioritize follow-ups for highly motivated leads.
- You can view the specific **UTM Source** to track which marketing campaigns are performing best.

## Development

The frontend is built with React. If you need to make changes to the UI or the questions:
1. Edit files in the `/src/` directory.
2. The questions are configured in `/src/data/questions.js`.
3. Run `npm run dev` to watch for changes, or `npm run build` to compile for production.

The backend logic (API, Email, PDF, Admin UI) is located in the `/includes/` directory.

## License
Proprietary.
