<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_profile()
    {
        $response = $this->get('/akun');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_kriteria(){
        $response = $this->get('/kriteria');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_subkriteria(){
        $response = $this->get('/kriteria/sub');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_bobotkriteria(){
        $response = $this->get('/bobot');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_hasilkriteria(){
        $response = $this->get('/bobot/hasil');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_resetkriteria(){
        $response = $this->get('/bobot/reset');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_bobotsubkriteria(){
        $response = $this->get('/bobot/sub');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_compsubkriteria(){
        $response = $this->get('/bobot/sub/comp');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_hasilsubkriteria(){
        $response = $this->get('/bobot/sub/hasil/1');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_resetsubkriteria(){
        $response = $this->get('/bobot/sub/reset/1');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_alternatif(){
        $response = $this->get('/alternatif');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_hasil(){
        $response = $this->get('/alternatif/hasil');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_nilai(){
        $response = $this->get('/alternatif/nilai');
        $this->assertGuest();
        $response->assertStatus(302);
    }
    public function test_ranking(){
        $response = $this->get('/ranking');
        $this->assertGuest();
        $response->assertStatus(302);
    }
}
