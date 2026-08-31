@extends('backend.layouts.master')

@section('title')
    Créer une Info Flash
@endsection

@section('content')
    @component('backend.components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('info-flash.index') }}">Infos Flash</a>
        @endslot
        @slot('title')
            Créer une Info Flash
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer une nouvelle info flash</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('info-flash.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                   id="message" name="message" maxlength="500" rows="2" required
                                   placeholder="Ex: Les inscriptions pour le concours 2026 sont ouvertes !">{{ old('message') }}</textarea>
                            <div class="form-text">500 caractères maximum. Un message court reste plus lisible en bandeau.</div>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lien" class="form-label">Lien (optionnel)</label>
                                    <input type="url" class="form-control @error('lien') is-invalid @enderror"
                                           id="lien" name="lien" value="{{ old('lien') }}" placeholder="https://...">
                                    @error('lien')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lien_texte" class="form-label">Texte du lien</label>
                                    <input type="text" class="form-control @error('lien_texte') is-invalid @enderror"
                                           id="lien_texte" name="lien_texte" value="{{ old('lien_texte') }}"
                                           maxlength="50" placeholder="Ex: En savoir plus">
                                    @error('lien_texte')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="info" {{ old('type', 'info') == 'info' ? 'selected' : '' }}>Info (bleu)</option>
                                        <option value="succes" {{ old('type') == 'succes' ? 'selected' : '' }}>Succès (vert)</option>
                                        <option value="attention" {{ old('type') == 'attention' ? 'selected' : '' }}>Attention (orange)</option>
                                        <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent (rouge)</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('position') is-invalid @enderror"
                                           id="position" name="position" value="{{ old('position', 1) }}" min="1" required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                        <option value="active" {{ old('statut', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="desactive" {{ old('statut') == 'desactive' ? 'selected' : '' }}>Désactivée</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ri-save-line"></i> Enregistrer
                            </button>
                            <a href="{{ route('info-flash.index') }}" class="btn btn-danger ms-2">
                                <i class="ri-close-line"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="ri-eye-line me-2"></i>Aperçu</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Affichée en bandeau dans l'en-tête du site public, au-dessus du menu, pour toutes les infos flash actives.</p>
                    <div id="apercuBandeau" class="px-3 py-2 rounded text-center small fw-semibold" style="background:#0d6efd; color:#fff;">
                        Votre message ici
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    const couleurs = { info: '#0d6efd', succes: '#198754', attention: '#f7931e', urgent: '#dc3545' };
    function majApercu() {
        const message = document.getElementById('message').value || 'Votre message ici';
        const type = document.getElementById('type').value;
        const bandeau = document.getElementById('apercuBandeau');
        bandeau.textContent = message;
        bandeau.style.background = couleurs[type] || couleurs.info;
    }
    document.getElementById('message').addEventListener('input', majApercu);
    document.getElementById('type').addEventListener('change', majApercu);
</script>
@endsection
