import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    console.log("Product Type page");

    let table=$('#productTypesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/product-types/data',
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
            { data: 'code', name: 'code', searchable: true },
                
            { data: 'status', name: 'is_active', searchable: false },
            { data: 'slug', name: 'slug', searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#productTypeForm').on('submit', function (e) {
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
    
            success: function (response) {
                Swal.close();
    
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    window.location.href = "/product-types";
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
    
    $(document).on('change', '.productTypeStatusToggler', function () {
        let id = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;
        Swal.fire({
            title: 'Updating Category...',
            text: 'Please wait while the product type is being updated.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
        $.ajax({
            url: `/admin/product-type/status/${id}`, 
            type: 'POST',
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
    
            success: function (response) {
                Swal.close();
                console.log("Product type updated:", response);
    
                Swal.fire({
                    title: 'Success!',
                    text: 'Product type updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                })
                
                table.ajax.reload(null, false);
            },
    
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: Object.values(xhr.responseJSON?.errors || {})[0] || 'An error occurred',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                table.ajax.reload(null, false);
            }
        });
    });
});