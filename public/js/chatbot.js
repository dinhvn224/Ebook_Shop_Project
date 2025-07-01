/**
 * Chatbot AI Gemini Integration
 * Handles chat interface and communication with backend
 */
class Chatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.sessionId = this.generateSessionId();
        this.messages = [];
        this.apiUrl = '/chatbot/webhook';
        this.n8nWebhookUrl = 'http://localhost:5678/webhook/chatbot'; // n8n webhook URL

        this.init();
    }

    /**
     * Initialize chatbot
     */
    init() {
        this.createChatbotHTML();
        this.bindEvents();
        this.loadSession();
        this.addWelcomeMessage();
    }

    /**
     * Generate unique session ID
     */
    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Create chatbot HTML structure
     */
    createChatbotHTML() {
        const chatbotHTML = `
            <div class="chatbot-container">
                <button class="chatbot-toggle" id="chatbotToggle">
                    <i class="fas fa-comments"></i>
                </button>

                <div class="chatbot-window" id="chatbotWindow">
                    <div class="chatbot-header">
                        <h3>📚 AI Book Assistant</h3>
                        <div class="status">Online</div>
                        <button class="chatbot-close" id="chatbotClose">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="chatbot-messages" id="chatbotMessages">
                        <!-- Messages will be added here -->
                    </div>

                    <div class="typing-indicator" id="typingIndicator">
                        <div class="typing-dots">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    </div>

                    <div class="chatbot-input-area">
                        <textarea
                            class="chatbot-input"
                            id="chatbotInput"
                            placeholder="Nhập câu hỏi của bạn..."
                            rows="1"
                        ></textarea>
                        <button class="chatbot-send" id="chatbotSend">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', chatbotHTML);
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Toggle chatbot
        document.getElementById('chatbotToggle').addEventListener('click', () => {
            this.toggleChatbot();
        });

        // Close chatbot
        document.getElementById('chatbotClose').addEventListener('click', () => {
            this.closeChatbot();
        });

        // Send message
        document.getElementById('chatbotSend').addEventListener('click', () => {
            this.sendMessage();
        });

        // Input events
        const input = document.getElementById('chatbotInput');
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        input.addEventListener('input', () => {
            this.autoResizeInput();
        });

        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeChatbot();
            }
        });

        // Click outside to close
        document.addEventListener('click', (e) => {
            const container = document.querySelector('.chatbot-container');
            const window = document.getElementById('chatbotWindow');

            if (this.isOpen &&
                !container.contains(e.target) &&
                !e.target.closest('.chatbot-container')) {
                this.closeChatbot();
            }
        });
    }

    /**
     * Toggle chatbot visibility
     */
    toggleChatbot() {
        const window = document.getElementById('chatbotWindow');
        const toggle = document.getElementById('chatbotToggle');

        if (this.isOpen) {
            this.closeChatbot();
        } else {
            this.openChatbot();
        }
    }

    /**
     * Open chatbot
     */
    openChatbot() {
        const window = document.getElementById('chatbotWindow');
        const toggle = document.getElementById('chatbotToggle');

        window.classList.add('active');
        this.isOpen = true;

        // Focus input
        setTimeout(() => {
            document.getElementById('chatbotInput').focus();
        }, 300);

        // Scroll to bottom
        this.scrollToBottom();
    }

    /**
     * Close chatbot
     */
    closeChatbot() {
        const window = document.getElementById('chatbotWindow');
        window.classList.remove('active');
        this.isOpen = false;
    }

    /**
     * Send message
     */
    async sendMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();

        if (!message || this.isTyping) return;

        // Clear input
        input.value = '';
        this.autoResizeInput();

        // Add user message
        this.addMessage(message, 'user');

        // Show typing indicator
        this.showTyping();

        try {
            // First try Laravel backend
            const response = await this.sendToLaravel(message);

            if (response.success) {
                if (response.source === 'database') {
                    // Database result
                    this.hideTyping();
                    this.addMessage(response.message, 'bot');
                } else {
                    // Need AI processing - send to n8n
                    const aiResponse = await this.sendToN8n(message);
                    this.hideTyping();
                    this.addMessage(aiResponse.message, 'bot');
                }
            } else {
                throw new Error(response.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            console.error('Chatbot error:', error);
            this.hideTyping();
            this.addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
        }
    }

    /**
     * Send message to Laravel backend
     */
    async sendToLaravel(message) {
        const response = await fetch(this.apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                sessionId: this.sessionId,
                timestamp: new Date().toISOString()
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    /**
     * Send message to n8n workflow
     */
    async sendToN8n(message) {
        const response = await fetch(this.n8nWebhookUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                sessionId: this.sessionId,
                timestamp: new Date().toISOString(),
                source: 'ai'
            })
        });

        if (!response.ok) {
            throw new Error(`n8n HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    /**
     * Get CSRF token from meta tag
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Add message to chat
     */
    addMessage(content, sender) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;

        const time = new Date().toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });

        messageDiv.innerHTML = `
            <div class="message-content">
                ${this.formatMessage(content)}
                <div class="message-time">${time}</div>
            </div>
        `;

        messagesContainer.appendChild(messageDiv);

        // Save to session
        this.messages.push({
            content: content,
            sender: sender,
            timestamp: new Date().toISOString()
        });
        this.saveSession();

        // Scroll to bottom
        this.scrollToBottom();
    }

    /**
     * Format message content (support markdown-like formatting)
     */
    formatMessage(content) {
        // Convert **text** to bold
        content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // Convert line breaks to <br>
        content = content.replace(/\n/g, '<br>');

        // Convert URLs to links
        content = content.replace(
            /(https?:\/\/[^\s]+)/g,
            '<a href="$1" target="_blank" rel="noopener">$1</a>'
        );

        return content;
    }

    /**
     * Show typing indicator
     */
    showTyping() {
        this.isTyping = true;
        document.getElementById('typingIndicator').classList.add('active');
        this.scrollToBottom();
    }

    /**
     * Hide typing indicator
     */
    hideTyping() {
        this.isTyping = false;
        document.getElementById('typingIndicator').classList.remove('active');
    }

    /**
     * Add welcome message
     */
    addWelcomeMessage() {
        const welcomeMessage = `
            <div class="welcome-message">
                <h4>👋 Chào mừng bạn!</h4>
                <p>Tôi là AI Assistant của cửa hàng sách. Tôi có thể giúp bạn:</p>
                <ul style="text-align: left; margin: 10px 0; padding-left: 20px;">
                    <li>🔍 Tìm kiếm sách theo tên, tác giả, thể loại</li>
                    <li>💰 Kiểm tra giá và khuyến mãi</li>
                    <li>📚 Tư vấn sách phù hợp</li>
                    <li>❓ Trả lời câu hỏi về sách</li>
                </ul>
                <p>Hãy bắt đầu cuộc trò chuyện nào!</p>
            </div>
        `;

        document.getElementById('chatbotMessages').innerHTML = welcomeMessage;
    }

    /**
     * Auto-resize input field
     */
    autoResizeInput() {
        const input = document.getElementById('chatbotInput');
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 100) + 'px';
    }

    /**
     * Scroll to bottom of messages
     */
    scrollToBottom() {
        const messagesContainer = document.getElementById('chatbotMessages');
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    }

    /**
     * Save session to localStorage
     */
    saveSession() {
        try {
            localStorage.setItem('chatbot_session', JSON.stringify({
                sessionId: this.sessionId,
                messages: this.messages.slice(-50) // Keep last 50 messages
            }));
        } catch (error) {
            console.warn('Could not save chatbot session:', error);
        }
    }

    /**
     * Load session from localStorage
     */
    loadSession() {
        try {
            const saved = localStorage.getItem('chatbot_session');
            if (saved) {
                const session = JSON.parse(saved);
                this.sessionId = session.sessionId || this.sessionId;
                this.messages = session.messages || [];
            }
        } catch (error) {
            console.warn('Could not load chatbot session:', error);
        }
    }

    /**
     * Clear session
     */
    clearSession() {
        this.messages = [];
        this.sessionId = this.generateSessionId();
        localStorage.removeItem('chatbot_session');
        document.getElementById('chatbotMessages').innerHTML = '';
        this.addWelcomeMessage();
    }

    /**
     * Handle errors
     */
    handleError(error) {
        console.error('Chatbot error:', error);
        this.hideTyping();
        this.addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Check if FontAwesome is loaded, if not load it
    if (!document.querySelector('link[href*="fontawesome"]')) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';
        document.head.appendChild(link);
    }

    // Initialize chatbot
    window.chatbot = new Chatbot();
});

// Export for global access
window.Chatbot = Chatbot;
