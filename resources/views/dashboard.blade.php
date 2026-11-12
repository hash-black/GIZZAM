<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GIZZAM | Store Management</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body data-modal-to-open="{{ $errors->any()
    ? (old('product_name') !== null
        ? 'productModal'
        : (old('customer_name') !== null
            ? 'customerModal'
            : (old('customer_id') !== null || old('products') !== null
                ? 'purchaseModal'
                : '')))
    : '' }}">

    <main class="main" id="dashboard">

        <!-- HEADER -->

        <div class="header">

            <div>
                <h1>GIZZAM</h1>
                <p>Computer Accessories Store Management</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>


        </div>


        <!-- SUCCESS MESSAGE -->

        @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
        @endif


        <!-- ERROR MESSAGE -->

        @if($errors->any())
        <div class="alert alert-error">
            <strong>Please check the following:</strong>

            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        <!-- ACTION BUTTONS -->

        <div class="action-area">

            <h2>Quick Actions</h2>

            <div class="action-buttons">

                <button class="action-btn blue"
                    type="button"
                    onclick="openModal('productModal')">
                    + Add Product
                </button>

                <button class="action-btn green"
                    type="button"
                    onclick="openModal('customerModal')">
                    + Register Customer
                </button>

                <button class="action-btn purple"
                    type="button"
                    onclick="openModal('purchaseModal')">
                    + Record Purchase
                </button>

            </div>

        </div>


        <!-- STATISTICS -->

        <div class="stats">

            <div class="stat-card">
                <p>Total Products</p>
                <h2 class="blue-text">{{ $totalProducts }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Customers</p>
                <h2 class="green-text">{{ $totalCustomers }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Orders</p>
                <h2 class="purple-text">{{ $totalOrders }}</h2>
            </div>

            <div class="stat-card">
                <p>Total Sales</p>
                <h2>₱{{ number_format($totalSales, 2) }}</h2>
            </div>

        </div>


        <!-- ONE-TO-ONE RELATIONSHIP -->

        <div class="section" id="products">

            <div class="section-header">

                <h3>Products & Product Details</h3>

                <span class="relationship-tag">
                    ONE-TO-ONE
                </span>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Brand</th>
                            <th>Warranty</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($products as $product)

                        <tr>
                            <td class="product-name">
                                {{ $product->product_name }}
                            </td>

                            <td>
                                ₱{{ number_format($product->price, 2) }}
                            </td>

                            <td>
                                {{ $product->stock }}
                            </td>

                            <td>
                                {{ $product->productDetail?->brand ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $product->productDetail?->warranty ?? 'N/A' }}
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="empty">
                                No products registered yet.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- ONE-TO-MANY RELATIONSHIP -->

        <div class="section" id="customers">

            <div class="section-header">

                <h3>Customers & Orders</h3>

                <span class="relationship-tag">
                    ONE-TO-MANY
                </span>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Total Orders</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($customers as $customer)

                        <tr>
                            <td class="product-name">
                                {{ $customer->customer_name }}
                            </td>

                            <td>
                                {{ $customer->email }}
                            </td>

                            <td>
                                {{ $customer->orders->count() }}
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="3" class="empty">
                                No customers registered yet.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- MANY-TO-MANY RELATIONSHIP -->

        <div class="section" id="orders">

            <div class="section-header">

                <h3>Orders & Purchased Products</h3>

                <span class="relationship-tag">
                    MANY-TO-MANY
                </span>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Purchased Products</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($orders as $order)

                        <tr>

                            <td>
                                #{{ $order->id }}
                            </td>

                            <td>
                                {{ $order->customer?->customer_name ?? 'N/A' }}
                            </td>

                            <td>

                                @forelse($order->products as $product)

                                <div style="margin-bottom:5px;">
                                    {{ $product->product_name }}
                                    (x{{ $product->pivot->quantity }})
                                </div>

                                @empty

                                No products

                                @endforelse

                            </td>

                            <td>
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>

                            <td>
                                <span class="status">
                                    {{ $order->status }}
                                </span>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="empty">
                                No orders registered yet.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <div class="footer">
            Data-Con's Computer Accessories Store Management System
        </div>

    </main>


    <!-- ADD PRODUCT MODAL -->

    <div class="modal" id="productModal">

        <div class="modal-content">

            <button class="close-btn" type="button"
                onclick="closeModal('productModal')">
                &times;
            </button>

            <div class="modal-header">
                <h2>Add New Product</h2>
                <p>Enter the computer accessory information.</p>
            </div>

            <form action="{{ route('products.store') }}" method="POST">

                @csrf

                <div class="form-group">
                    <label>Product Name</label>
                    <input
                        name="product_name"
                        value="{{ old('product_name') }}"
                        placeholder="e.g. Gaming Mouse"
                        required>
                </div>

                <div class="form-group">
                    <label>Price (₱)</label>
                    <input
                        name="price"
                        type="number"
                        step="0.01"
                        min="0"
                        value="{{ old('price') }}"
                        placeholder="0.00"
                        required>
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input
                        name="stock"
                        type="number"
                        min="0"
                        value="{{ old('stock') }}"
                        placeholder="Available stock"
                        required>
                </div>

                <div class="form-group">
                    <label>Brand</label>
                    <input
                        name="brand"
                        value="{{ old('brand') }}"
                        placeholder="e.g. Logitech"
                        required>
                </div>

                <div class="form-group">
                    <label>Warranty</label>
                    <input
                        name="warranty"
                        value="{{ old('warranty') }}"
                        placeholder="e.g. 1 Year Warranty"
                        required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea
                        name="description"
                        placeholder="Product description">{{ old('description') }}</textarea>
                </div>

                <button class="submit-btn" type="submit">
                    Save Product
                </button>

            </form>

        </div>

    </div>


    <!-- REGISTER CUSTOMER MODAL -->

    <div class="modal" id="customerModal">

        <div class="modal-content">

            <button class="close-btn" type="button"
                onclick="closeModal('customerModal')">
                &times;
            </button>

            <div class="modal-header">
                <h2>Register Customer</h2>
                <p>Enter the customer's information.</p>
            </div>

            <form action="{{ route('customers.store') }}" method="POST">

                @csrf

                <div class="form-group">
                    <label>Customer Name</label>
                    <input
                        name="customer_name"
                        value="{{ old('customer_name') }}"
                        placeholder="Full Name"
                        required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="example@gmail.com"
                        required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="09XXXXXXXXX">
                </div>

                <button class="submit-btn" type="submit">
                    Register Customer
                </button>

            </form>

        </div>

    </div>


    <!-- RECORD PURCHASE MODAL -->

    <div class="modal" id="purchaseModal">

        <div class="modal-content">

            <button class="close-btn" type="button"
                onclick="closeModal('purchaseModal')">
                &times;
            </button>

            <div class="modal-header">
                <h2>Record Purchase</h2>
                <p>Select a customer and their purchased products.</p>
            </div>

            <form action="{{ route('orders.store') }}" method="POST">

                @csrf

                <div class="form-group">
                    <label>Select Customer</label>

                    <select name="customer_id" required>

                        <option value="">Choose a customer</option>

                        @foreach($customers as $customer)

                        <option
                            value="{{ $customer->id }}"
                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                            {{ $customer->customer_name }}

                        </option>

                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label>Select Products</label>
                </div>

                <div class="purchase-list">

                    @forelse($products as $product)

                    <div class="purchase-item">

                        <label>

                            <input
                                type="checkbox"
                                class="product-checkbox"
                                name="products[{{ $product->id }}][id]"
                                value="{{ $product->id }}"
                                data-product="{{ $product->id }}"
                                {{ $product->stock == 0 ? 'disabled' : '' }}
                                {{ old('products.' . $product->id . '.id') ? 'checked' : '' }}>

                            <span>
                                <strong>{{ $product->product_name }}</strong>

                                <small>
                                    ₱{{ number_format($product->price, 2) }}
                                    | Stock: {{ $product->stock }}
                                </small>
                            </span>

                        </label>

                        <input
                            type="number"
                            name="products[{{ $product->id }}][quantity]"
                            class="product-quantity"
                            data-product="{{ $product->id }}"
                            min="1"
                            max="{{ $product->stock }}"
                            value="{{ old('products.' . $product->id . '.quantity') }}"
                            placeholder="Quantity"
                            {{ $product->stock == 0 || !old('products.' . $product->id . '.id') ? 'disabled' : '' }}>

                    </div>

                    @empty

                    <p class="muted">
                        No products available. Add products first.
                    </p>

                    @endforelse

                </div>

                <div class="purchase-total">
                    <span>Selected Products</span>
                    <span id="selectedCount">0</span>
                </div>

                <button class="submit-btn" type="submit">
                    Complete Purchase
                </button>

            </form>

        </div>

    </div>


    <!-- JAVASCRIPT -->

    <script>
        // OPEN MODAL

        function openModal(id) {

            const modal = document.getElementById(id);

            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

        }


        // CLOSE MODAL

        function closeModal(id) {

            const modal = document.getElementById(id);

            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }

        }


        // CLOSE WHEN CLICKING OUTSIDE

        document.querySelectorAll('.modal').forEach(modal => {

            modal.addEventListener('click', function(event) {

                if (event.target === modal) {
                    closeModal(modal.id);
                }

            });

        });


        // CLOSE WITH ESCAPE KEY

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeModal(modal.id);
                });

            }

        });


        // PRODUCT CHECKBOX AND QUANTITY

        function updatePurchaseCount() {

            const checked = document.querySelectorAll(
                '.product-checkbox:checked'
            );

            document.getElementById('selectedCount').textContent =
                checked.length;

        }


        document.querySelectorAll('.product-checkbox').forEach(checkbox => {

            const id = checkbox.dataset.product;

            const quantity = document.querySelector(
                `.product-quantity[data-product="${id}"]`
            );

            function updateQuantity() {

                quantity.disabled = !checkbox.checked;
                quantity.required = checkbox.checked;

                if (!checkbox.checked) {
                    quantity.value = '';
                }

                updatePurchaseCount();

            }

            checkbox.addEventListener('change', updateQuantity);

        });


        updatePurchaseCount();

        // REOPEN MODAL AFTER VALIDATION ERROR
        const modalToOpen = document.body.dataset.modalToOpen;

        if (modalToOpen) {
            openModal(modalToOpen);
        }
    </script>

    <script>
        document.querySelectorAll(
            'form[action*="/products"], form[action*="/customers"], form[action*="/orders"]'
        ).forEach(function(form) {

            form.addEventListener('submit', function(event) {

                if (!form.checkValidity()) {
                    return;
                }

                const button = form.querySelector('button[type="submit"]');

                if (button && !button.disabled) {
                    button.disabled = true;
                    button.textContent = 'Processing...';
                    button.style.opacity = '0.7';
                    button.style.cursor = 'not-allowed';
                }
            });

        });
    </script>
</body>

</html>