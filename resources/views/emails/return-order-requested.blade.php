@php
    use App\Enums\Times\TimeFormatsEnum;
    use App\Support\Converters\NumberConverter;
@endphp

<x-emails.layout
    bgColor="#1DB48D"
    title="ثبت مرجوع سفارش"
    siteTitle="{{ $siteTitle }}"
>
    <style>
        /* Table */
        .table {
            margin-top: 2.5rem;
        }

        .test-warning,
        .alert-info {
            padding: .75rem 1rem;
            border-radius: .5rem;
        }

        .test-warning > *,
        .alert-info > * {
            margin: 0;
        }

        .test-warning {
            background-color: #fff3cd;
        }

        .alert-info {
            background-color: #dacdff;
        }

        .w-80px {
            width: 80px;
        }
    </style>

    @notproduction
    <div class="test-warning">
        <h3 class="font-iranyekan-bold">این ایمیل تنها جهت تست می‌باشد.</h3>
    </div>
    @endnotproduction

    <p>
        درخواست مرجوع برای سفارش به کد
        <span class="font-arial-sans" dir="ltr">{{ $request['order']['code'] }}</span>
        با کد مرجوع
        <span class="font-arial-sans" dir="ltr">{{ $request['code'] }}</span>
        ثبت شده است.
    </p>

    <p class="alert-info">
        به دلیل نهایی شدن تصمیم کاربر، لطفا پس از ۱ ساعت بررسی نهایی نمایید.
    </p>

    <p>
        ثبت شده برای
        <span class="font-iranyekan-bold">{{ $request['user']['first_name'] ?? 'کاربر' }}</span>
        در تاریخ
        <span
            class="font-iranyekan-bold">{{ NumberConverter::toPersian(vertaTz($request['requested_at'])->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value)) }}</span>
    </p>

    <table class="table">
        <caption class="font-iranyekan-bold">
            محصولات سفارش داده شده
        </caption>
        <thead>
        <tr>
            <th class="font-iranyekan-bold">عنوان محصول</th>
            <th class="font-iranyekan-bold text-center">تعداد بازگشتی</th>
            <th class="font-iranyekan-bold text-center">تعداد خریداری شده</th>
        </tr>
        </thead>
        <tbody>
        @if (is_array($request['return_order_items']) && !empty($request['return_order_items']))
            @foreach($request['return_order_items'] as $item)
                <tr>
                    <td>
                        {{ $item['order_item']['product_title'] }}
                    </td>
                    <td class="font-iranyekan-bold text-center w-80px">
                        {{ NumberConverter::toPersian($item['quantity']) }}
                    </td>
                    <td class="font-iranyekan-bold text-center w-80px">
                        {{ NumberConverter::toPersian($item['order_item']['quantity']) }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3">
                    هیچ محصولی وجود ندارد!
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</x-emails.layout>
