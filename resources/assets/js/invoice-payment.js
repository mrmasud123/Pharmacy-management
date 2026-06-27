import $ from 'jquery';
window.$ = window.jQuery = $;


$(function () {
    const $form = $('#payDueForm');
    const $amountInput = $('#payDueAmountInput');
    const $amountError = $('#payDueAmountError');

    $form.on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const actionUrl = $(form).attr('action');

        if (!actionUrl || actionUrl === '#') {
            Swal.fire({
                title: 'Error!',
                text: 'Invoice reference is missing. Please reopen the payment dialog.',
                icon: 'error'
            });
            return;
        }

        const formData = new FormData(form);

        $amountError.addClass('hidden').text('');

        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(form).find('button[type="submit"]').prop('disabled', true);
            },
            complete: function () {
                $(form).find('button[type="submit"]').prop('disabled', false);
            },
            success: function (response) {
                Swal.close();

                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Payment recorded successfully.',
                    icon: 'success'
                }).then(() => {
                    // window.location.reload();
                    console.log(response);
                });
            },
            error: function (xhr) {
                Swal.close();

                let errorMsg = 'Something went wrong. Please try again.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const firstError = Object.values(xhr.responseJSON.errors)[0];
                    errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;

                    $amountError.removeClass('hidden').text(errorMsg);
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    });

    // Reset validation message as soon as user edits the amount again
    $amountInput.on('input', function () {
        $amountError.addClass('hidden').text('');
    });
});
