<?php

require_once 'config/config.php';

?>

<?php require_once 'includes/head.php'; ?>

<?php require_once 'includes/navbar.php'; ?>


<!-- ================================
     HERO SECTION
================================ -->

<section class="min-h-[620px] bg-white">

    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[620px]">

            <!-- LEFT CONTENT -->

            <div class="pt-10 lg:pt-0">

                <h1 class="text-5xl lg:text-6xl font-semibold text-[#8c2d7d] leading-tight">
                    Mock-API
                </h1>


                <h2 class="mt-2 text-xl lg:text-2xl text-gray-900">
                    Realistic E-Commerce Data. Instantly.
                </h2>


                <p class="mt-7 text-lg text-gray-700 leading-8 max-w-xl">
                    A free RESTful API for developers, ideal for
                    prototyping, testing, or teaching.
                    <br>
                    No backend setup required.
                </p>


                <!-- BUTTONS -->

                <div class="flex flex-wrap gap-6 mt-7">

                    <a href="<?= $docsUrl ?>"
                        class="inline-flex items-center justify-center px-9 py-3.5 rounded-full bg-[#8c2d7d] text-white text-lg font-medium hover:bg-[#762368] transition">
                        Read Docs
                    </a>


                    <a href="https://github.com/kingtech8001/kt_prod_mock_api_php" target="_blank"
                        class="inline-flex items-center justify-center px-9 py-3.5 rounded-full border border-[#8c2d7d] text-[#8c2d7d] text-lg font-medium hover:bg-[#8c2d7d] hover:text-white transition">
                        View on Github
                    </a>

                </div>

            </div>


            <!-- RIGHT ILLUSTRATION -->

            <div class="flex justify-center lg:justify-end">

                <div class="relative w-full max-w-xl">

                    <!-- Decorative background -->

                    <div class="absolute inset-8 bg-[#f5e8f1] rounded-[45%]"></div>


                    <!-- Browser illustration -->

                    <div
                        class="relative z-10 mx-auto w-[310px] sm:w-[360px] bg-white border-4 border-[#eadde7] shadow-sm">

                        <!-- Browser top -->

                        <div class="h-8 bg-[#e3d0df] flex items-center gap-1.5 px-3">

                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                            <span class="w-2 h-2 rounded-full bg-white"></span>

                        </div>


                        <!-- Browser content -->

                        <div class="p-5">

                            <div class="h-2 bg-[#eadce7] w-full"></div>

                            <div class="h-2 bg-[#8c2d7d] w-12 ml-auto -mt-2"></div>


                            <div class="grid grid-cols-2 gap-4 mt-8">

                                <?php for ($i = 0; $i < 6; $i++): ?>

                                    <div class="relative h-24 bg-[#f5f3f5] flex items-center justify-center">

                                        <div class="w-16 h-14 bg-[#a66a9b] rounded-t-[45%] rounded-b-md opacity-90"></div>

                                        <div
                                            class="absolute bottom-2 right-2 w-5 h-5 rounded-full bg-green-500 text-white text-xs flex items-center justify-center">
                                            ✓
                                        </div>

                                    </div>

                                <?php endfor; ?>

                            </div>

                        </div>

                    </div>


                    <!-- Person -->

                    <div class="absolute z-20 bottom-0 left-[18%]">

                        <div class="w-10 h-10 rounded-full bg-[#303044]"></div>

                        <div class="w-14 h-32 bg-[#f15b78] rounded-t-[30px] -mt-2"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= API DEMO ================= -->
