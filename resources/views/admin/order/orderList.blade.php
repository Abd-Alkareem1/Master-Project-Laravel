@extends('admin.layouts.master')

@section('title', 'Category List Page')

@section('content')

    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="col-md-12">
                    <!-- DATA TABLE -->
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <div class="overview-wrap">
                                <h2 class="title-1">Orders</h2>

                            </div>
                        </div>
                        <div class="table-data__tool-right">
                            <button class="au-btn au-btn-icon au-btn--green au-btn--small">
                                CSV download
                            </button>
                        </div>
                    </div>

                    @if (session('success'))
                        {{-- BOOTSTRAP ALERT BOX  --}}
                        <div class="col-4 offset-8">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-check"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        {{-- BOOTSTRAP ALERT BOX END  --}}
                    @endif

                    @if (session('productDelete'))
                        {{-- BOOTSTRAP ALERT BOX  --}}
                        <div class="col-4 offset-8">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                {{ session('productDelete') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        {{-- BOOTSTRAP ALERT BOX END  --}}
                    @endif

                    {{-- SEARCH BOX START --}}
                    <div class="row">
                        <div class="col-3">
                            @if (request('key'))
                            <h4 class="text-secondary">Search key: <span class="text-success">{{ request('key') }}</span>
                            </h4>
                            @endif
                            
                        </div>
                        <div class="col-3 offset-6">
                            <form action="{{ route('order#list') }}" method="GET">
                                @csrf
                                <div class="d-flex">
                                    <input type="text" name="key" class="form-control" placeholder="Search By UserName"
                                        value="{{ request('key')}}">
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- SEARCH BOX END --}}

                    {{-- TOTAL BOX START  --}}
                    <div class="row">
                        <div class="col-5">
                            <h3 class=""><i class="fa-solid fa-database "></i> <span>Total - {{$orders->total()}}
                                   </span>
                            </h3>
                        </div>
                    </div>
                    {{-- TOTAL BOX END --}}

                   <div class="row">
                    <div class="col-2 my-4">
                        <select name="filterStatus" id="filterStatus" class="form-control">
                            <option value="all">All</option>
                            <option value={{ 0}}>Pending</option>
                            <option value={{1}}>Approve</option>
                            <option value={{ 2 }}>Reject</option>
                        </select>
                    </div>
                   </div>

                    {{-- UPDATE SUCCESS BOX START --}}
                    @if (session('updateSuccess'))
                        {{-- BOOTSTRAP ALERT BOX  --}}
                        <div class="col-4 offset-8">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-check"></i> {{ session('updateSuccess') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        {{-- BOOTSTRAP ALERT BOX END  --}}
                    @endif
                    {{-- UPDATE SUCCESS BOX END --}}

                    <div class="table-responsive table-responsive-data2" id="order-table">

                        @if (count($orders) != 0)
                            {{-- LIST TABLE START  --}}
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>User ID</th>
                                        <th>User Name</th>
                                        <th>Order Date</th>
                                        <th>Total</th>
                                        <th>Lists</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($orders as $order)
                                    <tr class="tr-shadow my-2">
                                        <td class="col-2">
                                            <input type="hidden" value="{{ $order->id}}" class="orderId">
                                            <span class="">{{ $order->user->id }}</span>
                                        </td>
                                        <td class="col-2">
                                            <span class="">{{ $order->user->name }}</span>
                                        </td>
                                        <td class="col-2 ">{{ $order->created_at->format('m/d/Y') }}</td>
                                        <td class="col-2">{{ $order->total_price }} Kyats</td>
                                        <td class="col-2">
                                            <a href="{{ route('orderList', $order->id)}}">
                                                <button type="button" class="btn btn-dark position-relative">
                                                    Lists
                                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                      {{count($order->orderLists)}}
                                                      <span class="visually-hidden">unread messages</span>
                                                    </span>
                                                  </button>                                                </a>
                                        </td>
                                    
                                        <td class=" col-2 ">
                                            <select class="form-control orderStatus" name="orderStatus">
                                                <option value="0" class="form-control" {{ $order->status == 0 ? 'selected' : '' }}>pending</option>
                                                <option value="1" class="form-control" {{ $order->status == 1 ? 'selected' : '' }}>approve</option>
                                                <option value="2" class="form-control"{{ $order->status == 2 ? 'selected' : '' }} >reject</option>
                                            </select>
                                        </td>
                                    </tr>                                    @endforeach
                                </tbody>
                            </table>
                            {{-- LIST TABLE END  --}}
                        @else
                            <h1 class=" text-secondary text-center">There is no data</h1>

                        @endif


                        {{-- PAGINATOR UI START --}}

                        <div class="mt-3">
                            {{ $orders->links() }}
                        </div> 

                        {{-- PAGINATOR UI END  --}}
                    </div>



                    <!-- END DATA TABLE -->
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT-->
@endsection

@section('scriptSection')
    <script>
        $('.orderStatus').each(function() {
    $(this).change(function() {
        $orderId = $(this).closest('tr').find('.orderId').val();
        // Your AJAX request
        $.ajax({
            type: 'get',
            url: 'http://127.0.0.1:8000/order/status',
            data: { orderStatus: parseInt($(this).val(), 10), orderId: $orderId },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });


});

$('#filterStatus').change(function() {
        $status = $('#filterStatus').val();
        $.ajax({
            type:'get',
            url: 'http://127.0.0.1:8000/order/filter',
            data: { status: $status},
            dataType: 'json',
            success: function(response) {
                console.log(response);
    let $list = '';

    if (response.length > 0) {
        for (let i = 0; i < response.length; i++) {

            let formattedDate = new Date(response[i].created_at).toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
            });

            $list += `
                <tr class="tr-shadow my-2">
                    <td class="col-2">
                        <input type="hidden" value="${response[i].id}" class="orderId">
                        <span class="">${response[i].user_id}</span>
                    </td>
                    <td class="col-2">
                        <span class="">${response[i].name}</span>
                    </td>
                    <td class="col-2">${formattedDate}</td>
                    <td class="col-2">${response[i].total_price} Kyats</td>
                    <td class="col-2">
                        <a href="{{ route('orderList', $order->id)}}">
                            <button type="button" class="btn btn-dark position-relative">
                                Lists
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    ${response[i].order_lists_count}
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                            </button>
                        </a>
                    </td>
                    <td class="col-2">
                        <select class="form-control orderStatus" name="orderStatus">
                            <option value="0" class="form-control" ${response[i].status == 0 ? 'selected' : ''}>pending</option>
                            <option value="1" class="form-control" ${response[i].status == 1 ? 'selected' : ''}>approve</option>
                            <option value="1" class="form-control" ${response[i].status == 2 ? 'selected' : ''}>reject</option>

                        </select>
                    </td>
                </tr>
            `;
        }

        $('#tableBody').html($list);
    } else {
        $list += `<div></div>`;
        $('#tableBody').html($list);
    }
}

        })
    })
    </script>
@endsection
