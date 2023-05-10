<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Word;
use App\Models\Group;

use Illuminate\Support\Facades\Log;

class GroupAdder extends Component
{

    public $title = "";

    // Once a word has been added to a group, it shouldn't show up as
    // a suggestion to be added to another group.
    private $grouplessWords = [];



    // String typed in the adder to see matching words.
    public $searchedWord = '';

    protected $queryString = [
        'searchedWord' => ['except' => '', 'as' => 'af-word'],
    ];

    // Contains the results after the search filter has been applied.
    private $filteredWords = [];



    // The IDs of the selected assoc words. These are used for showing
    // which words have been selected so far.
    public $chosenWordIds = [];



    // For visual cues.
    public $status = [
        'type' => '',
        'text' => 'Clean',
    ];



    // Validation rules.
    protected $rules = [
        'title' => 'required|string|max:50',
        'chosenWordIds' => 'required|array|min:1',
        'chosenWordIds.*' => 'numeric',
    ];



    public function createGroup()
    {

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Trying to add...';

        $validatedData = $this->validate();

        // Group creation.
        $newGroup = Group::create([
            'title' => $validatedData['title'],
        ]);

        // Selected words are included in the newly created group.
        foreach ($validatedData['chosenWordIds'] as $id) {
            $word = Word::find($id);
            $word->group_id = $newGroup->id;
            $word->save();
        }

        // Input fields are cleared.
        $this->reset();

        if ($newGroup) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New group created';

            // An event is emitted in case another component needs to update.
            $this->emitTo('group-index', 'groupCreated');

        }

    }



    // Search filters (if any) are applied.
    public function applySearchFilters()
    {
        if ($this->searchedWord) {
            $query = Word::where('group_id', null)
                            ->where('en', 'like', $this->searchedWord.'%');
        } else {
            $query = Word::where('id', 0);
        }

        $this->filteredWords = $query->get();
    }



    public function addWordToGroup($id)
    {
        array_push($this->chosenWordIds, $id);

        // Used for focusing the assoc word input field.
        $this->emit('word-added-to-group');
    }

    public function removeWordFromGroup($id)
    {
        if (in_array($id, $this->chosenWordIds)) {
            unset($this->chosenWordIds[ array_search($id, $this->chosenWordIds) ]);
        }

        // Used for focusing the assoc word input field.
        $this->emit('word-removed-from-group');
    }



    public function render()
    {
        $this->grouplessWords = Word::orderBy('en')
                                    ->where('group_id', null)
                                    ->select('id', 'en')
                                    ->get();

        $this->applySearchFilters();

        return view('livewire.group-adder', [
            'grouplessWords' => $this->grouplessWords,
            'filteredWords' => $this->filteredWords,
        ]);
    }

}
