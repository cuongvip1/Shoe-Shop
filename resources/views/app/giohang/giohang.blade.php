<div class="card mb-3 shadow-5" style="background-color: #EEEEEE">
    <div class="card-body" style="margin-top:40px">
        <center>
            <h3 class="card-title">GIỎ HÀNG</h3>
        </center>
    </div>
    <br>
</div>

<div class="container">
    <br>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/trang-chu">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>
    <br>

    
    <div class="table-responsive">
        <!-- table-hover -->
        <table class="table" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Hình ảnh</th>
                    <th scope="col">Tên giày</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col">Khuyến mãi</th>
                    <th scope="col">Size</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Thành tiền</th>
                    <th scope="col">Thay đổi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($giohangs as $id=>$giohang)
                <tr>
                    @php
                        // Ensure $maxQty is available for this row (product stock)
                        $giayObj = isset($giays) ? ($giays->firstWhere('id_giay', $id) ?? null) : null;
                        $maxQty = $giayObj ? intval($giayObj->so_luong) : 0;
                        $qtyMessage = $maxQty > 0 ? 'Sản phẩm trong kho còn lại: '.$maxQty : 'Sản phẩm đã hết hàng';
                    @endphp
                    <th>
                        <form id="thanh-toan" action="/thanh-toan" method="POST">
                            @csrf 
                            <div class="form-check info">
                                @if(isset($giays) && ($giays->firstWhere('id_giay', $id)->so_luong ?? 0) > 0)
                                    <input class="form-check-input" type="checkbox" value="{{$id}}" name="check-gio-hang[]" form="thanh-toan" checked/>
                                @else
                                    <input class="form-check-input" type="checkbox" value="{{$id}}" disabled />
                                @endif
                            </div>
                        </form>
                    </th>
                    <td scope="row">
                        <img src="/storage/images/{{$giohang['hinh_anh_1']}}" alt="..." class="img-fluid rounded-start"
                            width="100px" />
                    </td>
                    <td>{{$giohang['ten_giay']}}</td>
                    <td>{{number_format($giohang['don_gia'])}} VNĐ</td>
                    <td>{{$giohang['khuyen_mai']}}%</td>
                    
                    <form action="/gio-hang/cap-nhat" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" name="id" value="{{$id}}"/>
                        
                        <td>
                            <select name="size" class="form-select form-select-sm">
                                <option value="">-- Chọn size --</option>
                                <option value="35" @if(isset($giohang['size']) && $giohang['size'] == 35) selected @endif>35</option>
                                <option value="36" @if(isset($giohang['size']) && $giohang['size'] == 36) selected @endif>36</option>
                                <option value="37" @if(isset($giohang['size']) && $giohang['size'] == 37) selected @endif>37</option>
                                <option value="38" @if(isset($giohang['size']) && $giohang['size'] == 38) selected @endif>38</option>
                                <option value="39" @if(isset($giohang['size']) && $giohang['size'] == 39) selected @endif>39</option>
                                <option value="40" @if(isset($giohang['size']) && $giohang['size'] == 40) selected @endif>40</option>
                                <option value="41" @if(isset($giohang['size']) && $giohang['size'] == 41) selected @endif>41</option>
                                <option value="42" @if(isset($giohang['size']) && $giohang['size'] == 42) selected @endif>42</option>
                                <option value="43" @if(isset($giohang['size']) && $giohang['size'] == 43) selected @endif>43</option>
                                <option value="44" @if(isset($giohang['size']) && $giohang['size'] == 44) selected @endif>44</option>
                                <option value="45" @if(isset($giohang['size']) && $giohang['size'] == 45) selected @endif>45</option>
                            </select>
                        </td>

                        <td>
                            <div class="d-flex">
                                <div class="btn btn-info px-3 mr-1"
                                    onclick="this.parentNode.querySelector('input[type=number]').stepDown()" style="margin-right:2px"
                                    @if($maxQty == 0) disabled @endif>
                                    <i class="fas fa-minus"></i>
                                </div>

                                <div class="form-outline" style="width:80px">
                                    @php
                                        $giayObj = $giays->firstWhere('id_giay', $id) ?? null;
                                        $maxQty = $giayObj ? intval($giayObj->so_luong) : 1;
                                    @endphp
                                    <input id="form1" min="1" max="{{ $maxQty }}" name="so_luong" value="{{ min($giohang['so_luong'], $maxQty) }}"
                                        type="number" autocomplete="off" class="form-control"
                                        oninput="this.setCustomValidity('')" data-max-msg="{{ $qtyMessage }}"
                                        oninvalid="this.setCustomValidity(this.dataset.maxMsg)" />
                                    <label class="form-label" for="form1">Số lượng</label>
                                </div>&nbsp;

                                <div class="btn btn-info px-3 mr-1"
                                    onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                                    @if($maxQty == 0) disabled @endif>
                                    <i class="fas fa-plus"></i>
                                </div>
                            </div>
                            <td>
                            @php $effectiveQty = min($giohang['so_luong'], $maxQty); @endphp
                            <td><b>{{number_format($km = sprintf('%d', ($effectiveQty * $giohang['don_gia']) - ($effectiveQty * $giohang['don_gia'] * $giohang['khuyen_mai'] * 0.01)))}} VNĐ<b>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-info">Cập nhật</button>

                            <a href="/gio-hang/xoa/id={{$id}}" onclick="return confirm('Bạn có thật sự muốn xóa ?');" type="button"
                                class="btn btn-danger">Xóa</a>
                        </td>
                    </form>
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

    <br>
    <br>

    @php
    $tongtien = 0;
    @endphp

    @foreach($giohangs as $id => $giohang)
    @php
    $tongtien += $giohang['so_luong'] * $giohang['don_gia'];
    @endphp
    @endforeach
    <div class="card ">
        <form class="card-header">
            <div class="float-start">
                <h4 class="card-title text-success" style="margin-top: 20px">Tổng tiền:  {{number_format($tongtien)}} VNĐ
                </h4>
            </div>
                <div class="float-end">
                    <button type="submit" class="btn btn-success" style="margin: 15px" form="thanh-toan">Thanh Toán</button>
                </div>
            </div>
        </form>
    </div>
 
    <br>
    <br>

</div>