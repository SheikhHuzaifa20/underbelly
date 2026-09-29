@extends('layouts.main')
@section('content')
    <section class="hero-content about">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="banner_content" data-aos="fade-up" data-aos-duration="1000">

                        <h1 class="banner_title ml2">{{$page->name}}</h1>

                    </div>
                </div>
            </div>
        </div>

    </section>


    <section class="privacy-pg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="privacy-content">
                        {!! $page->content !!}
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
