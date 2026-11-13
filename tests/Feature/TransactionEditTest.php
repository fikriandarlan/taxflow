<?php

namespace Tests\Feature;

use App\Models\Merchant;
use App\Models\TaxType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

class TransactionEditTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Pastikan pengujian tidak mengakses
     * database aplikasi utama.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $connection = DB::connection();

        $driver = $connection->getDriverName();
        $database = $connection->getDatabaseName();

        if (
            ! app()->environment('testing') ||
            $driver !== 'pgsql' ||
            $database !== 'taxflow_test'
        ) {
            throw new RuntimeException(
                'Pengujian dihentikan. ' .
                'Database wajib menggunakan taxflow_test.'
            );
        }
    }

    /**
     * Membuat data pengujian mandiri.
     *
     * Semua data akan di-rollback
     * setelah masing-masing test.
     */
    private function makeTestData(
        string $status = 'draft'
    ): array {
        $user = User::factory()->create();

        $unique = strtoupper(Str::random(8));

        $merchant = Merchant::create([
            'merchant_code' => 'MRC-TEST-' . $unique,
            'name' => 'Merchant Pengujian',
            'email' => null,
            'phone' => null,
            'address' => null,
            'is_active' => true,
        ]);

        $taxType = TaxType::create([
            'tax_code' => 'TAX-TEST-' . $unique,
            'name' => 'Pajak Pengujian',
            'rate_percent' => '10.00',
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'transaction_code' =>
                'TRX-TEST-' . $unique,

            'merchant_id' => $merchant->id,
            'tax_type_id' => $taxType->id,

            'transaction_date' => now()->startOfMinute(),

            'base_amount' => '100.00',
            'tax_rate_percent' => '10.00',
            'tax_amount' => '10.00',
            'total_amount' => '110.00',

            'status' => $status,

            'description' =>
                'Data sementara untuk pengujian',
        ]);

        return [
            'user' => $user,
            'merchant' => $merchant,
            'taxType' => $taxType,
            'transaction' => $transaction,
        ];
    }

    /**
     * Payload valid untuk request Update.
     */
    private function updatePayload(
        Transaction $transaction
    ): array {
        return [
            'transaction_code' =>
                $transaction->transaction_code,

            'merchant_id' =>
                $transaction->merchant_id,

            'tax_type_id' =>
                $transaction->tax_type_id,

            'transaction_date' =>
                now()->startOfMinute()
                    ->format('Y-m-d\TH:i'),

            'base_amount' => '200.00',

            'description' =>
                'Hasil pengujian Edit Transaksi',
        ];
    }

    /**
     * TEST 1
     *
     * Transaksi Draft dapat diperbarui.
     * Nominal dan status palsu dari browser
     * tidak boleh dipercaya backend.
     */
    public function test_draft_transaction_can_be_updated(): void
    {
        $data = $this->makeTestData();

        $transaction = $data['transaction'];
        $user = $data['user'];

        $countBefore = Transaction::count();

        $payload = $this->updatePayload(
            $transaction
        );

        // Simulasikan manipulasi request.
        $payload['tax_rate_percent'] = '99.00';
        $payload['tax_amount'] = '999999.00';
        $payload['total_amount'] = '999999.00';
        $payload['status'] = 'completed';

        $response = $this
            ->actingAs($user)
            ->put(
                route(
                    'transactions.update',
                    $transaction
                ),
                $payload
            );

        $response->assertRedirect(
            route('transactions.index')
        );

        $transaction->refresh();

        $this->assertSame(
            '200.00',
            (string) $transaction->base_amount
        );

        $this->assertSame(
            '10.00',
            (string) $transaction->tax_rate_percent
        );

        $this->assertSame(
            '20.00',
            (string) $transaction->tax_amount
        );

        $this->assertSame(
            '220.00',
            (string) $transaction->total_amount
        );

        $this->assertSame(
            'draft',
            $transaction->status
        );

        // Update tidak menambahkan transaksi baru.
        $this->assertSame(
            $countBefore,
            Transaction::count()
        );
    }

    /**
     * TEST 2
     *
     * Transaksi Completed tidak boleh diedit.
     */
    public function test_completed_transaction_cannot_be_updated(): void
    {
        $data = $this->makeTestData(
            'completed'
        );

        $transaction = $data['transaction'];

        $payload = $this->updatePayload(
            $transaction
        );

        $payload['base_amount'] = '999.00';

        $response = $this
            ->actingAs($data['user'])
            ->put(
                route(
                    'transactions.update',
                    $transaction
                ),
                $payload
            );

        $response->assertForbidden();

        $transaction->refresh();

        $this->assertSame(
            'completed',
            $transaction->status
        );

        $this->assertSame(
            '100.00',
            (string) $transaction->base_amount
        );
    }

    /**
     * TEST 3
     *
     * Transaksi Cancelled tidak boleh diedit.
     */
    public function test_cancelled_transaction_cannot_be_updated(): void
    {
        $data = $this->makeTestData(
            'cancelled'
        );

        $transaction = $data['transaction'];

        $response = $this
            ->actingAs($data['user'])
            ->put(
                route(
                    'transactions.update',
                    $transaction
                ),
                $this->updatePayload($transaction)
            );

        $response->assertForbidden();

        $transaction->refresh();

        $this->assertSame(
            'cancelled',
            $transaction->status
        );

        $this->assertSame(
            '100.00',
            (string) $transaction->base_amount
        );
    }

    /**
     * TEST 4
     *
     * Pengguna tanpa login tidak boleh
     * memperbarui transaksi.
     */
    public function test_guest_cannot_update_transaction(): void
    {
        $data = $this->makeTestData();

        $transaction = $data['transaction'];

        $response = $this->put(
            route(
                'transactions.update',
                $transaction
            ),
            $this->updatePayload($transaction)
        );

        $response->assertRedirect(
            route('login')
        );

        $transaction->refresh();

        $this->assertSame(
            '100.00',
            (string) $transaction->base_amount
        );
    }

    /**
     * TEST 5
     *
     * Nominal negatif harus ditolak
     * tanpa mengubah transaksi.
     */
    public function test_negative_amount_is_rejected(): void
    {
        $data = $this->makeTestData();

        $transaction = $data['transaction'];

        $payload = $this->updatePayload(
            $transaction
        );

        $payload['base_amount'] = '-500.00';

        $response = $this
            ->actingAs($data['user'])
            ->put(
                route(
                    'transactions.update',
                    $transaction
                ),
                $payload
            );

        $response->assertSessionHasErrors(
            'base_amount'
        );

        $transaction->refresh();

        $this->assertSame(
            '100.00',
            (string) $transaction->base_amount
        );
    }
}
