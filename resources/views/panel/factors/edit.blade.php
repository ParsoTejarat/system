@extends('panel.layouts.master')
@section('title', 'ویرایش فاکتور')
@section('styles')
    <style>
        #products_table input, #products_table select{
            width: auto;
        }

        #other_products_table input, #other_products_table select{
            width: auto;
        }
    </style>
@endsection
@section('content')
    {{--  discount Modal  --}}
        <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="discountModalLabel">اعمال کد تخفیف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="بستن">
                            <i class="ti-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="code" class="col-form-label"> کد تخفیف:<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="code">
                                <div class="invalid-feedback d-flex" id="error_div"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="button" class="btn btn-primary" id="btn_apply_discount">اعمال</button>
                    </div>
                </div>
            </div>
        </div>
    {{--  end discount Modal  --}}

    <div class="card">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between align-items-center">
                <h6>ویرایش فاکتور</h6>
                <a href="{{ route('invoices.show', [$factor->invoice->id, 'type' => 'factor']) }}" class="btn btn-outline-info"><i class="fa fa-print mr-2"></i>نسخه چاپی </a>
            </div>
            <form action="{{ route('factors.update', $factor->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="col-12 mb-4 text-center">
                        <h4>مشخصات خریدار</h4>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="buyer_name">نام شخص حقیقی/حقوقی <span class="text-danger">*</span></label>
                        <select name="buyer_name" id="buyer_name" class="js-example-basic-single select2-hidden-accessible" data-select2-id="6" tabindex="-3" aria-hidden="true" disabled>
                            @foreach(\App\Models\Customer::all(['id','name']) as $customer)
                                <option value="{{ $customer->id }}" {{ $factor->invoice->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        @error('buyer_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="economical_number">شماره اقتصادی@can('system-user')<span class="text-danger">*</span>@endcan</label>
                        <input type="text" name="economical_number" class="form-control" id="economical_number" value="{{ $factor->invoice->economical_number }}">
                        @error('economical_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="national_number">شماره ثبت/ملی<span class="text-danger">*</span></label>
                        <input type="text" name="national_number" class="form-control" id="national_number" value="{{ $factor->invoice->national_number }}">
                        @error('national_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="postal_code">کد پستی<span class="text-danger">*</span></label>
                        <input type="text" name="postal_code" class="form-control" id="postal_code" value="{{ $factor->invoice->postal_code }}">
                        @error('postal_code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="phone">شماره تماس<span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" id="phone" value="{{ $factor->invoice->phone }}">
                        @error('phone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="province">استان <span class="text-danger">*</span></label>
                        <select name="province" id="province" class="js-example-basic-single select2-hidden-accessible" data-select2-id="4" tabindex="-1" aria-hidden="true">
                            @foreach(\App\Models\Province::all() as $province)
                                <option value="{{ $province->name }}" {{ $factor->invoice->province == $province->name ? 'selected' : '' }}>{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('province')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="city">شهر<span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control" id="city" value="{{ $factor->invoice->city }}">
                        @error('city')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="address">نشانی<span class="text-danger">*</span></label>
                        <textarea name="address" id="address" class="form-control">{{ $factor->invoice->address }}</textarea>
                        @error('address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3 mb-3">
                        <label for="status">وضعیت <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="js-example-basic-single select2-hidden-accessible" data-select2-id="5" tabindex="-2" aria-hidden="true">
                            <option value="invoiced" {{ $factor->status == 'invoiced' ? 'selected' : '' }}>{{ \App\Models\Factor::STATUS['invoiced'] }}</option>
                            <option value="paid" {{ $factor->status == 'paid' ? 'selected' : '' }}>{{ \App\Models\Factor::STATUS['paid'] }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-4 mt-2 text-center">
                        <hr>
                        <h4>مشخصات کالا یا خدمات مورد معامله</h4>
                    </div>
                    <div class="col-12 mt-2 text-center">
                        <h5>محصولات آرتین</h5>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between mb-3">
                            <button class="btn btn-outline-success" type="button" id="btn_add"><i class="fa fa-plus mr-2"></i> افزودن کالا</button>
                        </div>
                        <div class="overflow-auto">
                            <table class="table table-bordered table-striped text-center" id="products_table">
                                <thead>
                                <tr>
                                    <th>کالا</th>
                                    <th>رنگ</th>
                                    <th>تعداد</th>
                                    <th>واحد اندازه گیری</th>
                                    <th>مبلغ واحد</th>
                                    <th>مبلغ کل</th>
                                    <th>مبلغ تخفیف</th>
                                    <th>مبلغ اضافات</th>
                                    <th>مبلغ کل پس از تخفیف و اضافات</th>
                                    <th>جمع مالیات و عوارض</th>
                                    <th>خالص فاکتور</th>
                                    <th>اعمال تخفیف</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!$factor->invoice->products()->exists())
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <select class="form-control" name="products[]" required>
                                                <option value="" disabled selected>انتخاب کنید...</option>
                                                @foreach(\App\Models\Product::all(['id','title']) as $product)
                                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="colors[]">
                                                @foreach(\App\Models\Product::COLORS as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="counts[]" class="form-control" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <select class="form-control" name="units[]">
                                                <option value="number">عدد</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="prices[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="total_prices[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="discount_amounts[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="extra_amounts[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="total_prices_with_off[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="taxes[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="invoice_nets[]" class="form-control" min="0" value="0" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-floating btn_discount" data-toggle="modal" data-target="#discountModal"><i class="fa fa-percent"></i></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @else
                                    @foreach($factor->invoice->products as $item)
                                        @php
                                            $usedCoupon = DB::table('coupon_invoice')->where([
                                                'product_id' => $item->pivot->product_id,
                                                'invoice_id' => $factor->invoice->id,
                                            ])->first();

                                            if ($usedCoupon){
                                                $coupon = \App\Models\Coupon::find($usedCoupon->coupon_id);
                                                $discount_amount = $item->pivot->total_price * ($coupon->amount_pc / 100);
                                            }else{
                                                $discount_amount = 0;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <select class="form-control" name="products[]" required>
                                                    <option value="" disabled selected>انتخاب کنید...</option>
                                                    @foreach(\App\Models\Product::all(['id','title']) as $product)
                                                        <option value="{{ $product->id }}" {{ $item->pivot->product_id == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="colors[]">
                                                    @foreach(\App\Models\Product::COLORS as $key => $value)
                                                        <option value="{{ $key }}" {{ $item->pivot->color == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="counts[]" class="form-control" min="1" value="{{ $item->pivot->count }}" required>
                                            </td>
                                            <td>
                                                <select class="form-control" name="units[]">
                                                    <option value="{{ $item->pivot->unit }}">{{ \App\Models\Product::UNITS[$item->pivot->unit] }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="prices[]" class="form-control" min="0" value="{{ $item->pivot->price }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="total_prices[]" class="form-control" min="0" value="{{ $item->pivot->total_price }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="discount_amounts[]" class="form-control" min="0" value="{{ $discount_amount }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="extra_amounts[]" class="form-control" min="0" value="{{ $item->pivot->extra_amount }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="total_prices_with_off[]" class="form-control" min="0" value="{{ $item->pivot->total_price - ($item->pivot->extra_amount + $discount_amount) }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="taxes[]" class="form-control" min="0" value="{{ $item->pivot->tax }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="invoice_nets[]" class="form-control" min="0" value="{{ $item->pivot->invoice_net }}" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-floating btn_discount" data-toggle="modal" data-target="#discountModal"><i class="fa fa-percent"></i></button>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 mt-4 text-center">
                        <h5>محصولات دیگر</h5>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between mb-3">
                            <button class="btn btn-outline-success" type="button" id="btn_other_add"><i class="fa fa-plus mr-2"></i> افزودن کالا</button>
                        </div>
                        <div class="overflow-auto">
                            <table class="table table-bordered table-striped text-center" id="other_products_table">
                                <thead>
                                <tr>
                                    <th>کالا</th>
                                    <th>رنگ</th>
                                    <th>تعداد</th>
                                    <th>واحد اندازه گیری</th>
                                    <th>مبلغ واحد</th>
                                    <th>مبلغ کل</th>
                                    <th>مبلغ تخفیف</th>
                                    <th>مبلغ اضافات</th>
                                    <th>مبلغ کل پس از تخفیف و اضافات</th>
                                    <th>جمع مالیات و عوارض</th>
                                    <th>خالص فاکتور</th>
                                    <th>حذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($factor->invoice->other_products()->exists())
                                    @foreach($factor->invoice->other_products as $product)
                                        <tr>
                                            <td>
                                                <input type="text" name="other_products[]" class="form-control" value="{{ $product->title }}" placeholder="عنوان کالا" required>
                                            </td>
                                            <td>
                                                <input type="text" name="other_colors[]" class="form-control" value="{{ $product->color }}" placeholder="نام رنگ" required>
                                            </td>
                                            <td>
                                                <input type="number" name="other_counts[]" class="form-control" min="1" value="{{ $product->count }}" required>
                                            </td>
                                            <td>
                                                <select class="form-control" name="other_units[]">
                                                    <option value="number">عدد</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="other_prices[]" class="form-control" min="0" value="{{ $product->price }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="other_total_prices[]" class="form-control" min="0" value="{{ $product->total_price }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="other_discount_amounts[]" class="form-control" min="0" value="{{ $product->discount_amount }}" required>
                                            </td>
                                            <td>
                                                <input type="number" name="other_extra_amounts[]" class="form-control" min="0" value="{{ $product->extra_amount }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="other_total_prices_with_off[]" class="form-control" min="0" value="{{ $product->total_price - ($product->extra_amount + $product->discount_amount) }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="other_taxes[]" class="form-control" min="0" value="{{ $product->tax }}" readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="other_invoice_nets[]" class="form-control" min="0" value="{{ $product->invoice_net }}" readonly>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">ثبت فرم</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>

        var products = [];
        var colors = [];
        var invoice_id = "{{ $factor->invoice->id }}";

        @foreach(\App\Models\Product::all(['id','title']) as $product)
        products.push({
            "id": "{{ $product->id }}",
            "title": "{{ $product->title }}",
        })
        @endforeach
        @foreach(\App\Models\Product::COLORS as $key => $value)
        colors.push({
            "key": "{{ $key }}",
            "value": "{{ $value }}",
        })
        @endforeach

        var products_options_html = '';
        var colors_options_html = '';

        $.each(products, function (i, item) {
            products_options_html += `<option value="${item.id}">${item.title}</option>`
        })

        $.each(colors, function (i, item) {
            colors_options_html += `<option value="${item.key}">${item.value}</option>`
        })

        $(document).ready(function () {
            // add property
            $('#btn_add').on('click', function () {
                $('#products_table tbody').append(`
                <tr>
                <td>
                    <select class="form-control" name="products[]" required>
                        <option value="" disabled selected>انتخاب کنید...</option>
                        ${products_options_html}
                    </select>
                </td>
                <td>
                    <select class="form-control" name="colors[]" required>
                        ${colors_options_html}
                    </select>
                </td>
                <td>
                    <input type="number" name="counts[]" class="form-control" min="1" value="1" required>
                </td>
                <td>
                    <select class="form-control" name="units[]">
                        <option value="number">عدد</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="prices[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="total_prices[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="discount_amounts[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="extra_amounts[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="total_prices_with_off[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="taxes[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="invoice_nets[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-floating btn_discount" data-toggle="modal" data-target="#discountModal"><i class="fa fa-percent"></i></button>
                </td>
                <td>
                    <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

`);
            })
            // end add property

            // add other property
            $('#btn_other_add').on('click', function () {
                $('#other_products_table tbody').append(`
                <tr>
                <td>
                    <input type="text" class="form-control" name="other_products[]" placeholder="عنوان کالا" required>
                </td>
                <td>
                    <input type="text" class="form-control" name="other_colors[]" placeholder="نام رنگ" required>
                </td>
                <td>
                    <input type="number" name="other_counts[]" class="form-control" min="1" value="1" required>
                </td>
                <td>
                    <select class="form-control" name="other_units[]">
                        <option value="number">عدد</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="other_prices[]" class="form-control" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" name="other_total_prices[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_discount_amounts[]" class="form-control" min="0" value="0" required>
                </td>
                <td>
                    <input type="number" name="other_extra_amounts[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_total_prices_with_off[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_taxes[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <input type="number" name="other_invoice_nets[]" class="form-control" min="0" value="0" readonly>
                </td>
                <td>
                    <button class="btn btn-danger btn-floating btn_remove" type="button"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

`);
            })
            // end add other property

            // remove property
            $(document).on('click','.btn_remove', function () {
                $(this).parent().parent().remove();
            })
            // end remove property

            // calc the product invoice
            $(document).on('change', '#products_table select[name="products[]"]', function () {
                CalcProductInvoice(this)
            })
            $(document).on('change', '#products_table input[name="counts[]"]', function () {
                CalcProductInvoice(this)
            })
            $(document).on('change', '#other_products_table input[name="other_counts[]"]', function () {
                CalcOtherProductInvoice(this)
            })
            $(document).on('change', '#other_products_table input[name="other_prices[]"]', function () {
                CalcOtherProductInvoice(this)
            })
            $(document).on('change', '#other_products_table input[name="other_discount_amounts[]"]', function () {
                CalcOtherProductInvoice(this)
            })
            // end calc the product invoice

            // apply discount on products
            var active_row_index;
            $(document).on('click','.btn_discount', function () {
                active_row_index = $(this).parent().parent().index();
            })

            $(document).on('click','#btn_apply_discount', function () {
                let code = $('#code').val();
                let product_id = $('#products_table select[name="products[]"]')[active_row_index].value;
                let count =  $('#products_table input[name="counts[]"]')[active_row_index].value;

                $.ajax({
                    url: "{{ route('invoices.applyDiscount') }}",
                    type: 'post',
                    data: {
                        code,
                        count,
                        invoice_id,
                        product_id,
                    },
                    success: function (res) {
                        if(res.error){
                            $('#error_div').text(res.message)
                        }else{
                            $('#products_table input[name="discount_amounts[]"]')[active_row_index].value = res.data.discount_amount;
                            $('#products_table input[name="total_prices_with_off[]"]')[active_row_index].value = res.data.total_price_with_off;
                            $('#products_table input[name="taxes[]"]')[active_row_index].value = res.data.tax;
                            $('#products_table input[name="invoice_nets[]"]')[active_row_index].value = res.data.invoice_net;

                            Swal.fire({
                                title: res.message,
                                icon: 'success',
                                showConfirmButton: false,
                                toast: true,
                                timer: 2000,
                                timerProgressBar: true,
                                position: 'top-start',
                                customClass: {
                                    popup: 'my-toast',
                                    icon: 'icon-center',
                                    title: 'left-gap',
                                    content: 'left-gap',
                                }
                            })

                            $('#discountModal').modal('hide')
                        }
                    }
                })
            })
            // end apply discount on products

            // get customer info
            $(document).on('change', 'select[name="buyer_name"]', function () {
                let customer_id = this.value;

                $.ajax({
                    url: '/panel/get-customer-info/'+customer_id,
                    type: 'post',
                    success: function(res) {
                        $('#economical_number').val(res.data.economical_number)
                        $('#national_number').val(res.data.national_number)
                        $('#postal_code').val(res.data.postal_code)
                        $('#phone').val(res.data.phone1)
                        $('#address').val(res.data.address1)
                        $('#province').val(res.data.province).trigger('change');
                        $('#city').val(res.data.city)
                    }
                })
            })
            // end get customer info
        })

        function CalcProductInvoice(changeable) {
            var index = $(changeable).parent().parent().index()
            let product_id =  $('#products_table select[name="products[]"]')[index].value;
            let count =  $('#products_table input[name="counts[]"]')[index].value;

            $.ajax({
                url: "{{ route('calcProductsInvoice') }}",
                type: 'post',
                data: {
                    'invoice_id': invoice_id,
                    'product_id': product_id,
                    'count': count,
                },
                success: function (res) {
                    $('#products_table input[name="prices[]"]')[index].value = res.data.price;
                    $('#products_table input[name="total_prices[]"]')[index].value = res.data.total_price;
                    $('#products_table input[name="discount_amounts[]"]')[index].value = res.data.discount_amount;
                    $('#products_table input[name="extra_amounts[]"]')[index].value = res.data.extra_amount;
                    $('#products_table input[name="total_prices_with_off[]"]')[index].value = res.data.total_price_with_off;
                    $('#products_table input[name="taxes[]"]')[index].value = res.data.tax;
                    $('#products_table input[name="invoice_nets[]"]')[index].value = res.data.invoice_net;
                }
            })
        }

        function CalcOtherProductInvoice(changeable) {
            var index = $(changeable).parent().parent().index()
            let count =  $('#other_products_table input[name="other_counts[]"]')[index].value;
            let price = $('#other_products_table input[name="other_prices[]"]')[index].value;
            let discount_amount = $('#other_products_table input[name="other_discount_amounts[]"]')[index].value;

            $.ajax({
                url: "{{ route('calcOtherProductsInvoice') }}",
                type: 'post',
                data: {
                    'price': price,
                    'count': count,
                    'discount_amount': discount_amount,
                },
                success: function (res) {
                    $('#other_products_table input[name="other_prices[]"]')[index].value = res.data.price;
                    $('#other_products_table input[name="other_total_prices[]"]')[index].value = res.data.total_price;
                    $('#other_products_table input[name="other_discount_amounts[]"]')[index].value = res.data.discount_amount;
                    $('#other_products_table input[name="other_extra_amounts[]"]')[index].value = res.data.extra_amount;
                    $('#other_products_table input[name="other_total_prices_with_off[]"]')[index].value = res.data.total_price_with_off;
                    $('#other_products_table input[name="other_taxes[]"]')[index].value = res.data.tax;
                    $('#other_products_table input[name="other_invoice_nets[]"]')[index].value = res.data.invoice_net;
                }
            })
        }


    </script>
@endsection
