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

      <!-- Dashboard -->
      @if(Auth::user()->isBakeryUser())
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
      @else
        {{-- Dashboard Inventaire avec sous-menu --}}
        <li class="menu-item {{ request()->is('dashboard*') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-home"></i>
            <div class="text-truncate">{{ __('Tableaux de bord') }}</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('dashboard') }}">{{ __('Gestion de Stock') }}</a>
            </li>
            <li class="menu-item {{ request()->routeIs('dashboard.boulangerie') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('dashboard.boulangerie') }}">{{ __('Boulangerie') }}</a>
            </li>
          </ul>
        </li>

        <!-- Magasin -->
        <li class="menu-item {{ request()->routeIs('pos.index') ? 'active' : '' }}">
          <a class="menu-link" href="{{ route('pos.index') }}">
            <i class="menu-icon tf-icons bx bx-cart-alt"></i>
            <div class="text-truncate">{{ __('menu.magasin') }}</div>
          </a>
        </li>

        @if (Auth::user()->role_id == 1)
          <!-- Points de vente -->
          <li class="menu-item {{ request()->is('stores*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('stores.index') }}">
              <i class="menu-icon tf-icons bx bx-store"></i>
              <div class="text-truncate">{{ __('menu.points_de_vente') }}</div>
            </a>
          </li>

          <!-- Produits -->
          <li class="menu-item {{ request()->is('categories*') || request()->is('units*') || request()->is('brands*')
          || request()->is('products*') || request()->is('transfers*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-package"></i>
              <div class="text-truncate">{{ __('menu.produits') }}</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('categories.index') }}">{{ __('menu.categories') }}</a>
              </li>
              <li class="menu-item {{ request()->routeIs('units.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('units.index') }}">{{ __('menu.unites') }}</a>
              </li>
              <li class="menu-item {{ request()->routeIs('brands.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('brands.index') }}">{{ __('menu.marques') }}</a>
              </li>
              <li class="menu-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('products.index') }}">{{ __('menu.produits') }}</a>
              </li>
            </ul>
          </li>
        @endif

        <!-- Contacts -->
        <li class="menu-item {{ request()->is('clients*') || request()->is('suppliers*') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div class="text-truncate">{{ __('menu.contacts') }}</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('clients.index') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('clients.index') }}">{{ __('menu.clients') }}</a>
            </li>
            <li class="menu-item {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('suppliers.index') }}">{{ __('menu.fournisseurs') }}</a>
            </li>
          </ul>
        </li>

        <!-- Ventes -->
        <li
          class="menu-item {{ request()->is('sales*') || request()->routeIs('salereturns.index') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cart"></i>
            <div class="text-truncate">{{ __('menu.ventes') }}</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('sales.index') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('sales.index') }}">{{ __('menu.historique') }}</a>
            </li>
            <li class="menu-item {{ request()->routeIs('salereturns.index') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('salereturns.index') }}">{{ __('menu.retours') }}</a>
            </li>
          </ul>
        </li>

        @if (Auth::user()->role_id == 1)
          <!-- Achats -->
          <li
            class="menu-item {{ request()->is('purchases*') || request()->routeIs('purchasereturns.index') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-cart-download"></i>
              <div class="text-truncate">{{ __('menu.achats') }}</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('purchases.create') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('purchases.create') }}">{{ __('menu.nouvel_achat') }}</a>
              </li>
              <li class="menu-item {{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('purchases.index') }}">{{ __('menu.historique') }}</a>
              </li>
              <li class="menu-item {{ request()->routeIs('purchasereturns.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('purchasereturns.index') }}">{{ __('menu.retours') }}</a>
              </li>
            </ul>
          </li>
        @endif

        <!-- Dettes -->
        <li
          class="menu-item {{ request()->routeIs('clientdebts.index') || request()->routeIs('supplierdebts.index') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-credit-card"></i>
            <div class="text-truncate">{{ __('menu.dettes') }}</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('clientdebts.index') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('clientdebts.index') }}">{{ __('menu.clients') }}</a>
            </li>
            @if (Auth::user()->role_id == 1)
              <li class="menu-item {{ request()->routeIs('supplierdebts.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('supplierdebts.index') }}">{{ __('menu.fournisseurs') }}</a>
              </li>
            @endif
          </ul>
        </li>

        @if (Auth::user()->role_id == 1)
          <!-- Inventaire -->
          <li class="menu-item {{ request()->routeIs('inventories*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('inventories.index') }}">
              <i class="menu-icon tf-icons bx bx-box"></i>
              <div class="text-truncate">{{ __('menu.inventaire') }}</div>
            </a>
          </li>
        @endif

        <!-- Dépenses -->
        <li
          class="menu-item {{ request()->routeIs('expensecategory.index') || request()->routeIs('expenses.index') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-wallet"></i>
            <div class="text-truncate">{{ __('menu.depenses') }}</div>
          </a>
          <ul class="menu-sub">
            @if (Auth::user()->role_id == 1)
              <li class="menu-item {{ request()->routeIs('expensecategory.index') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('expensecategory.index') }}">{{ __('menu.categories_depense') }}</a>
              </li>
            @endif
            <li class="menu-item {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
              <a class="menu-link" href="{{ route('expenses.index') }}">{{ __('menu.depenses') }}</a>
            </li>
          </ul>
        </li>

        <!-- Rapports -->
        <li class="menu-item {{ request()->is('reports*') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bxs-report"></i>
            <div class="text-truncate">{{ __('menu.rapports') }}</div>
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('reports.products') ? 'active' : '' }}"><a class="menu-link"
                href="{{ route('reports.products') }}">{{ __('menu.produits_rapport') }}</a></li>
            <li class="menu-item {{ request()->routeIs('reports.sales') ? 'active' : '' }}"><a class="menu-link"
                href="{{ route('reports.sales') }}">{{ __('menu.ventes_rapport') }}</a></li>
            <li class="menu-item {{ request()->routeIs('reports.stock') ? 'active' : '' }}"><a class="menu-link"
                href="{{ route('reports.stock') }}">{{ __('menu.stock') }}</a></li>
            @if (Auth::user()->role_id == 1)
              <li class="menu-item {{ request()->routeIs('reports.purchases') ? 'active' : '' }}"><a class="menu-link"
                  href="{{ route('reports.purchases') }}">{{ __('menu.achats_rapport') }}</a></li>
              <li class="menu-item {{ request()->routeIs('reports.customers') ? 'active' : '' }}"><a class="menu-link"
                  href="{{ route('reports.customers') }}">{{ __('menu.clients_rapport') }}</a></li>
              <li class="menu-item {{ request()->routeIs('reports.suppliers') ? 'active' : '' }}"><a class="menu-link"
                  href="{{ route('reports.suppliers') }}">{{ __('menu.fournisseurs_rapport') }}</a></li>
            @endif
            <li class="menu-item {{ request()->routeIs('reports.expense') ? 'active' : '' }}"><a class="menu-link"
                href="{{ route('reports.expense') }}">{{ __('menu.depenses_rapport') }}</a></li>
            @if (Auth::user()->role_id == 1)
              <li class="menu-item {{ request()->routeIs('reports.cash') ? 'active' : '' }}"><a class="menu-link"
                  href="{{ route('reports.cash') }}">{{ __('menu.caisses') }}</a></li>
            @endif
          </ul>
        </li>
      @endif


      @if (Auth::user()->role_id == 1)
        <!-- Utilisateurs -->
        <!-- <li class="menu-item {{ request()->routeIs('users*') ? 'active' : '' }}">
            <a class="menu-link" href="{{ route('users.index') }}" >
              <i class="menu-icon tf-icons bx bx-user-circle"></i>
              <div class="text-truncate">{{ __('menu.utilisateurs') }}</div>
            </a>
          </li> -->

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