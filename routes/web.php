<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PermisssionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\SuppliersController;
use App\Http\Controllers\Admin\UnitsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AIChatbotController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\DueCollectionController;
//ajZMVci2fF.SaSAA
//8BCmjdtvmmT=a?na
//Authentication
Route::get('/login', [AuthController::class, 'index'])->name('login.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('auth.google.callback');
// dashboard pages
Route::middleware('auth:web')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
    })->name('dashboard');

    Route::get('/roles', [RolesController::class, 'index'])->name('admin.roles');
    Route::get('/roles/create', [RolesController::class, 'create'])->name('admin.roles.create');
    Route::post('/roles', [RolesController::class, 'store'])->name('admin.roles.store');
    Route::get('/roles/{role}/permissions', [RolesController::class, 'show'])->name('admin.add.permissions.to.role');
    Route::put('/roles/{role}/permissions', [RolesController::class, 'assignPermission'])->name('admin.roles.update-permissions');
    Route::get('/admin/roles/data', [RolesController::class, 'data'])->name('admin.roles.data');
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',              [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read',    [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all',     [NotificationController::class, 'markAllAsRead'])->name('readAll');
    });


    Route::get('/permissions', [PermisssionController::class, 'index'])->name('admin.permissions');
    Route::get('/permissions/create', [PermisssionController::class, 'create'])->name('admin.permissions.create');
    Route::post('/permissions', [PermisssionController::class, 'store'])->name('admin.permissions.store');

//Sales

    Route::get('/sales', [SalesController::class, 'index'])->name('admin.sales.index');

    Route::get('/sales/data', [SalesController::class, 'data'])->name('admin.sales.data');

    Route::get('/sales-create', [SalesController::class, 'create'])->name('admin.sales.create');

    Route::post('/sales', [SalesController::class, 'store'])->name('admin.sales.store');

    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('admin.sales.show');

    Route::get('/sales/{sale}/edit', [SalesController::class, 'edit'])->name('admin.sales.edit');

    Route::put('/sales/{sale}', [SalesController::class, 'update'])->name('admin.sales.update');

    Route::delete('/sales/{sale}', [SalesController::class, 'destroy'])->name('admin.sales.destroy');

    Route::get('/sales/invoice/{id}', [SalesController::class, 'invoices'])->name('admin.sales.invoice');

//Products
    Route::get('/get-products', [SalesController::class, 'getProducts']);
    Route::get('/get-product-batches/{id}', [SalesController::class, 'getBatches']);

//Supplier
    Route::get('/suppliers', [SuppliersController::class, 'manageSuppliers'])->name('admin.suppliers.manage');

    Route::get('/suppliers/data', [SuppliersController::class, 'data'])
        ->name('admin.suppliers.data');

    Route::get('/suppliers/create', [SuppliersController::class, 'createSuppliers'])->name('admin.supplier.create');

    Route::post('/suppliers', [SuppliersController::class, 'storeSuppliers'])
        ->name('admin.supplier.store');

    Route::get('/suppliers/{supplier}/edit', [SuppliersController::class, 'editSuppliers'])->name('admin.suppliers.edit');

    Route::put('/suppliers/{supplier}', [SuppliersController::class, 'updateSuppliers'])
        ->name('admin.suppliers.update');

// Route::get('/suppliers/{supplier}', [SuppliersController::class, 'showSuppliers'])->name('admin.suppliers.show');

    Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'deleteSuppliers'])->name('admin.suppliers.destroy');
    Route::post('/admin/supplier/status/{id}', [SuppliersController::class, 'updateStatus']);

//Brands
    Route::get('/brands', [BrandController::class, 'brands'])->name('admin.brands.manage');
    Route::get('/brands/data', [BrandController::class, 'data'])->name('admin.brands.data');
    Route::get('/brands/create', [BrandController::class, 'createBrand'])->name('admin.brand.create');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'editBrand'])->name('admin.brand.edit');
    Route::delete('/brands/{brand}', [BrandController::class, 'deleteBrand'])->name('admin.brand.destroy');
    Route::post('/brands', [BrandController::class, 'store'])->name('admin.brand.store');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('admin.brand.update');
    Route::post('/admin/brand/status/{id}', [BrandController::class, 'updateStatus'])->name('admin.brand.status.update');

//Units
    Route::get('/units', [UnitsController::class, 'units'])->name('admin.units.manage');
    Route::post('/units', [UnitsController::class, 'store'])->name('admin.unit.store');
    Route::put('/units/{unit}', [UnitsController::class, 'update'])->name('admin.unit.update');
    Route::get('/units/data', [UnitsController::class, 'data'])->name('admin.units.data');
    Route::get('/units/create', [UnitsController::class, 'createUnit'])->name('admin.unit.create');
    Route::get('/units/{unit}/edit', [UnitsController::class, 'editUnit'])->name('admin.unit.edit');
    Route::delete('/units/{unit}', [UnitsController::class, 'deleteUnit'])->name('admin.unit.destroy');
    Route::post('/admin/unit/status/{id}', [UnitsController::class, 'updateStatus'])->name('admin.unit.status.update');



