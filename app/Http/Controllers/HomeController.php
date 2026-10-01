<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\inquiry;
use App\schedule;
use App\newsletter;
use App\post;
use App\banner;
use App\imagetable;
use DB;
use Mail;
use View;
use Session;
use App\Http\Helpers\UserSystemInfoHelper;
use App\Http\Traits\HelperTrait;
use Auth;
use App\Profile;
use App\Page;
use Image;

class HomeController extends Controller
{
    use HelperTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // use Helper;

    public function __construct()
    {
        //$this->middleware('auth');

        $logo = imagetable::select('img_path')
            ->where('table_name', '=', 'logo')
            ->first();

        $favicon = imagetable::select('img_path')
            ->where('table_name', '=', 'favicon')
            ->first();

        View()->share('logo', $logo);
        View()->share('favicon', $favicon);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = DB::table('pages')->where('id', 1)->first();
        $section = DB::table('sections')->where('page_id', 1)->get();
        $banner = DB::table('banners')->where('status', 1)->where('id', 1)->first();
        $testimonial = DB::table('testimonial')->where('status', 1)->get();
        $product = \App\Models\Product::with(['images', 'primaryImage', 'galleryImages'])->where('status', 1)->get();

        return view('welcome', compact('page', 'banner', 'section', 'testimonial','product'));
    }

    public function about()
    {
        $page = DB::table('pages')->where('id', 2)->first();
        $section = DB::table('sections')->where('page_id', 2)->get();
        $testimonial = DB::table('testimonial')->where('status', 1)->get();

        return view('about', compact('page', 'section', 'testimonial'));
    }

    public function blog()
    {
        $page = DB::table('pages')->where('id', 3)->first();
        $blog = DB::table('blog')->where('status', 1)->get();
        $section = DB::table('sections')->where('page_id', 3)->get();

        return view('blog', compact('page', 'section', 'blog'));
    }

    public function blog_detail($id)
    {
        $blog = DB::table('blog')->where('id', $id)->where('status', 1)->first();
        $cat = DB::table('blog')->where('status', 1)->get();

        return view('blog_detail', compact('blog', 'cat'));
    }

    public function shop(Request $request)
    {
        $query = \App\Models\Product::with(['images', 'primaryImage', 'galleryImages'])->where('status', 1);
        if ($request->has('q') && $request->q != '') {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        $products = $query->get();
        return view('shop', compact('products'));
    }

    public function help()
    {
        $page = DB::table('pages')->where('id', 5)->first();
        
        return view('help' , compact('page'));
    }
    public function terms_and_conditions()
    {
        $page = DB::table('pages')->where('id', 4)->first();
        
        return view('terms_and_conditions' , compact('page'));
    }
    public function privacy_policy()
    {
        $page = DB::table('pages')->where('id', 3)->first();
        
        return view('privacy_policy' , compact('page'));
    }


    public function careerSubmit(Request $request)
    {


        inquiry::create($request->all());


        return response()->json(['message' => 'Thank you for contacting us. We will get back to you asap', 'status' => true]);
        return back();
    }

    public function newsletterSubmit(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'newsletter_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Please provide a valid email address.', 'status' => false]);
        }

        $email = $request->newsletter_email;
        $is_email = \App\Models\Newsletter::where('newsletter_email', $email)->count();

        if ($is_email == 0) {
            $newsletter = new \App\Models\Newsletter();
            $newsletter->newsletter_email = $email;
            $newsletter->save();

            // Send confirmation email to subscriber
            try {
                \Mail::to($email)->send(new \App\Mail\NewsletterUserMail($email));
            } catch (\Exception $e) {
                \Log::error('Newsletter user email failed: ' . $e->getMessage());
            }

            // Send notification email to admin
            try {
                $adminEmail = config('mail.admin_email') ?? env('MAIL_TO_ADMIN', 'underbelly@gmail.com');
                if ($adminEmail) {
                    \Mail::to($adminEmail)->send(new \App\Mail\NewsletterAdminMail($email));
                }
            } catch (\Exception $e) {
                \Log::error('Newsletter admin email failed: ' . $e->getMessage());
            }

            return response()->json(['message' => 'Thank you for subscribing! A confirmation email has been sent.', 'status' => true]);
        } else {
            return response()->json(['message' => 'This email is already subscribed to our newsletter.', 'status' => false]);
        }
    }

    public function updateContent(Request $request)
    {
        $id = $request->input('id');
        $keyword = $request->input('keyword');
        $htmlContent = $request->input('htmlContent');
        if ($keyword == 'page') {
            $update = DB::table('pages')
                ->where('id', $id)
                ->update(array('content' => $htmlContent));

            if ($update) {
                return response()->json(['message' => 'Content Updated Successfully', 'status' => true]);
            } else {
                return response()->json(['message' => 'Error Occurred', 'status' => false]);
            }
        } else if ($keyword == 'section') {
            $update = DB::table('section')
                ->where('id', $id)
                ->update(array('value' => $htmlContent));
            if ($update) {
                return response()->json(['message' => 'Content Updated Successfully', 'status' => true]);
            } else {
                return response()->json(['message' => 'Error Occurred', 'status' => false]);
            }
        }
    }
}
