<div class="border rounded shadow-sm bg-dark-subtle p-3">
    <a href="{{ route('cars.show', $car->id) }}" class="d-flex justify-content-between">
        <div>
            <div class="mb-1">
                        <img class="img-fluid w-100 rounded" src="{{$car->image()}}" style="height: 150px; width: 350px" >

                <div class="d-flex align-items-center justify-content-between">

                    <a href="{{ route('cars.index', ['brand' => $car->brand->id]) }}" class="text-dark fw-semibold text-decoration-none">
                        {{ $car->brand->name }}
                    </a>
                    <div>
                    @if($car->created_at >= \Carbon\Carbon::now()->subMonths(3)->toDateTimeString())
                        <span class="badge bg-danger-subtle border border-danger-subtle text-danger-emphasis rounded-pill">
                    @lang('app.new')
                </span>
                    @endif
                    <button class="btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCar{{ $car->id }}" aria-expanded="false" aria-controls="collapseCar{{ $car->id }}">
                        <i class="bi-caret-down-fill"></i>
                    </button>
                    </div>
                </div>
            </div>
            <div class="mb-1">
                <a href="{{ route('cars.index', ['color' => $car->color->id]) }}" class="link-dark text-decoration-none">
                    {{ $car->name }} ∙ {{ $car->color->getName() }}
                </a>
                <a href="{{ route('cars.index', ['year' => [$car->year->id]]) }}" class="link-dark text-decoration-none">
                    ∙ {{ $car->year->name }}
                </a>
            </div>
        </div>
    </a>
    <div id="collapseCar{{ $car->id }}" class="small text-secondary collapse">
        <a href="{{ route('cars.index', ['location' => $car->location->id]) }}" class="link-dark text-decoration-none">
            {{ $car->location->getName() }}
        </a>
        ∙ {{ $car->description }} ({{ $car->created_at }})
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="text-primary fw-semibold">
                {{ round($car->price, 2) }} <small>TMT</small>
            </span>
            @if($car->credit)
                <i class="bi bi-patch-check-fill text-primary"></i>
            @endif
            @if($car->change)
                <i class="bi bi-patch-check-fill text-success"></i>
            @endif
        </div>
        <div>
            <a href="{{ route('cars.show', $car->id) }}" class="text-dark text-decoration-none">
                <i class="bi-eye-fill"></i> {{ $car->viewed }}
            </a>
        </div>
    </div>
</div>