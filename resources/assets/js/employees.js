import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    console.log("Roles page");

    $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/users/with/roles/permissions/data',
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
            { data: 'name', name: 'name' },
            { data: 'role', orderable: false, searchable: false },
            // { data: 'permissions', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });


    $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        searching:true,
        ajax: {
            url: 'admin/employee/data',
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
            { data: 'name', name: 'name' },
            { data: 'employee_code', orderable: false, searchable: false },
            { data: 'designation', orderable: false, searchable: false },
            { data: 'join_date', orderable: false, searchable: false },
            { data: 'email', orderable: false, searchable: false },
            { data: 'phone', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });



    $('#employeeForm').on('submit', function (e) {
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
                        window.location.href = "/employees";
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


    $('#employeeRoleMappingForm').on('submit', function (e) {
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
                    console.log(response)
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = "/role-permission-mapping";
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

});
