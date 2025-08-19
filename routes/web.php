<?php

use App\Http\Controllers\ActsController;
use App\Http\Controllers\AppearerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleRedirectController;
use App\Http\Controllers\LoginRedirectController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DenominationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\InstrumentActController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\NoticeTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkTeamController;
use App\Models\Appearer;
use App\Models\InstrumentAct;

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});
Route::get('/logout', [CustomLoginController::class, 'logout'])->name('logout');

Route::post('/login', [CustomLoginController::class, 'login'])->name('custom.login');

// Rutas protegidas
Route::middleware(['auth', 'active'])->group(function () {

    Route::middleware('role:administrator|operator|technical_support')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('administrator.home');
        })->name('dashboard.admin');


        Route::get('/clients', [ClientController::class, 'index'])
            ->name('clients.admin');

        Route::post('/clients', [ClientController::class, 'store'])
            ->name('clients.admin');

        Route::put('/clients', [ClientController::class, 'update'])
            ->name('clients.admin.update');


        Route::delete('/clients', [ClientController::class, 'destroy'])
            ->name('clients.admin.delete');

        Route::post('/clients/dataTable', [ClientController::class, 'dataTable'])
            ->name('clients.admin.datatable');



        Route::get('/denominations', function () {
            return view('administrator.denominations');
        })->name('denominations.admin');

        Route::post('/denominations', [DenominationController::class, 'store'])
            ->name('denominations.admin.store');

        Route::put('/denominations', [DenominationController::class, 'update'])
            ->name('denominations.admin.update');

        Route::delete('/denominations', [DenominationController::class, 'destroy'])
            ->name('denominations.admin.delete');

        Route::post('/denominations/dataTable', [DenominationController::class, 'dataTable'])
            ->name('denominations.admin.datatable');


        Route::get('/acts', [ActsController::class, 'index'])
            ->name('acts.admin');

        Route::post('/acts', [ActsController::class, 'store'])
            ->name('acts.admin.store');

        Route::put('/acts', [ActsController::class, 'update'])
            ->name('acts.admin.update');

        Route::delete('/acts', [ActsController::class, 'destroy'])
            ->name('acts.admin.delete');

        Route::post('/acts/dataTable', [ActsController::class, 'dataTable'])
            ->name('acts.admin.datatable');


        Route::get('/notice_type', [NoticeTypeController::class, 'index'])
            ->name('notice_type.admin');


        Route::post('/notice_type', [NoticeTypeController::class, 'store'])
            ->name('notice_type.admin.store');

        Route::put('/notice_type', [NoticeTypeController::class, 'update'])
            ->name('notice_type.admin.update');

        Route::delete('/notice_type', [NoticeTypeController::class, 'destroy'])
            ->name('notice_type.admin.delete');

        Route::post('/notice_type/dataTable', [NoticeTypeController::class, 'dataTable'])
            ->name('notice_type.admin.datatable');


        //
        Route::get('/file_type', [FileTypeController::class, 'index'])
            ->name('file_type.admin');


        Route::post('/file_type', [FileTypeController::class, 'store'])
            ->name('file_type.admin.store');

        Route::put('/file_type', [FileTypeController::class, 'update'])
            ->name('file_type.admin.update');

        Route::delete('/file_type', [FileTypeController::class, 'destroy'])
            ->name('file_type.admin.delete');

        Route::post('/file_type/dataTable', [FileTypeController::class, 'dataTable'])
            ->name('file_type.admin.datatable');

        //
        Route::get('/work_team', [WorkTeamController::class, 'index'])
            ->name('work_team.admin');


        Route::post('/work_team', [WorkTeamController::class, 'store'])
            ->name('work_team.admin.store');

        Route::put('/work_team', [WorkTeamController::class, 'update'])
            ->name('work_team.admin.update');

        Route::delete('/work_team', [WorkTeamController::class, 'destroy'])
            ->name('work_team.admin.delete');

        Route::post('/work_team/dataTable', [WorkTeamController::class, 'dataTable'])
            ->name('work_team.admin.datatable');


        //
        Route::get('/payment_method', [PaymentMethodController::class, 'index'])
            ->name('payment_method.admin');


        Route::post('/payment_method', [PaymentMethodController::class, 'store'])
            ->name('payment_method.admin.store');

        Route::put('/payment_method', [PaymentMethodController::class, 'update'])
            ->name('payment_method.admin.update');

        Route::delete('/payment_method', [PaymentMethodController::class, 'destroy'])
            ->name('payment_method.admin.delete');

        Route::post('/payment_method/dataTable', [PaymentMethodController::class, 'dataTable'])
            ->name('payment_method.admin.datatable');




        //
        Route::get('/user', [UserController::class, 'index'])
            ->name('user.admin');


        Route::post('/user', [UserController::class, 'store'])
            ->name('user.admin.store');

        Route::put('/user', [UserController::class, 'update'])
            ->name('user.admin.update');

        Route::delete('/user', [UserController::class, 'destroy'])
            ->name('user.admin.delete');

        Route::post('/user/dataTable', [UserController::class, 'dataTable'])
            ->name('user.admin.datatable');

        Route::put('/reset_password', [UserController::class, 'resetPassword'])
            ->name('user.admin.reset_password');

          
        Route::put('/change_password', [UserController::class, 'updatePassword'])
        ->name('user.admin.update_password');


        //
        Route::get('/instrument', [InstrumentController::class, 'index'])
            ->name('instrument.admin');

        Route::get('/instrument_report', [InstrumentController::class, 'report'])
            ->name('instrument.admin.report');

        Route::post('/instrument', [InstrumentController::class, 'store'])
            ->name('instrument.admin.store');


        Route::get('/instrument/{id}', [InstrumentController::class, 'edit'])
            ->name('instrument.admin.edit');

        Route::put('/instrument', [InstrumentController::class, 'update'])
            ->name('instrument.admin.update');

            Route::put('/instrument_submission', [InstrumentController::class, 'updateSubmission'])
            ->name('instrument.admin.update.submission');


        Route::delete('/instrument', [InstrumentController::class, 'destroy'])
            ->name('instrument.admin.delete');

        Route::post('/instrument/dataTable', [InstrumentController::class, 'dataTable'])
            ->name('instrument.admin.datatable');

    

        Route::get('/instrument/extracts/{min}/{max}/{format}', [InstrumentController::class, 'exportExtracts'])
        ->name('instrument.admin.extracts');


        //
        Route::post('/instrument_act', [InstrumentActController::class, 'store'])
            ->name('instrument_act.admin.store');

        Route::put('/instrument_act', [InstrumentActController::class, 'update'])
            ->name('instrument_act.admin.update');

        Route::delete('/instrument_act', [InstrumentActController::class, 'destroy'])
            ->name('instrument_act.admin.delete');

        Route::post('/instrument_act/dataTable', [InstrumentActController::class, 'dataTable'])
            ->name('instrument_act.admin.datatable');


        //
        Route::post('/appearer', [AppearerController::class, 'store'])
            ->name('appearer.admin.store');


        Route::delete('/appearer', [AppearerController::class, 'destroy'])
            ->name('appearer.admin.delete');


        Route::post('/appearer/dataTable', [AppearerController::class, 'dataTable'])
            ->name('appearer.admin.datatable');



        Route::put('/appearer', [AppearerController::class, 'update'])
            ->name('appearer.admin.update');
        //


        //

        Route::get('/file', [FileController::class, 'index'])
            ->name('file.admin');


        Route::post('/file', [FileController::class, 'store'])
            ->name('file.admin.store');

        Route::put('/file', [FileController::class, 'update'])
            ->name('file.admin.update');

        Route::delete('/file', [FileController::class, 'destroy'])
            ->name('file.admin.delete');

        Route::post('/file/dataTable', [FileController::class, 'dataTable'])
            ->name('file.admin.datatable');


        //

        Route::get('/payment', [PaymentController::class, 'index'])
            ->name('payment.admin');


        Route::post('/payment', [PaymentController::class, 'store'])
            ->name('payment.admin.store');

        Route::put('/payment', [PaymentController::class, 'update'])
            ->name('payment.admin.update');

        Route::delete('/payment', [PaymentController::class, 'destroy'])
            ->name('payment.admin.delete');

        Route::post('/payment/dataTable', [PaymentController::class, 'dataTable'])
            ->name('payment.admin.datatable');

        Route::get('/payment/print/{id}', [PaymentController::class, 'generatePdf'])
            ->name('payment.admin.print');


        Route::get('/payment_report', [PaymentController::class, 'report'])
            ->name('payment.admin.report');

        Route::post('/payment_report', [PaymentController::class, 'reportData'])
            ->name('payment.admin.report.data');

        Route::post('/payment_report/dataTable', [PaymentController::class, 'reportDataTable'])
            ->name('payment.admin.report.datatable');

        //

        Route::get('/notification', [NotificationController::class, 'index'])
            ->name('notification.admin');


        Route::post('/notification', [NotificationController::class, 'store'])
            ->name('notification.admin.store');

        Route::put('/notification', [NotificationController::class, 'update'])
            ->name('notification.admin.update');

        Route::delete('/notification', [NotificationController::class, 'destroy'])
            ->name('notification.admin.delete');

        Route::post('/notification/dataTable', [NotificationController::class, 'dataTable'])
            ->name('notification.admin.datatable');



        //

        Route::get('/canceled', [InstrumentController::class, 'canceled'])
            ->name('canceled.admin');


        //

        Route::get('/instrument_act', [InstrumentActController::class, 'index'])
            ->name('instrument_act.admin');



        Route::post('/instrument_act/dataTable/index', [InstrumentActController::class, 'dataTableIndex'])
            ->name('instrument_act.admin.datatable.index');

            
        ////////////////////////////////////////

        Route::get('/calendar', [CalendarController::class, 'index'])
            ->name('calendar.admin');



        Route::post('/calendar', [CalendarController::class, 'update'])
            ->name('calendar.admin.update');

     
        Route::post('/calendar/dataTable', [CalendarController::class, 'dataTable'])
            ->name('calendar.admin.datatable');


        //
    });



    Route::get('/dashboard/operator', function () {
        return view('operator.home');
    })->name('dashboard.operator')->middleware('role:operator');

    Route::get('/dashboard/support', function () {
        return view('technical_support.home');
    })->name('dashboard.support')->middleware('role:technical_support');
});
