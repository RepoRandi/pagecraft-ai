# 🚀 PageCraft AI — AI Sales Page Generator

PageCraft AI is a full-stack Laravel web application that transforms raw product or service information into a structured, persuasive, and export-ready sales page using AI.

This project was built for the **PT Dakwah Digital Network Technical Task (Option B: AI Sales Page Generator)**. The goal is not only to meet the feature requirements, but also to demonstrate production-minded engineering through AI integration, database persistence, fallback handling, secure deployment, and modern UI/UX.

---

## 🌍 Live Demo

**Live Application:**  
https://pagecraft-ai-production-d910.up.railway.app

**Video Walkthrough:**  
https://youtu.be/qzNqg590HVE

**Source Code:**  
https://github.com/RepoRandi/pagecraft-ai

---

## 👤 Dummy Account

Use this account for quick review and testing:

```txt
Email: reviewer@demo.com
Password: password123
```

---

## ✨ Main Features

### 🔐 Authentication

The application includes user authentication using Laravel authentication flow.

Features:

- Register
- Login
- Logout
- Profile management
- Secure session handling
- Protected dashboard routes

---

### 📝 Product Input Form

Users can submit structured product or service information that will be used by the AI generator.

Input fields:

- Product / Service Name
- Product Description
- Key Features
- Target Audience
- Price
- Unique Selling Points
- Design Template

Each field contributes to the generated sales page structure. For example, the product name helps generate the headline, the target audience influences the copy tone, and the price is used to build the pricing section.

---

### 🤖 AI Sales Page Generation

The system sends product data to an LLM API and generates structured sales page content.

Generated sections include:

- Compelling headline
- Subheadline
- Product description
- Benefits section
- Features breakdown
- Social proof placeholder
- Pricing display
- Clear call-to-action

The output is rendered as a styled landing page preview, not raw text.

---

### 🧠 Hybrid AI System

PageCraft AI uses a hybrid generation strategy to improve reliability.

#### 1. Primary Generator — Gemini API

The primary generator uses Gemini API to generate persuasive and structured sales page content.

Key characteristics:

- Structured JSON output
- Prompt designed for concise marketing copy
- Token usage optimization
- Conversion-focused content generation

#### 2. Local Fallback Generator

If the external AI API fails, times out, or reaches quota limits, the system automatically uses a local fallback generator.

Why this matters:

- The app still produces output even when the AI API fails
- Better user experience
- More reliable demo and production behavior
- Reduced dependency risk on external services

---

### 📚 Saved Pages

All generated sales pages are saved into the database.

Users can:

- View generated page history
- Search saved pages
- Filter by template
- Preview generated pages
- Export pages
- Delete saved pages

In production, saved pages are persisted using PostgreSQL on Railway.

---

### 👀 Live Preview

Generated content is displayed in a live preview mode that resembles a real landing page layout.

The preview includes:

- Hero section
- Headline and subheadline
- Benefit cards
- Feature breakdown
- Social proof section
- Pricing section
- CTA section

This makes the result easier to review and more useful than plain generated text.

---

### 📦 Export HTML

Users can export a generated sales page as a standalone HTML file.

This allows generated pages to be reused outside the application, shared, or deployed separately.

---

## 🔥 Bonus Features

This project includes several optional improvements beyond the core requirements:

- Multiple design templates:
  - Dark Luxury
  - Minimal Clean
  - Glassmorphism
- Copy actions:
  - Copy Headline
  - Copy CTA
  - Copy Full Copy
- Conversion Score system
- Section-by-section regeneration:
  - Regenerate Headline
  - Regenerate CTA
  - Regenerate Benefits
- Loading states for better UX
- Search and filter for saved pages
- PostgreSQL production database
- Railway deployment

---

## 🧠 AI Strategy

The AI implementation is designed with reliability and cost-awareness in mind.

### Prompt Strategy

The prompt asks the AI to return a clean JSON structure only. This helps the application parse and render the result consistently.

Benefits:

- Easier parsing
- More predictable response format
- Cleaner integration with Blade views
- Less post-processing complexity

---

### Cost Optimization

To reduce unnecessary AI usage, the system applies:

- Concise prompt structure
- Limited output tokens
- Rate limiting on AI endpoints
- Section-based regeneration instead of regenerating the whole page
- Fallback generator when AI is unavailable

---

### Reliability Strategy

The system is designed to avoid total failure when the AI API is unavailable.

Flow:

```txt
User Input
   ↓
Gemini API Request
   ↓
If success → Save AI result
   ↓
If failed → Use fallback generator
   ↓
Save generated content
   ↓
Render live preview
```

---

## 🏗 System Architecture

The application follows a simple and maintainable structure.

```txt
Controller
   ↓
Service Layer (GeminiService)
   ↓
AI Generator / Fallback Generator
   ↓
Database
   ↓
Blade UI
```

### Components

#### Controller

Responsible for handling HTTP requests, validating input, storing generated pages, and returning views.

#### GeminiService

Responsible for:

- Building AI prompts
- Calling Gemini API
- Parsing AI response
- Handling errors
- Returning fallback content when needed

#### Database

Stores:

- Users
- Generated sales pages
- Page content
- Template selection
- Timestamps

#### Blade UI

Responsible for rendering:

- Dashboard
- Product input form
- Saved page list
- Live preview
- Exportable page output

