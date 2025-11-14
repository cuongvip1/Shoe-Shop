<!-- <div class="card mb-3 shadow-5" style="background-color: #EEEEEE">
    <div class="card-body" style="margin-top:40px">
        <center>
            <h3 class="card-title" style="text-transform: uppercase;">NỘI DUNG</h3>
        </center>
    </div>
    <br>
</div> -->

<div class="container">
    <br>

    <div class="card mb-3 shadow-1">
        <div class="card-body" style="margin-top:40px">
            <center>
                <h3 class="card-title" style="text-transform: uppercase;">THƯƠNG HIỆU</h3>
            </center>
        </div>
        <br>
    </div>
    <!-- Tabs navs -->
    <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="ex3-tab-1" data-mdb-toggle="tab" href="#ex3-tabs-1" role="tab"
                aria-controls="ex3-tabs-1" aria-selected="true">Adidas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex3-tab-2" data-mdb-toggle="tab" href="#ex3-tabs-2" role="tab"
                aria-controls="ex3-tabs-2" aria-selected="false">Nike</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="tab" href="#ex3-tabs-3" role="tab"
                aria-controls="ex3-tabs-3" aria-selected="false">Converse</a>
        </li>
    </ul>
    <!-- Tabs navs -->

    <!-- Tabs content -->
    <div class="tab-content" id="ex2-content">
        <div class="tab-pane fade show active" id="ex3-tabs-1" role="tabpanel" aria-labelledby="ex3-tab-1">
                <div class="row">
                    @foreach($giays_adidas ?? [] as $giay)
                        <div class="col-md-3">
                            <div class="card" style="margin-bottom: 25px">
                                <a href="/cua-hang/san-pham={{ data_get($giay,'id_giay') }}">
                                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light" >
                                        <center><img src="/storage/images/{{ data_get($giay,'hinh_anh_1') }}" class="img-fluid" style="height:306px; width:306px"/></center>
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </div>
                                    <div class="card-body">
                                        <center>
                                            <h4 class="card-title">{{ data_get($giay,'ten_giay') }}</h4>
                                            <p class="card-text text-success">
                                                @php $km = 0; @endphp
                                                @foreach($khuyenmais ?? [] as $khuyenmai)
                                                    @if(data_get($khuyenmai,'ten_khuyen_mai') == data_get($giay,'ten_khuyen_mai'))
                                                        @php $km = intval(sprintf('%d', data_get($giay,'don_gia',0) * 0.01 * data_get($khuyenmai,'gia_tri_khuyen_mai',0))); @endphp
                                                    @endif
                                                @endforeach

                                                <b>{{ number_format(data_get($giay,'don_gia',0) - $km, 0, ',', ',') }} VNĐ</b>
                                                <del class="card-text text-danger">{{ number_format(data_get($giay,'don_gia',0), 0, ',', ',') }} VNĐ</del>
                                            </p>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>

    <div class="tab-pane fade" id="ex3-tabs-2" role="tabpanel" aria-labelledby="ex3-tab-2">
        <div class="row">
            @foreach($giays_nike ?? [] as $giay)
                <div class="col-md-3">
                    <div class="card" style="margin-bottom: 25px">
                        <a href="/cua-hang/san-pham={{ data_get($giay,'id_giay') }}">
                            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light" >
                                <center><img src="/storage/images/{{ data_get($giay,'hinh_anh_1') }}" class="img-fluid" style="height:306px; width:306px"/></center>
                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                            </div>
                            <div class="card-body">
                                <center>
                                    <h4 class="card-title">{{ data_get($giay,'ten_giay') }}</h4>
                                    <p class="card-text text-success">
                                        @php $km = 0; @endphp
                                        @foreach($khuyenmais ?? [] as $khuyenmai)
                                            @if(data_get($khuyenmai,'ten_khuyen_mai') == data_get($giay,'ten_khuyen_mai'))
                                                @php $km = intval(sprintf('%d', data_get($giay,'don_gia',0) * 0.01 * data_get($khuyenmai,'gia_tri_khuyen_mai',0))); @endphp
                                            @endif
                                        @endforeach

                                        <b>{{ number_format(data_get($giay,'don_gia',0) - $km, 0, ',', ',') }} VNĐ</b>
                                        <del class="card-text text-danger">{{ number_format(data_get($giay,'don_gia',0), 0, ',', ',') }} VNĐ</del>
                                    </p>
                                </center>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="tab-pane fade" id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
        <div class="row">
            @foreach($giays_converse ?? [] as $giay)
                <div class="col-md-3">
                    <div class="card" style="margin-bottom: 25px">
                        <a href="/cua-hang/san-pham={{ data_get($giay,'id_giay') }}">
                            <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                                <center><img src="/storage/images/{{ data_get($giay,'hinh_anh_1') }}" class="img-fluid" style="height:306px; width:306px"/></center>
                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                            </div>
                            <div class="card-body">
                                <center>
                                    <h4 class="card-title">{{ data_get($giay,'ten_giay') }}</h4>
                                    <p class="card-text text-success">
                                        @php $km = 0; @endphp
                                        @foreach($khuyenmais ?? [] as $khuyenmai)
                                            @if(data_get($khuyenmai,'ten_khuyen_mai') == data_get($giay,'ten_khuyen_mai'))
                                                @php $km = intval(sprintf('%d', data_get($giay,'don_gia',0) * 0.01 * data_get($khuyenmai,'gia_tri_khuyen_mai',0))); @endphp
                                            @endif
                                        @endforeach

                                        <b>{{ number_format(data_get($giay,'don_gia',0) - $km, 0, ',', ',') }} VNĐ</b>
                                        <del class="card-text text-danger">{{ number_format(data_get($giay,'don_gia',0), 0, ',', ',') }} VNĐ</del>
                                    </p>
                                </center>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
