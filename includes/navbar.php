<nav class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <!-- Main Navbar -->
        <div class="flex items-center justify-between py-5">

            <!-- Logo -->
            <a href="https://mockapi.kingtechnology.in/index.php"
                class="flex items-center">

                <div class="w-14 h-14 bg-[#8c2d7d] rounded-lg flex items-center justify-center">

                    <span class="text-white text-3xl font-bold">
                        { }
                    </span>

                </div>

            </a>


            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-10 lg:gap-16">

                <a href="https://mockapi.kingtechnology.in/index.php"
                    class="text-gray-600 hover:text-[#8c2d7d] transition">
                    Home
                </a>

                <a href="https://mockapi.kingtechnology.in/docs.php"
                    class="text-gray-600 hover:text-[#8c2d7d] transition">
                    Docs
                </a>

                <a href="https://github.com/kingtech8001/kt_prod_mock_api_php"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-gray-600 hover:text-[#8c2d7d] transition">
                    GitHub
                </a>


            </div>


            <!-- Mobile Menu Button -->
            <button
                id="mobile-menu-btn"
                type="button"
                class="md:hidden w-11 h-11 flex items-center justify-center
                       rounded-lg border border-gray-200
                       text-gray-700 hover:text-[#8c2d7d]
                       hover:border-[#8c2d7d]
                       transition"
                aria-label="Open menu">

                <!-- Hamburger -->
                <svg id="menu-open-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16" />

                </svg>


                <!-- Close -->
                <svg id="menu-close-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 hidden"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />

                </svg>

            </button>

        </div>


        <!-- Mobile Navigation -->
        <div id="mobile-menu"
            class="md:hidden hidden border-t border-gray-100 py-4">

            <div class="flex flex-col space-y-1">

                <a href="https://mockapi.kingtechnology.in/index.php"
                    class="px-4 py-3 rounded-lg text-gray-600
                           hover:bg-gray-50 hover:text-[#8c2d7d]
                           transition">
                    Home
                </a>

                <a href="https://mockapi.kingtechnology.in/docs.php"
                    class="px-4 py-3 rounded-lg text-gray-600
                           hover:bg-gray-50 hover:text-[#8c2d7d]
                           transition">
                    Docs
                </a>

                <a href="https://github.com/kingtech8001/kt_prod_mock_api_php"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-4 py-3 rounded-lg text-gray-600
                           hover:bg-gray-50 hover:text-[#8c2d7d]
                           transition">
                    GitHub
                </a>

            </div>

        </div>

    </div>

</nav>


<script>

    const mobileMenuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    const menuOpenIcon = document.getElementById("menu-open-icon");
    const menuCloseIcon = document.getElementById("menu-close-icon");

    mobileMenuBtn.addEventListener("click", function () {

        mobileMenu.classList.toggle("hidden");

        menuOpenIcon.classList.toggle("hidden");
        menuCloseIcon.classList.toggle("hidden");

    });

</script>