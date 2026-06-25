@extends('layouts.admin')

@section('title', 'Управление отзывами')

@push('styles')
<style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #7f8c8d;
        --accent-color: #4071CB;
        --success-color: #27ae60;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --card-bg: #ffffff;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
    }

    .section {
        padding-top: 30px;
        padding-bottom: 50px;
    }

    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
        padding: 20px 0;
    }

    .review-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 0;
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .review-card-header {
        padding: 24px 24px 0;
        position: relative;
    }

    .review-card-body {
        padding: 10px 24px;
        flex-grow: 1;
    }

    .review-card-footer {
        padding: 0 24px 24px;
    }

    .review-user {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .review-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f1f5fd;
    }

    .review-user-info {
        flex-grow: 1;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .review-user-details {
        flex: 1;
    }

    .review-user-name {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .review-stars {
        color: #ffc107;
        letter-spacing: 2px;
    }

    .review-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 500;
        letter-spacing: 0.5px;
        margin-left: 15px;
    }

    .status-approved {
        background: rgba(39, 174, 96, 0.15);
        color: var(--success-color);
        border: 1px solid rgba(39, 174, 96, 0.3);
    }

    .status-pending {
        background: rgba(243, 156, 18, 0.15);
        color: var(--warning-color);
        border: 1px solid rgba(243, 156, 18, 0.3);
    }

    .review-meta-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding: 12px 0;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    .review-id {
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .review-date {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .review-text {
        color: var(--text-primary);
        line-height: 1.6;
        margin-bottom: 0;
        font-size: 0.95rem;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .show-more-btn {
        color: var(--accent-color);
        background: none;
        border: none;
        font-size: 0.9rem;
        cursor: pointer;
        padding: 5px 0;
        margin-top: 8px;
        text-decoration: underline;
        display: inline-block;
    }

    .show-more-btn:hover {
        color: #345fb0;
    }

    .review-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        flex: 1;
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
    }

    .action-btn-filled {
        background: var(--accent-color);
        color: white;
        border: none;
    }

    .action-btn-filled:hover {
        background: #345fb0;
        transform: translateY(-2px);
        color: white;
    }

    .action-btn-outline {
        background: transparent;
        color: var(--accent-color);
        border: 1px solid var(--accent-color);
    }

    .action-btn-outline:hover {
        background: var(--accent-color);
        color: white;
        transform: translateY(-2px);
    }

    .action-btn-danger {
        background: transparent;
        color: var(--danger-color);
        border: 1px solid var(--danger-color);
    }

    .action-btn-danger:hover {
        background: var(--danger-color);
        color: white;
        transform: translateY(-2px);
    }

    .section-subtitle {
        color: var(--text-secondary);
        font-size: 1.05rem;
        margin-bottom: 0 !important;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--text-secondary);
        grid-column: 1 / -1;
    }

    .empty-state i {
        font-size: 4.5rem;
        margin-bottom: 1.5rem;
        color: #e0e6ed;
        opacity: 0.7;
    }

    .empty-state h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .alert-notification {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        border-radius: 12px;
        border: none;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
    }

    .btn-primary {
        background: var(--accent-color);
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .full-review-modal .modal-content {
        border-radius: 16px;
        border: none;
    }

    .full-review-modal .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 20px 24px;
    }

    .full-review-modal .modal-body {
        padding: 24px;
        line-height: 1.6;
        font-size: 1rem;
    }

    .full-review-modal .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        padding: 16px 24px;
    }

    @media (max-width: 992px) {
        .reviews-grid {
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .reviews-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .review-card {
            margin-bottom: 0;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .review-user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .review-status {
            margin-left: 0;
            align-self: flex-start;
        }
    }

    @media (max-width: 576px) {
        .review-card-header,
        .review-card-body,
        .review-card-footer {
            padding: 20px;
        }

        .review-user {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .review-user-info {
            align-items: center;
            text-align: center;
        }

        .review-meta-info {
            flex-direction: column;
            gap: 8px;
            align-items: flex-start;
        }

        .review-actions {
            flex-direction: column;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .review-card {
        animation: fadeIn 0.5s ease forwards;
    }

    .review-card:nth-child(1) { animation-delay: 0.1s; }
    .review-card:nth-child(2) { animation-delay: 0.2s; }
    .review-card:nth-child(3) { animation-delay: 0.3s; }
    .review-card:nth-child(4) { animation-delay: 0.4s; }
    .review-card:nth-child(5) { animation-delay: 0.5s; }
    .review-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-notification alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="d-none d-xl-block mt-2 mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Отзывы</li>
            </ol>
        </nav>
    </div>

    <div class="page-header">
        <div>
            <h4 class="section-title">Управление отзывами</h4>
            <p class="section-subtitle">Модерация и публикация отзывов пользователей</p>
        </div>
        <div class="header-actions">
            <form method="POST" action="{{ route('admin.reviews.publishAll') }}">
                @csrf
                <button type="submit" name="publish_all" class="btn btn-primary"
                        onclick="return confirm('Опубликовать все ожидающие отзывы?')">
                    <i class="bi bi-send-check me-2"></i> Опубликовать все
                </button>
            </form>
        </div>
    </div>

    <div class="reviews-grid">
        @forelse ($reviews as $review)
            <div class="review-card">
                <div class="review-card-header">
                    <div class="review-user">
                        <img src="{{ asset('assets/images/reviews/user.svg') }}" alt="user" class="review-avatar">
                        <div class="review-user-info">
                            <div class="review-user-details">
                                <div class="review-user-name">{{ $review->user_name }}</div>
                                <div class="review-stars">
                                    {!! str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) !!}
                                </div>
                            </div>
                            <span class="review-status status-{{ $review->status }}">
                                {{ $review->status === 'approved' ? 'Опубликован' : 'На модерации' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="review-card-body">
                    <p class="review-text">
                        {{ $review->comment }}
                    </p>

                    @if (mb_strlen($review->comment) > 300)
                        <button class="show-more-btn" data-bs-toggle="modal" data-bs-target="#fullReviewModal"
                                data-review-id="{{ $review->id }}"
                                data-review-name="{{ $review->user_name }}"
                                data-review-rating="{{ $review->rating }}"
                                data-review-date="{{ $review->created_at->format('d.m.Y в H:i') }}"
                                data-review-comment="{{ $review->comment }}">
                            Показать полностью
                        </button>
                    @endif

                    <div class="review-meta-info">
                        <div class="review-id">ID: {{ $review->id }}</div>
                        <div class="review-date">{{ $review->created_at->format('d.m.Y в H:i') }}</div>
                    </div>
                </div>

                <div class="review-card-footer">
                    <div class="review-actions">
                        @if ($review->status === 'approved')
                            <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" style="flex:1;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="action-btn action-btn-outline w-100"
                                        onclick="return confirm('Скрыть этот отзыв?')">
                                    Скрыть
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" style="flex:1;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="action-btn action-btn-filled w-100"
                                        onclick="return confirm('Опубликовать этот отзыв?')">
                                    Опубликовать
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="flex:1;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn action-btn-danger w-100"
                                    onclick="return confirm('Вы уверены, что хотите удалить этот отзыв?')">
                                Удалить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-chat-square-text"></i>
                <h5>Отзывы не найдены</h5>
                <p class="text-muted">Пользователи еще не оставили отзывов</p>
            </div>
        @endforelse
    </div>

    {{-- Модальное окно для полного текста отзыва --}}
    <div class="modal fade full-review-modal" id="fullReviewModal" tabindex="-1" aria-labelledby="fullReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center w-100">
                        <img src="{{ asset('assets/images/reviews/user.svg') }}" alt="user" class="review-avatar me-3">
                        <div class="flex-grow-1">
                            <h5 id="fullReviewModalLabel"></h5>
                            <div class="review-stars" id="modalReviewStars"></div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalReviewComment" style="line-height: 1.6; font-size: 1rem; white-space: pre-wrap; word-wrap: break-word;"></p>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <small class="text-muted" id="modalReviewId"></small>
                            <span class="text-muted mx-2">•</span>
                            <small class="text-muted" id="modalReviewDate"></small>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.review-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
        });

        const fullReviewModal = document.getElementById('fullReviewModal');
        if (fullReviewModal) {
            fullReviewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const reviewId = button.getAttribute('data-review-id');
                const reviewName = button.getAttribute('data-review-name');
                const reviewRating = button.getAttribute('data-review-rating');
                const reviewDate = button.getAttribute('data-review-date');
                const reviewComment = button.getAttribute('data-review-comment');

                document.getElementById('fullReviewModalLabel').textContent = reviewName;
                document.getElementById('modalReviewStars').innerHTML = '★'.repeat(reviewRating) + '☆'.repeat(5 - reviewRating);
                document.getElementById('modalReviewDate').textContent = reviewDate;
                document.getElementById('modalReviewComment').textContent = reviewComment;
                document.getElementById('modalReviewId').textContent = 'ID отзыва: ' + reviewId;
            });
        }
    });
</script>
@endpush