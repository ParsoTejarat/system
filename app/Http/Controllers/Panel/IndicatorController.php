<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF as PDF;

class IndicatorController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        return view('panel.indicator.create');
    }

    public function store(Request $request)
    {
        dd($request->all());
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function exportToPdf(Request $request)
    {
        $title = $request->title;
        $text = $request->text;
        $date = $request->date ?? '';
        $number = $request->number ?? '';
        $attachment = $request->attachment ?? '';


//       return exportPdfInfoPersian($title, $text, $date, $number, $attachment);
//       return exportPdfEnglish($title, $text, $date, $number, $attachment);
//       return exportPdfSalePersian($title, $text, $date, $number, $attachment);
    }



}
