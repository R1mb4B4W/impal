<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class ProductController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $title = 'Master Product';
        $products = Product::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.index', compact('title', 'products', 'categories'));
    }
    public function create()
    {
        $title = 'Create Product';
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.index', compact('title', 'categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:200',
            'price' => 'required|numeric',
            'stoks' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'name.max' => 'Nama produk maksimal 50 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.max' => 'Deskripsi maksimal 200 karakter',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'category_id.required' => 'Isi kategori',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'image.required' => 'Gambar produk wajib diupload',
            'image.image' => 'File harus berupa gambar',
            'image.max' => 'Ukuran gambar maksimal 10MB',
            'image.mimes' => 'Format gambar harus jpg/jpeg/png',
        ]);

        $input = new Product;
        $input->id = $request->id;
        $input->description = $request->description;
        $input->name = $request->name;
        $input->price = $request->price;
        $input->stock = 1; // atau bisa disesuaikan
        $input->stoks = $request->stoks;

        if ($request->hasFile('image')) {
            $imageName = Str::slug($input->name, '-') . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'), $imageName);
            $input->image = '/upload/products/' . $imageName;
        }

        $input->category_id = $request->category_id;
        $input->save();

        return redirect()->back()->with('status', 'Anda berhasil menambahkan product');
    }


    public function edit($id)
    {
        $title = 'Edit Product';
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'title', 'categories'));
    }
    public function update(Request $request, $id)
    {
        // Validasi Input
        $request->validate([
            'name' => 'required|max:50',
            'description' => 'required|max:200',
            'price' => 'required|numeric',
            'stoks' => 'required|in:0,1',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ], [
            'name.required' => 'Nama produk wajib diisi',
            'name.max' => 'Nama produk maksimal 50 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.max' => 'Deskripsi maksimal 200 karakter',
            'price.required' => 'Harga wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'category_id.required' => 'Isi kategori',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'image.image' => 'File harus berupa gambar',
            'image.max' => 'Ukuran gambar maksimal 10MB',
            'image.mimes' => 'Format gambar harus jpg/jpeg/png',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stoks = $request->stoks;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $imageName = Str::slug($product->name, '-') . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/upload/products/'), $imageName);
            $product->image = '/upload/products/' . $imageName;
        }

        $product->save();

        return redirect()->route('product.index')->with('status', 'Anda berhasil mengubah data produk');
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if (!$product->image == NULL) {
            unlink(public_path($product->image));
        }
        Product::destroy($id);
        return redirect()->back()->with('status', 'Anda berhasil menghapus produk' . $product->name);
    }
    public function detail($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.detail', compact('product'));
    }
    public function changeStoks($id)
    {
        $product = Product::findOrFail($id);
        if ($product->stoks == 0) {
            $product->stoks = 1;
        } else {
            $product->stoks = 0;
        }
        $product->save();
        return redirect()->back()->with('berhasil diubah');
    }
    public function produk()
    {
        return view('products.index2');
    }

    public function produkData()
    {
        $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name as pname',
                'categories.name as cname',
                'products.description',
                'products.price',
                'products.stoks',
                'products.image'
            );
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $actiona = '<a href="' . route('product.edit', $data->id) . '" id="action" class="btn btn-xs btn-success">
    <i class="fa-solid fa-pen-to-square"></i></a>';
                $actionb = '<a href="' . route('product.detail', $data->id) . '" id="action" class="btn btn-xs btn-warning" >
    <i class="fa-solid fa-circle-info"></i></a>';
                return $actiona . $actionb;
            })
            ->addIndexColumn()
            ->editColumn('image', function ($data) {
                return '<img src=" ' . url($data->image) . ' "/>';
            })
            ->editColumn('stoks', function ($data) {
                if ($data->stoks == 0) {
                    $actiona = '<a href="' . route('change.stoks', $data->id) . '" class="btn btn-xs btn-danger" >Habis</a>';
                } else {
                    $actiona = '<a href="' . route('change.stoks', $data->id) . '" class="btn btn-xs btn-primary" >Ada</a>';
                }
                return $actiona;
            })
            ->editColumn('price', function ($data) {
                return 'Rp. ' . number_format($data->price, 0) . ' ';
            })
            ->rawColumns(['image', 'action', 'stoks'])
            ->make(true);
    }
}
