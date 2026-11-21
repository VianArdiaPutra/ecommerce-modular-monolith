<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_utama_diarahkan_ke_katalog(): void
    {
        $this->get('/')->assertRedirect('/produk');
    }

    public function test_katalog_dapat_dibuka_tanpa_login(): void
    {
        $this->get('/produk')->assertOk();
    }

    public function test_tamu_diarahkan_ke_login_saat_membuka_keranjang(): void
    {
        $this->get('/keranjang')->assertRedirect(route('login'));
    }

    public function test_customer_tidak_boleh_membuka_halaman_admin(): void
    {
        $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

        $this->actingAs($customer)->get('/admin/produk')->assertForbidden();
    }

    public function test_admin_dapat_membuka_halaman_admin(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->actingAs($admin)->get('/admin/produk')->assertOk();
    }
}
