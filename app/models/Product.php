<?php

/** Definición del modelo con su tipo de dato e importo el fichero JSON
 **/
class Product
{
    private int $id;
    private string $productCode;
    private string $productNAme;
    private string $batchNumber;
    private string $location;
    private int $quantity;
    private string $category;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $productCode, string $productNAme, string $batchNumber, string $location, int $quantity, string $category)
    {
        $this->id = $id;
        $this->productCode = $productCode;
        $this->productNAme = $productNAme;
        $this->batchNumber = $batchNumber;
        $this->location = $location;
        $this->quantity = $quantity;
        $this->category = $category;
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
    public function getBatchNumber()
    {
        return $this->batchNumber;
    }
    public function getLocation()
    {
        return $this->location;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getCategory()
    {
        return $this->category;
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
    public function setBatchNumber($batchNumber)
    {
        $this->batchNumber = $batchNumber;
    }
    public function setLocation($location)
    {
        $this->location = $location;
    }
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
    public function setCategory($category)
    {
        $this->category = $category;
    }

    public static function getFilePath()
    { // Por visualización he creado esta función decodificando el JSON y poder usarlo en las otras funciones
        return __DIR__ . '/../../data/product.json'; // Ruta del archivo JSON
    }

    public static function datosJsonParseados() // Esta función es pública pues necesitaré estos datos en la clase Movement
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

    public static function create($newData)
    {
        $dataArray = self::datosJsonParseados();

        $arrayErrores = validacionesDeProduct($newData);

        if (existsObjectid($dataArray, $newData['productCode'], 'productCode')) {
            $arrayErrores["productCode"] = 'El código de producto ya está registrado';
        }
        if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
            print_r($arrayErrores);
        } else {
            $newId = nextId($dataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

            // Creo un objeto de la clase y asigno los datos con setters
            $newElement = new self($newId, '', '', '', '', 0, ''); // Inicializo el objeto con el nuevo ID
            $newElement->setProductCode($newData['productCode']);
            $newElement->setProductName($newData['productName']);
            $newElement->setBatchNumber($newData['batchNumber']);
            $newElement->setLocation($newData['location']);
            $newElement->setQuantity($newData['quantity']);
            $newElement->setCategory($newData['category']);

            // Convierto el objeto de la clase a un array para guardarlo en el JSON
            $dataArray[] = [
                'id' => $newElement->getId(),
                'productCode' => $newElement->getProductCode(),
                'productName' => $newElement->getProductName(),
                'batchNumber' => $newElement->getBatchNumber(),
                'location' => $newElement->getLocation(),
                'quantity' => $newElement->getQuantity(),
                'category' => $newElement->getCategory(),
            ];
            // Guardo en el JSON
            $newJsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
            return file_put_contents(self::getFilePath(), $newJsonData) !== false;
        }
    }

    public static function update($id, $newData)
    {
        $dataArray = self::datosJsonParseados();

        // Busco por ID
        $elementConfirmed = false;
        foreach ($dataArray as &$data) { // Uso la referencia "&", para que los cambios que realizo 
            //en el array dentro del bucle se apliquen al array original.

            if ($data['id'] === $id) {
                $arrayErrores = validacionesDeProduct($newData);

                if (existeIdExcluyendo($dataArray, $newData['productCode'], $id, 'productCode')) { // Evito que se duplique el Id de la clase
                    $arrayErrores["productCode"] = 'El código de producto ya está registrado';
                }
                if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
                    print_r($arrayErrores);
                    break;
                }

                // Creo un objeto con los datos actuales
                $element = new self(
                    $data['id'],
                    $data['productCode'],
                    $data['productName'],
                    $data['batchNumber'],
                    $data['location'],
                    $data['quantity'],
                    $data['category'],
                );

                // Uso los setters para actualizar los datos
                if (isset($newData['productCode'])) {
                    $element->setProductCode($newData['productCode']);
                }
                if (isset($newData['productName'])) {
                    $element->setProductName($newData['productName']);
                }
                if (isset($newData['batchNumber'])) {
                    $element->setBatchNumber($newData['batchNumber']);
                }
                if (isset($newData['location'])) {
                    $element->setLocation($newData['location']);
                }
                if (isset($newData['quantity'])) {
                    $element->setQuantity($newData['quantity']);
                }
                if (isset($newData['category'])) {
                    $element->setCategory($newData['category']);
                }

                // Actualizo los datos en el array
                $data = [
                    'id' => $element->getId(),
                    'productCode' => $element->getProductCode(),
                    'productName' => $element->getProductName(),
                    'batchNumber' => $element->getBatchNumber(),
                    'location' => $element->getLocation(),
                    'quantity' => $element->getQuantity(),
                    'category' => $element->getCategory(),
                ];
                $elementConfirmed  = true;
                unset($data);
            }
        }
        if (!$elementConfirmed) {
            return false;
        }

        $newJsonData = json_encode($dataArray, JSON_PRETTY_PRINT);
        return file_put_contents(self::getFilePath(), $newJsonData) !== false;
    }

    public static function delete($id)
    {
        $dataArray = self::datosJsonParseados();

        // Busco por ID
        $result = getElementById($dataArray, $id);
        if (!$result) {
            echo "No se ha encontrado el producto con id: " . $id . "\n";
            return false;
        } else {
            unset($dataArray[$id]);
            $json = json_encode(array_values($dataArray), JSON_PRETTY_PRINT);
            file_put_contents(self::getFilePath(), $json);
            return true;
        };
    }


    // Convierto array de objetos a array asociativo de producto
    public static function objectsArrayToArray($objectsArray)
    {
        foreach ($objectsArray as $product) {
            $productsDataArrayUpdated[] = [
                'id' => $product->getId(),
                'productCode' => $product->getProductCode(),
                'productName' => $product->getProductName(),
                'batchNumber' => $product->getBatchNumber(),
                'location' => $product->getLocation(),
                'quantity' => $product->getQuantity(),
                'category' => $product->getCategory()
            ];
        }
        return $productsDataArrayUpdated;
    }

    // Convierto array asociativo a array de objetos Product
    public static function arrayToObjectsArray($productsDataArray)
    {
        foreach ($productsDataArray as $productData) {
            $productObjectsArray[] = new Product(
                $productData['id'],
                $productData['productCode'],
                $productData['productName'],
                $productData['batchNumber'],
                $productData['location'],
                $productData['quantity'],
                $productData['category']
            );
        }
        return $productObjectsArray;
    }
}

/*********** Funciones necesarias ***********/

function validacionesDeProduct($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    // En el caso de producto, batchNumber, location y quantity pueden estar vacíos; especialmente cuando se trata de un producto nuevo
    $arrayErrores = array();
    if (empty($data["productCode"])) {
        $arrayErrores["productCode"] = 'El Código del producto es obligatorio';
    }
    if (empty($data["productName"])) {
        $arrayErrores["productName"] = 'El nombre del producto es obligatorio';
    }
    if (empty($data["category"])) {
        $arrayErrores["category"] = 'La categoría del producto es obligatoria';
    }
    return $arrayErrores;
}
