<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use PDF as PDF;

if (!function_exists('active_sidebar')) {
    function active_sidebar(array $items)
    {
        $route = Route::current()->uri;
        $data = [];

        foreach ($items as $value) {
            if ($value == 'panel') {
                $data[] = "panel";
            } else {
                $data[] = "panel/" . $value;
            }
        }
        if (in_array($route, $data)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('make_slug')) {
    function make_slug(string $string)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        return $slug;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, $folder)
    {
        if ($file) {
            $filename = time() . $file->getClientOriginalName();
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $path = public_path("/uploads/{$folder}/{$year}/{$month}/");
            $file->move($path, $filename);
            $img = "/uploads/{$folder}/{$year}/{$month}/" . $filename;
            return $img;
        }
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
//         $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS(int $bodyId, string $to, array $args, array $options = [])
    {
        $url = 'https://console.melipayamak.com/api/send/shared/d131cf0fe6ef4a6cb983308e46836678';
        $data = array('bodyId' => $bodyId, 'to' => $to, 'args' => $args);
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        // Next line makes the request absolute insecure
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        \App\Models\SmsHistory::create([
            'user_id' => auth()->id(),
            'phone' => $to,
            'text' => $options['text'] ?? '',
            'status' => isset($result->recId) ? $result->recId != 11 ? 'sent' : 'failed' : 'failed',
        ]);

        return $result;
    }
}

function englishToPersianNumbers($input)
{
    $persianNumbers = [
        '0' => '۰',
        '1' => '۱',
        '2' => '۲',
        '3' => '۳',
        '4' => '۴',
        '5' => '۵',
        '6' => '۶',
        '7' => '۷',
        '8' => '۸',
        '9' => '۹',
    ];

    return strtr($input, $persianNumbers);
}


function exportPdfInfoPersian($title, $text, $date, $number, $attachment)
{

    $backgroundImage = public_path('/assets/images/persian-header-info.png');

    $pdf = PDF::loadView('panel.indicator.indicator-header-info-persian-pdf', ['text' => $text, 'date' => $date, 'number' => $number, 'attachment' => $attachment], [], [
        'format' => 'A4',
        'orientation' => 'P',
        'default_font_size' => '10',
        'default_font' => extractName($text),
        'display_mode' => 'fullpage',
        'watermark_text_alpha' => 1,
        'watermark_image_path' => $backgroundImage,
        'watermark_image_alpha' => 1,
        'watermark_image_size' => [210, 297],
        'show_watermark_image' => true,
        'watermarkImgBehind' => true,
    ]);


    return $pdf->stream($title . ".pdf");
}


function exportPdfSalePersian($title, $text, $date, $number, $attachment)
{

    $backgroundImage = public_path('/assets/images/persian-header-sale.png');

    $pdf = PDF::loadView('panel.indicator.indicator-header-sale-persian-pdf', ['text' => $text, 'date' => $date, 'number' => $number, 'attachment' => $attachment], [], [
        'format' => 'A4',
        'orientation' => 'P',
        'default_font_size' => '10',
        'default_font' => extractName($text),
        'display_mode' => 'fullpage',
        'watermark_text_alpha' => 1,
        'watermark_image_path' => $backgroundImage,
        'watermark_image_alpha' => 1,
        'watermark_image_size' => [210, 297],
        'show_watermark_image' => true,
        'watermarkImgBehind' => true,
    ]);


    return $pdf->stream($title . ".pdf");
}
function exportPdfEnglish($title, $text, $date, $number, $attachment)
{

    $backgroundImage = public_path('/assets/images/english-header.png');

    $pdf = PDF::loadView('panel.indicator.indicator-header-english-pdf', ['text' => $text, 'date' => $date, 'number' => $number, 'attachment' => $attachment], [], [
        'format' => 'A4',
        'orientation' => 'P',
        'default_font_size' => '10',
        'default_font' => extractName($text),
        'display_mode' => 'fullpage',
        'watermark_text_alpha' => 1,
        'watermark_image_path' => $backgroundImage,
        'watermark_image_alpha' => 1,
        'watermark_image_size' => [210, 297],
        'show_watermark_image' => true,
        'watermarkImgBehind' => true,
    ]);


    return $pdf->stream($title . ".pdf");
}





function extractName($text)
{
    $tempDiv = new \DOMDocument();
    $tempDiv->loadHTML('<?xml encoding="utf-8" ?>' . $text);


    $spanElements = $tempDiv->getElementsByTagName('span');
    $fontFamily = null;

    foreach ($spanElements as $span) {

        $style = $span->getAttribute('style');


        preg_match('/font-family\s*:\s*([^;]+)(;|$)/', $style, $matches);

        if (isset($matches[1])) {
            $fontFamily = trim($matches[1], " '\"");
            break;
        }
    }


    return $fontFamily ?? 'Nazanin';
}
