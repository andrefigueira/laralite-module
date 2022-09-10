<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Admin Auth Routes, prefixed with Admin, so doesn't show up for frontend
 */
Route::group(['prefix' => 'admin', 'middleware' => ['web']], function () {
    Auth::routes([
        'register' => false,
    ]);
});

/**
 * Admin Routes
 */
Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/admin', 'Admin\OrdersController@index');
    Route::get('/admin/home', 'Admin\OrdersController@index');

    Route::get('/admin/pages', 'Admin\PagesController@index');
    Route::get('/admin/pages/create', 'Admin\PagesController@create');
    Route::get('/admin/pages/edit/{id}', 'Admin\PagesController@edit');

    Route::get('/admin/templates', 'Admin\TemplatesController@index');
    Route::get('/admin/templates/create', 'Admin\TemplatesController@create');
    Route::get('/admin/templates/edit/{id}', 'Admin\TemplatesController@edit');

    Route::get('/admin/navigation', 'Admin\NavigationController@index');
    Route::get('/admin/navigation/create', 'Admin\NavigationController@create');
    Route::get('/admin/navigation/edit/{id}', 'Admin\NavigationController@edit');

    Route::get('/admin/components', 'Admin\ComponentController@index');

    Route::get('/admin/users', 'Admin\UsersController@index');
    Route::get('/admin/users/create', 'Admin\UsersController@create');
    Route::get('/admin/users/edit/{id}', 'Admin\UsersController@edit');

    Route::get('/admin/roles', 'Admin\RolesController@index');
    Route::get('/admin/roles/create', 'Admin\RolesController@create');
    Route::get('/admin/roles/edit/{id}', 'Admin\RolesController@edit');

    Route::get('/admin/permissions', 'Admin\PermissionsController@index');
    Route::get('/admin/permissions/create', 'Admin\PermissionsController@create');
    Route::get('/admin/permissions/edit/{id}', 'Admin\PermissionsController@edit');

    Route::get('/admin/subscriptions', 'Admin\SubscriptionsController@index');
    Route::get('/admin/subscriptions/create', 'Admin\SubscriptionsController@create');
    Route::get('/admin/subscriptions/edit/{id}', 'Admin\SubscriptionsController@edit');

    Route::get('/admin/customers', 'Admin\CustomersController@index');
    Route::get('/admin/customers/edit/{id}', 'Admin\CustomersController@edit');
    Route::get('/admin/customers/view/{id}', 'Admin\CustomersController@view');

    Route::get('/admin/orders', 'Admin\OrdersController@index');
    Route::get('/admin/orders/view/{id}', 'Admin\OrdersController@view');

    Route::get('/admin/product', 'Admin\ProductController@index');
    Route::get('/admin/product/create', 'Admin\ProductController@create');
    Route::get('/admin/product/edit/{id}', 'Admin\ProductController@edit');

    Route::get('/admin/discounts', 'Admin\DiscountController@index');
    Route::get('/admin/discounts/create', 'Admin\DiscountController@create');
    Route::get('/admin/discounts/edit/{id}', 'Admin\DiscountController@edit');

    Route::get('/admin/product-category', 'Admin\ProductCategoriesController@index');
    Route::get('/admin/product-category/create', 'Admin\ProductCategoriesController@create');
    Route::get('/admin/product-category/edit/{id}', 'Admin\ProductCategoriesController@edit');

    Route::get('/admin/variables', 'Admin\VariableController@index');
    Route::get('/admin/authentication', 'Admin\AuthenticationController@index');
    Route::get('/admin/settings', 'Admin\SettingsController@index');
    Route::get('/admin/import', 'Admin\DataImportController@index');

    Route::get('/admin/scanner', 'Admin\ScannerController@scanner');
    Route::get('/admin/reporting', 'Admin\ReportingController@index');

    Route::get('/admin/sales', 'Admin\AdminController@getSales');
    Route::get('/admin/scannerlog', 'Admin\AdminController@getScannerLog');


});


Route::post('/login', 'Auth\CustomerAuthController@login');
Route::get('/logout', 'Auth\CustomerAuthController@logout');
Route::post('/password-reset', 'Auth\CustomerAuthController@sendResetLinkEmail');
Route::post('/reset', 'Auth\CustomerAuthController@reset');
Route::post('/signup', 'Auth\SignupController@signup');
Route::get('/verify-account', 'Auth\CustomerAuthController@verify')->name('verification.verify');

Route::get('/ticket/{uuid}', 'TicketController@generateTicket');

Route::get('/parallax', 'LaraliteController@parallax');

Route::get('/dynamic', 'CmsController@dynamic');

Route::group(['middleware' => 'auth:customers'], function () {
    Route::get('/account', 'CustomerController@account');
    Route::put('/account', 'CustomerController@accountUpdate');
    Route::get('/account/wallet', 'CustomerController@wallet');
    Route::post('/change-password', 'CustomerController@changePassword');
    Route::get('/orders', 'CustomerController@orders');
    Route::get('/orders/detail/{id}', 'CustomerController@orderDetails');
    Route::post('/image/upload','CustomerController@imageUpload');
    Route::post('/claim/item', 'Web\CreditPaymentController@itemClaim');

    Route::get('/account/subscription', 'CustomerSubscriptionController@get');
    Route::post('/account/subscription/{id}', 'CustomerSubscriptionController@cancel');
    Route::post('/subscription/get_payment_token', 'SubscriptionPaymentController@getSubscriptionPaymentIntent');
    Route::post('/subscription/process-payment', 'SubscriptionPaymentController@processPayment');
    Route::get('/subscriptions', 'SubscriptionsController@get');
});

Route::post('/payment/webhook', 'SubscriptionPaymentController@webhook');
Route::post('/process-payment', 'Api\PaymentController@processPayment');
Route::post('/basket/validate', 'Api\PaymentController@validateBasket');


Route::get('/ticket/view/{uuid}', 'TicketController@view');

/**
 * CMS catch-all route
 *
 * !! DO NOT DEFINE ANY ROUTES BENEATH THIS, MUST ALL BE REGISTERED ABOVE
 */
Route::any('/{any}', 'CmsController@route')->where('any', '.*');

