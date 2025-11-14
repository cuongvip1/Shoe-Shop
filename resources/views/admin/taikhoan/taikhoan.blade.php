@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="" style="margin-top: 10px">
            <strong>QUẢN LÝ TÀI KHOẢN</strong>&ensp;
            <i class="fas fa-user"></i>
        </h4>
    </div>

    <div class="card-body">
        @if(session('thanhcong'))
            <div class="alert alert-success">{{ session('thanhcong') }}</div>
        @endif
        @if(session('thatbai'))
            <div class="alert alert-danger">{{ session('thatbai') }}</div>
        @endif
        <div class="table-responsive">
            <!-- table-hover -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên người dùng</th>
                        <th scope="col">Email</th>
                        <th scope="col">SĐT</th>
                        <th scope="col">Tên đăng nhập</th>
                        <th scope="col">Mã phân quyền</th>
                        <th scope="col">Thay đổi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ data_get($user, 'id') ?? data_get($user, 'id_user') }}</th>
                        <td>{{ data_get($user, 'ten_nguoi_dung') }}</td>
                        <td>{{ data_get($user, 'email') }}</td>
                        <td>{{ data_get($user, 'sdt') }}</td>
                        <td>{{ data_get($user, 'Ten_dang_nhap') ?? data_get($user, 'ten_dang_nhap') }}</td>
                        <td>{{ data_get($user, 'id_phan_quyen') ?? data_get($user, 'phan_quyen_id') }}</td>
                        <td>
                            <!-- <a href="" type="button" class="btn btn-success btn-rounded" target="_blank">Xem</a> -->
                            <a href="/admin/taikhoan/sua/id={{ data_get($user, 'id') ?? data_get($user, 'id_user') }}" type="button" class="btn btn-warning btn-rounded">Sửa</a>
                            <a href="/admin/taikhoan/xoa/id={{ data_get($user, 'id') ?? data_get($user, 'id_user') }}" onclick="return confirm('Bạn có thật sự muốn xóa ?');" type="button"
                                class="btn btn-danger btn-rounded">Xóa</a>
                        </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>

            <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
            </script>
        </div>
    </div>

</div>


<div class="card shadow">
    <div class="card-header">
        <h5 class="card-title" style="margin-top: 10px">Tùy chỉnh:</h5>
    </div>
    <div class="card-body">

        <a href="/admin/taikhoan/them" type="button" class="btn btn-info">Thêm Tài khoản</a>

    </div>
</div>



<br>
@endsection