@extends('layouts.app')

@section('title', 'О нас')

@section('content')
    <!-- О нас -->
    <section class="py-5" data-aos="fade-up" data-aos-delay="100">
        <div class="container-xxl">
            <h2 class="section-title">О НАС</h2>

            <div class="row align-items-center my-5">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="about-text">
                        <div class="about-text-1 mb-3">
                            <p>
                                Автосалон «Форсаж» в Казани — это надёжный партнёр, который заботится о каждом клиенте. Мы —
                                официальный
                                дилерский центр с современной площадкой, где представлены новые и проверенные подержанные
                                автомобили
                                ведущих марок.
                            </p>
                        </div>
                        <div class="about-text-2">
                            <p>
                                Для нас важно, чтобы каждый клиент чувствовал заботу — от первого звонка до передачи ключей
                                и после. В
                                «Форсаже» вы не просто покупаете машину — вы получаете уверенность, комфорт и настоящее
                                удовольствие от
                                покупки.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12 text-center p-0">
                    <div class="about-image">
                        <img src="{{ asset('assets/images/about/image.svg') }}" alt="Автомобиль Porsche"
                            class="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Наша команда -->
    <section class="team-section" data-aos="fade-up" data-aos-delay="500">
        <div class="container-xxl">
            <h2 class="section-title">НАША КОМАНДА</h2>
            <div class="team-carousel-wrapper">
                <div class="row g-4 team-row">
                    <div class="col-md-4 col-sm-6 team-col">
                        <div class="team-member">
                            <img src="{{ asset('assets/images/about/1.png') }}" alt="Борисов Валентин" class="team-img" />
                            <h4 class="team-name">Борисов Валентин</h4>
                            <p class="team-role">Генеральный директор</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 team-col">
                        <div class="team-member">
                            <img src="{{ asset('assets/images/about/2.png') }}" alt="Паучкова Валентина" class="team-img" />
                            <h4 class="team-name">Паучкова Валентина</h4>
                            <p class="team-role">Коммерческий директор</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 team-col">
                        <div class="team-member">
                            <img src="{{ asset('assets/images/about/3.jpg') }}" alt="Яковлев Кирилл" class="team-img" />
                            <h4 class="team-name">Яковлев Кирилл</h4>
                            <p class="team-role">Технический директор</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-xxl mb-5">
        @include('partials.footer')
    </section>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-out-cubic',
            once: true
        });
    </script>
@endpush
