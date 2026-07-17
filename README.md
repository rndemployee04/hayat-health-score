# Hayat Tayyiba Health Score Plugin

A custom WordPress plugin that provides an interactive "60-Second Health Score" assessment tool. This plugin acts as a lead generation engine by calculating a personalized health score, generating a customized PDF report, automatically registering users in WordPress, and seamlessly redirecting qualified leads to a booking calendar.

## Features
- **Interactive React UI:** Smooth, multi-step assessment flow with no page reloads.
- **State Persistence:** Autosaves progress in the browser (`localStorage`) so users never lose their answers.
- **Exit-Intent Capture:** Prompts users if they attempt to abandon the form.
- **Lead Capture & Auto-Registration:** Collects name and email, and automatically creates a `subscriber` account in WordPress.
- **Dynamic Scoring Logic:** Calculates a Risk Score, Health Score (0-100), Readiness Score (1-10), and identifies the Top 3 Health Opportunities.
- **Async PDF & Email Generation:** Generates a personalized PDF report using Dompdf and emails it to the user via a non-blocking WP-Cron background task.
- **UTM Source Tracking:** Captures URL parameters (e.g., `?utm_source=facebook`) to track lead generation efforts.
- **Staff Admin Dashboard:** Provides a secure WP Admin interface for staff to view, sort, and manage incoming leads by their Readiness Score.

## Installation & Setup

1. **Upload/Clone the Plugin:** Place the `hayat-health-score` folder inside your `/wp-content/plugins/` directory.
2. **Install Dependencies:**
   Navigate into the plugin directory and install PHP dependencies (Dompdf):
   ```bash
   composer install
   ```
   Install Node dependencies and build the React assets:
   ```bash
   npm install
   npm run build
   ```
3. **Activate Plugin:** Go to your WordPress Admin panel -> Plugins and activate "Hayat Tayyiba Health Score".
4. **Configure SMTP:** For the PDF emails to actually reach user inboxes, ensure you have an SMTP plugin (like WP Mail SMTP) configured and active on your WordPress site.

## Usage

To display the assessment on any page or post, simply use the following shortcode:

```text
[hayat_health_score]
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
Proprietary - Developed for Hayat Tayyiba.
