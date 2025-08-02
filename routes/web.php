<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserSearchController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentUserViewController;
use App\Http\Controllers\ReadingHistoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ActivityHubController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SkillController;
use App\Models\User;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ClientRegisteredUserController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

Route::view('/', 'welcome')->name('home');

Route::get('/register', function () {
    if (User::where('type', 'admin')->exists()) {
        abort(403, 'Registration is disabled.');
    }
    return view('auth.register');
})->name('register');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::validate($credentials)) {
        $user = User::where('email', $request->email)->first();

        if (! in_array($user->type, ['admin', 'library_staff'])) {
            Log::warning('Unauthorized login attempt', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'type'    => $user->type,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only admins and library staff can log in.',
            ], 403);
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended('admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login');

Route::post('/ClientLogin', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if ($user && $user->confirmed) {
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return response()->json(['status' => 'Login successful!', 'redirect_url' => url('/')]);
        }
    } elseif ($user && ! $user->confirmed) {
        return response()->json(['errors' => ['email' => ['Your account is not activated yet.']]], 422);
    }

    return response()->json(['errors' => ['email' => ['The provided credentials do not match our records.']]], 422);
})->name('client.login');

Route::post('/ClientRegister', [ClientRegisteredUserController::class, 'store'])->name('client.register');

Route::post('/ClientLogout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('status', 'You have been logged out.');
})->name('client.logout');

Route::get('language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar', 'ru'])) {
        Session::put('locale', $locale);
    }
    return Redirect::back();
})->name('language.switcher');

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->type === 'admin') {
        return redirect('/admin/dashboard');
    }
    return redirect('/login');
})->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'publicIndex'])->name('books');
Route::get('/events', [EventController::class, 'publicIndex'])->name('events');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/documents', [DocumentController::class, 'publicIndex'])->name('documents.index');
Route::get('/documents/{document}/download', [DocumentController::class, 'publicDownload'])->name('documents.download');
Route::get('/skills', [SkillController::class, 'publicIndex'])->name('skills.index');

Route::get('/books/{book}/view', [App\Http\Controllers\BookViewController::class, 'view'])->name('books.view');

// Event registration route
Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');

// Skill exchange routes
Route::middleware('auth')->group(function () {
    Route::post('/skills/request', [SkillController::class, 'submitSkillRequest'])->name('skills.request');
    Route::post('/skills/exchange/{skill}', [SkillController::class, 'submitExchangeRequest'])->name('skills.exchange');
    Route::post('/skills/exchange/{exchange}/cancel', [SkillController::class, 'cancelExchange'])->name('exchange.cancel');
    Route::post('/skills/exchange/{exchange}/accept', [SkillController::class, 'acceptExchange'])->name('exchange.accept');
    Route::post('/skills/exchange/{exchange}/reject', [SkillController::class, 'rejectExchange'])->name('exchange.reject');
    Route::post('/skills/exchange/{exchange}/complete', [SkillController::class, 'completeExchange'])->name('exchange.complete');
    Route::post('/skills/exchange/{exchange}/rate', [SkillController::class, 'rateExchange'])->name('exchange.rate');

    // Route for non-admin users to create events
    Route::post('/user/events/store', [EventController::class, 'storeUserEvent'])->name('user.events.store');
});

Route::post('/books/{book}/reserve', [ReservationController::class, 'storeFromBook'])
    ->middleware('auth')
    ->name('reservations.storeFromBook');

Route::get('/my-reservations', [ReservationController::class, 'myReservations'])
    ->middleware('auth')
    ->name('reservations.my');

