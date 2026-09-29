@php
    $copyright = DB::table('m_flag')->where('id', 1)->first();
    $facebook = DB::table('m_flag')->where('id', 2)->first();
    $instagram = DB::table('m_flag')->where('id', 3)->first();
@endphp
<section class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-content">
                    <div class="get-know">
                        <h2>Subscribe To Our Newsletter</h2>
                        <p class="footer-intro">Get exclusive updates, behind-the-scenes insights, and early access to
                            new releases.</p>
                        <form class="footer-form" id="newsletterForm" method="POST" style="display: flex; flex-direction: column; align-items: center; gap: 20px;">
                            @csrf
                            <input type="email" name="newsletter_email" placeholder="Your email address" aria-label="Your email address" required style="width: 100%;">
                            <button type="submit" class="explore-button" id="subscribeBtn" style="border: none; padding: 10px 30px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                                <span class="btn-text">Subscribe</span>
                                <i class="fa fa-spinner fa-spin" id="subscribeLoader" style="display: none;"></i>
                            </button>
                        </form>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#newsletterForm').on('submit', function(e) {
                                    e.preventDefault();
                                    let form = $(this);
                                    let email = form.find('input[name="newsletter_email"]').val();
                                    let submitBtn = $('#subscribeBtn');
                                    let loader = $('#subscribeLoader');
                                    
                                    // Show loader and disable button
                                    submitBtn.prop('disabled', true);
                                    loader.show();

                                    $.ajax({
                                        url: "{{ route('newsletterSubmit') }}",
                                        type: "POST",
                                        data: form.serialize(),
                                        success: function(response) {
                                            submitBtn.prop('disabled', false);
                                            loader.hide();
                                            
                                            if (response.status) {
                                                if (typeof toastr !== 'undefined') toastr.success(response.message);
                                                form[0].reset();
                                            } else {
                                                if (typeof toastr !== 'undefined') toastr.error(response.message);
                                            }
                                        },
                                        error: function(xhr) {
                                            submitBtn.prop('disabled', false);
                                            loader.hide();
                                            
                                            let msg = 'An error occurred. Please try again.';
                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                msg = xhr.responseJSON.message;
                                            }
                                            if (typeof toastr !== 'undefined') toastr.error(msg);
                                        }
                                    });
                                });
                            });
                        </script>
                        <p class="footer-policy">By signing up you agree with our <a href="{{route('privacy_policy')}}">Privacy Policy.</a>
                        </p>
                    </div>
                    <div class="footer-social">
                        <a href="{{ $facebook->flag_value }}" aria-label="Facebook"><i
                                class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{ $instagram->flag_value }}" aria-label="Instagram"><i
                                class="fa-brands fa-instagram"></i></a>
                    </div>
                    <div class="footer-links">
                        <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
                        <span>|</span>
                        <a href="{{ route('terms_and_conditions') }}">Terms &amp; Conditions</a>
                        <span>|</span>
                        <a href="{{ route('help') }}">Help</a>
                    </div>
                    <p class="footer-copyright">{{ $copyright->flag_value }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
</main>



<!-- ONE Popup -->
<div class="modal fade" id="backCoverModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0"> <button type="button"
                class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal"> </button> <img
                id="backCoverImage" src="" class="img-fluid rounded" alt="Back Cover"> </div>
    </div>
</div>
