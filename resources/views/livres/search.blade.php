@extends('layouts.app', [
    'title' => 'Recherche de livres',
    'breadcrumbs' => [
    ['label' => 'Catalogue', 'url' => route('livres.index')],
    ['label' => 'Recherche', 'url' => null]
    ]
])

@section('content')
<div class="container">
    {{-- Formulaire de recherche --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2><i class="fas fa-search"></i> Recherche de Livres</h2>
                    <form action="{{ route('livres.search') }}" method="GET" class="mt-3">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control form-control-lg" 
                                   placeholder="Rechercher par titre, auteur ou catégorie..."
                                   value="{{ $query }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                        </div>
                        @if($query)
                        <div class="mt-2">
                            <a href="{{ route('livres.search') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Effacer la recherche
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Résultats --}}
    @if($query)
    <div class="row mb-4">
        <div class="col-12">
            <h3>
                Résultats pour "{{ $query }}"
                <span class="badge bg-secondary">{{ $total }}</span>
            </h3>
        </div>
    </div>
    @endif

    <div class="row">
        @forelse($livres as $livre)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $livre->titre }}</h5>
                        <p class="card-text">
                            <strong>👤 Auteur :</strong> {{ $livre->auteur }}<br>
                            <strong>📂 Catégorie :</strong>
                            <span class="badge bg-info">{{ $livre->categorie->nom ?? 'Non classé' }}</span><br>
                        </p>
                        <div class="mt-auto">
                            @if($livre->disponible)
                                <span class="badge bg-success mb-2">✅ Disponible</span>
                            @else
                                <span class="badge bg-danger mb-2">❌ Indisponible</span>
                            @endif
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('livres.show', $livre) }}"
                                   class="btn btn-primary btn-sm">👁️ Voir</a>
                                <a href="{{ route('livres.edit', $livre) }}"
                                   class="btn btn-secondary btn-sm">✏️ Modifier</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>📭 Aucun livre trouvé</h4>
                </div>
            </div>
        @endforelse
    </div>
@endsection