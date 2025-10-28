@extends('admin.layouts.master')

@section('title', 'User List Page')

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
                                <h2 class="title-1">user List</h2>

                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        {{-- BOOTSTRAP ALERT BOX  --}}
                        <div class="col-4 offset-8">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa-solid fa-check"></i> <span id="notice"></span>
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
                            <form action="{{ route('user#list')}}" method="GET">
                                @csrf
                                <div class="d-flex">
                                    <input type="text" name="key" class="form-control" placeholder="Search"
                                        value="{{ request('key') }}">
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
                            <h3 class=""><i class="fa-solid fa-database "></i> <span>{{$users->count()}}</span>
                            </h3>
                        </div>
                    </div>
                    {{-- TOTAL BOX END --}}
                    <div class="table-responsive table-responsive-data2">
                        @if (count($users) != 0)

                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>IMAGE</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>GENDER</th>
                                        <th>PHONE</th>
                                        <th>ADDRESS</th>
                                        <th>Role</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr class="tr-shadow my-2 userTable">
                                            <input type="hidden" name="" class="userId" value="{{ $user->id }}">
                                            <td class="col-2">
                                                @if ($user->image == null)
                                                    <img src="{{ asset('image/defaultUser.png') }}" alt="">
                                                @else
                                                    <img src="{{ asset('storage/' . $user->image) }}"
                                                        class="shadow-sm img-thumbnail" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->gender ? $user->gender : '___' }}</td>
                                            <td>{{ $user->phone  ? $user->gender : '___'}}</td>
                                            <td>{{ $user->address ? $user->gender : '___' }}</td>
                                            <td><select name="" class="form-control userStatus">
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : ''}}>User</option>    
                                                <option value="admin" >Admin</option>
                                            </select></td>

                                        </tr>
                                    @endforeach




                                </tbody>
                            </table>
                            {{-- PAGINATOR UI START --}}

                            <div class="mt-4">
                                {{ $users->links()}}
                            </div>

                            {{-- PAGINATOR UI END  --}}
                    </div>
                @else
                    <h1 class=" text-secondary text-center">There is no data</h1>
                    @endif
                    <!-- END DATA TABLE -->
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT-->
@endsection

@section('scriptSection')
<script>
     $('.userStatus').each(function() {
        $(this).change(function() {
            $userId =  $(this).closest('.userTable').find('.userId').val();
            $role = $(this).val();

            $.ajax({
                type: 'get',
                url: 'http://127.0.0.1:8000/user/changeUserRole',
                data: {
                    'role': $role,
                    'userId' : $userId,
                },
                dataType: 'json',
                success: function(response) {
                    window.location.reload();
                }
            })
        })
     })
</script>
   
@endsection
