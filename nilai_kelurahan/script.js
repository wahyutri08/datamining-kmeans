function edit(params) {
    var javascript_data = '<?php echo json_encode($atribut); ?>';
    // Parse JSON string menjadi array JavaScript
var data = JSON.parse(javascript_data);

// Membuat array kosong untuk menampung nama atribut
var namaAtributArray = [];

// Loop melalui setiap elemen dalam array dan menambahkan nilai 'nama_atribut' ke dalam array namaAtributArray
data.forEach(function(item) {
    // Membuat variabel dengan nama dinamis berdasarkan kombinasi dari nilai id_atribut dan nama_atribut
    var namaVariabel = 'atribut_' + item.idatribut;

    // Memberikan nilai pada variabel dinamis
    window[namaVariabel] = $('#id_keluran').val();
});

// Contoh penggunaan variabel yang telah dibuat secara dinamis
console.log(atribut_1);
console.log(atribut_2);
console.log(atribut_3);
console.log(atribut_4);
console.log (params, javascript_data)
}