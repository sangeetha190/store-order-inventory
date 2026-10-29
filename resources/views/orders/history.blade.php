<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - Store Inventory</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            color: #334155;
            font-family: Arial, sans-serif;
        }

        .topbar {
            background: #1e293b;
            color: white;
            padding: 22px 26px;
            border-radius: 12px;
        }

        .panel {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
        }

        .table thead th {
            background: #e8edf5;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- <header class="topbar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">Order History</h1>
                <p class="mb-0 text-white-50">
                    View previously created customer orders
                </p>
            </div>

            <a href="{{ route('billing.create') }}" class="btn btn-light">
                + Create New Order
            </a>
        </header> --}}

        @include('partials.navbar')

        <section class="panel">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h2 class="h5 fw-bold mb-0">All Orders</h2>

                <span class="badge bg-secondary">
                    {{ $orders->total() }} orders
                </span>
            </div>

            @if ($orders->isEmpty())
                <div class="alert alert-info mb-0">
                    No orders have been created yet.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Subtotal</th>
                                <th>Tax</th>
                                <th>Grand Total</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>

                                    <td>
                                        {{ $order->customer?->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $order->customer?->email ?? 'N/A' }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $order->subtotal, 2) }}
                                    </td>

                                    <td>
                                        ₹{{ number_format((float) $order->tax_amount, 2) }}
                                    </td>

                                    <td class="fw-bold">
                                        ₹{{ number_format((float) $order->grand_total, 2) }}
                                    </td>

                                    <td>
                                        {{ $order->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </section>

    </div>
</body>

</html>
