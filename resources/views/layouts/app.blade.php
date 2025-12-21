<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Engaja') }}</title>
  <link rel="icon" type="image/png" href="{{ asset('images/engaja-favicon.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

  <style>
    :root {
      --engaja-purple: #421944;
    }

    body {
      font-family: 'Montserrat', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    .navbar-brand {
      font-weight: 700;
      letter-spacing: .2px;
    }
  </style>
  <style>
    .form-control {
      border-color: #b1b6bbff !important;
      /* cinza escuro padrão Bootstrap */
    }

    .form-control:focus {
      border-color: #421944 !important;
      /* roxo Engaja no foco */
      box-shadow: 0 0 0 0.2rem rgba(66, 25, 68, 0.25);
      /* glow roxo no foco */
    }

    .form-select {
      border-color: #b1b6bbff !important;
    }

    .form-select:focus {
      border-color: #421944 !important;
      box-shadow: 0 0 0 0.2rem rgba(66, 25, 68, 0.25);
    }
  </style>
  <style>
    .admin-shell {
      min-height: 100vh;
      background: #f6f7fb;
      transition: all .2s ease;
    }

    .admin-sidebar {
      width: 300px;
      background: linear-gradient(180deg, #421944 0%, #2c1230 100%);
      color: #f5f3ff;
      min-height: 100vh;
      position: sticky;
      top: 0;
      padding: 1.5rem 1.25rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      z-index: 1030;
      transition: transform .3s ease;
      overflow-y: auto;
      overflow-x: hidden;
      -webkit-overflow-scrolling: touch;
    }

    .admin-sidebar__brand {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      padding: 0 .25rem;
    }

    .admin-sidebar__actions {
      display: flex;
      align-items: center;
      gap: .4rem;
    }

    .admin-collapse-btn {
      border-radius: .9rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #fff;
      background: rgba(255, 255, 255, 0.08);
      padding: .35rem .55rem;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .admin-collapse-btn:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.16);
    }

    .admin-sidebar__brand {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
    }

    .admin-sidebar__logo {
      height: 38px;
    }

    .admin-sidebar__logo-mini {
      display: none;
      height: 32px;
    }

    .admin-sidebar__section {
      display: grid;
      gap: .35rem;
      padding: .5rem 0 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .admin-sidebar__section:last-of-type {
      border-bottom: none;
      padding-bottom: 0;
    }

    .admin-sidebar__label {
      text-transform: uppercase;
      letter-spacing: .6px;
      color: rgba(255, 255, 255, 0.55);
      font-size: .75rem;
      margin-bottom: .35rem;
      font-weight: 700;
    }

    .admin-nav-link {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .65rem .75rem;
      color: #f5f3ff;
      text-decoration: none;
      border-radius: .9rem;
      transition: all .2s ease;
      font-weight: 600;
      width: 100%;
      max-width: 100%;
      box-sizing: border-box;
      overflow: hidden;
    }

    .admin-nav-link.btn {
      border: none;
      background: transparent;
      text-align: left;
    }

    .admin-nav-link:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
    }

    .admin-nav-link.active {
      background: #f8f7fb;
      color: #2c1230;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .admin-nav-icon {
      width: 36px;
      height: 36px;
      border-radius: .9rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.12);
      font-weight: 700;
      font-size: .8rem;
      letter-spacing: .3px;
      flex-shrink: 0;
      color: inherit;
    }

    .admin-nav-link.active .admin-nav-icon {
      background: linear-gradient(135deg, #421944, #62305f);
      color: #fff;
    }

    .admin-nav-text {
      white-space: nowrap;
    }

    .admin-topbar {
      background: #ffffff;
      border-bottom: 1px solid #e7e8ed;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      position: sticky;
      top: 0;
      z-index: 1010;
      box-sizing: border-box;
      width: 100%;
    }

    .admin-topbar__title {
      font-size: 1rem;
      margin: 0;
    }

    .admin-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #421944;
      color: #fff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
    }

    .admin-main {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
    }

    .admin-content {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    .admin-main {
      width: 100%;
      overflow-x: hidden;
    }

    .admin-page-header {
      background: #fff;
      border: 1px solid #e7e8ed;
      border-radius: 1rem;
      padding: 1rem 1.25rem;
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.06);
    }

    .admin-sidebar-backdrop {
      display: none;
    }

    .admin-shell.is-collapsed .admin-sidebar {
      width: 86px;
    }

    .admin-shell.is-collapsed .admin-nav-text,
    .admin-shell.is-collapsed .admin-sidebar__label,
    .admin-shell.is-collapsed .admin-sidebar__brand div.lh-sm {
      display: none;
    }

    .admin-shell.is-collapsed .admin-sidebar__logo {
      height: 34px;
    }

    .admin-shell.is-collapsed .admin-nav-link {
      justify-content: center;
    }

    .admin-shell.is-collapsed .admin-nav-icon {
      margin: 0;
    }

    .admin-shell.is-collapsed .admin-topbar {
      padding-left: 1rem;
    }

    .admin-shell.is-collapsed .admin-sidebar__logo-main {
      display: none;
    }

    .admin-shell.is-collapsed .admin-sidebar__logo-mini {
      display: block;
    }

    .admin-shell.is-collapsed .admin-sidebar__brand {
      justify-content: center;
      padding: 0;
    }

    .admin-shell.is-collapsed .admin-sidebar__brand a {
      justify-content: center;
      gap: 0;
    }

    .admin-shell.is-collapsed .admin-nav-link {
      padding: .55rem;
      justify-content: center;
    }

    .admin-shell.is-collapsed .admin-nav-icon {
      width: 38px;
      height: 38px;
    }

    .admin-shell.is-collapsed .admin-nav-link.active {
      box-shadow: none;
    }

    @media (max-width: 991.98px) {
      .admin-sidebar {
        position: fixed;
        inset: 0 auto 0 0;
        transform: translateX(-105%);
        width: 260px;
      }

      .admin-topbar {
        padding: .85rem 1rem;
      }

      .admin-sidebar.is-open {
        transform: translateX(0);
      }

      .admin-sidebar-backdrop {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        visibility: hidden;
        transition: opacity .3s ease;
        z-index: 1020;
      }

      .admin-sidebar-backdrop.show {
        opacity: 1;
        visibility: visible;
      }
    }

    body.sidebar-open {
      overflow: hidden;
    }
  </style>
  @stack('styles')
