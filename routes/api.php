<?php

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->get('/users', function (Request $request) {

    $hasStatus = in_array($request->status, ['active', 'inactive']);

    /**
     * @var Builder $results
     */
    $results = User::query()->when($hasStatus, function (Builder $q) use ($request) {
        return $q->where('status', $request->status);
    });

    $pagination = $results->paginate(20);

    return $hasStatus ? $pagination->appends('status', $request->status) : $pagination;
});
