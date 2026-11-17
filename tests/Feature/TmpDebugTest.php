<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Models\User;

uses(RefreshDatabase::class);

test('tmp debug', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user);
    $livewire = \Livewire\Livewire::test(CreateOrder::class)
        ->set('data.order_type', 'manual');
    file_put_contents('/tmp/layout2.html', $livewire->html());
});
