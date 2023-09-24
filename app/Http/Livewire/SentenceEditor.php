<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cluster;
use App\Models\Sentence;
use App\Models\SentenceTranslation;
use App\Models\Word;

use Illuminate\Support\Facades\Log;

class SentenceEditor extends Component
{

    private $allClusters;



    public $canShowEditor = false;

    // The sentence that's being edited.
    public Sentence $sentence;



    // 
    public $canShowClusterDropdown = false;



    // This string is actually matched with both words and clusters. So,
    // this will help the user find relevant clusters easily regardless of
    // which from they search for. For example, the cluster 'die' would be
    // shown regardless of which of these words the user searches: 'die',
    // 'dying', 'death'.
    public $searchedCluster;

    protected $queryString = [
        'searchedCluster' => ['except' => '', 'as' => 'ef-assoc'],
    ];



    private $filteredClusters;

    // IDs of existing + suggested clusters.
    public $chosenClusterIds = [];



    /*
        Warning: The allowed values of 'note_type' must be updated when
        values in the relevant migration file change.
    */
    protected $rules = [
        'sentence.translations.*.text' => 'nullable|string',
        'sentence.context' => 'nullable|max:200',
        'sentence.subcontext' => 'nullable|max:200',
        'sentence.project' => 'nullable|max:100',
        'sentence.link_1' => 'nullable|max:200',
        'sentence.link_2' => 'nullable|max:200',
        'sentence.link_3' => 'nullable|max:200',
        'sentence.note' => 'nullable|string',
        'sentence.note_type' => 'required|in:Note,Reference',
        'sentence.needs_revision' => 'required|boolean',
        'chosenClusterIds' => 'required|array',
        'chosenClusterIds.*' => 'required|numeric',
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
        $this->resetExcept('sentence');
        $this->sentence = new Sentence();

        $this->toggleEditor(0);
        $this->emitTo('sentence-index', 'editorClosed');
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
        $wordsInSentence = explode(' ', strtolower($this->sentence['text']));

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



    // The editor is displayed with the selected sentence's data in its
    // input fields.
    public function editSentence(Sentence $sentenceToEdit)
    {
        $this->sentence = $sentenceToEdit;

        $existingClusterIds = $this->sentence->clusters->pluck('id')->toArray();
        $this->chosenClusterIds = array_merge(
            $this->chosenClusterIds,
            $existingClusterIds
        );

        $this->autosuggestClusters($existingClusterIds);

        $this->showEditor();
        $this->emit('editor-opened');
    }



    // Updates the sentence and closes the editor.
    public function saveUpdates()
    {
        $validatedData = $this->validate();
        $this->sentence->update( $validatedData['sentence'] );

        // Associated clusters are updated.
        $this->sentence->clusters()->sync($this->chosenClusterIds);

        // All translations for this sentence are updated.
        $this->sentence->translations->each->save();

        $this->resetExcept('sentence');
        $this->sentence = new Sentence();

        $this->emitTo('sentence-index', 'sentenceUpdated');
        $this->emitTo('sentence-index', 'editorClosed');
    }



    // The selected cluster's ID is stored in an array.
    public function associateCluster($id)
    {
        array_push($this->chosenClusterIds, $id);
        $this->chosenClusterIds = array_unique($this->chosenClusterIds);

        $this->reset('searchedCluster');

        // Used for focusing the cluster input field.
        $this->emit('sentence-editor-cluster-associated');
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
        $this->emit('sentence-editor-cluster-dissociated');
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
    }



    // The dropdown's visibility needs to be checked every time the cluster
    // search string is modified.
    public function updatedsearchedCluster()
    {
        $this->checkDropdownToggling('cluster');
    }



    public function mount()
    {
        $this->checkDropdownToggling();

        // Stop the Blade view from throwing the 'non-object' error.
        $this->sentence = new Sentence();
    }



    public function render()
    {

        $this->allClusters = Cluster::orderBy('name')->pluck('name', 'id');

        $this->applySearchFilters();

        return view('livewire.sentence-editor', [
            'allClusters' => $this->allClusters,
            'filteredClusters' => $this->filteredClusters,
        ]);

    }

}
