<!-- Menu -->
<div wire:ignore>
  <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo" style="padding-top: 2rem; margin-bottom: 2rem;">
      @php
        $logoQuira = \App\Models\CompanySetting::first();
      @endphp
      @if($logoQuira?->logo && file_exists(public_path($logoQuira->logo)))
        <a href="{{ url('/') }}">
          <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path($logoQuira->logo))) }}"
            class="w-100" alt="{{ __('Logo') }}">
        </a>
      @else
        <a href="{{ url('/') }}" class="app-brand-link"><x-app-logo /></a>
      @endif
    </div>

    <div class="menu-inner-shadow mt-4"></div>

    <ul class="menu-inner py-1">
      {{-- <li class="menu-item">
        <p class="menu-link text-primary">
          @php
          if(Auth::check()){
          if (Auth::user()->role_id == 1) {
          echo company()?->name ?? config('app.name');
          } else {
          $store = Auth::user()->stores()->first();
          if($store){
          echo __('navbar.point_de_vente: ').$store?->name ?? company()?->name;
          }
          }
          } else{
          echo __('navbar.application_name');
          }
          @endphp
        </p>
      </li> --}}

      <!-- Dashboards -->
      @if (Auth::user()->role_id == 4)
        <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('dashboard') }}">
            <i class="menu-icon tf-icons bx bx-stats"></i>
            <div class="text-truncate">{{ __('Dashboard SaaS') }}</div>
          </a>
        </li>
        <li class="menu-item {{ request()->routeIs('overviewsuperadmin.index') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('overviewsuperadmin.index') }}">
            <i class="menu-icon tf-icons bx bx-tachometer"></i>
            <div class="text-truncate">{{ __('Tableau de Bord Global') }}</div>
          </a>
        </li>

        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __('Navigation Boulangerie') }}</span>
        </li>
      @endif

      @if(Auth::user()->isBakeryUser() || Auth::user()->role_id == 4)
        {{-- Dashboard Boulangerie uniquement --}}
        <li class="menu-item {{ request()->routeIs('dashboard.boulangerie') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('dashboard.boulangerie') }}">
            <i class="menu-icon tf-icons bx bx-home"></i>
            <div class="text-truncate">{{ __('Dashboard Boulangerie') }}</div>
          </a>
        </li>

        {{-- LOGISTIQUE & MP --}}
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __('Logistique & MP') }}</span>
        </li>

        @if(Auth::user()->hasRoleString('admin') || Auth::user()->hasRoleString('geran_depot_magasin'))
          <li class="menu-item {{ request()->routeIs('bakery.fournisseurs') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.fournisseurs') }}">
              <i class="menu-icon tf-icons bx bx-user-voice"></i>
              <div class="text-truncate">{{ __('Fournisseurs') }}</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('bakery.achats') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.achats') }}">
              <i class="menu-icon tf-icons bx bx-cart"></i>
              <div class="text-truncate">{{ __('Achats MP') }}</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('bakery.stock.maison') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.stock.maison') }}">
              <i class="menu-icon tf-icons bx bx-box"></i>
              <div class="text-truncate">{{ __('Stock MP Dépôt') }}</div>
            </a>
          </li>
        @endif

        @if(Auth::user()->hasRoleString('admin') || Auth::user()->hasRoleString('geran_depot_usine'))
          <li class="menu-item {{ request()->routeIs('bakery.stock.usine') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.stock.usine') }}">
              <i class="menu-icon tf-icons bx bx-buildings"></i>
              <div class="text-truncate">{{ __('Stock MP Usine') }}</div>
            </a>
          </li>
        @endif

        @if(Auth::user()->hasRoleString('admin') || Auth::user()->hasRoleString('geran_depot_magasin') || Auth::user()->hasRoleString('geran_depot_usine'))
          <li class="menu-item {{ request()->routeIs('bakery.stock.mouvements') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.stock.mouvements') }}">
              <i class="menu-icon tf-icons bx bx-transfer"></i>
              <div class="text-truncate">{{ __('Hist. Mouvements MP') }}</div>
            </a>
          </li>
        @endif

        {{-- PRODUCTION & PF --}}
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __('Production & PF') }}</span>
        </li>

        @if(Auth::user()->hasRoleString('admin') || Auth::user()->hasRoleString('geran_depot_usine'))
          <li class="menu-item {{ request()->routeIs('bakery.production') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.production') }}">
              <i class="menu-icon tf-icons bx bx-repost"></i>
              <div class="text-truncate">{{ __('Production') }}</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('bakery.stock.pf') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.stock.pf') }}">
              <i class="menu-icon tf-icons bx bx-package"></i>
              <div class="text-truncate">{{ __('Stock Produits Finis') }}</div>
            </a>
          </li>
        @endif

        @if(Auth::user()->hasRoleString('admin') || Auth::user()->hasRoleString('geran_depot_usine') || Auth::user()->hasRoleString('geran_depot_boulangerie'))
          <li class="menu-item {{ request()->routeIs('bakery.stock.mouvements-pf') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.stock.mouvements-pf') }}">
              <i class="menu-icon tf-icons bx bx-transfer"></i>
              <div class="text-truncate">{{ __('Hist. Mouvements PF') }}</div>
            </a>
          </li>
        @endif

        {{-- VENTES & BOULANGERIE --}}
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __('Ventes & Boulangerie') }}</span>
        </li>

        @if(Auth::user()->hasRoleString('admin') || Auth::user()->hasRoleString('geran_depot_boulangerie'))
          <li class="menu-item {{ request()->routeIs('bakery.pos') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.pos') }}">
              <i class="menu-icon tf-icons bx bx-cart-alt"></i>
              <div class="text-truncate">{{ __('Vente POS') }}</div>
            </a>
          </li>

          <li class="menu-item {{ request()->routeIs('bakery.dettes') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.dettes') }}">
              <i class="menu-icon tf-icons bx bx-money"></i>
              <div class="text-truncate">{{ __('Dettes Clients') }}</div>
            </a>
          </li>

          <li class="menu-item {{ request()->routeIs('bakery.clients') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.clients') }}">
              <i class="menu-icon tf-icons bx bx-user-pin"></i>
              <div class="text-truncate">{{ __('Clients') }}</div>
            </a>
          </li>

          <li class="menu-item {{ request()->routeIs('bakery.stock.boulangerie') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.stock.boulangerie') }}">
              <i class="menu-icon tf-icons bx bx-store"></i>
              <div class="text-truncate">{{ __('Stock Points de Vente') }}</div>
            </a>
          </li>

          @if(Auth::user()->hasRoleString('admin'))
            <li class="menu-item {{ request()->routeIs('bakery.stock.transfert') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('bakery.stock.transfert') }}">
                <i class="menu-icon tf-icons bx bx-repost"></i>
                <div class="text-truncate">{{ __('Transferts Inter-Sites') }}</div>
              </a>
            </li>
          @endif

          <li class="menu-item {{ request()->routeIs('bakery.cloture') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.cloture') }}">
              <i class="menu-icon tf-icons bx bx-lock-alt"></i>
              <div class="text-truncate">{{ __('Clôture de Journée') }}</div>
            </a>
          </li>
        @endif

        {{-- FINANCES & ADMIN --}}
        @if(Auth::user()->hasRoleString('admin'))
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('Finances & Admin') }}</span>
          </li>

          <li class="menu-item {{ request()->routeIs('bakery.caisse') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.caisse') }}">
              <i class="menu-icon tf-icons bx bx-wallet"></i>
              <div class="text-truncate">{{ __('Gestion Caisse') }}</div>
            </a>
          </li>

          <li class="menu-item {{ request()->routeIs('bakery.reports') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.reports') }}">
              <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
              <div class="text-truncate">{{ __('Rapports') }}</div>
            </a>
          </li>

          <li class="menu-item {{ request()->routeIs('bakery.admin.settings') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('bakery.admin.settings') }}">
              <i class="menu-icon tf-icons bx bx-cog"></i>
              <div class="text-truncate">{{ __('Administration') }}</div>
            </a>
          </li>
        @endif
      @endif

      @if (Auth::user()->role_id == 4)


        <!-- Paramètres -->
        <li class="menu-item {{ request()->is('settings/*') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div class="text-truncate">{{ __('menu.parametres') }}</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('settings.profile') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('settings.profile') }}">{{ __('menu.profil') }}</a>
            </li>
            <li class="menu-item {{ request()->routeIs('settings.password') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('settings.password') }}">{{ __('menu.mot_de_passe') }}</a>
            </li>
            <li class="menu-item {{ request()->routeIs('company.settings') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('company.settings') }}">{{ __('menu.parametres_entreprise') }}</a>
            </li>
          </ul>
        </li>
      @endif

    </ul>
  </aside>
</div>
<!-- / Menu -->

<!-- Overlay (important pour mobile) -->
<div wire:ignore>
  <div class="layout-overlay"></div>
</div>

<style>
  #layout-menu {
    max-height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
  }

  /* Optionnel : pour que le scroll soit plus élégant */
  #layout-menu::-webkit-scrollbar {
    width: 6px;
  }

  #layout-menu::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
  }
</style>

<script>
  // Toggle the 'open' class when the menu-toggle is clicked
  document.querySelectorAll('.menu-toggle').forEach(function (menuToggle) {
    menuToggle.addEventListener('click', function () {
      const menuItem = menuToggle.closest('.menu-item');
      // Toggle the 'open' class on the clicked menu-item
      menuItem.classList.toggle('open');
    });
  });
</script>