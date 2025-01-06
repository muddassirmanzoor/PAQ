<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Schools;
use App\Models\SchoolVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a form of the assessment.
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Submit Assessment.
     */
    public function submitAssessment(Request $request): RedirectResponse
    {
        $emiscode = Session::get('emiscode');
        if($emiscode == null){
            return redirect()->intended('/');
        }
        $visit_data = SchoolVisit::where('emiscode', Session::get('emiscode'))->first();
        if($visit_data){
            return redirect()->intended('show-assessment');
        }
        $data = $request->all();
        $filePath = '';
        if($request->file()) {
            $request->validate([
                'link' => 'max:10240',
            ],['link.max' => 'The file size must not exceed 10 MB.',]
            );
            $fileName = $emiscode.'_'.$request->link->getClientOriginalName();
            $filePath = $request->file('link')->storeAs('uploads', $fileName, 'public');
        }

        foreach ($data['data'] as  $inner){
            SchoolVisit::create([
                'emiscode' => $emiscode,
                'class' => $inner['class'],
                'subject' => $inner['subject'],
                'qty_received' => $inner['qty_received'],
                'useable' => $inner['useable'],
                'unuseable' => $inner['unuseable'],
                'head_name' => $data['head_name'],
                'head_mobile_no' => $data['head_mobile_no'],
                'link' => '/storage/' . $filePath,
            ]);

        }

        return redirect()->intended('show-assessment');

    }

    /**
     * Display of the assessment.
     */
    public function showAssessment()
    {
        $emiscode = Session::get('emiscode');

        if($emiscode == null){
            return redirect()->intended('/');
        }
        $visit_data = SchoolVisit::where('emiscode', Session::get('emiscode'))->get();
        $school_data = Schools::where('emiscode', Session::get('emiscode'))->first();

        if(count($visit_data) > 0){
            $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];

            $explodeImage = explode('.', $visit_data[0]->link);
            $extension = end($explodeImage);

            $image = false;
            if(in_array($extension, $imageExtensions)){
                $image = true;
            }
            return view('show-assessment', compact('visit_data', 'school_data','image'));
        }else{
            return redirect()->intended('assessment');

        }
    }

    /**
     * Display edit form of the assessment.
     */
    public function editAssessment()
    {
        $emiscode = Session::get('emiscode');

        if($emiscode == null){
            return redirect()->intended('/');
        }
        $visit_data = SchoolVisit::where('emiscode', Session::get('emiscode'))->get();
        $school_data = Schools::where('emiscode', Session::get('emiscode'))->first();

        if(count($visit_data) > 0){
            return view('edit-assessment', compact('visit_data', 'school_data'));
        }
    }

    /**
     * Update Assessment.
     */
    public function updateAssessment(Request $request): RedirectResponse
    {
        $emiscode = Session::get('emiscode');
        if($emiscode == null){
            return redirect()->intended('/');
        }
        $data = $request->all();
        $filePath = '';
        if($request->file()) {
            $request->validate([
                'link' => 'max:10240',
            ],['link.max' => 'The file size must not exceed 10 MB.',]
            );
            $fileName = $emiscode.'_'.$request->link->getClientOriginalName();
            $filePath = $request->file('link')->storeAs('uploads', $fileName, 'public');
        }

        foreach ($data['data'] as $key=> $inner){
            $visit_data = SchoolVisit::where('emiscode', Session::get('emiscode'))->where('class', $inner['class'])->first();

            $visit_data->qty_received = $inner['qty_received'];
            $visit_data->useable = $inner['useable'];
            $visit_data->unuseable = $inner['unuseable'];
            $visit_data->head_name = $data['head_name'];
            $visit_data->head_mobile_no = $data['head_mobile_no'];
            if($request->file()) {
                $visit_data->link = '/storage/' . $filePath;
            }
            $visit_data->save();
        }

        return redirect()->intended('show-assessment');

    }
    /**
     * Display report of the assessment.
     */
    public function reportAssessment()
    {
        $emiscode = Session::get('emiscode');

        if(!$emiscode == '4432111'){
            return redirect()->intended('/');
        }
        $total_submitted = SchoolVisit::select('emiscode')->distinct('emiscode')->count();
        $school_data = new \stdClass();
        $school_data->emiscode = '4432111';
        return view('report-assessment', compact('total_submitted','school_data'));
    }

    public function downloadAssessment(){
        $emiscode = Session::get('emiscode');

        if(!$emiscode == '4432111'){
            return redirect()->intended('/');
        }
        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=abc.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        );

        $filename = "emiscode.csv";
        $handle = fopen($filename, 'w');
        fputcsv($handle, [
            "emiscode","class", "subject", "Quantity Received", "Use able", "Shaheed"
        ]);

        DB::table("school_visit")->orderBy('id')->chunk(1000, function ($data) use ($handle) {
            foreach ($data as $row) {
                // Add a new row with data
                fputcsv($handle, [
                    $row->emiscode,
                    $row->class,
                    $row->subject,
                    $row->qty_received,
                    $row->useable,
                    $row->unuseable,
                ]);
            }
        });

        fclose($handle);

        return Response::download($filename, "emiscode.csv", $headers);
    }
}
