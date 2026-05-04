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
            url: '/admin/roles/data',
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
            { data: 'permissions', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });
});