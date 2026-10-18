<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Updates the privacy_policy site-data text (locale_sites) for the Google Play review:
 * names both apps (Climb Georgia — ge.climbing.app — and the training app), adds the
 * Donations, Mobile apps and Analytics (Google Analytics / Firebase, pseudonymous)
 * sections, and describes the keep-anonymously option of account deletion.
 * Overwrites the current text, including admin edits. {contact_email} is filled from
 * sites.email. Same text as docs/BACKEND/sql-imports/privacy_policy_update.sql.
 */
return new class extends Migration
{
    public function up(): void
    {
        $contact_email = DB::table('sites')->value('email') ?? '';

        $values = [
            'us_data'    => str_replace('{contact_email}', $contact_email, $this->us_data()),
            'ka_data'    => str_replace('{contact_email}', $contact_email, $this->ka_data()),
            'updated_at' => now(),
        ];

        if (DB::table('locale_sites')->where('slug', 'privacy_policy')->exists()) {
            DB::table('locale_sites')->where('slug', 'privacy_policy')->update($values);
        } else {
            DB::table('locale_sites')->insert(array_merge($values, ['slug' => 'privacy_policy', 'created_at' => now()]));
        }
    }

    public function down(): void
    {
        // No-op: the previous text may have been edited in the admin, so it can't be
        // restored reliably. Edit it in the admin site-info page if needed.
    }

    private function us_data(): string
    {
        return <<<'HTML'
<h2>Privacy Policy</h2>
<p>This policy explains what personal data climbing.ge collects, why, and what your rights are. It applies to:</p>
<ul>
<li>the website climbing.ge and its subdomains (guidebook, shop, blog, summit, films, user dashboard);</li>
<li>the <strong>Climb Georgia</strong> mobile app — the climbing.ge guidebook app (Google Play package <code>ge.climbing.app</code>);</li>
<li>the <strong>climbing.ge training</strong> mobile app.</li>
</ul>
<p>Together they are called the "Service". The website and both apps use the same climbing.ge account.</p>

<h3>1. Data we collect</h3>
<ul>
<li><strong>Account data</strong> — name, surname, email, phone number, country, city, preferred language, password (stored only as a secure hash), optional profile photo, bio and social links.</li>
<li><strong>Social login</strong> — if you sign in with Google or Facebook we receive your name, email, profile photo and provider account id.</li>
<li><strong>Content you publish</strong> — comments, route and product reviews, summit and multi-pitch ascents (date, route, comment, photo), favorites and follows.</li>
<li><strong>Location</strong> — only when you record a summit ascent and allow location access, your GPS coordinates at that moment are used to verify that you reached the summit and are stored with the ascent. Archive ascents and multi-pitch ascents store no location.</li>
<li><strong>Orders</strong> — delivery address, contact details, order contents and payment status.</li>
<li><strong>Donations</strong> — see section 2.</li>
<li><strong>Training app data</strong> — if you log in in the training app, your custom workouts, training plan progress and workout history are synced to your account.</li>
<li><strong>Usage and device data</strong> — see section 4 (analytics).</li>
<li><strong>Technical data</strong> — login tokens and preferences stored in your browser or on your device, and server logs (IP address, browser or device type) kept for security. Google reCAPTCHA is used on forms to block spam and may process device and usage data under Google's privacy policy.</li>
</ul>
<p>Card payments for orders and donations are processed only by our payment providers (TBC Bank, Flitt). climbing.ge never receives or stores your card details.</p>

<h3 id="donations">2. Donations</h3>
<p>When you donate, we collect what you enter in the donation form — name, surname, email, phone number, country, age and an optional comment — together with the amount, currency and payment status. We use it to process and record your donation and to answer questions about it; if you are logged in, or later register with the same email, the donation is linked to your account. Card payments are handled only by TBC Bank: climbing.ge never receives or stores your card details. Donation records are kept for accounting and legal obligations, also after you delete your account.</p>

<h3 id="mobile-apps">3. Mobile apps</h3>
<ul>
<li><strong>Permissions</strong> — the apps ask for a permission only when you use the feature that needs it: location (to verify a summit ascent you are recording), camera or photos (to attach a photo you choose to an ascent or your profile) and notifications (training reminders). You can refuse or revoke any permission in your device settings; the rest of the app keeps working.</li>
<li><strong>Data on your device</strong> — your login token is stored on the device; guidebook content and, in the training app, workouts and history are cached on the device so they work offline. Reminder notifications are scheduled on the device itself. Uninstalling the app removes this local data.</li>
<li><strong>Without an account</strong> — you can browse the guidebook without logging in; then nothing is linked to an account, but analytics (section 4) still apply.</li>
</ul>

<h3 id="analytics">4. Analytics</h3>
<p>To understand how the Service is used and to improve it we use:</p>
<ul>
<li><strong>Website</strong> — Google Analytics: pages viewed, time on page, referring site, browser and device type, approximate location derived from your IP address, and a cookie identifier.</li>
<li><strong>Mobile apps</strong> — Google Analytics for Firebase: usage events (for example screens viewed and features used), an app-instance identifier, device model, operating system and app version, and approximate location derived from your IP address.</li>
</ul>
<p>This data is linked to a cookie or app-instance identifier, not to your name, email or climbing.ge account, so it is pseudonymous rather than fully anonymous. It is processed by Google under its own privacy policy, is used only for statistics and improving the Service, and is never sold or used for advertising.</p>

<h3>5. How we use your data</h3>
<p>To create and run your account, show your public climber profile and published content, process orders and donations, send the emails and notifications you enable, sync training data between your devices, prevent spam and abuse, and improve the Service. We do not sell your personal data.</p>

<h3>6. What is public</h3>
<p>Your name, surname, profile photo, published comments, reviews and ascents are visible to other visitors. Your email, phone number and address are never shown publicly.</p>

<h3>7. Sharing</h3>
<p>We share data only with service providers needed to run the Service: hosting, email delivery, payment providers (TBC Bank, Flitt), delivery partners for shop orders, Google (Analytics, Firebase, reCAPTCHA, sign-in) and Facebook (sign-in), or when required by law.</p>

<h3 id="deletion">8. Retention and account deletion</h3>
<p>We keep your data while your account exists. You can delete your account at any time on the <a href="https://user.climbing.ge/delete-account">account deletion page</a> or in the apps. Deletion is immediate and removes your profile, logins, addresses, favorites, follows, notification settings and training data.</p>
<p>By default it also deletes your ascents, reviews, product feedback and comments. When deleting, you can choose to keep any of these four: kept items stay on the Service anonymously, shown as "Deleted user", with your name and email removed and no link to any account.</p>
<p>Records of past orders and donations are kept for accounting and legal obligations, no longer linked to any account. Analytics data (section 4) is not linked to your account and is kept by Google under its retention settings.</p>

<h3>9. Your rights</h3>
<p>You can view and edit your data in your account settings, ask for a copy of it, ask us to correct or delete it, and turn off notification emails. Contact us using the email below.</p>

<h3>10. Children</h3>
<p>The Service is not directed at children under 13, and we do not knowingly collect their data.</p>

<h3>11. Changes</h3>
<p>We may update this policy. The current version is always available on this page.</p>

<h3>12. Contact</h3>
<p>Privacy questions and requests: <a href="mailto:{contact_email}">{contact_email}</a></p>
HTML;
    }

    private function ka_data(): string
    {
        return <<<'HTML'
<h2>კონფიდენციალურობის პოლიტიკა</h2>
<p>ეს პოლიტიკა განმარტავს, რა პერსონალურ მონაცემებს აგროვებს climbing.ge, რა მიზნით და რა უფლებები გაქვთ. ის ვრცელდება:</p>
<ul>
<li>ვებსაიტზე climbing.ge და მის ქვედომენებზე (გზამკვლევი, მაღაზია, ბლოგი, მწვერვალები, ფილმები, მომხმარებლის პანელი);</li>
<li>მობილურ აპლიკაციაზე <strong>Climb Georgia</strong> — climbing.ge-ის გზამკვლევის აპლიკაცია (Google Play პაკეტი <code>ge.climbing.app</code>);</li>
<li>მობილურ აპლიკაციაზე <strong>climbing.ge training</strong> (სავარჯიშო აპლიკაცია).</li>
</ul>
<p>ერთად მათ ეწოდება „სერვისი“. ვებსაიტი და ორივე აპლიკაცია იყენებს ერთსა და იმავე climbing.ge ანგარიშს.</p>

<h3>1. რა მონაცემებს ვაგროვებთ</h3>
<ul>
<li><strong>ანგარიშის მონაცემები</strong> — სახელი, გვარი, ელფოსტა, ტელეფონის ნომერი, ქვეყანა, ქალაქი, სასურველი ენა, პაროლი (ინახება მხოლოდ დაშიფრული ჰეშის სახით), სურვილისამებრ პროფილის ფოტო, ბიოგრაფია და სოციალური ბმულები.</li>
<li><strong>სოციალური ავტორიზაცია</strong> — Google-ით ან Facebook-ით შესვლისას ვიღებთ თქვენს სახელს, ელფოსტას, პროფილის ფოტოს და პროვაიდერის ანგარიშის იდენტიფიკატორს.</li>
<li><strong>თქვენ მიერ გამოქვეყნებული კონტენტი</strong> — კომენტარები, მარშრუტებისა და პროდუქტების შეფასებები, მწვერვალებზე და მრავალპიჩიან მარშრუტებზე ასვლები (თარიღი, მარშრუტი, კომენტარი, ფოტო), რჩეულები და გამოწერები.</li>
<li><strong>მდებარეობა</strong> — მხოლოდ მწვერვალზე ასვლის დაფიქსირებისას და თქვენი ნებართვით, იმ მომენტის GPS კოორდინატები გამოიყენება მწვერვალზე ყოფნის დასადასტურებლად და ინახება ასვლასთან ერთად. არქივის ასვლები და მრავალპიჩიანი ასვლები მდებარეობას არ ინახავს.</li>
<li><strong>შეკვეთები</strong> — მიწოდების მისამართი, საკონტაქტო მონაცემები, შეკვეთის შემადგენლობა და გადახდის სტატუსი.</li>
<li><strong>შემოწირულობები</strong> — იხილეთ მე-2 ნაწილი.</li>
<li><strong>სავარჯიშო აპლიკაციის მონაცემები</strong> — სავარჯიშო აპლიკაციაში შესვლისას თქვენი ვარჯიშები, სავარჯიშო გეგმების პროგრესი და ვარჯიშების ისტორია სინქრონიზდება თქვენს ანგარიშთან.</li>
<li><strong>გამოყენებისა და მოწყობილობის მონაცემები</strong> — იხილეთ მე-4 ნაწილი (ანალიტიკა).</li>
<li><strong>ტექნიკური მონაცემები</strong> — ავტორიზაციის ტოკენები და პარამეტრები თქვენს ბრაუზერში ან მოწყობილობაზე და სერვერის ჟურნალები (IP მისამართი, ბრაუზერის ან მოწყობილობის ტიპი) უსაფრთხოებისთვის. ფორმებზე სპამის თავიდან ასაცილებლად გამოიყენება Google reCAPTCHA, რომელმაც შეიძლება დაამუშაოს მოწყობილობისა და გამოყენების მონაცემები Google-ის კონფიდენციალურობის პოლიტიკის შესაბამისად.</li>
</ul>
<p>შეკვეთებისა და შემოწირულობების ბარათით გადახდებს ამუშავებენ მხოლოდ ჩვენი საგადახდო პროვაიდერები (თიბისი ბანკი, Flitt). climbing.ge არასოდეს იღებს და არ ინახავს თქვენი ბარათის მონაცემებს.</p>

<h3 id="donations">2. შემოწირულობები</h3>
<p>შემოწირულობისას ვაგროვებთ იმას, რასაც შემოწირულობის ფორმაში შეიყვანთ — სახელს, გვარს, ელფოსტას, ტელეფონის ნომერს, ქვეყანას, ასაკს და სურვილისამებრ კომენტარს — ასევე თანხას, ვალუტას და გადახდის სტატუსს. ამ მონაცემებს ვიყენებთ შემოწირულობის დასამუშავებლად და აღსარიცხად, ასევე მასთან დაკავშირებულ კითხვებზე პასუხის გასაცემად; თუ სისტემაში შესული ხართ ან მოგვიანებით იმავე ელფოსტით დარეგისტრირდებით, შემოწირულობა დაუკავშირდება თქვენს ანგარიშს. ბარათით გადახდას ამუშავებს მხოლოდ თიბისი ბანკი: climbing.ge არასოდეს იღებს და არ ინახავს თქვენი ბარათის მონაცემებს. შემოწირულობების ჩანაწერები ინახება ბუღალტრული და სამართლებრივი ვალდებულებების გამო, მათ შორის ანგარიშის წაშლის შემდეგაც.</p>

<h3 id="mobile-apps">3. მობილური აპლიკაციები</h3>
<ul>
<li><strong>ნებართვები</strong> — აპლიკაციები ნებართვას ითხოვენ მხოლოდ მაშინ, როცა იყენებთ შესაბამის ფუნქციას: მდებარეობა (თქვენ მიერ დაფიქსირებული მწვერვალზე ასვლის დასადასტურებლად), კამერა ან ფოტოები (თქვენ მიერ არჩეული ფოტოს ასვლაზე ან პროფილზე დასამატებლად) და შეტყობინებები (ვარჯიშის შეხსენებები). ნებისმიერ ნებართვაზე შეგიძლიათ უარი თქვათ ან გააუქმოთ მოწყობილობის პარამეტრებში; აპლიკაციის დანარჩენი ნაწილი მუშაობას განაგრძობს.</li>
<li><strong>მონაცემები თქვენს მოწყობილობაზე</strong> — ავტორიზაციის ტოკენი ინახება მოწყობილობაზე; გზამკვლევის კონტენტი და სავარჯიშო აპლიკაციაში ვარჯიშები და ისტორია ინახება მოწყობილობაზე ინტერნეტის გარეშე მუშაობისთვის. შეხსენებები იგეგმება თავად მოწყობილობაზე. აპლიკაციის წაშლა შლის ამ ლოკალურ მონაცემებს.</li>
<li><strong>ანგარიშის გარეშე</strong> — გზამკვლევის დათვალიერება შესაძლებელია სისტემაში შესვლის გარეშე; ამ შემთხვევაში არაფერი უკავშირდება ანგარიშს, თუმცა ანალიტიკა (მე-4 ნაწილი) მაინც მოქმედებს.</li>
</ul>

<h3 id="analytics">4. ანალიტიკა</h3>
<p>სერვისის გამოყენების გასაგებად და გასაუმჯობესებლად ვიყენებთ:</p>
<ul>
<li><strong>ვებსაიტი</strong> — Google Analytics: ნანახი გვერდები, გვერდზე გატარებული დრო, საიდან მოხვედით, ბრაუზერისა და მოწყობილობის ტიპი, IP მისამართით განსაზღვრული მიახლოებითი მდებარეობა და cookie-იდენტიფიკატორი.</li>
<li><strong>მობილური აპლიკაციები</strong> — Google Analytics for Firebase: გამოყენების მოვლენები (მაგალითად, ნანახი ეკრანები და გამოყენებული ფუნქციები), აპლიკაციის ინსტანციის იდენტიფიკატორი, მოწყობილობის მოდელი, ოპერაციული სისტემისა და აპლიკაციის ვერსია და IP მისამართით განსაზღვრული მიახლოებითი მდებარეობა.</li>
</ul>
<p>ეს მონაცემები დაკავშირებულია cookie-ს ან აპლიკაციის ინსტანციის იდენტიფიკატორთან და არა თქვენს სახელთან, ელფოსტასთან ან climbing.ge ანგარიშთან — ანუ ისინი ფსევდონიმურია და არა სრულად ანონიმური. მათ ამუშავებს Google თავისი კონფიდენციალურობის პოლიტიკის შესაბამისად; ისინი გამოიყენება მხოლოდ სტატისტიკისა და სერვისის გასაუმჯობესებლად და არასოდეს იყიდება ან გამოიყენება რეკლამისთვის.</p>

<h3>5. როგორ ვიყენებთ მონაცემებს</h3>
<p>ანგარიშის შესაქმნელად და სამართავად, თქვენი საჯარო პროფილისა და გამოქვეყნებული კონტენტის საჩვენებლად, შეკვეთებისა და შემოწირულობების დასამუშავებლად, თქვენ მიერ ჩართული ელფოსტებისა და შეტყობინებების გასაგზავნად, სავარჯიშო მონაცემების მოწყობილობებს შორის სინქრონიზაციისთვის, სპამისა და ბოროტად გამოყენების თავიდან ასაცილებლად და სერვისის გასაუმჯობესებლად. ჩვენ არ ვყიდით თქვენს პერსონალურ მონაცემებს.</p>

<h3>6. რა არის საჯარო</h3>
<p>თქვენი სახელი, გვარი, პროფილის ფოტო, გამოქვეყნებული კომენტარები, შეფასებები და ასვლები ხილულია სხვა ვიზიტორებისთვის. თქვენი ელფოსტა, ტელეფონი და მისამართი არასოდეს ჩანს საჯაროდ.</p>

<h3>7. მონაცემების გაზიარება</h3>
<p>მონაცემებს ვუზიარებთ მხოლოდ სერვისის მუშაობისთვის საჭირო პროვაიდერებს: ჰოსტინგს, ელფოსტის მიწოდების სერვისს, საგადახდო პროვაიდერებს (თიბისი ბანკი, Flitt), მაღაზიის შეკვეთების მიწოდების პარტნიორებს, Google-ს (Analytics, Firebase, reCAPTCHA, ავტორიზაცია) და Facebook-ს (ავტორიზაცია), ან კანონით მოთხოვნის შემთხვევაში.</p>

<h3 id="deletion">8. შენახვის ვადა და ანგარიშის წაშლა</h3>
<p>მონაცემებს ვინახავთ, სანამ თქვენი ანგარიში არსებობს. ანგარიშის წაშლა ნებისმიერ დროს შეგიძლიათ <a href="https://user.climbing.ge/delete-account">ანგარიშის წაშლის გვერდზე</a> ან აპლიკაციებში. წაშლა ხდება დაუყოვნებლივ და შლის თქვენს პროფილს, ავტორიზაციებს, მისამართებს, რჩეულებს, გამოწერებს, შეტყობინებების პარამეტრებს და სავარჯიშო მონაცემებს.</p>
<p>ნაგულისხმევად ასევე იშლება თქვენი ასვლები, შეფასებები, პროდუქტების შეფასებები და კომენტარები. წაშლისას შეგიძლიათ აირჩიოთ ამ ოთხიდან რომელიმეს შენარჩუნება: შენარჩუნებული ელემენტები სერვისზე დარჩება ანონიმურად, როგორც „წაშლილი მომხმარებელი“, თქვენი სახელისა და ელფოსტის გარეშე და რომელიმე ანგარიშთან კავშირის გარეშე.</p>
<p>წარსული შეკვეთებისა და შემოწირულობების ჩანაწერები ინახება ბუღალტრული და სამართლებრივი ვალდებულებების გამო და აღარ არის დაკავშირებული რომელიმე ანგარიშთან. ანალიტიკის მონაცემები (მე-4 ნაწილი) არ არის დაკავშირებული თქვენს ანგარიშთან და Google-ის მიერ ინახება მისი შენახვის პარამეტრების მიხედვით.</p>

<h3>9. თქვენი უფლებები</h3>
<p>შეგიძლიათ ნახოთ და შეცვალოთ თქვენი მონაცემები ანგარიშის პარამეტრებში, მოითხოვოთ მათი ასლი, მათი შესწორება ან წაშლა და გამორთოთ ელფოსტით შეტყობინებები. დაგვიკავშირდით ქვემოთ მითითებულ ელფოსტაზე.</p>

<h3>10. ბავშვები</h3>
<p>სერვისი არ არის განკუთვნილი 13 წლამდე ბავშვებისთვის და ჩვენ შეგნებულად არ ვაგროვებთ მათ მონაცემებს.</p>

<h3>11. ცვლილებები</h3>
<p>ჩვენ შეიძლება განვაახლოთ ეს პოლიტიკა. მოქმედი ვერსია ყოველთვის ხელმისაწვდომია ამ გვერდზე.</p>

<h3>12. კონტაქტი</h3>
<p>კონფიდენციალურობასთან დაკავშირებული კითხვები და მოთხოვნები: <a href="mailto:{contact_email}">{contact_email}</a></p>
HTML;
    }
};
