<?php
/*
|--------------------------------------------------------------------------
| Mock-API API Documentation
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/config/config.php';
$activePage = 'docs';

// Base URL of the project (works on localhost/fakestoreapi AND on the live domain)
if (!isset($baseUrl)) {
    $docRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $projectRoot = str_replace('\\', '/', realpath(__DIR__));
    $baseUrl = rtrim(str_replace($docRoot, '', $projectRoot), '/');
}
$homeUrl = $baseUrl !== '' ? $baseUrl . '/' : '/';
$docsUrl = $baseUrl !== '' ? $baseUrl . '/docs' : '/docs';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MockAPI Docs</title>
    <link rel="icon" type="image/png" href="<?= $baseUrl ?>/assets/images/logo/mockapicv.png">
    <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/images/logo/mockapicv.png">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#faf4fa',
                            100: '#f3e4f2',
                            200: '#e7c9e5',
                            300: '#d39dce',
                            400: '#bd70b5',
                            500: '#92278f',
                            600: '#7d207b',
                            700: '#681a66',
                            800: '#541651',
                            900: '#40113d'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* Hide scrollbar but keep scrolling */
        .hide-scrollbar::-webkit-scrollbar {
            width: 0;
        }

        .hide-scrollbar {
            scrollbar-width: none;
        }

        /* Code scrollbar */
        .code-scroll::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }

        .code-scroll::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 10px;
        }

        /* Active navigation */
        .sidebar-item.active {
            background: #f1f1f1;
            color: #92278f;
            border-left-color: #92278f;
            font-weight: 500;
        }

        .sidebar-item {
            border-left: 3px solid transparent;
        }

        /* Section offset */
        .doc-section {
            scroll-margin-top: 95px;
        }

        /* Method badges */
        .method-badge {
            min-width: 36px;
            text-align: center;
        }

        /* Request sample tabs */
        .request-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
        }

        .request-tab {
            border: 1px solid #334155;
            background: #182126;
            color: #cbd5e1;
            padding: 8px 13px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .18s ease;
        }

        .request-tab:hover {
            background: #24323a;
            color: white;
        }

        .request-tab.active {
            background: #111827;
            color: white;
            border-color: #64748b;
        }

        .request-code {
            background: #0b1013;
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
            max-height: 390px;
        }

        .request-code pre {
            margin: 0;
            padding: 38px 48px 8px 0;
            box-sizing: border-box;
            white-space: pre;
            font-size: 13px;
            line-height: 1.65;
            color: #e5e7eb;
            font-family: Consolas, Monaco, "Courier New", monospace;
        }

        .request-code {
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .request-code pre {
            min-width: max-content;
        }

        .request-code {
            position: relative;
        }

        .request-copy-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #334155;
            border-radius: 6px;
            background: #182126;
            color: #cbd5e1;
            cursor: pointer;
            transition: all .18s ease;
            z-index: 2;
        }

        .request-copy-btn:hover {
            background: #26323a;
            color: white;
        }

        .request-copy-btn.copied {
            background: #16a34a;
            border-color: #16a34a;
            color: white;
        }
    </style>
</head>


