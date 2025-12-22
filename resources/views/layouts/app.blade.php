{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SGPD COAC - Sistema de Gestión de Protección de Datos')</title>

    {{-- Tipografía global (elige una; aquí uso Montserrat por estética institucional) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- ✅ Opción A (simple): Tailwind por CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ✅ Opción B (pro): Vite (descomenta cuando tengan Tailwind compilado) --}}
    {{--
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    --}}

    <style>
        [x-cloak] { display: none !important; }

        :root{
            --sgpd-font: "Montserrat", ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji","Segoe UI Emoji";
        }

        html, body { height: 100%; }
        body {
            font-family: var(--sgpd-font);
            font-size: 14px;
            line-height: 1.45;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Scrollbar ligera (opcional) */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-thumb { background: rgba(100,116,139,.35); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(100,116,139,.55); }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800">
@php
    // Navegación base (el equipo puede editar labels/urls sin tocar el layout)
    // Para no romper si aún no existen rutas, uso href '#'. Luego cambian por route('...') o url('...')
    $nav = [
        [
            'label' => 'Dashboard',
            'items' => [
                ['label' => 'Inicio', 'href' => url('/'), 'key' => 'dashboard'],
            ],
        ],
        [
            'label' => 'CORE',
            'items' => [
                ['label' => 'Organizaciones', 'href' => '#', 'key' => 'org'],
            ],
        ],
        [
            'label' => 'IAM',
            'items' => [
                ['label' => 'Usuarios', 'href' => '#', 'key' => 'users'],
                ['label' => 'Roles', 'href' => '#', 'key' => 'roles'],
                ['label' => 'Permisos', 'href' => '#', 'key' => 'permissions'],
            ],
        ],
        [
            'label' => 'PRIVACY',
            'items' => [
                ['label' => 'Catálogos (Base)', 'href' => '#', 'key' => 'privacy_catalogs'],
                ['label' => 'Sistemas / Data Stores', 'href' => '#', 'key' => 'systems'],
                ['label' => 'Destinatarios', 'href' => '#', 'key' => 'recipients'],
                ['label' => 'RAT: Actividades de Tratamiento', 'href' => '#', 'key' => 'rat'],
                ['label' => 'Titulares / Consentimientos', 'href' => '#', 'key' => 'subjects'],
                ['label' => 'Documentos', 'href' => '#', 'key' => 'documents'],
                ['label' => 'DSAR', 'href' => '#', 'key' => 'dsar'],
            ],
        ],
        [
            'label' => 'RISK & AUDIT',
            'items' => [
                ['label' => 'Riesgos', 'href' => '#', 'key' => 'risks'],
                ['label' => 'DPIA', 'href' => '#', 'key' => 'dpia'],
                ['label' => 'Auditorías', 'href' => '#', 'key' => 'audits'],
            ],
        ],
        [
            'label' => 'TRAINING',
            'items' => [
                ['label' => 'Cursos', 'href' => '#', 'key' => 'courses'],
                ['label' => 'Asignaciones', 'href' => '#', 'key' => 'assignments'],
                ['label' => 'Resultados', 'href' => '#', 'key' => 'results'],
            ],
        ],
    ];
@endphp

<div
    x-data="sgpdLayout()"
    x-cloak
    @keydown.escape.window="closeAll()"
    class="min-h-screen"
>
    {{-- HEADER --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="flex items-center justify-between px-4 py-3">

            {{-- Toggle (junto al sidebar) --}}
            <div class="flex items-center gap-2">
                <button
                    type="button"
                    @click="toggleSidebar()"
                    class="p-2 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    aria-label="Abrir/cerrar menú"
                >
                    {{-- Icono hamburguesa / X --}}
                    <svg x-show="!sidebarOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="sidebarOpen" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="flex items-center space-x-2">
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">SGPD</h1>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                        COAC
                    </span>
                </div>
            </div>

            {{-- Acciones derecha --}}
            <div class="flex items-center space-x-2">
                {{-- Notificaciones --}}
                <div class="relative">
                    <button
                        type="button"
                        @click="showNotifications = !showNotifications"
                        class="relative p-2 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        aria-label="Notificaciones"
                    >
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div
                        x-show="showNotifications"
                        @click.away="showNotifications = false"
                        x-transition.opacity
                        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
                    >
                        <div class="p-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Notificaciones</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Actividad reciente del sistema</p>
                        </div>
                        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                            <template x-for="n in notifications" :key="n.id">
                                <div class="p-4 hover:bg-gray-50">
                                    <p class="text-sm font-medium text-gray-900" x-text="n.title"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="n.time"></p>
                                </div>
                            </template>
                            <div x-show="notifications.length === 0" class="p-6 text-center text-sm text-gray-500">
                                Sin notificaciones.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ajustes --}}
                <a href="#" class="p-2 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Ajustes">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>

                {{-- Usuario (placeholder) --}}
                <div class="hidden sm:flex items-center gap-2 pl-2">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 text-white flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold text-gray-900 truncate max-w-[160px]">
                            {{ auth()->user()->name ?? 'Usuario' }}
                        </div>
                        <div class="text-xs text-gray-500 truncate max-w-[160px]">
                            {{ auth()->user()->email ?? 'sesión' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- SIDEBAR + OVERLAY (mobile) --}}
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 md:hidden" @click="sidebarOpen = false"></div>

    <aside
        class="fixed z-50 inset-y-0 left-0 w-72 bg-white border-r border-gray-200 shadow-xl md:shadow-none md:translate-x-0 md:static md:z-auto"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        style="transition: transform 280ms ease-out;"
    >
        {{-- Sidebar header --}}
        <div class="p-5 bg-gradient-to-r from-blue-600 to-blue-800">
            <div class="flex items-center space-x-3 text-white">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-lg leading-tight">SGPD COAC</h2>
                    <p class="text-xs text-blue-100">Layout global (equipo)</p>
                </div>
            </div>
        </div>

        {{-- Sidebar nav --}}
        <nav class="p-4 overflow-y-auto h-[calc(100vh-160px)] space-y-5">
            @foreach($nav as $section)
                <div>
                    <div class="text-[11px] uppercase tracking-wider text-gray-400 font-semibold px-2 mb-2">
                        {{ $section['label'] }}
                    </div>

                    <div class="space-y-1">
                        @foreach($section['items'] as $item)
                            <a
                                href="{{ $item['href'] }}"
                                @click="if (isMobile()) sidebarOpen = false"
                                class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                       hover:bg-gray-50 text-gray-700"
                                :class="activeKey === '{{ $item['key'] }}' ? 'bg-blue-50 text-blue-700 border border-blue-100' : ''"
                                @mouseenter="hoverKey='{{ $item['key'] }}'"
                                @mouseleave="hoverKey=null"
                                @focus="hoverKey='{{ $item['key'] }}'"
                                @blur="hoverKey=null"
                                @click.prevent="goTo('{{ $item['href'] }}', '{{ $item['key'] }}')"
                            >
                                <span class="truncate">{{ $item['label'] }}</span>
                                <svg x-show="activeKey === '{{ $item['key'] }}'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Zona libre para que cada módulo agregue accesos directos --}}
            @hasSection('sidebar_extra')
                <div class="pt-4 border-t border-gray-100">
                    @yield('sidebar_extra')
                </div>
            @endif
        </nav>

        {{-- Sidebar footer --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="text-xs text-gray-600 truncate">Sesión activa</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="md:ml-72">
        {{-- Page container --}}
        <main class="px-4 sm:px-6 lg:px-8 py-6 pb-24">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="font-semibold mb-1">Hay errores en el formulario:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Encabezado de página (opcional) --}}
            @hasSection('page_header')
                <div class="mb-5">
                    @yield('page_header')
                </div>
            @else
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-gray-900">@yield('h1', 'Panel')</h2>
                    <p class="text-sm text-gray-500">@yield('subtitle', 'Bienvenido al sistema')</p>
                </div>
            @endif

            {{-- Contenido de cada pantalla --}}
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="border-t border-gray-200 bg-white">
            <div class="px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="text-xs text-gray-500">
                    © {{ date('Y') }} SGPD COAC — Todos los derechos reservados.
                </div>
                <div class="text-xs text-gray-500 flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full bg-gray-100 border border-gray-200">v1</span>
                    <a href="#" class="hover:text-gray-700">Soporte</a>
                    <span>•</span>
                    <a href="#" class="hover:text-gray-700">Políticas</a>
                </div>
            </div>
        </footer>
    </div>
</div>

{{-- Alpine.js (si no lo compilan por Vite) --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    function sgpdLayout() {
        return {
            sidebarOpen: false,
            showNotifications: false,
            hoverKey: null,

            // Clave activa (para resaltar item). Si quieres, cada vista puede setearlo con:
            // <div x-init="$store.sgpd.activeKey = 'rat'">...
            activeKey: '{{ trim($__env->yieldContent('active_key')) ?: 'dashboard' }}',

            notifications: [
                { id: 1, title: 'Nueva solicitud DSAR registrada', time: 'hace 5 min' },
                { id: 2, title: 'Riesgo marcado como ALTO', time: 'hace 1 hora' },
                { id: 3, title: 'Auditoría finalizada', time: 'hace 1 día' },
            ],

            toggleSidebar() {
                this.sidebarOpen = !this.sidebarOpen;
                this.showNotifications = false;
            },

            closeAll() {
                this.sidebarOpen = false;
                this.showNotifications = false;
            },

            isMobile() {
                return window.matchMedia('(max-width: 767px)').matches;
            },

            // Navegación controlada: marca activo y navega
            goTo(href, key) {
                this.activeKey = key;
                // Si el href es "#", solo marca activo (sirve mientras crean rutas)
                if (!href || href === '#') return;
                window.location.href = href;
            }
        }
    }
</script>

@stack('scripts')
</body>
</html>
