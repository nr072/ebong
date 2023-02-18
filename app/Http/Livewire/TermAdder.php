<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Term;

use Illuminate\Support\Facades\Log;

class TermAdder extends Component
{

    public $newText = '';

    public $status = [
        'type' => '',
        'text' => 'Clean',
    ];

    protected $rules = [
        'newText' => 'required|string|max:50',
    ];



    public function addTerm()
    {
        $this->validate();

        // Nice visual cue that things are starting.
        $this->status['type'] = 'warning';
        $this->status['text'] = 'Adding new term...';

        if (empty($this->newText)) {
            $this->status['type'] = 'error';
            $this->status['text'] = 'Empty term entered!';
            return;
        }

        // Term creation.
        $newTerm = Term::create([
            'en' => $this->newText
        ]);

        // Input fields are cleared.
        $this->reset();

        if ($newTerm) {

            // Nice visual cue again.
            $this->status['type'] = 'success';
            $this->status['text'] = 'New term added';

            // An event is emitted so that other Livewire components can
            // detect this and update if needed.
            $this->emit('termCreated');

        }

    }

    public function render()
    {
        return view('livewire.term-adder');
    }

}
