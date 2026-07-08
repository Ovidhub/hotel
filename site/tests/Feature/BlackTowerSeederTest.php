<?php
// tests/Feature/BlackTowerSeederTest.php
use App\Models\Room;
use Database\Seeders\BlackTowerRoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds the four black tower rooms', function () {
    (new BlackTowerRoomSeeder())->run();
    expect(Room::count())->toBe(4);
    expect(Room::where('name', 'Luxury Suite')->value('price'))->toBe(125000);
});
