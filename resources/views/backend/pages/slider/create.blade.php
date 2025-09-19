@extends('backend.layouts.master')

@section('title')
    Créer un Slider
@endsection

@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            Slider
        @endslot
        @slot('title')
            Créer un Slider
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer un nouveau Slider</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                           id="titre" name="titre" value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bouton_text" class="form-label">Texte du bouton</label>
                                            <input type="text" class="form-control @error('bouton_text') is-invalid @enderror" 
                                                   id="bouton_text" name="bouton_text" value="{{ old('bouton_text') }}"
                                                   placeholder="Ex: En savoir plus">
                                            @error('bouton_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="bouton_url" class="form-label">URL du bouton</label>
                                            <input type="url" class="form-control @error('bouton_url') is-invalid @enderror" 
                                                   id="bouton_url" name="bouton_url" value="{{ old('bouton_url') }}"
                                                   placeholder="https://...">
                                            @error('bouton_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*" required>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Formats acceptés: JPG, PNG, GIF (max 5MB). Taille recommandée: 1200x400px</div>
                                </div>

                                <div class="mb-3">
                                    <label for="position" class="form-label">Position d'affichage <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('position') is-invalid @enderror" 
                                           id="position" name="position" value="{{ old('position', 1) }}" min="1" required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Plus le nombre est petit, plus le slider apparaît en premier</div>
                                </div>

                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                        <option value="active" {{ old('statut', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('statut') == 'desactive' ? 'selected' : '' }}>Desactive</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Aperçu de l'image -->
                                <div class="mb-3">
                                    <label class="form-label">Aperçu</label>
                                    <div class="border rounded p-3 text-center" style="min-height: 150px;">
                                        <img id="imagePreview" src="#" alt="Aperçu" 
                                             style="max-width: 100%; max-height: 140px; display: none;" class="img-thumbnail">
                                        <div id="imagePlaceholder" class="text-muted">
                                            <i class="ri-image-line" style="font-size: 3rem;"></i>
                                            <p class="mt-2">Aperçu de l'image</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-save-line"></i> Enregistrer
                            </button>
                            <a href="{{ route('slider.index') }}" class="btn btn-danger ms-2">
                                <i class="ri-close-line"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Aperçu de l'image
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    });

    // Auto-génération de l'URL si le bouton text est rempli
    document.getElementById('bouton_text').addEventListener('input', function(e) {
        const boutonUrl = document.getElementById('bouton_url');
        if (e.target.value && !boutonUrl.value) {
            boutonUrl.placeholder = 'Saisissez l\'URL de destination...';
        }
    });
</script>
@endsection