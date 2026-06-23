<!-- footer start -->
<footer class="site-footer my-100 mb-100" aria-label="Footer">
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-left" role="contentinfo">
                <a href="{{ route('home') }}" class="brand-logo">
                    <img src="{{ asset('assets/images/logo/logo-2.svg') }}" alt="ФОРСАЖ">
                </a>
                <div class="brand-sub">Автосалон автомобилей</div>
            </div>
            <div class="footer-right" role="navigation" aria-label="Contacts">
                <div>
                    <p class="phone-large">
                        <a href="tel:+79874161010" style="text-decoration:none;">+7 (987) 416-10-10</a>
                    </p>
                </div>
                <div class="messengers" aria-label="Messengers">
                    <a href="https://wa.me/79600311715" title="WhatsApp">
                        <span>WhatsApp</span>
                        <svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="icon-adaptive mb-1">
                            <path
                                d="M3.42091 0.5V1.7334H11.5942L0.699219 12.6284L1.57082 13.5L12.4658 2.605V10.7783H13.6992V0.5H3.42091Z"
                                fill="#919191" />
                        </svg>
                    </a>
                    <a href="https://t.me/wicsay" title="Telegram">
                        <span>Telegram</span>
                        <svg viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="icon-adaptive mb-1">
                            <path
                                d="M3.42091 0.5V1.7334H11.5942L0.699219 12.6284L1.57082 13.5L12.4658 2.605V10.7783H13.6992V0.5H3.42091Z"
                                fill="#919191" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="lines"></div>
        <div class="footer-bottom mt-5">
            <div class="copy">Copyright © {{ date('Y') }} Мусина Ралина</div>
            <div class="support">
                <a href="{{ asset('assets/documents/politics.docx') }}" target="_blank" rel="noopener">Политика
                    конфиденциальности</a>
            </div>
        </div>
    </div>
</footer>

<button class="chat-button" aria-label="Чат" title="Открыть чат" id="chatBtn">
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <path
            d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z" />
    </svg>
    <span class="chat-badge" id="chatBadge" style="display:none">0</span>
</button>


<button class="to-top" aria-label="Наверх" title="Наверх" id="toTopBtn">
    <svg viewBox="0 0 24 24" aria-hidden="true">
        <path d="M7.41 15.41 12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
    </svg>
</button>

@include('partials.mini-chat')

