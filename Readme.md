# 💧 Ro_Water_Delivery System

A comprehensive web-based water delivery management system designed to streamline the ordering process for customers and provide administrators with full control over orders and messages. Built with PHP, MySQL, HTML, CSS, and JavaScript, this system offers a seamless experience for both users and admins.

---

## 📖 About the Project

The **Ro_Water_Delivery System** was developed to address the growing need for an efficient, digital solution in the water delivery industry. It eliminates manual processes by allowing customers to place orders online, track their delivery status, and manage their accounts. For administrators, it provides a centralized dashboard to view, update, and process orders, as well as respond to customer inquiries.

### Key Objectives
- Simplify water ordering for customers.
- Provide real-time order tracking.
- Enable administrators to manage orders efficiently.
- Offer a secure authentication system for both users and admins.
- Maintain a clean, responsive user interface.

---

## ✨ Features

### 👤 User Features
- **User Registration & Login** – Secure sign-up and sign-in.
- **Forgot Password** – Reset password via email.
- **Place Orders** – Select water quantity, delivery address, and schedule.
- **Order History** – View all past and current orders with status.
- **Cancel / Update Orders** – Modify or cancel pending orders.
- **Invoice Generation** – Download or print invoices for completed orders.
- **Contact Admin** – Send messages directly to the administrator.
- **Change Password** – Update account password securely.

### 👨‍💼 Admin Features
- **Secure Admin Login** – Separate authentication for admin panel.
- **Dashboard** – Overview of total orders, pending deliveries, recent activities.
- **Order Management** – View all orders, update status (pending, processing, delivered, cancelled), delete orders.
- **Message Management** – View and respond to customer messages.
- **Change Password** – Admin can update own password.
- **Logout** – Secure logout.

---

## 🛠️ Built With

| Technology | Purpose |
|------------|---------|
| PHP | Server-side logic, authentication, database interaction |
| MySQL | Database storage for users, orders, messages |
| HTML5 & CSS3 | Structure and styling of web pages |
| JavaScript | Client-side form validation and interactive elements |
| Apache (XAMPP/WAMP) | Local development server |

---

### Project Structure

```css
Ro_Water_Delivery_System/
├─ admin/                     # Admin panel files
│  ├─ includes/               # Reusable admin components (header, footer, navbar)
│  │  ├─ footer.php
│  │  ├─ header.php
│  │  └─ navbar.php
│  ├─ index.php               # Admin dashboard redirect
│  ├─ login.php               # Admin login page
│  ├─ logout.php              # Admin logout handler
│  └─ pages/                  # Admin sub-pages
│     ├─ change-password.php
│     ├─ dashboard.php
│     ├─ messages.php
│     └─ orders.php
├─ app/                       # Core application files
│  └─ config/                 # Configuration files
│     └─ database.php         # Database connection settings
└─ public/                    # Public-facing frontend files
   ├─ assets/                 # Static assets
   │  ├─ css/                 # All CSS files (admin & user styles)
   │  │  ├─ admin-change-pass.css
   │  │  ├─ admin-login.css
   │  │  ├─ change-password.css
   │  │  ├─ contact.css
   │  │  ├─ dashboard.css
   │  │  ├─ invoice.css
   │  │  ├─ login-and-register.css
   │  │  ├─ messages.css
   │  │  ├─ order-history.css
   │  │  ├─ order-success.css
   │  │  ├─ order.css
   │  │  ├─ orders.css
   │  │  └─ style.css
   │  └─ js/
   │     └─ form-validation.js
   ├─ includes/               # Frontend includes
   │  ├─ auth.php             # Authentication check
   │  ├─ footer.php
   │  ├─ header.php
   │  └─ navbar.php
   ├─ index.php               # Frontend entry point
   └─ pages/                  # Frontend pages
      ├─ cancel-order.php
      ├─ change-password.php
      ├─ contact.php
      ├─ forgot-password.php
      ├─ home.php
      ├─ invoice.php
      ├─ login.php
      ├─ logout.php
      ├─ order-history.php
      ├─ order-success.php
      ├─ order.php
      ├─ register.php
      └─ update-order.php
```