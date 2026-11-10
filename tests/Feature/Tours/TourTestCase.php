<?php

namespace Tests\Feature\Tours;

use App\Models\Shop\Tour;
use App\Models\User;
use App\Services\ReCaptchaV3Service;
use App\Services\TourGuideService;
use App\Services\TourPriceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Fixtures for the tour suite (reservations, prices, schedule, guides, fees, mails).
 * Every test runs in a rolled-back transaction; mail is faked; reCAPTCHA is off.
 */
abstract class TourTestCase extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->mock(ReCaptchaV3Service::class, fn ($m) => $m->shouldReceive('isConfigured')->andReturn(false));
    }

    protected function user(array $attrs = []): User
    {
        $id = DB::table('users')->insertGetId(array_merge([
            'name' => 'Guide', 'surname' => 'T' . Str::random(4),
            'email' => 'tour-test-' . Str::random(10) . '@example.test',
            'phone_number' => '555' . rand(100000, 999999),
            'password' => bcrypt('secret'), 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ], $attrs));
        return User::find($id);
    }

    protected function admin(): User
    {
        $u = $this->user(['name' => 'Admin', 'surname' => 'Tester']);
        DB::table('user_role')->insert(['user_id' => $u->id, 'role_id' => DB::table('roles')->where('slug', 'admin')->value('id'), 'created_at' => now(), 'updated_at' => now()]);
        return $u->fresh();
    }

    /** Published tour, all year, priced: 1–2 days × 100 €/person, max 6 persons. */
    protected function tour(array $attrs = [], ?User $general = null): Tour
    {
        $tag = 'tour-test-' . Str::random(8);
        $us = DB::table('locale_tours')->insertGetId(['locale' => 'us', 'title' => 'EN ' . $tag, 'location' => 'Somewhere', 'duration' => '1–2 days', 'created_at' => now(), 'updated_at' => now()]);
        $ka = DB::table('locale_tours')->insertGetId(['locale' => 'ka', 'title' => 'KA ' . $tag, 'created_at' => now(), 'updated_at' => now()]);
        $id = DB::table('tours')->insertGetId(array_merge([
            'url_title' => $tag, 'published' => 1, 'category_id' => DB::table('tour_categories')->value('id'),
            'us_tour_id' => $us, 'ka_tour_id' => $ka, 'price_currency' => 'EUR', 'all_year' => 1, 'max_persons' => 6,
            'created_at' => now(), 'updated_at' => now(),
        ], $attrs));
        $tour = Tour::find($id);
        TourPriceService::save($tour, 'EUR', [
            ['days' => 1, 'persons_min' => 1, 'persons_max' => null, 'price' => 100, 'price_type' => 'per_person'],
            ['days' => 2, 'persons_min' => 1, 'persons_max' => null, 'price' => 180, 'price_type' => 'per_person'],
        ]);
        TourGuideService::add_guide($tour, ($general ?? $this->user(['name' => 'General']))->id);
        return $tour->fresh();
    }

    protected function form(array $over = []): array
    {
        return array_merge([
            'check_in' => now()->addDays(10)->format('Y-m-d'),
            'days' => 2, 'persons' => 3, 'tour_language' => 'en',
            'name' => 'Guest Person', 'email' => 'guest-' . Str::random(8) . '@example.test',
            'phone' => '+995 555 12 34 56', 'country' => 'Georgia', 'city' => 'Tbilisi',
            'text' => 'We are three beginners.',
        ], $over);
    }

    protected function book(Tour $tour, array $over = [])
    {
        return $this->postJson('/api/set_user_reservation/create_reservation/' . $tour->id, ['form_data' => $this->form($over)]);
    }

    /** Was $class sent on-demand to $email? */
    protected function mailedTo(string $class, string $email): bool
    {
        try {
            Notification::assertSentOnDemand($class, fn ($n, $channels, $notifiable) => strtolower($notifiable->routes['mail'] ?? '') === strtolower($email));
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    protected function siteEmail(): string
    {
        return (string) (DB::table('sites')->value('email') ?: config('mail.from.address'));
    }
}
