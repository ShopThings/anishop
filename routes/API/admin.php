<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Blog\BlogCategoryController;
use App\Http\Controllers\Blog\BlogCommentBadgeController;
use App\Http\Controllers\Blog\BlogCommentController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Order\OrderBadgeController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\ReturnOrderRequestController;
use App\Http\Controllers\Other\AdminController;
use App\Http\Controllers\Other\AdminDashboardController;
use App\Http\Controllers\Other\CityPostPriceController;
use App\Http\Controllers\Other\ComplaintController;
use App\Http\Controllers\Other\ContactUsController;
use App\Http\Controllers\Other\FaqController;
use App\Http\Controllers\Other\FileManagerController;
use App\Http\Controllers\Other\MenuController;
use App\Http\Controllers\Other\NewsletterController;
use App\Http\Controllers\Other\SettingController;
use App\Http\Controllers\Other\SliderController;
use App\Http\Controllers\Other\SmsLogController;
use App\Http\Controllers\Other\StaticPageController;
use App\Http\Controllers\Other\WeightPostPriceController;
use App\Http\Controllers\Payment\PaymentMethodController;
use App\Http\Controllers\Report\ExportOrderController;
use App\Http\Controllers\Report\ReportOrderController;
use App\Http\Controllers\Report\ReportProductController;
use App\Http\Controllers\Report\ReportUserController;
use App\Http\Controllers\Shop\BrandController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\CategoryImageController;
use App\Http\Controllers\Shop\ColorController;
use App\Http\Controllers\Shop\CommentController;
use App\Http\Controllers\Shop\CouponController;
use App\Http\Controllers\Shop\FestivalController;
use App\Http\Controllers\Shop\ProductAttributeCategoryController;
use App\Http\Controllers\Shop\ProductAttributeController;
use App\Http\Controllers\Shop\ProductAttributeProductController;
use App\Http\Controllers\Shop\ProductAttributeValueController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\SendMethodController;
use App\Http\Controllers\Shop\UnitController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('api.admin.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');

            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');

            Route::middleware('xss')->group(function () {
                $codeRegex = '[\d\w\-\_]+';

                /*
                 * dashboard routes
                 */
                Route::get('counting/alerts', [AdminController::class, 'alertCounting'])
                    ->name('index.counting.alerts');
                Route::get('counting/orders', [AdminController::class, 'orderCounting'])
                    ->name('index.counting.orders');

                /*
                 * notification routes
                 */
                Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
                Route::put('notifications', [NotificationController::class, 'update'])->name('notifications.update');
                Route::get('notifications/new', [NotificationController::class, 'newNotifications'])->name('notifications.new');

                /*
                 * dashboard routes
                 */
                Route::get('sale/total', [AdminDashboardController::class, 'totalSale'])
                    ->name('dashboard.sale.total');
                Route::get('sale/{period}', [AdminDashboardController::class, 'periodSale'])
                    ->name('dashboard.sale.period');
                Route::get('chart/users/{period}', [AdminDashboardController::class, 'chartUsers'])
                    ->name('dashboard.users.period');
                Route::get('chart/orders/{period}/{status}', [AdminDashboardController::class, 'chartOrders'])
                    ->name('dashboard.orders.period');
                Route::get('chart/return-orders/{period}/{status}', [AdminDashboardController::class, 'chartReturnOrders'])
                    ->name('dashboard.return-orders.period');
                Route::get('table/most-sale-product/{period}', [AdminDashboardController::class, 'tableMostSaleProducts'])
                    ->name('dashboard.most-sale-products.period');
                Route::get('dashboard/counting', [AdminDashboardController::class, 'itemsCounts'])
                    ->name('dashboard.counting');

                /*
                 * user routes
                 */
                Route::delete('users/batch', [UserController::class, 'batchDestroy'])
                    ->name('users.destroy.batch');
                Route::apiResource('users', UserController::class)->whereNumber('users');
                Route::get('users/{user}/addresses', [UserController::class, 'addresses'])
                    ->whereNumber('user')->name('users.addresses');
                Route::get('users/{user}/favoriteProducts', [UserController::class, 'favoriteProducts'])
                    ->whereNumber('user')->name('users.addresses');
                Route::get('users/{user}/purchases', [UserController::class, 'purchases'])
                    ->whereNumber('user')->name('users.addresses');
                Route::get('users/{user}/carts', [UserController::class, 'carts'])
                    ->whereNumber('user')->name('users.addresses');

                /*
                 * payment method routes
                 */
                Route::apiResource('payment-methods', PaymentMethodController::class)
                    ->whereNumber('payment_method');
                Route::delete('payment-methods/batch', [PaymentMethodController::class, 'batchDestroy'])
                    ->name('payment-methods.destroy.batch');

                /*
                 * send method routes
                 */
                Route::apiResource('send-methods', SendMethodController::class)
                    ->whereNumber('send_method');
                Route::delete('send-methods/batch', [SendMethodController::class, 'batchDestroy'])
                    ->name('send-methods.destroy.batch');

                /*
                 * color routes
                 */
                Route::delete('colors/batch', [ColorController::class, 'batchDestroy'])
                    ->name('colors.destroy.batch');
                Route::apiResource('colors', ColorController::class)
                    ->whereNumber('color');

                /*
                 * brand routes
                 */
                Route::delete('brands/batch', [BrandController::class, 'batchDestroy'])
                    ->name('brands.destroy.batch');
                Route::apiResource('brands', BrandController::class)
                    ->whereNumber('brand');

                /*
                 * category routes
                 */
                Route::delete('categories/batch', [CategoryController::class, 'batchDestroyBySlug'])
                    ->name('categories.destroy.batch');
                Route::apiResource('categories', CategoryController::class)
                    ->where(['category' => $codeRegex]);

                /*
                 * category image routes
                 */
                Route::apiResource('category-images', CategoryImageController::class)
                    ->whereNumber('category_image');

                /*
                 * festival routes
                 */
                Route::delete('festivals/batch', [FestivalController::class, 'batchDestroyBySlug'])
                    ->name('festivals.destroy.batch');
                Route::apiResource('festivals', FestivalController::class)
                    ->where(['festival' => $codeRegex]);
                Route::get('festivals/{festival}/products', [FestivalController::class, 'products'])
                    ->where(['festival' => $codeRegex])->name('festivals.products.index');
                Route::post('festivals/{festival}/product', [FestivalController::class, 'storeProduct'])
                    ->where(['festival' => $codeRegex])->name('festivals.product.store');
                Route::post('festivals/{festival}/category', [FestivalController::class, 'storeCategoryProducts'])
                    ->where(['festival' => $codeRegex])->name('festivals.category.store');
                Route::delete('festivals/{festival}/product/{product}', [FestivalController::class, 'destroyProduct'])
                    ->where(['festival' => $codeRegex])->whereNumber('product')
                    ->name('festivals.product.destroy');
                Route::delete('festivals/{festival}/products', [FestivalController::class, 'batchDestroyProduct'])
                    ->where(['festival' => $codeRegex])->name('festivals.products.destroy.batch');
                Route::delete('festivals/{festival}/category/{category}', [FestivalController::class, 'batchDestroyCategory'])
                    ->where(['festival' => $codeRegex])->whereNumber('product')
                    ->name('festivals.category.destroy');

                /*
                 * unit routes
                 */
                Route::delete('units/batch', [UnitController::class, 'batchDestroy'])
                    ->name('units.destroy.batch');
                Route::apiResource('units', UnitController::class)
                    ->whereNumber('unit');

                /*
                 * coupon routes
                 */
                Route::delete('coupons/batch', [CouponController::class, 'batchDestroy'])
                    ->name('coupons.destroy.batch');
                Route::apiResource('coupons', CouponController::class)
                    ->whereNumber('coupon');

                /*
                 * product routes
                 */
                Route::put('products/batch/info', [ProductController::class, 'batchUpdateInfo'])
                    ->name('products.update.batch.info');
                Route::put('products/batch/price', [ProductController::class, 'batchUpdatePrice'])
                    ->name('products.update.batch.price');
                Route::delete('products/batch', [ProductController::class, 'batchDestroy'])
                    ->name('products.destroy.batch');
                Route::post('products/{product}/modify', [ProductController::class, 'modifyProducts'])
                    ->where(['product' => $codeRegex])->name('products.modify-products');
                Route::get('products/{product}/variants', [ProductController::class, 'showVariants'])
                    ->where(['product' => $codeRegex])->name('products.show.variants');
                Route::post('products/{product}/gallery', [ProductController::class, 'storeGalleryImages'])
                    ->where(['product' => $codeRegex])->name('products.store.gallery');
                Route::get('products/{product}/gallery', [ProductController::class, 'showGalleryImages'])
                    ->where(['product' => $codeRegex])->name('products.show.gallery');
                Route::post('products/{product}/related-products', [ProductController::class, 'storeRelatedProducts'])
                    ->where(['product' => $codeRegex])->name('products.store.related-products');
                Route::get('products/{product}/related-products', [ProductController::class, 'showRelatedProducts'])
                    ->where(['product' => $codeRegex])->name('products.show.related-products');
                Route::apiResource('products', ProductController::class)
                    ->where(['product' => $codeRegex]);

                /*
                 * product attribute routes
                 */
                Route::delete('product-attributes/batch', [ProductAttributeController::class, 'batchDestroy'])
                    ->name('product-attributes.destroy.batch');
                Route::apiResource('product-attributes', ProductAttributeController::class)
                    ->whereNumber('product_attribute');

                /*
                 * product attribute value routes
                 */
                Route::delete('product-attribute-values/batch', [ProductAttributeValueController::class, 'batchDestroy'])
                    ->name('product-attribute-values.destroy.batch');
                Route::apiResource('product-attribute-values', ProductAttributeValueController::class)
                    ->whereNumber('product_attribute_value');

                /*
                 * product attribute category routes
                 */
                Route::delete('product-attribute-categories/batch', [ProductAttributeCategoryController::class, 'batchDestroy'])
                    ->name('product-attribute-categories.destroy.batch');
                Route::apiResource('product-attribute-categories', ProductAttributeCategoryController::class)
                    ->whereNumber('product_attribute_category');

                /*
                 * product attribute product routes
                 */
                Route::get('product-attribute-products/{product}', [ProductAttributeProductController::class, 'show'])
                    ->whereNumber('product_attribute_product')->name('product-attribute-products.show');
                Route::post('product-attribute-products/{product}', [ProductAttributeProductController::class, 'store'])
                    ->whereNumber('product_attribute_product')->name('product-attribute-products.store');

                /*
                 * comment routes
                 */
                Route::get('products/comments/all', [CommentController::class, 'all'])
                    ->name('products.comments.all');
                Route::delete('products/{product}/comments/batch', [CommentController::class, 'batchDestroy'])
                    ->whereNumber('product')->name('products.comments.destroy.batch');
                Route::apiResource('products.comments', CommentController::class)->except(['store'])
                    ->where(['product' => $codeRegex])->whereNumber('comment');

                /*
                 * order routes
                 */
                Route::get('orders/payment-statuses', [OrderController::class, 'paymentStatuses'])
                    ->name('orders.payment-statuses');
                Route::get('orders/send-statuses', [OrderController::class, 'sendStatuses'])
                    ->name('orders.send-statuses');
                Route::get('orders/all/{user?}', [OrderController::class, 'index'])
                    ->whereNumber('user')->name('orders.index');
                Route::put('orders/export/{order}', [ExportOrderController::class, 'pdf'])
                    ->where(['order' => $codeRegex])->name('orders.export.pdf');
                Route::put('orders/{order}/payment', [OrderController::class, 'updatePayment'])
                    ->where(['order' => $codeRegex])->name('orders.update.payment');
                Route::apiResource('orders', OrderController::class)->except(['index', 'store'])
                    ->where(['order' => $codeRegex]);

                /*
                 * order badge routes
                 */
                Route::delete('order-badges/batch', [OrderBadgeController::class, 'batchDestroy'])
                    ->name('order-badges.destroy.batch');
                Route::apiResource('order-badges', OrderBadgeController::class)
                    ->whereNumber('order_badge');

                /*
                 * return order routes
                 */
                Route::get('return-orders/all-statuses', [ReturnOrderRequestController::class, 'allStatuses'])
                    ->name('return-orders.all-statuses');
                Route::get('return-orders/statuses', [ReturnOrderRequestController::class, 'statuses'])
                    ->name('return-orders.statuses');
                Route::get('return-orders/all/{user?}', [ReturnOrderRequestController::class, 'index'])
                    ->whereNumber('user')->name('return-orders.index');
                Route::put('return-orders/{return_order}/to-stock', [ReturnOrderRequestController::class, 'returnItemsToStock'])
                    ->where(['return_order' => $codeRegex])->name('return-orders.to-stock');
                Route::put('return-orders/{return_order}/{return_order_item}/modify-item', [ReturnOrderRequestController::class, 'modifyItem'])
                    ->where(['return_order' => $codeRegex])->name('return-orders.modify-item');
                Route::apiResource('return-orders', ReturnOrderRequestController::class)->except(['index', 'store'])
                    ->where(['return_order' => $codeRegex]);

                /*
                 * report routes
                 */
                Route::get('reports/users/query-builder', [ReportUserController::class, 'usersQB'])
                    ->name('reports.users.query-builder');
                Route::post('reports/users/export', [ReportUserController::class, 'export'])
                    ->name('reports.users.export');
                Route::post('reports/users', [ReportUserController::class, 'users'])
                    ->name('reports.users');

                Route::get('reports/products/query-builder', [ReportProductController::class, 'productsQB'])
                    ->name('reports.products.query-builder');
                Route::post('reports/products/export', [ReportProductController::class, 'export'])
                    ->name('reports.products.export');
                Route::post('reports/products', [ReportProductController::class, 'products'])
                    ->name('reports.products');

                Route::get('reports/orders/query-builder', [ReportOrderController::class, 'ordersQB'])
                    ->name('reports.orders.query-builder');
                Route::post('reports/orders/export', [ReportOrderController::class, 'export'])
                    ->name('reports.orders.export');
                Route::post('reports/orders', [ReportOrderController::class, 'orders'])
                    ->name('reports.orders');

                /*
                 * blog comment badge routes
                 */
                Route::delete('blog-badges/batch', [BlogCommentBadgeController::class, 'batchDestroy'])
                    ->name('blog-badges.destroy.batch');
                Route::apiResource('blog-badges', BlogCommentBadgeController::class)
                    ->whereNumber('blog_badge');

                /*
                 * blog routes
                 */
                Route::delete('blogs/batch', [BlogController::class, 'batchDestroyBySlug'])
                    ->name('blogs.destroy.batch');
                Route::apiResource('blogs', BlogController::class)
                    ->where(['blog' => $codeRegex]);

                /*
                 * blog comment routes
                 */
                Route::get('blogs/comments/all', [BlogCommentController::class, 'all'])
                    ->name('blogs.comments.all');
                Route::delete('blogs/{blog}/comments/batch', [BlogCommentController::class, 'batchDestroy'])
                    ->name('blogs.comments.destroy.batch');
                Route::apiResource('blogs.comments', BlogCommentController::class)
                    ->where(['blog' => $codeRegex])->whereNumber('comment');

                /*
                 * blog category routes
                 */
                Route::delete('blog-categories/batch', [BlogCategoryController::class, 'batchDestroyBySlug'])
                    ->name('blog-categories.destroy.batch');
                Route::apiResource('blog-categories', BlogCategoryController::class)
                    ->where(['blog_category' => $codeRegex]);

                /*
                 * sms log routes
                 */
                Route::get('sms-log', [SmsLogController::class, 'index'])->name('sms-log.index');

                /*
                 * static page routes
                 */
                Route::delete('static-pages/batch', [StaticPageController::class, 'batchDestroyByUrl'])
                    ->name('static-pages.destroy.batch');
                Route::apiResource('static-pages', StaticPageController::class)
                    ->whereNumber('static_page');

                /*
                 * contact routes
                 */
                Route::delete('contacts/batch', [ContactUsController::class, 'batchDestroy'])
                    ->name('contacts.destroy.batch');
                Route::apiResource('contacts', ContactUsController::class)->except(['store'])
                    ->whereNumber('contact');

                /*
                 * complaint routes
                 */
                Route::delete('complaints/batch', [ComplaintController::class, 'batchDestroy'])
                    ->name('complaints.destroy.batch');
                Route::apiResource('complaints', ComplaintController::class)->except(['store'])
                    ->whereNumber('complaint');

                /*
                 * faq routes
                 */
                Route::delete('faqs/batch', [FaqController::class, 'batchDestroy'])
                    ->name('faqs.destroy.batch');
                Route::apiResource('faqs', FaqController::class)
                    ->whereNumber('faq');

                /*
                 * newsletter routes
                 */
                Route::delete('newsletters/batch', [NewsletterController::class, 'batchDestroy'])
                    ->name('newsletters.destroy.batch');
                Route::apiResource('newsletters', NewsletterController::class)->except(['update'])
                    ->whereNumber('newsletter');

                /*
                 * city post price routes
                 */
                Route::delete('city-post-prices/batch', [CityPostPriceController::class, 'batchDestroy'])
                    ->name('city-post-prices.destroy.batch');
                Route::apiResource('city-post-prices', CityPostPriceController::class)
                    ->whereNumber('city_post_price');

                /*
                 * weight post price routes
                 */
                Route::delete('weight-post-prices/batch', [WeightPostPriceController::class, 'batchDestroy'])
                    ->name('weight-post-prices.destroy.batch');
                Route::apiResource('weight-post-prices', WeightPostPriceController::class)
                    ->whereNumber('weight_post_price');

                /*
                 * slider routes
                 */
                Route::delete('sliders/batch', [SliderController::class, 'batchDestroy'])
                    ->name('sliders.destroy.batch');
                Route::apiResource('sliders', SliderController::class)
                    ->whereNumber('slider');
                Route::get('sliders/{slider}/slides', [SliderController::class, 'showSlides'])
                    ->whereNumber('slider')->name('sliders.show.slides');
                Route::post('sliders/{slider}/modify', [SliderController::class, 'modifySlides'])
                    ->whereNumber('slider')->name('sliders.modify-slides');

                /*
                 * menu routes
                 */
                Route::apiResource('menus', MenuController::class)->only(['index', 'show', 'update'])
                    ->whereNumber('menu');
                Route::get('menus/{menu}/items', [MenuController::class, 'showItems'])
                    ->whereNumber('menu')->name('menus.show.slides');
                Route::post('menus/{menu}/modify', [MenuController::class, 'modifyMenus'])
                    ->whereNumber('menu')->name('menus.modify-menus');

                /*
                 * setting routes
                 */
                Route::get('settings/{group?}', [SettingController::class, 'index'])
                    ->whereAlpha('group')->name('settings.index');
                Route::put('settings', [SettingController::class, 'update'])
                    ->name('settings.update');
            });

            /*
             * file-manager routes
             */
            Route::prefix('files')
                ->name('files.')
                ->group(function () {
                    Route::get('/', [FileManagerController::class, 'index'])
                        ->name('index');
                    Route::get('tree', [FileManagerController::class, 'treeList'])
                        ->name('tree');
                    Route::post('directory', [FileManagerController::class, 'createDirectory'])
                        ->name('create-directory');
                    Route::post('rename', [FileManagerController::class, 'rename'])
                        ->name('rename');
                    Route::post('move', [FileManagerController::class, 'move'])
                        ->name('move');
                    Route::post('copy', [FileManagerController::class, 'copy'])
                        ->name('copy');
                    Route::post('/', [FileManagerController::class, 'store'])->middleware('optimizeImages')
                        ->name('store');
                    Route::get('{file}', [FileManagerController::class, 'download'])
                        ->name('download');
                    Route::delete('/', [FileManagerController::class, 'destroy'])
                        ->name('destroy');
                    Route::delete('batch', [FileManagerController::class, 'batchDestroy'])
                        ->name('destroy.batch');
                });
        });
    });
