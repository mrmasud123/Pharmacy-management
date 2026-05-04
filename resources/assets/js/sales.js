import $ from 'jquery';
window.$ = window.jQuery = $;
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';
import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';

$(function () {
    console.log("Sales page");

    // ── DataTable (only init if table exists on this page) ──────────────────
    if ($('#salesTable').length) {
        $('#salesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/sales/data',
                type: 'GET',
                dataSrc: function (json) {
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
                { data: 'invoice_no',   name: 'invoice_no' },
                { data: 'date',         name: 'created_at' },
                { data: 'items',        name: 'items',       searchable: false },
                { data: 'grand_total',  name: 'grand_total' },
                { data: 'paid',         name: 'paid' },
                { data: 'due',          name: 'due' },
                // { data: 'status_badge', name: 'status',      orderable: false, searchable: false },
                { data: 'action',                            orderable: false, searchable: false }
            ]
        });
    }

    // ── CSRF ─────────────────────────────────────────────────────────────────
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
            paid:   $('[name="paid"]').val()
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
                // Swal.fire('Success', res.message, 'success')
                //     .then(() => window.location.href = '/sales');
                console.log(res);
            },
            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Sale failed', 'error');
            },
            complete: function () {
                Swal.close();
            }
        });
    });

    // ── Row Management ────────────────────────────────────────────────────────
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
        // Don't allow removing the last row
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

});