<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Term;

use Illuminate\Support\Facades\Log;

class TermIndex extends Component
{

    private $terms;
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
            $this->terms = Term::orderBy('en')
                ->where('en', 'like', '%'.$this->searchedEn.'%')
                ->paginate($this->paginCount);
        } else {
            $this->terms = Term::orderBy('en')
                ->paginate($this->paginCount);
        }

        return view('livewire.term-index', [
            'terms' => $this->terms
        ]);
    }

}
