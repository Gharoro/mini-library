<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use Validator;

class AuthorsController extends Controller
{
    public $success = 200;
    public $clientError = 400;
    public $notFound = 404;
    public $serverError = 500;

    /**
     * Creates a new author
     *
     * @return \Illuminate\Http\Response
     */
    public function add_author(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $input = $request->all();
        if (Author::where('email', '=', $input['email'])->exists()) {
            return response()->json([
                'status' => $this->clientError,
                'error' => 'Email already exist, please use a different email'
            ]);
        }
        $author = Author::create($input);
        if ($author) {
            return response()->json([
                'status' => $this->success,
                'message' => 'Author created successfuly',
                'author' => $author
            ]);
        } else {
            return response()->json([
                'status' => $this->serverError,
                'error' => 'Unable to add author at the moment'
            ]);
        }
    }
}
