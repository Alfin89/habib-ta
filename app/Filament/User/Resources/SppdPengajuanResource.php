<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\SppdPengajuanResource\Pages;
use App\Models\SppdPengajuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;

class SppdPengajuanResource extends Resource
{
    protected static ?string $model = SppdPengajuan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pengajuan SPPD Saya';
    protected static ?string $modelLabel = 'Pengajuan SPPD';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kegiatan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('deskripsi_kegiatan')
                            ->label('Deskripsi Kegiatan')
                            ->rows(3),
                        Forms\Components\TextInput::make('tempat_kegiatan')
                            ->label('Tempat Kegiatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tempat_berangkat')
                            ->label('Tempat Berangkat')
                            ->required()
                            ->maxLength(255),
                    ])->columns(1),

                Forms\Components\Section::make('Jadwal Perjalanan')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required(),
                        Forms\Components\TimePicker::make('waktu_kegiatan')
                            ->label('Waktu Kegiatan'),
                        Forms\Components\TextInput::make('estimasi_biaya')
                            ->label('Estimasi Biaya')
                            ->numeric()
                            ->prefix('Rp'),
                        AdvancedFileUpload::make('dokumen_pendukung')
                            ->label('Dokumen Pendukung (PDF)')
                            ->preserveFilenames()
                            ->directory('sppd_documents')
                            ->acceptedFileTypes(['application/pdf'])
                            ->pdfPreviewHeight(400)
                            ->pdfToolbar(true)
                            ->openable()
                            ->downloadable()
                            ->nullable()
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Placeholder::make('status_info')
                            ->label('Status Pengajuan')
                            ->content(function ($record) {
                                if (!$record) return 'Draft - Belum disimpan';

                                return match($record->status) {
                                    'draft' => 'Draft - Belum diajukan',
                                    'pending' => 'Menunggu persetujuan admin',
                                    'approved' => 'Disetujui oleh admin',
                                    'rejected' => 'Ditolak oleh admin',
                                    'completed' => 'Perjalanan dinas selesai',
                                    default => 'Status tidak diketahui'
                                };
                            }),
                        Forms\Components\Placeholder::make('catatan_admin')
                            ->label('Catatan dari Admin')
                            ->content(fn ($record) => $record?->catatan_admin ?? 'Tidak ada catatan')
                            ->visible(fn ($record) => !empty($record?->catatan_admin)),
                    ])->columns(1)
                    ->visible(fn ($operation) => $operation === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')
                    ->label('Kegiatan')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('tempat_kegiatan')
                    ->label('Tempat Kegiatan')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('tempat_berangkat')
                    ->label('Tempat Berangkat')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
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
                Tables\Columns\TextColumn::make('dokumen_pendukung')
                    ->label('Dokumen')
                    ->url(fn (SppdPengajuan $record): ?string => filled($record->dokumen_pendukung) ? Storage::url($record->dokumen_pendukung) : null)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->tooltip(fn (SppdPengajuan $record): string => filled($record->dokumen_pendukung) ? 'Klik untuk Lihat/Unduh' : 'Tidak ada dokumen')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (SppdPengajuan $record) => in_array($record->status, ['draft', 'rejected'])),
                Tables\Actions\Action::make('submit')
                    ->label('Ajukan')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn (SppdPengajuan $record) => $record->status === 'draft')
                    ->action(function (SppdPengajuan $record) {
                        $record->update(['status' => 'pending']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Ajukan Permohonan SPPD')
                    ->modalDescription('Setelah diajukan, Anda tidak dapat mengedit pengajuan ini hingga ada keputusan dari admin.'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (SppdPengajuan $record) => in_array($record->status, ['draft', 'rejected'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => true),
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
                        Infolists\Components\TextEntry::make('nama_kegiatan')
                            ->label('Nama Kegiatan'),
                        Infolists\Components\TextEntry::make('deskripsi_kegiatan')
                            ->label('Deskripsi Kegiatan'),
                        Infolists\Components\TextEntry::make('tempat_kegiatan')
                            ->label('Tempat Kegiatan'),
                        Infolists\Components\TextEntry::make('tempat_berangkat')
                            ->label('Tempat Berangkat'),
                    ])->columns(2),

                Infolists\Components\Section::make('Jadwal Perjalanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->date(),
                        Infolists\Components\TextEntry::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->date(),
                        Infolists\Components\TextEntry::make('waktu_kegiatan')
                            ->label('Waktu Kegiatan')
                            ->time(),
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
                        Infolists\Components\TextEntry::make('invoice_image') // Ganti dari ImageEntry menjadi TextEntry
                            ->label('Gambar Faktur')
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
                    // Menyembunyikan SELURUH section jika field invoice_image tidak ada isinya,
                    // DAN hanya tampil jika status pengajuan sudah 'approved'
                    ->visible(fn (SppdPengajuan $record) => filled($record->invoice_image) && $record->status === 'approved'),



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
                            ->label('Diproses Oleh')
                            ->placeholder('Belum diproses'),
                        Infolists\Components\TextEntry::make('approved_at')
                            ->label('Tanggal Diproses')
                            ->dateTime()
                            ->placeholder('Belum diproses'),
                        Infolists\Components\TextEntry::make('catatan_admin')
                            ->label('Catatan Admin')
                            ->placeholder('Tidak ada catatan'),
                    ])->columns(2),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Pastikan hanya pengajuan milik user yang login yang ditampilkan
        return parent::getEloquentQuery()->where('user_id', auth()->id());
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
