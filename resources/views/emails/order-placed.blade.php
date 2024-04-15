@php
    use App\Enums\Times\TimeFormatsEnum;
@endphp

<x-emails.layout title="ثبت سفارش" siteTitle="{{ $siteTitle }}">
    <style>
        /* Table */
        .table {
            margin-top: 2.5rem;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* Table Header */
        .table th {
            font-size: .9rem;
            text-align: right;
            background-color: #f2f2f2;
            padding: .5rem;
            border: 1px solid #e0e0e0;
            border-bottom: 2px solid #e0e0e0;
        }

        /* Table Body */
        .table td {
            padding: .5rem;
            border: 1px solid #e0e0e0;
        }

        .w-60px {
            width: 60px;
        }
    </style>

    <h2 class="font-iranyekan-bold">توجه</h2>
    <h4 class="font-iranyekan-bold">(این ایمیل تنها جهت تست می‌باشد.)</h4>

    <p>
        سفارش به کد
        <span class="font-iranyekan-bold">{{ $orderDetail->code }}</span>
        ثبت شده است، لطفا پیگیری نمایید.
    </p>

    <p>
        ثبت شده برای
        <span class="font-iranyekan-bold">{{ $orderDetail->first_name }}</span>
        در تاریخ
        <span
            class="font-iranyekan-bold">{{ vertaTz($orderDetail->ordered_at)->format(TimeFormatsEnum::DEFAULT_WITH_TIME->value) }}</span>
    </p>

    <table class="table">
        <caption class="font-iranyekan-bold">
            محصولات سفارش داده شده
        </caption>
        <thead>
        <tr>
            <th class="font-iranyekan-bold">عنوان محصول</th>
            <th class="font-iranyekan-bold">تعداد</th>
        </tr>
        </thead>
        <tbody>
        @forelse($orderDetail?->items as $item)
            <tr>
                <td>
                    {{ $item->product_title }}
                </td>
                <td class="font-iranyekan-bold text-center w-60px">{{ $item->quantity }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">
                    هیچ محصولی وجود ندارد!
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</x-emails.layout>
