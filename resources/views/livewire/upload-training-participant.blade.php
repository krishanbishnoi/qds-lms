<div>
    <div class="p-4">
        <!-- Project Selection -->
        <div class="mb-4">
            <label for="project" class="block font-bold mb-1">Select Project</label>
            <select wire:model="project" id="project" class="border rounded p-2 w-full">
                <option value="">-- Choose Project --</option>
                @foreach ($projects as $proj)
                    <option value="{{ $proj }}">{{ $proj }}</option>
                @endforeach
            </select>
        </div>
        {{-- @dump($project) --}}
        <!-- Conditional Fields -->
        @if ($project === 'RetailIQ')
            <div class="mb-4">
                <label for="auction_id" class="block font-bold mb-1">Auction ID</label>
                <input type="text" wire:model="auction_id" id="auction_id" class="border rounded p-2 w-full"
                    placeholder="Enter Auction ID">
            </div>
        @elseif($project)
            <div class="mb-4">
                <label for="users" class="block font-bold mb-1">Select Users to Assign</label>
                <select wire:model="selected_users" multiple id="users" class="border rounded p-2 w-full h-32">
                    @foreach ($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Debug (Optional) -->
        {{-- <div class="mt-4 text-sm text-gray-500">
            <p><strong>Project:</strong> {{ $project }}</p>
            <p><strong>Auction ID:</strong> {{ $auction_id }}</p>
            <p><strong>Selected Users:</strong> {{ json_encode($selected_users) }}</p>
        </div> --}}
    </div>

</div>
