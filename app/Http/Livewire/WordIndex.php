<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class WordIndex extends Component
{

    private $words;
    private $paginCount = 20;

    protected $queryString = [
        'searchedEn' => ['except' => '', 'as' => 'en'],
    ];

    protected $listeners = [
        'wordCreated' => 'render',
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
            $this->words = Word::orderBy('en')
                ->where('en', 'like', '%'.$this->searchedEn.'%')
                ->paginate($this->paginCount);
        } else {
            $this->words = Word::orderBy('en')
                ->paginate($this->paginCount);
        }

        return view('livewire.word-index', [
            'words' => $this->words
        ]);
    }

}
