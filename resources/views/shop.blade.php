@extends('layouts.main')
@section('content')
    <section class="hero-content about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner_content" data-aos="fade-up" data-aos-duration="1000">

                        <h1 class="banner_title ml2">Shop</h1>

                    </div>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>

    </section>

    <section class="my-books-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="my-books-title  animate__animated animate__bounce animate__infinite animate__slow">My Books
                    </h2>
                </div>
            </div>
            <div class="row">
                {{-- <div class="col-lg-12">
                    <div class="owl-carousel owl-theme books-slider">
                        <div class="item">
                            <div class="book-card">
                                <div class="swiper mySwiper2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img src="{{ asset('asset/images/book1.png') }}" />
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="{{ asset('asset/images/backcover-03.png') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--<img class="img-fluid" src="{{ asset('asset/images/book1.png') }}" alt="The Underbelly">-->
                            </div>
                            <div class="book-content">
                                <h3>The Underbelly: A Proletariat Novel</h3>
                                <button type="button">Buy Now</button>
                            </div>
                        </div>
                        <div class="item">
                            <div class="book-card">
                                <div class="swiper mySwiper2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img class="img-fluid" src="{{ asset('asset/images/book02.png') }}"
                                                alt="The Underbelly">
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="{{ asset('asset/images/backcover-01.png') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="book-content">
                                <h3>Manitoulin Memories</h3>
                                <button type="button">Buy Now</button>
                            </div>
                        </div>


                        <div class="item">
                            <div class="book-card">
                                <div class="swiper mySwiper2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img class="img-fluid" src="{{ asset('asset/images/book05.png') }}"
                                                alt="The Underbelly">
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="{{ asset('asset/images/backcover-02.png') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="book-content">
                                <h3>The Blonde with Half a Face</h3>
                                <button type="button">Buy Now</button>
                            </div>
                        </div>

                        <div class="item">
                            <div class="book-card">
                                <div class="swiper mySwiper2">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img class="img-fluid" src="{{ asset('asset/images/book04.png') }}"
                                                alt="The Underbelly">
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="{{ asset('asset/images/backcover-04.png') }}" />
                                        </div>
                                    </div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                        <div class="swiper-slide">
                                            <span class="dots-click"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="book-content">
                                <h3>Betrayal in Brooklyn</h3>
                                <button type="button">Buy Now</button>
                            </div>
                        </div>


                        <!--<div class="item">-->
                        <!--    <div class="book-card">-->
                        <!--        <img class="img-fluid" src="{{ asset('asset/images/book02.png') }}" alt="The Underbelly">-->
                        <!--    </div>-->
                        <!--    <div class="book-content">-->
                        <!--        <h3>Banished to Brooklyn</h3>-->
                        <!--        <button type="button">Buy Now</button>-->
                        <!--    </div>-->
                        <!--</div>-->


                        <!--<div class="item">-->
                        <!--    <div class="book-card">-->
                        <!--        <img class="img-fluid" src="{{ asset('asset/images/book1.png') }}" alt="The Underbelly">-->
                        <!--    </div>-->
                        <!--    <div class="book-content">-->
                        <!--        <h3>Why NOT Go to College?</h3>-->
                        <!--        <button type="button">Buy Now</button>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div> --}}

                <div class="owl-carousel owl-theme books-slider">
                    @foreach ($products as $p)
                        @php
                            $allImages = collect();
                            if ($p->primaryImage) {
                                $allImages->push($p->primaryImage);
                            }
                            if ($p->galleryImages && $p->galleryImages->count() > 0) {
                                foreach ($p->galleryImages as $gImg) {
                                    $allImages->push($gImg);
                                }
                            }
                            if ($allImages->isEmpty() && isset($p->images) && count($p->images) > 0) {
                                $allImages = collect($p->images);
                            }
                        @endphp
                        <div class="item">
                            <div class="book-card">
                                <div class="swiper mySwiper2">
                                    <div class="swiper-wrapper">
                                        @if ($allImages->count() > 0)
                                            @foreach ($allImages as $img)
                                                <div class="swiper-slide">
                                                    <img class="img-fluid" src="{{ asset($img->image_path) }}"
                                                        alt="{{ $p->name }}" />
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="swiper-slide">
                                                <img class="img-fluid" src="{{ asset('asset/images/book1.png') }}"
                                                    alt="{{ $p->name }}" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        @if ($allImages->count() > 0)
                                            @foreach ($allImages as $img)
                                                <div class="swiper-slide">
                                                    <span class="dots-click"></span>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="swiper-slide">
                                                <span class="dots-click"></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="book-content">
                                <h3>{{ $p->name }}</h3>
                                @if (!empty($p->link))
                                    <a href="{{ $p->link }}" target="_blank"><button type="button">Buy
                                            Now</button></a>
                                @else
                                    <button type="button">Buy Now</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    {{-- <div class="item">
                        <div class="book-card">
                            <div class="swiper mySwiper2">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img class="img-fluid" src="{{asset('asset/images/book02.png')}}" alt="The Underbelly">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="{{asset('asset/images/backcover-01.png')}}" />
                                    </div>
                                </div>
                            </div>
                            <div thumbsSlider="" class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <span class="dots-click"></span>
                                    </div>
                                    <div class="swiper-slide">
                                        <span class="dots-click"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="book-content">
                            <h3>Manitoulin Memories</h3>
                            <button type="button">Buy Now</button>
                        </div>
                    </div>


                    <div class="item">
                        <div class="book-card">
                            <div class="swiper mySwiper2">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img class="img-fluid" src="{{asset('asset/images/book05.png')}}" alt="The Underbelly">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="{{asset('asset/images/backcover-02.png')}}" />
                                    </div>
                                </div>
                            </div>
                            <div thumbsSlider="" class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <span class="dots-click"></span>
                                    </div>
                                    <div class="swiper-slide">
                                        <span class="dots-click"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="book-content">
                            <h3>The Blonde with Half a Face</h3>
                            <button type="button">Buy Now</button>
                        </div>
                    </div>

                    <div class="item">
                        <div class="book-card">
                            <div class="swiper mySwiper2">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img class="img-fluid" src="{{asset('asset/images/book04.png')}}" alt="The Underbelly">
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="{{asset('asset/images/backcover-04.png')}}" />
                                    </div>
                                </div>
                            </div>
                            <div thumbsSlider="" class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <span class="dots-click"></span>
                                    </div>
                                    <div class="swiper-slide">
                                        <span class="dots-click"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="book-content">
                            <h3>Betrayal in Brooklyn</h3>
                            <button type="button">Buy Now</button>
                        </div>
                    </div> --}}


                    <!--<div class="item">-->
                    <!--    <div class="book-card">-->
                    <!--        <img class="img-fluid" src="{{ asset('asset/images/book02.png') }}" alt="The Underbelly">-->
                    <!--    </div>-->
                    <!--    <div class="book-content">-->
                    <!--        <h3>Banished to Brooklyn</h3>-->
                    <!--        <button type="button">Buy Now</button>-->
                    <!--    </div>-->
                    <!--</div>-->


                    <!--<div class="item">-->
                    <!--    <div class="book-card">-->
                    <!--        <img class="img-fluid" src="{{ asset('asset/images/book1.png') }}" alt="The Underbelly">-->
                    <!--    </div>-->
                    <!--    <div class="book-content">-->
                    <!--        <h3>Why NOT Go to College?</h3>-->
                    <!--        <button type="button">Buy Now</button>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
        </div>
    </section>
    <style>
        section.my-books-section.mahs {
            padding-top: 70px;
        }

        .video-content img {
            width: 100vw;
        }

        .owl-carousel.owl-theme.books-slider.owl-loaded.owl-drag {
            margin: 4rem 0px;
        }

        .my-books-section {
            padding: 56px 0 20px;
        }
    </style>
@endsection
@section('css')
    <style>

    </style>
@endsection

@section('js')
    <script type="text/javascript"></script>
@endsection
