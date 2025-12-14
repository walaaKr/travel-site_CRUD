# Voyagely - Travel Explorer 🌍✈️

A polished, student-grade travel website built with **PHP, HTML, CSS, and MySQL**. No JavaScript on the client—all interactivity is server-rendered using HTML forms and PHP.

## Features

✅ **User Authentication**
- Signup / Login with role-based access (Admin / User)
- Session management with server-side validation
- Logout functionality

✅ **Travel Packages**
- Browse all available packages
- Admin CRUD (Create, Read, Update, Delete)
- Package details with images, prices, destinations

✅ **Bookings System**
- Users can book packages (logged-in only)
- Admin manages all bookings
- Status tracking (pending, confirmed, cancelled)
- Full CRUD for admin

✅ **Destination Map**
- Explore places with interactive OpenStreetMap links
- Categories: Beach, Mountain, City
- Server-rendered cards with gradients

✅ **AI Chatbot**
- Server-side chatbot with rule-based fallback
- Supports external AI APIs (OpenAI/Anthropic) with proper fallback
- Conversation history stored in database
- Smart responses for booking, contact, travel questions

✅ **Visual Design**
- **5-color palette** (navy, teal, orange, neutral, dark)
- **Professional fonts**: Poppins (headings), Inter (body)
- **Gradient text** and card animations
- **Animated footer** with CSS scroll effects (no JS)
- **Responsive design** (desktop, tablet, mobile)
- **Moving background video** in hero section

✅ **Admin Panel**
- Manage packages (create, edit, delete)
- Manage bookings (create, edit, delete)
- Role-based access control
- Protected routes

---

## Installation & Setup

### 1. **Install Database**

1. Open phpMyAdmin or MySQL command line
2. Create database:
   ```sql
   CREATE DATABASE travel_explorer;
   ```
3. Import the SQL file:
   - **File**: `database/travel_explorer.sql`
   - Or paste contents into phpMyAdmin

### 2. **Configure Database Connection**

Edit `config/db.php`:

```php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';  // Your MySQL password (empty for XAMPP default)
$db_name = 'travel_explorer';
```

### 3. **Download & Place Media Files**

Place these files in `/travel_site/assets/img/`:

#### Required Files:

