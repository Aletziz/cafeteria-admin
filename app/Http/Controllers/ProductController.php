<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Mostrar lista de productos
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filtro por búsqueda
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filtro por disponibilidad
        if ($request->filled('available')) {
            $query->where('available', $request->available);
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }
    
    /**
     * Mostrar formulario para crear producto
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
    
    /**
     * Guardar nuevo producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'available' => 'boolean'
        ]);
        
        $data = $request->all();
        $data['available'] = $request->has('available');
        
        // Manejar imagen
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            
            // Guardar en cafeteria-admin
            $image->move(public_path('images/products'), $imageName);
            
            // Copiar también a cafeteria-app para que sea accesible desde ambas aplicaciones
            $adminImagePath = public_path('images/products/' . $imageName);
            $appImagePath = dirname(dirname(dirname(__DIR__))) . '/cafeteria-app/public/images/products/' . $imageName;
            
            // Crear directorio si no existe
            $appImageDir = dirname($appImagePath);
            if (!file_exists($appImageDir)) {
                mkdir($appImageDir, 0755, true);
            }
            
            // Copiar imagen
            copy($adminImagePath, $appImagePath);
            
            $data['image'] = 'images/products/' . $imageName;
        }
        
        Product::create($data);
        
        return redirect()->route('products.index')
                        ->with('success', 'Producto creado exitosamente.');
    }
    
    /**
     * Mostrar producto específico
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }
    
    /**
     * Mostrar formulario para editar producto
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }
    
    /**
     * Actualizar producto
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'available' => 'boolean'
        ]);
        
        $data = $request->all();
        $data['available'] = $request->has('available');
        
        // Manejar imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe en ambas aplicaciones
            if ($product->image) {
                $adminOldPath = public_path($product->image);
                $appOldPath = dirname(dirname(dirname(__DIR__))) . '/cafeteria-app/public/' . $product->image;
                
                if (file_exists($adminOldPath)) {
                    unlink($adminOldPath);
                }
                if (file_exists($appOldPath)) {
                    unlink($appOldPath);
                }
            }
            
            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $image->getClientOriginalExtension();
            
            // Guardar en cafeteria-admin
            $image->move(public_path('images/products'), $imageName);
            
            // Copiar también a cafeteria-app
            $adminImagePath = public_path('images/products/' . $imageName);
            $appImagePath = dirname(dirname(dirname(__DIR__))) . '/cafeteria-app/public/images/products/' . $imageName;
            
            // Crear directorio si no existe
            $appImageDir = dirname($appImagePath);
            if (!file_exists($appImageDir)) {
                mkdir($appImageDir, 0755, true);
            }
            
            // Copiar imagen
            copy($adminImagePath, $appImagePath);
            
            $data['image'] = 'images/products/' . $imageName;
        }
        
        $product->update($data);
        
        return redirect()->route('products.index')
                        ->with('success', 'Producto actualizado exitosamente.');
    }
    
    /**
     * Eliminar producto
     */
    public function destroy(Product $product)
    {
        // Eliminar imagen si existe en ambas aplicaciones
        if ($product->image) {
            $adminImagePath = public_path($product->image);
            $appImagePath = dirname(dirname(dirname(__DIR__))) . '/cafeteria-app/public/' . $product->image;
            
            if (file_exists($adminImagePath)) {
                unlink($adminImagePath);
            }
            if (file_exists($appImagePath)) {
                unlink($appImagePath);
            }
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
                        ->with('success', 'Producto eliminado exitosamente.');
    }
    
    /**
     * Cambiar disponibilidad del producto
     */
    public function toggleAvailability(Product $product)
    {
        $product->update([
            'available' => !$product->available
        ]);
        
        $status = $product->available ? 'disponible' : 'no disponible';
        
        return response()->json([
            'success' => true,
            'message' => "Producto marcado como {$status}",
            'available' => $product->available
        ]);
    }
}
