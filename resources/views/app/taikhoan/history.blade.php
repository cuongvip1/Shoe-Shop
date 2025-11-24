<div class="card mb-3 shadow-5" style="background-color: #EEEEEE">
    <div class="card-body" style="margin-top:40px">
        <center>
            <h3 class="card-title">LỊCH SỬ ĐƠN HÀNG</h3>
        </center>
    </div>
    <br>
</div>

<div class="container">
    <br>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/trang-chu">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lịch sử đơn hàng</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            @if(session('thatbai'))
                <div class="alert alert-danger">{{ session('thatbai') }}</div>
            @endif

            @if(empty($myOrders) || count($myOrders) == 0)
                <p>Bạn chưa có đơn hàng nào.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Người nhận</th>
                            <th>SDT</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myOrders as $o)
                            <tr>
                                <td>{{ data_get($o, 'created_at') }}</td>
                                <td>{{ data_get($o, 'ten_nguoi_nhan') }}</td>
                                <td>{{ data_get($o, 'sdt') }}</td>
                                <td>{{ data_get($o, 'tong_tien') }}</td>
                                @php
                                    $s = data_get($o, 'trang_thai', 'cho');
                                    $labels = [
                                        'cho' => 'Chờ',
                                        'da_xac_nhan' => 'Đã xác nhận',
                                        'tu_choi' => 'Từ chối',
                                        'da_huy' => 'Đã hủy',
                                    ];
                                @endphp
                                <td>{{ $labels[$s] ?? $s }}</td>
                                <td><a href="/tai-khoan/lich-su/xem/id={{ data_get($o, 'id_don_hang') }}" class="btn btn-sm btn-info">Xem</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>