<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller{
     public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        $title = "Daftar Kategori";
        $categories = Category::orderBy('name','ASC')->get();
        return view('categories.index', compact('title', 'categories'));
    }

    public function create(){
        $title = "Tambah Kategori";
        return view('categories.create', compact('title'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:categories,name',
        ], [
            'name.unique' => 'Kategori sudah ditambah.',
        ]);

        // Simpan kategori jika validasi lolos
        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('category.index')->with('status', 'Kategori berhasil ditambah.');
    }

    // public function edit($id){
    //     $title = 'Edit Kategori';
    //     $category = Category::findOrFail($id);
    //     return view('categories.edit',compact('category', 'title'));
    // }
    // public function update(Request $request, $id){
    //     $category = Category::findOrFail($id);
    //     $category->name = $request->get('name');
    //     $category->update();

    //     return redirect()->back()->with('status','Anda berhasil mengupdate kategori');

    
    // }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('status','Anda berhasil menghapus kategori');
    }
}