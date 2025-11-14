@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="" style="margin-top: 10px">
            <strong>ĐƠN HÀNG ĐÃ XỬ LÝ</strong>&ensp;
            <i class="fas fa-history"></i>
        </h4>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <form method="GET" action="{{ url('/admin/donhang/processed') }}" class="form-inline">
                <label for="status" class="mr-2">Lọc trạng thái:</label>
                <select id="status" name="status" class="form-control mr-2">
                    <option value="" {{ empty($filter) ? 'selected' : '' }}>Tất cả</option>
                    <option value="da_xac_nhan" {{ ($filter === 'da_xac_nhan') ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="tu_choi" {{ ($filter === 'tu_choi') ? 'selected' : '' }}>Từ chối</option>
                    <option value="da_huy" {{ ($filter === 'da_huy') ? 'selected' : '' }}>Đã hủy</option>
                </select>
                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableProcessed" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tên người nhận</th>
                        <th scope="col">Địa chỉ nhận</th>
                        <th scope="col">Số điện thoại</th>
                        <th scope="col">Ngày đặt hàng</th>
                        <th scope="col">Ghi chú</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Thay đổi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($processedOrders as $donhang)
                    <tr>
                        <td scope="row">{{ data_get($donhang, 'id_don_hang') }}</td>
                        <td>{{ data_get($donhang, 'ten_nguoi_nhan') }}</td>
                        <td>{{ data_get($donhang, 'dia_chi_nhan') }}</td>
                        <td>{{ data_get($donhang, 'sdt') }}</td>
                        <td>{{ data_get($donhang, 'created_at') }}</td>
                        <td>{{ data_get($donhang, 'ghi_chu') }}</td>
                        <td>{{ data_get($donhang, 'tong_tien') }}</td>
                        <td>{{ data_get($donhang, 'trang_thai') }}</td>
                        <td>
                            <a href="/admin/donhang/xem/id={{ data_get($donhang, 'id_don_hang') }}" type="button" class="btn btn-success btn-rounded">Xem chi tiết</a>
                            <a href="/admin/donhang/xoa/id={{ data_get($donhang, 'id_don_hang') }}" onclick="return confirm('Bạn có thật sự muốn xóa ?');" type="button"
                                class="btn btn-danger btn-rounded">Xóa</a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <script>
            $(document).ready(function() {
                $('#dataTableProcessed').DataTable();
            });
            </script>
        </div>
    </div>

</div>

<br>
@endsection