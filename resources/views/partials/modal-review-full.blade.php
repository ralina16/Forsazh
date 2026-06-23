
<div class="modal fade full-review-modal" id="fullReviewModal" tabindex="-1" aria-labelledby="fullReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Закрыть">
                <svg viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </button>

            <div class="modal-inner-box">
                <div class="content">
                    <h2 class="modal-title">ОТЗЫВ</h2>
                    <div class="divider"></div>

                    <div class="review-detail">
                        <div class="review-detail-header">
                            <img src="{{ asset('assets/images/reviews/user.svg') }}" alt="user" class="review-avatar">
                            <div class="review-detail-info">
                                <div class="review-name-row">
                                    <h5 class="review-name mb-0" id="fullReviewModalLabel"></h5>
                                    <small class="review-date" id="modalReviewDate"></small>
                                </div>
                                <div class="review-stars" id="modalReviewStars"></div>
                            </div>
                        </div>
                        
                        <p class="review-comment-text mb-0" id="modalReviewComment"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .full-review-modal .modal-content {
        border: none;
        border-radius: 20px;
        color: #2c3e50;
        position: relative;
        overflow: hidden;
    }

    .full-review-modal .modal-dialog {
        max-width: 560px;
    }

    .full-review-modal .modal-inner-box {
        position: relative;
        padding: 40px;
    }

    .full-review-modal .content {
        position: relative;
        z-index: 2;
    }

    .full-review-modal .modal-title {
        font-size: 1.6rem;
        text-align: center;
        color: #333;
        margin-bottom: 12px;
        letter-spacing: 1px;
    }

    .full-review-modal .divider {
        width: 60px;
        height: 3px;
        background: rgba(0,0,0,0.15);
        border-radius: 2px;
        margin: 0 auto 28px;
    }

    .full-review-modal .review-detail-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 20px;
    }

    .full-review-modal .review-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(0,0,0,0.08);
        flex-shrink: 0;
    }

    .full-review-modal .review-detail-info {
        flex: 1;
        min-width: 0;
    }

    .full-review-modal .review-name-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 4px;
    }

    .full-review-modal .review-name {
        font-size: 1.15rem;
        font-weight: 600;
        color: #2c3e50;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .full-review-modal .review-stars {
        color: #ffc107;
        font-size: 1.1rem;
        letter-spacing: 3px;
        line-height: 1;
    }

    .full-review-modal .review-date {
        font-size: 0.85rem;
        color: rgba(0,0,0,0.5);
        white-space: nowrap;
        flex-shrink: 0;
    }

    .full-review-modal .review-comment-text {
        color: #2c3e50;
        font-size: 1.05rem;
        line-height: 1.7;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    @media (max-width: 576px) {
        .full-review-modal .modal-inner-box {
            padding: 32px 20px 24px;
        }
        .full-review-modal .modal-title {
            font-size: 1.3rem;
        }
        .full-review-modal .review-avatar {
            width: 48px;
            height: 48px;
        }
        .full-review-modal .review-comment-text {
            font-size: 0.95rem;
        }
        .full-review-modal .review-name-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        .full-review-modal .review-date {
            margin-top: 0;
        }
    }
</style>
@endpush

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fullReviewModal = document.getElementById('fullReviewModal');
        if (fullReviewModal) {
            fullReviewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                if (!button) return;

                const reviewName = button.getAttribute('data-review-name');
                const reviewRating = parseInt(button.getAttribute('data-review-rating'), 10) || 0;
                const reviewDate = button.getAttribute('data-review-date');
                const reviewComment = button.getAttribute('data-review-comment');

                document.getElementById('fullReviewModalLabel').textContent = reviewName || '';
                document.getElementById('modalReviewStars').innerHTML = '★'.repeat(reviewRating) + '☆'.repeat(5 - reviewRating);
                document.getElementById('modalReviewDate').textContent = reviewDate || '';
                document.getElementById('modalReviewComment').textContent = reviewComment || '';
            });
        }
    });
</script>
@endpush
@endonce