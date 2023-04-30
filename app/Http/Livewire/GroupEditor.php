<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class GroupEditor extends Component
{

    public $canShowEditor = false;

    // The one that shows words matching the search string.
    public $canShowWordDropdown = false;



    // The group that's being edited.
    public $group = [];



    // The search string.
    public $searched = '';

    protected $queryString = [
        'searched' => ['except' => '', 'as' => 'ef-word'],
    ];



    // Words that can be added to this group. These are the ones shown in
    // the dropdown.
    // These won't be all words because some may already be associated with
    // this group or other groups and some may get filtered out based on
    // the search string. So, basically:
    //   filtered = all words - already associated - filtered out
    private $filteredWords = [];

    // The IDs of the words that are either already associated or have just
    // been chosen by the user to be associated.
    public $chosenWordIds = [];

    // Corresponds to 'chosenWordIds'. Shows the words with a cross button
    // so they can be dissociated.
    private $chosenWords = [];



    protected $rules = [
        'chosenWordIds' => 'required|array',
        'chosenWordIds.*' => 'numeric',
    ];



    // Certain functions are executed when certian events have been emitted.
    protected $listeners = [
        'editButtonClicked' => 'editGroup',
    ];



    // The editor is displayed/hidden based on user interaction.
    private function toggleEditor($canShow = 0)
    {
        $this->canShowEditor = $canShow === 1 ? true : false;
    }



    public function showEditor()
    {
        $this->toggleEditor(1);
    }

    public function closeEditor()
    {
        $this->reset();
        $this->toggleEditor(0);
        $this->emitTo('group-index', 'editorClosed');
    }



    public function checkDropdownToggling($whichDropdown = 'all')
    {

        // The word dropdown is only shown when something is searched.
        if ($whichDropdown === 'word' || $whichDropdown === 'all') {
            $this->canShowWordDropdown = $this->searched ? true : false;
        }

    }



    public function toggleWordDropdown($canShow = 0)
    {
        $this->canShowWordDropdown = $canShow === 1 ? true : false;
    }



    // The editor is displayed with the selected group's data in its
    // input fields.
    public function editGroup(Group $groupToEdit)
    {
        $this->group = $groupToEdit;

        // IDs of words already associated with the group are added to the
        // ID array so that they can be updated if necessary.
        $alreadyAssocWordIds = $this->group->words->pluck('id')->toArray();
        $this->chosenWordIds = array_unique(
            array_merge($this->chosenWordIds, $alreadyAssocWordIds)
        );

        $this->showEditor();
        $this->emit('editor-opened');
    }



    // When the user types in the search field, matching results are shown
    // excluding words that are already associated with this group or other
    // groups.
    public function applySearchFilters()
    {
        $query = Word::orderBy('en')
                        ->whereNotIn('id', $this->chosenWordIds)
                        ->where('group_id', null);

        if ($this->searched) {
            $query = $query->where('en', 'like', $this->searched.'%');
        }

        $this->filteredWords = $query->get();
    }



    public function resetSearched()
    {
        $this->reset('searched');
        $this->checkDropdownToggling('word');
    }



    // The selected word's ID is stored in an array.
    public function associateWord($id)
    {
        array_push($this->chosenWordIds, $id);
        $this->chosenWordIds = array_unique($this->chosenWordIds);

        $this->checkDropdownToggling();

        $this->emitTo('group-index', 'wordAssociated');
    }

    // The word's ID is removed from the array.
    public function dissociateWord($id)
    {
        if (in_array($id, $this->chosenWordIds)) {
            unset(
                $this->chosenWordIds[ array_search($id, $this->chosenWordIds) ]
            );
        }

        $this->emitTo('group-index', 'wordDissociated');
    }



    // The list of chosen words are updated based on the array of IDs.
    public function updateChosenWords()
    {
        $this->chosenWords = Word::whereIn('id', $this->chosenWordIds)
                                    ->get();
    }



    // 
    public function saveUpdates()
    {
        $vData = $this->validate();

        $alreadyAssocWordIds = $this->group->words->pluck('id')->toArray();

        /*
            First, associations are removed for the words that have been
            chosen by the user to be removed.

            This is done by checking the difference between already asociated
            IDs and chosen IDs. If an ID from the already associated word
            ID array doesn't exist in the chosen ID array, it means the
            word needs to be removed.
        */
        $IdsToRemove = array_diff($alreadyAssocWordIds, $vData['chosenWordIds']);
        foreach ($IdsToRemove as $id) {
            $word = Word::find($id);
            $word->group_id = null;
            $word->save();
        }

        // Then, new associations are created for the new words chosen by
        // the user.
        $IdsToAdd = array_diff($vData['chosenWordIds'], $alreadyAssocWordIds);
        foreach ($IdsToAdd as $id) {
            $this->group->words()->save( Word::find($id) );
        }

        $this->reset();

        $this->emitTo('group-index', 'groupUpdated');
        $this->emitTo('group-index', 'editorClosed');
    }



    // The dropdown's visibility needs to be checked every time the word
    // search string is modified.
    public function updatedSearched()
    {
        $this->checkDropdownToggling('word');
    }



    public function mount()
    {
        $this->checkDropdownToggling();
    }



    public function render()
    {
        if ($this->group) {
            $this->applySearchFilters();
        }

        $this->updateChosenWords();

        return view('livewire.group-editor', [
            'words' => $this->filteredWords,
            'chosenWords' => $this->chosenWords,
        ]);
    }

}
