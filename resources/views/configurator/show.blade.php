@extends('layouts.app')

@section('title', 'Конфигуратор - ' . ($selectedCar['name'] ?? 'Автомобиль'))

@push('styles')
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #f5f5f7 !important;
            color: #111;
            font-family: 'Ruberoid', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
@endpush

@section('content')
    <div class="app-area container-xxl">
        <div class="viewer">
            <div class="stage" id="stage" role="img" aria-label="Просмотр автомобиля">
                <canvas id="modelViewer"></canvas>

                <div id="photoViewerContainer">
                    <div class="photo-slide-wrapper" id="photoSlideWrapper">
                        <div class="photo-layer photo-layer-current" style="z-index:2;">
                            <img id="photoCurrent" src="" alt="Автомобиль" loading="eager">
                        </div>
                        <div class="photo-layer photo-layer-next" style="opacity:0; z-index:3;">
                            <img id="photoNext" src="" alt="Автомобиль" loading="eager">
                        </div>
                    </div>
                    <div class="photo-thumbnails-strip">
                        <div class="thumbnails-track" id="thumbnailsTrack"></div>
                    </div>
                </div>

                <div class="view-mode-toggle" id="viewModeToggle">
                    <button type="button" class="mode-btn active" data-mode="3d" id="mode3dBtn">3D</button>
                    <button type="button" class="mode-btn" data-mode="photo" id="modePhotoBtn">Фото</button>
                </div>

                <div class="viewer-controls" id="viewerControls">
                    <button class="viewer-btn" id="resetView" title="Сбросить вид">↺</button>
                    <button class="viewer-btn" id="zoomIn" title="Приблизить">+</button>
                    <button class="viewer-btn" id="zoomOut" title="Отдалить">−</button>
                    <button class="viewer-btn" id="autoRotate" title="Автоповорот">⟳</button>
                </div>

                <div class="loading-overlay" id="loadingOverlay">
                    <div class="spinner"></div>
                    <div id="loadingText">Загрузка 3D модели...</div>
                </div>
            </div>

            <div class="stats">
                <div class="stat">
                    <div id="hpValue">---</div><small>Мощность, л.с.</small>
                </div>
                <div class="stat">
                    <div id="accelValue">---</div><small>Разгон 0–100, с</small>
                </div>
                <div class="stat">
                    <div id="fuelValue">---</div><small>Расход, л/100 км</small>
                </div>
                <div class="stat">
                    <div id="co2Value">---</div><small>Вых. CO₂, г/км</small>
                </div>
            </div>
        </div>

        <aside class="sidebar" aria-label="Панель конфигурации">
            <div class="sidebar-scroll" id="sidebarScroll">
                <div class="steps-fixed" id="stepsFixed">
                    <div class="steps">
                        <div class="step-label" id="stepsLabel">01/07: Двигатель</div>
                        <div class="steps-dots" id="stepsDots"></div>
                    </div>
                </div>

                <div class="panel mt-3" id="enginePanel" data-section="engine">
                    <div class="panel-inner">
                        <div class="acc-header acc-header-ai" data-acc-toggle="engine">
                            <div>
                                <div class="acc-title">Двигатель</div>
                            </div>
                            <div class="pills" id="engineDesc">Дизель</div>
                        </div>
                        <div class="acc-body show" id="engineBody">
                            <div class="acc-sub" id="engineTitle">{{ $selectedCar['name'] ?? '' }}</div>
                            <button type="button" class="btn-outline-custom" id="showAllEngines">Посмотреть все
                                двигатели</button>
                            <div class="mt-3" id="engineExtra" style="display:none;"></div>
                        </div>
                    </div>
                </div>

                <div class="panel" id="variantPanel" data-section="variant">
                    <div class="panel-inner">
                        <div class="acc-header acc-header-ai" data-acc-toggle="variant">
                            <div>
                                <div class="acc-title">Вариант исполнения</div>
                                <div class="acc-sub" id="variantTitle">{{ $selectedCar['variant'] ?? 'Business' }}</div>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; margin-top:12px;">
                            <button type="button" class="btn-outline-custom" id="variantMore">Подробнее ›</button>
                        </div>
                        <div class="acc-body" id="variantBody">
                            <p class="acc-sub" id="variantDescription" style="margin:8px 0 0 0;">
                                {{ $selectedCar['description'] ?? 'Содержит: комфорт, пакет защиты, мультимедиа' }}</p>
                        </div>
                    </div>
                </div>

                <div class="panel" id="colorPanel" data-section="color">
                    <div class="panel-inner">
                        <div class="acc-header acc-header-ai" data-acc-toggle="color">
                            <div>
                                <div class="acc-title">Окраска кузова</div>
                                <div class="acc-sub">Выбор покрытия</div>
                            </div>
                            <div class="pills" id="colorName">—</div>
                        </div>
                        <div class="acc-body show" id="colorBody">
                            <div
                                style="font-weight:600; font-size:12px; color:#888; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">
                                Металлик</div>
                            <div class="swatches" id="swatchesMetal"></div>
                            <div
                                style="font-weight:600; font-size:12px; color:#888; text-transform:uppercase; letter-spacing:0.5px; margin:16px 0 8px;">
                                Неметаллик</div>
                            <div class="swatches" id="swatchesNonMetal"></div>
                            <div class="mt-3" style="font-size:12px; color:#888;">Активный цвет: <strong
                                    id="selColorLabel" style="color:#111;">—</strong></div>
                        </div>
                    </div>
                </div>

                <div class="panel" id="wheelPanel" data-section="wheel">
                    <div class="panel-inner">
                        <div class="acc-header acc-header-ai" data-acc-toggle="wheel">
                            <div>
                                <div class="acc-title">Колёса</div>
                                <div class="acc-sub">Литые диски и шины</div>
                            </div>
                            <div class="pills" id="wheelName">—</div>
                        </div>
                        <div class="acc-body show" id="wheelBody">
                            <div id="wheelOptions" class="wheel-options-compact"></div>
                            <div id="wheelDetailPanel" class="wheel-detail-panel" style="display:none;"></div>
                        </div>
                    </div>
                </div>

                <div class="panel" id="interiorPanel" data-section="interior">
                    <div class="panel-inner">
                        <div class="acc-header acc-header-ai" data-acc-toggle="interior">
                            <div>
                                <div class="acc-title">Просмотр салона</div>
                                <div class="acc-sub">Интерьер автомобиля</div>
                            </div>
                        </div>
                        <div class="acc-body show" id="interiorBody">
                            <div class="interior-types mb-3" id="interiorTypes">
                                <div class="type-switcher" id="typeSwitcher"></div>
                            </div>
                            <div id="interiorPriceTag" style="margin-bottom: 12px;"></div>
                            <div id="interiorThumbnails" class="interior-thumbnails"></div>
                        </div>
                    </div>
                </div>

                <div class="panel" id="specPanel" data-section="specs">
                    <div class="panel-inner">
                        <div class="acc-header acc-header-ai" data-acc-toggle="specs">
                            <div>
                                <div class="acc-title">Технические данные</div>
                                <div class="acc-sub">Подробные характеристики</div>
                            </div>
                        </div>
                        <div class="acc-body show" id="specsBody">
                            <ul id="specList" style="padding-left:18px; margin:8px 0 0 0; color:#666; font-size:13px;">
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="panel" id="summaryPanel" data-section="summary">
                    <div class="panel-inner">
                        <div style="margin-bottom:8px;">
                            <div
                                style="font-size:12px; color:#888; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">
                                Итоговая стоимость</div>
                            <div class="price" id="summaryPrice"
                                style="font-size:24px; font-weight:800; margin-top:4px;">
                                {{ number_format($selectedCar['basePrice'] ?? 2954000, 0, ',', ' ') }} ₽</div>
                            <div style="font-size:12px; color:#888; margin-top:4px;" id="summaryVariant">
                                {{ $selectedCar['variant'] ?? 'Business' }}</div>
                        </div>
                        <div style="margin-top:16px;">
                            <button type="button" class="btn-save" id="saveConfigBtn">Сохранить конфигурацию</button>
                        </div>
                        <div class="link-row">
                            <input id="shareLink" value="https://configure.bmw.ru/ru_RU/config/..." readonly />
                            <button type="button" class="icon-btn" id="copyBtn" title="Скопировать ссылку"
                                style="background:#111;color:#fff;border-radius:12px;padding:10px;border:none;min-width:44px;height:44px;display:flex;align-items:center;justify-content:center;">
                                <svg id="copyIcon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M16 12.9V17.1C16 20.6 14.6 22 11.1 22H6.9C3.4 22 2 20.6 2 17.1V12.9C2 9.4 3.4 8 6.9 8H11.1C14.6 8 16 9.4 16 12.9Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M22 6.9V11.1C22 14.6 20.6 16 17.1 16H16V12.9C16 9.4 14.6 8 11.1 8H8V6.9C8 3.4 9.4 2 12.9 2H17.1C20.6 2 22 3.4 22 6.9Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                <svg id="successIcon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    style="display:none;">
                                    <path
                                        d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.75 12L10.58 14.83L16.25 9.17004" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <div class="bottom-bar" id="bottomBar" role="region" aria-label="Итоговая панель">
        <div style="display:flex; gap:12px; align-items:center;">
            <div style="font-weight:700; font-size:16px;" id="bottomCarName">
                {{ $selectedCar['name'] ?? 'BMW X5 xDrive25d' }}</div>
            <div style="color:#888; font-size:13px;">•</div>
            <div style="color:#888; font-size:13px; font-weight:500;" id="bottomVariant">
                {{ $selectedCar['variant'] ?? 'Business' }}</div>
        </div>
        <div>
            <button type="button" class="btn-save" id="bottomQuestionBtn"
                style="padding:12px 24px; width:auto; border-radius:8px; font-size:13px;">ЗАДАТЬ ВОПРОС</button>
        </div>
    </div>

    {{-- AI Chat Dialog --}}
    <div class="ai-chat-dialog" id="aiChatDialog">
        <div class="chat-dialog-content">
            <div class="chat-dialog-header">
                <div style="flex: 1;">
                    <h5 style="font-size:15px; font-weight:700; margin:0; letter-spacing:-0.2px;">AI Assistant</h5>
                    <div style="font-size: 12px; color: #888; margin-top:2px;">Готов помочь с выбором</div>
                </div>
                <button type="button" class="chat-close-btn" id="closeChatDialog" aria-label="Закрыть">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="chat-dialog-body">
                <div class="ai-chat-messages" id="aiChatMessages">
                    <div class="message ai-message">
                        <div class="message-content">
                            <div class="message-text">
                                Привет! AI-помощник по конфигурации. Помощь с:
                                <div style="margin-top: 8px;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;"><span
                                            style="color: #0078D7;">•</span><span
                                            style="font-size: 12px;">Характеристиками двигателей</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;"><span
                                            style="color: #0078D7;">•</span><span style="font-size: 12px;">Комплектациями
                                            и опциями</span></div>
                                    <div style="display: flex; align-items: center; gap: 6px;"><span
                                            style="color: #0078D7;">•</span><span style="font-size: 12px;">Техническими
                                            деталями</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="typing-indicator" id="aiTypingIndicator">
                    <div class="typing-dots"><span></span><span></span><span></span></div>
                    <span style="font-size: 12px; color: #888;">Помощник печатает...</span>
                </div>
                <div class="chat-input-container">
                    <input type="text" id="aiChatInput" class="chat-input" placeholder="Написать вопрос..."
                        maxlength="500">
                    <button class="send-btn-conf" id="aiSendBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Config Modal --}}
    <div class="modal fade" id="saveConfigModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="padding: 32px;">
                <div class="text-center">
                    <div
                        style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: #f5f5f7;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z"
                                stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M17 21V13H7V21" stroke="#111" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 3V8H15" stroke="#111" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 style="font-weight: 700; font-size: 18px; color: #111; margin-bottom: 8px;">Сохранение конфигурации
                    </h5>
                    <p style="color: #888; font-size: 14px; margin-bottom: 24px;">Укажите название для конфигурации:</p>
                    <input type="text" id="configNameInput" class="form-control mb-4"
                        placeholder="Например: {{ $selectedCar['name'] ?? 'BMW X5' }}" maxlength="100"
                        style="padding: 14px 16px; border-radius: 14px; border: 1.5px solid #e5e5e5; font-size: 14px; background: #f8f9fa;">
                    <div class="d-flex gap-2" style="justify-content: center;">
                        <button type="button" id="cancelSaveConfig" class="btn"
                            style="flex: 1; padding: 12px; border-radius: 14px; font-weight: 600; color: #555; background: #f5f5f7; border: none;"
                            data-bs-dismiss="modal">Отмена</button>
                        <button type="button" id="confirmSaveConfig" class="btn"
                            style="flex: 1; padding: 12px; border-radius: 14px; font-weight: 600; color: white; background: #111; border: none;">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal fade" id="successSaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content" style="padding: 32px;">
                <div class="text-center">
                    <div
                        style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; background: #f0fdf4;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M22 11.08V12C21.9988 14.1564 21.3005 16.2547 20.0093 17.9818C18.7182 19.709 16.9033 20.9725 14.8354 21.5839C12.7674 22.1953 10.5573 22.1219 8.53447 21.3746C6.51168 20.6273 4.78465 19.2461 3.61096 17.4371C2.43727 15.628 1.87979 13.4881 2.02168 11.3363C2.16356 9.18455 2.99721 7.13631 4.39828 5.49706C5.79935 3.85781 7.69279 2.71537 9.79619 2.24013C11.8996 1.7649 14.1003 1.98232 16.07 2.85999"
                                stroke="#16a34a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22 4L12 14.01L9 11.01" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 style="font-weight: 700; font-size: 18px; color: #111; margin-bottom: 8px;">Конфигурация сохранена!
                    </h5>
                    <p style="color: #888; font-size: 14px; margin-bottom: 28px;">Конфигурация успешно сохранена в личном
                        кабинете.</p>
                    <div class="d-flex gap-2" style="justify-content: center;">
                        <button type="button" id="closeSuccessModal" class="btn"
                            style="flex: 1; padding: 12px; border-radius: 14px; font-weight: 600; color: #555; background: #f5f5f7; border: none;"
                            data-bs-dismiss="modal">ОК</button>
                        <button type="button" id="goToAccount" class="btn"
                            style="flex: 1; padding: 12px; border-radius: 14px; font-weight: 600; color: white; background: #111; border: none;">Личный
                            кабинет</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/RGBELoader.js"></script>
    <script>
        const CARS_JSON = @json($carsData);
        const CARS = (CARS_JSON && typeof CARS_JSON === 'object') ? CARS_JSON : {};

        let activeCarKey = '{{ $selectedCarKey ?? array_key_first($carsData) }}';
        if (!activeCarKey || !CARS[activeCarKey]) {
            activeCarKey = Object.keys(CARS)[0] || '';
        }
        let activeCar = CARS[activeCarKey];

        if (!activeCar) {
            console.error('Отсутствуют данные автомобиля. Проверить $carsData в контроллере.');
            activeCar = {
                id: 0,
                name: '',
                variant: '',
                description: '',
                basePrice: 0,
                colors: {},
                engines: [],
                wheels: [],
                interior: {},
                interior_prices: {},
                models: [],
                mainImage: ''
            };
        }

        let currentColorKey = Object.keys(activeCar.colors || {})[0] || '';
        let selectedEngine = (activeCar.engines && activeCar.engines[0]) ? activeCar.engines[0].id : '';
        let selectedWheel = (activeCar.wheels && activeCar.wheels[0]) ? activeCar.wheels[0].id : '';
        let modelViewer3D = null;
        let currentViewerMode = '3d';
        let activeSection = 'engine';
        let currentInteriorType = '';
        let currentInteriorPrice = 0;

        let colorFrames = {};
        let interiorFrames = {};
        let wheelFrames = {};
        let currentFrameIndex = 0;
        let currentPhotoContext = 'color';

        let thumbnailsClickHandler = null;

        const engineTitle = document.getElementById('engineTitle');
        const engineDesc = document.getElementById('engineDesc');
        const engineExtra = document.getElementById('engineExtra');
        const variantTitle = document.getElementById('variantTitle');
        const variantDescription = document.getElementById('variantDescription');
        const colorName = document.getElementById('colorName');
        const selColorLabel = document.getElementById('selColorLabel');
        const specList = document.getElementById('specList');
        const summaryPrice = document.getElementById('summaryPrice');
        const summaryVariant = document.getElementById('summaryVariant');
        const bottomCarName = document.getElementById('bottomCarName');
        const bottomVariant = document.getElementById('bottomVariant');
        const shareLink = document.getElementById('shareLink');
        const copyBtn = document.getElementById('copyBtn');
        const swatchesMetal = document.getElementById('swatchesMetal');
        const swatchesNonMetal = document.getElementById('swatchesNonMetal');
        const showAllEnginesBtn = document.getElementById('showAllEngines');
        const stepsLabel = document.getElementById('stepsLabel');
        const stepsDots = document.getElementById('stepsDots');
        const sidebarScroll = document.getElementById('sidebarScroll');
        const stepsFixed = document.getElementById('stepsFixed');

        function formatPrice(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
        }

        function preloadImage(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(url);
                img.onerror = () => reject(url);
                img.src = url;
            });
        }

        class ModelViewer3D {
            constructor() {
                this.scene = null;
                this.camera = null;
                this.renderer = null;
                this.controls = null;
                this.model = null;
                this.autoRotate = false;
                this.envMap = null;
                this.init();
            }
            async init() {
                if (!this.setupScene()) return;
                this.setupEventListeners();
                await this.loadHDRIEnvironment();
                this.animate();
            }
            async loadHDRIEnvironment() {
                try {
                    const c = document.createElement('canvas');
                    c.width = 512;
                    c.height = 512;
                    const x = c.getContext('2d');
                    const g = x.createRadialGradient(256, 256, 0, 256, 256, 300);
                   g.addColorStop(0, '#bbb');
g.addColorStop(0.7, '#999');
g.addColorStop(1, '#777');
                    g.addColorStop(1, '#444');
                    x.fillStyle = g;
                    x.fillRect(0, 0, 512, 512);
                    const t = new THREE.CanvasTexture(c);
                    t.mapping = THREE.EquirectangularReflectionMapping;
                    this.envMap = t;
                    this.scene.environment = this.envMap;
                    this.scene.background = new THREE.Color(0xf8f9fa);
                } catch (e) {
                    console.warn('Ошибка загрузки окружения', e);
                }
            }
            setupScene() {
                const canvas = document.getElementById('modelViewer');
                if (!canvas) return false;
                try {
                    this.scene = new THREE.Scene();
                    this.scene.background = new THREE.Color(0xffffff);
                    const w = canvas.clientWidth || canvas.parentElement.clientWidth || 800;
                    const h = canvas.clientHeight || canvas.parentElement.clientHeight || 600;
                    this.camera = new THREE.PerspectiveCamera(45, w / h, 0.1, 1000);
                    this.camera.position.set(6, 3, 6);
                    this.renderer = new THREE.WebGLRenderer({
                        canvas,
                        antialias: true,
                        alpha: true,
                        powerPreference: "high-performance"
                    });
                    this.renderer.setSize(w, h);
                    this.renderer.shadowMap.enabled = true;
                    this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
                    this.renderer.outputEncoding = THREE.sRGBEncoding;
                    this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
                    this.renderer.toneMappingExposure = 1.0;
                    this.renderer.physicallyCorrectLights = true;
                    this.scene.add(new THREE.AmbientLight(0xffffff, 0.6));
                    const ml = new THREE.DirectionalLight(0xffffff, 1.2);
                    ml.position.set(10, 15, 8);
                    ml.castShadow = true;
                    ml.shadow.mapSize.width = 2048;
                    ml.shadow.mapSize.height = 2048;
                    this.scene.add(ml);
                    this.scene.add(new THREE.DirectionalLight(0xffffff, 0.4).position.set(-8, 5, 5));
                    this.scene.add(new THREE.DirectionalLight(0xffffff, 0.3).position.set(0, 8, -12));
                    const ground = new THREE.Mesh(new THREE.PlaneGeometry(30, 30), new THREE.MeshBasicMaterial({
                        color: 0xf8f9fa,
                        toneMapped: false
                    }));
                    ground.rotation.x = -Math.PI / 2;
                    this.scene.add(ground);
                    const grid = new THREE.GridHelper(25, 25, 0x4071CB, 0x4071CB);
                    grid.position.y = 0.01;
                    grid.material.opacity = 0.2;
                    grid.material.transparent = true;
                    this.scene.add(grid);
                    this.controls = new THREE.OrbitControls(this.camera, this.renderer.domElement);
                    this.controls.enableDamping = true;
                    this.controls.dampingFactor = 0.05;
                    this.controls.minDistance = 3;
                    this.controls.maxDistance = 25;
                    this.controls.maxPolarAngle = Math.PI / 2;
                    this.controls.target.set(0, 1, 0);
                    return true;
                } catch (e) {
                    console.error(e);
                    return false;
                }
            }
            async loadModel(modelPath) {
                if (!this.scene) return;
                showLoading('Загрузка 3D модели...');
                try {
                    if (this.model) {
                        this.scene.remove(this.model);
                        this.model.traverse(c => {
                            if (c.isMesh) {
                                c.geometry?.dispose();
                                if (c.material) Array.isArray(c.material) ? c.material.forEach(m => m
                                .dispose()) : c.material.dispose();
                            }
                        });
                    }
                    const loader = new THREE.GLTFLoader();
                    await new Promise((res, rej) => {
                        loader.load(modelPath, (g) => {
                                this.processLoadedModel(g);
                                res(g);
                            },
                            (p) => {
                                if (p.lengthComputable) {
                                    const loadingText = document.getElementById('loadingText');
                                    if (loadingText) loadingText.textContent =
                                        `Загрузка... ${(p.loaded / p.total * 100).toFixed(0)}%`;
                                }
                            }, (e) => rej(e));
                    });
                } catch (e) {
                    console.error(e);
                    hideLoading();
                    this.showError('Ошибка загрузки 3D модели');
                    this.showTestModel();
                }
            }
            processLoadedModel(gltf) {
                if (!gltf || !gltf.scene) throw new Error('Invalid model');
                this.model = gltf.scene;
                const box = new THREE.Box3().setFromObject(this.model);
                const center = box.getCenter(new THREE.Vector3());
                const size = box.getSize(new THREE.Vector3());
                this.model.position.set(-center.x, -center.y + size.y / 2, -center.z);
                const maxDim = Math.max(size.x, size.y, size.z);
                const scale = maxDim > 0 ? 6.2 / maxDim : 1;
                this.model.scale.setScalar(scale);
                this.scene.add(this.model);
                this.resetCamera();
                hideLoading();
                if (currentColorKey && activeCar.colors && activeCar.colors[currentColorKey] && activeCar.colors[
                        currentColorKey].hex) {
                    const colorData = activeCar.colors[currentColorKey];
                    this.changeColor(colorData.hex, colorData.group === 'nonmetal' ? 'matte' : 'gloss');
                }
            }
            resetCamera() {
                this.camera.position.set(6, 3, 6);
                this.controls.target.set(0, 1, 0);
                this.controls.update();
            }
            changeColor(hex, type) {
                if (!this.model || !hex) return;
                const color = new THREE.Color(hex);
                const params = this.getMaterialParams(type);
                this.model.traverse(child => {
                    if (!child.isMesh || !child.material) return;
                    const mats = Array.isArray(child.material) ? child.material : [child.material];
                    mats.forEach((mat, i) => {
                        if (this.isBodyMaterial(mat, child)) {
                            const nm = new THREE.MeshPhysicalMaterial({
                                color,
                                ...params,
                                envMap: this.envMap,
                                flatShading: false,
                                vertexColors: false
                            });
                            if (Array.isArray(child.material)) child.material[i] = nm;
                            else child.material = nm;
                        }
                    });
                });
                this.renderer.render(this.scene, this.camera);
            }
            isBodyMaterial(mat, mesh) {
                const mn = (mat.name || '').toLowerCase();
                const meshn = (mesh.name || '').toLowerCase();
                const keys = ['body', 'paint', 'car_paint', 'exterior_paint', 'main_color', 'basecolor', 'carpaint'];
                if (!keys.some(k => mn.includes(k) || meshn.includes(k))) return false;
                if (mat.transparent === true) return false;
                return true;
            }
            getMaterialParams(type) {
                const p = {
                    gloss: {
                        metalness: 0,
                        roughness: 0.08,
                        envMapIntensity: 0.5,
                        clearcoat: 1,
                        clearcoatRoughness: 0.05
                    },
                    metal: {
                        metalness: 0.8,
                        roughness: 0.15,
                        envMapIntensity: 1,
                        clearcoat: 1,
                        clearcoatRoughness: 0.08
                    },
                    matte: {
                        metalness: 0,
                        roughness: 0.95,
                        envMapIntensity: 0.02,
                        clearcoat: 0,
                        clearcoatRoughness: 1
                    },
                    satin: {
                        metalness: 0,
                        roughness: 0.6,
                        envMapIntensity: 0.2,
                        clearcoat: 0.3,
                        clearcoatRoughness: 0.4
                    }
                };
                return p[type] || p.gloss;
            }
            showTestModel() {
                if (!this.scene) return;
                const b = new THREE.Mesh(new THREE.BoxGeometry(3, 1.2, 1.8), new THREE.MeshPhysicalMaterial({
                    color: 0x4071CB,
                    metalness: 0,
                    roughness: 0.1,
                    clearcoat: 1,
                    clearcoatRoughness: 0.05,
                    envMap: this.envMap,
                    envMapIntensity: 0.8
                }));
                b.position.y = 0.6;
                this.model = new THREE.Group();
                this.model.add(b);
                this.scene.add(this.model);
                this.resetCamera();
            }
            showError(msg) {
                const el = document.getElementById('loadingText');
                if (el) {
                    el.textContent = msg;
                    el.style.color = '#dc3545';
                }
            }
            setupEventListeners() {
                document.getElementById('resetView')?.addEventListener('click', () => this.resetCamera());
                document.getElementById('zoomIn')?.addEventListener('click', () => {
                    this.camera.fov = Math.max(15, this.camera.fov - 5);
                    this.camera.updateProjectionMatrix();
                });
                document.getElementById('zoomOut')?.addEventListener('click', () => {
                    this.camera.fov = Math.min(75, this.camera.fov + 5);
                    this.camera.updateProjectionMatrix();
                });
                document.getElementById('autoRotate')?.addEventListener('click', (e) => {
                    this.autoRotate = !this.autoRotate;
                    this.controls.autoRotate = this.autoRotate;
                    this.controls.autoRotateSpeed = 0.8;
                    e.target.style.background = this.autoRotate ? '#111' : '';
                    e.target.style.color = this.autoRotate ? 'white' : '';
                });
                window.addEventListener('resize', () => this.handleResize());
            }
            handleResize() {
                const canvas = document.getElementById('modelViewer');
                if (!canvas || !this.camera || !this.renderer) return;
                const w = canvas.clientWidth || canvas.parentElement?.clientWidth || 1;
                const h = canvas.clientHeight || canvas.parentElement?.clientHeight || 1;
                if (w === 0 || h === 0) return;
                this.camera.aspect = w / h;
                this.camera.updateProjectionMatrix();
                this.renderer.setSize(w, h, false);
            }
            animate() {
                requestAnimationFrame(() => this.animate());
                if (this.controls) this.controls.update();
                if (this.renderer && this.scene && this.camera) this.renderer.render(this.scene, this.camera);
            }
        }

        function initPhotoData() {
            colorFrames = {};
            Object.keys(activeCar.colors || {}).forEach(key => {
                const color = activeCar.colors[key];
                colorFrames[key] = [];
                if (color.images && Array.isArray(color.images)) {
                    const sorted = [...color.images].sort((a, b) => (a.frame_index || 0) - (b.frame_index || 0));
                    sorted.forEach(img => {
                        if (img.image_url) colorFrames[key].push(img.image_url);
                    });
                }
            });

            interiorFrames = {};
            Object.keys(activeCar.interior || {}).forEach(key => {
                const images = activeCar.interior[key];
                if (Array.isArray(images)) interiorFrames[key] = images.map(img => img.image_url).filter(Boolean);
            });

            wheelFrames = {};
            if (activeCar.wheels && Array.isArray(activeCar.wheels)) {
                activeCar.wheels.forEach(wheel => {
                    wheelFrames[wheel.id] = [];
                    if (wheel.images && Array.isArray(wheel.images)) {
                        const sorted = [...wheel.images].sort((a, b) => (a.frame_index || 0) - (b.frame_index ||
                        0));
                        sorted.forEach(img => {
                            if (img.image_url) wheelFrames[wheel.id].push(img.image_url);
                        });
                    } else if (wheel.image_url) {
                        wheelFrames[wheel.id].push(wheel.image_url);
                    }
                });
            }
        }

        function getCurrentPhotos() {
            if (currentPhotoContext === 'color') {
                return colorFrames[currentColorKey] || [];
            }
            if (currentPhotoContext === 'wheel') {
                return wheelFrames[selectedWheel] || [];
            }
            if (currentPhotoContext === 'interior') {
                return interiorFrames[currentInteriorType] || [];
            }
            return [];
        }

        function syncPhotoViewer(context, preserveFrame = true) {
            currentPhotoContext = context;
            const photos = getCurrentPhotos();

            if (!photos.length && activeCar.mainImage) {
                updateMainPhoto(activeCar.mainImage);
                return;
            }
            if (!photos.length) {
                document.getElementById('photoViewerContainer').style.display = 'none';
                return;
            }

            if (!preserveFrame) currentFrameIndex = 0;
            if (currentFrameIndex >= photos.length) currentFrameIndex = 0;

            updateMainPhoto(photos[currentFrameIndex]);
        }

        function updateMainPhoto(url) {
            if (!url) return;
            const currentImg = document.getElementById('photoCurrent');
            if (!currentImg) return;

            preloadImage(url).then(() => {
                currentImg.src = url;
            }).catch(() => {
                currentImg.src = url;
            });
        }

        function renderThumbnails() {
            const track = document.getElementById('thumbnailsTrack');
            if (!track) return;

            const colorPhotos = colorFrames[currentColorKey] || [];
            const wheel = activeCar.wheels?.find(w => w.id === selectedWheel);
            const wheelPhotos = wheelFrames[selectedWheel] || [];
            const wheelUrl = wheel?.image_url || wheelPhotos[0] || '';
            const interiorPhotos = interiorFrames[currentInteriorType] || [];
            const interiorUrl = interiorPhotos[0] || '';

            const thumbDefs = [];

            colorPhotos.forEach((url, i) => {
                thumbDefs.push({
                    type: 'color',
                    frame: i,
                    src: url,
                    alt: `Ракурс ${i+1}`,
                    isActive: currentPhotoContext === 'color' && i === currentFrameIndex
                });
            });

            thumbDefs.push({
                type: 'wheel',
                frame: null,
                src: wheelUrl || '{{ asset('assets/images/offer/2.png') }}',
                alt: 'Колёса',
                isActive: currentPhotoContext === 'wheel'
            });

            thumbDefs.push({
                type: 'interior',
                frame: null,
                src: interiorUrl || '{{ asset('assets/images/offer/2.png') }}',
                alt: 'Салон',
                isActive: currentPhotoContext === 'interior'
            });

            const existingThumbs = Array.from(track.children);

            thumbDefs.forEach((def, index) => {
                let thumb = existingThumbs[index];

                if (!thumb) {
                    thumb = document.createElement('div');
                    thumb.className = 'thumb-item';
                    thumb.innerHTML =
                        `<img src="" alt="" loading="lazy" onerror="this.src='{{ asset('assets/images/offer/2.png') }}'">`;
                    track.appendChild(thumb);
                }

                thumb.dataset.type = def.type;
                if (def.frame !== null) thumb.dataset.frame = def.frame;
                else delete thumb.dataset.frame;

                thumb.classList.toggle('active', def.isActive);

                const img = thumb.querySelector('img');
                if (img && def.src) {
                    if (img.getAttribute('src') !== def.src) {
                        const preloadImg = new Image();
                        preloadImg.onload = () => {
                            img.src = def.src;
                            img.alt = def.alt;
                        };
                        preloadImg.onerror = () => {
                            img.src = '{{ asset('assets/images/offer/2.png') }}';
                        };
                        preloadImg.src = def.src;
                    }
                    img.alt = def.alt;
                }
            });

            while (track.children.length > thumbDefs.length) {
                track.removeChild(track.lastChild);
            }

            if (thumbnailsClickHandler) {
                track.removeEventListener('click', thumbnailsClickHandler);
            }

            thumbnailsClickHandler = function(e) {
                const thumb = e.target.closest('.thumb-item');
                if (!thumb) return;

                const type = thumb.dataset.type;

                if (type === 'color') {
                    const frame = parseInt(thumb.dataset.frame, 10);
                    currentPhotoContext = 'color';
                    currentFrameIndex = frame;
                    const photos = colorFrames[currentColorKey] || [];
                    if (photos[frame]) updateMainPhoto(photos[frame]);
                    highlightThumbnail(thumb);
                } else if (type === 'wheel') {
                    currentPhotoContext = 'wheel';
                    currentFrameIndex = 0;
                    const w = activeCar.wheels?.find(wheel => wheel.id === selectedWheel);
                    const wPhotos = wheelFrames[selectedWheel] || [];
                    const wUrl = wPhotos[0] || w?.image_url || '';
                    updateMainPhoto(wUrl);
                    highlightThumbnail(thumb);
                } else if (type === 'interior') {
                    currentPhotoContext = 'interior';
                    currentFrameIndex = 0;
                    const iPhotos = interiorFrames[currentInteriorType] || [];
                    updateMainPhoto(iPhotos[0] || '');
                    highlightThumbnail(thumb);
                }
            };

            track.addEventListener('click', thumbnailsClickHandler);
        }

        function highlightThumbnail(activeThumb) {
            document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
            if (activeThumb) activeThumb.classList.add('active');
        }

        function updateColorThumbnailActive() {
            if (currentPhotoContext !== 'color') return;
            const thumb = document.querySelector(`.thumb-item[data-type="color"][data-frame="${currentFrameIndex}"]`);
            if (thumb) highlightThumbnail(thumb);
        }

        function initPhotoSwipe() {
            const wrapper = document.getElementById('photoSlideWrapper');
            if (!wrapper) return;

            let startX = 0;
            let isDown = false;
            let hasMoved = false;

            wrapper.addEventListener('mousedown', e => {
                isDown = true;
                hasMoved = false;
                startX = e.clientX;
                wrapper.style.cursor = 'grabbing';
                e.preventDefault();
            });

            wrapper.addEventListener('mousemove', e => {
                if (!isDown) return;
                const diffX = Math.abs(e.clientX - startX);
                if (diffX > 5) hasMoved = true;
            });

            wrapper.addEventListener('mouseleave', e => {
                if (isDown && hasMoved) {
                    const diff = e.clientX - startX;
                    if (Math.abs(diff) > 50) {
                        navigatePhoto(diff < 0 ? 1 : -1);
                    }
                }
                isDown = false;
                hasMoved = false;
                wrapper.style.cursor = 'grab';
            });

            wrapper.addEventListener('mouseup', e => {
                if (!isDown) return;
                isDown = false;
                wrapper.style.cursor = 'grab';

                if (!hasMoved) return;

                const diff = e.clientX - startX;
                if (Math.abs(diff) > 50) {
                    navigatePhoto(diff < 0 ? 1 : -1);
                }
            });

            let touchStartX = 0;
            let touchHasMoved = false;

            wrapper.addEventListener('touchstart', e => {
                touchStartX = e.touches[0].clientX;
                touchHasMoved = false;
            }, {
                passive: true
            });

            wrapper.addEventListener('touchmove', e => {
                const diffX = Math.abs(e.touches[0].clientX - touchStartX);
                if (diffX > 5) touchHasMoved = true;
            }, {
                passive: true
            });

            wrapper.addEventListener('touchend', e => {
                if (!touchHasMoved) return;
                const diff = e.changedTouches[0].clientX - touchStartX;
                if (Math.abs(diff) > 40) {
                    navigatePhoto(diff < 0 ? 1 : -1);
                }
            }, {
                passive: true
            });
        }


        function navigatePhoto(direction) {
            const photos = getCurrentPhotos();
            if (!photos.length) return;

            currentFrameIndex = (currentFrameIndex + direction + photos.length) % photos.length;

            updateMainPhoto(photos[currentFrameIndex]);
            updateColorThumbnailActive();
        }

        function initThumbnailDragScroll() {
            const track = document.getElementById('thumbnailsTrack');
            if (!track) return;
            let isDown = false;
            let startX = 0;
            let scrollLeft = 0;
            track.addEventListener('mousedown', (e) => {
                isDown = true;
                track.style.cursor = 'grabbing';
                startX = e.pageX - track.offsetLeft;
                scrollLeft = track.scrollLeft;
            });
            track.addEventListener('mouseleave', () => {
                isDown = false;
                track.style.cursor = 'grab';
            });
            track.addEventListener('mouseup', () => {
                isDown = false;
                track.style.cursor = 'grab';
            });
            track.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - track.offsetLeft;
                const walk = (x - startX) * 2;
                track.scrollLeft = scrollLeft - walk;
            });
        }

        function has3DModel() {
            return activeCar.models && activeCar.models.length > 0;
        }

        function initViewer() {
            const has3D = has3DModel();
            const toggle = document.getElementById('viewModeToggle');
            if (!toggle) return;

            renderThumbnails();

            if (has3D) {
                toggle.style.display = 'flex';
                switchViewer('3d');
            } else {
                toggle.style.display = 'none';
                switchViewer('photo');
                currentPhotoContext = 'color';
                syncPhotoViewer('color', false);
            }
        }

        function switchViewer(mode) {
            currentViewerMode = mode;
            const canvas = document.getElementById('modelViewer');
            const photoContainer = document.getElementById('photoViewerContainer');
            const controls = document.getElementById('viewerControls');
            const indicator = document.querySelector('.rotate-indicator');

            document.querySelectorAll('.mode-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.mode === mode);
            });

            if (mode === '3d') {
                if (photoContainer) photoContainer.style.display = 'none';
                if (canvas) canvas.style.visibility = 'visible';
                if (controls) controls.style.display = 'flex';
                if (indicator) indicator.style.display = 'block';
                if (modelViewer3D) {
                    setTimeout(() => {
                        modelViewer3D.handleResize();
                        if (modelViewer3D.renderer && modelViewer3D.scene && modelViewer3D.camera) {
                            modelViewer3D.renderer.render(modelViewer3D.scene, modelViewer3D.camera);
                        }
                    }, 50);
                }
            } else {
                if (photoContainer) photoContainer.style.display = 'flex';
                if (canvas) canvas.style.visibility = 'hidden';
                if (controls) controls.style.display = 'none';
                if (indicator) indicator.style.display = 'none';
            }
        }

        function initInteriorGallery() {
            createInteriorTypeSwitcher();
            if (activeCar.interior && Object.keys(activeCar.interior).length > 0) {
                const type = currentInteriorType && activeCar.interior[currentInteriorType] ?
                    currentInteriorType : Object.keys(activeCar.interior)[0];
                selectInteriorType(type, false);
            } else {
                renderInteriorThumbnails();
            }
        }

        function createInteriorTypeSwitcher() {
            const ts = document.getElementById('typeSwitcher');
            if (!ts) return;
            ts.innerHTML = '';
            if (!activeCar.interior || !Object.keys(activeCar.interior).length) {
                ts.innerHTML = '<div style="font-size:12px;color:#888;">Типы интерьера не доступны</div>';
                return;
            }
            Object.keys(activeCar.interior).forEach(key => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'type-btn';
                btn.textContent = key.charAt(0).toUpperCase() + key.slice(1);
                btn.dataset.type = key;
                if (key === currentInteriorType || (!currentInteriorType && key === Object.keys(activeCar.interior)[
                        0])) {
                    btn.classList.add('active');
                }
                btn.addEventListener('click', () => selectInteriorType(key));
                ts.appendChild(btn);
            });
        }

        function selectInteriorType(typeKey, updateViewer = true) {
            if (!activeCar.interior || !activeCar.interior[typeKey]) return;
            currentInteriorType = typeKey;
            currentInteriorPrice = activeCar.interior_prices ? (activeCar.interior_prices[typeKey] || 0) : 0;

            document.querySelectorAll('.type-btn').forEach(b => b.classList.toggle('active', b.dataset.type === typeKey));

            renderInteriorThumbnails();
            renderThumbnails();
            updatePrice();
            updateShareLink();
            markStepByKey('interior');

            if (updateViewer && currentViewerMode === 'photo' && currentPhotoContext === 'interior') {
                syncPhotoViewer('interior', false);
            }
        }

        function renderInteriorThumbnails() {
            const container = document.getElementById('interiorThumbnails');
            const priceTag = document.getElementById('interiorPriceTag');
            if (!container) return;

            container.innerHTML = '';
            const images = activeCar.interior && activeCar.interior[currentInteriorType] ? activeCar.interior[
                currentInteriorType] : [];

            if (priceTag) {
                const price = currentInteriorPrice || 0;
                priceTag.innerHTML = price > 0 ?
                    `<span style="color:#111;font-weight:700;">${formatPrice(price)}</span> <span style="color:#888;">доплата</span>` :
                    `<span style="color:#16a34a;font-weight:600;">Включено в базовую цену</span>`;
            }

            if (!images.length) {
                container.innerHTML =
                    '<div style="grid-column: 1/-1; color:#888; font-size:12px; text-align:center; padding:12px;">Фотографии не загружены</div>';
                return;
            }

            images.forEach((img, i) => {
                const thumb = document.createElement('div');
                thumb.className = 'interior-thumb' + (i === 0 ? ' active' : '');
                thumb.innerHTML =
                    `<img src="${img.image_url}" alt="${img.image_name || 'Салон'}" loading="lazy" onerror="this.style.display='none'">`;
                thumb.addEventListener('click', () => {
                    if (currentViewerMode !== 'photo') switchViewer('photo');
                    currentPhotoContext = 'interior';
                    currentFrameIndex = i;
                    const photos = interiorFrames[currentInteriorType] || [];
                    updateMainPhoto(photos[i] || img.image_url);

                    document.querySelectorAll('.interior-thumb').forEach(t => t.classList.remove('active'));
                    thumb.classList.add('active');
                });
                container.appendChild(thumb);
            });
        }

        class AIChatAssistant {
            constructor() {
                this.messages = [];
                this.isProcessing = false;
                this.chatDialog = document.getElementById('aiChatDialog');
                this.chatInput = document.getElementById('aiChatInput');
                this.sendBtn = document.getElementById('aiSendBtn');
                this.typingIndicator = document.getElementById('aiTypingIndicator');
                this.chatMessages = document.getElementById('aiChatMessages');
                this.isVisible = false;
                this.bindEvents();
                this.updateSendButtonState();
            }
            bindEvents() {
                const bottomBtn = document.getElementById('bottomQuestionBtn');
                const closeBtn = document.getElementById('closeChatDialog');

                if (bottomBtn) bottomBtn.addEventListener('click', () => this.toggle());
                if (closeBtn) closeBtn.addEventListener('click', () => this.hide());
                if (this.sendBtn) this.sendBtn.addEventListener('click', () => this.sendMessage());
                if (this.chatInput) {
                    this.chatInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter' && !this.isProcessing) this.sendMessage();
                    });
                    this.chatInput.addEventListener('input', () => this.updateSendButtonState());
                }
                document.addEventListener('click', (e) => {
                    if (!this.isVisible || !this.chatDialog) return;
                    const bottomBtn = document.getElementById('bottomQuestionBtn');
                    if (!this.chatDialog.contains(e.target) && (!bottomBtn || !bottomBtn.contains(e.target)))
                        this.hide();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isVisible) this.hide();
                });
            }
            toggle() {
                this.isVisible ? this.hide() : this.show();
            }
            show() {
                if (!this.isVisible) {
                    this.chatDialog.classList.add('show');
                    this.isVisible = true;
                    this.loadHistory();
                    setTimeout(() => this.chatInput.focus(), 300);
                }
            }
            hide() {
                if (this.isVisible) {
                    this.chatDialog.classList.remove('show');
                    this.isVisible = false;
                }
            }
            updateSendButtonState() {
                this.sendBtn.disabled = !this.chatInput.value.trim() || this.isProcessing;
            }
            getStorageKey() {
                const uid = document.querySelector('meta[name="user-id"]')?.content || 'guest';
                return `ai_chat_${uid}_car_${activeCarKey || 'global'}`;
            }
            async loadHistory() {
                let serverMessages = [];
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                    const carId = activeCar?.id || '';

                    const r = await fetch(`/configurator/history?car_config_id=${carId}`, {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const d = await r.json();
                    if (d.success && Array.isArray(d.messages)) {
                        serverMessages = d.messages;
                    }
                } catch (e) {
                    console.warn('Не удалось загрузить историю с сервера', e);
                }

                this.chatMessages.innerHTML = '';

                if (serverMessages.length > 0) {
                    serverMessages.forEach(m => this.addMessage(m.text, m.dir === 'sent' ? 'user' : 'ai', false));
                } else {
                    this.showWelcomeMessage();
                }
            }
            syncLocalStorage(ut, at) {
                try {
                    let h = [];
                    const c = localStorage.getItem(this.getStorageKey());
                    if (c) h = JSON.parse(c);
                    h.push({
                        text: ut,
                        dir: 'sent',
                        time: new Date().toLocaleTimeString()
                    }, {
                        text: at,
                        dir: 'received',
                        time: new Date().toLocaleTimeString()
                    });
                    if (h.length > 100) h = h.slice(-100);
                    localStorage.setItem(this.getStorageKey(), JSON.stringify(h));
                } catch (e) {}
            }
            showWelcomeMessage() {
                const w = document.createElement('div');
                w.className = 'message ai-message';
                w.innerHTML =
                    `<div class="message-content"><div class="message-text">Привет! AI-помощник по конфигурации. Помощь с:<div style="margin-top:8px;"><div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;"><span style="color:#0078D7;">•</span><span style="font-size:12px;">Характеристиками двигателей</span></div><div style="display:flex;align-items:center;gap:6px;margin-bottom:4px;"><span style="color:#0078D7;">•</span><span style="font-size:12px;">Комплектациями и опциями</span></div><div style="display:flex;align-items:center;gap:6px;"><span style="color:#0078D7;">•</span><span style="font-size:12px;">Техническими деталями</span></div></div></div></div></div></div>`;
                this.chatMessages.appendChild(w);
            }
            async sendMessage() {
                const text = this.chatInput.value.trim();
                if (!text || this.isProcessing) return;

                this.addMessage(text, 'user');
                this.chatInput.value = '';
                this.updateSendButtonState();
                this.isProcessing = true;
                this.showTyping();

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                const carConfigId = activeCar?.id || '';

                try {
                    const carModel = activeCar ? `${activeCar.name} ${activeCar.variant}` : 'BMW';

                    const fd = new URLSearchParams();
                    fd.append('ai_question', text);
                    fd.append('car_model', carModel);
                    fd.append('car_config_id', carConfigId);

                    const r = await fetch('{{ route('configurator.ai') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: fd
                    });

                    const d = await r.json();
                    this.hideTyping();

                    if (d.success) {
                        this.addMessage(d.answer, 'ai');
                    } else {
                        this.addMessage(d.answer || 'Извините, произошла ошибка.', 'ai');
                    }
                } catch (e) {
                    this.hideTyping();
                    this.addMessage('Ошибка соединения.', 'ai');
                } finally {
                    this.isProcessing = false;
                    this.updateSendButtonState();
                    this.chatInput.focus();
                }
            }
            addMessage(text, sender, animate = true) {
                const md = document.createElement('div');
                md.className = `message ${sender === 'user' ? 'user-message' : 'ai-message'}`;
                if (!animate) md.style.animation = 'none';
                const mc = document.createElement('div');
                mc.className = 'message-content';
                const mt = document.createElement('div');
                mt.className = 'message-text';
                mt.textContent = text;
                mc.appendChild(mt);
                md.appendChild(mc);
                this.chatMessages.appendChild(md);
                this.scrollToBottom();
            }
            showTyping() {
                this.typingIndicator.style.display = 'flex';
                this.scrollToBottom();
            }
            hideTyping() {
                this.typingIndicator.style.display = 'none';
            }
            scrollToBottom() {
                setTimeout(() => this.chatMessages.scrollTo({
                    top: this.chatMessages.scrollHeight,
                    behavior: 'smooth'
                }), 100);
            }
        }

        function applyCarConfig(carKey, savedConfig = null) {
            if (window.aiChatAssistant) {
                window.aiChatAssistant.chatMessages.innerHTML = '';
            }
            localStorage.removeItem(
                `ai_chat_${'user_' + (document.querySelector('meta[name="user-id"]')?.content || 'guest')}_car_${activeCarKey}`
                );

            if (!CARS[carKey]) return;
            activeCarKey = carKey;
            activeCar = CARS[carKey];

            initPhotoData();

            if (savedConfig) {
                if (savedConfig.color && activeCar.colors && activeCar.colors[savedConfig.color]) {
                    currentColorKey = savedConfig.color;
                } else if (Object.keys(activeCar.colors || {}).length > 0) {
                    currentColorKey = Object.keys(activeCar.colors)[0];
                } else {
                    currentColorKey = '';
                }

                if (savedConfig.engine) {
                    const found = activeCar.engines?.find(e => String(e.id) === String(savedConfig.engine));
                    selectedEngine = found ? found.id : (activeCar.engines?.[0]?.id || '');
                } else {
                    selectedEngine = activeCar.engines?.[0]?.id || '';
                }

                if (savedConfig.interior && activeCar.interior && activeCar.interior[savedConfig.interior]) {
                    currentInteriorType = savedConfig.interior;
                } else if (Object.keys(activeCar.interior || {}).length > 0) {
                    currentInteriorType = Object.keys(activeCar.interior)[0];
                } else {
                    currentInteriorType = '';
                }
                currentInteriorPrice = activeCar.interior_prices?.[currentInteriorType] || 0;

                if (savedConfig.wheel) {
                    const found = activeCar.wheels?.find(w => String(w.id) === String(savedConfig.wheel));
                    selectedWheel = found ? found.id : (activeCar.wheels?.[0]?.id || '');
                } else {
                    selectedWheel = activeCar.wheels?.[0]?.id || '';
                }
            } else {
                currentColorKey = Object.keys(activeCar.colors || {})[0] || '';
                selectedEngine = activeCar.engines?.[0]?.id || '';
                selectedWheel = activeCar.wheels?.[0]?.id || '';
                currentInteriorType = Object.keys(activeCar.interior || {})[0] || '';
                currentInteriorPrice = activeCar.interior_prices?.[currentInteriorType] || 0;
            }

            if (bottomCarName) bottomCarName.textContent = activeCar.name || '';
            if (variantTitle) variantTitle.textContent = activeCar.variant || '';
            if (variantDescription) variantDescription.textContent = activeCar.description ||
            'Описание варианта исполнения';
            if (bottomVariant) bottomVariant.textContent = activeCar.variant || '';
            if (summaryVariant) summaryVariant.textContent = activeCar.variant || '';

            buildSwatchesUI();
            initInteriorGallery();
            renderEngineOptions();
            renderWheelOptions();

            if (currentColorKey) selectColor(currentColorKey, false);
            if (selectedEngine) selectEngine(selectedEngine);
            if (selectedWheel) selectWheel(selectedWheel);

            updatePrice();
            updateShareLink();

            if (modelViewer3D && activeCar.models && activeCar.models.length) {
                modelViewer3D.loadModel(activeCar.models[0].file_path);
            } else if (modelViewer3D) {
                modelViewer3D.showTestModel();
            }
        }

        function buildSwatchesUI() {
            if (!swatchesMetal || !swatchesNonMetal) return;
            swatchesMetal.innerHTML = '';
            swatchesNonMetal.innerHTML = '';
            if (!activeCar.colors) return;

            Object.keys(activeCar.colors).forEach(key => {
                const c = activeCar.colors[key];
                const el = document.createElement('div');
                el.className = 'swatch';
                el.dataset.key = key;
                el.title = `${c.label} ${c.price ? '(' + formatPrice(c.price) + ')' : ''}`;
                const inner = document.createElement('div');
                inner.style.cssText = 'width:100%;height:100%;border-radius:50%;background:' + (c.hex || '#ccc') +
                    ';display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,0.8);box-shadow:0 2px 6px rgba(0,0,0,0.1)';
                el.appendChild(inner);
                const check = document.createElement('div');
                check.className = 'check';
                check.innerHTML =
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                el.appendChild(check);
                el.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectColor(key);
                    markStepByKey('color');
                });
                (c.group === 'metal' ? swatchesMetal : swatchesNonMetal).appendChild(el);
            });
            highlightSwatches();
        }

        function highlightSwatches() {
            document.querySelectorAll('.swatch').forEach(s => s.classList.toggle('selected', s.dataset.key ===
                currentColorKey));
        }

        function renderEngineOptions() {
            if (!engineExtra) return;
            engineExtra.innerHTML = '';
            if (!activeCar.engines || !activeCar.engines.length) {
                engineExtra.innerHTML = '<div style="padding:12px;color:#888;font-size:13px;">Двигатели не настроены</div>';
                return;
            }
            const wrapper = document.createElement('div');
            activeCar.engines.forEach(eng => {
                const id = `engine_option_${eng.id}`;
                const row = document.createElement('div');
                row.className = `engine-option ${eng.id === selectedEngine ? 'active' : ''}`;
                row.innerHTML = `
        <div style="display:flex;align-items:flex-start;gap:12px;width:100%;">
            <input type="radio" name="engine" id="${id}" value="${eng.id}" ${eng.id === selectedEngine ? 'checked' : ''} class="engine-radio-hidden" />
            <div class="engine-radio-custom"></div>
            <div style="flex:1;">
                <div class="engine-title">${eng.title}</div>
                <div class="engine-desc">${eng.desc}</div>
            </div>
        </div>
        ${eng.price ? `<div style="position:absolute;top:16px;right:20px;font-weight:600;color:#111;font-size:13px;">+${formatPrice(eng.price)}</div>` : ''}
    `;
                wrapper.appendChild(row);
                row.addEventListener('click', () => selectEngine(eng.id));
                const radio = document.getElementById(id);
                if (radio) radio.addEventListener('change', () => {
                    if (radio.checked) selectEngine(eng.id);
                });
            });
            engineExtra.appendChild(wrapper);
        }

        function renderWheelOptions() {
            const container = document.getElementById('wheelOptions');
            const detailPanel = document.getElementById('wheelDetailPanel');
            if (!container) return;
            container.innerHTML = '';
            if (!activeCar.wheels || !activeCar.wheels.length) {
                container.innerHTML =
                    '<div style="padding:20px;text-align:center;color:#888;">Опции колёс не настроены</div>';
                if (detailPanel) detailPanel.style.display = 'none';
                const wheelNameEl = document.getElementById('wheelName');
                if (wheelNameEl) wheelNameEl.textContent = '—';
                return;
            }
            activeCar.wheels.forEach(wheel => {
                const card = document.createElement('div');
                card.className = `wheel-card-compact ${wheel.id === selectedWheel ? 'active' : ''}`;
                card.dataset.wheelId = wheel.id;
                const imgUrl = wheel.image_url || '/assets/images/wheels/default-wheel.png';
                card.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px; padding: 14px 16px;">
            <div style="width: 48px; height: 48px; border-radius: 10px; background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden;">
                <img src="${imgUrl}" alt="${wheel.title}" style="width: 100%; height: 100%; object-fit: cover;" loading="lazy" onerror="this.src='/assets/images/wheels/default-wheel.png'">
            </div>
            <div style="flex: 1; min-width: 0;">
                <div style="font-weight: 700; font-size: 13px; color: #111; margin-bottom: 2px;">${wheel.title}</div>
                <div style="font-size: 11px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${wheel.desc || 'Литые диски'}</div>
            </div>
        </div>
    `;
                card.addEventListener('click', () => selectWheel(wheel.id));
                container.appendChild(card);
            });
            const aw = activeCar.wheels.find(w => w.id === selectedWheel);
            const wheelNameEl = document.getElementById('wheelName');
            if (wheelNameEl) wheelNameEl.textContent = aw ? aw.title : '—';
            if (aw) renderWheelDetail(aw);
        }

        function renderWheelDetail(wheel) {
            const panel = document.getElementById('wheelDetailPanel');
            if (!panel) return;
            panel.style.display = 'block';
            panel.innerHTML = `
    <div class="wheel-detail-title">${wheel.title}</div>
    <div class="wheel-detail-row">
        <span class="wheel-detail-label">Описание</span>
        <span class="wheel-detail-value">${wheel.desc || 'Литые диски'}</span>
    </div>
    <div class="wheel-detail-row">
        <span class="wheel-detail-label">Доплата</span>
        <span class="wheel-detail-value">${wheel.price ? formatPrice(wheel.price) : 'Включено'}</span>
    </div>
    <div class="wheel-detail-row">
        <span class="wheel-detail-label">Артикул</span>
        <span class="wheel-detail-value">${wheel.id}</span>
    </div>
`;
        }

        function selectWheel(wheelId) {
            const wheel = activeCar.wheels ? activeCar.wheels.find(w => w.id === wheelId) : null;
            if (!wheel) return;

            selectedWheel = wheelId;

            document.querySelectorAll('.wheel-card-compact').forEach(c => {
                c.classList.toggle('active', c.dataset.wheelId === String(wheelId));
            });

            const wheelNameEl = document.getElementById('wheelName');
            if (wheelNameEl) wheelNameEl.textContent = wheel.title;

            renderWheelDetail(wheel);
            renderThumbnails();

            updatePrice();
            updateShareLink();
            markStepByKey('wheel');

            if (currentViewerMode === 'photo' && currentPhotoContext === 'wheel') {
                syncPhotoViewer('wheel', false);
            }
        }

        function selectEngine(engineId) {
            const eng = activeCar.engines ? activeCar.engines.find(e => e.id === engineId) : null;
            if (!eng) return;
            selectedEngine = engineId;
            document.querySelectorAll('.engine-option').forEach(opt => {
                const r = opt.querySelector('input[type="radio"]');
                if (r && r.value === String(engineId)) {
                    opt.classList.add('active');
                    opt.style.border = '2px solid #111';
                    opt.style.background = '#fff';
                    r.checked = true;
                } else {
                    opt.classList.remove('active');
                    opt.style.border = '2px solid transparent';
                    opt.style.background = '#f8f9fa';
                    if (r) r.checked = false;
                }
            });
            if (engineTitle) engineTitle.textContent = eng.title;
            if (engineDesc) engineDesc.textContent = eng.desc;
            updateSpecs();
            updatePrice();
            updateShareLink();
            markStepByKey('engine');
        }

        function selectColor(key, updatePhoto = true) {
            if (!activeCar.colors || !activeCar.colors[key]) return;

            currentColorKey = key;
            highlightSwatches();

            const c = activeCar.colors[key];
            if (colorName) colorName.textContent = c.label;
            if (selColorLabel) selColorLabel.textContent = c.label + (c.price ? ' • ' + formatPrice(c.price) : '');

            const colorType = c.group === 'nonmetal' ? 'matte' : 'gloss';
            if (modelViewer3D && c.hex) {
                modelViewer3D.changeColor(c.hex, colorType);
            }

            updatePrice();
            updateShareLink();

            const newColorPhotos = colorFrames[currentColorKey] || [];

            if (newColorPhotos.length > 0) {
                if (currentFrameIndex >= newColorPhotos.length) {
                    currentFrameIndex = 0;
                }

                if (currentPhotoContext === 'color' && updatePhoto) {
                    updateMainPhoto(newColorPhotos[currentFrameIndex]);
                }
            } else if (activeCar.mainImage) {
                updateMainPhoto(activeCar.mainImage);
            }

            requestAnimationFrame(() => {
                renderThumbnails();
                updateColorThumbnailActive();
            });
        }

        function updateSpecs() {
            if (!specList) return;
            const eng = activeCar.engines ? activeCar.engines.find(e => e.id === selectedEngine) : null;
            if (!eng) return;
            specList.innerHTML = '';
            const labels = ['Мощность, л.с.', 'Разгон 0–100, с', 'Расход, л/100 км', 'CO₂, г/км'];
            const values = [eng.hp, eng.accel, eng.fuel, eng.co2];
            labels.forEach((label, i) => {
                const li = document.createElement('li');
                li.textContent = `${label}: ${values[i] || '—'}`;
                li.style.marginBottom = '4px';
                specList.appendChild(li);
            });
            const hpEl = document.getElementById('hpValue');
            const accelEl = document.getElementById('accelValue');
            const fuelEl = document.getElementById('fuelValue');
            const co2El = document.getElementById('co2Value');
            if (hpEl) hpEl.textContent = eng.hp;
            if (accelEl) accelEl.textContent = eng.accel;
            if (fuelEl) fuelEl.textContent = eng.fuel;
            if (co2El) co2El.textContent = eng.co2;
        }

        function updatePrice() {
            const base = activeCar.basePrice || 0;
            const colorData = activeCar.colors ? activeCar.colors[currentColorKey] : null;
            const colorPrice = colorData ? (colorData.price || 0) : 0;
            const engine = activeCar.engines ? activeCar.engines.find(e => e.id === selectedEngine) : null;
            const enginePrice = engine ? (engine.price || 0) : 0;
            const interiorPrice = currentInteriorPrice || 0;
            const wheel = activeCar.wheels ? activeCar.wheels.find(w => w.id === selectedWheel) : null;
            const wheelPrice = wheel ? (wheel.price || 0) : 0;
            const total = base + colorPrice + enginePrice + interiorPrice + wheelPrice;
            if (summaryPrice) summaryPrice.textContent = formatPrice(total);
        }

        function updateShareLink() {
            if (!shareLink) return;

            const cfg = {
                car: activeCarKey,
                color: currentColorKey,
                engine: selectedEngine,
                interior: currentInteriorType,
                wheel: selectedWheel
            };

            const configStr = JSON.stringify(cfg);
            const encoded = btoa(encodeURIComponent(configStr));

            shareLink.value = `${window.location.origin}${window.location.pathname}?config=${encoded}`;
        }

        function copyShareLink() {
            if (!shareLink) return;
            shareLink.select();
            shareLink.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(shareLink.value).then(() => {
                const copyIcon = document.getElementById('copyIcon');
                const successIcon = document.getElementById('successIcon');
                if (copyIcon) copyIcon.style.display = 'none';
                if (successIcon) successIcon.style.display = 'block';
                if (copyBtn) copyBtn.style.background = '#16a34a';
                setTimeout(() => {
                    if (copyIcon) copyIcon.style.display = 'block';
                    if (successIcon) successIcon.style.display = 'none';
                    if (copyBtn) copyBtn.style.background = '#111';
                }, 2000);
            });
        }

        const STEPS = ['Двигатель', 'Вариант исполнения', 'Окраска кузова', 'Колёса', 'Галерея салона',
            'Технические данные', 'Итог'
        ];

        function renderSteps() {
            if (!stepsDots) return;
            stepsDots.innerHTML = '';
            const mapping = ['enginePanel', 'variantPanel', 'colorPanel', 'wheelPanel', 'interiorPanel', 'specPanel',
                'summaryPanel'
            ];
            STEPS.forEach((step, i) => {
                const dot = document.createElement('div');
                dot.className = 'steps-dot';
                dot.dataset.step = i;
                dot.title = step;
                dot.addEventListener('click', () => {
                    const t = document.getElementById(mapping[i]);
                    if (t && sidebarScroll) {
                        sidebarScroll.scrollTo({
                            top: t.offsetTop - (stepsFixed ? stepsFixed.offsetHeight : 0) - 20,
                            behavior: 'smooth'
                        });
                    }
                });
                stepsDots.appendChild(dot);
            });
            markStep(0);
        }

        function markStep(idx) {
            document.querySelectorAll('.steps-dot').forEach((d, i) => d.classList.toggle('active', i <= idx));
            if (stepsLabel) stepsLabel.textContent = `${String(idx + 1).padStart(2, '0')}/${STEPS.length}: ${STEPS[idx]}`;
        }

        function markStepByKey(key) {
            const map = {
                engine: 0,
                variant: 1,
                color: 2,
                wheel: 3,
                interior: 4,
                specs: 5,
                summary: 6
            };
            if (map.hasOwnProperty(key)) markStep(map[key]);
        }

        function updateActivePanelByVisibility() {
            if (!sidebarScroll) return;
            const r = sidebarScroll.getBoundingClientRect();
            let mv = 0;
            let ai = 0;
            const panelIds = ['enginePanel', 'variantPanel', 'colorPanel', 'wheelPanel', 'interiorPanel', 'specPanel',
                'summaryPanel'
            ];
            panelIds.forEach((id, idx) => {
                const el = document.getElementById(id);
                if (!el) return;
                const cr = el.getBoundingClientRect();
                const v = Math.max(0, Math.min(cr.bottom, r.bottom) - Math.max(cr.top, r.top));
                if (v > mv) {
                    mv = v;
                    ai = idx;
                }
            });
            markStep(ai);

            const activePanel = document.getElementById(panelIds[ai]);
            if (activePanel) {
                const section = activePanel.dataset.section || panelIds[ai].replace('Panel', '');
                syncViewerToSection(section);
            }
        }

        function syncViewerToSection(section) {
            activeSection = section;
            const has3D = has3DModel();

            if (section === 'color') {
                if (currentViewerMode !== 'photo') switchViewer('photo');
                currentPhotoContext = 'color';
                syncPhotoViewer('color', true);
                updateColorThumbnailActive();
            } else if (section === 'wheel') {
                if (currentViewerMode !== 'photo') switchViewer('photo');
                currentPhotoContext = 'wheel';
                syncPhotoViewer('wheel', false);
                const wheelThumb = document.querySelector('.thumb-item[data-type="wheel"]');
                highlightThumbnail(wheelThumb);
            } else if (section === 'interior') {
                if (currentViewerMode !== 'photo') switchViewer('photo');
                currentPhotoContext = 'interior';
                syncPhotoViewer('interior', false);
                const interiorThumb = document.querySelector('.thumb-item[data-type="interior"]');
                highlightThumbnail(interiorThumb);
            } else {
                if (has3D) {
                    switchViewer('3d');
                } else {
                    switchViewer('photo');
                    currentPhotoContext = 'color';
                    syncPhotoViewer('color', false);
                    updateColorThumbnailActive();
                }
            }
        }

        function saveConfiguration() {
            const configNameInput = document.getElementById('configNameInput');
            const configName = configNameInput ? configNameInput.value.trim() : '';
            if (!configName) {
                alert('Введите название конфигурации');
                return;
            }

            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                alert('Ошибка: Bootstrap JS не подключен');
                return;
            }

            const base = activeCar.basePrice || 0;
            const colorData = activeCar.colors ? activeCar.colors[currentColorKey] : null;
            const colorPrice = colorData ? (colorData.price || 0) : 0;
            const engine = activeCar.engines ? activeCar.engines.find(e => e.id === selectedEngine) : null;
            const enginePrice = engine ? (engine.price || 0) : 0;
            const interiorPrice = currentInteriorPrice || 0;
            const wheel = activeCar.wheels ? activeCar.wheels.find(w => w.id === selectedWheel) : null;
            const wheelPrice = wheel ? (wheel.price || 0) : 0;
            const total = base + colorPrice + enginePrice + interiorPrice + wheelPrice;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (!csrfToken) {
                alert('Ошибка CSRF-токена');
                return;
            }

            fetch('{{ route('configurator.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: new URLSearchParams({
                        car_config_id: activeCar.id || 0,
                        config_name: configName,
                        total_price: total,
                        selected_engine: selectedEngine,
                        selected_color: currentColorKey,
                        selected_interior: currentInteriorType,
                        selected_wheel: selectedWheel
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const saveModalEl = document.getElementById('saveConfigModal');
                        const saveModal = bootstrap.Modal.getInstance(saveModalEl) || new bootstrap.Modal(saveModalEl);
                        saveModal.hide();

                        const successModalEl = document.getElementById('successSaveModal');
                        const successModal = new bootstrap.Modal(successModalEl);
                        successModal.show();
                    } else {
                        alert('Ошибка: ' + (data.errors ? data.errors.join(', ') : 'Неизвестная ошибка'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Ошибка при сохранении');
                });
        }

        function showLoading(msg) {
            const l = document.getElementById('loadingOverlay');
            const t = document.getElementById('loadingText');
            if (l && t) {
                l.style.display = 'flex';
                t.textContent = msg;
            }
        }

        function hideLoading() {
            const l = document.getElementById('loadingOverlay');
            if (l) l.style.display = 'none';
        }

        function getUrlParams() {
            const p = new URLSearchParams(window.location.search);
            return {
                engine: p.get('engine'),
                color: p.get('color'),
                interior: p.get('interior'),
                wheel: p.get('wheel')
            };
        }

        function init() {
            modelViewer3D = new ModelViewer3D();

            const urlParams = getUrlParams();
            const savedConfig = (urlParams.engine || urlParams.color || urlParams.interior || urlParams.wheel) ? urlParams :
                null;
            applyCarConfig(activeCarKey, savedConfig);

            initViewer();
            initPhotoSwipe();
            initThumbnailDragScroll();

            const aiChat = new AIChatAssistant();
            window.aiChatAssistant = aiChat;

            document.getElementById('mode3dBtn')?.addEventListener('click', () => {
                switchViewer('3d');
            });
            document.getElementById('modePhotoBtn')?.addEventListener('click', () => {
                switchViewer('photo');
                syncPhotoViewer(currentPhotoContext, true);
            });

            renderSteps();

            setTimeout(updateActivePanelByVisibility, 150);

            if (sidebarScroll) {
                sidebarScroll.addEventListener('scroll', () => {
                    updateActivePanelByVisibility();
                    clearTimeout(window.scrollTimeout);
                    window.scrollTimeout = setTimeout(updateActivePanelByVisibility, 100);
                }, {
                    passive: true
                });
            }

            const saveConfigBtn = document.getElementById('saveConfigBtn');
            if (saveConfigBtn) {
                saveConfigBtn.addEventListener('click', () => {
                    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                        alert('Ошибка: Bootstrap JS не подключен');
                        return;
                    }
                    const modalEl = document.getElementById('saveConfigModal');
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    const configNameInput = document.getElementById('configNameInput');
                    if (configNameInput) configNameInput.value = (activeCar.name || '') + ' ' + (activeCar
                        .variant || '');
                });
            }

            const cancelSaveConfig = document.getElementById('cancelSaveConfig');
            if (cancelSaveConfig) {
                cancelSaveConfig.addEventListener('click', () => {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modalEl = document.getElementById('saveConfigModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                });
            }

            const confirmSaveConfig = document.getElementById('confirmSaveConfig');
            if (confirmSaveConfig) confirmSaveConfig.addEventListener('click', saveConfiguration);

            const configNameInput = document.getElementById('configNameInput');
            if (configNameInput) {
                configNameInput.addEventListener('keypress', e => {
                    if (e.key === 'Enter') saveConfiguration();
                });
            }

            const closeSuccessModal = document.getElementById('closeSuccessModal');
            if (closeSuccessModal) {
                closeSuccessModal.addEventListener('click', () => {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modalEl = document.getElementById('successSaveModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                });
            }

            const goToAccount = document.getElementById('goToAccount');
            if (goToAccount) {
                goToAccount.addEventListener('click', () => window.location.href = '{{ route('account') }}');
            }

            if (copyBtn) copyBtn.addEventListener('click', copyShareLink);

            if (showAllEnginesBtn) {
                showAllEnginesBtn.addEventListener('click', e => {
                    e.stopPropagation();
                    const hidden = !engineExtra.style.display || engineExtra.style.display === 'none';
                    engineExtra.style.display = hidden ? 'block' : 'none';
                    showAllEnginesBtn.textContent = hidden ? 'Скрыть двигатели' : 'Посмотреть все двигатели';
                    markStepByKey('engine');
                });
            }

            const variantMoreBtn = document.getElementById('variantMore');
            if (variantMoreBtn) {
                variantMoreBtn.addEventListener('click', () => {
                    const b = document.getElementById('variantBody');
                    if (!b) return;
                    b.classList.toggle('show');
                    b.style.display = b.classList.contains('show') ? 'block' : 'none';
                    variantMoreBtn.textContent = b.classList.contains('show') ? 'Свернуть ‹' : 'Подробнее ›';
                    b.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                    markStepByKey('variant');
                });
            }

            document.querySelectorAll('.acc-header').forEach(h => {
                h.addEventListener('click', function(e) {
                    const toggleId = this.dataset.accToggle;
                    if (!toggleId) return;

                    const body = document.getElementById(toggleId + 'Body');
                    if (!body) return;

                    const wasShown = body.classList.contains('show');
                    body.classList.toggle('show');
                    body.style.display = body.classList.contains('show') ? 'block' : 'none';

                    if (!wasShown) {
                        const sectionMap = {
                            engine: 'engine',
                            variant: 'variant',
                            color: 'color',
                            wheel: 'wheel',
                            interior: 'interior',
                            specs: 'specs'
                        };
                        const section = sectionMap[toggleId] || toggleId;
                        syncViewerToSection(section);
                    }
                });
            });

            window.addEventListener('resize', updateActivePanelByVisibility);
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
        else init();
    </script>
@endpush
