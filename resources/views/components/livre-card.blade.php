{{-- 
    Composant Carte Livre
    Usage : <x-livre-card :livre="$livre" />
--}}

@props([
    'livre',                    // Le modèle Livre (obligatoire)
    'showActions' => true,      // Afficher les boutons d'action
    'compact' => false          // Mode compact (moins de détails)
])

<div class="card h-100 shadow-sm hover-shadow">
    {{-- En-tête avec catégorie --}}
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span>📚 {{ $livre->categorie->nom ?? 'Sans catégorie' }}</span>
        
        {{-- Badge disponibilité --}}
        @if($livre->disponible ?? true)
            <span class="badge bg-success">Disponible</span>
        @else
            <span class="badge bg-secondary">Emprunté</span>
        @endif
    </div>
    
    {{-- Corps de la carte --}}
    <div class="card-body">
        {{-- Titre --}}
        <h5 class="card-title">{{ $livre->titre }}</h5>
        
        {{-- Auteur --}}
        <p class="card-text text-muted mb-2">
            <strong>Auteur :</strong> {{ $livre->auteur }}
        </p>
        
        {{-- Résumé (seulement si mode non compact) --}}
        @if(!$compact && isset($livre->resume))
            <p class="card-text small">
                {{ Str::limit($livre->resume, 100) }}
            </p>
        @endif
        
        {{-- ISBN (seulement si mode non compact) --}}
        @if(!$compact && isset($livre->isbn))
            <p class="card-text small text-muted">
                <strong>ISBN :</strong> {{ $livre->isbn }}
            </p>
        @endif
    </div>
    
    {{-- Actions --}}
    @if($showActions)
        <div class="card-footer bg-light d-flex gap-2">
            <a href="{{ route('livres.show', $livre) }}" 
               class="btn btn-sm btn-primary flex-fill">
                👁️ Voir
            </a>
            <a href="{{ route('livres.edit', $livre) }}" 
               class="btn btn-sm btn-warning flex-fill">
                ✏️ Modifier
            </a>
        </div>
    @endif
</div>

{{-- Styles pour l'effet hover --}}
@once
@push('styles')
<style>
    .hover-shadow {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
    }
</style>
@endpush
@endonce