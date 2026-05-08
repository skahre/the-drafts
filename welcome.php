<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/output.css">
    <title>The Drafts</title>
</head>
<body>

    <?php require_once "components/header.php"; ?>

    <main class="flex flex-row p-4 gap-4 flex-1 bg-offwhite">
        <div class="flex flex-1 flex-col items-center gap-8 p-8">
            <h1 class="text-2xl font-bold">NEWS</h1>
            <div class="w-full">
                <div class="flex flex-col gap-4 bg-white p-8 rounded-2xl">
                    <h2 class="text-xl font-bold">Title</h2>
                    <p class="text-sm text-gray">2026-05-05</p>
                    <p class="truncate-overflow blog-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eu nulla vitae dolor imperdiet
                        molestie a sed neque. Pellentesque leo ligula, imperdiet in lectus non, scelerisque vehicula
                        metus. Aenean vehicula volutpat consectetur. Nam id egestas orci. Morbi velit sapien,
                        convallis quis consequat at, porta non nisi. Vestibulum ultrices nisl ac elementum finibus.
                        Nullam fermentum consequat dui, quis imperdiet magna aliquam at. Phasellus id augue vehicula,
                        condimentum tortor a, aliquam lectus. Fusce aliquet egestas tortor. Vestibulum ante ipsum
                        primis in faucibus orci luctus et ultrices posuere cubilia curae; Vestibulum sed nisl quis
                        sem porttitor mattis placerat nec quam. Pellentesque tristique dignissim massa, non suscipit
                        augue sollicitudin luctus. Quisque fermentum sagittis magna vitae commodo. Donec nec ante a
                        mi tincidunt dapibus. Suspendisse nec molestie nisl, ut ornare est. Sed consequat, sem et
                        porttitor tempus, nibh leo rhoncus sem, et tincidunt tellus ligula non sem.</p>
                </div>
            </div>
        </div>
        <div class="flex shrink-0 bg-white w-1/5 h-[80lvh] rounded-2xl">
            <ul class="list-none p-2 w-full flex flex-col gap-1">
                <li class="border border-gray px-2 py-4 rounded-lg flex justify-center"><a href="blog.php">Example Blogger</a></li>
                <li class="border border-gray px-2 py-4 rounded-lg flex justify-center">Sandra Kåhre</li>
                <li class="border border-gray px-2 py-4 rounded-lg flex justify-center">Hell YEZZ</li>
            </ul>
        </div>
    </main>

    <?php require_once "components/footer.php"; ?>

</body>
</html>
