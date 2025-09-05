<?php

namespace App\Http\Controllers;

use App\Models\CarouselSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarouselSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = CarouselSlide::ordered()->get();
        return view('carousel-slides.index', compact('slides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('carousel-slides.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|url',
            'button_text' => 'required|string|max:255',
            'button_url' => 'nullable|url',
            'background_gradient' => 'required|string',
            'badge_text' => 'nullable|string|max:255',
            'badge_color' => 'required|string',
            'price' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        CarouselSlide::create($request->all());

        return redirect()->route('carousel-slides.index')
            ->with('success', 'Slide creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CarouselSlide $carouselSlide)
    {
        return view('carousel-slides.show', compact('carouselSlide'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarouselSlide $carouselSlide)
    {
        return view('carousel-slides.edit', compact('carouselSlide'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarouselSlide $carouselSlide)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'required|url',
            'button_text' => 'required|string|max:255',
            'button_url' => 'nullable|url',
            'background_gradient' => 'required|string',
            'badge_text' => 'nullable|string|max:255',
            'badge_color' => 'required|string',
            'price' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $carouselSlide->update($request->all());

        return redirect()->route('carousel-slides.index')
            ->with('success', 'Slide actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarouselSlide $carouselSlide)
    {
        $carouselSlide->delete();

        return redirect()->route('carousel-slides.index')
            ->with('success', 'Slide eliminado exitosamente.');
    }
}
