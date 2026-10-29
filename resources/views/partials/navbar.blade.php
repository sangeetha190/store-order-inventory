<nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background:#1e293b; padding:16px 24px;">
    <div class="container-fluid">

        {{-- <a class="navbar-brand fw-bold" href="{{ route('billing.create') }}">
            🛒 Store Billing — New Order
            <span class="fw-normal text-white-50">— New Order</span>
        </a>
        <small class="text-white-50">Inventory &amp; order management</small> --}}


        <div>

            <h1 class="h4 fw-bold mb-1"><a class="navbar-brand fw-bold" href="{{ route('billing.create') }}"> 🛒 Store
                    Billing — New Order </a></h1>

            <small class="text-white-50">Inventory &amp; order management</small>

        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar"
            aria-controls="storeNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="storeNavbar">
            <div class="navbar-nav ms-auto gap-lg-2 mt-3 mt-lg-0">

                <a class="btn {{ request()->routeIs('billing.create') ? 'btn-secondary' : 'btn-outline-light' }}"
                    href="{{ route('billing.create') }}">
                    💳 POS Counter
                </a>

                <a class="btn {{ request()->routeIs('orders.history') ? 'btn-secondary' : 'btn-outline-light' }}"
                    href="{{ route('orders.history') }}">
                    📜 Order History
                </a>

            </div>
        </div>

    </div>
</nav>
