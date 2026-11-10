<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour;
use App\Models\Shop\Locale_tour;
use App\Models\Shop\Tour_image;
use App\Models\User\User_tour;

use App\Services\TourService;
use App\Services\TourPriceService;
use App\Services\TourScheduleService;
use App\Services\TourGuideService;
use App\Services\URLTitleService;
use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;
use App\Services\PermissionService;

use Validator;
use Auth;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    function get_user_tours(){
        if ($auth = PermissionService::authorize('tour', 'show')) return $auth;
        return Auth::user()->tours;
    }

    function add_tour(Request $request){
        $auth = PermissionService::authorize('tour', 'add');
        if ($auth) return $auth;
        $data = json_decode($request->data, true );

        $validator = Validator::make($data, [
            'global_tour.category_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }
        
        // $image_path = 'images/tour_img/';

        $tour_adding = TourService::add_content($data, Tour::class, Locale_tour::class, '_tour', $request);
        
        if (!array_key_exists('validation', $tour_adding->original)) {
            if($request->hasFile('tour_images')){
                GalleryService::add_gallery_images(
                    $request->tour_images, 
                    $tour_adding->original['global_tour_id'], 
                    tour_image::class, 
                    'image', 
                    'tour_id', 
                    '/images/tour_img/'
                );
            }

            // the creator becomes the tour's first (= general) guide
            TourGuideService::add_guide(Tour::find($tour_adding->original['global_tour_id']), Auth::id());
        }
        else {
            return $tour_adding;
        }
    }

    public function edit_tour(Request $request)
    {
        $auth = PermissionService::authorize('tour', 'edit');
        if ($auth) return $auth;
        $data = json_decode($request->data, true );

        $validator = Validator::make($data, [
            'global_tour.category_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }

        $tour_editing = TourService::edit_content(Tour::class, Locale_tour::class, '_tour', $request, 'images/tour_img/');

        if(!array_key_exists('validation', $tour_editing->original)){
            if($request->hasFile('tour_new_images')){
                GalleryService::add_gallery_images(
                    $request->tour_new_images, 
                    $tour_editing->original['global_tour_id'], 
                    tour_image::class, 
                    'image', 
                    'tour_id', 
                    '/images/tour_img/'
                );
            }
        }
        else{
            return $article_editing;
        }   
    }

    // legacy helper — goes through TourGuideService so the first guide becomes general
    public function create_tour_user_relations($tour_id, $user_id = null) {
        $tour = Tour::find($tour_id);
        if ($tour) TourGuideService::add_guide($tour, $user_id ?? Auth::id());
    }

    // "Manage tour & guides" modal: guides (general first) + open reservations each
    public function get_tour_users($tour_id)
    {
        if ($auth = PermissionService::authorize('tour', 'show')) return $auth;
        $tour = Tour::find($tour_id);
        if (!$tour) return response()->json(['message' => 'Tour not found.'], 404);

        return response()->json(TourGuideService::admin_guides($tour));
    }

    public function add_tour_user(Request $request, $tour_id)
    {
        if ($auth = PermissionService::authorize('tour', 'edit')) return $auth;
        $tour = Tour::find($tour_id);
        if (!$tour) return response()->json(['error' => 'Tour not found.'], 404);

        $error = TourGuideService::add_guide($tour, (int) $request->user_id);
        if ($error) return response()->json(['error' => $error], 422);
        return response()->json(['success' => true, 'guides' => TourGuideService::admin_guides($tour)]);
    }

    // a general guide hands over to the next guide; their open reservations move (mails sent)
    public function remove_tour_user($tour_id, $user_id)
    {
        if ($auth = PermissionService::authorize('tour', 'edit')) return $auth;
        $tour = Tour::find($tour_id);
        if (!$tour) return response()->json(['error' => 'Tour not found.'], 404);

        $res = TourGuideService::remove_guide($tour, (int) $user_id, Auth::user());
        if ($res['error']) return response()->json(['error' => $res['error']], 422);
        return response()->json(['success' => true, 'moved' => $res['moved'], 'guides' => TourGuideService::admin_guides($tour)]);
    }

    public function set_main_guide(Request $request, $tour_id)
    {
        if ($auth = PermissionService::authorize('tour', 'edit')) return $auth;
        $tour = Tour::find($tour_id);
        if (!$tour) return response()->json(['error' => 'Tour not found.'], 404);

        $error = TourGuideService::set_main($tour, (int) $request->user_id);
        if ($error) return response()->json(['error' => $error], 422);
        return response()->json(['success' => true, 'guides' => TourGuideService::admin_guides($tour)]);
    }

    // hand the tour to another guide: new general guide + (optionally) move the old general's open reservations
    public function handover_tour(Request $request, $tour_id)
    {
        if ($auth = PermissionService::authorize('tour', 'edit')) return $auth;
        $tour = Tour::find($tour_id);
        if (!$tour) return response()->json(['error' => 'Tour not found.'], 404);
        $request->validate(['user_id' => 'required|integer', 'move_reservations' => 'nullable|boolean', 'notify_clients' => 'nullable|boolean']);

        $res = TourGuideService::handover($tour, (int) $request->user_id, $request->boolean('move_reservations', true), $request->boolean('notify_clients', true), Auth::user());
        if ($res['error']) return response()->json(['error' => $res['error']], 422);
        return response()->json(['success' => true, 'moved' => $res['moved'], 'guides' => TourGuideService::admin_guides($tour)]);
    }

    // open reservations of the tour with the guide in charge (modal "Reservations" list)
    public function get_tour_open_reservations($tour_id)
    {
        if ($auth = PermissionService::authorize('tour_reservation', 'show')) return $auth;
        $cols = ['id', 'name', 'check_in', 'days', 'persons', 'tour_language', 'verificate', 'guide_id', 'price_total', 'price_currency', 'completed_at', 'commission_percent', 'commission_amount', 'commission_paid_at'];
        $today = now()->toDateString();
        $open = \App\Models\Shop\Tour_reservation::where('tour_id', (int) $tour_id)
            ->where(fn ($q) => $q->whereNull('check_in')->orWhere('check_in', '>=', $today))
            ->orderBy('check_in')->get($cols);
        // "To settle": confirmed trips in the past not marked completed, or any unpaid fee
        $settle = \App\Models\Shop\Tour_reservation::where('tour_id', (int) $tour_id)
            ->where(function ($q) use ($today) {
                $q->where(fn ($p) => $p->where('verificate', 1)->whereNull('completed_at')->whereNotNull('check_in')->where('check_in', '<', $today))
                  ->orWhere(fn ($p) => $p->whereNotNull('commission_percent')->whereNull('commission_paid_at'));
            })
            ->orderBy('check_in')->get($cols);
        $names = \App\Models\User::whereIn('id', $open->pluck('guide_id')->merge($settle->pluck('guide_id'))->filter()->unique())
            ->get(['id', 'name', 'surname'])->mapWithKeys(fn ($u) => [$u->id => trim($u->name . ' ' . $u->surname)]);
        $settle = $settle->map(fn ($r) => $r->toArray() + ['guide_name' => $names[$r->guide_id] ?? null]);
        return response()->json(['open' => $open, 'settle' => $settle]);
    }

    public function change_user_relation(Request $request) {
        if($request['data']['old_user_id'] != []){
            $user_relatione = User_tour::where('tour_id', '=', $request['data']['tour_id'])->where('user_id', '=', $request['data']['old_user_id'])->first();
            if($user_relatione != []){
                $user_relatione['user_id'] = $request['data']['new_user_id'];
                $user_relatione -> save();
            }
        }
        else{
            $this->create_tour_user_relations($request['data']['tour_id'], $request['data']['new_user_id']);
        }
    }

    // Prices tab: all rules + currency
    public function get_tour_prices(Request $request)
    {
        if ($auth = PermissionService::authorize('tour', 'show')) return $auth;
        $tour = Tour::where('id', (int) $request->tour_id)->first();
        if (!$tour) return response()->json(['message' => 'Tour not found.'], 404);

        return response()->json([
            'currency' => TourPriceService::currency($tour),
            'rules' => TourPriceService::rules($tour->id),
        ]);
    }

    // Prices tab: replace all rules (TourPriceService::save checks overlaps)
    public function save_tour_prices(Request $request)
    {
        if ($auth = PermissionService::authorize('tour', 'edit')) return $auth;
        $tour = Tour::where('id', (int) $request->tour_id)->first();
        if (!$tour) return response()->json(['message' => 'Tour not found.'], 404);

        $validator = Validator::make($request->all(), [
            'currency' => 'nullable|in:' . implode(',', TourPriceService::CURRENCIES),
            'rules' => 'present|array|max:200',
            'rules.*.days' => 'required|integer|min:1|max:60',
            'rules.*.persons_min' => 'required|integer|min:1|max:500',
            'rules.*.persons_max' => 'nullable|integer|min:1|max:500',
            'rules.*.price' => 'required|numeric|min:0|max:9999999',
            'rules.*.price_type' => 'required|in:' . implode(',', TourPriceService::TYPES),
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $error = TourPriceService::save($tour, $request->currency ?: TourPriceService::DEFAULT_CURRENCY, $request->rules ?? []);
        if ($error) {
            return response()->json(['message' => $error], 422);
        }

        return response()->json([
            'success' => true,
            'currency' => $tour->fresh()->price_currency,
            'rules' => TourPriceService::rules($tour->id),
        ]);
    }

    // Season & duration tab
    public function get_tour_schedule(Request $request)
    {
        if ($auth = PermissionService::authorize('tour', 'show')) return $auth;
        $tour = Tour::where('id', (int) $request->tour_id)->first();
        if (!$tour) return response()->json(['message' => 'Tour not found.'], 404);

        return response()->json(TourScheduleService::admin_schedule($tour));
    }

    public function save_tour_schedule(Request $request)
    {
        if ($auth = PermissionService::authorize('tour', 'edit')) return $auth;
        $tour = Tour::where('id', (int) $request->tour_id)->first();
        if (!$tour) return response()->json(['message' => 'Tour not found.'], 404);

        $validator = Validator::make($request->all(), [
            'all_year' => 'required|boolean',
            'seasons' => 'present|array|max:12',
            'seasons.*.from_month' => 'required|integer|min:1|max:12',
            'seasons.*.from_day' => 'required|integer|min:1|max:31',
            'seasons.*.to_month' => 'required|integer|min:1|max:12',
            'seasons.*.to_day' => 'required|integer|min:1|max:31',
            'duration_mode' => 'nullable|in:fixed,range',
            'duration_days' => 'nullable|array|max:60',
            'duration_days.*' => 'integer|min:1|max:60',
            'duration_min' => 'nullable|integer|min:1|max:60',
            'duration_max' => 'nullable|integer|min:1|max:60',
            'start_weekdays' => 'nullable|array|max:7',
            'start_weekdays.*' => 'integer|min:0|max:6',
            'lead_days' => 'nullable|integer|min:0|max:365',
            'max_persons' => 'nullable|integer|min:1|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        $error = TourScheduleService::save($tour, $request->all());
        if ($error) return response()->json(['message' => $error], 422);

        return response()->json(['success' => true] + TourScheduleService::admin_schedule($tour->fresh()));
    }

    public function get_editing_tour(Request $request)
    {
        if ($auth = PermissionService::authorize('tour', 'show')) return $auth;
        $tour = Tour::where('id', '=', $request->tour_id)->first();
        
        $data = [
            'global_tour' => $tour,
            'us_tour' => $tour->us_tour,
            'ka_tour' => $tour->ka_tour,
            // 'ru_tour' => $tour->ru_tour,

            'tour_images' => $tour->tour_images,
        ];

        return $data;
    }

    function get_tour_images(Request $request) {
        if ($auth = PermissionService::authorize('tour', 'show')) return $auth;
        $tour = Tour::where('id', '=', $request->tour_id)->first();
        
        if (!$tour) {
            return abort(404);
        }
        
        if($tour->tour_images->count() > 0){
            return $tour->tour_images;
        }
        return [];
    }

    function del_tour(Request $request){
        $auth = PermissionService::authorize('tour', 'del');
        if ($auth) return $auth;

        $this->deleteOneTour($request->tour_id);
    }

    /**
     * Deletes a single tour, its gallery images, and its us/ka locale content.
     * Returns false gracefully if the tour no longer exists (used by bulk_delete).
     */
    private function deleteOneTour($global_id)
    {
        $global_tour = Tour::where('id',strip_tags($global_id))->first();
        if (!$global_tour) {
            return false;
        }

        $us_tour = Locale_tour::where('id',strip_tags($global_tour->us_tour_id))->first();
        // $ru_tour = Locale_tour::where('id',strip_tags($global_tour->ru_tour_id))->first();
        $ka_tour = Locale_tour::where('id',strip_tags($global_tour->ka_tour_id))->first();

        // delete tour file
        $tour_images_count = Tour_image::where('tour_id',strip_tags($global_id))->count();

        if ($tour_images_count > 0) {
            $tour_images = Tour_image::where('tour_id',strip_tags($global_id))->get();
            foreach ($tour_images as $tour_image) {
                imageControllService::image_delete('images/tour_img/', $tour_image, 'image');
                $tour_image ->delete();
            }
        }

        // delete tour from db
        $global_tour ->delete();
        if ($us_tour) $us_tour ->delete();
        // if ($ru_tour) $ru_tour ->delete();
        if ($ka_tour) $ka_tour ->delete();

        return true;
    }

    public function bulk_delete(Request $request)
    {
        $auth = PermissionService::authorize('tour', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $deleted_count = 0;

        DB::transaction(function () use ($request, &$deleted_count) {
            foreach ($request->ids as $id) {
                if ($this->deleteOneTour($id)) {
                    $deleted_count++;
                }
            }
        });

        return response()->json(['success' => true, 'count' => $deleted_count]);
    }

    public function bulk_publish(Request $request)
    {
        $auth = PermissionService::authorize('tour', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        Tour::whereIn('id', $request->ids)->update(['published' => 1]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    public function bulk_unpublish(Request $request)
    {
        $auth = PermissionService::authorize('tour', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        Tour::whereIn('id', $request->ids)->update(['published' => 0]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    function del_tour_image(Request $request) {
        $auth = PermissionService::authorize('tour', 'edit');
        if ($auth) return $auth;
        $image = Tour_image::where('id', '=', $request->image_id)->first();
        if($image){
            ImageControllService::image_delete('images/tour_img/', $image, 'image');
            $image->delete();
        }
    }
}
