<?php
$connection = pg_connect("dbname=postgres user=postgres password=postgres host=localhost port=5432");


# Fetches all brands from the car table that aren't hidden or rented
$brandQuery = "SELECT DISTINCT marca FROM carro WHERE ocultado = false AND arrendado = false";
$brandResult = pg_query($connection, $brandQuery);

if (!$brandResult) {
    die("Erro ao buscar marcas: " . pg_last_error($connection));
}

$brands = pg_fetch_all_columns($brandResult, 0);

$str = '<form class="marginTop gap" method="POST">
    <div class="layoutGridAutoFit maxWidth">
        <span class="reference maxWidth">
            <label for="min-price">Preço Mínimo</label>
            <input type="number" id="min-price" name="min-price" placeholder="Preço Mínimo">
        </span>
        <span class="reference maxWidth">
            <label for="max-price">Preço Máximo</label>
            <input type="number" id="max-price" name="max-price" placeholder="Preço Máximo">
        </span>
        <span class="reference maxWidth">
            <label for="car-brand">Marca</label>
            <select name="car-brand">
                <option value="">Todas as Marcas</option>';

foreach ($brands as $brand) {
    $str .= '<option value="' . $brand . '">' . $brand . '</option>';
}

$str .= '    </select>
        </span>
        <span class="reference maxWidth">
            <label for="assentos">Assentos</label>
            <select id="assentos" name="assentos">
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </span>
        <div class="infoFlex maxWidth">
        <span>
            <input type="submit" class="redBackground whiteFont noPadding" name="submitFilter" value="Filtrar">
        </span>
        </div>
        </div>
    </form>';



echo $str;