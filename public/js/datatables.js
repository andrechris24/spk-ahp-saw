const setTableColor = () => {
    document
        .querySelectorAll(".dataTables_paginate .pagination")
        .forEach((dt) => {
            dt.classList.add("pagination-primary");
        });
};
function removeBtn() {
    $("#spare-button").addClass("d-none");
}
function initError(message) {
    Toastify({
        text: "Terjadi kesalahan saat menampilkan data",
        style: {
            background: "#dc3545",
        },
        duration: 7000,
    }).showToast();
    console.error(message);
}
function errorDT(message, note) {
    Toastify({
        text: message,
        style: {
            background: "#ffc107",
        },
        destination: "https://datatables.net/tn/" + note,
        newWindow: true,
        duration: 10000,
    }).showToast();
    console.warn(message);
}
