<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Example;

use Illuminate\Support\Facades\Log;

class ExampleIndex extends Component
{

    private $examples;
    private $paginCount = 20;

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
        // If something was searched, results are filtered.
        if ($this->searchedEn != '') {
            $this->examples = Example::where('en', 'like', '%'.$this->searchedEn.'%')
                ->paginate($this->paginCount);
        } else {
            $this->examples = Example::paginate($this->paginCount);
        }

        return view('livewire.example-index', [
            'examples' => $this->examples
        ]);
    }

}
