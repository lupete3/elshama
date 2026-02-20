<div>
    {{-- Breadcrumbs --}}
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ __('Boulangerie') }} / {{ __('Stock') }} /</span>
        {{ __('Points de Vente') }}
    </h4>

    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div
            class="card-header border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="card-title mb-0">{{ __('Situation des stocks en points de vente') }}</h5>

            <div class="d-flex flex-wrap align-items-center gap-2">
                @if(!Auth::user()->isBakeryUser() || Auth::user()->hasRoleString('admin'))
                    <div style="min-width: 200px;">
                        <select class="form-select" wire:model.live="site_id">
                            <option value="">{{ __('Tous les points de vente') }}</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="badge bg-label-info p-2 px-3">
                        <i class="bx bx-map-pin me-1"></i> {{ Auth::user()->site->nom ?? 'Site inconnu' }}
                    </div>
                @endif

                <div class="input-group input-group-merge" style="width: 250px;">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('Filtrer par produit...') }}"
                        wire:model.live.debounce.300ms="search">
                </div>
            </div>
        </div>

        <div class="card-datatable table-responsive">
            <table class="table border-top table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        @if(!Auth::user()->isBakeryUser())
                            <th>{{ __('Point de Vente') }}</th>
                        @endif
                        <th>{{ __('Produit Fini') }}</th>
                        <th>{{ __('Prix Unitaire') }}</th>
                        <th>{{ __('Solde') }}</th>
                        <th>{{ __('Valeur') }}</th>
                        <th>{{ __('Dernière mise à jour') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($produits->currentPage() - 1) * $produits->perPage() + 1; @endphp
                    @forelse($produits as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            @if(!Auth::user()->isBakeryUser())
                                <td><span class="badge bg-label-secondary">{{ $item->site->nom }}</span></td>
                            @endif
                            <td><strong>{{ $item->stockProduitFinis->designation }}</strong></td>
                            <td>{{ number_format($item->stockProduitFinis->prix, 0, ',', ' ') }} FC</td>
                            <td>
                                <span class="badge bg-label-{{ $item->solde <= 5 ? 'danger' : 'info' }}">
                                    {{ $item->solde }} {{ __('pcs') }}
                                </span>
                            </td>
                            <td>{{ number_format($item->stockProduitFinis->prix * $item->solde, 0, ',', ' ') }} FC</td>
                            <td class="small">{{ $item->updated_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isBakeryUser() ? 6 : 7 }}" class="text-center py-5 text-muted">
                                <i class="bx bx-info-circle d-block mb-2 fs-2"></i>
                                {{ __('Aucun stock trouvé pour ce site ou critère.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($produits->total() > 0)
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="{{ Auth::user()->isBakeryUser() ? 4 : 5 }}" class="fw-bold text-end pe-4">
                                {{ __('Valeur Totale du Stock Sélectionné') }}
                            </td>
                            <td colspan="2" class="fw-bold text-info">{{ number_format($tot, 0, ',', ' ') }} FC</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        <div class="card-footer px-3 py-2 border-top">
            {{ $produits->links() }}
        </div>
    </div>
</div>