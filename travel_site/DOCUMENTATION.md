# ViaNova - Travel Explorer Documentation

## Project Overview

**ViaNova** is a full-stack travel booking and exploration website built as a student-grade project using **PHP, HTML, CSS, and MySQL**. The platform allows users to browse travel packages, make bookings, explore destinations on an interactive map, and interact with an AI-powered chatbot.

**Project Status**: Complete and ready for deployment  
**Build Date**: 2025  
**Version**: 1.0 (Student Edition)

---

## What This Site Does

### Core Functionality

| Feature | Description |
|---------|-------------|
| **User Authentication** | Signup/login with role-based access (Admin/User), session management |
| **Travel Packages** | Browse, view details, and book travel packages with images, prices, and destinations |
| **Booking System** | Users can book packages; admins manage all bookings with status tracking |
| **Destination Map** | Explore places via interactive OpenStreetMap with categories (Beach, Mountain, City) |
| **AI Chatbot** | Rule-based chatbot with external AI API support (OpenAI/Anthropic) |
| **Admin Panel** | Full CRUD operations for packages, bookings, and users |

### Target Users

- **Regular Users**: Browse packages, create accounts, make bookings, explore destinations
- **Administrators**: Manage packages, bookings, users; full control over site content

---

## Technology Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | HTML5, CSS3 (custom design system), minimal JavaScript |
| **Backend** | PHP 7.4+ |
| **Database** | MySQL 5.7+ |
| **Web Server** | Apache (XAMPP/WAMP) or PHP built-in server |
| **Fonts** | Google Fonts (Poppins, Inter) |
| **Maps** | OpenStreetMap integration |

---

## Design System

### Color Palette (5 Colors)

```css
:root {
    --color-primary: #0F172A;      /* Deep Navy */
    --color-accent-1: #0EA5A4;     /* Teal */
    --color-accent-2: #F97316;     /* Orange */
    --color-neutral: #F8FAFC;      /* Off-white */
    --color-dark: #0B1220;         /* Near-black */
}
```

### Typography

- **Headings**: Poppins (600, 700, 800 weights)
- **Body**: Inter (400, 500, 600 weights)

### Visual Features

- Gradient text effects
- Card hover animations
- Animated footer (CSS-only slide-up effect)
- Hero section with background video
- Responsive design (desktop, tablet, mobile)

---

## Project Structure

```
/travel_site/
├── index.php                          # Homepage with hero & featured packages
├── style.css                          # Main stylesheet (design system)
├── README.md                          # Quick start guide
├── troubleshooting.md                 # Debug and fix common issues
├── DOCUMENTATION.md                   # This file
│
├── config/
│   └── db.php                         # Database connection settings
│
├── includes/
│   ├── header.php                     # HTML head, fonts, meta tags
│   ├── navbar.php                     # Site navigation
│   └── footer.php                     # Footer with CSS animations
│
├── pages/
│   ├── signup.php                     # User registration form
│   ├── login.php                      # User login form
│   ├── logout.php                     # Session destruction
│   ├── about.php                      # About the company
│   ├── contact.php                    # Contact form
│   ├── map.php                        # Interactive destination explorer
│   ├── packages.php                   # Browse all packages
│   └── chatbot.php                    # AI chat interface
│
├── admin/
│   ├── packages/
│   │   ├── read.php                   # List all packages
│   │   ├── create.php                 # Add new package
│   │   ├── update.php                 # Edit package
│   │   └── delete.php                 # Remove package
│   ├── bookings/
│   │   ├── read.php                   # List all bookings
│   │   ├── create.php                 # Create booking
│   │   ├── update.php                 # Edit booking status
│   │   └── delete.php                 # Cancel/delete booking
│   └── users/
│       └── [user management files]
│
├── api/
│   └── chatbot_handler.php            # Chatbot API endpoint
│
├── assets/
│   ├── img/                           # Images and videos
│   │   ├── hero.mp4                   # Hero background video
│   │   ├── hero-poster.jpg            # Video fallback image
│   │   └── [package images]
│   └── js/
│       └── theme.js                   # Theme toggle functionality
│
└── database/
    ├── travel.sql                     # Main database schema
    └── add_packages.sql               # Sample package data
```

---

## Database Schema

### Tables Overview

| Table | Purpose |
|-------|---------|
| `users` | User accounts with roles |
| `packages` | Travel package listings |
| `bookings` | User booking records |
| `places` | Map destination data |
| `conversations` | Chatbot history |
| `contact_messages` | Contact form submissions |

### Entity Relationships

```
users (1) ──────< (many) bookings
packages (1) ───< (many) bookings
```

### Key Fields

**Users**
- `id`, `name`, `email`, `password`, `role` (admin/user), `created_at`

