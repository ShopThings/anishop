<?php

use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogCommentBadgeController;
use App\Http\Controllers\Admin\BlogCommentController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryImageController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CityPostPriceController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FestivalController;
use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\OrderBadgeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ProductAttributeCategoryController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductAttributeProductController;
use App\Http\Controllers\Admin\ProductAttributeValueController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReturnOrderRequestController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WeightPostPriceController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('api.admin.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');

            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');

            Route::middleware('xss')->group(function () {
                /*
                 * user routes
                 */
                Route::delete('users/batch', [UserController::class, 'batchDestroy'])
                    ->name('users.destroy.batch');
                Route::apiResource('users', UserController::class)->whereNumber('users');
                Route::get('users/{users}/addresses', [UserController::class, 'addresses'])->whereNumber('users')
                    ->name('users.addresses');
                Route::get('users/{users}/favoriteProducts', [UserController::class, 'favoriteProducts'])
                    ->whereNumber('users')->name('users.addresses');
                Route::get('users/{users}/purchases', [UserController::class, 'purchases'])
                    ->whereNumber('users')->name('users.addresses');
                Route::get('users/{users}/carts', [UserController::class, 'carts'])
                    ->whereNumber('users')->name('users.addresses');

                /*
                 * payment method routes
                 */
                Route::apiResource('payment-methods', PaymentMethodController::class)
                    ->whereNumber('payment_method');
                Route::delete('payment-methods/batch', [PaymentMethodController::class, 'batchDestroy'])
                    ->name('payment-methods.destroy.batch');

                /*
                 * color routes
                 */
                Route::apiResource('colors', ColorController::class)
                    ->whereNumber('color');
                Route::delete('colors/batch', [ColorController::class, 'batchDestroy'])
                    ->name('colors.destroy.batch');

                /*
                 * brand routes
                 */
                Route::apiResource('brands', BrandController::class)
                    ->whereNumber('brand');
                Route::delete('brands/batch', [BrandController::class, 'batchDestroy'])
                    ->name('brands.destroy.batch');

                /*
                 * category routes
                 */
                Route::apiResource('categories', CategoryController::class)
                    ->whereNumber('category');
                Route::delete('categories/batch', [CategoryController::class, 'batchDestroy'])
                    ->name('categories.destroy.batch');

                /*
                 * category image routes
                 */
                Route::apiResource('category-images', CategoryImageController::class)
                    ->whereNumber('category_image');

                /*
                 * festival routes
                 */
                Route::apiResource('festivals', FestivalController::class)
                    ->whereNumber('festival');
                Route::delete('festivals/batch', [FestivalController::class, 'batchDestroy'])
                    ->name('festivals.destroy.batch');
                Route::get('festivals/{festival}/products', [FestivalController::class, 'products'])
                    ->whereNumber('festival')->name('festivals.products.index');
                Route::post('festivals/{festival}/product', [FestivalController::class, 'storeProduct'])
                    ->whereNumber('festival')->name('festivals.product.store');
                Route::post('festivals/{festival}/category', [FestivalController::class, 'storeCategoryProducts'])
                    ->whereNumber('festival')->name('festivals.category.store');
                Route::delete('festivals/{festival}/product/{product}', [FestivalController::class, 'destroyProduct'])
                    ->whereNumber(['festival', 'product'])->name('festivals.product.destroy');
                Route::delete('festivals/{festival}/category/{category}', [FestivalController::class, 'batchDestroyProduct'])
                    ->whereNumber(['festival', 'product'])->name('festivals.category.destroy');

                /*
                 * unit routes
                 */
                Route::apiResource('units', UnitController::class)
                    ->whereNumber('unit');
                Route::delete('units/batch', [UnitController::class, 'batchDestroy'])
                    ->name('units.destroy.batch');

                /*
                 * coupon routes
                 */
                Route::apiResource('coupons', CouponController::class)
                    ->whereNumber('coupon');
                Route::delete('coupons/batch', [CouponController::class, 'batchDestroy'])
                    ->name('coupons.destroy.batch');

                /*
                 * product routes
                 */
                Route::apiResource('products', ProductController::class)
                    ->whereNumber('product');
                Route::delete('products/batch', [ProductController::class, 'batchDestroy'])
                    ->name('products.destroy.batch');
                Route::get('products/{product}/main-info', [ProductController::class, 'mainInfo'])
                    ->name('products.index.main-info');

                /*
                 * product attribute routes
                 */
                Route::apiResource('product-attributes', ProductAttributeController::class)
                    ->whereNumber('product_attribute');
                Route::delete('product-attributes/batch', [ProductAttributeController::class, 'batchDestroy'])
                    ->name('product-attributes.destroy.batch');
                Route::get('product-attributes/{product}/main-info', [ProductAttributeController::class, 'showProductMain'])
                    ->whereNumber('product')->name('product-attributes.show.product');

                /*
                 * product attribute value routes
                 */
                Route::apiResource('product-attribute-values', ProductAttributeValueController::class)
                    ->whereNumber('product_attribute_value');
                Route::delete('product-attribute-values/batch', [ProductAttributeValueController::class, 'batchDestroy'])
                    ->name('product-attribute-values.destroy.batch');

                /*
                 * product attribute category routes
                 */
                Route::apiResource('product-attribute-categories', ProductAttributeCategoryController::class)
                    ->whereNumber('product_attribute_category');
                Route::delete('product-attribute-categories/batch', [ProductAttributeCategoryController::class, 'batchDestroy'])
                    ->name('product-attribute-categories.destroy.batch');

                /*
                 * product attribute product routes
                 */
                Route::put('product-attribute-products/{product_attribute_product}', [ProductAttributeProductController::class, 'update'])
                    ->whereNumber('product_attribute_product')->name('product-attribute-products.update');

                /*
                 * comment routes
                 */
                Route::apiResource('products.comments', CommentController::class)->except(['store'])
                    ->whereNumber(['product', 'comment']);//->shallow();
                Route::delete('products/{product}/comments/{comment}/batch', [CommentController::class, 'batchDestroy'])
                    ->whereNumber(['product', 'comment'])->name('products.comments.destroy.batch');

                /*
                 * order routes
                 */
                Route::get('orders/{user?}', [OrderController::class, 'index'])
                    ->whereNumber('user')->name('orders.index');
                Route::apiResource('orders', ReturnOrderRequestController::class)->except(['index', 'store'])
                    ->whereNumber('order');
                Route::get('orders/payment-statuses', [OrderController::class, 'paymentStatuses'])
                    ->name('orders.payment-statuses');
                Route::get('orders/send-statuses', [OrderController::class, 'sendStatuses'])
                    ->name('orders.send-statuses');

                /*
                 * order badge routes
                 */
                Route::apiResource('order-badges', OrderBadgeController::class)
                    ->whereNumber('order_badge');
                Route::delete('order-badges/batch', [OrderBadgeController::class, 'batchDestroy'])
                    ->name('order-badges.destroy.batch');

                /*
                 * return order routes
                 */
                Route::apiResource('return-orders', ReturnOrderRequestController::class)->except(['store'])
                    ->whereNumber('return_order');

                /*
                 * report routes
                 */
                Route::get('reports/users', [ReportController::class, 'users'])
                    ->name('reports.users');
                Route::get('reports/products', [ReportController::class, 'products'])
                    ->name('reports.products');
                Route::get('reports/orders', [ReportController::class, 'orders'])
                    ->name('reports.orders');
                Route::get('reports/users/query-builder', [ReportController::class, 'usersQB'])
                    ->name('reports.users.query-builder');
                Route::get('reports/products/query-builder', [ReportController::class, 'productsQB'])
                    ->name('reports.products.query-builder');
                Route::get('reports/orders/query-builder', [ReportController::class, 'ordersQB'])
                    ->name('reports.orders.query-builder');

                /*
                 * blog comment badge routes
                 */
                Route::apiResource('blog-badges', BlogCommentBadgeController::class)
                    ->whereNumber('blog_badge');
                Route::delete('blog-badges/batch', [BlogCommentBadgeController::class, 'batchDestroy'])
                    ->name('blog-badges.destroy.batch');

                /*
                 * blog routes
                 */
                Route::apiResource('blogs', BlogController::class)
                    ->whereNumber('blog');
                Route::delete('blogs/batch', [BlogController::class, 'batchDestroy'])
                    ->name('blogs.destroy.batch');

                /*
                 * blog comment routes
                 */
                Route::apiResource('blogs.comments', BlogCommentController::class)->except(['store'])
                    ->whereNumber(['blog', 'comment']);//->shallow();
                Route::delete('blogs/{blog}/comments/{comment}/batch', [BlogCommentController::class, 'batchDestroy'])
                    ->name('blogs.comments.destroy.batch');

                /*
                 * blog category routes
                 */
                Route::apiResource('blog-categories', BlogCategoryController::class)
                    ->whereNumber('blog_category');
                Route::delete('blog-categories/batch', [BlogCategoryController::class, 'batchDestroy'])
                    ->name('blog-categories.destroy.batch');

                /*
                 * static page routes
                 */
                Route::apiResource('static-pages', StaticPageController::class)
                    ->whereNumber('static_page');
                Route::delete('static-pages/batch', [StaticPageController::class, 'batchDestroy'])
                    ->name('static-pages.destroy.batch');

                /*
                 * contact routes
                 */
                Route::apiResource('contacts', ContactUsController::class)->except(['store'])
                    ->whereNumber('contact');
                Route::delete('contacts/batch', [ContactUsController::class, 'batchDestroy'])
                    ->name('contacts.destroy.batch');

                /*
                 * complaint routes
                 */
                Route::apiResource('complaints', ComplaintController::class)->except(['store'])
                    ->whereNumber('complaint');
                Route::delete('complaints/batch', [ComplaintController::class, 'batchDestroy'])
                    ->name('complaints.destroy.batch');

                /*
                 * faq routes
                 */
                Route::apiResource('faqs', FaqController::class)
                    ->whereNumber('faq');
                Route::delete('faqs/batch', [FaqController::class, 'batchDestroy'])
                    ->name('faqs.destroy.batch');

                /*
                 * newsletter routes
                 */
                Route::apiResource('newsletters', NewsletterController::class)->except(['update'])
                    ->whereNumber('newsletter');
                Route::delete('newsletters/batch', [NewsletterController::class, 'batchDestroy'])
                    ->name('newsletters.destroy.batch');

                /*
                 * city post price routes
                 */
                Route::apiResource('city-post-prices', CityPostPriceController::class)
                    ->whereNumber('city_post_price');
                Route::delete('city-post-prices/batch', [CityPostPriceController::class, 'batchDestroy'])
                    ->name('city-post-prices.destroy.batch');

                /*
                 * city routes
                 */
                Route::delete('cities', [CityController::class, 'index'])
                    ->name('cities.index');

                /*
                 * province routes
                 */
                Route::delete('provinces', [ProvinceController::class, 'index'])
                    ->name('provinces.index');

                /*
                 * weight post price routes
                 */
                Route::apiResource('weight-post-prices', WeightPostPriceController::class)
                    ->whereNumber('weight_post_price');
                Route::delete('weight-post-prices/batch', [WeightPostPriceController::class, 'batchDestroy'])
                    ->name('weight-post-prices.destroy.batch');

                /*
                 * slider routes
                 */
                Route::apiResource('sliders', SliderController::class)
                    ->whereNumber('slider');
                Route::delete('sliders/batch', [SliderController::class, 'batchDestroy'])
                    ->name('sliders.destroy.batch');

                /*
                 * menu routes
                 */
                Route::apiResource('menus', MenuController::class)
                    ->whereNumber('menu');
                Route::delete('menus/batch', [MenuController::class, 'batchDestroy'])
                    ->name('menus.destroy.batch');
            });

            /*
             * file-manager routes
             */
            Route::get('files', [FileManagerController::class, 'index'])
                ->name('files.index');
            Route::get('files/tree', [FileManagerController::class, 'treeList'])
                ->name('files.tree');
            Route::post('files/directory', [FileManagerController::class, 'createDirectory'])
                ->name('files.create-directory');
            Route::post('files/rename', [FileManagerController::class, 'rename'])
                ->name('files.rename');
            Route::post('files/move', [FileManagerController::class, 'move'])
                ->name('files.move');
            Route::post('files/copy', [FileManagerController::class, 'copy'])
                ->name('files.copy');
            Route::post('files', [FileManagerController::class, 'store'])->middleware('optimizeImages')
                ->name('files.store');
            Route::get('files/{file}', [FileManagerController::class, 'download'])
                ->name('files.download');
            Route::get('files/{file}/{size?}', [FileManagerController::class, 'show'])
                ->name('files.show');
            Route::delete('files/{file}', [FileManagerController::class, 'destroy'])
                ->name('files.destroy')->whereNumber('file');
            Route::delete('files/batch', [FileManagerController::class, 'batchDestroy'])
                ->name('files.destroy.batch');
        });
    });
