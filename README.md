# 📘 ClassMonitor

**Smart Attendance & Quiz Management System**

ClassMonitor is a modern **Laravel-based classroom management system** designed to help teachers manage attendance sessions, quizzes, and student performance efficiently, while giving students a clean and intuitive experience.

---

## 🚀 Features Overview

### 👩‍🏫 Teacher Features
- ✅ Role-based teacher dashboard  
- ✅ Create attendance sessions with auto-expiry  
- ✅ Live attendance tracking  
- ✅ QR code–based attendance joining  
- ✅ Attendance session history  
- ✅ Export attendance data as CSV  
- ✅ Create quizzes (MCQ, True/False, Short Answer)  
- ✅ Edit & delete quiz questions  
- ✅ Start / stop quizzes  
- ✅ Auto-scoring for MCQs  
- ✅ Manual grading for short-answer questions  
- ✅ Quiz leaderboard (ranked by score)  
- ✅ Quiz performance snapshot  

---

### 🎓 Student Features
- ✅ Student dashboard  
- ✅ Join attendance using session code or QR  
- ✅ View attendance history  
- ✅ Play quizzes with timer countdown  
- ✅ Auto-submit quiz when time ends  
- ✅ View quiz results (correct vs wrong answers)  
- ✅ Quiz history with scores  

---

## 🧠 Tech Stack

| Layer | Technology |
|------|-----------|
| Backend | Laravel 12 |
| Frontend | Blade + Tailwind CSS |
| Database | MySQL |
| Authentication | Laravel Breeze |
| Authorization | Role-based middleware |
| CSV Export | Native PHP streams |
| UI / Charts | Blade + Tailwind |

---

## 📂 Project Structure, Roles & Middleware

```txt
CLASSMONITOR/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── AttendanceController.php
│   │   │   ├── Controller.php
│   │   │   ├── ProfileController.php
│   │   │   ├── QuizController.php
│   │   │   ├── StudentDashboardController.php
│   │   │   └── TeacherDashboardController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── EnsureStudent.php
│   │   │   └── EnsureTeacher.php
│   │   │
│   │   ├── Requests/
│   │   └── Models/
│   │
│   └── Providers/
│
│
├── database/
│   ├── database.sqlite
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   ├── build/
│   ├── images/
│   │  ├── cm-logo.png
│   │  ├── focus-1.png
│   │  ├── focus-2.png
│   │  ├── focus-3.png
│   │  ├── login-illustration.png
│   │  └── welcome-hero-bg.png
│   
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── attendance/
│       │   ├── index.blade.php
│       │   ├── join.blade.php
│       │   ├── show.blade.php
│       │   └── student_history.blade.php
│       │
│       ├── auth/
│       ├── components/
│       │
│       ├── dashboard/
│       │   ├── student.blade.php
│       │   └── teacher.blade.php
│       │
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   └── navigation.blade.php
│       │
│       ├── profile/
│       │
│       ├── quizzes/
│       │   ├── grading/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   │
│       │   ├── create.blade.php
│       │   ├── edit_question.blade.php
│       │   ├── history.blade.php
│       │   ├── index.blade.php
│       │   ├── leaderboard.blade.php
│       │   ├── manage.blade.php
│       │   ├── play.blade.php
│       │   ├── result.blade.php
│       │   ├── results.blade.php
│       │   └── show.blade.php
│       │
│       ├── dashboard.blade.php
│       └── welcome.blade.php
│
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php


```
## 🔐 Role System

| Role | Access |
|-----|-------|
| Teacher | Dashboard, Attendance, Quiz creation & grading |
| Student | Attendance join, Quiz play, History |

**Middleware Used**
- `EnsureTeacher.php`
- `EnsureStudent.php`

---

## 🧾 Attendance Flow
1. Teacher creates an attendance session  
2. System generates a unique session code / QR  
3. Students join using code or QR  
4. Live check-ins update automatically  
5. Teacher ends the session  
6. Attendance history stored permanently  
7. CSV export available  

---

## 📝 Quiz Flow

### Teacher
- Create quiz  
- Add questions & options  
- Start quiz  
- View leaderboard  
- Stop quiz  
- Manually grade short answers  

### Student
- Open active quiz  
- Timer starts automatically  
- Submit answers or auto-submit on timeout  
- View result breakdown  
- Access quiz history  

---

## 📊 Leaderboard Logic
- Sorted by **highest score**
- Tie-breaker: **earlier submission**
- Available per quiz

---

## 🕒 Timer System
- Quiz duration set by teacher (minutes)
- Countdown visible to students
- Auto-submit at zero
- Server-side validation for fairness

---

## 📦 Installation Guide

### 1️⃣ Clone Repository
```bash
git clone https://github.com/your-username/classmonitor.git
cd classmonitor
```
2️⃣ Install Dependencies
```bash
composer install
npm install && npm run build
```
3️⃣ Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Update .env:
```env
DB_DATABASE=classmonitor
DB_USERNAME=root
DB_PASSWORD=
```
4️⃣ Run Migrations
```bash
php artisan migrate
```
5️⃣ Start Server
```bash
php artisan serve
```
Visit 👉 http://127.0.0.1:8000
---
## 🔮 Future Improvements
- Analytics dashboards
- Student performance trends
- Notifications
- Mobile-friendly PWA

---
## 👩‍💻 Developer

Faiza Akter Borsha<br>
ID: 232-134-022<br>
Batch 5th<br>
Project – ClassMonitor
---