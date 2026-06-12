<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'customer'])->latest();

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }

        if ($request->filled('search')) {
            $search = '%'.$request->search.'%';
            $query->where(fn($q) => $q
                ->whereHas('product', fn($q) => $q->where('name', 'like', $search))
                ->orWhereHas('customer', fn($q) => $q->where('name', 'like', $search))
            );
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(20)->withQueryString();

        $statusCounts = [
            'pending'  => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'statusCounts'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:255',
            'body'   => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'title'  => $request->title,
            'body'   => $request->body,
        ]);

        return back()->with('success', 'แก้ไขรีวิวแล้ว');
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'อนุมัติรีวิวแล้ว');
    }

    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        return back()->with('success', 'ปฏิเสธรีวิวแล้ว');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'ลบรีวิวแล้ว');
    }
}
