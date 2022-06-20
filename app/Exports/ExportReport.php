<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
//models
use App\Models\Transaction;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportReport implements FromCollection, WithHeadings,ShouldAutoSize,WithStyles,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $tgl_awal,$paket,$tgl_akhir;
    public function __construct($filter)
    {
        $this->tgl_awal = $filter['tgl_awal'];
        $this->tgl_akhir = $filter['tgl_akhir'];
        $this->paket = $filter['paket'];
    }

    public function collection()
    {
        $data = Transaction::query()
            ->leftJoin('mst_package as mp','mp.id','transactions.id_package')
            ->whereBetween('date',[$this->tgl_awal,$this->tgl_akhir])
            ->select('date','mp.name as package')
            ->selectRaw('FORMAT(transactions.amount,"C") as amount')
            ->get();
        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        // TODO: Implement columnFormats() method.
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function headings(): array{
        return [
            'Tanggal',
            'Paket',
            'Harga',
        ];
    }
}
