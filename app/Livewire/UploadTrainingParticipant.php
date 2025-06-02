<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UploadTrainingParticipant extends Component
{
    public $project;
    public $auction_id;
    public $selected_users = [];

    public $projects = QDS_PROJECT_LIST;
    public $users = []; // You can load real users from DB

    public function mount()
    {
        // Example dummy users; ideally fetch from DB
        $this->users = User::whereNotIn('user_role_id', [1, 2, 4])->select('id', 'fullname')->get()->toArray();
    }

    public function render()
    {
        return view('livewire.upload-training-participant');
    }
}
