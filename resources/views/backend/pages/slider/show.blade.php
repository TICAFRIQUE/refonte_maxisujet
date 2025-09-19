@extends('backend.layouts.master')

@section('title')
    Détails du Slider
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Détails du Slider: {{ $slider->titre }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.slider.edit', $slider) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="{{ route('admin.slider.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px;">Titre</th>
                                        <td>{{ $slider->titre }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $slider->description ?? 'Aucune description' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Texte du bouton</th>
                                        <td>{{ $slider->bouton_text ?? 'Aucun texte' }}</td>
                                    </tr>
                                    <tr>
                                        <th>URL du bouton</th>
                                        <td>
                                            @if($slider->bouton_url)
                                                <a href="{{ $slider->bouton_url }}" target="_blank">{{ $slider->bouton_url }}</a>
                                            @else
                                                Aucune URL
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ordre</th>
                                        <td>
                                            <span class="badge badge-info">{{ $slider->ordre }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Statut</th>
                                        <td>
                                            @if($slider->actif)
                                                <span class="badge badge-success">Actif</span>
                                            @else
                                                <span class="badge badge-secondary">Inactif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Créé le</th>
                                        <td>{{ $slider->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Modifié le</th>
                                        <td>{{ $slider->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                @if($slider->getFirstMedia('slider'))
                                    <div class="text-center">
                                        <h5>Image du slider</h5>
                                        <img src="{{ $slider->image_url }}" alt="{{ $slider->titre }}" 
                                             class="img-fluid rounded">
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <p>Aucune image disponible</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection