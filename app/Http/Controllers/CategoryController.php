<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //redirect to category list 
    public function list() {
        $categories = Category::when(request('key'), function($query){
                        $query->where('name', 'like', '%'. request('key'). '%');
        })
        ->orderBy('id', 'desc')
        ->paginate(4);
        return view('admin.category.list', compact('categories'));
    }

    public function createPage() {
        return view('admin.category.create');
    }

    public function create(Request $request) {
       $this->categoryValidation($request);
       $categories = $this->getCategoryData($request);
       Category::create($categories);
       return redirect()->route('category#list')->with(['success' => 'Category created successfully']);
    }

    public function edit($id) {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request) {
        $this->categoryValidation($request);
        $category = $this->getCategoryData($request);
        Category::where('id', $request->id)->update($category);
        return redirect()->route('category#list')->with(['success' => 'Category updated successfully ']);
    }

    public function delete($id) {
        Category::where('id', $id)->delete();
        return back()->with(['deleteMessage' => 'Category deleted successfully']);
    }

    //helper functions for checking validation 

    private function categoryValidation($request) {
        Validator::make($request->all(), [
            'categoryName' => 'required|min:4|unique:categories,name,'.$request->id,
        ],[
            'categoryName.required' => 'Category Name should be filled',
        ])->validate();
    }

    //helper function for converting data to array 
    private function getCategoryData($request) {
        return [
            'name' => $request->categoryName
        ];
    }
}
