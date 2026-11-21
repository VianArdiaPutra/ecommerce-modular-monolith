<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Password bawaan factory adalah "password".
        $this->user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@example.com',
        ]);
    }

    public function test_tamu_tidak_bisa_mengubah_pengaturan_akun(): void
    {
        $this->patch(route('account.name'), ['name' => 'X'])->assertRedirect(route('login'));
    }

    public function test_ganti_nama_berhasil(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.name'), ['name' => 'Nama Baru'])
            ->assertRedirect(route('account'))
            ->assertSessionHas('success');

        $this->assertSame('Nama Baru', $this->user->fresh()->name);
    }

    public function test_ganti_nama_kosong_ditolak(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.name'), ['name' => ''])
            ->assertSessionHasErrors('name', null, 'updateName');

        $this->assertSame('Nama Lama', $this->user->fresh()->name);
    }

    public function test_ganti_email_berhasil_dengan_password_lama_benar(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.email'), [
                'email' => 'Baru@Example.com',
                'current_password' => 'password',
            ])
            ->assertRedirect(route('account'))
            ->assertSessionHas('success');

        $this->assertSame('baru@example.com', $this->user->fresh()->email);
    }

    public function test_ganti_email_ditolak_jika_password_lama_salah(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.email'), [
                'email' => 'baru@example.com',
                'current_password' => 'salah-total',
            ])
            ->assertSessionHasErrors('current_password', null, 'updateEmail');

        $this->assertSame('lama@example.com', $this->user->fresh()->email);
    }

    public function test_ganti_email_ditolak_jika_dipakai_akun_lain(): void
    {
        User::factory()->create(['email' => 'dipakai@example.com']);

        $this->actingAs($this->user)
            ->patch(route('account.email'), [
                'email' => 'dipakai@example.com',
                'current_password' => 'password',
            ])
            ->assertSessionHasErrors('email', null, 'updateEmail');

        $this->assertSame('lama@example.com', $this->user->fresh()->email);
    }

    public function test_ganti_password_berhasil(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.password'), [
                'current_password' => 'password',
                'password' => 'passwordbaru1',
                'password_confirmation' => 'passwordbaru1',
            ])
            ->assertRedirect(route('account'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('passwordbaru1', $this->user->fresh()->password));
    }

    public function test_ganti_password_ditolak_jika_password_lama_salah(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.password'), [
                'current_password' => 'salah-total',
                'password' => 'passwordbaru1',
                'password_confirmation' => 'passwordbaru1',
            ])
            ->assertSessionHasErrors('current_password', null, 'updatePassword');

        $this->assertTrue(Hash::check('password', $this->user->fresh()->password));
    }

    public function test_ganti_password_ditolak_jika_konfirmasi_tidak_sama(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.password'), [
                'current_password' => 'password',
                'password' => 'passwordbaru1',
                'password_confirmation' => 'beda-sekali',
            ])
            ->assertSessionHasErrors('password', null, 'updatePassword');
    }

    public function test_password_baru_tidak_boleh_sama_dengan_password_lama(): void
    {
        $this->actingAs($this->user)
            ->patch(route('account.password'), [
                'current_password' => 'password',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertSessionHasErrors('password', null, 'updatePassword');
    }
}
