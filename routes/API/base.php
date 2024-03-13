<?php

use App\Exceptions\RouteNotFoundException;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RecoverPasswordController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Blog\HomeBlogCommentController;
use App\Http\Controllers\Blog\HomeBlogController;
use App\Http\Controllers\Other\CityController;
use App\Http\Controllers\Other\FileManagerController;
use App\Http\Controllers\Other\HomeController;
use App\Http\Controllers\Other\HomeMenuController;
use App\Http\Controllers\Other\HomePageController;
use App\Http\Controllers\Other\HomeSliderController;
use App\Http\Controllers\Other\ProvinceController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Shop\HomeBrandController;
use App\Http\Controllers\Shop\HomeCategoryController;
use App\Http\Controllers\Shop\HomeCommentController;
use App\Http\Controllers\Shop\HomeFestivalController;
use App\Http\Controllers\Shop\HomeProductController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->group(function () {
        Route::middleware('xss')->group(function () {
            $codeRegex = '[\d\w\-\_]+';

            /*
             * be careful with this route, because anyone can call it!
             * TODO: make this route more secure (I think in controller).
             */
            Route::any('payments/{payment}/verify', [PaymentController::class, 'verify'])
                ->name('payment.verify');

            Route::get('files', [FileManagerController::class, 'show'])
                ->name('files.show');

            Route::post('login', [AuthController::class, 'login'])
                ->name('login');

            /*
             * city routes
             */
            Route::get('cities/{province}', [CityController::class, 'index'])
                ->whereNumber('province')->name('cities.index');

            /*
             * province routes
             */
            Route::get('provinces', [ProvinceController::class, 'index'])
                ->name('provinces.index');

            /*
             * signup routes
             */
            Route::post('signup/mobile', [SignupController::class, 'store'])
                ->name('signup.store');
            Route::post('signup/code', [SignupController::class, 'verifyCode'])
                ->name('signup.verify-code');
            Route::post('signup/new-password', [SignupController::class, 'assignPassword'])
                ->name('signup.assign-password');
            Route::post('signup/resend-code', [SignupController::class, 'resendCode'])
                ->name('signup.resend-code');

            /*
             * recover password routes
             */
            Route::post('recover-password/mobile', [RecoverPasswordController::class, 'checkMobile'])
                ->name('recover-password.check-mobile');
            Route::post('recover-password/code', [RecoverPasswordController::class, 'verifyCode'])
                ->name('recover-password.verify-code');
            Route::post('recover-password/new-password', [RecoverPasswordController::class, 'assignNewPassword'])
                ->name('recover-password.assign-password');
            Route::post('recover-password/resend-code', [RecoverPasswordController::class, 'resendCode'])
                ->name('recover-password.resend-code');

            /*
             * main page routes
             */
            Route::get('menus/{menu}', [HomeMenuController::class, 'menu'])->name('menu')
                ->where(['menu' => '[a-zA-Z0-9_-]+']);
            Route::get('categories/main', [HomeCategoryController::class, 'categories'])->name('categories.main');
            Route::get('sliders/main', [HomeSliderController::class, 'main'])->name('sliders.main');
            Route::get('sliders/categories', [HomeSliderController::class, 'sliderCategories'])->name('sliders.categories');
            Route::get('sliders/amazing-offers', [HomeSliderController::class, 'amazingOffers'])->name('sliders.amazing-offers');
            Route::get('sliders', [HomeSliderController::class, 'allSliders'])->name('sliders.all');
            Route::get('sliders/brands', [HomeBrandController::class, 'brandSlider'])->name('sliders.brands');

            /*
             * brand routes
             */
            Route::get('brands', [HomeBrandController::class, 'brands'])->name('brands.index');

            /*
             * product routes
             */
            Route::get('products', [HomeProductController::class, 'index'])->name('products.index');
            Route::get('products/filter/brands', [HomeProductController::class, 'brandsFilter'])
                ->name('products.filter.brands');
            Route::get('products/filter/colors-and-sizes', [HomeProductController::class, 'colorsAndSizesFilter'])
                ->name('products.filter.colors-and-sizes');
            Route::get('products/filter/price-range', [HomeProductController::class, 'priceRangeFilter'])
                ->name('products.filter.price-range');
            Route::get('products/filter/get-dynamic-filters', [HomeProductController::class, 'dynamicFilters'])
                ->name('products.filter.dynamic-filters');
            Route::get('products/{product}/minified', [HomeProductController::class, 'minifiedShow'])
                ->name('products.minified-show')->where(['product' => $codeRegex]);
            Route::get('products/{product}', [HomeProductController::class, 'show'])->name('products.show')
                ->middleware('log_visit')->where(['product' => $codeRegex]);

            /*
             * product comment routes
             */
            Route::get('products/{product}/comments', [HomeCommentController::class, 'index'])
                ->name('products.comments.index')->where(['product' => $codeRegex]);
            Route::put('products/{product}/comments/{comment}/report', [HomeCommentController::class, 'report'])
                ->name('products.comments.report')->where(['product' => $codeRegex, 'comment' => '[0-9]+']);
            Route::put('products/{product}/comments/{comment}/vote', [HomeCommentController::class, 'vote'])
                ->name('products.comments.vote')->where(['product' => $codeRegex, 'comment' => '[0-9]+']);

            /*
             * festival routes
             */
            Route::get('festivals', [HomeFestivalController::class, 'index'])->name('festivals.index');

            /*
             * blog routes
             */
            Route::get('blogs', [HomeBlogController::class, 'index'])->name('blogs.index');
            Route::get('blogs/latest', [HomeBlogController::class, 'homeLatestBlogs'])->name('blogs.latest');
            Route::get('blogs/archive', [HomeBlogController::class, 'archive'])->name('blogs.archive');
            Route::get('blogs/sliders/main', [HomeBlogController::class, 'mainSlider'])->name('blogs.sliders.main');
            Route::get('blogs/sliders/side-slides', [HomeBlogController::class, 'mainSideSlides'])->name('blogs.sliders.side-slides');
            Route::get('blogs/popular-categories', [HomeBlogController::class, 'popularCategories'])->name('blogs.popular-categories');
            Route::get('blogs/most-viewed', [HomeBlogController::class, 'mostViewed'])->name('blogs.most-viewed');
            Route::get('blogs/{blog}', [HomeBlogController::class, 'show'])->name('blogs.show')
                ->middleware('log_visit')->where(['blog' => $codeRegex]);
            Route::get('blogs/{blog}/minified', [HomeBlogController::class, 'minifiedShow'])
                ->name('blogs.minified-show')->where(['blog' => $codeRegex]);
            Route::post('blogs/{blog}/vote', [HomeBlogController::class, 'vote'])->name('blogs.vote')
                ->where(['blog' => $codeRegex]);

            /*
             * blog comment routes
             */
            Route::get('blogs/{blog}/comments', [HomeBlogCommentController::class, 'index'])->name('blogs.comments.index')
                ->where(['blog' => $codeRegex]);
            Route::put('blogs/{blog}/comments/{comment}/report', [HomeBlogCommentController::class, 'report'])->name('blogs.comments.report')
                ->where(['blog' => $codeRegex, 'comment' => '[0-9]+']);

            /*
             * static page routes
             */
            Route::get('pages/{page}', [HomePageController::class, 'show'])->name('pages.show')
                ->where(['page' => '[a-zA-Z]+[a-zA-Z\\\-][a-zA-Z]+']);

            /*
             * other pages routes
             */
            Route::post('contact-us', [HomeController::class, 'storeContactUs'])->name('contacts.store');
            Route::post('complaints', [HomeController::class, 'storeComplaint'])->name('complaints.store');
            Route::post('newsletters', [HomeController::class, 'storeNewsletter'])->name('newsletters.store');
            Route::get('faqs', [HomeController::class, 'faqs'])->name('faqs.index');
            Route::get('settings', [HomeController::class, 'settings'])->name('settings.index');
        });
    });

Route::fallback(function () {
    throw new RouteNotFoundException();
});
