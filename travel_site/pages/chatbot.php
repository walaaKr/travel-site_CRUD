<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['conversation_id'])) {
    $_SESSION['conversation_id'] = session_id();
}
$session_id = $_SESSION['conversation_id'];

$conversation_query = "SELECT user_message, bot_response, created_at FROM conversations 
                       WHERE session_id = '" . $conn->real_escape_string($session_id) . "' 
                       ORDER BY created_at ASC LIMIT 10";
$conversation_result = $conn->query($conversation_query);
$messages = [];
if ($conversation_result) {
    while ($row = $conversation_result->fetch_assoc()) {
        $messages[] = $row;
    }
}

include_once('../includes/header.php');
include_once('../includes/navbar.php');
?>

<section class="chatbot-container" role="main" aria-label="Chat bot section">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h1>Travel Chat Bot</h1>
        <p style="color: #6b7280;">Ask questions about our packages, bookings, or travel tips</p>
    </div>
    
    <div class="conversation-box" role="region" aria-label="Chat conversation history" aria-live="polite">
        <?php if (empty($messages)): ?>
            <div style="text-align: center; color: #9ca3af; padding: 2rem;">
                <p>No messages yet. Start a conversation!</p>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message user-message" role="article">
                    <div class="user-text"><?php echo htmlspecialchars($msg['user_message']); ?></div>
                </div>
                <div class="message bot-message" role="article">
                    <div class="bot-text"><?php echo htmlspecialchars($msg['bot_response']); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <form method="POST" action="../api/chatbot_handler.php" class="chatbot-form" novalidate>
        <div class="form-group">
            <label for="message">Your Message *</label>
            <textarea id="message" name="message" required placeholder="Ask about packages, bookings, travel tips, etc."></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
    </form>
    
    <div style="margin-top: 2rem; padding: 1.5rem; background: #f0f9f9; border-radius: 0.5rem; border-left: 4px solid var(--color-accent-1);">
        <p style="margin: 0; color: #0f172a;"><strong>💡 Tip:</strong> Try asking about booking packages, contacting support, or our travel destinations!</p>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>