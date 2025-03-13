    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'iSagha E-commerce')</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/omar ayman logo.png') }}">

        <!-- Bootstrap & FontAwesome -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

        <style>
            /* Soft Gradient Background */
            body {
                background: linear-gradient(to right, #f8f9fa, #e9ecef);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Navbar Styling */
            .navbar {
                background: rgba(0, 0, 0, 0.85);
                backdrop-filter: blur(10px);
                padding: 15px 0;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            .navbar-brand {
                font-weight: bold;
                font-size: 1.5rem;
                color: #f8f9fa !important;
            }

            .navbar .btn {
                font-weight: bold;
                transition: all 0.3s ease-in-out;
            }

            .navbar .btn:hover {
                transform: translateY(-2px);
            }

            /* Main Container */
            .container {
                flex: 1;
            }

            /* Footer Styling */
            footer {
                background: rgba(0, 0, 0, 0.85);
                color: white;
                text-align: center;
                padding: 15px 0;
                margin-top: auto;
            }

            body {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
            }

            .navbar {
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(5px);
            }

            .navbar-brand {
                font-weight: bold;
                font-size: 1.5rem;
            }

            .product-card {
                background: rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(20px);
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            }

            .product-card:hover {
                transform: scale(1.05);
                box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
            }

            .product-img {
                width: 100%;
                height: 250px;
                object-fit: cover;
                border-top-left-radius: 15px;
                border-top-right-radius: 15px;
            }

            .product-card .btn {
                border-radius: 50px;
                padding: 10px;
                font-weight: bold;
                transition: 0.3s;
            }

            .btn-primary {
                background: #007bff;
                border: none;
            }

            .btn-primary:hover {
                background: #0056b3;
            }

        </style>
    </head>
    <body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">iSagha E-commerce Task</a>

                <!-- Toggler Button for Small Screens -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collapsible Menu -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <div class="d-flex flex-column flex-lg-row align-items-center">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-light me-lg-2 mb-2 mb-lg-0" data-bs-toggle="tooltip" title="Home">
                            <i class="fas fa-home"></i>
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-warning me-lg-2 mb-2 mb-lg-0 position-relative" data-bs-toggle="tooltip" title="Cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-info me-lg-2 mb-2 mb-lg-0" data-bs-toggle="tooltip" title="Orders">
                            <i class="fas fa-box"></i>
                        </a>
                        <a href="{{ route('logout') }}" class="btn btn-danger" data-bs-toggle="tooltip" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Section -->
        <div class="container mt-5">
            @if(session('first_time') !== null)
            @if(session('first_time'))
            <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                🎉 Welcome to iSagha E-commerce! We’re excited to have you. Start shopping now!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @else
            <div class="alert alert-info text-center alert-dismissible fade show" role="alert">
                👋 Welcome back, {{ auth()->user()->name }}! Happy shopping!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @php
            session()->forget('first_time'); // Remove session after showing once
            @endphp
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; {{ date('Y') }} iSagha E-commerce Task. All Rights Reserved.</p>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                fetchCartCount();
            });

            function fetchCartCount() {
                fetch("{{ route('cart.count') }}")
                    .then(response => response.json())
                    .then(data => {
                        let cartCountElement = document.getElementById("cart-count");
                        cartCountElement.textContent = data.cartCount;
                        if (data.cartCount > 0) {
                            cartCountElement.classList.remove("d-none"); // Show badge
                        } else {
                            cartCountElement.classList.add("d-none"); // Hide if zero
                        }
                    })
                    .catch(error => console.error("Error fetching cart count:", error));
            }

            function addToCart(productId) {
                fetch(`/cart/add/${productId}`, {
                        method: "POST"
                        , headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            , "Content-Type": "application/json"
                        }
                        , body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchCartCount(); // Refresh the cart count dynamically
                        } else {
                            alert("Failed to add to cart.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }

        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

        </script>
    </body>
    </html>
