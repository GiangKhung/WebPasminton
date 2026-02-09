<!-- Floating Action Button - Zalo + AI Chatbot -->
<div id="fab-container" class="fab-container">
    <!-- Main Toggle Button (dấu cộng) -->
    <button id="fab-toggle" class="fab-toggle" title="Liên hệ">
        <i class="fas fa-plus"></i>
    </button>
    
    <!-- Sub Buttons -->
    <div class="fab-buttons">
        <!-- Zalo Button -->
        <a href="https://zalo.me/0912431719" target="_blank" class="fab-btn fab-zalo" title="Chat Zalo">
            <span class="zalo-icon">Zalo</span>
            <span class="fab-label">Zalo</span>
        </a>
        
        <!-- AI Chatbot Button -->
        <button id="chatbot-toggle" class="fab-btn fab-chatbot" title="Chat với AI">
            <i class="fas fa-robot"></i>
            <span class="fab-label">AI Chat</span>
        </button>
    </div>
</div>

<!-- Chat Window -->
<div id="chatbot-window" class="chatbot-window">
    <div class="chatbot-header">
        <div class="chatbot-header-info">
            <div class="chatbot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="chatbot-title">
                <h4>VNB Assistant</h4>
                <span class="chatbot-status">
                    <span class="status-dot"></span>
                    Online
                </span>
            </div>
        </div>
        <button id="chatbot-close" class="chatbot-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div id="chatbot-messages" class="chatbot-messages">
        <div class="chat-message bot-message">
            <div class="message-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="message-content">
                <p>Xin chào! 👋 Tôi là trợ lý AI của VNB Sports.</p>
                <p>Tôi có thể giúp bạn tìm kiếm sản phẩm nhanh chóng:</p>
                <div class="quick-actions">
                    <button class="quick-btn" data-message="Tìm vợt cầu lông">🏸 Vợt</button>
                    <button class="quick-btn" data-message="Tìm giày cầu lông">👟 Giày</button>
                    <button class="quick-btn" data-message="Sản phẩm giảm giá">🏷️ Sale</button>
                    <button class="quick-btn" data-message="Sản phẩm nổi bật">⭐ Hot</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="chatbot-input-area">
        <form id="chatbot-form">
            <input type="text" id="chatbot-input" placeholder="Nhập tin nhắn..." autocomplete="off">
            <button type="submit" id="chatbot-send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<style>
/* FAB Container */
.fab-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9998;
    display: flex;
    flex-direction: column-reverse;
    align-items: center;
    gap: 12px;
}

/* Main Toggle Button */
.fab-toggle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 10;
}

.fab-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.fab-toggle i {
    font-size: 24px;
    color: white;
    transition: transform 0.3s ease;
}

.fab-container.active .fab-toggle i {
    transform: rotate(45deg);
}

/* Sub Buttons Container */
.fab-buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.fab-container.active .fab-buttons {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

/* Sub Button Style */
.fab-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    position: relative;
    text-decoration: none;
}

.fab-btn:hover {
    transform: scale(1.15);
}

/* Zalo Button */
.fab-zalo {
    background: #0068ff;
}

.zalo-icon {
    color: white;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: -0.5px;
}

/* Chatbot Button */
.fab-chatbot {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.fab-chatbot i {
    font-size: 20px;
    color: white;
}

/* Labels */
.fab-label {
    position: absolute;
    right: 60px;
    background: #333;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
}

.fab-btn:hover .fab-label {
    opacity: 1;
    visibility: visible;
}

.fab-label::after {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    transform: translateY(-50%);
    border: 6px solid transparent;
    border-left-color: #333;
}

/* Chat Window */
.chatbot-window {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 360px;
    height: 480px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
    animation: slideUp 0.3s ease;
}

.chatbot-window.active {
    display: flex;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.chatbot-header-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.chatbot-avatar {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chatbot-avatar i { font-size: 18px; }

.chatbot-title h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
}

.chatbot-status {
    font-size: 11px;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 4px;
}

.status-dot {
    width: 6px;
    height: 6px;
    background: #2ecc71;
    border-radius: 50%;
}

.chatbot-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chatbot-close:hover {
    background: rgba(255, 255, 255, 0.3);
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f8f9fa;
}

.chat-message {
    display: flex;
    gap: 8px;
    margin-bottom: 14px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.bot-message .message-avatar {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.bot-message .message-avatar i {
    color: white;
    font-size: 12px;
}

.message-content {
    background: white;
    padding: 10px 14px;
    border-radius: 14px;
    border-top-left-radius: 4px;
    max-width: 260px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.message-content p {
    margin: 0 0 6px 0;
    font-size: 13px;
    line-height: 1.4;
    color: #333;
}

.message-content p:last-child { margin-bottom: 0; }

.user-message {
    flex-direction: row-reverse;
}

.user-message .message-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 14px;
    border-top-right-radius: 4px;
}

.user-message .message-content p { color: white; }

.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
}

.quick-btn {
    background: #f0f2ff;
    border: 1px solid #667eea;
    color: #667eea;
    padding: 5px 10px;
    border-radius: 16px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.3s;
}

.quick-btn:hover {
    background: #667eea;
    color: white;
}

/* Product Cards */
.chat-products {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 8px;
}

.chat-product-card {
    display: flex;
    gap: 8px;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    color: inherit;
}

.chat-product-card:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.chat-product-img {
    width: 50px;
    height: 50px;
    border-radius: 6px;
    object-fit: cover;
    background: #ddd;
}

.chat-product-info {
    flex: 1;
    min-width: 0;
}

.chat-product-info h5 {
    margin: 0 0 4px 0;
    font-size: 12px;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-product-price {
    display: flex;
    align-items: center;
    gap: 6px;
}

.chat-product-price .price {
    color: #e74c3c;
    font-weight: 700;
    font-size: 12px;
}

.chat-product-price .old-price {
    color: #999;
    text-decoration: line-through;
    font-size: 10px;
}

.chat-product-price .discount {
    background: #e74c3c;
    color: white;
    font-size: 9px;
    padding: 2px 5px;
    border-radius: 4px;
}

/* Input Area */
.chatbot-input-area {
    padding: 12px;
    background: white;
    border-top: 1px solid #eee;
}

#chatbot-form {
    display: flex;
    gap: 8px;
}

#chatbot-input {
    flex: 1;
    padding: 10px 14px;
    border: 2px solid #e9ecef;
    border-radius: 20px;
    font-size: 13px;
    outline: none;
    transition: border-color 0.3s;
}

#chatbot-input:focus {
    border-color: #667eea;
}

#chatbot-send {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s;
}

