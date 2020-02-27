<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\Author;
use Validator;

class BooksController extends Controller
{
    public $success = 200;
    public $clientError = 400;
    public $notFound = 404;
    public $serverError = 500;

    /**
     * Creates a new book
     *
     * @return \Illuminate\Http\Response
     */
    public function add_book(Request $request, $authorId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'book_title' => 'required',
                'num_of_pages' => 'required'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $input = $request->all();
        if (!is_numeric($authorId)) {
            return response()->json([
                'status' => $this->clientError,
                'error' => 'Author Id must be an integer',
            ]);
        }
        $integerId = (int) $authorId;
        $author = Author::find($integerId);
        if (!$author) {
            return response()->json([
                'status' => $this->notFound,
                'error' => 'Author does not exist'
            ]);
        } else {
            $book = Book::create([
                'author_id' => $authorId,
                'book_title' => $input['book_title'],
                'num_of_pages' => $input['num_of_pages']
            ]);
            if ($book) {
                return response()->json([
                    'status' => $this->success,
                    'message' => 'Book created successfuly',
                    'book' => $book
                ]);
            }
            return response()->json([
                'status' => $this->serverError,
                'error' => 'Unable to book at the moment'
            ]);
        }
    }


    /**
     * Fetch all books
     *
     *  @return \Illuminate\Http\Response
     */
    public function get_books()
    {
        $books = Book::orderBy('id', 'DESC')->paginate(1);
        if (sizeof($books) < 1) {
            return response()->json([
                'status' => $this->notFound,
                'error' => 'No book found'
            ]);
        } else {
            return response()->json([
                'status' => $this->success,
                'message' => 'Success',
                'books' => $books
            ]);
        }
    }

    /**
     * Fetch one book
     *
     * @param  int  $bookId
     * @return \Illuminate\Http\Response
     */
    public function get_book($bookId)
    {
        if (!is_numeric($bookId)) {
            return response()->json([
                'status' => $this->clientError,
                'error' => 'Book Id must be an integer',
            ]);
        }
        $integerId = (int) $bookId;
        $book = Book::find($integerId);
        if (!$book) {
            return response()->json([
                'status' => $this->notFound,
                'error' => 'Book not found'
            ]);
        } else {
            return response()->json([
                'status' => $this->success,
                'message' => 'Success',
                'book' => $book
            ]);
        }
    }

    /**
     * Delete Book
     *
     * @param  int  $bookId
     * @return \Illuminate\Http\Response
     */
    public function delete_book($bookId)
    {

        if (!is_numeric($bookId)) {
            return response()->json([
                'status' => $this->clientError,
                'error' => 'Book Id must be an integer',
            ]);
        }
        $integerId = (int) $bookId;
        $book = Book::find($integerId);
        if (!$book) {
            return response()->json([
                'status' => $this->notFoundStatus,
                'error' => 'Book does not exist'
            ]);
        } else {
            $deleted = $book->delete();
            if ($deleted) {
                return response()->json([
                    'status' => $this->success,
                    'message' => 'Book successfuly deleted'
                ]);
            }
            return response()->json([
                'status' => $this->serverError,
                'error' => 'Unable to delete book'
            ]);
        }
    }
}
