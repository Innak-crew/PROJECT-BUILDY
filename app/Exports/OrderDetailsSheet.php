<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderDetailsSheet implements FromView, WithTitle,  ShouldAutoSize, WithStyles
{
    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function title(): string
    {
        return $this->order->invoice->invoice_number;
    }

    public function view(): View
    {
        $totalAmount = $this->order->invoice->total_amount;
        $formattedAmount = str_replace(",", "", number_format($totalAmount));
        $amountInWords = inrConvertNumberToWords((int)$formattedAmount);

        $labourData = [];

        $labours = $this->order->labours->map(function ($item) {
            return [
                'date' => $item->date,
                'labor_category_name' => $item->category->name,
                'number_of_labors' => $item->number_of_labors,
                'per_labor_amount' => $item->per_labor_amount,
                'total_amount' => $item->per_labor_amount * $item->number_of_labors,
            ];
        })->toArray();

        foreach ($labours as $labour) {
            $date = Carbon::parse($labour['date'])->format('Y-m-d'); 
            if (!isset($labourData[$date])) {
                $labourData[$date] = [];
            }
            
            $labourData[$date][] = [
                'labor_category_name' => $labour['labor_category_name'],
                'number_of_labors' => $labour['number_of_labors'],
                'per_labor_amount' => $labour['per_labor_amount'],
                'total_amount' => $labour['total_amount'],
                'date' => Carbon::parse($labour['date'])->format('jS F Y')
            ];
        }
        
        

        return view('single_invoice', [
            'created_date' => $this->order->created_at->format('Y-m-d'),
            'invoice_number' => $this->order->invoice->invoice_number,
            'due_date' => $this->order->invoice->due_date,
            'createdby_name' => $this->order->creator->name,
            'customer_name' => $this->order->customer->name,
            'customer_phone' => $this->order->customer->phone,
            'customer_email' => $this->order->customer->email,
            'customer_address' => $this->order->customer->address,
            'orderItems' => $this->order->orderItems->map(function ($item) {
                return [
                    'category_name' =>$item->catagories->name,
                    'design_name' => $item->design->name,
                    'quantity' => $item->quantity,
                    'unit' => $item->design->unit->name,
                    'rate_per' => format_inr(number_format($item->rate_per)),
                    'discount_amount' => format_inr(number_format($item->discount_amount,2),1),
                    'discount_percentage' => $item->discount_percentage,
                    'total' => format_inr(number_format($item->total,2),1),
                ];
            })->toArray(),
            'paymentHistory' => $this->order->paymentHistory()->orderBy('payment_date')->get()->map(function ($item) {
                return [
                    'amount' => $item->amount,
                    'method' => $item->payment_method,
                    'date' => Carbon::parse($item->payment_date)->format('jS F Y'),
                ];
            })->toArray(),
            'labourDetails' => $labourData,
            'discount_amount' => format_inr(number_format(optional($this->order->invoice)->discount_amount)),
            'discount_percentage' => optional($this->order->invoice)->discount_percentage,
            'total_amount' => format_inr(number_format(optional($this->order->invoice)->total_amount)),
            'terms_and_conditions' =>  nl2br(e($this->order->invoice->terms_and_conditions)),
            'amountInWords' => $amountInWords,
        ]);
    }


    public function styles(Worksheet $sheet)
    {
        return [
            "A:E"=>[
                'font'=>[
                    'name'      =>  'Calibri',
                    'size'      =>  12,
                ],
            ],
            1 => [
                'font' => [
                    'name'      =>  'Calibri',
                    'size'      =>  15,
                    'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => [
                    'name'      =>  'Calibri',
                    'size'      =>  15,
                    'bold' => true],
            ],
            7 => [
                'font' => [
                    'name'      =>  'Calibri',
                    'size'      =>  15,
                    'bold' => true],
            ],
            12 => [
                'font' => [
                    'name'      =>  'Calibri',
                    'size'      =>  15,
                    'bold' => true],
            ],
        ];
    }
}
