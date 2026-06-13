<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TermsOfUseSeeder extends Seeder
{
    public function run()
    {
        // ─── PERMISSION SEEDS (comment out — uncomment in production if needed) ───
        // $this->call(PermissionTableSeeder::class);
        // $this->call(RoleTableSeeder::class);
        // ──────────────────────────────────────────────────────────────────────────

        $data = [

            // ── Guide / climbing.ge ──────────────────────────────────────────────
            [
                'slug'    => 'guide_terms_of_use',
                'us_data' => "<h2>Terms of Use &mdash; Climbing Georgia (climbing.ge)</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Terms of Use govern your access to and use of the website <strong>climbing.ge</strong> (the &ldquo;Website&rdquo;), operated by Climbing Georgia (the &ldquo;Company&rdquo;). By using this Website you agree to these Terms. If you disagree, please leave the Website.</p>

<h3>1. Legal Basis</h3>
<p>These Terms are governed by the legislation of Georgia, including:</p>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Commerce</li>
  <li>Law of Georgia on Electronic Communications</li>
  <li>Law of Georgia on Consumer Rights Protection</li>
</ul>

<h3>2. Intellectual Property</h3>
<p>All climbing route descriptions, sector information, photographs, maps, topos, and other content are the intellectual property of Climbing Georgia or its contributors. Personal, non-commercial use is permitted. Reproduction or commercial use without written consent is prohibited.</p>

<h3>3. User Accounts</h3>
<p>You must provide accurate information when registering. You are solely responsible for your account security and all activity under your account. Notify us immediately of any unauthorised access.</p>

<h3>4. User-Generated Content</h3>
<p>By submitting comments, route descriptions, or photos you grant Climbing Georgia a non-exclusive, royalty-free licence to use and display that content. You confirm that you hold all necessary rights to submit it.</p>

<h3>5. Safety Disclaimer</h3>
<p>Rock climbing and mountaineering are inherently dangerous activities. Route grades and descriptions on this Website are for informational purposes only. <strong>Climbing Georgia does not guarantee the accuracy, completeness, or current safety of any route information.</strong> You assume all risks. Always verify conditions on-site and consult qualified local guides.</p>

<h3>6. Personal Data</h3>
<p>Personal data is processed in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS). Registration constitutes consent to collect and process your name and email address for service delivery and communication. You may request access, correction, or deletion of your data at any time.</p>

<h3>7. Prohibited Use</h3>
<p>You may not: use the Website unlawfully; post false information; attempt unauthorised access; scrape data without permission; or interfere with Website operation.</p>

<h3>8. Limitation of Liability</h3>
<p>To the maximum extent permitted by Georgian law, Climbing Georgia is not liable for any direct, indirect, or consequential damages arising from use of the Website or reliance on its content.</p>

<h3>9. Changes</h3>
<p>We may update these Terms at any time. Continued use of the Website constitutes acceptance of the updated Terms.</p>

<h3>10. Governing Law &amp; Disputes</h3>
<p>These Terms are governed by the law of Georgia. Disputes shall be resolved by the courts of Georgia.</p>",

                'ka_data' => "<h2>გამოყენების პირობები &mdash; Climbing Georgia (climbing.ge)</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე გამოყენების პირობები არეგულირებს ვებ-საიტ <strong>climbing.ge</strong>-ის წვდომასა და გამოყენებას. ვებ-საიტი ოპერირებს კომპანია Climbing Georgia-ს მიერ. ვებ-საიტით სარგებლობით თქვენ ეთანხმებით წინამდებარე პირობებს.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<p>წინამდებარე პირობები რეგულირდება საქართველოს კანონმდებლობით, მათ შორის:</p>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომერციის შესახებ</li>
  <li>საქართველოს კანონი ელექტრონული კომუნიკაციების შესახებ</li>
  <li>საქართველოს კანონი მომხმარებელთა უფლებების დაცვის შესახებ</li>
</ul>

<h3>2. ინტელექტუალური საკუთრება</h3>
<p>ვებ-საიტზე განთავსებული ყველა კლდეზე ცოცვის მარშრუტის აღწერა, სექტორების ინფორმაცია, ფოტოგრაფიები, რუკები, ტოპოები და სხვა კონტენტი Climbing Georgia-სა და მისი ავტორების ინტელექტუალური საკუთრებაა. პირადი, არაკომერციული მიზნებით გამოყენება დაშვებულია. გამრავლება ან კომერციული გამოყენება წერილობითი თანხმობის გარეშე აკრძალულია.</p>

<h3>3. მომხმარებლის ანგარიში</h3>
<p>რეგისტრაციისას ვალდებული ხართ მიუთითოთ ზუსტი ინფორმაცია. თქვენ პირადად ხართ პასუხისმგებელი ანგარიშის უსაფრთხოებასა და ამ ანგარიშიდან განხორციელებულ ყველა ქმედებაზე.</p>

<h3>4. მომხმარებლის კონტენტი</h3>
<p>კომენტარების, მარშრუტების აღწერის ან ფოტოების გამოქვეყნებით თქვენ ანიჭებთ Climbing Georgia-ს არაექსკლუზიურ, უსასყიდლო ლიცენზიას ამ კონტენტის გამოყენებისა და ჩვენებისთვის.</p>

<h3>5. უსაფრთხოების შეზღუდვა</h3>
<p>კლდეზე ცოცვა და ალპინიზმი თავისი ბუნებით სახიფათო სპორტია. ვებ-საიტზე მარშრუტების სირთულე და აღწერები მხოლოდ საინფორმაციო ხასიათისაა. <strong>Climbing Georgia არ იძლევა გარანტიას მარშრუტის ინფორმაციის სიზუსტეზე, სისრულეზე ან მიმდინარე უსაფრთხოებაზე.</strong> ყოველთვის გადაამოწმეთ პირობები ადგილზე და კვალიფიციურ გიდებს მიმართეთ.</p>

<h3>6. პერსონალური მონაცემები</h3>
<p>პერსონალური მონაცემები მუშავდება საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად. რეგისტრაციით თქვენ თანხმდებით, რომ თქვენი სახელი და ელ. ფოსტა გამოყენებულ იქნეს მომსახურების მიწოდებისა და კომუნიკაციის მიზნით. ნებისმიერ დროს შეგიძლიათ მოითხოვოთ პერსონალური მონაცემების ხელმისაწვდომობა, გასწორება ან წაშლა.</p>

<h3>7. აკრძალული გამოყენება</h3>
<p>აკრძალულია: ვებ-საიტის უკანონო მიზნებისთვის გამოყენება; მცდარი ინფორმაციის გამოქვეყნება; არაავტორიზებული წვდომის მცდელობა; მონაცემების თვითნებური მოგროვება; ვებ-საიტის ნორმალური ფუნქციობის ხელშეშლა.</p>

<h3>8. პასუხისმგებლობის შეზღუდვა</h3>
<p>საქართველოს კანონმდებლობით დადგენილი მაქსიმალური ფარგლების ფარგლებში Climbing Georgia არ არის პასუხისმგებელი ვებ-საიტის გამოყენებით ან მასში განთავსებულ ინფორმაციაზე დაყრდნობით გამოწვეულ ზიანზე.</p>

<h3>9. ცვლილებები</h3>
<p>ჩვენ ვიტოვებთ ამ პირობების ნებისმიერ დროს განახლების უფლებას. ვებ-საიტის გამოყენების გაგრძელება ახალ პირობებთან თანხმობას ნიშნავს.</p>

<h3>10. მოქმედი კანონმდებლობა და დავები</h3>
<p>წინამდებარე პირობები რეგულირდება საქართველოს კანონმდებლობით. ნებისმიერი დავა განიხილება საქართველოს სასამართლოების მიერ.</p>",
            ],

            // ── Shop / shop.climbing.ge ──────────────────────────────────────────
            [
                'slug'    => 'shop_terms_of_use',
                'us_data' => "<h2>Terms of Use &amp; Purchase Conditions &mdash; Climbing Georgia Shop (shop.climbing.ge)</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Terms govern all purchases and use of the online shop at <strong>shop.climbing.ge</strong>. By placing an order you accept these Terms in full.</p>

<h3>1. Legal Basis</h3>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Law of Georgia on Consumer Rights Protection</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Commerce</li>
  <li>Tax Code of Georgia</li>
</ul>

<h3>2. Products &amp; Pricing</h3>
<p>All prices are displayed in Georgian Lari (GEL) and include applicable taxes unless stated otherwise. We reserve the right to change prices without prior notice. The price at the time of order confirmation is binding.</p>

<h3>3. Order Placement &amp; Confirmation</h3>
<p>An order constitutes an offer to purchase. A binding contract is formed only upon our written or electronic order confirmation. We reserve the right to decline any order.</p>

<h3>4. Payment</h3>
<p>Accepted payment methods are displayed at checkout. Payments are processed via secure third-party gateways. Card data is not stored on our servers.</p>

<h3>5. Delivery</h3>
<p>Estimated delivery times are provided at checkout and are not guaranteed. We are not responsible for delays caused by third-party carriers, customs, or force majeure. Risk of loss passes to you upon delivery.</p>

<h3>6. Returns &amp; Refunds (Consumer Rights)</h3>
<p>In accordance with the <strong>Law of Georgia on Consumer Rights Protection</strong>, you have the right to return a product within <strong>14 calendar days</strong> of receipt if the product is unused, undamaged, and in original packaging. Custom-made or personalised products are non-refundable. To initiate a return, contact us via the Website. Refunds are processed within 14 business days of receiving the returned item.</p>

<h3>7. Warranty</h3>
<p>Products carry a warranty as specified on the product page or as required by Georgian law. Warranty claims must be submitted within the warranty period with proof of purchase.</p>

<h3>8. Personal Data</h3>
<p>Your name, address, email, and phone number are processed solely to fulfil your order and comply with legal obligations, in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS). Data is not sold or transferred to third parties except as required for delivery.</p>

<h3>9. Limitation of Liability</h3>
<p>Our liability is limited to the purchase price of the product. We are not liable for indirect or consequential losses.</p>

<h3>10. Governing Law &amp; Disputes</h3>
<p>These Terms are governed by Georgian law. Consumer disputes may also be referred to the National Enforcement Bureau of Georgia or the relevant mediation services.</p>",

                'ka_data' => "<h2>გამოყენების პირობები და შეძენის პირობები &mdash; Climbing Georgia მაღაზია (shop.climbing.ge)</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე პირობები არეგულირებს ყველა შეძენასა და <strong>shop.climbing.ge</strong>-ის ონლაინ მაღაზიის გამოყენებას. შეკვეთის განთავსებით თქვენ სრულად ეთანხმებით ამ პირობებს.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს კანონი მომხმარებელთა უფლებების დაცვის შესახებ</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომერციის შესახებ</li>
  <li>საქართველოს საგადასახადო კოდექსი</li>
</ul>

<h3>2. პროდუქტები და ფასები</h3>
<p>ყველა ფასი ნაჩვენებია ქართულ ლარში (&lari;) და, თუ სხვა მითითება არ არის, მოიცავს გათვალისწინებულ გადასახადებს. ჩვენ ვიტოვებთ წინასწარი შეტყობინების გარეშე ფასების შეცვლის უფლებას. შეკვეთის დადასტურების მომენტში მითითებული ფასია სავალდებულო.</p>

<h3>3. შეკვეთის განთავსება და დადასტურება</h3>
<p>შეკვეთა წარმოადგენს შეძენის შეთავაზებას. სავალდებულო ხელშეკრულება იდება მხოლოდ ჩვენი წერილობითი ან ელექტრონული დადასტურების შემდეგ. ვიტოვებთ ნებისმიერი შეკვეთის გაუქმების უფლებას.</p>

<h3>4. გადახდა</h3>
<p>გადახდის ხელმისაწვდომი მეთოდები ნაჩვენებია გადახდის გვერდზე. გადახდები მუშავდება უსაფრთხო მესამე მხარის სისტემების მეშვეობით. ბარათის მონაცემები არ ინახება ჩვენს სერვერებზე.</p>

<h3>5. მიწოდება</h3>
<p>მიწოდების სავარაუდო ვადა ნაჩვენებია შეკვეთის გვერდზე და არ წარმოადგენს გარანტიას. ჩვენ არ ვართ პასუხისმგებელი მესამე მხარის კურიერული სამსახურის, საბაჟოს ან ფორსმაჟორის გამო გამოწვეულ დაგვიანებებზე.</p>

<h3>6. დაბრუნება და თანხის უკან დაბრუნება (მომხმარებლის უფლებები)</h3>
<p>საქართველოს კანონის <strong>&ldquo;მომხმარებელთა უფლებების დაცვის შესახებ&rdquo;</strong> შესაბამისად, თქვენ გაქვთ უფლება დააბრუნოთ პროდუქტი მიღებიდან <strong>14 კალენდარული დღის</strong> განმავლობაში, თუ ის არ არის გამოყენებული, დაზიანებული და ინახება ორიგინალ შეფუთვაში. ინდივიდუალური შეკვეთით დამზადებული ან პერსონალიზებული პროდუქტები არ ექვემდებარება დაბრუნებას.</p>

<h3>7. გარანტია</h3>
<p>პროდუქტები ახლავს გარანტია, როგორც მითითებულია პროდუქტის გვერდზე ან საქართველოს კანონმდებლობის მოთხოვნით. გარანტიის პრეტენზია უნდა წარმოდგინდეს გარანტიის ვადის განმავლობაში შეძენის დამადასტურებელი დოკუმენტით.</p>

<h3>8. პერსონალური მონაცემები</h3>
<p>თქვენი სახელი, მისამართი, ელ. ფოსტა და ტელეფონის ნომერი გამოიყენება მხოლოდ შეკვეთის შესასრულებლად საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად. მონაცემები არ იყიდება ან გადაეცემა მესამე მხარეს, გარდა მიწოდებისთვის საჭირო შემთხვევებისა.</p>

<h3>9. პასუხისმგებლობის შეზღუდვა</h3>
<p>ჩვენი პასუხისმგებლობა შემოიფარგლება პროდუქტის შეძენის ფასით. ჩვენ არ ვართ პასუხისმგებელი არაპირდაპირ ან შედეგობრივ ზიანზე.</p>

<h3>10. მოქმედი კანონმდებლობა და დავები</h3>
<p>ეს პირობები რეგულირდება საქართველოს კანონმდებლობით. სამომხმარებლო დავები ასევე შეიძლება განხილულ იქნეს საქართველოს ეროვნული აღსრულების ბიუროში ან შესაბამის მედიაციის სამსახურებში.</p>",
            ],

            // ── Blog / blog.climbing.ge ──────────────────────────────────────────
            [
                'slug'    => 'blog_terms_of_use',
                'us_data' => "<h2>Terms of Use &mdash; Climbing Georgia Blog (blog.climbing.ge)</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Terms govern your use of the blog at <strong>blog.climbing.ge</strong>. By reading or interacting with this blog you agree to these Terms.</p>

<h3>1. Legal Basis</h3>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Commerce</li>
  <li>Law of Georgia on Copyright and Related Rights</li>
</ul>

<h3>2. Content &amp; Copyright</h3>
<p>All blog posts, articles, photographs, and media are the intellectual property of Climbing Georgia or the respective named authors. Content may be shared with a link to the original source. Full reproduction or commercial use requires written permission.</p>

<h3>3. Comments &amp; Community Standards</h3>
<p>Comments are moderated. You agree not to post: defamatory, threatening, harassing, or offensive content; spam or commercial solicitation; content that violates Georgian law or third-party rights. We reserve the right to remove any comment and ban users who violate these standards.</p>

<h3>4. Accuracy of Information</h3>
<p>Blog content reflects the opinion and experience of the authors. It is not professional advice. We do not guarantee the accuracy or completeness of any information published.</p>

<h3>5. Personal Data</h3>
<p>Comment submissions may collect your name and email address, processed in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS) for moderation and anti-spam purposes only.</p>

<h3>6. Limitation of Liability</h3>
<p>Climbing Georgia is not liable for any loss arising from reliance on blog content. External links are provided for convenience only; we do not endorse or control linked websites.</p>

<h3>7. Governing Law</h3>
<p>These Terms are governed by the law of Georgia. Disputes are subject to the jurisdiction of Georgian courts.</p>",

                'ka_data' => "<h2>გამოყენების პირობები &mdash; Climbing Georgia ბლოგი (blog.climbing.ge)</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე პირობები არეგულირებს <strong>blog.climbing.ge</strong>-ის ბლოგის გამოყენებას. ბლოგთან ინტერაქციით თქვენ ეთანხმებით ამ პირობებს.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომერციის შესახებ</li>
  <li>საქართველოს კანონი საავტორო და მომიჯნავე უფლებების შესახებ</li>
</ul>

<h3>2. კონტენტი და საავტორო უფლებები</h3>
<p>ყველა ბლოგ-პოსტი, სტატია, ფოტოგრაფია და მედია Climbing Georgia-სა ან შესაბამისი მითითებული ავტორის ინტელექტუალური საკუთრებაა. კონტენტის გაზიარება დასაშვებია ორიგინალ წყაროზე ბმულის მითითებით. სრული გამრავლება ან კომერციული გამოყენება მოითხოვს წერილობით ნებართვას.</p>

<h3>3. კომენტარები და საზოგადოების სტანდარტები</h3>
<p>კომენტარები გადის მოდერაციას. ეკრძალება: დამამცირებელი, მომბეზრებელი, შევიწროებული ან შეურაცხმყოფელი კონტენტის გამოქვეყნება; სპამი ან კომერციული სოლიციტაცია; საქართველოს კანონმდებლობის ან მესამე მხარის უფლებების დარღვევა. ჩვენ ვიტოვებთ ნებისმიერი კომენტარის წაშლის უფლებას.</p>

<h3>4. ინფორმაციის სიზუსტე</h3>
<p>ბლოგ-კონტენტი ასახავს ავტორების პირად მოსაზრებებსა და გამოცდილებებს. ეს არ წარმოადგენს პროფესიულ კონსულტაციას. ჩვენ არ ვიძლევით გარანტიას გამოქვეყნებული ინფორმაციის სიზუსტეზე ან სისრულეზე.</p>

<h3>5. პერსონალური მონაცემები</h3>
<p>კომენტარების განთავსებისას შეიძლება შეგროვდეს თქვენი სახელი და ელ. ფოსტა, რომლებიც მუშავდება საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად მხოლოდ მოდერაციისა და სპამის საწინააღმდეგო მიზნებისთვის.</p>

<h3>6. პასუხისმგებლობის შეზღუდვა</h3>
<p>Climbing Georgia არ არის პასუხისმგებელი ბლოგ-კონტენტზე დაყრდნობით გამოწვეულ ნებისმიერ ზიანზე. გარე ბმულები მოწოდებულია მხოლოდ სარგებლობის მიზნით; ჩვენ არ ვუჭერთ მხარს და არ ვაკონტროლებთ მიბმულ ვებ-საიტებს.</p>

<h3>7. მოქმედი კანონმდებლობა</h3>
<p>ეს პირობები რეგულირდება საქართველოს კანონმდებლობით. დავები განიხილება საქართველოს სასამართლოების მიერ.</p>",
            ],

            // ── Films / films.climbing.ge ────────────────────────────────────────
            [
                'slug'    => 'films_terms_of_use',
                'us_data' => "<h2>Terms of Use &mdash; Climbing Georgia Films (films.climbing.ge)</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Terms govern your use of the films portal at <strong>films.climbing.ge</strong>. By accessing or streaming content you agree to these Terms.</p>

<h3>1. Legal Basis</h3>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Law of Georgia on Copyright and Related Rights</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Communications</li>
</ul>

<h3>2. Intellectual Property &amp; Licensing</h3>
<p>All films, videos, and media content are protected by the <strong>Law of Georgia on Copyright and Related Rights</strong>. All rights are reserved. Streaming is permitted for personal, non-commercial viewing only. Downloading, copying, redistribution, or public showing without prior written authorisation is strictly prohibited.</p>

<h3>3. Content Standards</h3>
<p>Content on this portal depicts extreme outdoor sports. Viewing certain content may be unsuitable for minors. We do not accept responsibility for how viewers interpret or act upon content shown.</p>

<h3>4. User Uploads</h3>
<p>If you submit a film or video for publication, you represent that you own or have all necessary rights to the content and that it does not infringe any third-party rights. You grant Climbing Georgia a non-exclusive licence to host and stream the content on this portal.</p>

<h3>5. Personal Data</h3>
<p>Viewing activity may be logged for analytics and service improvement purposes, in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS). No personally identifiable data is sold.</p>

