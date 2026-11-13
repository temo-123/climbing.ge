<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User\SeasonalCampaign;
use App\Services\PermissionService;
use App\Services\SeasonalCampaignService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Admin: Notifications → Seasonal campaigns (user.climbing.ge/seasonal_campaigns).
 * docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md
 */
class SeasonalCampaignController extends Controller
{
    public function index()
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'show')) return $auth;

        $today = now(config('app.timezone'));
        $campaigns = SeasonalCampaign::orderBy('start_md')->get()->map(function (SeasonalCampaign $c) use ($today) {
            $next = SeasonalCampaignService::next_start($c, $today);
            $end = SeasonalCampaignService::season_end($c, $today);
            return array_merge($c->toArray(), [
                'in_season'      => SeasonalCampaignService::in_season($c, $today),
                'next_start'     => $next?->toDateString(),
                'season_end'     => $end?->toDateString(),
                'audience_count' => SeasonalCampaignService::audience_count($c),
                'content_count'  => SeasonalCampaignService::candidates($c)->count(),
                'stats'          => SeasonalCampaignService::stats($c),
            ]);
        });

        return response()->json([
            'today'     => $today->toDateString(),
            'gap_days'  => SeasonalCampaignService::GLOBAL_GAP_DAYS,
            'campaigns' => $campaigns,
            'options'   => [
                'categories'    => SeasonalCampaignService::CATEGORIES,
                'content_types' => SeasonalCampaignService::CONTENT_TYPES,
                'picks'         => SeasonalCampaignService::PICKS,
                'audiences'     => SeasonalCampaignService::AUDIENCES,
                'sun_filters'   => SeasonalCampaignService::SUN_FILTERS,
            ],
            'can' => [
                'add'  => !PermissionService::authorize('seasonal_campaign', 'add'),
                'edit' => !PermissionService::authorize('seasonal_campaign', 'edit'),
                'del'  => !PermissionService::authorize('seasonal_campaign', 'del'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'add')) return $auth;
        $c = SeasonalCampaign::create($this->validated($request));
        return response()->json(['message' => 'Campaign created', 'id' => $c->id], 201);
    }

    public function update(Request $request, int $id)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'edit')) return $auth;
        SeasonalCampaign::findOrFail($id)->update($this->validated($request));
        return response()->json(['message' => 'Campaign saved']);
    }

    public function toggle(int $id)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'edit')) return $auth;
        $c = SeasonalCampaign::findOrFail($id);
        $c->update(['enabled' => !$c->enabled]);
        return response()->json(['enabled' => $c->enabled]);
    }

    public function destroy(int $id)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'del')) return $auth;
        SeasonalCampaign::findOrFail($id)->delete();   // its log rows stay for the analytics history
        return response()->json(['message' => 'Campaign deleted']);
    }

    /** what the logged-in admin would get today (ignores season and throttles) */
    public function preview(Request $request, int $id)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'show')) return $auth;
        $c = SeasonalCampaign::findOrFail($id);
        $user = $request->user();
        return response()->json([
            'items'     => SeasonalCampaignService::pick_for($c, $user, SeasonalCampaignService::candidates($c)),
            'due'       => SeasonalCampaignService::due_for($c, $user),
            'in_season' => SeasonalCampaignService::in_season($c),
            'subject'   => ($user->lang === 'ka' && $c->subject_ka) ? $c->subject_ka : $c->subject_us,
        ]);
    }

    /** queue the preview mail to the admin themself — not logged, so it doesn't count or block */
    public function send_test(Request $request, int $id)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'edit')) return $auth;
        $c = SeasonalCampaign::findOrFail($id);
        $user = $request->user();
        $items = SeasonalCampaignService::pick_for($c, $user, SeasonalCampaignService::candidates($c));
        if (!$items) {
            return response()->json(['message' => 'Nothing to send: this campaign has no content to pick from.'], 422);
        }
        SeasonalCampaignService::send($c, $user, $items, false);
        return response()->json(['message' => 'Test mail queued to ' . $user->email]);
    }

    /** run today's send now (same rules as the daily job); ?dry=1 only counts */
    public function run(Request $request, int $id)
    {
        if ($auth = PermissionService::authorize('seasonal_campaign', 'edit')) return $auth;
        return response()->json(SeasonalCampaignService::run(SeasonalCampaign::findOrFail($id), $request->boolean('dry')));
    }

    private function validated(Request $request): array
    {
        $md = ['required', 'regex:/^(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/'];
        $data = $request->validate([
            'name'           => 'required|string|max:120',
            'content_type'   => ['required', Rule::in(SeasonalCampaignService::CONTENT_TYPES)],
            'category'       => ['required', Rule::in(SeasonalCampaignService::CATEGORIES)],
            'sun_filter'     => ['nullable', Rule::in(SeasonalCampaignService::SUN_FILTERS)],
            'start_md'       => $md,
            'end_md'         => $md,
            'every_days'     => 'required|integer|min:3|max:365',
            'items_per_mail' => 'required|integer|min:1|max:3',
            'pick'           => ['required', Rule::in(SeasonalCampaignService::PICKS)],
            'audience'       => ['required', Rule::in(SeasonalCampaignService::AUDIENCES)],
            'inactive_days'  => 'nullable|required_if:audience,inactive|integer|min:7|max:730',
            'max_per_run'    => 'required|integer|min:1|max:5000',
            'subject_us'     => 'required|string|max:150',
            'subject_ka'     => 'nullable|string|max:150',
            'intro_us'       => 'nullable|string|max:2000',
            'intro_ka'       => 'nullable|string|max:2000',
            'enabled'        => 'boolean',
        ]);
        $data['sun_filter'] = $data['content_type'] === 'sector' ? ($data['sun_filter'] ?? 'any') : 'any';
        return $data;
    }
}
