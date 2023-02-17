<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Term;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class TermIndex extends Component
{

    private $terms;

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
        $this->terms = DB::table('terms');

        // If something was searched, results are filtered.
        if ($this->searchedEn != '') {
            $this->terms = $this->terms
                ->where('en', 'like', '%'.$this->searchedEn.'%');
        }

        return view('livewire.term-index', [
            'terms' => $this->terms->paginate(10)
        ]);
    }

}
