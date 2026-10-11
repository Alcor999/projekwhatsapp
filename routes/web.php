<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NotificationController::class, 'index'])->name('dashboard');
Route::get('/master-customer', [NotificationController::class, 'masterCustomer'])->name('customers.master');
Route::get('/master-dokumen', [NotificationController::class, 'masterDocument'])->name('documents.master');
Route::get('/kirim-pesan', [NotificationController::class, 'sendMessage'])->name('messages.menu');

// Customer CRUD
Route::post('/customers', [NotificationController::class, 'storeCustomer'])->name('customers.store');
Route::put('/customers/{customer}', [NotificationController::class, 'updateCustomer'])->name('customers.update');
Route::delete('/customers/{customer}', [NotificationController::class, 'destroyCustomer'])->name('customers.destroy');
Route::post('/import-customers', [NotificationController::class, 'importCustomers'])->name('customers.import');

// Document CRUD
Route::post('/upload-document', [NotificationController::class, 'uploadDocument'])->name('documents.upload');
Route::put('/documents/{document}', [NotificationController::class, 'updateDocument'])->name('documents.update');
Route::delete('/documents/{document}', [NotificationController::class, 'destroyDocument'])->name('documents.destroy');

// Send Notifications
Route::post('/send-notifications', [NotificationController::class, 'sendNotifications'])->name('notifications.send');
Route::post('/save-template', [NotificationController::class, 'saveTemplate'])->name('template.save');
