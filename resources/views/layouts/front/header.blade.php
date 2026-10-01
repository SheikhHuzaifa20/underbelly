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
                        <div class="icons" style="display: flex; align-items: center;">
                            <form action="{{ route('book.shop') }}" method="GET" id="searchForm" style="display: flex; align-items: center; margin-bottom: 0;">
                                <input type="text" name="q" id="searchInput" placeholder="Search products..." style="display: none; padding: 5px 10px; border-radius: 20px; border: 1px solid #ccc; outline: none; margin-right: 10px; background: transparent; color: #fff;">
                                <a href="javascript:void(0)" onclick="toggleSearch()">
                                    <img class="header-icon" src="{{asset('asset/images/search-icon.png')}}" alt="Search">
                                </a>
                            </form>
                            <script>
                                function toggleSearch() {
                                    var input = document.getElementById('searchInput');
                                    if (input.style.display === 'none' || input.style.display === '') {
                                        input.style.display = 'block';
                                        input.focus();
                                    } else {
                                        if (input.value.trim() !== '') {
                                            document.getElementById('searchForm').submit();
                                        } else {
                                            input.style.display = 'none';
                                        }
                                    }
                                }
                            </script>
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
