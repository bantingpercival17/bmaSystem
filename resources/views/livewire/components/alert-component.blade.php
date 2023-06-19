<div>
    @push('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:load', function() {
                Livewire.on('showAlert', function(message, type) {
                    Swal.fire({
                        title: type,
                        text: message,
                        icon: type,
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
            });
        </script>
    @endpush
</div>
