<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Adds the privacy_policy site-data item (locale_sites) shown on /privacy_policy of
 * every subdomain and in the registration modal (LegalDocumentComponent.vue).
 * Inserted only if missing, so text edited later in the admin site-info page is
 * never overwritten. {contact_email} is filled from the site's contact email (sites.email).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('locale_sites')->where('slug', 'privacy_policy')->exists()) {
            return;
        }

        $contact_email = DB::table('sites')->value('email') ?? '';

        DB::table('locale_sites')->insert([
            'slug'       => 'privacy_policy',
            'us_data'    => str_replace('{contact_email}', $contact_email, $this->us_data()),
            'ka_data'    => str_replace('{contact_email}', $contact_email, $this->ka_data()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('locale_sites')->where('slug', 'privacy_policy')->delete();
    }

    private function us_data(): string
    {
        return <<<'HTML'
<h2>Privacy Policy</h2>
<p>This policy explains what personal data climbing.ge collects on its website and subdomains and in the climbing.ge mobile training app (the "Service"), why, and what your rights are.</p>

<h3>1. Data we collect</h3>
<ul>
<li><strong>Account data</strong> — name, surname, email, phone number, country, city, preferred language, password (stored only as a secure hash), optional profile photo, bio and social links.</li>
<li><strong>Social login</strong> — if you sign in with Google or Facebook we receive your name, email, profile photo and provider account id.</li>
<li><strong>Content you publish</strong> — comments, route and product reviews, summit and multi-pitch ascents (date, route, comment, photo), favorites and follows.</li>
<li><strong>Location</strong> — only when you record a summit ascent and allow location access, your GPS coordinates at that moment are used to verify that you reached the summit and are stored with the ascent. Archive ascents store no location.</li>
<li><strong>Orders and donations</strong> — delivery address, contact details, order contents and payment status. Card payments are processed by our payment providers (TBC Bank, Flitt); we never receive or store your card details.</li>
<li><strong>Training app data</strong> — if you log in in the mobile app, your custom workouts, training plan progress and workout history are synced to your account. Reminder notifications are scheduled on your device.</li>
<li><strong>Technical data</strong> — login tokens and preferences stored in your browser or device, server logs (IP address, browser type) kept for security, and anonymous usage statistics (Google Analytics). Google reCAPTCHA is used on forms to block spam and may process device and usage data under Google's privacy policy.</li>
</ul>

<h3 id="donations">Donations</h3>
<p>When you donate, we collect what you enter in the donation form — name, surname, email, phone number, country, age and an optional comment — together with the amount, currency and payment status. We use it to process and record your donation and to answer questions about it; if you are logged in, or later register with the same email, the donation is linked to your account. Card payments are handled only by TBC Bank: climbing.ge never receives or stores your card details. Donation records are kept for accounting and legal obligations, also after you delete your account.</p>

<h3>2. How we use it</h3>
<p>To create and run your account, show your public climber profile and published content, process orders and donations, send the emails and notifications you enable, sync training data between your devices, prevent spam and abuse, and improve the Service. We do not sell your personal data.</p>

<h3>3. What is public</h3>
<p>Your name, surname, profile photo, published comments, reviews and ascents are visible to other visitors. Your email, phone number and address are never shown publicly.</p>

<h3>4. Sharing</h3>
<p>We share data only with service providers needed to run the Service: hosting, email delivery, payment providers, delivery partners for shop orders, Google (Analytics, reCAPTCHA, sign-in) and Facebook (sign-in), or when required by law.</p>

<h3>5. Retention and deletion</h3>
<p>We keep your data while your account exists. You can delete your account at any time on the <a href="https://user.climbing.ge/delete-account">account deletion page</a> or in the mobile app. Deletion is immediate and removes your profile, logins, addresses, favorites, follows, training data, comments, reviews and ascents. Records of past orders and donations are kept for accounting and legal obligations, no longer linked to any account.</p>

<h3>6. Your rights</h3>
<p>You can view and edit your data in your account settings, ask for a copy of it, ask us to correct or delete it, and turn off notification emails. Contact us using the email below.</p>

<h3>7. Children</h3>
<p>The Service is not directed at children under 13, and we do not knowingly collect their data.</p>

<h3>8. Changes</h3>
<p>We may update this policy. The current version is always available on this page.</p>

<h3>9. Contact</h3>
<p>Privacy questions and requests: <a href="mailto:{contact_email}">{contact_email}</a></p>
HTML;
    }

    private function ka_data(): string
    {
        return <<<'HTML'
<h2>კონფიდენციალურობის პოლიტიკა</h2>
<p>ეს პოლიტიკა განმარტავს, რა პერსონალურ მონაცემებს აგროვებს climbing.ge თავის ვებსაიტზე, ქვედომენებსა და climbing.ge სავარჯიშო მობილურ აპლიკაციაში („სერვისი“), რა მიზნით და რა უფლებები გაქვთ.</p>

<h3>1. რა მონაცემებს ვაგროვებთ</h3>
<ul>
<li><strong>ანგარიშის მონაცემები</strong> — სახელი, გვარი, ელფოსტა, ტელეფონის ნომერი, ქვეყანა, ქალაქი, სასურველი ენა, პაროლი (ინახება მხოლოდ დაშიფრული ჰეშის სახით), სურვილისამებრ პროფილის ფოტო, ბიოგრაფია და სოციალური ბმულები.</li>
<li><strong>სოციალური ავტორიზაცია</strong> — Google-ით ან Facebook-ით შესვლისას ვიღებთ თქვენს სახელს, ელფოსტას, პროფილის ფოტოს და პროვაიდერის ანგარიშის იდენტიფიკატორს.</li>
<li><strong>თქვენ მიერ გამოქვეყნებული კონტენტი</strong> — კომენტარები, მარშრუტებისა და პროდუქტების შეფასებები, მწვერვალებზე და მრავალპიჩიან მარშრუტებზე ასვლები (თარიღი, მარშრუტი, კომენტარი, ფოტო), რჩეულები და გამოწერები.</li>
<li><strong>მდებარეობა</strong> — მხოლოდ მწვერვალზე ასვლის დაფიქსირებისას და თქვენი ნებართვით, იმ მომენტის GPS კოორდინატები გამოიყენება მწვერვალზე ყოფნის დასადასტურებლად და ინახება ასვლასთან ერთად. არქივის ასვლები მდებარეობას არ ინახავს.</li>
<li><strong>შეკვეთები და შემოწირულობები</strong> — მიწოდების მისამართი, საკონტაქტო მონაცემები, შეკვეთის შემადგენლობა და გადახდის სტატუსი. ბარათით გადახდებს ამუშავებენ ჩვენი საგადახდო პროვაიდერები (თიბისი ბანკი, Flitt); ჩვენ არასოდეს ვიღებთ და არ ვინახავთ ბარათის მონაცემებს.</li>
<li><strong>სავარჯიშო აპლიკაციის მონაცემები</strong> — მობილურ აპლიკაციაში შესვლისას თქვენი ვარჯიშები, სავარჯიშო გეგმების პროგრესი და ვარჯიშების ისტორია სინქრონიზდება თქვენს ანგარიშთან. შეხსენებები იგეგმება თქვენს მოწყობილობაზე.</li>
<li><strong>ტექნიკური მონაცემები</strong> — ავტორიზაციის ტოკენები და პარამეტრები თქვენს ბრაუზერში ან მოწყობილობაზე, სერვერის ჟურნალები (IP მისამართი, ბრაუზერის ტიპი) უსაფრთხოებისთვის და ანონიმური გამოყენების სტატისტიკა (Google Analytics). ფორმებზე სპამის თავიდან ასაცილებლად გამოიყენება Google reCAPTCHA, რომელმაც შეიძლება დაამუშაოს მოწყობილობისა და გამოყენების მონაცემები Google-ის კონფიდენციალურობის პოლიტიკის შესაბამისად.</li>
</ul>

<h3 id="donations">შემოწირულობები</h3>
<p>შემოწირულობისას ვაგროვებთ იმას, რასაც შემოწირულობის ფორმაში შეიყვანთ — სახელს, გვარს, ელფოსტას, ტელეფონის ნომერს, ქვეყანას, ასაკს და სურვილისამებრ კომენტარს — ასევე თანხას, ვალუტას და გადახდის სტატუსს. ამ მონაცემებს ვიყენებთ შემოწირულობის დასამუშავებლად და აღსარიცხად, ასევე მასთან დაკავშირებულ კითხვებზე პასუხის გასაცემად; თუ სისტემაში შესული ხართ ან მოგვიანებით იმავე ელფოსტით დარეგისტრირდებით, შემოწირულობა დაუკავშირდება თქვენს ანგარიშს. ბარათით გადახდას ამუშავებს მხოლოდ თიბისი ბანკი: climbing.ge არასოდეს იღებს და არ ინახავს თქვენი ბარათის მონაცემებს. შემოწირულობების ჩანაწერები ინახება ბუღალტრული და სამართლებრივი ვალდებულებების გამო, მათ შორის ანგარიშის წაშლის შემდეგაც.</p>

<h3>2. როგორ ვიყენებთ</h3>
<p>ანგარიშის შესაქმნელად და სამართავად, თქვენი საჯარო პროფილისა და გამოქვეყნებული კონტენტის საჩვენებლად, შეკვეთებისა და შემოწირულობების დასამუშავებლად, თქვენ მიერ ჩართული ელფოსტებისა და შეტყობინებების გასაგზავნად, სავარჯიშო მონაცემების მოწყობილობებს შორის სინქრონიზაციისთვის, სპამისა და ბოროტად გამოყენების თავიდან ასაცილებლად და სერვისის გასაუმჯობესებლად. ჩვენ არ ვყიდით თქვენს პერსონალურ მონაცემებს.</p>

<h3>3. რა არის საჯარო</h3>
<p>თქვენი სახელი, გვარი, პროფილის ფოტო, გამოქვეყნებული კომენტარები, შეფასებები და ასვლები ხილულია სხვა ვიზიტორებისთვის. თქვენი ელფოსტა, ტელეფონი და მისამართი არასოდეს ჩანს საჯაროდ.</p>

<h3>4. მონაცემების გაზიარება</h3>
<p>მონაცემებს ვუზიარებთ მხოლოდ სერვისის მუშაობისთვის საჭირო პროვაიდერებს: ჰოსტინგს, ელფოსტის მიწოდების სერვისს, საგადახდო პროვაიდერებს, მაღაზიის შეკვეთების მიწოდების პარტნიორებს, Google-ს (Analytics, reCAPTCHA, ავტორიზაცია) და Facebook-ს (ავტორიზაცია), ან კანონით მოთხოვნის შემთხვევაში.</p>

<h3>5. შენახვის ვადა და წაშლა</h3>
<p>მონაცემებს ვინახავთ, სანამ თქვენი ანგარიში არსებობს. ანგარიშის წაშლა ნებისმიერ დროს შეგიძლიათ <a href="https://user.climbing.ge/delete-account">ანგარიშის წაშლის გვერდზე</a> ან მობილურ აპლიკაციაში. წაშლა ხდება დაუყოვნებლივ და შლის თქვენს პროფილს, ავტორიზაციებს, მისამართებს, რჩეულებს, გამოწერებს, სავარჯიშო მონაცემებს, კომენტარებს, შეფასებებს და ასვლებს. წარსული შეკვეთებისა და შემოწირულობების ჩანაწერები ინახება ბუღალტრული და სამართლებრივი ვალდებულებების გამო და აღარ არის დაკავშირებული რომელიმე ანგარიშთან.</p>

<h3>6. თქვენი უფლებები</h3>
<p>შეგიძლიათ ნახოთ და შეცვალოთ თქვენი მონაცემები ანგარიშის პარამეტრებში, მოითხოვოთ მათი ასლი, მათი შესწორება ან წაშლა და გამორთოთ ელფოსტით შეტყობინებები. დაგვიკავშირდით ქვემოთ მითითებულ ელფოსტაზე.</p>

<h3>7. ბავშვები</h3>
<p>სერვისი არ არის განკუთვნილი 13 წლამდე ბავშვებისთვის და ჩვენ შეგნებულად არ ვაგროვებთ მათ მონაცემებს.</p>

<h3>8. ცვლილებები</h3>
<p>ჩვენ შეიძლება განვაახლოთ ეს პოლიტიკა. მოქმედი ვერსია ყოველთვის ხელმისაწვდომია ამ გვერდზე.</p>

<h3>9. კონტაქტი</h3>
<p>კონფიდენციალურობასთან დაკავშირებული კითხვები და მოთხოვნები: <a href="mailto:{contact_email}">{contact_email}</a></p>
HTML;
    }
};