**Packages**
- `id`, `title`, `description`, `price`, `image`, `duration_days`, `destination`, `created_at`

**Bookings**
- `id`, `user_id`, `package_id`, `booking_date`, `status` (pending/confirmed/cancelled), `number_of_people`, `total_price`

---

## Features Implemented

### 1. User Authentication System
- Registration with email validation
- Login with session management
- Role-based access control (admin vs user)
- Logout with session destruction
- Protected routes for admin areas

### 2. Travel Packages Module
- Dynamic package listing from database
- Featured packages on homepage (3 latest)
- Package cards with images, prices, descriptions
- Fallback gradient when images missing
- Admin CRUD operations

### 3. Booking System
- Book packages (logged-in users only)
- Booking status workflow: pending → confirmed → cancelled
- Admin booking management dashboard
- Total price calculation based on travelers

### 4. Interactive Map
- Destination cards with OpenStreetMap links
- Category filtering (Beach, Mountain, City)
- Latitude/longitude coordinates for each place
- Server-rendered cards with gradient backgrounds

### 5. AI Chatbot
- Rule-based responses (no API required)
- Smart keyword detection:
  - "book" → Links to packages
  - "contact" → Support information
  - "price" → Pricing details
- Optional external AI API integration (OpenAI/Anthropic)
- Conversation history stored in database

### 6. Visual Design
- Hero section with autoplay background video
- Slow cinematic playback (0.4x speed)
- Gradient text animations
- Card hover effects
- Footer slide-up animation on scroll
- Fully responsive (mobile-first approach)

### 7. Admin Panel
- Protected admin routes
- Package management (create/read/update/delete)
- Booking management with status updates
- User management capabilities

---

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@travel.com | admin123 |
| **User** | john@example.com | pass123 |

---

## Installation Summary

1. **Database**: Create `travel_explorer` database and import `database/travel.sql`
2. **Configuration**: Update `config/db.php` with MySQL credentials
3. **Media Files**: Download and place images/videos in `assets/img/`
4. **Server**: Run via XAMPP/WAMP or `php -S localhost:8000`
5. **Access**: Visit `http://localhost/travel_site/`

---

## Development Timeline

### What Was Built

1. **Project Setup**
   - Created folder structure
   - Set up database connection
   - Designed 5-color palette

2. **Core Templates**
   - Header with Google Fonts
   - Responsive navbar
   - Animated footer

3. **Authentication System**
   - Signup form with validation
   - Login with session management
   - Role-based access control
   - Logout functionality

4. **Package System**
   - Database schema for packages
   - Browse packages page
   - Featured packages on homepage
   - Admin CRUD interface

5. **Booking System**
   - Booking form and logic
   - Status management
   - Admin booking dashboard

6. **Additional Features**
   - Interactive map with destinations
   - AI chatbot with fallback responses
   - Contact form
   - About page

7. **Visual Polish**
   - Hero video section
   - Gradient text effects
   - Card animations
   - Mobile responsiveness

8. **Documentation**
   - README.md (quick start)
   - troubleshooting.md (debug guide)
   - DOCUMENTATION.md (this file)

---

## Security Notes

This is a **student project** with simplified security:

| Concern | Current State | Production Recommendation |
|---------|---------------|---------------------------|
| Passwords | Plain text | Use `password_hash()` |
| SQL Queries | Direct queries | Use prepared statements |
| CSRF | Not implemented | Add CSRF tokens |
| Input Validation | Basic | Add comprehensive validation |
| HTTPS | Not required | Enable SSL/TLS |

---

## Known Limitations

1. No JavaScript frameworks (intentionally vanilla for learning)
2. Basic error handling
3. No email verification
4. No payment processing
5. No real-time notifications

---

## File Checklist

```
✅ index.php
✅ style.css
✅ README.md
✅ troubleshooting.md
✅ DOCUMENTATION.md
✅ config/db.php
✅ includes/header.php, navbar.php, footer.php
✅ pages/signup.php, login.php, logout.php
✅ pages/about.php, contact.php, map.php, packages.php, chatbot.php
✅ admin/packages/create.php, read.php, update.php, delete.php
✅ admin/bookings/create.php, read.php, update.php, delete.php
✅ api/chatbot_handler.php
✅ database/travel.sql, add_packages.sql
✅ assets/img/ (media files)
✅ assets/js/theme.js
```

---

## Support Resources

- **README.md**: Quick installation and setup
- **troubleshooting.md**: Common issues and solutions
- **Database schema**: See `database/` folder
- **Demo accounts**: Use credentials above for testing

---

**Project**: ViaNova - Travel Explorer  
**Type**: Full-Stack Web Application  
**Stack**: PHP + MySQL + HTML/CSS  
**Status**: Production Ready (Student Edition)
