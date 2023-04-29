<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\Sentence;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceAdder extends Component
{

    public $inputs = [];

    // 
    private $allGroups;

    private $filteredGroups;

    // private $associableGroups;

    // The IDs of the associated groups.
    public $chosenGroupIds = [];



    // 
    public $canShowGroupDropdown = false;



    // This string is actually matched with both words and groups. So,
    // this will help the user find relevant groups easily regardless of
    // which from they search for. For example, the group 'die' would be
    // shown regardless of which of these words the user searches: 'die',
    // 'dying', 'death'.
    public $searchedGroup;

    protected $queryString = [
        'searchedGroup' => ['except' => '', 'as' => 'af-group'],
    ];



    // Visual cues to let the user know things are happening (or maybe
    // not happening).
    public $status = [
        'type' => '',
        'text' => 'Clean'
    ];



    // List of sources to show in a dropdown.
    public $sources = [];

    public $canShowSourceDropdown = true;



    // Validation rules.
    protected $rules = [
        'inputs.*.en' => 'required|string',
        'inputs.*.bn' => 'nullable|string',
        'inputs.*.context' => 'nullable|max:200',
        'inputs.*.subcontext' => 'nullable|max:200',
        'inputs.*.source' => 'nullable|max:100',
        'inputs.*.link1' => 'nullable|max:200',
        'inputs.*.link2' => 'nullable|max:200',
        'inputs.*.link3' => 'nullable|max:200',
        'chosenGroupIds' => 'required|array',
        'chosenGroupIds.*' => 'numeric',
    ];



    public function addSentence()
    {
        $validatedData = $this->validate();

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Adding new sentence...';

        if (empty($this->inputs[0]['en'])) {
            $this->status['type'] = 'error';
            $this->status['text'] = 'Empty sentence entered!';
            return;
        }

        // A sentence is created for each set of sentence-related inputs.
        foreach ($validatedData['inputs'] as $validInput) {

            $newSentence = Sentence::create([
                'en' => trim( $validInput['en'] ),
                'bn' => trim( $validInput['bn'] ),
                'context' => trim( $validInput['context'] ),
                'subcontext' => trim( $validInput['subcontext'] ),
                'source' => trim( $validInput['source'] ),
                'link_1' => trim( $validInput['link1'] ),
                'link_2' => trim( $validInput['link2'] ),
                'link_3' => trim( $validInput['link3'] ),
            ]);

            // Groups are associated.
            $newSentence->groups()->attach( $validatedData['chosenGroupIds'] );

        }

        // Input fields are cleared.
        $this->reset();

        if ($newSentence) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New sentence added';

            // An event is emitted so that other Livewire components can
            // detect this and update if needed.
            $this->emit('sentenceCreated');

        }

        // The array for sentence-related inputs needs at least one item
        // for the input fields on the page to show up.
        $this->insertArrayForNewSentece();
    }



    // The selected group's ID is stored in an array.
    public function associateGroup($id)
    {
        array_push($this->chosenGroupIds, $id);
        $this->reset('searchedGroup');

        // Used for focusing the group input field.
        $this->emit('sentence-adder-group-associated');
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
        $this->emit('sentence-adder-group-dissociated');
    }



    public function addAnotherSentence()
    {
        $this->insertArrayForNewSentece();
        $this->toggleSourceDropdown(1);
    }



    // An array full of inputs for a sentence is inserted into the main
    // array (for all the inputs) so that a/another set of (empty) input
    // fields can show up on the page.
    private function insertArrayForNewSentece()
    {
        array_push(
            $this->inputs, 
            [
                'en' => '',
                'bn' => '',
                'context' => '',
                'subcontext' => '',
                'source' => '',
                'link1' => '',
                'link2' => '',
                'link3' => '',
            ]
        );
    }



    // An existing source is selected from a dropdown.
    public function selectSource($index, $source)
    {
        $this->inputs[$index]['source'] = $source;
        $this->toggleSourceDropdown(0);
    }



    public function toggleSourceDropdown($canShow = 0)
    {
        $this->canShowSourceDropdown = $canShow === 1 ? true : false;
    }

    public function toggleGroupDropdown($canShow = 0)
    {
        $this->canShowGroupDropdown = $canShow === 1 ? true : false;
    }



    // // Potential, existing words are suggested to be associated. This only
    // // works for identical matches though.
    // public function autosuggestAssocWords()
    // {
    //     foreach ($this->inputs as $sentence) {

    //         $wordsInSentence = explode(' ', strtolower($sentence['en']));
    //         $suggestedAssocWordIds = Word::whereIn('en', $wordsInSentence)
    //                                     ->whereNotIn('id', $this->chosenGroupIds)
    //                                     ->pluck('id')
    //                                     ->toArray();

    //         $this->chosenGroupIds = array_merge(
    //             $this->chosenGroupIds,
    //             $suggestedAssocWordIds
    //         );

    //     }
    // }



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

        if ($whichDropdown === 'source' || $whichDropdown === 'all') {
            if (method_exists($this->sources, 'count')) {
                $this->canShowSourceDropdown = $this->sources->count() > 0;
            } else {
                $this->canShowSourceDropdown = sizeof($this->sources) > 0;
            }
        }

    }



    public function updatedInputs()
    {
        // $this->autosuggestAssocWords();
    }



    // The dropdown's visibility needs to be checked every time the group
    // search string is modified.
    public function updatedsearchedGroup()
    {
        $this->checkDropdownToggling('group');
    }



    public function mount()
    {

        // The array for sentence-related inputs needs at least one item
        // for the input fields on the page to show up.
        $this->insertArrayForNewSentece();

        $this->checkDropdownToggling();

    }



    public function render()
    {

        $this->allGroups = Group::orderBy('title')->pluck('title', 'id');

        $this->applySearchFilters();

        // Existing sources are shown in a dropdown to easily choose from.
        $this->sources = Sentence::groupBy('source')->pluck('source');

        return view('livewire.sentence-adder', [
            'allGroups' => $this->allGroups,
            'filteredGroups' => $this->filteredGroups,
            // 'associableGroups' => $this->associableGroups,
        ]);

    }

}