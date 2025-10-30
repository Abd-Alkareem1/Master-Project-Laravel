@extends('user.layout.master')

@section('content')
    {{-- SHOP START --}}

    <div class="container-fluid">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter by
                        price</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form>
                        <div class=" d-flex align-items-center justify-content-between mb-3 bg-dark text-white px-3 py-1">
                            <input type="checkbox" class="custom-control-input" checked id="price-all">
                            <label class="" for="price-all">Categories</label>
                            <span class="badge border font-weight-normal">{{ $categories->count() }}</span>


                        </div>

                        <div class=" d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ route('user#home') }}" class="text-dark"><label for=""
                                    class="">All</label></a>
                        </div>

                        @foreach ($categories as $category)
                            <div class=" d-flex align-items-center justify-content-between mb-3">
                                <a href="{{ route('user#filter', $category->id) }}" class="text-dark "><label for=""
                                        class="text-capitalize">{{ $category->name }}</label></a>
                            </div>
                        @endforeach
                    </form>
                </div>
                <div class="">
                    <button class="btn btn btn-warning w-100">Order</button>
                </div>
                <!-- Size End -->
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->

            <div class="col-lg-9 col-md-8">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <a href="{{ route('user#carts') }}" class="">
                                    <button type="button" class="btn bg-dark position-relative me-2 rounded">
                                        <i class="fa-solid fa-cart-shopping text-white"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            id="cartCount">

                                            {{ $carts->count() }}
                                        </span>
                                    </button>
                                </a>

                                <a href="{{ route('user#history') }}">
                                    <button type="button" class="btn bg-dark position-relative">
                                        <i class="fa-sharp fa-solid fa-clock-rotate-left text-white"></i>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $orders->count() }}
                                        </span>
                                    </button>
                                </a>
                            </div>


                            <div class="ml-2">
                                <div class="btn-group">
                                    <select name="" id="sortingOption" class="form-control">
                                        <option value="">Choose Options</option>
                                        <option value="asc">Oldest</option>
                                        <option value="desc">Newest</option>
                                        <option value="popular">Most Popular</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span id="dataList" class="row">
                        @if (count($pizzas) != 0)
                            @foreach ($pizzas as $pizza)
                                <div class="col-lg-4 col-md-6 col-sm-6 pb-1 ">
                                    <div class="product-item bg-light mb-4" id="myForm">
                                        <div class="product-img position-relative overflow-hidden">
                                            <img class="img-fluid w-100" style="height: 230px"
                                                src="{{ asset('storage/' . $pizza->image) }}" alt="">
                                            <div class="product-action">
                                                <input type="hidden" name="piazzaId" value="{{ $pizza->id }}"
                                                    class="pizzaId">
                                                <a class="btn btn-outline-dark btn-square cartBtn"><i
                                                        class="fa fa-shopping-cart"></i></a>
                                                <a class="btn btn-outline-dark btn-square"
                                                    href="{{ route('user#show', $pizza->id) }}"><i
                                                        class="fa-solid fa-circle-info"></i></a>

                                            </div>
                                        </div>
                                        <div class="text-center py-4">
                                            <a class="h6 text-decoration-none text-truncate"
                                                href="">{{ $pizza->name }}</a>
                                            <div class="d-flex align-items-center justify-content-center mt-2">
                                                <h5>{{ $pizza->price }}</h5>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h1 class="text-center bg-warning fs-5 text-white p-4 col-6 offset-3 ">There is no food in
                                this category</h1>
                        @endif
                    </span>

                </div>
            </div>


            <!-- Shop Product End -->
        </div>
    </div>

    {{-- SHOP END  --}}
@endsection

