import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    console.log("Product page");

    $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/products/data',
            type: 'GET',
            dataSrc: function (json) {
                console.log("DataTables response:", json);
                return json.data;
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        },
        columnDefs: [
            {
                targets: '_all',
                createdCell: function (td) {
                    $(td).addClass('text-gray-800 dark:text-gray-200');
                }
            }
        ],
        columns: [
            { data: 'name', name: 'name',searchable: true },
            // { data: 'supplier', name: 'supplier', searchable: true },
            { data: 'brand', name: 'brand', searchable: true },
            { data: 'category', name: 'category', searchable: true },
            { data: 'productType', name: 'productType', searchable: true },
            // { data: 'purchase_quantity', name: 'purchase_quantity', searchable: true },
            // { data: 'sales_price', name: 'sales_price', searchable: true },
            // { data: 'purchase_price', name: 'purchase_price', searchable: true },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#productForm').on('submit', function (e) {
        e.preventDefault();
    
        const form = this;
        const formData = new FormData(form);
        const actionUrl = $(form).attr('action');
    
        let method = $(form).find('input[name="_method"]').val() || 'POST';
        console.log("Form method:", method);
        Swal.fire({
            title: 'Processing...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    
        $.ajax({
            url: actionUrl,
            method: "POST",
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
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    window.location.href = "/products";
                });
            },
    
            error: function (xhr) {
                Swal.close();
    
                let errorMsg = 'Something went wrong';
    
                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                }
    
                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    });
    
    
    $('#batchForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const actionUrl = $(form).attr('action');

        Swal.fire({
            title: 'Adding Stock...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: actionUrl,
            method: "POST",
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
                    text: response.message || 'Stock added successfully',
                    icon: 'success'
                }).then(() => {
                    // Option 1: redirect
                    window.location.href = "/products";

                    // Option 2 (better UX): reset form instead
                    // form.reset();
                });
            },

            error: function (xhr) {
                Swal.close();

                let errorMsg = 'Something went wrong';

                if (xhr.responseJSON?.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                } else if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMsg,
                    icon: 'error'
                });
            }
        });
    });

    
});