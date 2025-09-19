@extends('frontend.layouts.front_app')

@section('title', 'MaxiSujets - Plateforme N°1 de Documents Éducatifs en Côte d\'Ivoire')
@section('meta_description', 'Téléchargez gratuitement des milliers de documents éducatifs : cours, exercices corrigés, examens blancs, sujets de concours. Ressources pour primaire, secondaire et supérieur.')
@section('meta_keywords', 'documents scolaires côte d\'ivoire, cours gratuits CI, exercices corrigés, examens blancs, sujets concours, BEPC, BAC, université côte d\'ivoire, ressources éducatives')
@section('og_title', 'MaxiSujets - Documents Éducatifs Gratuits Côte d\'Ivoire')
@section('og_description', 'La plus grande bibliothèque de documents éducatifs en Côte d\'Ivoire. Cours, exercices, examens pour tous les niveaux.')

@section('content')
    <!-- Carousel -->
    @include('frontend.sections.carousel')

    <!-- Section actions avantages -->
    @include('frontend.sections.carte_avantage')

    <!-- Cycles horizontal cycles -->
    @include('frontend.components.cycle_niveaux')

    <!-- Derniers documents -->
    @include('frontend.sections.recent_document')

    <!-- Blog & Astuces -->
    {{-- @include('frontend.sections.blog_astuce') --}}

    <!-- component matieres -->
    @include('frontend.components.matieres')
@endsection
