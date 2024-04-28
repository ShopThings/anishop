<?php

namespace App\Exports\Excels;

use App\Enums\Times\TimeFormatsEnum;
use App\Services\Contracts\ReportServiceInterface;
use App\Support\Export\ExcelExport;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProductExport extends ExcelExport
{
    protected array $dateColumnsNames = ['M', 'O'];

    /**
     * @return Collection
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function collection()
    {
        /**
         * @var ReportServiceInterface $service
         */
        $service = app()->get(ReportServiceInterface::class);
        return $service->getProductsForReport($this->filter, $this->query);
    }

    /**
     * @inheritDoc
     */
    public function title(): string
    {
        return 'products';
    }

    /**
     * @inheritDoc
     */
    public function map($row): array
    {
        $productsStr = '';
        $products = $row->items;
        foreach ($products as $product) {
            $productsStr .= '(' . "\n";
            $productsStr .= 'کد: ' . $product->code . "\n";
            $productsStr .= 'رنگ: ' . $product->color_name ?? '-' . "\n";
            $productsStr .= 'سایز: ' . $product->size ?? '-' . "\n";
            $productsStr .= 'گارانتی: ' . $product->guarantee . "\n";
            $productsStr .= 'وزن با بسته‌بندی (به گرم): ' . $this->formatNumber($product->weight, 0) . "\n";
            $productsStr .= 'قیمت (به تومان): ' . $this->formatNumber($product->price) . "\n";
            $productsStr .= 'قیمت با تخفیف (به تومان): ' . $this->formatNumber($product->discounted_price, '-') . "\n";
            $productsStr .= 'تخفیف از تاریخ: ' . ($product->discounted_from ? vertaTz($row->discounted_until)->format(TimeFormatsEnum::NORMAL_DATETIME->value) : '-') . "\n";
            $productsStr .= 'تخفیف تا تاریخ: ' . ($product->discounted_until ? vertaTz($row->discounted_until)->format(TimeFormatsEnum::NORMAL_DATETIME->value) : '-') . "\n";
            $productsStr .= 'درصد اعمال مالیات: ' . $product->tax_rate . "\n";
            $productsStr .= 'تعداد موجود در انبار: ' . $this->formatNumber($product->stock_count) . "\n";
            $productsStr .= 'محصول ویژه: ' . (bool)$product->is_special . "\n";
            $productsStr .= 'وضعیت موجودی: ' . (bool)$product->is_available . "\n";
            $productsStr .= 'نمایش برچسب به زودی: ' . (bool)$product->show_coming_soon . "\n";
            $productsStr .= 'نمایش برچسب تماس برای اطلاعات بیشتر: ' . (bool)$product->show_call_for_more . "\n";
            $productsStr .= 'وضعیت انتشار: ' . (bool)$product->is_published . "\n";
            $productsStr .= 'مرسوله مجزا: ' . (bool)$product->has_separate_shipment . "\n";
            $productsStr .= ')' . "\n";
            $productsStr .= "\r\n";
        }

        return [
            $row->id,
            $row->title,
            $row->brand->name,
            $row->category->name,
            $this->prepareSubPropertiesForShow($row->quick_properties),
            $this->preparePropertiesForShow($row->properties),
            $row->unit_name,
            implode(', ', $row->keywords),
            (bool)$row->is_available,
            (bool)$row->is_commenting_allwed,
            (bool)$row->is_published,
            $productsStr,
            $row->created_at
                ? Date::dateTimeToExcel($row->created_at)
                : null,
            $row->created_at
                ? vertaTz($row->created_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
            $row->updated_at
                ? Date::dateTimeToExcel($row->updated_at)
                : null,
            $row->updated_at
                ? vertaTz($row->updated_at)->format(TimeFormatsEnum::NORMAL_DATETIME->value)
                : null,
        ];
    }

    /**
     * @param array $properties
     * @return string
     */
    private function prepareSubPropertiesForShow(array $properties): string
    {
        if (empty($properties)) return '';

        $builder = '';
        foreach ($properties as $property) {
            $builder .= $property['title'] . ':' . "\n";
            $builder .= implode(', ', $property['tags']) . "\n";
        }
        return $builder;
    }

    /**
     * @param array $properties
     * @return string
     */
    private function preparePropertiesForShow(array $properties): string
    {
        if (empty($properties)) return '';

        $builder = '';
        foreach ($properties as $property) {
            $builder .= $property['title'] . ':' . "\n";
            $builder .= $this->prepareSubPropertiesForShow($property['children']) . "\n";
        }
        return $builder;
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            [
                '#',
                'عنوان',
                'برند',
                'دسته‌بندی',
                'ویژگی‌های سریع',
                'ویژگی‌ها',
                'واحد محصول',
                'کلمات کلیدی',
                'وضعیت موجودی',
                'اجازه ارسال کامنت',
                'وضعیت انتشار',
                'انواع محصولات',
                'تاریخ ایجاد',
                'تاریخ ایجاد (تهران)',
                'تاریخ بروزرسانی',
                'تاریخ بروزرسانی (تهران)',
            ],
            [
                '#',
                'Title',
                'Brand',
                'Category',
                'Quick Properties',
                'Properties',
                'Unit Name',
                'Keywords',
                'Availability',
                'Allowed Commenting',
                'Publish Status',
                'Product Variants',
                'Created At',
                'Created At (Asia/Tehran)',
                'Updated At',
                'Updated At (Asia/Tehran)',
            ],
        ];
    }
}
