@extends('layouts.main')
@section('content')
    <section class="hero-content about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner_content" data-aos="fade-up" data-aos-duration="1000">

                        <h1 class="banner_title ml2">{{ $page->page_name }}</h1>

                    </div>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>

    </section>

    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-img">
                        <img class="img-fluid" src="{{ $page->image }}" alt="About The Underbelly book">
                    </div>
                    <div class="about-content" data-aos="fade-up" data-aos-duration="1000">
                        <h2 class="about-title">{{ $page->name }}</h2>
                        {!! $page->content !!}
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
    </style>

    <style>
        .video-content iframe {
            margin-bottom: -7px;
            width: 100%;
            height: 100vh;
        }
    </style>

    <section class="video-section" data-aos="zoom-in" data-aos-duration="1000">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="video-content">
                        <iframe width="560" height="315"
                            src="{{$section[0]->value}}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testinomial">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="testinomial-heading">
                        <h2 class=" animate__animated animate__bounce animate__infinite animate__slow">Testimonials</h2>
                        <p>Readers Reviews & Feedbacks</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme testinomial-slider">
                        @foreach ($testimonial as $t)
                            <div class="item">
                                <a href="{{ !empty($t->link) ? $t->link : 'javascript:void(0)' }}" {!! !empty($t->link) ? 'target="_blank"' : '' !!}>
                                    <div class="testinomial-card">
                                        <h5 style="color: #ff754c;">
                                            @for ($i = 0; $i < $t->rating; $i++)
                                                <i class="fa-solid fa-star"></i>
                                            @endfor
                                        </h5>
                                        {{-- <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars"> --}}
                                        {!! $t->description !!}
                                        <div class="testinomial-profile">
                                            <img class="testinomial-avatar" src="{{ asset($t->image) }}" alt="Angela Moss">
                                            <div class="testinomial-info">
                                                <h6>{{ $t->title }}</h6>
                                                <!--<p>Book Lovers</p>-->
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('css')
    <style>

    </style>
@endsection

@section('js')
    <script type="text/javascript"></script>
@endsection
