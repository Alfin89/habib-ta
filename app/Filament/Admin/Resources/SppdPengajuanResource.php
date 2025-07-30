<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SppdPengajuanResource\Pages;
use App\Models\SppdPengajuan;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Storage; // Penting: Diimpor untuk mengakses file dari storage

// Import untuk fitur ekspor
use App\Filament\Exports\SppdPengajuanExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Models\Export;

class SppdPengajuanResource extends Resource
{
    protected static ?string $model = SppdPengajuan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pengajuan SPPD';
    protected static ?string $modelLabel = 'Pengajuan SPPD';
    protected static ?string $pluralModelLabel = 'Pengajuan SPPD';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengajuan')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengaju')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->readOnly()
                            ->dehydrated(false)
                            ->visibleOn('edit')
                            ->hint('Nomor surat akan dibuat otomatis saat pengajuan disimpan.'),
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('deskripsi_kegiatan')
                            ->label('Deskripsi Kegiatan')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Perjalanan')
                    ->schema([
                        Forms\Components\TextInput::make('tempat_kegiatan')
                            ->label('Tempat Kegiatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tempat_berangkat')
                            ->label('Tempat Berangkat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->afterOrEqual('tanggal_mulai'),
                        Forms\Components\TimePicker::make('waktu_kegiatan')
                            ->label('Waktu Kegiatan'),
                        Forms\Components\TextInput::make('estimasi_biaya')
                            ->label('Estimasi Biaya')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\FileUpload::make('dokumen_pendukung')
                            ->label('Dokumen Pendukung (PDF)')
                            ->disk('public')
                            ->directory('sppd_documents')
                            ->acceptedFileTypes(['application/pdf'])
                            ->preserveFilenames()
                            ->openable()
                            ->downloadable()
                            ->nullable()
                            ->maxSize(5120)
                            ->columnSpanFull()
                            ->hint('Format: PDF, ukuran maks 5MB.'),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Approval')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Menunggu Persetujuan',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'completed' => 'Selesai',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        Forms\Components\Select::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->relationship('approvedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->check() ? auth()->id() : null)
                            ->visible(fn (Forms\Get $get) => in_array($get('status'), ['approved', 'rejected'])),
                        Forms\Components\DateTimePicker::make('approved_at')
                            ->label('Tanggal Persetujuan')
                            ->default(fn () => now())
                            ->visible(fn (Forms\Get $get) => in_array($get('status'), ['approved', 'rejected'])),
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get) => $get('status') === 'rejected'),
                    ])->columns(2),

                Forms\Components\Section::make('Invoice Pembayaran')
                    ->description('Unggah gambar faktur pembayaran setelah pengajuan disetujui.')
                    ->schema([
                        Forms\Components\FileUpload::make('invoice_image')
                            ->label('Gambar Faktur')
                            ->disk('public')
                            ->directory('sppd_invoices')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->image()
                            ->openable()
                            ->downloadable()
                            ->nullable()
                            ->maxSize(2048)
                            ->visible(fn (Forms\Get $get) => $get('status') === 'approved'),
                    ])
                    ->columns(1)
                    ->visible(fn (Forms\Get $get) => $get('status') === 'approved'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengaju')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('tempat_kegiatan')
                    ->label('Tempat')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'info' => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('estimasi_biaya')
                    ->label('Estimasi Biaya')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                    ]),
                Tables\Filters\Filter::make('tanggal_mulai')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari_tanggal'], fn ($q, $date) => $q->whereDate('tanggal_mulai', '>=', $date))
                            ->when($data['sampai_tanggal'], fn ($q, $date) => $q->whereDate('tanggal_mulai', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (SppdPengajuan $record) => $record->status === 'pending')
                    ->action(function (SppdPengajuan $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (SppdPengajuan $record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (SppdPengajuan $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'catatan_admin' => $data['catatan_admin'],
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(SppdPengajuanExporter::class)
                    ->formats([
                        ExportFormat::Csv,
                        ExportFormat::Xlsx,
                    ])
                    ->fileName(fn (Export $export): string => "sppd-pengajuan-{$export->getKey()}-" . now()->format('Ymd_His') . '.{format}'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->exporter(SppdPengajuanExporter::class)
                        ->formats([
                            ExportFormat::Csv,
                            ExportFormat::Xlsx,
                        ])
                        ->fileName(fn (Export $export): string => "sppd-pengajuan-bulk-{$export->getKey()}-" . now()->format('Ymd_His') . '.{format}'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pengajuan')
                    ->schema([
                        Infolists\Components\TextEntry::make('nomor_surat')
                            ->label('Nomor Surat'),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Pengaju'),
                        Infolists\Components\TextEntry::make('nama_kegiatan')
                            ->label('Nama Kegiatan'),
                        Infolists\Components\TextEntry::make('deskripsi_kegiatan')
                            ->label('Deskripsi Kegiatan')
                            ->placeholder('Tidak ada deskripsi'),
                    ])->columns(2),

                Infolists\Components\Section::make('Detail Perjalanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('tempat_kegiatan')
                            ->label('Tempat Kegiatan'),
                        Infolists\Components\TextEntry::make('tempat_berangkat')
                            ->label('Tempat Berangkat'),
                        Infolists\Components\TextEntry::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->date(),
                        Infolists\Components\TextEntry::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->date(),
                        Infolists\Components\TextEntry::make('waktu_kegiatan')
                            ->label('Waktu Kegiatan')
                            ->time()
                            ->placeholder('Tidak ditentukan'),
                        Infolists\Components\TextEntry::make('estimasi_biaya')
                            ->label('Estimasi Biaya')
                            ->money('IDR'),
                    ])->columns(2),

                Infolists\Components\Section::make('Dokumen Pendukung')
                    ->description('Klik tautan di bawah untuk melihat atau mengunduh dokumen PDF.')
                    ->schema([
                        Infolists\Components\TextEntry::make('dokumen_pendukung')
                            ->label('File Dokumen')
                            ->url(fn (SppdPengajuan $record): ?string => filled($record->dokumen_pendukung) ? Storage::url($record->dokumen_pendukung) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-document-text')
                            ->color('primary')
                            ->formatStateUsing(fn ($state) => filled($state) ? 'Lihat/Unduh PDF' : 'Tidak Ada Dokumen')
                            ->hidden(fn (SppdPengajuan $record) => !filled($record->dokumen_pendukung)),
                    ])
                    ->columns(1)
                    ->visible(fn (SppdPengajuan $record) => filled($record->dokumen_pendukung)),




                Infolists\Components\Section::make('Invoice Pembayaran')
                    ->description('Klik tautan di bawah untuk melihat atau mengunduh gambar faktur pembayaran.') // Deskripsi disesuaikan
                    ->schema([
                        Infolists\Components\TextEntry::make('invoice_image')
                            ->label('Gambar Faktur') // Label tetap "Gambar Faktur"
                            // Membuat URL untuk gambar
                            ->url(fn (SppdPengajuan $record): ?string => filled($record->invoice_image) ? Storage::url($record->invoice_image) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-photo') // Ikon disesuaikan untuk gambar
                            ->color('primary')
                            // Mengatur teks yang terlihat di UI menjadi "Lihat/Unduh Gambar"
                            ->formatStateUsing(fn ($state) => filled($state) ? 'Lihat/Unduh Gambar' : 'Tidak Ada Gambar')
                            // Menyembunyikan entri "Gambar Faktur" jika field invoice_image kosong
                            ->hidden(fn (SppdPengajuan $record) => !filled($record->invoice_image)),
                    ])
                    ->columns(1)
                    // Menyembunyikan SELURUH section jika field invoice_image tidak ada isinya
                    ->visible(fn (SppdPengajuan $record) => filled($record->invoice_image)),


                Infolists\Components\Section::make('Status & Approval')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'completed' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('approvedBy.name')
                            ->label('Disetujui Oleh')
                            ->placeholder('Belum diproses'),
                        Infolists\Components\TextEntry::make('approved_at')
                            ->label('Tanggal Persetujuan')
                            ->dateTime()
                            ->placeholder('Belum diproses'),
                        Infolists\Components\TextEntry::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->placeholder('Tidak ada catatan')
                            ->visible(fn ($record) => !empty($record?->catatan_admin)),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSppdPengajuans::route('/'),
            'create' => Pages\CreateSppdPengajuan::route('/create'),
            'view' => Pages\ViewSppdPengajuan::route('/{record}'),
            'edit' => Pages\EditSppdPengajuan::route('/{record}/edit'),
        ];
    }

}
