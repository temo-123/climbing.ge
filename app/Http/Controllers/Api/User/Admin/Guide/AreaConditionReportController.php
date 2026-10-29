<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\AreaConditionReport;
use App\Models\Guide\Article;
use App\Services\PermissionService;

class AreaConditionReportController extends Controller
{
    public function create_area_report(Request $request, $article_id)
    {
        $article = Article::whereIn('category', ['outdoor', 'ice'])->findOrFail($article_id);

        $request->validate([
            'condition' => 'required|in:' . implode(',', AreaConditionReport::CONDITIONS),
            'note'      => 'nullable|string|max:500',
        ]);

        // one report per climber per area every 10 minutes — enough to fix a typo, not to spam
        $recent = AreaConditionReport::where('article_id', $article->id)
            ->where('user_id', $request->user()->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();
        if ($recent) {
            return response()->json(['message' => 'You already reported conditions here a few minutes ago.'], 429);
        }

        $report = AreaConditionReport::create([
            'article_id' => $article->id,
            'user_id'    => $request->user()->id,
            'condition'  => $request->condition,
            'note'       => $request->note ? strip_tags($request->note) : null,
        ]);

        return response()->json(['message' => 'Report saved', 'report_id' => $report->id], 201);
    }

    public function del_my_area_report(Request $request, $report_id)
    {
        $report = AreaConditionReport::where('id', $report_id)->where('user_id', $request->user()->id)->first();
        if (!$report) return response()->json(['error' => 'Not found'], 404);

        $report->delete();

        return response()->json(['success' => true]);
    }

    // guide dashboard → Community tab: latest reports incl. hidden ones
    public function get_area_reports_admin()
    {
        $auth = PermissionService::authorize('comment', 'show');
        if ($auth) return $auth;

        return AreaConditionReport::with(['user:id,name,surname', 'article:id,category,url_title'])
            ->latest('id')
            ->limit(200)
            ->get();
    }

    // moderation shares the comment permissions, like route reviews
    public function hide_area_report_admin($report_id)
    {
        $auth = PermissionService::authorize('comment', 'del');
        if ($auth) return $auth;

        $report = AreaConditionReport::findOrFail($report_id);
        $report->admin_hidden = !$report->admin_hidden;
        $report->save();

        return response()->json(['success' => true, 'admin_hidden' => $report->admin_hidden]);
    }
}
