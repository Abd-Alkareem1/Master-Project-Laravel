@extends(Auth::user()->role !== 'admin' ? 'user.layout.master' : 'user.layout.adminMaster')

@section('content')
<!-- Cart Start -->
<div class="container-fluid" style="height: 400px">
    <div class="row px-xl-5">
        <div class="col-lg-8 offset-2 table-responsive mb-5">
            <table class="table table-light table-borderless table-hover text-center mb-0" id="dataTable">
                <thead class="thead-dark">
                    <tr>
                        <th>User</th>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    @foreach ($orderList as $order)
                    <tr>
                        <td class="align-middle">{{ $order->user->name }}</td>
                        <td class="align-middle">{{ $order->order->id }}</td>
                        <td class="align-middle ">
                            <img src="{{ asset('storage/'.$order->product->image ) }}" style="width: 50px" alt="{{ $order->product->name}}">
                        </td>
                        <td class="align-middle">{{ $order->qty }}</td>
                        <td class="align-middle">{{ $order->total }} JOD</td>
                        <td class="align-middle">{{ $order->created_at->format('F-j-Y')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Cart End -->
@endsection