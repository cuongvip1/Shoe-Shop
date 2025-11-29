<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\User;
use App\Services\ApiClient;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(session()->get(key:'check') == 1){
            $api = new ApiClient();
            $users = $api->get('/api/users');
            $giays = $this->fetchAllAdminGiays($api);
            $loaigiays = $api->get('/api/loai-giay');
            $thuonghieus = $api->get('/api/thuong-hieu');
            $khuyenmais = $api->get('/api/khuyen-mai');
            $phanquyens = $api->get('/api/phan-quyen');
            $donhangs = $api->get('/api/don-hang');

            // Normalize API responses to arrays or Paginators so views can use count() and links()
            $users = $this->normalizeData($users);
            $giays = $this->normalizeData($giays);
            $loaigiays = $this->normalizeData($loaigiays);
            $thuonghieus = $this->normalizeData($thuonghieus);
            $khuyenmais = $this->normalizeData($khuyenmais);
            $phanquyens = $this->normalizeData($phanquyens);
            $donhangs = $this->normalizeData($donhangs);

            return view('admin.trangchu.trangchu')
            ->with('data', User::where('id',session('DangNhap'))->first())
            ->with('route', 'TrangChu')
            ->with('users', $users)
            ->with('giays', $giays)
            ->with('loaigiays', $loaigiays)
            ->with('thuonghieus', $thuonghieus)
            ->with('khuyenmais', $khuyenmais)
            ->with('phanquyens', $phanquyens)
            ->with('donhangs', $donhangs)
            ;
        } else{
            return Redirect('/trang-chu');
        }
    }

    /**
     * Normalize API responses that may be arrays or stdClass objects with a `data` wrapper.
     * Returns the inner items when `data` exists, otherwise returns the original value.
     */
    private function normalizeData($value)
    {
        if (is_null($value)) return null;

        $isPaginated = false;
        $meta = null;
        if (is_object($value) && property_exists($value, 'data')) {
            $itemsRaw = $value->data;
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
            return $itemsRaw;
        }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }

    public function dieuhuong(Request $request, $slug){
        if(session()->get(key:'check') == 1){
            $data = User::where('id',session('DangNhap'))->first();
            $api = new ApiClient();
            $thuonghieus = $api->get('/api/thuong-hieu');
            $loaigiays = $api->get('/api/loai-giay');
            $giays = $this->fetchAllAdminGiays($api);
            $users = $api->get('/api/users');
            $khuyenmais = $api->get('/api/khuyen-mai');
            $phanquyens = $api->get('/api/phan-quyen');
            $donhangs = $api->get('/api/don-hang');

            $thuonghieus = $this->normalizeData($thuonghieus);
            $loaigiays = $this->normalizeData($loaigiays);
            $giays = $this->normalizeData($giays);
            $users = $this->normalizeData($users);
            $khuyenmais = $this->normalizeData($khuyenmais);
            $phanquyens = $this->normalizeData($phanquyens);
            $donhangs = $this->normalizeData($donhangs);

            // Prepare pending and processed orders for views that expect them (admin/donhang)
            $pendingOrders = [];
            $processedOrders = [];
            $filter = $request->query('status') ?? '';

            if ($donhangs) {
                if ($donhangs instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                    $collection = collect($donhangs->items());
                } else {
                    $collection = collect($donhangs);
                }

                $pendingOrders = $collection->filter(function($d){
                    return (data_get($d, 'trang_thai') === 'cho');
                })->values()->all();

                $processedCollection = $collection->filter(function($d){
                    return in_array(data_get($d, 'trang_thai'), ['tu_choi','da_huy','da_xac_nhan']);
                });

                if ($filter && in_array($filter, ['tu_choi','da_huy','da_xac_nhan'])) {
                    $processedCollection = $processedCollection->filter(function($d) use ($filter){
                        return data_get($d, 'trang_thai') === $filter;
                    });
                }

                $processedOrders = $processedCollection->values()->all();
            }

            return view("admin.{$slug}.{$slug}")
            ->with('data', $data)
            ->with('thuonghieus', $thuonghieus)
            ->with('loaigiays', $loaigiays)
            ->with('giays', $giays)
            ->with('users', $users)
            ->with('khuyenmais', $khuyenmais)
            ->with('phanquyens', $phanquyens)
            ->with('donhangs', $donhangs)
            ->with('pendingOrders', $pendingOrders)
            ->with('processedOrders', $processedOrders)
            ->with('filter', $filter)
            ;
        } else{
            return Redirect('/trang-chu');
        }
    }

    public function dieuhuong2(Request $request, $slug, $slug2){
        if(session()->get(key:'check') == 1){
            $data = User::where('id',session('DangNhap'))->first();
            $api = new ApiClient();
            $thuonghieus = $api->get('/api/thuong-hieu');
            $loaigiays = $api->get('/api/loai-giay');
            $giays = $this->fetchAllAdminGiays($api);
            $users = $api->get('/api/users');
            $phanquyens = $api->get('/api/phan-quyen');
            $khuyenmais = $api->get('/api/khuyen-mai');
            $donhangs = $api->get('/api/don-hang');

            $thuonghieus = $this->normalizeData($thuonghieus);
            $loaigiays = $this->normalizeData($loaigiays);
            $giays = $this->normalizeData($giays);
            $users = $this->normalizeData($users);
            $phanquyens = $this->normalizeData($phanquyens);
            $khuyenmais = $this->normalizeData($khuyenmais);
            $donhangs = $this->normalizeData($donhangs);

            // Prepare pending and processed orders for views
            $pendingOrders = [];
            $processedOrders = [];
            $filter = $request->query('status') ?? '';

            if ($donhangs) {
                if ($donhangs instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                    $collection = collect($donhangs->items());
                } else {
                    $collection = collect($donhangs);
                }

                $pendingOrders = $collection->filter(function($d){
                    return (data_get($d, 'trang_thai') === 'cho');
                })->values()->all();

                $processedCollection = $collection->filter(function($d){
                    return in_array(data_get($d, 'trang_thai'), ['tu_choi','da_huy','da_xac_nhan']);
                });

                if ($filter && in_array($filter, ['tu_choi','da_huy','da_xac_nhan'])) {
                    $processedCollection = $processedCollection->filter(function($d) use ($filter){
                        return data_get($d, 'trang_thai') === $filter;
                    });
                }

                $processedOrders = $processedCollection->values()->all();
            }

            return view("admin.{$slug}.{$slug2}")
            ->with('data', $data)
            ->with('thuonghieus', $thuonghieus)
            ->with('loaigiays', $loaigiays)
            ->with('giays', $giays)
            ->with('users', $users)
            ->with('phanquyens', $phanquyens)
            ->with('khuyenmais', $khuyenmais)
            ->with('donhangs', $donhangs)
            ->with('pendingOrders', $pendingOrders)
            ->with('processedOrders', $processedOrders)
            ->with('filter', $filter)
            ;
        } else{
            return Redirect('/trang-chu');
        }
    }

    /**
     * Fetch the full giay catalog for admin views by requesting a larger page size.
     */
    private function fetchAllAdminGiays(ApiClient $api, array $filters = [])
    {
        $perPage = (int) env('ADMIN_GIAY_PER_PAGE', 200);
        if ($perPage < 1) {
            $perPage = 200;
        }

        return $api->get('/api/giay', array_merge(['per_page' => $perPage], $filters));
    }
}
