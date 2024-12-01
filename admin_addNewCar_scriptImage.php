<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['car_photo']['name'])) {
        $file = $_FILES['car_photo'];

        // Get file details
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed file types and max size
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (in_array($file_ext, $allowed) && $file['size'] <= $max_size && $file['error'] === 0) {
            // Unique file name and destination
            $file_name_new = uniqid() . '.' . $file_ext;
            $file_destination = 'uploads/' . $file_name_new;

            if (move_uploaded_file($file_tmp, $file_destination)) {
                echo "<script>
                        alert('Imagem carregada com sucesso!');
                        window.opener.document.getElementById('imagePreview').src = '$file_destination';
                        window.close();
                      </script>";
            } else {
                echo "Falha ao mover o arquivo enviado.";
            }
        } else {
            echo "Tipo ou tamanho de arquivo inválido!";
        }
    } else {
        echo "Nenhum arquivo foi carregado.";
    }
}
?>