<h3>6. Limitation of Liability</h3>
<p>Climbing Georgia is not liable for any loss arising from viewing content on this portal. Extreme sport activities depicted are inherently dangerous and should not be attempted without proper training and equipment.</p>

<h3>7. Governing Law</h3>
<p>These Terms are governed by Georgian law. Disputes are subject to the jurisdiction of Georgian courts.</p>",

                'ka_data' => "<h2>გამოყენების პირობები &mdash; Climbing Georgia ფილმები (films.climbing.ge)</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე პირობები არეგულირებს <strong>films.climbing.ge</strong>-ის ფილმების პორტალის გამოყენებას. კონტენტზე წვდომით ან სტრიმინგით თქვენ ეთანხმებით ამ პირობებს.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს კანონი საავტორო და მომიჯნავე უფლებების შესახებ</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომუნიკაციების შესახებ</li>
</ul>

<h3>2. ინტელექტუალური საკუთრება და ლიცენზია</h3>
<p>ყველა ფილმი, ვიდეო და მედია-კონტენტი დაცულია <strong>საქართველოს კანონით &ldquo;საავტორო და მომიჯნავე უფლებების შესახებ&rdquo;</strong>. ყველა უფლება დაცულია. სტრიმინგი დასაშვებია მხოლოდ პირადი, არაკომერციული ნახვის მიზნით. ჩამოტვირთვა, კოპირება, გავრცელება ან საჯარო ჩვენება წინასწარი წერილობითი ნებართვის გარეშე მკაცრად აკრძალულია.</p>

<h3>3. კონტენტის სტანდარტები</h3>
<p>ამ პორტალზე კონტენტი ასახავს ექსტრემალური გარე სპორტის სახეობებს. გარკვეული კონტენტი შეიძლება შეუფერებელი იყოს არასრულწლოვნებისთვის. ჩვენ პასუხს არ ვაგებთ მაყურებლის მიერ კონტენტის ინტერპრეტაციაზე.</p>

<h3>4. მომხმარებლის ატვირთვები</h3>
<p>ფილმის ან ვიდეოს გამოქვეყნებისთვის გამოგზავნისას ადასტურებთ, რომ ფლობთ ან გაქვთ კონტენტზე ყველა საჭირო უფლება და ის არ არღვევს მესამე მხარის უფლებებს. თქვენ ანიჭებთ Climbing Georgia-ს არაექსკლუზიურ ლიცენზიას კონტენტის ამ პორტალზე განთავსებისა და სტრიმინგისთვის.</p>

<h3>5. პერსონალური მონაცემები</h3>
<p>ნახვის აქტივობა შეიძლება ჩაიწეროს ანალიტიკის მიზნებისთვის, საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად. პირადი მონაცემები არ იყიდება.</p>

<h3>6. პასუხისმგებლობის შეზღუდვა</h3>
<p>Climbing Georgia არ არის პასუხისმგებელი ამ პორტალზე კონტენტის ყურებით გამოწვეულ ნებისმიერ ზიანზე. ნაჩვენები ექსტრემალური სპორტის სახეობები თავისი ბუნებით სახიფათოა.</p>

