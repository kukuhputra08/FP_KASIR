let countItem = 1;

const itemCountSpan = document.getElementById('itemCount');
const increamentButton = document.getElementById('increaseCount');
const decremeantButton = document.getElementById('decreaseCount');
const totalHargaElement = document.getElementById('total');

let hargaPerItem = 0;

increamentButton.addEventListener('click', () => {
    countItem++;
    itemCountSpan.textContent = countItem;
    updateTotalHarga();
});

decremeantButton.addEventListener('click', () => {
    if (countItem > 1) {
        countItem--;
        itemCountSpan.textContent = countItem;
        updateTotalHarga();
    }
});

function updateTotalHarga() {
    const totalHarga = hargaPerItem * countItem;
    totalHargaElement.textContent = `Rp. ${totalHarga.toLocaleString('id-ID')}`;

    document.getElementById('hiddenJumlah').value = countItem;
}

document.addEventListener('DOMContentLoaded', () => {
    const menuModal = document.getElementById('menuModal');

    menuModal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;

        const nama = button.getAttribute('data-nama');
        hargaPerItem = parseInt(button.getAttribute('data-harga'), 10);

        document.getElementById('menuNama').textContent = nama;
        document.getElementById('menuHarga').textContent = `Rp. ${hargaPerItem.toLocaleString('id-ID')}`;
        countItem = 1; 
        itemCountSpan.textContent = countItem;

        updateTotalHarga();
    });
});

const menuModal = document.getElementById('menuModal');

menuModal.addEventListener('show.bs.modal', (event) => {
    const button = event.relatedTarget; // Tombol yang memicu modal
    const namaMenu = button.getAttribute('data-nama');
    const hargaMenu = button.getAttribute('data-harga');

    // Masukkan data ke input hidden
    document.getElementById('hiddenNama').value = namaMenu;
    document.getElementById('hiddenHarga').value = hargaMenu;
    document.getElementById('hiddenJumlah').value = 1; // Default jumlah awal

    // Perbarui tampilan di modal
    document.getElementById('menuNama').innerText = namaMenu;
    document.getElementById('menuHarga').innerText = hargaMenu;
});
