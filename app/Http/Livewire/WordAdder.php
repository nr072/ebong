<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cluster;
use App\Models\Pos;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class WordAdder extends Component
{

    public $newWordEn = '';
    public $newWordPos = '';
    public $newWordCluster = '';

    // Used for showing a list of existing clusters to add this new word to.
    private $allClusters = [];

    // String typed in the index to see matching words.
    public $searchedEn = '';

    protected $queryString = [
        'newWordEn' => ['except' => '', 'as' => 'af-word'],
        'searchedEn' => ['except' => '', 'as' => 'en'],
    ];

    public $status = [
        'type' => '',
        'text' => 'Clean',
    ];

    protected $rules = [
        'newWordEn' => 'required|string|max:50',
        'newWordPos' => 'nullable|exists:poses,id',
        'newWordCluster' => 'nullable|exists:clusters,id',
    ];



    // Certain functions are executed when certian events are emitted.
    protected $listeners = [
        'wordCreated' => 'render',
    ];



    private $words;
    private $paginCount = 20;

    // As the user types in the adder, matching words are shown to help
    // avoid adding existing words again.
    private $wordsMatchingSearched;



    // The searched en string is cleared.
    public function resetSearchedEn()
    {
        $this->reset('searchedEn');
    }



    public function addWord()
    {

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Trying to add...';

        $vData = $this->validate();

        // Word creation.
        $newWord = Word::create([
            'en' => trim( $vData['newWordEn'] ),
            'pos_id' => (
                $vData['newWordPos'] === ''
                    ? null
                    : trim( $vData['newWordPos'] )
            ),
            'cluster_id' => (
                $vData['newWordCluster'] === ''
                    ? null
                    : trim( $vData['newWordCluster'] )
            ),
        ]);

        // Input fields are cleared.
        $this->reset();

        if ($newWord) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New word added';

            // An event is emitted so that other Livewire components can
            // detect this and update if needed.
            $this->emit('wordCreated');

        }

    }



    public function applySearchFilters()
    {

        // Results for the word index are filtered.
        if ($this->searchedEn != '') {
            $this->words = Word::orderBy('en')
                ->where('en', 'like', '%'.$this->searchedEn.'%')
                ->paginate($this->paginCount);
        } else {
            $this->words = Word::orderBy('en')
                ->paginate($this->paginCount);
        }

        // Results for the word adder are filtered.
        if ($this->newWordEn != '') {
            $query = Word::orderBy('en')
                            ->where('en', 'like', '%'.$this->newWordEn.'%');
        } else {
            $query = Word::where('id', 0);
        }
        $this->wordsMatchingSearched = $query->get();

    }



    // Specified input fields are cleared.
    public function resetInput($fieldName = '') {
        $this->reset($fieldName);
    }



    public function render()
    {
        $this->allClusters = Cluster::orderBy('name')->get();

        $this->applySearchFilters();

        return view('livewire.word-adder', [
            'words' => $this->words,
            'poses' => Pos::pluck('en', 'id'),
            'wordsMatchingSearched' => $this->wordsMatchingSearched,
            'clusters' => $this->allClusters,
        ]);
    }

}
