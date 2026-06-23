
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top">
        <div class="modal-content">
            <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Закрыть">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </button>

            <div class="modal-inner-box">
                <div class="content">
                    <div class="svg-vector">
                        <svg width="421" height="578" viewBox="0 0 421 578" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M-133.22 1.40758C-133.22 1.40758 124.267 100.639 139.145 217.357C154.023 334.075 33.8168 309.992 37.1622 451.076C40.5076 592.16 361.784 651.036 405.765 739.023C449.745 827.01 371.441 913.95 371.441 913.95"
                                stroke="white" stroke-opacity="0.8" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="svg-vector-1">
                        <svg width="648" height="451" viewBox="0 0 648 451" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M892.5 1.4448C892.5 1.4448 616.816 -10.5824 556.5 90.4448C496.184 191.472 615.99 217.473 556.5 345.445C497.01 473.417 179.633 359.331 103.5 461.945C27.3667 564.559 112.745 599.207 50 681.945C-12.7447 764.683 -274 735.445 -274 735.445"
                                stroke="white" stroke-opacity="0.8" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <h2 class="modal-title">ОСТАВИТЬ ОТЗЫВ</h2>
                    <div class="divider"></div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="forms">
                        <form class="review-form active" novalidate method="POST" action="{{ route('reviews.store') }}"
                            id="reviewForm">
                            @csrf

                            <!-- Рейтинг -->
                            <div class="mb-3">
                                <div class="field rating-field d-flex justify-content-between align-items-center"
                                    data-name="rating">
                                    <p class="fields-label m-0">Ваша оценка <span class="text-danger">*</span></p>
                                    <div class="rating-stars" role="radiogroup" aria-label="Оцените от 1 до 5 звёзд">
                                        <span class="star" data-value="1">★</span>
                                        <span class="star" data-value="2">★</span>
                                        <span class="star" data-value="3">★</span>
                                        <span class="star" data-value="4">★</span>
                                        <span class="star" data-value="5">★</span>
                                    </div>
                                    <input type="hidden" id="review-rating" name="rating" value="{{ old('rating') }}"
                                        required />
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                <div class="error-container" id="error-container-rating"></div>
                                @error('rating')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Имя пользователя -->
                            @auth
                                <div class="field mb-4">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <input type="text" value="{{ Auth::user()->name }}" readonly
                                        class="form-control-plaintext">
                                    <label style="left: 52px;">Ваше имя</label>
                                </div>
                            @else
                                <div class="field mb-4">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <input type="text" value="Гость" readonly class="form-control-plaintext">
                                    <label style="left: 52px;">Ваше имя</label>
                                </div>
                                <div class="alert alert-warning mb-3">
                                    <small>Для отправки отзыва необходимо <a href="{{ route('login') }}">войти в
                                            аккаунт</a></small>
                                </div>
                            @endauth

                            <!-- Отзыв -->
                            <div class="mb-3">
                                <div class="field textarea-field" data-name="comment">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M17 3H7c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 14H8v-2h8v2zm0-4H8v-2h8v2zm0-4H8V7h8v2z" />
                                        </svg>
                                    </div>
                                    <textarea id="review-comment" name="comment" placeholder=" " required minlength="10" maxlength="500"
                                        rows="4">{{ old('comment') }}</textarea>
                                    <label for="review-comment">Ваш отзыв</label>
                                    <span class="status" aria-hidden="true"></span>
                                    <div class="char-count comment-count">0 / 500</div>
                                </div>
                                @error('comment')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Согласие -->
                            <div class="mb-3">
                                <div class="consent {{ $errors->has('agree') ? 'error' : '' }}" id="consent-block">
                                    <input type="checkbox" id="review-agree" name="agree" value="1"
                                        {{ old('agree') ? 'checked' : '' }} required>
                                    <label for="review-agree">
                                        Я согласен с
                                        <a href="{{ asset('assets/documents/politics.docx') }}" target="_blank"
                                            rel="noopener">
                                            политикой конфиденциальности
                                        </a> *
                                    </label>
                                </div>
                                @error('agree')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            @auth
                                <button class="submit" type="submit">ОТПРАВИТЬ ОТЗЫВ</button>
                            @else
                                <button class="submit" type="button"
                                    onclick="window.location.href='{{ route('login') }}'">ВОЙТИ ЧТОБЫ ОТПРАВИТЬ</button>
                            @endauth
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal {
        z-index: 1060 !important;
    }

    .reviews-track {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .reviews-track:focus {
        outline: none;
    }

    .rating-stars {
        display: flex;
        gap: 8px;
    }

    .star {
        font-size: 28px;
        color: #e0e0e0;
        cursor: pointer;
        transition: all 0.2s ease;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .star.active {
        color: #4071CB;
        text-shadow: 0 2px 4px rgba(64, 113, 203, 0.3);
    }

    .star:hover {
        transform: scale(1.1);
    }

    .char-count {
        text-align: right;
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }

    .field.error {
        border-color: #dc3545 !important;
    }

    .field.error input,
    .field.error textarea {
        border-color: #dc3545;
    }

    .field.error label {
        color: #dc3545;
    }

    .error-container {
        min-height: 20px;
        margin-top: 4px;
    }

    .field-error {
        color: #dc3545;
        font-size: 0.875em;
        display: block;
        font-weight: 500;
    }

    .consent.error label {
        color: #dc3545;
    }

    .review-card {
        flex: 0 0 calc(50% - 12px);
        min-width: 0;
        border: 1px solid #dedede;
        border-radius: 12px;
        padding: 1.5rem;
        min-height: 220px;
        background: transparent url("assets/images/reviews/vector.svg") no-repeat right 0 bottom 0;
        background-size: 150px !important;
    }

    @media (max-width: 768px) {
        .review-card {
            flex: 0 0 100%;
            min-width: 100%;
            margin-bottom: 16px;
        }

        .reviews-container {
            gap: 16px !important;
        }
    }

    .review-date {
        margin-top: 10px;
        font-size: 12px;
        color: #6c757d;
    }

    .review-stars {
        color: #4071CB;
        font-size: 18px;
        letter-spacing: 2px;
    }

    .review-text {
        color: #6c757d;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 6;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .read-more-btn {
        background: none;
        border: none;
        color: #4071CB;
        cursor: pointer;
        font-size: 14px;
        padding: 0;
        text-decoration: underline;
        transition: color 0.2s ease;
    }

    .read-more-btn:hover {
        color: #2c5bb4;
    }

    .full-review-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }

    .full-review-text {
        line-height: 1.6;
        color: #333;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .rating-stars {
            gap: 4px;
        }

        .star {
            font-size: 24px;
            width: 24px;
            height: 24px;
        }

        .review-text {
            font-size: 14px;
        }
    }

    .field-error {
        animation: fadeIn 0.3s ease-in-out;
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

    .alert-success {
        animation: slideDown 0.5s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fullReviews = {};
        <?php foreach ($reviews as $review): ?>
        fullReviews[<?= $review['id'] ?>] = {
            name: '<?= addslashes($review['user_name']) ?>',
            rating: <?= $review['rating'] ?>,
            comment: `<?= addslashes($review['comment']) ?>`,
            date: '<?= date('d.m.Y', strtotime($review['created_at'])) ?>'
        };
        <?php endforeach; ?>

        function resetReviewForm() {
            const form = document.getElementById('reviewForm');
            if (form) {
                form.reset();
                document.querySelectorAll('.star').forEach(star => {
                    star.classList.remove('active');
                    star.style.color = '#e0e0e0';
                });
                const ratingInput = document.getElementById('review-rating');
                if (ratingInput) ratingInput.value = '';
                const commentCount = document.querySelector('.comment-count');
                if (commentCount) commentCount.textContent = '0 / 500';
                clearAllErrors();
                document.querySelectorAll('.status').forEach(status => status.innerHTML = '');
            }
        }

        function clearAllErrors() {
            document.querySelectorAll('.error-container').forEach(el => el.innerHTML = '');
            document.querySelectorAll('.field').forEach(field => field.classList.remove('error'));
            document.querySelectorAll('.consent').forEach(el => el.classList.remove('error'));
        }

        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('review-rating');

        function restoreRatingStars() {
            if (stars.length > 0 && ratingInput && ratingInput.value) {
                const value = parseInt(ratingInput.value);
                stars.forEach(star => {
                    const starValue = parseInt(star.getAttribute('data-value'));
                    if (starValue <= value) {
                        star.classList.add('active');
                        star.style.color = '#4071CB';
                    } else {
                        star.classList.remove('active');
                        star.style.color = '#e0e0e0';
                    }
                });
            }
        }

        if (stars.length > 0 && ratingInput) {
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = value;
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        if (starValue <= value) {
                            s.classList.add('active');
                            s.style.color = '#4071CB';
                        } else {
                            s.classList.remove('active');
                            s.style.color = '#e0e0e0';
                        }
                    });
                    clearError('rating');
                });

                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        if (starValue <= value) s.style.color = '#6c9aff';
                    });
                });

                star.addEventListener('mouseout', function() {
                    const currentRating = parseInt(ratingInput.value) || 0;
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        if (starValue <= currentRating) s.style.color = '#4071CB';
                        else s.style.color = '#e0e0e0';
                    });
                });
            });
        }

        function showError(field, message) {
            const container = document.getElementById('error-container-' + field);
            if (container) {
                container.innerHTML = '<div class="field-error">' + message + '</div>';
            }
            const fieldEl = document.querySelector('[data-name="' + field + '"]');
            if (fieldEl) fieldEl.classList.add('error');
            if (field === 'agree') {
                const consent = document.getElementById('consent-block');
                if (consent) consent.classList.add('error');
            }
        }

        function clearError(field) {
            const container = document.getElementById('error-container-' + field);
            if (container) container.innerHTML = '';
            const fieldEl = document.querySelector('[data-name="' + field + '"]');
            if (fieldEl) fieldEl.classList.remove('error');
            if (field === 'agree') {
                const consent = document.getElementById('consent-block');
                if (consent) consent.classList.remove('error');
            }
        }

        function validateRatingField() {
            const ratingValue = ratingInput ? ratingInput.value : '';
            if (!ratingValue) {
                showError('rating', 'Выберите оценку');
                return false;
            }
            clearError('rating');
            return true;
        }

        const commentTextarea = document.getElementById('review-comment');
        const commentCount = document.querySelector('.comment-count');

        if (commentTextarea && commentCount) {
            commentTextarea.addEventListener('input', function() {
                const length = this.value.length;
                commentCount.textContent = length + ' / 500';
                commentCount.style.color = length > 500 ? '#dc3545' : '#666';
                if (length >= 10) clearError('comment');
            });
        }

        function validateCommentField() {
            const commentValue = commentTextarea ? commentTextarea.value.trim() : '';
            if (!commentValue) {
                showError('comment', 'Введите текст отзыва');
                return false;
            } else if (commentValue.length < 10) {
                showError('comment', 'Отзыв должен содержать минимум 10 символов');
                return false;
            } else if (commentValue.length > 500) {
                showError('comment', 'Отзыв не должен превышать 500 символов');
                return false;
            }
            clearError('comment');
            return true;
        }

        function validateAgreeField() {
            const agreeCheckbox = document.getElementById('review-agree');
            if (!agreeCheckbox || !agreeCheckbox.checked) {
                showError('agree', 'Необходимо согласие с политикой конфиденциальности');
                return false;
            }
            clearError('agree');
            return true;
        }

        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearAllErrors();

                let isValid = true;
                if (!validateRatingField()) isValid = false;
                if (!validateCommentField()) isValid = false;
                if (!validateAgreeField()) isValid = false;

                if (!isValid) {
                    const firstError = document.querySelector('.error');
                    if (firstError) firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return;
                }

                const submitBtn = this.querySelector('.submit');
                const originalText = submitBtn ? submitBtn.textContent : 'ОТПРАВИТЬ ОТЗЫВ';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Отправка...';
                    submitBtn.style.opacity = '0.7';
                }

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            if (typeof bootstrap !== "undefined") {
                                const modalEl = document.getElementById("reviewModal");
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();
                            }
                            resetReviewForm();
                        } else {
                            return response.json().then(data => {
                                throw data;
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Ошибка отправки:', err);
                        alert('Произошла ошибка при отправке отзыва');
                    })
                    .finally(() => {
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                            submitBtn.style.opacity = '1';
                        }
                    });
            });
        }

        const reviewModal = document.getElementById('reviewModal');
        if (reviewModal) {
            reviewModal.addEventListener('show.bs.modal', resetReviewForm);
            reviewModal.addEventListener('hidden.bs.modal', function() {
                const submitBtn = reviewForm ? reviewForm.querySelector('.submit') : null;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'ОТПРАВИТЬ ОТЗЫВ';
                    submitBtn.style.opacity = '1';
                }
            });
            reviewModal.addEventListener('shown.bs.modal', restoreRatingStars);
        }

        document.querySelectorAll('.read-more-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const reviewId = this.getAttribute('data-review-id');
                const reviewData = fullReviews[reviewId];
                if (reviewData) {
                    document.getElementById('fullReviewName').textContent = reviewData.name;
                    document.getElementById('fullReviewText').textContent = reviewData.comment;
                    document.getElementById('fullReviewDate').textContent = reviewData.date;
                    const starsContainer = document.getElementById('fullReviewStars');
                    starsContainer.innerHTML = '';
                    for (let i = 1; i <= 5; i++) {
                        const star = document.createElement('span');
                        star.textContent = i <= reviewData.rating ? '★' : '☆';
                        star.style.color = i <= reviewData.rating ? '#4071CB' : '#e0e0e0';
                        starsContainer.appendChild(star);
                    }
                    const fullReviewModal = new bootstrap.Modal(document.getElementById(
                        'fullReviewModal'));
                    fullReviewModal.show();
                }
            });
        });

        const track = document.querySelector('.reviews-track');
        const scrollLeftBtn = document.querySelector('.scroll-left');
        const scrollRightBtn = document.querySelector('.scroll-right');

        if (track && scrollLeftBtn && scrollRightBtn) {
            let isScrolling = false;

            function updateButtonVisibility() {
                scrollLeftBtn.style.display = track.scrollLeft <= 0 ? 'none' : 'block';
                const maxScrollLeft = track.scrollWidth - track.clientWidth;
                scrollRightBtn.style.display = track.scrollLeft >= maxScrollLeft - 1 ? 'none' : 'block';
            }

            updateButtonVisibility();
            track.addEventListener('scroll', updateButtonVisibility);
            window.addEventListener('resize', updateButtonVisibility);

            function getContainerPadding(container) {
                return parseInt(window.getComputedStyle(container).paddingLeft) || 0;
            }

            function getNextScrollPosition(container, direction) {
                const cards = container.querySelectorAll('.review-card');
                if (!cards.length) return container.scrollLeft;
                const containerRect = container.getBoundingClientRect();
                let currentVisibleIndex = -1;
                for (let i = 0; i < cards.length; i++) {
                    const cardRect = cards[i].getBoundingClientRect();
                    if (cardRect.left >= containerRect.left && cardRect.right <= containerRect.right) {
                        currentVisibleIndex = i;
                        break;
                    }
                }
                if (currentVisibleIndex === -1) {
                    for (let i = 0; i < cards.length; i++) {
                        const cardRect = cards[i].getBoundingClientRect();
                        if (cardRect.right > containerRect.left && cardRect.left < containerRect.right) {
                            currentVisibleIndex = i;
                            break;
                        }
                    }
                }
                if (currentVisibleIndex === -1) currentVisibleIndex = 0;
                if (direction === 'right') {
                    const nextIndex = Math.min(currentVisibleIndex + 1, cards.length - 1);
                    return cards[nextIndex].offsetLeft - getContainerPadding(container);
                } else {
                    const prevIndex = Math.max(currentVisibleIndex - 1, 0);
                    return cards[prevIndex].offsetLeft - getContainerPadding(container);
                }
            }

            function smoothScroll(container, direction) {
                if (isScrolling) return;
                isScrolling = true;
                container.scrollTo({
                    left: getNextScrollPosition(container, direction),
                    behavior: 'smooth'
                });
                setTimeout(() => {
                    isScrolling = false;
                    updateButtonVisibility();
                }, 300);
            }

            scrollRightBtn.addEventListener('click', () => smoothScroll(track, 'right'));
            scrollLeftBtn.addEventListener('click', () => smoothScroll(track, 'left'));
        }

        @if (session('success'))
            setTimeout(() => {
                if (typeof bootstrap !== "undefined") {
                    const modalEl = document.getElementById("reviewModal");
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
            }, 2000);
        @endif

        if (commentTextarea && commentCount) {
            commentCount.textContent = commentTextarea.value.length + ' / 500';
        }

        restoreRatingStars();
    });
</script>