<section id="api-demo" class="py-16 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <!-- Heading -->
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-[#92278f]">
                Tired of fake JSON and Lorem Ipsum?
            </h2>

            <p class="mt-4 text-lg text-gray-700">
                Mock-API gives you clean, realistic e-commerce data:
                products, carts, users, and more, accessible in seconds via REST.
            </p>
        </div>


        <!-- API Demo Box -->
        <div class="bg-[#211b1f] rounded-2xl p-8 md:p-10">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- ================= LEFT ================= -->
                <div>

                    <h3 class="text-xl font-semibold text-orange-300 mb-5">
                        Example Request
                    </h3>


                    <!-- Tabs -->
                    <div class="flex flex-wrap gap-3 mb-5">

                        <button onclick="showCode('javascript')" id="tab-javascript" class="code-tab active-tab">
                            Javascript
                        </button>

                        <button onclick="showCode('python')" id="tab-python" class="code-tab">
                            Python
                        </button>

                        <button onclick="showCode('curl')" id="tab-curl" class="code-tab">
                            Curl
                        </button>

                        <button onclick="showCode('java')" id="tab-java" class="code-tab">
                            Java
                        </button>

                        <button onclick="showCode('dart')" id="tab-dart" class="code-tab">
                            Dart
                        </button>

                    </div>


                    <!-- Code Container -->
                    <div class="relative">

                        <pre id="code-box" class="api-code"><code id="code-content"></code></pre>


                        <!-- Copy -->
                        <button id="copy-btn" onclick="copyCode()"
                            class="absolute bottom-4 right-4 w-12 h-12 rounded-full bg-orange-400 hover:bg-orange-500 flex items-center justify-center transition">

                            <svg id="copy-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">

                                <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>

                            </svg>

                        </button>
                    </div>

                </div>


                <!-- ================= RIGHT ================= -->
                <div>

                    <h3 class="text-xl font-semibold text-orange-300 mb-5">
                        API Response
                    </h3>


                    <div class="api-code response-box">
                        <code>{
  <span class="json-key">"id"</span>: <span class="json-number">1</span>,

  <span class="json-key">"title"</span>:
  <span class="json-string">"Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops"</span>,

  <span class="json-key">"price"</span>: <span class="json-number">109.95</span>,

  <span class="json-key">"description"</span>:
  <span class="json-string">"Your perfect pack for everyday use and walks in the city."</span>,

  <span class="json-key">"category"</span>:
  <span class="json-string">"men's clothing"</span>,

  <span class="json-key">"rating"</span>: {
    <span class="json-key">"rate"</span>: <span class="json-number">3.9</span>,
    <span class="json-key">"count"</span>: <span class="json-number">120</span>
  }
}</code>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ==================== RESOURCES ==================== -->
<section class="max-w-6xl mx-auto px-4 py-16">

    <div class="border border-pink-200 rounded-2xl px-6 md:px-10 py-10 bg-white">

        <!-- Heading -->
        <div class="text-center mb-10">

            <h2 class="text-3xl font-semibold text-purple-700">
                Resources
            </h2>

            <p class="mt-4 text-gray-700 text-lg leading-relaxed max-w-4xl mx-auto">
                Mock-API provides the 4 essential resources commonly used in
                e-commerce apps. Use them to build realistic flows for your
                frontend or backend prototypes.
            </p>

        </div>


        <!-- Resources List -->
        <div class="max-w-4xl mx-auto space-y-3">

            <!-- Products -->
            <div class="flex items-center justify-between
                        bg-pink-50/50
                        rounded-2xl
                        px-5 py-5">

                <div>
                    <h3 class="text-xl text-gray-900">
                        Products
                    </h3>

                    <p class="text-gray-400 mt-1">
                        List of items with title, price, category, description and image
                    </p>
                </div>

                <span class="text-gray-900 whitespace-nowrap ml-6">
                    20 products
                </span>

            </div>


            <!-- Cart -->
            <div class="flex items-center justify-between
                        bg-pink-50/50
                        rounded-2xl
                        px-5 py-5">

                <div>
                    <h3 class="text-xl text-gray-900">
                        Cart
                    </h3>

                    <p class="text-gray-400 mt-1">
                        Includes full user profiles (name, email, address, etc.)
                    </p>
                </div>

                <span class="text-gray-900 whitespace-nowrap ml-6">
                    20 carts
                </span>

            </div>


            <!-- Users -->
            <div class="flex items-center justify-between
                        bg-pink-50/50
                        rounded-2xl
                        px-5 py-5">

                <div>
                    <h3 class="text-xl text-gray-900">
                        Users
                    </h3>

                    <p class="text-gray-400 mt-1">
                        Simulates shopping cart logic: user + list of product IDs + quantity
                    </p>
                </div>

                <span class="text-gray-900 whitespace-nowrap ml-6">
                    10 users
                </span>

            </div>


            <!-- Login Token -->
            <div class="flex items-center justify-between
                        bg-pink-50/50
                        rounded-2xl
                        px-5 py-5">

                <div>
                    <h3 class="text-xl text-gray-900">
                        Login Token
                    </h3>

                    <p class="text-gray-400 mt-1">
                        Returns a JWT token for testing authentication
                    </p>
                </div>

                <span class="text-gray-900 whitespace-nowrap ml-6">
                    JWT token
                </span>

            </div>

        </div>


        <!-- Docs Button -->
        <div class="flex justify-center mt-5">

            <a href="<?= $docsUrl ?>" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-purple-600 text-white font-semibold hover:bg-purple-700 transition">
                View details on doc
            </a>

        </div>

    </div>

</section>

<!-- ==================== TESTIMONIALS / DEVELOPERS ==================== -->
<section class="py-20 overflow-hidden max-w-6xl mx-auto">

    <!-- Heading -->
    <div class="text-center max-w-5xl mx-auto px-4">

        <h2 class="text-3xl md:text-4xl font-semibold text-purple-700">
            ❤️ Loved by Millions of Developers
        </h2>

        <p class="mt-5 text-gray-700 text-lg leading-relaxed">
            This is just a small glimpse of your incredible support.
            so much more kindness and trust has been shared over these
            <strong>5 amazing years</strong> with Mock-API.
        </p>

        <p class="mt-1 text-gray-700 text-lg">
            Thank you for being part of this journey!
        </p>

    </div>


    <!-- Slider -->
    <div class="relative mt-10">

        <!-- Left fade -->
        <div class="absolute left-0 top-0 bottom-0 w-24
                    bg-gradient-to-r from-white to-transparent
                    z-10 pointer-events-none">
        </div>

        <!-- Right fade -->
        <div class="absolute right-0 top-0 bottom-0 w-24
                    bg-gradient-to-l from-white to-transparent
                    z-10 pointer-events-none">
        </div>


        <!-- Moving Track -->
        <div id="testimonialTrack" class="flex gap-5 w-max">

            <!-- CARD 1 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>Olitt Blog</span>

                    <span class="text-xl">◼</span>
                </div>

                <div class="testimonial-body">
                    <p>
                        5 Best Free eCommerce APIs and Store API
                        Solutions for Developers: <strong>Fake Store API</strong>
                        is a fantastic option for developers looking to
                        create a shopping website prototype or test their
                        e-commerce applications.
                    </p>
                </div>

                <div class="testimonial-date">
                    2024-05-11
                </div>

            </div>


            <!-- CARD 2 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>PeanutSquare</span>

                    <span class="text-xs">
                        ▣
                    </span>
                </div>

                <div class="testimonial-body">
                    <p>
                        Exploring <strong>Mock-API</strong> for
                        ecommerce apps – essential component for our
                        Flutter ecommerce app.
                    </p>
                </div>

                <div class="testimonial-date">
                    2023-07-21
                </div>

            </div>


            <!-- CARD 3 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>Sami Maxhuni</span>

                    <span class="text-blue-600 font-bold">
                        in
                    </span>
                </div>

                <div class="testimonial-body">
                    <p>
                        🚀 New Feature in ChatWise: Now Powered by
                        Model Context Protocol (MCP) ... In this example,
                        we've integrated the <strong>Mock-API</strong>.
                        ✅ Fetch product data in real time
                        ✅ Display the results in a beautiful interactive
                        carousel
                        ✅ Filter the results via natural language –
                        e.g., "Show me all products under 50 USD"
                    </p>
                </div>

                <div class="testimonial-date">
                    2025-07-24
                </div>

            </div>


            <!-- CARD 4 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>Chris Staudinger</span>
                </div>

                <div class="testimonial-body">
                    <p>
                        An e-commerce store is a great project to have
                        and it just got easier with Mock-API.
                    </p>

                    <p class="mt-4">
                        Forget about spinning up the backend database
                        and focus on building carts, wishlists,
                        recommendations and other functionalities.
                    </p>

                    <p class="mt-4 text-blue-500">
                        👉 https://fakestoreapi.com
                    </p>
                </div>

                <div class="testimonial-date">
                    2023-07-20
                </div>

            </div>


            <!-- DUPLICATE CARDS FOR SEAMLESS LOOP -->

            <!-- CARD 1 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>Olitt Blog</span>
                    <span class="text-xl">◼</span>
                </div>

                <div class="testimonial-body">
                    <p>
                        5 Best Free eCommerce APIs and Store API
                        Solutions for Developers: <strong>Mock-API</strong>
                        is a fantastic option for developers looking to
                        create a shopping website prototype or test their
                        e-commerce applications.
                    </p>
                </div>

                <div class="testimonial-date">
                    2024-05-11
                </div>

            </div>


            <!-- CARD 2 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>PeanutSquare</span>

                    <span class="text-xs">
                        ▣
                    </span>
                </div>

                <div class="testimonial-body">
                    <p>
                        Exploring <strong>Mock-API</strong> for
                        ecommerce apps – essential component for our
                        Flutter ecommerce app.
                    </p>
                </div>

                <div class="testimonial-date">
                    2023-07-21
                </div>

            </div>


            <!-- CARD 3 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>Sami Maxhuni</span>

                    <span class="text-blue-600 font-bold">
                        in
                    </span>
                </div>

                <div class="testimonial-body">
                    <p>
                        🚀 New Feature in ChatWise: Now Powered by
                        Model Context Protocol (MCP) ... In this example,
                        we've integrated the <strong>Mock-API</strong>.
                        ✅ Fetch product data in real time
                        ✅ Display the results in a beautiful interactive
                        carousel
                        ✅ Filter the results via natural language.
                    </p>
                </div>

                <div class="testimonial-date">
                    2025-07-24
                </div>

            </div>


            <!-- CARD 4 -->
            <div class="testimonial-card">

                <div class="testimonial-header">
                    <span>Chris Staudinger</span>
                </div>

                <div class="testimonial-body">
                    <p>
                        An e-commerce store is a great project to have
                        and it just got easier with Mock-API.
                    </p>

                    <p class="mt-4">
                        Forget about spinning up the backend database
                        and focus on building carts, wishlists,
                        recommendations and other functionalities.
                    </p>

                    <p class="mt-4 text-blue-500">
                        👉 https://fakestoreapi.com
                    </p>
                </div>

                <div class="testimonial-date">
                    2023-07-20
                </div>

            </div>

        </div>

    </div>

</section>

<style>
    /* ================= API DEMO ================= */

    .code-tab {
        padding: 10px 18px;
        border: 1px solid #8b7f86;
        border-radius: 8px;
        background: transparent;
        color: white;
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .code-tab:hover {
        background: #3a3035;
    }

    .active-tab {
        background: #f7d7a4 !important;
        color: #171717 !important;
        border-color: #f7d7a4 !important;
    }


    /* Code box */

    .api-code {
        background: #292e36;
        border-radius: 10px;
        padding: 24px;

        min-height: 185px;
        max-height: 390px;

        overflow-x: auto;
        overflow-y: auto;

        font-family: Consolas, Monaco, "Courier New", monospace;
        font-size: 15px;
        line-height: 1.7;

        color: #d5d9df;

        white-space: pre;
        margin: 0;
    }

    /* Response */

    .response-box {
        height: 390px;
        overflow-y: hidden;
    }


    /* JSON colors */

    .json-key {
        color: #a8c77b;
    }

    .json-string {
        color: #7edc91;
    }

    .json-number {
        color: #ffd42a;
    }


    /* Scrollbar */

    .api-code::-webkit-scrollbar {
        height: 7px;
    }

    .api-code::-webkit-scrollbar-track {
        background: #252932;
    }

    .api-code::-webkit-scrollbar-thumb {
        background: #777;
        border-radius: 10px;
    }


    @media (max-width: 768px) {

        .api-code {
            font-size: 13px;
            padding: 18px;
        }

        .response-box {
            height: 320px;
        }

    }

    .testimonial-card {
        width: 320px;
        min-width: 320px;
        height: 200px;

        border: 1px solid #eadde7;
        border-radius: 18px;

        background: #fff;

        overflow: hidden;

        display: flex;
        flex-direction: column;

        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .testimonial-header {
        height: 42px;

        padding: 0 12px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        color: #96327f;
        font-size: 16px;
    }

    .testimonial-body {
        flex: 1;

        padding: 10px 12px;

        background: #ffffff;

        color: #444;

        font-size: 15px;
        line-height: 1.25;

        overflow: hidden;
    }

    .testimonial-date {
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: flex-end;

        padding: 0 12px;

        background: #fafafa;

        color: #444;

        font-size: 14px;
    }

    #testimonialTrack {
        animation: testimonialSlide 28s linear infinite;
    }

    #testimonialTrack:hover {
        animation-play-state: paused;
    }

    @keyframes testimonialSlide {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(calc(-50% - 10px));
        }
    }

    @media (max-width: 768px) {
        .testimonial-card {
            width: 290px;
            min-width: 290px;
        }

        #testimonialTrack {
            animation-duration: 24s;
        }
    }
