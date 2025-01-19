@php
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Str;

    //mengecek rute aktif
    $isDashboard = Route::currentRouteName() === 'dashboard';
    $isPeta = Route::currentRouteName() === 'peta';
    $isProses = Route::currentRouteName() === 'proses';

    if ($isDashboard) {
        $title = 'Dashboard';
        $subtitle = '';
        $titleRoute = route('dashboard');
    } elseif ($isPeta) {
        $title = 'Peta Cluster';
        $subtitle = '';
        $titleRoute = route('peta');
    } elseif ($isProses) {
        $title = 'Proses Clustering';
        $subtitle = '';
        $titleRoute = route('proses');
    }else {
        $currentRoute = Route::currentRouteName();
        //array untuk mengambil title dan subtitle dari route
        $routeParts = $currentRoute ? explode('.', $currentRoute) : [];

        $mainEntity = $routeParts[0] ?? 'default';
        $title = 'Data ' . Str::title($mainEntity);
        $titleRoute = route($mainEntity);
        $subtitle = isset($routeParts[1]) ? Str::title($routeParts[1] . ' ' . Str::title($mainEntity)) : '';
    }
@endphp

<nav aria-label="breadcrumb">
    <span class="mb-3"></span>
    <ol class="breadcrumb bg-transparent px-0 mb-0 me-3">
        <li class="breadcrumb-item text-lg2 {{ $subtitle ? 'opacity-5' : 'fw-bold' }} text-white active"
            aria-current="page">
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
