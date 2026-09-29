<div class="card address-card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-uppercase">{{ $title }}</h6>

            <button
                class="btn btn-sm btn-outline-primary edit-address"
                data-id="{{ $address->id }}"
                data-address='@json($address)'
            >
                Edit
            </button>
        </div>

        <div class="small text-muted">
            <div>{{ $address->recipient }}</div>
            <div>{{ $address->street }}</div>
            <div>{{ $address->city }}, {{ $address->state }}</div>
            <div>{{ $address->zip }}, {{ $address->country }}</div>
        </div>

    </div>
</div>
