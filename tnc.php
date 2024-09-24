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
                    <h3 class="text-white mb-0">Terms &amp; Conditions</h3>
                    <p class="text-white opacity-8 mb-4">Last modified: June 22 202</p>
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
                    <p>
                        These <a href="tnc.php">Terms and Conditions</a> govern your use of the WANIEY BRIDAL WEDDING PLANNER MANAGEMENT SYSTEM (hereafter referred to as "the System"). By accessing or using the System, you agree to be bound by these <a href="tnc.php">Terms and Conditions</a>. If you disagree with any part of the terms, then you may not access the System.
                    </p>
                    <h4 class="mt-5 mb-3">User Account</h4>
                    <p>
                        To use certain features of the System, you must create an account. You agree to provide accurate, current, and complete information during the registration process and update such information to keep it accurate, current, and complete. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.
                    </p>
                    <h4 class="mt-5  mb-3">Use of the System</h4>
                    <p>
                        You may use the System for lawful purposes only. You agree not to use the System in any way that violates any applicable federal, state, local, or international law or regulation, to impersonate or attempt to impersonate any person or entity, or to engage in any conduct that restricts or inhibits anyone’s use or enjoyment of the System. You also agree not to use the System in any manner that could disable, overburden, damage, or impair the System, or to use any robot, spider, or other automatic devices, process, or means to access the System for any purpose, or to introduce any viruses, Trojan horses, worms, logic bombs, or other material that is malicious or technologically harmful.
                    </p>
                    <h4 class="mt-5 mb-3">Intellectual Property Rights</h4>
                    <p>
                        The System and its entire contents, features, and functionality are owned by WANIEY BRIDAL, its licensors, or other providers of such material and are protected by copyright, trademark, patent, trade secret, and other intellectual property or proprietary rights laws.
                    </p>
                    <h4 class="mt-5 mb-3">Limitation of Liability</h4>
                    <p>
                        In no event will WANIEY BRIDAL, its affiliates, or their licensors, service providers, employees, agents, officers, or directors be liable for damages of any kind arising out of or in connection with your use, or inability to use, the System.
                    </p>
                    <h4 class="mt-5 mb-3">Changes to These Terms and Conditions</h4>
                    <p>
                        We may revise and update these <a href="tnc.php">Terms and Conditions</a> from time to time. All changes are effective immediately when we post them and apply to all access to and use of the System thereafter.
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