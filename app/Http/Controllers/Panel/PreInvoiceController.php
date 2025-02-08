<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Exception;
use App\Http\Requests\PreInvoiceRequest;
use App\Models\Holding;
use App\Models\PreInvoice;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use PDF as PDF;


class PreInvoiceController extends Controller
{

    public function index()
    {
        $pre_invoices = PreInvoice::query();
        if ($code = request()->query('invoice_number')) {
            $pre_invoices->where('invoice_number', 'like', '%' . $code . '%');
        }
        $pre_invoices = $pre_invoices->latest()->paginate(30);
        return view('panel.pre_invoice.index', compact(['pre_invoices']));
    }


    public function create()
    {
        return view('panel.pre_invoice.create');
    }


    public function store(PreInvoiceRequest $request)
    {
//        return $request->all();
        $data = [];
        if ($request->other_products) {
            foreach ($request->other_products as $key => $product) {
                $obj = (object)[
                    'product' => $product,
                    'color' => $request->other_colors[$key],
                    'count' => $request->other_counts[$key],
                    'unit' => $request->other_units[$key],
                    'prices' => $request->other_prices[$key],
                    'total_prices' => $request->other_total_prices[$key],
                    'discount_amounts' => $request->other_discount_amounts[$key],
                    'extra_amounts' => $request->other_extra_amounts[$key],
                    'total_prices_with_off' => $request->other_total_prices_with_off[$key],
                    'taxes' => $request->other_taxes[$key],
                    'invoice_nets' => $request->other_invoice_nets[$key],
                ];
                $data[] = $obj;
            }
        }
        $products_encoded = json_encode($data);

        $preInvoice = new PreInvoice();
        $preInvoice->invoice_number = $request->code;
        $preInvoice->customer_name = $request->buyer_name;
        $preInvoice->commercial_code = $request->economical_number;
        $preInvoice->national_code = $request->need_no;
        $preInvoice->need_no = $request->need_no;
        $preInvoice->holding_id = $request->holding_id;
        $preInvoice->zip_code = $request->postal_code;
        $preInvoice->phone_number = $request->phone;
        $preInvoice->province = $request->province;
        $preInvoice->city = $request->city;
        $preInvoice->address = $request->address;
        $preInvoice->description = $request->description;
        $preInvoice->products = $products_encoded;
        $preInvoice->user_id = auth()->id();
        $preInvoice->save();
        activity_log('create-pre-invoice', __METHOD__, [$request->all(), $preInvoice]);
        alert()->success('فایل پیش فاکتور با موفقیت اضافه شد.', 'موفقیت آمیز');

        return redirect()->route('panel.pre-invoice.index');


    }

    public function show($id)
    {
        $invoice = PreInvoice::where('id', $id)->first();
        return view('panel.pre_invoice.printable', compact(['invoice']));

    }


    public function edit($id)
    {
        $invoice = PreInvoice::where('id', $id)->first();
        return view('panel.pre_invoice.edit', compact(['invoice']));
    }


    public function update(PreInvoiceRequest $request, $id)
    {
        $data = [];
        $preInvoice = PreInvoice::where('id', $id)->first();


        if ($request->other_products) {
            foreach ($request->other_products as $key => $product) {
                $obj = (object)[
                    'product' => $product,
                    'color' => $request->other_colors[$key],
                    'count' => $request->other_counts[$key],
                    'unit' => $request->other_units[$key],
                    'prices' => $request->other_prices[$key],
                    'total_prices' => $request->other_total_prices[$key],
                    'discount_amounts' => $request->other_discount_amounts[$key],
                    'extra_amounts' => $request->other_extra_amounts[$key],
                    'total_prices_with_off' => $request->other_total_prices_with_off[$key],
                    'taxes' => $request->other_taxes[$key],
                    'invoice_nets' => $request->other_invoice_nets[$key],
                ];
                $data[] = $obj;
            }
        }
        $products_encoded = json_encode($data);
        $preInvoice->invoice_number = $request->code;
        $preInvoice->customer_name = $request->buyer_name;
        $preInvoice->commercial_code = $request->economical_number;
        $preInvoice->national_code = $request->need_no;
        $preInvoice->need_no = $request->need_no;
        $preInvoice->zip_code = $request->postal_code;
        $preInvoice->holding_id = $request->holding_id;
        $preInvoice->phone_number = $request->phone;
        $preInvoice->province = $request->province;
        $preInvoice->city = $request->city;
        $preInvoice->address = $request->address;
        $preInvoice->description = $request->description;
        $preInvoice->products = $products_encoded;
        $preInvoice->save();
        activity_log('edit-pre-invoice', __METHOD__, [$request->all(), $preInvoice]);
        alert()->success('فایل پیش فاکتور با موفقیت ویرایش شد.', 'موفقیت آمیز');
        return redirect()->route('pre-invoices.index');

    }


    public function destroy($id)
    {
        $preInvoice = PreInvoice::where('id', $id)->first();
        $preInvoice->delete();
        activity_log('edit-pre-invoice', __METHOD__, $preInvoice);
        alert()->success('فایل پیش فاکتور با موفقیت حذف شد.', 'موفقیت آمیز');
        return back();
    }

    public function print(Request $request)
    {
//        dd($request->invoice_id);

        $invoice = PreInvoice::whereId($request->invoice_id)->first();
        $company = Holding::where('id', $invoice->holding_id)->first();
        $folder = 'customer_pre_invoices';

        try {
            $pdf = PDF::loadView('panel.pre_invoice.invoice_printable', ['invoice' => $invoice], [], [
                'format' => 'A3',
                'orientation' => 'L',
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => 2,
                'margin_bottom' => 0,
            ]);

            $tempPdfPath = storage_path('app/public/temp_invoice.pdf');
            $pdf->save($tempPdfPath);

            $mpdf = new Mpdf([
                'tempDir' => storage_path('app/mpdf-temp'),
                'format' => 'A3',
            ]);

            $pageCount = $mpdf->SetSourceFile($tempPdfPath);
            $imagePath = public_path(str_replace('\\', '/', $company->stamp));

            list($imgWidth, $imgHeight) = getimagesize($imagePath);
            $imgWidthMm = $imgWidth * 0.264583;
            $imgHeightMm = $imgHeight * 0.264583;

            $x = 350 - $imgWidthMm;
            $y = 220 - $imgHeightMm;

            for ($i = 1; $i <= $pageCount; $i++) {
                $templateId = $mpdf->ImportPage($i);
                $mpdf->AddPage('L');
                $mpdf->UseTemplate($templateId);

                if ($i == $pageCount) {
                    $mpdf->Image($imagePath, $x, $y, $imgWidthMm, $imgHeightMm);
                }
            }

            $filename = make_slug(time()) . '-processed.pdf';
            $outputPdfPath = storage_path('app/public/' . $filename);

            $mpdf->Output($outputPdfPath, 'F');

            unlink($tempPdfPath);

            return response()->download($outputPdfPath, $filename)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            alert()->warning('خطا در ایجاد PDF', 'خطا');
            return redirect()->to(route('invoices.index'));
        }





//        $pdf = PDF::loadView('panel.pre_invoice.invoice_printable', ['invoice' => $invoice], [], [
//            'format' => 'A3',
//            'orientation' => 'L',
//            'margin_left' => 2,
//            'margin_right' => 2,
//            'margin_top' => 2,
//            'margin_bottom' => 0,
//        ]);
//
//        return response($pdf->output(), 200)
//            ->header('Content-Type', 'application/pdf')
//            ->header('Content-Disposition', 'attachment; filename="pre_invoice_' . make_slug(verta(now())) . '.pdf"');
    }
}