<div class="tab-pane fade" id="ex3-tabs-3" role="tabpanel" aria-labelledby="ex3-tab-3">
    <div class="row">
        @if($dem = 1)@endif
        @foreach(($giays ?? []) as $giay)
        @if(($dem < 5) && ($giay->ten_thuong_hieu=='Converse' )) <div class="col-md-3">
            <div class="card" style="margin-bottom: 25px">
                    <a href="/cua-hang/san-pham={{$giay->id_giay}}">
                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                        <center><img src="/storage/images/{{$giay->hinh_anh_1}}" class="img-fluid" style="height:306px; width:306px"/></center>
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </div>
                    <div class="card-body">
                        <center>
                                <h4 class="card-title">{{$giay->ten_giay}}</h4>
                            <p class="card-text text-success">
                                @if($km = 0)@endif
                                @foreach(($khuyenmais ?? []) as $khuyenmai)
                                    @if($khuyenmai->ten_khuyen_mai == $giay->ten_khuyen_mai)
                                @if($km = sprintf('%d', $giay->don_gia * 0.01 *
                                        $khuyenmai->gia_tri_khuyen_mai))@endif
                                @endif
                                @endforeach

                                <b>{{number_format($giay->don_gia - $km, 0, ',', ',')}} VNĐ</b>
                                <del class="card-text text-danger">{{number_format($giay->don_gia, 0, ',', ',')}}
                                    VNĐ </del>
                            </p>
                        </center>
                    </div>
                </a>
            </div>
    </div>
    @if($dem = $dem + 1)@endif
    @endif
    @endforeach
</div>
</div>
</div>
<!-- Tabs content -->




<div class="card mb-3 shadow-1">
    <div class="card-body" style="margin-top:40px">
        <center>
            <h3 class="card-title" style="text-transform: uppercase;">GIÀY NỔI BẬT</h3>
        </center>
    </div>
    <br>
</div>

<!-- Custom carousel arrow color: brown (#A0522D) -->
<style>
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-size: 1.25rem 1.25rem;
        background-position: center;
        background-repeat: no-repeat;
    }
    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23A0522D'><path d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/></svg>") !important;
    }
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23A0522D'><path d='M4.646 1.646a.5.5 0 0 0 0 .708L10.293 8 4.646 13.646a.5.5 0 0 0 .708.708l6-6a.5.5 0 0 0 0-.708l-6-6a.5.5 0 0 0-.708 0z'/></svg>") !important;
    }
    /* Ensure controls are on top and visible regardless of card shadows */
    .carousel-control-prev,
    .carousel-control-next {
        z-index: 9999 !important;
        opacity: 1 !important;
        width: 5% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1 !important;
    }
    /* Slightly larger icon for better visibility */
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        width: 28px !important;
        height: 28px !important;
        background-size: 28px 28px !important;
    }
</style>

