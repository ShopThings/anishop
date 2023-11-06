<template>
    <div class="mb-6 p-3">
        <div class="flex flex-col lg:flex-row gap-6 sticky-container">
            <div class="grow">
                <base-loading-panel
                    :loading="loading"
                >
                    <template #loader>
                        <loader-content-blog/>
                    </template>

                    <template #content>
                        <div class="flex flex-col gap-6">
                            <partial-card class="border-0 p-3">
                                <template #body>
                                    <div class="flex flex-wrap flex-col sm:flex-row gap-3">
                                        <div class="flex gap-5 items-center">
                                            <div class="flex gap-2 items-center">
                                                <UserCircleIcon class="w-8 h-8 text-slate-300 shrink-0"/>
                                                <span class="text-xs text-slate-500">محمد مهدی دهقان منشادی</span>
                                            </div>

                                            <div class="flex gap-2 items-center">
                                                <CalendarIcon class="w-5 h-5 text-slate-300 shrink-0"/>
                                                <span class="text-xs text-slate-500">۱۷ مهر ۱۴۰۲</span>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 items-center sm:mr-auto">
                                            <ClockIcon class="w-5 h-5 text-slate-300"/>
                                            <div class="flex gap-2 items-center">
                                                <span class="text-xs text-slate-400">زمان مطالعه:</span>
                                                <span class="text-xs text-slate-500">12 دقیقه</span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </partial-card>

                            <partial-card class="border-0 p-6">
                                <template #body>
                                    <h1 class="text-2xl font-iranyekan-bold mb-7 leading-relaxed">
                                        راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی
                                    </h1>

                                    <div class="mb-4 flex flex-wrap items-center justify-end sm:justify-between gap-4">
                                        <ul class="flex flex-wrap items-center justify-end gap-4">
                                            <li>
                                                <button
                                                    v-tooltip.bottom="'نپسندیدم'"
                                                    type="button"
                                                    class="text-slate-400 hover:text-black transition flex items-center gap-2"
                                                >
                                                    <HandThumbDownIcon class="w-6 h-6"/>
                                                    <span class="flex items-center gap-0.5">
                                                        <span class="text-xs">(</span>
                                                        <span>0</span>
                                                        <span class="text-xs">)</span>
                                                    </span>
                                                </button>
                                            </li>
                                            <li>
                                                <button
                                                    v-tooltip.bottom="'خوشم اومده'"
                                                    type="button"
                                                    class="text-slate-400 hover:text-black transition flex items-center gap-2"
                                                >
                                                    <HandThumbUpIcon class="w-6 h-6"/>
                                                    <span class="flex items-center gap-0.5">
                                                        <span class="text-xs">(</span>
                                                        <span>12</span>
                                                        <span class="text-xs">)</span>
                                                    </span>
                                                </button>
                                            </li>
                                        </ul>

                                        <ul class="flex flex-wrap items-center justify-end gap-4 mr-auto">
                                            <li class="pl-4 border-l-2 border-slate-200">
                                                <a
                                                    v-tooltip.bottom="'دیدگاه‌ها'"
                                                    href="#"
                                                    class="text-slate-400 hover:text-black transition flex items-center gap-2"
                                                >
                                                    <div class="flex items-center gap-0.5">
                                                        <span class="text-xs">(</span>
                                                        <span>2</span>
                                                        <span class="text-xs">)</span>
                                                    </div>
                                                    <ChatBubbleLeftIcon class="w-6 h-6"/>
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    v-tooltip.bottom="'اشتراک گذاری در اینستاگرام'"
                                                    href="javascript:void(0)"
                                                    class="text-slate-400 hover:text-black transition"
                                                >
                                                    <svg
                                                        class="w-6 h-6 fill-current"
                                                        height="100%"
                                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                                                        viewBox="0 0 600 600" width="100%"
                                                        xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                                    <g transform="matrix(1.01619,0,0,1.01619,44,43.8384)">
                                                        <path
                                                            d="M251.921,0.159C183.503,0.159 174.924,0.449 148.054,1.675C121.24,2.899 102.927,7.157 86.902,13.385C70.336,19.823 56.287,28.437 42.282,42.442C28.277,56.447 19.663,70.496 13.225,87.062C6.997,103.086 2.739,121.399 1.515,148.213C0.289,175.083 0,183.662 0,252.08C0,320.497 0.289,329.076 1.515,355.946C2.739,382.76 6.997,401.073 13.225,417.097C19.663,433.663 28.277,447.712 42.282,461.718C56.287,475.723 70.336,484.337 86.902,490.775C102.927,497.002 121.24,501.261 148.054,502.484C174.924,503.71 183.503,504 251.921,504C320.338,504 328.917,503.71 355.787,502.484C382.601,501.261 400.914,497.002 416.938,490.775C433.504,484.337 447.553,475.723 461.559,461.718C475.564,447.712 484.178,433.663 490.616,417.097C496.843,401.073 501.102,382.76 502.325,355.946C503.551,329.076 503.841,320.497 503.841,252.08C503.841,183.662 503.551,175.083 502.325,148.213C501.102,121.399 496.843,103.086 490.616,87.062C484.178,70.496 475.564,56.447 461.559,42.442C447.553,28.437 433.504,19.823 416.938,13.385C400.914,7.157 382.601,2.899 355.787,1.675C328.917,0.449 320.338,0.159 251.921,0.159ZM251.921,45.551C319.186,45.551 327.154,45.807 353.718,47.019C378.28,48.14 391.619,52.244 400.496,55.693C412.255,60.263 420.647,65.723 429.462,74.538C438.278,83.353 443.737,91.746 448.307,103.504C451.757,112.381 455.861,125.72 456.981,150.282C458.193,176.846 458.45,184.814 458.45,252.08C458.45,319.345 458.193,327.313 456.981,353.877C455.861,378.439 451.757,391.778 448.307,400.655C443.737,412.414 438.278,420.806 429.462,429.621C420.647,438.437 412.255,443.896 400.496,448.466C391.619,451.916 378.28,456.02 353.718,457.14C327.158,458.352 319.191,458.609 251.921,458.609C184.65,458.609 176.684,458.352 150.123,457.14C125.561,456.02 112.222,451.916 103.345,448.466C91.586,443.896 83.194,438.437 74.378,429.621C65.563,420.806 60.103,412.414 55.534,400.655C52.084,391.778 47.98,378.439 46.859,353.877C45.647,327.313 45.391,319.345 45.391,252.08C45.391,184.814 45.647,176.846 46.859,150.282C47.98,125.72 52.084,112.381 55.534,103.504C60.103,91.746 65.563,83.353 74.378,74.538C83.194,65.723 91.586,60.263 103.345,55.693C112.222,52.244 125.561,48.14 150.123,47.019C176.687,45.807 184.655,45.551 251.921,45.551Z"
                                                            style="fill-rule:nonzero;"/>
                                                        <path
                                                            d="M251.921,336.053C205.543,336.053 167.947,298.457 167.947,252.08C167.947,205.702 205.543,168.106 251.921,168.106C298.298,168.106 335.894,205.702 335.894,252.08C335.894,298.457 298.298,336.053 251.921,336.053ZM251.921,122.715C180.474,122.715 122.556,180.633 122.556,252.08C122.556,323.526 180.474,381.444 251.921,381.444C323.367,381.444 381.285,323.526 381.285,252.08C381.285,180.633 323.367,122.715 251.921,122.715Z"
                                                            style=";fill-rule:nonzero;"/>
                                                        <path
                                                            d="M416.627,117.604C416.627,134.3 403.092,147.834 386.396,147.834C369.701,147.834 356.166,134.3 356.166,117.604C356.166,100.908 369.701,87.374 386.396,87.374C403.092,87.374 416.627,100.908 416.627,117.604Z"
                                                            style=";fill-rule:nonzero;"/>
                                                    </g>
                                                    </svg>
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    v-tooltip.bottom="'اشتراک گذاری در واتساپ'"
                                                    href="javascript:void(0)"
                                                    class="text-slate-400 hover:text-black transition"
                                                >
                                                    <svg
                                                        class="w-6 h-6 fill-current"
                                                        height="100%"
                                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                                                        viewBox="0 0 512 512" width="100%"
                                                        xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M373.295,307.064c-6.37,-3.188 -37.687,-18.596 -43.526,-20.724c-5.838,-2.126 -10.084,-3.187 -14.331,3.188c-4.246,6.376 -16.454,20.725 -20.17,24.976c-3.715,4.251 -7.431,4.785 -13.8,1.594c-6.37,-3.187 -26.895,-9.913 -51.225,-31.616c-18.935,-16.89 -31.72,-37.749 -35.435,-44.126c-3.716,-6.377 -0.397,-9.824 2.792,-13c2.867,-2.854 6.371,-7.44 9.555,-11.16c3.186,-3.718 4.247,-6.377 6.37,-10.626c2.123,-4.252 1.062,-7.971 -0.532,-11.159c-1.591,-3.188 -14.33,-34.542 -19.638,-47.298c-5.171,-12.419 -10.422,-10.737 -14.332,-10.934c-3.711,-0.184 -7.963,-0.223 -12.208,-0.223c-4.246,0 -11.148,1.594 -16.987,7.969c-5.838,6.377 -22.293,21.789 -22.293,53.14c0,31.355 22.824,61.642 26.009,65.894c3.185,4.252 44.916,68.59 108.816,96.181c15.196,6.564 27.062,10.483 36.312,13.418c15.259,4.849 29.145,4.165 40.121,2.524c12.238,-1.827 37.686,-15.408 42.995,-30.286c5.307,-14.882 5.307,-27.635 3.715,-30.292c-1.592,-2.657 -5.838,-4.251 -12.208,-7.44m-116.224,158.693l-0.086,0c-38.022,-0.015 -75.313,-10.23 -107.845,-29.535l-7.738,-4.592l-80.194,21.037l21.405,-78.19l-5.037,-8.017c-21.211,-33.735 -32.414,-72.726 -32.397,-112.763c0.047,-116.825 95.1,-211.87 211.976,-211.87c56.595,0.019 109.795,22.088 149.801,62.139c40.005,40.05 62.023,93.286 62.001,149.902c-0.048,116.834 -95.1,211.889 -211.886,211.889m180.332,-392.224c-48.131,-48.186 -112.138,-74.735 -180.335,-74.763c-140.514,0 -254.875,114.354 -254.932,254.911c-0.018,44.932 11.72,88.786 34.03,127.448l-36.166,132.102l135.141,-35.45c37.236,20.31 79.159,31.015 121.826,31.029l0.105,0c140.499,0 254.87,-114.366 254.928,-254.925c0.026,-68.117 -26.467,-132.166 -74.597,-180.352"
                                                        id="WhatsApp-Logo"/>
                                                    </svg>
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    v-tooltip.bottom="'اشتراک گذاری در لینکدین'"
                                                    href="javascript:void(0)"
                                                    class="text-slate-400 hover:text-black transition"
                                                >
                                                    <svg
                                                        class="w-6 h-6 fill-current"
                                                        height="100%"
                                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                                                        viewBox="0 0 512 512" xml:space="preserve"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="M257.6,5c74,0,148,0,221.9,0C499,5,511,16.9,511,36.4c0,147.1,0,294.3,0,441.4c0,19.2-12,31.2-31.1,31.2   c-148.6,0-297.3,0-445.9,0c-18.8,0-31-12.1-31-30.9C3,330.7,3,183.2,3,35.8C3,17.3,15.2,5,33.6,5C108.3,5,182.9,5,257.6,5z M257,19   c-74,0-148,0-222,0c-12.3,0-18,5.7-18,18c0,146.7,0,293.3,0,440c0,12.3,5.7,18,18,18c148,0,296,0,444,0c12.3,0,18-5.7,18-18   c0-146.7,0-293.3,0-440c0-12.3-5.7-18-18-18C405,19,331,19,257,19z"/>
                                                        <path
                                                            d="M200.2,430.8c0-76.8,0-152.9,0-229.5c23.3,0,46.5,0,70.1,0c0,9.1,0,18.3,0,28.7c4.1-4.9,7.4-9,10.9-13   c13-14.9,29.6-22.7,49-24.5c19.7-1.8,38.8,0.7,56.3,10.9c14.1,8.1,24.3,19.7,31.4,34.2c8.6,17.5,11.8,36.3,11.9,55.6   c0.2,44.2,0.1,88.3,0.1,132.5c0,1.5-0.1,3-0.2,4.9c-1.8,0.1-3.4,0.2-5,0.2c-20.2,0-40.3-0.1-60.5,0.1c-4.6,0.1-5.6-1.5-5.6-5.8   c0.1-40.5,0.2-81,0-121.5c0-7.4-1-15-2.7-22.2c-3-12.5-11.4-20.9-23.4-25.2c-12.3-4.4-25-5.3-36.9,0.6c-17.5,8.8-24.9,23.7-24.8,43   c0.2,40.8,0.1,81.7,0,122.5c0,10.2,0.1,8.2-8.3,8.3c-18.8,0.1-37.7,0-56.5,0C204.3,430.8,202.5,430.8,200.2,430.8z"/>
                                                        <path
                                                            d="M155.5,430.8c-4.7,0-9,0-13.3,0c-17.2,0-34.3-0.1-51.5,0.1c-4.2,0.1-5.9-0.9-5.9-5.5c0.1-72.6,0.1-145.2,0.1-217.8   c0-3.1,0.2-5.4,4.4-5.4c20.8,0.1,41.6,0.1,62.5,0.1c1.1,0,2.3,0.3,3.7,0.5C155.5,278.6,155.5,354.3,155.5,430.8z"/>
                                                        <path
                                                            d="M118.6,157c-21.1,0-35.9-15-35.8-36.3c0.1-20.8,15.3-35.7,36.6-35.6c21.8,0.1,36,14.8,36.2,36.1   C155.8,141.7,141.2,157.2,118.6,157z"/>
                                                    </g>
                                                    </svg>
                                                </a>
                                            </li>

                                            <li>
                                                <a
                                                    v-tooltip.bottom="'اشتراک گذاری در توییتر'"
                                                    href="javascript:void(0)"
                                                    class="text-slate-400 hover:text-black transition"
                                                >
                                                    <svg
                                                        class="w-6 h-6 fill-current"
                                                        height="100%"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"
                                                        xml:space="preserve">
                                                    <g>
                                                        <path
                                                            d="M21.159809,2C21.623091,2,22,2.376909,22,2.840191v18.319618C22,21.623091,21.623091,22,21.159809,22H2.84019   C2.37691,22,2,21.623091,2,21.159809V2.840191C2,2.376909,2.37691,2,2.84019,2H21.159809 M21.159809,1H2.84019   C1.82388,1,1,1.823879,1,2.840191v18.319618C1,22.176121,1.82388,23,2.84019,23h18.319618C22.176121,23,23,22.176121,23,21.159809   V2.840191C23,1.823879,22.176121,1,21.159809,1L21.159809,1z"/>
                                                    </g>
                                                        <path
                                                            d="M13.523985,10.775623L19.480984,4h-1.41143l-5.174801,5.88195L8.764665,4H4l6.246901,8.895341L4,20h1.411431  l5.461361-6.21299L15.235336,20H20L13.523985,10.775623z M11.590199,12.973429l-0.633908-0.886331L5.920428,5.041171h2.168246  l4.065295,5.6884l0.631236,0.886331l5.283681,7.39365H15.90064L11.590199,12.973429z"/>
                                                    </svg>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                    <base-lazy-image
                                        lazy-src="/src/assets/blogs/b1.jpg"
                                        alt=""
                                        class="!w-full !h-auto rounded-lg"
                                    />

                                    <div class="mt-6">
                                        <div class="styled-description">
                                            <h2 id="-">مقدمه</h2>
                                            <p>در دنیای پر از تکنولوژی امروزه، هدست‌ها به یکی از ابزارهای حیاتی برای گوش
                                                دادن به موسیقی، تماشای فیلم و انجام تماس‌های تلفنی تبدیل شده‌اند. اما با
                                                تنوع بی‌پایان مدل‌ها و برندهای مختلف در بازار، انتخاب بهترین هدست برای
                                                نیازهای خود ممکن است یک چالش باشد. در این مقاله، به بهترین هدست‌ها با
                                                توجه به کیفیت صدا، سبک طراحی، و قابلیت‌های ویژه آن‌ها پرداخته و شما را
                                                در انتخاب بهترین هدست برای تجربه گوش دادن بی‌نظیرتان راهنمایی خواهیم
                                                کرد.</p>
                                            <h2 id="1-">1. هدف از خرید هدست</h2>
                                            <p>قبل از انتخاب هدست مناسب، اهمیتی دارد که مشخص کنید هدست را برای کدام
                                                فعالیت‌ها می‌خواهید استفاده کنید. آیا برای گوش دادن به موسیقی، تماشای
                                                فیلم، یا انجام تماس‌های تلفنی است؟</p>
                                            <h2 id="2-">2. نوع هدست</h2>
                                            <p>هدست‌ها به دو نوع اصلی تقسیم می‌شوند: </p>
                                            <h3 id="2-1-">2.1. هدفون‌ها</h3>
                                            <ul>
                                                <li>هدفون‌ها، هدست‌های بزرگی هستند که بر روی گوش‌ها قرار می‌گیرند و صدای
                                                    فوق‌العاده‌ای ارائه می‌دهند.
                                                </li>
                                            </ul>
                                            <h3 id="2-2-">2.2. هدست‌های تخت</h3>
                                            <ul>
                                                <li>هدست‌های تخت به صورت یک نوار از جلوی سر شما قرار می‌گیرند و معمولاً
                                                    سبک‌تر از هدفون‌ها هستند.
                                                </li>
                                            </ul>
                                            <h2 id="3-">3. کیفیت صدا</h2>
                                            <p>یکی از مهم‌ترین عوامل در انتخاب هدست، کیفیت صدای آن است. </p>
                                            <h3 id="3-1-">3.1. صدای استریو</h3>
                                            <p>هدست‌های با صدای استریو برای گوش دادن به موسیقی و تماشای فیلم مناسب
                                                هستند.</p>
                                            <h3 id="3-2-">3.2. کنترل صدا</h3>
                                            <p>در برخی مدل‌ها، امکان تنظیم صدا و کنترل میزان صدا وجود دارد.</p>
                                            <p></p>
                                            <img src="/src/assets/blogs/b2.jpg" alt="">
                                            <p></p>
                                            <h2 id="4-">4. سبک و راحتی</h2>
                                            <p>سبکی که هدست ارائه می‌دهد و راحتی استفاده از آن نیز مهم است. </p>
                                            <h3 id="4-1-">4.1. هدست‌های بی‌سیم</h3>
                                            <p>هدست‌های بی‌سیم بدون کابل وصل‌کننده به دستگاه هستند و حرکت آزادتری را
                                                ممکن می‌کنند.</p>
                                            <h3 id="4-2-">4.2. هدست‌های باسیم</h3>
                                            <p>هدست‌های باسیم اغلب از کابل وصل‌کننده به دستگاه استفاده می‌کنند و معمولاً
                                                ارزان‌تر هستند.</p>
                                            <h2 id="5-">5. قیمت</h2>
                                            <p>هدست‌ها در رنج قیمت‌های مختلف موجود هستند. برنامه‌ریزی بودجه خود و انتخاب
                                                هدستی که با آن همخوانی دارد اهمیت دارد.</p>
                                            <h2 id="6-">6. برند و اعتبار</h2>
                                            <h2 id="7-">7. مشخصات فنی</h2>
                                            <h2 id="8-">8. باتری و عمر استفاده</h2>
                                            <h2 id="9-">9. مقاومت به آب و گرد و غبار</h2>
                                            <p></p>
                                            <img src="/src/assets/blogs/b3.jpg" alt="">
                                            <p></p>
                                            <h2 id="10-">10. قابلیت‌های ویژه</h2>
                                            <h2 id="11-">11. نظرات مشتریان</h2>
                                            <h2 id="12-">12. مقایسه مدل‌ها</h2>
                                            <h2 id="13-">13. بهترین هدست‌ها برای موسیقی</h2>
                                            <h3 id="13-1-sony-wh-1000xm4">13.1. Sony WH-1000XM4</h3>
                                            <h3 id="13-2-bose-quietcomfort-35-ii">13.2. Bose QuietComfort 35 II</h3>
                                            <h3 id="13-3-sennheiser-hd-660-s">13.3. Sennheiser HD 660 S</h3>
                                            <h2 id="14-">14. بهترین هدست‌ها برای تماشای فیلم</h2>
                                            <h3 id="14-1-sony-wh-1000xm4">14.1. Sony WH-1000XM4</h3>
                                            <h3 id="14-2-bose-quietcomfort-35-ii">14.2. Bose QuietComfort 35 II</h3>
                                            <h3 id="14-3-apple-airpods-max">14.3. Apple AirPods Max</h3>
                                            <p></p>
                                            <img src="/src/assets/blogs/b6.jpg" alt="">
                                            <p></p>
                                            <h2 id="15-">15. بهترین هدست‌ها برای تماس‌های تلفنی</h2>
                                            <h3 id="15-1-j">15.1. J</h3>
                                            <p>abra Elite 85h</p>
                                            <h3 id="15-2-bose-700">15.2. Bose 700</h3>
                                            <h3 id="15-3-sony-wh-1000xm4">15.3. Sony WH-1000XM4</h3>
                                            <h2 id="-">نتیجه‌گیری</h2>
                                            <p>در انتخاب بهترین هدست برای نیازهای خود، باید به مواردی نظیر نوع هدست،
                                                کیفیت صدا، سبک و راحتی، قیمت، برند و اعتبار، مشخصات فنی، باتری و عمر
                                                استفاده، مقاومت به آب و گرد و غبار، قابلیت‌های ویژه، نظرات مشتریان و
                                                مقایسه مدل‌ها توجه کنید. با دقت به این عوامل، می‌توانید بهترین هدست برای
                                                تجربه گوش دادن بی‌نظیرتان را پیدا کنید.</p>
                                            <h2 id="-">پرسش‌های متداول</h2>
                                            <h3 id="1-">1. چگونه می‌توانم هدست مناسب را انتخاب کنم؟</h3>
                                            <h3 id="2-">2. آیا هدست‌های بی‌سیم بهتر از هدست‌های باسیم هستند؟</h3>
                                            <h3 id="3-">3. کدام برند هدست بهترین کیفیت صدا را ارائه می‌دهد؟</h3>
                                            <h3 id="4-">4. آیا هدست‌ها مقاوم به آب و گرد و غبار هستند؟</h3>
                                            <h3 id="5-">5. چگونه می‌توانم هدست خود را به بهترین شکل مراقبت کنم؟</h3>
                                            <h2 id="-">اطلاعات بیشتر</h2>
                                            <p>برای اطلاعات بیشتر و خرید هدست‌های با کیفیت، به لینک زیر مراجعه کنید:</p>
                                            <p><strong>Get Access Now: <a href="https://bit.ly/J_Umma">https://bit.ly/J_Umma</a></strong>
                                            </p>
                                            <p>این مقاله با مطالبی در مورد بهترین هدست‌ها به شما کمک کرد تا هدستی که با
                                                نیازهای شما همخوانی دارد را پیدا کنید. بهترین تجربه گوش دادن را برای خود
                                                ایجاد کنید.</p>
                                        </div>
                                    </div>

                                    <div class="mt-8 pt-3 border-t">
                                        <partial-input-label title="برچسب‌ها"/>

                                        <ul class="flex flex-wrap items-center gap-3 mt-3">
                                            <li>
                                                <router-link
                                                    :to="{name: 'blog.search', query: {tag: 'something'}}"
                                                    class="py-1 text-xs px-3 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-black transition"
                                                >
                                                    راهنمای خرید هدفون
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link
                                                    :to="{name: 'blog.search', query: {tag: 'something'}}"
                                                    class="py-1 text-xs px-3 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-black transition"
                                                >
                                                    هدست
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link
                                                    :to="{name: 'blog.search', query: {tag: 'something'}}"
                                                    class="py-1 text-xs px-3 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-black transition"
                                                >
                                                    هدست مخصوص بازی
                                                </router-link>
                                            </li>
                                            <li>
                                                <router-link
                                                    :to="{name: 'blog.search', query: {tag: 'something'}}"
                                                    class="py-1 text-xs px-3 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-black transition"
                                                >
                                                    هدفون
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </partial-card>

                            <partial-card class="border-0 p-6">
                                <template #body>
                                    <partial-general-title
                                        container-class="mb-5"
                                        title="دیدگاه‌ها"
                                    />

                                    <div class="mb-12">
                                        <blog-comments/>
                                    </div>

                                    <form-blog-comment/>
                                </template>
                            </partial-card>
                        </div>
                    </template>
                </base-loading-panel>
            </div>

            <Vue3StickySidebar
                class="shrink-0 lg:w-80"
                containerSelector=".sticky-container"
                innerWrapperSelector='.sidebar__inner'
                :top-spacing="84"
                :bottom-spacing="20"
                :min-width="1024"
            >
                <div class="flex flex-col gap-6">
                    <partial-card
                        class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
                        <template #body>
                            <partial-general-title
                                type="side"
                                title="دسته‌بندی‌های پرطرفدار"
                            />

                            <app-side-categories-blog/>
                        </template>
                    </partial-card>

                    <partial-card
                        class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
                        <template #body>
                            <partial-general-title
                                type="side"
                                title="آرشیو نوشته‌ها"
                            />

                            <div class="flex flex-col divide-y">
                                <router-link
                                    to="#"
                                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                                >
                                    <ArchiveBoxIcon
                                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"></ArchiveBoxIcon>
                                    <span>اردیبهشت ۱۴۰۲</span>
                                </router-link>
                                <router-link
                                    to="#"
                                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                                >
                                    <ArchiveBoxIcon
                                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                                    <span>خرداد ۱۴۰۲</span>
                                </router-link>
                                <router-link
                                    to="#"
                                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                                >
                                    <ArchiveBoxIcon
                                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                                    <span>تیر ۱۴۰۲</span>
                                </router-link>
                                <router-link
                                    to="#"
                                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                                >
                                    <ArchiveBoxIcon
                                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                                    <span>مرداد ۱۴۰۲</span>
                                </router-link>
                                <router-link
                                    to="#"
                                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                                >
                                    <ArchiveBoxIcon
                                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"/>
                                    <span>شهریور ۱۴۰۲</span>
                                </router-link>
                                <router-link
                                    to="#"
                                    class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
                                >
                                    <ArchiveBoxIcon
                                        class="w-5 h-5 text-slate-300 group-hover:text-violet-500"></ArchiveBoxIcon>
                                    <span>مهر ۱۴۰۲</span>
                                </router-link>
                            </div>
                        </template>
                    </partial-card>

                    <partial-card
                        class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80">
                        <template #body>
                            <partial-general-title
                                type="side"
                                title="پربازدیدترین نوشته‌ها"
                            />

                            <div class="flex flex-col divide-y">
                                <loader-list-single-blog
                                    v-if="loadingLatestBlog"
                                    v-for="i in 9"
                                    :key="i"
                                />

                                <div
                                    v-else
                                    v-for="blog in latestBlogs"
                                    :key="blog.id"
                                    class="w-full py-2"
                                >
                                    <blog-card-small
                                        container-class="border-0 !bg-transparent"
                                        :blog="blog"
                                    />
                                </div>
                            </div>
                        </template>
                    </partial-card>
                </div>
            </Vue3StickySidebar>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import BaseLoadingPanel from "../components/base/BaseLoadingPanel.vue";
