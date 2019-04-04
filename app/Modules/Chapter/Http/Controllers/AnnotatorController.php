<?php

namespace App\Modules\Chapter\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Chapter\Annotation;
use App\Modules\Chapter\Chapter;
use DB;
use Illuminate\Http\Request;
use Storage;

class AnnotatorController extends Controller
{
    public function view(Request $request)
    {
        $chapter = Chapter::all();
        return view('chapter.annotator', compact('chapter'));
    }

    public function search(Request $request)
    {

        $annotations = Annotation::where('page_id', $request->get('page'))->get();
        foreach ($annotations as $value) {
            $annotation[] = [
                'ranges' => $value->ranges,
                'quote' => $value->quote,
                'text' => $value->text,
                'page' => $value->page
            ];

        }
        /*print_r($annotation);
        exit();
        $annotation = [
            'ranges' => $data['ranges'],
            'quote' => $data['quote'],
            'text' => $data['text'],
            'page_id' => $data['page']
        ];*/
        return response()->json(['total' => count($annotation), 'rows' => $annotation]);
    }

    public function store(Request $request)
    {

//        $data = json_decode($request->all(), true);
        $data = $request->all();

        $annotation = [
            'ranges' => $data['ranges'],
            'quote' => $data['quote'],
            'text' => $data['text'],
            'page_id' => $data['page']
        ];

        if ($id = Annotation::create($annotation)) {
            return response()->json(['status' => 'success', 'id' => $id]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }


    public function update($id, Request $request)
    {
        $annotation = Annotation::find($id);
        if ($annotation) {
            $data = json_decode($request->getContent(), true);
            $annotation->ranges = $data['ranges'];
            $annotation->quote = $data['quote'];
            $annotation->text = $data['text'];
            $annotation->page_id = $data['page_id'];

            if ($annotation->save()) {
                return response()->json(['status' => 'success']);
            }
        }

        return response()->json(['status' => 'error']);
    }

}
