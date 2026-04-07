<?php declare(strict_types=1);

namespace App\Services\Accounting;

use App\Traits\MoneyTrait;
use Illuminate\Support\Facades\DB;

class DoubleEntryService {
    use MoneyTrait;

    public function recordTransaction(string $amount, string $debitAccount, string $creditAccount, string $narration): void {
        DB::transaction(function () use ($amount, $debitAccount, $creditAccount, $narration) {
            $entryId = DB::table('journal_entries')->insertGetId([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'entry_date' => now(),
                'total_amount' => $amount,
                'narration' => $narration,
                'created_at' => now()
            ]);

            // Debit Line
            DB::table('journal_entry_lines')->insert([
                'journal_entry_id' => $entryId,
                'account_code' => $debitAccount,
                'debit' => $amount,
                'credit' => '0.00',
            ]);

            // Credit Line
            DB::table('journal_entry_lines')->insert([
                'journal_entry_id' => $entryId,
                'account_code' => $creditAccount,
                'debit' => '0.00',
                'credit' => $amount,
            ]);
        });
    }
}