import LoaderContentBlog from "../components/base/loader/LoaderContentBlog.vue";
import PartialGeneralTitle from "../components/partials/PartialGeneralTitle.vue";
import BlogCardSmall from "../components/blog/BlogCardSmall.vue";
import AppSideCategoriesBlog from "../components/AppSideCategoriesBlog.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import LoaderListSingleBlog from "../components/base/loader/LoaderListSingleBlog.vue";
import {
    UserCircleIcon,
    CalendarIcon,
    ClockIcon,
    ArchiveBoxIcon,
    ChatBubbleLeftIcon,
    HandThumbUpIcon,
    HandThumbDownIcon,
} from "@heroicons/vue/24/outline/index.js";
import BaseLazyImage from "../components/base/BaseLazyImage.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import FormBlogComment from "./forms/FormBlogComment.vue";
import PartialInputLabel from "../components/partials/PartialInputLabel.vue";
import BlogComments from "../components/blog/BlogComments.vue";

const loading = ref(false)

//----------------------------
// Latest Blogs
//----------------------------
const loadingLatestBlog = ref(false)
const latestBlogs = ref([
    {
        id: 9,
        title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
        image: {
            path: '/src/assets/blogs/b9.jpg'
        },
        category: {
            name: 'تعمیر و نگهداری',
        },
        created_at: '۲۵ مهر ۱۴۰۲',
        creator: {
            first_name: 'محمد مهدی',
            last_name: 'دهقان منشادی',
        },
    },
    {
        id: 8,
        title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
        image: {
            path: '/src/assets/blogs/b8.jpg'
        },
        category: {
            name: 'تلنت (Talent)',
        },
        created_at: '۲۱ مهر ۱۴۰۲',
        creator: {
            first_name: 'سعید',
            last_name: 'گرامی فر',
        },
    },
    {
        id: 7,
        title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
        image: {
            path: '/src/assets/blogs/b7.jpg'
        },
        category: {
            name: 'اسپیکر و هدفون',
        },
        created_at: '۱۵ شهریور ۱۴۰۲',
        creator: {
            first_name: 'اضغر',
            last_name: 'فرهادی',
        },
    },
    {
        id: 6,
        title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
        image: {
            path: '/src/assets/blogs/b6.jpg'
        },
        category: {
            name: 'تعمیر و نگهداری',
        },
        created_at: '۲۵ مهر ۱۴۰۲',
        creator: {
            first_name: 'محمد مهدی',
            last_name: 'دهقان منشادی',
        },
    },
    {
        id: 5,
        title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
        image: {
            path: '/src/assets/blogs/b5.jpg'
        },
        category: {
            name: 'تلنت (Talent)',
        },
        created_at: '۲۱ مهر ۱۴۰۲',
        creator: {
            first_name: 'سعید',
            last_name: 'گرامی فر',
        },
    },
    {
        id: 4,
        title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
        image: {
            path: '/src/assets/blogs/b4.jpg'
        },
        category: {
            name: 'اسپیکر و هدفون',
        },
        created_at: '۱۵ شهریور ۱۴۰۲',
        creator: {
            first_name: 'اضغر',
            last_name: 'فرهادی',
        },
    },
    {
        id: 3,
        title: 'بررسی ماوس لاجیتک G502 Hero؛ اسطوره‌ی قدیمی',
        image: {
            path: '/src/assets/blogs/b3.jpg'
        },
        category: {
            name: 'تعمیر و نگهداری',
        },
        created_at: '۲۵ مهر ۱۴۰۲',
        creator: {
            first_name: 'محمد مهدی',
            last_name: 'دهقان منشادی',
        },
    },
    {
        id: 2,
        title: 'بررسی اسپیکر جوی روم JR-ML03؛ یک اسپیکر آرامش‌بخش',
        image: {
            path: '/src/assets/blogs/b2.jpg'
        },
        category: {
            name: 'تلنت (Talent)',
        },
        created_at: '۲۱ مهر ۱۴۰۲',
        creator: {
            first_name: 'سعید',
            last_name: 'گرامی فر',
        },
    },
    {
        id: 1,
        title: 'راهنمای خرید بهترین هدست اونیکوما؛ ۱۵ محصول جذاب با قیمت عالی',
        image: {
            path: '/src/assets/blogs/b1.jpg'
        },
        category: {
            name: 'اسپیکر و هدفون',
        },
        created_at: '۱۵ شهریور ۱۴۰۲',
        creator: {
            first_name: 'اضغر',
            last_name: 'فرهادی',
        },
    },
])

//----------------------------
</script>

<style scoped>
@import "../assets/css/skeleton/normalize.css";
@import "../assets/css/skeleton/skeleton.css";
</style>
