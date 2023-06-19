// ANCHOR CHECK KATEGORI
function checkKategori() {
    var kategori = document.getElementsByName('kategori');
    const filterKategori = [];
    filterKategori
    for (var i = 0; i < kategori.length; i++) {
        if (kategori[i].checked) {
            filterKategori.push(kategori[i].value);
        }
    }
}

// ANCHOR KEYUP INPUT HARGA

// var harga = document.getElementById('input-harga');
// harga.addEventListener('keyup', function () {
//     harga.value = formatHarga(this.value);
// });

function formatHargaRendah(angka)
{
    var harga = document.getElementById('input-harga-terendah');
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split    = number_string.split(','),
        sisa     = split[0].length % 3,
        rupiah     = split[0].substr(0, sisa),
        ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
        harga.value = rupiah;
    }
}

function formatHargaTinggi(angka)
{
    var harga = document.getElementById('input-harga-tertinggi');
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split    = number_string.split(','),
        sisa     = split[0].length % 3,
        rupiah     = split[0].substr(0, sisa),
        ribuan     = split[0].substr(sisa).match(/\d{3}/gi);
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
        harga.value = rupiah;
    }
}