<div id="carouselExampleControls" class="carousel slide" data-mdb-ride="carousel">
    <div class="carousel-inner">
        @php
            // normalize to array of items
            $featuredItems = [];
            if (is_object($giaynoibats) && method_exists($giaynoibats, 'items')) {
                $featuredItems = $giaynoibats->items();
            } elseif (is_array($giaynoibats)) {
                $featuredItems = $giaynoibats;
            } elseif (is_object($giaynoibats)) {
                $featuredItems = [$giaynoibats];
            }
            $featuredChunks = array_chunk((array)$featuredItems, 4);
        @endphp

        @foreach($featuredChunks as $idx => $chunk)
            <div class="carousel-item @if($idx == 0) active @endif">
                <div class="row">
                    @foreach($chunk as $giaynoibat)
                        <div class="col-md-3">
                            <div class="card" style="margin-bottom: 25px">
                                <a href="/cua-hang/san-pham={{ data_get($giaynoibat, 'id_giay') }}">
                                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light" >
                                        <center><img src="/storage/images/{{ data_get($giaynoibat, 'hinh_anh_1') }}" class="img-fluid" style="height:306px; width:306px"/></center>
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </div>
                                    <div class="card-body">
                                        <center>
                                            <h4 class="card-title">{{ data_get($giaynoibat, 'ten_giay') }}</h4>
                                            <p class="card-text text-success">
                                                @php $km = 0; @endphp
                                                @foreach($khuyenmais ?? [] as $khuyenmai)
                                                    @if(data_get($khuyenmai,'ten_khuyen_mai') == data_get($giaynoibat,'ten_khuyen_mai'))
                                                        @php $km = intval(sprintf('%d', data_get($giaynoibat,'don_gia',0) * 0.01 * data_get($khuyenmai,'gia_tri_khuyen_mai',0))); @endphp
                                                    @endif
                                                @endforeach

                                                <b>{{ number_format(data_get($giaynoibat,'don_gia',0) - $km, 0, ',', ',') }} VNĐ</b>
                                                <del class="card-text text-danger">{{ number_format(data_get($giaynoibat,'don_gia',0), 0, ',', ',') }} VNĐ</del>
                                            </p>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
</div>
<button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleControls" data-mdb-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleControls" data-mdb-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>

</div>



<div class="card mb-3 shadow-1">
    <div class="card-body" style="margin-top:40px">
        <center>
            <h3 class="card-title" style="text-transform: uppercase;">GIÀY MỚI NHẤT</h3>
        </center>
    </div>
    <br>
</div>

<div id="carouselExampleControls2" class="carousel slide" data-mdb-ride="carousel">
    <div class="carousel-inner">
        @php
            $newestItems = [];
            if (is_object($giaymoinhats) && method_exists($giaymoinhats, 'items')) {
                $newestItems = $giaymoinhats->items();
            } elseif (is_array($giaymoinhats)) {
                $newestItems = $giaymoinhats;
            } elseif (is_object($giaymoinhats)) {
                $newestItems = [$giaymoinhats];
            }
            $newestChunks = array_chunk((array)$newestItems, 4);
        @endphp

        @foreach($newestChunks as $idx => $chunk)
            <div class="carousel-item @if($idx == 0) active @endif">
                <div class="row">
                    @foreach($chunk as $giaymoinhat)
                        <div class="col-md-3">
                            <div class="card" style="margin-bottom: 25px">
                                <a href="/cua-hang/san-pham={{ data_get($giaymoinhat,'id_giay') }}">
                                    <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                                        <center><img src="/storage/images/{{ data_get($giaymoinhat,'hinh_anh_1') }}" class="img-fluid" style="height:306px; width:306px"/></center>
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </div>
                                    <div class="card-body">
                                        <center>
                                            <h4 class="card-title">{{ data_get($giaymoinhat,'ten_giay') }}</h4>
                                            <p class="card-text text-success">
                                                @php $km = 0; @endphp
                                                @foreach($khuyenmais ?? [] as $khuyenmai)
                                                    @if(data_get($khuyenmai,'ten_khuyen_mai') == data_get($giaymoinhat,'ten_khuyen_mai'))
                                                        @php $km = intval(sprintf('%d', data_get($giaymoinhat,'don_gia',0) * 0.01 * data_get($khuyenmai,'gia_tri_khuyen_mai',0))); @endphp
                                                    @endif
                                                @endforeach

                                                <b>{{ number_format(data_get($giaymoinhat,'don_gia',0) - $km, 0, ',', ',') }} VNĐ</b>
                                                <del class="card-text text-danger">{{ number_format(data_get($giaymoinhat,'don_gia',0), 0, ',', ',') }} VNĐ</del>
                                            </p>
                                        </center>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
<button class="carousel-control-prev" type="button" data-mdb-target="#carouselExampleControls2" data-mdb-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-mdb-target="#carouselExampleControls2" data-mdb-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>

</div>



<br>
<br>


</div>