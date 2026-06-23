<div class="chat-price-cards">
    @foreach ($cars as $car)
        @php
            $displayName = $car->model;
            $brandPrefix = strtolower($car->brand) . ' ';
            if (str_starts_with(strtolower($car->model), $brandPrefix)) {
                $displayName = $car->model;
            }
        @endphp
        <div class="chat-car-card">
            <img src="{{ $car->photo ? asset('storage/' . $car->photo) : asset('assets/images/cars/1779135937_c980abff.png') }}"
                alt="{{ $displayName }}" loading="lazy" 
                onerror="this.src='{{ asset('assets/images/cars/1779135937_c980abff.png') }}'">

            <div class="chat-car-info">
                <div class="chat-car-name">{{ $displayName }}</div>
                <div class="chat-car-price">от {{ number_format($car->price, 0, ',', ' ') }} ₽</div>

                <a href="{{ route('catalog.show', $car) }}" target="_blank" class="chat-car-btn">
                    Подробнее →
                </a>
            </div>
        </div>
    @endforeach

    <a href="{{ $catalogUrl }}" target="_blank" class="chat-price-all">
        Открыть полный каталог →
    </a>
</div>

<style>
    .chat-price-cards {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 4px;
        min-width: 220px;
    }

    .chat-car-card {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(64, 113, 203, 0.05);
        border-radius: 10px;
        padding: 10px;
        border: 1px solid rgba(64, 113, 203, 0.1);
    }

    .chat-car-card img {
        width: 80px;
        height: 55px;
        object-fit: cover;
        border-radius: 6px;
        flex-shrink: 0;
        background: #f0f0f0;
    }

    .chat-car-name {
        font-weight: 600;
        font-size: 13px;
        color: #1D1D1F;
    }

    .chat-car-price {
        font-weight: 700;
        font-size: 13px;
        color: #4071CB;
        margin: 3px 0;
    }

    .chat-car-btn {
        font-size: 11px;
        color: #4071CB;
        text-decoration: none;
        font-weight: 500;
    }

    .chat-price-all {
        text-align: center;
        font-size: 12px;
        color: #4071CB;
        font-weight: 600;
        text-decoration: none;
        padding: 6px;
        background: rgba(64, 113, 203, 0.1);
        border-radius: 8px;
        margin-top: 4px;
        display: block;
    }
</style>