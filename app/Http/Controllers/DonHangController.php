<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

use App\Models\User;
use App\Models\Giay;
use App\Models\LoaiGiay;
use App\Models\ThuongHieu;
use App\Models\KhuyenMai;
use App\Models\GioHang;
use App\Models\PhanQuyen;
use App\Models\DonHang;

class DonHangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->get(key:'check') == 0){
            return view('auth.login');
        } else{
            $data = User::where('id',session('DangNhap'))->first();
            $thuonghieus = ThuongHieu::all();
            $loaigiays = LoaiGiay::all();
            $giays = Giay::all();
            $users = User::all();
            $phanquyens = PhanQuyen::all();
            $khuyenmais = KhuyenMai::all();

            $giohangs = session()->get(key:'gio_hang');
            if(!$giohangs){
                $giohangs = array();
            }

            return view('index')->with('route', 'thanh-toan')
            ->with('data', $data)
            ->with('thuonghieus', $thuonghieus)
            ->with('loaigiays', $loaigiays)
            ->with('giays', $giays)
            ->with('users', $users)
            ->with('phanquyens', $phanquyens)
            ->with('khuyenmais', $khuyenmais)
            ->with('giohangs', $giohangs)
            ;
        }

    }

    public function thanhtoan(Request $request)
    {

        $giohangs = session()->get(key:'gio_hang');
        if(!$giohangs){
            $giohangs = array();
        }

        $thanhtoans = array();

        $check_gio_hangs = $request->input('check-gio-hang');
        foreach($check_gio_hangs as $check_gio_hang){
            foreach($giohangs as $id=>$giohang){
                if($check_gio_hang == $id){
                    $thanhtoans[$id] = $giohang;
                }
            }
        }

        if(session()->get(key:'check') == 0){
            return view('auth.login');
        } else{
            $data = User::where('id',session('DangNhap'))->first();
            $thuonghieus = ThuongHieu::all();
            $loaigiays = LoaiGiay::all();
            $giays = Giay::all();
            $users = User::all();
            $phanquyens = PhanQuyen::all();
            $khuyenmais = KhuyenMai::all();

            return view('index')->with('route', 'thanh-toan')
            ->with('data', $data)
            ->with('thuonghieus', $thuonghieus)
            ->with('loaigiays', $loaigiays)
            ->with('giays', $giays)
            ->with('users', $users)
            ->with('phanquyens', $phanquyens)
            ->with('khuyenmais', $khuyenmais)
            ->with('giohangs', $thanhtoans)
            ;
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 'ten_nguoi_nhan', 'sdt', 'dia_chi_nhan', 'ghi_chu', 'ten_giay', 'don_gia', 'so_luong', 'thanh_tien'
        // serialize() - unserialize()
        $giohangs = session()->get(key:'gio_hang');

        // Validate stock availability before creating order
        $oks = unserialize($request->input('thanh_toans'));
        foreach ($oks as $id=>$ok) {
            $giay = Giay::find($id);
            $requested = intval($ok['so_luong'] ?? ($ok->so_luong ?? 0));
            $available = $giay ? intval($giay->so_luong) : 0;
            if ($requested <= 0 || $available <= 0 || $requested > $available) {
                // Not enough stock for this item
                session()->flash('thatbai', "Sản phẩm '".($giay->ten_giay ?? $id)."' không đủ số lượng (còn: $available). Vui lòng điều chỉnh giỏ hàng.");
                return Redirect('/gio-hang');
            }
        }

        $donhang = DonHang::create([
            'ten_nguoi_nhan' => $request->input('ten_nguoi_nhan'),
            'sdt' => $request->input('sdt'),
            'dia_chi_nhan' => $request->input('dia_chi_nhan'),
            'ghi_chu' => $request->input('ghi_chu'),
            'tong_tien' => $request->input('tong_tien'),
            'hoa_don' => $request->input('thanh_toans'),
            'hinh_thuc_thanh_toan' => $request->input('hinh_thuc_thanh_toan'),
            
        ]);

        // $danh_gias = $request->input('thanh_toans');
        $danh_gias = session()->get(key:'danh_gias');
        if(!$danh_gias){
            $danh_gias = array();
        }
        // Reduce stock based on purchased quantities and record products into danh_gias session
        foreach($oks as $id=>$ok){
            $danh_gias[$id] = $ok;
            $giay = Giay::find($id);
            $purchasedQty = intval($ok['so_luong'] ?? ($ok->so_luong ?? 0));
            if ($giay) {
                // decrement available stock
                $giay->so_luong = max(0, intval($giay->so_luong) - $purchasedQty);
                // increment counter of total sold if field exists
                if (isset($giay->so_luong_mua)) {
                    $giay->so_luong_mua = intval($giay->so_luong_mua) + $purchasedQty;
                }
                $giay->save();
            }

        }
        session()->put('danh_gias', $danh_gias);

        foreach($danh_gias as $iddg=>$danh_gia){
            // dd($danh_gias);
            foreach($giohangs as $idgh=>$giohang){
                if($idgh == $iddg){
                    unset($giohangs[$idgh]);
                    // $giohangs[$idgh] = '';
                }
            }

        

        }

       
        // dd($giohangs);

        session()->put('gio_hang', $giohangs);

        return Redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::where('id',session('DangNhap'))->first();
        $thuonghieus = ThuongHieu::all();
        $loaigiays = LoaiGiay::all();
        $giays = Giay::all();
        $users = User::all();
        $phanquyens = PhanQuyen::all();
        $khuyenmais = KhuyenMai::all();
        $donhang = DonHang::find($id);

        $giohangs = session()->get(key:'gio_hang');

        return view('admin.donhang.xem')
        ->with('data', $data)
        ->with('thuonghieus', $thuonghieus)
        ->with('loaigiays', $loaigiays)
        ->with('giays', $giays)
        ->with('users', $users)
        ->with('phanquyens', $phanquyens)
        ->with('khuyenmais', $khuyenmais)
        ->with('donhang', $donhang)
        ;
    }

    /**
     * Admin listing: pending orders for review and processed orders (filtered).
     */
    public function indexAdmin(Request $request)
    {
        // pending orders for review
        $pendingOrders = DonHang::where('trang_thai', 'cho')->orderByDesc('created_at')->get();

        // processed orders: rejected, cancelled, confirmed
        $processedQuery = DonHang::whereIn('trang_thai', ['tu_choi', 'da_huy', 'da_xac_nhan'])->orderByDesc('created_at');

        $filter = $request->query('status'); // optional: 'tu_choi'|'da_huy'|'da_xac_nhan' or null for all
        if ($filter && in_array($filter, ['tu_choi', 'da_huy', 'da_xac_nhan'])) {
            $processedQuery->where('trang_thai', $filter);
        }

        $processedOrders = $processedQuery->get();

        $data = User::where('id',session('DangNhap'))->first();
        $thuonghieus = ThuongHieu::all();
        $loaigiays = LoaiGiay::all();
        $giays = Giay::all();
        $users = User::all();
        $phanquyens = PhanQuyen::all();
        $khuyenmais = KhuyenMai::all();

        return view('admin.donhang.donhang')
            ->with('data', $data)
            ->with('thuonghieus', $thuonghieus)
            ->with('loaigiays', $loaigiays)
            ->with('giays', $giays)
            ->with('users', $users)
            ->with('phanquyens', $phanquyens)
            ->with('khuyenmais', $khuyenmais)
            ->with('pendingOrders', $pendingOrders)
            ->with('processedOrders', $processedOrders)
            ->with('filter', $filter ?? '')
        ;
    }

    /**
     * Show processed orders list (rejected, cancelled, confirmed) as a dedicated admin page.
     */
    public function processed(Request $request)
    {
        if(session()->get(key:'check') == 0){
            return view('auth.login');
        }

        $data = User::where('id',session('DangNhap'))->first();
        $thuonghieus = ThuongHieu::all();
        $loaigiays = LoaiGiay::all();
        $giays = Giay::all();
        $users = User::all();
        $phanquyens = PhanQuyen::all();
        $khuyenmais = KhuyenMai::all();

        $filter = $request->query('status');
        $query = DonHang::whereIn('trang_thai', ['tu_choi','da_huy','da_xac_nhan'])->orderByDesc('created_at');
        if ($filter && in_array($filter, ['tu_choi','da_huy','da_xac_nhan'])) {
            $query->where('trang_thai', $filter);
        }

        $processedOrders = $query->get();

        return view('admin.donhang.processed')
            ->with('data', $data)
            ->with('thuonghieus', $thuonghieus)
            ->with('loaigiays', $loaigiays)
            ->with('giays', $giays)
            ->with('users', $users)
            ->with('phanquyens', $phanquyens)
            ->with('khuyenmais', $khuyenmais)
            ->with('processedOrders', $processedOrders)
            ->with('filter', $filter ?? '')
        ;
    }

    /**
     * Reject an order (admin) and restock products when order was pending.
     */
    public function tuCho(Request $request, $id)
    {
        $order = DonHang::find($id);
        if (!$order) {
            session()->flash('thatbai', 'Đơn hàng không tồn tại');
            return Redirect('/admin/donhang');
        }

        if ($order->trang_thai !== 'cho') {
            session()->flash('thatbai', 'Chỉ có đơn hàng ở trạng thái chờ mới có thể từ chối.');
            return Redirect('/admin/donhang/xem/id='.$id);
        }

        // unserialize invoice and restock products
        $items = [];
        try {
            $items = is_string($order->hoa_don) ? @unserialize($order->hoa_don) : $order->hoa_don;
        } catch (\Exception $e) {
            $items = [];
        }

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

        $order->trang_thai = 'tu_choi';
        $order->save();

        // TODO: implement refund logic (payment gateway) — currently just flash message
        session()->flash('thanhcong', 'Đã từ chối đơn và hoàn lại hàng; tồn kho đã được cập nhật.');
        return Redirect('/admin/donhang/xem/id='.$id);
    }

    /**
     * Approve an order (admin) - mark as confirmed.
     */
    public function duyet(Request $request, $id)
    {
        $order = DonHang::find($id);
        if (!$order) {
            session()->flash('thatbai', 'Đơn hàng không tồn tại');
            return Redirect('/admin/donhang');
        }

        if ($order->trang_thai !== 'cho') {
            session()->flash('thatbai', 'Chỉ có đơn hàng ở trạng thái chờ mới có thể duyệt.');
            return Redirect('/admin/donhang/xem/id='.$id);
        }

        $order->trang_thai = 'da_xac_nhan';
        $order->save();

        // TODO: send notification/email to customer or trigger further processing
        session()->flash('thanhcong', 'Đã duyệt đơn hàng.');
        return Redirect('/admin/donhang/xem/id='.$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $data = DonHang::find($id);
        $data->delete();
        return Redirect('/admin/donhang');
    }
}
