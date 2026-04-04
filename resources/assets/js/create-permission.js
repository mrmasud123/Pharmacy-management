$(document).ready(function () {

    $('#createPermissionForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);

        Swal.fire({
            title: 'Creating Permission...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),

            success: function () {
                Swal.close();

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Permission created successfully',
                }).then(() => {
                    window.location.href = "/permissions";
                });
            },

            error: function (xhr) {
                Swal.close();

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Something went wrong',
                });
            }
        });
    });

});