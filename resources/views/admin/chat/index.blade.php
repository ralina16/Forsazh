@extends('layouts.admin')

@section('title', 'Чат-менеджер')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f9ff;
            --panel-bg: #ffffff;
            --accent: #4071cb;
            --accent-light: rgba(64, 113, 203, 0.1);
            --accent-gradient: linear-gradient(135deg, #4071cb, #2f5bb7);
            --text-primary: #1e293b;
            --text-muted: #5f6c80;
            --text-secondary: #7a8a9e;
            --border: #eef2f6;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 40px -8px rgba(64, 113, 203, 0.2);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --transition: all 0.2s ease;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        body.chat-body {
            margin: 0;
            background: var(--bg);
            color: var(--text-primary);
            font-family: 'Gilroy', sans-serif;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
        }

        .navbar,
        .admin-header,
        .admin-navbar,
        .admin-footer,
        footer,
        .sidebar,
        .breadcrumb,
        .container-fluid>.row:first-child {
            display: none !important;
        }

        main.container-xxl.py-4,
        main {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        .app {
            flex: 1;
            display: flex;
            overflow: hidden;
            background: var(--panel-bg);
            width: 100%;
            height: 100vh;
        }

        /* ===== ВКЛАДКИ ===== */
        .chat-tabs {
            display: flex;
            justify-content: space-between;
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 26px;
            gap: 4px;
            flex-shrink: 0;
        }

        .chat-tab {
            padding: 14px 20px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border: none;
            background: none;
            position: relative;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-tab:hover {
            color: var(--accent);
        }

        .chat-tab.active {
            color: var(--accent);
        }

        .chat-tab.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--accent);
            border-radius: 3px 3px 0 0;
        }

        .chat-tab-badge {
            background: linear-gradient(145deg, #ff4757, #ff6b81);
            color: white;
            font-size: 11px;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }

        /* ===== ЛЕВАЯ ПАНЕЛЬ ===== */
        .left-panel {
            width: 320px;
            background: #ffffff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        .left-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 16px;
            transition: var(--transition);
            cursor: pointer;
        }

        .icon-btn:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        .icon-btn[title="Новый чат"] {
            background: linear-gradient(145deg, #4071cb, #2a5a9c) !important;
            color: white;
        }

        .search-reveal {
            height: 0;
            overflow: hidden;
            transition: height 0.22s ease, padding 0.22s ease;
        }

        .search-reveal.open {
            height: 52px;
            padding: 8px 14px;
        }

        .search-reveal .form-control {
            background: #f8fafd;
            border: 1px solid transparent;
            border-radius: 30px;
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-reveal .form-control:focus {
            background: white;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-light);
            outline: none;
        }

        .chats-container {
            flex: 1;
            overflow: hidden;
            position: relative;
        }

        .chats {
            height: 100%;
            overflow-y: auto;
            padding: 12px;
            scrollbar-width: thin;
        }

        .chats::-webkit-scrollbar {
            width: 4px;
        }

        .chat-item {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 12px;
            margin: 0 0 8px 0;
            border-radius: var(--radius-lg);
            background: white;
            border: 1px solid transparent;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .chat-item:hover {
            box-shadow: 0 4px 16px rgba(64, 113, 203, 0.12);
        }

        .chat-item.active {
            background: var(--accent-gradient);
            border: none;
            box-shadow: 0 8px 20px rgba(64, 113, 203, 0.3);
        }

        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: white;
            background: linear-gradient(145deg, #4071cb, #2a5a9c);
            flex-shrink: 0;
        }

        .chat-item.active .avatar {
            background: white !important;
            color: var(--accent);
        }

        .chat-info {
            flex: 1;
            min-width: 0;
        }

        .chat-info .name {
            font-weight: 700;
            font-size: 15px;
            color: #1e2a3a;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .chat-item.active .chat-info .name {
            color: white;
        }

        .chat-info .preview {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-item.active .chat-info .preview {
            color: rgba(255, 255, 255, 0.85);
        }

        .right-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .time-badge {
            font-size: 11px;
            font-weight: 500;
            color: #8a9bb0;
        }

        .chat-item.active .time-badge {
            color: rgba(255, 255, 255, 0.8);
        }

        .badge-unread {
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            background: linear-gradient(145deg, #ff4757, #ff6b81);
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            font-size: 11px;
            font-weight: 700;
        }

        /* ===== ПРАВАЯ ПАНЕЛЬ ===== */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--panel-bg);
            min-width: 0;
            position: relative;
        }

        .topbars {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            z-index: 5;
            flex-shrink: 0;
        }

        .user {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: var(--accent-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        #chatTitle {
            font-weight: 700;
            font-size: 15px;
            color: var(--text-primary);
        }

        #chatSubtitle {
            font-size: 12px;
            color: var(--text-muted);
        }

        .controls {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .controls .btn {
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 18px;
            padding: 8px;
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .controls .btn:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        .messages-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            position: relative;
        }

        .messages-wrap {
            flex: 1;
            overflow-y: auto;
            padding: 24px 32px;
            background: #f8fafc;
            scroll-behavior: smooth;
            min-height: 0;
        }

        .messages-wrap::-webkit-scrollbar {
            width: 6px;
        }

        .msgs {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .date-sep {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            width: fit-content;
            margin: 8px auto;
            border: 1px solid var(--border);
        }

        .msg-row {
            display: flex;
            align-items: flex-end;
            animation: slideInUp 0.3s ease;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .msg-row.sent {
            justify-content: flex-end;
        }

        .msg-row.received {
            justify-content: flex-start;
        }

        .msg-bubble {
            border-radius: 18px;
            padding: 10px 16px;
            font-size: 14px;
            line-height: 1.5;
            max-width: 65%;
            position: relative;
            word-wrap: break-word;
        }

        .msg-bubble.sent {
            background: linear-gradient(145deg, #4071cb, #2a5a9c);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .msg-bubble.received {
            background: white;
            color: var(--text-primary);
            border-bottom-left-radius: 4px;
            border: 1px solid rgba(64, 113, 203, 0.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .msg-text {
            word-wrap: break-word;
        }

        .chat-car-price {
            color: #fff !important;
        }

        .chat-car-btn {
            color: #fff !important
        }

        .chat-price-all {
            color: #fff !important
        }

        .chat-car-name {
            color: #fff !important
        }


        .bubble-meta {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            margin-top: 4px;
            opacity: 0.7;
            justify-content: flex-end;
        }

        .reply-indicator {
            background: white;
            border-top: 1px solid var(--border);
            padding: 12px 24px;
            display: none;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
        }

        .reply-indicator::before {
            content: '';
            position: absolute;
            left: 24px;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        .reply-indicator-content {
            flex: 1;
            margin-left: 12px;
            min-width: 0;
        }

        .reply-indicator strong {
            display: block;
            font-size: 12px;
            color: var(--accent);
            margin-bottom: 2px;
            font-weight: 600;
        }

        .reply-indicator-text {
            font-size: 13px;
            color: var(--text-primary);
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cancel-reply {
            background: #f1f5f9;
            border: none;
            color: var(--text-muted);
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
            flex-shrink: 0;
        }

        .cancel-reply:hover {
            background: #e2e8f0;
            color: var(--text-primary);
        }

        .composer {
            padding: 14px 24px;
            border-top: 1px solid var(--border);
            background: white;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-shrink: 0;
        }

        .composer .form-control {
            background: #f1f5f9;
            border: none;
            border-radius: 24px;
            padding: 12px 18px;
            font-size: 14px;
            transition: var(--transition);
        }

        .composer .form-control:focus {
            background: white;
            box-shadow: 0 0 0 3px var(--accent-light);
            outline: none;
        }

        .send-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(145deg, #4071cb, #2a5a9c);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(64, 113, 203, 0.25);
            transition: transform 0.2s;
            cursor: pointer;
            flex-shrink: 0;
        }

        .send-btn:hover {
            transform: scale(1.08);
        }

        .send-btn img {
            width: 18px;
            height: 18px;
            filter: brightness(0) invert(1);
        }

        .modal-content {
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .modal-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon.success {
            background: rgba(47, 107, 240, 0.12);
        }

        .modal-icon.warning {
            background: rgba(239, 68, 68, 0.12);
        }

        .modal-icon.info {
            background: rgba(59, 130, 246, 0.12);
        }

        .modal-title {
            color: #333;
            font-weight: 600;
            font-size: 20px;
            text-align: center;
        }

        .btn-primary-gradient {
            background: linear-gradient(180deg, #2f6bf0, #245be6);
            color: white;
            border-radius: var(--radius-md);
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: var(--transition);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-danger-gradient {
            background: linear-gradient(180deg, #ef4444, #dc2626);
            color: white;
            border-radius: var(--radius-md);
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: var(--transition);
        }

        .btn-secondary-light {
            background: #f3f4f6;
            color: #374151;
            border-radius: var(--radius-md);
            padding: 12px;
            font-weight: 500;
            width: 100%;
            border: 1px solid #e5e7eb;
            transition: var(--transition);
        }

        .template-item {
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            margin-bottom: 8px;
            cursor: pointer;
            transition: var(--transition);
            background: #fafbfc;
        }

        .template-item:hover {
            background: var(--accent-light);
            border-color: var(--accent);
        }

        .template-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            background: var(--accent);
            color: white;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-right: 10px;
        }

        /* Контекстное меню */
        .message-context-menu {
            display: none;
            position: fixed;
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 6px;
            z-index: 1001;
            min-width: 160px;
        }

        .message-context-menu.show {
            display: block;
        }

        .message-context-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 13px;
            color: var(--text-primary);
        }

        .message-context-item:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        .message-context-item.delete:hover {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
        }

        .custom-dropdown {
            position: absolute;
            min-width: 200px;
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            padding: 6px;
            display: none;
            z-index: 99999;
            font-size: 13px;
        }

        .custom-dropdown.show {
            display: block;
        }

        .custom-dropdown .custom-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            color: var(--text-primary);
        }

        .custom-dropdown .custom-item:hover {
            background: var(--accent-light);
            color: var(--accent);
        }

        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 1100;
            box-shadow: var(--shadow-lg);
            animation: fadeIn 0.2s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .left-panel {
                position: fixed;
                top: 0;
                bottom: 0;
                left: -100%;
                width: 85%;
                max-width: 340px;
                z-index: 1200;
                transition: left 0.26s ease;
            }

            .left-panel.open {
                left: 0;
            }

            .chat-tabs {
                padding: 0 12px;
            }

            .chat-tab {
                padding: 12px 14px;
                font-size: 13px;
            }

            .messages-wrap {
                padding: 16px;
            }

            .msg-bubble {
                max-width: 80%;
            }
        }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="app">
        <aside id="leftPanel" class="left-panel">
            <div class="chat-tabs">
                <button class="chat-tab active" data-chat-type="mini_chat" onclick="switchChatType('mini_chat')">
                    Мини-чат
                </button>
                <button class="chat-tab" data-chat-type="configurator" onclick="switchChatType('configurator')">
                    Конфигуратор
                </button>
            </div>

            <div class="left-top">
                <div class="brand" id="chatListTitle">Чаты клиентов</div>
                <div class="left-actions">
                    <button id="searchIcon" class="icon-btn" title="Поиск"><i class="bi bi-search"></i></button>
                    <button id="newChatBtn" class="icon-btn" title="Новый чат">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>

            <div id="searchReveal" class="search-reveal">
                <div style="height:100%;display:flex;align-items:center;padding:0 2px;">
                    <input id="searchInput" class="form-control" placeholder="Поиск по имени, телефону..." />
                </div>
            </div>

            <div class="chats-container">
                <div class="chats" id="chatList">
                    <div class="text-center text-muted p-4">Загрузка чатов...</div>
                </div>
            </div>
        </aside>

        <div id="overlay" class="overlay" style="display:none"></div>

        <section class="right-panel">
            <div class="topbars">
                <div class="user" style="gap:10px">
                    <button id="burgerBtn" class="icon-btn" title="Меню" style="display:none"><i
                            class="bi bi-list"></i></button>
                    <div id="topAvatar" class="avatar-sm">—</div>
                    <div>
                        <div class="fw-bold" id="chatTitle">Выберите чат</div>
                        <div class="text-muted" id="chatSubtitle" style="font-size:12px">Информация о пользователе</div>
                    </div>
                </div>

                <div class="controls">
                    <button id="cannedBtn" class="btn" title="Шаблоны"><i class="bi bi-chat-dots"></i></button>
                    <button class="btn" id="menuBtn" title="Меню">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
            </div>

            <div class="messages-area">
                <div id="messagesWrap" class="messages-wrap">
                    <div id="msgs" class="msgs">
                        <div class="text-center text-muted p-4">Выберите чат для начала общения</div>
                    </div>
                </div>

                <div class="reply-indicator" id="replyIndicator">
                    <div class="reply-indicator-content">
                        <strong>Ответ на сообщение:</strong>
                        <div class="reply-indicator-text" id="replyText"></div>
                    </div>
                    <button class="cancel-reply" onclick="cancelReply()" title="Отменить">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>

            <div id="composer" class="composer">
                <input id="inputMessage" class="form-control" placeholder="Введите сообщение..." autocomplete="off"
                    disabled />
                <button id="sendBtn" class="send-btn" title="Отправить" disabled>
                    <img src="{{ asset('assets/images/chat/icon.svg') }}" alt=""
                        style="width:18px;height:18px;filter:brightness(0) invert(1)">
                </button>
            </div>
        </section>

        <!-- Меню -->
        <div id="customMenu" class="custom-dropdown">
            <div class="custom-item" data-action="clear"><i class="bi bi-trash"></i> Очистить чат</div>
            <div class="custom-item delete" data-action="delete_user"><i class="bi bi-person-x"></i> Удалить пользователя
            </div>
        </div>

        <!-- Модалки -->
        <div class="modal fade" id="addChatModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4 text-center">
                        <div class="modal-icon info">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3b82f6"
                                stroke-width="1.5">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-3">Добавить новый чат</h5>
                        <form id="addChatForm">
                            <div class="mb-3 text-start">
                                <label class="form-label">Имя пользователя *</label>
                                <input type="text" id="newUserName" class="form-control" required>
                            </div>
                            <div class="mb-4 text-start">
                                <label class="form-label">Телефон</label>
                                <input type="tel" id="newUserPhone" class="form-control"
                                    placeholder="+7 (___) ___-__-__">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary-gradient">
                                    <i class="bi bi-person-plus"></i> Создать чат
                                </button>
                                <button type="button" class="btn btn-secondary-light"
                                    data-bs-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="clearChatModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4 text-center">
                        <div class="modal-icon warning">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444"
                                stroke-width="1.5">
                                <path
                                    d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h18z" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-3">Очистить чат?</h5>
                        <p class="mb-4 text-muted">Все сообщения будут удалены без возможности восстановления.</p>
                        <div class="d-grid gap-2">
                            <button id="confirmClearBtn" class="btn btn-danger-gradient">Да, очистить</button>
                            <button class="btn btn-secondary-light" data-bs-dismiss="modal">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteUserModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4 text-center">
                        <div class="modal-icon warning">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444"
                                stroke-width="1.5">
                                <path
                                    d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h18z" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-3">Удалить пользователя?</h5>
                        <p class="mb-4 text-muted">Пользователь и вся переписка будут удалены безвозвратно.</p>
                        <div class="d-grid gap-2">
                            <button id="confirmDeleteBtn" class="btn btn-danger-gradient">Удалить</button>
                            <button class="btn btn-secondary-light" data-bs-dismiss="modal">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="templateModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4">
                        <div class="modal-icon info mb-3">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3b82f6"
                                stroke-width="1.5">
                                <path d="M4 4h16v16H4z" />
                                <path d="M8 10h8M8 14h8M8 18h5" />
                            </svg>
                        </div>
                        <h5 class="modal-title text-center mb-4">Шаблоны сообщений</h5>
                        <div class="template-list" id="templateList" style="max-height:300px;overflow-y:auto"></div>
                        <div class="d-grid mt-3">
                            <button class="btn btn-secondary-light" data-bs-dismiss="modal">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteMessageModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body p-4 text-center">
                        <div class="modal-icon warning">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444"
                                stroke-width="1.5">
                                <path
                                    d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h18z" />
                            </svg>
                        </div>
                        <h5 class="modal-title mb-3">Удалить сообщение?</h5>
                        <p class="mb-4 text-muted" id="deleteMessageText">Это действие нельзя отменить.</p>
                        <div class="d-grid gap-2">
                            <button id="confirmDeleteMessageBtn" class="btn btn-danger-gradient">Удалить</button>
                            <button class="btn btn-secondary-light" data-bs-dismiss="modal">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Контекстное меню -->
        <div id="messageContextMenu" class="message-context-menu">
            <div class="message-context-item copy" data-action="copy">
                <i class="bi bi-copy"></i><span>Копировать</span>
            </div>
            <div class="message-context-item reply" data-action="reply">
                <i class="bi bi-reply"></i><span>Ответить</span>
            </div>
            <div class="divider"></div>
            <div class="message-context-item delete" data-action="delete" style="display:none">
                <i class="bi bi-trash"></i><span>Удалить</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const routes = {
            getUsers: '{{ route('admin.chat.getUsers') }}',
            getUserInfo: '{{ route('admin.chat.getUserInfo') }}',
            getMessages: '{{ route('admin.chat.getMessages') }}',
            sendMessage: '{{ route('admin.chat.sendMessage') }}',
            clearMessages: '{{ route('admin.chat.clearMessages') }}',
            deleteUser: '{{ route('admin.chat.deleteUser') }}',
            createUser: '{{ route('admin.chat.createUser') }}',
            deleteMessage: '{{ route('admin.chat.deleteMessage') }}',
        };

        async function fetchJson(url, data) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new URLSearchParams(data)
            });
            return await response.json();
        }

        // === СОСТОЯНИЕ ===
        let currentChatType = 'mini_chat';
        let users = [];
        let activeUserId = null;
        let currentMessages = [];
        let lastMessageId = 0;
        let currentReplyMessage = null;
        let currentMessageToDelete = null;

        // === DOM ===
        const chatListEl = document.getElementById('chatList');
        const msgsEl = document.getElementById('msgs');
        const messagesWrap = document.getElementById('messagesWrap');
        const input = document.getElementById('inputMessage');
        const sendBtn = document.getElementById('sendBtn');
        const replyIndicator = document.getElementById('replyIndicator');
        const replyText = document.getElementById('replyText');

        const cannedTemplates = [
            'Здравствуйте! Спасибо за обращение — чем могу помочь?',
            'Могу записать вас на тест-драйв. Удобное время?',
            'Мы можем предложить скидку при оформлении прямо сейчас.',
            'Хорошо, пришлю полный прайс и VIN-спецификацию.',
            'Для оформления потребуются паспорт и водительское удостоверение.',
            'Автомобиль доступен для осмотра в нашем салоне.',
            'Можем организовать доставку автомобиля для тест-драйва.',
            'Есть несколько вариантов кредитования с разными условиями.',
            'Специальное предложение: бесплатное ТО при покупке.',
            'Можем рассмотреть вариант trade-in вашего автомобиля.'
        ];

        // === ПЕРЕКЛЮЧЕНИЕ ВКЛАДОК ===
        function switchChatType(type) {
            currentChatType = type;
            activeUserId = null;
            currentMessages = [];
            lastMessageId = 0;
            currentReplyMessage = null;
            cancelReply();

            // Обновляем вкладки
            document.querySelectorAll('.chat-tab').forEach(tab => {
                tab.classList.toggle('active', tab.dataset.chatType === type);
            });

            // Сбрасываем UI
            msgsEl.innerHTML = '<div class="text-center text-muted p-4">Выберите чат</div>';
            document.getElementById('chatTitle').textContent = 'Выберите чат';
            document.getElementById('chatSubtitle').textContent = 'Информация о пользователе';
            document.getElementById('topAvatar').textContent = '—';
            input.disabled = true;
            sendBtn.disabled = true;

            loadUsers();
        }

        // === ВСПОМОГАТЕЛЬНЫЕ ===
        function escapeHtml(s) {
            if (!s) return '';
            return String(s).replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');
        }

        function groupMessagesByDate(messages) {
            const groups = {};
            messages.forEach(m => {
                if (!m.created_at) return;
                const d = new Date(m.created_at.replace(' ', 'T'));
                if (isNaN(d)) return;
                const key = d.toDateString();
                if (!groups[key]) groups[key] = {
                    date: m.created_at,
                    messages: []
                };
                groups[key].messages.push(m);
            });
            return groups;
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const d = new Date(dateString.replace(' ', 'T'));
            if (isNaN(d)) return '';
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            if (d.toDateString() === today.toDateString()) return 'Сегодня';
            if (d.toDateString() === yesterday.toDateString()) return 'Вчера';
            return d.toLocaleDateString('ru-RU', {
                day: 'numeric',
                month: 'long'
            });
        }

        // === СООБЩЕНИЯ ===
        function showMessageContextMenu(e, bubble, m) {
            e.preventDefault();
            hideMessageContextMenu();
            bubble.classList.add('active');

            const menu = document.getElementById('messageContextMenu');
            menu.style.display = 'block';
            menu.style.left = e.clientX + 'px';
            menu.style.top = e.clientY + 'px';
            menu.classList.add('show');

            const isSent = bubble.closest('.msg-row').classList.contains('sent');
            menu.querySelector('[data-action="delete"]').style.display = isSent ? 'flex' : 'none';

            menu.querySelector('[data-action="copy"]').onclick = () => {
                navigator.clipboard.writeText(m.text);
                hideMessageContextMenu();
            };
            menu.querySelector('[data-action="reply"]').onclick = () => {
                replyToMessage(m.id, m.text);
                hideMessageContextMenu();
            };
            if (isSent) {
                menu.querySelector('[data-action="delete"]').onclick = () => {
                    showDeleteMessageModal(m.id, m.text);
                    hideMessageContextMenu();
                };
            }
        }

        function hideMessageContextMenu() {
            document.getElementById('messageContextMenu').classList.remove('show');
            document.querySelectorAll('.msg-bubble.active').forEach(b => b.classList.remove('active'));
        }

        function replyToMessage(id, text) {
            currentReplyMessage = {
                id,
                text
            };
            const preview = text.length > 80 ? text.substring(0, 80) + '...' : text;
            replyText.textContent = preview;
            replyIndicator.style.display = 'flex';
            input.focus();
        }

        function cancelReply() {
            currentReplyMessage = null;
            replyIndicator.style.display = 'none';
        }

        function showDeleteMessageModal(id, text) {
            currentMessageToDelete = {
                id,
                text
            };
            const preview = text.length > 60 ? text.substring(0, 60) + '...' : text;
            document.getElementById('deleteMessageText').textContent = `Удалить: "${preview}"?`;
            new bootstrap.Modal(document.getElementById('deleteMessageModal')).show();
        }

        function showNotification(msg) {
            const n = document.createElement('div');
            n.className = 'notification';
            n.textContent = msg;
            document.body.appendChild(n);
            setTimeout(() => n.remove(), 2000);
        }

        // === ЗАГРУЗКА ===
        function loadUsers(q = '') {
            fetchJson(routes.getUsers, {
                q,
                chat_type: currentChatType
            }).then(data => {
                if (data.success) {
                    users = data.users || [];
                    users.sort((a, b) => {
                        if (a.unread > 0 && b.unread === 0) return -1;
                        if (a.unread === 0 && b.unread > 0) return 1;
                        return new Date(b.last_activity || b.created_at) - new Date(a.last_activity || a
                            .created_at);
                    });
                    renderUserList();
                } else {
                    chatListEl.innerHTML = '<div class="text-center text-muted p-4">Ошибка загрузки</div>';
                }
            });
        }

        function renderUserList() {
            chatListEl.innerHTML = '';
            if (!users.length) {
                chatListEl.innerHTML = '<div class="text-center text-muted p-4">Нет чатов</div>';
                return;
            }
            users.forEach(u => {
                const el = document.createElement('div');
                el.className = 'chat-item' + (u.user_id === activeUserId ? ' active' : '');
                el.dataset.id = u.user_id;
                const time = u.last_time ? new Date(u.last_time).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                }) : '';
                el.innerHTML = `
                    <div class="avatar">${escapeHtml((u.name || u.user_id).slice(0, 2).toUpperCase())}</div>
                    <div class="chat-info">
                        <div class="name">${escapeHtml(u.name || u.user_id)}</div>
                        <div class="preview">${escapeHtml(u.last_message || 'Нет сообщений')}</div>
                    </div>
                    <div class="right-meta">
                        <div class="time-badge">${time}</div>
                        ${u.unread ? `<div class="badge-unread">${u.unread}</div>` : ''}
                    </div>
                `;
                el.addEventListener('click', () => setActiveUser(u.user_id));
                chatListEl.appendChild(el);
            });
        }

        function setActiveUser(userId) {
            activeUserId = userId;
            currentMessages = [];
            lastMessageId = 0;
            cancelReply();
            msgsEl.innerHTML = '<div class="text-center text-muted p-4">Загрузка...</div>';
            document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
            document.querySelector(`.chat-item[data-id="${userId}"]`)?.classList.add('active');

            fetchJson(routes.getUserInfo, {
                user_id: userId
            }).then(data => {
                if (data.success) {
                    const u = data.user;
                    document.getElementById('chatTitle').textContent = u.name || u.user_id;
                    document.getElementById('chatSubtitle').textContent = u.phone ? 'Тел: ' + u.phone : 'ID: ' + u
                        .user_id;
                    document.getElementById('topAvatar').textContent = (u.name || u.user_id).slice(0, 2)
                        .toUpperCase();
                }
            });

            input.disabled = false;
            sendBtn.disabled = false;
            loadMessages(userId);
            loadUsers(document.getElementById('searchInput').value.trim());
        }

        function loadMessages(userId, isUpdate = false) {
            if (!userId) return;
            if (!isUpdate) msgsEl.innerHTML = '<div class="text-center text-muted p-4">Загрузка...</div>';

            fetchJson(routes.getMessages, {
                    user_id: userId,
                    last_message_id: isUpdate ? lastMessageId : 0,
                    chat_type: currentChatType
                })
                .then(data => {
                    if (!data.success) {
                        if (!isUpdate) msgsEl.innerHTML = '<div class="text-center text-muted p-4">Ошибка</div>';
                        return;
                    }
                    const msgs = data.messages || [];
                    if (msgs.length) {
                        if (!isUpdate) {
                            currentMessages = msgs;
                            renderMessages();
                        } else {
                            appendNewMessages(msgs);
                        }
                        lastMessageId = Math.max(...msgs.map(m => m.id), lastMessageId);
                    } else if (!isUpdate) {
                        msgsEl.innerHTML = '<div class="text-center text-muted p-4">Чат пуст. Начните общение!</div>';
                    }
                });
        }

        function renderMessages() {
            msgsEl.innerHTML = '';
            if (!currentMessages.length) {
                msgsEl.innerHTML = '<div class="text-center text-muted p-4">Чат пуст</div>';
                return;
            }
            const groups = groupMessagesByDate(currentMessages);
            Object.keys(groups).sort((a, b) => new Date(groups[a].date) - new Date(groups[b].date))
                .forEach(key => {
                    const g = groups[key];
                    const sep = document.createElement('div');
                    sep.className = 'date-sep';
                    sep.textContent = formatDate(g.date);
                    msgsEl.appendChild(sep);
                    g.messages.forEach(m => addMessageToDOM(m));
                });
            scrollToBottom();
        }

        function addMessageToDOM(m) {
            if (document.querySelector(`.msg-bubble[data-msgid="${m.id}"]`)) return;

            const row = document.createElement('div');
            row.className = 'msg-row ' + (m.dir === 'sent' ? 'sent' : 'received');

            const bubble = document.createElement('div');
            bubble.className = 'msg-bubble ' + (m.dir === 'sent' ? 'sent' : 'received');
            bubble.dataset.msgid = m.id;
            bubble.addEventListener('contextmenu', e => showMessageContextMenu(e, bubble, m));

            const textDiv = document.createElement('div');
            textDiv.className = 'msg-text';
            textDiv.innerHTML = m.format === 'price_card' && m.html ? m.html : escapeHtml(m.text).replace(/\n/g, '<br>');

            const meta = document.createElement('div');
            meta.className = 'bubble-meta';
            meta.innerHTML = `<span>${m.time || ''}</span>` + (m.dir === 'sent' ? ' <i class="bi bi-check2-all"></i>' : '');

            bubble.appendChild(textDiv);
            bubble.appendChild(meta);
            row.appendChild(bubble);
            msgsEl.appendChild(row);
        }

        function appendNewMessages(msgs) {
            let hasNew = false;
            msgs.forEach(m => {
                if (!document.querySelector(`.msg-bubble[data-msgid="${m.id}"]`)) {
                    addMessageToDOM(m);
                    currentMessages.push(m);
                    hasNew = true;
                }
            });
            if (hasNew) scrollToBottom();
        }

        function scrollToBottom() {
            setTimeout(() => messagesWrap.scrollTop = messagesWrap.scrollHeight, 50);
        }

        // === ОТПРАВКА ===
        sendBtn.addEventListener('click', () => {
            const text = input.value.trim();
            if (!text || !activeUserId) return;

            let finalText = text;
            if (currentReplyMessage) {
                finalText = `Ответ на: "${currentReplyMessage.text}"\n\n${text}`;
                cancelReply();
            }

            sendBtn.disabled = true;
            fetchJson(routes.sendMessage, {
                    user_id: activeUserId,
                    text: finalText,
                    chat_type: currentChatType
                })
                .then(data => {
                    sendBtn.disabled = false;
                    if (data.success) {
                        input.value = '';
                        setTimeout(() => loadMessages(activeUserId, true), 300);
                        loadUsers();
                    } else {
                        alert('Ошибка: ' + (data.error || 'Неизвестная ошибка'));
                    }
                })
                .catch(() => {
                    sendBtn.disabled = false;
                    alert('Ошибка сети');
                });
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendBtn.click();
            }
        });

        // === ШАБЛОНЫ ===
        document.getElementById('cannedBtn').addEventListener('click', () => {
            const list = document.getElementById('templateList');
            list.innerHTML = '';
            cannedTemplates.forEach((t, i) => {
                const item = document.createElement('div');
                item.className = 'template-item';
                item.innerHTML = `<span class="template-number">${i + 1}</span>${escapeHtml(t)}`;
                item.addEventListener('click', () => {
                    input.value = t;
                    input.focus();
                    bootstrap.Modal.getInstance(document.getElementById('templateModal')).hide();
                });
                list.appendChild(item);
            });
            new bootstrap.Modal(document.getElementById('templateModal')).show();
        });

        // === МЕНЮ ===
        const menuBtn = document.getElementById('menuBtn');
        const customMenu = document.getElementById('customMenu');
        let isMenuOpen = false;

        menuBtn.addEventListener('click', e => {
            e.stopPropagation();
            if (!activeUserId) {
                alert('Выберите чат');
                return;
            }
            isMenuOpen = !isMenuOpen;
            customMenu.style.display = isMenuOpen ? 'block' : 'none';
            customMenu.classList.toggle('show', isMenuOpen);
            const rect = menuBtn.getBoundingClientRect();
            customMenu.style.left = (rect.right - customMenu.offsetWidth) + 'px';
            customMenu.style.top = (rect.bottom + 8) + 'px';
        });

        customMenu.addEventListener('click', e => {
            const item = e.target.closest('.custom-item');
            if (!item) return;
            const action = item.dataset.action;
            if (action === 'clear') new bootstrap.Modal(document.getElementById('clearChatModal')).show();
            if (action === 'delete_user') new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
            isMenuOpen = false;
            customMenu.classList.remove('show');
        });

        document.addEventListener('click', e => {
            if (isMenuOpen && !customMenu.contains(e.target) && e.target !== menuBtn) {
                isMenuOpen = false;
                customMenu.classList.remove('show');
            }
            hideMessageContextMenu();
        });

        // === МОДАЛКИ ===
        document.getElementById('confirmClearBtn').addEventListener('click', () => {
            if (!activeUserId) return;
            fetchJson(routes.clearMessages, {
                user_id: activeUserId,
                chat_type: currentChatType
            }).then(data => {
                if (data.success) {
                    currentMessages = [];
                    lastMessageId = 0;
                    renderMessages();
                    loadUsers();
                    bootstrap.Modal.getInstance(document.getElementById('clearChatModal')).hide();
                }
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            if (!activeUserId) return;
            fetchJson(routes.deleteUser, {
                user_id: activeUserId
            }).then(data => {
                if (data.success) {
                    activeUserId = null;
                    currentMessages = [];
                    lastMessageId = 0;
                    msgsEl.innerHTML = '<div class="text-center text-muted p-4">Выберите чат</div>';
                    loadUsers();
                    bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
                }
            });
        });

        document.getElementById('confirmDeleteMessageBtn').addEventListener('click', () => {
            if (!currentMessageToDelete) return;
            fetchJson(routes.deleteMessage, {
                message_id: currentMessageToDelete.id
            }).then(data => {
                if (data.success) {
                    document.querySelector(`.msg-bubble[data-msgid="${currentMessageToDelete.id}"]`)
                        ?.closest('.msg-row')?.remove();
                    currentMessages = currentMessages.filter(m => m.id != currentMessageToDelete.id);
                    showNotification('Сообщение удалено');
                }
                bootstrap.Modal.getInstance(document.getElementById('deleteMessageModal')).hide();
            });
        });

        // === ПОИСК ===
        document.getElementById('searchIcon').addEventListener('click', () => {
            document.getElementById('searchReveal').classList.toggle('open');
        });
        document.getElementById('searchInput').addEventListener('input', e => loadUsers(e.target.value.trim()));

        // === НОВЫЙ ЧАТ ===
        document.getElementById('newChatBtn').addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('addChatModal')).show();
        });
        document.getElementById('addChatForm').addEventListener('submit', e => {
            e.preventDefault();
            const name = document.getElementById('newUserName').value.trim();
            const phone = document.getElementById('newUserPhone').value.trim();
            if (!name) return;
            fetchJson(routes.createUser, {
                name,
                phone
            }).then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('addChatModal')).hide();
                    document.getElementById('newUserName').value = '';
                    document.getElementById('newUserPhone').value = '+7';
                    loadUsers();
                    setTimeout(() => setActiveUser(data.user_id), 200);
                }
            });
        });

        // === МАСКА ТЕЛЕФОНА ===
        function initPhoneMask(el) {
            el.addEventListener('input', function() {
                let v = this.value.replace(/\D/g, '');
                if (v.startsWith('7') || v.startsWith('8')) v = '7' + v.substring(1);
                else if (v) v = '7' + v;
                let f = '+7';
                if (v.length > 1) f += ' (' + v.substring(1, 4);
                if (v.length >= 5) f += ') ' + v.substring(4, 7);
                if (v.length >= 8) f += '-' + v.substring(7, 9);
                if (v.length >= 10) f += '-' + v.substring(9, 11);
                this.value = f;
            });
        }
        initPhoneMask(document.getElementById('newUserPhone'));
        document.getElementById('newUserPhone').value = '+7';

        // === ИНИЦИАЛИЗАЦИЯ ===
        document.addEventListener('DOMContentLoaded', () => {
            loadUsers();
            setInterval(() => {
                loadUsers(document.getElementById('searchInput').value.trim());
                if (activeUserId) loadMessages(activeUserId, true);
            }, 3000);
        });
    </script>
@endpush
