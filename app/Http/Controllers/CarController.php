<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Car;
use App\Models\Color;
use App\Models\Location;
use App\Models\Option;
use App\Models\Year;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Str;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
            'brands' => 'nullable|array|min:0',
            'brands.*' => 'nullable|integer|min:1',
            'locations' => 'nullable|array|min:0',
            'locations.*' => 'nullable|integer|min:1',
            'years' => 'nullable|array|min:0',
            'years.*' => 'nullable|integer|min:1',
            'color' => 'nullable|integer|min:0',
            'sort' => 'nullable|string|in:new-to-old,old-to-new,low-to-high,high-to-low',
            'credit' => 'nullable|boolean',
            'value' => 'nullable|array', // values => v
            'value.*' => 'nullable|array', // values[] => v.*
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|in:15,30,60,120',
        ]);

        $search = $request->q ?: null;
        $f_brands = $request->has('brands') ? $request->brands : [];
        $f_locations = $request->has('locations') ? $request->locations : [];
        $f_years = $request->has('years') ? $request->years : [];
        $f_color = $request->has('color') ? $request->color : 0;
        $f_sort = $request->has('sort') ? $request->sort : null;
//        $f_credit = $request->has('credit') ?: null;
        $f_page = $request->has('page') ? $request->page : 1;
        $f_perPage = $request->has('perPage') ? $request->perPage : 15;

        $cars = Car::when($f_brands, function ($query) use ($f_brands) {
            $query->whereIn('brand_id', $f_brands);
        })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->orWhere('name', 'ilike', '%' . $search . '%');
                });
            })
            ->when($f_locations, function ($query) use ($f_locations) {
                $query->whereIn('location_id', $f_locations);
            })
            ->when($f_years, function ($query) use ($f_years) {
                $query->whereIn('year_id', $f_years);
            })
            ->when($f_color, function ($query) use ($f_color) {
                $query->where('color_id', $f_color);
            })
            ->with('brand', 'location', 'year', 'color')
            ->when(isset($f_sort), function ($query) use ($f_sort) {
                if ($f_sort == 'old-to-new') {
                    $query->orderBy('id');
                } elseif ($f_sort == 'high-to-low') {
                    $query->orderBy('price', 'desc');
                } elseif ($f_sort == 'low-to-high') {
                    $query->orderBy('price');
                } else {
                    $query->orderBy('id', 'desc');
                }
            }, function ($query) {
                $query->orderBy('id', 'desc');
            })
            ->paginate($f_perPage, ['*'], 'page', $f_page)
            ->withQueryString();

        $brands = Brand::orderBy('name')
            ->get();
        $locations = Location::orderBy('name')
            ->get();
        $years = Year::orderBy('name')
            ->get();
        $colors = Color::orderBy('name')
            ->get();

        return view('car.index')
            ->with([
                'cars' => $cars,
                'brands' => $brands,
                'locations' => $locations,
                'years' => $years,
                'colors' => $colors,
                'f_brands' => $f_brands,
                'f_locations' => $f_locations,
                'f_years' => $f_years,
                'f_color' => $f_color,
                'f_sort' => $f_sort,
//                'credit' => $f_credit,
                'f_perPage' => $f_perPage,
            ]);
    }


    public function show($id)
    {
        $car = Car::with('user', 'brand', 'location', 'year', 'color', 'values.option')
            ->findOrFail($id);

            $similar2 = Car::where('id', '!=', $id)
                ->with('brand', 'location', 'year', 'color', 'values.option')
                ->take(4)
                ->get();

            $similar = Car::where('brand_id', $car->brand->id)
                ->where('location_id', $car->location->id)
                ->where('id', '!=', $id)
                ->where('year_id', $car->year->id)
                ->with('brand', 'location', 'year', 'color', 'values.option')
                ->take(4)
                ->get();


        return view('car.show')
            ->with([
                'car' => $car,
                'similar' => $similar,
                'similar2' => $similar2,
            ]);
    }



    public function favorite($id)
    {
        $car = Car::where('id', $id)
            ->firstOrFail();

        if (Cookie::has('store_favorites')) {
            $cookies = explode(",", Cookie::get('store_favorites'));
            if (in_array($car->id, $cookies)) {
                $car->decrement('favorited');
                $index = array_search($car->id, $cookies);
                unset($cookies[$index]);
            } else {
                $car->increment('favorited');
                $cookies[] = $car->id;
            }
            Cookie::queue('store_favorites', implode(",", $cookies), 60 * 24);
        } else {
            $car->increment('favorited');
            Cookie::queue('store_favorites', $car->id, 60 * 24);
        }

        return redirect()->back();
    }


    public function create()
    {
        $brands = Brand::orderBy('id')
            ->get();
        $colors = Color::orderBy('id')
            ->get();
        $locations = Location::orderBy('id')
            ->get();
        $years = Year::orderBy('id')
            ->get();
        $options = Option::with(['values'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('car.create', [
            'brands' => $brands,
            'colors' => $colors,
            'locations' => $locations,
            'years' => $years,
            'options' => $options,
        ]);
    }


    public function store(Request $request)
    {
        $request->validatedData = $request -> validate ([
            'brand_id' => 'required|max:255',
            'color_id' => 'required|max:255',
            'location_id' => 'required|max:255',
            'year_id' => 'required|max:255',
            'probeg' => 'required|string|max:255',
            'vin_code' => 'required|string|max:255|unique:cars',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'credit' => 'nullable|boolean',
            'change' => 'nullable|boolean',
            'values_id' => 'required|array',
            'values_id.*' => 'required|integer|min:1|distinct',
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
        ]);
        // name
        $user = Auth::user()->id;
        $brand = Brand::findOrFail($request->brand_id);
        $color = Color::findOrFail($request->color_id);
        $location = Location::findOrFail($request->location_id);
        $year = Year::findOrFail($request->year_id);
        $name = $brand->name . ' ' . $request->name;

        // car
        $car = new Car();
        $car->user_id = $user;
        $car->brand_id = $brand->id;
        $car->color_id = $color->id;
        $car->location_id = $location->id;
        $car->probeg = $request->probeg;
        $car->vin_code = $request->vin_code;
        $car->year_id = $year->id;
        $car->name = $name;
        $car->description = $request->description ?: null;
        $car->price = $request->price;
        $car->credit = $request->credit ?: 0;
        $car->change = $request->change ?: 0;

        $car->values()->sync($request->values_id);

        // image
        if ($request->hasFile('image')) {
            $newImage = $request->file('image');
            $newImageName = $car->id . '.' . $newImage->getClientOriginalExtension();
            $newImage->storeAs('public/cars/', $newImageName);
            $car->image = $newImageName;
        }

        $car->save();

        $success = trans('app.store-response', ['name' => $car->name]);
        return redirect()->route('cars.show', $car->id)
            ->with([
                'success' => $success,
            ]);
    }


    public function edit($id)
    {
        $car = Car::where('id', $id)
            ->firstOrFail();
        $brands = Brand::orderBy('id')
            ->get();
        $colors = Color::orderBy('id')
            ->get();
        $locations = Location::orderBy('id')
            ->get();
        $years = Year::orderBy('id')
            ->get();
        $options = Option::with(['values'])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('car.edit', [
            'car' => $car,
            'brands' => $brands,
            'colors' => $colors,
            'locations' => $locations,
            'years' => $years,
            'options' => $options,
        ]);
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'color_id' => 'required|exists:colors,id',
            'location_id' => 'required|exists:locations,id',
            'year_id' => 'required|exists:years,id',
            'probeg' => 'required|string|max:255',
            'vin_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars')->ignore($car->id)
            ],
            'description' => 'nullable|string|max:2550',
            'price' => 'required|numeric|min:0',
            'credit' => 'nullable|boolean',
            'change' => 'nullable|boolean',
            'values_id' => 'required|array',
            'values_id.*' => 'required|integer|min:1|distinct',
            'image' => 'nullable|image|mimes:jpg,png|max:2048',
        ]);
        $user = Auth::user()->id;

        $car->user_id = $user;
        $car->brand_id = $request->brand_id;
        $car->color_id = $request->color_id;
        $car->location_id = $request->location_id;
        $car->year_id = $request->year_id;
        $car->name = $request->name;
        $car->description = $request->description;
        $car->price = $request->price;
        $car->values()->sync($request->values_id);

        // image
        if ($request->hasFile('image')) {
            if ($car->image) {
                Storage::delete('public/cars/' . $car->image);
            }
            $newImage = $request->file('image');
            $newImageName = $car->id . '.' . $newImage->getClientOriginalExtension();
            $newImage->storeAs('public/cars/', $newImageName);
            $car->image = $newImageName;
        }

        $car->save();

        $success = trans('app.update-response', ['name' => $car->name]);
        return redirect()->route('cars.show', $car->id)
            ->with(['success' => $success]);
    }


    public function delete($id)
    {
        $car = Car::where('id', $id)
            ->firstOrFail();
        $success = trans('app.delete-response', ['name' => $car->name]);
        $car->delete();

        return redirect()->route('home')
            ->with([
                'success' => $success,
            ]);
    }

    public function userCars()
    {
        $cars = Car::where('user_id', auth()->user()->id)
            ->with(['brand', 'color', 'year', 'location'])
            ->get();

        return view('car.userCars', [
            'cars' => $cars,
        ]);
    }

}
