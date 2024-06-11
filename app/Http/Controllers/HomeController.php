<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('cars')
            ->orderBy('cars_count', 'desc')
            ->take(5)
            ->get();

        $brandCars = [];
        foreach ($brands as $brand) {
            $brandCars[] = [
                'brand' => $brand,
                'cars' => Car::where('brand_id', $brand->id)
                    ->with('brand', 'location', 'year', 'color')
                    ->take(4)
                    ->get(),
            ];
        }

        $credit = Car::where('credit', 1)
            ->with(['brand'])
            ->inRandomOrder()
            ->take(6)
            ->get([
                'id', 'brand_id', 'name', 'image', 'price', 'credit',
            ]);

        $change = Car::where('change', 1)
            ->with(['brand'])
            ->inRandomOrder()
            ->take(6)
            ->get([
                'id', 'brand_id', 'name', 'image', 'price', 'credit',
            ]);

        return view('home.index')
            ->with([
                'brandCars' => $brandCars,
                'change' => $change,
                'credit' => $credit,
            ]);
    }


    public function locale($locale)
    {
        if ($locale == 'en') {
            session()->put('locale', 'en');
            return redirect()->back();
        }

        elseif ($locale == 'tm') {
            session()->put('locale', 'tm');
            return redirect()->back();
        }

        elseif ($locale == 'ru') {
            session()->put('locale', 'ru');
            return redirect()->back();
        }

        elseif ($locale == 'uk') {
            session()->put('locale', 'uk');
            return redirect()->back();
        }

        elseif ($locale == 'tr') {
            session()->put('locale', 'tr');
            return redirect()->back();
        }

        else {
            return redirect()->back();
        }
    }
}
