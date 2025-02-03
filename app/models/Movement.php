<?php

/** Definición del modelo con su tipo de dato e importo el fichero JSON
 **/
class Movement
{
    private int $id;
    private string $productCode;
    private string $productNAme;
    private string $fromBatchNumber;
    private string $toBatchNumber;
    private string $fromLocation;
    private string $toLocation;
    private int $quantity;
    private string $movementId;
    private string $movementDate;
    private string $customer;
    private string $supplier;

    // Constructor para inicializar propiedades

    public function __construct(
        int $id,
        string $productCode,
        string $productNAme,
        string $fromBatchNumber,
        string $toBatchNumber,
        string $fromLocation,
        string $toLocation,
        int $quantity,
        string $movementId,
        string $movementDate,
        string $customer,
        string $supplier,
    ) {
        $this->id = $id;
        $this->productCode = $productCode;
        $this->productNAme = $productNAme;
        $this->fromBatchNumber = $fromBatchNumber;
        $this->toBatchNumber = $toBatchNumber;
        $this->fromLocation = $fromLocation;
        $this->toLocation = $toLocation;
        $this->quantity = $quantity;
        $this->movementId = $movementId;
        $this->movementDate = $movementDate;
        $this->customer = $customer;
        $this->supplier = $supplier;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getProductCode()
    {
        return $this->productCode;
    }
    public function getProductName()
    {
        return $this->productNAme;
    }
    public function getFromBatchNumber()
    {
        return $this->fromBatchNumber;
    }
    public function getToBatchNumber()
    {
        return $this->toBatchNumber;
    }
    public function getFromLocation()
    {
        return $this->fromLocation;
    }
    public function getToLocation()
    {
        return $this->toLocation;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getMovementId()
    {
        return $this->movementId;
    }
    public function getMovementDate()
    {
        return $this->movementDate;
    }
    public function getCustomer()
    {
        return $this->customer;
    }
    public function getSupplier()
    {
        return $this->supplier;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;
    }
    public function setProductName($productNAme)
    {
        $this->productNAme = $productNAme;
    }
    public function setFromBatchNumber($fromBatchNumber)
    {
        $this->fromBatchNumber = $fromBatchNumber;
    }
    public function setToBatchNumber($toBatchNumber)
    {
        $this->toBatchNumber = $toBatchNumber;
    }
    public function setFromLocation($fromLocation)
    {
        $this->fromLocation = $fromLocation;
    }
    public function setToLocation($toLocation)
    {
        $this->toLocation = $toLocation;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function setMovementId($movementId)
    {
        $this->movementId = $movementId;
    }
    public function setMovementDate($movementDate)
    {
        $this->movementDate = $movementDate;
    }
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    }

    public static function getFilePath()
    { // Por visualización he creado esta función decodificando el JSON y poder usarlo en las otras funciones
        return __DIR__ . '/../../data/movement.json'; // Ruta del archivo JSON
    }

    private static function datosJsonParseados()
    { // Por visualización he creado esta función decodificando el JSON y poder usarlo en las otras funciones
        return json_decode(self::getAll(), true);
    }

    // Método estático getAll para obtener todos los datos del JSON
    public static function getAll()
    {
        $filePath = self::getFilePath(); // Ruta del archivo JSON
        $jsonData = file_get_contents($filePath); // Leo el archivo JSON
        return $jsonData; // Retorno el array con los datos
    }

    public static function getById($id)
    {
        $dataArray = self::datosJsonParseados();
        return getElementById($dataArray, $id);

    }
    public static function getByData($data)
    {
        $dataArray = self::datosJsonParseados();

        // Filtro datos
        // Serán opcionales todas las opciones de búsqueda, filtrándose según se le pasen los criterios
        $filteredData = array_filter($dataArray, function ($item) use ($data) {

            // Verifico coincidencia de productCode para que muestre todos los elementos en caso  que no se asigne ningún valor ['productCode'] === ''
            // Lo mismo sucederá con los siguientes criterios de búsqueda
            $matchProductCode = !isset($data['productCode']) ||
                $data['productCode'] === '' ||
                (isset($item['productCode'], $data['productCode']) &&
                    $item['productCode'] === $data['productCode']);

            $matchFromBatchNumber = !isset($data['fromBatchNumber']) ||
                $data['fromBatchNumber'] === '' ||
                (isset($item['fromBatchNumber'], $data['fromBatchNumber']) &&
                    $item['fromBatchNumber'] === $data['fromBatchNumber']);

            $matchToBatchNumber = !isset($data['toBatchNumber']) ||
                $data['toBatchNumber'] === '' ||
                (isset($item['toBatchNumber'], $data['toBatchNumber']) &&
                    $item['toBatchNumber'] === $data['toBatchNumber']);

            $matchFromLocation = !isset($data['fromLocation']) ||
                $data['fromLocation'] === '' ||
                (isset($item['fromLocation'], $data['fromLocation']) &&
                    $item['fromLocation'] === $data['fromLocation']);

            $matchToLocation = !isset($data['toLocation']) ||
                $data['toLocation'] === '' ||
                (isset($item['toLocation'], $data['toLocation']) &&
                    $item['toLocation'] === $data['toLocation']);

            $matchQuantity = !isset($data['quantity']) ||
                $data['quantity'] === '' ||
                (isset($item['quantity'], $data['quantity']) &&
                    $item['quantity'] === $data['quantity']);

            $matchMovementId = !isset($data['movementId']) ||
                $data['movementId'] === '' ||
                (isset($item['movementId'], $data['movementId']) &&
                    $item['movementId'] === $data['movementId']);

            $matchMovementDate = !isset($data['movementDate']) ||
                $data['movementDate'] === '' ||
                (isset($item['movementDate'], $data['movementDate']) &&
                    $item['movementDate'] === $data['movementDate']);

            $matchCustomer = !isset($data['customer']) ||
                $data['customer'] === '' ||
                (isset($item['customer'], $data['customer']) &&
                    $item['customer'] === $data['customer']);

            $matchSupplier = !isset($data['supplier']) ||
                $data['supplier'] === '' ||
                (isset($item['supplier'], $data['supplier']) &&
                    $item['supplier'] === $data['supplier']);

            return $matchProductCode && $matchMovementId && $matchFromBatchNumber && $matchToBatchNumber && $matchFromLocation
                && $matchToLocation &&  $matchQuantity &&  $matchMovementDate &&  $matchCustomer && $matchSupplier;
        });

        if (empty($filteredData)) {
            return;
        }

        // Convierto el resultado filtrado a JSON
        $result = json_encode(array_values($filteredData), JSON_PRETTY_PRINT);

        // Muestro el resultado
        return $result;
    }

    public static function sale($newData)
    {
        $movementDataArray = self::datosJsonParseados();
        $productsDataArray = Product::datosJsonParseados();

        // Convierto array asociativo a array de objetos Product
        $productObjectsArray = Product::arrayToObjectsArray($productsDataArray);

        $arrayErrores = validacionesDeSale($newData); // Valido los campos obligatorios

        if (!existsObjectId($productsDataArray, $newData['productCode'], 'productCode')) { // Verifico si el producto está registrado en product.json
            $arrayErrores["productCode"] = 'El producto no está registrado';
        }
        if (count($arrayErrores) > 0) {

            print_r($arrayErrores);
        } else {
            // Verifico que exista stock exactamente en la ubicación que se indica
            foreach ($productObjectsArray as $product) {
                if ($product->getProductCode() === $newData['productCode']) {
                    if (
                        $product->getBatchNumber() !== $newData['fromBatchNumber']
                        || $product->getLocation() !== $newData['fromLocation']
                    ) {
                        $arrayErrores['error'] = "No hay stock de este producto en la ubicación y lote indicados";
                        print_r($arrayErrores);
                        break;
                    }
                }

                if (
                    $product->getProductCode() === $newData['productCode']
                    && $product->getBatchNumber() === $newData['fromBatchNumber']
                    && $product->getLocation() === $newData['fromLocation']
                ) {
                    // Valido si hay suficiente cantidad
                    if ($product->getQuantity() < $newData['quantity']) {

                        $arrayErrores['quantity'] = 'No hay stock suficiente para esta venta';
                        print_r($arrayErrores);
                        break; // Salgo del bucle si hay un error

                    } else {
                        // Resto la cantidad solicitada a ese elemento
                        $newQuantity = $product->getQuantity() - $newData['quantity'];
                        $product->setQuantity($newQuantity);

                        // Convierto array de objetos a array asociativo 
                        $productsDataArrayUpdated = Product::objectsArrayToArray($productObjectsArray);

                        $newId = nextId($movementDataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

                        // Creo un objeto de la clase y asigno los datos con setters
                        $newElement = new self($newId, '', '', '', '', '', '', 0, '', '', '', ''); // Inicializo el objeto con el nuevo ID
                        $newElement->setProductCode($newData['productCode']);
                        $newElement->setProductName($newData['productName']);
                        $newElement->setFromBatchNumber($newData['fromBatchNumber']);
                        $newElement->setToBatchNumber($newData['toBatchNumber']);
                        $newElement->setFromLocation($newData['fromLocation']);
                        $newElement->setToLocation($newData['toLocation']);
                        $newElement->setQuantity($newData['quantity']);
                        $newElement->setMovementId($newData['movementId']);
                        $newElement->setMovementDate($newData['movementDate']);
                        $newElement->setCustomer($newData['customer']);
                        $newElement->setSupplier($newData['supplier']);

                        // Convierto el objeto de la clase a un array para guardarlo en el JSON
                        $movementDataArray[] = [
                            'id' => $newElement->getId(),
                            'productCode' => $newElement->getProductCode(),
                            'productName' => $newElement->getProductName(),
                            'fromBatchNumber' => $newElement->getFromBatchNumber(),
                            'toBatchNumber' => $newElement->getToBatchNumber(),
                            'fromLocation' => $newElement->getFromLocation(),
                            'toLocation' => $newElement->getToLocation(),
                            'quantity' => $newElement->getQuantity(),
                            'movementId' => $newElement->getMovementId(),
                            'movementDate' => $newElement->getMovementDate(),
                            'customer' => $newElement->getCustomer(),
                            'supplier' => $newElement->getSupplier(),
                        ];

                        // Llamo a la función
                        saveData($movementDataArray, $productsDataArrayUpdated);
                        return true;
                    }
                }
            }
        }
    }

    public static function purchase($newData)
    {
        $movementDataArray = self::datosJsonParseados();
        $productsDataArray = Product::datosJsonParseados();

        // Convierto array asociativo a array de objetos Product
        $productObjectsArray = Product::arrayToObjectsArray($productsDataArray);

        $arrayErrores = validacionesDePurchase($newData); // Valido los campos obligatorios

        if (!existsObjectId($productsDataArray, $newData['productCode'], 'productCode')) { // Verifico si el producto está registrado en product.json
            $arrayErrores["productCode"] = 'El producto no está registrado';
        }
        if (count($arrayErrores) > 0) {
            print_r($arrayErrores);
        } else {

            $newId = nextId($movementDataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

            // Creo un objeto de la clase y asigno los datos con setters
            $newElement = new self($newId, '', '', '', '', '', '', 0, '', '', '', ''); // Inicializo el objeto con el nuevo ID
            $newElement->setProductCode($newData['productCode']);
            $newElement->setProductName($newData['productName']);
            $newElement->setFromBatchNumber($newData['fromBatchNumber']);
            $newElement->setToBatchNumber($newData['toBatchNumber']);
            $newElement->setFromLocation($newData['fromLocation']);
            $newElement->setToLocation($newData['toLocation']);
            $newElement->setQuantity($newData['quantity']);
            $newElement->setMovementId($newData['movementId']);
            $newElement->setMovementDate($newData['movementDate']);
            $newElement->setCustomer($newData['customer']);
            $newElement->setSupplier($newData['supplier']);

            // Convierto el objeto de la clase a un array para guardarlo en el JSON
            $movementDataArray[] = [
                'id' => $newElement->getId(),
                'productCode' => $newElement->getProductCode(),
                'productName' => $newElement->getProductName(),
                'fromBatchNumber' => $newElement->getFromBatchNumber(),
                'toBatchNumber' => $newElement->getToBatchNumber(),
                'fromLocation' => $newElement->getFromLocation(),
                'toLocation' => $newElement->getToLocation(),
                'quantity' => $newElement->getQuantity(),
                'movementId' => $newElement->getMovementId(),
                'movementDate' => $newElement->getMovementDate(),
                'customer' => $newElement->getCustomer(),
                'supplier' => $newElement->getSupplier(),
            ];
        }

        // Verifico que no exista stock exactamente en la ubicación que se indica para crear una nueva línea para ese producto
        foreach ($productObjectsArray as $product) {
            if ($product->getProductCode() === $newData['productCode']) {
                if (
                    $product->getBatchNumber() !== $newData['toBatchNumber']
                    || $product->getLocation() !== $newData['toLocation']
                ) {

                    $newId = nextId($productsDataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

                    // Creo un objeto de la clase y asigno los datos con setters
                    $newElement = new Product($newId, '', '', '', '', 0, ''); // Inicializo el objeto con el nuevo ID
                    $newElement->setProductCode($newData['productCode']);
                    $newElement->setProductName($newData['productName']);
                    $newElement->setBatchNumber($newData['toBatchNumber']);
                    $newElement->setLocation($newData['toLocation']);
                    $newElement->setQuantity($newData['quantity']);
                    $newElement->setCategory("");

                    $productObjectsArray[] = $newElement;
                }

                if (
                    $product->getProductCode() === $newData['productCode']
                    && $product->getBatchNumber() === $newData['toBatchNumber']
                    && $product->getLocation() === $newData['toLocation']
                ) {
                    // Sumo la cantidad que entra en caso que exista stock de el mismo código, lote y ubicación
                    $newQuantity = $product->getQuantity() + $newData['quantity'];
                    $product->setQuantity($newQuantity);

                    // Convierto array de objetos a array asociativo 
                    $productsDataArrayUpdated = Product::objectsArrayToArray($productObjectsArray);
                }
                // Convierto array de objetos a array asociativo 
                $productsDataArrayUpdated = Product::objectsArrayToArray($productObjectsArray);
                // Llamo a la función
                saveData($movementDataArray, $productsDataArrayUpdated);
                return true;
            }
        }
    }

    public static function inventoryTransfer($newData)
    {
        $movementDataArray = self::datosJsonParseados();
        $productsDataArray = Product::datosJsonParseados();

        // Convierto array asociativo a array de objetos Product
        $productObjectsArray = Product::arrayToObjectsArray($productsDataArray);

        $arrayErrores = validacionesDeInventoryTransfer($newData); // Valido los campos obligatorios

        if (!existsObjectId($productsDataArray, $newData['productCode'], 'productCode')) { // Verifico si el producto está registrado en product.json
            $arrayErrores["productCode"] = 'El producto no está registrado';
        }
        if (count($arrayErrores) > 0) {

            print_r($arrayErrores);
        } else {
            // Verifico que exista stock exactamente en la ubicación que se indica para descontar
            foreach ($productObjectsArray as $product) {
                if ($product->getProductCode() === $newData['productCode']) {
                    if (
                        $product->getBatchNumber() !== $newData['fromBatchNumber']
                        || $product->getLocation() !== $newData['fromLocation']
                    ) {
                        $arrayErrores['error'] = "No hay stock de este producto en la ubicación y lote indicados";
                        print_r($arrayErrores);
                        break;
                    }
                }
                if (
                    $product->getProductCode() === $newData['productCode']
                    && $product->getBatchNumber() === $newData['fromBatchNumber']
                    && $product->getLocation() === $newData['fromLocation']
                ) {
                    // Valido si hay suficiente cantidad
                    if ($product->getQuantity() < $newData['quantity']) {

                        $arrayErrores['quantity'] = 'No hay stock suficiente para esta transferencia';
                        print_r($arrayErrores);
                        break;
                    } else {
                        // Resto la cantidad solicitada a ese elemento
                        $newQuantity = $product->getQuantity() - $newData['quantity'];
                        $product->setQuantity($newQuantity);

                        $newId = nextId($movementDataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

                        // Creo un objeto de la clase y asigno los datos con setters
                        $newElement = new self($newId, '', '', '', '', '', '', 0, '', '', '', ''); // Inicializo el objeto con el nuevo ID
                        $newElement->setProductCode($newData['productCode']);
                        $newElement->setProductName($newData['productName']);
                        $newElement->setFromBatchNumber($newData['fromBatchNumber']);
                        $newElement->setToBatchNumber($newData['toBatchNumber']);
                        $newElement->setFromLocation($newData['fromLocation']);
                        $newElement->setToLocation($newData['toLocation']);
                        $newElement->setQuantity($newData['quantity']);
                        $newElement->setMovementId($newData['movementId']);
                        $newElement->setMovementDate($newData['movementDate']);
                        $newElement->setCustomer($newData['customer']);
                        $newElement->setSupplier($newData['supplier']);

                        // Convierto el objeto de la clase a un array para guardarlo en el JSON
                        $movementDataArray[] = [
                            'id' => $newElement->getId(),
                            'productCode' => $newElement->getProductCode(),
                            'productName' => $newElement->getProductName(),
                            'fromBatchNumber' => $newElement->getFromBatchNumber(),
                            'toBatchNumber' => $newElement->getToBatchNumber(),
                            'fromLocation' => $newElement->getFromLocation(),
                            'toLocation' => $newElement->getToLocation(),
                            'quantity' => $newElement->getQuantity(),
                            'movementId' => $newElement->getMovementId(),
                            'movementDate' => $newElement->getMovementDate(),
                            'customer' => $newElement->getCustomer(),
                            'supplier' => $newElement->getSupplier(),
                        ];
                    }

                    // Verifico que no exista stock exactamente en la ubicación que se indica para crear una nueva línea para ese producto
                    foreach ($productObjectsArray as $product) {
                        if ($product->getProductCode() === $newData['productCode']) {
                            if (
                                $product->getBatchNumber() !== $newData['toBatchNumber']
                                || $product->getLocation() !== $newData['toLocation']
                            ) {

                                $newId = nextId($productsDataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

                                // Creo un objeto de la clase y asigno los datos con setters
                                $newElement = new Product($newId, '', '', '', '', 0, ''); // Inicializo el objeto con el nuevo ID
                                $newElement->setProductCode($newData['productCode']);
                                $newElement->setProductName($newData['productName']);
                                $newElement->setBatchNumber($newData['toBatchNumber']);
                                $newElement->setLocation($newData['toLocation']);
                                $newElement->setQuantity($newData['quantity']);
                                $newElement->setCategory("");

                                $productObjectsArray[] = $newElement;
                            }

                            if (
                                $product->getProductCode() === $newData['productCode']
                                && $product->getBatchNumber() === $newData['toBatchNumber']
                                && $product->getLocation() === $newData['toLocation']
                            ) {
                                // Sumo la cantidad que entra en caso que exista stock de el mismo código, lote y ubicación
                                $newQuantity = $product->getQuantity() + $newData['quantity'];
                                $product->setQuantity($newQuantity);
                            }
                        }
                    }
                }
            }
            // Convierto array de objetos a array asociativo 
            $productsDataArrayUpdated = Product::objectsArrayToArray($productObjectsArray);

            // Llamo a la función
            saveData($movementDataArray, $productsDataArrayUpdated);
            return true;
        }
    }
}

/*********** Funciones necesarias ***********/
function validacionesDeSale($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    $arrayErrores = array();
    if (empty($data["productCode"])) {
        $arrayErrores["productCode"] = 'El Código del producto es obligatorio';
    }
    if (empty($data["productName"])) {
        $arrayErrores["productName"] = 'El nombre del producto es obligatorio';
    }
    if (empty($data["fromBatchNumber"])) {
        $arrayErrores["fromBatchNumber"] = 'El lote de origen es obligatorio';
    }
    if (empty($data["fromLocation"])) {
        $arrayErrores["fromLocation"] = 'La ubicación de origen es obligatoria';
    }
    if (empty($data["quantity"])) {
        $arrayErrores["quantity"] = 'La cantidad es obligatoria';
    }
    if (empty($data["movementId"])) {
        $arrayErrores["movementId"] = 'El tipo de movimiento es obligatorio';
    }
    if (empty($data["movementDate"])) {
        $arrayErrores["movementDate"] = 'La fecha del movimiento es obligatoria';
    }
    if (empty($data["customer"])) {
        $arrayErrores["customer"] = 'El cliente es obligatorio';
    }
    return $arrayErrores;
}

function validacionesDePurchase($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    $arrayErrores = array();
    if (empty($data["productCode"])) {
        $arrayErrores["productCode"] = 'El Código del producto es obligatorio';
    }
    if (empty($data["productName"])) {
        $arrayErrores["productName"] = 'El nombre del producto es obligatorio';
    }
    if (empty($data["toBatchNumber"])) {
        $arrayErrores["toBatchNumber"] = 'El lote de destino es obligatorio';
    }
    if (empty($data["toLocation"])) {
        $arrayErrores["toLocation"] = 'La ubicación de destino es obligatoria';
    }
    if (empty($data["quantity"])) {
        $arrayErrores["quantity"] = 'La cantidad es obligatoria';
    }
    if (empty($data["movementId"])) {
        $arrayErrores["movementId"] = 'El tipo de movimiento es obligatorio';
    }
    if (empty($data["movementDate"])) {
        $arrayErrores["movementDate"] = 'La fecha del movimiento es obligatoria';
    }
    if (empty($data["supplier"])) {
        $arrayErrores["supplier"] = 'El proveedor es obligatorio';
    }
    return $arrayErrores;
}

function validacionesDeInventoryTransfer($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    $arrayErrores = array();
    if (empty($data["productCode"])) {
        $arrayErrores["productCode"] = 'El Código del producto es obligatorio';
    }
    if (empty($data["productName"])) {
        $arrayErrores["productName"] = 'El nombre del producto es obligatorio';
    }
    if (empty($data["fromBatchNumber"])) {
        $arrayErrores["fromBatchNumber"] = 'El lote de origen es obligatorio';
    }
    if (empty($data["toBatchNumber"])) {
        $arrayErrores["toBatchNumber"] = 'El lote de destino es obligatorio';
    }
    if (empty($data["fromLocation"])) {
        $arrayErrores["fromLocation"] = 'La ubicación de origen es obligatoria';
    }
    if (empty($data["toLocation"])) {
        $arrayErrores["toLocation"] = 'La ubicación de destino es obligatoria';
    }
    if (empty($data["quantity"])) {
        $arrayErrores["quantity"] = 'La cantidad es obligatoria';
    }
    if (empty($data["movementId"])) {
        $arrayErrores["movementId"] = 'El tipo de movimiento es obligatorio';
    }
    if (empty($data["movementDate"])) {
        $arrayErrores["movementDate"] = 'La fecha del movimiento es obligatoria';
    }

    return $arrayErrores;
}

// Con la función saveData actualizo los ficheros movement.json y product.json cada vez que se realizan cambios
function saveData($movementDataArray, $productsDataArray)
{
    // Guardar en el JSON de movimientos
    $newJsonDataMovements = json_encode($movementDataArray, JSON_PRETTY_PRINT);
    $resultMovements = file_put_contents(Movement::getFilePath(), $newJsonDataMovements) !== false;

    // Guardar en el JSON de producto
    $newJsonDataProduct = json_encode($productsDataArray, JSON_PRETTY_PRINT);
    $resultProduct = file_put_contents(Product::getFilePath(), $newJsonDataProduct) !== false;


    // Devolver resultados de ambas operaciones
    return [
        'movementsSaved' => $resultMovements,
        'productSaved' => $resultProduct
    ];
}
