<style>
    /* Cartes de sujets : format horizontal compact, 2 blocs (aperçu / infos).
       Partagé entre le catalogue (index) et les "sujets similaires" (show). */
    .subject-card-h {
        position: relative;
        cursor: pointer;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
        border: 1px solid var(--ms-border-subtle);
        border-radius: var(--ms-radius-md);
        overflow: hidden;
        box-shadow: var(--ms-shadow-rest);
    }

    .subject-card-h:hover {
        box-shadow: var(--ms-shadow-hover);
        border-color: var(--ms-border);
    }

    .subject-preview {
        width: 82px;
        min-height: 82px;
        position: relative;
        overflow: hidden;
    }

    .subject-preview i {
        font-size: 1.4rem;
    }

    .subject-info {
        padding: 0.6rem 0.7rem;
        font-weight: 300;
        font-size: 0.8rem;
    }

    .min-w-0 { min-width: 0; }

    .subject-title-sm {
        color: #2d3748;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .badge-code-sm {
        font-size: 0.65rem;
        font-weight: 500;
        background: var(--ms-navy);
        color: #fff;
        padding: 0.15rem 0.4rem;
        border-radius: 8px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .tag-sm {
        font-size: 0.68rem;
        font-weight: 400;
        padding: 0.15rem 0.45rem;
        border-radius: 10px;
        white-space: nowrap;
    }

    .tag-matiere { background: var(--ms-blue-light); color: var(--ms-blue-dark); }
    .tag-niveau { background: #f1f5f9; color: #64748b; }
    .tag-annee { background: var(--ms-orange-light); color: var(--ms-orange-dark); }

    .link-details-sm {
        font-size: 0.75rem;
        font-weight: 300;
        color: var(--ms-blue);
        text-decoration: none;
        white-space: nowrap;
    }

    .link-details-sm:hover { color: var(--ms-blue-dark); text-decoration: underline; }

    .cost-tag-sm {
        font-size: 0.68rem;
        font-weight: 500;
        color: var(--ms-orange-dark);
        white-space: nowrap;
    }
</style>
