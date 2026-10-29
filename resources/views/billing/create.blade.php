<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Billing - New Order</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --navy: #1e293b;
            --purple: #6d4aff;
            --border: #cbd5e1;
            --muted: #64748b;
        }

        body {
            background: #f5f7fb;
            color: #334155;
            font-family: Arial, sans-serif;
        }

        .topbar {
            background: var(--navy);
            color: white;
            padding: 20px 28px;
            border-radius: 10px;
        }

        .panel {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            color: var(--navy);
        }

        .form-control,
        .form-select {
            border-color: var(--border);
            min-height: 43px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--purple);
            box-shadow: 0 0 0 .2rem rgba(109, 74, 255, .12);
        }

        .table thead th {
            background: #e8edf5;
            white-space: nowrap;
            font-size: 13px;
        }

        .table td {
            vertical-align: middle;
        }

        .low-stock {
            background: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .grand-total {
            border-top: 1px dashed var(--border);
            margin-top: 8px;
            padding-top: 16px;
            font-size: 20px;
            font-weight: 700;
        }

        .btn-purple {
            background: var(--purple);
            border-color: var(--purple);
            color: white;
        }

        .btn-purple:hover {
            background: #5835e8;
            color: white;
        }

        #result {
            white-space: pre-wrap;
        }

        @media (max-width: 767px) {
            .topbar {
                padding: 16px;
            }

            .panel {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- <header class="topbar d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">Store Billing — New Order</h1>
                <small class="text-white-50">Inventory &amp; order management</small>
            </div>
            <span class="badge bg-light text-dark">Billing Counter</span>
        </header> --}}

        @include('partials.navbar')


        <div id="result" class="alert d-none" role="alert"></div>

        <form id="orderForm">
            <div class="row g-4">

                <div class="col-lg-8">
                    <!-- Customer -->
                    <section class="panel mb-4">
                        <h2 class="section-title">1. Customer Details</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customerEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="customerEmail" required
                                    placeholder="customer@example.com">
                            </div>

                            <div class="col-md-6">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" required maxlength="255"
                                    placeholder="Customer name">
                            </div>
                        </div>
                    </section>

                    <!-- Products -->
                    <section class="panel mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="section-title mb-0">2. Products</h2>
                            <span class="badge bg-secondary">
                                {{ $products->count() }} products
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="productsTable">
                                <thead>
                                    <tr>
                                        <th style="min-width: 230px;">Product</th>
                                        <th style="width: 100px;">Qty</th>
                                        <th>Unit Price</th>
                                        <th>Tax</th>
                                        <th>Line Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="productRows"></tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-outline-primary" id="addProductBtn">
                            + Add Product
                        </button>
                    </section>

                    <!-- Payment -->
                    <section class="panel">
                        <h2 class="section-title">3. Payment</h2>

                        <div class="total-row">
                            <span>Subtotal</span>
                            <strong id="subtotal">₹0.00</strong>
                        </div>

                        <div class="total-row">
                            <span>Tax</span>
                            <strong id="taxTotal">₹0.00</strong>
                        </div>

                        <div class="total-row grand-total">
                            <span>Grand Total</span>
                            <span id="grandTotal">₹0.00</span>
                        </div>

                        <div class="mt-4">
                            <label for="amountGiven" class="form-label fw-semibold">
                                Amount Given by Customer (₹)
                            </label>
                            <input type="number" id="amountGiven" class="form-control" min="0" step="0.01"
                                placeholder="Enter amount received">
                        </div>

                        <div class="total-row mt-2">
                            <span>Balance to Return</span>
                            <strong id="balance" class="text-success">₹0.00</strong>
                        </div>

                        <div class="text-muted small">
                            The payment amount is for counter calculation only.
                            This task does not implement payment processing.
                        </div>
                    </section>
                </div>

                <!-- Low stock -->





                <!-- Low Stock Alert -->
                <aside class="col-lg-4">
                    <section class="panel low-stock">
                        <h2 class="section-title text-warning-emphasis">
                            ⚠ Low Stock Alert
                        </h2>

                        @forelse ($lowStockProducts as $product)
                            <div class="d-flex justify-content-between gap-2 border-bottom py-2">
                                <span>{{ $product->name }}</span>

                                <strong class="text-danger text-nowrap">
                                    {{ $product->stock_quantity }} left
                                </strong>
                            </div>
                        @empty
                            <p class="text-muted mb-0">
                                No products below the low-stock threshold.
                            </p>
                        @endforelse
                    </section>

                    <section class="panel mt-4">
                        <h2 class="section-title">Order Summary</h2>

                        <p class="text-muted small">
                            Review the customer and products before generating the bill.
                            Stock availability is validated by the server.
                        </p>

                        <button type="submit" id="generateBtn" form="orderForm" class="btn btn-success btn-lg w-100">
                            Generate Bill
                        </button>

                        <p class="text-muted small mt-2 mb-0">
                            Creates an order using the Laravel API.
                        </p>
                    </section>
                </aside>

            </div>
        </form>

    </div>

    @php
        $productData = $products
            ->map(function ($product) {
                return [
                    'id' => (int) $product->id,
                    'name' => (string) $product->name,
                    'price' => (float) $product->price,
                    'tax' => (float) $product->tax_percentage,
                    'stock' => (int) $product->stock_quantity,
                ];
            })
            ->values();
    @endphp

    <script>
        'use strict';

        const products = {{ Illuminate\Support\Js::from($productData) }};

        const rows = document.getElementById('productRows');
        const form = document.getElementById('orderForm');
        const result = document.getElementById('result');
        const amountGiven = document.getElementById('amountGiven');
        const generateBtn = document.getElementById('generateBtn');
        const addProductBtn = document.getElementById('addProductBtn');

        const subtotalElement = document.getElementById('subtotal');
        const taxTotalElement = document.getElementById('taxTotal');
        const grandTotalElement = document.getElementById('grandTotal');
        const balanceElement = document.getElementById('balance');

        let latestTotals = {
            subtotal: 0,
            tax: 0,
            total: 0
        };

        const money = (value) => {
            const amount = Number(value);

            return '₹' + (Number.isFinite(amount) ? amount : 0).toFixed(2);
        };

        const roundMoney = (value) => Math.round((value + Number.EPSILON) * 100) / 100;

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[char]);
        }

        function addProductRow() {
            const row = document.createElement('tr');

            row.innerHTML = `
        <td>
            <select class="form-select product-select" required>
                <option value="">Select product</option>
                ${products.map((product) => `
                                                        <option
                                                            value="${product.id}"
                                                            ${product.stock < 1 ? 'disabled' : ''}
                                                        >
                                                            ${escapeHtml(product.name)} — ${product.stock} available
                                                        </option>
                                                    `).join('')}
            </select>
        </td>

        <td>
            <input
                type="number"
                class="form-control quantity-input"
                value="1"
                min="1"
                step="1"
                required
            >
        </td>

        <td class="unit-price">—</td>
        <td class="line-tax">—</td>
        <td class="line-total fw-semibold">—</td>

        <td>
            <button
                type="button"
                class="btn btn-sm btn-outline-danger remove-row"
                aria-label="Remove product"
            >×</button>
        </td>
    `;

            rows.appendChild(row);

            row.querySelector('.product-select')
                .addEventListener('change', calculate);

            row.querySelector('.quantity-input')
                .addEventListener('input', calculate);

            row.querySelector('.remove-row')
                .addEventListener('click', () => {
                    row.remove();
                    calculate();
                });

            calculate();
        }

        function calculate() {
            let subtotal = 0;
            let taxTotal = 0;

            rows.querySelectorAll('tr').forEach((row) => {
                const productId = Number(
                    row.querySelector('.product-select').value
                );

                const quantityInput = row.querySelector('.quantity-input');
                const quantity = Number(quantityInput.value);

                const product = products.find(
                    (item) => item.id === productId
                );

                const unitPriceElement = row.querySelector('.unit-price');
                const lineTaxElement = row.querySelector('.line-tax');
                const lineTotalElement = row.querySelector('.line-total');

                if (!product || !Number.isInteger(quantity) || quantity < 1) {
                    unitPriceElement.textContent = '—';
                    lineTaxElement.textContent = '—';
                    lineTotalElement.textContent = '—';
                    return;
                }

                const lineSubtotal = roundMoney(product.price * quantity);

                // Tax is stored as a percentage, for example 18 means 18%.
                const lineTax = roundMoney(
                    lineSubtotal * (product.tax / 100)
                );

                const lineTotal = roundMoney(lineSubtotal + lineTax);

                unitPriceElement.textContent = money(product.price);
                lineTaxElement.textContent = money(lineTax);
                lineTotalElement.textContent = money(lineTotal);

                subtotal += lineSubtotal;
                taxTotal += lineTax;
            });

            subtotal = roundMoney(subtotal);
            taxTotal = roundMoney(taxTotal);

            const total = roundMoney(subtotal + taxTotal);

            latestTotals = {
                subtotal,
                tax: taxTotal,
                total
            };

            subtotalElement.textContent = money(subtotal);
            taxTotalElement.textContent = money(taxTotal);
            grandTotalElement.textContent = money(total);

            const receivedText = amountGiven.value.trim();
            const received = receivedText === '' ? 0 : Number(receivedText);
            const difference = roundMoney(received - total);

            if (receivedText !== '' && Number.isFinite(received) && difference >= 0) {
                balanceElement.textContent =
                    'Change to return: ' + money(difference);

                balanceElement.classList.remove('text-danger');
                balanceElement.classList.add('text-success');
            } else if (receivedText !== '' && Number.isFinite(received)) {
                balanceElement.textContent =
                    'Amount due: ' + money(Math.abs(difference));

                balanceElement.classList.remove('text-success');
                balanceElement.classList.add('text-danger');
            } else {
                balanceElement.textContent = 'Change / amount due: —';
                balanceElement.classList.remove('text-success', 'text-danger');
            }
        }

        function showMessage(message, success = false) {
            result.className = 'alert ' +
                (success ? 'alert-success' : 'alert-danger');

            result.textContent = message;

            result.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function getOrderItems() {
            const items = [];
            const seen = new Set();
            let validationError = '';

            rows.querySelectorAll('tr').forEach((row) => {
                const productId = Number(
                    row.querySelector('.product-select').value
                );

                const quantityInput = row.querySelector('.quantity-input');
                const quantity = Number(quantityInput.value);

                const product = products.find(
                    (item) => item.id === productId
                );

                if (!product || !Number.isInteger(quantity) || quantity < 1) {
                    validationError =
                        'Select a product and enter a valid whole-number quantity.';
                    return;
                }

                if (seen.has(productId)) {
                    validationError =
                        'Please use one row per product.';
                    return;
                }

                if (quantity > product.stock) {
                    validationError =
                        `Only ${product.stock} units of ${product.name} are available.`;
                    return;
                }

                seen.add(productId);

                items.push({
                    product_id: productId,
                    quantity
                });
            });

            if (items.length === 0 && !validationError) {
                validationError = 'Please add at least one product.';
            }

            return {
                items,
                validationError
            };
        }

        addProductBtn.addEventListener('click', addProductRow);

        amountGiven.addEventListener('input', calculate);

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            result.className = 'alert d-none';

            const customerName = document
                .getElementById('customerName').value.trim();

            const customerEmail = document
                .getElementById('customerEmail').value.trim();

            if (!customerName || !customerEmail) {
                showMessage('Please enter the customer name and email.');
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(customerEmail)) {
                showMessage('Please enter a valid customer email address.');
                return;
            }

            const {
                items,
                validationError
            } = getOrderItems();

            if (validationError) {
                showMessage(validationError);
                return;
            }

            const receivedText = amountGiven.value.trim();

            if (receivedText !== '') {
                const received = Number(receivedText);

                if (!Number.isFinite(received) || received < 0) {
                    showMessage('Please enter a valid amount received.');
                    return;
                }
            }

            generateBtn.disabled = true;
            generateBtn.textContent = 'Creating order...';

            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        customer: {
                            name: customerName,
                            email: customerEmail
                        },
                        items
                    })
                });

                // Read the response as text first so HTML errors do not
                // produce an unexplained JSON parsing error.
                const responseText = await response.text();

                let body = {};

                if (responseText.trim() !== '') {
                    try {
                        body = JSON.parse(responseText);
                    } catch {
                        throw new Error(
                            `The server returned a non-JSON response (HTTP ${response.status}). ` +
                            'Check the Laravel logs for details.'
                        );
                    }
                }

                if (!response.ok) {
                    const errors = body.errors ?
                        Object.values(body.errors).flat().join('\n') :
                        body.message || `Request failed (HTTP ${response.status}).`;

                    throw new Error(errors);
                }

                const order = body.data;

                if (
                    !order ||
                    order.id === undefined ||
                    order.subtotal === undefined ||
                    order.tax_amount === undefined ||
                    order.grand_total === undefined
                ) {
                    throw new Error(
                        'The order response is missing expected fields. ' +
                        'Check the API response format.'
                    );
                }


                const receivedText = amountGiven.value.trim();
                const received = receivedText === '' ? null : Number(receivedText);

                const grandTotal = Number(order.grand_total);
                const difference = received === null ?
                    null :
                    Math.round((received - grandTotal + Number.EPSILON) * 100) / 100;

                let paymentMessage = '';

                if (received === null) {
                    paymentMessage = 'Amount received: Not entered';
                } else {
                    paymentMessage =
                        `Amount received: ${money(received)}\n` +
                        (difference >= 0 ?
                            `Change to return: ${money(difference)}` :
                            `Amount due: ${money(Math.abs(difference))}`);
                }

                showMessage(
                    `Order #${order.id} created successfully!\n\n` +
                    `Subtotal: ${money(order.subtotal)}\n` +
                    `Tax: ${money(order.tax_amount)}\n` +
                    `Grand total: ${money(grandTotal)}\n\n` +
                    `${paymentMessage}\n\n` +
                    'Inventory has been updated by the server.',
                    true
                );


                // showMessage(
                //     `Order #${order.id} created successfully!\n` +
                //     `Subtotal: ${money(order.subtotal)}\n` +
                //     `Tax: ${money(order.tax_amount)}\n` +
                //     `Grand total: ${money(order.grand_total)}\n\n` +
                //     'Inventory has been updated by the server.',
                //     true
                // );

                // Update the local display after the server confirms success.
                // This is only a UI update; the server remains authoritative.
                items.forEach((item) => {
                    const product = products.find(
                        (p) => p.id === item.product_id
                    );

                    if (product) {
                        product.stock = Math.max(0, product.stock - item.quantity);
                    }
                });

                form.reset();
                rows.innerHTML = '';
                amountGiven.value = '';

                addProductRow();
                calculate();

            } catch (error) {
                showMessage(
                    error.message || 'Request failed. Check your Laravel server.'
                );
            } finally {
                generateBtn.disabled = false;
                generateBtn.textContent = 'Generate Bill';
            }
        });

        addProductRow();
        calculate();
    </script>





</body>

</html>
