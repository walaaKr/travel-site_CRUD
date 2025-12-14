# Troubleshooting Guide - Voyagely Travel Explorer

## Problem: "404 Not Found" or "Page Not Found" When Clicking Links

### Root Cause
The issue is typically **relative path resolution** when navigating between pages at different directory levels.

---

## Solution 1: Fix All Path References (RECOMMENDED)

### Step 1: Update `index.php`
Replace the includes at the top with:

```php
<?php
session_start();

// Get absolute path to root directory
$root_dir = dirname(__FILE__);

// Include database connection
require_once($root_dir . '/config/db.php');

// Check connection
if (!isset($conn) || $conn->connect_error) {
    die("ERROR: Database connection failed. Check config/db.php");
}
?>
```

Then update includes:
```php
<?php include_once($root_dir . '/includes/header.php'); ?>
<?php include_once($root_dir . '/includes/navbar.php'); ?>
<?php include_once($root_dir . '/includes/footer.php'); ?>
```

### Step 2: Update All Page Files (`pages/*.php`)
For files in `/pages/` directory, use:

```php
<?php
session_start();

// Get absolute path to root directory (go up one level)
$root_dir = dirname(dirname(__FILE__));

// Include database
require_once($root_dir . '/config/db.php');

// Include header, navbar, footer
include_once($root_dir . '/includes/header.php');
include_once($root_dir . '/includes/navbar.php');
include_once($root_dir . '/includes/footer.php');
?>
```

### Step 3: Update All Admin Files (`admin/**/*.php`)
For files in `/admin/` directory, use:

```php
<?php
session_start();

// Get absolute path (go up two levels from admin/packages/ or admin/bookings/)
$root_dir = dirname(dirname(dirname(__FILE__)));

// Include database and templates
require_once($root_dir . '/config/db.php');
include_once($root_dir . '/includes/header.php');
include_once($root_dir . '/includes/navbar.php');
include_once($root_dir . '/includes/footer.php');
?>
```

### Step 4: Use Absolute URLs in Links
In `includes/navbar.php`, change all links to:

```php
<!-- Instead of relative paths, use absolute paths -->
<a href="/travel_site/index.php">Home</a>
<a href="/travel_site/pages/about.php">About</a>
<a href="/travel_site/pages/packages.php">Packages</a>
<a href="/travel_site/pages/map.php">Map</a>
<a href="/travel_site/pages/chatbot.php">Chat Bot</a>
<a href="/travel_site/pages/contact.php">Contact</a>
```

---

## Solution 2: Create Helper Function (ALTERNATIVE)

Create a new file: `config/helpers.php`

```php
<?php
/**
 * Helper Functions
 */

// Get root directory path
function getRootDir() {
    return dirname(dirname(__FILE__));
}

// Get site URL
function getSiteUrl() {
    return '/travel_site';
}

// Include template from includes folder
function includeTemplate($template) {
    $file = getRootDir() . '/includes/' . $template . '.php';
    if (file_exists($file)) {
        include_once($file);
    } else {
        die("ERROR: Template file not found: " . $template);
    }
}
?>
```

Then in any page, use:

```php
<?php
require_once('../config/helpers.php'); // or appropriate relative path
require_once(getRootDir() . '/config/db.php');

includeTemplate('header');
includeTemplate('navbar');
includeTemplate('footer');
?>
```

---

## Quick Fix Checklist

- [ ] **Database Connection**
  - Verify MySQL is running
  - Check `config/db.php` credentials match your setup
  - Test with phpMyAdmin

- [ ] **File Paths**
  - Use `dirname(__FILE__)` to get current directory
  - Use `dirname(dirname(__FILE__))` to go up one level
  - Verify all includes use correct relative paths

- [ ] **URLs in Links**
  - Change all relative links to absolute: `/travel_site/pages/...`
  - Ensure trailing slashes are consistent

- [ ] **Assets Folder**
  - Verify `/travel_site/assets/img/` exists
  - Check image files are properly placed

- [ ] **Directory Structure**
  ```
  /travel_site/
  ├── index.php
  ├── style.css
  ├── config/db.php
  ├── includes/header.php, navbar.php, footer.php
  ├── pages/packages.php, login.php, etc.
  ├── admin/packages/*, bookings/*
  └── assets/img/
  ```

