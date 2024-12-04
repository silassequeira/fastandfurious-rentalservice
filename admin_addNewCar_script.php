<?php

$car = $_SESSION['selected_car'];
$imagePath = '/' . $car['foto'];

if (isset($_SESSION['admin']) && isset($_SESSION['selected_car'])) {
    $str = '<form method="POST" action="admin_addNewCar_scriptForm.php" enctype="multipart/form-data">' .
        '<div class="layoutGrid">' .
        '<div class="infoFlex column">' .
        '<input type="hidden" name="car_id" value="' . $car['idcarro'] . '">' .

        '<label for="foto">Atualizar Imagem do Veículo</label>' .
        '<input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto">' .

        '<div id="preview-container">' .
        '<img src="' . $imagePath . '" alt="Imagem Atual" width="150">' .
        '</div>' .

        '<div id="error-message" style="color: red;"></div>' .

        '<label for="marca">Marca</label>' .
        '<input type="text" id="marca" name="marca" value="' . $car['marca'] . '" required>' .

        '<label for="modelo">Modelo</label>' .
        '<input type="text" id="modelo" name="modelo" value="' . $car['modelo'] . '" required>' .

        '<label for="ano">Ano</label>' .
        '<input type="number" id="ano" name="ano" min="1900" max="2100" value="' . $car['ano'] . '" required>' .

        '<label for="assentos">Números de Lugares</label>' .
        '<select id="assentos" name="assentos" required>' .
        '<option value="2" ' . ($car['assentos'] == 2 ? 'selected' : '') . '>2</option>' .
        '<option value="3" ' . ($car['assentos'] == 3 ? 'selected' : '') . '>3</option>' .
        '<option value="4" ' . ($car['assentos'] == 4 ? 'selected' : '') . '>4</option>' .
        '<option value="5" ' . ($car['assentos'] == 5 ? 'selected' : '') . '>5</option>' .
        '</select>' .

        '<label for="valordiario">Preço Diário</label>' .
        '<input type="text" id="valordiario" name="valordiario" value="' . $car['valordiario'] . '" required>' .

        '<div class="buttonContainer">' .
        '<input type="submit" name="submitUpdateCar" value="Atualizar">' .
        '</div>' .
        '</div>' .
        '</div>' .
        '</form>';

    echo $str;
} else {

    $str = '<form method="POST" action="admin_addNewCar_scriptForm.php" enctype="multipart/form-data">' .
        '<div class="layoutGrid">' .
        '<div class="infoFlex column">' .
        '<label for="foto">Adicionar Nova Imagem do Veículo</label>' .
        '<input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto" required>' .
        '<div id="preview-container">' .
        '<p>No image selected</p>' .
        '</div>' .
        '<div id="error-message" style="color: red;"></div>' .

        '<label for="marca">Marca</label>' .
        '<input type="text" id="marca" name="marca" required>' .

        '<label for="modelo">Modelo</label>' .
        '<input type="text" id="modelo" name="modelo" required>' .

        '<label for="ano">Ano</label>' .
        '<input type="number" id="ano" name="ano" min="1900" max="2100" required>' .

        '<label for="assentos">Números de Lugares</label>' .
        '<select id="assentos" name="assentos" required>' .
        '<option value="2">2</option>' .
        '<option value="3">3</option>' .
        '<option value="4">4</option>' .
        '<option value="5">5</option>' .
        '</select>' .

        '<label for="valordiario">Preço Diário</label>' .
        '<input type="text" id="valordiario" name="valordiario" required>' .

        '<div class="buttonContainer">' .
        '<input type="submit" name="submitNewCar" value="Guardar" id="submitNewCar" disabled>' .
        '</div>' .
        '</div>' .
        '</div>' .
        '</form>';

    echo $str;

}