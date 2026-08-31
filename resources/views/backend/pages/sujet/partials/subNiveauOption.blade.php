<option value="{{ $sousNiveaux->id }}"
    @if(isset($selectedNiveaux) && in_array($sousNiveaux->id, $selectedNiveaux)) selected @endif>
    {{ $sousNiveaux->libelle }}
</option>
@if($sousNiveaux->children && $sousNiveaux->children->count())
    @foreach($sousNiveaux->children as $child)
        @include('backend.pages.sujet.partials.subNiveauOption', [
            'sousNiveaux' => $child,
            'selectedNiveaux' => $selectedNiveaux ?? [],
        ])
    @endforeach
@endif