@section('scriptSource')
    <script>
        $(document).ready(function() {
            $('#sortingOption').change(function() {
                $eventOption = $('#sortingOption').val();
                if ($eventOption == 'asc') {
                    $.ajax({
                        type: 'get',
                        url: 'http://127.0.0.1:8000/user/ajax/pizzas',
                        data: {
                            'status': 'asc'
                        },
                        dataType: 'json', //essential
                        success: function(response) {
                            $list = '';
                            for ($i = 0; $i < response.length; $i++) {
                                $list += `
                            <div class="col-lg-4 col-md-6 col-sm-6 pb-1 " >
                            <div class="product-item bg-light mb-4" id="myForm">
                                <div class="product-img position-relative overflow-hidden">
                                    <img class="img-fluid w-100" style="height: 230px"
                                        src="{{ asset('storage/${response[$i].image}') }}" alt="">
                                    <div class="product-action">
                                        <a  class="btn btn-outline-dark btn-square cartBtn"><i
                                                class="fa fa-shopping-cart"></i></a>
                                        <a class="btn btn-outline-dark btn-square" href=""><i
                                                class="fa-solid fa-circle-info"></i></a>
                                    </div>
                                </div>
                                <div class="text-center py-4">
                                    <a class="h6 text-decoration-none text-truncate" href="">${response[$i].name}</a>
                                    <div class="d-flex align-items-center justify-content-center mt-2">
                                        <h5>${response[$i].price}</h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                            `;
                            }
                            $('#dataList').html($list);

                        }
                    })
                } else if ($eventOption == 'desc') {
                    $.ajax({
                        type: 'get',
                        url: 'http://127.0.0.1:8000/user/ajax/pizzas',
                        data: {
                            'status': 'desc'
                        },
                        dataType: 'json', //essential
                        success: function(response) {
                            $list = '';
                            for ($i = 0; $i < response.length; $i++) {
                                $list += `
                            <div class="col-lg-4 col-md-6 col-sm-6 pb-1 " >
                            <div class="product-item bg-light mb-4" id="myForm">
                                <div class="product-img position-relative overflow-hidden">
                                    <img class="img-fluid w-100" style="height: 230px"
                                        src="{{ asset('storage/${response[$i].image}') }}" alt="">
                                    <div class="product-action">
                                        <a class="btn btn-outline-dark btn-square cartBtn"><i
                                                class="fa fa-shopping-cart"></i></a>
                                        <a class="btn btn-outline-dark btn-square" href=""><i
                                                class="fa-solid fa-circle-info"></i></a>
                                    </div>
                                </div>
                                <div class="text-center py-4">
                                    <a class="h6 text-decoration-none text-truncate" href="">${response[$i].name}</a>
                                    <div class="d-flex align-items-center justify-content-center mt-2">
                                        <h5>${response[$i].price}</h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                            `;
                            }
                            $('#dataList').html($list);
                        }
                    })
                } else if ($eventOption == 'popular') {
                    $.ajax({
                        type: 'get',
                        url: 'http://127.0.0.1:8000/user/ajax/pizzas',
                        data: {
                            'status': 'popular'
                        },
                        dataType: 'json', //essential
                        success: function(response) {
                            $list = '';
                            for ($i = 0; $i < response.length; $i++) {
                                $list += `
                            <div class="col-lg-4 col-md-6 col-sm-6 pb-1 " >
                            <div class="product-item bg-light mb-4" id="myForm">
                                <div class="product-img position-relative overflow-hidden">
                                    <img class="img-fluid w-100" style="height: 230px"
                                        src="{{ asset('storage/${response[$i].image}') }}" alt="">
                                    <div class="product-action">
                                        <a class="btn btn-outline-dark btn-square cartBtn"><i
                                                class="fa fa-shopping-cart"></i></a>
                                        <a class="btn btn-outline-dark btn-square" href=""><i
                                                class="fa-solid fa-circle-info"></i></a>
                                    </div>
                                </div>
                                <div class="text-center py-4">
                                    <a class="h6 text-decoration-none text-truncate" href="">${response[$i].name}</a>
                                    <div class="d-flex align-items-center justify-content-center mt-2">
                                        <h5>${response[$i].price}</h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                            `;
                            }
                            $('#dataList').html($list);
                        }
                    })
                }
            });

            $('.cartBtn').each(function() {
                $(this).click(function(event) {
                    event.preventDefault();
                    $pizzaId = $(this).closest('.product-item').find('.pizzaId').val();

                    $.ajax({
                        type: 'get',
                        url: 'http://127.0.0.1:8000/user/ajax/autoAddToCart',
                        data: {
                            pizzaId: $pizzaId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                $count = parseInt($('#cartCount').text()) +
                                1; // Parse the current text as an integer
                                $('#cartCount').text($count);
                            }
                        }
                    })

                });
            });
        });
    </script>
@endsection
