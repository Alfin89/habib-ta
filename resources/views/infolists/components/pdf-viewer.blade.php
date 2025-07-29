<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        {{ $attributes->merge($getExtraAttributes())->class([
            // Tambahkan kelas CSS dasar untuk konsistensi tampilan Filament
            'filament-infolists-text-entry text-gray-950 dark:text-white',
            'w-full', // Memastikan lebar penuh untuk pratinjau
        ]) }}
    >
        @if ($getState()) {{-- Periksa apakah ada state (path file) --}}
            @php
                // Membuat instance AdvancedFileUpload secara dinamis untuk rendering
                $fileUploadComponent = Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload::make($entry->getName())
                    ->directory($getDirectory()) // Mengambil direktori dari komponen PdfViewer
                    ->acceptedFileTypes(['application/pdf']) // Hanya izinkan PDF
                    ->pdfPreviewHeight(600) // Atur tinggi pratinjau sesuai kebutuhan (misal: 600px)
                    ->pdfToolbar(true) // Tampilkan toolbar PDF (zoom, print, dll.)
                    ->openable() // Memungkinkan file dibuka di tab baru
                    ->downloadable() // Memungkinkan file diunduh
                    ->disabled() // Mencegah interaksi atau perubahan pada tampilan ini
                    // Mengambil nilai path file dari record saat ini
                    ->getStateUsing(fn ($record) => $record->{$entry->getName()});
            @endphp

            {{-- Render komponen AdvancedFileUpload. Ia akan menangani logika pratinjau sendiri. --}}
            {{ $fileUploadComponent }}
        @else
            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada dokumen PDF terlampir.</p>
        @endif
    </div>
</x-dynamic-component>
