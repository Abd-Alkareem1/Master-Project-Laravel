@extends('user.layout.master')
@section('content')
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="#">Home</a>
                    <a class="breadcrumb-item text-dark" href="#">Shop</a>
                    <span class="breadcrumb-item active">Shopping Cart</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart Start -->
    <div class="container-fluid">
        @if (count($cartList) != 0)
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-light table-borderless table-hover text-center mb-0" id="dataTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>Pizzas</th>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <input type="hidden" value="{{ Auth::User()->id }}" id="userId">
                        @foreach ($cartList as $c)
                        <tr>
                            <td class="align-middle">
                                <img src="{{ asset('storage/' . $c->product->image) }}" class="img-thumbnail" alt="" style="width: 100px;">
                            </td>
                            <td class="align-middle">{{ $c->product->name }}</td>
                            <input type="hidden" id="orderId" value="{{ $c->id }}">
                            <input type="hidden" id="productId" value="{{ $c->product_id }}">
                            <input type="hidden" id="userId" value="{{ $c->user_id }}">
                            <td class="align-middle col-3" id="pizzaPrice">{{ $c->product->price }} JOD</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary border-0 text-center" value="{{ $c->quantity }}" id="qty">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle" id="total">{{ $c->product->price * $c->quantity }} JOD</td>
                            <td class="align-middle btnRemove">
                                <button class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Cart Summary</span></h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Subtotal</h6>
                            <h6 id="subTotalPrice">{{ $totalPrice }} JOD</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Delivery</h6>
                            <h6 class="font-weight-medium" id="deliveryPrice">2 JOD</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Total</h5>
                            <h5 id="finalTotal">{{ $totalPrice + 2 }} JOD</h5>
                        </div>
                        <!-- Cash on Delivery Button -->
                        <button class="btn btn-block btn-primary font-weight-bold my-3 py-3" id="cashOnDeliveryBtn">Cash on Delivery</button>
                        <button class="btn btn-block bg-danger text-white font-weight-bold my-3 py-3" id="clearBtn">Clear Cart</button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div style="height: 50vh" class="d-flex justify-content-center align-items-center">
            <h2 class="text-center">There are no carts right now</h2>
        </div>
        @endif
    </div>
    <!-- Cart End -->

    <!-- Modal Form for Cash on Delivery -->
    <div id="codModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Cash on Delivery</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="codForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scriptSource')
<script src="{{ asset('js/cart.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Cash on Delivery Button Click
    $('#cashOnDeliveryBtn').click(function() {
        $('#codModal').modal('show');
    });

    // Submit COD Form
    $('#codForm').submit(function(e) {
        e.preventDefault();
        let name   = $('#name').val();
        let phone  = $('#phone').val();
        let address= $('#address').val();

        if (!name || !phone || !address) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing information',
                text: 'Please fill all fields before submitting.'
            });
            return;
        }

        // Hide modal
        $('#codModal').modal('hide');

        // مسح كل الطلبات مثل Clear Cart
        $('#dataTable tbody tr').remove();
        $('#subTotalPrice').html("0 JOD");
        $('#finalTotal').html("0 JOD");

        // إرسال طلب AJAX لحفظ الطلب في السيرفر (اختياري)
        let order = {
            user_id     : $('#userId').val(),
            total_price : Number($('#finalTotal').text().replace(' JOD','')),
            name        : name,
            phone       : phone,
            address     : address
        };
        let orderList = [];
        // هنا ممكن تعبئة orderList إذا تريد إرسال المنتجات

        $.ajax({
            type     : 'post',
            url      : 'http://127.0.0.1:8000/user/ajax/order/cod',
            data     : {order: order, orderList: orderList},
            dataType : 'json',
            headers  : {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Placed!',
                    text: 'Your order has been placed and is being processed.'
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'success',
                    title: '',
                    text: 'Your request is being processed'
                });
            }
        });
    });

    // Clear Cart
    $('#clearBtn').click(function() {
        $('#dataTable tbody tr').remove();
        $('#subTotalPrice').html("0 JOD");
        $('#finalTotal').html("0 JOD");

        $.ajax({
            type     : 'get',
            url      : 'http://127.0.0.1:8000/user/ajax/clear/cart',
            dataType : 'json'
        });
    });

    // Remove Product
    $('.btnRemove').click(function() {
        let $parentNode = $(this).parents("tr");
        let $productId  = $parentNode.find('#productId').val();
        let $orderId    = $parentNode.find('#orderId').val();

        $.ajax({
            type     : 'get',
            url      : 'http://127.0.0.1:8000/user/ajax/remove',
            data     : {'productId': $productId, 'orderId': $orderId},
            dataType : 'json'
        });
        $parentNode.remove();

        let totalPrice = 0;
        $('#dataTable tbody tr').each(function(index, row) {
            totalPrice += Number($(row).find('#total').text().replace(" JOD",""));
        });
        $('#subTotalPrice').html(totalPrice + " JOD");
        $('#finalTotal').html((totalPrice + 2) + " JOD");
    });

    // تعديل التوصيل إلى 2 JOD
    $('#deliveryPrice').html('2 JOD');
});
</script>
@endsection
