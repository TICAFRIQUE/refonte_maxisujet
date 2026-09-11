/**
 * Analyse IA (Gemini) d'un sujet PDF avant enregistrement : appelle l'endpoint
 * d'analyse, pré-remplit catégorie/matière/niveau/concours, et laisse
 * l'utilisateur corriger avant soumission. Piloté par des attributs data-*
 * sur un conteneur portant la classe .ai-sujet-analyse (voir create.blade.php
 * admin et auteur), réutilisable tel quel pour les deux formulaires.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var container = document.querySelector('.ai-sujet-analyse');
        if (!container) {
            return;
        }

        var analyseUrl = container.dataset.analyseUrl;
        var fileInput = document.querySelector(container.dataset.fileInput);
        var hasConcours = container.dataset.hasConcours === 'true';
        var button = container.querySelector('.ai-analyse-btn');
        var status = container.querySelector('.ai-analyse-status');
        var hiddenSentinels = document.getElementById('ai_new_taxonomy');

        var categorieSelect = document.getElementById('categorie_id');
        var matiereSelect = document.getElementById('matiere_id');
        var concoursSelect = hasConcours ? document.getElementById('concours_id') : null;
        var concoursField = hasConcours ? document.getElementById('concours-field') : null;
        var niveauxSelect = document.getElementById('niveaux');

        if (!analyseUrl || !fileInput || !button) {
            return;
        }

        function setStatus(message, type) {
            if (!status) return;
            status.textContent = message || '';
            status.className = 'ai-analyse-status small mt-2' + (type ? ' text-' + type : '');
        }

        function updateButtonState() {
            var file = fileInput.files && fileInput.files[0];
            var isPdf = !!file && /\.pdf$/i.test(file.name);
            button.disabled = !isPdf;
            button.title = isPdf ? '' : 'Sélectionnez un fichier PDF pour utiliser l\'analyse IA';
            if (file && !isPdf) {
                setStatus('L\'analyse IA n\'est disponible que pour les fichiers PDF (les .doc/.docx se classent manuellement).', 'muted');
            } else {
                setStatus('');
            }
        }

        fileInput.addEventListener('change', updateButtonState);
        updateButtonState();

        function applyFlatSuggestion(select, suggestion) {
            if (!select || !suggestion) return;

            if (suggestion.status === 'existing') {
                select.value = suggestion.id;
            } else {
                var already = select.querySelector('option[value="' + suggestion.key + '"]');
                if (!already) {
                    var opt = document.createElement('option');
                    opt.value = suggestion.key;
                    opt.textContent = '🆕 ' + suggestion.label + ' (sera créé)';
                    select.appendChild(opt);
                }
                select.value = suggestion.key;
            }

            select.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function applyNiveauSuggestion(select, niveauPath) {
            if (!select || !niveauPath || !niveauPath.length) return;

            var leaf = niveauPath[niveauPath.length - 1];
            var instance = select.choicesInstance;

            if (!instance) {
                // Repli si Choices.js n'est pas initialisé sur ce champ (ne devrait pas
                // arriver côté admin) : sélection native directe.
                if (leaf.status === 'existing') {
                    var option = select.querySelector('option[value="' + leaf.id + '"]');
                    if (option) option.selected = true;
                } else {
                    var newOpt = document.createElement('option');
                    newOpt.value = leaf.key;
                    newOpt.textContent = '🆕 ' + leaf.label + ' (sera créé)';
                    newOpt.selected = true;
                    select.appendChild(newOpt);
                }
                select.dispatchEvent(new Event('change', { bubbles: true }));
                return;
            }

            if (leaf.status === 'existing') {
                instance.setChoiceByValue(leaf.id);
            } else {
                instance.setChoices(
                    [{ value: leaf.key, label: '🆕 ' + leaf.label + ' (sera créé)', selected: false }],
                    'value',
                    'label',
                    false
                );
                instance.setChoiceByValue(leaf.key);
            }
        }

        button.addEventListener('click', function () {
            var file = fileInput.files && fileInput.files[0];
            if (!file) {
                setStatus('Sélectionnez d\'abord un fichier PDF.', 'warning');
                return;
            }

            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            var formData = new FormData();
            formData.append('fichier', file);

            button.disabled = true;
            var originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Analyse en cours…';
            setStatus('');

            fetch(analyseUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.content : '',
                    Accept: 'application/json',
                },
                body: formData,
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (data.status === 'disabled') {
                        setStatus('Analyse IA non configurée sur ce site — sélection manuelle uniquement.', 'muted');
                        return;
                    }
                    if (data.status === 'unsupported_format') {
                        setStatus('Format non pris en charge par l\'analyse IA — sélection manuelle uniquement.', 'muted');
                        return;
                    }
                    if (data.status !== 'ok') {
                        setStatus(data.message || 'Analyse IA indisponible — merci de sélectionner manuellement.', 'danger');
                        return;
                    }

                    if (hiddenSentinels) {
                        hiddenSentinels.value = JSON.stringify(data.sentinels || {});
                    }

                    applyFlatSuggestion(categorieSelect, data.categorie);
                    applyFlatSuggestion(matiereSelect, data.matiere);
                    applyNiveauSuggestion(niveauxSelect, data.niveau);

                    if (hasConcours) {
                        if (data.concours) {
                            if (concoursField) concoursField.style.display = 'block';
                            applyFlatSuggestion(concoursSelect, data.concours);
                        } else if (concoursField) {
                            concoursField.style.display = 'none';
                            if (concoursSelect) concoursSelect.value = '';
                        }
                    }

                    setStatus('Suggestions appliquées — vérifiez et corrigez si besoin avant d\'enregistrer.', 'success');
                })
                .catch(function () {
                    setStatus('Analyse IA indisponible — merci de sélectionner manuellement.', 'danger');
                })
                .finally(function () {
                    button.disabled = false;
                    button.innerHTML = originalText;
                    updateButtonState();
                });
        });
    });
})();
