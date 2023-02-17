<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Example;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class ExampleIndex extends Component
{

    private $examples;

    protected $queryString = [
        'searchedEn' => ['except' => '', 'as' => 'en'],
    ];



    public $searchedEn = '';



    // The searched en string is cleared.
    public function resetSearchedEn()
    {
        $this->searchedEn = '';
    }



    public function render()
    {
        $this->examples = DB::table('examples');

        // If something was searched, results are filtered.
        if ($this->searchedEn != '') {
            $this->examples = $this->examples
                ->where('en', 'like', '%'.$this->searchedEn.'%');
        }

        return view('livewire.example-index', [
            'examples' => $this->examples->paginate(10)
        ]);
    }

}
