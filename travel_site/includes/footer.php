<footer class="footer" role="contentinfo" aria-label="Site footer">
    <div class="footer-background">
        <div class="footer-image-strip" aria-hidden="true"></div>
    </div>
    
    <div class="footer-content">
        <div class="footer-cta" role="region" aria-label="Call to action">
            <h2 class="footer-heading">Ready for Your Next Adventure?</h2>
            <p class="footer-subtext">Discover amazing travel packages and book your dream trip today.</p>
            <a href="/travel_site/pages/packages.php" class="btn btn-primary footer-cta-btn" aria-label="Explore all travel packages">
                Start Exploring
            </a>
        </div>
        
        <div class="footer-links" role="navigation" aria-label="Footer navigation">
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul role="list">
                    <li><a href="/travel_site/index.php">Home</a></li>
                    <li><a href="/travel_site/pages/about.php">About Us</a></li>
                    <li><a href="/travel_site/pages/packages.php">Packages</a></li>
                    <li><a href="/travel_site/pages/map.php">Explore Map</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Support</h3>
                <ul role="list">
                    <li><a href="/travel_site/pages/contact.php">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Connect</h3>
                <ul role="list">
                    <li><a href="#" aria-label="Follow on Facebook">Facebook</a></li>
                    <li><a href="#" aria-label="Follow on Twitter">Twitter</a></li>
                    <li><a href="#" aria-label="Follow on Instagram">Instagram</a></li>
                    <li><a href="#" aria-label="Follow on LinkedIn">LinkedIn</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 ViaNova Travel Explorer. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
    /* Chatbot Widget Styles */
    .chatbot-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 380px;
        height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 40px rgba(0,0,0,0.2);
        display: none;
        flex-direction: column;
        z-index: 9999;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }

    .chatbot-widget.open {
        display: flex;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #0EA5A4 0%, #F97316 100%);
        color: white;
        padding: 1rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chatbot-header h3 {
        margin: 0;
        font-size: 1rem;
    }

    .chatbot-close {
        background: rgba(255,255,255,0.3);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background 0.3s;
    }

    .chatbot-close:hover {
        background: rgba(255,255,255,0.5);
    }

    .chatbot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background: #f9fafb;
    }

    .message {
        margin-bottom: 1rem;
        display: flex;
        gap: 0.5rem;
        animation: slideIn 0.3s ease-out;
    }

    .message.user {
        flex-direction: row-reverse;
    }

    .message-bubble {
        max-width: 70%;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        word-wrap: break-word;
        font-size: 0.9rem;
        line-height: 1.4;
        animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes popIn {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }
        70% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .message.bot .message-bubble {
        background: #e5e7eb;
        color: #1f2937;
        white-space: pre-wrap;
    }

    .message.user .message-bubble {
        background: #0EA5A4;
        color: white;
    }

    .chatbot-input {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 0.5rem;
    }

    .chatbot-input input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
    }

    .chatbot-input input:focus {
        outline: none;
        border-color: #0EA5A4;
        box-shadow: 0 0 0 3px rgba(14, 165, 164, 0.1);
    }

    .chatbot-send {
        background: #0EA5A4;
        color: white;
        border: none;
        border-radius: 6px;
        width: 40px;
        cursor: pointer;
        font-size: 1.1rem;
        transition: background 0.3s;
    }

    .chatbot-send:hover {
        background: #0d9994;
    }

    .chatbot-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0EA5A4 0%, #F97316 100%);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        z-index: 9998;
        transition: all 0.3s ease;
        display: block;
        animation: float 3s ease-in-out infinite;
    }

    .chatbot-widget.open ~ .chatbot-toggle {
        display: none;
    }

    .chatbot-toggle:hover {
        transform: scale(1.1);
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-15px);
        }
    }

    .chatbot-widget.open ~ .chatbot-toggle {
        display: none;
    }

    @media (max-width: 480px) {
        .chatbot-widget {
            width: 100%;
            height: 100%;
            right: 0;
            bottom: 0;
            border-radius: 0;
        }
    }

    .loading {
        display: flex;
        gap: 4px;
        align-items: center;
    }

    .loading span {
        width: 6px;
        height: 6px;
        background: #0EA5A4;
        border-radius: 50%;
        animation: bounce 1.4s infinite;
    }

    .loading span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .loading span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {
        0%, 80%, 100% { opacity: 0.5; transform: translateY(0); }
        40% { opacity: 1; transform: translateY(-8px); }
    }
