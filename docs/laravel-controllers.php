<?php

// app/Http/Controllers/Api/Auth/RegisterController.php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function registerRefinery(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::defaults()],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'refinery',
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    // Add other registration methods for marketer, driver, customer
}

// app/Http/Controllers/Api/Admin/ProductTypeController.php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        return ProductType::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:product_types,name'],
            'description' => ['nullable', 'string'],
        ]);

        $productType = ProductType::create($validated);

        return response()->json($productType, 201);
    }

    public function update(Request $request, ProductType $productType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:product_types,name,' . $productType->id],
            'description' => ['nullable', 'string'],
        ]);

        $productType->update($validated);

        return response()->json($productType);
    }
}

// app/Http/Controllers/Api/Refinery/ProductController.php
namespace App\Http\Controllers\Api\Refinery;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return Product::where('refinery_id', auth()->user()->refinery->id)->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type_id' => ['required', 'exists:product_types,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,active'],
        ]);

        $product = Product::create([
            ...$validated,
            'refinery_id' => auth()->user()->refinery->id,
            'added_by' => auth()->id(),
        ]);

        return response()->json($product, 201);
    }

    // Add other methods...
}

// Continue with more controllers...
// Add controllers for other features and endpoints
