<?php

use App\Http\Controllers\Api\HouseController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ExpenseCategoryController;
use App\Http\Controllers\API\OccupantController;
use App\Http\Controllers\HouseOccupantHistoryController;
use App\Models\Occupant;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsController;

Route::apiResource('news', NewsController::class);

Route::apiResource('house', HouseController::class);

Route::apiResource('occupant', OccupantController::class);

Route::apiResource('houseoccupanthistory', HouseOccupantHistoryController::class);

Route::apiResource('expense', ExpenseController::class);

Route::apiResource('expense-category', ExpenseCategoryController::class);