<?php
// Base URL of the project (works on localhost/fakestoreapi AND on the live domain)
if (!isset($baseUrl)) {
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $baseUrl = rtrim(str_replace($docRoot, '', $projectRoot), '/');
}
$homeUrl = $baseUrl . '/home';
$docsUrl = $baseUrl !== '' ? $baseUrl . '/docs' : '/docs';

// Detect active page if not explicitly set
if (!isset($activePage)) {
    $script = basename($_SERVER['SCRIPT_NAME'] ?? '', '.php');
    if ($script === 'docs') {
        $activePage = 'docs';
    } elseif ($script === 'index') {
        $activePage = 'home';
    } else {
        $reqUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        if (preg_match('#/docs(?:/|\.php)?$#i', (string)$reqUri) || $reqUri === '/docs') {
            $activePage = 'docs';
        } else {
            $activePage = 'home';
        }
    }
}
$isHome = ($activePage === 'home');
$isDocs = ($activePage === 'docs');
?>
<nav class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <!-- Main Navbar -->
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="<?= $homeUrl ?>" class="flex items-center group" aria-label="Mock API Home">
                <img src="<?= $baseUrl ?>/assets/images/logo/mockapicv.png" alt="MockAPI logo" width="64" height="64"
                    class="w-14 h-14 sm:w-16 sm:h-16 rounded-lg object-contain group-hover:scale-105 transition-transform duration-200">
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-3 lg:gap-5">
                <a href="<?= $homeUrl ?>"
                    class="px-4 py-2 rounded-lg text-sm lg:text-base font-medium transition-all <?= $isHome ? 'text-[#8c2d7d] bg-[#8c2d7d]/10 font-semibold shadow-sm' : 'text-gray-600 hover:text-[#8c2d7d] hover:bg-gray-50' ?>">
                    Home
                </a>
                <a href="<?= $docsUrl ?>"
                    class="px-4 py-2 rounded-lg text-sm lg:text-base font-medium transition-all <?= $isDocs ? 'text-[#8c2d7d] bg-[#8c2d7d]/10 font-semibold shadow-sm' : 'text-gray-600 hover:text-[#8c2d7d] hover:bg-gray-50' ?>">
                    Docs
                </a>
                <a href="https://github.com/kingtech8001/kt_prod_mock_api_php" target="_blank" rel="noopener noreferrer"
                    class="px-4 py-2 rounded-lg text-sm lg:text-base font-medium text-gray-600 hover:text-[#8c2d7d] hover:bg-gray-50 transition-all flex items-center gap-1.5">
                    <span>GitHub</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" type="button"
                class="md:hidden w-11 h-11 flex items-center justify-center rounded-lg border border-gray-200 text-gray-700 hover:text-[#8c2d7d] hover:border-[#8c2d7d] hover:bg-gray-50 transition"
                aria-label="Open menu" aria-controls="mobile-menu" aria-expanded="false">
                <svg id="menu-open-icon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="menu-close-icon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden border-t border-gray-100 py-3">
            <div class="flex flex-col space-y-1.5">
                <a href="<?= $homeUrl ?>"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium transition <?= $isHome ? 'bg-[#8c2d7d]/10 text-[#8c2d7d] font-semibold border-l-4 border-[#8c2d7d]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#8c2d7d]' ?>">
                    Home
                </a>
                <a href="<?= $docsUrl ?>"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium transition <?= $isDocs ? 'bg-[#8c2d7d]/10 text-[#8c2d7d] font-semibold border-l-4 border-[#8c2d7d]' : 'text-gray-600 hover:bg-gray-50 hover:text-[#8c2d7d]' ?>">
                    Docs
                </a>
                <a href="https://github.com/kingtech8001/kt_prod_mock_api_php" target="_blank" rel="noopener noreferrer"
                    class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-[#8c2d7d] transition flex items-center justify-between">
                    <span>GitHub</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    (() => {
        const mobileMenuBtn = document.getElementById("mobile-menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");
        const menuOpenIcon = document.getElementById("menu-open-icon");
        const menuCloseIcon = document.getElementById("menu-close-icon");

        if (!mobileMenuBtn || !mobileMenu || !menuOpenIcon || !menuCloseIcon) return;

        mobileMenuBtn.addEventListener("click", () => {
            const isOpening = mobileMenu.classList.contains("hidden");
            mobileMenu.classList.toggle("hidden");
            menuOpenIcon.classList.toggle("hidden");
            menuCloseIcon.classList.toggle("hidden");
            mobileMenuBtn.setAttribute("aria-expanded", String(isOpening));
            mobileMenuBtn.setAttribute("aria-label", isOpening ? "Close menu" : "Open menu");
        });
    })();
</script>
