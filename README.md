# quiz-website
Server-driven PHP quiz application with user authentication, saved scores, profile history, and a leaderboard.

## About
This repository contains a PHP/MySQL quiz application. The server decides which questions to include, starts the game, tracks score, and serves the HTML/CSS/JS assets.

## Project Structure
```
quiz-website/
├── backend/
│   └── database.sql
├── css/
│   └── styles.css
├── js/
│   └── app.js
├── assets/
├── index.php
├── login.php
├── logout.php
├── signup.php
├── quiz.php
├── results.php
├── profile.php
├── leaderboard.php
├── init.php
├── questions.json
└── README.md
```

## How to Run
1. Create a MySQL database and user or use your local MySQL server.
2. Import `backend/database.sql` into MySQL.
3. Update `init.php` if your database credentials differ from:
   - host: `localhost`
   - database: `quiz_app`
   - user: `root`
   - password: ``
4. Place the app in a PHP-enabled web server document root.
5. Visit `index.php` in your browser.

## Features
- Home page with login/signup and quiz entry.
- Signup/login with password hashing.
- Server-driven 10-question quiz selected randomly from `questions.json`.
- Results page with score and answer summary.
- User profile page showing play history.
- Leaderboard page showing the top 10 quiz scores.

## Notes
- The quiz uses PHP sessions to track game progress and store the current question set.
- Each play generates a new random question set so users receive different questions on each attempt.
