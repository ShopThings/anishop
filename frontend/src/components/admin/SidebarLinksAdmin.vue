<template>
    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                اصلی
            </li>
            <li>
                <router-link :to="{name: 'admin.home'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <WindowIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">داشبورد</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.users'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <UsersIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">کاربران</span>
                </router-link>
            </li>
        </ul>
    </div>

    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                فروشگاه
            </li>
            <li>
                <router-link :to="{name: 'admin.payment_methods'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <CreditCardIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">روش‌های پرداخت</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.colors'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <PaintBrushIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">رنگ‌ها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.brands'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <TagIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">برندها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.categories'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <Square2StackIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">مدیریت دسته‌بندی‌ها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.festivals'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <GiftIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">جشنواره‌ها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.units'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <HashtagIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">واحدها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.coupons'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <ReceiptPercentIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">کوپن‌های تخفیف</span>
                </router-link>
            </li>
            <li>
                <sidebar-link-sub-admin>
                    <template #link>
                        <router-link :to="{name: 'admin.products'}"
                                     class="rounded-lg py-2.5 px-3 flex items-center hover:bg-white hover:bg-opacity-10 transition grow">
                            <ArchiveBoxIcon class="h-6 w-6 ml-2 shrink-0"/>
                            <span class="grow">محصولات</span>
                        </router-link>
                    </template>

                    <template #children>
                        <partial-sidebar-item-admin>
                            <router-link :to="{name: 'admin.search.attrs'}"
                                         class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                                <span
                                    class="grow">ویژگی‌های جستجوی محصول</span>
                            </router-link>
                            <router-link :to="{name: 'admin.search.attrs.categories'}"
                                         class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                                <span
                                    class="grow">دسته‌بندی‌ها و ویژگی‌های جستجوی</span>
                            </router-link>
                        </partial-sidebar-item-admin>
                    </template>
                </sidebar-link-sub-admin>
            </li>
        </ul>
    </div>

    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                مدیریت سفارشات
            </li>
            <li>
                <sidebar-link-sub-admin>
                    <template #link>
                        <router-link :to="{name: 'admin.orders'}"
                                     class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition grow">
                            <ShoppingBagIcon class="h-6 w-6 ml-2 shrink-0"/>
                            <span
                                class="grow">سفارشات ثبت شده</span>
                        </router-link>
                    </template>

                    <template #children>
                        <partial-sidebar-item-admin>
                            <router-link :to="{name: 'admin.orders.badges'}"
                                         class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                                    <span
                                        class="grow">برچسب وضعیت سفارشات</span>
                            </router-link>
                        </partial-sidebar-item-admin>
                    </template>
                </sidebar-link-sub-admin>
            </li>
            <li>
                <router-link :to="{name: 'admin.return_orders'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <ArchiveBoxXMarkIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">سفارشات مرجوعی</span>
                </router-link>
            </li>
        </ul>
    </div>

    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                گزارش گیری
            </li>
            <li>
                <router-link :to="{name: 'admin.report.users'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <RectangleStackIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">گزارش‌گیری از کاربران</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.report.products'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <RectangleStackIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">گزارش‌گیری از محصولات</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.report.orders'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <RectangleStackIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">گزارش‌گیری از سفارشات</span>
                </router-link>
            </li>
        </ul>
    </div>

    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                صفحات
            </li>
            <li>
                <sidebar-link-sub-admin>
                    <template #link>
                        <router-link :to="{name: 'admin.blogs'}"
                                     class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition grow">
                            <DocumentTextIcon class="h-6 w-6 ml-2 shrink-0"/>
                            <span
                                class="grow">بلاگ</span>
                        </router-link>
                    </template>

                    <template #children>
                        <partial-sidebar-item-admin>
                            <router-link :to="{name: 'admin.blogs.badges'}"
                                         class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                                    <span
                                        class="grow">برچسب نظرات</span>
                            </router-link>
                        </partial-sidebar-item-admin>
                        <partial-sidebar-item-admin>
                            <router-link :to="{name: 'admin.blogs.categories'}"
                                         class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                                    <span
                                        class="grow">مدیریت دسته‌بندی‌ها</span>
                            </router-link>
                        </partial-sidebar-item-admin>
                    </template>
                </sidebar-link-sub-admin>
            </li>
            <li>
                <router-link :to="{name: 'admin.static_pages'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <DocumentIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">صفحات ثابت</span>
                </router-link>
            </li>
        </ul>
    </div>

    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                ارتباطات
            </li>
            <li>
                <router-link :to="{name: 'admin.contacts'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <DevicePhoneMobileIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">تماس‌ها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.complaints'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <ScaleIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">شکایات</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.faqs'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <QuestionMarkCircleIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">سؤالات متداول</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.newsletters'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <NewspaperIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">خبرنامه</span>
                </router-link>
            </li>
        </ul>
    </div>

    <div>
        <ul class="flex flex-col">
            <li class="text-white opacity-70 text-xs px-3 mb-1 mt-5">
                سایر
            </li>
            <li>
                <router-link :to="{name: 'admin.post_prices'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <CurrencyDollarIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">هزینه ارسال</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.sliders'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <ViewColumnsIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">مدیریت اسلایدرها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.menus'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <RectangleGroupIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">مدیریت منو‌ها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.file_manager'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <FolderOpenIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">مدیریت فایل‌ها</span>
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'admin.guides'}"
                             class="rounded-lg py-2.5 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                    <FlagIcon class="h-6 w-6 ml-2 shrink-0"/>
                    <span
                        class="grow">راهنما</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import {
    CreditCardIcon, PaintBrushIcon, UsersIcon, WindowIcon,
    TagIcon, Square2StackIcon, GiftIcon, HashtagIcon, ReceiptPercentIcon,
    ArchiveBoxIcon, ShoppingBagIcon, ArchiveBoxXMarkIcon, RectangleStackIcon,
    DocumentTextIcon, DocumentIcon, DevicePhoneMobileIcon, ScaleIcon,
    QuestionMarkCircleIcon, NewspaperIcon, CurrencyDollarIcon, RectangleGroupIcon,
    ViewColumnsIcon, FolderOpenIcon, FlagIcon,
} from "@heroicons/vue/24/outline"
import SidebarLinkSubAdmin from "./SidebarLinkSubAdmin.vue";
import PartialSidebarItemAdmin from "../partials/PartialSidebarItemAdmin.vue";
</script>

<style scoped>
.router-link-active,
.router-link-exact-active {
    position: relative;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, .1), transparent, rgba(255, 255, 255, .1));
    color: #fff;
}

.router-link-active:before,
.router-link-exact-active:before {
    content: '';
    position: absolute;
    top: 50%;
    right: 0;
    width: .325rem;
    height: 50%;
    border-radius: 50rem 0 0 50rem;
    background-color: #fff;
    transform: translate(0, -50%);
}
</style>
