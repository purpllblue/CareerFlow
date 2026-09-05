# CareerFlow

**CareerFlow** is a modern web-based job application tracker built with **PHP, MySQL, Bootstrap, HTML, CSS, and JavaScript**. It is designed to help users organize and monitor their job search process, from managing applications and companies to tracking recruitment stages, deadlines, and important events.

## ✨ Features

* 📊 **Dashboard** — Overview of job applications and recruitment progress.
* 💼 **Application Management** — Add, edit, and manage job applications.
* 🏢 **Company Management** — Store and manage company information.
* 📅 **Calendar** — Track interviews, deadlines, and other important recruitment events.
* 👤 **Profile Management** — Manage personal profile information.
* 🎨 **Theme Customization** — Choose between Blue, Pink, Purple, and Black themes.
* 📂 **Collapsible Sidebar** — Expand or collapse the navigation sidebar.
* 🔐 **Login & Logout** — Simple session-based authentication.
* 💾 **Persistent Preferences** — Theme and sidebar preferences are saved locally.

## 🛠️ Built With

* **PHP** — Backend development
* **MySQL** — Database management
* **HTML5** — Page structure
* **CSS3** — Styling and responsive design
* **JavaScript** — Interactive functionality
* **Bootstrap 5.3.7** — UI components and responsive layout
* **Bootstrap Icons** — Interface icons
* **XAMPP** — Local development environment

## 📁 Project Structure

```text
CareerFlow/
├── admin/
│   ├── applications/
│   │   └── edit.php
│   ├── companies/
│   │   ├── index.php
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   └── hapus.php
│   ├── dashboard.php
│   ├── calendar.php
│   ├── profile.php
│   ├── lamaran/
│   │   └── index.php
│   └── logout.php
├── assets/
│   └── css/
│       └── theme.css
├── config/
│   └── koneksi.php
├── login.php
├── .gitignore
└── README.md
```

## 🚀 Getting Started

### Prerequisites

Make sure you have the following installed:

* [XAMPP](https://www.apachefriends.org/)
* PHP
* MySQL
* Web browser

### Installation

1. Clone this repository:

```bash
git clone https://github.com/purpllblue/CareerFlow.git
```

2. Move the project into the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\CareerFlow
```

3. Start **Apache** and **MySQL** from XAMPP Control Panel.

4. Create a MySQL database named:

```text
job_tracker
```

5. Import the required database tables into MySQL.

6. Check the database configuration in:

```text
config/koneksi.php
```

7. Open CareerFlow in your browser:

```text
http://localhost/CareerFlow/
```

## 🎨 Available Themes

CareerFlow provides four interface themes:

| Theme     | Description                |
| --------- | -------------------------- |
| 🔵 Blue   | Default professional theme |
| 🌸 Pink   | Soft and vibrant theme     |
| 🟣 Purple | Modern purple theme        |
| ⚫ Black   | Dark neutral theme         |

Theme preferences are stored locally in the browser and automatically restored when the application is opened again.

## 📌 Purpose

CareerFlow was created to provide a simple and organized way to manage the job application process. Instead of keeping application information across multiple notes or spreadsheets, users can manage their applications, companies, recruitment stages, deadlines, and events through a single web application.

## 🔮 Future Improvements

Potential improvements for future versions include:

* Application statistics and analytics
* Search and filtering
* Email reminders
* Document and CV management
* More advanced authentication
* Online deployment
* Mobile-friendly improvements

## 📄 License

This project is created for personal and educational purposes.
