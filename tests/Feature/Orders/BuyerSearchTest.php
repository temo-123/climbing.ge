<?php

namespace Tests\Feature\Orders;

use Laravel\Sanctum\Sanctum;

/** GET custom_order/search_buyers — the account suggestions in the custom-order form. */
class BuyerSearchTest extends OrderTestCase
{
    private function search(array $q)
    {
        Sanctum::actingAs($this->admin());
        return $this->getJson('/api/custom_order/search_buyers?' . http_build_query($q));
    }

    public function test_exact_email_ranks_first_and_is_flagged_exact(): void
    {
        $tag = uniqid();
        $target = $this->user(['name' => 'Nino', 'surname' => 'Target' . $tag, 'email' => "nino.$tag@example.test"]);
        $this->user(['name' => 'Nino', 'surname' => 'Other' . $tag, 'email' => "nino.$tag.other@example.test"]);

        $res = $this->search(['email' => "nino.$tag@example.test"])->assertOk();

        $this->assertSame($target->id, $res->json('0.id'));
        $this->assertTrue($res->json('0.exact'));
        $this->assertContains('email', $res->json('0.matched_by'));
    }

    public function test_phone_matches_regardless_of_formatting(): void
    {
        $u = $this->user(['phone_number' => '+995 (577) 44-55-66']);

        $res = $this->search(['phone' => '577445566'])->assertOk();

        $this->assertSame($u->id, $res->json('0.id'));
        $this->assertContains('phone', $res->json('0.matched_by'));
    }

    public function test_name_and_surname_together(): void
    {
        $tag = 'Zq' . substr(uniqid(), -6);
        $u = $this->user(['name' => 'Levan', 'surname' => $tag]);
        $this->user(['name' => 'Davit', 'surname' => $tag]);

        $ids = collect($this->search(['name' => 'Levan', 'surname' => $tag])->json())->pluck('id');

        $this->assertTrue($ids->contains($u->id));
        $this->assertCount(1, $ids, 'surname alone matches two, name + surname only one');
    }

    public function test_partner_organization_is_shown(): void
    {
        $u = $this->user();
        $this->partner(15, $u);

        $res = $this->search(['email' => $u->email]);

        $this->assertEquals(15, $res->json('0.organization.discount'));
    }

    public function test_only_safe_columns_are_returned(): void
    {
        $u = $this->user();
        $row = $this->search(['email' => $u->email])->json('0');

        foreach (['password', 'remember_token', 'email_verified_at', 'last_seen_at'] as $secret) {
            $this->assertArrayNotHasKey($secret, $row);
        }
    }

    public function test_too_short_query_returns_nothing(): void
    {
        $this->search(['name' => 'a', 'email' => 'ab', 'phone' => '12'])->assertOk()->assertExactJson([]);
    }

    public function test_like_wildcards_are_not_injected(): void
    {
        $this->search(['email' => '%%%'])->assertOk()->assertExactJson([]);
    }

    public function test_plain_user_cannot_search(): void
    {
        Sanctum::actingAs($this->user());
        $this->getJson('/api/custom_order/search_buyers?email=someone@example.test')->assertForbidden();
    }
}
