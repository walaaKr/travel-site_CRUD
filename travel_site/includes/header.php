<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ViaNova - Explore the world's best travel packages">
    <meta name="theme-color" content="#0F172A">
    
    <title>ViaNova - Travel Explorer</title>
    
    <!-- Google Fonts: Poppins (headings) and Inter (body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Main stylesheet -->
    <link rel="stylesheet" href="/travel_site/style.css">
    
    <!-- Fallback for video background if not supported -->
    <style>
        /* Fallback if <video> autoplay is blocked */
        .hero-background-fallback {
            background: linear-gradient(135deg, #0EA5A4 0%, #F97316 100%);
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
    </style>
</head>
<body>
<script>
// Early apply of saved theme to prevent flash
(function(){
  try {
    var isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) {
      document.body.classList.add('dark-mode');
    }
    var meta = document.querySelector('meta[name="theme-color"]');
    if (meta) meta.setAttribute('content', isDark ? '#0a0e27' : '#0F172A');
  } catch(e) {}
})();
</script>