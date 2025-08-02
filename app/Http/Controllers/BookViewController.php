<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookUserView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookViewController extends Controller
{
    public function view(Book $book)
    {
        // Check if book is digital
        if ($book->format !== 'digital') {
            return redirect()->route('books.show', $book)
                ->with('error', 'This book is not available for digital viewing.');
        }

        // Record the view if it doesn't exist within time window
        $userId = auth()->id();
        $ipAddress = request()->ip();

        if (!BookUserView::existsWithinTimeWindow($book->id, $userId, $ipAddress)) {
            $userAgent = request()->userAgent();

            $browser = $this->detectBrowser($userAgent);
            $platform = $this->detectPlatform($userAgent);
            $device = $this->detectDevice($userAgent);

            BookUserView::create([
                'book_id' => $book->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
                'user_agent' => $userAgent,
                'platform' => $platform,
                'browser' => $browser,
                'device' => $device,
            ]);
        }

        return view('books.view', compact('book'));
    }

    /**
     * Simple browser detection
     */
    private function detectBrowser($userAgent)
    {
        $browsers = [
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox',
            'Safari' => 'Safari',
            'Edge' => 'Edge',
            'MSIE' => 'Internet Explorer',
            'Opera' => 'Opera',
        ];

        foreach ($browsers as $key => $value) {
            if (stripos($userAgent, $key) !== false) {
                return $value;
            }
        }

        return 'Unknown';
    }

    private function detectPlatform($userAgent)
    {
        $platforms = [
            'Windows' => 'Windows',
            'Mac' => 'MacOS',
            'Linux' => 'Linux',
            'Android' => 'Android',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
        ];

        foreach ($platforms as $key => $value) {
            if (stripos($userAgent, $key) !== false) {
                return $value;
            }
        }

        return 'Unknown';
    }

    private function detectDevice($userAgent)
    {
        if (stripos($userAgent, 'mobile') !== false) {
            return 'Mobile';
        }

        if (stripos($userAgent, 'tablet') !== false || stripos($userAgent, 'iPad') !== false) {
            return 'Tablet';
        }

        return 'Desktop';
    }
}