</style>

<div class="chatbot-widget" id="chatbotWidget">
    <div class="chatbot-header">
        <h3>ViaNova Q&A</h3>
        <button class="chatbot-close">−</button>
    </div>
    
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            <div class="message-bubble">
                👋 Hi! I'm ViaNova's AI Assistant. Ask me anything about our travel packages, bookings, or destinations!
            </div>
        </div>
    </div>
    
    <div class="chatbot-input">
        <input 
            type="text" 
            id="chatbotInput" 
            placeholder="Ask me something..."
        >
        <button class="chatbot-send" id="chatbotSendBtn">➤</button>
    </div>
</div>

<button class="chatbot-toggle" id="chatbotToggle">💬</button>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const widget = document.getElementById("chatbotWidget");
    const closeBtn = widget.querySelector(".chatbot-close");
    const toggleBtn = document.getElementById("chatbotToggle");
    const input = document.getElementById("chatbotInput");
    const messagesContainer = document.getElementById("chatbotMessages");
    const sendBtn = document.getElementById("chatbotSendBtn");

    function openChat() {
        widget.classList.add("open");
        input.focus();
    }

    function closeChat() {
        widget.classList.remove("open");
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        // Add user message
        const userDiv = document.createElement("div");
        userDiv.className = "message user";
        userDiv.innerHTML = `<div class="message-bubble">${escapeHtml(message)}</div>`;
        messagesContainer.appendChild(userDiv);

        input.value = "";
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Send to backend
       fetch('/travel_site/api/chatbot_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.reply) {
                const botDiv = document.createElement("div");
                botDiv.className = "message bot";
                botDiv.innerHTML = `<div class="message-bubble">${escapeHtml(data.reply)}</div>`;
                messagesContainer.appendChild(botDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const botDiv = document.createElement("div");
            botDiv.className = "message bot";
            botDiv.innerHTML = `<div class="message-bubble">Sorry, I couldn't connect. Please try again.</div>`;
            messagesContainer.appendChild(botDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    }

    toggleBtn.addEventListener("click", openChat);
    closeBtn.addEventListener("click", closeChat);
    sendBtn.addEventListener("click", sendMessage);

    input.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            sendMessage();
        }
    });
});
</script>

<script>
// Dark Mode Toggle (production-ready)
(function() {
    function applyMetaTheme(isDark) {
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) meta.setAttribute('content', isDark ? '#0a0e27' : '#0F172A');
    }

    // Apply saved theme immediately
    var savedDark = false;
    try {
        savedDark = localStorage.getItem('darkMode') === 'true';
    } catch (e) { savedDark = false; }

    if (savedDark) {
        document.body.classList.add('dark-mode');
    }
    applyMetaTheme(savedDark);

    document.addEventListener('DOMContentLoaded', function() {
        var themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;

        function syncButton() {
            var isDark = document.body.classList.contains('dark-mode');
            themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            themeToggle.title = isDark ? 'Switch to light mode' : 'Switch to dark mode';
            applyMetaTheme(isDark);
        }

        // Initialize state
        syncButton();

        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            var isDark = document.body.classList.contains('dark-mode');
            try {
                localStorage.setItem('darkMode', isDark ? 'true' : 'false');
            } catch (e) {}
            syncButton();
        });
    });
})();
</script>
</body>
</html>
