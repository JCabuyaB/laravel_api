<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();

        return response()->json($books, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // validar datos
            $validated = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'autor' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'publish_date' => ['required', 'date']
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validated->errors(),
                    'status' => 400,
                ], 400);
            }

            $book = Book::create($request->all());
            if(!$book){
                return  response()->json([
                    'message' => 'error al crear el estudiante',
                    'status' => 500
                ], 500);
            }
            
            return response()->json([
                'message' => $book,
                'status' => 201
            ], 201);

        } catch (\Exception $e) {
            // excepción no relacionada con la validación.
            return response()->json([
                'message' => 'Ocurrio un error inesperado',
                'status' => 500
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);
        try {
            if (!empty($book)) {
                return response()->json([
                    'status' => 200,
                    'message' => $book
                ], 200);
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => 'No se encontró el libro'
                ], 204);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Ocurrió un error inesperado'
            ], 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Book::where('id', $id)->exists()) {

            $validated = Validator::make($request->all(), [
                'title' => 'string|max:255',
                'autor' => 'string|max:255|regex:/^[a-zA-Z\s]+$/',
                'publish_date' => 'date|regex:/^[0-9-.\/]+$/'
            ]);

            if($validated->fails()){
                return response()->json([
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validated->errors(),
                    'status' => 400,
                ], 400);
            }

            $book = Book::find($id);
            $book->title = is_null($request->title) ? $book->title : $request->title;
            $book->autor = is_null($request->autor) ? $book->autor : $request->autor;
            $book->publish_date = is_null($request->publish_date) ? $book->publish_date : $request->publish_date;
            $book->save();

            return response()->json([
                'message' => 'Libro actualizado',
                'book' => $book, 
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Libro no encontrado',
                'status' => 204
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Book::where('id', '=',  $id)->exists()){
            $book = Book::find($id);
            $book->delete();

            return response()->json([
                'message' => 'Libro eliminado',
                'book' => $book,
                'status' => 200,
            ]);

        }else{
            return response()->json([
                'status' => 204,
                'message' => 'El libro no existe'
            ]);
        }
    }
}
