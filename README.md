# 🌸✨ TrackiNime — Your Ultimate Anime Tracking Companion ✨🌸

> **Track. Discover. Enjoy.**  
> A clean, fun, anime-inspired platform to organize and explore your favorite anime.

---
<div style="position: relative; width: 100vw; height: 100vh; overflow: hidden;">
  <img src="https://github.com/crissa-ya/trackinime/blob/main/Screenshot%202025-12-06%20211157.png?raw=true" 
       alt="TrackiNime Logo" 
       style="width: 100%; height: 100%; object-fit: cover;">
</div>




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
CREATE TABLE `about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 🎬 `anime` Table
```sql
CREATE TABLE `anime` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `episodes` int(11) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `watch_count` int(11) DEFAULT 0,
  `section` enum('top','most','new') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 👤 `users` Table
```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(150) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 📚 `user_list` Table
```sql
CREATE TABLE `user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `anime_id` int(11) DEFAULT NULL,
  `anime_title` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```
**ERD**

<div style="position: relative; width: 100vw; height: 100vh; overflow: hidden;">
  <img src="https://github.com/crissa-ya/trackinime/blob/main/Screenshot%202025-12-06%20211157.png?raw=true" 
       alt="TrackiNime Logo" 
       style="width: 100%; height: 100%; object-fit: cover;">
</div>

---

## 🌸 Screenshots

### 🏠 Home Page
<p align="center">
  <img src="https://github.com/crissa-ya/trackinime/blob/main/Screenshot%202025-12-06%20193203.png?raw=true" width="80%">
</p>

### 👤 User Dashboard
<p align="center">
  <img src="https://github.com/crissa-ya/trackinime/blob/main/Screenshot%202025-12-06%20193313.png?raw=true"?raw=true" width="80%">
</p>

### 🛡️ Admin Dashboard
<p align="center">
  <img src="https://github.com/crissa-ya/trackinime/blob/main/Screenshot%202025-12-06%20193247.png?raw=true" width="80%">
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

## 🏮 License
This project is open-source and free to use.

