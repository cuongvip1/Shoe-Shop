<div class="card mb-3 shadow-5" style="background-color: #EEEEEE">
    <div class="card-body" style="margin-top:40px">
        <center>
            <h3 class="card-title">CHI TIẾT ĐƠN HÀNG #{{ data_get($order, 'id_don_hang') }}</h3>
        </center>
    </div>
    <br>
</div>

<div class="container">
    <br>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/trang-chu">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/tai-khoan/lich-su">Lịch sử đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <p><b>Người nhận:</b> {{ data_get($order, 'ten_nguoi_nhan') }}</p>
            <p><b>SDT:</b> {{ data_get($order, 'sdt') }}</p>
            <p><b>Địa chỉ:</b> {{ data_get($order, 'dia_chi_nhan') }}</p>
            <p><b>Ghi chú:</b> {{ data_get($order, 'ghi_chu') }}</p>
            <p><b>Tổng tiền:</b> {{ data_get($order, 'tong_tien') }}</p>

            <h5>Hóa đơn</h5>
            @php
                $items = [];
                try {
                    $items = is_string(data_get($order, 'hoa_don')) ? @unserialize(data_get($order, 'hoa_don')) : data_get($order, 'hoa_don');
                } catch (
                    Exception $e) {
                    $items = [];
                }
            @endphp

            @if($items && count($items) > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên giày</th>
                            <th>Size</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $it)
                            <tr>
                                <td>{{ data_get($it, 'ten_giay') }}</td>
                                <td>{{ data_get($it, 'size', 'Chưa chọn') }}</td>
                                <td>{{ number_format(data_get($it, 'don_gia')) }}</td>
                                <td>{{ data_get($it, 'so_luong') }}</td>
                                <td>{{ number_format((data_get($it, 'don_gia') * data_get($it, 'so_luong'))) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Không có thông tin hóa đơn chi tiết.</p>
            @endif

            @if(data_get($order, 'trang_thai') == 'cho')
                <form action="/tai-khoan/lich-su/huy/{{ data_get($order, 'id_don_hang') }}" method="POST" style="display:inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">Hủy đơn && Hoàn tiền</button>
                </form>
            @endif

            <a href="/tai-khoan/lich-su" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>