// Admin routes
Route::prefix('admin')->middleware(['auth', AdminMiddleware::class])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard', ['tabs' => ['Overview', 'Users']]);
    })->name('admin.dashboard');

    Route::get('/', function () {
        return view('dashboard', ['tabs' => ['Overview', 'Users']]);
    })->name('admin.home');

    // Resource routes
    Route::resource('users', UserController::class)->names('admin.users');
    Route::post('users/{user}/confirm', [UserController::class, 'confirm'])->name('admin.users.confirm');
    Route::resource('books', BookController::class)->names('admin.books');
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('activities', ActivityHubController::class)->names('admin.activities');
    Route::resource('skills', SkillController::class)->names('admin.skills');
    Route::resource('borrowings', BorrowingController::class)->names('admin.borrowings');
    Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('admin.borrowings.return');

    Route::resource('reservations', ReservationController::class)->names('admin.reservations');
    Route::post('reservations/{reservation}/approve', [ReservationController::class, 'approve'])->name('admin.reservations.approve');

    // Document management
    Route::resource('documents', DocumentController::class)->names('admin.documents');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('admin.documents.download');

    // Document view statistics
    Route::get('document-views', [DocumentUserViewController::class, 'index'])->name('admin.document-views.index');
    Route::get('document-views/{document}/stats', [DocumentUserViewController::class, 'documentStats'])->name('admin.document-views.stats');

    // User search API endpoints
    Route::get('/admin/api/user-search', [UserSearchController::class, 'search'])->name('api.user-search');
    Route::get('/api/user/{user}', [UserSearchController::class, 'show'])->name('api.user');
    Route::get('reading-history', [ReadingHistoryController::class, 'index'])->name('admin.reading-history.index');

    Route::resource('events', EventController::class)->names('admin.events');
    Route::post('events/{event}/register', [EventController::class, 'register'])->name('admin.events.register');
    Route::delete('events/{event}/unregister', [EventController::class, 'unregister'])->name('admin.events.unregister');
    Route::get('events/{event}/registrations', [EventController::class, 'registrations'])->name('admin.events.registrations');

    Route::post('/events/{event}/register-user', [EventController::class, 'registerUser'])->name('admin.events.register-user');

    Route::get('/skills/user/{id}', [SkillController::class, 'getUserSkills'])->name('admin.skills.user');

    Route::get('/user-skills/{id}', [SkillController::class, 'getUserSkills'])->name('admin.user-skills');

    // Skills and exchanges
    Route::controller(SkillController::class)->group(function () {
        // Skills routes
        Route::get('skills', 'index')->name('admin.skills.index');
        Route::get('skills/create', 'create')->name('admin.skills.create');
        Route::post('skills', 'store')->name('admin.skills.store');
        Route::get('skills/{skill}/edit', 'edit')->name('admin.skills.edit');
        Route::put('skills/{skill}', 'update')->name('admin.skills.update');
        Route::delete('skills/{skill}', 'destroy')->name('admin.skills.destroy');
        Route::get('skills/by-category/{category}', 'byCategory')->name('admin.skills.by-category');
        Route::post('skills/{skill}/confirm', 'confirmSkill')->name('admin.skills.confirm');
        Route::post('skills/{skill}/reject', 'rejectSkill')->name('admin.skills.reject');

        // Skill Exchanges routes
        Route::get('skill-exchanges', 'exchangesIndex')->name('admin.skills.exchanges');
        Route::get('skill-exchanges/create', 'exchangesCreate')->name('admin.skills.exchanges.create');
        Route::post('skill-exchanges', 'exchangesStore')->name('admin.skills.exchanges.store');
        Route::get('skill-exchanges/{exchange}', 'exchangesShow')->name('admin.skills.exchanges.show');
        Route::post('skill-exchanges/{exchange}/accept', 'exchangesAccept')->name('admin.skills.exchanges.accept');
        Route::post('skill-exchanges/{exchange}/complete', 'exchangesComplete')->name('admin.skills.exchanges.complete');
        Route::patch('skill-exchanges/{exchange}/cancel', 'exchangesCancel')->name('admin.skills.exchanges.cancel');
        Route::post('skill-exchanges/{exchange}/rate', 'exchangesRate')->name('admin.skills.exchanges.rate');
        Route::delete('skill-exchanges/{exchange}', 'exchangesDestroy')->name('admin.skills.exchanges.destroy');
    });
});


