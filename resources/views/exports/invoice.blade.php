@php
    use App\Enums\Payments\PaymentStatusesEnum;use App\Enums\Times\TimeFormatsEnum;use App\Support\Converters\NumberConverter;
@endphp

    <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>فاکتور سفارش</title>

    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    @vite(['resources/css/invoice.css'])
</head>
<body class="invoice-body" dir="rtl">
<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="4" style="font-weight: bold;">
            مشخصات سفارش
        </th>
    </tr>
    </thead>
    <tr>
        <td>
            <small>
                کد سفارش:
            </small>
            <strong>
                {{ $detail->code }}
            </strong>
        </td>
        <td>
            <small>
                تاریخ ثبت سفارش:
            </small>
            <strong>
                {{ vertaTz($detail->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value) }}
            </strong>
        </td>
        <td class="bg-gray">
            <div>
                <small>
                    وضعیت پرداخت:
                </small>
                <strong>
                    @if($detail->hasCompletePaid())
                        {{ PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::SUCCESS) }}
                    @elseif($detail->hasAnyPaid())
                        {{ PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::PARTIAL_SUCCESS) }}
                    @else
                        {{ PaymentStatusesEnum::getTranslations(PaymentStatusesEnum::NOT_PAID) }}
                    @endif
                </strong>
            </div>

            <br>

            <div>
                <small>
                    وضعیت سفارش:
                </small>
                <strong>
                    {{ $detail->send_status_title }}
                </strong>
            </div>
        </td>
        <td class="bg-gray">
            <small>
                مبلغ قابل پرداخت:
            </small>
            <strong>
                {{ NumberConverter::toPersian(number_format($detail->final_price)) }}
                تومان
            </strong>
        </td>
    </tr>

    <tr>
        <td>
            <small>
                مبلغ تخفیف:
            </small>
            <strong>
                @if($detail->discount_price > 0)
                    {{ NumberConverter::toPersian(number_format($detail->discount_price)) }}
                    تومان
                @else
                    -
                @endif
            </strong>
        </td>
        <td>
            <small>
                روش ارسال:
            </small>
            <strong>
                {{ $detail->send_method_title }}
            </strong>
        </td>
        <td>
            <small>
                هزینه ارسال:
            </small>
            <strong>
                @if($detail->shipping_price > 0)
                    {{ NumberConverter::toPersian(number_format($detail->shipping_price)) }}
                    تومان
                @else
                    رایگان
                @endif
            </strong>
        </td>
        <td colspan="2">
            <small>
                مبلغ کل:
            </small>
            <strong>
                {{ NumberConverter::toPersian(number_format($detail->total_price)) }}
                تومان
            </strong>
        </td>
    </tr>

    @if(!empty($detail->coupon_code))
        <tr>
            <td colspan="2">
                <small>
                    کد استفاده شده:
                </small>
                <strong>
                    {{ $detail->coupon_code }}
                </strong>
            </td>
            <td colspan="2">
                <small>
                    مبلغ کوپن تخفیف:
                </small>
                <strong>
                    @if($detail->coupon_price > 0)
                        {{ NumberConverter::toPersian(number_format($detail->coupon_price)) }}
                        تومان
                    @else
                        -
                    @endif
                </strong>
            </td>
        </tr>
    @endif

    @if(trim($detail?->description ?? '') !== '')
        <tr>
            <td colspan="4">
                {{ $detail->description }}
            </td>
        </tr>
    @endif
</table>

<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="3" style="font-weight: bold;">
            مشخصات ثبت کننده سفارش
        </th>
    </tr>
    </thead>
    <tr>
        <td>
            <small>
                نام و نام خانوادگی:
            </small>
            <strong>
                {{ trim($detail->first_name . ' ' . $detail->last_name) }}
            </strong>
        </td>
        <td>
            <small>
                شماره موبایل:
            </small>
            <strong>
                {{ NumberConverter::toPersian($detail->mobile) }}
            </strong>
        </td>
        <td>
            <small>
                کد ملی:
            </small>
            <strong>
                {{ NumberConverter::toPersian($detail->national_code) }}
            </strong>
        </td>
    </tr>
</table>