<body class="bg-white text-gray-800">

    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <!-- ====================================================================== -->
    <!-- MOBILE DOCS SUB-BAR -->
    <!-- ====================================================================== -->

    <div class="lg:hidden sticky top-[80px] z-30 bg-gray-50/95 backdrop-blur border-b border-gray-200 px-4 py-2.5 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-600">API Documentation</span>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-100 text-[#8c2d7d] font-semibold border border-purple-200">v2.1.11</span>
        </div>
        <button id="mobileMenuButton" type="button" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-xs font-medium text-gray-700 hover:text-[#8c2d7d] hover:border-[#8c2d7d] shadow-sm transition" aria-label="Open documentation endpoints menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#8c2d7d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
            <span>Endpoints</span>
        </button>
    </div>

    <!-- ====================================================================== -->
    <!-- MAIN LAYOUT -->
    <!-- ====================================================================== -->

    <div class="min-h-screen flex">

        <!-- ================================================================== -->
        <!-- LEFT SIDEBAR -->
        <!-- ================================================================== -->

        <aside id="sidebar" class="
            fixed lg:sticky
            top-0 lg:top-[80px] left-0
            z-50 lg:z-40
            h-screen lg:h-[calc(100vh-80px)]
            w-[285px]
            bg-white
            border-r border-gray-200
            flex flex-col
            -translate-x-full lg:translate-x-0
            transition-transform duration-300
            shadow-xl lg:shadow-none
        ">

            <!-- Sidebar Header -->
            <div class="h-14 flex items-center justify-between px-6 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#8c2d7d]"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-700">API Reference</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-purple-50 text-[#8c2d7d] border border-purple-200">v2.1.11</span>
                    <button id="mobileMenuClose" type="button" class="lg:hidden p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-md transition" aria-label="Close endpoints menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search -->

            <div class="px-5 py-4">

                <div class="
                    flex items-center gap-2
                    border border-gray-200
                    rounded-lg
                    px-3
                    py-2
                    focus-within:border-brand-500
                    transition
                ">

                    <span class="text-gray-500">
                        🔍
                    </span>

                    <input id="searchInput" type="text" placeholder="Search..." class="w-full outline-none text-sm">

                </div>

            </div>


            <!-- Navigation -->

            <nav id="sidebarNav" class="
                flex-1
                overflow-y-auto
                hide-scrollbar
                pb-10
            ">


                <!-- ========================================================== -->
                <!-- PRODUCTS -->
                <!-- ========================================================== -->

                <div class="sidebar-group">

                    <button class="
                        group-title
                        w-full
                        flex
                        items-center
                        justify-between
                        px-6
                        py-3
                        text-gray-800
                        font-medium
                        hover:bg-gray-50
                    " data-group="products">

                        <span class="flex items-center gap-2">
                            🛒
                            Products
                        </span>

                        <span class="group-arrow transition">
                            ⌄
                        </span>

                    </button>


                    <div class="group-items">

                        <!-- GET ALL PRODUCTS -->

                        <a href="#get-all-products" data-target="get-all-products" class="
                            sidebar-item
                            flex
                            items-center
                            gap-2
                            px-6
                            py-3
                            text-sm
                            text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span class="
                                method-badge
                                text-[10px]
                                font-bold
                                px-1.5
                                py-0.5
                                rounded
                                bg-green-100
                                text-green-700
                            ">
                                GET
                            </span>

                            <span>
                                Get all products
                            </span>

                        </a>


                        <!-- ADD PRODUCT -->

                        <a href="#add-product" data-target="add-product" class="
                            sidebar-item
                            flex
                            items-center
                            gap-2
                            px-6
                            py-3
                            text-sm
                            text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span class="
                                method-badge
                                text-[10px]
                                font-bold
                                px-1.5
                                py-0.5
                                rounded
                                bg-blue-100
                                text-blue-700
                            ">
                                POST
                            </span>

                            Add a new product

                        </a>


                        <!-- GET SINGLE PRODUCT -->

                        <a href="#get-product" data-target="get-product" class="
                            sidebar-item
                            flex
                            items-center
                            gap-2
                            px-6
                            py-3
                            text-sm
                            text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span class="
                                method-badge
                                text-[10px]
                                font-bold
                                px-1.5
                                py-0.5
                                rounded
                                bg-green-100
                                text-green-700
                            ">
                                GET
                            </span>

                            Get a single product

                        </a>


                        <!-- UPDATE PRODUCT -->

                        <a href="#update-product" data-target="update-product" class="
                            sidebar-item
                            flex
                            items-center
                            gap-2
                            px-6
                            py-3
                            text-sm
                            text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span class="
                                method-badge
                                text-[10px]
                                font-bold
                                px-1.5
                                py-0.5
                                rounded
                                bg-purple-100
                                text-purple-700
                            ">
                                PUT
                            </span>

                            Update a product

                        </a>


                        <!-- DELETE PRODUCT -->

                        <a href="#delete-product" data-target="delete-product" class="
                            sidebar-item
                            flex
                            items-center
                            gap-2
                            px-6
                            py-3
                            text-sm
                            text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span class="
                                method-badge
                                text-[10px]
                                font-bold
                                px-1.5
                                py-0.5
                                rounded
                                bg-red-100
                                text-red-700
                            ">
                                DEL
                            </span>

                            Delete a product

                        </a>

                    </div>

                </div>



                <!-- ========================================================== -->
                <!-- CARTS -->
                <!-- ========================================================== -->

                <div class="sidebar-group mt-1">

                    <button class="
                        group-title
                        w-full
                        flex
                        items-center
                        justify-between
                        px-6
                        py-3
                        text-gray-800
                        font-medium
                        hover:bg-gray-50
                    " data-group="carts">

                        <span class="flex items-center gap-2">
                            🛍️
                            Carts
                        </span>

                        <span class="group-arrow transition">
                            ⌄
                        </span>

                    </button>


                    <div class="group-items">

                        <a href="#get-all-carts" data-target="get-all-carts" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-700">
                                GET
                            </span>

                            Get all carts

                        </a>


                        <a href="#add-cart" data-target="add-cart" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">
                                POST
                            </span>

                            Add a new cart

                        </a>


                        <a href="#get-cart" data-target="get-cart" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-700">
                                GET
                            </span>

                            Get a single cart

                        </a>


                        <a href="#update-cart" data-target="update-cart" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-700">
                                PUT
                            </span>

                            Update a cart

                        </a>


                        <a href="#delete-cart" data-target="delete-cart" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-red-100 text-red-700">
                                DEL
                            </span>

                            Delete a cart

                        </a>

                    </div>

                </div>



                <!-- ========================================================== -->
                <!-- USERS -->
                <!-- ========================================================== -->

                <div class="sidebar-group mt-1">

                    <button class="
                        group-title
                        w-full
                        flex
                        items-center
                        justify-between
                        px-6
                        py-3
                        text-gray-800
                        font-medium
                        hover:bg-gray-50
                    " data-group="users">

                        <span class="flex items-center gap-2">
                            👤
                            Users
                        </span>

                        <span class="group-arrow transition">
                            ⌄
                        </span>

                    </button>


                    <div class="group-items">

                        <a href="#get-all-users" data-target="get-all-users" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-700">
                                GET
                            </span>

                            Get all users

                        </a>


                        <a href="#add-user" data-target="add-user" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">
                                POST
                            </span>

                            Add a new user

                        </a>


                        <a href="#get-user" data-target="get-user" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-700">
                                GET
                            </span>

                            Get a single user

                        </a>


                        <a href="#update-user" data-target="update-user" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-700">
                                PUT
                            </span>

                            Update a user

                        </a>


                        <a href="#delete-user" data-target="delete-user" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-red-100 text-red-700">
                                DEL
                            </span>

                            Delete a user

                        </a>

                    </div>

                </div>



                <!-- ========================================================== -->
                <!-- AUTH -->
                <!-- ========================================================== -->

                <div class="sidebar-group mt-1">

                    <button class="
                        group-title
                        w-full
                        flex
                        items-center
                        justify-between
                        px-6
                        py-3
                        text-gray-800
                        font-medium
                        hover:bg-gray-50
                    " data-group="auth">

                        <span class="flex items-center gap-2">
                            🔐
                            Auth
                        </span>

                        <span class="group-arrow transition">
                            ⌄
                        </span>

                    </button>


                    <div class="group-items">

                        <a href="#login" data-target="login" class="
                            sidebar-item
                            flex items-center gap-2
                            px-6 py-3
                            text-sm text-gray-700
                            hover:bg-gray-100
                            hover:text-brand-500
                            transition
                        ">

                            <span
                                class="method-badge text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">
                                POST
                            </span>

                            Login

                        </a>

                    </div>

                </div>

            </nav>


            <!-- Footer -->

            <div class="border-t border-gray-200 px-6 py-4 text-xs text-gray-500">
                API docs by Mock-API
            </div>

        </aside>



        <!-- ================================================================== -->
        <!-- MOBILE OVERLAY -->
        <!-- ================================================================== -->

        <div id="mobileOverlay" class="
            fixed inset-0
            bg-black/40
            backdrop-blur-sm
            z-45
            hidden
            lg:hidden
        "></div>



        <!-- ================================================================== -->
        <!-- MAIN CONTENT -->
        <!-- ================================================================== -->

        <main class="flex-1 min-w-0">


            <!-- =============================================================== -->
            <!-- DOCUMENTATION HEADER -->
            <!-- =============================================================== -->

            <div class="max-w-7xl mx-auto px-8 md:px-12 py-16">


                <div class="max-w-4xl">

                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800">
                        Mock-API
                        <span class="text-gray-500">
                            (v2.1.11)
                        </span>
                    </h1>


                    <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-gray-600">

                        <span>
                            E-mail:
                            <a href="mailto: info@kingtechnology.in" class="text-brand-500 hover:underline">
                                info@kingtechnology.in
                            <a href="mailto: info@kingtechnology.in" class="text-brand-500 hover:underline">
                                info@kingtechnology.in
                            </a>
                        </span>

                        <span>|</span>

                        <span>
                            URL:
                            <span class="text-brand-500">
                                https://mockapi.kingtechnology.in
                            </span>
                        </span>

                    </div>


                    <p class="mt-6 text-lg text-gray-600">
                        A free Mock API for testing and prototyping
                        e-commerce applications.
                    </p>

                </div>



                <!-- =========================================================== -->
                <!-- PRODUCTS TITLE -->
                <!-- =========================================================== -->

                <div class="mt-20 mb-14">

                    <h2 class="text-3xl font-bold text-gray-800">
                        🛒 Products
                    </h2>

                </div>



                <!-- =========================================================== -->
                <!-- GET ALL PRODUCTS -->
                <!-- =========================================================== -->

                <section id="get-all-products" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Get all products
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Retrieve a list of all available products.
                        </p>


                        <div class="mt-12">

                            <h3 class="text-xl font-semibold text-gray-800 mb-5">
                                Responses
                            </h3>

                            <div class="border rounded-lg overflow-hidden">

                                <div class="bg-green-50 text-green-700 px-5 py-4 font-medium">
                                    ✓ 200 Success
                                </div>

                                <div class="bg-red-50 text-red-600 px-5 py-4">
                                    — 400 Bad request
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="flex items-center gap-3 px-5 py-4">

                                <span class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    GET
                                </span>

                                <span class="text-white font-mono">
                                    /products
                                </span>

                            </div>


                            <div class="border-t border-gray-700 p-5">

                                <div class="text-gray-400 text-sm mb-3">
                                    Request sample
                                </div>

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/products.php')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- ADD PRODUCT -->
                <!-- =========================================================== -->

                <section id="add-product" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Add a new product
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Create a new product.
                        </p>


                        <div class="mt-10">

                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                                Request body schema
                            </h3>

                            <div class="mt-4 border rounded-lg overflow-hidden">

                                <div class="flex border-b">
                                    <div class="w-1/2 px-5 py-4 text-gray-700 font-medium">id</div>
                                    <div class="w-1/2 px-5 py-4 text-gray-500">integer</div>
                                </div>

                                <div class="flex border-b">
                                    <div class="w-1/2 px-5 py-4 text-gray-700 font-medium">title</div>
                                    <div class="w-1/2 px-5 py-4 text-gray-500">string</div>
                                </div>

                                <div class="flex border-b">
                                    <div class="w-1/2 px-5 py-4 text-gray-700 font-medium">price</div>
                                    <div class="w-1/2 px-5 py-4 text-gray-500">number &lt;float&gt;</div>
                                </div>

                                <div class="flex border-b">
                                    <div class="w-1/2 px-5 py-4 text-gray-700 font-medium">description</div>
                                    <div class="w-1/2 px-5 py-4 text-gray-500">string</div>
                                </div>

                                <div class="flex border-b">
                                    <div class="w-1/2 px-5 py-4 text-gray-700 font-medium">category</div>
                                    <div class="w-1/2 px-5 py-4 text-gray-500">string</div>
                                </div>

                                <div class="flex">
                                    <div class="w-1/2 px-5 py-4 text-gray-700 font-medium">image</div>
                                    <div class="w-1/2 px-5 py-4 text-gray-500">string &lt;uri&gt;</div>
                                </div>

                            </div>

                        </div>


                        <div class="mt-10">

                            <h3 class="text-xl font-semibold text-gray-800 mb-5">
                                Responses
                            </h3>

                            <div class="border rounded-lg overflow-hidden">

                                <div class="bg-green-50 text-green-700 px-5 py-4">
                                    201 Product created successfully
                                </div>

                                <div class="bg-red-50 text-red-600 px-5 py-4">
                                    400 Bad request
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    POST
                                </span>

                                <span class="text-white font-mono">
                                    /products
                                </span>

                            </div>


                            <div class="border-t border-gray-700 p-5">

                                <div class="text-gray-400 text-sm mb-3">
                                    Content type
                                </div>

                                <div class="bg-[#182126] text-gray-200 p-4 rounded mb-4 font-mono text-sm">
                                    application/json
                                </div>


                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>{
    "title": "string",
    "price": 0.1,
    "description": "string",
    "category": "string",
    "image": "https://example.com/image.jpg"
}</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- GET SINGLE PRODUCT -->
                <!-- =========================================================== -->

                <section id="get-product" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Get a single product
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Retrieve details of a specific product by ID.
                        </p>


                        <div class="mt-10">

                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                                Path parameters
                            </h3>

                            <div class="mt-4 border rounded-lg">

                                <div class="grid grid-cols-2 px-5 py-5">

                                    <div class="font-medium">
                                        id
                                        <span class="block text-xs text-red-500">
                                            required
                                        </span>
                                    </div>

                                    <div class="text-gray-500">
                                        integer
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="mt-10">

                            <h3 class="text-xl font-semibold text-gray-800 mb-5">
                                Responses
                            </h3>

                            <div class="border rounded-lg overflow-hidden">

                                <div class="bg-green-50 text-green-700 px-5 py-4">
                                    200 Success
                                </div>

                                <div class="bg-red-50 text-red-600 px-5 py-4">
                                    400 Bad request
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    GET
                                </span>

                                <span class="text-white font-mono">
                                    /products/{id}
                                </span>

                            </div>


                            <div class="border-t border-gray-700 p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/products.php?id=1')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- UPDATE PRODUCT -->
                <!-- =========================================================== -->

                <section id="update-product" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Update a product
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Update an existing product.
                        </p>

                        <div class="mt-10 border rounded-lg overflow-hidden">

                            <div class="px-5 py-4 border-b font-medium">
                                Required fields
                            </div>

                            <div class="grid grid-cols-2">

                                <div class="px-5 py-4 border-b">
                                    title
                                </div>

                                <div class="px-5 py-4 border-b text-gray-500">
                                    string
                                </div>

                                <div class="px-5 py-4 border-b">
                                    price
                                </div>

                                <div class="px-5 py-4 border-b text-gray-500">
                                    number
                                </div>

                                <div class="px-5 py-4 border-b">
                                    description
                                </div>

                                <div class="px-5 py-4 border-b text-gray-500">
                                    string
                                </div>

                                <div class="px-5 py-4 border-b">
                                    category
                                </div>

                                <div class="px-5 py-4 border-b text-gray-500">
                                    string
                                </div>

                                <div class="px-5 py-4">
                                    image
                                </div>

                                <div class="px-5 py-4 text-gray-500">
                                    string
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-purple-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    PUT
                                </span>

                                <span class="text-white font-mono">
                                    /products/{id}
                                </span>

                            </div>


                            <div class="border-t border-gray-700 p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/products.php?id=1', {
    method: 'PUT',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        "title": "Updated Product",
        "price": 29.99,
        "description": "Updated description",
        "category": "electronics",
        "image": "https://example.com/image.jpg"
    })
})
.then(response =&gt; response.json())
.then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- DELETE PRODUCT -->
                <!-- =========================================================== -->

                <section id="delete-product" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Delete a product
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Delete a product by ID.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-red-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    DEL
                                </span>

                                <span class="text-white font-mono">
                                    /products/{id}
                                </span>

                            </div>


                            <div class="border-t border-gray-700 p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/products.php?id=1', {
    method: 'DELETE'
})
.then(response =&gt; response.json())
.then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- CARTS TITLE -->
                <!-- =========================================================== -->

                <div class="mt-20 mb-14">

                    <h2 class="text-3xl font-bold text-gray-800">
                        🛍️ Carts
                    </h2>

                </div>



                <!-- =========================================================== -->
                <!-- GET ALL CARTS -->
                <!-- =========================================================== -->

                <section id="get-all-carts" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Get all carts
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Retrieve a list of all available carts.
                        </p>

                        <div class="mt-10 border rounded-lg overflow-hidden">

                            <div class="bg-green-50 text-green-700 px-5 py-4">
                                200 Success
                            </div>

                            <div class="bg-red-50 text-red-600 px-5 py-4">
                                400 Bad request
                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    GET
                                </span>

                                <span class="text-white font-mono">
                                    /carts
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/carts.php')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- ADD CART -->
                <!-- =========================================================== -->

                <section id="add-cart" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Add a new cart
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Create a new shopping cart.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    POST
                                </span>

                                <span class="text-white font-mono">
                                    /carts
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>{
    "userId": 1,
    "date": "2026-09-22",
    "products": [
        {
            "productId": 1,
            "quantity": 2
        }
    ]
}</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- GET CART -->
                <!-- =========================================================== -->

                <section id="get-cart" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Get a single cart
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Retrieve details of a specific cart by ID.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    GET
                                </span>

                                <span class="text-white font-mono">
                                    /carts/{id}
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/carts.php?id=1')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- UPDATE CART -->
                <!-- =========================================================== -->

                <section id="update-cart" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Update a cart
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Update an existing shopping cart.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-purple-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    PUT
                                </span>

                                <span class="text-white font-mono">
                                    /carts/{id}
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>{
    "userId": 1,
    "date": "2026-09-22",
    "products": [
        {
            "productId": 1,
            "quantity": 5
        }
    ]
}</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- DELETE CART -->
                <!-- =========================================================== -->

                <section id="delete-cart" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Delete a cart
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Delete a cart by ID.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-red-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    DEL
                                </span>

                                <span class="text-white font-mono">
                                    /carts/{id}
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/carts.php?id=1', {
    method: 'DELETE'
})
.then(response =&gt; response.json())
.then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- USERS TITLE -->
                <!-- =========================================================== -->

                <div class="mt-20 mb-14">

                    <h2 class="text-3xl font-bold text-gray-800">
                        👤 Users
                    </h2>

                </div>



                <!-- =========================================================== -->
                <!-- GET ALL USERS -->
                <!-- =========================================================== -->

                <section id="get-all-users" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Get all users
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Retrieve a list of all users.
                        </p>

                        <div class="mt-10 border rounded-lg overflow-hidden">

                            <div class="bg-green-50 text-green-700 px-5 py-4">
                                200 Success
                            </div>

                            <div class="bg-red-50 text-red-600 px-5 py-4">
                                400 Bad request
                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    GET
                                </span>

                                <span class="text-white font-mono">
                                    /users
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/users.php')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- ADD USER -->
                <!-- =========================================================== -->

                <section id="add-user" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Add a new user
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Create a new user.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    POST
                                </span>

                                <span class="text-white font-mono">
                                    /users
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>{
    "username": "john",
    "email": "john@example.com",
    "password": "password123"
}</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- GET USER -->
                <!-- =========================================================== -->

                <section id="get-user" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Get a single user
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Retrieve details of a specific user by ID.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    GET
                                </span>

                                <span class="text-white font-mono">
                                    /users/{id}
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/users.php?id=1')
    .then(response =&gt; response.json())
    .then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- UPDATE USER -->
                <!-- =========================================================== -->

                <section id="update-user" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Update a user
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Update an existing user.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-purple-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    PUT
                                </span>

                                <span class="text-white font-mono">
                                    /users/{id}
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>{
    "username": "updatedUser",
    "email": "updated@example.com",
    "password": "newPassword"
}</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- DELETE USER -->
                <!-- =========================================================== -->

                <section id="delete-user" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Delete a user
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Delete a user by ID.
                        </p>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-red-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    DEL
                                </span>

                                <span class="text-white font-mono">
                                    /users/{id}
                                </span>

                            </div>

                            <div class="p-5">

                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>fetch('https://mockapi.kingtechnology.in/api/users.php?id=1', {
    method: 'DELETE'
})
.then(response =&gt; response.json())
.then(data =&gt; console.log(data));</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                <!-- =========================================================== -->
                <!-- AUTH TITLE -->
                <!-- =========================================================== -->

                <div class="mt-20 mb-14">

                    <h2 class="text-3xl font-bold text-gray-800">
                        🔐 Auth
                    </h2>

                </div>



                <!-- =========================================================== -->
                <!-- LOGIN -->
                <!-- =========================================================== -->

                <section id="login" class="doc-section grid grid-cols-1 xl:grid-cols-2 gap-12 mb-32">

                    <div>

                        <h2 class="text-3xl font-bold text-gray-800">
                            Login
                        </h2>

                        <p class="mt-5 text-lg text-gray-600">
                            Authenticate a user.
                        </p>


                        <div class="mt-10">

                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                                Request body schema
                            </h3>


                            <div class="mt-4 border rounded-lg overflow-hidden">

                                <div class="grid grid-cols-2 border-b">

                                    <div class="px-5 py-4 font-medium">
                                        username
                                    </div>

                                    <div class="px-5 py-4 text-gray-500">
                                        string
                                    </div>

                                </div>


                                <div class="grid grid-cols-2">

                                    <div class="px-5 py-4 font-medium">
                                        password
                                    </div>

                                    <div class="px-5 py-4 text-gray-500">
                                        string
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="mt-10">

                            <h3 class="text-xl font-semibold text-gray-800 mb-5">
                                Responses
                            </h3>

                            <div class="border rounded-lg overflow-hidden">

                                <div class="bg-green-50 text-green-700 px-5 py-4">
                                    200 Login successful
                                </div>

                                <div class="bg-red-50 text-red-600 px-5 py-4">
                                    400 Bad request
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="xl:sticky xl:top-8 h-fit">

                        <div class="bg-[#10171b] rounded-lg overflow-hidden shadow-lg">

                            <div class="px-5 py-4 flex items-center gap-3">

                                <span class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold">
                                    POST
                                </span>

                                <span class="text-white font-mono">
                                    /auth/login
                                </span>

                            </div>


                            <div class="p-5">

                                <div class="text-gray-400 text-sm mb-3">
                                    Content type
                                </div>

                                <div class="bg-[#182126] text-gray-200 p-4 rounded mb-4 font-mono text-sm">
                                    application/json
                                </div>


                                <div class="bg-[#0b1013] rounded-lg p-5 overflow-x-auto code-scroll">

                                    <pre class="text-sm text-gray-200"><code>{
    "username": "string",
    "password": "string"
}</code></pre>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>


            </div>

        </main>

    </div>



    <!-- ====================================================================== -->
    <!-- JAVASCRIPT -->
    <!-- ====================================================================== -->

    <script>


        /*
        |--------------------------------------------------------------------------
        | REQUEST SAMPLE TABS
        |--------------------------------------------------------------------------
        | Adds the same multi-language request samples used by modern API docs:
        | JavaScript (Browser), Node.js (Axios), PHP, cURL and Python.
        */

        const requestSamples = {

            "get-all-products": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/products.php')
    .then(response => response.json())
    .then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.get('https://mockapi.kingtechnology.in/api/products.php')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
$response = file_get_contents('https://mockapi.kingtechnology.in/api/products.php');
$data = json_decode($response, true);
print_r($data);
?>`,
                curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/products.php'`,
                python: `import requests

response = requests.get(
    'https://mockapi.kingtechnology.in/api/products.php'
)
print(response.json())`
            },

            "add-product": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/products.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        title: 'New Product',
        price: 29.99,
        description: 'A new product',
        category: 'electronics',
        image: 'https://example.com/image.jpg'
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.post('https://mockapi.kingtechnology.in/api/products.php', {
    title: 'New Product',
    price: 29.99,
    description: 'A new product',
    category: 'electronics',
    image: 'https://example.com/image.jpg'
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'title' => 'New Product',
    'price' => 29.99,
    'description' => 'A new product',
    'category' => 'electronics',
    'image' => 'https://example.com/image.jpg'
];

$ch = curl_init('https://mockapi.kingtechnology.in/api/products.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X POST 'https://mockapi.kingtechnology.in/api/products.php' \
  -H 'Content-Type: application/json' \
  -d '{"title":"New Product","price":29.99,"description":"A new product","category":"electronics","image":"https://example.com/image.jpg"}'`,
                python: `import requests

data = {
    'title': 'New Product',
    'price': 29.99,
    'description': 'A new product',
    'category': 'electronics',
    'image': 'https://example.com/image.jpg'
}

response = requests.post(
    'https://mockapi.kingtechnology.in/api/products.php',
    json=data
)
print(response.json())`
            },

            "get-product": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/products.php?id=1')
    .then(response => response.json())
    .then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.get('https://mockapi.kingtechnology.in/api/products.php?id=1')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
$response = file_get_contents(
    'https://mockapi.kingtechnology.in/api/products.php?id=1'
);
echo $response;
?>`,
                curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/products.php?id=1'`,
                python: `import requests

response = requests.get(
    'https://mockapi.kingtechnology.in/api/products.php?id=1'
)
print(response.json())`
            },

            "update-product": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/products.php?id=1', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        title: 'Updated Product',
        price: 29.99,
        description: 'Updated description',
        category: 'electronics',
        image: 'https://example.com/image.jpg'
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.put('https://mockapi.kingtechnology.in/api/products.php?id=1', {
    title: 'Updated Product',
    price: 29.99,
    description: 'Updated description',
    category: 'electronics',
    image: 'https://example.com/image.jpg'
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'title' => 'Updated Product',
    'price' => 29.99,
    'description' => 'Updated description',
    'category' => 'electronics',
    'image' => 'https://example.com/image.jpg'
];

$ch = curl_init('https://mockapi.kingtechnology.in/api/products.php?id=1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X PUT 'https://mockapi.kingtechnology.in/api/products.php?id=1' \
  -H 'Content-Type: application/json' \
  -d '{"title":"Updated Product","price":29.99,"description":"Updated description","category":"electronics","image":"https://example.com/image.jpg"}'`,
                python: `import requests

data = {
    'title': 'Updated Product',
    'price': 29.99,
    'description': 'Updated description',
    'category': 'electronics',
    'image': 'https://example.com/image.jpg'
}

response = requests.put(
    'https://mockapi.kingtechnology.in/api/products.php?id=1',
    json=data
)
print(response.json())`
            },

            "delete-product": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/products.php?id=1', {
    method: 'DELETE'
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.delete('https://mockapi.kingtechnology.in/api/products.php?id=1')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
$ch = curl_init('https://mockapi.kingtechnology.in/api/products.php?id=1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X DELETE 'https://mockapi.kingtechnology.in/api/products.php?id=1'`,
                python: `import requests

response = requests.delete(
    'https://mockapi.kingtechnology.in/api/products.php?id=1'
)
print(response.json())`
            },

            "get-all-carts": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/carts.php')
    .then(response => response.json())
    .then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.get('https://mockapi.kingtechnology.in/api/carts.php')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
$response = file_get_contents('https://mockapi.kingtechnology.in/api/carts.php');
echo $response;
?>`,
                curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/carts.php'`,
                python: `import requests

response = requests.get('https://mockapi.kingtechnology.in/api/carts.php')
print(response.json())`
            },

            "add-cart": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/carts.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        userId: 1,
        date: '2026-09-22',
        products: [{ productId: 1, quantity: 2 }]
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.post('https://mockapi.kingtechnology.in/api/carts.php', {
    userId: 1,
    date: '2026-09-22',
    products: [{ productId: 1, quantity: 2 }]
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'userId' => 1,
    'date' => '2026-09-22',
    'products' => [['productId' => 1, 'quantity' => 2]]
];

$ch = curl_init('https://mockapi.kingtechnology.in/api/carts.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X POST 'https://mockapi.kingtechnology.in/api/carts.php' \
  -H 'Content-Type: application/json' \
  -d '{"userId":1,"date":"2026-09-22","products":[{"productId":1,"quantity":2}]}'`,
                python: `import requests

data = {
    'userId': 1,
    'date': '2026-09-22',
    'products': [{'productId': 1, 'quantity': 2}]
}
response = requests.post(
    'https://mockapi.kingtechnology.in/api/carts.php',
    json=data
)
print(response.json())`
            },

            "get-cart": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/carts.php?id=1')
    .then(response => response.json())
    .then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.get('https://mockapi.kingtechnology.in/api/carts.php?id=1')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
echo file_get_contents(
    'https://mockapi.kingtechnology.in/api/carts.php?id=1'
);
?>`,
                curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/carts.php?id=1'`,
                python: `import requests

response = requests.get('https://mockapi.kingtechnology.in/api/carts.php?id=1')
print(response.json())`
            },

            "update-cart": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/carts.php?id=1', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        userId: 1,
        date: '2026-09-22',
        products: [{ productId: 1, quantity: 5 }]
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.put('https://mockapi.kingtechnology.in/api/carts.php?id=1', {
    userId: 1,
    date: '2026-09-22',
    products: [{ productId: 1, quantity: 5 }]
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'userId' => 1,
    'date' => '2026-09-22',
    'products' => [['productId' => 1, 'quantity' => 5]]
];

$ch = curl_init('https://mockapi.kingtechnology.in/api/carts.php?id=1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X PUT 'https://mockapi.kingtechnology.in/api/carts.php?id=1' \
  -H 'Content-Type: application/json' \
  -d '{"userId":1,"date":"2026-09-22","products":[{"productId":1,"quantity":5}]}'`,
                python: `import requests

data = {
    'userId': 1,
    'date': '2026-09-22',
    'products': [{'productId': 1, 'quantity': 5}]
}
response = requests.put(
    'https://mockapi.kingtechnology.in/api/carts.php?id=1',
    json=data
)
print(response.json())`
            },

            "delete-cart": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/carts.php?id=1', {
    method: 'DELETE'
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.delete('https://mockapi.kingtechnology.in/api/carts.php?id=1')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
$ch = curl_init('https://mockapi.kingtechnology.in/api/carts.php?id=1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X DELETE 'https://mockapi.kingtechnology.in/api/carts.php?id=1'`,
                python: `import requests

response = requests.delete('https://mockapi.kingtechnology.in/api/carts.php?id=1')
print(response.json())`
            },

            "get-all-users": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/users.php')
    .then(response => response.json())
    .then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.get('https://mockapi.kingtechnology.in/api/users.php')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
echo file_get_contents('https://mockapi.kingtechnology.in/api/users.php');
?>`,
                curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/users.php'`,
                python: `import requests

response = requests.get('https://mockapi.kingtechnology.in/api/users.php')
print(response.json())`
            },

            "add-user": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/users.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        username: 'john',
        email: 'john@example.com',
        password: 'password123'
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.post('https://mockapi.kingtechnology.in/api/users.php', {
    username: 'john',
    email: 'john@example.com',
    password: 'password123'
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'username' => 'john',
    'email' => 'john@example.com',
    'password' => 'password123'
];

$ch = curl_init('https://mockapi.kingtechnology.in/api/users.php');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X POST 'https://mockapi.kingtechnology.in/api/users.php' \
  -H 'Content-Type: application/json' \
  -d '{"username":"john","email":"john@example.com","password":"password123"}'`,
                python: `import requests

data = {
    'username': 'john',
    'email': 'john@example.com',
    'password': 'password123'
}
response = requests.post(
    'https://mockapi.kingtechnology.in/api/users.php',
    json=data
)
print(response.json())`
            },

            "get-user": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/users.php?id=1')
    .then(response => response.json())
    .then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.get('https://mockapi.kingtechnology.in/api/users.php?id=1')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
echo file_get_contents(
    'https://mockapi.kingtechnology.in/api/users.php?id=1'
);
?>`,
                curl: `curl -X GET 'https://mockapi.kingtechnology.in/api/users.php?id=1'`,
                python: `import requests

response = requests.get('https://mockapi.kingtechnology.in/api/users.php?id=1')
print(response.json())`
            },

            "update-user": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/users.php?id=1', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        username: 'updatedUser',
        email: 'updated@example.com',
        password: 'newPassword'
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.put('https://mockapi.kingtechnology.in/api/users.php?id=1', {
    username: 'updatedUser',
    email: 'updated@example.com',
    password: 'newPassword'
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'username' => 'updatedUser',
    'email' => 'updated@example.com',
    'password' => 'newPassword'
];

$ch = curl_init('https://mockapi.kingtechnology.in/api/users.php?id=1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X PUT 'https://mockapi.kingtechnology.in/api/users.php?id=1' \
  -H 'Content-Type: application/json' \
  -d '{"username":"updatedUser","email":"updated@example.com","password":"newPassword"}'`,
                python: `import requests

data = {
    'username': 'updatedUser',
    'email': 'updated@example.com',
    'password': 'newPassword'
}
response = requests.put(
    'https://mockapi.kingtechnology.in/api/users.php?id=1',
    json=data
)
print(response.json())`
            },

            "delete-user": {
                javascript: `fetch('https://mockapi.kingtechnology.in/api/users.php?id=1', {
    method: 'DELETE'
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.delete('https://mockapi.kingtechnology.in/api/users.php?id=1')
    .then(response => console.log(response.data));`,
                php: `\u003C?php
$ch = curl_init('https://mockapi.kingtechnology.in/api/users.php?id=1');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X DELETE 'https://mockapi.kingtechnology.in/api/users.php?id=1'`,
                python: `import requests

response = requests.delete('https://mockapi.kingtechnology.in/api/users.php?id=1')
print(response.json())`
            },

            "login": {
                javascript: `fetch('https://mockapi.kingtechnology.in/auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        username: 'john',
        password: 'password123'
    })
})
.then(response => response.json())
.then(data => console.log(data));`,
                node: `const axios = require('axios');

axios.post('https://mockapi.kingtechnology.in/auth/login', {
    username: 'john',
    password: 'password123'
})
.then(response => console.log(response.data));`,
                php: `\u003C?php
$data = [
    'username' => 'john',
    'password' => 'password123'
];

$ch = curl_init('https://mockapi.kingtechnology.in/auth/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
?>`,
                curl: `curl -X POST 'https://mockapi.kingtechnology.in/auth/login' \
  -H 'Content-Type: application/json' \
  -d '{"username":"john","password":"password123"}'`,
                python: `import requests

data = {
    'username': 'john',
    'password': 'password123'
}
response = requests.post(
    'https://mockapi.kingtechnology.in/auth/login',
    json=data
)
print(response.json())`
            }
        };

        /*
        |--------------------------------------------------------------------------
        | DART REQUEST SAMPLES
        |--------------------------------------------------------------------------
        | Uses the Dart http package. Add `http: ^1.4.0` to pubspec.yaml.
        */
        const dartSamples = {
            "get-all-products": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/products.php'),
  );

  print(jsonDecode(response.body));
}` ,

            "add-product": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.post(
    Uri.parse('https://mockapi.kingtechnology.in/api/products.php'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'title': 'New Product',
      'price': 29.99,
      'description': 'A new product',
      'category': 'electronics',
      'image': 'https://example.com/image.jpg',
    }),
  );

  print(jsonDecode(response.body));
}` ,

            "get-product": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/products.php?id=1'),
  );

  print(jsonDecode(response.body));
}` ,

            "update-product": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.put(
    Uri.parse('https://mockapi.kingtechnology.in/api/products.php?id=1'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'title': 'Updated Product',
      'price': 29.99,
      'description': 'Updated description',
      'category': 'electronics',
      'image': 'https://example.com/image.jpg',
    }),
  );

  print(jsonDecode(response.body));
}` ,

            "delete-product": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.delete(
    Uri.parse('https://mockapi.kingtechnology.in/api/products.php?id=1'),
  );

  print(jsonDecode(response.body));
}` ,

            "get-all-carts": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/carts.php'),
  );

  print(jsonDecode(response.body));
}` ,

            "add-cart": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.post(
    Uri.parse('https://mockapi.kingtechnology.in/api/carts.php'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'userId': 1,
      'date': '2026-09-22',
      'products': [
        {'productId': 1, 'quantity': 2}
      ],
    }),
  );

  print(jsonDecode(response.body));
}` ,

            "get-cart": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/carts.php?id=1'),
  );

  print(jsonDecode(response.body));
}` ,

            "update-cart": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.put(
    Uri.parse('https://mockapi.kingtechnology.in/api/carts.php?id=1'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'userId': 1,
      'date': '2026-09-22',
      'products': [
        {'productId': 1, 'quantity': 5}
      ],
    }),
  );

  print(jsonDecode(response.body));
}` ,

            "delete-cart": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.delete(
    Uri.parse('https://mockapi.kingtechnology.in/api/carts.php?id=1'),
  );

  print(jsonDecode(response.body));
}` ,

            "get-all-users": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/users.php'),
  );

  print(jsonDecode(response.body));
}` ,

            "add-user": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.post(
    Uri.parse('https://mockapi.kingtechnology.in/api/users.php'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'username': 'john',
      'email': 'john@example.com',
      'password': 'password123',
    }),
  );

  print(jsonDecode(response.body));
}` ,

            "get-user": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.get(
    Uri.parse('https://mockapi.kingtechnology.in/api/users.php?id=1'),
  );

  print(jsonDecode(response.body));
}` ,

            "update-user": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.put(
    Uri.parse('https://mockapi.kingtechnology.in/api/users.php?id=1'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'username': 'updatedUser',
      'email': 'updated@example.com',
      'password': 'newPassword',
    }),
  );

  print(jsonDecode(response.body));
}` ,

            "delete-user": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.delete(
    Uri.parse('https://mockapi.kingtechnology.in/api/users.php?id=1'),
  );

  print(jsonDecode(response.body));
}` ,

            "login": `import 'dart:convert';
import 'package:http/http.dart' as http;

Future<void> main() async {
  final response = await http.post(
    Uri.parse('https://mockapi.kingtechnology.in/auth/login'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'username': 'john',
      'password': 'password123',
    }),
  );

  print(jsonDecode(response.body));
}`
        };

        Object.keys(dartSamples).forEach(endpoint => {
            if (requestSamples[endpoint]) {
                requestSamples[endpoint].dart = dartSamples[endpoint];
            }
        });

        function renderRequestTabs(section) {
            const endpoint = section.id;
            const samples = requestSamples[endpoint];
            if (!samples) return;

            const codeBox = section.querySelector('.code-scroll');
            if (!codeBox) return;

            const languages = [
                ['javascript', 'JavaScript (Browser)'],
                ['node', 'Node.js (Axios)'],
                ['php', 'PHP'],
                ['curl', 'cURL'],
                ['python', 'Python'],
                ['dart', 'Dart']
            ];

            const wrapper = document.createElement('div');
            wrapper.className = 'request-sample-wrapper';

            const label = document.createElement('div');
            label.className = 'text-gray-400 text-sm mb-3';
            label.textContent = 'Request samples';
            wrapper.appendChild(label);

            const tabs = document.createElement('div');
            tabs.className = 'request-tabs';

            const codeContainer = document.createElement('div');
            codeContainer.className = 'request-code';

            const pre = document.createElement('pre');
            codeContainer.appendChild(pre);

            const copyButton = document.createElement('button');
            copyButton.type = 'button';
            copyButton.className = 'request-copy-btn';
            copyButton.title = 'Copy request sample';
            copyButton.setAttribute('aria-label', 'Copy request sample');

            const copyIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="9" y="9" width="13" height="13" rx="2"></rect>
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
            </svg>`;

            const checkIcon = `
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12l4 4L19 6"></path>
            </svg>`;

            copyButton.innerHTML = copyIcon;
            codeContainer.appendChild(copyButton);

            let copyTimer;

            async function copyCurrentSample() {
                const text = pre.textContent;

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        const textarea = document.createElement('textarea');
                        textarea.value = text;
                        textarea.style.position = 'fixed';
                        textarea.style.left = '-9999px';
                        document.body.appendChild(textarea);
                        textarea.focus();
                        textarea.select();
                        document.execCommand('copy');
                        textarea.remove();
                    }

                    copyButton.innerHTML = checkIcon;
                    copyButton.classList.add('copied');

                    clearTimeout(copyTimer);
                    copyTimer = setTimeout(() => {
                        copyButton.innerHTML = copyIcon;
                        copyButton.classList.remove('copied');
                    }, 1200);
                } catch (error) {
                    console.error('Copy failed:', error);
                }
            }

            copyButton.addEventListener('click', copyCurrentSample);

            function selectLanguage(language, clickedTab) {
                pre.textContent = samples[language];
                tabs.querySelectorAll('.request-tab').forEach(tab => {
                    tab.classList.remove('active');
                });
                clickedTab.classList.add('active');
            }

            languages.forEach(([key, labelText], index) => {
                const tab = document.createElement('button');
                tab.type = 'button';
                tab.className = 'request-tab' + (index === 0 ? ' active' : '');
                tab.textContent = labelText;
                tab.addEventListener('click', () => selectLanguage(key, tab));
                tabs.appendChild(tab);
            });

            wrapper.appendChild(tabs);
            wrapper.appendChild(codeContainer);

            codeBox.replaceWith(wrapper);

            const firstTab = tabs.querySelector('.request-tab');
            selectLanguage('javascript', firstTab);
        }

        document.addEventListener("DOMContentLoaded", function () {


            /*
            |--------------------------------------------------------------------------
            | ELEMENTS
            |--------------------------------------------------------------------------
            */

            const sidebar =
                document.getElementById("sidebar");

            const mobileMenuButton =
                document.getElementById("mobileMenuButton");

            const mobileOverlay =
                document.getElementById("mobileOverlay");

            const sidebarItems =
                document.querySelectorAll(".sidebar-item");

            const sections =
                document.querySelectorAll(".doc-section");

            const searchInput =
                document.getElementById("searchInput");


            /* Render language tabs for every endpoint */
            document.querySelectorAll(".doc-section").forEach(renderRequestTabs);


            /*
            |--------------------------------------------------------------------------
            | MOBILE SIDEBAR
            |--------------------------------------------------------------------------
            */

            function openMobileMenu() {

                sidebar.classList.remove("-translate-x-full");

                mobileOverlay.classList.remove("hidden");

            }


            function closeMobileMenu() {

                sidebar.classList.add("-translate-x-full");

                mobileOverlay.classList.add("hidden");

            }


            if (mobileMenuButton) {

                mobileMenuButton.addEventListener(
                    "click",
                    openMobileMenu
                );

            }

            const mobileMenuClose = document.getElementById("mobileMenuClose");
            if (mobileMenuClose) {

                mobileMenuClose.addEventListener(
                    "click",
                    closeMobileMenu
                );

            }


            if (mobileOverlay) {

                mobileOverlay.addEventListener(
                    "click",
                    closeMobileMenu
                );

            }


            /*
            |--------------------------------------------------------------------------
            | SIDEBAR CLICK
            |--------------------------------------------------------------------------
            */

            sidebarItems.forEach(item => {

                item.addEventListener("click", function (e) {

                    e.preventDefault();

                    const targetId =
                        this.dataset.target;

                    const target =
                        document.getElementById(targetId);

                    if (!target) return;


                    /*
                    | Smooth scroll
                    */

                    target.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });


                    /*
                    | Mobile close
                    */

                    if (window.innerWidth < 1024) {

                        closeMobileMenu();

                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | ACTIVE SIDEBAR ITEM
            |--------------------------------------------------------------------------
            */

            function setActiveItem(id) {

                sidebarItems.forEach(item => {

                    const target =
                        item.dataset.target;


                    if (target === id) {

                        item.classList.add(
                            "active"
                        );

                    } else {

                        item.classList.remove(
                            "active"
                        );

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | INTERSECTION OBSERVER
            |--------------------------------------------------------------------------
            */

            const observer =
                new IntersectionObserver(

                    function (entries) {

                        /*
                        | Find sections currently visible
                        */

                        const visibleSections =
                            entries
                                .filter(entry =>
                                    entry.isIntersecting
                                );


                        if (visibleSections.length > 0) {

                            /*
                            | Choose the section closest
                            | to the top of viewport
                            */

                            visibleSections.sort(
                                (a, b) =>
                                    a.boundingClientRect.top -
                                    b.boundingClientRect.top
                            );


                            const activeSection =
                                visibleSections[0];


                            setActiveItem(
                                activeSection.target.id
                            );

                        }

                    },

                    {

                        /*
                        | Active zone
                        |
                        | Section becomes active when it
                        | enters the upper-middle area.
                        */

                        root: null,

                        rootMargin:
                            "-15% 0px -65% 0px",

                        threshold: 0

                    }

                );


            /*
            |--------------------------------------------------------------------------
            | OBSERVE ALL SECTIONS
            |--------------------------------------------------------------------------
            */

            sections.forEach(section => {

                observer.observe(section);

            });


            /*
            |--------------------------------------------------------------------------
            | INITIAL ACTIVE SECTION
            |--------------------------------------------------------------------------
            */

            if (sections.length > 0) {

                setActiveItem(
                    sections[0].id
                );

            }


            /*
            |--------------------------------------------------------------------------
            | GROUP COLLAPSE / EXPAND
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll(".group-title")
                .forEach(button => {

                    button.addEventListener(
                        "click",
                        function () {

                            const group =
                                this.parentElement;

                            const items =
                                group.querySelector(
                                    ".group-items"
                                );

                            const arrow =
                                this.querySelector(
                                    ".group-arrow"
                                );


                            if (
                                items.classList.contains(
                                    "hidden"
                                )
                            ) {

                                items.classList.remove(
                                    "hidden"
                                );

                                arrow.style.transform =
                                    "rotate(0deg)";

                            } else {

                                items.classList.add(
                                    "hidden"
                                );

                                arrow.style.transform =
                                    "rotate(-90deg)";

                            }

                        }
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            if (searchInput) {

                searchInput.addEventListener(
                    "input",
                    function () {

                        const query =
                            this.value
                                .toLowerCase()
                                .trim();


                        sidebarItems.forEach(item => {

                            const text =
                                item.textContent
                                    .toLowerCase();


                            if (
                                text.includes(query)
                            ) {

                                item.classList.remove(
                                    "hidden"
                                );

                            } else {

                                item.classList.add(
                                    "hidden"
                                );

                            }

                        });


                        /*
                        | Hide/show group when searching
                        */

                        document
                            .querySelectorAll(".sidebar-group")
                            .forEach(group => {

                                const visibleItems =
                                    group.querySelectorAll(
                                        ".sidebar-item:not(.hidden)"
                                    );


                                if (
                                    query !== "" &&
                                    visibleItems.length === 0
                                ) {

                                    group.classList.add(
                                        "hidden"
                                    );

                                } else {

                                    group.classList.remove(
                                        "hidden"
                                    );

                                }

                            });

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | HANDLE HASH
            |--------------------------------------------------------------------------
            */

            if (window.location.hash) {

                const hash =
                    window.location.hash.substring(1);

                const target =
                    document.getElementById(hash);


                if (target) {

                    setTimeout(() => {

                        target.scrollIntoView({
                            behavior: "instant",
                            block: "start"
                        });

                        setActiveItem(hash);

                    }, 100);

                }

            }

        });

    </script>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
