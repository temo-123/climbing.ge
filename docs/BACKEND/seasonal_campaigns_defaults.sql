-- Default seasonal campaigns (docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md).
-- All start DISABLED: review each in user.climbing.ge/seasonal_campaigns, "Preview" / "Send test to me", then switch on.
-- Idempotent: skips a campaign whose name already exists.

INSERT INTO seasonal_campaigns
  (name, content_type, category, sun_filter, start_md, end_md, every_days, items_per_mail, pick, audience, inactive_days, max_per_run,
   subject_us, subject_ka, intro_us, intro_ka)
SELECT * FROM (
  SELECT 'Ice season opens' AS name, 'article' AS content_type, 'ice' AS category, 'any' AS sun_filter, '12-01' AS start_md, '02-28' AS end_md, 21 AS every_days, 2 AS items_per_mail, 'popular' AS pick, 'interested' AS audience, NULL AS inactive_days, 500 AS max_per_run,
    'Ice season is open ❄️' AS subject_us, 'ყინულის სეზონი გაიხსნა ❄️' AS subject_ka,
    'The cold is here and the ice is forming. Here are a couple of ice spots to plan your first swings of the season — check the conditions and go early.' AS intro_us,
    'სიცივე მოვიდა და ყინული იკვრება. აი რამდენიმე ყინულის ადგილი სეზონის პირველი ცოცვისთვის — გადაამოწმე პირობები და ადრე გადი.' AS intro_ka
  UNION ALL SELECT 'Spring crags', 'article', 'outdoor', 'any', '03-20', '05-31', 21, 2, 'popular', 'interested', NULL, 500,
    'Spring is crag season 🌱', 'გაზაფხული კლდეების სეზონია 🌱',
    'Warm days, dry rock — the best time of year to climb outside. Two crags worth a visit this spring:',
    'თბილი დღეები, მშრალი კლდე — წლის საუკეთესო დრო გარეთ საცოცად. ორი კლდე, რომელიც ამ გაზაფხულზე უნდა ნახო:'
  UNION ALL SELECT 'Shady sectors for hot days', 'sector', 'outdoor', 'shade', '06-15', '08-31', 14, 3, 'popular', 'interested', NULL, 500,
    'Too hot? Climb in the shade ☀️', 'ცხელა? იცოცე ჩრდილში ☀️',
    'Summer sun is brutal on the rock. These sectors stay in the shade for most of the day — perfect for an afternoon session.',
    'ზაფხულის მზე კლდეზე მკაცრია. ეს სექტორები დღის უმეტეს ნაწილს ჩრდილში რჩება — იდეალურია შუადღის ცოცვისთვის.'
  UNION ALL SELECT 'Mountaineering season', 'article', 'mount_route', 'any', '07-01', '08-31', 14, 2, 'popular', 'interested', NULL, 500,
    'High season in the mountains 🏔', 'მთის სეზონი დაიწყო 🏔',
    'Mid-summer is the most stable time for the big peaks of the Caucasus. A couple of routes to put on your list — check the weather and go prepared.',
    'შუა ზაფხული კავკასიის დიდი მწვერვალებისთვის ყველაზე სტაბილური დროა. რამდენიმე მარშრუტი შენი სიისთვის — გადაამოწმე ამინდი და მომზადებული წადი.'
  UNION ALL SELECT 'Sunny sectors for autumn', 'sector', 'outdoor', 'sun', '09-15', '11-15', 21, 3, 'popular', 'interested', NULL, 500,
    'Autumn sending season 🍂', 'შემოდგომა — ცოცვის სეზონი 🍂',
    'Cool air, great friction. When the mornings get cold, these sunny sectors warm up first:',
    'გრილი ჰაერი, კარგი ხახუნი. როცა დილები ცივდება, ეს მზიანი სექტორები პირველი თბება:'
  UNION ALL SELECT 'Winter training indoors', 'article', 'indoor', 'any', '11-15', '03-15', 30, 2, 'random', 'interested', NULL, 500,
    'Keep strong this winter 💪', 'ზამთარშიც იყავი ფორმაში 💪',
    'Short days don''t have to mean a break. A couple of gyms to keep your fingers strong until spring:',
    'მოკლე დღეები შესვენებას არ ნიშნავს. რამდენიმე დარბაზი, რომ თითები გაზაფხულამდე ძლიერი დარჩეს:'
  UNION ALL SELECT 'Tech tip of the month', 'article', 'tech_tip', 'any', '01-01', '12-31', 30, 1, 'random', 'all', NULL, 500,
    'A quick tip to climb safer 🪢', 'სწრაფი რჩევა უსაფრთხო ცოცვისთვის 🪢',
    'Five minutes of reading that might save your day out:',
    'ხუთწუთიანი საკითხავი, რომელმაც შეიძლება გადაგირჩინოს დღე:'
  UNION ALL SELECT 'We miss you', 'article', 'outdoor', 'any', '01-01', '12-31', 60, 3, 'newest', 'inactive', 45, 300,
    'It''s been a while — here''s what''s new 🧗', 'დიდი ხანია არ გვინახიხარ — აი რა არის ახალი 🧗',
    'We haven''t seen you on climbing.ge for a while. Here are the newest crags and topos added since then:',
    'დიდი ხანია climbing.ge-ზე არ შემოსულხარ. აი უახლესი კლდეები და ტოპოები, რაც მას შემდეგ დაემატა:'
) AS d
WHERE NOT EXISTS (SELECT 1 FROM seasonal_campaigns s WHERE s.name = d.name);

-- the INSERT above leaves enabled/created_at to defaults; set timestamps:
UPDATE seasonal_campaigns SET created_at = NOW(), updated_at = NOW() WHERE created_at IS NULL;
