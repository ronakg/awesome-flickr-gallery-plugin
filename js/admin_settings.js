function verifyPerPageBlank() {
    var per_page = document.getElementById("afg_per_page");
    var submit_button = document.getElementById('afg_save_changes');
    if (per_page.value == "") {
        alert('Per Page can not be blank.');
        submit_button.disabled = true;
        per_page.focus();
        return;
    }
    submit_button.disabled = false;
}
