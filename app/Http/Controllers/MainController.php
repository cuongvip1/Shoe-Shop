<?php

namespace App\Http\Controllers;

use App\Models\DanhGia;
use App\Models\User;
use App\Models\DonHang;
use App\Models\Giay;
use App\Services\ApiClient;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
  
    public function index(){

        if(session()->get(key:'gio_hang') == null){
            $gio_hang = array();
            session()->put('gio_hang', $gio_hang);
        }
        
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        $giays = $api->get('/api/giay');
        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        // Normalize lists so views can safely iterate and use paginator when present
        $users = $this->normalizeData($users);
        $phanquyens = $this->normalizeData($phanquyens);
        $khuyenmais = $this->normalizeData($khuyenmais);

        // Normalize paginated responses (API returns pagination with `data` property or key)
        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);
        $users = $this->normalizeData($users);
        $khuyenmais = $this->normalizeData($khuyenmais);

        // $soluongdanhgia = array();
        // $soluongdanhgia['danh_gia'] = DB::table('danh_gia')->avg('danh_gia');

        $giaymoinhats = $api->get('/api/giay/moi-nhat');
        $giaynoibats = $api->get('/api/giay/noi-bat');

        $giaymoinhats = $this->normalizeData($giaymoinhats);
        $giaynoibats = $this->normalizeData($giaynoibats);

        // Fallback: if API returned no data, load from local DB so the site can still show products.
        try {
            $emptyFeatured = (empty($giaynoibats) || (is_array($giaynoibats) && count($giaynoibats) === 0));
            $emptyNewest = (empty($giaymoinhats) || (is_array($giaymoinhats) && count($giaymoinhats) === 0));
        } catch (\Exception $e) {
            $emptyFeatured = true;
            $emptyNewest = true;
        }

        if ($emptyFeatured) {
            $localFeatured = Giay::orderBy('so_luong_mua', 'desc')->take(8)->get();
            // convert Eloquent collection to array of objects
            $giaynoibats = $localFeatured->map(function($it){ return $it; })->all();
        }

        if ($emptyNewest) {
            $localNewest = Giay::orderBy('updated_at', 'desc')->take(8)->get();
            $giaymoinhats = $localNewest->map(function($it){ return $it; })->all();
        }

        // Build per-brand lists: prefer fetching brand-specific lists from the API
        // This avoids missing items when the global /api/giay endpoint is paginated.
        $brandsToShow = ['Adidas', 'Nike', 'Converse'];
        $brandLists = [];
        foreach ($brandsToShow as $b) {
            // Try API helper for brand — request a reasonably large per_page so we get all items
            try {
                $resp = $api->get('/api/giay/thuong-hieu/' . urlencode($b), ['per_page' => 200]);
                $resp = $this->normalizeData($resp);
                // normalizeData may return LengthAwarePaginator or array
                if (is_object($resp) && method_exists($resp, 'items')) {
                    $items = $resp->items();
                } elseif (is_array($resp)) {
                    $items = $resp;
                } elseif (is_object($resp)) {
                    $items = [$resp];
                } else {
                    $items = [];
                }
            } catch (\Exception $e) {
                $items = [];
            }

            // Fallback: filter from $giays (current page) if brand API gave nothing
            if (empty($items)) {
                $allGiays = is_object($giays) ? (method_exists($giays, 'items') ? $giays->items() : (array)$giays) : (array)$giays;
                $items = array_values(array_filter((array)$allGiays, function($g) use ($b) {
                    return strval(trim(strtolower((string)data_get($g,'ten_thuong_hieu','')))) === strval(trim(strtolower($b)));
                }));
            }

            // sort by don_gia desc and take up to 4
            usort($items, function($a, $b2){
                $pa = floatval(data_get($a, 'don_gia', 0));
                $pb = floatval(data_get($b2, 'don_gia', 0));
                return $pb <=> $pa;
            });
            $brandLists[$b] = array_slice($items, 0, 4);
        }

        return view('index')->with('route', 'trang-chu')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('giaymoinhats', $giaymoinhats)
        ->with('giaynoibats', $giaynoibats)
        ->with('giays_adidas', $brandLists['Adidas'])
        ->with('giays_nike', $brandLists['Nike'])
        ->with('giays_converse', $brandLists['Converse'])
        // ->with('soluongdanhgia', $soluongdanhgia)
        ;
    }

    public function cuahang(){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        $giays = $api->get('/api/giay');
        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);

        return view('index')->with('route', 'cua-hang')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('timloaigiay', '')->with('timthuonghieu', '')
        ;
    }

    public function thanhtoan(){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        $giays = $api->get('/api/giay');
        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);

        return view('index')->with('route', 'thanh-toan')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ;
    }

    /**
     * Show order history for logged-in user.
     */
    public function orderHistory()
    {
        if (!session()->has('DangNhap')) {
            return redirect('/auth/login');
        }

        $api = new ApiClient();
        $data = $api->get('/api/users/' . session('DangNhap'));

        // fetch all orders from API and normalize
        $donhangs = $api->get('/api/don-hang');
        $donhangs = $this->normalizeData($donhangs);

        // prepare items array from paginator or array
        $items = [];
        if (is_object($donhangs) && method_exists($donhangs, 'items')) {
            $items = $donhangs->items();
        } elseif (is_array($donhangs)) {
            $items = $donhangs;
        } elseif (is_object($donhangs)) {
            $items = [$donhangs];
        }

        // filter orders by phone or recipient name matching current user
        $userPhone = data_get($data, 'sdt');
        $userName = data_get($data, 'ten_nguoi_dung');

        $myOrders = array_values(array_filter($items, function($d) use ($userPhone, $userName) {
            $dPhone = data_get($d, 'sdt');
            $dName = data_get($d, 'ten_nguoi_nhan');
            return ($dPhone && $userPhone && $dPhone == $userPhone) || ($dName && $userName && $dName == $userName);
        }));

        // pass data to view (use index with custom route key)
        return view('index')
            ->with('route', 'tai-khoan-lich-su')
            ->with('data', $data)
            ->with('myOrders', $myOrders);
    }

    /**
     * Show a single order detail for the logged-in user.
     */
    public function orderShow($id)
    {
        if (!session()->has('DangNhap')) {
            return redirect('/auth/login');
        }

        $api = new ApiClient();
        $data = $api->get('/api/users/' . session('DangNhap'));

        $donhangs = $api->get('/api/don-hang');
        $donhangs = $this->normalizeData($donhangs);

        $items = [];
        if (is_object($donhangs) && method_exists($donhangs, 'items')) {
            $items = $donhangs->items();
        } elseif (is_array($donhangs)) {
            $items = $donhangs;
        } elseif (is_object($donhangs)) {
            $items = [$donhangs];
        }

        $order = null;
        foreach ($items as $d) {
            if (intval(data_get($d, 'id_don_hang')) === intval($id)) {
                $order = $d;
                break;
            }
        }

        if (! $order) {
            return redirect('/tai-khoan/lich-su')->with('thatbai', 'Không tìm thấy đơn hàng.');
        }

        // ensure the order belongs to the current user (by phone or name)
        $userPhone = data_get($data, 'sdt');
        $userName = data_get($data, 'ten_nguoi_dung');
        $dPhone = data_get($order, 'sdt');
        $dName = data_get($order, 'ten_nguoi_nhan');
        if (!(($dPhone && $userPhone && $dPhone == $userPhone) || ($dName && $userName && $dName == $userName))) {
            return redirect('/tai-khoan/lich-su')->with('thatbai', 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('index')
            ->with('route', 'tai-khoan-lich-su-xem')
            ->with('data', $data)
            ->with('order', $order);
    }


        public function cancelOrder(Request $request, $id)
        {
            $api = new ApiClient();
            // ensure user owns this order
            $order = DonHang::find($id);
            if (!$order) {
                session()->flash('thatbai', 'Đơn hàng không tồn tại');
                return Redirect('/tai-khoan/lich-su');
            }

            // For now basic ownership check using phone or name stored in order vs user session
            $user = session()->has('DangNhap') ? $api->get('/api/users/'.session('DangNhap')) : null;
            $userPhone = data_get($user, 'sdt');
            if ($order->trang_thai !== 'cho') {
                session()->flash('thatbai', 'Chỉ có đơn hàng đang chờ mới có thể hủy.');
                return Redirect('/tai-khoan/lich-su');
            }

            // check ownership
            if ($userPhone && $order->sdt !== $userPhone) {
                session()->flash('thatbai', 'Bạn không có quyền hủy đơn hàng này.');
                return Redirect('/tai-khoan/lich-su');
            }

            // unserialize and restock
            $items = is_string($order->hoa_don) ? @unserialize($order->hoa_don) : $order->hoa_don;
            foreach ($items as $it) {
                $giay = Giay::find(data_get($it, 'id') ?? null);
                $qty = intval(data_get($it, 'so_luong') ?? 0);
                if ($giay && $qty > 0) {
                    $giay->so_luong = intval($giay->so_luong) + $qty;
                    if (isset($giay->so_luong_mua)) {
                        $giay->so_luong_mua = max(0, intval($giay->so_luong_mua) - $qty);
                    }
                    $giay->save();
                }
            }

            $order->trang_thai = 'da_huy';
            $order->save();

            session()->flash('thanhcong', 'Đã hủy đơn và trả lại sản phẩm vào tồn kho.');
            return Redirect('/tai-khoan/lich-su');
        }
    public function timkiem(Request $request){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        // basic search via API (pass query param `tim_kiem` to /api/giay)
        $giays = $api->get('/api/giay', ['tim_kiem' => $request->tim_kiem]);

        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);

        return view('index')->with('route', 'cua-hang')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('timloaigiay', '')->with('timthuonghieu', '')
        ;
    }

    public function sanpham($slug){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        $giay = $api->get('/api/giay/' . $slug);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);
        // $ok = Giay::where('ten_thuong_hieu', $giay['ten_thuong_hieu'])->get();

        // Get similar products via API helpers
        // similar products; API may return pagination
        $giay_ten_thuong_hieu = null;
        if (is_object($giay) && property_exists($giay, 'ten_thuong_hieu')) {
            $giay_ten_thuong_hieu = $giay->ten_thuong_hieu;
        } elseif (is_array($giay) && array_key_exists('ten_thuong_hieu', $giay)) {
            $giay_ten_thuong_hieu = $giay['ten_thuong_hieu'];
        }

        $giaytuongtus = $api->get('/api/giay/thuong-hieu/' . urlencode($giay_ten_thuong_hieu ?? ''));
        $giaytuongtus = $this->normalizeData($giaytuongtus);

        $giay_id = null;
        if (is_object($giay) && property_exists($giay, 'id_giay')) {
            $giay_id = $giay->id_giay;
        } elseif (is_array($giay) && array_key_exists('id_giay', $giay)) {
            $giay_id = $giay['id_giay'];
        } elseif (is_object($giay) && property_exists($giay, 'id')) {
            $giay_id = $giay->id;
        } elseif (is_array($giay) && array_key_exists('id', $giay)) {
            $giay_id = $giay['id'];
        }

        $danhgias = $api->get('/api/danh-gia/' . ($giay_id ?? ''));
        $danhgias = $this->normalizeData($danhgias);
        
        $danh_gias = session()->get(key:'danh_gias');
        if(!$danh_gias){
            $danh_gias = array();
        }

        $soluongdanhgia = array();
        $soluongdanhgia['count_danh_gia'] = count($danhgias ?? []);
        $avg = 0;
        if (count($danhgias ?? []) > 0) {
            $sum = 0;
            foreach ($danhgias as $dg) { $sum += floatval(data_get($dg, 'danh_gia', 0)); }
            $avg = $sum / count($danhgias);
        }
        $soluongdanhgia['danh_gia'] = $avg;

        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        // dd($danh_gias);
        
        // DB::table('mon_an')->where('ID_nha_hang', $request->ID_nha_hang);

        // return $giaytuongtus;

        $gio_hangs = session()->get(key:'gio_hang');
        if(!$gio_hangs){$gio_hangs = array();}


        return view('index')->with('route', 'san-pham')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giay', $giay)
        ->with('giaytuongtus', $giaytuongtus)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('danh_gias', $danh_gias)
        ->with('danhgias', $danhgias)
        ->with('soluongdanhgia', $soluongdanhgia)
        ->with('gio_hangs', $gio_hangs)
        ;
    }

    //

    public function timloaigiay($loaigiay){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        $giays = $api->get('/api/giay', ['ten_loai_giay' => $loaigiay]);

        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);

        return view('index')->with('route', 'cua-hang')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('timloaigiay', $loaigiay)
        ->with('timthuonghieu', '')

        ;
    }

    public function timthuonghieu($thuonghieu){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');
        $giays = $api->get('/api/giay', ['ten_thuong_hieu' => $thuonghieu]);
        $users = $api->get('/api/users');
        $phanquyens = $api->get('/api/phan-quyen');
        $khuyenmais = $api->get('/api/khuyen-mai');

        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);

        return view('index')->with('route', 'cua-hang')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('timthuonghieu', $thuonghieu)
        ->with('timloaigiay', '')
        ;
    }

    public function timgia($gia1, $gia2){
        $api = new ApiClient();
        $data = null;
        if (session()->has('DangNhap')) {
            $data = $api->get('/api/users/' . session('DangNhap'));
        }
        $thuonghieus = $api->get('/api/thuong-hieu');
        $loaigiays = $api->get('/api/loai-giay');

        $giays = $api->get('/api/giay', ['gia_min' => $gia1, 'gia_max' => $gia2]);

        $users = $api->get('/api/users');

        // Normalize API responses so views receive objects or paginators
        $giays = $this->normalizeData($giays);
        $thuonghieus = $this->normalizeData($thuonghieus);
        $loaigiays = $this->normalizeData($loaigiays);
        $phanquyens = $this->normalizeData($api->get('/api/phan-quyen'));
        $khuyenmais = $this->normalizeData($api->get('/api/khuyen-mai'));

        // If API didn't filter by price, apply server-side filter here using don_gia
        $min = intval($gia1);
        $max = intval($gia2);

        // Extract items from paginator or array
        $items = [];
        $wasPaginated = false;
        if (is_object($giays) && method_exists($giays, 'items')) {
            $wasPaginated = true;
            $items = $giays->items();
        } elseif (is_array($giays)) {
            $items = $giays;
        } elseif (is_object($giays)) {
            $items = [$giays];
        }

        // Filter by don_gia
        $filtered = [];
        foreach ($items as $g) {
            $price = intval(data_get($g, 'don_gia', 0));
            if ($price >= $min && $price <= $max) {
                $filtered[] = $g;
            }
        }

        // Re-wrap into paginator if original was paginated
        if ($wasPaginated) {
            $perPage = method_exists($giays, 'perPage') ? $giays->perPage() : count($filtered);
            $currentPage = 1;
            $total = count($filtered);
            $path = Paginator::resolveCurrentPath();
            $giays = new LengthAwarePaginator($filtered, $total, $perPage, $currentPage, ['path' => $path]);
        } else {
            $giays = $filtered;
        }

        return view('index')->with('route', 'cua-hang')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('timthuonghieu', '')
        ->with('timloaigiay', '')
        ;
    }

    /**
     * Normalize API responses that may be arrays or stdClass objects with a `data` wrapper.
     * Returns the inner items when `data` exists, otherwise returns the original value.
     */
    private function normalizeData($value)
    {
        if (is_null($value)) return null;

        // Detect paginated API response (object or array with pagination metadata)
        $isPaginated = false;
        $meta = null;
        if (is_object($value) && property_exists($value, 'data')) {
            $itemsRaw = $value->data;
            // check for pagination metadata
            if (property_exists($value, 'current_page') || property_exists($value, 'total') || property_exists($value, 'per_page')) {
                $isPaginated = true;
                $meta = (array) $value;
            }
        } elseif (is_array($value) && array_key_exists('data', $value)) {
            $itemsRaw = $value['data'];
            if (array_key_exists('current_page', $value) || array_key_exists('total', $value) || array_key_exists('per_page', $value)) {
                $isPaginated = true;
                $meta = $value;
            }
        } else {
            $itemsRaw = $value;
        }

        // Normalize each item to an object when it's an array
        $normalized = [];
        if (is_array($itemsRaw)) {
            foreach ($itemsRaw as $it) {
                if (is_array($it)) {
                    $normalized[] = (object) $it;
                } else {
                    $normalized[] = $it;
                }
            }
        } elseif (is_object($itemsRaw)) {
            $normalized = [$itemsRaw];
        } else {
            // single scalar value
            return $itemsRaw;
        }

        // If response was paginated, wrap items in a LengthAwarePaginator so Blade links() works
        if ($isPaginated && is_array($meta)) {
            $perPage = isset($meta['per_page']) ? intval($meta['per_page']) : (isset($meta['perPage']) ? intval($meta['perPage']) : count($normalized));
            $currentPage = isset($meta['current_page']) ? intval($meta['current_page']) : (isset($meta['currentPage']) ? intval($meta['currentPage']) : 1);
            $total = isset($meta['total']) ? intval($meta['total']) : count($normalized);

            $path = Paginator::resolveCurrentPath();
            $paginator = new LengthAwarePaginator($normalized, $total, $perPage, $currentPage, ['path' => $path]);
            return $paginator;
        }

        return $normalized;
    }

    public function aboutUs(){
        return view('index')->with('route', 'gioi-thieu');
    }

    public function login(){
        return view('auth.login');
    }

    public function register(){
        return view('auth.register');
    }

    // Register;
    public function storeReg(Request $request){
        // Validate on the web side before calling API (including password confirmation)
        $request->validate([
            'ten_nguoi_dung' => 'required',
            'email' => 'required|email',
            'sdt' => 'required',
            'Ten_dang_nhap' => 'required',
            'password' => 'required|min:5|confirmed',
        ],[
            'email.required' => '* Email là bắt buộc.',
            'email.email' => '* Email không hợp lệ.',
            'password.min' => '* Mật khẩu phải chứa ít nhất 5 kí tự.',
            'password.confirmed' => '* Mật khẩu xác nhận không khớp.',
        ]);

        $api = new ApiClient();
        $resp = $api->post('/api/users', [
            'ten_nguoi_dung' => $request->input('ten_nguoi_dung'),
            'email' => $request->input('email'),
            'sdt' => $request->input('sdt'),
            'Ten_dang_nhap' => $request->input('Ten_dang_nhap'),
            'password' => $request->input('password'),
            'id_phan_quyen' => '2',
        ]);

        // If API returned validation errors (422), it will come back as an object with 'errors'
        if (is_object($resp) && property_exists($resp, 'errors')) {
            $errors = (array) $resp->errors;
            // Extract first error messages
            $messages = [];
            foreach ($errors as $field => $msgs) {
                if (is_array($msgs)) {
                    foreach ($msgs as $m) $messages[] = $m;
                } elseif (is_object($msgs)) {
                    foreach ((array)$msgs as $m) $messages[] = $m;
                }
            }
            $msg = implode(' ', $messages);
            return back()->with('thatbai', $msg)->withInput();
        }

        return redirect()->route('auth.login')->with('thanhcong', 'Tạo tài khoản thành công.');
    }

    // Login Check;
    public function loginCheck(Request $request){
        $request->validate([
            'ten_dang_nhap' => 'required',
            'password' => 'required | min:5',
        ]);

        $api = new ApiClient();

        // call API login
        try {
            $resp = $api->post('/api/auth/login', [
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'password' => $request->password,
            ]);
        } catch (\Exception $e) {
            return back()->with('thatbai','* Tên đăng nhập hoặc Email không tồn tại!');
        }

        $user = null;
        if (is_array($resp) && array_key_exists('user', $resp)) {
            $user = $resp['user'];
        } elseif (is_object($resp) && property_exists($resp, 'user')) {
            $user = $resp->user;
        }

        if ($user) {
            $userId = is_array($user) ? ($user['id'] ?? null) : ($user->id ?? null);
            if ($userId) {
                $request->session()->put('DangNhap', $userId);

                $data = $api->get('/api/users/' . $userId);
                $thuonghieus = $api->get('/api/thuong-hieu');
                $loaigiays = $api->get('/api/loai-giay');
                $giays = $api->get('/api/giay');
                $users = $api->get('/api/users');
                $khuyenmais = $api->get('/api/khuyen-mai');
                $donhangs = $api->get('/api/don-hang');
                $giaymoinhats = $api->get('/api/giay/moi-nhat');
                $giaynoibats = $api->get('/api/giay/noi-bat');

                // Normalize lists so views receive consistent arrays of objects
                $thuonghieus = $this->normalizeData($thuonghieus);
                $loaigiays = $this->normalizeData($loaigiays);
                $giays = $this->normalizeData($giays);
                $users = $this->normalizeData($users);
                $khuyenmais = $this->normalizeData($khuyenmais);
                $donhangs = $this->normalizeData($donhangs);
                $giaymoinhats = $this->normalizeData($giaymoinhats);
                $giaynoibats = $this->normalizeData($giaynoibats);

                $id_phan_quyen = is_array($user) ? ($user['id_phan_quyen'] ?? '') : ($user->id_phan_quyen ?? '');

                if ($id_phan_quyen == '1') {
                    session()->put('check', '1');
                    return view('admin.trangchu.trangchu')
                        ->with('data', $data)
                        ->with('thuonghieus', $thuonghieus)
                        ->with('loaigiays', $loaigiays)
                        ->with('giays', $giays)
                        ->with('users', $users)
                        ->with('khuyenmais', $khuyenmais)
                        ->with('donhangs', $donhangs)
                        ->with('giaymoinhats', $giaymoinhats)
                        ->with('giaynoibats', $giaynoibats);
                } else {
                    session()->put('check', '2');
                    return view('index')->with('data', $data)->with('route', 'trang-chu')
                        ->with('thuonghieus', $thuonghieus)
                        ->with('loaigiays', $loaigiays)
                        ->with('giays', $giays)
                        ->with('users', $users)
                        ->with('khuyenmais', $khuyenmais)
                        ->with('donhangs', $donhangs)
                        ->with('giaymoinhats', $giaymoinhats)
                        ->with('giaynoibats', $giaynoibats);
                }
            }
        }

        // $userinfoUser = User::where('Ten_dang_nhap', $request->ten_dang_nhap)->first();
        // if (!$userinfoUser){
        //     return back()->with('thatbai','* Tên đăng nhập hoặc Email không tồn tại!');
        // } else {
        //     if (Hash::check($request->password, $userinfoUser->password)){
        //         $request->session()->put('DangNhap', $userinfoUser->id);

        //         return view('admin.trangchu.trangchu')->with('data', User::where('id',session('DangNhap'))->first());
        //     } else {
        //         return back()->with('thatbai','* Mật khẩu nhập không đúng, vui lòng nhập lại');
        //     }
        // }

    }

    function dangXuat(){
        if (session()->has('DangNhap')){
            session()->pull('DangNhap');
            session()->put('check', '0');
            return redirect('/');
            // return session()->get(key:'check');
        }
        session()->put('check', '0');
        return redirect('/');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validate duplicate username and email
        $username = $request->input('Ten_dang_nhap');
        $email = $request->input('email');
        if (User::where('Ten_dang_nhap', $username)->exists()) {
            return back()->with('thatbai', '* Tên đăng nhập đã được sử dụng!')->withInput();
        }
        if (User::where('email', $email)->exists()) {
            return back()->with('thatbai', '* Email đã được sử dụng!')->withInput();
        }

        User::create([
            'ten_nguoi_dung' => $request->input('ten_nguoi_dung'),
            'email' => $request->input('email'),
            'sdt' => $request->input('sdt'),
            'Ten_dang_nhap' => $username,
            'password' => Hash::make($request->input('password')),
            'id_phan_quyen' => '2',
        ]);

        return Redirect('/admin/taikhoan')->with('thanhcong', 'Tạo tài khoản thành công.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /// hiển thị
        $data = User::all();
        return View('admin.taikhoan.taikhoan', ['taikhoans'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // sửa
        $data = User::find($id);
        return View('admin.taikhoan.sua', ['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // update
        $data = User::find($request->id);
        $data['ten_nguoi_dung'] = $request->ten_nguoi_dung;
        $data['email'] = $request->email;
        $data['sdt'] = $request->sdt;
        $data['Ten_dang_nhap'] = $request->Ten_dang_nhap;
        // $data['password'] = $request->password;
        $data['password'] = Hash::make($request->password);

        $data->save();
        return Redirect('/admin/taikhoan');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // xóa
        $data = User::find($id);
        $data->delete();
        return Redirect('/admin/taikhoan');
    }


    
}
