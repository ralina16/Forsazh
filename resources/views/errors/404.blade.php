@extends('layouts.app')

@section('title', 'Страница не найдена | ФОРСАЖ')

@section('content')
<style>
    @keyframes slideUpFade {
        0% {
            opacity: 0;
            transform: translateY(24px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-container {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 40px 20px;
    }

    @media (min-width: 768px) {
        .error-container {
            min-height: 70vh;
        }
    }

    .error-bg-number {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
        user-select: none;
        overflow: hidden;
    }

    .error-bg-number span {
        font-size: 250px;
        font-weight: 900;
        letter-spacing: -0.05em;
        color: rgba(64, 113, 203, 0.05);
        line-height: 1;
    }

    @media (min-width: 768px) {
        .error-bg-number span {
            font-size: 400px;
        }
    }

    .error-content {
        position: relative;
        z-index: 10;
        text-align: center;
        max-width: 640px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .error-icon {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border: 1px solid #e9ecef;
        box-shadow: 0 12px 24px -12px rgba(64, 113, 203, 0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 32px;
        color: #cbd5e1;
    }

    .error-icon svg {
        width: 32px;
        height: 32px;
        stroke: #4071CB;
        stroke-width: 1.8;
        fill: none;
    }

    .error-title {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #1D1D1F;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .error-message {
        font-size: 1rem;
        color: #6c757d;
        font-weight: 500;
        line-height: 1.6;
        margin-bottom: 40px;
        padding: 0 16px;
    }

    @media (min-width: 768px) {
        .error-title {
            font-size: 3rem;
        }
        .error-message {
            font-size: 1.125rem;
            padding: 0;
        }
    }

    .error-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: #4071CB;
        color: white;
        padding: 12px 32px;
        border-radius: 60px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-decoration: none;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(64, 113, 203, 0.2);
    }

    .error-btn:hover {
        background: #2f5bb0;
        transform: scale(1.02);
        box-shadow: 0 12px 24px -8px rgba(64, 113, 203, 0.3);
        color: white;
        text-decoration: none;
    }

    .error-btn:active {
        transform: scale(0.98);
    }

    .error-btn svg {
        width: 18px;
        height: 18px;
        transition: transform 0.2s ease;
    }

    .error-btn:hover svg {
        transform: translateX(-4px);
    }

    @media (max-width: 480px) {
        .error-icon {
            width: 52px;
            height: 52px;
        }
        .error-icon svg {
            width: 26px;
            height: 26px;
        }
        .error-title {
            font-size: 1.8rem;
        }
        .error-message {
            font-size: 0.9rem;
        }
        .error-btn {
            padding: 10px 24px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="container-xxl error-container">
    <div class="error-bg-number">
        <span>404</span>
    </div>

    <div class="error-content">
        <div class="error-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        <h1 class="error-title">
            Страница <br class="d-md-none">не найдена.
        </h1>

        <p class="error-message">
            Возможно, она была удалена, переименована или вы допустили опечатку в адресе.
        </p>

        <a href="{{ url('/') }}" class="error-btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Вернуться на главную
        </a>
    </div>
</div>
@endsection