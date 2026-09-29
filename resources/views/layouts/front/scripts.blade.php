<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="{{asset('asset/js/custom.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@14.0.1/swiper-bundle.min.js"></script>


<!-- ============================================================== -->
<!-- All SCRIPTS AND JS LINKS BELOW  -->
<!-- ============================================================== -->

<!-- Js Files Start -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Front Scripts -->



<script>
    AOS.init();
</script>
<script>
    $(document).ready(function() {

        /* =========================================
           INNER BOOK FRONT / BACK COVER SLIDER
        ========================================= */




        /* =========================================
           MAIN BOOKS SLIDER
        ========================================= */

        $('.books-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: false,

            autoplay: false,
            slideTransition: 'linear',
            autoplayTimeout: 5000,
            autoplaySpeed: 5000,
            autoplayHoverPause: true,

            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });



        /* =========================================
           BOOKS SLIDER 1
        ========================================= */

        $('.books-slider1').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: true,

            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 1
                }
            }
        });


        /* =========================================
           TESTIMONIAL SLIDER
        ========================================= */

        $('.testinomial-slider').owlCarousel({
            loop: true,
            margin: 22,
            nav: false,
            dots: false,

            autoplay: true,
            slideTransition: 'linear',
            autoplayTimeout: 5000,
            autoplaySpeed: 5000,
            autoplayHoverPause: true,

            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 4
                }
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        $('.book-card').each(function() {

            var card = this;

            var thumbSwiper = new Swiper(
                card.querySelector('.mySwiper'), {
                    spaceBetween: 10,
                    slidesPerView: 2,
                    freeMode: true,
                    watchSlidesProgress: true
                }
            );

            new Swiper(
                card.querySelector('.mySwiper2'), {
                    spaceBetween: 10,

                    thumbs: {
                        swiper: thumbSwiper
                    }
                }
            );

        });

    });
</script>




<script>
    function editableContent() {
        $('.editable').each(function() {
            $(this).append(
                '<div class="editable-wrapper"><a href="javascript:" class="edit" title="Edit" onclick="editContent(this)"><i class="far fa-edit"></i></a><a href="javascript:" class="update" title="Update" onclick="updateContent(this)"><i class="far fa-share-square"></i></a></div>'
                );
        });
    }

    function editContent(a) {
        $(a).closest('.editable').attr('contenteditable', true);;
        $(a).closest('.editable-wrapper').attr('contenteditable', false);
        $(a).closest('.editable').focus();
    }

    function updateContent(a) {
        var editableDiv = $(a).closest('.editable');
        var id = $(editableDiv).attr('data-id');
        var keyword = $(editableDiv).attr('data-name');
        var htmlcontent = $(editableDiv).clone(true);
        $(htmlcontent).find('.editable-wrapper').remove();
        sendData(id, keyword, $(htmlcontent).html());
    }

    function sendData(id, keyword, htmlContent) {
        console.log(id);
        console.log(keyword);
        console.log(htmlContent);
        $.ajax({
            url: "update-content",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                keyword: keyword,
                htmlContent: htmlContent,
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                } else {
                    toastr.success(response.error);
                }
            },
        });
    }
</script>

<script type="text/javascript">
    $('#newForm').on('submit', function(e) {
        $('#newsresult').html('');
        e.preventDefault();

        let email = $('#newemail').val();

        $.ajax({
            url: "newsletter-submit",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                newsletter_email: email
            },
            success: function(response) {
                if (response.status) {
                    $('#newsresult').html("<div class='alert alert-success'>" + response.message +
                        "</div>");
                } else {
                    $('#newsresult').html("<div class='alert alert-danger'>" + response.message +
                        "</div>");
                }
            },
        });
    });
</script>


<script type="text/javascript">
    $('#contactform').on('submit', function(e) {
        //alert('hogaya');
        $('#contactformsresult').html('');
        e.preventDefault();

        $.ajax({
            url: "{{ route('contactUsSubmit') }}",
            type: "POST",
            data: $("#contactform").serialize(),

            success: function(response) {
                if (response.status) {
                    document.getElementById("contactform").reset();
                    $('#contactformsresult').html("<div class='alert alert-success'>" + response
                        .message + "</div>");
                } else {
                    $('#contactformsresult').html("<div class='alert alert-danger'>" + response
                        .message + "</div>");
                }
            },
        });
    });
</script>

{{-- @if (!Auth::guest())
@if (Auth::user()->isAdmin())
<script>editableContent();</script>
@endif
@endif --}}

@if (Session::has('message'))
    <script type="text/javascript">
        toastr.success("{{ Session::get('message') }}");
    </script>
@endif
