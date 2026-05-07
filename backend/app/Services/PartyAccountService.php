<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\Party;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 *  PartyAccountService — shared create/update/delete flow for
 *  Customer, Supplier, and Partner records.
 * ============================================================
 *
 *  Why this exists:
 *  ----------------
 *  Customer / Supplier / Partner each have a `Party` row plus a small
 *  extension row (Customer / Supplier / Partner). Without this service
 *  every controller would repeat the same Party-create-then-extension
 *  + audit-log + transaction pattern. SKILL.md §4: do not copy-paste.
 *
 *  Beginner usage:
 *      $customer = app(PartyAccountService::class)
 *          ->createForCustomer($partyData, creditLimitPaisas: 50000_00);
 * ============================================================
 */
class PartyAccountService
{
    public function __construct(private AuditLogService $auditLog) {}

    public function createForCustomer(array $partyData, int $creditLimitPaisas = 0): Customer
    {
        return DB::transaction(function () use ($partyData, $creditLimitPaisas) {
            $party    = $this->createParty($partyData, 'customer');
            $customer = Customer::create([
                'party_id'            => $party->id,
                'credit_limit_paisas' => $creditLimitPaisas,
            ]);
            $this->auditLog->log('create', Customer::class, $customer->id, [], $customer->toArray());
            return $customer->load('party');
        });
    }

    public function createForSupplier(array $partyData): Supplier
    {
        return DB::transaction(function () use ($partyData) {
            $party    = $this->createParty($partyData, 'supplier');
            $supplier = Supplier::create(['party_id' => $party->id]);
            $this->auditLog->log('create', Supplier::class, $supplier->id, [], $supplier->toArray());
            return $supplier->load('party');
        });
    }

    public function createForPartner(array $partyData, float $sharePercentage = 0): Partner
    {
        return DB::transaction(function () use ($partyData, $sharePercentage) {
            $party   = $this->createParty($partyData, 'partner');
            $partner = Partner::create([
                'party_id'         => $party->id,
                'share_percentage' => $sharePercentage,
            ]);
            $this->auditLog->log('create', Partner::class, $partner->id, [], $partner->toArray());
            return $partner->load('party');
        });
    }

    /**
     * Update the Party row underneath any extension model (Customer / Supplier / Partner).
     * Pass only Party-level fields (name, phone, address, city, opening_balance_paisas).
     */
    public function updateParty(Customer|Supplier|Partner $extension, array $partyData): void
    {
        DB::transaction(function () use ($extension, $partyData) {
            $before = $extension->party->toArray();
            $extension->party->update(array_intersect_key($partyData, array_flip([
                'name', 'phone', 'address', 'city', 'opening_balance_paisas',
            ])));
            $this->auditLog->log(
                'update',
                Party::class,
                $extension->party->id,
                $before,
                $extension->party->fresh()->toArray()
            );
        });
    }

    /**
     * Delete an extension model AND its underlying Party in one transaction.
     */
    public function deleteWithParty(Customer|Supplier|Partner $extension): void
    {
        DB::transaction(function () use ($extension) {
            $modelClass = $extension::class;
            $extId      = $extension->id;
            $partyId    = $extension->party_id;

            $extension->delete();
            $extension->party?->delete();

            $this->auditLog->log('delete', $modelClass, $extId, [], []);
            $this->auditLog->log('delete', Party::class, $partyId, [], []);
        });
    }

    /**
     * Internal helper — picks Party-level fields and sets party_type.
     */
    private function createParty(array $data, string $partyType): Party
    {
        return Party::create([
            'name'                   => $data['name'] ?? '',
            'phone'                  => $data['phone'] ?? null,
            'address'                => $data['address'] ?? null,
            'city'                   => $data['city'] ?? null,
            'opening_balance_paisas' => (int) ($data['opening_balance_paisas'] ?? 0),
            'party_type'             => $partyType,
        ]);
    }
}
