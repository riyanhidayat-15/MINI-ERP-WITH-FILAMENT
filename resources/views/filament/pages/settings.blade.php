<x-filament-panels::page>
    <form wire:submit="save" class="mx-auto w-full max-w-5xl space-y-6">     
        {{ $this->form }}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Pastikan perubahan sudah benar sebelum disimpan.
            </p>

            <div class="flex justify-end gap-3">
                <x-filament::button
                    type="button"
                    color="gray"
                    wire:click="cancel"
                >
                    Batal
                </x-filament::button>

                <x-filament::button
                    type="submit"
                    icon="heroicon-o-check"
                >
                    Simpan perubahan
                </x-filament::button>
            </div>
        </div>
    </form>
</x-filament-panels::page>