<h3>7. მოქმედი კანონმდებლობა</h3>
<p>ეს პირობები რეგულირდება საქართველოს კანონმდებლობით. დავები განიხილება საქართველოს სასამართლოების მიერ.</p>",
            ],

            // ── Summit / summit.climbing.ge ──────────────────────────────────────
            [
                'slug'    => 'summit_terms_of_use',
                'us_data' => "<h2>Terms of Use &mdash; Climbing Georgia Summit (summit.climbing.ge)</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Terms govern your use of the summit tracking platform at <strong>summit.climbing.ge</strong>. By submitting ascents or using this platform you agree to these Terms.</p>

<h3>1. Legal Basis</h3>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Commerce</li>
</ul>

<h3>2. Ascent Records</h3>
<p>Summit ascent records submitted by users are self-reported. Climbing Georgia does not verify ascent claims and makes no representation as to their accuracy. Submitting false ascent records is prohibited and may result in account suspension.</p>

<h3>3. Safety &amp; Risk Disclaimer</h3>
<p>Mountaineering and high-altitude climbing are extremely dangerous activities. Route information, summit descriptions, and ascent records on this platform are for informational purposes only. <strong>Climbing Georgia accepts no responsibility for injury, death, or property damage arising from mountaineering activities.</strong> Always engage certified mountain guides, obtain appropriate permits, carry emergency equipment, and check official Georgian mountain rescue services before attempting any ascent.</p>

<h3>4. User Accounts &amp; Data</h3>
<p>Your ascent history and profile data are processed in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS). Your publicly visible ascent records may be displayed on summit pages. You may request deletion of your records at any time.</p>

