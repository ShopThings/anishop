<?php

namespace App\Http\Requests;

use App\Enums\Settings\SettingsEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            SettingsEnum::TITLE->value => [
                'sometimes',
                'max:250',
            ],
            SettingsEnum::DESCRIPTION->value => [
                'sometimes',
                'max: 300',
            ],
            SettingsEnum::KEYWORDS->value => [
                'sometimes',
                'array',
                'min:1',
            ],
            SettingsEnum::SMS_SIGNUP->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::SMS_ACTIVATION->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::SMS_RECOVER_PASS->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::SMS_BUY->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::SMS_ORDER_STATUS->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::SMS_RETURN_ORDER->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::SMS_RETURN_ORDER_STATUS->value => [
                'sometimes',
                'max:400',
            ],
            SettingsEnum::ADDRESS->value => [
                'sometimes',
            ],
            SettingsEnum::PHONES->value => [
                'sometimes',
            ],
            SettingsEnum::STORE_PROVINCE->value => [
                'sometimes',
            ],
            SettingsEnum::STORE_CITY->value => [
                'sometimes',
            ],
            SettingsEnum::LAT_LNG->value => [
                'sometimes',
                'array',
                'min:2',
                'max:2',
            ],
            SettingsEnum::LAT_LNG->value . '.*' => [
                'numeric',
            ],
            SettingsEnum::DIVIDE_PAYMENT_PRICE->value => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],
            SettingsEnum::MIN_FREE_POST_PRICE->value => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            SettingsEnum::PRODUCT_EACH_PAGE->value => [
                'sometimes',
                'numeric',
                'min:6',
                'max:25',
            ],
            SettingsEnum::BLOG_EACH_PAGE->value => [
                'sometimes',
                'numeric',
                'min:6',
                'max:25',
            ],
            SettingsEnum::SOCIALS->value => [
                'sometimes',
                'array',
                'min:0',
            ],
            SettingsEnum::SOCIALS->value . '.*.id' => [
                'required_if:' . SettingsEnum::SOCIALS->value,
                'numeric',
            ],
            SettingsEnum::SOCIALS->value . '.*.type' => [
                'required_if:' . SettingsEnum::SOCIALS->value,
            ],
            SettingsEnum::SOCIALS->value . '.*.link' => [
                'required_if:' . SettingsEnum::SOCIALS->value,
            ],
            SettingsEnum::FOOTER_DESCRIPTION->value => [
                'sometimes',
                'max:300',
            ],
            SettingsEnum::FOOTER_COPYRIGHT->value => [
                'sometimes',
                'max:300',
            ],
            SettingsEnum::FOOTER_NAMADS->value => [
                'sometimes',
                'array',
                'min:0',
            ],
            SettingsEnum::FOOTER_NAMADS->value . '.*.id' => [
                'required_if:' . SettingsEnum::FOOTER_NAMADS->value,
                'numeric',
            ],
            SettingsEnum::FOOTER_NAMADS->value . '.*.link' => [
                'required_if:' . SettingsEnum::FOOTER_NAMADS->value,
            ],
            SettingsEnum::DEFAULT_POST_PRICE->value => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ];
    }

    public function attributes()
    {
        return [
            SettingsEnum::TITLE->value => 'عنوان سایت',
            SettingsEnum::DESCRIPTION->value => 'توضیحات مختصر',
            SettingsEnum::KEYWORDS->value => 'کلمات کلیدی',
            SettingsEnum::SMS_SIGNUP->value => 'متن پیامک خوش‌آمدگویی ثبت نام',
            SettingsEnum::SMS_ACTIVATION->value => 'متن پیامک فعالسازی حساب',
            SettingsEnum::SMS_RECOVER_PASS->value => 'متن پیامک بازگردانی کلمه عبور',
            SettingsEnum::SMS_BUY->value => 'متن پیامک ایجاد سفارش',
            SettingsEnum::SMS_ORDER_STATUS->value => 'متن پیامک تغییر وضعیت سفارش',
            SettingsEnum::SMS_RETURN_ORDER->value => 'متن پیامک مرجوع سفارش',
            SettingsEnum::SMS_RETURN_ORDER_STATUS->value => 'متن پیامک تغییر وضعیت سفارش مرجوعی',
            SettingsEnum::LAT_LNG->value => 'طول و عرض جغرافیایی',
            SettingsEnum::LAT_LNG->value . '.0' => 'عرض جغرافیایی',
            SettingsEnum::LAT_LNG->value . '.1' => 'طول جغرافیایی',
            SettingsEnum::DIVIDE_PAYMENT_PRICE->value => 'مبلغ تقسیم پرداخت‌ها',
            SettingsEnum::MIN_FREE_POST_PRICE->value => 'حداقل مبلغ خرید برای رایگان شدن هزینه ارسال',
            SettingsEnum::PRODUCT_EACH_PAGE->value => 'تعداد نمایش محصول در هر صفحه',
            SettingsEnum::BLOG_EACH_PAGE->value => 'تعداد نمایش بلاگ در هر صفحه',
            SettingsEnum::SOCIALS->value => 'شبکه‌های اجتماعی',
            SettingsEnum::FOOTER_DESCRIPTION->value => 'توضیحات پاورقی',
            SettingsEnum::FOOTER_COPYRIGHT->value => 'متن حق مالکیت',
            SettingsEnum::FOOTER_NAMADS->value => 'نمادها',
            SettingsEnum::DEFAULT_POST_PRICE->value => 'هزینه ارسال پیش‌فرض',
        ];
    }
}
