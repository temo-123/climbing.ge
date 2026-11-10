<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\MountSystemService;

use App\Models\Guide\Mount;
use App\Models\Guide\Locale_mount;

use App\Models\Guide\Article_mount;
use App\Models\Guide\Article;
use App\Models\Summit\Summit;

use Auth;
use Validator;

class MountController extends Controller
{
    // Every mount massif (published or not): feeds the admin article forms'
    // mount massif select, so it is intentionally not filtered.
    public function get_all_mount()
    {
        return MountSystemService::get_local_mounts_use_locale(Mount::latest('id')->get(), 'us');
    }

    // Public lists (guide mountaineering filter, summit filter): public only.
    public function get_locale_mounts(Request $request)
    {
        return MountSystemService::get_local_mounts_use_locale(Mount::listed()->latest('id')->get(), $request->lang);
    }

    // One mount massif: public, or "public by URL" (published = 2).
    public function get_locale_mount(Request $request)
    {
        if (!Mount::where('id', $request->mount_id)->viewable()->exists()) {
            return [];
        }
        return MountSystemService::get_local_mount_use_locale($request->mount_id, $request->lang);
    }

    public function get_locale_mount_on_route_page(Request $request)
    {
        $m_system = Article::where('id', '=', $request->mount_route_id)->first();
        if (!$m_system) {
            return null;
        }

        $mount_sys = $m_system->mount_masiv;

        if(count($mount_sys) > 0){
            $mounts_system = Mount::where('id', '=', $mount_sys[0]->id)->viewable()->first();
            if (!$mounts_system) {
                return null;
            }

            $system = MountSystemService::get_local_mount_use_locale($mounts_system->id, $request->lang);
            if (empty($system)) {
                return null;
            }

            $massif = $system[0];

            // Context for the massif card on the route page: how many public
            // routes share this massif (links to the filtered list) and its
            // published summits (name + height chips; coordinates for the
            // card's OpenStreetMap view).
            $massif['routes_count'] = $mounts_system->articles()
                ->where('category', 'mount_route')
                ->where('published', 1)
                ->count();

            $is_ka = $request->lang === 'ka';
            $massif['summits'] = Summit::where('mount_id', $mounts_system->id)
                ->where('published', 1)
                ->orderByDesc('height')
                ->get(['id', 'title', 'ka_title', 'url_title', 'height', 'latitude', 'longitude'])
                ->map(fn ($s) => [
                    'id'        => $s->id,
                    'title'     => ($is_ka && $s->ka_title) ? $s->ka_title : $s->title,
                    'url_title' => $s->url_title,
                    'height'    => $s->height,
                    'latitude'  => $s->latitude !== null ? (float) $s->latitude : null,
                    'longitude' => $s->longitude !== null ? (float) $s->longitude : null,
                ])
                ->values();

            return $massif;
        }

        return null;
    }

}
