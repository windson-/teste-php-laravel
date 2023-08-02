<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\Document;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Crawler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $document;

    /**
     * Create a new job instance.
     *
     * @param array $document
     * @return void
     */
    public function __construct(array $document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $category = Category::where('name', $this->document['categoria'])->first();

        if (empty($category)) {
            $category = Category::create([
                'name' => $this->document['categoria']
            ]);
        }

        Document::create([
            'category_id' => $category->id,
            'title' => $this->document['titulo'],
            'contents' => $this->document['conteúdo']
        ]);
    }
}
