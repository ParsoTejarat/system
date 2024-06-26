<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndicatorRequest;
use App\Models\Indicator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF as PDF;

class IndicatorController extends Controller
{


    public function index()
    {
        $indicators = Indicator::where('user_id', auth()->id())->latest()->paginate(30);
        return view('panel.indicator.index', compact(['indicators']));
    }


    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('panel.indicator.create', compact(['users']));
    }

    public function store(StoreIndicatorRequest $request)
    {

        $Indicator = new Indicator();
        $Indicator->title = $request->title;
        $Indicator->date = $request->date;
        $Indicator->number = $request->number;
        $Indicator->attachment = $request->attachment;
        $Indicator->header = $request->header;
        $Indicator->text = $request->text;
        $Indicator->user_id = auth()->id();
        $Indicator->save();
        $Indicator->users()->sync($request->receiver);
        alert()->success('نامه مورد نظر با موفقیت ثبت شد', 'ثبت نامه');
        return redirect()->route('indicator.index');
    }


    public function show($id)
    {
        //
    }


    public function edit(Indicator $indicator)
    {
        $this->authorize('edit-indicator', $indicator);
        return view('panel.indicator.edit', compact(['indicator']));

    }


    public function update(StoreIndicatorRequest $request, Indicator $indicator)
    {
        $this->authorize('edit-indicator', $indicator);
        $indicator->title = $request->title;
        $indicator->date = $request->date;
        $indicator->number = $request->number;
        $indicator->attachment = $request->attachment;
        $indicator->header = $request->header;
        $indicator->text = $request->text;
        $indicator->save();
        alert()->success('نامه مورد نظر با موفقیت ویرایش شد', 'ویرایش نامه');
        return redirect()->route('indicator.index');
    }


    public function destroy(Indicator $indicator)
    {
        if ($indicator->users()->exists()){
//            return response('این نامه به ',422);
        }
        $indicator->delete();
        return back();
    }


    //export section
    public function exportToPdf(StoreIndicatorRequest $request)
    {
        $title = $request->title;
        $text = $request->text;
        $date = $request->date ?? '';
        $number = $request->number ?? '';
        $header = $request->header ?? '';
        $attachment = $request->attachment ?? '';


        if ($header == 'info') {
            return exportPdfInfoPersian($title, $text, $date, $number, $attachment);
        } elseif ($header == 'sale') {
            return exportPdfSalePersian($title, $text, $date, $number, $attachment);
        }
        return exportPdfEnglish($title, $text, $date, $number, $attachment);
    }

    public function downloadFromIndicator($id)
    {
        $indicator = Indicator::whereId($id)->first();
        if ($indicator->header == 'info') {
            return exportPdfInfoPersian($indicator->title, $indicator->text, $indicator->date, $indicator->number, $indicator->attachment);
        } elseif ($indicator->header == 'sale') {
            return exportPdfSalePersian($indicator->title, $indicator->text, $indicator->date, $indicator->number, $indicator->attachment);
        }
        return exportPdfEnglish($indicator->title, $indicator->text, $indicator->date, $indicator->number, $indicator->attachment);
    }


    public function inbox()
    {
        $inbox = auth()->user()->indicators()->paginate(30);
        return view('panel.indicator.inbox', compact(['inbox']));
    }


}