#chatbot-send:hover {
    transform: scale(1.1);
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 10px 14px;
}

.typing-indicator span {
    width: 6px;
    height: 6px;
    background: #667eea;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-8px); }
}

/* Responsive */
@media (max-width: 480px) {
    .chatbot-window {
        width: calc(100vw - 40px);
        height: 70vh;
        bottom: 80px;
    }
    .fab-label { display: none; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fabContainer = document.getElementById('fab-container');
    const fabToggle = document.getElementById('fab-toggle');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatWindow = document.getElementById('chatbot-window');
    const chatClose = document.getElementById('chatbot-close');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const messages = document.getElementById('chatbot-messages');
    
    // Toggle FAB menu on hover
    fabContainer.addEventListener('mouseenter', () => {
        fabContainer.classList.add('active');
    });
    
    fabContainer.addEventListener('mouseleave', () => {
        if (!chatWindow.classList.contains('active')) {
            fabContainer.classList.remove('active');
        }
    });
    
    // Also toggle on click for mobile
    fabToggle.addEventListener('click', () => {
        fabContainer.classList.toggle('active');
    });
    
    // Open chatbot
    chatbotToggle.addEventListener('click', () => {
        chatWindow.classList.add('active');
        input.focus();
    });
    
    // Close chatbot
    chatClose.addEventListener('click', () => {
        chatWindow.classList.remove('active');
        fabContainer.classList.remove('active');
    });
    
    // Quick actions
    document.querySelectorAll('.quick-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            sendMessage(btn.dataset.message);
        });
    });
    
    // Form submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const message = input.value.trim();
        if (message) {
            sendMessage(message);
            input.value = '';
        }
    });
    
    function sendMessage(message) {
        addMessage(message, 'user');
        showTyping();
        
        fetch('<?= BASE_URL ?>/api/chat-search.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            hideTyping();
            if (data.error) {
                addMessage(data.error, 'bot');
            } else if (data.type === 'products') {
                addProductsMessage(data.message, data.products);
            } else {
                addMessage(data.message, 'bot');
            }
        })
        .catch(() => {
            hideTyping();
            addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại!', 'bot');
        });
    }
    
    function addMessage(text, type) {
        const div = document.createElement('div');
        div.className = `chat-message ${type}-message`;
        
        if (type === 'bot') {
            div.innerHTML = `
                <div class="message-avatar"><i class="fas fa-robot"></i></div>
                <div class="message-content"><p>${text.replace(/\n/g, '</p><p>')}</p></div>
            `;
        } else {
            div.innerHTML = `<div class="message-content"><p>${text}</p></div>`;
        }
        
        messages.appendChild(div);
        scrollToBottom();
    }
    
    function addProductsMessage(text, products) {
        const div = document.createElement('div');
        div.className = 'chat-message bot-message';
        
        let productsHtml = products.map(p => `
            <a href="<?= BASE_URL ?>/product-detail.php?slug=${p.slug}" class="chat-product-card">
                <img src="/shopcaulong/images/${p.image || 'product-placeholder.jpg'}" 
                     alt="${p.name}" class="chat-product-img"
                     onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                <div class="chat-product-info">
                    <h5>${p.name}</h5>
                    <div class="chat-product-price">
                        <span class="price">${p.price_formatted}</span>
                        ${p.old_price_formatted ? `<span class="old-price">${p.old_price_formatted}</span>` : ''}
                        ${p.discount > 0 ? `<span class="discount">-${p.discount}%</span>` : ''}
                    </div>
                </div>
            </a>
        `).join('');
        
        div.innerHTML = `
            <div class="message-avatar"><i class="fas fa-robot"></i></div>
            <div class="message-content">
                <p>${text}</p>
                <div class="chat-products">${productsHtml}</div>
            </div>
        `;
        
        messages.appendChild(div);
        scrollToBottom();
    }
    
    function showTyping() {
        const div = document.createElement('div');
        div.className = 'chat-message bot-message';
        div.id = 'typing-message';
        div.innerHTML = `
            <div class="message-avatar"><i class="fas fa-robot"></i></div>
            <div class="message-content">
                <div class="typing-indicator"><span></span><span></span><span></span></div>
            </div>
        `;
        messages.appendChild(div);
        scrollToBottom();
    }
    
    function hideTyping() {
        const typing = document.getElementById('typing-message');
        if (typing) typing.remove();
    }
    
    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }
});
</script>
