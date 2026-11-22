<?php

namespace Tests\Feature;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GuestCodeTest extends TestCase
{
    use RefreshDatabase;

    private function makeGuest(string $code = 'AB12CD'): Guest
    {
        return Guest::create([
            'name' => 'Tamu Uji',
            'phone' => '0800',
            'invitation_count' => 2,
            'unique_code' => Guest::fullCode($code),
            'qr_token' => Str::random(64),
            'rsvp_status' => 'pending',
            'checkin_status' => 'not_arrived',
        ]);
    }

    private function staff(): User
    {
        $user = new User();
        $user->forceFill([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('secret-test'),
            'role' => 'admin',
        ])->save();

        return $user;
    }

    public function test_codes_use_nodetone_prefix(): void
    {
        $guest = $this->makeGuest('ab12cd');

        $this->assertSame('NT-AB12CD', $guest->unique_code);
        $this->assertSame('AB12CD', $guest->short_code);
    }

    public function test_invitation_page_opens_with_short_code(): void
    {
        $this->makeGuest();

        $this->get('/undangan/AB12CD')->assertOk()->assertSee('Tamu Uji');
        $this->get('/undangan/NT-AB12CD')->assertNotFound();
    }

    public function test_scanner_finds_guest_from_invitation_qr_url(): void
    {
        $guest = $this->makeGuest();

        // Scanner sends the last path segment of the QR URL.
        $this->actingAs($this->staff())
            ->postJson('/admin/checkin/find', ['token' => 'AB12CD'])
            ->assertOk()
            ->assertJsonPath('guest.id', $guest->id);
    }

    public function test_scanner_still_accepts_raw_qr_token(): void
    {
        $guest = $this->makeGuest();

        $this->actingAs($this->staff())
            ->postJson('/admin/checkin/find', ['token' => $guest->qr_token])
            ->assertOk()
            ->assertJsonPath('guest.id', $guest->id);
    }

    public function test_scanner_rejects_unknown_code(): void
    {
        $this->makeGuest();

        $this->actingAs($this->staff())
            ->postJson('/admin/checkin/find', ['token' => 'ZZZZZZ'])
            ->assertNotFound();
    }
}
