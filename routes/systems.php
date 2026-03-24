<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Rocont\OrchidRepeaterField\Http\Controllers\Systems\RepeaterController;

Route::post('repeater', [RepeaterController::class, 'view'])->name('systems.repeater');
