<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/

Route::get('/', [Site\HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [Site\PageController::class, 'about'])->name('about');
Route::get('/equipe', [Site\PageController::class, 'team'])->name('team');
Route::get('/temoignages', [Site\PageController::class, 'testimonials'])->name('testimonials');
Route::get('/faq', [Site\PageController::class, 'faq'])->name('faq');

Route::get('/causes', [Site\CauseController::class, 'index'])->name('causes.index');
Route::get('/causes/{cause:slug}', [Site\CauseController::class, 'show'])->name('causes.show');

Route::get('/faire-un-don', [Site\DonationController::class, 'create'])->name('donations.create');
Route::post('/faire-un-don', [Site\DonationController::class, 'store'])->name('donations.store');
Route::get('/don/{donation:reference}/merci', [Site\DonationController::class, 'thanks'])->name('donations.thanks');

Route::get('/evenements', [Site\EventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event:slug}', [Site\EventController::class, 'show'])->name('events.show');
Route::post('/evenements/{event:slug}/inscription', [Site\EventController::class, 'register'])->name('events.register');

Route::get('/actualites', [Site\PostController::class, 'index'])->name('posts.index');
Route::get('/actualites/{post:slug}', [Site\PostController::class, 'show'])->name('posts.show');
Route::post('/actualites/{post:slug}/commentaires', [Site\PostController::class, 'comment'])->name('posts.comment');

Route::get('/contact', [Site\ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [Site\ContactController::class, 'store'])->name('contact.store');

Route::get('/devenir-benevole', [Site\VolunteerController::class, 'create'])->name('volunteers.create');
Route::post('/devenir-benevole', [Site\VolunteerController::class, 'store'])->name('volunteers.store');

Route::post('/newsletter', [Site\SubscriberController::class, 'store'])->name('newsletter.store');

Route::get('/page/{page:slug}', [Site\PageController::class, 'show'])->name('pages.show');

Route::get('/sitemap.xml', [Site\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [Site\SeoController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Administration
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('causes', Admin\CauseController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('posts', Admin\PostController::class)->except('show');
    Route::resource('events', Admin\EventController::class)->except('show');
    Route::resource('team', Admin\TeamMemberController::class)->except('show')->parameters(['team' => 'member']);
    Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
    Route::resource('faqs', Admin\FaqController::class)->except('show');
    Route::resource('sliders', Admin\SliderController::class)->except('show');
    Route::resource('partners', Admin\PartnerController::class)->except('show');
    Route::resource('pages', Admin\PageController::class)->except('show');

    Route::resource('donations', Admin\DonationController::class);
    Route::patch('donations/{donation}/status', [Admin\DonationController::class, 'updateStatus'])->name('donations.status');
    Route::get('donations-export', [Admin\DonationController::class, 'export'])->name('donations.export');

    Route::get('registrations', [Admin\RegistrationController::class, 'index'])->name('registrations.index');
    Route::patch('registrations/{registration}', [Admin\RegistrationController::class, 'update'])->name('registrations.update');
    Route::delete('registrations/{registration}', [Admin\RegistrationController::class, 'destroy'])->name('registrations.destroy');

    Route::get('comments', [Admin\CommentController::class, 'index'])->name('comments.index');
    Route::patch('comments/{comment}', [Admin\CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [Admin\CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('volunteers', [Admin\VolunteerController::class, 'index'])->name('volunteers.index');
    Route::get('volunteers/{volunteer}', [Admin\VolunteerController::class, 'show'])->name('volunteers.show');
    Route::patch('volunteers/{volunteer}', [Admin\VolunteerController::class, 'update'])->name('volunteers.update');
    Route::delete('volunteers/{volunteer}', [Admin\VolunteerController::class, 'destroy'])->name('volunteers.destroy');

    Route::get('messages', [Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [Admin\MessageController::class, 'show'])->name('messages.show');
    Route::patch('messages/{message}', [Admin\MessageController::class, 'update'])->name('messages.update');
    Route::delete('messages/{message}', [Admin\MessageController::class, 'destroy'])->name('messages.destroy');

    Route::get('subscribers', [Admin\SubscriberController::class, 'index'])->name('subscribers.index');
    Route::delete('subscribers/{subscriber}', [Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy');
    Route::get('subscribers-export', [Admin\SubscriberController::class, 'export'])->name('subscribers.export');

    Route::middleware('staff:admin')->group(function () {
        Route::resource('users', Admin\UserController::class)->except('show');
        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

/*
 * Cible de redirection après connexion : l'équipe arrive dans l'administration,
 * tout autre compte est renvoyé vers le site public plutôt que sur une erreur 403.
 */
Route::get('dashboard', function () {
    return redirect()->to(
        optional(request()->user())->isStaff() === false ? route('home') : url('/admin')
    );
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| Compte connecté
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
