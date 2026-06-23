{{-- Мини-чат окно --}}
<div id="miniChatModal" class="mini-chat-modal">
    <div class="mini-chat-header">
        <div class="mini-chat-title">
            <div class="admin-avatar-mini"><img src="{{ asset('assets/images/chat/robot.jpg') }}" alt=""></div>
            <div class="mini-chat-info">
                <div class="mini-chat-name-wrapper">
                    <span class="name">Смарти</span>
                    <span class="ai-badge">AI</span>
                </div>
                <div class="mini-chat-status-wrapper">
                    <div class="status-dot" id="statusDot"></div>
                    <div class="status-text" id="statusText">Загрузка...</div>
                </div>
            </div>
        </div>
        <div class="mini-chat-actions">
            <button class="mini-chat-menu" id="miniChatMenu">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <circle cx="12" cy="6" r="1.5" />
                    <circle cx="12" cy="12" r="1.5" />
                    <circle cx="12" cy="18" r="1.5" />
                </svg>
            </button>
        </div>
        <!-- Выпадающее меню -->
        <div class="mini-chat-dropdown" id="miniChatDropdown">
            <button class="dropdown-item" data-action="clear-chat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                </svg>
                Очистить чат
            </button>
            <button class="dropdown-item" data-action="contact-salon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                </svg>
                Контакт салона
            </button>
            <button class="dropdown-item" data-action="complaint">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                Оставить жалобу
            </button>
            <button class="dropdown-item" data-action="export-chat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                </svg>
                Экспорт чата
            </button>
        </div>
    </div>
    
    <!-- Основной контент чата -->
    <div class="mini-chat-content" id="miniChatContent">

        <div class="chat-content" id="chatContent" style="display: flex;">
            <div class="mini-chat-messages" id="miniChatMessages">
            </div>
            <div class="mini-chat-typing" id="miniChatTyping" style="display: none;">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
                <span>Смарти печатает...</span>
            </div>
            <div class="mini-chat-input-container">
                <div class="mini-quick-actions">
                    <button class="mini-chip" data-action="callback"><i class="bi bi-phone"></i>Обратный звонок</button>
                    <button class="mini-chip" data-action="price"><i class="bi bi-phone"></i>Прайс</button>
                </div>
                <div class="mini-chat-input-wrapper">
                    <input type="text" id="miniChatInput" placeholder="Напишите сообщение..." class="mini-chat-input">
                    <button id="miniChatSend" class="mini-chat-send">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения очистки чата -->
    <div id="confirmClearModal" class="complaint-modal-inner">
        <div class="complaint-modal-header">
            <h3>Подтверждение</h3>
            <button class="complaint-modal-close" id="confirmClearClose">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" />
                </svg>
            </button>
        </div>
        <div class="complaint-modal-body">
            <div class="confirm-message">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="#FF9500" style="margin: 0 auto 16px; display: block;">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                </svg>
                <p style="text-align: center; margin-bottom: 12px; color: #1D1D1F;" class="confirm-p">Вы уверены, что хотите очистить историю чата?</p>
                <p style="text-align: center; color: #6B7280; font-size: 13px; margin-bottom: 24px;">
                    Все сообщения будут удалены без возможности восстановления.
                </p>
            </div>
            <div class="complaint-modal-actions">
                <button type="button" class="btn-secondary" id="confirmClearCancel">Отмена</button>
                <button type="button" class="btn-primary" id="confirmClearOk">Очистить чат</button>
            </div>
        </div>
    </div>

    <!-- Модальное окно для жалобы-->
    <div id="complaintModal" class="complaint-modal-inner">
        <div class="complaint-modal-header">
            <h3>Оставить жалобу</h3>
            <button class="complaint-modal-close" id="complaintModalClose">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" />
                </svg>
            </button>
        </div>
        <div class="complaint-modal-body">
            <form id="complaintForm">
                <div class="form-group">
                    <label for="complaintType">Тип жалобы</label>
                    <select id="complaintType" class="form-select" required>
                        <option value="">Выберите тип жалобы</option>
                        <option value="technical">Техническая проблема</option>
                        <option value="service">Качество обслуживания</option>
                        <option value="content">Некорректный контент</option>
                        <option value="other">Другое</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="complaintText">Описание проблемы</label>
                    <textarea id="complaintText" class="form-textarea" placeholder="Подробно опишите вашу проблему или жалобу..." rows="4" required></textarea>
                </div>
                <div class="complaint-modal-actions">
                    <button type="button" class="btn-secondary" id="complaintCancel">Отмена</button>
                    <button type="submit" class="btn-primary">Отправить жалобу</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Модальное окно контактов салона -->
    <div id="contactSalonModal" class="complaint-modal-inner">
        <div class="complaint-modal-header">
            <h3>Контакт салона</h3>
            <button class="complaint-modal-close" id="contactSalonClose">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" />
                </svg>
            </button>
        </div>
        <div class="complaint-modal-body">
            <div class="contact-info">
                <div class="contact-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#4071CB">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z" />
                    </svg>
                    <span>Email: info@salon.ru</span>
                </div>
                <div class="contact-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#4071CB">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                    </svg>
                    <span>Телефон: +7 (987) 416-10-10</span>
                </div>
                <div class="contact-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#4071CB">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                    <span>Адрес: г. Москва, ул. Примерная, д. 1</span>
                </div>
                <div class="contact-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#4071CB">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                    <span>Время работы: Пн-Пт 9:00-19:00, Сб 10:00-18:00</span>
                </div>
            </div>
            <div class="complaint-modal-actions">
                <button type="button" class="btn-primary" id="contactSalonBack">Назад</button>
            </div>
        </div>
    </div>
    
</div>