<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cluster;
use App\Models\Sentence;
use App\Models\SentenceTranslation;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceAdder extends Component
{

    public $inputs = [];

    // 
    private $allClusters;

    private $filteredClusters;

    // The IDs of the associated clusters.
    public $chosenClusterIds = [];



    public $canShowClusterDropdown = false;



    public $canEnableAutosuggestion = true;



    // This string is actually matched with both words and clusters. So,
    // this will help the user find relevant clusters easily regardless of
    // which from they search for. For example, the cluster 'die' would be
    // shown regardless of which of these words the user searches: 'die',
    // 'dying', 'death'.
    public $searchedCluster;

    protected $queryString = [
        'searchedCluster' => ['except' => '', 'as' => 'af-cluster'],
    ];



    // Visual cues to let the user know things are happening (or maybe
    // not happening).
    public $status = [
        'type' => '',
        'text' => 'Clean'
    ];



    // List of projects to show in a dropdown.
    public $projects = [];

    public $canShowProjectDropdown = true;



    /*
        Warning: The allowed values of 'noteType' must be updated when
        values in the relevant migration file change.

        Warning: The initial values of 'noteType' and 'needsRevision' are
        manually set somewhere below.
    */
    protected $rules = [
        'inputs' => 'array',
        'inputs.*.sourceText' => 'required|string',
        'inputs.*.sourceLang' => 'required|string|max:6',
        'inputs.*.translations' => 'array',
        'inputs.*.translations.*.targetText' => 'nullable|string',
        'inputs.*.translations.*.targetLang' => 'nullable|string|max:6',
        'inputs.*.stringKey' => 'nullable|max:200',
        'inputs.*.context' => 'nullable|max:200',
        'inputs.*.subcontext' => 'nullable|max:200',
        'inputs.*.project' => 'nullable|max:100',
        'inputs.*.link1' => 'nullable|max:200',
        'inputs.*.link2' => 'nullable|max:200',
        'inputs.*.link3' => 'nullable|max:200',
        'inputs.*.note' => 'nullable|string',
        'inputs.*.noteType' => 'required|in:Note,Reference',
        'inputs.*.needsRevision' => 'required|boolean',
        'chosenClusterIds' => 'required|array',
        'chosenClusterIds.*' => 'numeric',
    ];



    protected $listeners = [
        'bAdderDataSent' => 'fillBulkData',
    ];



    public function createSentence()
    {
        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Trying to add...';

        $validatedData = $this->validate();

        // A sentence is created for each set of sentence-related inputs.
        foreach ($validatedData['inputs'] as $sentence) {

            /*
                Note to developer: Don't forget to add new column names
                to the model's 'fillable' property.
            */
            $newSentence = Sentence::create([
                'text' => trim( $sentence['sourceText'] ),
                'lang' => trim( $sentence['sourceLang'] ),
                'stringKey' => trim( $sentence['stringKey'] ),
                'context' => trim( $sentence['context'] ),
                'subcontext' => trim( $sentence['subcontext'] ),
                'project' => trim( $sentence['project'] ),
                'link_1' => trim( $sentence['link1'] ),
                'link_2' => trim( $sentence['link2'] ),
                'link_3' => trim( $sentence['link3'] ),
                'note_type' => trim( $sentence['noteType'] ),
                'note' => trim( $sentence['note'] ),
                'needs_revision' => $sentence['needsRevision'],
            ]);

            // Clusters are associated.
            $newSentence->clusters()->attach( $validatedData['chosenClusterIds'] );

            // Sentence translations are created and associated with the
            // sentence.
            foreach ($sentence['translations'] as $senTrans) {
                $newSenTrans = SentenceTranslation::create([
                    'text' => trim( $senTrans['targetText'] ),
                    'lang' => trim( $senTrans['targetLang'] ),
                    'sentence_id' => $newSentence->id,
                ]);
            }

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



    // The selected cluster's ID is stored in an array.
    public function associateCluster($id)
    {
        array_push($this->chosenClusterIds, $id);
        $this->chosenClusterIds = array_unique($this->chosenClusterIds);

        $this->reset('searchedCluster');

        // Used for focusing the cluster input field.
        $this->emit('sentence-adder-cluster-associated');
    }

    // The cluster's ID is removed from the array.
    public function dissociateCluster($id)
    {
        if (in_array($id, $this->chosenClusterIds)) {
            unset(
                $this->chosenClusterIds[ array_search($id, $this->chosenClusterIds) ]
            );
        }

        // Used for focusing the cluster input field.
        $this->emit('sentence-adder-cluster-dissociated');
    }



    public function addAnotherSentence()
    {
        $this->insertArrayForNewSentece();
        $this->toggleProjectDropdown(1);
    }



    /*
        An array full of inputs for a sentence is inserted into the main
        array (for all the inputs) so that a/another set of (empty) input
        fields can show up on the page.

        Some initial values need to be manually set whenever a new sentence
        is going to be created because the relevant columns have default
        values in the database and the validation rules at the top of this
        file reflect the mandatory (required) state of those fields (columns).

        Warning: These keys correspond to the validation rules listed
        somewhere above.
    */
    private function insertArrayForNewSentece()
    {
        array_push(
            $this->inputs, 
            [
                'sourceText' => '',
                'sourceLang' => 'en',
                'translations' => [
                    0 => [
                        'targetText' => '',
                        'targetLang' => 'bn',
                    ],
                ],
                'stringKey' => '',
                'context' => '',
                'subcontext' => '',
                'project' => '',
                'link1' => '',
                'link2' => '',
                'link3' => '',
                'noteType' => 'Note',
                'note' => '',
                'needsRevision' => false,
            ]
        );
    }



    // An empty array is inserted into the selected sentence's data.
    /*
        TODO
        Note to developer: The value 'bn' is hardcoded as the target
        langauge for now. It needs to be blank by default and selectable
        on the page by the user.
    */
    public function addAnotherSenTrans($sentenceIndex)
    {
        array_push(
            $this->inputs[$sentenceIndex]['translations'],
            [
                'targetText' => '',
                'targetLang' => 'bn',
            ]
        );;
    }



    // An existing project is selected from a dropdown.
    public function selectProject($index, $project)
    {
        $this->inputs[$index]['project'] = $project;
        $this->toggleProjectDropdown(0);
    }



    public function toggleProjectDropdown($canShow = 0)
    {
        $this->canShowProjectDropdown = $canShow === 1 ? true : false;
    }

    public function toggleClusterDropdown($canShow = 0)
    {
        $this->canShowClusterDropdown = $canShow === 1 ? true : false;
    }



    // Potential, existing clusters are suggested to be associated. This
    // works by matching words from the sentence and then displaying a
    // list of clusters that those words belong to.
    public function autosuggestClusters()
    {
        foreach ($this->inputs as $sentence) {

            $wordsInSentence = explode(' ', strtolower($sentence['sourceText']));

            $suggestedClusterIds = [];

            // The cluster IDs for all the matching words are stored in an
            // array.
            $wordsinDb = Word::whereIn('en', $wordsInSentence)->get();
            foreach ($wordsinDb as $word) {

                // Some words may not exist in any cluster yet.
                if ($word->cluster) {
                    array_push($suggestedClusterIds, $word->cluster->id);
                }

            }

            // IDs for manually chosen clusters and autosuggestd clusters are
            // merged. Duplicated are removed.
            $this->chosenClusterIds = array_unique(
                array_merge($this->chosenClusterIds, $suggestedClusterIds)
            );

        }
    }



    /*
        When a string is typed, its match is searched in both word en and
        cluster names (as opposed to in cluster names only) but the cluster is
        what's shown in the dropdown in the end.

        This is done for the sake of user convenience. This allows the
        user to find both exact word matches and related cluster matches.
        For example, let's assume that a sentence contains the word 'dying'
        and that it exists in the 'die' cluster. Now, if the user didn't
        already know which cluster to search for, they'd type 'dying' and
        wouldn't find any cluster. The current implementation solves this
        problem by showing the user a union of results obtained by combining
        both word and cluster matches. So, if the user now types 'dying', its
        cluster is fetched under the hood and 'die' is displayed to the user.
    */
    public function applySearchFilters()
    {
        if ($this->searchedCluster) {

            // A list of clusters whose own names match the search string.
            $clustersFromCluster = Cluster::orderBy('name')
                            ->where('name', 'like', $this->searchedCluster.'%')
                            ->get();

            // A list of clusters whose words match the search string.
            $clustersFromWord = collect([]);
            $matchedWords = Word::orderBy('en')
                            ->where('en', 'like', $this->searchedCluster.'%')
                            ->get();
            foreach ($matchedWords as $word) {

                // Some words may not exist in any cluster yet.
                if ($word->cluster) {
                    $clustersFromWord = $clustersFromWord->concat([$word->cluster]);
                }

            }

        } else {

            // These need to be (empty) collections so they don't throw errors
            // when merged (or when properties are used in the view).
            $clustersFromCluster = collect([]);
            $clustersFromWord = collect([]);

        }

        // Clusters from both sides are merged. Duplicates are removed.
        $this->filteredClusters = $clustersFromCluster->concat($clustersFromWord)
                                                ->unique();
    }



    // Various values are checked in order to determine which dropdowns
    // should be displayed/hidden when.
    public function checkDropdownToggling($whichDropdown = 'all')
    {

        if ($whichDropdown === 'cluster' || $whichDropdown === 'all') {
            $this->canShowClusterDropdown = $this->searchedCluster ? true : false;
        }

        if ($whichDropdown === 'project' || $whichDropdown === 'all') {
            if (method_exists($this->projects, 'count')) {
                $this->canShowProjectDropdown = $this->projects->count() > 0;
            } else {
                $this->canShowProjectDropdown = sizeof($this->projects) > 0;
            }
        }

    }



    // Data received from the bulk adder is put into the adder's input
    // fields.
    public function fillBulkData($sentence)
    {

        // Input fields are cleared.
        $this->reset('chosenClusterIds');

        foreach (array_keys($sentence) as $key) {

            // If a key exists in the (regular) adder's property, the
            // corresponding sentence data received from the bulk adder
            // is saved there. This helps weed out property names that
            // don't match.
            if ( array_key_exists($key, $this->inputs[0]) ) {
                $this->inputs[0][$key] = $sentence[$key];
            }

        }

        // Autosuggestion doesn't trigger automatically (probably because
        // the values were set internally), so the function needs to be
        // called..
        $this->autosuggestClusters();

    }



    // Specified input fields are cleared.
    public function resetInput(
        $sentenceIndex = null,
        $field = '',
        $nestedIndex = null,
        $nestedField = null
    ) {
        // If nested index and nested field name are provided, only
        // they are cleared. If they aren't provided, the entire field
        // is reset.
        if (isset($nestedIndex) && isset($nestedField)) {

            $nestedIndex = intval($nestedIndex);
            $nestedValueSize = sizeof($this->inputs[$sentenceIndex][$field]);
            if ($nestedIndex < 0 || $nestedIndex > $nestedValueSize) {
                return;
            }

            $this->inputs[$sentenceIndex][$field][$nestedIndex][$nestedField] = '';

        } else {

            $sentenceIndex = intval($sentenceIndex);
            if ($sentenceIndex < 0 || $sentenceIndex > sizeof($this->inputs)) {
                return;
            }

            $this->inputs[$sentenceIndex][$field] = '';

        }
    }



    public function updatedInputs()
    {
        if ($this->canEnableAutosuggestion) {
            $this->autosuggestClusters();
        }
    }



    // The dropdown's visibility needs to be checked every time the cluster
    // search string is modified.
    public function updatedSearchedCluster()
    {
        $this->checkDropdownToggling('cluster');
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

        $this->allClusters = Cluster::orderBy('name')->pluck('name', 'id');

        $this->applySearchFilters();

        // Existing projects are shown in a dropdown to easily choose from.
        $this->projects = Sentence::groupBy('project')->pluck('project');

        return view('livewire.sentence-adder', [
            'allClusters' => $this->allClusters,
            'filteredClusters' => $this->filteredClusters,
        ]);

    }

}