<h3>5. Content Accuracy</h3>
<p>Summit altitudes, route difficulties, and approach information may change. Always use the most recent local sources and official Georgian topographic data. We are not liable for any inaccuracies in the published information.</p>

<h3>6. Prohibited Activities</h3>
<p>Submitting fraudulent ascents, misrepresenting route conditions, or using this platform to incite reckless climbing is prohibited.</p>

<h3>7. Governing Law</h3>
<p>These Terms are governed by Georgian law. Disputes are subject to the jurisdiction of Georgian courts.</p>",

                'ka_data' => "<h2>გამოყენების პირობები &mdash; Climbing Georgia სამიტი (summit.climbing.ge)</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე პირობები არეგულირებს <strong>summit.climbing.ge</strong>-ის სამიტების ტრეკინგ-პლატფორმის გამოყენებას. ასვლების შეტანით ან ამ პლატფორმის გამოყენებით თქვენ ეთანხმებით ამ პირობებს.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომერციის შესახებ</li>
</ul>

<h3>2. ასვლის ჩანაწერები</h3>
<p>მომხმარებლების მიერ შეტანილი სამიტის ასვლის ჩანაწერები თვითმოხსენებული ინფორმაციაა. Climbing Georgia არ ამოწმებს ასვლის განცხადებებს. მცდარი ასვლის ჩანაწერების შეტანა აკრძალულია და შეიძლება გამოიწვიოს ანგარიშის შეჩერება.</p>

