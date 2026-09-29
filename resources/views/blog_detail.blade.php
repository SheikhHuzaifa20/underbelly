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
                    <div class="blogImage">
                        <img src="{{asset($blog->image)}}" alt="">
                    </div>
                    <div class="Blogtext" style="padding-left:0;">
                        <h2>
                            {{$blog->title}}
                        </h2>
                        {!! $blog->long_text !!}

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sideright">
                        <div class="archieve">
                            <h2 class="customh2">CATEGORIES</h2>
                            <ul class="archieve-item">
                                @foreach ($cat as $b)
                                    <li>
                                        <a href="{{ route('blog_detail', $b->id) }}"> {{$b->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!--<div class="populartag">-->
                        <!--    <h6>POPULAR TAG'S</h6>-->
                        <!--    <ul>-->
                        <!--       <li>-->
                        <!--          <a href="#" class="btn btnTag active">Lorem </a>-->
                        <!--       </li>-->
                        <!--        <li>-->
                        <!--          <a href="#" class="btn btnTag">Lipsum </a>-->
                        <!--       </li>-->
                        <!--        <li>-->
                        <!--          <a href="#" class="btn btnTag">Dolor </a>-->
                        <!--       </li>-->
                        <!--    </ul>-->
                        <!--    <ul>-->
                        <!--       <li>-->
                        <!--          <a href="#" class="btn btnTag">Sit amot die </a>-->
                        <!--       </li>-->
                        <!--        <li>-->
                        <!--          <a href="#" class="btn btnTag">Lipsum </a>-->
                        <!--       </li>-->
                        <!--        <li>-->
                        <!--          <a href="#" class="btn btnTag">Dolor </a>-->
                        <!--       </li>-->
                        <!--    </ul>-->
                        <!--    <ul> -->
                        <!--        <li>-->
                        <!--          <a href="#" class="btn btnTag">Lipsum </a>-->
                        <!--       </li>-->
                        <!--        <li>-->
                        <!--          <a href="#" class="btn btnTag">Dolor </a>-->
                        <!--       </li>-->
                        <!--    </ul>-->
                        <!-- </div> -->
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
