import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    console.log("Category page");

    let table =$('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/admin/category/data',
            type: 'GET',
            dataSrc: function (json) {
                console.log("DataTables response:", json);
                return json.data;
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
            }
        },
        columns: [
            { data: 'name', name: 'name',searchable: true },
            { data: 'status', name: 'is_active', searchable:false },
            { data: 'image', name: 'image', searchable:false},
            { data: 'action', orderable: false, searchable: false }
        ]
    });
    
    //Image preview
    
    const defaultImage = "https://placehold.co/300x250";

    // Preview image
    $('#imageInput').on('change', function (e) {
        let file = e.target.files[0];

        if (file) {
            let reader = new FileReader();

            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
        }
    });

    // Remove image
    $('#removeImage').on('click', function () {
        console.log("Image reset")
        $('#imageInput').val(''); // reset input
        $('#imagePreview').attr('src', defaultImage);  
    });
    
    //Create the category
    
    $('#createCategoryForm').on('submit', function (e) {
        e.preventDefault();
    
        const form = this;
        const formData = new FormData(form);
        const actionUrl = $(form).attr('action');
        const method = $(form).attr('method');
    
        Swal.fire({
            title: 'Creating Category...',
            text: 'Please wait while the category is being created.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    
        $.ajax({
            url: actionUrl,
            method: method,
            data: formData,
            processData: false,   
            contentType: false,    
    
            success: function (response) {
                Swal.close();
    
                Swal.fire({
                    title: 'Success!',
                    text: 'Category created successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "/categories";
                });
            },
    
            error: function (xhr) {
                console.log(xhr);
                Swal.close();
    
                Swal.fire({
                    title: 'Error!',
                    text: Object.values(xhr.responseJSON?.errors || {})[0] || 'An error occurred',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    //Update category
    $('#updateCategoryForm').on('submit', function (e) {
        e.preventDefault();
    
        const form = this;
        const formData = new FormData(form);
        const actionUrl = $(form).attr('action');
    
        Swal.fire({
            title: 'Updating Category...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    
        $.ajax({
            url: actionUrl,
            method: 'POST',  
            data: formData,
            processData: false,
            contentType: false,
    
            success: function (response) {
                Swal.close();
    
                Swal.fire({
                    title: 'Updated!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    window.location.href = "/categories";
                });
            },
    
            error: function (xhr) {
                Swal.close();
    
                let errorMsg = 'Something went wrong';
    
                if (xhr.responseJSON && xhr.responseJSON.errors) {
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
    
    $(document).on('change', '.categoryStatusToggler', function () {
        let id = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;
        Swal.fire({
            title: 'Updating Category...',
            text: 'Please wait while the category is being updated.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
        $.ajax({
            url: `/admin/category/status/${id}`, 
            type: 'POST',
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
    
            success: function (response) {
                Swal.close();
                console.log("Status updated:", response);
    
                Swal.fire({
                    title: 'Success!',
                    text: 'Category updated successfully.',
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