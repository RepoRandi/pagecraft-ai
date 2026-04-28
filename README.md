# 🚀 PageCraft AI — AI Sales Page Generator

PageCraft AI is a full-stack Laravel web application that transforms raw product or service input into a structured, persuasive, and export-ready sales page.

This project was built for the PT Dakwah Digital Network Technical Task (Option B: AI Sales Page Generator) and focuses on AI integration, system reliability, and production-minded design.

---

# 🌍 Live Demo
👉 https://pagecraft-ai-production-d910.up.railway.app

---

# ✨ Features

## 🔐 Authentication
- Register / Login / Logout
- Profile management
- Secure session handling

---

## 📝 Product Input Form
Users can input:
- Product / Service Name
- Description
- Key Features
- Target Audience
- Price
- Unique Selling Points
- Design Template

---

## 🤖 AI Sales Page Generation
Generates structured landing page content:
- Headline
- Subheadline
- Description
- Benefits
- Features
- Social Proof
- Pricing
- CTA (Call To Action)

---

## 🧠 Hybrid AI System (Core Strength)

This system uses a dual-layer generation approach:

### 1. Primary: Gemini API (LLM)
- High-quality structured generation
- JSON-based response format

### 2. Fallback Generator (Local)
- Activated if API fails
- Ensures system reliability
- Guarantees output even without AI

---

## 📚 Saved Pages
- Persistent storage (SQLite)
- Search & filter
- Delete functionality

---

## 👀 Live Preview
- Fully rendered landing page UI
- Based on selected template
- Real-time content visualization

---

## 📦 Export HTML
- Standalone HTML file
- Fully styled
- Ready for deployment

---

## 🔥 Bonus Features
- Multiple templates:
  - Dark Luxury
  - Minimal Clean
  - Glassmorphism
- Copy:
  - Headline
  - CTA
  - Full Copy
- Conversion Score system
- Regenerate section (headline / CTA / benefits)
- Loading states for UX improvement

---

# 🧠 AI Strategy

To ensure cost efficiency, reliability, and performance, the system implements:

- Structured prompts (JSON-only output)
- Token minimization
- Fallback system for API failure
- Rate limiting (per user)
- Controlled AI usage (avoid unnecessary calls)

### Goals:
- Stable output
- Predictable cost
- Production-ready behavior

---

# 🏗 Architecture

Controller    ↓ Service Layer (GeminiService)    ↓ AI / Fallback Generator    ↓ Database (SQLite)    ↓ Blade UI (Preview / Dashboard)

### Components:
- Controller → Request handling
- Service Layer → AI logic abstraction
- Fallback Generator → Reliability layer
- Views → UI rendering

---

# 🔐 Security & Reliability

- HTTPS enforced in production
- CSRF protection enabled
- Input validation (Laravel)
- Rate limiting (AI requests)
- Fallback mechanism (AI failure safe)
- Secure authentication (Laravel Breeze)

---

# ⚙️ Tech Stack

- Laravel 13
- Blade (Laravel Breeze)
- Tailwind CSS
- Vite
- SQLite
- Gemini API (optional)

---

# ⚙️ Installation & Setup

## 1. Clone Repository
bash git clone https://github.com/RepoRandi/pagecraft-ai.git cd pagecraft-ai 

## 2. Install Dependencies
bash composer install npm install 

## 3. Setup Environment
bash cp .env.example .env php artisan key:generate 

## 4. Setup Database
bash touch database/database.sqlite 

Edit .env:
DB_CONNECTION=sqlite DB_DATABASE=database/database.sqlite

## 5. Run Migration
bash php artisan migrate 

## 6. Run App
bash php artisan serve npm run dev 

Open:
http://127.0.0.1:8000

---

# 🚀 Quick Test Flow

1. Register
2. Login
3. Click Use Example
4. Click Generate Sales Page
5. Preview / Export / Copy / Regenerate

---

# 👤 Dummy Account

Email: reviewer@demo.com  
Password: password123  

---

# 🧪 Example Flow

User Input    ↓ AI Generation (Gemini)    ↓ Fallback (if failed)    ↓ Save to DB    ↓ Render Preview    ↓ Export HTML

---

# 🎨 UI/UX Design

- Modern SaaS interface
- Dark mode optimized
- Glassmorphism effects
- Responsive layout
- Loading overlays for async actions

---

# ⚠️ Limitations

- AI output depends on external API (Gemini)
- No media/image generation yet
- No collaborative editing
- No versioning for generated pages

---

# 🚀 Future Improvements

- Multi-language support
- AI fine-tuning for niche industries
- A/B testing for conversion optimization
- Image generation integration
- Team collaboration workspace
- Analytics dashboard

---

# 🏁 Conclusion

This project demonstrates:

- Full-stack Laravel development
- AI integration with fallback strategy
- Clean architecture principles
- Modern UI/UX implementation
- Production-aware design (cost, reliability, performance)

> This system is designed not only to meet feature requirements,
> but also to reflect real-world engineering considerations such as scalability, reliability, and cost efficiency.

---

# 👨‍💻 Author

**Randi Maulana Akbar**  

Senior Software Engineer (Mobile & Fullstack)

📧 Email: devs.randi@gmail.com  

📱 WhatsApp: +62 819-3464-4920 

🔗 GitHub: https://github.com/RepoRandi  

🔗 LinkedIn: https://linkedin.com/in/randi-dev

---

# 🤝 Notes for Reviewer

If you have any questions or would like a walkthrough of the system, feel free to reach out via WhatsApp or email.

I would be happy to explain:

- System architecture

- AI strategy (Gemini + fallback)

- Performance & cost optimization decisions

- Future scalability approach

Thank you for your time and consideration 🙏
