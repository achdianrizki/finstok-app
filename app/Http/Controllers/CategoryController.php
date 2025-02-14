<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(5);

        return view('manager.categories.index', compact('categories'));
    }

    public function getCategories(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(5);

        return response()->json($categories);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manager.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validatedData = $request->validated();

        Category::create($validatedData);

        toast('Kategori berhasil ditambahkan', 'success');
        return redirect()->route('manager.other.categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validated();
        $category->update($validatedData);


        toast('Kategori berhasil diupdate', 'success');
        return redirect()->route('manager.other.categories.index')->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        toast('Kategori berhasil dihapus', 'success');
        return redirect()->route('manager.other.categories.index')->with('success', 'Kategori berhasil dihapus');
    }

    public function search(Request $request)
    {
        $search = $request->get('name');
        $categories = Category::where('name', 'LIKE', "%$search%")->get(['id', 'name']);

        return response()->json($categories);
    }

    public function storeinput(Request $request)
    {
        $name = $request->input('name');

        $category = Category::create([
            'name' => $name,
        ]);
        return response()->json($category);
    }
}
