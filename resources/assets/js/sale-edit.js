import $ from 'jquery';
window.$ = window.jQuery = $;

function recalcRow($row) {
    const qty      = parseFloat($row.find('.qty-input').val()) || 0;
    const rate     = parseFloat($row.find('.rate-input').val()) || 0;
    const discPct  = parseFloat($row.find('.discount-input').val()) || 0;
    const vatPct   = parseFloat($row.find('.vat-input').val()) || 0;

    const lineSubtotal  = qty * rate;
    const discountAmt   = lineSubtotal * (discPct / 100);
    const afterDiscount = lineSubtotal - discountAmt;
    const vatAmt         = afterDiscount * (vatPct / 100);
    const lineTotal      = afterDiscount + vatAmt;

    $row.find('.line-total')
        .attr('data-raw', lineTotal.toFixed(2))
        .text(lineTotal.toFixed(2));

    return { lineSubtotal, discountAmt, vatAmt, lineTotal };
}

function recalcAll() {
    let subtotal = 0, discount = 0, tax = 0;

    $('#itemsBody .item-row').each(function () {
        const r = recalcRow($(this));
        subtotal += r.lineSubtotal;
        discount += r.discountAmt;
        tax      += r.vatAmt;
    });

    const grandTotal = subtotal - discount + tax;
    const paid = parseFloat($('#paidInput').val()) || 0;
    const due = grandTotal - paid;

    $('#summarySubtotal').text(subtotal.toFixed(2));
    $('#summaryDiscount').text(discount.toFixed(2));
    $('#summaryTax').text(tax.toFixed(2));
    $('#summaryGrandTotal').text(grandTotal.toFixed(2));
    $('#summaryPaid').text(paid.toFixed(2));

    const $due = $('#summaryDue');
    $due.text(Math.abs(due).toFixed(2));
    $due.removeClass('text-red-500 dark:text-red-400 text-green-500 dark:text-green-400');
    $due.addClass(due > 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400');

    // Validate paid never exceeds grand total — no overpayment allowed
    const $paidError = $('#paidError');
    if (paid > grandTotal) {
        $paidError.removeClass('hidden').text('Paid amount cannot exceed grand total (' + grandTotal.toFixed(2) + ').');
    } else {
        $paidError.addClass('hidden').text('');
    }

    return { grandTotal, paid };
}

$(function () {
    recalcAll();

    $('#itemsBody').on('input change', '.qty-input, .rate-input, .discount-input, .vat-input', function () {
        recalcAll();
    });

    $('#paidInput').on('input', function () {
        recalcAll();
    });

    $('#itemsBody').on('click', '.remove-item-btn', function () {
        const $rows = $('#itemsBody .item-row');
        if ($rows.length <= 1) {
            Swal.fire('Notice', 'An invoice must have at least one item.', 'info');
            return;
        }
        $(this).closest('tr').remove();
        recalcAll();
    });

    $('#saleEditForm').on('submit', function (e) {
        e.preventDefault();

        const { grandTotal, paid } = recalcAll();

        if (paid > grandTotal) {
            Swal.fire('Validation Error', 'Paid amount cannot exceed the grand total.', 'error');
            return;
        }

        const form = this;
        const formData = new FormData(form);

        Swal.fire({
            title: 'Saving changes...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: $(form).attr('action'),
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
                    title: 'Saved!',
                    text: response.message || 'Invoice updated successfully.',
                    icon: 'success'
                }).then(() => {
                    window.location.href = response.redirect || document.referrer || '/';
                });
            },
            error: function (xhr) {
                Swal.close();
                let errorMsg = 'Something went wrong.';
                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                } else if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });
});