</style>


<script>

    const codeSamples = {

        javascript: `fetch('https://mockapi.kingtechnology.in/api/products.php?id=1')
    .then(res => res.json())
    .then(json => console.log(json))`,

        python: `import requests

response = requests.get(
    'https://mockapi.kingtechnology.in/api/products.php?id=1'
)

print(response.json())`,

        curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/products.php?id=1'`,

        java: `URL url = new URL(
    "https://mockapi.kingtechnology.in/api/products.php?id=1"
);

HttpURLConnection con =
    (HttpURLConnection) url.openConnection();

con.setRequestMethod("GET");
con.connect();

BufferedReader in =
    new BufferedReader(
        new InputStreamReader(con.getInputStream())
    );

String line;

while ((line = in.readLine()) != null) {
    System.out.println(line);
}

in.close();`,

        dart: `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/products.php?id=1'),
  );

  print(jsonDecode(response.body));
}`

    };


    let currentLanguage = "javascript";


    function showCode(language) {

        currentLanguage = language;

        document.getElementById("code-content").textContent =
            codeSamples[language];


        /* Remove active from all tabs */

        document.querySelectorAll(".code-tab").forEach(tab => {
            tab.classList.remove("active-tab");
        });


        /* Add active */

        document
            .getElementById("tab-" + language)
            .classList.add("active-tab");
    }

    async function copyCode() {

        const button = document.getElementById("copy-btn");

        try {

            await navigator.clipboard.writeText(
                codeSamples[currentLanguage]
            );

            // Show tick
            button.innerHTML = `
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="white"
                stroke-width="3">

                <path d="M5 12l4 4L19 6"></path>

            </svg>
        `;

            button.classList.remove("bg-orange-400");
            button.classList.add("bg-green-500");


            // Back to copy icon after 2 seconds
            setTimeout(() => {

                button.innerHTML = `
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="white"
                    stroke-width="2">

                    <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>

                </svg>
            `;

                button.classList.remove("bg-green-500");
                button.classList.add("bg-orange-400");

            }, 1000);

        } catch (error) {

            console.error("Copy failed:", error);

        }
    }


    /* Default */

    showCode("javascript");

</script>

<?php require_once 'includes/footer.php'; ?>