<h3>3. უსაფრთხოებისა და რისკის შესახებ</h3>
<p>მთათმსვლელობა და მაღალმთიანი ცოცვა უკიდურესად სახიფათო სახეობებია. ამ პლატფორმაზე ინფორმაცია მხოლოდ საინფორმაციო ხასიათისაა. <strong>Climbing Georgia არ არის პასუხისმგებელი მთათმსვლელობის საქმიანობით გამოწვეულ დაშავებაზე, სიკვდილზე ან ქონების ზიანზე.</strong> ყოველი ასვლამდე სავალდებულოა სერტიფიცირებული გიდის მომსახურება, შესაბამისი ნებართვები და საქართველოს მთის სამაშველო სამსახურთან კონსულტაცია.</p>

<h3>4. მომხმარებლის ანგარიში და მონაცემები</h3>
<p>თქვენი ასვლების ისტორია და პროფილის მონაცემები მუშავდება საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად. ნებისმიერ დროს შეგიძლიათ მოითხოვოთ თქვენი ჩანაწერების წაშლა.</p>

<h3>5. ინფორმაციის სიზუსტე</h3>
<p>სამიტის სიმაღლეები, მარშრუტის სირთულე და მიახლოების ინფორმაცია შეიძლება შეიცვალოს. ყოველთვის გამოიყენეთ ყველაზე განახლებული ადგილობრივი წყაროები. ჩვენ პასუხს არ ვაგებთ გამოქვეყნებული ინფორმაციის შეუსაბამობებზე.</p>

<h3>6. აკრძალული ქმედებები</h3>
<p>აკრძალულია მცდარი ასვლის ჩანაწერების შეტანა, მარშრუტის პირობების შეუსაბამო წარმოდგენა ან გაუფრთხილებელი მთათმსვლელობის წახალისება.</p>

<h3>7. მოქმედი კანონმდებლობა</h3>
<p>ეს პირობები რეგულირდება საქართველოს კანონმდებლობით. დავები განიხილება საქართველოს სასამართლოების მიერ.</p>",
            ],

            // ── User Portal / user.climbing.ge ───────────────────────────────────
            [
                'slug'    => 'user_terms_of_use',
                'us_data' => "<h2>Terms of Use &mdash; Climbing Georgia User Portal (user.climbing.ge)</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Terms govern your use of the user portal at <strong>user.climbing.ge</strong>, including account management, dashboard features, and contributor tools.</p>

<h3>1. Legal Basis</h3>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Commerce</li>
  <li>Law of Georgia on Electronic Communications</li>
</ul>

<h3>2. Account Registration &amp; Security</h3>
<p>You must be at least 16 years of age to create an account. You must provide accurate, current, and complete registration information. You are responsible for all activity that occurs under your account. You must not share your credentials or allow others to use your account.</p>

<h3>3. Contributor Responsibilities</h3>
<p>Users with contributor or editor permissions are responsible for the accuracy and legality of all content they publish. Content must not violate Georgian law, third-party rights, or Community Guidelines. Climbing Georgia reserves the right to edit, withhold, or remove content at its discretion.</p>

<h3>4. Account Suspension &amp; Termination</h3>
<p>We may suspend or terminate your account without notice if you: violate these Terms; engage in fraudulent activity; abuse other users; or repeatedly post content that violates Community Guidelines. Banned users will receive a notification and may appeal via the contact form.</p>

<h3>5. Personal Data</h3>
<p>Account data (name, email, activity history) is processed in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS). You may access, correct, or request deletion of your data through your account settings or by contacting us.</p>

<h3>6. Intellectual Property</h3>
<p>Content you create and publish through this portal remains your intellectual property, subject to the licence granted in the respective site terms. You retain the right to request removal of your content at any time.</p>