---

## 🗄 Database Configuration

This project supports different database setups for local development and production.

---

### Local Development Database

For local development, SQLite can be used because it is lightweight and easy to set up.

Example `.env` configuration:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Create the SQLite file:

```bash
touch database/database.sqlite
```

Run migration:

```bash
php artisan migrate
```

---

### Production Database

For production, this project uses PostgreSQL hosted on Railway.

Example Railway environment configuration:

```env
DB_CONNECTION=pgsql
DB_HOST=postgres.railway.internal
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=********
```

PostgreSQL is used in production because it provides:

- Reliable data persistence
- Better scalability
- Better suitability for deployed applications
- Industry-standard relational database support

---

## 🚀 Deployment

The application is deployed using Railway.

### Deployment Platform

- Railway for Laravel app hosting
- Railway PostgreSQL for production database
- Railway environment variables for secret management

---

### Production Deployment Strategy

The production deployment uses:

- Laravel app container
- PostgreSQL managed database
- HTTPS public domain
- Environment variables for database and Gemini API credentials
- Automatic migration during deployment

Example production start command:

```bash
php artisan optimize:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT}
```

Important note:

- `php artisan migrate --force` is used for production migration.
- `php artisan migrate:fresh --force` should not be used in normal production deployment because it will reset existing data.

---

## 🔐 Security & Reliability

Security and reliability considerations include:

- HTTPS enforced in production
- CSRF protection enabled
- Laravel validation for user input
- Authentication-protected routes
- Rate limiting for AI generation endpoints
- Fallback generator for AI failure scenarios
- Environment variables for sensitive credentials
- PostgreSQL persistence for production data

---

## ⚙️ Tech Stack

- Laravel 13
- Laravel Breeze
- Blade
- Tailwind CSS
- Vite
- PostgreSQL (Production)
- SQLite (Local Development)
- Gemini API
- Railway Deployment

---

## ⚙️ Installation & Setup

### 1. Clone Repository

```bash
git clone https://github.com/RepoRandi/pagecraft-ai.git
cd pagecraft-ai
```

---

### 2. Install Dependencies

```bash
composer install
npm install
```

---

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4. Setup Local Database

```bash
touch database/database.sqlite
```

Update `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

### 5. Run Migration

```bash
php artisan migrate
```

---

### 6. Build Frontend Assets

For development:

```bash
npm run dev
```

For production build:

```bash
npm run build
```

---

### 7. Run Application

```bash
php artisan serve
```

Open:

```txt
http://127.0.0.1:8000
```

---

## 🚀 Quick Test Flow

1. Open the live demo or local app
2. Register or login
3. Click **Use Example** to auto-fill sample product data
4. Click **Generate Sales Page**
5. Wait for AI generation
6. Open preview page
7. Test copy buttons
8. Test section regeneration
9. Export HTML
10. Delete saved page if needed

---

## 🧪 Example System Flow

```txt
User submits product information
   ↓
Laravel validates request
   ↓
SalesPageController sends data to GeminiService
   ↓
GeminiService calls Gemini API
   ↓
If Gemini succeeds, AI content is returned
   ↓
If Gemini fails, fallback content is generated
   ↓
Generated content is saved to PostgreSQL
   ↓
User sees dashboard and live preview
   ↓
User can export, regenerate, copy, search, or delete
```

---

## 🎨 UI/UX Design

The UI is designed with a modern SaaS-style experience.

Design characteristics:

- Dark theme
- Glassmorphism cards
- Responsive layout
- Loading overlays
- Clear dashboard layout
- Presentable landing page preview
- Action buttons for copy, export, regenerate, and delete

The focus is not only on functionality, but also on making the AI output feel useful and visually presentable.

---

## ⚠️ Limitations

Current limitations:

- AI output depends on Gemini API availability
- No image generation yet
- No collaborative editing
- No version history for generated pages
- Export is currently HTML only

---

## 🚀 Future Improvements

Potential future improvements:

- Multi-language generation
- More advanced template editor
- Image generation integration
- A/B testing support
- Analytics dashboard
- Version history for generated pages
- Team collaboration workspace
- PDF export
- Custom branding options

---

## 🏁 Conclusion

This project demonstrates:

- Full-stack Laravel development
- AI integration with Gemini API
- Fallback strategy for reliability
- PostgreSQL production database integration
- Modern UI/UX implementation
- Secure and production-aware deployment
- Cost-conscious AI usage

The system is designed not only to meet the technical task requirements, but also to reflect real-world engineering considerations such as scalability, reliability, maintainability, and cost efficiency.

---

## 👨‍💻 Author

**Randi Maulana Akbar**  
Senior Software Engineer (Mobile & Fullstack)

Email: devs.randi@gmail.com  
WhatsApp: +62 819-3464-4920  
GitHub: https://github.com/RepoRandi  
LinkedIn: https://linkedin.com/in/randi-dev

---

## 🤝 Notes for Reviewer

If you have any questions or would like a deeper walkthrough of the system, feel free to reach out via WhatsApp or email.

I would be happy to explain:

- System architecture
- AI strategy using Gemini API and fallback generator
- PostgreSQL production database setup
- Railway deployment process
- Performance and cost optimization decisions
- Future scalability approach

Thank you for your time and consideration 🙏
