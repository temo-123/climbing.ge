<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\AreaConditionReport;

class AreaConditionReportController extends Controller
{
    /**
     * Recent condition reports for one area — climber restricted to public-safe columns.
     */
    public function get_area_reports($article_id)
    {
        return AreaConditionReport::where('article_id', $article_id)
            ->where('admin_hidden', false)
            ->where('created_at', '>=', now()->subDays(AreaConditionReport::FEED_DAYS))
            ->with('user:id,name,surname,image')
            ->latest('id')
            ->limit(20)
            ->get(['id', 'article_id', 'user_id', 'condition', 'note', 'created_at']);
    }
}
