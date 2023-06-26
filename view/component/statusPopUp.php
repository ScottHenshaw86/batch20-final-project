<?php
if (isset($_GET['error']) and $_GET['error'] == "true") {
    echo '<link rel="stylesheet" href="public/css/popUp.css">';
} else {
    echo '<link rel="stylesheet" href="public/css/popUp.css">';
}
?>

<dialog>

    <button class="x-btn" onclick="closeModal()">x</button>
    <div class="modal-content">
        <p class="modal-message"><?= $_GET['message'] ?></p>
        <button class="confirm-btn" onclick="closeModal()">Confirm</button>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>

</dialog>

<script src="./public/js/popUp.js"></script>