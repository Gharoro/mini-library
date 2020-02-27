<?php

use Illuminate\Http\Request;

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

/**
 *  Add Author Route
 */
Route::prefix('v1/author')->group(function () {
    Route::post('/add', 'AuthorsController@add_author');
});

/**
 *  Book Routes
 */
Route::prefix('v1/books')->group(function () {
    /** Add Books Route */
    Route::post('/{authorId}/add', 'BooksController@add_book');

    /** Get All Books Route */
    Route::get('/', 'BooksController@get_books');

    /** Get Single Book Route */
    Route::get('/{bookId}', 'BooksController@get_book');

    /** Delete Book Route */
    Route::delete('/{bookId}/delete', 'BookController@delete_books');
});
