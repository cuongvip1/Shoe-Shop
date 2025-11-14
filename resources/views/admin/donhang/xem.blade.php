@extends('admin.index')

@section('admin_content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="" style="margin-top: 10px">
            <strong>XEM CHI TIẾT ĐƠN HÀNG</strong>&ensp;
            <i class="fas fa-cart-arrow-down"></i>
        </h4>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <!-- table-hover -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col">Tên giày</th>
                        <th scope="col">Đơn giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $donhangs = unserialize($donhang->hoa_don);
                    @endphp

                    @foreach ($donhangs as $it)
                    <tr>
                        <td scope="row">{{ data_get($it, 'ten_giay') }} VNĐ</td>
                        <td>{{ number_format(data_get($it, 'don_gia')) }}</td>
                        <td>{{ data_get($it, 'so_luong') }}</td>
                        <td>{{ number_format(data_get($it, 'don_gia') * data_get($it, 'so_luong')) }} VNĐ</td>
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

        <div class="d-flex gap-2">
            <a href="/admin/donhang" type="button" class="btn btn-info">Quay lại danh sách</a>
            @php
                $s = data_get($donhang, 'trang_thai');
                $labels = [
                    'cho' => 'Chờ',
                    'da_xac_nhan' => 'Đã xác nhận',
                    'tu_choi' => 'Từ chối',
                    'da_huy' => 'Đã hủy',
                ];
            @endphp
            @if($s == 'cho')
                <form action="/admin/donhang/duyet/{{ $donhang->id_don_hang }}" method="POST" style="display:inline-block; margin-right:8px;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc muốn duyệt đơn hàng này?')">Duyệt đơn hàng</button>
                </form>
                <form action="/admin/donhang/tu-choi/{{ $donhang->id_don_hang }}" method="POST" style="display:inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn từ chối đơn hàng này?')">Từ chối đơn hàng</button>
                </form>
            @else
                <span class="badge bg-secondary">Trạng thái: {{ $labels[$s] ?? $s }}</span>
            @endif
        </div>

    </div>
</div>


<br>
@endsection