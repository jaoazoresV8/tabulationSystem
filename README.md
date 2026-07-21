# CCDI TABULATION SYSTEM

## MASTER UI/UX PLAN

### Design Direction: Valorant Tactical Operations Interface

---

# CORE VISION

The CCDI Tabulation System should not feel like a Laravel CRUD application.

It should feel like professional tournament software used behind the scenes at an international esports event.

The inspiration comes from Valorant's tactical UI language—not its gameplay. The interface emphasizes precision, confidence, speed, and clarity through sharp geometry, restrained animations, and highly structured layouts. Every interaction should communicate authority and reliability.

Three.js is used only to enhance immersion through subtle depth and motion, never to distract from the workflow.

---

# DESIGN PRINCIPLES

## 1. Tactical, Not Playful
Every screen should feel like a command center. No rounded components. No glassmorphism. No neon overload. No unnecessary gradients. Every panel should appear engineered.

## 2. Information First
Users spend 95% of their time reading data. The UI should prioritize: rankings, scores, judges, contestants, contest status. Visual effects should never compete with the information.

## 3. Sharp Geometry
Every card uses clipped corners. Borders use thin technical lines. Spacing follows an 8px design grid. Everything aligns precisely.

## 4. Motion With Purpose
Every animation must communicate state. Hover → Lift → Selection → Highlight → Complete. No decorative animations.

---

# COLOR SYSTEM
- Primary Background: `#0B0F14`
- Secondary Background: `#11161F`
- Card: `#171C24`
- Elevated Card: `#1C2430`
- Border: `#2A313D`
- Accent: `#FF4655`
- Success: `#00D084`
- Warning: `#F5B941`
- Danger: `#FF5D5D`
- Text: `#F4F6F8`
- Secondary Text: `#8C97A8`
- Disabled: `#5A6475`

---

# TYPOGRAPHY
- Hero Titles: **Bebas Neue** (Large, Uppercase, Tracking +5)
- Section Titles: **Inter Bold**
- Tables: **Inter Medium**
- Numbers: **JetBrains Mono**
- Judge Access Codes: **JetBrains Mono Bold**

---

# IMPLEMENTATION STATUS
- [x] Base Layout (`layouts.app`, `layouts.admin`)
- [x] Tailwind CSS 4 Theme Configuration
- [x] Typography Setup (Google Fonts)
- [x] Tactical UI Components (Clipped corners, Borders)
- [x] Dashboard (`welcome.blade.php`)
- [x] Authentication System (Admin & Judge Login)
- [x] Contest Management (Create, Settings, Exposures, Criteria, Contestants, Judges)
- [x] Live Tabulation Interface
- [x] Live Leaderboard View
- [x] Results & Reports UI
- [x] Judge Scoring Interface
- [x] Database Schema & Migrations
- [x] Authentication Logic (Controllers/Middleware)
- [ ] Real-time updates (Laravel Reverb/Echo)
- [ ] Three.js Integration (Background Grid & Trophy)
