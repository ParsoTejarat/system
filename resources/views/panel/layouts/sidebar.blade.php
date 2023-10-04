<!-- begin::navigation -->
<div class="navigation">
    <div class="navigation-icon-menu">
        <ul>
            <li class="active" data-toggle="tooltip" title="داشبورد">
                <a href="#navigationDashboards" title="داشبوردها">
                    <i class="icon ti-pie-chart"></i>
                    <span class="badge badge-warning">2</span>
                </a>
            </li>
            <li data-toggle="tooltip" title="برنامه ها">
                <a href="#navigationApps" title="برنامه ها">
                    <i class="icon ti-package"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="پلاگین ها">
                <a href="#navigationPlugins">
                    <i class="icon ti-brush-alt"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="عناصر">
                <a href="#navigationElements">
                    <i class="icon ti-layers"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="صفحات">
                <a href="#navigationPages" title="صفحات کاربری">
                    <i class="icon ti-user"></i>
                </a>
            </li>
        </ul>
        <ul>
            <li data-toggle="tooltip" title="ویرایش پروفایل">
                <a href="#" class="go-to-page">
                    <i class="icon ti-settings"></i>
                </a>
            </li>
            <li data-toggle="tooltip" title="خروج">
                <a href="{{ route('logout') }}" class="go-to-page" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="icon ti-power-off"></i>
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
    <div class="navigation-menu-body">
        <ul id="navigationDashboards" class="navigation-active">
            <li class="navigation-divider">داشبورد</li>
            <li>
                <a class="active" href="index.html">فروش و مدیریت مشتری</a>
            </li>
            <li>
                <a href="dashboard-two.html">پشتیبانی <span class="badge badge-warning">2</span></a>
            </li>
            <li>
                <a href="dashboard-three.html">آمار وب سایت </a>
            </li>
            <li class="navigation-divider">سایر</li>
            <li>
                <a href="widgets.html">ویجت های بیشتر</a>
            </li>
            <li class="navigation-divider">خلاصه</li>
            <li>
                <a href="#" class="mb-2">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="icon-block bg-warning text-white mr-3">
                                <i class="ti-bar-chart"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-size-13 line-height-22 primary-font m-b-5">مجموع فروش</h6>
                            <h4 class="m-b-0 primary-font font-weight-bold line-height-30">15,687</h4>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="mb-2">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="icon-block bg-success text-white mr-3">
                                <i class="ti-email"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-size-13 line-height-22 primary-font m-b-5">مجموع تیکت ها</h6>
                            <h4 class="m-b-0 primary-font font-weight-bold line-height-30">214</h4>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="icon-block bg-info text-white mr-3">
                                <i class="ti-user"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-size-13 line-height-22 primary-font m-b-5">بازدید کنندگان</h6>
                            <h4 class="m-b-0 primary-font font-weight-bold line-height-30">30,313</h4>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
        <ul id="navigationApps">
            <li class="navigation-divider">اپ ها</li>
            <li>
                <a href="chat.html">گفتگو</a>
            </li>
            <li>
                <a href="inbox.html">ایمیل</a>
            </li>
            <li>
                <a href="calendar.html">تقویم</a>
            </li>
            <li>
                <a href="gallery.html">گالری</a>
            </li>
            <li class="navigation-divider">دوستان</li>
            <li>
                <a href="#" class="d-flex">
                    <div>
                        <figure class="avatar avatar-state-success avatar-xs mr-3">
                            <span class="avatar-title bg-warning rounded-circle">ت</span>
                        </figure>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="m-b-0 d-flex font-weight-normal justify-content-between primary-font">مری جین</h6>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="d-flex">
                    <div>
                        <figure class="avatar avatar-state-warning avatar-xs mr-3">
                            <img src="assets/media/image/avatar.jpg" class="rounded-circle" alt="image">
                        </figure>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="m-b-0 d-flex font-weight-normal justify-content-between primary-font">جانی دپ</h6>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="d-flex">
                    <div>
                        <figure class="avatar avatar-state-danger avatar-xs mr-3">
                            <span class="avatar-title bg-info rounded-circle">آ</span>
                        </figure>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="m-b-0 d-flex font-weight-normal justify-content-between primary-font">تونی استارک</h6>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="d-flex">
                    <div>
                        <figure class="avatar avatar-state-info avatar-xs mr-3">
                            <span class="avatar-title bg-danger rounded-circle">ک</span>
                        </figure>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="m-b-0 d-flex font-weight-normal justify-content-between primary-font">استیو راجرز</h6>
                    </div>
                </a>
            </li>
        </ul>
        <ul id="navigationPlugins">
            <li class="navigation-divider">پلاگین ها</li>
            <li><a href="sweet-alert.html">هشدار Sweet </a></li>
            <li><a href="lightbox.html">لایت باکس </a></li>
            <li><a href="toast.html">توست </a></li>
            <li><a href="tour.html">تور </a></li>
            <li><a href="slick-slide.html">اسلاید Slick </a></li>
            <li><a href="nestable.html">لیست تو در تو </a></li>
            <li>
                <a href="#">نمودار ها</a>
                <ul>
                    <li><a href="chart-apex.html">Apex</a></li>
                    <li><a href="chartjs.html">Chartjs</a></li>
                    <li><a href="chart-justgage.html">Justgage</a></li>
                    <li><a href="chart-morris.html">Morris</a></li>
                    <li><a href="chart-peity.html">Peity</a></li>
                </ul>
            </li>
            <li>
                <a href="#">نقشه ها</a>
                <ul>
                    <li><a href="google-map.html">نقشه گوگل</a></li>
                    <li><a href="vector-map.html">نقشه وکتور</a></li>
                </ul>
            </li>
        </ul>
        <ul id="navigationElements">
            <li class="navigation-divider">عناصر</li>
            <li>
                <a href="#">پایه</a>
                <ul>
                    <li><a href="alerts.html">اعلان‌ها </a></li>
                    <li><a href="badge.html">نشان </a></li>
                    <li><a href="buttons.html">دکمه ها </a></li>
                    <li><a href="pagination.html">صفحه‌بندی </a></li>
                    <li><a href="dropdown.html">منوی کشویی </a></li>
                    <li><a href="accordion.html">باز و بسته شونده </a></li>
                    <li><a href="carousel.html">اسلایدر </a></li>
                    <li><a href="typography.html">تایپوگرافی </a></li>
                    <li><a href="list-group.html">گروه لیست </a></li>
                    <li><a href="media-object.html">رسانه </a></li>
                    <li><a href="images.html">تصاویر </a></li>
                    <li><a href="progress.html">پیشرفت </a></li>
                    <li><a href="modal.html">مودال </a></li>
                    <li><a href="spinners.html">چرخنده ها </a></li>
                    <li><a href="navs.html">ناوبری ها </a></li>
                    <li><a href="tab.html">تب </a></li>
                    <li><a href="tooltip.html">راهنما (تولتیپ) </a></li>
                    <li><a href="popovers.html">پاپ اور </a></li>
                </ul>
            </li>
            <li>
                <a href="#">فرم ها</a>
                <ul>
                    <li><a href="basic-form.html">فرم پایه </a></li>
                    <li><a href="custom-form.html">فرم سفارشی </a></li>
                    <li><a href="advanced-form.html">فرم پیشرفته </a></li>
                    <li><a href="datepicker.html">انتخاب گر تاریخ </a></li>
                    <li><a href="timepicker.html">انتخاب گر زمان </a></li>
                    <li><a href="colorpicker.html">انتخاب گر رنگ </a></li>
                    <li><a href="form-validation.html">اعتبارسنجی فرم </a></li>
                    <li><a href="form-wizard.html">فرم مرحله ای </a></li>
                    <li><a href="file-upload.html">آپلود فایل </a></li>
                    <li><a href="#">ویرایشگر CK</a>
                        <ul>
                            <li><a href="ckeditor-article.html">ویرایشگر مقاله </a></li>
                            <li><a href="ckeditor-inline.html">ویرایشگر درون خطی </a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="tables.html">جدول‌ها</a>
                <ul>
                    <li>
                        <a href="tables.html">جدول‌های پایه </a>
                    </li>
                    <li>
                        <a href="data-table.html">جدول اطلاعات </a>
                    </li>
                    <li>
                        <a href="responsive-table.html">جدول واکنشگرا </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">کارت ها </a>
                <ul>
                    <li><a href="basic-cards.html">کارت های پایه </a></li>
                    <li><a href="image-cards.html">کارت های تصویری </a></li>
                    <li><a href="card-scroll.html">کارت های اسکرول دار </a></li>
                    <li><a href="other-cards.html">سایر </a></li>
                </ul>
            </li>
            <li><a href="colors.html">رنگ ها </a></li>
            <li>
                <a href="avatar.html">آواتار ها</a>
            </li>
            <li>
                <a href="icons.html">آیکن‌ها</a>
            </li>
        </ul>
        <ul id="navigationPages">
            <li class="navigation-divider">صفحات</li>
            <li><a href="profile.html">پروفایل </a></li>
            <li><a href="timeline.html">خط زمانی </a></li>
            <li><a href="invoice.html">صورتحساب </a></li>
            <li><a href="pricing-table.html">جداول قیمت ها </a></li>
            <li><a href="search-result.html">نتایج جستجو </a></li>
            <li><a href="login.html">ورود </a></li>
            <li><a href="register.html">ثبت نام </a></li>
            <li><a href="recover-password.html">بازیابی رمز عبور </a></li>
            <li><a href="lock-screen.html">قفل صفحه </a></li>
            <li>
                <a href="#">قالب های ایمیل</a>
                <ul>
                    <li><a href="email-template-basic.html">پایه</a></li>
                    <li><a href="email-template-alert.html">هشدار</a></li>
                    <li><a href="email-template-billing.html">صورتحساب</a></li>
                </ul>
            </li>
            <li>
                <a href="#">صفحات خطا </a>
                <ul>
                    <li><a href="404.html">404 </a></li>
                    <li><a href="404-2.html">404 نسخه 2</a></li>
                    <li><a href="503.html">503 </a></li>
                    <li><a href="mean-at-work.html">تعمیرات </a></li>
                </ul>
            </li>
            <li><a href="blank-page.html">صفحه شروع</a></li>
            <li>
                <a href="#">سطح منو</a>
                <ul>
                    <li><a href="#">سطح منو </a>
                        <ul>
                            <li><a href="#">سطح منو </a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- end::navigation -->