<h3>7. Limitation of Liability</h3>
<p>Climbing Georgia is not liable for loss of data, unauthorised access resulting from failure to maintain account security, or any other indirect damages.</p>

<h3>8. Governing Law</h3>
<p>These Terms are governed by Georgian law. Disputes are subject to the jurisdiction of Georgian courts.</p>",

                'ka_data' => "<h2>გამოყენების პირობები &mdash; Climbing Georgia მომხმარებლის პორტალი (user.climbing.ge)</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე პირობები არეგულირებს <strong>user.climbing.ge</strong>-ის მომხმარებლის პორტალის გამოყენებას, მათ შორის ანგარიშის მართვას, დაფის ფუნქციებს და კონტრიბუტორის ინსტრუმენტებს.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომერციის შესახებ</li>
  <li>საქართველოს კანონი ელექტრონული კომუნიკაციების შესახებ</li>
</ul>

<h3>2. ანგარიშის რეგისტრაცია და უსაფრთხოება</h3>
<p>ანგარიშის შესაქმნელად თქვენ უნდა გქონდეთ მინიმუმ 16 წელი. ვალდებული ხართ მიუთითოთ ზუსტი, მიმდინარე და სრული სარეგისტრაციო ინფორმაცია. თქვენ პირადად ხართ პასუხისმგებელი ანგარიშიდან განხორციელებულ ყველა ქმედებაზე. ანგარიშის სერთიფიკატების სხვა პირთან გაზიარება აკრძალულია.</p>

<h3>3. კონტრიბუტორის პასუხისმგებლობა</h3>
<p>კონტრიბუტორის ან რედაქტორის უფლებების მქონე მომხმარებლები პასუხისმგებელნი არიან გამოქვეყნებული კონტენტის სიზუსტეზე და კანონიერებაზე. კონტენტი არ უნდა არღვევდეს საქართველოს კანონმდებლობას ან მესამე მხარის უფლებებს. Climbing Georgia იტოვებს კონტენტის რედაქტირების, შეჩერების ან წაშლის უფლებას.</p>

<h3>4. ანგარიშის შეჩერება და გაუქმება</h3>
<p>ჩვენ შეგვიძლია გაფრთხილების გარეშე შეჩეროთ ან გავაუქმოთ თქვენი ანგარიში, თუ: დაარღვევთ ამ პირობებს; ჩაერთვებით თაღლითურ საქმიანობაში; სხვა მომხმარებლებს შეულახავთ; ან განმეორებით გამოაქვეყნებთ პირობების დამრღვევ კონტენტს. დაბლოკილი მომხმარებლები მიიღებენ შეტყობინებას.</p>

<h3>5. პერსონალური მონაცემები</h3>
<p>ანგარიშის მონაცემები (სახელი, ელ. ფოსტა, აქტივობის ისტორია) მუშავდება საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად. ანგარიშის პარამეტრების მეშვეობით შეგიძლიათ მოიძიოთ, გაასწოროთ ან მოითხოვოთ მონაცემების წაშლა.</p>

<h3>6. ინტელექტუალური საკუთრება</h3>
<p>ამ პორტალის მეშვეობით შექმნილი კონტენტი რჩება თქვენი ინტელექტუალური საკუთრება, შესაბამისი საიტის პირობებით მინიჭებული ლიცენზიის გათვალისწინებით. ნებისმიერ დროს გაქვთ კონტენტის წაშლის მოთხოვნის უფლება.</p>

<h3>7. პასუხისმგებლობის შეზღუდვა</h3>
<p>Climbing Georgia არ არის პასუხისმგებელი მონაცემების დაკარგვაზე, ანგარიშის უსაფრთხოების შენარჩუნების უგულებელყოფის შედეგად განხორციელებულ არაავტორიზებულ წვდომაზე, ან სხვა არაპირდაპირ ზიანზე.</p>

