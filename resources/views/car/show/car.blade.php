<div class="border rounded bg-dark-subtle shadow-md p-5">
    <div class="text-end">
        @if($car->created_at >= \Carbon\Carbon::now()->subMonths(3)->toDateTimeString())
            <span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">
                    @lang('app.new')
                </span>
        @endif
            @auth
                @if(auth()->user()->id == $car->user_id)
                <div>
                    <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-success btn-sm text-decoration-none">
                        <i class="bi bi-pencil-fill"></i> @lang('app.edit')
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm text-decoration-none" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash-fill"></i> @lang('app.delete')
                    </button>
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @lang('app.delete-question', ['name' => $car->name])
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('cars.delete', $car->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('app.cancel')</button>
                                        <button type="submit" class="btn btn-secondary">@lang('app.delete')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    @endif
            @endauth
    </div>
          <div class="row g-3">
            <div class="col-sm-6 col-lg-4">
                <div class="position-relative d-flex justify-content-center align-items-center">
                    <img src="{{ $car->image() }}" width="550" height="350" alt="" class="img-fluid w-100 border rounded mt-4">
                </div>
            </div>
            <div class="col mx-2">
                <a href="{{ route('cars.index', ['brand' => $car->brand->id]) }}" class="text-dark text-decoration-none h3 fw-bold">
                    {{ $car->brand->name }}
                </a>
                <div class="d-block h4 fw-bold mb-2 mt-1">
                    {{ $car->name }}
                </div>
                <a href="{{ route('cars.index', ['location' => $car->location->id]) }}" class="fw-bold text-dark text-decoration-none">@lang('app.location') :
                    {{ $car->location->getName() }}
                </a>
                <br>
                <a href="{{ route('cars.index', ['color' => $car->color->id]) }}" class="fw-bold text-dark text-decoration-none">@lang('app.color') :
                    {{ $car->color->getName() }}
                </a>
                <br>
                <a href="{{ route('cars.index', ['year' => $car->year->id]) }}" class="mt-1 fw-bold text-dark text-decoration-none bi bi-calendar"> @lang('app.year') :
                    {{ $car->year->name }}
                </a>

                <div class="d-block fw-bold">
                    <span class="text-dark mt-3">@lang('app.probeg') : </span> {{ $car->probeg }}
                </div>
                <div class="d-block fw-bold">
                    <span class="text-dark bi bi-qr-code"> @lang('app.vin_code') : </span> {{ $car->vin_code }}
                </div>

                <div class="d-flex align-items-center fw-bold mb-2 mt-1">

                    <div class="me-4 mt-1">
                        <i class="bi bi-eye-fill text-dark"></i> {{ $car->viewed }}
                    </div>
                    <a href="{{ route('cars.favorite', $car->id) }}" class="btn btn-danger btn-sm text-decoration-none">
                        <i class="bi bi-heart-fill"></i> {{ $car->favorited }}
                    </a>
                </div>
                <div class="text-dark fw-semibold">
                    @if($car->credit)
                        <i class="bi bi-patch-check-fill fw-bold text-primary"></i> <a class="fw-bold text-dark">@lang('app.credit')</a>
                    @endif
                    @if($car->change)
                        <i class="bi bi-patch-check-fill fw-bold text-success"></i> <a class="fw-bold text-dark">@lang('app.change')</a>
                    @endif
                        <br>
                        @foreach($car->values as $value)
                            <div class="{{ $loop->last ? 'mb-3':'mb-2' }}">
                                <span class="text-dark">{{ $value->option->name }}:</span>
                                <a href="{{ route('cars.index', ['value' => [[$value->id]]]) }}" class="link-dark">{{ $value->name }}</a>
                            </div>
                        @endforeach
                    <p class="rounded-5 border border-1 border-secondary p-3 mt-2">
                    ∙ {{ $car->description }} ({{ $car->created_at }})
                    </p>

                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 fw-semibold">
                    <div>
            <span class="text-primary">
                ∙ {{ round($car->price, 2) }} <small>TMT</small>
            </span>
                    </div>

                </div>
            </div>
        </div>
      </div>
