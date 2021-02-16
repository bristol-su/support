<?php

namespace BristolSU\Support\Search;

use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        config()->set('scout.driver', 'tntsearch');
        config()->set('scout.queue', true);
        config()->set('scout.soft_delete', true);
        config()->set('scout.tntsearch', [
            'storage'  => storage_path(), //place where the index files will be stored
            'fuzziness' => env('TNTSEARCH_FUZZINESS', false),
            'fuzzy' => [
                'prefix_length' => 2,
                'max_expansions' => 50,
                'distance' => 2
            ],
            'asYouType' => false,
            'searchBoolean' => env('TNTSEARCH_BOOLEAN', false),
            'maxDocs' => env('TNTSEARCH_MAX_DOCS', 500),
        ]);
    }

}
