<?php

namespace App\Exports\Excels;

use App\Enums\Payments\GatewaysEnum;
use App\Enums\Payments\PaymentStatusesEnum;
use App\Enums\Payments\PaymentTypesEnum;
use App\Enums\Times\TimeFormatsEnum;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Export\ExcelExport;
use App\Support\Filter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderExport extends ExcelExport implements WithEvents
{
    protected array $dateColumnsNames = ['N', 'S'];

    protected array $numberColumnsNames = ['G', 'H', 'I', 'J', 'K'];

    /**
     * @var Collection
     */
    protected Collection $orders;

    public function __construct(array $query, Filter $filter)
    {
        parent::__construct($query, $filter);

        /**
         * @var ReportServiceInterface $service
         */
        $service = app()->get(ReportServiceInterface::class);
        $this->orders = $service->getOrdersForReport($this->filter, $this->query);
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->orders;
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return 'orders';
    }

    /**
     * @inheritDoc
     */
    public function map($row): array
    {
        // Build client information
        $clientStr = '(' . "\n";
        $clientStr .= 'نام و نام خانوادگی: ' . trim($row->first_name . ' ' . $row->last_name) . "\n";
        $clientStr .= 'کد ملی: ' . $row->national_code . "\n";
        $clientStr .= 'موبایل: ' . $row->mobile . "\n";
        $clientStr .= ')' . "\n";
        $clientStr .= "\r\n";

        // Build receiver information
        $receiverStr = '(' . "\n";
        $receiverStr .= 'نام گیرنده: ' . $row->receiver_name . "\n";
        $receiverStr .= 'شماره تماس: ' . $row->receiver_mobile . "\n";
        $receiverStr .= 'استان: ' . $row->province . "\n";
        $receiverStr .= 'شهر: ' . $row->city . "\n";
        $receiverStr .= 'کدپستی: ' . $row->postal_code . "\n";
        $receiverStr .= 'آدرس: ' . $row->address . "\n";
        $receiverStr .= ')' . "\n";
        $receiverStr .= "\r\n";

        // Build order items
        $itemsStr = '';
        foreach ($row->items as $item) {
            $itemsStr .= '(' . "\n";
            $itemsStr .= 'کد: ' . $item->product_code . "\n";
            $itemsStr .= 'عنوان: ' . $item->product_title . "\n";
            $itemsStr .= ('رنگ: ' . $item->color_name ?? '-') . "\n";
            $itemsStr .= ('سایز: ' . $item->size ?? '-') . "\n";
            $itemsStr .= ('گارانتی: ' . $item->guarantee ?? '-') . "\n";
            $itemsStr .= 'وزن با بسته‌بندی (به گرم): ' . $this->formatNumber($item->weight, 0) . "\n";
            $itemsStr .= 'قیمت (به تومان): ' . $this->formatNumber($item->price) . "\n";
            $itemsStr .= 'قیمت با تخفیف (به تومان): ' . $this->formatNumber($item->discounted_price, '-') . "\n";
            $itemsStr .= 'فی (به تومان): ' . $this->formatNumber($item->unit_price) . "\n";
            $itemsStr .= 'تعداد: ' . $this->formatNumber($item->quantity, 0) . "\n";
            $itemsStr .= 'واحد محصول: ' . $item->unit_name . "\n";
            $itemsStr .= 'مرسوله مجزا: ' . (bool)$item->is_separate_shipment . "\n";
            $itemsStr .= 'مرجوع شده: ' . (bool)$item->is_returned . "\n";
            $itemsStr .= ')' . "\n";
            $itemsStr .= "\r\n";
        }

        // Build order payments
        $paymentsStr = '';
        foreach ($row->orders as $payment) {
            $paymentsStr .= '(' . "\n";
            $paymentsStr .= 'مبلغ قابل پرداخت (به تومان): ' . $this->formatNumber($payment->must_pay_price, 0) . "\n";
            $paymentsStr .= 'عنوان روش پرداخت: ' . $payment->payment_method_title . "\n";
            $paymentsStr .= 'پرداخت از طریق: ' . PaymentTypesEnum::getTranslations($payment->payment_method_type, 'نامشخص') . "\n";

            if ($payment->payment_method_type === PaymentTypesEnum::BANK_GATEWAY->value) {
                $paymentsStr .= 'از طریق درگاه: ' . GatewaysEnum::getTranslations($payment->paymenr_method_gateway_type, 'نامشخص') . "\n";
            }

            $paymentsStr .= 'وضعیت پرداخت: ' . PaymentStatusesEnum::getTranslations($payment->payment_status, 'نامشخص') . "\n";
            $paymentsStr .= 'پرداخت شده در تاریخ: ' . ($payment->paid_at ? vertaTz($row->paid_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value) : '-') . "\n";
            $paymentsStr .= 'تاریخ ایجاد پرداخت: ' . ($payment->created_at ? vertaTz($row->created_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value) : '-') . "\n";
            $paymentsStr .= ')' . "\n";
            $paymentsStr .= "\r\n";
        }

        // Check payment status
        $paymentStatus = $row->hasCompletePaid()
            ? PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::SUCCESS, 'نامشخص')
            : (
            $row->hasAnyPaid()
                ? PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::PARTIAL_SUCCESS, 'نامشخص')
                : PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAID, 'نامشخص')
            );

        return [
            $row->id,
            $row->code,
            $clientStr,
            $receiverStr,
            $row->description,
            $row->coupon_code ?? '-',
            $row->coupon_price ?: 0,
            $row->shipping_price ?: 0,
            $row->disocunt_price ?: 0,
            $row->final_price ?: 0,
            $row->total_price ?: 0,
            $row->send_method_title,
            $row->send_status_title,
            $row->send_status_changed_at
                ? Date::dateTimeToExcel($row->send_status_changed_at)
                : null,
            $row->send_status_changed_at
                ? vertaTz($row->send_status_changed_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            (bool)$row->is_needed_factor,
            $paymentStatus,
            (bool)$row->is_product_returned_to_stock,
            $row->ordered_at
                ? Date::dateTimeToExcel($row->ordered_at)
                : null,
            $row->ordered_at
                ? vertaTz($row->ordered_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            $itemsStr,
            $paymentsStr
        ];
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            [
                '#',
                'کد سفارش',
                'مشخصات سفارش دهنده',
                'مشخصات گیرنده',
                'توضیحات',
                'کد کوپن',
                'مبلغ کوپن (به تومان)',
                'هزینه ارسال (به تومان)',
                'مبلغ تخفیف (به تومان)',
                'مبلغ نهایی (به تومان)',
                'مبلغ کل (به تومان)',
                'روش ارسال',
                'وضعیت ارسال',
                'تغییر وضعیت ارسال در تاریخ',
                'تغییر وضعیت ارسال در تاریخ (تهران)',
                'نیاز به ارسال فاکتور',
                'وضعیت پرداخت',
                'سفارش مرجوع شده',
                'تاریخ سفارش',
                'تاریخ سفارش (تهران)',
                'آیتم‌های سفارش',
                'پرداخت‌ها',
            ],
            [
                '#',
                'Code',
                'Client Information',
                'Receiver Information',
                'Description',
                'Coupon Code',
                'Coupon Price',
                'Shipping Price',
                'Discount Price',
                'Final Price',
                'Total Price',
                'Sending Method',
                'Sending Status',
                'Changed Status At',
                'Changed Status At (Asia/Tehran)',
                'Need Factor',
                'Invoice Status',
                'Is Returned',
                'Ordered At',
                'Ordered At (Asia/Tehran)',
                'Order Items',
                'Invoices',
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();

                // This will add custom fill color to send status cell
                // -($startPointRow = 3) is starting point after headers(should change according to number of header rows)
                $startPointRow = 3;
                for ($row = $startPointRow; $row <= $lastRow; $row++) {
                    $sendStatusColor = $this->orders[$row - $startPointRow]['send_status_color_hex'];
                    $sendStatusColor = str_replace('#', '', $sendStatusColor);

                    // Adjust the cell reference to target the specific cell you want to change
                    $cell = 'M' . $row;

                    $this->changeCellBackgroundColor($sheet, $cell, $sendStatusColor);
                }

                // Calculate totals
                $total = 0;
                $totalCount = 0;
                $totalPaid = 0;
                $totalPaidCount = 0;
                $totalShipping = 0;
                $totalWithoutShipping = 0;
                foreach ($this->orders as $order) {
                    if ($order->hasCompletePaid()) {
                        $totalPaid += $order->final_price;
                        $totalShipping += $order->shipping_price;
                        $totalWithoutShipping += $order->final_price - $order->shipping_price;
                        $totalPaidCount += 1;
                    }

                    $total += $order->final_price;
                    $totalCount += 1;
                }

                // Add total row
                $totalRow = $lastRow + 1;

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'B' => 'مجموع پرداخت‌های موفق (' . $totalPaidCount . ')',
                    'C' => $totalPaid . ' تومان',
                ]);

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'D' => 'مجموع هزینه‌های ارسال',
                    'E' => $totalShipping . ' تومان',
                ]);

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'F' => 'مجموع پرداخت‌های موفق بدون هزینه ارسال',
                    'G' => $totalWithoutShipping . ' تومان',
                ]);

                $this->addContentToSpecificRow($sheet, $totalRow, [
                    'H' => 'مجموع تمامی پرداخت‌ها (' . $totalCount . ')',
                    'I' => $total . ' تومان',
                ]);
            },
        ];
    }
}
