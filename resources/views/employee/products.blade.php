@extends('employee.main')

@section('content')
    <div class="main-container">
        <!-- Product Side (Left, 70%) -->
        <div class="left-section">
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <h2 style="margin: 0;" id="section-title">Categories</h2>
                <button id="switch-btn" style="background: none; border: none; cursor: pointer; margin-left: 10px;">
                    <span class="material-icons-outlined" style="font-size: 24px; color: var(--color-2);">swap_horiz</span>
                </button>
            </div>
            <div id="category-container" style="display: flex; flex-wrap: wrap; gap: 15px; padding: 10px 0;">
                @foreach ($categories as $category)
                    <button class="item category-btn" data-category-id="{{ $category->categoryID }}" style="width: calc(20% - 12px); height: 66px; background-color: var(--color-6); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border: none; cursor: pointer;">
                        <div style="display: flex; align-items: center;">
                            <span class="material-icons-outlined" style="font-size: 24px; color: var(--color-2); margin-right: 8px;">
                                @switch($category->categoryName)
                                    @case('CPU') memory @break
                                    @case('Storage') storage @break
                                    @case('SSD') sd_card @break
                                    @case('HDD') save @break
                                    @case('Cables') cable @break
                                    @case('Ethernet Cable') lan @break
                                    @case('GPU') videogame_asset @break
                                    @case('Laptop') laptop @break
                                    @case('Laptop Screen') monitor @break
                                    @case('Laptop Battery') battery_full @break
                                    @default category
                                @endswitch
                            </span>
                            <p style="margin: 0; font-size: 16px;">{{ $category->categoryName }}</p>
                        </div>
                    </button>
                @endforeach
            </div>

            <h2 style="margin: 20px 0 10px;">Products</h2>
            <div class="products-container" style="max-height: 600px; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 20px; padding: 10px 0;" id="products-container">
                @foreach ($products as $product)
                    <button class="product-item" data-product-id="{{ $product->productID }}" style="width: calc(33.33% - 14px); height: 150px; background-color: var(--color-6); border-radius: 8px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 15px; border: none; cursor: pointer;">
                        <div style="display: flex; align-items: center;">
                            <span class="material-icons-outlined" style="font-size: 60px; color: var(--color-2); margin-right: 10px;">
                                @switch($product->categoryName)
                                    @case('CPU') memory @break
                                    @case('Storage') storage @break
                                    @case('SSD') sd_card @break
                                    @case('HDD') save @break
                                    @case('Cables') cable @break
                                    @case('Ethernet Cable') lan @break
                                    @case('GPU') videogame_asset @break
                                    @case('Laptop') laptop @break
                                    @case('Laptop Screen') monitor @break
                                    @case('Laptop Battery') battery_full @break
                                    @default category
                                @endswitch
                            </span>
                            <div>
                                <p style="margin: 0; font-size: 16px; font-weight: 500;">{{ $product->productName }}</p>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--color-3);">{{ $product->productDescription ?? 'No description available' }}</p>
                            </div>
                        </div>
                        <p style="margin: 0; font-size: 14px; text-align: right;">₱{{ number_format($product->price, 2) }}</p>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Invoice Side (Right, 30%) -->
        <div class="right-section">
            <div class="invoice-upper">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0; color: var(--color-1);">Invoice</h2>
                    <span id="invoice-count" class="invoice-count" style="margin-left: 10px;">0</span>
                </div>
                <div class="invoice-items-container" id="invoice-items"></div>
            </div>
            <div class="invoice-lower">
                <div class="payment-frame" id="payment-frame">
                    <h4>Payment Summary</h4>
                    <div class="payment-details" id="payment-details">
                        <p>Items Ordered</p>
                        <p id="items-ordered">0</p>
                        <p>Grand Total</p>
                        <p id="grand-total">₱0.00</p>
                    </div>
                    <div class="payment-methods" id="payment-methods">
                        <button class="payment-btn" data-method="cash">
                            <span class="material-icons-outlined">money</span>
                            Cash
                        </button>
                        <button class="payment-btn" data-method="credit card">
                            <span class="material-icons-outlined">credit_card</span>
                            Credit Card
                        </button>
                        <button class="payment-btn" data-method="digital">
                            <span class="material-icons-outlined">phone_android</span>
                            Digital
                        </button>
                    </div>
                    <button id="place-order-btn">Place an Order</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggling, filtering, and invoice -->
    <script>
        const brands = @json($brands);
        const products = @json($products);
        const employeeID = @json($employee->employeeID); // Pass employee ID from controller
        let showingCategories = true;
        let currentFilter = { type: null, id: null };
        let invoice = [];
        let selectedPaymentMethod = null;
        let isPaymentFormVisible = false;
        let grandTotal = 0; // Store grandTotal persistently

        function renderCategories() {
            const container = document.getElementById('category-container');
            container.innerHTML = '';
            @foreach ($categories as $category)
                container.innerHTML += `
                    <button class="item category-btn" data-category-id="{{ $category->categoryID }}" style="width: calc(20% - 12px); height: 66px; background-color: var(--color-6); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border: none; cursor: pointer;">
                        <div style="display: flex; align-items: center;">
                            <span class="material-icons-outlined" style="font-size: 24px; color: var(--color-2); margin-right: 8px;">
                                @switch($category->categoryName)
                                    @case('CPU') memory @break
                                    @case('Storage') storage @break
                                    @case('SSD') sd_card @break
                                    @case('HDD') save @break
                                    @case('Cables') cable @break
                                    @case('Ethernet Cable') lan @break
                                    @case('GPU') videogame_asset @break
                                    @case('Laptop') laptop @break
                                    @case('Laptop Screen') monitor @break
                                    @case('Laptop Battery') battery_full @break
                                    @default category
                                @endswitch
                            </span>
                            <p style="margin: 0; font-size: 16px;">{{ $category->categoryName }}</p>
                        </div>
                    </button>
                `;
            @endforeach
            document.getElementById('section-title').textContent = 'Categories';
            attachCategoryListeners();
        }

        function renderBrands() {
            const container = document.getElementById('category-container');
            container.innerHTML = '';
            brands.forEach(brand => {
                container.innerHTML += `
                    <button class="item brand-btn" data-brand-id="${brand.brandID}" style="width: calc(20% - 12px); height: 66px; background-color: var(--color-6); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border: none; cursor: pointer;">
                        <div style="display: flex; align-items: center;">
                            <p style="margin: 0; font-size: 16px;">${brand.brandName}</p>
                        </div>
                    </button>
                `;
            });
            document.getElementById('section-title').textContent = 'Brands';
            attachBrandListeners();
        }

        function renderProducts(filteredProducts) {
            const container = document.getElementById('products-container');
            container.innerHTML = '';
            filteredProducts.forEach(product => {
                container.innerHTML += `
                    <button class="product-item" data-product-id="${product.productID}" style="width: calc(33.33% - 14px); height: 150px; background-color: var(--color-6); border-radius: 8px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 15px; border: none; cursor: pointer;">
                        <div style="display: flex; align-items: center;">
                            <span class="material-icons-outlined" style="font-size: 60px; color: var(--color-2); margin-right: 10px;">
                                ${getIcon(product.categoryName)}
                            </span>
                            <div>
                                <p style="margin: 0; font-size: 16px; font-weight: 500;">${product.productName}</p>
                                <p style="margin: 5px 0 0; font-size: 12px; color: var(--color-3);">${product.productDescription || 'No description available'}</p>
                            </div>
                        </div>
                        <p style="margin: 0; font-size: 14px; text-align: right;">₱${Number(product.price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</p>
                    </button>
                `;
            });
            attachProductListeners();
        }

        function getIcon(categoryName) {
            switch (categoryName) {
                case 'CPU': return 'memory';
                case 'Storage': return 'storage';
                case 'SSD': return 'sd_card';
                case 'HDD': return 'save';
                case 'Cables': return 'cable';
                case 'Ethernet Cable': return 'lan';
                case 'GPU': return 'videogame_asset';
                case 'Laptop': return 'laptop';
                case 'Laptop Screen': return 'monitor';
                case 'Laptop Battery': return 'battery_full';
                default: return 'category';
            }
        }

        function filterProducts(type, id) {
            let filteredProducts = products;
            if (type === 'category' && id) {
                filteredProducts = products.filter(product => product.categoryID === id);
            } else if (type === 'brand' && id) {
                filteredProducts = products.filter(product => product.brandID === id);
            }
            renderProducts(filteredProducts);
        }

        function attachCategoryListeners() {
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const categoryId = parseInt(btn.getAttribute('data-category-id'));
                    currentFilter = { type: 'category', id: categoryId };
                    filterProducts('category', categoryId);
                });
            });
        }

        function attachBrandListeners() {
            document.querySelectorAll('.brand-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const brandId = parseInt(btn.getAttribute('data-brand-id'));
                    currentFilter = { type: 'brand', id: brandId };
                    filterProducts('brand', brandId);
                });
            });
        }

        function attachProductListeners() {
            document.querySelectorAll('.product-item').forEach(btn => {
                btn.addEventListener('click', () => {
                    const productId = parseInt(btn.getAttribute('data-product-id'));
                    const product = products.find(p => p.productID === productId);
                    addToInvoice(product);
                });
            });
        }

        function addToInvoice(product) {
            const existingItem = invoice.find(item => item.productID === product.productID);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                invoice.push({ ...product, quantity: 1 });
            }
            renderInvoice();
        }

        function removeFromInvoice(productId) {
            invoice = invoice.filter(item => item.productID !== productId);
            renderInvoice();
        }

        function updateQuantity(productId, change) {
            const item = invoice.find(item => item.productID === productId);
            if (item) {
                item.quantity = Math.max(1, item.quantity + change);
                renderInvoice();
            }
        }

        function renderInvoice() {
            const invoiceContainer = document.getElementById('invoice-items');
            invoiceContainer.innerHTML = '';
            invoice.forEach(item => {
                const totalPrice = (item.price * item.quantity).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                invoiceContainer.innerHTML += `
                    <div style="position: relative; background-color: var(--color-6); border-radius: 8px; padding: 12px; margin-bottom: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        <button class="delete-btn" data-product-id="${item.productID}" style="position: absolute; top: 4px; right: 4px; background: none; border: none; cursor: pointer;">
                            <span class="material-icons-outlined" style="font-size: 20px; color: var(--color-2);">close</span>
                        </button>
                        <div style="display: flex; align-items: center;">
                            <span class="material-icons-outlined" style="font-size: 48px; color: var(--color-2); margin-right: 8px;">${getIcon(item.categoryName)}</span>
                            <p style="margin: 0; font-size: 14px; font-weight: 500; flex-grow: 1; color: var(--color-2);">${item.productName}</p>
                            <p style="margin: 0; font-size: 12px; color: var(--color-2);">₱${Number(item.price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</p>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <div style="display: flex; align-items: center;">
                                <button class="quantity-btn minus" data-product-id="${item.productID}" style="width: 24px; height: 24px; background-color: var(--color-2); color: var(--color-1); border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1" data-product-id="${item.productID}" style="width: 40px; height: 24px; text-align: center; border: 1px solid var(--color-3); border-radius: 4px; margin: 0 4px; font-size: 12px; color: var(--color-1); background-color: var(--color-2); -webkit-appearance: none; -moz-appearance: textfield;">
                                <button class="quantity-btn plus" data-product-id="${item.productID}" style="width: 24px; height: 24px; background-color: var(--color-2); color: var(--color-1); border: none; border-radius: 4px; font-size: 16px; cursor: pointer;">+</button>
                            </div>
                            <p style="margin: 0; font-size: 14px; font-weight: bold; color: var(--color-2);">₱${totalPrice}</p>
                        </div>
                    </div>
                `;
            });

            grandTotal = invoice.reduce((sum, item) => sum + item.price * item.quantity, 0); // Calculate and store grandTotal
            const itemsOrdered = invoice.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('grand-total').textContent = `₱${grandTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}`;
            document.getElementById('items-ordered').textContent = itemsOrdered;
            document.getElementById('invoice-count').textContent = invoice.length;

            if (!isPaymentFormVisible) {
                renderPaymentSummary();
            }

            attachInvoiceListeners();
            attachPaymentMethodListeners();
        }

        function renderPaymentSummary() {
            const paymentFrame = document.getElementById('payment-frame');
            paymentFrame.innerHTML = `
                <h4>Payment Summary</h4>
                <div class="payment-details" id="payment-details">
                    <p>Items Ordered</p>
                    <p id="items-ordered">${document.getElementById('items-ordered').textContent}</p>
                    <p>Grand Total</p>
                    <p id="grand-total">₱${grandTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</p>
                </div>
                <div class="payment-methods" id="payment-methods">
                    <button class="payment-btn" data-method="cash">
                        <span class="material-icons-outlined">money</span>
                        Cash
                    </button>
                    <button class="payment-btn" data-method="credit card">
                        <span class="material-icons-outlined">credit_card</span>
                        Credit Card
                    </button>
                    <button class="payment-btn" data-method="digital">
                        <span class="material-icons-outlined">phone_android</span>
                        Digital
                    </button>
                </div>
                <button id="place-order-btn">Place an Order</button>
            `;
            attachPaymentMethodListeners();
            document.getElementById('place-order-btn').addEventListener('click', showPaymentForm);
        }

        function showPaymentForm() {
            if (invoice.length === 0) {
                alert('Please add items to the invoice before placing an order.');
                return;
            }
            if (!selectedPaymentMethod) {
                alert('Please select a payment method before placing an order.');
                return;
            }

            isPaymentFormVisible = true;
            const paymentFrame = document.getElementById('payment-frame');
            paymentFrame.innerHTML = `
                <div>
                    <h4>Payment Summary</h4>
                    <div class="payment-form">
                        <div class="form-group">
                            <label>Customer Name:</label>
                            <input type="text" id="customer-name" placeholder="Enter customer name" class="full-width">
                        </div>
                        <div class="form-group-inline">
                            <label>Amount Received:</label>
                            <div class="input-with-icon">
                                <span class="peso-icon">₱</span>
                                <input type="number" id="amount-received" placeholder="0.00" step="0.01" min="0" class="half-width">
                            </div>
                        </div>
                        <div class="form-group-inline">
                            <label>Change:</label>
                            <div class="input-with-icon">
                                <span class="peso-icon">₱</span>
                                <input type="number" id="change" readonly class="half-width">
                            </div>
                        </div>
                    </div>
                    <button id="confirm-order-btn">Confirm Order</button>
                </div>
            `;

            const amountReceivedInput = document.getElementById('amount-received');
            const changeInput = document.getElementById('change');
            const confirmOrderBtn = document.getElementById('confirm-order-btn');

            amountReceivedInput.addEventListener('input', () => {
                const amountReceived = parseFloat(amountReceivedInput.value) || 0;
                const change = amountReceived - grandTotal;
                changeInput.value = change >= 0 ? change.toFixed(2) : '0.00';
            });

            confirmOrderBtn.addEventListener('click', confirmOrder);
        }

        function confirmOrder() {
            const customerName = document.getElementById('customer-name').value.trim();
            const amountReceived = parseFloat(document.getElementById('amount-received').value) || 0;

            if (!customerName) {
                alert('Please enter a customer name.');
                return;
            }
            if (amountReceived < grandTotal) {
                alert('Amount received must be at least the grand total.');
                return;
            }

            const orderData = {
                customer_name: customerName,
                amount_received: amountReceived,
                payment_method: selectedPaymentMethod,
                items: invoice.map(item => ({
                    productID: item.productID,
                    quantity: item.quantity,
                    price: item.price,
                })),
                grand_total: grandTotal, // Use stored value
                _token: '{{ csrf_token() }}',
            };

            fetch('{{ route("pos.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': orderData._token,
                },
                body: JSON.stringify(orderData),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Order confirmed for ${customerName} via ${selectedPaymentMethod}! Change: ₱${document.getElementById('change').value} (Order ID: ${data.order_id})`);
                    invoice = [];
                    selectedPaymentMethod = null;
                    isPaymentFormVisible = false;
                    grandTotal = 0; // Reset grandTotal
                    renderInvoice();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while placing the order.');
            });
        }

        function attachInvoiceListeners() {
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const productId = parseInt(btn.getAttribute('data-product-id'));
                    removeFromInvoice(productId);
                });
            });
            document.querySelectorAll('.quantity-btn.minus').forEach(btn => {
                btn.addEventListener('click', () => {
                    const productId = parseInt(btn.getAttribute('data-product-id'));
                    updateQuantity(productId, -1);
                });
            });
            document.querySelectorAll('.quantity-btn.plus').forEach(btn => {
                btn.addEventListener('click', () => {
                    const productId = parseInt(btn.getAttribute('data-product-id'));
                    updateQuantity(productId, 1);
                });
            });
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', () => {
                    const productId = parseInt(input.getAttribute('data-product-id'));
                    const newQuantity = parseInt(input.value) || 1;
                    const item = invoice.find(item => item.productID === productId);
                    if (item) {
                        item.quantity = Math.max(1, newQuantity);
                        renderInvoice();
                    }
                });
            });
        }

        function attachPaymentMethodListeners() {
            document.querySelectorAll('.payment-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const method = btn.getAttribute('data-method');
                    if (selectedPaymentMethod !== method) {
                        selectedPaymentMethod = method;
                        document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('selected'));
                        btn.classList.add('selected');
                    }
                });
            });
        }

        document.getElementById('switch-btn').addEventListener('click', () => {
            showingCategories = !showingCategories;
            currentFilter = { type: null, id: null };
            if (showingCategories) {
                renderCategories();
                renderProducts(products);
            } else {
                renderBrands();
                renderProducts(products);
            }
        });

        renderCategories();
        renderProducts(products);
    </script>
@endsection