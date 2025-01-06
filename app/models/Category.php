<?php

/** Definición del modelo con su tipo de dato e importo el fichero JSON
 **/
class Category
{
    private int $id;
    private string $categoryId;
    private string $categoryName;

    // Constructor para inicializar propiedades

    public function __construct(int $id, string $categoryId, string $categoryName)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getCategoryId()
    {
        return $this->categoryId;
    }
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
    }
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }
    private static function getFilePath()
    { // Por visualización he creado esta función decoficiando el JSON y poder usarlo en las otras funciones
        return __DIR__ . '/../models/data/category.json'; // Ruta del archivo JSON
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

    public static function create($newData)
    {
        $dataArray = self::datosJsonParseados();

        $arrayErrores = validacionesDeCategoria($newData);

        if (existsObjectId($dataArray, $newData['categoryId'], 'categoryId')) {
            $arrayErrores['categoryId'] = 'El ID de esta categoría ya está registrado';
        }
        if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
            print_r($arrayErrores);
        } else {
            $newId = nextId($dataArray); // Llamo a la función nextId para asignarle un id correcto al nuevo objeto

            // Creo un objeto de la clase y asigno los datos con setters
            $newElement = new self($newId, '', '', '', '', ''); // Inicializo el objeto con el nuevo ID
            $newElement->setCategoryId($newData['categoryId']);
            $newElement->setCategoryName($newData['categoryName']);

            // Convierto el objeto de la clase a un array para guardarlo en el JSON
            $dataArray[] = [
                'id' => $newElement->getId(),
                'categoryId' => $newElement->getCategoryId(),
                'categoryName' => $newElement->getCategoryName(),
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
        foreach ($dataArray as &$data) { // Uso la referencia, para que los cambios que realizo 
            //en el array dentro del bucle se apliquen al array original.

            if ($data['id'] === $id) {
                $arrayErrores = validacionesDeCategoria($newData);

                if (existeIdExcluyendo($dataArray, $newData['categoryId'], $id, 'categoryId')) { // Evito que se duplique el Id de la clase
                    $arrayErrores["categoryId"] = 'El ID ya está registrado';
                }
                if (count($arrayErrores) > 0) { // Si el array de errores es mayor que 0, entonces  creo un array asociativo que mostrará la respuesta
                    print_r($arrayErrores);
                    break;
                }

                // Creo un objeto con los datos actuales
                $element = new self(
                    $data['id'],
                    $data['categoryId'],
                    $data['categoryName'],
                );

                // Uso los setters para actualizar los datos
                if (isset($newData['categoryId'])) {
                    $element->setCategoryId($newData['categoryId']);
                }
                if (isset($newData['categoryName'])) {
                    $element->setCategoryName($newData['categoryName']);
                }

                // Actualizo los datos en el array
                $data = [
                    'id' => $element->getId(),
                    'categoryId' => $element->getCategoryId(),
                    'categoryName' => $element->getCategoryName(),
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
            echo "No se ha encontrado la categoría con id: " . $id . "\n";
            return false;
        } else {
            unset($dataArray[$id]);
            $json = json_encode(array_values($dataArray), JSON_PRETTY_PRINT);
            file_put_contents(self::getFilePath(), $json);
            return true;
        };
    }
}

/*********** Funciones necesarias ***********/

function validacionesDeCategoria($data)
{
    // Valido los datos insertados en body (formulario) y voy completando el array $arrayErrores con los errores que aparezcan
    $arrayErrores = array();
    if (empty($data["categoryId"])) {
        $arrayErrores["categoryId"] = 'El ID de categoría es obligatoria';
    }
    if (empty($data["categoryName"])) {
        $arrayErrores["categoryName"] = 'El nombre de la categoría es obligatoria';
    }
    return $arrayErrores;
}
