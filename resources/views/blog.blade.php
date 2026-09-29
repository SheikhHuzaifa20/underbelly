@extends('layouts.main')
@section('content')
    <section class="hero-content about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner_content" data-aos="fade-up" data-aos-duration="1000">

                        <h1 class="banner_title ml2">blogs
                        </h1>

                    </div>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>

    </section>


    <section class="BlogMain">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="BlogTAbs">
                        <div class="HeadingText">
                            <h5>Latest blogs</h5>
                        </div>
                    </div>
                    <div class="BlogArea">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                @foreach ($blog as $b)
                                    <div class="BlogContent">
                                        <div class="blogImage">
                                            <img src="{{ asset($b->image) }}" alt="">
                                        </div>
                                        <div class="Blogtext">
                                            <span>Date: 22 September 2026</span>
                                            <a href="{{ route('blog_detail', $b->id) }}">
                                                <h6>
                                                    {{ $b->title }}
                                                </h6>
                                            </a>
                                            {!! $b->description !!}
                                            <a href="{{ route('blog_detail', $b->id) }}">Continue reading</a>
                                        </div>
                                    </div>
                                @endforeach
                                {{-- <div class="BlogContent">
                                    <div class="blogImage">
                                        <img src="{{ asset('asset/images/image3.jpg') }}" alt="">
                                    </div>
                                    <div class="Blogtext">
                                        <span>Date: 22 September 2026</span>
                                        <a href="class-and-ambition-in-the-novels-wj-reeves.php">
                                            <h6>
                                                The Real Cost of Climbing: Class and Ambition in the Novels of W.J. Reeves
                                            </h6>
                                        </a>
                                        <p>
                                            There is a story America loves to tell about hard work and reward. Study hard,
                                            climb the ladder, and a better life is waiting at the top.
                                        </p>
                                        <a href="class-and-ambition-in-the-novels-wj-reeves.php">Continue reading</a>
                                    </div>
                                </div>
                                <div class="BlogContent">
                                    <div class="blogImage">
                                        <img src="{{ asset('asset/images/image4.jpg') }}" alt="">
                                    </div>
                                    <div class="Blogtext">
                                        <span>Date: 22 September 2026</span>
                                        <a href="the-many-worlds-of-wj-reeves.php">
                                            <h6>
                                                From Manitoulin Island to Brooklyn: The Many Worlds of W.J. Reeves
                                            </h6>
                                        </a>
                                        <p>
                                            Some authors write the same book over and over. W.J. Reeves is not one of them.
                                            Over the course of his writing life, he has moved from tender...
                                        </p>
                                        <a href="the-many-worlds-of-wj-reeves.php">Continue reading</a>
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sideright">
                        <div class="archieve">
                            <h2 class="customh2">CATEGORIES</h2>
                            <ul class="archieve-item">
                                @foreach ($blog as $b)
                                    <li>
                                        <a href="{{ route('blog_detail', $b->id) }}"> {{$b->title}}</a>
                                    </li>
                                @endforeach
                                {{-- <li>
                                    <a href="class-and-ambition-in-the-novels-wj-reeves.php"> The Real Cost of Climbing:
                                        Class and Ambition in the Novels of W.J. Reeves </a>
                                </li>
                                <li>
                                    <a href="the-many-worlds-of-wj-reeves.php"> From Manitoulin Island to Brooklyn: The
                                        Many Worlds of W.J. Reeves </a>
                                </li> --}}
                            </ul>
                        </div>
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