//product types
    Route::get('/product-types', [ProductTypeController::class, 'productTypes'])->name('admin.product.type.manage');
    Route::get('/product-types/data', [ProductTypeController::class, 'data'])->name('admin.product.types.data');
    Route::get('/product-types/create', [ProductTypeController::class, 'createProductType'])->name('admin.product.type.create');
    Route::get('/product-types/{productType}/edit', [ProductTypeController::class, 'editProductType'])->name('admin.product.type.edit');
    Route::delete('/product-types/{productType}', [ProductTypeController::class, 'deleteProductType'])->name('admin.product.type.destroy')->whereNumber('productType');
    Route::post('/product-types', [ProductTypeController::class, 'storeProductType'])->name('admin.product.type.store');
    Route::put('/product-types/{productType}', [ProductTypeController::class, 'updateProductType'])->name('admin.product.type.update');
    Route::post('/admin/product-type/status/{id}', [ProductTypeController::class, 'updateStatus']);


//categories
    Route::get('/categories', [CategoryController::class, 'categories'])
        ->name('admin.categories.manage');

    Route::get('/categories/create', [CategoryController::class, 'createCategory'])
        ->name('admin.categories.create');

    Route::post('/categories', [CategoryController::class, 'storeCategory'])
        ->name('admin.category.store');

    Route::get('/categories/{category}/edit', [CategoryController::class, 'editCategory'])
        ->name('admin.categories.edit');

    Route::put('/categories/{category}', [CategoryController::class, 'updateCategory'])
        ->name('admin.category.update');

    Route::delete('/categories/{category}', [CategoryController::class, 'deleteCategory'])
        ->name('admin.categories.destroy');

    Route::get('/admin/category/data', [CategoryController::class, 'data'])
        ->name('admin.category.data');
    Route::post('/admin/category/status/{id}', [CategoryController::class, 'updateStatus']);


// Customers
    Route::get('/customers', [CustomerController::class, 'customers'])
        ->name('admin.customers.manage');

    Route::get('/role-permission-mapping', [CustomerController::class, 'rolePermissionMapping'])
        ->name('role.permission.mapping')->middleware('role:admin');

    Route::post('/role-permission-mapping/store',
        [CustomerController::class, 'storeMapping']
    )->name('role.permission.mapping.store');

    Route::get('/users/with/roles/permissions/data', [CustomerController::class, 'userWithRolesPermissionData'])->name('admin.customers.with.roles.permissions.data');
    Route::get('/role-permission-mapping/map/{user}', [CustomerController::class, 'assignEmployeeRole'])
        ->name('admin.assign.role');

    Route::get('/customers/create', [CustomerController::class, 'createCustomer'])
        ->name('admin.customers.create');

    Route::post('/customers', [CustomerController::class, 'storeCustomer'])
        ->name('admin.employee.store');

    Route::get('/customers/{customer}/edit', [CustomerController::class, 'editCustomer'])
        ->name('admin.customers.edit');

    Route::put('/customers/{customer}', [CustomerController::class, 'updateCustomer'])
        ->name('admin.customer.update');

    Route::delete('/customers/{customer}', [CustomerController::class, 'deleteCustomer'])
        ->name('admin.customers.destroy');

    Route::get('/admin/customer/data', [CustomerController::class, 'data'])
        ->name('admin.customer.data');

    Route::post('/admin/customer/status/{id}', [CustomerController::class, 'updateStatus']);

//products
    Route::get('/products', [ProductController::class, 'products'])->name('admin.products.manage');
    Route::get('/products/data', [ProductController::class, 'data'])->name('admin.products.data');
    Route::get('/products/create', [ProductController::class, 'createProduct'])->name('admin.product.create');
    Route::get('/products/{product}/edit', [ProductController::class, 'editProduct'])->name('admin.product.edit');
    Route::get('/products/delete', [ProductController::class, 'deleteProduct'])->name('admin.product.destroy');
    Route::post('/products', [ProductController::class, 'storeProduct'])->name('admin.product.store');
    Route::put('/products/{product}', [ProductController::class, 'updateProduct'])->name('admin.product.update');
    Route::get('/products/stock/{product}/create', [ProductController::class, 'addStock'])->name('admin.product.stock.create');
    Route::get('/products/{product}/batches', [ProductController::class, 'viewStock'])->name('admin.product.batches.view');
    Route::post('/products/stock/{product}/store', [ProductController::class, 'storeStock'])->name('admin.product.stock.store');

    //Customers
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('admin.customers.search');
    Route::get('/customers/invoice', [CustomerController::class, 'invoice'])->name('admin.customers.invoice');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('admin.customers.store');

    //AI chat bot

    Route::get('/ai-chat', [AIChatbotController::class, 'index'])->name('admin.ai-chat.index');
    Route::post('/continue-chat', [AIChatbotController::class, 'continueChat'])->name('admin.ai-chat.continue');


    // profile pages
    Route::get('/profile', [AdminController::class, 'index'])->name('profile');

    // Due collection
    Route::get('/collections', [DueCollectionController::class, 'index'])->name('admin.due.collections');
    Route::get('/due-collections/data', [DueCollectionController::class, 'data'])->name('admin.due.collection.data');
    Route::get('/collections/customers-invoices/{customer}', [DueCollectionController::class, 'customerInvoices'])->name('admin.collections.customers.invoices');
    Route::post('invoice/{sale}/pay-due', [DueCollectionController::class, 'payDue'])->name('admin.invoice.sales.pay-due');

    Route::get('/collections/invoice/{sale}/edit', [DueCollectionController::class, 'edit'])->name('admin.invoice.edit');
    Route::put('sales/{sale}', [DueCollectionController::class, 'update'])->name('admin.sales.update');

    Route::get('/collections/payment-history/{customer}', [DueCollectionController::class, 'paymentHistory'])->name('admin.collections.payment.history');
});


// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');


// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages


Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');






















