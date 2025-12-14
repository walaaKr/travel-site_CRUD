/**
 * Theme Toggle Script - Simple & Direct
 * 5 Professional Themes
 */

// Define themes and colors
const themes = [
    'premium-dark',
    'corporate-blue',
    'luxury-purple',
    'modern-slate',
    'elite-black'
];

const themeIcons = {
    'premium-dark': '🔥',
    'corporate-blue': '💎',
    'luxury-purple': '✨',
    'modern-slate': '🌊',
    'elite-black': '⭐'
};

// Initialize when page loads
window.addEventListener('DOMContentLoaded', initTheme);

function initTheme() {
    const saved = localStorage.getItem('currentTheme') || 'premium-dark';
    setTheme(saved);
}

function setTheme(themeName) {
    // Remove all theme classes
    themes.forEach(t => {
        document.body.classList.remove(t);
        document.documentElement.classList.remove(t);
    });
    
    // Add new theme class
    document.body.classList.add(themeName);
    document.documentElement.classList.add(themeName);
    
    // Update button
    const btn = document.getElementById('themeToggle');
    if (btn) {
        btn.textContent = themeIcons[themeName];
        btn.title = themeName.replace('-', ' ').toUpperCase();
    }
    
    // Save to storage
    localStorage.setItem('currentTheme', themeName);
}

function nextTheme() {
    const current = localStorage.getItem('currentTheme') || 'premium-dark';
    const index = themes.indexOf(current);
    const next = themes[(index + 1) % themes.length];
    setTheme(next);
}

// Setup button click
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('themeToggle');
    if (btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            btn.classList.add('rotating');
            nextTheme();
            setTimeout(() => {
                btn.classList.remove('rotating');
            }, 800);
        });
    }
});