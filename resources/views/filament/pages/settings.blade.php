
<x-filament-panels::page>
    <div class="mx-auto w-full max-w-5xl space-y-6">

        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                Pengaturan perusahaan
            </h1>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Kelola identitas perusahaan dan informasi pembayaran
                untuk invoice dan kwitansi.
            </p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">

                <div class="border-b border-gray-200 px-6 py-5 dark:border-white/10">
                    <h2 class="font-semibold text-gray-950 dark:text-white">
                        Informasi bisnis
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi ini digunakan pada dokumen perusahaan.
                    </p>
                </div>

                <div class="space-y-6 p-6">
                    {{ $this->form }}
                </div>

                <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-white/10 dark:bg-white/[0.02]">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Pastikan perubahan sudah benar sebelum disimpan.
                    </p>

                    <div class="flex justify-end gap-3">
                        <x-filament::button
                            type="button"
                            color="gray"
                            outlined
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
            </div>
        </form>
    </div>
</x-filament-panels::page>
