@php
    use App\Enums\Times\TimeFormatsEnum;
    use App\Support\Converters\NumberConverter;
@endphp

<x-emails.layout
    title="ثبت سفارش"
    siteTitle="{{ $siteTitle }}"
>
    <style>
        /* Table */
        .table {
            margin-top: 2.5rem;
        }

        .test-warning {
            padding: .75rem 1rem;
            border-radius: .5rem;
            background-color: #fff3cd;
        }

        .test-warning > * {
            margin: 0;
        }

        .w-60px {
            width: 80px;
        }
    </style>

    @notproduction
    <div class="test-warning">
        <h3 class="font-iranyekan-bold">این ایمیل تنها جهت تست می‌باشد.</h3>
    </div>
    @endnotproduction

    <p>
        سفارش به کد
        <span class="font-arial-sans" dir="ltr">{{ $orderDetail['code'] }}</span>
        ثبت شده است، لطفا پیگیری نمایید.
    </p>

    <p>
        ثبت شده برای
        <span class="font-iranyekan-bold">{{ $orderDetail['first_name'] }}</span>
        در تاریخ
        <span
            class="font-iranyekan-bold">{{ NumberConverter::toPersian(vertaTz($orderDetail['ordered_at'])->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)) }}</span>
    </p>

    <table class="table">
        <caption class="font-iranyekan-bold">
            محصولات سفارش داده شده
        </caption>
        <thead>
        <tr>
            <th class="font-iranyekan-bold">عنوان محصول</th>
            <th class="font-iranyekan-bold text-center">تعداد</th>
        </tr>
        </thead>
        <tbody>
        @if (is_array($orderDetail['items']) && !empty($orderDetail['items']))
            @foreach($orderDetail['items'] as $item)
                <tr>
                    <td>
                        {{ $item['product_title'] }}
                    </td>
                    <td class="font-iranyekan-bold text-center w-60px">
                        {{ NumberConverter::toPersian($item['quantity']) }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="2">
                    هیچ محصولی وجود ندارد!
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</x-emails.layout>
