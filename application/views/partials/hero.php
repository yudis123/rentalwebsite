<?php
 defined('BASEPATH') OR exit('No direct script access allowed');
 $wa_general = wa_link($site['whatsapp'], 'Halo '.$site['site_name'].', saya ingin menyewa mobil.');
 $hero_image = $site['hero_image'] !== '' ? asset_url($site['hero_image']) : base_url('assets/img/brand/hero-mpv.png');
?>
<section id="beranda" class="rental-hero">
 <div class="container-page hero-layout">
  <div class="hero-copy">
   <p class="eyebrow">TEMAN DI SETIAP PERJALANAN</p>
   <h1><?= e($site['hero_title_1']) ?> <span><?= e($site['hero_title_accent']) ?></span> <?= e($site['hero_title_2']) ?></h1>
   <p class="hero-description"><?= e($site['hero_description']) ?></p>
   <div class="hero-actions">
    <a href="#armada" class="btn-primary"><?= e($site['hero_btn_primary']) ?></a>
    <a href="<?= e($wa_general) ?>" target="_blank" rel="noopener" class="btn-outline"><?= e($site['hero_btn_secondary']) ?></a>
   </div>
   <div class="hero-note"><span class="note-icon">&#10003;</span> Pilih mobilnya, rencanakan perjalananmu.</div>
  </div>
  <div class="hero-art">
   <img class="route-art" src="<?= base_url('assets/img/brand/route.svg') ?>" alt="" width="720" height="480">
   <div class="hero-orbit"></div>
   <img class="hero-vehicle" src="<?= $hero_image ?>" alt="<?= $site['hero_image'] !== '' ? e($site['site_name']) : 'Ilustrasi mobil keluarga untuk perjalanan Anda' ?>" fetchpriority="high" width="1774" height="887">
   <img class="hero-mascot" src="<?= base_url('assets/img/brand/reno-wave.png') ?>" alt="Reno, maskot kunci mobil, menyapa Anda" width="1312" height="1199">
   <?php if ($min_price > 0): ?><div class="price-float"><span>Teman jalan mulai dari</span><strong><?= rupiah($min_price) ?><small> / hari</small></strong></div><?php endif; ?>
   <span class="mascot-label">Hai, aku Reno!</span>
  </div>
 </div>
 <div class="container-page"><div class="benefit-strip">
  <div><span class="benefit-icon">&#9671;</span><p><strong><?= (int) $car_count ?> pilihan armada</strong><span>Mobil untuk berbagai kebutuhan</span></p></div>
  <div><span class="benefit-icon">&#10003;</span><p><strong>Harga per hari</strong><span>Mudah dibandingkan, mudah dipilih</span></p></div>
  <div><span class="benefit-icon">&#9743;</span><p><strong>Langsung lewat WhatsApp</strong><span>Diskusikan jadwal dengan tim kami</span></p></div>
 </div></div>
</section>
