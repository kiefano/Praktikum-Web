<?= $this->extend('layout') ?>

<?php if ($msg = session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger mb-3">
        <?= esc($msg) ?>
    </div>
<?php endif; ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-5 ">
        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>

        <?= form_input([
    'type' => 'hidden',
    'name' => 'total_harga',
    'id'   => 'total_harga']) ?>

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
        'name'     => 'nama',
        'id'       => 'nama',
        'class'    => 'form-control',
        'value'    => session()->get('username'),
        'readonly' => true]) ?>
        </div>
        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
        'name'  => 'alamat',
        'id'    => 'alamat',
        'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>
        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <div class="input-group">
                <span class="input-group-text">IDR</span>
                <?= form_input([
            'name'     => 'ongkir',
            'id'       => 'ongkir',
            'class'    => 'form-control',
            'value'    => '0',
            'readonly' => true]) ?>
            </div>
        </div>
        <div class="col-12">
            <?= form_label('Kode Kupon', 'kupon_code', ['class' => 'form-label']) ?>
            <div class="input-group">
                <?= form_input([
            'name'        => 'kupon_code',
            'id'          => 'kupon_code',
            'class'       => 'form-control',
            'placeholder' => 'Masukkan kode kupon (opsional)']) ?>
                <button class="btn btn-outline-secondary" type="button" id="btn_apply_kupon">Terapkan</button>
            </div>
            <div id="kupon_info" class="form-text">
                <p>Tersedia : FLASH10, FLASH15, MEMBER20</p>
            </div>
        </div>
        <div class="col-12">
            <?= form_submit(
        'submit',
        'Buat Pesanan',
        ['class' => 'btn btn-primary']) ?>
        </div>

        <?= form_close() ?>
    </div>

    <div class="col-lg-7">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col-2">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)) :
                    foreach ($items as $index => $item) :
                ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                </tr>
                <?php
                    endforeach;
                endif;
                ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Diskon Kupon</td>
                    <td class="text-danger"><span id="diskon_kupon_display">- IDR 0</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>PPN (11%)</td>
                    <td> <?= number_to_currency($ppn ?? 0, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Biaya Admin</td>
                    <td> <?= number_to_currency($biaya_admin ?? 0, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Ongkir</td>
                    <td><span id="ongkir_display">IDR 0</span></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td><strong>Grand Total</strong></td>
                    <td><strong><span id="total">-</span></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    let ppn = <?= $ppn ?? 0 ?>;
    let biaya_admin = <?= $biaya_admin ?? 0 ?>;
    let diskon_kupon = 0;

    // Daftar kupon valid (validasi sisi client untuk UX, validasi final di server)
    const kuponList = {
        'FLASH10': 0.10,
        'FLASH15': 0.15,
        'MEMBER20': 0.20,
    };

    hitungTotal();

    function hitungTotal() {
        let grandTotal = subtotal - diskon_kupon + ppn + biaya_admin + ongkir;
        if (grandTotal < 0) grandTotal = 0;

        $("#ongkir").val(ongkir);
        $("#ongkir_display").text(`IDR ${ongkir.toLocaleString('id-ID')}`);
        $("#total").text(`IDR ${grandTotal.toLocaleString('id-ID')}`);
        $("#total_harga").val(grandTotal);
    }

    // Tombol terapkan kupon
    $("#btn_apply_kupon").on('click', function() {
        let kode = $("#kupon_code").val().trim().toUpperCase();
        let infoEl = $("#kupon_info");

        if (kode === '') {
            diskon_kupon = 0;
            $("#diskon_kupon_display").text('- IDR 0');
            infoEl.removeClass('text-success text-danger').text('');
            hitungTotal();
            return;
        }

        if (kuponList[kode] !== undefined) {
            diskon_kupon = Math.floor(subtotal * kuponList[kode]);
            let persen = kuponList[kode] * 100;
            $("#diskon_kupon_display").text(`- IDR ${diskon_kupon.toLocaleString('id-ID')}`);
            infoEl.removeClass('text-danger').addClass('text-success')
                .text(`✓ Kupon ${kode} berhasil diterapkan (${persen}%)`);
        } else {
            diskon_kupon = 0;
            $("#diskon_kupon_display").text('- IDR 0');
            infoEl.removeClass('text-success').addClass('text-danger')
                .text('✗ Kode kupon tidak valid');
        }

        hitungTotal();
    });

    // Select2 untuk kelurahan
    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan',
        minimumInputLength: 3,
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    $("#kelurahan").on('change', function() {
        let id_kelurahan = $(this).val();

        $("#layanan").empty();
        ongkir = 0;
        hitungTotal();

        $.ajax({
            url: "<?= site_url('ajax/costs') ?>",
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function(data) {
                data.forEach(function(item) {
                    $("#layanan").append(
                        $('<option>', {
                            value: item.cost,
                            text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                        })
                    );
                });
                // trigger perubahan awal layanan pertama
                $("#layanan").trigger('change');
            }
        });
    });

    $("#layanan").on('change', function() {
        let val = $(this).val();
        ongkir = val ? parseInt(val) : 0;
        hitungTotal();
    });
});
</script>
<?= $this->endSection() ?>