<style>
    .chat-button {
        position: fixed;
        right: 25px;
        bottom: 105px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: 1px solid #4071cb3c;
        background: white;
        display: grid;
        place-items: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        cursor: pointer;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .chat-button:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }

    .chat-button svg {
        width: 25px;
        height: 25px;
        fill: #4071cb;
    }

    .chat-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #4071CB;
        color: white;
        font-size: 11px;
        font-weight: 700;
        min-width: 18px;
        height: 18px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px 5px 2px 5px;
        box-shadow: 0 2px 6px #4071cb5c;
        border: 2px solid white;
        animation: badgePop 0.3s ease;
    }

    @keyframes badgePop {
        0% { transform: scale(0); }
        80% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .mini-chat-name-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 2px;
    }

    .ai-badge {
        background: linear-gradient(135deg, #4071CB 0%, #5A8DE8 100%);
        color: white;
        font-size: 9px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 1px 3px rgba(64, 113, 203, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        line-height: 1;
    }

    .mini-chat-status-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: 2px;
    }

    .status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        position: relative;
        flex-shrink: 0;
        margin-bottom: 3px;
    }

    .status-dot.online {
        background-color: #34C759;
        box-shadow: 0 0 0 0 rgba(52, 199, 89, 0.7);
        animation: pulseOnline 2s infinite;
    }

    @keyframes pulseOnline {
        0% {
            box-shadow: 0 0 0 0 rgba(52, 199, 89, 0.7);
        }

        70% {
            box-shadow: 0 0 0 6px rgba(52, 199, 89, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(52, 199, 89, 0);
        }
    }

    .status-dot.offline {
        background-color: #FF9500;
        animation: none;
    }

    .status-dot.loading {
        background-color: #8E8E93;
        animation: blink 1.5s infinite;
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .status-text {
        font-size: 11px;
        font-weight: 400;
        transition: color 0.3s ease;
    }

    .status-text.online {
        color: #34C759;
    }

    .status-text.offline {
        color: #FF9500;
    }

    .status-text.loading {
        color: #8E8E93;
    }

    .mini-chat-info {
        display: flex;
        flex-direction: column;
    }

    .mini-chat-info .name {
        font-weight: 600;
        font-size: 14px;
        color: #1D1D1F;
        margin-top: 4px;
    }

    .to-top {
        position: fixed;
        right: 25px;
        bottom: 25px;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: 1px solid #4071cb3c;
        background: white;
        display: grid;
        place-items: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        cursor: pointer;
        z-index: 1000;
        transition: all 0.3s ease;
        opacity: 0;
        visibility: hidden;
    }

    .to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .to-top:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }

    .to-top svg {
        width: 25px;
        height: 25px;
        fill: #4071cb;
    }

    .mini-chat-modal {
        position: fixed;
        bottom: 170px;
        right: 25px;
        width: 380px;
        height: 580px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(64, 113, 203, 0.2);
        border: 1px solid rgba(64, 113, 203, 0.1);
        display: none;
        flex-direction: column;
        z-index: 1001;
        overflow: hidden;
        animation: chatSlideUp 0.3s ease;
    }

    @keyframes chatSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .mini-chat-modal.active {
        display: flex;
    }

    .mini-chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        background: white;
        border-bottom: 1px solid rgba(64, 113, 203, 0.1);
        position: relative;
        color: #1D1D1F;
        flex-shrink: 0;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(64, 113, 203, 0.1);
    }

    .mini-chat-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-avatar-mini img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .mini-chat-info .name {
        font-weight: 600;
        font-size: 14px;
        color: #1D1D1F;
    }

    .mini-chat-actions {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .mini-chat-menu,
    .mini-chat-close {
        background: rgba(64, 113, 203, 0.1);
        border: 1px solid rgba(64, 113, 203, 0.2);
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        color: #4071CB;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .mini-chat-menu:hover,
    .mini-chat-close:hover {
        background: rgba(64, 113, 203, 0.2);
        transform: scale(1.05);
    }

    .mini-chat-menu svg,
    .mini-chat-close svg {
        width: 16px;
        height: 16px;
    }

    .mini-chat-dropdown {
        position: absolute;
        top: 100%;
        right: 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(64, 113, 203, 0.15);
        border: 1px solid rgba(64, 113, 203, 0.1);
        padding: 4px;
        z-index: 1002;
        display: none;
        min-width: 160px;
        animation: dropdownFade 0.2s ease;
    }

    @keyframes dropdownFade {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mini-chat-dropdown.show {
        display: block;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 8px 12px;
        border: none;
        background: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        color: #1D1D1F;
        text-align: left;
    }

    .dropdown-item:hover {
        background: rgba(64, 113, 203, 0.1);
        color: #4071CB;
    }

    .dropdown-item svg {
        fill: #4071CB;
        opacity: 1;
        flex-shrink: 0;
        display: block;
        line-height: 1;
        transition: none !important;
        transform: translateZ(0);
        backface-visibility: hidden;
    }

    .mini-chat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        position: relative;
        background: white;
        overflow: hidden;
    }

    .registration-modal {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
        background: white;
        overflow-y: auto;
    }

    .registration-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .registration-header h2 {
        font-size: 20px;
        font-weight: 600;
        color: #4071CB;
        margin: 0 0 8px 0;
    }

    .registration-subtitle {
        font-size: 15px;
        color: #6B7280;
        margin: 0;
        line-height: 1.4;
    }

    .registration-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .form-section {
        margin-bottom: 10px;
    }

    .section-label {
        display: block;
        font-size: 15px;
        font-weight: 500;
        color: #4071CB;
        margin-bottom: 8px;
    }

    .required {
        color: #FF3B30;
    }

    .phone-input-container {
        display: flex;
        align-items: center;
        background: rgba(64, 113, 203, 0.05);
        border-radius: 10px;
        border: 1px solid rgba(64, 113, 203, 0.2);
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .phone-input-container:focus-within {
        border-color: #4071CB;
        box-shadow: 0 0 0 3px rgba(64, 113, 203, 0.1);
    }

    .phone-prefix {
        padding: 12px 0 12px 16px;
        font-size: 15px;
        color: #4071CB;
        font-weight: 500;
        background: transparent;
        border: none;
    }

    .phone-input {
        flex: 1;
        padding: 12px 16px;
        border: none;
        background: transparent;
        font-size: 15px;
        color: #1D1D1F;
        outline: none;
    }

    .phone-input::placeholder {
        color: #8E8E93;
    }

    .name-input {
        width: 100%;
        padding: 12px 16px;
        background: rgba(64, 113, 203, 0.05);
        border: 1px solid rgba(64, 113, 203, 0.2);
        border-radius: 10px;
        font-size: 15px;
        color: #1D1D1F;
        outline: none;
        transition: all 0.2s ease;
    }

    .name-input::placeholder {
        color: #8E8E93;
    }

    .name-input:focus {
        border-color: #4071CB;
        box-shadow: 0 0 0 3px rgba(64, 113, 203, 0.1);
    }

    .start-chat-btn {
        width: 100%;
        padding: 14px 16px;
        background: linear-gradient(135deg, #4071CB 0%, #5A8DE8 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 8px;
        box-shadow: 0 4px 12px rgba(64, 113, 203, 0.3);
    }

    .start-chat-btn:hover {
        background: linear-gradient(135deg, #3A68C0 0%, #5285E0 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(64, 113, 203, 0.4);
    }

    .chat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .mini-chat-messages {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        background: linear-gradient(135deg, #F8FAFF 0%, #F0F5FF 100%);
        min-height: 0;
    }

    .mini-message {
        max-width: 85%;
        padding: 11px 15px;
        border-radius: 18px;
        font-size: 13.5px;
        line-height: 1.4;
        position: relative;
        animation: messageAppear 0.3s ease;
        word-wrap: break-word;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    @keyframes messageAppear {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mini-message.sent {
        align-self: flex-end;
        background: linear-gradient(135deg, #4071CB 0%, #5A8DE8 100%);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 2px 8px rgba(64, 113, 203, 0.3);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .mini-message.received {
        align-self: flex-start;
        background: white;
        color: #1D1D1F;
        border-bottom-left-radius: 6px;
        border: 1px solid rgba(64, 113, 203, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .mini-message-content {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
    }

    .mini-message-text {
        flex: 1;
        word-wrap: break-word;
        line-height: 1.4;
    }

    .mini-message-time {
        font-size: 8px;
        opacity: 0.7;
        flex-shrink: 0;
        white-space: nowrap;
        margin-left: 8px;
    }

    .message-copy-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        background: rgba(0,0,0,0.05);
        border: none;
        border-radius: 4px;
        padding: 4px;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    .mini-message.received:hover .message-copy-btn {
        opacity: 1;
    }

    .message-copy-btn:hover {
        background: rgba(64, 113, 203, 0.1);
    }

    .message-copy-btn svg {
        width: 15px;
        height: 15px;
        fill: #4071CB;
    }

    .mini-chat-typing {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 16px 12px;
        font-size: 12px;
        color: #8E8E93;
        flex-shrink: 0;
    }

    .typing-dots {
        display: flex;
        gap: 3px;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        background: #4071CB;
        border-radius: 50%;
        animation: typingBounce 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-dot:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes typingBounce {

        0%,
        80%,
        100% {
            transform: scale(0.8);
            opacity: 0.5;
        }

        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .mini-chat-input-container {
        padding: 12px 16px;
        background: white;
        border-top: 1px solid rgba(64, 113, 203, 0.1);
        flex-shrink: 0;
        position: relative;
    }

    .mini-quick-actions {
        display: flex;
        gap: 6px;
        margin-bottom: 12px;
        flex-wrap: wrap;
        padding-bottom: 12px;
        position: relative;
    }

    .mini-quick-actions::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(142, 142, 147, 0.12);
        border-radius: 1px;
    }

    .mini-chip {
        background: rgba(64, 113, 203, 0.1);
        border: 1px solid rgba(64, 113, 203, 0.2);
        color: #4071CB;
        padding: 6px 10px;
        border-radius: 16px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .mini-chip:hover {
        background: rgba(64, 113, 203, 0.2);
        transform: translateY(-1px);
    }

    .mini-chat-input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
    }

    .mini-chat-input {
        flex: 1;
        border: 1px solid rgba(64, 113, 203, 0.2);
        border-radius: 20px;
        padding: 10px 16px;
        font-size: 14px;
        outline: none;
        transition: all 0.2s ease;
        background: rgba(64, 113, 203, 0.05);
    }

    .mini-chat-input:focus {
        border-color: #4071CB;
        background: white;
        box-shadow: 0 0 0 3px rgba(64, 113, 203, 0.1);
    }

    .mini-chat-send {
        background: linear-gradient(135deg, #4071CB 0%, #5A8DE8 100%);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: white;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(64, 113, 203, 0.3);
    }

    .mini-chat-send:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(64, 113, 203, 0.4);
    }

    .mini-chat-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .complaint-modal-inner {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: white;
        display: none;
        flex-direction: column;
        z-index: 1003;
        animation: modalFadeScale 0.3s ease;
    }

    @keyframes modalFadeScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .complaint-modal-inner.active {
        display: flex;
    }

    .complaint-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        background: white;
        border-bottom: 1px solid rgba(64, 113, 203, 0.1);
        color: #1D1D1F;
        flex-shrink: 0;
    }

    .complaint-modal-header h3 {
        margin: 0;
        color: #1D1D1F;
        font-size: 17px;
        font-weight: 600;
    }

    .complaint-modal-close {
        background: rgba(64, 113, 203, 0.1);
        border: 1px solid rgba(64, 113, 203, 0.2);
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
        color: #4071CB;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .complaint-modal-close:hover {
        background: rgba(64, 113, 203, 0.2);
    }

    .complaint-modal-close svg {
        fill: #4071CB;
    }

    .complaint-modal-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #4071CB;
        font-size: 14px;
    }

    .form-select,
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid rgba(64, 113, 203, 0.2);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: rgba(64, 113, 203, 0.05);
        font-family: inherit;
    }

    .form-select:focus,
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #4071CB;
        background: white;
        box-shadow: 0 0 0 3px rgba(64, 113, 203, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .complaint-modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid rgba(64, 113, 203, 0.1);
        flex-shrink: 0;
    }

    .btn-primary,
    .btn-secondary {
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 80px;
    }

    .btn-secondary {
        background: rgba(64, 113, 203, 0.1);
        color: #4071CB;
        border: 1px solid rgba(64, 113, 203, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(64, 113, 203, 0.2);
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 20px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: rgba(64, 113, 203, 0.05);
        border-radius: 8px;
        border: 1px solid rgba(64, 113, 203, 0.1);
    }

    .contact-item svg {
        flex-shrink: 0;
        fill: #4071CB;
    }

    .contact-item span {
        font-size: 14px;
        color: #1D1D1F;
        line-height: 1.4;
    }

    .confirm-message {
        text-align: center;
        padding: 20px 0;
    }

    .confirm-p {
        font-size: calc(1.5 + .3vw) !important;
        font-weight: 600;
    }

    @media (max-width: 480px) {
        .mini-chat-modal {
            width: calc(100vw - 40px);
            height: 70vh;
            right: 20px;
            bottom: 80px;
        }

        .chat-button,
        .to-top {
            right: 20px;
            bottom: 20px;
        }

        .to-top {
            bottom: 80px;
        }

        .registration-modal {
            padding: 16px;
        }

        .complaint-modal-body {
            padding: 16px;
        }

        .complaint-modal-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }

        .mini-message {
            max-width: 90%;
        }
    }

    .mini-chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .mini-chat-messages::-webkit-scrollbar-track {
        background: rgba(64, 113, 203, 0.05);
        border-radius: 3px;
    }

    .mini-chat-messages::-webkit-scrollbar-thumb {
        background: rgba(64, 113, 203, 0.3);
        border-radius: 3px;
    }

    .mini-chat-messages::-webkit-scrollbar-thumb:hover {
        background: rgba(64, 113, 203, 0.5);
    }

    .message-wrapper {
        position: relative;
    }

    .message-menu-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: none;
        border: none;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .message-wrapper:hover .message-menu-btn {
        opacity: 1;
    }

    .message-menu {
        position: absolute;
        top: 25px;
        right: 5px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 4px 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        min-width: 120px;
    }

    .message-menu button {
        width: 100%;
        padding: 8px 12px;
        background: none;
        border: none;
        text-align: left;
        cursor: pointer;
    }

    .message-menu button:hover {
        background: #f5f5f5;
    }

    /* ===== Когда чат открыт ===== */
    body.chat-open .chat-button,
    body.chat-open .to-top {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: scale(0.8);
    }

    body.chat-open .mini-chat-modal {
        width: 480px;
        height: 750px;
        bottom: 25px;
        right: 25px;
    }

    /* Увеличенный текст в чате */
    body.chat-open .mini-message {
        font-size: 15px;
        padding: 13px 18px;
        max-width: 88%;
    }

    body.chat-open .mini-message-time {
        font-size: 10px;
    }

    body.chat-open .mini-chat-input {
        font-size: 15px;
        padding: 12px 18px;
    }

    body.chat-open .mini-chip {
        font-size: 13px;
        padding: 8px 12px;
    }

    body.chat-open .mini-chat-header {
        padding: 18px;
    }

    body.chat-open .admin-avatar-mini img {
        width: 44px;
        height: 44px;
    }

    body.chat-open .mini-chat-info .name {
        font-size: 15px;
    }

    .chat-button,
    .to-top,
    .mini-chat-modal,
    .mini-message,
    .mini-chat-input,
    .mini-chip {
        transition: all 0.3s ease;
    }

    @media (max-width: 480px) {
        body.chat-open .mini-chat-modal {
            width: calc(100vw - 30px);
            height: 85vh;
            right: 15px;
            bottom: 15px;
            border-radius: 20px;
        }
    }

    body.chat-open .mini-chat-send {
        width: 44px;
        height: 44px;
        border-radius: 17px;
    }

    body.chat-open .mini-chat-send svg {
        width: 20px;
        height: 20px;
    }

    body.chat-open .complaint-modal-inner {
        font-size: 15px;
    }

    body.chat-open .complaint-modal-header {
        padding: 20px;
    }

    body.chat-open .complaint-modal-header h3 {
        font-size: 19px;
    }

    body.chat-open .complaint-modal-body {
        padding: 24px;
    }

    body.chat-open .form-group {
        margin-bottom: 20px;
    }

    body.chat-open .form-group label {
        font-size: 15px;
    }

    body.chat-open .form-select,
    body.chat-open .form-input,
    body.chat-open .form-textarea {
        font-size: 15px;
        padding: 12px 14px;
    }

    body.chat-open .complaint-modal-actions {
        margin-top: 24px;
        padding-top: 20px;
    }

    body.chat-open .btn-primary,
    body.chat-open .btn-secondary {
        font-size: 15px;
        padding: 12px 20px;
    }

    body.chat-open .contact-info {
        gap: 18px;
    }

    .mini-message.received.message-new {
    box-shadow: 0 2px 12px rgba(64, 113, 203, 0.15);
}

    body.chat-open .contact-item {
        padding: 14px;
    }

    body.chat-open .contact-item span {
        font-size: 15px;
    }

    body.chat-open .confirm-message {
        padding: 24px 0;
    }

    body.chat-open .confirm-p {
        font-size: 17px !important;
    }

    body.chat-open .registration-header h2 {
        font-size: 22px;
    }

    body.chat-open .registration-subtitle {
        font-size: 16px;
    }

    body.chat-open .section-label {
        font-size: 16px;
    }

    body.chat-open .phone-prefix,
    body.chat-open .phone-input,
    body.chat-open .name-input {
        font-size: 16px;
    }

    body.chat-open .start-chat-btn {
        font-size: 18px;
    }
</style>

@push('scripts')
    <script>
        (function() {
            'use strict';
            const btn = document.getElementById('toTopBtn');
            if (!btn) return;

            function toggle() {
                const scrolled = window.pageYOffset || document.documentElement.scrollTop;
                btn.classList.toggle('show', scrolled > 300);
            }
            toggle();
            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        toggle();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, {
                passive: true
            });
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        })();
    </script>

   
<script>
class MiniChat {
    constructor() {
        this.isOpen = false;
        this.isRegistered = false;
        this.isGuest = true;
        this.userPhone = '';
        this.userName = '';
        this.userId = 'guest_' + Math.random().toString(36).substr(2, 9);
        this.lastMessageId = 0;
        this.messages = [];
        this.aiResponseLock = false;
        this.isSending = false;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        this.unreadCount = 0;
        this.draftKey = 'mini_chat_draft_' + (this.csrfToken?.slice(-8) || 'guest');
        this.readKey = 'mini_chat_read_' + this.userId;
        
        this.setStatus = this.setStatus.bind(this);
        this.updateStatus = this.updateStatus.bind(this);
        this.init = this.init.bind(this);
        this.checkRegistration = this.checkRegistration.bind(this);
        this.toggle = this.toggle.bind(this);
        this.open = this.open.bind(this);
        this.close = this.close.bind(this);
        this.sendMessage = this.sendMessage.bind(this);
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }

    init() {
        const chatBtn = document.getElementById('chatBtn');
        const chatModal = document.getElementById('miniChatModal');
        if (!chatBtn || !chatModal) {
            console.error('Chat elements not found');
            return;
        }

        this.setStatus('loading', 'Загрузка...');
        this.bindEvents();
        this.updateStatus();
        this.startStatusUpdates();
        this.checkRegistration();
    }

    setStatus(type, text) {
        const dot = document.getElementById('statusDot');
        const txt = document.getElementById('statusText');
        if (dot) dot.className = 'status-dot ' + type;
        if (txt) {
            txt.className = 'status-text ' + type;
            txt.textContent = text;
        }
    }

    updateStatus() {
        const h = new Date().getHours();
        const work = h >= 9 && h < 18;
        this.setStatus(
            work ? 'online' : 'offline',
            work ? 'Онлайн • до 18:00' : 'Не в сети • с 9:00'
        );
    }

    startStatusUpdates() {
        setInterval(() => this.updateStatus(), 60000);
    }

    async checkRegistration() {
        try {
            const res = await fetch("{{ route('chat.check') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin'
            });
            const data = await res.json();
            
            this.isGuest = data.is_guest ?? true;
            this.isRegistered = data.is_logged_in ?? false;
            this.userPhone = data.phone || '';
            this.userName = data.name || '';
            this.userId = data.user_id || this.userId;
            this.readKey = 'mini_chat_read_' + this.userId;
            
            this.showChatView();
            this.loadMessages();
        } catch (e) {
            console.error('Check registration error:', e);
            this.isGuest = true;
            this.isRegistered = false;
            this.showChatView();
            this.loadMessages();
        }
    }

    showChatView() {
        this.hideAllModals();
        const reg = document.getElementById('registrationModal');
        const chat = document.getElementById('chatContent');
        
        if (reg) reg.style.display = 'none';
        if (chat) chat.style.display = 'flex';
        
        this.loadMessages();
    }

    hideAllModals() {
        ['registrationModal', 'confirmClearModal', 'complaintModal', 'contactSalonModal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (id === 'registrationModal') el.style.display = 'none';
                else el.classList.remove('active');
            }
        });
    }

    bindEvents() {
        const btn = document.getElementById('chatBtn');
        const modal = document.getElementById('miniChatModal');
        
        if (btn) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggle();
            });
        }
        
        if (modal) {
            document.addEventListener('click', (e) => {
                if (this.isOpen && !modal.contains(e.target) && !btn?.contains(e.target)) {
                    this.close();
                }
            });
        }

        const menu = document.getElementById('miniChatMenu');
        const drop = document.getElementById('miniChatDropdown');
        if (menu && drop) {
            menu.addEventListener('click', (e) => {
                e.stopPropagation();
                drop.classList.toggle('show');
            });
            document.addEventListener('click', () => drop.classList.remove('show'));
        }

        document.querySelectorAll('.dropdown-item').forEach(i => {
            i.addEventListener('click', (e) => {
                this.handleMenuAction(e.currentTarget.dataset.action);
                drop?.classList.remove('show');
            });
        });

        const send = document.getElementById('miniChatSend');
        const inp = document.getElementById('miniChatInput');
        
        if (send) send.addEventListener('click', () => this.sendMessage());
        if (inp) {
            inp.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }

        document.querySelectorAll('.mini-chip').forEach(c => {
            c.addEventListener('click', async (e) => {
                const a = e.currentTarget.dataset.action;
                if (a === 'callback' && this.userPhone) {
                    const i = document.getElementById('miniChatInput');
                    if (i) {
                        i.value = `Пожалуйста, перезвоните мне на номер ${this.formatPhoneForDisplay(this.userPhone)}`;
                        i.focus();
                    }
                } else if (['callback', 'price'].includes(a)) {
                    await this.sendTemplateMessage(a);
                }
            });
        });

        this.bindModalEvents();
    }

    bindModalEvents() {
        ['confirmClearClose', 'confirmClearCancel', 'complaintModalClose', 'complaintCancel',
            'contactSalonClose', 'contactSalonBack'
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('click', () => this.showChatView());
        });
        
        const ok = document.getElementById('confirmClearOk');
        if (ok) ok.addEventListener('click', () => this.clearChatConfirmed());
        
        const cf = document.getElementById('complaintForm');
        if (cf) cf.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitComplaint();
        });
    }

    async loadMessages() {
        try {
            const res = await fetch("{{ route('chat.messages') }}?last_id=" + this.lastMessageId, {
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin'
            });
            const data = await res.json();
            
            if (data.success && data.messages?.length > 0) {
                const container = document.getElementById('miniChatMessages');
                if (container && this.lastMessageId === 0) {
                    container.innerHTML = '';
                    this.messages = [];
                }
                
                const savedReadId = parseInt(localStorage.getItem(this.readKey) || '0');
                
                data.messages.forEach(msg => {
                    if (!this.messages.find(m => m.id === msg.id)) {
                        if (!msg.is_read && msg.dir === 'received' && msg.id <= savedReadId) {
                            msg.is_read = true;
                        }
                        
                        this.messages.push(msg);
                        this.addMessageToDOM(msg);
                        if (msg.id > this.lastMessageId) {
                            this.lastMessageId = msg.id;
                            if (!this.isOpen && msg.dir === 'received' && !msg.is_read) {
                                this.unreadCount++;
                                this.updateBadge();
                                this.playNotificationSound();
                            }
                        }
                    }
                });
                this.scrollToBottom();
            }
        } catch (e) {
            console.error('Load messages error:', e);
        }
    }

    async markAsRead() {
        document.querySelectorAll('.mini-message.received.message-new').forEach(el => {
            el.classList.remove('message-new');
        });
        
        const lastReceived = [...this.messages].reverse().find(m => m.dir === 'received');
        if (lastReceived) {
            localStorage.setItem(this.readKey, lastReceived.id);
        }
        
        try {
            await fetch("{{ route('chat.read') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin'
            });
        } catch (e) {
            console.log('Mark read error:', e);
        }
    }

    async sendTemplateMessage(a) {
        if (this.isGuest) {
            this.showAuthRequiredMessage();
            return;
        }

        if (!this.isRegistered || this.isSending) return;

        if (a === 'price') {
            this.showTypingIndicator();
            try {
                const r = await fetch("{{ route('chat.price') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    },
                    credentials: 'same-origin'
                });
                const d = await r.json();
                this.hideTypingIndicator();

                if (d.success) {
                    const m = {
                        id: d.message_id,
                        text: d.text,
                        dir: 'received',
                        time: this.getCurrentTime(),
                        format: d.format,
                        html: d.html,
                        is_read: false
                    };
                    this.addMessageToDOM(m);
                    this.messages.push(m);
                    this.scrollToBottom();
                } else {
                    this.showNotification(d.error || 'Ошибка сервера', 'error');
                }
            } catch (e) {
                this.hideTypingIndicator();
                console.error('Price fetch error:', e);
                this.showNotification('Ошибка загрузки прайса', 'error');
            }
            return;
        }

        if (a === 'callback') {
            await this.sendMessageDirectly('Пожалуйста, перезвоните мне');
            await this.sendAdminResponse('Спасибо! Мы перезвоним вам в ближайшее время.');
        }
    }

    addMessageToDOM(m) {
        const c = document.getElementById('miniChatMessages');
        if (!c || c.querySelector(`[data-id="${m.id}"]`)) return;

        const el = document.createElement('div');
        el.className = `mini-message ${m.dir === 'sent' ? 'sent' : 'received'}`;
        if (!m.is_read && m.dir === 'received') {
            el.classList.add('message-new');
        }
        el.dataset.id = m.id;
        if (m.isTemp) el.dataset.temp = 'true';

        if (m.format === 'price_card' && m.html) {
            el.innerHTML = `
                <div class="mini-message-text" style="width:100%">
                    <div style="font-weight:500;margin-bottom:6px;">${this.escapeHtml(m.text)}</div>
                    ${m.html}
                </div>
                <div class="mini-message-time">${m.time}</div>
            `;
        } else if (m.format === 'html') {
            el.innerHTML = `
                <div class="mini-message-content">
                    <div class="mini-message-text">${m.text}</div>
                    <div class="mini-message-time">${m.time}</div>
                </div>
            `;
        } else {
            const hasHtml = /<[a-z][\s\S]*>/i.test(m.text);
            el.innerHTML = `
                <div class="mini-message-content">
                    <div class="mini-message-text">${hasHtml ? m.text : this.escapeHtml(m.text)}</div>
                    <div class="mini-message-time">${m.time}</div>
                </div>
            `;
        }

        if (m.dir === 'received') {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'message-copy-btn';
            copyBtn.title = 'Копировать';
            copyBtn.innerHTML = `<svg width="25" height="25" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M16 12.9V17.1C16 20.6 14.6 22 11.1 22H6.9C3.4 22 2 20.6 2 17.1V12.9C2 9.4 3.4 8 6.9 8H11.1C14.6 8 16 9.4 16 12.9Z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M22 6.9V11.1C22 14.6 20.6 16 17.1 16H16V12.9C16 9.4 14.6 8 11.1 8H8V6.9C8 3.4 9.4 2 12.9 2H17.1C20.6 2 22 3.4 22 6.9Z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`;
            copyBtn.onclick = (e) => {
                e.stopPropagation();
                const textToCopy = m.format === 'price_card' ? m.text : (m.text.replace(/<[^>]*>/g, ''));
                navigator.clipboard.writeText(textToCopy).then(() => {
                    this.showNotification('Скопировано', 'success');
                });
            };
            el.appendChild(copyBtn);
        }

        c.appendChild(el);
    }

    async sendMessage() {
        if (this.isGuest) {
            this.showAuthRequiredMessage();
            return;
        }

        if (!this.isRegistered || this.isSending) return;

        const inp = document.getElementById('miniChatInput');
        const text = inp?.value.trim();
        if (!text) return;

        const now = Date.now();
        if (now - (window.lastUserMsgTime || 0) < 1000) return;
        window.lastUserMsgTime = now;

        this.isSending = true;
        const btn = document.getElementById('miniChatSend');
        if (btn) btn.disabled = true;

        try {
            const tid = 'temp_' + Date.now();
            this.addMessageToDOM({
                id: tid,
                text,
                dir: 'sent',
                time: this.getCurrentTime(),
                is_read: true,
                isTemp: true
            });
            if (inp) inp.value = '';
            this.scrollToBottom();

            this.showTypingIndicator();

            const r = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message_text: text })
            });
            const d = await r.json();

            if (d.success) {
                document.querySelector(`[data-id="${tid}"]`)?.remove();
                const m = {
                    id: d.message_id,
                    text,
                    dir: 'sent',
                    time: this.getCurrentTime(),
                    is_read: true
                };
                this.addMessageToDOM(m);
                this.messages.push(m);
                if (m.id > this.lastMessageId) this.lastMessageId = m.id;

                await this.getSmartResponse(text);
            } else {
                document.querySelector(`[data-id="${tid}"]`)?.remove();
                throw new Error(d.error || 'Ошибка отправки');
            }
        } catch (e) {
            this.hideTypingIndicator();
            this.showNotification(e.message || 'Ошибка сети', 'error');
        } finally {
            this.isSending = false;
            if (btn) btn.disabled = false;
        }
    }

    showAuthRequiredMessage() {
        const m = {
            id: 'auth_' + Date.now(),
            text: 'Для отправки сообщений и сохранения истории диалога, пожалуйста, авторизуйтесь. <a href="{{ route('login') }}">Войти</a> или <a href="{{ route('register') }}">Зарегистрироваться</a>',
            dir: 'received',
            time: this.getCurrentTime(),
            format: 'html',
            is_read: false
        };
        this.addMessageToDOM(m);
        this.messages.push(m);
        this.scrollToBottom();
    }

    async getSmartResponse(txt) {
        if (this.aiResponseLock) return;
        this.aiResponseLock = true;

        try {
            const r = await fetch("{{ route('chat.ai') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message_text: txt })
            });
            const d = await r.json();

            if (d.success && !d.is_duplicate) {
                const m = {
                    id: d.message_id || 'ai_' + Date.now(),
                    text: d.response,
                    dir: 'received',
                    time: this.getCurrentTime(),
                    is_read: false
                };
                this.addMessageToDOM(m);
                this.messages.push(m);
                this.scrollToBottom();

                if (!this.isOpen) {
                    this.unreadCount++;
                    this.updateBadge();
                    this.playNotificationSound();
                }
            }
        } catch (e) {
            console.error('AI error:', e);
            const m = {
                id: 'ai_err_' + Date.now(),
                text: 'Извините, я сейчас недоступен. Пожалуйста, позвоните нам: +7 (987) 416-10-10',
                dir: 'received',
                time: this.getCurrentTime(),
                is_read: false
            };
            this.addMessageToDOM(m);
            this.messages.push(m);
            this.scrollToBottom();

            if (!this.isOpen) {
                this.unreadCount++;
                this.updateBadge();
                this.playNotificationSound();
            }
        } finally {
            this.hideTypingIndicator();
            this.aiResponseLock = false;
        }
    }

    async sendMessageDirectly(txt) {
        if (this.isSending) return;
        this.isSending = true;
        try {
            const tid = 'temp_' + Date.now();
            this.addMessageToDOM({
                id: tid,
                text: txt,
                dir: 'sent',
                time: this.getCurrentTime(),
                is_read: true,
                isTemp: true
            });
            this.scrollToBottom();

            const r = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message_text: txt })
            });
            const d = await r.json();
            if (d.success) {
                document.querySelector(`[data-id="${tid}"]`)?.remove();
                const m = {
                    id: d.message_id,
                    text: txt,
                    dir: 'sent',
                    time: this.getCurrentTime(),
                    is_read: true
                };
                this.addMessageToDOM(m);
                this.messages.push(m);
                if (m.id > this.lastMessageId) this.lastMessageId = m.id;
            }
        } finally {
            this.isSending = false;
        }
    }

    async sendAdminResponse(txt) {
        if (this.aiResponseLock) return;
        this.aiResponseLock = true;
        this.showTypingIndicator();

        try {
            await new Promise(r => setTimeout(r, 800));

            const m = {
                id: 'admin_' + Date.now(),
                text: txt,
                dir: 'received',
                time: this.getCurrentTime(),
                is_read: false
            };
            this.addMessageToDOM(m);
            this.messages.push(m);
            this.scrollToBottom();

            if (!this.isOpen) {
                this.unreadCount++;
                this.updateBadge();
                this.playNotificationSound();
            }
        } finally {
            this.hideTypingIndicator();
            this.aiResponseLock = false;
        }
    }

    showTypingIndicator() {
        const el = document.getElementById('miniChatTyping');
        if (el) el.style.display = 'flex';
        this.scrollToBottom();
    }

    hideTypingIndicator() {
        const el = document.getElementById('miniChatTyping');
        if (el) el.style.display = 'none';
    }

    scrollToBottom() {
        setTimeout(() => {
            const c = document.getElementById('miniChatMessages');
            if (c) c.scrollTop = c.scrollHeight;
        }, 50);
    }

    getCurrentTime() {
        return new Date().toLocaleTimeString('ru-RU', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    escapeHtml(t) {
        const d = document.createElement('div');
        d.textContent = t;
        return d.innerHTML;
    }

    formatPhoneForDisplay(p) {
        const c = p.replace(/\D/g, '');
        if (c.length === 11 && c.startsWith('7')) {
            return `+7 (${c.substring(1,4)}) ${c.substring(4,7)}-${c.substring(7,9)}-${c.substring(9,11)}`;
        }
        return p;
    }

    showNotification(msg, type = 'info') {
        const colors = {
            success: { bg: '#e8f5e9', border: '#c8e6c9', text: '#2e7d32' },
            error:   { bg: '#ffebee', border: '#ffcdd2', text: '#c62828' },
            info:    { bg: '#e3f2fd', border: '#bbdefb', text: '#1565c0' },
            warning: { bg: '#fff8e1', border: '#ffecb3', text: '#f57f17' }
        };

        const c = colors[type] || colors.info;

        const n = document.createElement('div');
        n.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${c.bg};
            color: ${c.text};
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid ${c.border};
            z-index: 10000;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            animation: slideIn 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            max-width: 320px;
            line-height: 1.4;
        `;

        n.textContent = msg;

        document.body.appendChild(n);
        setTimeout(() => {
            n.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => n.remove(), 300);
        }, 3000);
    }

    updateBadge() {
        const badge = document.getElementById('chatBadge');
        if (badge) {
            if (this.unreadCount > 0) {
                badge.textContent = this.unreadCount > 9 ? '9+' : this.unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    playNotificationSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.15);
        } catch (e) {}
    }

    toggle() {
        this.isOpen ? this.close() : this.open();
    }

    open() {
        const m = document.getElementById('miniChatModal');
        if (!m) return;
        m.classList.add('active');
        document.body.classList.add('chat-open');
        this.isOpen = true;
        this.updateStatus();
        this.showChatView();
        
        this.unreadCount = 0;
        this.updateBadge();
        this.markAsRead();

        const draft = localStorage.getItem(this.draftKey);
        if (draft) {
            const i = document.getElementById('miniChatInput');
            if (i) {
                i.value = draft;
                i.focus();
            }
        } else {
            setTimeout(() => {
                const i = document.getElementById('miniChatInput');
                if (i) i.focus();
            }, 300);
        }
    }

    close() {
        const m = document.getElementById('miniChatModal');
        if (!m) return;
        m.classList.remove('active');
        document.body.classList.remove('chat-open');
        this.isOpen = false;
        document.getElementById('miniChatDropdown')?.classList.remove('show');

        const inp = document.getElementById('miniChatInput');
        if (inp && inp.value.trim()) {
            localStorage.setItem(this.draftKey, inp.value);
        } else {
            localStorage.removeItem(this.draftKey);
        }
    }

    handleMenuAction(a) {
        if (this.isGuest) {
            this.showAuthRequiredMessage();
            return;
        }
        
        if (!this.isRegistered) {
            this.showNotification('Сначала завершите регистрацию', 'error');
            return;
        }
        
        switch (a) {
            case 'clear-chat':
                this.showConfirmClearView();
                break;
            case 'contact-salon':
                this.showContactSalonView();
                break;
            case 'complaint':
                this.showComplaintView();
                break;
            case 'export-chat':
                this.exportChat();
                break;
        }
    }

    showConfirmClearView() {
        this.hideAllModals();
        document.getElementById('confirmClearModal')?.classList.add('active');
    }

    async clearChatConfirmed() {
        try {
            const r = await fetch("{{ route('chat.clear') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin'
            });
            const d = await r.json();
            if (d.success) {
                const c = document.getElementById('miniChatMessages');
                if (c) {
                    c.innerHTML = '';
                    this.messages = [];
                    this.lastMessageId = 0;
                }
                localStorage.removeItem(this.readKey);
                this.showChatView();
                this.showNotification('Чат очищен', 'success');
            }
        } catch (e) {
            this.showNotification('Ошибка сети', 'error');
        }
    }

    showComplaintView() {
        this.hideAllModals();
        const m = document.getElementById('complaintModal');
        if (m) {
            m.classList.add('active');
            document.getElementById('complaintForm')?.reset();
        }
    }

    async submitComplaint() {
        const t = document.getElementById('complaintType')?.value;
        const txt = document.getElementById('complaintText')?.value;

        if (!t || !txt) {
            this.showNotification('Заполните обязательные поля', 'error');
            return;
        }

        try {
            const r = await fetch("{{ route('chat.complaint') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                credentials: 'same-origin',
                body: JSON.stringify({ type: t, text: txt })
            });
            const d = await r.json();
            if (d.success) {
                this.showNotification('Жалоба отправлена', 'success');
                this.showChatView();
            } else {
                this.showNotification('Ошибка: ' + d.error, 'error');
            }
        } catch (e) {
            this.showNotification('Ошибка сети', 'error');
        }
    }

    showContactSalonView() {
        this.hideAllModals();
        document.getElementById('contactSalonModal')?.classList.add('active');
    }

    exportChat() {
        const txt = this.messages.map(m => `[${m.time}] ${m.dir === 'sent' ? 'Вы' : 'Смарти'}: ${m.text}`).join('\n');
        const blob = new Blob([txt], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `чат_смарти_${new Date().toISOString().split('T')[0]}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        this.showNotification('Чат экспортирован', 'success');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.miniChat = new MiniChat();
});
</script>
@endpush