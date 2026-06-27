<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function record(
        Customer $customer,
        string $type,
        string $direction,
        float $amount,
        ?Model $transactionable = null,
        ?string $paymentMethod = null,
        ?string $referenceNo = null,
        ?string $note = null
    ): Transaction {
        return DB::transaction(function () use (
            $customer, $type, $direction, $amount, $transactionable, $paymentMethod, $referenceNo, $note
        ) {
            $customer = Customer::lockForUpdate()->findOrFail($customer->id);

            $delta = $direction === 'credit' ? -$amount : $amount;
            $customer->current_balance = round((float) $customer->current_balance + $delta, 2);
            $customer->save();

            return Transaction::create([
                'customer_id'           => $customer->id,
                'transactionable_id'    => $transactionable?->id,
                'transactionable_type'  => $transactionable ? get_class($transactionable) : null,
                'type'                  => $type,
                'direction'             => $direction,
                'amount'                => round($amount, 2),
                'payment_method'        => $paymentMethod,
                'reference_no'          => $referenceNo,
                'note'                  => $note,
                'created_by'            => Auth::id(),
            ]);
        });
    }
}
