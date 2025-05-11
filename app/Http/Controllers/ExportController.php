<?php

namespace App\Http\Controllers;

use App\Exports\SimpananEkport;
use App\Models\SimpananBerjangka;
use App\Models\SimpananPokok;
use App\Models\SimpananSukarela;
use App\Models\SimpananWajib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportPDF(Request $request)
    {
        $type = $request->get('type');
        $data = $this->getSimpananData($type);
    
        // Render Blade view as HTML
        $html = View::make('exports.simpanan-pdf', compact('data', 'type'))->render();
    
        // Buat instance mPDF
        $mpdf = new \Mpdf\Mpdf();
    
        // Tulis HTML ke PDF
        $mpdf->WriteHTML($html);
    
        // Download
        return response($mpdf->Output("Simpanan_{$type}.pdf", 'D'));
    }


    public function exportExcel(Request $request)
    {
        $type = $request->type;
        $data = match($type) {
            'sukarela' => SimpananSukarela::all(),
            'berjangka' => SimpananBerjangka::all(),
            'wajib' => SimpananWajib::all(),
            'pokok' => SimpananPokok::all(),
            default => collect(),
        };
    
        return Excel::download(new SimpananEkport($data, $type), "simpanan_{$type}.xlsx");
    }
    
    


protected function getSimpananData($type)
{
    switch ($type) {
        case 'sukarela':
            return SimpananSukarela::all();
        case 'berjangka':
            return SimpananBerjangka::all();
        case 'wajib':
            return SimpananWajib::all();
        case 'pokok':
            return SimpananPokok::all();
        default:
            return collect();
    }
}

}
