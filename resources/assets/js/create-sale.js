
import $ from 'jquery';
window.$ = window.jQuery = $;
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';
import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';


$(function () {
    console.log("Create sale")
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#saleForm').on('submit', function (e) {
        e.preventDefault();

        const payload = {
            items:  collectItems(),
            total:  $('#grand_total').val(),
            paid: $('[name="paid"]').val(),
            customer_id: $('[name="customer_id"]').val(),
            sale_date: $('[name="sale_date"]').val()
        };
        console.log(payload);

        if (!payload.items.length) {
            Swal.fire('Error', 'Please add at least one product', 'error');
            return;
        }

        Swal.fire({
            title: 'Processing Sale...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: '/sales',
            method: 'POST',
            data: JSON.stringify(payload),
            contentType: 'application/json',
            success: function (res) {
                console.log(res)
                Swal.fire('Success', res.message, 'success')
                    .then(() => window.location.href = '/collections');

            },
            error: function (xhr) {
                // Swal.fire('Error', xhr.responseJSON?.message || 'Sale failed', 'error');
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Sale failed',
                    icon: 'error'
                });
            },
            // complete: function () {
            //     Swal.close();
            // }
        });
    });

    let rowIndex = 0;

    function addRow() {

        const row = `
        <tr data-row="${rowIndex}" class="border-t dark:border-gray-700">

            {{-- Product Search (Tom Select) --}}
            <td class="p-2">
                <select class="product_search w-full" name="items[${rowIndex}][product_id]">
                    <option value="" disabled selected>Search product...</option>
                </select>
            </td>


            <td class="p-2">
                <input type="text" placeholder="Description"
                    name="items[${rowIndex}][desc]"
                    class="w-full input-style" />
            </td>


            <td class="p-2">
                <select name="items[${rowIndex}][batch_id]" class="batch_select w-full input-style">
                    <option value="" disabled selected>Select Batch</option>
                </select>
            </td>


            <td class="p-2">
                <input type="number" value="0" readonly
                    class="available_qty w-full input-style bg-gray-100 dark:bg-gray-800" />
            </td>


            <td class="p-2">
                <input type="text" value="None" readonly
                    class="unit w-full input-style bg-gray-100 dark:bg-gray-800" />
            </td>

            <td class="p-2">
                <input type="number" value="1" min="1" max=""
                    name="items[${rowIndex}][qty]"
                    class="qty w-full input-style" />
            </td>

            <td class="p-2">
                <input type="number" value="0"
                    name="items[${rowIndex}][rate]"
                    readonly
                    class="rate w-full input-style" />
            </td>


            <td class="p-2">
                <input type="number" value="0" min="0" max="100"
                    name="items[${rowIndex}][discount]"
                    class="discount w-full input-style" />
            </td>


            <td class="p-2">
                <input type="number" value="0" min="0"
                    name="items[${rowIndex}][vat]"
                    class="vat w-full input-style" />
            </td>

            <td class="p-2">
                <input type="number" value="0" readonly
                    class="total w-full input-style bg-gray-100 dark:bg-gray-800" />
            </td>

            <td class="p-2 text-center">
                <button type="button" class="removeRow bg-red-500 text-white px-2 py-1 rounded">×</button>
            </td>

        </tr>`;

        $('#sale_items').append(row);

        const newRow = $('#sale_items tr[data-row="' + rowIndex + '"]');
        const selectEl = newRow.find('.product_search')[0];

        new TomSelect(selectEl, {
            valueField:  'id',
            labelField:  'name',
            searchField: 'name',
            preload:     false,
            placeholder: 'Search product...',

            load: function (query, callback) {
                if (query.length < 2) return callback();
                $.get('/get-products', { q: query }, callback)
                 .fail(() => callback());
            },

            onChange: function (productId) {
                if (!productId) return;

                newRow.find('.batch_select').html('<option value="">Loading...</option>');
                newRow.find('.available_qty').val(0);
                newRow.find('.unit').val('None');
                newRow.find('.rate').val(0);
                calculateRow(newRow);

                $.get(`/get-product-batches/${productId}`, function (data) {

                    let options = '<option value="">Select Batch</option>';

                    data.forEach(b => {
                        options += `<option
                            value="${b.id}"
                            data-qty="${b.quantity}"
                            data-price="${b.sales_price}"
                            data-unit="${b.unit?.name ?? 'None'}">
                            ${b.batch_no}
                        </option>`;
                    });

                    newRow.find('.batch_select').html(options);
                });
            },

            render: {
                no_results: () => `<div class="p-2 text-gray-500 text-sm">No products found</div>`
            }
        });

        rowIndex++;
    }

    addRow();

    $('#addRow').on('click', function () {
        addRow();
    });

    $(document).on('click', '.removeRow', function () {
        if ($('#sale_items tr').length === 1) {
            Swal.fire('Warning', 'At least one product row is required', 'warning');
            return;
        }
        $(this).closest('tr').remove();
        calculateGrandTotal();
    });

    $(document).on('change', '.batch_select', function () {

        const tr       = $(this).closest('tr');
        const selected = $(this).find(':selected');

        tr.find('.available_qty').val(selected.data('qty')   || 0);
        tr.find('.rate').val(       selected.data('price')   || 0);
        tr.find('.unit').val(       selected.data('unit')    || 'None');

        calculateRow(tr);
    });

    $(document).on('input', '.qty, .rate, .discount, .vat', function () {
        calculateRow($(this).closest('tr'));
    });

    function calculateRow(tr) {

        const qty      = parseFloat(tr.find('.qty').val())      || 0;
        const rate     = parseFloat(tr.find('.rate').val())     || 0;
        const discount = parseFloat(tr.find('.discount').val()) || 0;
        const vat      = parseFloat(tr.find('.vat').val())      || 0;

        const subtotal      = qty * rate;
        const discountAmt   = subtotal * (discount / 100);
        const afterDiscount = subtotal - discountAmt;
        const vatAmt        = afterDiscount * (vat / 100);
        const total         = afterDiscount + vatAmt;

        tr.find('.total').val(total.toFixed(2));

        calculateGrandTotal();
    }

    function calculateGrandTotal() {

        let sum = 0;

        $('.total').each(function () {
            sum += parseFloat($(this).val()) || 0;
        });

        $('#grand_total').val(sum.toFixed(2));
    }

    function collectItems() {

        const items = [];

        $('#sale_items tr').each(function () {
            const tr        = $(this);
            const productId = tr.find('[name*="product_id"]').val();
            const batchId   = tr.find('.batch_select').val();

            if (!productId || !batchId) return;

            items.push({
                product_id: productId,
                batch_id:   batchId,
                desc:       tr.find('[name*="desc"]').val(),
                qty:        tr.find('.qty').val(),
                rate:       tr.find('.rate').val(),
                discount:   tr.find('.discount').val(),
                vat:        tr.find('.vat').val(),
                total:      tr.find('.total').val(),
            });
        });

        return items;
    }




    $(document).on('click', '#addCustomerBtn', function () {

        Swal.fire({
            title: '<span class="text-lg font-semibold text-gray-800">Add Customer</span>',
            html: `
                <div class="space-y-4 text-left">

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Name *</label>
                        <input type="text" id="cust_name"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
                            placeholder="Enter customer name">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                        <input type="tel" oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="cust_phone"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
                            placeholder="Enter phone number">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Address</label>
                        <textarea id="cust_address"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm"
                            rows="3"
                            placeholder="Enter address"></textarea>
                    </div>

                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Customer',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-xl p-6',
                confirmButton: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium mr-2',
                cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium'
            },

            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),

            preConfirm: () => {

                let name = $('#cust_name').val();
                let phone = $('#cust_phone').val();
                let address = $('#cust_address').val();

                if (!name) {
                    Swal.showValidationMessage('Name is required');
                    return false;
                }

                // Return AJAX Promise (IMPORTANT FIX)
                return $.ajax({
                    url: '/customers/store',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name,
                        phone: phone,
                        address: address
                    }
                }).then(function (res) {
                    return res;
                }).catch(function (xhr) {

                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;

                        let errorMsg = Object.values(errors)
                            .map(err => err[0])
                            .join('<br>');

                        Swal.showValidationMessage(errorMsg);
                    } else {
                        Swal.showValidationMessage('Something went wrong');
                    }

                    return false;
                });
            }

        }).then((result) => {

            if (result.isConfirmed && result.value) {

                let res = result.value;


                $('input[name="customer"]').val(res.data.name);

                Swal.fire({
                    icon: 'success',
                    title: 'Customer Added',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });

    });

    let customerSelect = new TomSelect("#customerSelect", {
            valueField: "id",
            labelField: "name",
            searchField: ["name", "phone"],
            placeholder: "Search customer...",

            load: function(query, callback) {
                if (!query.length) return callback();

                $.ajax({
                    url: "/customers/search",
                    type: "GET",
                    data: { q: query },
                    success: function(res) {
                        callback(res.data);
                    },
                    error: function() {
                        callback();
                    }
                });
            },

            render: {
                option: function(item, escape) {
                    return `
                        <div class="p-2">
                            <div class="font-medium">${escape(item.name)}</div>
                            <div class="text-xs text-gray-500">${escape(item.phone || '')}</div>
                        </div>
                    `;
                }
            }
        });
    });