| Filename | Download Source | Direct Link |
|----------|-----------------|------------|
| `hero.mp4` | Pexels Videos | [Pexels Travel/Nature Videos](https://www.pexels.com/search/videos/travel/) |
| `hero-poster.jpg` | Unsplash | [Unsplash Travel](https://unsplash.com/s/photos/travel) |
| `sahara.jpg` | Pexels | [Pexels Sahara](https://www.pexels.com/search/sahara%20desert/) |
| `mediterranean.jpg` | Unsplash | [Unsplash Mediterranean](https://unsplash.com/s/photos/mediterranean-beach) |
| `alps.jpg` | Pexels | [Pexels Alps/Mountains](https://www.pexels.com/search/alps/) |
| `food-loop.mp4` | Pexels | [Pexels Food Videos](https://www.pexels.com/search/videos/food/) |
| `food-loop.jpg` | Pexels | [Pexels Food](https://www.pexels.com/search/food/) |

**Quick Download Instructions:**
1. Visit each link above
2. Download the first recommended image/video
3. Rename according to the filename column
4. Save to `/travel_site/assets/img/`

### 4. **Start Your Server**

Using **XAMPP/WAMP**:
```bash
# Place /travel_site folder in htdocs (XAMPP) or www (WAMP)
# Start Apache & MySQL from control panel
# Visit: http://localhost/travel_site/
```

Using **PHP Built-in Server**:
```bash
cd /path/to/travel_site
php -S localhost:8000
# Visit: http://localhost:8000/
```

---

## Demo Credentials

### Admin Account
- **Email**: `admin@travel.com`
- **Password**: `admin123`
- **Access**: `/travel_site/admin/packages/read.php`

### Regular User Account
- **Email**: `john@example.com`
- **Password**: `pass123`

---

## Folder Structure

```
/travel_site/
├── index.php                          # Homepage with hero & featured packages
├── style.css                          # Main stylesheet (5-color palette)
├── config/
│   └── db.php                         # Database connection
├── includes/
│   ├── header.php                     # HTML header, fonts, styles
│   ├── navbar.php                     # Navigation bar
│   └── footer.php                     # Footer with animations
├── pages/
│   ├── signup.php                     # User registration
│   ├── login.php                      # User login
│   ├── logout.php                     # Logout & session destroy
│   ├── about.php                      # About page
│   ├── contact.php                    # Contact form
│   ├── map.php                        # Explore destinations
│   ├── packages.php                   # Browse all packages
│   └── chatbot.php                    # Chat interface
├── admin/
│   ├── packages/
│   │   ├── read.php                   # List packages
│   │   ├── create.php                 # Create package
│   │   ├── update.php                 # Edit package
│   │   └── delete.php                 # Delete package
│   └── bookings/
│       ├── read.php                   # List bookings
│       ├── create.php                 # Create booking
│       ├── update.php                 # Edit booking
│       └── delete.php                 # Delete booking
├── api/
│   ├── chatbot.php                    # Chatbot API handler
│   ├── get_packages.php               # JSON packages endpoint
│   └── get_places.php                 # JSON places endpoint
├── assets/
│   ├── img/                           # Images & videos (download these)
│   │   └── manifest.txt               # Image download links
│   └── css/                           # Additional CSS if needed
└── database/
    └── travel_explorer.sql            # Database schema & sample data
```

---

## Design & Customization

### Color Palette (5 colors only)

Located in `style.css`:

```css
:root {
    --color-primary: #0F172A;      /* Deep Navy */
    --color-accent-1: #0EA5A4;     /* Teal */
    --color-accent-2: #F97316;     /* Orange */
    --color-neutral: #F8FAFC;      /* Off-white */
    --color-dark: #0B1220;         /* Near-black */
}
```

**To change theme**: Only modify these 5 color values in `:root`.

### Fonts

Loaded from Google Fonts in `includes/header.php`:
- **Headings**: Poppins (600, 700, 800 weights)
- **Body**: Inter (400, 500, 600 weights)

To change:
1. Update the `@import` link in `includes/header.php`
2. Update font-family variables in `style.css`

### Footer Animation

The footer animates in when scrolled to the bottom using pure CSS:

```css
@keyframes slideUpFade {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.footer-content {
    animation: slideUpFade 0.8s ease-out forwards;
}
```

**To demo:**
1. Open `http://localhost/travel_site/`
2. Scroll all the way to the bottom
3. Watch footer elements slide up with fade effect

---

## Chatbot Configuration

### Option 1: Use Local Fallback (Default)

The chatbot uses smart rule-based responses without any API key:

- If message contains "book" → Link to packages
- If message contains "contact" → Support info
- If message contains "price" → Pricing info
- Otherwise → Friendly travel response

**No configuration needed!**

### Option 2: Enable External AI API

To use OpenAI or Anthropic API:

#### Method A: Create Config File

1. Create `config/api_key.txt` in project root
2. Add your API key:
   ```
   sk-your-openai-key-here
   ```

#### Method B: Environment Variable

Set environment variable:
```bash
export AI_API_KEY=sk-your-key-here
```

#### Method C: Uncomment API Code

In `api/chatbot.php`, uncomment the cURL block (lines ~95-120) that calls OpenAI API.

**Tested with**: OpenAI (GPT-3.5-turbo), supports Anthropic with endpoint change

---

## Database Schema

### Users Table
```sql
- id (PK)
- name
- email (UNIQUE)
- password (plain text for student project)
- role (admin/user)
- created_at (TIMESTAMP)
```

### Packages Table
```sql
- id (PK)
- title
- description
- price
- image (filename)
- duration_days
- destination
- created_at (TIMESTAMP)
```

### Bookings Table
```sql
- id (PK)
- user_id (FK)
- package_id (FK)
- booking_date
- status (pending/confirmed/cancelled)
- number_of_people
- total_price
- created_at (TIMESTAMP)
```

### Places Table
```sql
- id (PK)
- name
- category (Beach/Mountain/City)
- description
- latitude
- longitude
- created_at (TIMESTAMP)
```

### Conversations Table
```sql
- id (PK)
- session_id
- user_message
- bot_response
- created_at (TIMESTAMP)
```

### Contact Messages Table
```sql
- id (PK)
- name
- email
- subject
- message
- created_at (TIMESTAMP)
```

---

## Security Notes

⚠️ **This is a student project**:
- Passwords stored as plain text (not recommended for production)
- No SQL injection protection (for simplicity)
- No CSRF tokens
- Basic session management

For production:
1. Use `password_hash()` for passwords
2. Implement prepared statements
3. Add CSRF protection
4. Use HTTPS
5. Implement proper input validation

---

## Troubleshooting

### Video Not Playing
- Ensure `hero.mp4` is in `/travel_site/assets/img/`
- Check browser supports HTML5 video
- Fallback gradient will show if video fails

### Database Connection Error
- Verify MySQL is running
- Check credentials in `config/db.php`
- Ensure `travel_explorer` database exists
- Run `database/travel_explorer.sql` import

### Images Not Loading
- Check filenames match exactly
- Ensure files are in `/travel_site/assets/img/`
- Use correct extensions (.jpg, .png, .mp4)
- Clear browser cache

### Admin Panel Not Accessible
- Ensure you logged in with admin account (`admin@travel.com`)
- Check `$_SESSION['role']` is set to 'admin'
- Verify SQL roles are correct in database

### Footer Animation Not Showing
- Open browser DevTools (F12)
- Scroll to bottom of page
- Footer should animate in with CSS
- Works in all modern browsers

---

## File Checklist

Before deployment, ensure all files exist:

```
✅ index.php
✅ style.css
✅ config/db.php
✅ includes/header.php, navbar.php, footer.php
✅ pages/signup.php, login.php, logout.php
✅ pages/about.php, contact.php, map.php, packages.php, chatbot.php
✅ admin/packages/create.php, read.php, update.php, delete.php
✅ admin/bookings/create.php, read.php, update.php, delete.php
✅ api/chatbot.php, get_packages.php, get_places.php
✅ database/travel_explorer.sql
✅ assets/img/ (with downloaded media files)
✅ README.md
✅ assets/img/manifest.txt
```

---

## Server Requirements

- **PHP**: 7.4+
- **MySQL**: 5.7+
- **Web Server**: Apache/Nginx with PHP support
- **Supported Browsers**: Chrome, Firefox, Safari, Edge (modern versions)

---

## Credits & Licenses

### Free Resources Used:
- **Images**: Pexels, Unsplash (Creative Commons)
- **Videos**: Pexels, Coverr (CC0)
- **Fonts**: Google Fonts (Open Source)
- **Icons**: Unicode/Emoji

All resources are free and legal to use for student projects.

---

## Testing Checklist

- [ ] Can login with admin credentials
- [ ] Can create/edit/delete packages
- [ ] Can create/edit/delete bookings
- [ ] Can signup as new user
- [ ] Chat bot responds to messages
- [ ] Map shows all places with links
- [ ] Footer animates on scroll
- [ ] Hero video plays (or shows fallback)
- [ ] Responsive on mobile
- [ ] All forms validate server-side
- [ ] Logout destroys session

---

## Support & Questions

For help:
1. Check README troubleshooting section
2. Verify database connection
3. Check browser console for errors
4. Ensure all files are in correct locations
5. Re-run SQL import

---

**Build date**: 2025  
**Version**: 1.0 (Student Edition)  
**Status**: ✅ Ready for classroom demo

Enjoy your travel exploration platform! 🌍✈️