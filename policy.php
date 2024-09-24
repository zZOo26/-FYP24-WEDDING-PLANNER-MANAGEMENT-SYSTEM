<?php
session_start();
include('dbcon.php');
include('functions.php');

$page_title = "Bridal attire";
include('includes/header.php');
?>

<section class="pt-3 pt-md-5 pb-md-5 pt-lg-7 bg-gray-200">
    <div class="container">
        <div class="col-lg-8 col-md-10 mx-auto pb-5">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-warning p-5 position-relative">
                    <h3 class="text-white mb-0">Privacy &amp; Policy</h3>
                    <p class="text-white opacity-8 mb-4">Last modified: June 22 2024</p>
                    <div class="position-absolute w-100 z-index-1 bottom-0 ms-n5">
                        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 40" preserveAspectRatio="none" shape-rendering="auto" style="height:7vh;min-height:50px;">
                            <defs>
                                <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
                            </defs>
                            <g class="moving-waves">
                                <use xlink:href="#gentle-wave" x="48" y="-1" fill="rgba(255,255,255,0.40"></use>
                                <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.35)"></use>
                                <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.25)"></use>
                                <use xlink:href="#gentle-wave" x="48" y="8" fill="rgba(255,255,255,0.20)"></use>
                                <use xlink:href="#gentle-wave" x="48" y="13" fill="rgba(255,255,255,0.15)"></use>
                                <use xlink:href="#gentle-wave" x="48" y="16" fill="rgba(255,255,255,0.95"></use>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="card-body p-5 pt-0 text-justify">
                    <h4 class="mt-5 mb-3">Introduction</h4>
                    Welcome to WANIEY BRIDAL WEDDING PLANNER MANAGEMENT SYSTEM (hereafter referred to as "the System"). This Privacy Policy outlines our practices regarding the collection, use, and disclosure of personal information when you use our services. We are committed to protecting your privacy and ensuring that your personal information is handled in a safe and responsible manner.
                    <br><br>
                    We collect the following types of information:
                    <ul>
                        <li>Personal Information: Information that identifies you as an individual, such as your name, email address, phone number, and address.</li>
                        <li>Usage Data: Information on how the System is accessed and used, including your IP address, browser type, and operating system.</li>
                    </ul>

                    <h4 class="mt-5 mb-3">Sharing of Information</h4>
                    <p> We use your information to provide and maintain the System, notify you about changes, provide customer support, gather analysis or valuable information for improvement, monitor usage, and detect, prevent, and address technical issues.</p>
                    
                    <p>We do not share your personal information with third parties except in the following cases:</p>
                    <ul>
                        <li>With your consent</li>
                        <li>To protect and defend our rights and property</li>
                        <li>To prevent or investigate possible wrongdoing in connection with the System</li>
                        <li>To protect the personal safety of users of the System or the public</li>
                    </ul>

                    <h4 class="mt-5 mb-3">Security of Your Information</h4>
                    <p>
                        We take the security of your personal information seriously and use appropriate measures to protect it from unauthorized access, disclosure, alteration, or destruction. However, no method of transmission over the Internet or method of electronic storage is 100% secure.
                    </p>
                    <h4 class="mt-5 mb-3">Your Rights</h4>
                    <p>
                        You have the right to access, update, or delete the personal information we hold about you. You can do this directly within your account settings section or by contacting us. You also have the right to withdraw your consent to the processing of your personal information at any time.
                    </p>
                    <h4 class="mt-5 mb-3">Children's Privacy</h4>

                    <p>
                        Our System is not intended for use by children under the age of 13. We do not knowingly collect personally identifiable information from children under 13. If we become aware that we have collected personal information from children under 13, we will take steps to delete such information.
                    </p>
                    <h4 class="mt-5 mb-3">Changes to This Privacy Policy</h4>

                    <p>
                        We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.
                    </p>
                    <h4 class="mt-5 mb-3">Contact Us</h4>
                    <p>
                        If you have any questions about these <a href="tnc.php">Terms and Conditions</a>, please contact us by email at
                    <ul>
                        <li>By email: wanieybridal@gmail.com</li>
                        <li>By phone: +6017 340 5912</li>
                        <li>By mail: Waniey Bridal, G-11-G Jalan PP 25 Taman Pinggiran Putra,
                            Putra Walk, 43300 Seri Kembangan, Selangor.</li>
                    </ul>
                    </p>


                    <p>
                        By using the WANIEY BRIDAL WEDDING PLANNER MANAGEMENT SYSTEM, you acknowledge that you have read, understood, and agree to be bound by these <a href="policy.php">Privacy Policy</a> and <a href="tnc.php">Terms and Conditions</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php') ?>