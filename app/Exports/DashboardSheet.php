<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Collection;

class DashboardSheet implements FromArray, WithTitle,  ShouldAutoSize, WithStyles
{
    protected $orders;

    public function __construct(Collection $orders)
    {
        $this->orders = $orders;
    }

    public function array(): array
    {
        $totalOrders = (string) $this->orders->count();
        $completedOrders = (string) $this->orders->where('status', 'completed')->count();
        $canceledOrders = (string) $this->orders->where('status', 'canceled')->count();
        $ongoingOrders = (string) $this->orders->where('status', 'ongoing')->count();
        $pendingOrders = (string) $this->orders->where('status', 'pending')->count();

        $totalAmount = (float) $this->orders->sum(function($order) {
            return $order->invoice->sum('total_amount');
        });
        $totalDiscountAmount = (float) $this->orders->sum(function($order) {
            return $order->invoice->sum('discount_amount');
        });
        $balanceAmount = (float) $this->orders->sum(function($order) {
            return $order->invoice->sum('balance_amount');
        });

        $reportExportDate = now()->toDateString(); // Example report export date
        $note = <<<EOT
This report provides a summary of the orders data.
It includes a dashboard overview, detailed orders list,
and individual order details.
EOT;
  
        return [
            ['Metric', 'Value'],
            ['Total Orders', $totalOrders],
            ["",""], 
            ['Completed Orders', $completedOrders],
            ['Canceled Orders', $canceledOrders],
            ['Ongoing Orders', $ongoingOrders],
            ['Pending Orders', $pendingOrders],
            ["",""], 
            ['Total Amount', "₹ ".format_inr(number_format($totalAmount))],
            ['Total Discount Amount', "₹ ".format_inr(number_format($totalDiscountAmount))],
            ['Balance Amount', "₹ ".format_inr(number_format($balanceAmount))],
            ["",""],  
            ["",""],  
            ['Report Export Date', $reportExportDate],
            ['Note', $note],
        ];
    }

    public function title(): string
    {
        return 'Dashboard Overview';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'name'      =>  'Calibri',
                    'size'      =>  15,
                    'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EFEFEF'], 
                ],
            ],

            'B2:B15' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],

            'A9:B11' => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ]
            ],
        ];
    }
}