<h3>8. მოქმედი კანონმდებლობა</h3>
<p>ეს პირობები რეგულირდება საქართველოს კანონმდებლობით. დავები განიხილება საქართველოს სასამართლოების მიერ.</p>",
            ],

            // ── Donation Terms (guide section) ───────────────────────────────────
            [
                'slug'    => 'donation_terms_of_use',
                'us_data' => "<h2>Donation Terms &mdash; Climbing Georgia</h2>
<p><strong>Effective date: June 2026</strong></p>
<p>These Donation Terms apply to all voluntary financial contributions (&ldquo;donations&rdquo;) made to Climbing Georgia through the Website or any of its subdomains.</p>

<h3>1. Legal Basis</h3>
<ul>
  <li>Civil Code of Georgia</li>
  <li>Tax Code of Georgia</li>
  <li>Law of Georgia on Personal Data Protection (No. 5669-RS)</li>
  <li>Law of Georgia on Electronic Commerce</li>
</ul>

<h3>2. Voluntary &amp; Non-Refundable</h3>
<p>All donations are <strong>strictly voluntary and non-refundable</strong>. By completing a donation payment you acknowledge that the contribution is a gift and you waive any right to a refund, chargeback, or reversal except as required by mandatory Georgian law or payment processor policy in cases of demonstrably fraudulent transactions.</p>

<h3>3. Use of Funds</h3>
<p>Donations are used exclusively to support the development, maintenance, and improvement of the Climbing Georgia platforms and the publication of free climbing route information. Funds are not distributed to third parties for unrelated purposes.</p>

<h3>4. Tax</h3>
<p>Donations may or may not be tax-deductible depending on your personal tax situation under the Tax Code of Georgia. Climbing Georgia does not provide tax advice. Please consult a qualified tax professional.</p>

<h3>5. Personal Data</h3>
<p>Donor name, email, and transaction data are collected solely to process the donation and provide a receipt, in accordance with the Law of Georgia on Personal Data Protection (No. 5669-RS). Payment card data is processed exclusively by our payment gateway and is never stored on our servers.</p>

<h3>6. Failed Transactions</h3>
<p>If a payment fails after your bank or card has been charged, contact us immediately via the Website contact form. We will investigate and, if confirmed, arrange a refund within 14 business days.</p>

<h3>7. Governing Law</h3>
<p>These Donation Terms are governed by Georgian law. Any disputes shall be subject to the exclusive jurisdiction of the courts of Georgia.</p>",

                'ka_data' => "<h2>შემოწირულობის პირობები &mdash; Climbing Georgia</h2>
<p><strong>ამოქმედების თარიღი: ივნისი 2026</strong></p>
<p>წინამდებარე შემოწირულობის პირობები ვრცელდება Climbing Georgia-სთვის ვებ-საიტის ან მისი ნებისმიერი სუბდომენის მეშვეობით განხორციელებულ ყველა ნებაყოფლობით ფინანსურ შენატანზე.</p>

<h3>1. სამართლებრივი საფუძველი</h3>
<ul>
  <li>საქართველოს სამოქალაქო კოდექსი</li>
  <li>საქართველოს საგადასახადო კოდექსი</li>
  <li>საქართველოს კანონი პერსონალური მონაცემების დაცვის შესახებ (№5669-რს)</li>
  <li>საქართველოს კანონი ელექტრონული კომერციის შესახებ</li>
</ul>

<h3>2. ნებაყოფლობითი და დაუბრუნებელი</h3>
<p>ყველა შემოწირულობა <strong>მკაცრად ნებაყოფლობითი და დაუბრუნებელია</strong>. შემოწირულობის გადახდის დასრულებით თქვენ ადასტურებთ, რომ შენატანი წარმოადგენს ნიჭს და უარს ამბობთ თანხის დაბრუნების ნებისმიერ მოთხოვნაზე, გარდა საქართველოს სავალდებულო კანონმდებლობით ან გადამხდელი სისტემის პოლიტიკით გათვალისწინებული გამონაკლისი შემთხვევებისა.</p>

<h3>3. სახსრების გამოყენება</h3>
<p>შემოწირულობები გამოიყენება ექსკლუზიურად Climbing Georgia-ს პლატფორმების განვითარების, მოვლის და გაუმჯობესების, ასევე ცოცვის მარშრუტების უფასო ინფორმაციის გამოქვეყნების მხარდასაჭერად. სახსრები არ გადაეცემა მესამე მხარეს არასაკუთრებელი მიზნებისთვის.</p>

<h3>4. გადასახადი</h3>
<p>შემოწირულობები შეიძლება ან არ ექვემდებარებოდეს საგადასახადო გამოქვითვას საქართველოს საგადასახადო კოდექსის შესაბამისად. Climbing Georgia არ გასცემს საგადასახადო კონსულტაციებს. კვალიფიციურ საგადასახადო სპეციალისტს მიმართეთ.</p>

<h3>5. პერსონალური მონაცემები</h3>
<p>მომწირველის სახელი, ელ. ფოსტა და ტრანზაქციის მონაცემები გროვდება მხოლოდ შემოწირულობის დამუშავებისა და ქვითრის გაგზავნის მიზნით, საქართველოს კანონის &ldquo;პერსონალური მონაცემების დაცვის შესახებ&rdquo; (№5669-რს) შესაბამისად. ბარათის მონაცემები მუშავდება ექსკლუზიურად გადახდის სისტემის მიერ და არასდროს ინახება ჩვენს სერვერებზე.</p>

<h3>6. წარუმატებელი ტრანზაქციები</h3>
<p>თუ გადახდა ჩავარდება ბანკის ჩამოჭრის შემდეგ, დაუყოვნებლივ დაგვიკავშირდით ვებ-საიტის საკონტაქტო ფორმის მეშვეობით. ჩვენ გამოვიძიებთ და, დადასტურების შემთხვევაში, 14 სამუშაო დღის განმავლობაში განვახორციელებთ თანხის დაბრუნებას.</p>

<h3>7. მოქმედი კანონმდებლობა</h3>
<p>ეს შემოწირულობის პირობები რეგულირდება საქართველოს კანონმდებლობით. ნებისმიერი დავა ექვემდებარება საქართველოს სასამართლოების ექსკლუზიურ იურისდიქციას.</p>",
            ],

        ];

        foreach ($data as $record) {
            DB::table('locale_sites')
                ->updateOrInsert(
                    ['slug' => $record['slug']],
                    array_merge($record, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
        }
    }
}
