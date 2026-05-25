import $ from 'jquery';
window.$ = window.jQuery = $;

import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';

import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {

    console.log("Sales page");
 
    if ($('#salesTable').length) {
 
        const table = $('#salesTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,

            ajax: {
                url: '/sales/data',
                type: 'GET',

                data: function (d) {

                    d.customer_id = $('#customerSelect').val() || '';
                    d.invoice_no  = $('#invoiceSelect').val() || '';

                    const saleDate = $('[name="sale_date"]').val();

                    if (saleDate) {
                        d.sale_date = saleDate;
                    }

                    return d;
                },

                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            },

            columns: [
                { data: 'invoice_no',  name: 'invoice_no' },
                { data: 'date',        name: 'created_at' },

                {
                    data: 'customer',
                    name: 'customer',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'items',
                    name: 'items',
                    searchable: false,
                    orderable: false
                },

                { data: 'grand_total', name: 'grand_total' },
                { data: 'paid',        name: 'paid' },

                {
                    data: 'due',
                    name: 'due',
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                }
            ]
        });
 
        function reloadTable() {
            table.ajax.reload();
        }

 
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
 
        const invoiceTom = new TomSelect('#invoiceSelect', {

            valueField: 'invoice_no',
            labelField: 'invoice_no',
            searchField: ['invoice_no'],

            placeholder: 'Search invoice...',

            load: function(query, callback) {

                if (!query.length) return callback();

                $.ajax({
                    url: '/customers/invoice',
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
                                ${escape(item.invoice_no)}
                            </div>
                        </div>
                    `;
                }
            }
        });

        invoiceTom.on('change', function () {
            reloadTable();
        });

 
        $('[name="sale_date"]').on('change', function () {
            reloadTable();
        });
    }

});