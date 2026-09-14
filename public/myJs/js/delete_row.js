function delete_row(route){
    // Délégation sur document plutôt que liaison directe sur .delete : les tableaux
    // utilisent DataTables (pagination côté client), qui détache du DOM les lignes
    // des pages non affichées au moment du chargement. Une liaison directe ne
    // s'attache donc qu'aux lignes de la page 1 — sur les pages suivantes, le clic
    // suit alors le lien "#" sans jamais afficher la confirmation.
    $(document).on("click", '.delete', function(e) {
        e.preventDefault();
        var Id = $(this).attr('data-id');
        var url = '/admin/'+ route +'/delete/'
        Swal.fire({
            title: 'Etes-vous sûr(e) de vouloir supprimer ?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Supprimer!',
            cancelButtonText: 'Annuler',
            customClass: {
                confirmButton: 'btn btn-primary w-xs me-2 mt-2',
                cancelButton: 'btn btn-danger w-xs mt-2',
            },
            buttonsStyling: false,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url:  url+ Id,
                    dataType: "json",
                    // data: {
                    //     _token: '{{ csrf_token() }}',

                    // },
                    success: function(response) {
                        if (response.status == 200) {
                            Swal.fire({
                                title: 'Supprimé!',
                                text: 'Suppression effectuée avec succès.',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary w-xs mt-2',
                                },
                                buttonsStyling: false
                            })

                            $('#row_' + Id).remove();
                            location.reload();
                        } else {
                            Swal.fire({
                                title: 'Suppression impossible',
                                text: response.message || "Une erreur est survenue lors de la suppression.",
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary w-xs mt-2',
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Erreur',
                            text: "Une erreur est survenue lors de la suppression.",
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary w-xs mt-2',
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
        });
    });
}