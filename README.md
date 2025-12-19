📘 ClassMonitor

Smart Attendance & Quiz Management System

ClassMonitor is a modern Laravel-based classroom management system designed to help teachers manage attendance sessions, quizzes, and student performance efficiently, while giving students a simple and clean experience to participate and track their progress.

🚀 Features Overview
👩‍🏫 Teacher Features

✅ Role-based teacher dashboard

✅ Create attendance sessions with auto-expiry

✅ Live attendance tracking

✅ QR code–based attendance joining

✅ Attendance session history

✅ Export attendance data as CSV

✅ Create quizzes (MCQ, True/False, Short Answer)

✅ Edit & delete quiz questions

✅ Start / stop quizzes

✅ Auto-scoring for MCQs

✅ Manual grading for short-answer questions

✅ Quiz leaderboard (ranked by score)

✅ Quiz performance snapshot on dashboard

🎓 Student Features

✅ Student dashboard

✅ Join attendance using session code

✅ View attendance history

✅ Play quizzes with timer countdown

✅ Auto-submit quiz when time ends

✅ View quiz results (correct vs wrong answers)

✅ Quiz history with scores

🧠 Tech Stack
Layer	Technology
Backend	Laravel 12
Frontend	Blade + Tailwind CSS
Database	MySQL
Authentication	Laravel Breeze
Authorization	Role-based middleware
CSV Export	Native PHP streams
Charts / UI	Tailwind + Blade components
📂 Project Structure (Key Files)
app/
 ├── Http/
 │   ├── Controllers/
 │   │   ├── TeacherDashboardController.php
 │   │   ├── AttendanceController.php
 │   │   ├── QuizController.php
 │   └── Middleware/
 │       ├── EnsureTeacher.php
 │       └── EnsureStudent.php

resources/
 ├── views/
 │   ├── dashboard/
 │   │   ├── teacher.blade.php
 │   │   └── student.blade.php
 │   ├── attendance/
 │   ├── quizzes/
 │   └── student/

routes/
 └── web.php

🔐 Role System
Role	Access
Teacher	Dashboard, Attendance, Quiz creation & grading
Student	Attendance join, Quiz play, History

Middleware used:

teacher

student

🧾 Attendance Flow

Teacher creates an attendance session

System generates a unique session code

Students join using code or QR

Live check-ins update automatically

Teacher can end session anytime

Attendance history stored permanently

CSV export available

📝 Quiz Flow
Teacher

Create quiz

Add questions & options

Start quiz

View live leaderboard

Stop quiz

Manually grade short answers (optional)

Student

Open active quiz

Timer starts automatically

Submit answers or auto-submit on timeout

View result breakdown

Access quiz history

📊 Leaderboard Logic

Sorted by highest score

Tie-breaker: earlier submission

Top results shown on dashboard

Full leaderboard available per quiz

🕒 Timer System

Quiz duration (minutes) set by teacher

Countdown visible to students

Auto-submit when timer reaches zero

Server-side validation ensures fairness

📦 Installation Guide
1️⃣ Clone the Repository
git clone https://github.com/your-username/classmonitor.git
cd classmonitor

2️⃣ Install Dependencies
composer install
npm install && npm run build

3️⃣ Environment Setup
cp .env.example .env
php artisan key:generate


Update .env database credentials:

DB_DATABASE=classmonitor
DB_USERNAME=root
DB_PASSWORD=

4️⃣ Run Migrations
php artisan migrate

5️⃣ Start Server
php artisan serve


Visit:
👉 http://127.0.0.1:8000

🧪 Test Accounts (Example)
Role	Email	Password
Teacher	teacher@test.com
	password
Student	student@test.com
	password
📤 Export Features

Attendance CSV export

Includes session code, time, and check-in count

Excel-compatible

🔮 Future Improvements

Live quiz answer monitoring

Graph-based analytics

Student performance trends

Notifications

Mobile-friendly PWA

👩‍💻 Developer

Faiza Akter Borsha
Laravel Project – ClassMonitor
