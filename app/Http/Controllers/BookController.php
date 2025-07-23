<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index()
    {
        return Book::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'year' => 'required|integer',
            'pages' => 'required|integer',
            'description' => 'required|string',
        ]);

        $validated = array_merge($validated, ['user_id' => $request['user']['id']]);

        Log::info($validated['user_id']);

        return Book::create($validated);
    }

    public function show($id)
    {
        $book = Book::find($id);


        return $book;
    }

    public function update(Request $request, Book $book)
    {
        $book->update($request->only([
            'title',
            'author',
            'year',
            'pages',
            'description'
        ]));

        return $book;
    }

    public function destroy(Book $book)
    {

        $book->delete();
        return response()->noContent();
    }
}