---

## Debugging Steps

### 1. Check PHP Errors
Add this at the top of any problematic file:

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

### 2. Verify Database Connection
Create a test file: `test_db.php`

```php
<?php
$conn = new mysqli('localhost', 'root', '', 'travel_explorer');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT COUNT(*) as count FROM packages");
$row = $result->fetch_assoc();

echo "Packages in database: " . $row['count'];
echo "<br>";
echo "Connection successful!";
?>
```

Visit: `http://localhost/travel_site/test_db.php`

### 3. Check File Paths
In any page, add:

```php
<?php
echo "Current file: " . __FILE__ . "<br>";
echo "Root dir: " . dirname(dirname(__FILE__)) . "<br>";
echo "Config exists: " . (file_exists(dirname(dirname(__FILE__)) . '/config/db.php') ? 'YES' : 'NO') . "<br>";
?>
```

### 4. Verify Includes
Test each include:

```php
<?php
$root = dirname(dirname(__FILE__));

// Test config
if (file_exists($root . '/config/db.php')) {
    require_once($root . '/config/db.php');
    echo "✓ Database loaded<br>";
} else {
    echo "✗ Database file NOT found<br>";
}

// Test header
if (file_exists($root . '/includes/header.php')) {
    echo "✓ Header found<br>";
} else {
    echo "✗ Header NOT found<br>";
}

// Test navbar
if (file_exists($root . '/includes/navbar.php')) {
    echo "✓ Navbar found<br>";
} else {
    echo "✗ Navbar NOT found<br>";
}
?>
```

---

## Common Errors & Solutions

### Error: "Warning: include_once(): Failed opening '../config/db.php'"

**Solution**: Use absolute path instead:
```php
require_once(dirname(dirname(__FILE__)) . '/config/db.php');
```

---

### Error: "Database query failed: No such table"

**Solution**: 
1. Ensure `travel_explorer.sql` was imported
2. Check database name in `config/db.php` matches
3. Verify tables exist: `SHOW TABLES;` in phpMyAdmin

---

### Error: "404 Not Found" on all pages

**Solution**:
1. Verify folder structure matches exactly
2. Check all links use `/travel_site/` prefix
3. Ensure Apache/PHP can serve files from `/travel_site/`

---

### Error: "Packages show but links don't work"

**Solution**: 
Update `includes/navbar.php` links to use absolute paths:
```php
<!-- WRONG -->
<a href="pages/packages.php">Packages</a>

<!-- CORRECT -->
<a href="/travel_site/pages/packages.php">Packages</a>
```

---

## Verify Installation

### Test Checklist

1. **Homepage loads** → `http://localhost/travel_site/`
   - Hero section displays
   - Featured packages show
   - Navigation bar visible

2. **Packages page loads** → Click "View Packages" button
   - All packages display
   - Images load (or fallback gradient shows)
   - Prices and details visible

3. **Login works** → Click "Login" in navbar
   - Form appears
   - Demo credentials work: `admin@travel.com` / `admin123`

4. **Admin panel accessible** → After login as admin
   - "Manage Packages" link appears
   - Can create/edit/delete packages

5. **Database connection** → Run test_db.php
   - Shows package count
   - No connection errors

---

## If Problems Persist

1. **Check browser console** (F12 → Console tab)
   - Are there JavaScript errors?
   - Check Network tab for failed requests

2. **Check server logs**
   - XAMPP: `apache_error.log` in `/apache/logs/`
   - WAMP: Check logs folder in installation directory

3. **Test basic connectivity**
   ```php
   <?php phpinfo(); ?>
   ```
   - Create this file, visit it
   - Verify PHP version 7.4+
   - Verify MySQLi extension is loaded

4. **Reset and reimport database**
   - Drop `travel_explorer` database
   - Re-import `database/travel_explorer.sql`
   - Verify tables: `SHOW TABLES;`

---

## Need More Help?

1. **Check error display** - All files now include proper error reporting
2. **Verify paths** - Use the debugging steps above to pinpoint issues
3. **Test incrementally** - Add error checking to each include
4. **Review logs** - Check PHP/MySQL error logs for detailed info

The fixed code provided should resolve 99% of path-related issues!