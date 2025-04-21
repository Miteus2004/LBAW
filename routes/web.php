<?php

use App\Http\Controllers\AnswerCommentController;
use App\Http\Controllers\QuestionCommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ContactController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Home Route
Route::get('/', [QuestionController::class, 'index_home'])->name('questions.index_home');

// Authentication Routes (Module M01)
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // R101
    Route::post('/login', [LoginController::class, 'authenticate']); // R102

    // Registration Routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register'); // R104
    Route::post('/register', [RegisterController::class, 'register']); // R105

    // Password Recovery Routes
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth'); // R103

// Public Profile Routes
Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/{id}/questions', [UserController::class, 'myQuestions'])->name('users.questions'); // R207
    Route::get('/{id}/answers', [UserController::class, 'myAnswers'])->name('users.answers'); // R208
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // R203
    Route::post('/{id}', [UserController::class, 'update'])->name('users.update'); // R202
    Route::get('/{id}/badges', [BadgeController::class, 'index'])->name('users.badges'); // R204
    Route::get('/{id}/bookmarks', [UserController::class, 'showBookmarks'])->name('users.show.bookmarks'); // R205
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy'); // R206
    Route::get('/create', [UserController::class, 'create'])->name('admin.users.create'); // R209
    Route::post('/', [UserController::class, 'store'])->name('admin.users.store'); // R210
    Route::post('/{id}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
    Route::post('/{id}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
    Route::post('/{id}/change-role', [UserController::class, 'changeRole'])->name('users.changeRole');
});

// Public user routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show'); // R201

// Question Routes (Module M03)
Route::middleware('auth')->prefix('questions')->group(function () {
    Route::get('/create', [QuestionController::class, 'create'])->name('questions.create'); // R303
    Route::post('/', [QuestionController::class, 'store'])->name('questions.store'); // R302
    Route::get('/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit'); // R306
    Route::post('/{id}', [QuestionController::class, 'update'])->name('questions.update'); // R305
    Route::delete('/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy'); // R308
    Route::post('/{id}/vote', [QuestionController::class, 'vote'])->name('questions.vote'); // R307
    Route::post('/{id}/tag', [QuestionController::class, 'tag'])->name('questions.tag'); // R309
    Route::post('/{id}/bookmark', [QuestionController::class, 'toggleBookmark'])->name('questions.toggleBookmark');
});

// Question Public Routes

Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index'); // R301
Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show'); // R304

// Answer Routes (Module M04)
Route::middleware('auth')->prefix('answers')->group(function () {
    Route::post('/', [AnswerController::class, 'store'])->name('answers.store'); // R401
    Route::post('/{id}', [AnswerController::class, 'update'])->name('answers.update'); // R402
    Route::delete('/{id}/delete', [AnswerController::class, 'destroy'])->name('answers.destroy'); // R403
    Route::post('/{id}/vote', [AnswerController::class, 'vote'])->name('answers.vote'); // R404
    Route::post('/{id}/valid', [AnswerController::class, 'markAsValid'])->name('answers.markAsValid'); // R405
    Route::post('/answers/{id}/unmark-valid', [AnswerController::class, 'unmarkAsValid'])->name('answers.unmarkValid');
});

// Answer Comment Routes (Module M05)
Route::middleware('auth')->prefix('answer_comments')->group(function () {
    Route::post('/', [AnswerCommentController::class, 'store'])->name('answer_comments.store'); // R501
    Route::post('/{id}', [AnswerCommentController::class, 'update'])->name('answer_comments.update'); // R502
    Route::delete('/{id}/delete', [AnswerCommentController::class, 'destroy'])->name('answer_comments.destroy'); // R503
});

// Question Comment Routes (Module M06)
Route::middleware('auth')->prefix('question_comments')->group(function () {
    Route::post('/', [QuestionCommentController::class, 'store'])->name('question_comments.store'); // R601
    Route::post('/{id}', [QuestionCommentController::class, 'update'])->name('question_comments.update'); // R602
    Route::delete('/{id}/delete', [QuestionCommentController::class, 'destroy'])->name('question_comments.destroy'); // R603
});

// TODO add serach tags to the open api
// Search Routes (Module M07)
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/profiles', [SearchController::class, 'searchProfiles'])->name('search.profiles'); // R701
    Route::get('/questions', [SearchController::class, 'searchQuestions'])->name('search.questions'); // R702
    Route::get('/tags', [SearchController::class, 'seachTags'])->name('search.tags'); // R703
});

// TODO passar para o que está auqi em baixo no open api
// Question Comment Notification Routes (Module M08)
// Route::middleware('auth')->prefix('question_comment_notifications')->group(function () {
//     Route::get('/', [NotificationController::class, 'index'])->name('notifications.index'); // R801
//     Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy'); // R802
// });

// // Answer Comment Notification Routes (Module M09)
// Route::middleware('auth')->prefix('answer_comment_notifications')->group(function () {
//     Route::get('/', [NotificationController::class, 'index'])->name('notifications.index'); // R901
//     Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy'); // R902
// });

// // Answer Notification Routes (Module M10)
// Route::middleware('auth')->prefix('answer_notifications')->group(function () {
//     Route::get('/', [NotificationController::class, 'index'])->name('notifications.index'); // R1001
//     Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy'); // R1002
// });
// TODO fim do q e preciso passar para o open api

// Static Pages (Module M11)
Route::controller(StaticPageController::class)->group(function () {
    Route::get('/aboutUs', 'aboutUs')->name('aboutUs'); // R1101
    Route::get('/contactUs', 'contactUs')->name('contactUs'); // R1102
    Route::get('/faq', [StaticPageController::class, 'faq'])->name('faq'); // R1103
});

// Answer Comment Routes (Module M12)
// Route::middleware('auth')->prefix('question_comments')->group(function () {
//     Route::post('/', [QuestionCommentController::class, 'store'])->name('question_comments.store'); // R1201
//     Route::post('/{id}', [QuestionCommentController::class, 'update'])->name('question_comments.update'); // R1202
//     Route::post('/{id}/delete', [QuestionCommentController::class, 'destroy'])->name('question_comments.destroy'); // R1203
// });

// TODO add the things below to open api documentation
// Tag Routes (Module M13)
Route::resource('tags', TagController::class); // R1301
Route::post('/tags/{tag}/follow', [TagController::class, 'follow'])->name('tags.follow');
Route::post('/tags/{tag}/unfollow', [TagController::class, 'unfollow'])->name('tags.unfollow');

// Badge Routes (Module M14)
Route::middleware('auth')->prefix('badges')->group(function () {
    Route::get('/', [BadgeController::class, 'index'])->name('badges.index'); // R1401
    Route::get('/{id}', [BadgeController::class, 'show'])->name('badges.show'); // R1402
});

Route::controller(ContactController::class)->group(function () {
    Route::post('/submit-contact-form', 'submitForm')->name('contact.form');
});

// Tag Routes (Module M15)
// Route::get('/tags/{id}', [TagController::class, 'show'])->name('tags.show');
