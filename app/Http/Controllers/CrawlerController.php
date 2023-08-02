<?php

namespace App\Http\Controllers;

use App\Jobs\Crawler;
use App\Models\Document;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CrawlerController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        $documents = Document::all();

        return view('index', compact('jobs', 'documents'));
    }

    public function run()
    {
        $jobs = Job::where('status', 0)->get();
        foreach ($jobs as $j) {
            $json = json_decode(Storage::disk('data')->get($j['file_path']), true);

            collect($json['documentos'])->chunk(10)->each(function ($chunk) {
                foreach ($chunk as $document) {
                    Crawler::dispatch($document);
                }
            });

            $j->status = 1;
            $j->save();
        }

        $message = '';
        $jobsCount = $jobs->count();
        if ($jobsCount > 0) {
            if ($jobsCount === 1) {
                $message = "Foi realizado 1 job";
            } else {
                $message = "Foram realizados {$jobsCount} jobs";
            }
        }

        return redirect('/')->with('message', $message);
    }

    public function add(Request $request)
    {
        $file = $request->file('file');

        if ($file->isValid() && $file->getClientMimeType() === 'application/json') {
            $content = $file->get();
            $json = json_decode($content, true);

            $validator = Validator::make($json, [
                'documentos' => 'required|array',
            ]);

            if ($validator->fails()) {
                return redirect('index')
                    ->withErrors($validator)
                    ->withInput();
            }

            $filename = now()->format('Y-m-d_H-i-s') . '.json';
            Storage::disk('data')->put($filename, $content);

            Job::create([
                'status' => 0,
                'file_path' => $filename
            ]);

            return redirect('/')->with('message', 'Arquivo adicionado a fila');
        }
        return redirect('/')->with('message', 'Arquivo no formato incorreto');
    }
}
