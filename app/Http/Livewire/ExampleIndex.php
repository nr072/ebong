<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Example;

use Illuminate\Support\Facades\Log;

class ExampleIndex extends Component
{

    private $examples;

    // The number of paginated entries to be returned.
    private $paginCount = 20;

    // A reset function depends on these properties.
    public $searchedEn = '';
    public $searchedBn = '';
    public $searchedContext = '';
    public $searchedTerm = '';
    public $searchedSource = '';

    protected $queryString = [
        'searchedEn' => ['except' => '', 'as' => 'en'],
        'searchedBn' => ['except' => '', 'as' => 'bn'],
        'searchedContext' => ['except' => '', 'as' => 'context'],
        'searchedTerm' => ['except' => '', 'as' => 'term'],
        'searchedSource' => ['except' => '', 'as' => 'source'],
    ];

    protected $listeners = [
        'exampleCreated' => 'render',
    ];



    // The searched string is cleared.
    // These values depend on some properties of this class.
    public function resetSearched($column)
    {
        if ($column === 'en') {
            $this->reset('searchedEn');
        } else if ($column === 'bn') {
            $this->reset('searchedBn');
        } else if ($column === 'context') {
            $this->reset('searchedContext');
        } else if ($column === 'term') {
            $this->reset('searchedTerm');
        } else if ($column === 'source') {
            $this->reset('searchedSource');
        }
    }



    public function render()
    {
        $this->examples = Example::orderBy('en');

        /*
            If something was searched, results are filtered.
        */
        if ($this->searchedEn !== '') {
            $this->examples = $this->examples
                ->where('en', 'like', '%'.$this->searchedEn.'%');
        }
        if ($this->searchedBn !== '') {
            $this->examples = $this->examples
                ->where('bn', 'like', '%'.$this->searchedBn.'%');
        }
        // Context searching works on the subcontext column too.
        if ($this->searchedContext !== '') {
            $this->examples = $this->examples
                ->where(function($query) {
                    $query->where('context', 'like', '%'.$this->searchedContext.'%')
                        ->orWhere('subcontext', 'like', '%'.$this->searchedContext.'%');
                });
        }
        // The associated terms exist in another table.
        if ($this->searchedTerm !== '') {
            $this->examples = $this->examples
                ->whereHas('terms', function ($query){
                    $query->where('en', 'like', '%'.$this->searchedTerm.'%');
                });
        }
        if ($this->searchedSource !== '') {
            $this->examples = $this->examples
                ->where('source', 'like', '%'.$this->searchedSource.'%');
        }

        return view('livewire.example-index', [
            'examples' => $this->examples->paginate($this->paginCount)
        ]);
    }

}
