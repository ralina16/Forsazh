@extends('layouts.admin')

@section('title', 'Админ-панель')

@section('content')
<section class="container-xxl section">

    <h2 class="text-center my-5 admin-title">Активность AI-чата «Смарти»</h2>

    <div class="chart-wrapper">
        <canvas id="myChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chatChart['labels']),
                datasets: [
                    {
                        label: 'Сообщения пользователей',
                        data: @json($chatChart['userMessages']),
                        borderWidth: 1,
                        borderColor: '#4071CB',
                        backgroundColor: '#4071CB',
                        hoverBackgroundColor: '#2C5AA0',
                        hoverBorderColor: '#2C5AA0',
                        borderRadius: 9,
                        borderSkipped: 'bottom'
                    },
                    {
                        label: 'Ответы AI (Смарти)',
                        data: @json($chatChart['aiResponses']),
                        borderWidth: 1,
                        borderColor: '#7BA3E8',
                        backgroundColor: '#7BA3E8',
                        hoverBackgroundColor: '#5A8DE8',
                        hoverBorderColor: '#5A8DE8',
                        borderRadius: 9,
                        borderSkipped: 'bottom'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#666',
                            font: { size: 13 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#666',
                            font: { size: 13 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#333',
                            font: { size: 14 },
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            }
        });
    </script>

</section>

<style>
    .chart-wrapper {
        width: 100%;
        height: 620px;
        position: relative;
    }

    @media (max-width: 768px) {
        .chart-wrapper {
            height: 320px;
        }

        .admin-title {
            font-size: 1.4rem !important;
            margin-top: 2rem !important;
            margin-bottom: 2rem !important;
        }
    }
</style>

<section class="container-xxl section">
    <div class="row g-4">
      <!-- Автомобили -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <div class="card-number">1</div>
            <h5 class="card-title">Автомобили</h5>
            <p class="card-text flex-grow-1">
              Управляйте каталогом автомобилей: добавляйте, редактируйте и удаляйте модели, указывайте характеристики.
            </p>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-primary mt-auto">Перейти</a>
          </div>
        </div>
      </div>

      <!-- Пользователи -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <div class="card-number">2</div>
            <h5 class="card-title">Пользователи</h5>
            <p class="card-text flex-grow-1">
              Администрирование учётных записей: управление ролями, блокировка, редактирование профилей и контроль
              активности.
            </p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary mt-auto">Перейти</a>
          </div>
        </div>
      </div>

      <!-- Конфигурации -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100">
          <div class="card-body d-flex flex-column">
            <div class="card-number">3</div>
            <h5 class="card-title">Конфигурации</h5>
            <p class="card-text flex-grow-1">
              Настройка параметров сайта: контактная информация, баннеры, социальные сети и общие настройки
              платформы.
            </p>
            <a href="{{ route('admin.car-configs.index') }}" class="btn btn-primary mt-auto">Перейти</a>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection