# 🌸✨ TrackiNime — Your Ultimate Anime Tracking Companion ✨🌸

> **Track. Discover. Enjoy.**  
> A clean, fun, anime-inspired platform to organize and explore your favorite anime.

---

<p align="center">
  <img src="https://github.com/crissa-ya/trackinime/blob/main/traackinime.png?raw=true" alt="TrackiNime Logo" width="260">
</p>

<p align="center">
  <em>TrackiNime — keeping your anime journey simple, fast, and enjoyable.</em>
</p>

---

## 🌌 About TrackiNime

**TrackiNime** is a **web-based anime tracking platform** that helps users track, organize, and discover anime easily.

It offers:

- ✔ Smooth browsing experience  
- ✔ Personal watchlist  
- ✔ Search & filter anime  
- ✔ Explore top, most-watched, and newly added anime  
- ✔ Clean anime-themed interface  
- ✔ Admin panel for full management  

With a beautiful UI and fast performance, TrackiNime makes anime tracking **simple, fun, and organized**.

---

<p align="center">
  <img src="YOUR_BACKGROUND_IMAGE_LINK_HERE" alt="Anime Sky Background" width="100%">
</p>

---

## 🎌 Features

### 🌟 User Features
- 🔎 Browse & search anime  
- ❤️ Add anime to "My List"  
- 🎨 Aesthetic anime-themed UI  
- 📄 View descriptions, genres, episodes & release dates  
- ⭐ Ratings & watch counters  
- 👤 User accounts with profile images  

### 🛡️ Admin Features
- 📊 Admin dashboard  
- ➕ Add anime entries  
- ✏️ Edit anime  
- 🗑️ Delete anime  
- 👥 Manage users  
- 📈 Monitor watch counts  

---

## 🗄️ Database Structure

### 📝 `about` Table
```sql
id INT PRIMARY KEY AUTO_INCREMENT,
content TEXT
```

---

### 🎬 `anime` Table
```sql
id INT PRIMARY KEY AUTO_INCREMENT,
title VARCHAR(255),
description TEXT,
genre VARCHAR(100),
episodes INT,
release_date DATE,
rating FLOAT,
cover_image VARCHAR(255),
watch_count INT DEFAULT 0,
section ENUM('top','most','new') DEFAULT 'new',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
```

---

### 👤 `users` Table
```sql
id INT PRIMARY KEY AUTO_INCREMENT,
fullname VARCHAR(150),
username VARCHAR(100) UNIQUE,
password VARCHAR(255),
email VARCHAR(150),
address VARCHAR(255),
birthdate DATE,
role ENUM('user','admin') DEFAULT 'user',
profile_image VARCHAR(255)
```

---

### 📚 `user_list` Table
```sql
id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT,
anime_id INT,
anime_title VARCHAR(255)
```

---

## 🌸 Screenshots

> Replace these links after uploading images to GitHub.

### 🏠 Home Page
<p align="center">
  <img src="YOUR_HOME_IMAGE_LINK_HERE" width="80%">
</p>

### 👤 User Dashboard
<p align="center">
  <img src="YOUR_USER_DASHBOARD_IMAGE_LINK_HERE" width="80%">
</p>

### 🛡️ Admin Dashboard
<p align="center">
  <img src="YOUR_ADMIN_DASHBOARD_IMAGE_LINK_HERE" width="80%">
</p>

---

## 🛠️ Tech Stack

| Layer     | Technology |
|-----------|------------|
| Frontend  | HTML, CSS, PHP |
| Backend   | PHP |
| Database  | MySQL |
| Server    | XAMPP / Apache |
| Design    | Anime-themed UI |

---

## 🚀 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/YOUR_USERNAME/trackinime.git
```

### 2. Import the Database
- Open **phpMyAdmin**
- Create database: `trackinime_db`
- Import the SQL file inside the project

### 3. Place in XAMPP
Move the folder to:

```
xampp/htdocs/
```

### 4. Start Server
Run Apache & MySQL in XAMPP.

### 5. Open in Browser
```
http://localhost/trackinime
```

---

## 💖 Credits

- 🎨 Logo: Custom-designed  
- 🌌 Background: Anime aesthetic sky art  
- 🖥️ UI Prototype: TrackiNime PDF  

<p align="center">  
  Made with 💙 for anime lovers  
</p>

---

## 🏮 License
This project is open-source and free to use.

