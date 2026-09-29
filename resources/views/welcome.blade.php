@extends('layouts.main')
@section('content')
    <section class="hero-content">
        <div class="vedio-nammer">
            <video playsinline="playsinline" muted="muted" preload="yes" autoplay="autoplay" loop="loop"
                id="vjs_video_739_html5_api" class="video-js" data-setup='{"autoplay":"any"}'>
                <source src="{{ $banner->video }}" type="video/mp4">
            </video>
        </div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner_content" data-aos="fade-up" data-aos-duration="1000">
                        <p class="eyebrow">{{ $banner->title }} </p>
                        {!! $banner->description !!}
                        <a href="{{ route('about') }}">
                            <button class="explore-button" type="button">Explore More</button>
                        </a>
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
                        <img class="img-fluid" src="{{ asset($page->image) }}" alt="About The Underbelly book">
                    </div>
                    <div class="about-content" data-aos="fade-up" data-aos-duration="1000">
                        <h2 class="about-title">{{ $page->name }}</h2>
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="my-books-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="my-books-title  animate__animated animate__bounce animate__infinite animate__slow">
                        {{ $section[0]->value }}
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme books-slider">
                        @foreach($product as $p)
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
                                        @if($allImages->count() > 0)
                                            @foreach($allImages as $img)
                                                <div class="swiper-slide">
                                                    <img class="img-fluid" src="{{ asset($img->image_path) }}" alt="{{ $p->name }}" />
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="swiper-slide">
                                                <img class="img-fluid" src="{{ asset('asset/images/book1.png') }}" alt="{{ $p->name }}" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div thumbsSlider="" class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        @if($allImages->count() > 0)
                                            @foreach($allImages as $img)
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
                                @if(!empty($p->link))
                                    <a href="{{ $p->link }}" target="_blank"><button type="button">Buy Now</button></a>
                                @else
                                    <button type="button">Buy Now</button>
                                @endif
                            </div>
                        </div>
                        @endforeach
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




    <section class="explore-more">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="my-books-title  animate__animated animate__bounce animate__infinite animate__slow">
                        {{ $section[1]->value }}</h2>
                </div>
            </div>
            <div class="owl-carousel owl-theme books-slider1">
                @foreach ($product as $p)
                <div class="item">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="explore-main">
                                <div class="explore-more-image">
                                    <img class="img-fluid explore-background"
                                        src="{{ asset('asset/images/explore_more.png') }}" alt="A quiet city walkway">
                                </div>
                                <div class="explore-more-book">
                                    <img class="img-fluid" src="{{ $p->primaryImage && $p->primaryImage->image_path ? asset($p->primaryImage->image_path) : asset('asset/images/book1.png') }}"
                                        alt="{{ $p->name }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="explore-more-content" data-aos="fade-left" data-aos-duration="1500">
                                <h2>{{ $p->name }}</h2>
                                <p>{!! $p->description !!}</p>
                                @if(!empty($p->link))
                                    <a href="{{ $p->link }}" target="_blank"><button class="explore-button" type="button">Explore More</button></a>
                                @else
                                    <button class="explore-button" type="button">Explore More</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

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
                        {{-- <img class="img-fluid" src="{{ asset('asset/images/video_section.png') }}" alt=""> --}}
                        <iframe width="560" height="315" src="{{ $section[2]->value }}"
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
                        <h2 class=" animate__animated animate__bounce animate__infinite animate__slow">
                            {{ $section[3]->value }}</h2>
                        <p>{{ $section[4]->value }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme testinomial-slider">
                        @foreach ($testimonial as $t)
                            <div class="item">
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
                            </div>
                        @endforeach
                        {{-- <div class="item">
                            <div class="testinomial-card">
                                <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars">
                                <p>Manitoulin Memories took me straight back to my own childhood summers. Reeves writes
                                    about memory and loss with such tenderness that I found myself smiling and aching at the
                                    same time. A quiet, beautiful collection that lingers long after you finish.</p>
                                <div class="testinomial-profile">
                                    <img class="testinomial-avatar" src="{{ asset('asset/images/testinomial-img.png') }}"
                                        alt="Angela Moss">
                                    <div class="testinomial-info">
                                        <h6>Ottoline Farraday</h6>
                                        <!--<p>Book Lovers</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testinomial-card">
                                <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars">
                                <p>Bucceroni is one of the most entertaining characters in crime fiction. This book is fast,
                                    funny, and full of surprises. Every time I thought I knew where it was going, Reeves
                                    pulled the rug out. Pure Brooklyn from start to finish.</p>
                                <div class="testinomial-profile">
                                    <img class="testinomial-avatar" src="{{ asset('asset/images/testinomial-img.png') }}"
                                        alt="Angela Moss">
                                    <div class="testinomial-info">
                                        <h6>Thaddeus Okonkwo</h6>
                                        <!--<p>Book Lovers</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testinomial-card">
                                <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars">
                                <p>The Blonde with Half a Face is a gripping neo-noir that grabbed me from the first
                                    chapter. Candy is a fierce, complicated heroine, and her fight for revenge is impossible
                                    to look away from. Reeves balances grit and heart in a way few writers manage.</p>
                                <div class="testinomial-profile">
                                    <img class="testinomial-avatar" src="{{ asset('asset/images/testinomial-img.png') }}"
                                        alt="Angela Moss">
                                    <div class="testinomial-info">
                                        <h6>Seraphina Delacroix</h6>
                                        <!--<p>Book Lovers</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testinomial-card">
                                <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars">
                                <p>A hard-boiled thriller with heart. Richie's grief gives the story real stakes, and the
                                    action never lets up. Reeves blends violence, dark humor, and genuine emotion in a way
                                    that kept me hooked from the first page.</p>
                                <div class="testinomial-profile">
                                    <img class="testinomial-avatar" src="{{ asset('asset/images/testinomial-img.png') }}"
                                        alt="Angela Moss">
                                    <div class="testinomial-info">
                                        <h6>Alistair Brenner</h6>
                                        <!--<p>Book Lovers</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testinomial-card">
                                <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars">
                                <p>The suspense had me flipping pages late into the night. Watching Richie become the prime
                                    suspect in his own investigation was brilliant. Add Candy back into the mix, and you
                                    have a thriller that truly delivers on every level.</p>
                                <div class="testinomial-profile">
                                    <img class="testinomial-avatar" src="{{ asset('asset/images/testinomial-img.png') }}"
                                        alt="Angela Moss">
                                    <div class="testinomial-info">
                                        <h6>Dashiell Voronova</h6>
                                        <!--<p>Book Lovers</p>-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <div class="testinomial-card">
                                <img class="testinomial-stars" src="{{ asset('asset/images/star-img.png') }}"
                                    alt="5 stars">
                                <p>As a parent, Why NOT Go to College? made me stop and reconsider everything I assumed
                                    about college. Reeves is provocative, funny, and refreshingly direct. It does not
                                    lecture. It challenges. I have already passed my copy to three friends.</p>
                                <div class="testinomial-profile">
                                    <img class="testinomial-avatar" src="{{ asset('asset/images/testinomial-img.png') }}"
                                        alt="Angela Moss">
                                    <div class="testinomial-info">
                                        <h6>Cressida Halloran</h6>
                                        <!--<p>Book Lovers</p>-->
                                    </div>
                                </div>
                            </div>
                        </div> --}}
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
