$(document).ready(function () {

    $('#createRoleForm').on('submit', function (e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const actionUrl = $(this).attr('action');
        const method = $(this).attr('method');

        // Show loading
        Swal.fire({
            title: 'Creating Role...',
            text: 'Please wait while the role is being created.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,

            success: function (response) {
                Swal.close(); 
                Swal.fire({
                    title: 'Success!',
                    text: 'Role created successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "/roles";
                    // window.location.href = '/account';
                });
            },

            error: function (xhr) {
                Swal.close(); 

                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'An error occurred',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

});