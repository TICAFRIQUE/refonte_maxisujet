<!--Afficher les sous categories en option dans produit -->
{{-- {{count($category->children) > 0 ? 'disabled' : '' }} --}}
{{-- <option {{count($category->children)  }} value="{{ $category->id }}">{{ str_repeat('--', $level ?? 0) }} {{ $category->name }}</option>
@if ($category->children)
    @foreach ($category->children as $child)
        @include('backend.pages.produit.partials.subCategorieOption', ['category' => $child, 'level' => ($level ?? 0) + 1])
    @endforeach
@endif --}}

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
