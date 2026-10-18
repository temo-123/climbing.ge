<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\MtpAscent;
use App\Models\Guide\Mtp;
use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;
use App\Services\ReCaptchaV3Service;
use Illuminate\Support\Facades\File;

class MtpAscentController extends Controller
{
    public function create_mtp_ascent(Request $request, $mtp_id)
    {
        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verifySmart($token, $request->ip())) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $mtp = Mtp::findOrFail($mtp_id);

        $request->validate([
            'ascent_date'  => 'required|date|before_or_equal:today',
            'ascent_time'  => 'nullable|date_format:H:i',
            'ascent_style' => 'nullable|in:' . implode(',', MtpAscent::STYLES),
            'comment'      => 'nullable|string|max:2000',
            'photo'        => 'nullable|image|max:10240',
        ]);

        File::ensureDirectoryExists(public_path('images/mtp_ascents_img'));

        $ascent = MtpAscent::create([
            'mtp_id'       => $mtp->id,
            'user_id'      => $request->user()->id,
            'ascent_date'  => $request->ascent_date,
            'ascent_time'  => $request->ascent_time,
            'ascent_style' => $request->ascent_style,
            'comment'      => $request->comment,
            'photo'        => ImageControllService::image_upload('images/mtp_ascents_img/', $request, 'photo', 2),
        ]);

        return response()->json(['message' => 'Ascent recorded successfully', 'ascent_id' => $ascent->id], 201);
    }

    public function get_my_mtp_ascents(Request $request)
    {
        return MtpAscent::where('user_id', $request->user()->id)
            ->with('mtp:id,name,sector_id')
            ->orderByDesc('ascent_date')
            ->get()
            ->map(fn ($ascent) => array_merge($ascent->toArray(), ['mtp_name' => $ascent->mtp?->name]));
    }

    public function del_my_mtp_ascent(Request $request, $ascent_id)
    {
        $ascent = MtpAscent::where('id', $ascent_id)->where('user_id', $request->user()->id)->first();
        if (!$ascent) return response()->json(['error' => 'Not found'], 404);

        $this->delete_ascent($ascent);

        return response()->json(['success' => true]);
    }

    public function get_all_mtp_ascents_admin()
    {
        $auth = PermissionService::authorize('mtp', 'show');
        if ($auth) return $auth;

        return MtpAscent::with(['mtp:id,name', 'user:id,name,surname,email'])
            ->latest('id')
            ->get()
            ->map(fn ($ascent) => array_merge($ascent->toArray(), [
                'mtp_name'  => $ascent->mtp?->name,
                'user_name' => trim(($ascent->user?->name ?? '') . ' ' . ($ascent->user?->surname ?? '')),
                'user_email'=> $ascent->user?->email,
            ]));
    }

    public function del_mtp_ascent_admin($ascent_id)
    {
        $auth = PermissionService::authorize('mtp', 'del');
        if ($auth) return $auth;

        $ascent = MtpAscent::find($ascent_id);
        if (!$ascent) return response()->json(['error' => 'Not found'], 404);

        $this->delete_ascent($ascent);

        return response()->json(['success' => true]);
    }

    private function delete_ascent(MtpAscent $ascent)
    {
        if ($ascent->photo) {
            ImageControllService::image_delete('images/mtp_ascents_img/', $ascent, 'photo');
        }
        $ascent->delete();
    }
}
