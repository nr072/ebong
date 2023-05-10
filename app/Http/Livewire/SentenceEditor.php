<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\Sentence;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceEditor extends Component
{

    private $allGroups;



    public $canShowEditor = false;

    // The sentence that's being edited.
    public $sentence = [];



    // 
    public $canShowGroupDropdown = false;



    // This string is actually matched with both words and groups. So,
    // this will help the user find relevant groups easily regardless of
    // which from they search for. For example, the group 'die' would be
    // shown regardless of which of these words the user searches: 'die',
    // 'dying', 'death'.
    public $searchedGroup;

    protected $queryString = [
        'searchedGroup' => ['except' => '', 'as' => 'ef-assoc'],
    ];



    private $filteredGroups;

    // IDs of existing + suggested groups.
    public $chosenGroupIds = [];



    /*
        Warning: The allowed values of 'note_type' must be updated when
        values in the relevant migration file change.
    */
    protected $rules = [
        'sentence.bn' => 'nullable|string',
        'sentence.context' => 'nullable|max:200',
        'sentence.subcontext' => 'nullable|max:200',
        'sentence.source' => 'nullable|max:100',
        'sentence.link_1' => 'nullable|max:200',
        'sentence.link_2' => 'nullable|max:200',
        'sentence.link_3' => 'nullable|max:200',
        'sentence.note' => 'nullable|string',
        'sentence.note_type' => 'required|in:Note,Reference',
        'sentence.needs_revision' => 'required|boolean',
        'chosenGroupIds' => 'required|array',
        'chosenGroupIds.*' => 'required|numeric',
    ];



    // Certain functions are executed when certian events have been emitted.
    protected $listeners = [
        'editButtonClicked' => 'editSentence',
    ];



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
        $this->emitTo('sentence-index', 'editorClosed');
    }



    public function toggleGroupDropdown($canShow = 0)
    {
        $this->canShowGroupDropdown = $canShow === 1 ? true : false;
    }



    // Potential, existing groups are suggested to be associated. This
    // works by matching words from the sentence and then displaying a
    // list of groups that those words belong to.
    public function autosuggestGroups()
    {
        $wordsInSentence = explode(' ', strtolower($this->sentence['en']));

        $suggestedGroupIds = [];

        // The group IDs for all the matching words are stored in an
        // array.
        $wordsinDb = Word::whereIn('en', $wordsInSentence)->get();
        foreach ($wordsinDb as $word) {

            // Some words may not exist in any group yet.
            if ($word->group) {
                array_push($suggestedGroupIds, $word->group->id);
            }

        }

        // IDs for manually chosen groups and autosuggestd groups are
        // merged. Duplicated are removed.
        $this->chosenGroupIds = array_unique(
            array_merge($this->chosenGroupIds, $suggestedGroupIds)
        );
    }



    // The editor is displayed with the selected sentence's data in its
    // input fields.
    public function editSentence(Sentence $sentenceToEdit)
    {
        $this->sentence = $sentenceToEdit;

        $existingGroupIds = $this->sentence->groups->pluck('id')->toArray();
        $this->chosenGroupIds = array_merge(
            $this->chosenGroupIds,
            $existingGroupIds
        );

        $this->autosuggestGroups($existingGroupIds);

        $this->showEditor();
        $this->emit('editor-opened');
    }



    // Updates the sentence and closes the editor.
    public function saveUpdates()
    {
        $validatedData = $this->validate();
        $this->sentence->update( $validatedData['sentence'] );

        // Associated groups are updated.
        $this->sentence->groups()->sync($this->chosenGroupIds);

        $this->reset();

        $this->emitTo('sentence-index', 'sentenceUpdated');
        $this->emitTo('sentence-index', 'editorClosed');
    }



    // The selected group's ID is stored in an array.
    public function associateGroup($id)
    {
        array_push($this->chosenGroupIds, $id);
        $this->chosenGroupIds = array_unique($this->chosenGroupIds);

        $this->reset('searchedGroup');

        // Used for focusing the group input field.
        $this->emit('sentence-editor-group-associated');
    }

    // The group's ID is removed from the array.
    public function dissociateGroup($id)
    {
        if (in_array($id, $this->chosenGroupIds)) {
            unset(
                $this->chosenGroupIds[ array_search($id, $this->chosenGroupIds) ]
            );
        }

        // Used for focusing the group input field.
        $this->emit('sentence-editor-group-dissociated');
    }



    /*
        When a string is typed, its match is searched in both word en and
        group titles (as opposed to in group titles only) but the group is
        what's shown in the dropdown in the end.

        This is done for the sake of user convenience. This allows the
        user to find both exact word matches and related group matches.
        For example, let's assume that a sentence contains the word 'dying'
        and that it exists in the 'die' group. Now, if the user didn't
        already know which group to search for, they'd type 'dying' and
        wouldn't find any group. The current implementation solves this
        problem by showing the user a union of results obtained by combining
        both word and group matches. So, if the user now types 'dying', its
        group is fetched under the hood and 'die' is displayed to the user.
    */
    public function applySearchFilters()
    {
        if ($this->searchedGroup) {

            // A list of groups whose own titles match the search string.
            $groupsFromGroup = Group::orderBy('title')
                            ->where('title', 'like', $this->searchedGroup.'%')
                            ->get();

            // A list of groups whose words match the search string.
            $groupsFromWord = collect([]);
            $matchedWords = Word::orderBy('en')
                            ->where('en', 'like', $this->searchedGroup.'%')
                            ->get();
            foreach ($matchedWords as $word) {

                // Some words may not exist in any group yet.
                if ($word->group) {
                    $groupsFromWord = $groupsFromWord->concat([$word->group]);
                }

            }

        } else {

            // These need to be (empty) collections so they don't throw errors
            // when merged (or when properties are used in the view).
            $groupsFromGroup = collect([]);
            $groupsFromWord = collect([]);

        }

        // Groups from both sides are merged. Duplicates are removed.
        $this->filteredGroups = $groupsFromGroup->concat($groupsFromWord)
                                                ->unique();
    }



    // Various values are checked in order to determine which dropdowns
    // should be displayed/hidden when.
    public function checkDropdownToggling($whichDropdown = 'all')
    {
        if ($whichDropdown === 'group' || $whichDropdown === 'all') {
            $this->canShowGroupDropdown = $this->searchedGroup ? true : false;
        }
    }



    // The dropdown's visibility needs to be checked every time the group
    // search string is modified.
    public function updatedsearchedGroup()
    {
        $this->checkDropdownToggling('group');
    }



    public function mount()
    {
        $this->checkDropdownToggling();
    }



    public function render()
    {

        $this->allGroups = Group::orderBy('title')->pluck('title', 'id');

        $this->applySearchFilters();

        return view('livewire.sentence-editor', [
            'allGroups' => $this->allGroups,
            'filteredGroups' => $this->filteredGroups,
        ]);

    }

}
