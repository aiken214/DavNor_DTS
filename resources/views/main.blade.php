<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .transition-transform {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 bg-gray-800 text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform fixed md:relative z-30 md:z-auto">
        <div class="flex items-center justify-center h-16 bg-gray-900">
            <h1 class="text-xl font-bold">Admin</h1>
        </div>
        <nav class="flex-grow mt-5">
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Dashboard</a>
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Users</a>
            <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 hover:text-white">Settings</a>
        </nav>
        <div class="p-4">
            <button class="w-full py-2 px-4 bg-red-500 text-white rounded hover:bg-red-700">Logout</button>
        </div>
    </aside>

    <!-- Hamburger Menu Button -->
    <div class="fixed top-0 left-0 md:hidden z-40 p-4">
        <button id="menu-button" class="text-gray-500 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Main Content -->
    <main class="flex-grow p-6 md:ml-64">
        <header class="flex justify-between items-center bg-white shadow p-4">
            <h1 class="text-2xl font-bold">Dashboard</h1>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Add New</button>
        </header>
        <div class="mt-6">
            <!-- Your main content goes here -->
            <div class="bg-white shadow p-4 rounded">
                <h2 class="text-xl font-bold">Welcome to your admin dashboard!</h2>
                <p class="mt-2">Here you can manage your content.</p>
            </div>
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.getElementById('menu-button');

        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>
