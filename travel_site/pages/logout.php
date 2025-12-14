<?php
/**
 * Logout Page
 * 
 * Destroys user session and redirects to homepage
 */

session_start();

// Destroy all session data
session_destroy();

// Redirect to homepage
header('Location: ../index.php');
exit();