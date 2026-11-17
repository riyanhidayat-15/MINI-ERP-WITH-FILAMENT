<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Models\User;

uses(RefreshDatabase::class);

test('layout debug', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);
    $livewire = \Livewire\Livewire::test(CreateOrder::class);
    $html = $livewire->html();
    file_put_contents('/tmp/layout.html', $html);
});
