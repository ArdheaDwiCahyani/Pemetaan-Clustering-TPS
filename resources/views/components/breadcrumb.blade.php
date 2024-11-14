@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;

    // Mengecek apakah route adalah dashboard atau root
    $isDashboard = Route::current()->uri() === '/';

    // Menentukan title, subtitle, dan route URL
    if ($isDashboard) {
        $title = 'Dashboard';
        $subtitle = '';
        $titleRoute = '/'; // Link ke dashboard
    } else {
        // Mendapatkan nama route saat ini jika bukan root
        $currentRoute = Route::currentRouteName();
        
        // Memecah nama route jika ada
        $routeParts = $currentRoute ? explode('.', $currentRoute) : [];

        // Menentukan title dan route URL untuk title
        $mainEntity = $routeParts[0] ?? 'default';
        $title = 'Data ' . Str::title($mainEntity);
        $titleRoute = route($mainEntity); // Menentukan route utama (misal: 'kecamatan')

        // Menentukan subtitle dari bagian kedua route, jika ada
        $subtitle = isset($routeParts[1]) ? Str::title($routeParts[1] . ' ' . Str::title($mainEntity)) : '';
    }
@endphp

<nav aria-label="breadcrumb">
    <span class="mb-3"></span>
    <ol class="breadcrumb bg-transparent px-0 mb-0 me-3">
        <li class="breadcrumb-item text-lg2 {{ $subtitle ? 'opacity-5' : 'fw-bold' }} text-white active" aria-current="page">
            <a href="{{ !$isDashboard ? $titleRoute : '#' }}" class="text-white">{{ $title }}</a>
        </li>
        @if ($subtitle)
            <li class="breadcrumb-item text-lg2 text-white active" aria-current="page">/</li>
            <li class="breadcrumb-item text-lg2 fw-bold text-white active" aria-current="page">
                {{ $subtitle }}
            </li>
        @endif
    </ol>
</nav>
