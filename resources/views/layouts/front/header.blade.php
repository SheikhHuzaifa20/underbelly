@php
    $logo = DB::table('imagetable')->where('id', 2)->first();
@endphp
<style>
    a:hover {
        color: #fff;
        text-decoration: none;
    }
</style>
<main class="hero">
    <header class="site-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <a class="logo" href="{{route('home')}}"><img src="{{ asset($logo->img_path) }}"></a>
                </div>
                <div class="col-lg-6">
                    <div class="header-right">
                        <nav class="nav" aria-label="Primary navigation">
                            <a href="{{route('home')}}">Home</a>
                            <a href="{{route('book.shop')}}">Shop</a>
                            <a href="{{ route('about') }}">About Us</a>
                            <a href="{{ route('blog') }}">Blog</a>
                            <!--<a href="contact.php">Contact Us</a>-->
                        </nav>
                        <div class="icons">
                            {{-- <a href="#">
                                    <img class="header-icon" src="{{asset('asset/images/search-icon.png')}}" alt="">
                                </a> --}}
                            {{-- <a href="#">
                                    <img class="header-icon" src="{{asset('asset/images/cart-img.png')}}" alt="">
                                </a> --}}
                            {{-- <a href="#">
                                    <img class="header-icon" src="{{asset('asset/images/account-img.png')}}" alt="">
                                </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