<table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="5" style="font-weight: bold;">
            مشخصات گیرنده سفارش
        </th>
    </tr>
    </thead>
    <tr>
        <td>
            <small>
                نام گیرنده:
            </small>
            <strong>
                {{ $detail->receiver_name }}
            </strong>
        </td>
        <td>
            <small>
                شماره تماس:
            </small>
            <strong>
                {{ NumberConverter::toPersian($detail->receiver_mobile) }}
            </strong>
        </td>
        <td>
            <small>
                استان:
            </small>
            <strong>
                {{ $detail->province }}
            </strong>
        </td>
        <td>
            <small>
                شهر:
            </small>
            <strong>
                {{ $detail->city }}
            </strong>
        </td>
        <td>
            <small>
                کد پستی:
            </small>
            <strong>
                @if(trim($detail?->postal_code) !== '')
                    {{ $detail->posta_code }}
                @else
                    -
                @endif
            </strong>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <small>
                آدرس:
            </small>
            <strong>
                {{ $detail->address }}
            </strong>
        </td>
    </tr>
</table>

<table class='table table-bordered'>
    <thead>
    <tr>
        <th colspan="10" style="font-weight: bold;">
            محصولات خریداری شده
        </th>
    </tr>
    </thead>
    <thead>
    <tr>
        <th style="font-weight: normal;">
            ردیف
        </th>
        <th style="font-weight: normal;">
            نام کالا
        </th>
        <th style="font-weight: normal;">
            مشخصات
        </th>
        <th style="font-weight: normal;">
            تعداد
        </th>
        <th style="font-weight: normal;">
            مبلغ واحد
            <br>
            (به تومان)
        </th>
        <th style="font-weight: normal;">
            مبلغ کل
            <br>
            (به تومان)
        </th>
        <th style="font-weight: normal;">
            تخفیف
            <br>
            (به تومان)
        </th>
        <th style="font-weight: normal;">
            مبلغ کل پس از تخفیف
            <br>
            (به تومان)
        </th>
        <th style="font-weight: normal;">
            مرسوله مجزا
        </th>
        <th style="font-weight: normal;">
            مرجوع شده
        </th>
    </tr>
    </thead>

    <tbody>
    @php
        $itemsDiscount = 0;
    @endphp

    @foreach($detail->items as $item)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $item->product_title }}
            </td>
            <td>
                @if(!empty($item->color_name))
                    <div>
                        رنگ:
                        <span>{{ $item->color_name }}</span>
                    </div>
                @endif

                @if(!empty($item->size))
                    <div>
                        {{ $item->size }}
                    </div>
                @endif

                @if(!empty($item->size))
                    <div>
                        {{ $item->guarantee }}
                    </div>
                @endif
            </td>
            <td>
                {{ $item->qunaitity }}
                {{ $item->unit_name }}
            </td>
            <td>
                {{ NumberConverter::toPersian(number_format($detail->unit_price)) }}
                تومان
            </td>
            <td>
                {{ NumberConverter::toPersian(number_format($detail->price)) }}
                تومان
            </td>
            <td>
                @if($item->price - $item->discounted_price > 0)
                    @php
                        $itemsDiscount += ($item->price - $item->discounted_price);
                    @endphp

                    {{ NumberConverter::toPersian(number_format($item->price - $item->discounted_price)) }}
                    تومان
                @else
                    -
                @endif
            </td>
            <td>
                {{ NumberConverter::toPersian(number_format($detail->discounted_price)) }}
                تومان
            </td>
            <td class="bg-gray">
                @if($item->has_separate_shipment)
                    بله
                @else
                    خیر
                @endif
            </td>
            <td>
                @if($item->is_returned)
                    بله
                @else
                    خیر
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
    <tr>
        <td colspan="6" class="text-left">
            <strong class="text-right">
                هزینه ارسال:
            </strong>
        </td>
        <td>
            -
        </td>
        <td colspan="2">
            @if($detail->shipping_price > 0)
                {{ NumberConverter::toPersian(number_format($detail->shipping_price)) }}
                تومان
            @else
                رایگان
            @endif
        </td>
    </tr>
    @if(!empty($detail->coupon_code))
        <tr>
            <td colspan="6" class="text-left">
                <strong class="text-right">
                    تخفیف ویژه (کوپن):
                </strong>
            </td>
            <td>
                @if($detail->coupon_price > 0)
                    @php
                        $itemsDiscount += $detail->coupon_price;
                    @endphp

                    {{ NumberConverter::toPersian(number_format($detail->coupon_price)) }}
                    تومان
                @else
                    -
                @endif
            </td>
            <td colspan="2">
                -
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="6" class="text-left">
            <strong class="text-right">
                مجموع مبالغ:
            </strong>
        </td>
        <td>
            @if($itemsDiscount > 0)
                {{ NumberConverter::toPersian(number_format($itemsDiscount)) }}
                تومان
            @else
                -
            @endif
        </td>
        <td colspan="2">
            <strong>
                {{ NumberConverter::toPersian(number_format($detail->final_price)) }}
                تومان
            </strong>
        </td>
    </tr>
    </tfoot>
</table>
</body>
</html>
