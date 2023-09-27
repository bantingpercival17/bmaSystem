<div>
    @if ($showModal)
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal">
            <!-- Modal content goes here -->
            <div class="p-6 bg-white shadow-lg rounded-lg">
                <!-- Your modal content -->
                <h2 class="text-xl font-semibold">Modal Title</h2>
                <p>Modal content goes here...</p>

                <!-- Close button -->
                <button wire:click="closeModal" class="text-blue-500 hover:underline">Close</button>
            </div>
        </div>
    </div>
    @endif
</div>