@if (session('success'))
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Succès !</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="closeMessage('success-message')">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z" />
            </svg>
        </span>
    </div>
@endif

@if (session('error'))
    <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Erreur !</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500 cursor-pointer" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="closeMessage('error-message')">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z" />
            </svg>
        </span>
    </div>
@endif

@if (session('warning'))
    <div id="warning-message"
        class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Attention !</strong>
        <span class="block sm:inline">{{ session('warning') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-yellow-500 cursor-pointer" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="closeMessage('warning-message')">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z" />
            </svg>
        </span>
    </div>
@endif

@if (session('info'))
    <div id="info-message" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Information !</strong>
        <span class="block sm:inline">{{ session('info') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-blue-500 cursor-pointer" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="closeMessage('info-message')">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z" />
            </svg>
        </span>
    </div>
@endif

<!-- Script pour fermer les messages -->
<script>
    function closeMessage(id) {
        document.getElementById(id).remove();
    }
</script>
