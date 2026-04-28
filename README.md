# 🚀 PageCraft AI - AI Sales Page Generator

PageCraft AI is a Laravel-based web application that transforms raw product or service information into a structured, persuasive, and export-ready sales page.

This project was built for the **PT Dakwah Digital Network technical task (Option B: AI Sales Page Generator)**.

---

# ✨ Features

## 🔐 Authentication
- Register
- Login
- Logout
- Profile management

## 📝 Product Input Form
Users can input:
- Product or service name
- Description
- Key features
- Target audience
- Price
- Unique selling points
- Design template

---

## 🤖 AI Sales Page Generation
The system generates structured landing page content:
- Headline
- Subheadline
- Description
- Benefits
- Features
- Social proof
- Pricing
- CTA

---

## 🧠 Fallback Generator
If the AI API fails, the system uses a local fallback generator.

---

## 📚 Saved Pages
- View history
- Search
- Filter by template
- Delete

---

## 👀 Live Preview
Generated content is rendered as real landing page UI.

---

## 📦 Export HTML
- Standalone HTML
- Fully styled

---

## 🔥 Bonus Features
- Multiple templates (Dark, Minimal, Glass)
- Copy Headline / CTA / Full Copy
- Conversion Score
- Regenerate Sections

---

# 🛠 Tech Stack
- Laravel 13
- Breeze (Blade)
- Tailwind CSS
- Vite
- Gemini API (optional)
- SQLite

---

# ⚙️ Installation & Setup

## Clone Repo
git clone https://github.com/your-username/pagecraft-ai.git
cd pagecraft-ai

## Install
composer install
npm install

## Env
cp .env.example .env
php artisan key:generate

## Database
touch database/database.sqlite

Update .env:
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

## Migrate
php artisan migrate

## Run
php artisan serve
npm run dev

Open:
http://127.0.0.1:8000

---

# 🚀 Quick Test
1. Register
2. Login
3. Use Example
4. Generate Page
5. Preview / Export / Copy / Regenerate

---

# 👤 Dummy Account
Email: reviewer@demo.com  
Password: password123

---

# 🧠 Flow
Input → AI/Fallback → Save → Preview → Export

---

# 🎨 UI
Modern SaaS design with:
- Dark theme
- Glass effects
- Responsive layout

---

# 🏁 Conclusion
This project demonstrates full-stack Laravel + AI + UI/UX + product thinking.