</head>

@php($useSidebar = auth()->check())
<body class="{{ $useSidebar ? 'min-vh-100 bg-light' : 'd-flex flex-column min-vh-100' }}">
  @if($useSidebar)
    <div class="admin-shell d-flex">
      @include('layouts.partials.admin-sidebar')
      <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>
      <div class="admin-main">
        <header class="admin-topbar shadow-sm">
          <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-primary d-lg-none" type="button" id="sidebarToggle" aria-label="Abrir menu lateral">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.5 3.75A.75.75 0 0 1 2.25 3h11.5a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1-.75-.75m0 4A.75.75 0 0 1 2.25 7h11.5a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1-.75-.75m0 4A.75.75 0 0 1 2.25 11h11.5a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1-.75-.75" />
              </svg>
            </button>
            <button class="btn btn-outline-secondary d-none d-lg-inline-flex" type="button" id="sidebarCollapseToggle" aria-label="Recolher menu lateral">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M12.5 2a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-.5-.5M6.646 4.146a.5.5 0 0 1 .708.708L4.707 7.5H7.5a.5.5 0 0 1 0 1H4.707l2.647 2.646a.5.5 0 0 1-.708.708l-3.5-3.5a.5.5 0 0 1 0-.708z"/>
              </svg>
            </button>
            <div>
              <div class="text-uppercase text-muted small fw-semibold mb-0">Area interna</div>
              <p class="admin-topbar__title fw-bold mb-0">Painel Engaja</p>
            </div>
          </div>
          <div class="d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-md-inline">Ola, {{ Auth::user()->name }}</span>
            <div class="dropdown">
              @php($initial = strtoupper(substr(Auth::user()->name ?? '', 0, 1)))
              <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="admin-avatar">{{ $initial }}</span>
                <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Meu perfil</a></li>
                <li><a class="dropdown-item" href="{{ route('profile.certificados') }}">Meus certificados</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Sair</button>
                  </form>
                </li>
              </ul>
            </div>
          </div>
        </header>

        <div class="admin-content">
          @include('layouts.partials.flash', ['containerClass' => 'container-fluid px-4 px-lg-5'])

          @isset($header)
            <div class="container-fluid px-4 px-lg-5 mt-3">
              <div class="admin-page-header">
                {{ $header }}
              </div>
            </div>
          @endisset

          <main class="flex-grow-1 py-4">
            <div class="container-fluid px-4 px-lg-5">
              @isset($slot) {{ $slot }} @else @yield('content') @endisset
            </div>
          </main>

          @include('layouts.footer')
        </div>
      </div>
    </div>
  @else
    <div class="d-flex flex-column min-vh-100">
      @includeWhen(View::exists('layouts.navigation'), 'layouts.navigation')

      @isset($header)
        <header class="bg-white border-bottom py-3">
          <div class="container">{{ $header }}</div>
        </header>
      @endisset

      @include('layouts.partials.flash')

      <main class="flex-grow-1 py-4">
        <div class="container">
          @isset($slot) {{ $slot }} @else @yield('content') @endisset
        </div>
      </main>

      @include('layouts.footer') {{-- <footer class="bg-primary border-top mt-auto pt-5"> ... --}}
    </div>
  @endif

  @stack('scripts')
  @if($useSidebar)
    <script>
      (() => {
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminSidebarBackdrop');
        const toggle = document.getElementById('sidebarToggle');
        const close = document.getElementById('sidebarClose');
        const collapseTopbarBtn = document.getElementById('sidebarCollapseToggle');
        const shell = document.querySelector('.admin-shell');

        const closeSidebar = () => {
          sidebar?.classList.remove('is-open');
          backdrop?.classList.remove('show');
          document.body.classList.remove('sidebar-open');
        };

        const openSidebar = () => {
          // em mobile sempre abre expandido
          if (window.innerWidth < 992) {
            shell?.classList.remove('is-collapsed');
          }
          sidebar?.classList.add('is-open');
          backdrop?.classList.add('show');
          document.body.classList.add('sidebar-open');
        };

        const toggleCollapsed = () => {
          shell?.classList.toggle('is-collapsed');
        };

        toggle?.addEventListener('click', (event) => {
          event.preventDefault();
          if (sidebar?.classList.contains('is-open')) {
            closeSidebar();
          } else {
            openSidebar();
          }
        });

        close?.addEventListener('click', closeSidebar);
        backdrop?.addEventListener('click', closeSidebar);
        collapseTopbarBtn?.addEventListener('click', toggleCollapsed);

        window.addEventListener('resize', () => {
          if (window.innerWidth >= 992) {
            closeSidebar();
          } else {
            // ao entrar em mobile, garantir que nao inicie colapsado
            shell?.classList.remove('is-collapsed');
          }
        });
      })();
    </script>
  @endif

  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content shadow-lg border-0">
        <div class="modal-header bg-engaja text-white">
          <h5 class="modal-title" id="confirmModalLabel">Confirmar acao</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <p class="mb-0 js-confirm-message"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-engaja js-confirm-accept">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
