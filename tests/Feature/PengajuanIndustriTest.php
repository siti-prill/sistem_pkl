<?php

namespace Tests\Feature;

use App\Models\Industri;
use App\Models\PengajuanPkl;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanIndustriTest extends TestCase
{
    use RefreshDatabase;

    private function siswaUser(): User
    {
        $user = User::factory()->create(['role' => 'siswa']);
        Siswa::factory()->create(['user_id' => $user->id, 'jurusan' => 'XII TKJ 1']);

        return $user;
    }

    public function test_create_page_shows_industries_grouped_by_lokasi(): void
    {
        Industri::factory()->create(['nama_perusahaan' => 'PT Padang Teknologi', 'lokasi' => 'Padang', 'status' => 'aktif']);
        Industri::factory()->create(['nama_perusahaan' => 'PT Bandung Jaya', 'lokasi' => 'Bandung', 'status' => 'aktif']);
        Industri::factory()->create(['nama_perusahaan' => 'PT Padang Lama', 'lokasi' => 'Padang', 'status' => 'tidak_aktif']);

        $response = $this->actingAs($this->siswaUser())->get(route('siswa.pengajuan.create'));

        $response->assertOk();
        $response->assertSee('PT Padang Teknologi');
        $response->assertSee('PT Bandung Jaya');
        $response->assertDontSee('PT Padang Lama');
    }

    public function test_store_saves_industri_choices(): void
    {
        $user = $this->siswaUser();

        $response = $this->actingAs($user)->post(route('siswa.pengajuan.store'), [
            'pilihan_1' => 'Padang',
            'industri_1' => 'PT Padang Teknologi',
            'pilihan_2' => 'Bandung',
            'industri_2' => 'PT Bandung Jaya',
            'jurusan' => 'TKJ',
            'penghasilan_ortu' => 3000000,
        ]);

        $response->assertRedirect(route('siswa.pengajuan.index'));

        $pengajuan = PengajuanPkl::where('siswa_id', $user->siswa->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('PT Padang Teknologi', $pengajuan->industri_1);
        $this->assertEquals('PT Bandung Jaya', $pengajuan->industri_2);
    }

    public function test_admin_can_update_logo_via_settings(): void
    {
        Storage::fake('public');

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
        Storage::disk('local')->put('test-logo.png', $png);
        $file = new UploadedFile(Storage::disk('local')->path('test-logo.png'), 'logo.png', 'image/png', null, true);

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'logo' => $file,
        ]);

        $response->assertRedirect(route('admin.settings.index'));

        $this->assertNotNull(Setting::get('logo_path'));
        Storage::disk('public')->assertExists(Setting::get('logo_path'));
    }
}
