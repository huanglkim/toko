
function rupiahjs(input) {
    var bilangan = input.value;
    var asli = input.dataset.asli;
    var number_string = bilangan.toString(),
        split = number_string.split(','),
        depankoma = split[0].replace(/[^,\d]/g, '').toString(),
        sisa = depankoma.length % 3,
        rupiah = depankoma.substr(0, sisa),
        ribuan = depankoma.substr(sisa).match(/\d{1,3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    rupiahasli = split[1] != undefined ? depankoma + '.' + split[1] : depankoma;
    document.getElementById(asli).value = depankoma == "" ? 0 : rupiahasli;
    input.value = rupiah;
}

function strtorp(bilangan) {
    // Convert the input number to a string and replace the dot with a comma for the decimal part
    var number_string = bilangan.toString().replace('.', ','),
        split = number_string.split(','),
        depankoma = split[0].replace(/[^,\d]/g, '').toString(),
        sisa = depankoma.length % 3,
        rupiah = depankoma.substr(0, sisa),
        ribuan = depankoma.substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    // Combine the integer part with the fractional part (if any)
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}
// function strtorp(bilangan) {
//     var number_string = bilangan.toString(),
//         split = number_string.split(','),
//         depankoma = split[0].replace(/[^,\d]/g, '').toString(),
//         sisa = depankoma.length % 3,
//         rupiah = depankoma.substr(0, sisa),
//         ribuan = depankoma.substr(sisa).match(/\d{1,3}/gi);

//     if (ribuan) {
//         separator = sisa ? '.' : '';
//         rupiah += separator + ribuan.join('.');
//     }
//     rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
//     return rupiah;
// }

function rptostr(bilangan) {
    var number_string = bilangan.toString(),
        split = number_string.split(','),
        depankoma = split[0].replace(/[^,\d]/g, '').toString(),
        sisa = depankoma.length % 3,
        rupiah = depankoma.substr(0, sisa),
        ribuan = depankoma.substr(sisa).match(/\d{1,3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    rupiahasli = split[1] != undefined ? depankoma + '.' + split[1] : depankoma;
    return rupiahasli;
}
