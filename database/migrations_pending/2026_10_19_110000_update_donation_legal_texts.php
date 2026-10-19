<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fills the donation legal texts in site data (locale_sites):
 * - organization_legal_info — "Organization Contacts" block on the guide About page;
 * - donation_terms_of_use   — donation terms (site donation modal + mobile app).
 * Values come from config/organization.php (ORGANIZATION_* in .env). Refuses to run
 * while required values are missing, so placeholder text is never published.
 * Overwrites both texts, including admin edits. Same texts as
 * docs/BACKEND/sql-imports/donation_legal_texts.sql.
 */
return new class extends Migration
{
    const REQUIRED = [
        'legal_name_us' => 'ORGANIZATION_LEGAL_NAME_US',
        'legal_name_ka' => 'ORGANIZATION_LEGAL_NAME_KA',
        'reg_number'    => 'ORGANIZATION_REG_NUMBER',
        'address_us'    => 'ORGANIZATION_ADDRESS_US',
        'address_ka'    => 'ORGANIZATION_ADDRESS_KA',
    ];

    const REFUND_US = 'Donations are non-refundable. If you were charged by mistake (wrong amount or a duplicate payment), contact us within 14 days and we will review a refund.';
    const REFUND_KA = 'შემოწირულობა არ ბრუნდება. თუ თანხა შეცდომით ჩამოგეჭრათ (არასწორი თანხა ან განმეორებითი გადახდა), დაგვიკავშირდით 14 დღის განმავლობაში და განვიხილავთ თანხის დაბრუნებას.';

    public function up(): void
    {
        $org = config('organization');

        $missing = [];
        foreach (self::REQUIRED as $key => $env_name) {
            if (empty($org[$key])) $missing[] = $env_name;
        }
        if ($missing) {
            throw new RuntimeException('Set ' . implode(', ', $missing) . ' in .env (then php artisan config:clear) before running this migration — see config/organization.php.');
        }

        $e = fn ($value) => e((string) $value);
        $email = $org['email'] ?: (DB::table('sites')->value('email') ?? '');
        $mail_link = '<a href="mailto:' . $e($email) . '">' . $e($email) . '</a>';
        $tax_us = $org['tax_status_us'] ? ' ' . $e($org['tax_status_us']) : '';
        $tax_ka = $org['tax_status_ka'] ? ' ' . $e($org['tax_status_ka']) : '';

        $this->set('organization_legal_info',
            '<p><strong>' . $e($org['legal_name_us']) . '</strong><br>' . $e($org['legal_form_us'])
                . '<br>Identification code: ' . $e($org['reg_number'])
                . '<br>Legal address: ' . $e($org['address_us'])
                . ($tax_us ? '<br>' . trim($tax_us) : '')
                . '<br>Email: ' . $mail_link . '</p>',
            '<p><strong>' . $e($org['legal_name_ka']) . '</strong><br>' . $e($org['legal_form_ka'])
                . '<br>საიდენტიფიკაციო კოდი: ' . $e($org['reg_number'])
                . '<br>იურიდიული მისამართი: ' . $e($org['address_ka'])
                . ($tax_ka ? '<br>' . trim($tax_ka) : '')
                . '<br>ელფოსტა: ' . $mail_link . '</p>'
        );

        $this->set('donation_terms_of_use',
            '<h3>Donation terms</h3>'
                . '<p>Donations to climbing.ge are received by <strong>' . $e($org['legal_name_us']) . '</strong>, ' . $e($org['legal_form_us'])
                . ', identification code ' . $e($org['reg_number']) . ', ' . $e($org['address_us']) . '.' . $tax_us . '</p><ul>'
                . '<li>Donations are voluntary and support the maintenance and development of the climbing.ge guidebook.</li>'
                . '<li>A donation is a gift: you receive no goods or services in return.</li>'
                . '<li>' . self::REFUND_US . '</li>'
                . '<li>Card payments are processed securely by TBC Bank. climbing.ge never receives or stores your card details.</li>'
                . '<li>How we handle the data you enter in the donation form is described in our <a href="https://climbing.ge/privacy_policy">Privacy Policy</a>.</li>'
                . '<li>Questions: ' . $mail_link . '</li></ul>',
            '<h3>შემოწირულობის პირობები</h3>'
                . '<p>climbing.ge-ზე შემოწირულობებს იღებს <strong>' . $e($org['legal_name_ka']) . '</strong>, ' . $e($org['legal_form_ka'])
                . ', საიდენტიფიკაციო კოდი ' . $e($org['reg_number']) . ', ' . $e($org['address_ka']) . '.' . $tax_ka . '</p><ul>'
                . '<li>შემოწირულობა ნებაყოფლობითია და ხმარდება climbing.ge გზამკვლევის შენარჩუნებასა და განვითარებას.</li>'
                . '<li>შემოწირულობა არის საჩუქარი: სანაცვლოდ არ იღებთ საქონელს ან მომსახურებას.</li>'
                . '<li>' . self::REFUND_KA . '</li>'
                . '<li>ბარათით გადახდას უსაფრთხოდ ამუშავებს თიბისი ბანკი. climbing.ge არასოდეს იღებს და არ ინახავს თქვენი ბარათის მონაცემებს.</li>'
                . '<li>როგორ ვამუშავებთ შემოწირულობის ფორმაში შეყვანილ მონაცემებს, აღწერილია ჩვენს <a href="https://climbing.ge/ka/privacy_policy">კონფიდენციალურობის პოლიტიკაში</a>.</li>'
                . '<li>კითხვები: ' . $mail_link . '</li></ul>'
        );
    }

    public function down(): void
    {
        // No-op: the previous texts may have been edited in the admin and can't be restored reliably.
    }

    private function set(string $slug, string $us, string $ka): void
    {
        $values = ['us_data' => $us, 'ka_data' => $ka, 'updated_at' => now()];

        if (DB::table('locale_sites')->where('slug', $slug)->exists()) {
            DB::table('locale_sites')->where('slug', $slug)->update($values);
        } else {
            DB::table('locale_sites')->insert(array_merge($values, ['slug' => $slug, 'created_at' => now()]));
        }
    }
};
