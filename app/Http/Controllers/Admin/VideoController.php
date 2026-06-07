<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::withTrashed()->orderBy('sort_order')->latest()->paginate(20);
        return view('admin.videos.index', compact('videos'));
    }

    public function create()
    {
        return view('admin.videos.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateVideo($request);

        $youtubeId = Video::extractYoutubeId($request->youtube_url);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'ลิงก์ YouTube ไม่ถูกต้อง กรุณาตรวจสอบ URL อีกครั้ง'])->withInput();
        }

        $data['youtube_id']  = $youtubeId;
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        Video::create($data);
        Cache::forget('home.videos');

        return redirect()->route('admin.videos.index')->with('success', 'เพิ่มวิดีโอเรียบร้อยแล้ว');
    }

    public function edit(Video $video)
    {
        return view('admin.videos.form', compact('video'));
    }

    public function update(Request $request, Video $video)
    {
        $data = $this->validateVideo($request);

        if ($request->youtube_url !== $video->youtube_url) {
            $youtubeId = Video::extractYoutubeId($request->youtube_url);
            if (!$youtubeId) {
                return back()->withErrors(['youtube_url' => 'ลิงก์ YouTube ไม่ถูกต้อง กรุณาตรวจสอบ URL อีกครั้ง'])->withInput();
            }
            $data['youtube_id'] = $youtubeId;
        }

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $video->update($data);
        Cache::forget('home.videos');

        return redirect()->route('admin.videos.index')->with('success', 'อัปเดตวิดีโอเรียบร้อยแล้ว');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        Cache::forget('home.videos');

        return redirect()->route('admin.videos.index')->with('success', 'ลบวิดีโอเรียบร้อยแล้ว');
    }

    private function validateVideo(Request $request): array
    {
        return $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'youtube_url' => 'required|url|max:500',
            'sort_order'  => 'nullable|integer|min:0|max:9999',
        ]);
    }
}
