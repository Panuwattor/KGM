<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\ConsentLog;
use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Showroom;
use App\Models\Video;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about() { return view('frontend.about'); }

    public function services() { return view('frontend.services'); }

    public function order() { return view('frontend.order'); }

    public function embroideryScreen() { return view('frontend.embroidery-screen'); }

    public function schoolUniforms() { return view('frontend.school-uniforms'); }

    public function showrooms()
    {
        $showrooms = Showroom::active()->get();
        return view('frontend.showrooms', compact('showrooms'));
    }

    public function contact() { return view('frontend.contact'); }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create($request->only('name', 'email', 'phone', 'subject', 'message'));
        return back()->with('success', 'ส่งข้อความเรียบร้อยแล้ว ทีมงานจะติดต่อกลับภายใน 24 ชั่วโมง');
    }

    public function videos()
    {
        $videos = Video::active()->orderBy('sort_order')->latest()->paginate(12);
        return view('frontend.videos', compact('videos'));
    }

    public function news(Request $request)
    {
        $categories = PostCategory::withCount('posts')->get();
        $query = Post::published()->with('postCategory');
        if ($request->filled('category')) {
            $query->whereHas('postCategory', fn($q) => $q->where('slug', $request->category));
        }
        $posts = $query->latest('published_at')->paginate(9)->withQueryString();
        return view('frontend.news', compact('posts', 'categories'));
    }

    public function newsShow(string $slug)
    {
        $post = Post::where('slug', $slug)->published()->with('author', 'postCategory')->firstOrFail();
        $post->increment('view_count');
        $related = Post::published()->where('id', '!=', $post->id)
            ->where('post_category_id', $post->post_category_id)
            ->latest('published_at')->take(3)->get();
        return view('frontend.news-show', compact('post', 'related'));
    }

    public function careers()
    {
        $careers = Career::active()->latest()->get();
        return view('frontend.careers', compact('careers'));
    }

    public function careerShow(Career $career)
    {
        abort_unless($career->is_active, 404);
        return view('frontend.career-show', compact('career'));
    }

    public function careerApply(Request $request, Career $career)
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email',
            'phone'        => 'required|string|max:20',
            'birthdate'    => 'nullable|date',
            'cover_letter' => 'nullable|string|max:2000',
            'resume'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resumePath = $request->file('resume')?->store('resumes', 'local');

        JobApplication::create([
            'career_id'       => $career->id,
            'position_applied'=> $career->title,
            'first_name'      => $request->first_name,
            'last_name'       => $request->last_name,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'birthdate'       => $request->birthdate,
            'cover_letter'    => $request->cover_letter,
            'resume_path'     => $resumePath,
        ]);

        return back()->with('success', 'ส่งใบสมัครเรียบร้อยแล้ว ทีมงานจะติดต่อกลับหากผ่านการคัดเลือก');
    }

    public function sizeGuide() { return view('frontend.size-guide'); }

    public function faq() { return view('frontend.faq'); }

    public function privacyPolicy() { return view('frontend.privacy-policy'); }
    public function terms() { return view('frontend.terms'); }
    public function cookiePolicy() { return view('frontend.cookie-policy'); }

    public function saveConsent(Request $request)
    {
        \App\Models\ConsentLog::create([
            'user_id'            => auth()->id(),
            'session_id'         => session()->getId(),
            'ip_address'         => $request->ip(),
            'analytics_consent'  => $request->boolean('analytics'),
            'marketing_consent'  => $request->boolean('marketing'),
            'necessary_consent'  => true,
        ]);
        session(['cookie_consent' => 'accepted']);
        return response()->json(['success' => true])
            ->cookie('cookie_consent', 'accepted', 60 * 24 * 365);
    }
}
