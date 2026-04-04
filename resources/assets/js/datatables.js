// import $ from 'jquery';
// window.$ = window.jQuery = $;

// import 'datatables.net';
// import 'datatables.net-dt';
// import 'datatables.net-dt/css/dataTables.dataTables.css';

// $(document).ready(function () {
//     $('#rolesTable').DataTable({
//         paging: true,
//         searching: true,
//         info: true,
//         pageLength: 10,
//         columnDefs: [
//             { orderable: false, targets: 2 } // disable sorting on Action column
//         ]
//     });
// });

import $ from 'jquery';
window.$ = window.jQuery = $;

import 'datatables.net';
import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/roles/data',

        columns: [
            { data: 'name', name: 'name' },
            { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],

        pageLength: 10
    });
});