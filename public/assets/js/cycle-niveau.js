/**
 * Gestion avancée des Cycles et Niveaux
 * Fonctionnalités: drag-and-drop, recherche, confirmations, animations
 */

class CycleNiveauManager {
    constructor() {
        this.initializeComponents();
        this.setupEventListeners();
        this.loadSavedState();
    }

    initializeComponents() {
        // Initialiser SortableJS pour le drag-and-drop
        if (typeof Sortable !== 'undefined' && document.getElementById('niveau-list')) {
            this.initSortable();
        }

        // Initialiser les tooltips
        this.initTooltips();
        
        // Initialiser la recherche
        this.initSearch();
        
        // Initialiser les animations
        this.initAnimations();
    }

    initSortable() {
        const listElement = document.getElementById('niveau-list');
        if (!listElement) return;

        this.sortable = Sortable.create(listElement, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onStart: (evt) => {
                evt.item.style.opacity = '0.5';
            },
            onEnd: (evt) => {
                evt.item.style.opacity = '1';
                this.updatePositions();
            }
        });
    }

    updatePositions() {
        const items = document.querySelectorAll('.niveau-item');
        const updates = [];

        items.forEach((item, index) => {
            const id = item.dataset.id;
            if (id) {
                updates.push({
                    id: id,
                    position: index + 1
                });
            }
        });

        // Envoyer les nouvelles positions au serveur
        this.savePositions(updates);
    }

    savePositions(updates) {
        fetch('/admin/niveau/update-positions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ updates: updates })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Ordre mis à jour avec succès!', 'success');
            } else {
                this.showNotification('Erreur lors de la mise à jour', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            this.showNotification('Erreur lors de la mise à jour', 'error');
        });
    }

    initTooltips() {
        // Initialiser les tooltips Bootstrap si disponible
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    }

    initSearch() {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) return;

        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.performSearch(e.target.value);
            }, 300);
        });

        // Ajouter un bouton de clear
        this.addClearButton(searchInput);
    }

    performSearch(term) {
        const items = document.querySelectorAll('.niveau-item');
        const searchTerm = term.toLowerCase().trim();

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            const shouldShow = !searchTerm || text.includes(searchTerm);
            
            if (shouldShow) {
                item.style.display = 'block';
                item.classList.remove('filtered');
            } else {
                item.style.display = 'none';
                item.classList.add('filtered');
            }
        });

        // Mettre à jour le compteur de résultats
        this.updateSearchResults(searchTerm);
    }

    addClearButton(searchInput) {
        const clearButton = document.createElement('button');
        clearButton.innerHTML = '<i class="ri-close-line"></i>';
        clearButton.className = 'btn btn-outline-secondary btn-sm position-absolute';
        clearButton.style.cssText = 'right: 5px; top: 50%; transform: translateY(-50%); z-index: 10; opacity: 0;';
        clearButton.type = 'button';

        searchInput.parentNode.style.position = 'relative';
        searchInput.parentNode.appendChild(clearButton);

        clearButton.addEventListener('click', () => {
            searchInput.value = '';
            this.performSearch('');
            clearButton.style.opacity = '0';
        });

        searchInput.addEventListener('input', (e) => {
            clearButton.style.opacity = e.target.value ? '1' : '0';
        });
    }

    updateSearchResults(term) {
        const visibleItems = document.querySelectorAll('.niveau-item:not(.filtered)').length;
        let resultText = '';

        if (term) {
            resultText = `${visibleItems} résultat(s) pour "${term}"`;
        }

        // Afficher le résultat si un élément existe pour cela
        const resultElement = document.getElementById('search-results');
        if (resultElement) {
            resultElement.textContent = resultText;
        }
    }

    initAnimations() {
        // Animation d'entrée pour les éléments
        const items = document.querySelectorAll('.niveau-item');
        items.forEach((item, index) => {
            item.style.animation = `fadeInUp 0.6s ease forwards`;
            item.style.animationDelay = `${index * 0.1}s`;
        });

        // Animation pour les cartes statistiques
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            card.style.animation = `slideInDown 0.8s ease forwards`;
            card.style.animationDelay = `${index * 0.2}s`;
        });
    }

    setupEventListeners() {
        // Gestion améliorée de la suppression
        document.addEventListener('click', (e) => {
            if (e.target.closest('.delete')) {
                e.preventDefault();
                this.handleDelete(e.target.closest('.delete'));
            }
        });

        // Auto-save pour les formulaires
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            this.addAutoSave(form);
        });

        // Gestion des erreurs globales
        window.addEventListener('error', (e) => {
            console.error('Erreur JavaScript:', e.error);
        });
    }

    handleDelete(deleteButton) {
        const id = deleteButton.dataset.id;
        const itemElement = deleteButton.closest('.niveau-item');
        const itemName = itemElement.querySelector('.fw-semibold, .fw-medium').textContent.trim();

        // Configuration SweetAlert2 améliorée
        Swal.fire({
            title: 'Confirmer la suppression',
            html: `
                <div class="text-start">
                    <p>Êtes-vous sûr(e) de vouloir supprimer :</p>
                    <div class="alert alert-warning">
                        <strong>"${itemName}"</strong>
                    </div>
                    <small class="text-muted">
                        <i class="ri-information-line"></i>
                        Cette action est irréversible et supprimera également tous les sous-niveaux associés.
                    </small>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Supprimer définitivement',
            cancelButtonText: '<i class="ri-close-line me-1"></i> Annuler',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return this.performDelete(id);
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                // Animation de suppression
                itemElement.style.animation = 'fadeOut 0.5s ease forwards';
                setTimeout(() => {
                    location.reload();
                }, 500);
                
                Swal.fire({
                    title: 'Supprimé!',
                    text: `"${itemName}" a été supprimé avec succès.`,
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }

    async performDelete(id) {
        try {
            const response = await fetch(`/admin/niveau/delete/${id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            return data;
        } catch (error) {
            Swal.showValidationMessage('Erreur lors de la suppression');
            throw error;
        }
    }

    addAutoSave(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                this.saveFormState(form);
            });
        });
    }

    saveFormState(form) {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        localStorage.setItem(`form_${form.id || 'default'}`, JSON.stringify(data));
    }

    loadSavedState() {
        // Restaurer l'état des formulaires
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const saved = localStorage.getItem(`form_${form.id || 'default'}`);
            if (saved) {
                try {
                    const data = JSON.parse(saved);
                    Object.keys(data).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input && input.value === '') {
                            input.value = data[key];
                        }
                    });
                } catch (e) {
                    console.warn('Erreur lors du chargement de l\'état du formulaire:', e);
                }
            }
        });
    }

    showNotification(message, type = 'info') {
        // Créer une notification toast
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(toast);

        // Auto-supprimer après 5 secondes
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }
}

// Styles CSS pour les animations
const animationStyles = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.9);
        }
    }

    .sortable-ghost {
        opacity: 0.3;
    }

    .sortable-chosen {
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
`;

// Ajouter les styles
const styleSheet = document.createElement("style");
styleSheet.textContent = animationStyles;
document.head.appendChild(styleSheet);

// Initialiser le gestionnaire quand le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
    new CycleNiveauManager();
});

// Export pour utilisation dans d'autres scripts
window.CycleNiveauManager = CycleNiveauManager;