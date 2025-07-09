/**
 * Advanced Chatbot AI with Database, PDF, and AI Integration
 * Priority: Database → PDF → AI Fallback
 */
class AdvancedChatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.sessionId = this.generateSessionId();
        this.messages = [];
        this.apiUrl = '/chatbot/webhook';
        // Configuration
        this.config = {
            maxRetries: 3,
            timeout: 30000,
            debugMode: true // Set to false in production
        };
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
        this.log('Chatbot initialized with session:', this.sessionId);
    }

    /**
     * Generate unique session ID
     */
    generateSessionId() {
        return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Debug logging
     */
    log(...args) {
        if (this.config.debugMode) {
            console.log('[Chatbot]', ...args);
        }
    }

    /**
     * Create chatbot HTML structure
     */
    createChatbotHTML() {
        const chatbotHTML = `
            <div class="chatbot-container">
                <button class="chatbot-toggle" id="chatbotToggle">
                    <i class="fas fa-robot"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;">1</span>
                </button>
                <div class="chatbot-window" id="chatbotWindow">
                    <div class="chatbot-header">
                        <div class="chatbot-header-left">
                            <span class=\"chatbot-logo-icon\">🤖</span>
                            <span class=\"chatbot-title\">Chatbot BookStore</span>
                        </div>
                        <div class="chatbot-header-right">
                            <button class="chatbot-action" id="clearChat" title="Xóa lịch sử chat">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="chatbot-close" id="chatbotClose" title="Đóng chat">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="chatbot-messages" id="chatbotMessages">
                        </div>
                    <div class="typing-indicator" id="typingIndicator">
                        <div class="typing-bubble">
                            <div class="typing-dots">
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                                <div class="typing-dot"></div>
                            </div>
                            <div class="typing-text" id="typingText">Đang tìm kiếm...</div>
                        </div>
                    </div>
                    <div class="chatbot-input-area">
                        <div class="input-container">

                            <textarea
                                class="chatbot-input"
                                id="chatbotInput"
                                placeholder="Hỏi tôi về sách, tác giả, giá cả..."
                                rows="1"
                                maxlength="500"
                            ></textarea>
                            <button class="chatbot-send" id="chatbotSend" title="Gửi tin nhắn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <span class="character-count" id="characterCount">0/500</span>
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
        document.getElementById('chatbotToggle').addEventListener('click', () => {
            this.toggleChatbot();
        });
        document.getElementById('chatbotClose').addEventListener('click', () => {
            this.closeChatbot();
        });
        document.getElementById('clearChat').addEventListener('click', () => {
            this.clearSession();
        });
        document.getElementById('chatbotSend').addEventListener('click', () => {
            this.sendMessage();
        });
        const input = document.getElementById('chatbotInput');
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
        input.addEventListener('input', (e) => {
            this.autoResizeInput();
            this.updateCharacterCount();
            this.handleTyping();
        });
        input.addEventListener('focus', () => {
            this.showSuggestions();
        });
        input.addEventListener('blur', () => {
            setTimeout(() => this.hideSuggestions(), 200);
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeChatbot();
            }
        });
        document.addEventListener('click', (e) => {
            const container = document.querySelector('.chatbot-container');
            if (this.isOpen && !container.contains(e.target)) {
                this.closeChatbot();
            }
        });
        window.addEventListener('beforeunload', () => {
            this.saveSession();
        });
    }

    /**
     * Toggle chatbot visibility
     */
    toggleChatbot() {
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
        const badge = document.getElementById('notificationBadge');
        window.classList.add('active');
        badge.style.display = 'none';
        this.isOpen = true;
        setTimeout(() => {
            document.getElementById('chatbotInput').focus();
        }, 300);
        this.scrollToBottom();
        this.log('Chatbot opened');
    }

    /**
     * Close chatbot
     */
    closeChatbot() {
        const window = document.getElementById('chatbotWindow');
        window.classList.remove('active');
        this.isOpen = false;
        this.log('Chatbot closed');
    }

    /**
     * Send message with advanced processing
     */
    async sendMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();
        if (!message || this.isTyping) return;
        if (message.length > 500) {
            this.showError('Tin nhắn quá dài. Vui lòng nhập dưới 500 ký tự.');
            return;
        }
        input.value = '';
        this.autoResizeInput();
        this.updateCharacterCount();
        this.hideSuggestions();
        this.addMessage(message, 'user');
        this.log('User message:', message);
        this.showEnhancedTyping();
        let retryCount = 0;
        const maxRetries = this.config.maxRetries;
        while (retryCount < maxRetries) {
            try {
                this.updateTypingStatus('Đang phân tích câu hỏi...');
                const response = await this.sendToBackend(message);
                this.hideTyping();
                this.log('Backend response:', response);
                if (response.success) {
                    this.addEnhancedMessage(response);
                    break;
                } else {
                    throw new Error(response.message || 'Có lỗi xảy ra từ server');
                }
            } catch (error) {
                retryCount++;
                this.log(`Attempt ${retryCount} failed:`, error);
                if (retryCount < maxRetries) {
                    this.updateTypingStatus(`Thử lại lần ${retryCount + 1}...`);
                    await this.delay(1000);
                } else {
                    this.hideTyping();
                    this.addMessage(
                        `Xin lỗi, tôi gặp sự cố kỹ thuật. Vui lòng thử lại sau. (Lỗi: ${error.message})`,
                        'bot',
                        'error'
                    );
                    break;
                }
            }
        }
    }

    /**
     * Send message to backend with timeout
     */
    async sendToBackend(message) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), this.config.timeout);
        try {
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
                }),
                signal: controller.signal
            });
            clearTimeout(timeoutId);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            clearTimeout(timeoutId);
            if (error.name === 'AbortError') {
                throw new Error('Yêu cầu quá thời gian chờ. Vui lòng thử lại.');
            }
            throw error;
        }
    }

    /**
     * Add enhanced message with source indication
     */
    addEnhancedMessage(response) {
        const { source, message, books = [] } = response;
        let sourceIcon = '🤖';
        let sourceText = 'AI Assistant';
        let sourceClass = 'ai';
        switch (source) {
            case 'database':
                sourceIcon = '💾';
                sourceText = 'Database';
                sourceClass = 'database';
                this.updateTypingStatus('Tìm thấy trong cơ sở dữ liệu');
                break;
            case 'pdf':
                sourceIcon = '📄';
                sourceText = 'PDF Document';
                sourceClass = 'pdf';
                this.updateTypingStatus('Tìm thấy trong tài liệu PDF');
                break;
            case 'ai':
                sourceIcon = '🧠';
                sourceText = 'AI Knowledge';
                sourceClass = 'ai';
                this.updateTypingStatus('Trả lời từ AI');
                break;
            case 'database_random_recommendation': // Thêm case này cho recommendation
                sourceIcon = '✨';
                sourceText = 'Gợi ý sách ngẫu nhiên';
                sourceClass = 'database';
                this.updateTypingStatus('Đang gợi ý sách...');
                break;
            case 'ai_summary': // Thêm case này cho tóm tắt sách
                sourceIcon = '📖';
                sourceText = 'Tóm tắt từ AI';
                sourceClass = 'ai';
                this.updateTypingStatus('Đang tóm tắt sách...');
                break;
        }
        setTimeout(() => {
            this.addMessage(message, 'bot', sourceClass, {
                sourceIcon,
                sourceText,
                books: books // Truyền mảng books vào đây
            });
        }, 500);
    }

    /**
     * Add message to chat (support source, books)
     */
    addMessage(content, sender, sourceClass = '', meta = {}) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender} ${sourceClass}`;
        const time = new Date().toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit'
        });
        let metaHtml = '';
        if (sender === 'bot' && meta.sourceIcon && meta.sourceText) {
            metaHtml = `<div class="message-meta"><span class="icon">${meta.sourceIcon}</span> <span class="text">${meta.sourceText}</span></div>`;
        }
        // Thay đổi dòng này để meta.books được xử lý bởi formatMessage riêng,
        // và message-time được đặt ngoài kết quả của formatMessage.
        messageDiv.innerHTML = `
            <div class="message-content">
                ${metaHtml}
                ${this.formatMessage(content, meta.books)}
                <div class="message-time">${time}</div>
            </div>
        `;
        messagesContainer.appendChild(messageDiv);
        this.messages.push({
            content: content,
            sender: sender,
            timestamp: new Date().toISOString(),
            source: sourceClass,
            meta: meta
        });
        this.saveSession();
        this.scrollToBottom();
    }

    /**
     * Format message content (support markdown-like formatting)
     * Now also handles product URLs.
     */
    formatMessage(content, books = []) {
        if (typeof content !== 'string') {
            content = String(content ?? '');
        }
        // Bold text
        content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // New lines
        content = content.replace(/\n/g, '<br>');

        // Handle URLs for books explicitly if they are in the message content
        // This regex specifically targets the "🔗 Chi tiết: URL" format
        content = content.replace(
            /🔗 Chi tiết:\s*(https?:\/\/[^\s<]+)/g,
            '<a href="$1" target="_blank" rel="noopener" class="product-detail-link">🔗 Chi tiết</a>'
        );

        // General URL formatting if any other URL is present
        content = content.replace(
            /(?<!href=")(https?:\/\/[^\s<]+)/g, // Avoid replacing URLs already inside href attributes
            '<a href="$1" target="_blank" rel="noopener" class="product-detail-link">$1</a>'
        );
        return content;
    }


    /**
     * Show enhanced typing indicator
     */
    showEnhancedTyping() {
        this.isTyping = true;
        document.getElementById('typingIndicator').classList.add('active');
        this.updateTypingStatus('Đang tìm kiếm...');
        this.scrollToBottom();
    }

    /**
     * Update typing status text
     */
    updateTypingStatus(text) {
        document.getElementById('typingText').textContent = text;
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
                <p>Tôi là trợ lý chatbot của cửa hàng sách. Tôi có thể giúp bạn:</p>
                <ul style="text-align: left; margin: 10px 0; padding-left: 20px;">
                    <li>🔍 Tìm kiếm sách theo tên, tác giả, thể loại</li>
                    <li>💰 Kiểm tra giá và khuyến mãi</li>
                    <li>📚 Tìm sách theo giá</li>

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
     * Update character count
     */
    updateCharacterCount() {
        const input = document.getElementById('chatbotInput');
        const count = input.value.length;
        document.getElementById('characterCount').textContent = `${count}/500`;
    }

    /**
     * Show suggestions (placeholder)
     */
    showSuggestions() {
        const suggestionsBox = document.getElementById('inputSuggestions');
        if (suggestionsBox) {
            suggestionsBox.style.display = 'block';
        }
    }

    hideSuggestions() {
        const suggestionsBox = document.getElementById('inputSuggestions');
        if (suggestionsBox) {
            suggestionsBox.style.display = 'none';
        }
    }

    /**
     * Handle typing (placeholder for future improvements)
     */
    handleTyping() {
        // You can implement typing detection, suggestions, etc.
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
                messages: this.messages.slice(-50)
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
     * Get CSRF token from meta tag
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Delay utility
     */
    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    /**
     * Show error message
     */
    showError(msg) {
        this.addMessage(msg, 'bot', 'error');
    }
}

// Initialize chatbot when DOM is loaded
// Check if FontAwesome is loaded, if not load it
document.addEventListener('DOMContentLoaded', () => {
    if (!document.querySelector('link[href*="fontawesome"]')) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';
        document.head.appendChild(link);
    }
    window.chatbot = new AdvancedChatbot();
});

window.AdvancedChatbot = AdvancedChatbot;
