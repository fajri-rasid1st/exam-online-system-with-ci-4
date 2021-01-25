<h1>Hai, Apa Kabar!</h1>

<h2>Ini adalah email aktivasi untuk akun kamu di <?= site_url() ?>.</h2>

<p>Untuk mengaktifkan akun, silahkan klik URL di bawah ini:</p>

<p>
    <a href="<?= site_url('activate-account') . '?token=' . $hash ?>">Aktifkan Akun Saya</a>.
</p>

<br>

<strong>
    <sup>*</sup>
    Jika kamu tidak pernah melakukan registrasi pada website kami, silahkan abaikan email ini.
</strong>