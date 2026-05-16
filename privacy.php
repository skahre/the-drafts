<?php require_once "utils/bases.php"; ?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>Privacy Policy | The Drafts</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <main class="flex flex-1 justify-center p-8 bg-offwhite">
        <div class="bg-white rounded-2xl p-8 w-full max-w-2xl flex flex-col gap-6">

            <h1 class="text-2xl font-bold">Privacy Policy</h1>

            <p class="text-sm text-gray-600">Last updated: May 2026</p>

            <section class="flex flex-col gap-2">
                <h2 class="text-lg font-semibold">What data we collect</h2>
                <p class="text-sm leading-relaxed">When you create an account on The Drafts, we store the following personal data:</p>
                <ul class="text-sm leading-relaxed list-disc list-inside flex flex-col gap-1">
                    <li><strong>Username</strong> — chosen by you at registration, visible on your posts.</li>
                    <li><strong>Password</strong> — stored as a one-way hash (bcrypt). We cannot read your password.</li>
                    <li><strong>Display name</strong> — if you choose one.</li>
                    <li><strong>Profile image</strong> — if you choose to upload one.</li>
                    <li><strong>Posts</strong> — any blog posts you publish on the platform.</li>
                </ul>
            </section>

            <section class="flex flex-col gap-2">
                <h2 class="text-lg font-semibold">Why we collect it</h2>
                <p class="text-sm leading-relaxed">This data is needed solely to operate your account and display your content. We do not share it with third parties, use it for advertising, or sell it.</p>
            </section>

            <section class="flex flex-col gap-2">
                <h2 class="text-lg font-semibold">Your rights (GDPR)</h2>
                <p class="text-sm leading-relaxed">Under the General Data Protection Regulation (GDPR) you have the right to:</p>
                <ul class="text-sm leading-relaxed list-disc list-inside flex flex-col gap-1">
                    <li>Access the personal data we hold about you.</li>
                    <li>Request correction of inaccurate data.</li>
                    <li>Request deletion of your account and associated data.</li>
                </ul>
                <p class="text-sm leading-relaxed">To exercise any of these rights, contact the site administrator.</p>
            </section>

        </div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
