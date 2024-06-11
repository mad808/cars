@extends('layouts.app')
@section('title') @lang('app.car') - @lang('app.create') @endsection
@section('content')
    <div class="container-xxl py-3">
        <div class="d-block h3 text-dark fw-bold text-center border-bottom py-2 mb-3">
            @lang('app.car') - @lang('app.create')
        </div>
        <div class="row justify-content-center">
            <form action="{{ route('cars.store') }}" method="post" enctype="multipart/form-data" class="col-md-6 col-lg-4">
                @csrf

                <div class="mb-3">
                    <label for="brand_id" class="form-label fw-bold">
                        @lang('app.brand') <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="color_id" class="form-label fw-bold">
                        @lang('app.color') <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('color_id') is-invalid @enderror" id="color_id" name="color_id" required>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">
                                {{ $color->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('color_id')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="location_id" class="form-label fw-bold">
                        @lang('app.locations') <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="year_id" class="form-label fw-bold">
                        @lang('app.year') <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('year_id') is-invalid @enderror" id="year_id" name="year_id" required>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('year_id')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>



                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">
                        @lang('app.name')
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                    @error('name')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="probeg" class="form-label fw-bold">
                        @lang('app.probeg') <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('probeg') is-invalid @enderror" name="probeg" id="probeg" value="{{ old('probeg') }}" maxlength="255" required>
                    @error('probeg')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="serial_number" class="form-label fw-bold">
                        @lang('app.vin_code') <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('vin_code') is-invalid @enderror" name="vin_code" id="vin_code" value="{{ old('vin_code') }}" maxlength="255" required>
                    @error('vin_code')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">
                        @lang('app.description')
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3" maxlength="2550">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="price" class="form-label fw-bold">
                        @lang('app.price') <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="0" min="0" step="0.1" required>
                    @error('price')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="credit" id="credit" value="1">
                        <label class="form-check-label" for="credit">
                            @lang('app.credit')
                        </label>
                        @error('credit')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="change" id="change" value="1">
                        <label class="form-check-label" for="change">
                            @lang('app.change')
                        </label>
                        @error('change')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                @foreach($options as $option)
                    <div class="mb-3">
                        <label for="option_{{ $option->id }}" class="form-label fw-bold">
                            {{ $option->name }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('values_id') is-invalid @enderror" id="option_{{ $option->id }}" name="values_id[]" required>
                            @foreach($option->values as $value)
                                <option value="{{ $value->id }}">
                                    {{ $value->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('values_id')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                @endforeach


                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">@lang('app.image') (500x500)</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" id="image" accept="image/jpeg">
                    @error('image')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle"></i> @lang('app.store')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection