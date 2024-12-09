<?php

$car = $_SESSION['selected_car'] ?? null;
$imagePath = $car ? '/' . $car['foto'] : '';

# Code dinamically adds HTML code related to the selected car
if (isset($_SESSION['admin']) && isset($car)) {
    $str = '<form method="POST" action="admin_addNewCar_scriptForm.php" enctype="multipart/form-data">' .
        '<div class="layoutGrid">' .
        '<div class="infoFlex column marginFlex">' .
        '<input type="hidden" name="car_id" value="' . $car['idcarro'] . '">' .

        '<label for="foto">Atualizar Imagem do Veículo</label>' .
        '<input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto">' .

        '<div id="preview-container">' .
        '<img src="' . $imagePath . '" alt="Imagem Atual" width="150">' .
        '</div>' .
        '</div>' .

        '<div class="infoFlex column marginFlex">' .
        '<div id="error-message" style="color: red;"></div>' .

        '<div class="infoFlex noMargin normalGap">' .
        '<span class="reference">' .
        '<label for="marca">Marca</label>' .
        '<input type="text" id="marca" name="marca" value="' . $car['marca'] . '" required>' .
        '</span>' .
        '</div>' .
        '<div class="infoFlex noMargin normalGap">' .
        '<span class="reference">' .
        '<label for="modelo">Modelo</label>' .
        '<input type="text" id="modelo" name="modelo" value="' . $car['modelo'] . '" required>' .
        '</span>' .
        '</div>' .

        '<div class="infoFlex noMargin normalGap">' .
        '<span>' .
        '<label for="ano">Ano</label>' .
        '<input type="number" id="ano" name="ano" min="1900" max="2100" value="' . $car['ano'] . '" required>' .
        '</span>' .

        '<span>' .
        '<label for="assentos">Números de Lugares</label>' .
        '<select id="assentos" name="assentos" required>' .
        '<option value="2" ' . ($car['assentos'] == 2 ? 'selected' : '') . '>2</option>' .
        '<option value="3" ' . ($car['assentos'] == 3 ? 'selected' : '') . '>3</option>' .
        '<option value="4" ' . ($car['assentos'] == 4 ? 'selected' : '') . '>4</option>' .
        '<option value="5" ' . ($car['assentos'] == 5 ? 'selected' : '') . '>5</option>' .
        '</select>' .
        '</span>' .
        '</div>' .

        '<div class="infoFlex noMargin normalGap">' .
        '<span>' .
        '<label for="valordiario">Preço Diário</label>' .
        '<input type="text" id="valordiario" name="valordiario" value="' . $car['valordiario'] . '" required>' .
        '</span>' .

        '<div class="buttonContainer">' .
        '<span>' .
        '<input type="submit" name="submitUpdateCar" value="Atualizar">' .
        '</div>' .
        '</span>' .
        '</div>' .
        '</div>' .
        '</div>' .
        '</form>';

    echo $str;

    # Adds HTML code to add a new car
} else {

    $str = '<form method="POST" action="admin_addNewCar_scriptForm.php" enctype="multipart/form-data">' .
        '<div class="layoutGrid">' .
        '<div class="infoFlex column marginFlex"> ' .
        '<label for="foto">Adicionar Nova Imagem do Veículo</label>' .
        '<div id="preview-container">' .
        '<p>No image selected</p>' .
        '</div>' .
        '<input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto" required>' .
        '<div id="error-message" style="color: red;"></div>' .
        '</div>' .

        '<div class="infoFlex column marginFlex">' .

        '<div class="infoFlex noMargin normalGap">' .
        '<span class="reference">' .
        '<label for="marca">Marca</label>' .
        '<input type="text" id="marca" name="marca" required>' .
        '</span>' .
        '</div>' .
        '<div class="infoFlex noMargin normalGap">' .
        '<span class="reference">' .
        '<label for="modelo">Modelo</label>' .
        '<input type="text" id="modelo" name="modelo" required>' .
        '</span>' .
        '</div>' .

        '<div class="infoFlex noMargin normalGap">' .
        '<span>' .
        '<label for="ano">Ano</label>' .
        '<input type="number" id="ano" name="ano" min="1900" max="2100" required>' .
        '</span>' .

        '<span class="reference">' .
        '<label for="assentos">Assentos</label>' .
        '<select id="assentos" name="assentos" required>' .
        '<option value="2">2</option>' .
        '<option value="3">3</option>' .
        '<option value="4">4</option>' .
        '<option value="5">5</option>' .
        '</select>' .
        '</span>' .

        '</div>' .

        '<div class="infoFlex noMargin normalGap">' .
        '<span>' .
        '<label for="valordiario">Preço Diário</label>' .
        '<input type="text" id="valordiario" name="valordiario" required>' .
        '</span>' .

        '<span>' .
        '<div class="buttonContainer">' .
        '<input type="submit" class="noPadding" name="submitNewCar" value="Guardar" id="submitNewCar" disabled>' .
        '</span>' .

        '</div>' .
        '</div>' .
        '</div>' .
        '</form>' .
        '</div>';

    echo $str;

}