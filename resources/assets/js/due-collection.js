import $ from 'jquery';
window.$ = window.jQuery = $;

import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    console.log("Due collection page");

    if($('#dueCollectionTable').length){
        const table=$('#dueCollectionTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 10,
            ajax: {
                url : '/due-collections/data',
                type : 'GET',
                data : function (d){
                    d.customer_id = $('#customerSelect').val() || '';

                    const collectionDate = $('[name="collection_date"]').val();

                    if(collectionDate){
                        d.collection_date= collectionDate;
                    }

                    return d;
                },
                error : function(xhr){
                    console.log("Ajax Error : " + xhr.responseText);
                }
            },

            columns: [


                {
                    data: 'customer_name',
                    render: function (data) {
                        return `<div class="font-medium text-gray-800 dark:text-white">${data}</div>`;
                    }
                },
                {
                    data: 'items_count',
                    render: function (data) {
                        return `<span class="font-medium flex items-center justify-center ms-auto text-center h-5 w-5 rounded-md bg-green-300 text-black">${data}</span>`;
                    }
                },

                {
                    data: 'total',
                    render: data => `৳ ${parseFloat(data).toFixed(2)}`
                },
                {
                    data: 'discount',
                    render: data => `৳ ${parseFloat(data).toFixed(2)}`
                },
                {
                    data: 'tax',
                    render: data => `৳ ${parseFloat(data).toFixed(2)}`
                },
                {
                    data: 'grand_total',
                    render: data => `৳ ${parseFloat(data).toFixed(2)}`
                },

                {
                    data: 'paid',
                    render: function(data){
                        return `<span class="text-green-500">৳ ${parseFloat(data).toFixed(2)}</span>`;
                    }
                },

                {
                    data: 'due',
                    // className: "text-right",
                    render: function (data) {
                        let val = parseFloat(data);

                        let color = val > 0
                            ? 'text-red-600 font-semibold'
                            : 'text-green-600 font-semibold';

                        return `<span class="${color}">৳ ${val.toFixed(2)}</span>`;
                    }
                },

                {
                    data: 'status_badge',
                },

                {
                    data: 'action',
                }
            ],
            rowCallback: function(row, data) {
                if (parseFloat(data.due) > 0) {
                    $(row).addClass('bg-red-50 dark:bg-red-900/10');
                }
            },

            language: {
                search: "",
                searchPlaceholder: "Search invoice or customer..."
            }
        });


        const customerTom = new TomSelect('#customerSelect', {

            valueField: 'id',
            labelField: 'name',
            searchField: ['name', 'phone'],

            placeholder: 'Search customer...',

            load: function(query, callback) {

                if (!query.length) return callback();

                $.ajax({
                    url: '/customers/search',
                    type: 'GET',
                    data: { q: query },

                    success: function(res) {
                        callback(res.data || []);
                    },

                    error: function() {
                        callback([]);
                    }
                });
            },

            render: {
                option: function(item, escape) {

                    return `
                        <div class="p-2">
                            <div class="font-medium">
                                ${escape(item.name)}
                            </div>

                            <div class="text-xs text-gray-500">
                                ${escape(item.phone || '')}
                            </div>
                        </div>
                    `;
                }
            }
        });

        customerTom.on('change', function () {
            reloadTable();
        });

        $('[name="collection_date"]').on('change', function () {
            reloadTable();
        });
        function reloadTable() {
            table.ajax.reload();
        }

        $('#clearFilter').on('click', function (){
            customerTom.clear();

            $('[name="collection_date"]').val('');

            table.ajax.reload();
        });
    }

    // $('#employeeForm').on('submit', function (e) {
    //         e.preventDefault();
    //
    //         const form = this;
    //         const formData = new FormData(form);
    //         const actionUrl = $(form).attr('action');
    //
    //         let method = $(form).find('input[name="_method"]').val() || 'POST';
    //         console.log("Form method:", method);
    //         Swal.fire({
    //             title: 'Processing...',
    //             allowOutsideClick: false,
    //             didOpen: () => Swal.showLoading()
    //         });
    //
    //         $.ajax({
    //             url: actionUrl,
    //             method: "POST",
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //             beforeSend: function () {
    //                 $(form).find('button[type="submit"]').prop('disabled', true);
    //             },
    //             complete: function () {
    //                 $(form).find('button[type="submit"]').prop('disabled', false);
    //             },
    //             success: function (response) {
    //                 Swal.close();
    //
    //                 Swal.fire({
    //                     title: 'Success!',
    //                     text: response.message,
    //                     icon: 'success'
    //                 }).then(() => {
    //                     window.location.href = "/customers";
    //                 });
    //             },
    //
    //             error: function (xhr) {
    //                 Swal.close();
    //
    //                 let errorMsg = 'Something went wrong';
    //
    //                 if (xhr.responseJSON?.errors) {
    //                     errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
    //                 }
    //
    //                 Swal.fire({
    //                     title: 'Error!',
    //                     text: errorMsg,
    //                     icon: 'error'
    //                 });
    //             }
    //         });
    // });
    //
    //
    // $('#employeeRoleMappingForm').on('submit', function (e) {
    //         e.preventDefault();
    //
    //         const form = this;
    //         const formData = new FormData(form);
    //         const actionUrl = $(form).attr('action');
    //
    //         let method = $(form).find('input[name="_method"]').val() || 'POST';
    //         console.log("Form method:", method);
    //         Swal.fire({
    //             title: 'Processing...',
    //             allowOutsideClick: false,
    //             didOpen: () => Swal.showLoading()
    //         });
    //
    //         $.ajax({
    //             url: actionUrl,
    //             method: "POST",
    //             data: formData,
    //             processData: false,
    //             contentType: false,
    //             beforeSend: function () {
    //                 $(form).find('button[type="submit"]').prop('disabled', true);
    //             },
    //             complete: function () {
    //                 $(form).find('button[type="submit"]').prop('disabled', false);
    //             },
    //             success: function (response) {
    //                 Swal.close();
    //                 console.log(response)
    //                 Swal.fire({
    //                     title: 'Success!',
    //                     text: response.message,
    //                     icon: 'success'
    //                 }).then(() => {
    //                     window.location.href = "/role-permission-mapping";
    //                 });
    //             },
    //
    //             error: function (xhr) {
    //                 Swal.close();
    //
    //                 let errorMsg = 'Something went wrong';
    //
    //                 if (xhr.responseJSON?.errors) {
    //                     errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
    //                 }
    //
    //                 Swal.fire({
    //                     title: 'Error!',
    //                     text: errorMsg,
    //                     icon: 'error'
    //                 });
    //             }
    //         });
    // });

});
