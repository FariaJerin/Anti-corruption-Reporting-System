<?php
// Database connection ba header include kora hocche jate design consistent thake
include 'db.php'; 
include 'header.php'; 
?>

<div class="center" style="padding: 40px 20px;">
    <div class="card" style="width: 100%; max-width: 900px; margin: 0 auto; background: #1e293b; color: #f8fafc; padding: 35px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); text-align: left;">
        
        <h1 style="color: #3b82f6; border-bottom: 3px solid #3b82f6; display: inline-block; padding-bottom: 10px; margin-bottom: 20px;">
            📖 ইউজার ম্যানুয়াল (ব্যবহার নির্দেশিকা)
        </h1>
        
        <p style="font-size: 1.1rem; line-height: 1.6; color: #cbd5e1;">
            আমাদের <strong>Anti-Corruption Reporting System (ACRS)</strong> প্ল্যাটফর্মে আপনাকে স্বাগতম। দুর্নীতিমুক্ত সমাজ গড়তে আপনার অভিযোগ অত্যন্ত গুরুত্বপূর্ণ। কিভাবে এই সিস্টেমটি ব্যবহার করবেন, তার বিস্তারিত নিচে দেওয়া হলো:
        </p>

        <hr style="border: 0; border-top: 1px solid #334155; margin: 30px 0;">

        <!-- Step 1 -->
        <section style="margin-bottom: 30px;">
            <h3 style="color: #38bdf8;">১. অ্যাকাউন্ট তৈরি ও লগইন</h3>
            <ul style="list-style-type: none; padding-left: 10px; line-height: 1.8;">
                <li>🔹 <strong>সাইন আপ:</strong> প্রথমে "Sign Up" বাটনে ক্লিক করে আপনার নাম, ইমেল এবং একটি পাসওয়ার্ড দিয়ে অ্যাকাউন্ট তৈরি করুন।</li>
                <li>🔹 <strong>লগইন:</strong> অ্যাকাউন্ট তৈরি হয়ে গেলে আপনার ইমেল ও পাসওয়ার্ড দিয়ে সিস্টেমে প্রবেশ করুন।</li>
            </ul>
        </section>

        <!-- Step 2 -->
        <section style="margin-bottom: 30px;">
            <h3 style="color: #38bdf8;">২. অভিযোগ জমা দেওয়া</h3>
            <ul style="list-style-type: none; padding-left: 10px; line-height: 1.8;">
                <li>🔹 <strong>ড্যাশবোর্ড:</strong> লগইন করার পর আপনি আপনার ব্যক্তিগত ড্যাশবোর্ড দেখতে পাবেন।</li>
                <li>🔹 <strong>নতুন অভিযোগ:</strong> "Submit Complaint" বাটনে ক্লিক করুন।</li>
                <li>🔹 <strong>ফর্ম পূরণ:</strong> 
                    <ul>
                        <li><strong>বিভাগ (Category):</strong> অভিযোগের ধরন (যেমন: ঘুষ, জালিয়াতি, বা ক্ষমতার অপব্যবহার) নির্বাচন করুন।</li>
                        <li><strong>বিস্তারিত বিবরণ:</strong> ঘটনার বিস্তারিত বিবরণ এবং সময় উল্লেখ করুন।</li>
                        <li><strong>প্রমাণ (Evidence):</strong> যদি আপনার কাছে কোনো ছবি, ভিডিও বা ডকুমেন্ট থাকে, তা আপলোড করুন। এটি তদন্তের জন্য অত্যন্ত সহায়ক।</li>
                    </ul>
                </li>
                <li>🔹 <strong>ট্র্যাকিং আইডি:</strong> অভিযোগ জমা দেওয়ার পর আপনি একটি অনন্য <strong>Tracking ID</strong> (যেমন: ACRS100007) পাবেন। এটি অবশ্যই নোট করে রাখুন।</li>
            </ul>
        </section>

        <!-- Step 3 -->
        <section style="margin-bottom: 30px;">
            <h3 style="color: #38bdf8;">৩. অভিযোগের অবস্থা যাচাই (Track Status)</h3>
            <p>লগইন না করেও আপনি যেকোনো সময় আপনার অভিযোগের সর্বশেষ অবস্থা জানতে পারবেন:</p>
            <ul style="list-style-type: none; padding-left: 10px; line-height: 1.8;">
                <li>🔹 হোম পেজে থাকা <strong>"Track Status"</strong> লিংকে ক্লিক করুন।</li>
                <li>🔹 আপনার দেওয়া <strong>Tracking ID</strong> টি বক্সে লিখুন এবং "Track" বাটনে ক্লিক করুন।</li>
                <li>🔹 এখানে আপনি নিচের স্ট্যাটাসগুলো দেখতে পাবেন:
                    <ul style="margin-top: 10px;">
                        <li><span style="color: #94a3b8; font-weight: bold;">⚪ PENDING:</span> আপনার অভিযোগটি এখনো অ্যাডমিন রিভিউ করছে।</li>
                        <li><span style="color: #fbbf24; font-weight: bold;">🟡 IN PROGRESS:</span> অভিযোগটি নিয়ে বর্তমানে কাজ চলছে।</li>
                        <li><span style="color: #38bdf8; font-weight: bold;">🔵 FORWARDED:</span> অভিযোগটি নির্দিষ্ট দপ্তরে তদন্তের জন্য পাঠানো হয়েছে।</li>
                        <li><span style="color: #34d399; font-weight: bold;">🟢 RESOLVED:</span> অভিযোগের বিষয়ে ব্যবস্থা নেওয়া হয়েছে বা সমাধান করা হয়েছে।</li>
                    </ul>
                </li>
            </ul>
        </section>

        <!-- Step 4 -->
        <section style="margin-bottom: 30px;">
            <h3 style="color: #38bdf8;">৪. অ্যাডমিন আপডেট</h3>
            <p style="line-height: 1.6;">তদন্ত চলাকালীন অ্যাডমিন যদি কোনো বিশেষ তথ্য বা আপডেট দেয়, তবে ট্র্যাকিং পেজে আপনি একটি নীল রঙের বক্সের মধ্যে তা দেখতে পাবেন (যেমন: কোন ডিপার্টমেন্ট তদন্ত করছে)।</p>
        </section>

        <hr style="border: 0; border-top: 1px solid #334155; margin: 30px 0;">

        <div style="background: rgba(59, 130, 246, 0.1); padding: 20px; border-radius: 10px; border: 1px solid #3b82f6;">
            <h4 style="color: #3b82f6; margin-top: 0;">💡 গুরুত্বপূর্ণ টিপস:</h4>
            <ul style="margin-bottom: 0; line-height: 1.6;">
                <li>আপনার ট্র্যাকিং আইডিটি গোপন রাখুন।</li>
                <li>সঠিক তথ্য প্রদান করুন যাতে তদন্ত প্রক্রিয়া দ্রুত সম্পন্ন হয়।</li>
                <li>আমাদের সিস্টেমে আপনার তথ্য সম্পূর্ণ সুরক্ষিত এবং গোপন রাখা হয়।</li>
            </ul>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="index.php" style="color: #3b82f6; text-decoration: none; font-weight: bold;">← হোম পেজে ফিরে যান</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>