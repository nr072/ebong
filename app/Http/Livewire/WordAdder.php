<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Word;
use App\Models\Pos;

use Illuminate\Support\Facades\Log;

class WordAdder extends Component
{

    public $newWordEn = '';
    public $newWordPos = '';

    // String typed in the index to see matching words.
    public $searchedEn = '';

    protected $queryString = [
        'newWordEn' => ['except' => '', 'as' => 'typed'],
        'searchedEn' => ['except' => '', 'as' => 'en'],
    ];

    public $status = [
        'type' => '',
        'text' => 'Clean',
    ];

    protected $rules = [
        'newWordEn' => 'required|string|max:50',
        'newWordPos' => 'nullable|exists:poses,id',
    ];



    // Certain functions are executed when certian events have been emitted.
    protected $listeners = [
        'wordCreated' => 'render',
    ];



    private $words;
    private $paginCount = 20;

    // Matching words are shown as the user types in the adder so that to
    // help avoid adding existing words.
    private $matchedForNew;



    // The searched en string is cleared.
    public function resetSearchedEn()
    {
        $this->reset('searchedEn');
    }



    public function addWord()
    {
        $validatedData = $this->validate();

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Adding new word...';

        if (empty($this->newWordEn)) {
            $this->status['type'] = 'error';
            $this->status['text'] = 'Empty word entered!';
            return;
        }

        // Word creation.
        $newWord = Word::create([
            'en' => $validatedData['newWordEn'],
            'pos_id' => $validatedData['newWordPos'],
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

    public function render()
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
            $this->matchedForNew = Word::orderBy('en')
                                    ->where('en', 'like', '%'.$this->newWordEn.'%');
        } else {
            $this->matchedForNew = Word::where('id', 0);
        }
        $this->matchedForNew = $this->matchedForNew->pluck('en', 'id');

        return view('livewire.word-adder', [
            'words' => $this->words,
            'poses' => Pos::pluck('en', 'id'),
            'matchedForNew' => $this->matchedForNew,
        ]);
    }

}
