# RoleSenseAI

RoleSenseAI is a Laravel-based web application integrated with an AI agent for analyzing resumes in SAP roles. The AI agent uses LangChain and OpenAI's GPT-3.5-turbo to extract key information from resumes, including summaries, role recommendations, skill gaps, learning tips, and reasoning steps.

## Features

- **Resume Analysis**: Upload or provide a resume text/file to get detailed analysis.
- **SAP Role Focus**: Supports roles like MM (Materials Management), FICO (Financial Accounting and Controlling), PM (Project Management), SD (Sales and Distribution), and Basis.
- **AI-Powered Insights**: Generates summaries, identifies skill gaps, and provides learning recommendations.
- **Web Interface**: Built with Laravel and styled with TailwindCSS for a modern UI.

## Prerequisites

- PHP ^8.2
- Composer
- Node.js and npm
- Python 3.x (for the AI agent)
- OpenAI API Key (for LangChain integration)

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd RoleSenseAI
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install Node.js dependencies:
   ```bash
   npm install
   ```

4. Set up environment variables:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Generate application key:
     ```bash
     php artisan key:generate
     ```
   - Add your OpenAI API key to `.env`:
     ```
     OPENAI_API_KEY=your_api_key_here
     ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

6. Build assets:
   ```bash
   npm run build
   ```

## Usage

### Running the Application

Use the provided setup script:
```bash
composer run setup
```

Or run development servers manually:
```bash
composer run dev
```

This will start:
- Laravel server on `http://localhost:8000`
- Queue worker
- Log tailing
- Vite dev server for assets

### Using the AI Agent

The AI agent is located in `scripts/ai_agent.py`. Run it with a resume file:

```bash
python scripts/ai_agent.py path/to/resume.txt
```

It will output JSON with analysis results.

## Project Structure

- `app/`: Laravel application code
- `resources/`: Views, CSS, JS
- `routes/`: Web and API routes
- `scripts/`: AI agent script
- `database/`: Migrations and seeders
- `tests/`: PHPUnit tests
