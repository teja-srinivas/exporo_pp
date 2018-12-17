<?php

namespace App\Exports;

use App\Models\Bill;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BillsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Bill::query()
            ->with('user.details')
            ->join('commissions', 'bills.id', 'commissions.bill_id')
            ->select('bills.*')
            ->selectRaw('sum(commissions.net) as totalNet')
            ->selectRaw('sum(commissions.gross) as totalGross')
            ->groupBy('bills.id')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'PartnerID',
            'PartnerName',
            'IBAN',
            'BIC',
            'RechnungsDatum',
            'Gegenkonto',
            'Konto',
            'Buchungstext',
            'Belegfeld',
            'Umsatz',
            'S/H',
            'BU',
        ];
    }

    /**
     * @param Bill $bill
     * @return array
     */
    public function map($bill): array
    {
        $totalGross = (float) $bill->totalGross;
        $totalNet = (float) $bill->totalNet;

        return [
            $bill->user_id,
            $bill->user->getDisplayName(),
            self::sanitize(str_replace('IBAN:', '', $bill->user->details->iban)),
            self::sanitize($bill->user->details->bic),
            $bill->released_at->format('d.m.Y'),
            $bill->user_id + 700000,
            31020,
            'Provisionsgutschrift',
            $bill->id,
            number_format($totalGross, 2, ',', ''),
            'S',
            $totalGross > $totalNet ? 99 : '',
        ];
    }

    private function sanitize(?string $content): string
    {
        if ($content === null || $content === '0') {
            return '';
        }

        return strtoupper(str_replace(' ', '', $content));
    }
}
