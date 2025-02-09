<?php

$car = $_SESSION['selected_car'] ?? null;
$imagePath = $car ? '/' . $car['foto'] : '';

# Code dinamically adds HTML code related to the selected car
if (isset($_SESSION['admin']) && isset($car)) {
    $str =
        '<div class="infoFlex">' .
        '<a href="admin_visualizeAllCars.php" class="back"> &lt; Voltar</a>' .
        '<h2>Modificar Carro</h2>' .
        '</div>' .
        '<form method="POST" action="admin_addNewCar_scriptForm.php" enctype="multipart/form-data">' .
        '<div class="layoutGrid" style=" justify-items: unset; padding-left: 1rem; width: 100%;">' .
        '<div class="infoFlex column maxWidth">' .
        '<input type="hidden" name="car_id" value="' . $car['idcarro'] . '">' .
        '<label for="foto">Atualizar Imagem do Veículo</label>' .
        '<div id="preview-container">' .
        '<img src="' . $imagePath . '" alt="Imagem Atual" width="150">' .
        '</div>' .
        '<input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto">' .

        '</div>' .

        '<div class="infoFlex column maxWidth" style="padding-left: 2.2rem;">';
    if (isset($_SESSION['errorNewCar'])) {
        $str .= '<p class="marginFlex redFont">&#9888; ' . $_SESSION['errorNewCar'] . '</p>';
        unset($_SESSION['errorNewCar']);
    }

    $str .=
        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span class="reference">' .
        '<label for="marca">Marca</label>' .
        '<input type="text" id="marca" name="marca" value="' . $car['marca'] . '" required>' .
        '</span>' .
        '</div>' .
        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span class="reference">' .
        '<label for="modelo">Modelo</label>' .
        '<input type="text" id="modelo" name="modelo" value="' . $car['modelo'] . '" required>' .
        '</span>' .
        '</div>' .

        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span>' .
        '<label for="ano">Ano</label>' .
        '<input type="number" id="ano" name="ano" min="1900" max="2100" value="' . $car['ano'] . '" required>' .
        '</span>' .

        '<span class="reference">' .
        '<label for="assentos">Assentos</label>' .
        '<select id="assentos" name="assentos" required>' .
        '<option value="2" ' . ($car['assentos'] == 2 ? 'selected' : '') . '>2</option>' .
        '<option value="3" ' . ($car['assentos'] == 3 ? 'selected' : '') . '>3</option>' .
        '<option value="4" ' . ($car['assentos'] == 4 ? 'selected' : '') . '>4</option>' .
        '<option value="5" ' . ($car['assentos'] == 5 ? 'selected' : '') . '>5</option>' .
        '</select>' .
        '</span>' .
        '</div>' .

        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span>' .
        '<label for="valordiario">Preço Diário</label>' .
        '<input type="text" id="valordiario" name="valordiario" value="' . $car['valordiario'] . '" required>' .
        '</span>' .

        '<div class="infoFlex buttonContainer maxWidth">' .
        '<span>' .
        '<input type="submit" class="noPadding" name="submitUpdateCar" value="Atualizar">' .
        '</div>' .
        '</span>' .
        '</div>' .
        '</div>' .
        '</div>' .
        '</form>';

    echo $str;

    # Adds HTML code to add a new car
} else {

    $str =
        '<div class="infoFlex">' .
        '<a href="admin_visualizeAllCars.php" class="back"> &lt; Voltar</a>' .
        '<h2>Adicionar Carro</h2>' .
        '</div>' .
        '<form method="POST" action="admin_addNewCar_scriptForm.php" enctype="multipart/form-data">' .
        '<div class="layoutGrid" style=" justify-items: unset; padding-left: 1rem; width: 100%;" >' .
        '<div class="infoFlex column maxWidth marginTopSmall pointer"> ' .
        '<label for="foto">Adicionar Nova Imagem do Veículo</label>' .
        '<div id="preview-container">' .
        '<p>No image selected</p>' .
        '</div>' .
        '<input type="file" id="foto" accept="image/png, image/jpg, image/jpeg" name="foto" required>' .
        '<div id="error-message" style="color: red;"></div>' .
        '</div>' .

        '<div class="infoFlex column maxWidth" style="padding-left: 2.2rem;">';
    if (isset($_SESSION['errorNewCar'])) {
        $str .= '<p class="marginFlex redFont">&#9888; ' . $_SESSION['errorNewCar'] . '</p>';
        unset($_SESSION['errorNewCar']);
    }

    $str .=
        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span class="reference">' .
        '<label for="marca">Marca</label>' .
        '<input type="text" id="marca" name="marca" required>' .
        '</span>' .
        '</div>' .
        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span class="reference">' .
        '<label for="modelo">Modelo</label>' .
        '<input type="text" id="modelo" name="modelo" required>' .
        '</span>' .
        '</div>' .

        '<div class="infoFlex noMargin normalGap maxWidth">' .
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

        '<div class="infoFlex noMargin normalGap maxWidth">' .
        '<span>' .
        '<label for="valordiario">Preço Diário</label>' .
        '<input type="text" id="valordiario" name="valordiario" required>' .
        '</span>' .

        '<span>' .
        '<div class="buttonContainer maxWidth">' .
        '<input type="submit" class="noPadding" name="submitNewCar" value="Guardar" id="submitNewCar" disabled>' .
        '</span>' .

        '</div>' .
        '</div>' .
        '</div>' .
        '</form>' .
        '</div>';

    echo $str;

}